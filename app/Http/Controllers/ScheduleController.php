<?php

namespace App\Http\Controllers;

use App\Models\Classes;
use App\Models\Schedule;
use App\Models\SchoolYear;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function displaySchedule(Request $request, $grade_level, $section)
    {
        $selectedYear = $request->query('school_year');
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
            ->wherePivotIn('role', ['adviser', 'subject_teacher', 'both'])
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

        $schoolYear = SchoolYear::where('school_year', $request->school_year)->firstOrFail();

        $class = Classes::where('grade_level', $grade_level)
            ->where('section', $section)
            ->firstOrFail();

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
                ->exists();

            if ($conflict) {
                $formattedStart = \Carbon\Carbon::createFromFormat('H:i', $request->start_time)->format('g:i A');
                $formattedEnd = \Carbon\Carbon::createFromFormat('H:i', $request->end_time)->format('g:i A');

                return back()->withErrors([
                    'schedule_conflict' => "The selected teacher already has a schedule from {$formattedStart} - {$formattedEnd} on {$day} in the selected school year."
                ])->withInput();
            }
        }

        foreach ($request->days as $day) {
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
            'subject_name' => 'required|string|max:255',
            'original_subject_name' => 'required|string|max:255',
            'teacher_id' => 'required|exists:users,id',
            'days' => 'required|array',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'school_year' => 'required|string'
        ]);

        $schoolYear = SchoolYear::where('school_year', $request->school_year)->firstOrFail();
        $class = Classes::where('grade_level', $grade_level)
            ->where('section', $section)
            ->firstOrFail();

        foreach ($request->days as $day) {
            $conflict = Schedule::where('teacher_id', $request->teacher_id)
                ->where('day', $day)
                ->where('class_id', '!=', $class->id)
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

        // Delete old schedule for that subject, class, and school year
        Schedule::where('class_id', $class->id)
            ->where('subject_name', $request->original_subject_name)
            ->where('school_year_id', $schoolYear->id)
            ->delete();

        // Insert new schedule entries
        foreach ($request->days as $day) {
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

        return back()->with('success', 'Schedule updated successfully!');
    }

    public function deleteSchedule(Request $request, $grade_level, $section, $subject)
    {
        $request->validate([
            'school_year' => 'required|string'
        ]);

        $schoolYear = SchoolYear::where('school_year', $request->school_year)->firstOrFail();
        $class = Classes::where('grade_level', $grade_level)
            ->where('section', $section)
            ->firstOrFail();

        Schedule::where('class_id', $class->id)
            ->where('subject_name', $subject)
            ->where('school_year_id', $schoolYear->id)
            ->delete();

        return back()->with('success', 'Schedule deleted successfully.');
    }
}
