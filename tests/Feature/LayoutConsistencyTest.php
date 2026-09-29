<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\SiteSetting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LayoutConsistencyTest extends TestCase
{
    use RefreshDatabase;

    public function test_all_public_pages_render_identical_header_and_footer(): void
    {
        $siteSetting = SiteSetting::query()->firstOrFail();
        $siteSetting->update([
            'hotel_name' => 'EtnoCity Test Resort',
            'phone' => '+90 555 123 4567',
            'email' => 'info@etnocitytest.com',
            'instagram_url' => 'https://instagram.com/etnocitytest',
            'whatsapp_url' => 'https://wa.me/905551234567',
            'facebook_url' => 'https://facebook.com/etnocitytest',
            'youtube_url' => 'https://youtube.com/@etnocitytest',
            'tripadvisor_url' => 'https://tripadvisor.com/etnocitytest',
        ]);

        $event = Event::create([
            'is_active' => true,
            'title' => 'Test Etkinlik',
            'category' => 'Test Kategori',
            'description' => 'Test Açıklama',
        ]);

        $routes = [
            route('home'),
            route('gallery'),
            route('events.show', $event),
            route('reservation.step1'),
            route('reservation.step2'),
            route('reservation.step3'),
            route('reservation.step4'),
            route('reservation.step5'),
        ];

        foreach ($routes as $url) {
            $response = $this->get($url);
            $response->assertOk();

            // Header checks
            $response->assertSee('EtnoCity Test Resort');
            $response->assertSee(route('reservation.step1'));
            $response->assertSee(route('gallery'));
            $response->assertSee('mobile-nav-toggle', false);

            // Footer checks
            $response->assertSee('https://instagram.com/etnocitytest', false);
            $response->assertSee('https://wa.me/905551234567', false);
            $response->assertSee('https://facebook.com/etnocitytest', false);
            $response->assertSee('https://youtube.com/@etnocitytest', false);
            $response->assertSee('https://tripadvisor.com/etnocitytest', false);
            $response->assertSee('+90 555 123 4567');
            $response->assertSee('info@etnocitytest.com');
        }
    }
}
