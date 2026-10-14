<?php

namespace Tests\Feature;

use App\Models\Reservation;
use App\Models\RoomImage;
use App\Models\RoomType;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReservationFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_room_list_displays_only_active_room_types(): void
    {
        $activeRoom = RoomType::factory()->create(['name' => 'Aktif Oda', 'is_active' => true]);
        RoomImage::factory()->for($activeRoom)->create();
        RoomType::factory()->create(['name' => 'Pasif Oda', 'is_active' => false]);

        $this->get(route('reservation.step1'))
            ->assertOk()
            ->assertSee('Aktif Oda')
            ->assertDontSee('Pasif Oda');
    }

    public function test_inactive_room_detail_returns_not_found(): void
    {
        $roomType = RoomType::factory()->create(['is_active' => false]);

        $this->get(route('reservation.step2', $roomType))->assertNotFound();
    }

    public function test_room_detail_displays_gallery_pricing_and_similar_rooms(): void
    {
        $roomType = RoomType::factory()->create(['name' => 'Manzaralı Oda', 'nightly_price' => 4700]);
        RoomImage::factory()->count(2)->for($roomType)->create();
        $similarRoom = RoomType::factory()->create(['name' => 'Standart Oda', 'nightly_price' => 3200]);
        RoomImage::factory()->for($similarRoom)->create();

        $response = $this->get(route('reservation.step2', [
            'roomType' => $roomType,
            'checkin' => today()->addDay()->toDateString(),
            'checkout' => today()->addDays(3)->toDateString(),
            'adults' => 2,
            'children' => 0,
        ]));

        $response->assertOk()
            ->assertSee('Galeriyi Aç')
            ->assertSee('9.400')
            ->assertSee('Benzer Odalar');
    }

    public function test_guest_information_page_displays_detailed_form_and_booking_summary(): void
    {
        $roomType = RoomType::factory()->create([
            'name' => 'Standart Oda',
            'nightly_price' => 3200,
        ]);

        $response = $this->get(route('reservation.step4', [
            'roomType' => $roomType,
            'checkin' => today()->addDay()->toDateString(),
            'checkout' => today()->addDays(3)->toDateString(),
            'adults' => 2,
            'children' => 0,
        ]));

        $response->assertOk()
            ->assertSee('Konaklayacak Misafir')
            ->assertSee('Konaklama Notunuz')
            ->assertSee('Seçilen Oda')
            ->assertSee('6.400');
    }

    public function test_valid_guest_information_creates_payment_pending_reservation_with_snapshot_price(): void
    {
        $this->travelTo('2026-10-13 12:00:00');
        $roomType = RoomType::factory()->create([
            'name' => 'Standart', 'nightly_price' => 3200, 'max_adults' => 2, 'max_children' => 1,
        ]);

        $response = $this->post(route('reservation.store', $roomType), [
            'check_in' => '2026-10-20', 'check_out' => '2026-10-23',
            'adults' => 2, 'children' => 1,
            'first_name' => 'Ahmet', 'last_name' => 'Yılmaz', 'email' => 'ahmet@example.com',
            'phone' => '+90 555 111 22 33',
            'special_requests' => 'Sessiz oda rica ederim.', 'terms_accepted' => '1',
        ]);

        $reservation = Reservation::query()->firstOrFail();
        $response->assertRedirect(route('reservation.payment', $reservation));
        $this->assertSame(Reservation::STATUS_PAYMENT_PENDING, $reservation->status);
        $this->assertSame(3, $reservation->nights);
        $this->assertSame('9600.00', $reservation->total_price);
        $this->assertSame('Standart', $reservation->room_name);
        $this->assertSame('Rez-1000', $reservation->reference);
        $this->assertNull($reservation->guest_title);
        $this->assertNull($reservation->country_code);
    }

    public function test_reservation_rejects_invalid_dates_and_missing_consent(): void
    {
        $this->travelTo('2026-10-13 12:00:00');
        $roomType = RoomType::factory()->create();

        $this->post(route('reservation.store', $roomType), [
            'check_in' => '2026-10-12', 'check_out' => '2026-10-11',
            'adults' => 2, 'children' => 0, 'first_name' => 'Test', 'last_name' => 'Misafir',
            'email' => 'test@example.com', 'phone' => '5551112233',
        ])->assertSessionHasErrors(['check_in', 'check_out', 'terms_accepted']);

        $this->assertSame(0, Reservation::query()->count());
    }

    public function test_reservation_references_increase_from_rez_1000(): void
    {
        $roomType = RoomType::factory()->create(['max_adults' => 2, 'max_children' => 0]);
        $guestInformation = [
            'check_in' => today()->addDay()->toDateString(),
            'check_out' => today()->addDays(2)->toDateString(),
            'adults' => 2,
            'children' => 0,
            'first_name' => 'Test',
            'last_name' => 'Misafir',
            'email' => 'test@example.com',
            'phone' => '5551112233',
            'terms_accepted' => '1',
        ];

        $this->post(route('reservation.store', $roomType), $guestInformation)->assertRedirect();
        $this->post(route('reservation.store', $roomType), [
            ...$guestInformation,
            'email' => 'ikinci@example.com',
        ])->assertRedirect();

        $this->assertSame(
            ['Rez-1000', 'Rez-1001'],
            Reservation::query()->orderBy('id')->pluck('reference')->all(),
        );
    }

    public function test_reservation_rejects_guest_count_above_room_capacity(): void
    {
        $roomType = RoomType::factory()->create(['max_adults' => 1, 'max_children' => 0]);

        $this->post(route('reservation.store', $roomType), [
            'check_in' => today()->addDay()->toDateString(), 'check_out' => today()->addDays(2)->toDateString(),
            'adults' => 2, 'children' => 1, 'first_name' => 'Test', 'last_name' => 'Misafir',
            'email' => 'test@example.com', 'phone' => '5551112233', 'terms_accepted' => '1',
        ])->assertSessionHasErrors(['adults', 'children']);

        $this->assertSame(0, Reservation::query()->count());
    }

    public function test_valid_card_payment_confirms_reservation_without_storing_sensitive_card_data(): void
    {
        $reservation = Reservation::factory()->create(['status' => Reservation::STATUS_PAYMENT_PENDING]);

        $response = $this->post(route('reservation.payment.store', $reservation), [
            'cardholder_name' => 'Ayşe Demir',
            'card_number' => '4242 4242 4242 4242',
            'expiry_month' => '12',
            'expiry_year' => now()->addYear()->year,
            'security_code' => '123',
        ]);

        $response->assertRedirect(route('reservation.confirmation', $reservation));

        $reservation->refresh();
        $this->assertSame(Reservation::STATUS_CONFIRMED, $reservation->status);
        $this->assertSame('Visa', $reservation->payment_card_brand);
        $this->assertSame('4242', $reservation->payment_card_last_four);
        $this->assertNotNull($reservation->payment_transaction_reference);
        $this->assertNotNull($reservation->paid_at);
        $this->assertArrayNotHasKey('card_number', $reservation->getAttributes());
        $this->assertArrayNotHasKey('security_code', $reservation->getAttributes());
    }

    public function test_payment_page_displays_secure_payment_details_and_charge_summary(): void
    {
        $reservation = Reservation::factory()->create([
            'status' => Reservation::STATUS_PAYMENT_PENDING,
            'total_price' => 6400,
        ]);

        $this->get(route('reservation.payment', $reservation))
            ->assertOk()
            ->assertSee('Kart Bilgileri')
            ->assertSee('Kart Bilgisi Saklanmaz')
            ->assertSee('Ödenecek Toplam')
            ->assertSee('6.400');
    }

    public function test_invalid_card_is_rejected_without_flashing_sensitive_values(): void
    {
        $reservation = Reservation::factory()->create(['status' => Reservation::STATUS_PAYMENT_PENDING]);

        $response = $this->from(route('reservation.payment', $reservation))->post(route('reservation.payment.store', $reservation), [
            'cardholder_name' => 'Ayşe Demir',
            'card_number' => '4242 4242 4242 4241',
            'expiry_month' => '12',
            'expiry_year' => now()->addYear()->year,
            'security_code' => '123',
        ]);

        $response->assertRedirect(route('reservation.payment', $reservation))
            ->assertSessionHasErrors(['card_number'])
            ->assertSessionMissing('_old_input.card_number')
            ->assertSessionMissing('_old_input.security_code');

        $this->assertSame(Reservation::STATUS_PAYMENT_PENDING, $reservation->fresh()->status);
    }

    public function test_simulated_declined_card_keeps_reservation_pending_and_displays_gateway_error(): void
    {
        $reservation = Reservation::factory()->create(['status' => Reservation::STATUS_PAYMENT_PENDING]);

        $response = $this->followingRedirects()->from(route('reservation.payment', $reservation))->post(route('reservation.payment.store', $reservation), [
            'cardholder_name' => 'Test Misafir',
            'card_number' => '4000 0000 0000 0002',
            'expiry_month' => '12',
            'expiry_year' => now()->addYear()->year,
            'security_code' => '123',
        ]);

        $response->assertOk()
            ->assertSee('Kartınızdan tahsilat yapılamadı')
            ->assertSessionMissing('_old_input.card_number')
            ->assertSessionMissing('_old_input.security_code');

        $this->assertSame(Reservation::STATUS_PAYMENT_PENDING, $reservation->fresh()->status);
    }

    public function test_confirmation_page_is_only_available_after_successful_payment(): void
    {
        $reservation = Reservation::factory()->create(['status' => Reservation::STATUS_PAYMENT_PENDING]);

        $this->get(route('reservation.confirmation', $reservation))
            ->assertRedirect(route('reservation.payment', $reservation));
    }

    public function test_confirmation_page_displays_stay_payment_and_arrival_details(): void
    {
        $reservation = Reservation::factory()->create([
            'status' => Reservation::STATUS_CONFIRMED,
            'payment_card_brand' => 'Visa',
            'payment_card_last_four' => '4242',
            'paid_at' => now(),
        ]);

        $this->get(route('reservation.confirmation', $reservation))
            ->assertOk()
            ->assertSee('Rezervasyonunuz Onaylandı')
            ->assertSee('Ödeme Makbuzu')
            ->assertSee('Konaklama Öncesi Kısa Rehber')
            ->assertSee('4242');
    }

    public function test_admin_can_view_reservation_details_and_update_status(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $reservation = Reservation::factory()->create(['first_name' => 'Ayşe', 'last_name' => 'Demir']);

        $this->actingAs($admin)->get(route('admin.reservations.index'))->assertOk()->assertSee('Ayşe Demir');
        $this->actingAs($admin)->get(route('admin.reservations.show', $reservation))->assertOk()->assertSee($reservation->reference);
        $this->actingAs($admin)->patch(route('admin.reservations.update', $reservation), ['status' => Reservation::STATUS_CONFIRMED])->assertRedirect()->assertSessionHas('success');

        $this->assertSame(Reservation::STATUS_CONFIRMED, $reservation->fresh()->status);
    }
}
