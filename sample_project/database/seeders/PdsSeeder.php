<?php

namespace Database\Seeders;

use App\Features\Employees\Models\Employee;
use App\Features\Pds\Models\Pds;
use App\Features\Pds\Models\PdsPersonal;
use Illuminate\Database\Seeder;

class PdsSeeder extends Seeder
{
    public function run(): void
    {
        $employees = Employee::all();

        foreach ($employees as $employee) {
            // Create PDS record
            $pds = Pds::firstOrCreate(
                ['employee_id' => $employee->id],
                [
                    'status' => 'approved',
                    'submitted_at' => now()->subMonths(3),
                    'reviewed_at' => now()->subMonths(3)->addDays(5),
                ]
            );

            // Create PDS Personal data
            PdsPersonal::firstOrCreate(
                ['pds_id' => $pds->id],
                [
                    'surname' => $employee->last_name,
                    'first_name' => $employee->first_name,
                    'middle_name' => $employee->middle_name,
                    'name_extension' => $employee->name_extension,
                    'dob' => fake()->dateTimeBetween('-50 years', '-25 years')->format('Y-m-d'),
                    'place_of_birth' => fake()->city().', Philippines',
                    'sex' => fake()->randomElement(['male', 'female']),
                    'civil_status' => fake()->randomElement(['single', 'married', 'widowed']),
                    'height' => fake()->randomFloat(2, 1.50, 1.90),
                    'weight' => fake()->randomFloat(2, 45, 90),
                    'blood_type' => fake()->randomElement(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-']),
                    'citizenship_type' => 'Filipino',
                    'citizenship_nature' => null,
                    'citizenship_country' => null,
                    'phone' => fake()->phoneNumber(),
                    'mobile' => fake()->phoneNumber(),
                    'email' => $employee->email,
                    'cs_id' => fake()->numerify('CS-#########'),
                    'agency_employee_no' => str_pad((string) $employee->id, 6, '0', STR_PAD_LEFT),
                    'gsis' => fake()->numerify('###########'),
                    'pag_ibig' => fake()->numerify('############'),
                    'philhealth' => fake()->numerify('############'),
                    'sss' => fake()->numerify('###########'),
                    'tin' => fake()->numerify('###-###-###-###'),
                    'residential_address' => [
                        'house_block_lot' => fake()->buildingNumber(),
                        'street' => fake()->streetName(),
                        'subdivision' => fake()->optional()->streetSuffix(),
                        'barangay' => 'Barangay '.fake()->numberBetween(1, 200),
                        'city_municipality' => fake()->city(),
                        'province' => fake()->state(),
                        'zip_code' => fake()->postcode(),
                    ],
                    'permanent_address' => [
                        'house_block_lot' => fake()->buildingNumber(),
                        'street' => fake()->streetName(),
                        'subdivision' => fake()->optional()->streetSuffix(),
                        'barangay' => 'Barangay '.fake()->numberBetween(1, 200),
                        'city_municipality' => fake()->city(),
                        'province' => fake()->state(),
                        'zip_code' => fake()->postcode(),
                    ],
                ]
            );
        }

        $this->command->info('Created PDS records for '.$employees->count().' employees.');
    }
}
