<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\SchoolYear;
use Carbon\Carbon;

class ClassesSeeder extends Seeder
{
    public function run(): void
    {
        $schoolYear = SchoolYear::firstOrCreate([
            'school_year' => '2024-2025',
        ], [
            'start_date' => '2024-06-01',
            'end_date' => '2025-03-31',
        ]);

        $gradeLevels = ['kindergarten', 'grade1', 'grade2', 'grade3', 'grade4', 'grade5', 'grade6'];
        $sections = ['A', 'B', 'C', 'D', 'E', 'F'];
        $now = Carbon::now();

        foreach ($gradeLevels as $grade) {
            foreach ($sections as $section) {
                DB::table('classes')->updateOrInsert([
                    'grade_level' => $grade,
                    'section' => $section,
                ], [
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }
    }
}
