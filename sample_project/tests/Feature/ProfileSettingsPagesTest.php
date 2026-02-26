<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileSettingsPagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_profile_and_settings_pages(): void
    {
        $response = $this->withSession([
            'user_id' => 'admin-001',
            'role' => 'admin',
        ])->get(route('admin.profile'));

        $response->assertStatus(200);

        $response = $this->withSession([
            'user_id' => 'admin-001',
            'role' => 'admin',
        ])->get(route('admin.settings'));

        $response->assertStatus(200);
    }

    public function test_hr_can_view_profile_and_settings_pages(): void
    {
        $response = $this->withSession([
            'user_id' => 'hr-001',
            'role' => 'hr',
        ])->get(route('hr.profile'));

        $response->assertStatus(200);

        $response = $this->withSession([
            'user_id' => 'hr-001',
            'role' => 'hr',
        ])->get(route('hr.settings'));

        $response->assertStatus(200);
    }

    public function test_employee_can_view_profile_and_settings_pages(): void
    {
        $response = $this->withSession([
            'user_id' => 'employee-001',
            'role' => 'employee',
        ])->get(route('employee.profile'));

        $response->assertStatus(200);

        $response = $this->withSession([
            'user_id' => 'employee-001',
            'role' => 'employee',
        ])->get(route('employee.settings'));

        $response->assertStatus(200);
    }
}
