<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Classes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function showAllTeachers()
    {
        $teachers = User::where('role', 'teacher')->get(); // Fetch only teachers
        return view('admin.teachers.showAllTeachers', compact('teachers'));
    }

    public function registerTeacher(Request $request)
    {
        $request->validate([
            'firstName' => 'required|string|max:255',
            'lastName' => 'required|string|max:255',
            'middleName' => 'nullable|string|max:255',
            'extName' => 'nullable|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'gender' => 'required|in:male,female',
            'section_assigned' => 'required|in:A,B,C,D,E,F,G',
            'grade_level_assigned' => 'required|in:kindergarten,grade1,grade2,grade3,grade4,grade5,grade6',
            'phone' => 'nullable|string|max:20',
            'house_no' => 'nullable|string|max:255',
            'street_name' => 'nullable|string|max:255',
            'barangay' => 'nullable|string|max:255',
            'municipality_city' => 'nullable|string|max:255',
            'province' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'zip_code' => 'nullable|string|max:20',
            'dob' => 'nullable|date',
            'password' => 'required|string|min:8|confirmed',
            'profile_photo' => 'nullable|image|mimes:jpg,jpeg,png,gif,svg|max:2048',
        ]);

        $profilePhotoPath = null;

        if ($request->hasFile('profile_photo')) {
            $profilePhotoPath = $request->file('profile_photo')->store('profile_photos', 'public');
        }

        // Retrieve class based on grade level and section
        $class = \App\Models\Classes::where('grade_level', $request->grade_level_assigned)
            ->where('section', $request->section_assigned)
            ->firstOrFail();

        // // Find or create class based on grade and section
        // $class = \App\Models\Classes::firstOrCreate([
        //     'grade_level' => $request->grade_level_assigned,
        //     'section' => $request->section_assigned,
        // ]);

        User::create([
            'firstName' => $request->firstName,
            'lastName' => $request->lastName,
            'middleName' => $request->middleName,
            'extName' => $request->extName,
            'email' => $request->email,
            'gender' => $request->gender,
            'phone' => $request->phone,

            'house_no' => $request->house_no,
            'street_name' => $request->street_name,
            'barangay' => $request->barangay,
            'municipality_city' => $request->municipality_city,
            'province' => $request->province,
            'country' => $request->country ?? 'Philippines',
            'zip_code' => $request->zip_code,

            'dob' => $request->dob,
            'password' => Hash::make($request->password),
            'profile_photo' => $profilePhotoPath,
            'role' => 'teacher',
            'class_id' => $class->id,
        ]);

        return redirect()->back()->with('success', 'Teacher registered successfully!');
    }


    public function updateTeacher(Request $request, $id)
    {
        $request->validate([
            'firstName' => 'required|string|max:255',
            'lastName' => 'required|string|max:255',
            'middleName' => 'nullable|string|max:255',
            'extName' => 'nullable|string|max:255',
            'gender' => 'required|in:male,female',
            'grade_level' => 'required|in:kindergarten,grade1,grade2,grade3,grade4,grade5,grade6',
            'section' => 'required|in:A,B,C,D,E,F',
            'phone' => 'nullable|string|max:20',
            'house_no' => 'nullable|string|max:255',
            'street_name' => 'nullable|string|max:255',
            'barangay' => 'nullable|string|max:255',
            'municipality_city' => 'nullable|string|max:255',
            'province' => 'nullable|string|max:255',
            'zip_code' => 'nullable|string|max:20',
            'dob' => 'nullable|date',
            'profile_photo' => 'nullable|image|mimes:jpg,jpeg,png,gif,svg|max:2048',
        ]);

        $teacher = User::findOrFail($id);

        // Get or create the class_id for the given grade level + section
        $class = Classes::firstOrCreate([
            'grade_level' => $request->grade_level,
            'section' => $request->section
        ]);

        if ($request->hasFile('profile_photo')) {
            $file = $request->file('profile_photo');
            $path = $file->store('profile_photos', 'public');

            if ($teacher->profile_photo && Storage::disk('public')->exists($teacher->profile_photo)) {
                Storage::disk('public')->delete($teacher->profile_photo);
            }

            $teacher->profile_photo = $path;
        }

        $teacher->update([
            'firstName' => $request->firstName,
            'lastName' => $request->lastName,
            'middleName' => $request->middleName,
            'extName' => $request->extName,
            'gender' => $request->gender,
            'class_id' => $class->id,
            'phone' => $request->phone,

            'house_no' => $request->house_no,
            'street_name' => $request->street_name,
            'barangay' => $request->barangay,
            'municipality_city' => $request->municipality_city,
            'province' => $request->province,
            'zip_code' => $request->zip_code,

            'dob' => $request->dob,
            'profile_photo' => $teacher->profile_photo,
        ]);

        return redirect()->route('show.teachers')->with('success', 'Teacher details updated successfully!');
    }

    public function editTeacher($id)
    {
        $teacher = User::findOrFail($id);
        $classes = Classes::all(); // for dropdowns
        return view('admin.teachers.editTeacher', compact('teacher', 'classes'));
    }


    public function deleteTeacher($id)
    {
        $teacher = User::findOrFail($id);

        if ($teacher->profile_photo && Storage::disk('public')->exists($teacher->profile_photo)) {
            Storage::disk('public')->delete($teacher->profile_photo);
        }

        $teacher->delete();

        return redirect()->route('show.teachers')->with('success', 'Teacher details deleted successfully!');
    }

    public function teacherInfo($id)
    {
        $teacher = User::findOrFail($id);
        return view('admin.teachers.teacherInfo', compact('teacher'));
    }
}
