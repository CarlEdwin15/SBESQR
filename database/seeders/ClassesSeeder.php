<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
// use Illuminate\Support\Carbon;

class ClassesSeeder extends Seeder
{
    public function run(): void
    {
        $gradeLevels = [
            'kindergarten',
            'grade1',
            'grade2',
            'grade3',
            'grade4',
            'grade5',
            'grade6',
        ];

        $sections = ['A', 'B', 'C', 'D', 'E', 'F'];

        $now = now();

        $data = [];

        foreach ($gradeLevels as $grade) {
            foreach ($sections as $section) {
                $data[] = [
                    'grade_level' => $grade,
                    'section' => $section,
                    // 'school_year' => $schoolYear,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
        }

        DB::table('classes')->insert($data);
    }
}
