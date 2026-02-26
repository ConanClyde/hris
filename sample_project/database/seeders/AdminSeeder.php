<?php

namespace Database\Seeders;

use App\Features\Employees\Models\Employee;
use App\Features\Users\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $admins = [
            [
                'user_id' => 'admin',
                'name' => config('seeder.admin.name'),
                'email' => config('seeder.admin.email'),
                'password' => Hash::make(config('seeder.admin.password')),
                'role' => config('seeder.admin.role'),
                'position' => config('seeder.admin.position'),
                'division' => config('seeder.admin.division'),
                'section' => config('seeder.admin.section'),
            ],
            [
                'user_id' => 'hr_manager',
                'name' => config('seeder.hr_manager.name'),
                'email' => config('seeder.hr_manager.email'),
                'password' => Hash::make(config('seeder.hr_manager.password')),
                'role' => config('seeder.hr_manager.role'),
                'position' => config('seeder.hr_manager.position'),
                'division' => config('seeder.hr_manager.division'),
                'section' => config('seeder.hr_manager.section'),
            ],
            [
                'user_id' => 'hr_staff',
                'name' => config('seeder.hr_staff.name'),
                'email' => config('seeder.hr_staff.email'),
                'password' => Hash::make(config('seeder.hr_staff.password')),
                'role' => config('seeder.hr_staff.role'),
                'position' => config('seeder.hr_staff.position'),
                'division' => config('seeder.hr_staff.division'),
                'section' => config('seeder.hr_staff.section'),
            ],
            [
                'user_id' => 'employee',
                'name' => config('seeder.employee.name'),
                'email' => config('seeder.employee.email'),
                'password' => Hash::make(config('seeder.employee.password')),
                'role' => config('seeder.employee.role'),
                'position' => config('seeder.employee.position'),
                'division' => config('seeder.employee.division'),
                'subdivision' => config('seeder.employee.subdivision'),
                'section' => config('seeder.employee.section'),
            ],
        ];

        foreach ($admins as $adminData) {
            // Create or update user
            $user = User::firstOrCreate(
                ['email' => $adminData['email']],
                [
                    'user_id' => $adminData['user_id'],
                    'name' => $adminData['name'],
                    'password' => $adminData['password'],
                    'role' => $adminData['role'],
                    'is_active' => true,
                ]
            );

            // Create linked employee record if position exists
            if (! empty($adminData['position'])) {
                $nameParts = explode(' ', $adminData['name'], 2);

                Employee::firstOrCreate(
                    ['user_id' => $user->id],
                    [
                        'first_name' => $nameParts[0],
                        'last_name' => $nameParts[1] ?? '',
                        'email' => $adminData['email'],
                        'position' => $adminData['position'] ?? null,
                        'division' => $adminData['division'] ?? null,
                        'subdivision' => $adminData['subdivision'] ?? null,
                        'section' => $adminData['section'] ?? null,
                        'status' => 'active',
                    ]
                );
            }
        }

        $this->command->info('Created '.count($admins).' admin/HR/employee users with linked employees.');
        $this->command->info('Login credentials (use User ID):');
        $this->command->info('  Admin: admin / [hidden]');
        $this->command->info('  HR: hr_manager / [hidden]');
        $this->command->info('  Employee: employee / [hidden]');
    }
}
