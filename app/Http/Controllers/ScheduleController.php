<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Classes;
use App\Models\Schedule;
use App\Models\SchoolYear;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function displaySchedule(Request $request, $grade_level, $section)
    {
        $selectedYear = $request->input('school_year', $this->getDefaultSchoolYear());
        $schoolYear = SchoolYear::where('school_year', $selectedYear)->firstOrFail();

        $class = Classes::where('grade_level', $grade_level)
            ->where('section', $section)
            ->firstOrFail();

        $schedules = Schedule::where('class_id', $class->id)
            ->where('school_year_id', $schoolYear->id)
            ->with('teacher')
            ->orderBy('day')
            ->orderBy('start_time')
            ->get();

        $teachers = $class->teachers()
            ->wherePivot('school_year_id', $schoolYear->id)
            ->wherePivotIn('role', ['adviser', 'subject_teacher'])
            ->withPivot('role')
            ->get()
            ->keyBy('id');

        foreach ($schedules as $schedule) {
            $teacher = $schedule->teacher;
            if ($teacher && $teachers->has($teacher->id)) {
                $teacher->setRelation('pivot', $teachers[$teacher->id]->pivot);
            }
        }

        return view('admin.classes.schedules.index', compact('class', 'schedules', 'teachers', 'selectedYear'));
    }

    public function addSchedule(Request $request, $grade_level, $section)
    {
        $request->validate([
            'school_year' => 'required|string',
            'subject_name' => 'required|string|max:255',
            'teacher_id' => 'required|exists:users,id',
            'days' => 'required|array',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ]);

        $selectedYear = $request->input('school_year', $this->getDefaultSchoolYear());

        $schoolYear = SchoolYear::where('school_year', $selectedYear)->firstOrFail();

        $class = Classes::where('grade_level', $grade_level)
            ->where('section', $section)
            ->firstOrFail();

        $teacher = User::findOrFail($request->teacher_id);
        $teacherFullName = trim("{$teacher->firstName} {$teacher->middleName} {$teacher->lastName} {$teacher->extName}");

        foreach ($request->days as $day) {
            $conflict = Schedule::where('teacher_id', $request->teacher_id)
                ->where('day', $day)
                ->where('school_year_id', $schoolYear->id)
                ->where(function ($query) use ($request) {
                    $query->whereBetween('start_time', [$request->start_time, $request->end_time])
                        ->orWhereBetween('end_time', [$request->start_time, $request->end_time])
                        ->orWhere(function ($query) use ($request) {
                            $query->where('start_time', '<=', $request->start_time)
                                ->where('end_time', '>=', $request->end_time);
                        });
                })
                ->with('class') // eager load class relation
                ->first();

            if ($conflict) {
                $conflictClass = $conflict->class;
                $gradeLevel = ucfirst(str_replace('grade', 'Grade ', $conflictClass->grade_level)); // e.g. grade1 → Grade 1
                $sectionLabel = $conflictClass->section;

                $formattedStart = \Carbon\Carbon::createFromFormat('H:i', $request->start_time)->format('g:i A');
                $formattedEnd = \Carbon\Carbon::createFromFormat('H:i', $request->end_time)->format('g:i A');

                return back()->withErrors([
                    'schedule_conflict' => "{$teacherFullName} already has a schedule from {$formattedStart} to {$formattedEnd} on {$day} for {$gradeLevel} - Section {$sectionLabel}."
                ])->withInput();
            }
        }

        foreach ($request->days as $day) {
            $alreadyExists = Schedule::where([
                'teacher_id' => $request->teacher_id,
                'class_id' => $class->id,
                'school_year_id' => $schoolYear->id,
                'day' => $day,
                'start_time' => $request->start_time,
            ])->exists();

            if ($alreadyExists) continue;

            Schedule::create([
                'subject_name' => $request->subject_name,
                'teacher_id' => $request->teacher_id,
                'class_id' => $class->id,
                'school_year_id' => $schoolYear->id,
                'day' => $day,
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
            ]);
        }

        return back()->with('success', 'Schedule added successfully!');
    }

    public function editSchedule(Request $request, $grade_level, $section)
    {
        $request->validate([
            'schedule_id' => 'required|exists:schedules,id',
            'subject_name' => 'required|string|max:255',
            'teacher_id' => 'required|exists:users,id',
            'days' => 'required|array',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'school_year' => 'required|string',
        ]);

        $schoolYear = SchoolYear::where('school_year', $request->school_year)->firstOrFail();
        $class = Classes::where('grade_level', $grade_level)
            ->where('section', $section)
            ->firstOrFail();

        $originalSchedule = Schedule::where('id', $request->schedule_id)
            ->where('class_id', $class->id)
            ->where('school_year_id', $schoolYear->id)
            ->firstOrFail();

        foreach ($request->days as $day) {
            $conflict = Schedule::where('teacher_id', $request->teacher_id)
                ->where('day', $day)
                ->where('id', '!=', $originalSchedule->id)
                ->where('school_year_id', $schoolYear->id)
                ->where(function ($query) use ($request) {
                    $query->whereBetween('start_time', [$request->start_time, $request->end_time])
                        ->orWhereBetween('end_time', [$request->start_time, $request->end_time])
                        ->orWhere(function ($query) use ($request) {
                            $query->where('start_time', '<=', $request->start_time)
                                ->where('end_time', '>=', $request->end_time);
                        });
                })
                ->exists();

            if ($conflict) {
                $formattedStart = \Carbon\Carbon::createFromFormat('H:i', $request->start_time)->format('g:i A');
                $formattedEnd = \Carbon\Carbon::createFromFormat('H:i', $request->end_time)->format('g:i A');
                return back()->withErrors([
                    'schedule_conflict' => "The selected teacher already has a schedule from {$formattedStart} - {$formattedEnd} on {$day}."
                ])->withInput();
            }
        }

        // Update the existing schedule
        $originalSchedule->update([
            'subject_name' => $request->subject_name,
            'teacher_id' => $request->teacher_id,
            'day' => $request->days[0],
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
        ]);

        return back()->with('success', 'Schedule updated successfully!');
    }

    public function deleteSchedule(Request $request, $grade_level, $section, $schedule_id)
    {
        $request->validate([
            'school_year' => 'required|string'
        ]);

        $schoolYear = SchoolYear::where('school_year', $request->school_year)->firstOrFail();
        $class = Classes::where('grade_level', $grade_level)
            ->where('section', $section)
            ->firstOrFail();

        $schedule = Schedule::where('id', $schedule_id)
            ->where('class_id', $class->id)
            ->where('school_year_id', $schoolYear->id)
            ->firstOrFail();

        $schedule->delete();

        return back()->with('success', 'Schedule deleted successfully.');
    }

    // Helper function to get the default school year (e.g., "2024-2025")
    private function getDefaultSchoolYear()
    {
        $now = now();
        $year = $now->year;
        $cutoff = now()->copy()->setMonth(6)->setDay(1);
        $start = $now->lt($cutoff) ? $year - 1 : $year;

        return $start . '-' . ($start + 1);
    }
}
