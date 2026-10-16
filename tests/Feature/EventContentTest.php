<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\SiteSetting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EventContentTest extends TestCase
{
    use RefreshDatabase;

    public function test_city_hotel_event_content_is_seeded_and_visible_on_homepage(): void
    {
        $this->assertSame(5, Event::query()->count());
        $this->assertSame([
            'Düğün Organizasyonları',
            'Toplantı Organizasyonları',
            'Kurumsal Etkinlikler',
            'Toplu Yemek Organizasyonları',
            'Özel Gün ve Haftalara Ait Organizasyonlar',
        ], Event::query()->orderBy('sort_order')->pluck('title')->all());

        $siteSetting = SiteSetting::query()->firstOrFail();
        $this->assertSame('EtnoCity Organizasyon', $siteSetting->events_eyebrow);
        $this->assertSame('Şehrin Merkezinde Kusursuz Organizasyonlar', $siteSetting->events_title);

        $response = $this->get(route('home'));

        $response->assertOk()
            ->assertSee('Şehrin Merkezinde Kusursuz Organizasyonlar')
            ->assertSee('Düğün Organizasyonları')
            ->assertSee('Toplantı Organizasyonları')
            ->assertSee('Kurumsal Etkinlikler')
            ->assertSee('Toplu Yemek Organizasyonları')
            ->assertSee('Özel Gün ve Haftalara Ait Organizasyonlar')
            ->assertDontSee('cloneNode', false);
    }

    public function test_event_detail_uses_city_hotel_organization_language_and_contact_action(): void
    {
        $event = Event::query()->where('title', 'Düğün Organizasyonları')->firstOrFail();

        $response = $this->get(route('events.show', $event));

        $response->assertOk()
            ->assertSee('Organizasyon Detayları')
            ->assertSee('İhtiyacınıza Uygun Planlama')
            ->assertSee('Deneyimli Organizasyon Ekibi')
            ->assertSee('Organizasyon Teklifi Al')
            ->assertSee('/iletisim?subject=Düğün Organizasyonu', false)
            ->assertDontSee('7/24 Butler & Concierge');
    }
}
