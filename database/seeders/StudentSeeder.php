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

        // Create classes per grade
        $classes = [];
        foreach ($gradeLevels as $level) {
            $classes[$level] = Classes::firstOrCreate([
                'grade_level' => $level,
                'section' => 'A',
            ]);
        }

        // Define subjects per grade
        $subjectSets = [
            'kindergarten' => ['Reading Readiness', 'Numbers', 'Music', 'Arts', 'Good Manners'],
            'grade1' => ['English', 'Math', 'Filipino', 'Araling Panlipunan', 'Science'],
            'grade2' => ['English', 'Math', 'Filipino', 'Science', 'MAPEH'],
            'grade3' => ['English', 'Math', 'Filipino', 'Science', 'HELE'],
            'grade4' => ['English', 'Math', 'Filipino', 'Science', 'Araling Panlipunan', 'Edukasyon sa Pagpapakatao'],
            'grade5' => ['English', 'Math', 'Filipino', 'Science', 'MAPEH', 'TLE'],
            'grade6' => ['English', 'Math', 'Filipino', 'Science', 'MAPEH', 'EPP', 'Araling Panlipunan'],
        ];

        // Create subjects and link to each class
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

        // Students per grade - UPDATED to include more Grade 6 students
        $students = [
            'grade6' => [
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
                ['Layla', 'O\'Connor', 'female'],
                ['Alexander', 'Ivanov', 'male'],
                ['Harper', 'Lee', 'female'],
                ['James', 'Walker', 'male'],
                ['Ava', 'Martinez', 'female'],
                // NEW: Additional Grade 6 students for testing graduation
                ['Benjamin', 'Anderson', 'male'],
                ['Charlotte', 'Wilson', 'female'],
                ['Samuel', 'Thomas', 'male'],
                ['Evelyn', 'Taylor', 'female'],
                ['Jackson', 'Moore', 'male'],
                ['Grace', 'White', 'female'],
                ['Sebastian', 'Harris', 'male'],
                ['Chloe', 'Martin', 'female'],
                ['Jack', 'Thompson', 'male'],
                ['Zoe', 'Garcia', 'female'],
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
            'grade2' => [
                ['Caleb', 'Ward', 'male'],
                ['Penelope', 'Reed', 'female'],
                ['Julian', 'Morgan', 'male'],
                ['Elena', 'Cooper', 'female'],
                ['Levi', 'Richardson', 'male'],
                ['Clara', 'Howard', 'female'],
                ['Nathan', 'Watson', 'male'],
                ['Violet', 'Peterson', 'female'],
                ['Anthony', 'Long', 'male'],
                ['Bella', 'James', 'female'],
            ],
            'grade3' => [
                ['Christian', 'Foster', 'male'],
                ['Skylar', 'Powell', 'female'],
                ['Aaron', 'Hughes', 'male'],
                ['Maya', 'Butler', 'female'],
                ['Thomas', 'Sanders', 'male'],
                ['Ariana', 'Perry', 'female'],
                ['Joshua', 'Flores', 'male'],
                ['Ellie', 'Washington', 'female'],
                ['Hunter', 'Bennett', 'male'],
                ['Nora', 'Gray', 'female'],
            ],
            'grade4' => [
                ['Evan', 'Reyes', 'male'],
                ['Hailey', 'Price', 'female'],
                ['Connor', 'Simmons', 'male'],
                ['Luna', 'Bell', 'female'],
                ['Adrian', 'Gonzalez', 'male'],
                ['Savannah', 'Ramirez', 'female'],
                ['Jordan', 'Coleman', 'male'],
                ['Eva', 'Stewart', 'female'],
                ['Carson', 'Morris', 'male'],
                ['Emilia', 'Murphy', 'female'],
            ],
            'grade5' => [
                ['Dominic', 'Rivera', 'male'],
                ['Aubrey', 'Cook', 'female'],
                ['Austin', 'Bailey', 'male'],
                ['Kennedy', 'Kelly', 'female'],
                ['Chase', 'Cox', 'male'],
                ['Paisley', 'Baxter', 'female'],
                ['Miles', 'Barnes', 'male'],
                ['Serenity', 'Ross', 'female'],
                ['Sawyer', 'Henderson', 'male'],
                ['Willow', 'Jenkins', 'female'],
            ],
        ];

        // Insert students + sample grades
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

                // Generate random grades for each subject
                $totalFinal = 0;
                $subjectCount = count($classSubjects[$gradeIndex]);

                // NEW: Enhanced grading logic for Grade 6 students
                if ($gradeIndex === 'grade6') {
                    // For Grade 6, create a mix of promotable (passing) and retainable (failing) students
                    // First 20 students will be promotable, next 10 will be retainable
                    $isPromotable = $i < 20;

                    foreach ($classSubjects[$gradeIndex] as $classSubject) {
                        $quarters = Quarter::where('class_subject_id', $classSubject->id)->get();

                        $quarterGrades = [];
                        foreach ($quarters as $quarter) {
                            if ($isPromotable) {
                                // For promotable students: grades between 85-98 (excellent to very good)
                                $grade = rand(85, 98);
                            } else {
                                // For retainable students: grades between 65-74 (failing)
                                $grade = rand(65, 74);
                            }
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
                            'remarks' => $final >= 75 ? 'passed' : 'failed',
                        ]);
                    }

                    // General average per student
                    $genAve = round($totalFinal / $subjectCount);

                    // Ensure averages are appropriate for promotable/retainable status
                    if ($isPromotable && $genAve < 85) {
                        $genAve = rand(85, 95); // Force good average for promotable
                    } elseif (!$isPromotable && $genAve >= 75) {
                        $genAve = rand(65, 74); // Force failing average for retainable
                    }
                } else {
                    // Original logic for other grades
                    $isFailingStudent = ($i % 5 === 0); // Every 5th student will fail

                    foreach ($classSubjects[$gradeIndex] as $classSubject) {
                        $quarters = Quarter::where('class_subject_id', $classSubject->id)->get();

                        $quarterGrades = [];
                        foreach ($quarters as $quarter) {
                            if ($isFailingStudent) {
                                $grade = rand(65, 74);
                            } else {
                                $grade = rand(80, 95);
                            }
                            $quarterGrades[] = $grade;

                            QuarterlyGrade::create([
                                'student_id' => $student->id,
                                'quarter_id' => $quarter->id,
                                'final_grade' => $grade,
                            ]);
                        }

                        $final = round(array_sum($quarterGrades) / count($quarterGrades));
                        $totalFinal += $final;

                        FinalSubjectGrade::create([
                            'student_id' => $student->id,
                            'class_subject_id' => $classSubject->id,
                            'final_grade' => $final,
                            'remarks' => $final >= 75 ? 'passed' : 'failed',
                        ]);
                    }

                    $genAve = round($totalFinal / $subjectCount);

                    if ($isFailingStudent && $genAve >= 75) {
                        $genAve = rand(65, 74);
                    }
                }

                GeneralAverage::create([
                    'student_id' => $student->id,
                    'school_year_id' => $schoolYear->id,
                    'general_average' => $genAve,
                    'remarks' => $genAve >= 75 ? 'passed' : 'failed',
                ]);

                // Output for verification
                $status = $genAve >= 75 ? 'PASSING' : 'FAILING';
                $type = ($gradeIndex === 'grade6' && $i < 20) ? 'PROMOTABLE' : 'RETAINABLE';
                echo "Created student: {$fname} {$lname} - Grade: {$gradeIndex} - Average: {$genAve} - Status: {$status} - {$type}\n";
            }
        }

        // Create specific failing students for testing promotion features
        $this->createSpecificFailingStudents($classes, $schoolYear, $classSubjects);

        // NEW: Create specific high-achieving Grade 6 students for graduation testing
        $this->createSpecificPromotableStudents($classes, $schoolYear, $classSubjects);
    }

    /**
     * Create specific students with controlled failing grades for testing
     */
    private function createSpecificFailingStudents($classes, $schoolYear, $classSubjects)
    {
        $specificFailingStudents = [
            'grade6' => [
                ['John', 'Smith', 'male', 72], // Borderline failing
                ['Sarah', 'Wilson', 'female', 68], // Clearly failing
            ],
            'grade1' => [
                ['Michael', 'Davis', 'male', 71], // Borderline failing
                ['Emily', 'Johnson', 'female', 65], // Clearly failing
            ]
        ];

        $baseLRN = 112828090000; // Different LRN range to avoid conflicts

        foreach ($specificFailingStudents as $gradeIndex => $studentList) {
            foreach ($studentList as $i => [$fname, $lname, $sex, $targetAverage]) {
                $lrn = (string) ($baseLRN + (array_search($gradeIndex, array_keys($specificFailingStudents)) * 100) + $i);

                $address = StudentAddress::create([
                    'house_no' => rand(100, 999),
                    'street_name' => 'Test St',
                    'barangay' => 'Barangay Test',
                    'municipality_city' => 'Test City',
                    'province' => 'Test Province',
                    'zip_code' => '2000',
                    'country' => 'Philippines',
                    'pob' => 'Test City',
                ]);

                $student = Student::create([
                    'student_lrn' => $lrn,
                    'student_fName' => $fname,
                    'student_mName' => 'T.',
                    'student_lName' => $lname,
                    'student_extName' => null,
                    'student_dob' => now()->subYears(5 + array_search($gradeIndex, array_keys($specificFailingStudents)))->format('Y-m-d'),
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

                // Generate grades to achieve target average
                $totalFinal = 0;
                $subjectCount = count($classSubjects[$gradeIndex]);

                foreach ($classSubjects[$gradeIndex] as $classSubject) {
                    $quarters = Quarter::where('class_subject_id', $classSubject->id)->get();

                    $quarterGrades = [];
                    foreach ($quarters as $quarter) {
                        // Generate grades around the target average with some variation
                        $grade = rand($targetAverage - 5, $targetAverage + 5);
                        $grade = max(65, min(74, $grade)); // Keep between 65-74 for failing students

                        $quarterGrades[] = $grade;

                        QuarterlyGrade::create([
                            'student_id' => $student->id,
                            'quarter_id' => $quarter->id,
                            'final_grade' => $grade,
                        ]);
                    }

                    $final = round(array_sum($quarterGrades) / count($quarterGrades));
                    $totalFinal += $final;

                    FinalSubjectGrade::create([
                        'student_id' => $student->id,
                        'class_subject_id' => $classSubject->id,
                        'final_grade' => $final,
                        'remarks' => $final >= 75 ? 'passed' : 'failed',
                    ]);
                }

                // Calculate and ensure the general average matches our target
                $genAve = round($totalFinal / $subjectCount);

                // If it's too high, adjust downward
                if ($genAve >= 75) {
                    $genAve = $targetAverage;
                }

                GeneralAverage::create([
                    'student_id' => $student->id,
                    'school_year_id' => $schoolYear->id,
                    'general_average' => $genAve,
                    'remarks' => $genAve >= 75 ? 'passed' : 'failed',
                ]);

                $status = $genAve >= 75 ? 'PASSING' : 'FAILING';
                echo "Created SPECIFIC failing student: {$fname} {$lname} - Grade: {$gradeIndex} - Average: {$genAve} - Status: {$status}\n";
            }
        }
    }

    /**
     * NEW: Create specific high-achieving Grade 6 students for graduation testing
     */
    private function createSpecificPromotableStudents($classes, $schoolYear, $classSubjects)
    {
        $specificPromotableStudents = [
            'grade6' => [
                ['Robert', 'Johnson', 'male', 92], // High achiever
                ['Elizabeth', 'Brown', 'female', 95], // Excellent student
                ['William', 'Davis', 'male', 89], // Very good student
                ['Sophia', 'Miller', 'female', 91], // High achiever
                ['Christopher', 'Wilson', 'male', 87], // Good student
            ],
        ];

        $baseLRN = 112828095000; // Different LRN range to avoid conflicts

        foreach ($specificPromotableStudents as $gradeIndex => $studentList) {
            foreach ($studentList as $i => [$fname, $lname, $sex, $targetAverage]) {
                $lrn = (string) ($baseLRN + (array_search($gradeIndex, array_keys($specificPromotableStudents)) * 100) + $i);

                $address = StudentAddress::create([
                    'house_no' => rand(100, 999),
                    'street_name' => 'Honor St',
                    'barangay' => 'Barangay Honor',
                    'municipality_city' => 'Honor City',
                    'province' => 'Honor Province',
                    'zip_code' => '3000',
                    'country' => 'Philippines',
                    'pob' => 'Honor City',
                ]);

                $student = Student::create([
                    'student_lrn' => $lrn,
                    'student_fName' => $fname,
                    'student_mName' => 'H.',
                    'student_lName' => $lname,
                    'student_extName' => null,
                    'student_dob' => now()->subYears(11)->format('Y-m-d'), // Grade 6 age
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

                // Generate excellent grades for promotable students
                $totalFinal = 0;
                $subjectCount = count($classSubjects[$gradeIndex]);

                foreach ($classSubjects[$gradeIndex] as $classSubject) {
                    $quarters = Quarter::where('class_subject_id', $classSubject->id)->get();

                    $quarterGrades = [];
                    foreach ($quarters as $quarter) {
                        // Generate excellent grades with some variation
                        $grade = rand($targetAverage - 8, $targetAverage + 3);
                        $grade = max(85, min(98, $grade)); // Keep between 85-98 for excellent students

                        $quarterGrades[] = $grade;

                        QuarterlyGrade::create([
                            'student_id' => $student->id,
                            'quarter_id' => $quarter->id,
                            'final_grade' => $grade,
                        ]);
                    }

                    $final = round(array_sum($quarterGrades) / count($quarterGrades));
                    $totalFinal += $final;

                    FinalSubjectGrade::create([
                        'student_id' => $student->id,
                        'class_subject_id' => $classSubject->id,
                        'final_grade' => $final,
                        'remarks' => 'passed', // Always passed for these students
                    ]);
                }

                // Calculate and ensure the general average matches our target
                $genAve = round($totalFinal / $subjectCount);

                // Ensure it's within excellent range
                if ($genAve < 85) {
                    $genAve = $targetAverage;
                }

                GeneralAverage::create([
                    'student_id' => $student->id,
                    'school_year_id' => $schoolYear->id,
                    'general_average' => $genAve,
                    'remarks' => 'passed',
                ]);

                $status = $genAve >= 75 ? 'PASSING' : 'FAILING';
                echo "Created SPECIFIC promotable student: {$fname} {$lname} - Grade: {$gradeIndex} - Average: {$genAve} - Status: {$status} - PROMOTABLE\n";
            }
        }
    }
}
