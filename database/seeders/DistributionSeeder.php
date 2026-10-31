<?php

namespace Database\Seeders;

use App\Models\MealDistribution;
use App\Models\School;
use Illuminate\Database\Seeder;

class DistributionSeeder extends Seeder
{
    public function run(): void
    {
        $menus = [
            'Nasi, ayam suwir, sayur sop, pisang',
            'Nasi, telur balado, tumis kangkung, jeruk',
            'Nasi, tahu tempe, sayur lodeh, semangka',
        ];

        foreach (School::all() as $school) {
            foreach ([2, 5, 9] as $i => $daysAgo) {
                MealDistribution::firstOrCreate(
                    [
                        'school_id' => $school->id,
                        'distribution_date' => now()->subDays($daysAgo)->toDateString(),
                    ],
                    [
                        'menu_description' => $menus[$i],
                        'portion_count' => 150 + ($i * 25),
                    ]
                );
            }
        }
    }
}