<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CookieConsentTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_pages_show_cookie_consent_banner_with_accept_and_reject(): void
    {
        $this->get(route('home'))
            ->assertOk()
            ->assertSee('id="cookie-consent"', false)
            ->assertSee('Çerez Tercihleriniz')
            ->assertSee('data-cookie-choice="accepted"', false)
            ->assertSee('data-cookie-choice="rejected"', false)
            ->assertSee('Kabul Et')
            ->assertSee('Reddet')
            ->assertSee(route('cookie-policy'));
    }

    public function test_cookie_policy_page_is_available_in_both_languages(): void
    {
        $this->get(route('cookie-policy'))
            ->assertOk()
            ->assertSee('Çerez Politikası')
            ->assertSee('Zorunlu çerezler')
            ->assertSee('Analitik çerezler')
            ->assertSee('cookie_consent');

        $this->withSession(['locale' => 'en'])
            ->get(route('cookie-policy'))
            ->assertOk()
            ->assertSee('Cookie Policy')
            ->assertSee('Analytics cookies');

        $this->get('/cookie-policy')->assertRedirect(route('cookie-policy'));
    }

    public function test_admin_pages_do_not_show_cookie_banner(): void
    {
        $this->get(route('admin.login'))
            ->assertOk()
            ->assertDontSee('id="cookie-consent"', false);
    }
}
