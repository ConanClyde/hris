<?php

namespace Tests\Feature;

use App\Features\Employees\Models\Division;
use App\Features\Employees\Models\Employee;
use App\Features\Employees\Models\Section;
use App\Features\Employees\Models\Subdivision;
use App\Features\Users\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserManagementTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $admin;

    protected $division;

    protected $subdivision;

    protected $section;

    protected function setUp(): void
    {
        parent::setUp();
        // Create Admin User
        $this->admin = User::create([
            'user_id' => 'ADMIN001',
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        // Seed Structure
        $this->division = Division::create(['name' => 'Test Division']);
        $this->subdivision = Subdivision::create(['name' => 'Test Subdivision', 'division_id' => $this->division->id]);
        $this->section = Section::create(['name' => 'Test Section', 'subdivision_id' => $this->subdivision->id, 'division_id' => $this->division->id]);
    }

    public function test_admin_can_view_users_page()
    {
        $response = $this->actingAs($this->admin)->get('/admin/users');
        $response->assertStatus(200);
        $response->assertSee('User Management');
    }

    public function test_admin_can_create_user_with_full_profile()
    {
        $data = [
            'user_id' => 'EMP001',
            'email' => 'emp@example.com',
            'password' => 'password123',
            'first_name' => 'John',
            'middle_name' => 'D',
            'last_name' => 'Doe',
            'name_extension' => 'Jr',
            'sex' => 'male',
            'date_of_birth' => '1990-01-01',
            'date_hired' => '2023-01-01',
            'position' => 'Developer',
            'classification' => 'Regular',
            'division' => $this->division->id,
            'subdivision' => $this->subdivision->id,
            'unit_section' => $this->section->id,
            'role' => 'employee',
        ];

        $response = $this->actingAs($this->admin)->post('/admin/users', $data);

        $response->assertRedirect();
        $response->assertSessionHasNoErrors();

        $this->assertDatabaseHas('users', ['email' => 'emp@example.com']);
        $this->assertDatabaseHas('employees', [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'middle_name' => 'D',
            'name_extension' => 'Jr',
            'division_id' => $this->division->id,
        ]);

        // Verify PDS Personal Info
        $user = User::where('email', 'emp@example.com')->first();
        $this->assertNotNull($user->employee->pds);
        $this->assertNotNull($user->employee->pds->personal);
        $this->assertEquals('1990-01-01', $user->employee->pds->personal->dob->format('Y-m-d'));
    }

    public function test_admin_can_update_user_profile()
    {
        // Create user first
        $user = User::create([
            'user_id' => 'EMP002',
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'password' => bcrypt('password'),
            'role' => 'employee',
        ]);
        Employee::create([
            'user_id' => $user->id,
            'first_name' => 'Jane',
            'last_name' => 'Doe',

        ]);

        $data = [
            'user_id' => 'EMP002',
            'email' => 'jane.updated@example.com',
            'first_name' => 'Janet',
            'last_name' => 'Does',
            'sex' => 'female',
            'date_of_birth' => '1995-05-05',
            'date_hired' => '2024-01-01',
            'position' => 'Senior Dev',
            'classification' => 'COS',
            'division' => $this->division->id,
            'unit_section' => $this->section->id,
            'role' => 'employee',
            'status' => 'active',
        ];

        $response = $this->actingAs($this->admin)->put("/admin/users/{$user->id}", $data);

        $response->assertRedirect();
        $response->assertSessionHasNoErrors();

        $this->assertDatabaseHas('users', ['email' => 'jane.updated@example.com']);
        $this->assertDatabaseHas('employees', ['first_name' => 'Janet', 'last_name' => 'Does']);
    }

    public function test_validation_rules()
    {
        $response = $this->actingAs($this->admin)->post('/admin/users', []);
        $response->assertSessionHasErrors(['user_id', 'email', 'first_name', 'last_name']);
    }
}
