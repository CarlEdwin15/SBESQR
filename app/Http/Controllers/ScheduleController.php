<?php

namespace App\Http\Controllers;

use App\Models\Classes;
use App\Models\Schedule;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function displaySchedule($grade_level, $section)
    {
        $class = \App\Models\Classes::where('grade_level', $grade_level)
            ->where('section', $section)
            ->firstOrFail();

        $schedules = \App\Models\Schedule::where('class_id', $class->id)->get();

        // Fetch teachers with pivot role
        $teachers = $class->teachers()
            ->wherePivotIn('role', ['adviser', 'subject_teacher', 'both'])
            ->get();

        return view('admin.classes.schedules.index', compact('class', 'schedules', 'teachers'));
    }

    public function addSchedule(Request $request, $grade_level, $section)
    {
        $request->validate([
            'subject_name' => 'required|string|max:255',
            'days' => 'required|array',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ]);

        // Get the class_id using grade_level and section
        $class = \App\Models\Classes::where('grade_level', $grade_level)
            ->where('section', $section)
            ->firstOrFail();

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
            'teacher_id' => 'nullable|exists:teachers,id',
            'days' => 'required|array',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ]);

        $class = Classes::where('grade_level', $grade_level)
            ->where('section', $section)
            ->firstOrFail();

        // Delete existing schedule entries for this subject
        Schedule::where('class_id', $class->id)
            ->where('subject_name', $request->subject_name)
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


    public function updateSchedule(Request $request, $grade_level, $section) {}

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
