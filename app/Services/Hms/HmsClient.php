<?php

namespace App\Services\Hms;

use App\Models\HmsSetting;
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

    /** @var array{enabled: bool, base_url: string, api_key: ?string, api_secret: ?string, hotel_code: ?string}|null */
    private ?array $settings = null;

    public function isEnabled(): bool
    {
        $settings = $this->settings();

        return $settings['enabled']
            && filled($settings['api_key'])
            && filled($settings['api_secret'])
            && filled($settings['hotel_code']);
    }

    public function hotelId(): int
    {
        return (int) $this->settings()['hotel_code'];
    }

    /**
     * Credentials from the admin panel, falling back to env config when they were never saved there.
     *
     * @return array{enabled: bool, base_url: string, api_key: ?string, api_secret: ?string, hotel_code: ?string}
     */
    public function settings(): array
    {
        if ($this->settings !== null) {
            return $this->settings;
        }

        $stored = rescue(fn () => HmsSetting::current(), null, report: false);

        return $this->settings = $stored ? [
            'enabled' => $stored->enabled,
            'base_url' => (string) $stored->base_url,
            'api_key' => $stored->api_key,
            'api_secret' => $stored->api_secret,
            'hotel_code' => $stored->hotel_code,
        ] : [
            'enabled' => (bool) config('services.hms.enabled'),
            'base_url' => (string) config('services.hms.base_url'),
            'api_key' => config('services.hms.api_key'),
            'api_secret' => config('services.hms.api_secret'),
            'hotel_code' => config('services.hms.hotel_code'),
        ];
    }

    /**
     * Use explicit credentials (e.g. to test them before saving).
     *
     * @param  array{enabled: bool, base_url: string, api_key: ?string, api_secret: ?string, hotel_code: ?string}  $settings
     */
    public function withSettings(array $settings): static
    {
        $client = clone $this;
        $client->settings = $settings;

        return $client;
    }

    /**
     * Log in and list room types to verify the credentials.
     *
     * @return array<int, string> HMS room type names
     */
    public function testConnection(): array
    {
        Cache::forget($this->tokenCacheKey());
        $checkIn = CarbonImmutable::today()->addDay();

        return collect($this->roomTypes($checkIn, $checkIn->addDay(), 1, fresh: true))
            ->map(fn (array $item): string => (string) $item['name'])
            ->all();
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
        return Cache::remember('hms.room-types.'.md5($this->fingerprint().serialize($payload)), now()->addMinute(), $fetch);
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
        return Cache::remember('hms.room-type-options.'.$this->fingerprint(), now()->addMinutes(10), fn (): array => $this->testConnection());
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
                'apiKey' => $this->settings()['api_key'],
                'apiSecret' => $this->settings()['api_secret'],
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
        return 'hms.token.'.$this->fingerprint();
    }

    /** Changes whenever the endpoint, credentials or hotel change, so cached tokens and lists never leak across them. */
    private function fingerprint(): string
    {
        $settings = $this->settings();

        return md5(implode('|', [$settings['base_url'], $settings['api_key'], $settings['api_secret'], $settings['hotel_code']]));
    }

    private function request(bool $asJson = false): PendingRequest
    {
        $request = Http::baseUrl(rtrim($this->settings()['base_url'], '/'))
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
