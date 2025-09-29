<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Student;
use App\Models\StudentAddress;
use App\Models\Classes;
use App\Models\SchoolYear;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class StudentSeeder extends Seeder
{
    public function run(): void
    {
        $schoolYear = SchoolYear::where('school_year', '2024-2025')->firstOrFail();

        $gradeLevels = [
            'kindergarten',
            'grade1',
            'grade2',
            'grade3',
            'grade4',
            'grade5',
            'grade6'
        ];

        $classes = [];
        foreach ($gradeLevels as $level) {
            $classes[$level] = Classes::firstOrCreate([
                'grade_level' => $level,
                'section' => 'A',
            ]);
        }

        $baseLRN = 112828080000;

        // Expanded name pools (20+ each)
        $maleNames = [
            'Liam',
            'Noah',
            'James',
            'Lucas',
            'Benjamin',
            'Elijah',
            'Logan',
            'Mason',
            'Ethan',
            'Alexander',
            'Daniel',
            'Matthew',
            'Jacob',
            'Michael',
            'William',
            'Henry',
            'Sebastian',
            'Jack',
            'Owen',
            'Samuel'
        ];
        $femaleNames = [
            'Emma',
            'Olivia',
            'Ava',
            'Isabella',
            'Sophia',
            'Charlotte',
            'Amelia',
            'Mia',
            'Harper',
            'Evelyn',
            'Abigail',
            'Ella',
            'Elizabeth',
            'Camila',
            'Luna',
            'Scarlett',
            'Victoria',
            'Grace',
            'Chloe',
            'Penelope'
        ];
        $lastNames = [
            'Garcia',
            'Santos',
            'Reyes',
            'Cruz',
            'Torres',
            'Lopez',
            'Dela Cruz',
            'Gomez',
            'Domingo',
            'Morales',
            'Navarro',
            'Castillo',
            'Ramos',
            'Mendoza',
            'Flores',
            'Rivera',
            'Aguilar',
            'Villanueva',
            'Bautista',
            'Fernandez'
        ];

        foreach ($gradeLevels as $gradeIndex => $gradeLevel) {
            for ($i = 0; $i < 20; $i++) {
                $isMale = $i < 10; // First 10 boys, next 10 girls

                $firstName = $isMale
                    ? $maleNames[array_rand($maleNames)]
                    : $femaleNames[array_rand($femaleNames)];

                $lastName = $lastNames[array_rand($lastNames)];

                $lrn = (string) ($baseLRN + ($gradeIndex * 100) + $i);

                $address = StudentAddress::create([
                    'house_no' => rand(100, 999),
                    'street_name' => 'Main St',
                    'barangay' => 'Barangay Uno',
                    'municipality_city' => 'Sample City',
                    'province' => 'Sample Province',
                    'zip_code' => '1000',
                    'country' => 'Philippines',
                    'pob' => 'Sample City',
                ]);

                $student = Student::create([
                    'student_lrn' => $lrn,
                    'student_fName' => $firstName,
                    'student_mName' => 'M.',
                    'student_lName' => $lastName,
                    'student_extName' => null,
                    'student_dob' => now()->subYears(5 + $gradeIndex)->format('Y-m-d'),
                    'student_sex' => $isMale ? 'male' : 'female',
                    'student_photo' => null,
                    'qr_code' => Str::uuid(),
                    'address_id' => $address->id,
                ]);

                DB::table('class_student')->insert([
                    'student_id' => $student->id,
                    'class_id' => $classes[$gradeLevel]->id,
                    'school_year_id' => $schoolYear->id,
                    'enrollment_status' => 'enrolled',
                ]);
            }
        }
    }
}
