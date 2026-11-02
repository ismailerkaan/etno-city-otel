<?php

namespace Tests\Feature;

use App\Models\Reservation;
use App\Models\RoomType;
use App\Models\User;
use App\Services\Hms\HmsOffer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class HmsIntegrationTest extends TestCase
{
    use RefreshDatabase;

    private const BASE_URL = 'https://hms.test';

    protected function setUp(): void
    {
        parent::setUp();

        $this->travelTo('2026-10-13 12:00:00');

        config([
            'services.hms.enabled' => true,
            'services.hms.base_url' => self::BASE_URL,
            'services.hms.api_key' => 'test-key',
            'services.hms.api_secret' => 'test-secret',
            'services.hms.hotel_code' => '1000',
        ]);
    }

    public function test_room_list_shows_live_hms_price_and_marks_unmapped_rooms_unavailable(): void
    {
        $this->fakeHms();
        $mapped = RoomType::factory()->create(['name' => 'Bağlı Oda', 'hms_room_type_id' => 2, 'sort_order' => 1]);
        RoomType::factory()->create(['name' => 'Bağlanmamış Oda', 'hms_room_type_id' => null, 'sort_order' => 2]);

        $this->get(route('reservation.step1', ['checkin' => '2026-10-20', 'checkout' => '2026-10-22', 'adults' => 2]))
            ->assertOk()
            ->assertSee('Bağlı Oda')
            ->assertSee('₺1.737')
            ->assertSee('Seçtiğiniz tarihlerde müsait değil')
            ->assertSee(e(route('reservation.step2', ['roomType' => $mapped, 'checkin' => '2026-10-20', 'checkout' => '2026-10-22', 'adults' => 2, 'children' => 0])), false);

        Http::assertSent(fn (Request $request): bool => str_ends_with($request->url(), '/external/online/roomType')
            && $request['hotelID'] === 1000
            && $request['startDate'] === '2026-10-20'
            && $request['adultCount'] === 2
            && $request->hasHeader('Authorization', 'Bearer test-token'));
    }

    public function test_room_detail_lists_board_and_refund_options(): void
    {
        $this->fakeHms();
        $roomType = RoomType::factory()->create(['hms_room_type_id' => 2]);

        $this->get(route('reservation.step2', ['roomType' => $roomType, 'checkin' => '2026-10-20', 'checkout' => '2026-10-22', 'adults' => 2]))
            ->assertOk()
            ->assertSee('Kahvaltı Dahil')
            ->assertSee('Ücretsiz iptal / iade edilebilir')
            ->assertSee('İade edilemez')
            ->assertSee('₺1.930')
            ->assertSee('rate_plan_id=2', false)
            ->assertSee('price_key=2-0', false);
    }

    public function test_child_ages_are_requested_before_prices_when_children_are_selected(): void
    {
        $this->fakeHms();
        $roomType = RoomType::factory()->create(['hms_room_type_id' => 2, 'max_children' => 2]);

        $this->get(route('reservation.step2', ['roomType' => $roomType, 'checkin' => '2026-10-20', 'checkout' => '2026-10-22', 'adults' => 2, 'children' => 1]))
            ->assertOk()
            ->assertSee('Çocuk yaşlarını seçin')
            ->assertDontSee('price_key=', false);

        Http::assertNotSent(fn (Request $request): bool => str_ends_with($request->url(), '/external/online/roomType'));

        $this->get(route('reservation.step2', ['roomType' => $roomType, 'checkin' => '2026-10-20', 'checkout' => '2026-10-22', 'adults' => 2, 'children' => 1, 'child_ages' => [7]]))
            ->assertOk()
            ->assertSee('price_key=2-1', false);

        Http::assertSent(fn (Request $request): bool => str_ends_with($request->url(), '/external/online/roomType')
            && $request['childCount'] === 1
            && $request['childAges'] === [7]);
    }

    public function test_guest_details_redirects_back_when_selected_offer_no_longer_exists(): void
    {
        $this->fakeHms();
        $roomType = RoomType::factory()->create(['hms_room_type_id' => 2]);

        $this->get(route('reservation.step4', ['roomType' => $roomType, 'checkin' => '2026-10-20', 'checkout' => '2026-10-22', 'adults' => 2, 'rate_plan_id' => 99, 'price_key' => '2-1']))
            ->assertRedirect()
            ->assertSessionHasErrors('offer');

        $this->get(route('reservation.step4', ['roomType' => $roomType, 'checkin' => '2026-10-20', 'checkout' => '2026-10-22', 'adults' => 2, 'rate_plan_id' => 2, 'price_key' => '2-1']))
            ->assertOk()
            ->assertSee('Kahvaltı Dahil')
            ->assertSee('Rezervasyonu Tamamla')
            ->assertSee('₺1.930');
    }

    public function test_reservation_is_pushed_to_hms_with_server_side_price_and_confirmed_without_payment(): void
    {
        $this->fakeHms();
        $roomType = RoomType::factory()->create(['name' => 'Standart', 'hms_room_type_id' => 2, 'max_children' => 1]);

        $response = $this->post(route('reservation.store', $roomType), [
            ...$this->guestPayload(),
            'children' => 1,
            'child_ages' => [7],
            'rate_plan_id' => 2,
            'price_key' => '2-1',
        ]);

        $reservation = Reservation::query()->firstOrFail();
        $response->assertRedirect(route('reservation.confirmation', $reservation));

        $this->assertSame(Reservation::STATUS_CONFIRMED, $reservation->status);
        $this->assertSame('pay_at_hotel', $reservation->payment_method);
        $this->assertSame(48213, $reservation->hms_booking_id);
        $this->assertSame('1930.00', $reservation->total_price);
        $this->assertSame('Kahvaltı Dahil', $reservation->board_name);
        $this->assertSame([7], $reservation->child_ages);
        $this->assertNull($reservation->paid_at);

        Http::assertSent(function (Request $request) use ($reservation): bool {
            if (! str_ends_with($request->url(), '/external/online/channel/booking')) {
                return false;
            }

            $booking = $request['BookingPushRQ']['Bookings'][0];

            return $booking['ID'] === $reservation->reference
                && $booking['hotelID'] === '1000'
                && $booking['type'] === 'Book'
                && $booking['RoomStays'][0]['roomTypeID'] === '2'
                && $booking['RoomStays'][0]['ratePlanID'] === '2'
                && $booking['RoomStays'][0]['GuestCount'] === ['adult' => 2, 'child' => 1]
                && $booking['RoomStays'][0]['PerDayRates']['PerDayRate'][0] === ['stayDate' => '2026-10-20', 'baseRate' => '965.00', 'hotelServiceFees' => '0']
                && $booking['ChildGuests'] === [['age' => 7]]
                && $booking['PrimaryGuests'][0]['email'] === 'ayse@example.com'
                && $booking['Total']['amountAfterTaxes'] === '1930.00';
        });

        $this->get(route('reservation.confirmation', $reservation))
            ->assertOk()
            ->assertSee('Rezervasyon Kesinleşti · Ödeme Otelde')
            ->assertSee('Kahvaltı Dahil');
        $this->get(route('reservation.payment', $reservation))->assertRedirect(route('reservation.confirmation', $reservation));
    }

    public function test_reservation_is_not_confirmed_when_hms_rejects_the_booking(): void
    {
        $this->fakeHms(booking: Http::response(['BookingPushRS' => ['Error' => 'Could not register.']]));
        $roomType = RoomType::factory()->create(['hms_room_type_id' => 2]);

        $this->from(route('reservation.step4', $roomType))
            ->post(route('reservation.store', $roomType), [...$this->guestPayload(), 'rate_plan_id' => 2, 'price_key' => '2-1'])
            ->assertRedirect(route('reservation.step4', $roomType))
            ->assertSessionHasErrors('hms');

        $reservation = Reservation::query()->firstOrFail();
        $this->assertSame(Reservation::STATUS_PAYMENT_PENDING, $reservation->status);
        $this->assertNull($reservation->hms_booking_id);
        $this->assertStringContainsString('Could not register.', $reservation->hms_sync_error);

        $this->get(route('reservation.confirmation', $reservation))->assertNotFound();
        $this->get(route('reservation.payment', $reservation))->assertNotFound();
    }

    public function test_store_rejects_unknown_offer_and_missing_child_ages(): void
    {
        $this->fakeHms();
        $roomType = RoomType::factory()->create(['hms_room_type_id' => 2, 'max_children' => 1]);

        $this->post(route('reservation.store', $roomType), [...$this->guestPayload(), 'rate_plan_id' => 2, 'price_key' => '9-1'])
            ->assertSessionHasErrors('offer');

        $this->post(route('reservation.store', $roomType), [...$this->guestPayload(), 'children' => 1, 'rate_plan_id' => 2, 'price_key' => '2-1'])
            ->assertSessionHasErrors('child_ages');

        $this->assertDatabaseCount('reservations', 0);
        Http::assertNotSent(fn (Request $request): bool => str_ends_with($request->url(), '/external/online/channel/booking'));
    }

    public function test_expired_token_triggers_a_single_relogin(): void
    {
        $roomTypeResponses = 0;
        $this->fakeHms(roomTypes: function () use (&$roomTypeResponses) {
            return ++$roomTypeResponses === 1
                ? Http::response(['success' => false, 'error' => 'Authentication required'], 401)
                : Http::response($this->roomListResponse());
        });
        RoomType::factory()->create(['name' => 'Bağlı Oda', 'hms_room_type_id' => 2]);

        $this->get(route('reservation.step1', ['checkin' => '2026-10-20', 'checkout' => '2026-10-22', 'adults' => 2]))
            ->assertOk()
            ->assertSee('₺1.737');

        Http::assertSentCount(4);
    }

    public function test_hms_outage_shows_friendly_message(): void
    {
        $this->fakeHms(roomTypes: Http::response('Server Error', 500));
        RoomType::factory()->create(['hms_room_type_id' => 2]);

        $this->get(route('reservation.step1', ['checkin' => '2026-10-20', 'checkout' => '2026-10-22', 'adults' => 2]))
            ->assertOk()
            ->assertSee('Güncel fiyat ve müsaitlik bilgisi şu anda alınamıyor')
            ->assertDontSee('Odayı Seç</a>', false);
    }

    public function test_admin_can_map_room_type_to_hms_room(): void
    {
        $this->fakeHms();
        $admin = User::factory()->create(['is_admin' => true]);
        $roomType = RoomType::factory()->create();

        $this->actingAs($admin)
            ->get(route('admin.rooms.edit', $roomType))
            ->assertOk()
            ->assertSee('HMS Eşleştirmesi')
            ->assertSee('Standart Oda (#2)');

        $this->actingAs($admin)
            ->put(route('admin.rooms.update', $roomType), [
                'name' => $roomType->name, 'slug' => $roomType->slug, 'nightly_price' => 3000,
                'max_adults' => 2, 'max_children' => 1, 'sort_order' => 1, 'is_active' => '1',
                'hms_room_type_id' => 2,
            ])
            ->assertSessionHasNoErrors();

        $this->assertSame(2, $roomType->fresh()->hms_room_type_id);
    }

    public function test_offer_parsing_skips_unsellable_rooms_and_converts_daily_dates(): void
    {
        $item = $this->roomListResponse()['items'][0];
        $offers = HmsOffer::fromRoomItem($item);

        $this->assertCount(2, $offers);
        $this->assertFalse($offers[0]->nonRefundable);
        $this->assertSame('1930.00', $offers[0]->total);
        $this->assertTrue($offers[1]->nonRefundable);
        $this->assertSame([['date' => '2026-10-20', 'price' => '965.00'], ['date' => '2026-10-21', 'price' => '965.00']], $offers[0]->dailyRates);
        $this->assertSame([], HmsOffer::fromRoomItem([...$item, 'roomCount' => 0]));
    }

    /** @return array<string, mixed> */
    private function guestPayload(): array
    {
        return [
            'check_in' => '2026-10-20', 'check_out' => '2026-10-22',
            'adults' => 2, 'children' => 0,
            'first_name' => 'Ayşe', 'last_name' => 'Demir', 'email' => 'ayse@example.com',
            'phone' => '+905551112233', 'terms_accepted' => '1',
        ];
    }

    private function fakeHms(mixed $roomTypes = null, mixed $booking = null): void
    {
        Http::fake([
            self::BASE_URL.'/external/public/login' => Http::response(['success' => true, 'hotelCode' => '1000', 'token' => 'test-token']),
            self::BASE_URL.'/external/online/roomType' => $roomTypes ?? Http::response($this->roomListResponse()),
            self::BASE_URL.'/external/online/channel/booking' => $booking ?? Http::response([
                'BookingPushRS' => ['Success' => true, 'BookingConfirmNumbers' => [['bookingID' => 48213, 'bookingType' => 'Book', 'HMS_ID' => 48213]]],
            ]),
        ]);
    }

    /** @return array<string, mixed> */
    private function roomListResponse(): array
    {
        $day = fn (string $price): array => [['price' => $price, 'tarih' => '20.10.2026'], ['price' => $price, 'tarih' => '21.10.2026']];

        return [
            'success' => true,
            'count' => 1,
            'items' => [[
                'id' => 2,
                'name' => 'Standart Oda',
                'roomCount' => 5,
                'currency' => 'TRY',
                'accommodationTypes' => [[
                    'id' => 2,
                    'title' => 'Kahvaltı Dahil',
                    'priceType' => 0,
                    'prices' => [
                        '2-1' => ['total' => 2, 'price' => '1930.00', 'currency' => 'TRY', 'nonRefundable' => '', 'id' => '2/2', 'prices' => $day('965.00')],
                        '2-0' => ['total' => 2, 'price' => '1737.00', 'currency' => 'TRY', 'nonRefundable' => '[NR]', 'id' => '2-0/2', 'prices' => $day('868.50')],
                    ],
                ]],
            ]],
        ];
    }
}
