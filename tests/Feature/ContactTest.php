<?php

namespace Tests\Feature;

use App\Models\ContactMessage;
use App\Models\SiteSetting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContactTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_can_view_contact_page(): void
    {
        $response = $this->get(route('contact'));

        $response->assertOk()
            ->assertSee('İletişim & Rezervasyon')
            ->assertSee('Bize Mesajınızı İletin');
    }

    public function test_contact_alias_redirects_to_canonical_route(): void
    {
        $response = $this->get('/contact');

        $response->assertRedirect(route('contact'));
    }

    public function test_guests_can_submit_contact_form(): void
    {
        $response = $this->post(route('contact.store'), [
            'name' => 'Canan Kaya',
            'email' => 'canan@example.com',
            'phone' => '+90 532 111 22 33',
            'subject' => 'Özel Konaklama & Rezervasyon',
            'message' => 'Ekim ayında 3 gece kaya süitinde konaklamak istiyoruz. Helikopter transferi mümkün mü?',
        ]);

        $response->assertRedirect()
            ->assertSessionHas('contact_success');

        $this->assertDatabaseHas('contact_messages', [
            'name' => 'Canan Kaya',
            'email' => 'canan@example.com',
            'subject' => 'Özel Konaklama & Rezervasyon',
            'is_read' => false,
        ]);
    }

    public function test_contact_form_requires_mandatory_fields(): void
    {
        $response = $this->post(route('contact.store'), [
            'name' => '',
            'email' => 'invalid-email',
            'message' => '',
        ]);

        $response->assertSessionHasErrors(['name', 'email', 'message']);
    }

    public function test_guests_cannot_access_admin_contact_page(): void
    {
        $this->get(route('admin.contact.index'))
            ->assertRedirect(route('login'));
    }

    public function test_admin_can_view_contact_messages_index(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);

        ContactMessage::query()->create([
            'name' => 'Mehmet Öz',
            'email' => 'mehmet@example.com',
            'phone' => '+90 555 123 4567',
            'subject' => 'VIP Helikopter Transferi',
            'message' => 'Havalimanından doğrudan iniş talep ediyoruz.',
            'is_read' => false,
        ]);

        $response = $this->actingAs($admin)->get(route('admin.contact-messages.index'));

        $response->assertOk()
            ->assertSee('İletişim Talepleri')
            ->assertSee('Mehmet Öz')
            ->assertSee('mehmet@example.com')
            ->assertSee('VIP Helikopter Transferi');
    }

    public function test_admin_can_filter_and_search_contact_messages(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);

        ContactMessage::query()->create([
            'name' => 'Zeynep Ak',
            'email' => 'zeynep@example.com',
            'subject' => 'Düğün Organizasyonu',
            'message' => 'Koyda özel düğün planlıyoruz.',
            'is_read' => true,
        ]);

        ContactMessage::query()->create([
            'name' => 'Kemal Tan',
            'email' => 'kemal@example.com',
            'subject' => 'Yat Transferi',
            'message' => 'Marina transferi talep ediyoruz.',
            'is_read' => false,
        ]);

        // Filter unread
        $response = $this->actingAs($admin)->get(route('admin.contact-messages.index', ['status' => 'unread']));
        $response->assertOk()
            ->assertSee('Kemal Tan')
            ->assertDontSee('Zeynep Ak');

        // Search query
        $responseSearch = $this->actingAs($admin)->get(route('admin.contact-messages.index', ['q' => 'Düğün']));
        $responseSearch->assertOk()
            ->assertSee('Zeynep Ak')
            ->assertDontSee('Kemal Tan');
    }

    public function test_admin_can_view_contact_page_settings(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $response = $this->actingAs($admin)->get(route('admin.contact.index'));

        $response->assertOk()
            ->assertSee('İletişim Sayfası Yönetimi')
            ->assertSee('İletişim Taleplerini Görüntüle');
    }

    public function test_admin_can_update_contact_settings(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $siteSetting = SiteSetting::firstOrCreate(['id' => 1]);

        $response = $this->actingAs($admin)->put(route('admin.contact.settings-update'), [
            'contact_eyebrow' => 'Özel Konsiyerj Hattı',
            'contact_title' => 'Bodrum’da Eşsiz Karşılama',
            'contact_subtitle' => 'Tüm tatil planlarınız için 24 saat hazırız.',
            'contact_eyebrow_en' => 'Peninsula Concierge',
            'contact_title_en' => 'Bespoke Welcome in Bodrum',
            'contact_subtitle_en' => 'Ready 24 hours for all your itinerary needs.',
            'contact_concierge_hours' => '24 Saat Kesintisiz Masa',
            'contact_concierge_hours_en' => '24/7 Dedicated Desk',
            'contact_phone_concierge' => '+90 252 333 44 55',
            'contact_email_reservations' => 'vip@etnocity.com',
            'location_map_embed_url' => 'https://maps.google.com/maps?q=37.1228,27.2789&output=embed',
            'location_coordinates' => '37°07\'22.4"N 27°16\'44.1"E',
        ]);

        $response->assertRedirect(route('admin.contact.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('site_settings', [
            'id' => $siteSetting->id,
            'contact_eyebrow' => 'Özel Konsiyerj Hattı',
            'contact_title' => 'Bodrum’da Eşsiz Karşılama',
            'contact_phone_concierge' => '+90 252 333 44 55',
            'contact_email_reservations' => 'vip@etnocity.com',
            'location_map_embed_url' => 'https://maps.google.com/maps?q=37.1228,27.2789&output=embed',
            'location_coordinates' => '37°07\'22.4"N 27°16\'44.1"E',
        ]);
    }

    public function test_admin_can_toggle_message_read_status(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $message = ContactMessage::query()->create([
            'name' => 'Ayşe Demir',
            'email' => 'ayse@example.com',
            'message' => 'Örnek soru metni.',
            'is_read' => false,
        ]);

        $response = $this->actingAs($admin)->patch(route('admin.contact-messages.toggle-read', $message));

        $response->assertRedirect(route('admin.contact-messages.index'))
            ->assertSessionHas('success');

        $this->assertTrue($message->fresh()->is_read);

        // Toggle back to unread
        $this->actingAs($admin)->patch(route('admin.contact-messages.toggle-read', $message));
        $this->assertFalse($message->fresh()->is_read);
    }

    public function test_admin_can_delete_contact_message(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $message = ContactMessage::query()->create([
            'name' => 'Silinecek Kişi',
            'email' => 'sil@example.com',
            'message' => 'Bu mesaj silinecek.',
            'is_read' => true,
        ]);

        $response = $this->actingAs($admin)->delete(route('admin.contact-messages.destroy', $message));

        $response->assertRedirect(route('admin.contact-messages.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseMissing('contact_messages', [
            'id' => $message->id,
        ]);
    }

    public function test_site_setting_generates_correct_directions_url(): void
    {
        $setting = new SiteSetting([
            'location_coordinates' => '37.122889, 27.278917',
        ]);

        $this->assertStringContainsString('https://www.google.com/maps/dir/?api=1&destination=37.122889,27.278917', $setting->directionsUrl());

        $settingWithEmbed = new SiteSetting([
            'location_map_embed_url' => 'https://maps.google.com/maps?q=37.122889,27.278917&output=embed',
        ]);

        $this->assertStringContainsString('https://www.google.com/maps/dir/?api=1&destination=37.122889,27.278917', $settingWithEmbed->directionsUrl());
    }

    public function test_refined_location_title_and_description(): void
    {
        $setting = new SiteSetting([
            'location_title' => 'Sakin Ama Kolayca Ulaşılabilir',
            'location_description' => "Cape Artemis'in el değmemiş batı burnunda yer alan AURA...",
        ]);

        $this->assertEquals('Konumumuz', $setting->translated('location_title'));
        $this->assertEquals('Yol tarifi alarak otelimize kolayca ulaşabilirsiniz.', $setting->translated('location_description'));
    }
}
