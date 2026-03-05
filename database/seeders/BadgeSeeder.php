<?php

namespace Database\Seeders;

use App\Models\Badge;
use Illuminate\Database\Seeder;

class BadgeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $badges = [
            [
                'name' => '1 Year Anniversary',
                'description' => 'Awarded for completing 1 year of service with the company.',
                'icon' => 'Award',
                'bg_color' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                'criteria_type' => 'tenure_1_year',
                'is_active' => true,
            ],
            [
                'name' => '3 Year Milestone',
                'description' => 'Awarded for completing 3 years of service.',
                'icon' => 'Medal',
                'bg_color' => 'bg-blue-100 text-blue-800 border-blue-200',
                'criteria_type' => 'tenure_3_years',
                'is_active' => true,
            ],
            [
                'name' => '5 Year Veteran',
                'description' => 'Awarded for completing 5 years of service.',
                'icon' => 'Star',
                'bg_color' => 'bg-purple-100 text-purple-800 border-purple-200',
                'criteria_type' => 'tenure_5_years',
                'is_active' => true,
            ],
            [
                'name' => '10 Year Legend',
                'description' => 'Awarded for completing 10 incredible years of service!',
                'icon' => 'Crown',
                'bg_color' => 'bg-amber-100 text-amber-800 border-amber-300',
                'criteria_type' => 'tenure_10_years',
                'is_active' => true,
            ],
            [
                'name' => 'Profile All-Star',
                'description' => 'Awarded for submitting a complete and approved Personal Data Sheet.',
                'icon' => 'CheckCircle',
                'bg_color' => 'bg-indigo-100 text-indigo-800 border-indigo-200',
                'criteria_type' => 'profile_100_percent',
                'is_active' => true,
            ],
        ];

        foreach ($badges as $badge) {
            Badge::updateOrCreate(
                ['criteria_type' => $badge['criteria_type']],
                $badge
            );
        }
    }
}
