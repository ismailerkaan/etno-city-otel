<?php

namespace Tests\Feature;

use App\Models\SiteSetting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class SiteSettingsTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_site_settings_form(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $this->actingAs($admin)
            ->get(route('admin.site-settings.edit'))
            ->assertOk()
            ->assertSee('Site Ayarları');
    }

    public function test_admin_can_update_site_settings_and_homepage_uses_them(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $siteSetting = SiteSetting::query()->firstOrFail();
        $data = $siteSetting->only($siteSetting->getFillable());
        $data['hotel_name'] = 'Yeni Otel Adı';
        $data['hero_title'] = 'Yeni Hero Başlığı';

        $this->actingAs($admin)
            ->put(route('admin.site-settings.update'), $data)
            ->assertSessionHasNoErrors()
            ->assertRedirect();

        $this->assertDatabaseHas('site_settings', [
            'hotel_name' => 'Yeni Otel Adı',
            'hero_title' => 'Yeni Hero Başlığı',
        ]);

        $this->get(route('home'))
            ->assertOk()
            ->assertSee('Yeni Otel Adı')
            ->assertSee('Yeni Hero Başlığı');
    }

    public function test_admin_can_upload_and_remove_logo(): void
    {
        Storage::fake('public');

        $admin = User::factory()->create(['is_admin' => true]);
        $siteSetting = SiteSetting::query()->firstOrFail();
        $data = $siteSetting->only($siteSetting->getFillable());

        $file = UploadedFile::fake()->image('custom_logo.png', 200, 100);
        $data['logo'] = $file;

        $this->actingAs($admin)
            ->put(route('admin.site-settings.update'), $data)
            ->assertSessionHasNoErrors()
            ->assertRedirect();

        $siteSetting->refresh();
        $this->assertNotNull($siteSetting->logo_url);
        Storage::disk('public')->assertExists($siteSetting->logo_url);

        $this->get(route('home'))
            ->assertOk()
            ->assertSee($siteSetting->logoUrl());

        // Remove logo test
        unset($data['logo']);
        $data['remove_logo'] = '1';

        $this->actingAs($admin)
            ->put(route('admin.site-settings.update'), $data)
            ->assertSessionHasNoErrors()
            ->assertRedirect();

        $siteSetting->refresh();
        $this->assertNull($siteSetting->logo_url);
    }

    public function test_footer_displays_only_filled_social_links(): void
    {
        $siteSetting = SiteSetting::query()->firstOrFail();
        $siteSetting->update([
            'instagram_url' => 'https://instagram.com/etnocity',
            'whatsapp_url' => 'https://wa.me/905550000000',
            'facebook_url' => null,
            'youtube_url' => 'https://youtube.com/@etnocity',
            'tripadvisor_url' => null,
        ]);

        $response = $this->get(route('home'));
        $response->assertOk();
        $response->assertSee('https://instagram.com/etnocity', false);
        $response->assertSee('https://wa.me/905550000000', false);
        $response->assertSee('https://youtube.com/@etnocity', false);
        $response->assertDontSee('https://facebook.com', false);
        $response->assertDontSee('https://tripadvisor.com', false);
    }
}
