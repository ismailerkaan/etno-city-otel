<?php

namespace Tests\Feature;

use App\Models\HmsSetting;
use App\Models\Reservation;
use App\Models\RoomType;
use App\Models\User;
use App\Services\Hms\HmsClient;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class HmsAdminTest extends TestCase
{
    use RefreshDatabase;

    private const BASE_URL = 'https://hms.test';

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->travelTo('2026-10-13 12:00:00');
        $this->admin = User::factory()->create(['is_admin' => true]);

        config([
            'services.hms.enabled' => true,
            'services.hms.base_url' => self::BASE_URL,
            'services.hms.api_key' => 'env-key',
            'services.hms.api_secret' => 'env-secret',
            'services.hms.hotel_code' => '1000',
        ]);
    }

    public function test_admin_can_save_hms_settings_encrypted_and_they_override_env(): void
    {
        $this->actingAs($this->admin)->get(route('admin.hms.edit'))
            ->assertOk()
            ->assertSee('HMS Entegrasyonu')
            ->assertSee('.env')
            ->assertDontSee('env-secret');

        $this->actingAs($this->admin)->put(route('admin.hms.update'), [
            'enabled' => '1',
            'base_url' => 'https://live.hms.test/',
            'hotel_code' => '2045',
            'api_key' => 'live-key',
            'api_secret' => 'live-secret',
        ])->assertSessionHasNoErrors()->assertSessionHas('success');

        $raw = DB::table('hms_settings')->first();
        $this->assertNotSame('live-secret', $raw->api_secret);
        $this->assertNotSame('live-key', $raw->api_key);

        $settings = app(HmsClient::class)->settings();
        $this->assertSame('https://live.hms.test', $settings['base_url']);
        $this->assertSame('2045', $settings['hotel_code']);
        $this->assertSame('live-secret', $settings['api_secret']);

        // Blank secret fields keep the stored values
        $this->actingAs($this->admin)->put(route('admin.hms.update'), [
            'enabled' => '0',
            'base_url' => 'https://live.hms.test',
            'hotel_code' => '2046',
            'api_key' => '',
            'api_secret' => '',
        ])->assertSessionHasNoErrors();

        $stored = HmsSetting::current();
        $this->assertFalse($stored->enabled);
        $this->assertSame('2046', $stored->hotel_code);
        $this->assertSame('live-key', $stored->api_key);
        $this->assertSame('live-secret', $stored->api_secret);
        $this->assertFalse(app(HmsClient::class)->isEnabled());
    }

    public function test_connection_test_uses_submitted_credentials_and_reports_result(): void
    {
        Http::fake([
            'https://live.hms.test/external/public/login' => Http::response(['success' => true, 'token' => 'live-token']),
            'https://live.hms.test/external/online/roomType' => Http::response($this->roomListResponse()),
        ]);

        $this->actingAs($this->admin)->put(route('admin.hms.test'), [
            'base_url' => 'https://live.hms.test',
            'hotel_code' => '2045',
            'api_key' => 'live-key',
            'api_secret' => 'live-secret',
        ])->assertSessionHas('success', fn (string $message): bool => str_contains($message, 'Bağlantı başarılı') && str_contains($message, 'Standart Oda'));

        Http::assertSent(fn (Request $request): bool => str_ends_with($request->url(), '/external/public/login')
            && $request['apiKey'] === 'live-key' && $request['hotelCode'] === 2045);
        $this->assertDatabaseCount('hms_settings', 0);

        Http::fake(['*' => Http::response(['success' => false, 'token' => null, 'message' => 'partner_is_not_found'])]);

        $this->actingAs($this->admin)->put(route('admin.hms.test'), [
            'base_url' => 'https://other.hms.test',
            'hotel_code' => '2045',
            'api_key' => 'wrong',
            'api_secret' => 'wrong',
        ])->assertSessionHas('hms_test_error', fn (string $message): bool => str_contains($message, 'partner_is_not_found'));
    }

    public function test_cancelling_a_synced_reservation_sends_cancel_to_hms(): void
    {
        $this->fakeHms();
        $reservation = $this->hmsReservation();

        $this->actingAs($this->admin)->post(route('admin.reservations.cancel', $reservation))
            ->assertSessionHas('success');

        $this->assertSame(Reservation::STATUS_CANCELLED, $reservation->fresh()->status);
        Http::assertSent(fn (Request $request): bool => str_ends_with($request->url(), '/external/online/channel/booking')
            && $request['BookingPushRQ']['Bookings'][0]['type'] === 'Cancel'
            && $request['BookingPushRQ']['Bookings'][0]['RoomStays'][0]['type'] === 'Cancel'
            && $request['BookingPushRQ']['Bookings'][0]['ID'] === $reservation->reference);
    }

    public function test_cancel_rejected_by_hms_keeps_reservation_confirmed(): void
    {
        $this->fakeHms(booking: Http::response(['BookingPushRS' => ['Error' => 'Could not register.']]));
        $reservation = $this->hmsReservation();

        $this->actingAs($this->admin)->post(route('admin.reservations.cancel', $reservation))
            ->assertSessionHasErrors('hms');

        $reservation->refresh();
        $this->assertSame(Reservation::STATUS_CONFIRMED, $reservation->status);
        $this->assertNull($reservation->hms_sync_error);
        $this->assertFalse($reservation->hasHmsSyncProblem());
    }

    public function test_reservation_that_never_reached_hms_is_cancelled_locally(): void
    {
        Http::fake();
        $reservation = $this->hmsReservation(['status' => Reservation::STATUS_PAYMENT_PENDING, 'hms_booking_id' => null, 'hms_sync_error' => '[Book] down']);

        $this->actingAs($this->admin)->post(route('admin.reservations.cancel', $reservation))->assertSessionHas('success');

        $this->assertSame(Reservation::STATUS_CANCELLED, $reservation->fresh()->status);
        Http::assertNothingSent();
    }

    public function test_failed_reservation_is_listed_and_can_be_resent(): void
    {
        $this->fakeHms();
        $failed = $this->hmsReservation(['status' => Reservation::STATUS_PAYMENT_PENDING, 'hms_booking_id' => null, 'hms_sync_error' => '[Book] HMS servisine ulaşılamadı']);
        $retry = $this->hmsReservation(['email' => $failed->email, 'reference' => 'Rez-2000', 'created_at' => now()->addMinute()]);

        $this->actingAs($this->admin)->get(route('admin.hms.edit'))->assertSee($failed->reference)->assertSee('HMS servisine ulaşılamadı');
        $this->actingAs($this->admin)->get(route('admin.reservations.index', ['status' => 'hms_failed']))
            ->assertSee($failed->reference)
            ->assertDontSee('Rez-2000');
        $this->actingAs($this->admin)->get(route('admin.reservations.show', $failed))
            ->assertSee('HMS\'e Tekrar Gönder', false)
            ->assertSee('Rez-2000');

        $this->actingAs($this->admin)->post(route('admin.reservations.hms-resend', $failed))->assertSessionHas('success');

        $failed->refresh();
        $this->assertSame(Reservation::STATUS_CONFIRMED, $failed->status);
        $this->assertSame(48213, $failed->hms_booking_id);
        $this->assertNull($failed->hms_sync_error);
        $this->assertFalse($failed->hasHmsSyncProblem());
        Http::assertSent(fn (Request $request): bool => ($request['BookingPushRQ']['Bookings'][0]['type'] ?? null) === 'Book'
            && $request['BookingPushRQ']['Bookings'][0]['ID'] === $failed->reference);

        $this->actingAs($this->admin)->post(route('admin.reservations.hms-resend', $retry))->assertNotFound();
    }

    public function test_guest_detail_change_is_sent_as_modify_without_repricing(): void
    {
        $this->fakeHms();
        $reservation = $this->hmsReservation();

        $this->actingAs($this->admin)->put(route('admin.reservations.update-stay', $reservation), [
            ...$this->stayPayload($reservation),
            'phone' => '+905550000000',
            'special_requests' => 'Geç giriş',
        ])->assertRedirect(route('admin.reservations.show', $reservation));

        $reservation->refresh();
        $this->assertSame('+905550000000', $reservation->phone);
        $this->assertSame('1930.00', $reservation->total_price);
        Http::assertNotSent(fn (Request $request): bool => str_ends_with($request->url(), '/external/online/roomType'));
        Http::assertSent(fn (Request $request): bool => ($request['BookingPushRQ']['Bookings'][0]['type'] ?? null) === 'Modify'
            && $request['BookingPushRQ']['Bookings'][0]['PrimaryGuests'][0]['PhoneNumber'] === '+905550000000');
    }

    public function test_date_change_is_repriced_from_hms_and_sent_as_modify(): void
    {
        $this->fakeHms(roomTypes: Http::response($this->roomListResponse(['25.10.2026', '26.10.2026', '27.10.2026'], '3100.00', '1033.33')));
        $reservation = $this->hmsReservation();

        $this->actingAs($this->admin)->put(route('admin.reservations.update-stay', $reservation), [
            ...$this->stayPayload($reservation),
            'check_in' => '2026-10-25',
            'check_out' => '2026-10-28',
        ])->assertSessionHasNoErrors();

        $reservation->refresh();
        $this->assertSame('2026-10-25', $reservation->check_in->toDateString());
        $this->assertSame(3, $reservation->nights);
        $this->assertSame('3100.00', $reservation->total_price);
        Http::assertSent(fn (Request $request): bool => ($request['BookingPushRQ']['Bookings'][0]['type'] ?? null) === 'Modify'
            && $request['BookingPushRQ']['Bookings'][0]['checkinDate'] === '2026-10-25'
            && $request['BookingPushRQ']['Bookings'][0]['Total']['amountAfterTaxes'] === '3100.00'
            && count($request['BookingPushRQ']['Bookings'][0]['RoomStays'][0]['PerDayRates']['PerDayRate']) === 3);
    }

    public function test_modify_is_not_applied_when_hms_rejects_it(): void
    {
        $this->fakeHms(booking: Http::response(['BookingPushRS' => ['Error' => 'Failed to parse information.']]));
        $reservation = $this->hmsReservation();

        $this->actingAs($this->admin)->put(route('admin.reservations.update-stay', $reservation), [...$this->stayPayload($reservation), 'first_name' => 'Değişti'])
            ->assertSessionHasErrors('hms');

        $reservation->refresh();
        $this->assertNotSame('Değişti', $reservation->first_name);
        $this->assertFalse($reservation->hasHmsSyncProblem());
    }

    public function test_stay_change_is_refused_when_the_same_board_is_not_available(): void
    {
        $this->fakeHms(roomTypes: Http::response(['success' => false, 'errorCode' => 20001, 'errorMessage' => 'Yeterli yetişkin müsaitliği bulunamadı.']));
        $reservation = $this->hmsReservation();

        $this->actingAs($this->admin)->put(route('admin.reservations.update-stay', $reservation), [...$this->stayPayload($reservation), 'adults' => 3])
            ->assertSessionHasErrors('check_in');

        $this->assertSame(2, $reservation->fresh()->adults);
        Http::assertNotSent(fn (Request $request): bool => str_ends_with($request->url(), '/external/online/channel/booking'));
    }

    public function test_hms_reservation_status_cannot_be_changed_from_the_plain_status_form(): void
    {
        Http::fake();
        $reservation = $this->hmsReservation();

        $this->actingAs($this->admin)->patch(route('admin.reservations.update', $reservation), ['status' => Reservation::STATUS_CANCELLED])
            ->assertSessionHasErrors('hms');

        $this->assertSame(Reservation::STATUS_CONFIRMED, $reservation->fresh()->status);
        Http::assertNothingSent();
    }

    /** @param array<string, mixed> $attributes */
    private function hmsReservation(array $attributes = []): Reservation
    {
        return Reservation::factory()->create([
            'reference' => 'Rez-1500',
            'room_type_id' => RoomType::factory()->create(['hms_room_type_id' => 2])->id,
            'status' => Reservation::STATUS_CONFIRMED,
            'check_in' => '2026-10-20',
            'check_out' => '2026-10-22',
            'nights' => 2,
            'adults' => 2,
            'children' => 0,
            'child_ages' => [],
            'room_name' => 'Standart',
            'hms_room_type_id' => 2,
            'hms_rate_plan_id' => 2,
            'board_name' => 'Kahvaltı Dahil',
            'hms_price_key' => '2-1',
            'non_refundable' => false,
            'daily_rates' => [['date' => '2026-10-20', 'price' => '965.00'], ['date' => '2026-10-21', 'price' => '965.00']],
            'nightly_price' => '965.00',
            'total_price' => '1930.00',
            'currency' => 'TRY',
            'payment_method' => 'pay_at_hotel',
            'hms_booking_id' => 48000,
            'hms_synced_at' => now(),
            ...$attributes,
        ]);
    }

    /** @return array<string, mixed> */
    private function stayPayload(Reservation $reservation): array
    {
        return [
            'first_name' => $reservation->first_name,
            'last_name' => $reservation->last_name,
            'email' => $reservation->email,
            'phone' => $reservation->phone,
            'special_requests' => $reservation->special_requests,
            'check_in' => $reservation->check_in->toDateString(),
            'check_out' => $reservation->check_out->toDateString(),
            'adults' => $reservation->adults,
            'child_ages' => [],
        ];
    }

    private function fakeHms(mixed $roomTypes = null, mixed $booking = null): void
    {
        Http::fake([
            self::BASE_URL.'/external/public/login' => Http::response(['success' => true, 'token' => 'test-token']),
            self::BASE_URL.'/external/online/roomType' => $roomTypes ?? Http::response($this->roomListResponse()),
            self::BASE_URL.'/external/online/channel/booking' => $booking ?? Http::response([
                'BookingPushRS' => ['Success' => true, 'BookingConfirmNumbers' => [['bookingID' => 48213, 'HMS_ID' => 48213]]],
            ]),
        ]);
    }

    /**
     * @param  array<int, string>  $days
     * @return array<string, mixed>
     */
    private function roomListResponse(array $days = ['20.10.2026', '21.10.2026'], string $total = '1930.00', string $daily = '965.00'): array
    {
        $prices = array_map(fn (string $day): array => ['price' => $daily, 'tarih' => $day], $days);

        return [
            'success' => true,
            'items' => [[
                'id' => 2,
                'name' => 'Standart Oda',
                'roomCount' => 5,
                'currency' => 'TRY',
                'accommodationTypes' => [[
                    'id' => 2,
                    'title' => 'Kahvaltı Dahil',
                    'prices' => [
                        '2-1' => ['price' => $total, 'currency' => 'TRY', 'nonRefundable' => '', 'prices' => $prices],
                    ],
                ]],
            ]],
        ];
    }
}
