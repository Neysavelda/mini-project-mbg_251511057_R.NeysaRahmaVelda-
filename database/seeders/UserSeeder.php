<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $password = Hash::make('Mbg#Demo2026');

        $accounts = [
            ['name' => 'Bu Rina',  'username' => 'rina', 'role' => User::ROLE_PELAPOR],
            ['name' => 'Pak Andi', 'username' => 'andi', 'role' => User::ROLE_PELAPOR],
            ['name' => 'Pak Dedi', 'username' => 'dedi', 'role' => User::ROLE_PEMANTAU],
            ['name' => 'Bu Sari',  'username' => 'sari', 'role' => User::ROLE_KOORDINATOR],
        ];

        foreach ($accounts as $account) {
            $user = User::firstOrNew(['username' => $account['username']]);
            $user->forceFill([...$account, 'password' => $password])->save();
        }
    }
}
