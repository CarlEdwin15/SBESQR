<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SchoolYearSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        $years = [];

        // Generate 100 school years starting from 2024-2025
        $startYear = 2024;
        for ($i = 0; $i < 100; $i++) {
            $label = ($startYear + $i) . '-' . ($startYear + $i + 1);
            $start = ($startYear + $i) . '-06-01';
            $end = ($startYear + $i + 1) . '-03-31';
            $years[] = [$label, $start, $end];
        }

        foreach ($years as [$label, $start, $end]) {
            DB::table('school_years')->updateOrInsert([
                'school_year' => $label,
            ], [
                'start_date' => $start,
                'end_date' => $end,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }
}
