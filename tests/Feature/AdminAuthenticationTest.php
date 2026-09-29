<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminAuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_view_admin_login_page(): void
    {
        $this->get(route('admin.login'))
            ->assertOk()
            ->assertSee('EtnoCity Yönetim Paneli');
    }

    public function test_admin_can_log_in_and_view_dashboard(): void
    {
        $admin = User::factory()->create([
            'is_admin' => true,
            'password' => 'password',
        ]);

        $this->post(route('admin.login.store'), [
            'email' => $admin->email,
            'password' => 'password',
        ])->assertRedirect(route('admin.dashboard'));

        $this->assertAuthenticatedAs($admin);
        $this->get(route('admin.dashboard'))->assertOk();
    }

    public function test_non_admin_cannot_log_in_to_admin_panel(): void
    {
        $user = User::factory()->create([
            'is_admin' => false,
            'password' => 'password',
        ]);

        $this->post(route('admin.login.store'), [
            'email' => $user->email,
            'password' => 'password',
        ])->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_admin_routes_redirect_guests_to_login(): void
    {
        $this->get(route('admin.dashboard'))
            ->assertRedirect(route('login'));
    }

    public function test_authenticated_non_admin_receives_forbidden_response(): void
    {
        $user = User::factory()->create(['is_admin' => false]);

        $this->actingAs($user)
            ->get(route('admin.dashboard'))
            ->assertForbidden();
    }

    public function test_admin_can_log_out(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $this->actingAs($admin)
            ->post(route('admin.logout'))
            ->assertRedirect(route('admin.login'));

        $this->assertGuest();
    }
}
