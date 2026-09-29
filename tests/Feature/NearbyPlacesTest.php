<?php

namespace Tests\Feature;

use App\Models\NearbyPlace;
use App\Models\SiteSetting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NearbyPlacesTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_cannot_access_admin_nearby_places(): void
    {
        $this->get(route('admin.nearby-places.index'))->assertRedirect(route('login'));
    }

    public function test_admin_can_view_nearby_places_index(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $this->actingAs($admin)
            ->get(route('admin.nearby-places.index'))
            ->assertOk()
            ->assertSee('Harita & Yakın Yerler')
            ->assertSee('Harita ve Konum Bölüm Ayarları');
    }

    public function test_admin_can_update_location_section_and_map_settings(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $siteSetting = SiteSetting::query()->firstOrFail();

        $this->actingAs($admin)
            ->put(route('admin.nearby-places.section-update'), [
                'location_eyebrow' => 'Yarımadaya Kolay Ulaşım',
                'location_title' => 'Ege’nin Kalbinde Saklı Cennet',
                'location_description' => 'Bodrum merkeze ve marinalara dakikalar mesafede.',
                'location_eyebrow_en' => 'Easy Access',
                'location_title_en' => 'Hidden Paradise in the Aegean',
                'location_description_en' => 'Minutes away from town and marinas.',
                'location_map_embed_url' => 'https://maps.google.com/maps?q=37.00,27.00&output=embed',
                'location_coordinates' => '37°00\'00"N 27°00\'00"E',
                'location_address' => 'Bodrum Yarımadası',
                'location_pin_label' => 'EtnoCity Özel Alan',
            ])
            ->assertRedirect()
            ->assertSessionHas('success');

        $this->assertDatabaseHas('site_settings', [
            'id' => $siteSetting->id,
            'location_eyebrow' => 'Yarımadaya Kolay Ulaşım',
            'location_title' => 'Ege’nin Kalbinde Saklı Cennet',
            'location_map_embed_url' => 'https://maps.google.com/maps?q=37.00,27.00&output=embed',
            'location_pin_label' => 'EtnoCity Özel Alan',
        ]);
    }

    public function test_admin_can_create_nearby_place(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $this->actingAs($admin)
            ->post(route('admin.nearby-places.store'), [
                'icon' => 'beach_access',
                'title' => 'Cennet Koyu',
                'title_en' => 'Paradise Bay',
                'description' => 'Özel Tekne ile 10 dk',
                'description_en' => '10 min by Private Boat',
                'distance' => '5 km',
                'sort_order' => 1,
                'is_active' => '1',
            ])
            ->assertRedirect()
            ->assertSessionHas('success');

        $this->assertDatabaseHas('nearby_places', [
            'title' => 'Cennet Koyu',
            'icon' => 'beach_access',
            'distance' => '5 km',
            'is_active' => 1,
        ]);
    }

    public function test_admin_can_update_nearby_place(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $place = NearbyPlace::factory()->create(['title' => 'Eski Yer']);

        $this->actingAs($admin)
            ->put(route('admin.nearby-places.update', $place), [
                'icon' => 'castle',
                'title' => 'Yeni Tarihi Yer',
                'title_en' => 'New Historic Spot',
                'description' => 'Taksi: 15 dk',
                'description_en' => 'Taxi: 15 min',
                'distance' => '12 km',
                'sort_order' => 4,
                'is_active' => '1',
            ])
            ->assertRedirect(route('admin.nearby-places.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('nearby_places', [
            'id' => $place->id,
            'title' => 'Yeni Tarihi Yer',
            'icon' => 'castle',
            'distance' => '12 km',
        ]);
    }

    public function test_admin_can_toggle_nearby_place_status(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $place = NearbyPlace::factory()->create(['is_active' => true]);

        $this->actingAs($admin)
            ->patch(route('admin.nearby-places.toggle-status', $place))
            ->assertRedirect();

        $this->assertFalse($place->fresh()->is_active);
    }

    public function test_admin_can_delete_nearby_place(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $place = NearbyPlace::factory()->create();

        $this->actingAs($admin)
            ->delete(route('admin.nearby-places.destroy', $place))
            ->assertRedirect()
            ->assertSessionHas('success');

        $this->assertDatabaseMissing('nearby_places', ['id' => $place->id]);
    }

    public function test_home_page_displays_map_and_active_nearby_places_and_no_transfer_button(): void
    {
        SiteSetting::query()->firstOrFail()->update([
            'location_eyebrow' => 'Özel Konum Rehberi',
            'location_title' => 'Huzur ve Ulaşılabilirlik',
            'location_description' => 'Tüm önemli noktalara yakın.',
            'location_map_embed_url' => 'https://maps.google.com/maps?q=37.12,27.27&output=embed',
        ]);

        NearbyPlace::factory()->create([
            'title' => 'Gümüşlük Koyu',
            'description' => 'Özel Araç: 15 dk',
            'distance' => '8 km',
            'is_active' => true,
        ]);

        NearbyPlace::factory()->create([
            'title' => 'Gizli Pasif Nokta',
            'is_active' => false,
        ]);

        $this->get(route('home'))
            ->assertOk()
            ->assertSee('Özel Konum Rehberi')
            ->assertSee('Huzur ve Ulaşılabilirlik')
            ->assertSee('https://maps.google.com/maps?q=37.12,27.27&output=embed')
            ->assertSee('Gümüşlük Koyu')
            ->assertSee('Özel Araç: 15 dk')
            ->assertSee('8 km')
            ->assertDontSee('Gizli Pasif Nokta')
            ->assertDontSee('Özel Havalimanı Yat Transferi Ayarla');
    }
}
