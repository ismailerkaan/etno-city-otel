<?php

namespace Tests\Feature;

use App\Models\ReservationGuarantee;
use App\Models\SiteSetting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReservationGuaranteeTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_cannot_access_admin_reservation_guarantees(): void
    {
        $this->get(route('admin.reservation-guarantees.index'))
            ->assertRedirect(route('login'));
    }

    public function test_admin_can_view_reservation_guarantees_index(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $this->actingAs($admin)
            ->get(route('admin.reservation-guarantees.index'))
            ->assertOk()
            ->assertSee('Rezervasyon Avantajları')
            ->assertSee('Doğrudan Fiyat Güvencesi');
    }

    public function test_admin_can_create_reservation_guarantee(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $response = $this->actingAs($admin)->post(route('admin.reservation-guarantees.store'), [
            'icon' => 'verified',
            'title' => 'Özel İkramlar',
            'title_en' => 'Special Treats',
            'description' => 'Her gün odaya özel taze meyve ve şarap ikramı.',
            'description_en' => 'Daily fresh fruit and wine treat in room.',
            'sort_order' => 10,
            'is_active' => '1',
        ]);

        $response->assertRedirect(route('admin.reservation-guarantees.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('reservation_guarantees', [
            'icon' => 'verified',
            'title' => 'Özel İkramlar',
            'title_en' => 'Special Treats',
            'description' => 'Her gün odaya özel taze meyve ve şarap ikramı.',
            'sort_order' => 10,
            'is_active' => true,
        ]);
    }

    public function test_admin_can_batch_update_reservation_guarantees(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $first = ReservationGuarantee::query()->orderBy('id')->firstOrFail();

        $response = $this->actingAs($admin)->put(route('admin.reservation-guarantees.batch-update'), [
            'guarantees' => [
                $first->id => [
                    'icon' => 'loyalty',
                    'title' => 'Güncellenmiş Garanti',
                    'title_en' => 'Updated Guarantee',
                    'description' => 'Güncellenmiş detay açıklaması.',
                    'description_en' => 'Updated description text.',
                    'sort_order' => 99,
                    'is_active' => '0',
                ],
            ],
        ]);

        $response->assertRedirect(route('admin.reservation-guarantees.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('reservation_guarantees', [
            'id' => $first->id,
            'icon' => 'loyalty',
            'title' => 'Güncellenmiş Garanti',
            'title_en' => 'Updated Guarantee',
            'sort_order' => 99,
            'is_active' => false,
        ]);
    }

    public function test_admin_can_update_guarantees_section_settings(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $siteSetting = SiteSetting::query()->firstOrFail();

        $response = $this->actingAs($admin)->put(route('admin.reservation-guarantees.section-update'), [
            'guarantees_eyebrow' => 'AURA Özel Güvencesi',
            'guarantees_title' => 'Bizimle Doğrudan Rezervasyon Ayrılığı',
            'guarantees_eyebrow_en' => 'AURA Special Guarantee',
            'guarantees_title_en' => 'Direct Booking Privileges with Us',
        ]);

        $response->assertRedirect(route('admin.reservation-guarantees.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('site_settings', [
            'id' => $siteSetting->id,
            'guarantees_eyebrow' => 'AURA Özel Güvencesi',
            'guarantees_title' => 'Bizimle Doğrudan Rezervasyon Ayrılığı',
            'guarantees_eyebrow_en' => 'AURA Special Guarantee',
            'guarantees_title_en' => 'Direct Booking Privileges with Us',
        ]);
    }

    public function test_admin_can_delete_reservation_guarantee(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $item = ReservationGuarantee::query()->create([
            'icon' => 'spa',
            'title' => 'Silinecek Madde',
            'description' => 'Açıklama',
            'sort_order' => 50,
            'is_active' => true,
        ]);

        $response = $this->actingAs($admin)->delete(route('admin.reservation-guarantees.destroy', $item));

        $response->assertRedirect(route('admin.reservation-guarantees.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseMissing('reservation_guarantees', [
            'id' => $item->id,
        ]);
    }

    public function test_guarantees_are_displayed_on_reservation_rooms_page(): void
    {
        $response = $this->get('/reservation/rooms');

        $response->assertOk()
            ->assertSee('Doğrudan Rezervasyon')
            ->assertSee('Açık, Kolay ve Güvenilir')
            ->assertSee('Doğrudan Fiyat Güvencesi')
            ->assertSee('Şeffaf Ücretlendirme');
    }

    public function test_inactive_guarantee_is_not_displayed_on_reservation_rooms_page(): void
    {
        ReservationGuarantee::query()->create([
            'icon' => 'lock',
            'title' => 'Gizli Pasif Madde',
            'description' => 'Görünmemeli',
            'sort_order' => 99,
            'is_active' => false,
        ]);

        $response = $this->get('/reservation/rooms');

        $response->assertOk()
            ->assertDontSee('Gizli Pasif Madde');
    }

    public function test_admin_can_seed_defaults(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);

        ReservationGuarantee::query()->delete();
        $this->assertSame(0, ReservationGuarantee::query()->count());

        $response = $this->actingAs($admin)->post(route('admin.reservation-guarantees.seed-defaults'));

        $response->assertRedirect(route('admin.reservation-guarantees.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('reservation_guarantees', [
            'title' => 'Doğrudan Fiyat Güvencesi',
        ]);
        $this->assertDatabaseHas('reservation_guarantees', [
            'title' => 'Şeffaf Hesap',
        ]);
        $this->assertDatabaseHas('reservation_guarantees', [
            'title' => '7/24 Yarımada Ev Sahibi',
        ]);
        $this->assertDatabaseHas('reservation_guarantees', [
            'title' => 'Esnek Depozito Politikası',
        ]);
    }
}
