<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Akun Koordinator (Ibu Sita)
        User::create([
            'name' => 'Ibu Sita (Koordinator)',
            'username' => 'sita',
            'password' => Hash::make('Mbg#Demo2026'),
            'role' => 'koordinator',
            'school_id' => null,
        ]);

        // 2. Akun Pemantau Komunitas (Pak Dedi)
        User::create([
            'name' => 'Pak Dedi (Pemantau)',
            'username' => 'dedi',
            'password' => Hash::make('Mbg#Demo2026'),
            'role' => 'pemantau',
            'school_id' => 1, // Terikat dengan SMP Harapan
        ]);

        // 3. Akun Pelapor (Bu Rina)
        User::create([
            'name' => 'Bu Rina (Pelapor)',
            'username' => 'rina',
            'password' => Hash::make('Mbg#Demo2026'),
            'role' => 'pelapor',
            'school_id' => null,
        ]);

        // 4. Akun Pelapor 2 (Pak Andi)
        User::create([
            'name' => 'Pak Andi (Pelapor)',
            'username' => 'andi',
            'password' => Hash::make('Mbg#Demo2026'),
            'role' => 'pelapor',
            'school_id' => null,
        ]);
    }
}