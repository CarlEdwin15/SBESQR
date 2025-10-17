<?php

namespace App\Jobs;

use App\Models\Attendance;
use App\Models\Classes;
use App\Models\Schedule;
use App\Models\SchoolYear;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class AutoMarkAbsent implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $classId;
    protected $scheduleId;
    protected $schoolYearId;
    protected $date;

    public function __construct($classId, $scheduleId, $schoolYearId, $date)
    {
        $this->classId = $classId;
        $this->scheduleId = $scheduleId;
        $this->schoolYearId = $schoolYearId;
        $this->date = $date;
    }

    public function handle(): void
    {
        $class = Classes::find($this->classId);
        $schedule = Schedule::find($this->scheduleId);
        $schoolYear = SchoolYear::find($this->schoolYearId);

        if (!$class || !$schedule || !$schoolYear) {
            Log::warning("❌ AutoMarkAbsent aborted: Missing data (class, schedule, or school year).");
            return;
        }

        $students = $class->students()
            ->wherePivot('school_year_id', $schoolYear->id)
            ->get();

        $absentCount = 0;

        foreach ($students as $student) {
            $existing = Attendance::where([
                'student_id' => $student->id,
                'schedule_id' => $schedule->id,
                'school_year_id' => $schoolYear->id,
                'date' => $this->date,
            ])->first();

            // Mark as absent only if no attendance record exists
            if (!$existing) {
                $attendance = Attendance::create([
                    'student_id' => $student->id,
                    'schedule_id' => $schedule->id,
                    'school_year_id' => $schoolYear->id,
                    'date' => $this->date,
                    'status' => 'absent',
                    'teacher_id' => $schedule->teacher_id,
                    'class_id' => $class->id,
                    'time_in' => null,
                    'time_out' => null,
                ]);

                $absentCount++;

                //  Send SMS to parents (queued)
                // \App\Jobs\SendAttendanceSMS::dispatch(
                //     $student,
                //     'absent',
                //     $schedule,
                //     $attendance
                // );
            }
        }

        Log::info(" AutoMarkAbsent: {$absentCount} students marked absent for {$class->grade_level}-{$class->section}, schedule {$schedule->subject_name}");
    }
}
