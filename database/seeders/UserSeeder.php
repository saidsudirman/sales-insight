<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Admin MIYA',
            'email' => 'admin@miya.test',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Pemilik MIYA',
            'email' => 'pemilik@miya.test',
            'password' => Hash::make('password'),
            'role' => 'pemilik',
        ]);
    }
}