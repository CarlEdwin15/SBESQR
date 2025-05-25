<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // First admin user
        User::create([
            'firstName' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
        ]);

        // Second admin user
        User::create([
            'firstName' => 'Admin 2',
            'email' => 'admin2@gmail.com',
            'password' => Hash::make('admin2123'),
            'role' => 'admin',
        ]);
    }
}
