<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProcessReservationPaymentRequest;
use App\Http\Requests\StoreReservationRequest;
use App\Models\Reservation;
use App\Models\ReservationGuarantee;
use App\Models\RoomType;
use App\Models\SiteSetting;
use App\Services\Hms\HmsClient;
use App\Services\Hms\HmsException;
use App\Services\Hms\HmsOffer;
use App\Services\Hms\HmsReservationSync;
use Carbon\CarbonImmutable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ReservationController extends Controller
{
    private const SIMULATED_DECLINED_CARD = '4000000000000002';

    public function __construct(private readonly HmsClient $hms) {}

    public function rooms(): View
    {
        $roomTypes = RoomType::query()->active()->with('images')->orderBy('sort_order')->orderBy('id')->get();
        $parameters = $this->bookingParameters();

        return view('reservation.step1', [
            'roomTypes' => $roomTypes,
            'guarantees' => ReservationGuarantee::getActiveGuarantees(),
            'siteSetting' => SiteSetting::query()->first(),
            ...$parameters,
            ...$this->hmsContext($roomTypes, $parameters),
        ]);
    }

    public function room(RoomType $roomType): View
    {
        abort_unless($roomType->is_active, 404);

        $similarRoomTypes = RoomType::query()
            ->active()
            ->whereKeyNot($roomType->getKey())
            ->with('images')
            ->orderByRaw('ABS(nightly_price - ?)', [$roomType->nightly_price])
            ->orderBy('sort_order')
            ->limit(3)
            ->get();
        $parameters = $this->bookingParameters();

        return view('reservation.step2', [
            'roomType' => $roomType->load('images'),
            'similarRoomTypes' => $similarRoomTypes,
            'siteSetting' => SiteSetting::query()->first(),
            ...$parameters,
            ...$this->hmsContext(collect([$roomType])->merge($similarRoomTypes), $parameters),
        ]);
    }

    public function guestDetails(RoomType $roomType): View|RedirectResponse
    {
        abort_unless($roomType->is_active, 404);

        $parameters = $this->bookingParameters();
        $offer = null;

        if ($this->hms->isEnabled()) {
            $offer = $this->selectedOffer($roomType, $parameters, request()->integer('rate_plan_id'), request()->string('price_key')->toString());

            if (! $offer) {
                return redirect()
                    ->route('reservation.step2', ['roomType' => $roomType, ...$parameters['searchParameters']])
                    ->withErrors(['offer' => 'Seçtiğiniz fiyat artık geçerli değil. Lütfen güncel seçeneklerden birini seçin.']);
            }
        }

        return view('reservation.step4', [
            'roomType' => $roomType->load('images'),
            'siteSetting' => SiteSetting::query()->first(),
            'offer' => $offer,
            ...$parameters,
        ]);
    }

    public function store(StoreReservationRequest $request, RoomType $roomType): RedirectResponse
    {
        $validated = $request->validated();
        $checkIn = CarbonImmutable::createFromFormat('Y-m-d', $validated['check_in'])->startOfDay();
        $checkOut = CarbonImmutable::createFromFormat('Y-m-d', $validated['check_out'])->startOfDay();
        $nights = $checkIn->diffInDays($checkOut);

        if ($this->hms->isEnabled()) {
            return $this->storeHmsReservation($validated, $roomType, $checkIn, $checkOut, $nights);
        }

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

        // HMS reservations skip the payment step until online payment is integrated
        abort_if($reservation->isHmsReservation(), 404);

        return view('reservation.step5', [
            'reservation' => $reservation->load('roomType.images'),
            'siteSetting' => SiteSetting::query()->first(),
        ]);
    }

    public function processPayment(ProcessReservationPaymentRequest $request, Reservation $reservation): RedirectResponse
    {
        abort_if($reservation->isHmsReservation(), 404);

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
            abort_if($reservation->isHmsReservation(), 404);

            return redirect()->route('reservation.payment', $reservation);
        }

        return view('reservation.step6', [
            'reservation' => $reservation->load('roomType.images'),
            'siteSetting' => SiteSetting::query()->first(),
        ]);
    }

    /**
     * @param  array<string, mixed>  $validated
     */
    private function storeHmsReservation(array $validated, RoomType $roomType, CarbonImmutable $checkIn, CarbonImmutable $checkOut, int $nights): RedirectResponse
    {
        $childAges = array_map('intval', $validated['child_ages'] ?? []);

        try {
            // Never trust the price from the form: re-read the live offer from HMS
            $offer = collect($this->hms->offers((int) $roomType->hms_room_type_id, $checkIn, $checkOut, $validated['adults'], $childAges, fresh: true))
                ->first(fn (HmsOffer $offer): bool => $offer->matches((int) $validated['rate_plan_id'], $validated['price_key']));
        } catch (HmsException $exception) {
            report($exception);

            return back()->withInput()->withErrors(['hms' => 'Güncel fiyat bilgisi şu anda alınamıyor. Lütfen biraz sonra tekrar deneyin.']);
        }

        if (! $roomType->hms_room_type_id || ! $offer) {
            return back()->withInput()->withErrors(['offer' => 'Seçtiğiniz oda ve fiyat bu tarihlerde artık müsait değil. Lütfen güncel seçeneklerden birini seçin.']);
        }

        $reservation = DB::transaction(function () use ($roomType, $validated, $checkIn, $checkOut, $nights, $childAges, $offer): Reservation {
            $reservation = Reservation::query()->create([
                'reference' => Str::ulid()->toBase32(),
                'room_type_id' => $roomType->id,
                'status' => Reservation::STATUS_PAYMENT_PENDING,
                'check_in' => $checkIn,
                'check_out' => $checkOut,
                'nights' => $nights,
                'adults' => $validated['adults'],
                'children' => count($childAges),
                'child_ages' => $childAges,
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'special_requests' => $validated['special_requests'] ?? null,
                'room_name' => $roomType->name,
                'hms_room_type_id' => $roomType->hms_room_type_id,
                'hms_rate_plan_id' => $offer->ratePlanId,
                'board_name' => $offer->ratePlanName,
                'hms_price_key' => $offer->priceKey,
                'non_refundable' => $offer->nonRefundable,
                'daily_rates' => $offer->dailyRates,
                'nightly_price' => number_format((float) $offer->total / max(1, $nights), 2, '.', ''),
                'total_price' => $offer->total,
                'currency' => $offer->currency,
            ]);

            $reservation->update([
                'reference' => Reservation::referenceForId($reservation->getKey()),
            ]);

            return $reservation;
        });

        try {
            app(HmsReservationSync::class)->book($reservation);
        } catch (HmsException) {
            return back()->withInput()->withErrors(['hms' => 'Rezervasyonunuz şu anda tamamlanamadı. Lütfen tekrar deneyin veya bizimle iletişime geçin.']);
        }

        return redirect()->route('reservation.confirmation', $reservation);
    }

    /**
     * Live HMS offers per local room type, keyed by local room type id.
     *
     * @param  Collection<int, RoomType>  $roomTypes
     * @param  array<string, mixed>  $parameters
     * @return array{hmsEnabled: bool, hmsOffers: array<int, array<int, HmsOffer>>, hmsError: ?string}
     */
    private function hmsContext(Collection $roomTypes, array $parameters): array
    {
        if (! $this->hms->isEnabled()) {
            return ['hmsEnabled' => false, 'hmsOffers' => [], 'hmsError' => null];
        }

        if ($parameters['needsChildAges']) {
            return ['hmsEnabled' => true, 'hmsOffers' => [], 'hmsError' => null];
        }

        try {
            $items = $this->hms->roomTypes($parameters['checkIn'], $parameters['checkOut'], $parameters['adults'], $parameters['childAges']);
        } catch (HmsException $exception) {
            report($exception);

            return ['hmsEnabled' => true, 'hmsOffers' => [], 'hmsError' => 'Güncel fiyat ve müsaitlik bilgisi şu anda alınamıyor. Lütfen biraz sonra tekrar deneyin.'];
        }

        return [
            'hmsEnabled' => true,
            'hmsOffers' => $roomTypes->mapWithKeys(fn (RoomType $roomType): array => [
                $roomType->id => isset($items[$roomType->hms_room_type_id]) ? HmsOffer::fromRoomItem($items[$roomType->hms_room_type_id]) : [],
            ])->all(),
            'hmsError' => null,
        ];
    }

    /** @param array<string, mixed> $parameters */
    private function selectedOffer(RoomType $roomType, array $parameters, int $ratePlanId, string $priceKey): ?HmsOffer
    {
        if (! $roomType->hms_room_type_id || $parameters['needsChildAges']) {
            return null;
        }

        try {
            $offers = $this->hms->offers($roomType->hms_room_type_id, $parameters['checkIn'], $parameters['checkOut'], $parameters['adults'], $parameters['childAges']);
        } catch (HmsException $exception) {
            report($exception);

            return null;
        }

        return collect($offers)->first(fn (HmsOffer $offer): bool => $offer->matches($ratePlanId, $priceKey));
    }

    /** @return array{checkIn: CarbonImmutable, checkOut: CarbonImmutable, adults: int, children: int, childAges: array<int, int>, needsChildAges: bool, nights: int, searchParameters: array<string, mixed>} */
    private function bookingParameters(): array
    {
        $checkIn = $this->dateParameter('checkin', today()->addDay()->toDateString());
        $checkOut = $this->dateParameter('checkout', $checkIn->addDay()->toDateString());

        if ($checkOut->lessThanOrEqualTo($checkIn)) {
            $checkOut = $checkIn->addDay();
        }

        $adults = max(1, min(20, request()->integer('adults', 2)));
        $children = max(0, min(20, request()->integer('children', 0)));
        $childAges = collect((array) request()->input('child_ages', []))
            ->filter(fn ($age): bool => is_numeric($age) && (int) $age >= 0 && (int) $age <= 16)
            ->map(fn ($age): int => (int) $age)
            ->take($children)
            ->values()
            ->all();

        return [
            'checkIn' => $checkIn,
            'checkOut' => $checkOut,
            'adults' => $adults,
            'children' => $children,
            'childAges' => $childAges,
            // HMS prices children by age, so ages are needed before showing prices
            'needsChildAges' => $this->hms->isEnabled() && $children > 0 && count($childAges) !== $children,
            'nights' => $checkIn->diffInDays($checkOut),
            'searchParameters' => array_filter([
                'checkin' => $checkIn->toDateString(),
                'checkout' => $checkOut->toDateString(),
                'adults' => $adults,
                'children' => $children,
                'child_ages' => $childAges ?: null,
            ], fn ($value): bool => $value !== null),
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
