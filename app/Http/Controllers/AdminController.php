<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Classes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function accountSettings()
    {
        $user = Auth::user();
        return view('admin.accountSettings', compact('user'));
    }

    public function updateAdmin($id)
    {
        $user = User::findOrFail($id);

        // Check if the logged-in user is updating their own account
        if (Auth::id() != $user->id) {
            abort(403, 'Unauthorized');
        }

        $data = request()->all();

        $validated = Validator::make($data, [
            'firstName' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'profile_photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'current_password' => 'nullable|string',
            'new_password' => 'nullable|string|min:8|same:confirm_password',
        ]);

        if ($validated->fails()) {
            return redirect()->back()->withErrors($validated)->withInput();
        }

        // Update profile photo if provided
        if (request()->hasFile('profile_photo')) {
            if ($user->profile_photo) {
                Storage::delete('public/' . $user->profile_photo);
            }
            $path = request()->file('profile_photo')->store('profile_photos', 'public');
            $user->profile_photo = $path;
        }

        // Update basic fields
        $user->firstName = $data['firstName'];
        $user->email = $data['email'];

        // Handle password update
        if (!empty($data['current_password']) && !empty($data['new_password'])) {
            if (Hash::check($data['current_password'], $user->password)) {
                $user->password = Hash::make($data['new_password']);
            } else {
                return back()->withErrors(['current_password' => 'Current password is incorrect'])->withInput();
            }
        }

        $user->save();

        return back()->with('success', 'Account updated successfully');
    }

    public function showAllTeachers()
    {
        $teachers = User::where('role', 'teacher')->get();
        $allClasses = Classes::with('teachers')->get();
        return view('admin.teachers.showAllTeachers', compact('teachers', 'allClasses'));
    }

    public function registerTeacher(Request $request)
    {
        // Validate the request data with enhanced password rules
        $request->validate([
            'firstName' => 'required|string|max:255',
            'lastName' => 'required|string|max:255',
            'middleName' => 'nullable|string|max:255',
            'extName' => 'nullable|string|max:255',
            'email' => 'required|email|unique:users,email',
            'gender' => 'required|in:male,female',
            'phone' => 'nullable|string|max:20',
            'house_no' => 'nullable|string|max:255',
            'street_name' => 'nullable|string|max:255',
            'barangay' => 'nullable|string|max:255',
            'municipality_city' => 'nullable|string|max:255',
            'province' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'zip_code' => 'nullable|string|max:20',
            'dob' => 'nullable|date',
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                'regex:/[a-z]/',
                'regex:/[A-Z]/',
                'regex:/[0-9]/',
                'regex:/[@$!%*#?&]/',
            ],
            'profile_photo' => 'nullable|image|mimes:jpg,jpeg,png,gif,svg|max:2048',
            'assigned_classes' => 'required|array|min:1',
            'assigned_classes.*' => 'exists:classes,id',
            'advisory_class' => 'nullable|exists:classes,id',
        ], [
            'email.unique' => 'The email is already taken.', // ✅ Custom message
        ]);


        // Ensure advisory class is part of assigned classes
        if ($request->advisory_class && !in_array($request->advisory_class, $request->assigned_classes)) {
            return back()->withErrors([
                'advisory_class' => 'Advisory Class must be one of the Assigned Classes.'
            ])->withInput();
        }

        // Check if the advisory class already has an adviser
        if ($request->advisory_class) {
            $advisoryClass = Classes::find($request->advisory_class);

            if ($advisoryClass->teachers()->wherePivot('role', 'adviser')->exists()) {
                return back()->withErrors([
                    'advisory_class' => 'This class already has an adviser assigned.'
                ])->withInput();
            }
        }

        // Handle profile photo upload
        $profilePhotoPath = null;
        if ($request->hasFile('profile_photo')) {
            $profilePhotoPath = $request->file('profile_photo')->store('profile_photos', 'public');
        }

        // Create the teacher user
        $teacher = User::create([
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
        ]);

        // Attach classes with pivot role
        foreach ($request->assigned_classes as $classId) {
            $role = ($classId == $request->advisory_class) ? 'adviser' : 'subject_teacher';
            $teacher->classes()->attach($classId, ['role' => $role]);
        }

        return redirect()->back()->with('success', 'Teacher registered successfully!');
    }

    public function updateTeacher(Request $request, $id)
    {
        $request->validate([
            'firstName' => 'required|string|max:255',
            'lastName' => 'required|string|max:255',
            'middleName' => 'nullable|string|max:255',
            'extName' => 'nullable|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'gender' => 'required|in:male,female',
            'phone' => 'nullable|string|max:20',
            'house_no' => 'nullable|string|max:255',
            'street_name' => 'nullable|string|max:255',
            'barangay' => 'nullable|string|max:255',
            'municipality_city' => 'nullable|string|max:255',
            'province' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'zip_code' => 'nullable|string|max:20',
            'dob' => 'nullable|date',
            'profile_photo' => 'nullable|image|mimes:jpg,jpeg,png,gif,svg|max:2048',
            'assigned_classes' => 'required|array|min:1',
            'assigned_classes.*' => 'exists:classes,id',
            'advisory_class' => 'nullable|exists:classes,id',
        ]);

        if ($request->advisory_class && !in_array($request->advisory_class, $request->assigned_classes)) {
            return back()->withErrors([
                'advisory_class' => 'Advisory Class must be one of the Assigned Classes.'
            ])->withInput();
        }

        $teacher = User::findOrFail($id);

        // Handle profile photo upload
        if ($request->hasFile('profile_photo')) {
            $file = $request->file('profile_photo');
            $path = $file->store('profile_photos', 'public');

            if ($teacher->profile_photo && Storage::disk('public')->exists($teacher->profile_photo)) {
                Storage::disk('public')->delete($teacher->profile_photo);
            }

            $teacher->profile_photo = $path;
        }

        $teacher->firstName = $request->firstName;
        $teacher->lastName = $request->lastName;
        $teacher->middleName = $request->middleName;
        $teacher->extName = $request->extName;
        $teacher->email = $request->email;
        $teacher->gender = $request->gender;
        $teacher->phone = $request->phone;
        $teacher->house_no = $request->house_no;
        $teacher->street_name = $request->street_name;
        $teacher->barangay = $request->barangay;
        $teacher->municipality_city = $request->municipality_city;
        $teacher->province = $request->province;
        $teacher->country = $request->country ?? 'Philippines';
        $teacher->zip_code = $request->zip_code;
        $teacher->dob = $request->dob;
        $teacher->save();

        // Sync classes with pivot role
        $syncData = [];
        foreach ($request->assigned_classes as $classId) {
            $role = ($classId == $request->advisory_class) ? 'adviser' : 'subject_teacher';
            $syncData[$classId] = ['role' => $role];
        }
        $teacher->classes()->sync($syncData);

        return redirect()->route('show.teachers')->with('success', 'Teacher details updated successfully!');
    }

    public function editTeacher($id)
    {
        $teacher = User::findOrFail($id);
        $allClasses = Classes::all();
        $assignedClasses = $teacher->classes()->pluck('classes.id')->toArray();
        $advisoryClass = $teacher->classes()->wherePivot('role', 'adviser')->pluck('classes.id')->first();

        return view('admin.teachers.editTeacher', compact('teacher', 'allClasses', 'assignedClasses', 'advisoryClass'));
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
        $assignedClasses = $teacher->classes()->get();
        $advisoryClass = $teacher->classes()->wherePivot('role', 'adviser')->first();

        return view('admin.teachers.teacherInfo', compact('teacher', 'assignedClasses', 'advisoryClass'));
    }
}
