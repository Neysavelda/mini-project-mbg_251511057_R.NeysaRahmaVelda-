<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Akun Koordinator (Ibu Sita) - Nama dirapikan
        User::create([
            'name' => 'Ibu Sita',
            'username' => 'sita',
            'password' => Hash::make('Mbg#Demo2026'),
            'role' => 'koordinator',
            'school_id' => null,
        ]);

        // 2. Akun Pemantau Komunitas (Pak Dedi)
        User::create([
            'name' => 'Pak Dedi',
            'username' => 'dedi',
            'password' => Hash::make('Mbg#Demo2026'),
            'role' => 'pemantau',
            'school_id' => 1,
        ]);

        // 3. Akun Pelapor (Bu Rina)
        User::create([
            'name' => 'Bu Rina',
            'username' => 'rina',
            'password' => Hash::make('Mbg#Demo2026'),
            'role' => 'pelapor',
            'school_id' => null,
        ]);

        // 4. Akun Pelapor 2 (Pak Andi)
        User::create([
            'name' => 'Pak Andi',
            'username' => 'andi',
            'password' => Hash::make('Mbg#Demo2026'),
            'role' => 'pelapor',
            'school_id' => null,
        ]);
    }
}