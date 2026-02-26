<?php

namespace Tests\Feature;

use App\Features\Users\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationFlowTest extends TestCase
{
    use RefreshDatabase;

    // ── Login ───────────────────────────────────────

    public function test_login_page_is_accessible(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
    }

    public function test_user_can_login_with_valid_credentials(): void
    {
        $user = User::factory()->create([
            'user_id' => 'test-user',
            'password' => bcrypt('password'),
            'role' => 'employee',
        ]);

        $response = $this->post('/login', [
            'user_id' => 'test-user',
            'password' => 'password',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticatedAs($user);
    }

    public function test_user_cannot_login_with_invalid_credentials(): void
    {
        User::factory()->create([
            'user_id' => 'test-user',
            'password' => bcrypt('password'),
        ]);

        $response = $this->from('/login')->post('/login', [
            'user_id' => 'test-user',
            'password' => 'wrong-password',
        ]);

        $response->assertRedirect('/login');
        $response->assertSessionHasErrors('user_id');
        $this->assertGuest();
    }

    // ── Login Throttling ────────────────────────────

    public function test_login_is_throttled_after_5_attempts(): void
    {
        User::factory()->create([
            'user_id' => 'throttle-user',
            'password' => bcrypt('password'),
        ]);

        // Make 5 failed attempts
        for ($i = 0; $i < 5; $i++) {
            $this->post('/login', [
                'user_id' => 'throttle-user',
                'password' => 'wrong',
            ]);
        }

        // 6th attempt should be throttled
        $response = $this->post('/login', [
            'user_id' => 'throttle-user',
            'password' => 'wrong',
        ]);

        $response->assertStatus(429); // Too Many Requests
    }

    // ── Logout ──────────────────────────────────────

    public function test_user_can_logout(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $response = $this->post('/logout');

        $response->assertRedirect('/login');
        $this->assertGuest();
    }

    // ── Registration Security ───────────────────────

    public function test_unauthenticated_user_cannot_access_registration(): void
    {
        $response = $this->get('/register');
        $response->assertRedirect(route('login'));
    }

    public function test_non_admin_cannot_access_registration(): void
    {
        $user = User::factory()->create(['role' => 'employee']);
        $this->actingAs($user);

        $response = $this->get('/register');
        $response->assertRedirect(route('dashboard'));
    }

    public function test_admin_can_access_registration(): void
    {
        $user = User::factory()->create(['role' => 'admin']);
        $this->actingAs($user);

        $response = $this->get('/register');
        $response->assertStatus(200);
    }

    // ── Middleware Guards ────────────────────────────

    public function test_unauthenticated_user_is_redirected_to_login(): void
    {
        $response = $this->get('/dashboard');
        $response->assertRedirect(route('login'));
    }

    public function test_employee_cannot_access_admin_dashboard(): void
    {
        $user = User::factory()->create(['role' => 'employee']);
        $this->actingAs($user);

        $response = $this->get('/admin/dashboard');
        // EnsureRole middleware redirects wrong-role users to their dashboard
        $response->assertRedirect(route('dashboard'));
    }

    public function test_employee_cannot_access_hr_dashboard(): void
    {
        $user = User::factory()->create(['role' => 'employee']);
        $this->actingAs($user);

        $response = $this->get('/hr/dashboard');
        // EnsureRole middleware redirects wrong-role users to their dashboard
        $response->assertRedirect(route('dashboard'));
    }

    public function test_authenticated_employee_can_access_dashboard(): void
    {
        $user = User::factory()->create(['role' => 'employee']);
        $this->actingAs($user);

        $response = $this->get('/dashboard');
        $response->assertRedirect(route('employee.dashboard'));
    }

    public function test_authenticated_admin_is_routed_to_admin_dashboard(): void
    {
        $user = User::factory()->create(['role' => 'admin']);
        $this->actingAs($user);

        $response = $this->get('/dashboard');
        $response->assertRedirect(route('admin.dashboard'));
    }
}
