<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Classes;
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

        // Teacher user 1
        $teacher1 = User::create([
            'firstName' => 'Carl Edwin',
            'middleName' => 'Vasquez',
            'lastName' => 'Conde',
            'email' => 'conde@gmail.com',
            'password' => Hash::make('@Conde123'),
            'role' => 'teacher',
            'gender' => 'male',
            'phone' => '09171234567',
            'municipality_city' => 'Nabua',
            'province' => 'Camarines Sur',
            'country' => 'Philippines',
        ]);

        // Assign teacher 1 to class (Kindergarten - A)
        $class1 = Classes::firstOrCreate([
            'grade_level' => 'kindergarten',
            'section' => 'A',
        ]);
        $teacher1->classes()->attach($class1->id);

        // Teacher user 2
        $teacher2 = User::create([
            'firstName' => 'Jison',
            'middleName' => 'Santos',
            'lastName' => 'Titum',
            'email' => 'titum@gmail.com',
            'password' => Hash::make('@Titum123'),
            'role' => 'teacher',
            'gender' => 'male',
            'phone' => '09179876543',
            'municipality_city' => 'Makati',
            'province' => 'Metro Manila',
            'country' => 'Philippines',
        ]);

        // Assign teacher 2 to class (Grade 1 - A)
        $class2 = Classes::firstOrCreate([
            'grade_level' => 'grade1',
            'section' => 'A',
        ]);
        $teacher2->classes()->attach($class2->id);
    }
}
