<?php

namespace App\Http\Controllers;

use App\Models\Classes;
use App\Models\Schedule;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function displaySchedule($grade_level, $section)
    {
        $class = Classes::where('grade_level', $grade_level)
            ->where('section', $section)
            ->firstOrFail();

        $schedules = Schedule::where('class_id', $class->id)
            ->with('teacher')
            ->orderBy('day')
            ->orderBy('start_time')
            ->get();

        // Get teachers with pivot role
        $teachers = $class->teachers()
            ->wherePivotIn('role', ['adviser', 'subject_teacher', 'both'])
            ->withPivot('role') // make sure pivot role is available
            ->get()
            ->keyBy('id'); // map by ID for fast lookup

        // Attach pivot role to each schedule's teacher
        foreach ($schedules as $schedule) {
            $teacher = $schedule->teacher;
            if ($teacher && $teachers->has($teacher->id)) {
                $teacher->setRelation('pivot', $teachers[$teacher->id]->pivot);
            }
        }

        return view('admin.classes.schedules.index', compact('class', 'schedules', 'teachers'));
    }

    public function addSchedule(Request $request, $grade_level, $section)
    {
        $request->validate([
            'subject_name' => 'required|string|max:255',
            'teacher_id' => 'required|exists:users,id',
            'days' => 'required|array',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ]);

        // Get the class using grade_level and section
        $class = Classes::where('grade_level', $grade_level)
            ->where('section', $section)
            ->firstOrFail();

        // Validate conflict for each selected day
        foreach ($request->days as $day) {
            $conflict = Schedule::where('teacher_id', $request->teacher_id)
                ->where('day', $day)
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
                    'schedule_conflict' => "The selected teacher already has a schedule from {$formattedStart} - {$formattedEnd} on {$day}. Please choose a different time or teacher."
                ])->withInput();
            }
        }

        // If no conflict, create schedules
        foreach ($request->days as $day) {
            Schedule::create([
                'subject_name' => $request->subject_name,
                'teacher_id' => $request->teacher_id,
                'class_id' => $class->id,
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
        ]);

        $class = Classes::where('grade_level', $grade_level)
            ->where('section', $section)
            ->firstOrFail();

        // Validate conflict for each selected day (exclude current subject's schedules)
        foreach ($request->days as $day) {
            $conflict = Schedule::where('teacher_id', $request->teacher_id)
                ->where('day', $day)
                ->where('class_id', '!=', $class->id)
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
                    'schedule_conflict' => "The selected teacher already has a schedule from {$formattedStart} - {$formattedEnd} on {$day}. Please choose a different time or teacher."
                ])->withInput();
            }
        }

        // Delete existing schedule entries for this subject
        Schedule::where('class_id', $class->id)
            ->where('subject_name', $request->original_subject_name)
            ->delete();

        // Recreate with updated days
        foreach ($request->days as $day) {
            Schedule::create([
                'subject_name' => $request->subject_name,
                'teacher_id' => $request->teacher_id,
                'class_id' => $class->id,
                'day' => $day,
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
            ]);
        }

        return back()->with('success', 'Schedule updated successfully!');
    }

    public function deleteSchedule($grade_level, $section, $subject)
    {
        $class = Classes::where('grade_level', $grade_level)
            ->where('section', $section)
            ->firstOrFail();

        Schedule::where('class_id', $class->id)
            ->where('subject_name', $subject)
            ->delete();

        return back()->with('success', 'Schedule deleted successfully.');
    }
}
