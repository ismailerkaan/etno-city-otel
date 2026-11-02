<?php

namespace App\Services\Hms;

use Carbon\CarbonImmutable;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

/**
 * Client for the HMS Online Booking API.
 *
 * @see https://www.hmsdev.com/online-booking
 */
class HmsClient
{
    /** Room list error codes meaning "no availability" rather than a failure. */
    private const NO_AVAILABILITY_CODES = [20001, 20002, 20003, 20004];

    public function isEnabled(): bool
    {
        return (bool) config('services.hms.enabled')
            && filled(config('services.hms.api_key'))
            && filled(config('services.hms.api_secret'))
            && filled(config('services.hms.hotel_code'));
    }

    public function hotelId(): int
    {
        return (int) config('services.hms.hotel_code');
    }

    /**
     * Sellable HMS room types for the given stay, keyed by HMS room type id.
     *
     * @param  array<int, int>  $childAges
     * @return array<int, array<string, mixed>>
     */
    public function roomTypes(CarbonImmutable $checkIn, CarbonImmutable $checkOut, int $adults, array $childAges = [], bool $fresh = false): array
    {
        $payload = [
            'hotelID' => $this->hotelId(),
            'startDate' => $checkIn->toDateString(),
            'endDate' => $checkOut->toDateString(),
            'adultCount' => $adults,
            'childCount' => count($childAges),
            'childAges' => array_values($childAges),
            'language' => app()->getLocale(),
        ];

        $fetch = function () use ($payload): array {
            $data = $this->send('post', '/external/online/roomType', $payload);

            if (empty($data['success'])) {
                if (in_array((int) ($data['errorCode'] ?? 0), self::NO_AVAILABILITY_CODES, true)) {
                    return [];
                }

                throw new HmsException($data['errorMessage'] ?? 'HMS oda listesi alınamadı.', $data['errorCode'] ?? null);
            }

            return collect($data['items'] ?? [])->keyBy(fn (array $item): int => (int) $item['id'])->all();
        };

        if ($fresh) {
            return $fetch();
        }

        // Availability changes constantly; only smooth out repeated page views
        return Cache::remember('hms.room-types.'.md5(serialize($payload)), now()->addMinute(), $fetch);
    }

    /**
     * Offers for a single HMS room type.
     *
     * @param  array<int, int>  $childAges
     * @return array<int, HmsOffer>
     */
    public function offers(int $hmsRoomTypeId, CarbonImmutable $checkIn, CarbonImmutable $checkOut, int $adults, array $childAges = [], bool $fresh = false): array
    {
        $roomItem = $this->roomTypes($checkIn, $checkOut, $adults, $childAges, $fresh)[$hmsRoomTypeId] ?? null;

        return $roomItem ? HmsOffer::fromRoomItem($roomItem) : [];
    }

    /**
     * HMS room type names for the admin mapping dropdown.
     *
     * @return array<int, string>
     */
    public function roomTypeOptions(): array
    {
        return Cache::remember('hms.room-type-options.'.$this->hotelId(), now()->addMinutes(10), function (): array {
            $checkIn = CarbonImmutable::today()->addDay();

            return collect($this->roomTypes($checkIn, $checkIn->addDay(), 1, fresh: true))
                ->map(fn (array $item): string => (string) $item['name'])
                ->all();
        });
    }

    /**
     * Push a booking (BookingPushRQ) and return the HMS booking id.
     *
     * @param  array<string, mixed>  $booking
     */
    public function pushBooking(array $booking): int
    {
        $data = $this->send('post', '/external/online/channel/booking', [
            'BookingPushRQ' => ['Bookings' => [$booking]],
        ], asJson: true);

        $response = $data['BookingPushRS'] ?? null;

        if (! is_array($response) || isset($response['Error']) || empty($response['Success'])) {
            throw new HmsException('HMS rezervasyonu kaydetmedi: '.($response['Error'] ?? 'beklenmeyen yanıt'));
        }

        $confirmation = $response['BookingConfirmNumbers'][0] ?? [];

        return (int) ($confirmation['HMS_ID'] ?? $confirmation['bookingID'] ?? 0);
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    private function send(string $method, string $path, array $data, bool $asJson = false): array
    {
        try {
            $response = $this->request($asJson)->withToken($this->token())->{$method}($path, $data);

            // Token expired or revoked: log in again once
            if ($response->status() === 401) {
                Cache::forget($this->tokenCacheKey());
                $response = $this->request($asJson)->withToken($this->token())->{$method}($path, $data);
            }
        } catch (ConnectionException $exception) {
            throw new HmsException('HMS servisine bağlanılamadı: '.$exception->getMessage());
        }

        return $this->decode($response);
    }

    private function token(): string
    {
        return Cache::remember($this->tokenCacheKey(), now()->addDays(6), function (): string {
            $data = $this->decode($this->request()->post('/external/public/login', [
                'apiKey' => config('services.hms.api_key'),
                'apiSecret' => config('services.hms.api_secret'),
                'hotelCode' => $this->hotelId(),
            ]));

            if (empty($data['success']) || empty($data['token'])) {
                throw new HmsException('HMS girişi başarısız: '.($data['message'] ?? 'bilinmeyen hata'), $data['message'] ?? null);
            }

            return $data['token'];
        });
    }

    private function tokenCacheKey(): string
    {
        return 'hms.token.'.$this->hotelId();
    }

    private function request(bool $asJson = false): PendingRequest
    {
        $request = Http::baseUrl(rtrim((string) config('services.hms.base_url'), '/'))
            ->acceptJson()
            ->timeout((int) config('services.hms.timeout', 15))
            ->retry(2, 500, fn ($exception): bool => $exception instanceof ConnectionException, throw: false);

        return $asJson ? $request->asJson() : $request->asForm();
    }

    /** @return array<string, mixed> */
    private function decode(Response $response): array
    {
        if ($response->serverError() || $response->status() === 401 || ! is_array($response->json())) {
            throw new HmsException('HMS servisine ulaşılamadı (HTTP '.$response->status().').', $response->status());
        }

        return $response->json();
    }
}
