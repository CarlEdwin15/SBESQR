<?php

namespace App\Http\Controllers;

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

        // Fetch teachers from the users table or teachers model (depending on your schema)
        $teachers = \App\Models\User::where('role', 'teacher')->get(); // Adjust this query as needed

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
}
