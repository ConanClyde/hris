<?php

namespace Database\Seeders;

use App\Features\Employees\Models\Division;
use App\Features\Employees\Models\Employee;
use App\Features\Employees\Models\Section;
use App\Features\Leave\Models\LeaveApplication;
use App\Features\Notices\Models\Notice;
use App\Features\Pds\Models\Pds;
use App\Features\Pds\Models\PdsPersonal;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class HrisSeeder extends Seeder
{
    public function run(): void
    {
        $division = Division::first();
        $section = $division ? Section::where('division_id', $division->id)->first() : null;

        // ── Admin User ─────────────────────────────
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Juan A. Dela Cruz',
                'username' => 'admin',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'is_active' => true,
                'first_name' => 'Juan',
                'middle_name' => 'Andrade',
                'last_name' => 'Dela Cruz',
                'name_extension' => null,
            ]
        );

        $this->ensureEmployeeAndPds($admin, [
            'position' => 'System Administrator',
            'classification' => 'Permanent',
            'division_id' => $division?->id,
            'division' => $division?->name,
            'section_id' => $section?->id,
            'section' => $section?->name,
            'date_hired' => '2020-01-15',
            'sex' => 'male',
            'dob' => '1990-05-20',
        ]);

        // ── HR User ────────────────────────────────
        $hrUser = User::firstOrCreate(
            ['email' => 'hr@example.com'],
            [
                'name' => 'Maria B. Santos',
                'username' => 'hr',
                'password' => Hash::make('password'),
                'role' => 'hr',
                'is_active' => true,
                'first_name' => 'Maria',
                'middle_name' => 'Buenaventura',
                'last_name' => 'Santos',
                'name_extension' => null,
            ]
        );

        $this->ensureEmployeeAndPds($hrUser, [
            'position' => 'HR Officer',
            'classification' => 'Permanent',
            'division_id' => $division?->id,
            'division' => $division?->name,
            'section_id' => $section?->id,
            'section' => $section?->name,
            'date_hired' => '2021-03-01',
            'sex' => 'female',
            'dob' => '1992-08-15',
        ]);

        // ── Employee User ──────────────────────────
        $employeeUser = User::firstOrCreate(
            ['email' => 'employee@example.com'],
            [
                'name' => 'Pedro C. Reyes Jr.',
                'username' => 'employee',
                'password' => Hash::make('password'),
                'role' => 'employee',
                'is_active' => true,
                'first_name' => 'Pedro',
                'middle_name' => 'Castillo',
                'last_name' => 'Reyes',
                'name_extension' => 'Jr.',
            ]
        );

        $this->ensureEmployeeAndPds($employeeUser, [
            'position' => 'Staff',
            'classification' => 'Contractual',
            'division_id' => $division?->id,
            'division' => $division?->name,
            'section_id' => $section?->id,
            'section' => $section?->name,
            'date_hired' => '2024-06-01',
            'sex' => 'male',
            'dob' => '1998-11-30',
        ]);

        // ── Sample data ────────────────────────────
        LeaveApplication::factory(5)->create();
        Notice::factory(3)->create();
    }

    /**
     * Create Employee + PDS + PdsPersonal records for a given user.
     */
    private function ensureEmployeeAndPds(User $user, array $data): void
    {
        $employee = Employee::firstOrCreate(
            ['user_id' => $user->id],
            [
                'first_name' => $user->first_name,
                'middle_name' => $user->middle_name,
                'last_name' => $user->last_name,
                'name_extension' => $user->name_extension,
                'email' => $user->email,
                'position' => $data['position'],
                'classification' => $data['classification'] ?? null,
                'division_id' => $data['division_id'],
                'division' => $data['division'],
                'section_id' => $data['section_id'],
                'section' => $data['section'],
                'date_hired' => $data['date_hired'] ?? null,
                'status' => 'active',
            ]
        );

        $pds = Pds::firstOrCreate(
            ['employee_id' => $employee->id],
            ['status' => 'draft']
        );

        PdsPersonal::firstOrCreate(
            ['pds_id' => $pds->id],
            [
                'surname' => $user->last_name,
                'first_name' => $user->first_name,
                'middle_name' => $user->middle_name,
                'name_extension' => $user->name_extension,
                'sex' => $data['sex'] ?? null,
                'dob' => $data['dob'] ?? null,
                'email' => $user->email,
            ]
        );
    }
}

