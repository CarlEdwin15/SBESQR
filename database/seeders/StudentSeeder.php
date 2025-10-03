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

        $students = [
            'kindergarten' => [
                ['Aiden', 'Carter', 'male'],
                ['Sophia', 'Nguyen', 'female'],
                ['Liam', 'Johnson', 'male'],
                ['Isabella', 'Rossi', 'female'],
                ['Noah', 'Patel', 'male'],
                ['Mia', 'Hernández', 'female'],
                ['Ethan', 'Kim', 'male'],
                ['Olivia', 'Wright', 'female'],
                ['Lucas', 'Silva', 'male'],
                ['Emma', 'Kowalski', 'female'],
                ['Benjamin', 'Brown', 'male'],
                ['Aria', 'Singh', 'female'],
                ['Mason', 'Chen', 'male'],
                ['Chloe', 'Anderson', 'female'],
                ['Elijah', 'Rivera', 'male'],
                ['Layla', 'O’Connor', 'female'],
                ['Alexander', 'Ivanov', 'male'],
                ['Harper', 'Lee', 'female'],
                ['James', 'Walker', 'male'],
                ['Ava', 'Martinez', 'female'],
            ],
            'grade1' => [
                ['Daniel', 'Ali', 'male'],
                ['Amelia', 'Thompson', 'female'],
                ['Matthew', 'Park', 'male'],
                ['Zoe', 'Garcia', 'female'],
                ['Henry', 'Müller', 'male'],
                ['Lily', 'Zhang', 'female'],
                ['Samuel', 'Clark', 'male'],
                ['Victoria', 'Adams', 'female'],
                ['Joseph', 'Davis', 'male'],
                ['Grace', 'Taylor', 'female'],
                ['David', 'Lewis', 'male'],
                ['Natalie', 'Scott', 'female'],
                ['Andrew', 'Robinson', 'male'],
                ['Hannah', 'Evans', 'female'],
                ['Gabriel', 'King', 'male'],
                ['Stella', 'Brooks', 'female'],
                ['Isaac', 'Baker', 'male'],
                ['Aurora', 'Torres', 'female'],
                ['Logan', 'Hill', 'male'],
                ['Ruby', 'Collins', 'female'],
            ],
            'grade2' => [
                ['Carter', 'Hughes', 'male'],
                ['Scarlett', 'Murphy', 'female'],
                ['Julian', 'Flores', 'male'],
                ['Penelope', 'Bell', 'female'],
                ['Owen', 'Morgan', 'male'],
                ['Eleanor', 'Reed', 'female'],
                ['Wyatt', 'Turner', 'male'],
                ['Nora', 'Phillips', 'female'],
                ['Nathan', 'Green', 'male'],
                ['Hazel', 'Foster', 'female'],
                ['Leo', 'Cooper', 'male'],
                ['Addison', 'Ward', 'female'],
                ['Dylan', 'Rivera', 'male'],
                ['Elena', 'Russell', 'female'],
                ['Caleb', 'Diaz', 'male'],
                ['Zoey', 'Stewart', 'female'],
                ['Jack', 'Hall', 'male'],
                ['Madison', 'Price', 'female'],
                ['Ryan', 'Hughes', 'male'],
                ['Violet', 'Cox', 'female'],
            ],
            'grade3' => [
                ['Joshua', 'Edwards', 'male'],
                ['Emilia', 'Perry', 'female'],
                ['Anthony', 'Sanders', 'male'],
                ['Paisley', 'Gray', 'female'],
                ['Christian', 'Barnes', 'male'],
                ['Maya', 'Butler', 'female'],
                ['Charles', 'Long', 'male'],
                ['Savannah', 'Ross', 'female'],
                ['Thomas', 'Mitchell', 'male'],
                ['Bella', 'Simmons', 'female'],
                ['Ezra', 'Henderson', 'male'],
                ['Leah', 'Kelly', 'female'],
                ['Adrian', 'Gonzales', 'male'],
                ['Lucy', 'Powell', 'female'],
                ['Christopher', 'James', 'male'],
                ['Camila', 'Howard', 'female'],
                ['Miles', 'Peterson', 'male'],
                ['Alice', 'Ward', 'female'],
                ['Nicholas', 'Hayes', 'male'],
                ['Autumn', 'Bryant', 'female'],
            ],
            'grade4' => [
                ['Jonathan', 'Wood', 'male'],
                ['Lydia', 'Rivera', 'female'],
                ['Adam', 'Lopez', 'male'],
                ['Mila', 'Foster', 'female'],
                ['Asher', 'Brooks', 'male'],
                ['Iris', 'Coleman', 'female'],
                ['Zachary', 'Bennett', 'male'],
                ['Claire', 'Simmons', 'female'],
                ['Nathaniel', 'Cook', 'male'],
                ['Naomi', 'Patterson', 'female'],
                ['Dominic', 'Sanders', 'male'],
                ['Delilah', 'Barnes', 'female'],
                ['Evan', 'Murphy', 'male'],
                ['Isla', 'Jenkins', 'female'],
                ['Aaron', 'Russell', 'male'],
                ['Eliana', 'Fisher', 'female'],
                ['Jason', 'Myers', 'male'],
                ['Eva', 'Stone', 'female'],
                ['Tyler', 'Hunt', 'male'],
                ['Serenity', 'Ford', 'female'],
            ],
            'grade5' => [
                ['Brandon', 'Cox', 'male'],
                ['Willow', 'Chapman', 'female'],
                ['Jordan', 'Fox', 'male'],
                ['Luna', 'Mills', 'female'],
                ['Gavin', 'Andrews', 'male'],
                ['Daisy', 'Arnold', 'female'],
                ['Cole', 'Hart', 'male'],
                ['Sadie', 'Elliott', 'female'],
                ['Ian', 'Riley', 'male'],
                ['Peyton', 'West', 'female'],
                ['Vincent', 'Dean', 'male'],
                ['Nova', 'Lawrence', 'female'],
                ['Luis', 'Chavez', 'male'],
                ['Madeline', 'Douglas', 'female'],
                ['Diego', 'Ortiz', 'male'],
                ['Quinn', 'Fleming', 'female'],
                ['Blake', 'Carr', 'male'],
                ['Gianna', 'Lowe', 'female'],
                ['Sean', 'Walters', 'male'],
                ['Everly', 'Barrett', 'female'],
            ],
            'grade6' => [
                ['Kyle', 'Gordon', 'male'],
                ['Aurora', 'Spencer', 'female'],
                ['Marcus', 'Stephens', 'male'],
                ['Ivy', 'Holland', 'female'],
                ['Eric', 'Knight', 'male'],
                ['Ayla', 'Matthews', 'female'],
                ['Patrick', 'Elliott', 'male'],
                ['Eden', 'Armstrong', 'female'],
                ['George', 'Foster', 'male'],
                ['Skylar', 'Doyle', 'female'],
                ['Raymond', 'Shaw', 'male'],
                ['Freya', 'Bowman', 'female'],
                ['Dean', 'Hopkins', 'male'],
                ['Jasmine', 'Barker', 'female'],
                ['Felix', 'Shepherd', 'male'],
                ['Sienna', 'Cross', 'female'],
                ['Peter', 'Lambert', 'male'],
                ['Elise', 'Grant', 'female'],
                ['Hugo', 'Pearson', 'male'],
                ['Dahlia', 'Baldwin', 'female'],
            ],
        ];

        // Insert students
        foreach ($students as $gradeIndex => $studentList) {
            foreach ($studentList as $i => [$fname, $lname, $sex]) {
                $lrn = (string) ($baseLRN + (array_search($gradeIndex, array_keys($students)) * 100) + $i);

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
                    'student_fName' => $fname,
                    'student_mName' => 'M.',
                    'student_lName' => $lname,
                    'student_extName' => null,
                    'student_dob' => now()->subYears(5 + array_search($gradeIndex, array_keys($students)))->format('Y-m-d'),
                    'student_sex' => $sex,
                    'student_photo' => null,
                    'qr_code' => Str::uuid(),
                    'address_id' => $address->id,
                ]);

                DB::table('class_student')->insert([
                    'student_id' => $student->id,
                    'class_id' => $classes[$gradeIndex]->id,
                    'school_year_id' => $schoolYear->id,
                    'enrollment_status' => 'enrolled',
                ]);
            }
        }
    }
}
