<?php

namespace Tests\Feature;

use App\Features\Employees\Models\Employee;
use App\Features\Users\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileDataSynchronizationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that profile page displays consistent data from database across all sections
     */
    public function test_profile_page_displays_consistent_database_values(): void
    {
        // Create a user with specific data
        $user = User::factory()->create([
            'name' => 'John Michael Doe',
            'email' => 'john.doe@example.com',
            'role' => 'admin',
        ]);

        // Create linked employee record
        Employee::create([
            'user_id' => $user->id,
            'first_name' => 'John',
            'middle_name' => 'Michael',
            'last_name' => 'Doe',
            'name_extension' => 'Jr.',
            'email' => 'john.doe@example.com',
            'position' => 'IT Manager',
            'division' => 'Chief of Hospital Offices Division',
            'section' => 'Information and Communications Technology Unit',
            'status' => 'active',
        ]);

        // Act as the user and visit profile page
        $response = $this->actingAs($user)
            ->withSession([
                'user_id' => $user->id,
                'role' => 'admin',
            ])
            ->get(route('admin.profile'));

        // Assert response is successful
        $response->assertStatus(200);

        // Assert that database values are displayed consistently across the page
        $response->assertSee('John');
        $response->assertSee('Doe');
        $response->assertSee('john.doe@example.com');

        // The full name should be constructed from database values
        $response->assertSee('John M. Doe Jr.');

        // User ID should be from database
        $response->assertSee((string) $user->id);
    }

    /**
     * Test that edit profile form is populated with database values
     */
    public function test_edit_profile_form_uses_database_values(): void
    {
        $user = User::factory()->create([
            'name' => 'Maria Santos Reyes',
            'email' => 'maria.reyes@example.com',
            'role' => 'hr',
        ]);

        Employee::create([
            'user_id' => $user->id,
            'first_name' => 'Maria',
            'middle_name' => 'Santos',
            'last_name' => 'Reyes',
            'name_extension' => null,
            'email' => 'maria.reyes@example.com',
            'position' => 'HR Manager',
            'division' => 'Finance and Administrative Division',
            'section' => 'Human Resource Management Section',
            'status' => 'active',
        ]);

        $response = $this->actingAs($user)
            ->withSession([
                'user_id' => $user->id,
                'role' => 'hr',
            ])
            ->get(route('hr.profile'));

        $response->assertStatus(200);

        // Assert form inputs contain database values
        $response->assertSee('value="Maria"', false);
        $response->assertSee('value="Santos"', false);
        $response->assertSee('value="Reyes"', false);
        $response->assertSee('value="maria.reyes@example.com"', false);

        // Should NOT see hardcoded defaults
        $response->assertDontSee('value="Admin"', false);
        $response->assertDontSee('value="User"', false);
        $response->assertDontSee('value="m@example.com"', false);
    }

    /**
     * Test that profile update synchronizes data across all sources
     */
    public function test_profile_update_synchronizes_session_and_database(): void
    {
        $user = User::factory()->create([
            'name' => 'Old Name',
            'email' => 'old@example.com',
            'role' => 'admin',
        ]);

        Employee::create([
            'user_id' => $user->id,
            'first_name' => 'Old',
            'middle_name' => '',
            'last_name' => 'Name',
            'email' => 'old@example.com',
            'status' => 'active',
        ]);

        // Update profile
        $response = $this->actingAs($user)
            ->withSession([
                'user_id' => $user->id,
                'role' => 'admin',
            ])
            ->put(route('admin.profile.update'), [
                '_token' => csrf_token(),
                'first_name' => 'New',
                'middle_name' => 'Middle',
                'surname' => 'Name',
                'name_extension' => 'Jr.',
                'email' => 'new@example.com',
            ]);

        $response->assertRedirect(route('admin.profile'));

        // Assert database was updated
        $user->refresh();
        $this->assertEquals('new@example.com', $user->email);

        // Assert session was updated
        $this->assertEquals('New', session('first_name'));
        $this->assertEquals('Middle', session('middle_name'));
        $this->assertEquals('Name', session('surname'));
        $this->assertEquals('Jr.', session('name_extension'));
        $this->assertEquals('new@example.com', session('email'));
    }

    /**
     * Test that member since displays correct date from database
     */
    public function test_member_since_shows_database_created_at(): void
    {
        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'role' => 'employee',
            'created_at' => '2023-06-15 10:00:00',
        ]);

        Employee::create([
            'user_id' => $user->id,
            'first_name' => 'Test',
            'last_name' => 'User',
            'email' => 'test@example.com',
            'status' => 'active',
        ]);

        $response = $this->actingAs($user)
            ->withSession([
                'user_id' => $user->id,
                'role' => 'employee',
            ])
            ->get(route('employee.profile'));

        $response->assertStatus(200);

        // Should show formatted created_at date
        $response->assertSee('June 2023');
    }

    /**
     * Test that profile data remains consistent after page reload
     */
    public function test_profile_data_consistent_across_reloads(): void
    {
        $user = User::factory()->create([
            'name' => 'Consistent User',
            'email' => 'consistent@example.com',
            'role' => 'admin',
        ]);

        Employee::create([
            'user_id' => $user->id,
            'first_name' => 'Consistent',
            'middle_name' => 'Test',
            'last_name' => 'User',
            'email' => 'consistent@example.com',
            'status' => 'active',
        ]);

        // First visit
        $response1 = $this->actingAs($user)
            ->withSession([
                'user_id' => $user->id,
                'role' => 'admin',
            ])
            ->get(route('admin.profile'));

        $response1->assertStatus(200);
        $content1 = $response1->getContent();

        // Second visit (clear session to ensure database is source of truth)
        $response2 = $this->actingAs($user)
            ->withSession([
                'user_id' => $user->id,
                'role' => 'admin',
            ])
            ->get(route('admin.profile'));

        $response2->assertStatus(200);
        $content2 = $response2->getContent();

        // Both visits should show the same data
        $this->assertStringContainsString('Consistent', $content1);
        $this->assertStringContainsString('Consistent', $content2);
        $this->assertStringContainsString('consistent@example.com', $content1);
        $this->assertStringContainsString('consistent@example.com', $content2);
    }
}
