<?php

namespace Database\Seeders;

use App\Features\Employees\Models\Employee;
use App\Features\Users\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class EmployeeSeeder extends Seeder
{
    public function run(): void
    {
        // Define employees with their corresponding user credentials using config
        $employees = [
            [
                'user_id' => 'employee01',
                'name' => config('seeder.demo_users.employee01.name', 'Maria Santos'),
                'email' => config('seeder.demo_users.employee01.email', 'employee01@hris.local'),
                'password' => config('seeder.demo_users.employee01.password', 'password'),
                'role' => config('seeder.demo_users.employee01.role', 'employee'),
                'position' => config('seeder.demo_users.employee01.position', 'Nurse II'),
                'classification' => 'Permanent',
                'date_hired' => '2019-06-15',
                'division' => config('seeder.demo_users.employee01.division', 'Treatment and Rehabilitation Division'),
                'subdivision' => config('seeder.demo_users.employee01.subdivision', 'Non-Residential Treatment & Rehabilitation'),
                'section' => config('seeder.demo_users.employee01.section', 'Nursing Section'),
                'status' => 'active',
            ],
            [
                'user_id' => 'employee02',
                'name' => config('seeder.demo_users.employee02.name', 'Pedro Garcia'),
                'email' => config('seeder.demo_users.employee02.email', 'employee02@hris.local'),
                'password' => config('seeder.demo_users.employee02.password', 'password'),
                'role' => config('seeder.demo_users.employee02.role', 'employee'),
                'position' => config('seeder.demo_users.employee02.position', 'Psychologist I'),
                'classification' => 'Permanent',
                'date_hired' => '2021-08-01',
                'division' => config('seeder.demo_users.employee02.division', 'Treatment and Rehabilitation Division'),
                'subdivision' => config('seeder.demo_users.employee02.subdivision', 'Residential Treatment & Rehabilitation'),
                'section' => config('seeder.demo_users.employee02.section', 'Psychological Section'),
                'status' => 'active',
            ],
            [
                'user_id' => 'hr01',
                'name' => config('seeder.demo_users.hr01.name', 'HR Specialist'),
                'email' => config('seeder.demo_users.hr01.email', 'hr01@hris.local'),
                'password' => config('seeder.demo_users.hr01.password', 'password'),
                'role' => config('seeder.demo_users.hr01.role', 'hr'),
                'position' => config('seeder.demo_users.hr01.position', 'Human Resource Management Officer I'),
                'classification' => 'Permanent',
                'date_hired' => '2020-03-10',
                'division' => config('seeder.demo_users.hr01.division', 'Finance and Administrative Division'),
                'subdivision' => null,
                'section' => config('seeder.demo_users.hr01.section', 'Human Resource Management Section'),
                'status' => 'active',
            ],
            [
                'user_id' => 'hr02',
                'name' => config('seeder.demo_users.hr02.name', 'HR Assistant'),
                'email' => config('seeder.demo_users.hr02.email', 'hr02@hris.local'),
                'password' => config('seeder.demo_users.hr02.password', 'password'),
                'role' => config('seeder.demo_users.hr02.role', 'hr'),
                'position' => config('seeder.demo_users.hr02.position', 'Administrative Assistant'),
                'classification' => 'Contract of Service',
                'date_hired' => '2022-01-15',
                'division' => config('seeder.demo_users.hr02.division', 'Finance and Administrative Division'),
                'subdivision' => null,
                'section' => config('seeder.demo_users.hr02.section', 'Human Resource Management Section'),
                'status' => 'active',
            ],
            [
                'user_id' => 'admin01',
                'name' => config('seeder.demo_users.admin01.name', 'IT Officer'),
                'email' => config('seeder.demo_users.admin01.email', 'admin01@hris.local'),
                'password' => config('seeder.demo_users.admin01.password', 'password'),
                'role' => config('seeder.demo_users.admin01.role', 'admin'),
                'position' => config('seeder.demo_users.admin01.position', 'ICT Officer I'),
                'classification' => 'Permanent',
                'date_hired' => '2018-11-20',
                'division' => config('seeder.demo_users.admin01.division', 'Chief of Hospital Offices Division'),
                'subdivision' => null,
                'section' => config('seeder.demo_users.admin01.section', 'Information and Communications Technology Unit'),
                'status' => 'active',
            ],
        ];

        foreach ($employees as $employeeData) {
            // Create user first
            $user = User::firstOrCreate(
                ['email' => $employeeData['email']],
                [
                    'user_id' => $employeeData['user_id'],
                    'name' => $employeeData['name'],
                    'password' => Hash::make($employeeData['password']),
                    'role' => $employeeData['role'],
                    'is_active' => true,
                ]
            );

            // Split name
            $nameParts = explode(' ', $employeeData['name'], 2);

            // Create employee linked to user (using user_id as lookup)
            Employee::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'first_name' => $nameParts[0],
                    'middle_name' => '',
                    'last_name' => $nameParts[1] ?? '',
                    'name_extension' => null,
                    'email' => $employeeData['email'],
                    'position' => $employeeData['position'],
                    'classification' => $employeeData['classification'],
                    'date_hired' => $employeeData['date_hired'],
                    'division' => $employeeData['division'],
                    'subdivision' => $employeeData['subdivision'],
                    'section' => $employeeData['section'],
                    'status' => $employeeData['status'],
                ]
            );
        }

        $this->command->info('Created '.count($employees).' employees with linked users.');
    }
}
