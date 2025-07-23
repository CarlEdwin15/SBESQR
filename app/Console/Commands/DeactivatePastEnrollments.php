<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\SchoolYear;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

// class DeactivatePastEnrollments extends Command
// {
//     protected $signature = 'students:deactivate-old-enrollments';
//     protected $description = 'Set enrollment_status to inactive if the school year has ended';

//     public function handle()
//     {
//         $now = Carbon::now();

//         $endedSchoolYears = SchoolYear::where('end_date', '<', $now)->pluck('id');

//         DB::table('class_student')
//             ->whereIn('school_year_id', $endedSchoolYears)
//             ->where('enrollment_status', 'active')
//             ->update(['enrollment_status' => 'inactive']);

//         $this->info('Old enrollments deactivated successfully.');
//     }
// }
