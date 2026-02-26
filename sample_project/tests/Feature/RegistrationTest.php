<?php

namespace Tests\Feature;

use App\Features\Employees\Models\Division;
use App\Features\Employees\Models\Section;
use App\Features\Users\Models\User;
use Database\Seeders\OrganizationalUnitSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(OrganizationalUnitSeeder::class);
    }

    public function test_admin_can_register_employee()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $division = Division::first();
        $section = Section::where('division_id', $division->id)->whereNull('subdivision_id')->first();

        $response = $this->actingAs($admin)->post(route('register.store'), [
            'role' => 'Employee',
            'first_name' => 'John',
            'surname' => 'Doe',
            'sex' => 'male',
            'date_of_birth' => now()->subYears(20)->toDateString(),
            'date_hired' => now()->subDay()->toDateString(),
            'division' => $division->id,
            'unit_section' => $section->id,
            'position' => 'Developer',
            'classification' => 'Regular',
            'user_id' => 'EMP-001',
            'email' => 'john@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertRedirect('/dashboard');

        $this->assertDatabaseHas('users', [
            'email' => 'john@example.com',
            'role' => 'employee',
        ]);

        $this->assertDatabaseHas('employees', [
            'email' => 'john@example.com',
            'division_id' => $division->id,
            'section_id' => $section->id,
            'division' => $division->name, // Legacy
            'section' => $section->name, // Legacy
        ]);

        $this->assertDatabaseHas('activity_logs', [
            'action' => 'create',
            'subject_type' => 'User',
        ]);
    }

    public function test_admin_can_register_hr()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $division = Division::first();
        $section = Section::where('division_id', $division->id)->first();

        $response = $this->actingAs($admin)->post(route('register.store'), [
            'role' => 'HR', // Capitalized from UI
            'first_name' => 'Jane',
            'surname' => 'Smith',
            'sex' => 'female',
            'date_of_birth' => '1990-01-01',
            'date_hired' => '2023-01-01',
            'division' => $division->id,
            'unit_section' => $section->id,
            'position' => 'HR Officer',
            'classification' => 'Regular',
            'user_id' => 'HR-001',
            'email' => 'jane@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertRedirect('/dashboard');

        $this->assertDatabaseHas('users', [
            'role' => 'hr', // Normalized
        ]);
    }

    public function test_registration_validates_age()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $division = Division::first();
        $section = Section::where('division_id', $division->id)->first();

        $response = $this->actingAs($admin)->post(route('register.store'), [
            'role' => 'Employee',
            'first_name' => 'Young',
            'surname' => 'Boy',
            'sex' => 'male',
            'date_of_birth' => now()->subYears(17)->toDateString(), // 17 years old
            'date_hired' => now()->toDateString(),
            'division' => $division->id,
            'unit_section' => $section->id,
            'position' => 'Intern',
            'classification' => 'COS',
            'user_id' => 'u17',
            'email' => 'young@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertSessionHasErrors('date_of_birth');
    }

    public function test_registration_validates_future_date_hired()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $division = Division::first();
        $section = Section::where('division_id', $division->id)->first();

        $response = $this->actingAs($admin)->post(route('register.store'), [
            'role' => 'Employee',
            'first_name' => 'Future',
            'surname' => 'Man',
            'sex' => 'male',
            'date_of_birth' => '1990-01-01',
            'date_hired' => now()->addDay()->toDateString(), // Future
            'division' => $division->id,
            'unit_section' => $section->id,
            'position' => 'Oracle',
            'classification' => 'Regular',
            'user_id' => 'future1',
            'email' => 'future@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertSessionHasErrors('date_hired');
    }
}
