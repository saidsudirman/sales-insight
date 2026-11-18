<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Admin MIYA',
                'password' => Hash::make('12341234'),
                'role' => 'admin',
            ]
        );

        User::updateOrCreate(
            ['email' => 'pemilik@gmail.com'],
            [
                'name' => 'Pemilik MIYA',
                'password' => Hash::make('12341234'),
                'role' => 'pemilik',
            ]
        );
    }
}
