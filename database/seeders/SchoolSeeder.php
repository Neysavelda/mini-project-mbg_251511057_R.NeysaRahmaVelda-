<?php

namespace Database\Seeders;

use App\Models\School;
use Illuminate\Database\Seeder;

class SchoolSeeder extends Seeder
{
    public function run(): void
    {
        $schools = [
            ['name' => 'SMP Harapan',       'district' => 'Kecamatan Utara'],
            ['name' => 'SMP Pelita Bangsa', 'district' => 'Kecamatan Selatan'],
            ['name' => 'SD Mekar Sari',     'district' => 'Kecamatan Timur'],
            ['name' => 'SMA Nusantara 1',   'district' => 'Kecamatan Utara'],
        ];

        foreach ($schools as $school) {
            School::firstOrCreate(
                ['name' => $school['name']],
                ['district' => $school['district']]
            );
        }
    }
}