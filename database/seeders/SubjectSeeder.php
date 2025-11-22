<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SchoolYear;
use App\Models\Classes;
use App\Models\Subject;
use App\Models\Quarter;
use App\Models\ClassSubject;
use Carbon\Carbon;

class SubjectSeeder extends Seeder
{
    public function run(): void
    {
        // Ensure current school year exists
        $schoolYear = SchoolYear::firstOrCreate(
            ['school_year' => '2024-2025'],
            ['start_date' => '2024-06-01', 'end_date' => '2025-03-31']
        );

        // Default subject list
        $defaultSubjects = [
            'Filipino',
            'English',
            'Mathematics',
            'Science',
            'Araling Panlipunan (AralPan)',
            'Edukasyon sa Pagpapakatao (ESP)',
            'Music, Arts, PE, Health (MAPEH)',
            'Technology and Livelihood Education (TLE)',
            'Mother Tongue',
        ];

        // Insert into subjects table (only once globally)
        $subjectIds = [];
        foreach ($defaultSubjects as $name) {
            $subject = Subject::firstOrCreate(
                ['name' => $name],
                ['description' => "Subject: {$name}"]
            );
            $subjectIds[] = $subject->id;
        }

        // Attach subjects to each class for this school year
        $classes = Classes::all();

        foreach ($classes as $class) {
            foreach ($subjectIds as $subjectId) {
                // Create or get pivot record
                $classSubject = ClassSubject::firstOrCreate(
                    [
                        'class_id' => $class->id,
                        'subject_id' => $subjectId,
                        'school_year_id' => $schoolYear->id,
                    ]
                );

                // Ensure 4 quarters exist per class_subject
                for ($q = 1; $q <= 4; $q++) {
                    Quarter::firstOrCreate(
                        [
                            'class_subject_id' => $classSubject->id,
                            'quarter' => $q,
                        ],
                        ['status' => 'upcoming']
                    );
                }
            }
        }
    }
}
