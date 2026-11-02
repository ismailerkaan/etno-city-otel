<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Services\Hms\HmsClient;
use App\Services\Hms\HmsException;
use App\Services\Hms\HmsOffer;
use App\Services\Hms\HmsReservationSync;
use Carbon\CarbonImmutable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ReservationController extends Controller
{
    public function index(Request $request): View
    {
        $status = $request->string('status')->toString();

        $reservations = Reservation::query()
            ->with('roomType:id,slug,name')
            ->when($status === 'hms_failed', fn ($query) => $query->hmsSyncFailed())
            ->when($status && $status !== 'hms_failed', fn ($query) => $query->where('status', $status))
            ->latest()
            ->orderByDesc('id')
            ->paginate(20)
            ->withQueryString();

        return view('admin.reservations.index', compact('reservations', 'status'));
    }

    public function show(Reservation $reservation): View
    {
        return view('admin.reservations.show', [
            'reservation' => $reservation->load('roomType.images'),
            // Guests who saw an error often book again; surface those before re-sending to HMS
            'possibleDuplicates' => $reservation->hasHmsSyncProblem() && $reservation->hms_booking_id === null
                ? Reservation::query()
                    ->whereKeyNot($reservation->getKey())
                    ->where('email', $reservation->email)
                    ->where('created_at', '>=', $reservation->created_at)
                    ->where('status', '!=', Reservation::STATUS_CANCELLED)
                    ->get()
                : collect(),
        ]);
    }

    public function update(Request $request, Reservation $reservation): RedirectResponse
    {
        // HMS reservations change status only through the actions that keep HMS in sync
        if ($reservation->isHmsReservation()) {
            return back()->withErrors(['hms' => 'HMS rezervasyonlarının durumu "İptal Et" ve "HMS\'e Tekrar Gönder" ile değiştirilir.']);
        }

        $validated = $request->validate([
            'status' => ['required', Rule::in([
                Reservation::STATUS_PAYMENT_PENDING,
                Reservation::STATUS_CONFIRMED,
                Reservation::STATUS_CANCELLED,
            ])],
        ]);

        $reservation->update($validated);

        return back()->with('success', 'Rezervasyon durumu güncellendi.');
    }

    public function cancel(Reservation $reservation, HmsReservationSync $sync): RedirectResponse
    {
        if ($reservation->status === Reservation::STATUS_CANCELLED) {
            return back();
        }

        // Never reached HMS: nothing to cancel there
        if (! $reservation->isHmsReservation() || $reservation->hms_booking_id === null) {
            $reservation->update(['status' => Reservation::STATUS_CANCELLED, 'hms_sync_error' => null]);

            return back()->with('success', 'Rezervasyon iptal edildi.');
        }

        try {
            $sync->cancel($reservation);
        } catch (HmsException $exception) {
            return back()->withErrors(['hms' => 'İptal HMS\'e gönderilemedi, rezervasyon iptal edilmedi: '.$exception->getMessage()]);
        }

        return back()->with('success', 'Rezervasyon iptal edildi ve HMS\'e bildirildi.');
    }

    public function resendToHms(Reservation $reservation, HmsReservationSync $sync): RedirectResponse
    {
        abort_unless($reservation->hasHmsSyncProblem(), 404);

        try {
            $reservation->hms_booking_id === null ? $sync->book($reservation) : $sync->modify($reservation);
        } catch (HmsException $exception) {
            return back()->withErrors(['hms' => 'HMS yine kabul etmedi: '.$exception->getMessage()]);
        }

        return back()->with('success', 'Rezervasyon HMS\'e aktarıldı.');
    }

    public function edit(Reservation $reservation): View
    {
        abort_unless($this->isEditable($reservation), 404);

        return view('admin.reservations.edit', ['reservation' => $reservation]);
    }

    public function updateStay(Request $request, Reservation $reservation, HmsClient $hms, HmsReservationSync $sync): RedirectResponse
    {
        abort_unless($this->isEditable($reservation), 404);

        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email:rfc', 'max:255'],
            'phone' => ['required', 'string', 'max:50'],
            'special_requests' => ['nullable', 'string', 'max:2000'],
            'check_in' => ['required', Rule::date()->format('Y-m-d')],
            'check_out' => ['required', Rule::date()->format('Y-m-d')->after('check_in')],
            'adults' => ['required', 'integer', 'min:1', 'max:20'],
            'child_ages' => ['nullable', 'array', 'max:10'],
            'child_ages.*' => ['integer', 'between:0,16'],
        ]);

        $checkIn = CarbonImmutable::createFromFormat('Y-m-d', $validated['check_in'])->startOfDay();
        $checkOut = CarbonImmutable::createFromFormat('Y-m-d', $validated['check_out'])->startOfDay();
        $childAges = array_map('intval', $validated['child_ages'] ?? []);

        $reservation->fill([
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'special_requests' => $validated['special_requests'] ?? null,
        ]);

        $stayChanged = ! $reservation->check_in->isSameDay($checkIn)
            || ! $reservation->check_out->isSameDay($checkOut)
            || $reservation->adults !== (int) $validated['adults']
            || ($reservation->child_ages ?? []) !== $childAges;

        if ($stayChanged) {
            try {
                // Same board type and refund policy, re-priced for the new stay
                $offer = collect($hms->offers($reservation->hms_room_type_id, $checkIn, $checkOut, (int) $validated['adults'], $childAges, fresh: true))
                    ->first(fn (HmsOffer $offer): bool => $offer->ratePlanId === $reservation->hms_rate_plan_id && $offer->nonRefundable === $reservation->non_refundable);
            } catch (HmsException $exception) {
                return back()->withInput()->withErrors(['hms' => 'HMS\'ten güncel fiyat alınamadı: '.$exception->getMessage()]);
            }

            if (! $offer) {
                return back()->withInput()->withErrors(['check_in' => 'Bu tarih ve kişi sayısı için aynı pansiyon tipinde ('.$reservation->board_name.') müsaitlik bulunamadı.']);
            }

            $nights = $checkIn->diffInDays($checkOut);
            $reservation->fill([
                'check_in' => $checkIn,
                'check_out' => $checkOut,
                'nights' => $nights,
                'adults' => (int) $validated['adults'],
                'children' => count($childAges),
                'child_ages' => $childAges,
                'hms_price_key' => $offer->priceKey,
                'daily_rates' => $offer->dailyRates,
                'total_price' => $offer->total,
                'nightly_price' => number_format((float) $offer->total / max(1, $nights), 2, '.', ''),
                'currency' => $offer->currency,
            ]);
        }

        if (! $reservation->isDirty()) {
            return redirect()->route('admin.reservations.show', $reservation)->with('success', 'Değişiklik yapılmadı.');
        }

        try {
            $sync->modify($reservation);
        } catch (HmsException $exception) {
            return back()->withInput()->withErrors(['hms' => 'Değişiklik HMS\'e gönderilemedi, rezervasyon güncellenmedi: '.$exception->getMessage()]);
        }

        return redirect()->route('admin.reservations.show', $reservation)->with('success', 'Rezervasyon güncellendi ve HMS\'e bildirildi.');
    }

    private function isEditable(Reservation $reservation): bool
    {
        return $reservation->isHmsReservation()
            && $reservation->status === Reservation::STATUS_CONFIRMED
            && $reservation->hms_booking_id !== null;
    }
}
