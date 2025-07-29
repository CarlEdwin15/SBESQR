<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $attendances = Attendance::with(['student', 'class'])->latest()->get();
        return view('attendances.index', compact('attendances'));
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function takeAttendance(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'teacher_id' => 'required|exists:users,id',
            'class_id' => 'required|exists:classes,id',
            'status' => 'required|in:present,absent,late',
            'time_in' => 'nullable|date_format:H:i',
            'time_out' => 'nullable|date_format:H:i',
        ]);

        $schedule = Schedule::where('teacher_id', $request->teacher_id)
            ->where('class_id', $request->class_id)
            ->where('day', now()->format('l')) // e.g. "Monday"
            ->first();

        Attendance::create([
            'student_id' => $request->student_id,
            'teacher_id' => $request->teacher_id,
            'class_id' => $request->class_id,
            'schedule_id' => $schedule?->id,
            'date' => Carbon::now()->toDateString(),
            'time_in' => $request->time_in,
            'time_out' => $request->time_out,
            'status' => $request->status,
        ]);

        return back()->with('success', 'Attendance recorded successfully!');
    }

    public function getAttendanceExportData()
    {
        // Reuse the exact logic from myAttendanceRecord()
        // but instead of returning a view, return the compacted variables

        // Use current or request params if needed
        $request = request();

        $grade_level = $request->input('grade_level');
        $section = $request->input('section');
        $school_year = $request->input('school_year');
        $month = $request->input('month');

        // Mock Request object for function reuse
        $mockRequest = new \Illuminate\Http\Request([
            'school_year' => $school_year,
            'month' => $month,
            '__return_array__' => true,
        ]);

        return app()->call(
            [app(\App\Http\Controllers\TeacherController::class), 'myAttendanceRecord'],
            [
                'request' => $mockRequest,
                'grade_level' => $grade_level,
                'section' => $section,
            ]
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(Attendance $attendance)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Attendance $attendance)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Attendance $attendance)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Attendance $attendance)
    {
        //
    }
}
