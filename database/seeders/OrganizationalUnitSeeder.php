<?php

namespace Database\Seeders;

use App\Features\Employees\Models\Division;
use App\Features\Employees\Models\Section;
use App\Features\Employees\Models\Subdivision;
use Illuminate\Database\Seeder;

class OrganizationalUnitSeeder extends Seeder
{
    public function run(): void
    {
        $structure = [
            'Chief of Hospital Offices Division' => [
                'subdivisions' => [],
                'sections' => [
                    'Legal Unit',
                    'Planning Unit',
                    'Information and Communications Technology Unit',
                ],
            ],
            'Treatment and Rehabilitation Division' => [
                'subdivisions' => [
                    'Non-Residential Treatment & Rehabilitation' => [
                        'Medical Section',
                        'Nursing Section',
                    ],
                    'Residential Treatment & Rehabilitation' => [
                        'Medical Section',
                        'Nursing Section',
                    ],
                ],
                'sections' => [],
            ],
            'Finance and Administrative Division' => [
                'subdivisions' => [],
                'sections' => [
                    'Human Resource Management Section',
                    'Procurement Section',
                    'Accounting Section',
                ],
            ],
        ];

        foreach ($structure as $divisionName => $divData) {
            $division = Division::firstOrCreate(['name' => $divisionName]);

            if (! empty($divData['subdivisions'])) {
                foreach ($divData['subdivisions'] as $subdivisionName => $sections) {
                    $subdivision = Subdivision::firstOrCreate([
                        'division_id' => $division->id,
                        'name' => $subdivisionName,
                    ]);
                    foreach ($sections as $sectionName) {
                        Section::firstOrCreate([
                            'division_id' => $division->id,
                            'subdivision_id' => $subdivision->id,
                            'name' => $sectionName,
                        ]);
                    }
                }
            }

            if (! empty($divData['sections'])) {
                foreach ($divData['sections'] as $sectionName) {
                    Section::firstOrCreate([
                        'division_id' => $division->id,
                        'subdivision_id' => null,
                        'name' => $sectionName,
                    ]);
                }
            }
        }
    }
}
