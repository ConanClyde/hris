<?php

namespace Tests\Feature;

use App\Features\Employees\Models\Employee;
use App\Features\Users\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminUsersPageTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Create admin user and set session for admin routes.
     */
    protected function asAdmin(): self
    {
        $user = User::factory()->create([
            'role' => 'admin',
            'is_active' => true,
        ]);

        return $this->withSession([
            'user_id' => $user->id,
            'role' => 'admin',
        ]);
    }

    /**
     * Test admin users page loads without RelationNotFoundException.
     */
    public function test_admin_users_page_loads_successfully(): void
    {
        $response = $this->asAdmin()->get(route('admin.users'));

        $response->assertStatus(200);
        $response->assertViewIs('features.users.admin.index');
    }

    /**
     * Test admin users page loads when users have no employee records.
     */
    public function test_admin_users_page_loads_with_users_without_employees(): void
    {
        User::factory()->count(3)->create([
            'role' => 'admin',
            'is_active' => true,
        ]);

        $response = $this->asAdmin()->get(route('admin.users'));

        $response->assertStatus(200);
        $response->assertViewHas('users');
    }

    /**
     * Test admin users page loads when users have employee records.
     */
    public function test_admin_users_page_loads_with_users_with_employees(): void
    {
        $user = User::factory()->create([
            'role' => 'employee',
            'is_active' => true,
        ]);

        Employee::create([
            'user_id' => $user->id,
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => $user->email,
            'division' => 'IT',
            'section' => 'Development',
            'status' => 'active',
        ]);

        $response = $this->asAdmin()->get(route('admin.users'));

        $response->assertStatus(200);
        $response->assertViewHas('users');
        $users = $response->viewData('users');
        $this->assertNotEmpty($users);
    }

    /**
     * Test admin users page gracefully handles mixed users (with and without employees).
     */
    public function test_admin_users_page_loads_with_mixed_users(): void
    {
        // User without employee
        User::factory()->create([
            'role' => 'admin',
            'is_active' => true,
        ]);

        // User with employee
        $userWithEmployee = User::factory()->create([
            'role' => 'employee',
            'is_active' => true,
        ]);
        Employee::create([
            'user_id' => $userWithEmployee->id,
            'first_name' => 'Jane',
            'last_name' => 'Smith',
            'email' => $userWithEmployee->email,
            'status' => 'active',
        ]);

        $response = $this->asAdmin()->get(route('admin.users'));

        $response->assertStatus(200);
    }
}
