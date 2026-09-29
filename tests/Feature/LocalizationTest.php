<?php

namespace Tests\Feature;

use App\Models\SiteSetting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LocalizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_language_switch_stores_supported_locale_in_session(): void
    {
        $this->get(route('locale.switch', 'en'))
            ->assertRedirect()
            ->assertSessionHas('locale', 'en');
    }

    public function test_unsupported_language_returns_not_found(): void
    {
        $this->get('/language/de')->assertNotFound();
    }

    public function test_homepage_renders_english_content_for_english_locale(): void
    {
        SiteSetting::query()->firstOrFail()->update([
            'hero_title' => 'Türkçe Başlık',
            'hero_title_en' => 'English Heading',
        ]);

        $this->withSession(['locale' => 'en'])
            ->get(route('home'))
            ->assertOk()
            ->assertSee('English Heading')
            ->assertSee('Reserve Stay')
            ->assertDontSee('Türkçe Başlık');
    }

    public function test_header_navigation_shows_events_removes_spa_and_removes_try(): void
    {
        // Turkish
        $this->get(route('home'))
            ->assertOk()
            ->assertSee('Etkinlikler')
            ->assertDontSee('#spa')
            ->assertDontSee('Sağlık & Spa')
            ->assertDontSee('>TRY<', false);

        // English
        $this->withSession(['locale' => 'en'])
            ->get(route('home'))
            ->assertOk()
            ->assertSee('Events')
            ->assertDontSee('Wellness & Spa')
            ->assertDontSee('>TRY<', false);
    }
}
