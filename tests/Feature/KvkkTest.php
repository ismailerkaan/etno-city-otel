<?php

namespace Tests\Feature;

use App\Models\SiteSetting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KvkkTest extends TestCase
{
    use RefreshDatabase;

    public function test_kvkk_notice_shows_data_controller_and_required_sections(): void
    {
        SiteSetting::query()->firstOrFail()->update([
            'legal_company_name' => 'EtnoCity Turizm Ltd. Şti.',
            'email' => 'kvkk@etnocity.test',
            'address' => 'Merkez Mah. No: 1',
        ]);

        $this->get(route('kvkk'))
            ->assertOk()
            ->assertSee('KVKK Aydınlatma Metni')
            ->assertSee('EtnoCity Turizm Ltd. Şti.')
            ->assertSee('kvkk@etnocity.test')
            ->assertSee('Merkez Mah. No: 1')
            ->assertSee('Veri Sorumlusu')
            ->assertSee('Hukuki Sebepleri')
            ->assertSee('HMS Otel Programı')
            ->assertSee('11. Maddesi');

        $this->withSession(['locale' => 'en'])
            ->get(route('kvkk'))
            ->assertOk()
            ->assertSee('Privacy Notice')
            ->assertSee('Data Controller');
    }

    public function test_hotel_name_is_used_when_company_name_is_missing(): void
    {
        SiteSetting::query()->firstOrFail()->update(['hotel_name' => 'EtnoCity Otel', 'legal_company_name' => null]);

        $this->get(route('kvkk'))->assertOk()->assertSee('EtnoCity Otel olarak');
    }

    public function test_kvkk_notice_is_linked_from_footer_and_forms(): void
    {
        $this->get(route('home'))->assertSee(route('kvkk'));
        $this->get(route('contact'))->assertSee(route('kvkk'));
        $this->get(route('cookie-policy'))->assertSee(route('kvkk'));
    }

    public function test_admin_can_set_legal_company_name(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $this->actingAs($admin)
            ->get(route('admin.site-settings.edit'))
            ->assertOk()
            ->assertSee('Şirket Unvanı (KVKK Veri Sorumlusu)');

        $this->actingAs($admin)
            ->put(route('admin.site-settings.update'), [
                'hotel_name' => 'EtnoCity Otel',
                'legal_company_name' => 'EtnoCity Turizm A.Ş.',
            ])
            ->assertSessionHasNoErrors();

        $this->assertSame('EtnoCity Turizm A.Ş.', SiteSetting::query()->firstOrFail()->legal_company_name);
    }
}
