<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{

    public function run(): void
    {
        // Usuario administrador
        User::create([
            'name' => 'Administrador',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin'
        ]);

        // Usuario normales
        User::create([
            'name' => 'Usuario 1',
            'email' => 'user1@example.com',
            'password' => Hash::make('password'),
            'role' => 'user'
        ]);

    }
}
