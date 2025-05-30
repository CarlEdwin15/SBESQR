<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class TeacherController extends Controller
{
    public function myStudents()
    {
        $teacher = Auth::user(); // Assuming the teacher is the currently logged-in user

        $students = Student::where('student_grade_level', $teacher->grade_level_assigned)
            ->where('student_section', $teacher->section_assigned)
            ->get();

        return view('teacher.students.myStudents', compact('students', 'teacher'));
    }

    public function studentInfo($student_id)
    {
        $student = Student::findOrFail($student_id);
        return view('teacher.students.studentInfo', compact('student'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function editStudentInfo(Request $request, $student_id)
    {
        $student = Student::findOrFail($student_id);
        return view('teacher.students.editStudent', compact('student'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function updateStudentInfo(Request $request, $student_id)
    {
        $student = Student::findOrFail($student_id);

        $messages = [
            'student_lrn.regex' => 'The LRN must start with "112828" and be exactly 12 digits long.',
        ];

        $validatedData = $request->validate([
            'student_lrn' => [
                'required',
                'string',
                'max:12',
                'regex:/^112828[0-9]{6}$/', // starts with 112828 + 6 digits
                'unique:students,student_lrn,' . $student->student_id . ',student_id',
            ],
            'student_grade_level' => 'required|in:kindergarten,grade1,grade2,grade3,grade4,grade5,grade6',
            'student_section' => 'required|in:A,B,C,D,E,F',
            'student_fName' => 'required|string|max:255',
            'student_mName' => 'nullable|string|max:255',
            'student_lName' => 'required|string|max:255',
            'student_extName' => 'nullable|string|max:45',
            'student_dob' => 'nullable|date',
            'student_sex' => 'required|in:male,female',
            'student_age' => 'nullable|integer',
            'student_pob' => 'required|string|max:255',
            'address' => 'required|string|max:255',

            'student_fatherFName' => 'nullable|string|max:255',
            'student_fatherMName' => 'nullable|string|max:255',
            'student_fatherLName' => 'nullable|string|max:255',

            'student_motherFName' => 'nullable|string|max:255',
            'student_motherMName' => 'nullable|string|max:255',
            'student_motherLName' => 'nullable|string|max:255',

            'student_parentPhone' => 'nullable|string|max:255',

            'student_profile_photo' => 'nullable|image|mimes:jpeg,png|max:2048',
        ]);

        // Handle photo upload
        if ($request->hasFile('student_profile_photo')) {
            $profilePhotoPath = $request->file('student_profile_photo')->store('student_profile_photos', 'public');
            $student->student_photo = $profilePhotoPath;
        }

        // Update fields
        $student->updateStudentInfo([
            'student_lrn' => $validatedData['student_lrn'],
            'student_grade_level' => $validatedData['student_grade_level'],
            'student_section' => $validatedData['student_section'],
            'student_fName' => $validatedData['student_fName'],
            'student_mName' => $validatedData['student_mName'] ?? null,
            'student_lName' => $validatedData['student_lName'],
            'student_extName' => $validatedData['student_extName'] ?? null,
            'student_dob' => $validatedData['student_dob'] ?? null,
            'student_sex' => ucfirst($validatedData['student_sex']),
            'student_age' => $validatedData['student_age'] ?? null,
            'student_pob' => $validatedData['student_pob'],
            'student_address' => $validatedData['address'],
            'student_fatherFName' => $validatedData['student_fatherFName'] ?? null,
            'student_fatherMName' => $validatedData['student_fatherMName'] ?? null,
            'student_fatherLName' => $validatedData['student_fatherLName'] ?? null,
            'student_motherFName' => $validatedData['student_motherFName'] ?? null,
            'student_motherMName' => $validatedData['student_motherMName'] ?? null,
            'student_motherLName' => $validatedData['student_motherLName'] ?? null,
            'student_parentPhone' => $validatedData['student_parentPhone'] ?? null,
        ]);

        return redirect(route('teacher.my.students'))->with('success', 'Student updated successfully!');
    }
}
