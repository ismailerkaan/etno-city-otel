<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProcessReservationPaymentRequest;
use App\Http\Requests\StoreReservationRequest;
use App\Models\Reservation;
use App\Models\ReservationGuarantee;
use App\Models\RoomType;
use App\Models\SiteSetting;
use Carbon\CarbonImmutable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ReservationController extends Controller
{
    private const SIMULATED_DECLINED_CARD = '4000000000000002';

    public function rooms(): View
    {
        return view('reservation.step1', [
            'roomTypes' => RoomType::query()->active()->with('images')->orderBy('sort_order')->orderBy('id')->get(),
            'guarantees' => ReservationGuarantee::getActiveGuarantees(),
            'siteSetting' => SiteSetting::query()->first(),
            ...$this->bookingParameters(),
        ]);
    }

    public function room(RoomType $roomType): View
    {
        abort_unless($roomType->is_active, 404);

        return view('reservation.step2', [
            'roomType' => $roomType->load('images'),
            'similarRoomTypes' => RoomType::query()
                ->active()
                ->whereKeyNot($roomType->getKey())
                ->with('images')
                ->orderByRaw('ABS(nightly_price - ?)', [$roomType->nightly_price])
                ->orderBy('sort_order')
                ->limit(3)
                ->get(),
            'siteSetting' => SiteSetting::query()->first(),
            ...$this->bookingParameters(),
        ]);
    }

    public function guestDetails(RoomType $roomType): View
    {
        abort_unless($roomType->is_active, 404);

        return view('reservation.step4', [
            'roomType' => $roomType->load('images'),
            'siteSetting' => SiteSetting::query()->first(),
            ...$this->bookingParameters(),
        ]);
    }

    public function store(StoreReservationRequest $request, RoomType $roomType): RedirectResponse
    {
        $validated = $request->validated();
        $checkIn = CarbonImmutable::createFromFormat('Y-m-d', $validated['check_in'])->startOfDay();
        $checkOut = CarbonImmutable::createFromFormat('Y-m-d', $validated['check_out'])->startOfDay();
        $nights = $checkIn->diffInDays($checkOut);

        $reservation = DB::transaction(function () use ($roomType, $validated, $checkIn, $checkOut, $nights): Reservation {
            $reservation = Reservation::query()->create([
                'reference' => Str::ulid()->toBase32(),
                'room_type_id' => $roomType->id,
                'status' => Reservation::STATUS_PAYMENT_PENDING,
                'check_in' => $checkIn,
                'check_out' => $checkOut,
                'nights' => $nights,
                'adults' => $validated['adults'],
                'children' => $validated['children'],
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'special_requests' => $validated['special_requests'] ?? null,
                'room_name' => $roomType->name,
                'nightly_price' => $roomType->nightly_price,
                'total_price' => number_format((float) $roomType->nightly_price * $nights, 2, '.', ''),
                'currency' => $roomType->currency,
            ]);

            $reservation->update([
                'reference' => Reservation::referenceForId($reservation->getKey()),
            ]);

            return $reservation;
        });

        return redirect()->route('reservation.payment', $reservation);
    }

    public function payment(Reservation $reservation): View|RedirectResponse
    {
        if ($reservation->status === Reservation::STATUS_CONFIRMED) {
            return redirect()->route('reservation.confirmation', $reservation);
        }

        return view('reservation.step5', [
            'reservation' => $reservation->load('roomType.images'),
            'siteSetting' => SiteSetting::query()->first(),
        ]);
    }

    public function processPayment(ProcessReservationPaymentRequest $request, Reservation $reservation): RedirectResponse
    {
        $validated = $request->validated();

        if ($validated['card_number'] === self::SIMULATED_DECLINED_CARD) {
            return redirect()
                ->route('reservation.payment', $reservation)
                ->withErrors(['payment' => 'Ödeme bankanız tarafından reddedildi. Kart bilgilerinizi kontrol edin veya farklı bir kartla tekrar deneyin.'])
                ->withInput([
                    'cardholder_name' => $validated['cardholder_name'],
                    'expiry_month' => $validated['expiry_month'],
                    'expiry_year' => $validated['expiry_year'],
                ]);
        }

        DB::transaction(function () use ($reservation, $validated): void {
            $lockedReservation = Reservation::query()->lockForUpdate()->findOrFail($reservation->id);

            if ($lockedReservation->status === Reservation::STATUS_CONFIRMED) {
                return;
            }

            abort_if($lockedReservation->status === Reservation::STATUS_CANCELLED, 422, 'İptal edilmiş rezervasyon için ödeme alınamaz.');

            $cardNumber = $validated['card_number'];

            $lockedReservation->update([
                'status' => Reservation::STATUS_CONFIRMED,
                'payment_method' => 'credit_card',
                'payment_card_brand' => $this->cardBrand($cardNumber),
                'payment_card_last_four' => substr($cardNumber, -4),
                'payment_transaction_reference' => 'PAY'.Str::ulid()->toBase32(),
                'paid_at' => now(),
            ]);
        });

        return redirect()->route('reservation.confirmation', $reservation);
    }

    public function confirmation(Reservation $reservation): View|RedirectResponse
    {
        if ($reservation->status !== Reservation::STATUS_CONFIRMED) {
            return redirect()->route('reservation.payment', $reservation);
        }

        return view('reservation.step6', [
            'reservation' => $reservation->load('roomType.images'),
            'siteSetting' => SiteSetting::query()->first(),
        ]);
    }

    /** @return array{checkIn: CarbonImmutable, checkOut: CarbonImmutable, adults: int, children: int, nights: int} */
    private function bookingParameters(): array
    {
        $checkIn = $this->dateParameter('checkin', today()->addDay()->toDateString());
        $checkOut = $this->dateParameter('checkout', $checkIn->addDay()->toDateString());

        if ($checkOut->lessThanOrEqualTo($checkIn)) {
            $checkOut = $checkIn->addDay();
        }

        return [
            'checkIn' => $checkIn,
            'checkOut' => $checkOut,
            'adults' => max(1, min(20, request()->integer('adults', 2))),
            'children' => max(0, min(20, request()->integer('children', 0))),
            'nights' => $checkIn->diffInDays($checkOut),
        ];
    }

    private function dateParameter(string $key, string $default): CarbonImmutable
    {
        try {
            return CarbonImmutable::createFromFormat('Y-m-d', request()->string($key, $default)->toString())->startOfDay();
        } catch (\Throwable) {
            return CarbonImmutable::parse($default)->startOfDay();
        }
    }

    private function cardBrand(string $cardNumber): string
    {
        return match (true) {
            str_starts_with($cardNumber, '4') => 'Visa',
            preg_match('/^5[1-5]/', $cardNumber) === 1 => 'Mastercard',
            preg_match('/^3[47]/', $cardNumber) === 1 => 'American Express',
            str_starts_with($cardNumber, '9792') => 'Troy',
            default => 'Banka Kartı',
        };
    }
}
