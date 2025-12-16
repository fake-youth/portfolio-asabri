<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create Super Admin
        User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@asabri.com',
            'password' => Hash::make('password123'),
            'role' => 'superadmin',
        ]);

        // Create Admin
        User::create([
            'name' => 'Admin ASABRI',
            'email' => 'admin@asabri.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        // Create User
        User::create([
            'name' => 'User Demo',
            'email' => 'user@asabri.com',
            'password' => Hash::make('password123'),
            'role' => 'user',
        ]);

        // Call other seeders
        $this->call([
            DocumentCategorySeeder::class,
        ]);
    }
}