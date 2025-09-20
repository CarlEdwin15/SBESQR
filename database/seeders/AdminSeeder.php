<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Classes;
use App\Models\SchoolYear;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {

        // Admin accounts
        User::create([
            'firstName' => 'Admin',
            'email' => 'sbesqr@gmail.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'status' => 'active',
        ]);

        User::create([
            'firstName' => 'Admin 2',
            'email' => 'conde@gmail.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
        ]);

        // Create or retrieve school year
        $schoolYear = SchoolYear::firstOrCreate([
            'school_year' => '2024-2025',
        ]);

        // Teacher 1
        $teacher1 = User::create([
            'firstName' => 'Carl Edwin',
            'middleName' => 'Vasquez',
            'lastName' => 'Conde',
            'email' => 'carledwinconde@gmail.com',
            'password' => Hash::make('@Conde123'),
            'role' => 'teacher',
            'gender' => 'male',
            'phone' => '09171234567',
            'municipality_city' => 'Nabua',
            'province' => 'Camarines Sur',
            'country' => 'Philippines',
        ]);

        $class1 = Classes::firstOrCreate([
            'grade_level' => 'kindergarten',
            'section' => 'A',
        ]);

        $teacher1->classes()->attach($class1->id, [
            'role' => 'adviser',
            'school_year_id' => $schoolYear->id,
        ]);


        // Teacher 2
        $teacher2 = User::create([
            'firstName' => 'Jison',
            'middleName' => 'Santos',
            'lastName' => 'Titum',
            'email' => 'piggypogi09@gmail.com',
            'password' => Hash::make('@Pogi123'),
            'role' => 'teacher',
            'gender' => 'male',
            'phone' => '09179876543',
            'municipality_city' => 'Makati',
            'province' => 'Metro Manila',
            'country' => 'Philippines',
        ]);

        $class2 = Classes::firstOrCreate([
            'grade_level' => 'grade1',
            'section' => 'A',
        ]);

        $teacher2->classes()->attach($class2->id, [
            'role' => 'adviser',
            'school_year_id' => $schoolYear->id,
        ]);
    }
}
