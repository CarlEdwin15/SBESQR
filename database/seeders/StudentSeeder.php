<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\{
    Student,
    StudentAddress,
    Classes,
    SchoolYear,
    Subject,
    ClassSubject,
    QuarterlyGrade,
    FinalSubjectGrade,
    GeneralAverage,
    Quarter
};
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

        // ✅ Create classes per grade
        $classes = [];
        foreach ($gradeLevels as $level) {
            $classes[$level] = Classes::firstOrCreate([
                'grade_level' => $level,
                'section' => 'A',
            ]);
        }

        // ✅ Define subjects per grade
        $subjectSets = [
            'kindergarten' => ['Reading Readiness', 'Numbers', 'Music', 'Arts', 'Good Manners'],
            'grade1' => ['English', 'Math', 'Filipino', 'Araling Panlipunan', 'Science'],
            'grade2' => ['English', 'Math', 'Filipino', 'Science', 'MAPEH'],
            'grade3' => ['English', 'Math', 'Filipino', 'Science', 'HELE'],
            'grade4' => ['English', 'Math', 'Filipino', 'Science', 'Araling Panlipunan', 'Edukasyon sa Pagpapakatao'],
            'grade5' => ['English', 'Math', 'Filipino', 'Science', 'MAPEH', 'TLE'],
            'grade6' => ['English', 'Math', 'Filipino', 'Science', 'MAPEH', 'EPP', 'Araling Panlipunan'],
        ];

        // ✅ Create subjects and link to each class
        $classSubjects = [];
        foreach ($subjectSets as $gradeLevel => $subjectNames) {
            $class = $classes[$gradeLevel];
            foreach ($subjectNames as $subjectName) {
                $subject = Subject::firstOrCreate(['name' => $subjectName]);
                $classSubject = ClassSubject::firstOrCreate([
                    'class_id' => $class->id,
                    'subject_id' => $subject->id,
                    'school_year_id' => $schoolYear->id,
                ]);
                $classSubjects[$gradeLevel][] = $classSubject;
            }
        }

        $baseLRN = 112828080000;

        // ✅ Students per grade (same as before)
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
                ['Amelia', 'Thompson', 'female'],
                ['Aria', 'Singh', 'female'],
                ['Mason', 'Chen', 'male'],
                ['Daniel', 'Ali', 'male'],
                ['Elijah', 'Rivera', 'male'],
                ['Layla', 'O’Connor', 'female'],
                ['Alexander', 'Ivanov', 'male'],
                ['Harper', 'Lee', 'female'],
                ['James', 'Walker', 'male'],
                ['Ava', 'Martinez', 'female'],
            ],
            'grade1' => [

                ['Anderson', 'Chloe', 'female'],
                ['Benjamin', 'Brown', 'male'],
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
            // ... (grade2 to grade6 remains exactly as your current file)
            // 👇 For brevity, no need to rewrite; keep them exactly the same
            // 'grade2' => [...],
            // 'grade3' => [...],
            // 'grade4' => [...],
            // 'grade5' => [...],
            // 'grade6' => [...],
        ];

        // ✅ Insert students + sample grades
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

                // Enroll student to class
                $class = $classes[$gradeIndex];
                DB::table('class_student')->insert([
                    'student_id' => $student->id,
                    'class_id' => $class->id,
                    'school_year_id' => $schoolYear->id,
                    'enrollment_status' => 'enrolled',
                ]);

                // ✅ Generate random grades for each subject
                $totalFinal = 0;
                $subjectCount = count($classSubjects[$gradeIndex]);

                foreach ($classSubjects[$gradeIndex] as $classSubject) {
                    // For each subject, generate 4 quarter grades
                    $quarters = Quarter::where('class_subject_id', $classSubject->id)->get();

                    $quarterGrades = [];
                    foreach ($quarters as $quarter) {
                        $grade = rand(80, 99);
                        $quarterGrades[] = $grade;

                        QuarterlyGrade::create([
                            'student_id' => $student->id,
                            'quarter_id' => $quarter->id,
                            'final_grade' => $grade,
                        ]);
                    }

                    // Average for subject
                    $final = round(array_sum($quarterGrades) / count($quarterGrades));
                    $totalFinal += $final;

                    FinalSubjectGrade::create([
                        'student_id' => $student->id,
                        'class_subject_id' => $classSubject->id,
                        'final_grade' => $final,
                        'remarks' => $final >= 75 ? 'Passed' : 'Failed',
                    ]);
                }

                // ✅ General average per student
                $genAve = round($totalFinal / $subjectCount);
                GeneralAverage::create([
                    'student_id' => $student->id,
                    'school_year_id' => $schoolYear->id,
                    'general_average' => $genAve,
                    'remarks' => $genAve >= 75 ? 'Passed' : 'Failed',
                ]);
            }
        }
    }
}
