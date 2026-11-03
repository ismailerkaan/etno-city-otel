<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DeadLinksTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_pages_have_no_links_that_only_jump_to_the_top(): void
    {
        foreach (['home', 'gallery', 'contact', 'menu', 'cookie-policy', 'kvkk', 'reservation.step1'] as $route) {
            $this->get(route($route))
                ->assertOk()
                ->assertDontSee('href="#"', false);
        }
    }

    public function test_footer_links_to_kvkk_notice_instead_of_empty_privacy_link(): void
    {
        $this->get(route('home'))
            ->assertSee(route('kvkk'))
            ->assertSee('KVKK Aydınlatma Metni')
            ->assertDontSee('availabilityModal', false);
    }
}
