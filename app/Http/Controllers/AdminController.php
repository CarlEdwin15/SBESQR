<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Classes;
use App\Models\ParentInfo;
use App\Models\SchoolYear;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function userManagement()
    {
        // Fetch all users with computed attributes
        $users = User::all()
            ->sortBy(function ($user) {
                return sprintf(
                    '%d-%s',
                    $user->is_online ? 0 : 1,  // online first
                    strtolower($user->full_name) // alphabetical inside group
                );
            })
            ->values(); // reindex collection

        // You can paginate manually if needed
        // $users = $users->paginate(10); // collections don’t paginate directly

        $stats = [
            'totalUsers'   => User::count(),
            'totalTeachers' => User::where('role', 'teacher')->count(),
            'totalAdmins'  => User::where('role', 'admin')->count(),
            'totalParents' => User::where('role', 'parent')->count(),
        ];

        return view('admin.user_management.index', compact('users', 'stats'));
    }

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

    public function showAllTeachers(Request $request)
    {
        $now = now();
        $year = $now->year;
        $cutoff = $now->copy()->setMonth(6)->setDay(1);
        $currentYear = $now->lt($cutoff) ? $year - 1 : $year;

        $currentSchoolYear = $currentYear . '-' . ($currentYear + 1);
        $nextSchoolYear = ($currentYear + 1) . '-' . ($currentYear + 2);

        // Fetch all saved school years from DB
        $savedYears = SchoolYear::pluck('school_year')->toArray();

        // Extract numeric start years
        $savedStartYears = array_map(function ($sy) {
            return (int)substr($sy, 0, 4);
        }, $savedYears);

        $minYear = !empty($savedStartYears) ? min($savedStartYears) : $currentYear;

        // Build school years from minYear up to currentYear
        $schoolYears = [];
        for ($y = $minYear; $y <= $currentYear; $y++) {
            $schoolYears[] = $y . '-' . ($y + 1);
        }

        // Add current and next school year if missing
        if (!in_array($currentSchoolYear, $schoolYears)) {
            $schoolYears[] = $currentSchoolYear;
        }
        if (!in_array($nextSchoolYear, $schoolYears)) {
            $schoolYears[] = $nextSchoolYear;
        }

        // Sort school years ascending
        usort($schoolYears, function ($a, $b) {
            return intval(substr($a, 0, 4)) <=> intval(substr($b, 0, 4));
        });

        // Determine selected year
        $selectedYear = $request->query('school_year', $currentSchoolYear);

        $schoolYear = SchoolYear::where('school_year', $selectedYear)->first();
        $currentSchoolYearRecord = SchoolYear::where('school_year', $currentSchoolYear)->first();

        // Get re-assignable teachers: previously assigned but currently archived, and not yet re-assigned this year
        $reAssignableTeachers = User::where('role', 'teacher')
            ->whereHas('classes', function ($query) use ($schoolYear) {
                $query->where('class_user.status', 'archived')
                    ->where('class_user.school_year_id', '<>', $schoolYear->id);
            })
            ->whereDoesntHave('classes', function ($query) use ($schoolYear) {
                $query->where('class_user.school_year_id', $schoolYear->id)
                    ->where('class_user.status', 'active');
            })
            ->with(['classes' => function ($query) {
                $query->withPivot('school_year_id', 'status');
            }])
            ->get();


        // 1. Auto-archive "active" from past years
        if ($currentSchoolYearRecord) {
            DB::table('class_user')
                ->where('school_year_id', '<>', $currentSchoolYearRecord->id)
                ->where('status', 'active')
                ->update(['status' => 'archived']);
        }

        $teachers = collect();
        $allClasses = collect();

        if ($schoolYear) {
            $teachers = User::where('role', 'teacher')
                ->whereHas('classes', function ($query) use ($schoolYear) {
                    $query->where('class_user.school_year_id', $schoolYear->id);
                })
                ->with(['classes' => function ($query) use ($schoolYear) {
                    $query->where('class_user.school_year_id', $schoolYear->id)
                        ->withPivot('status', 'school_year_id');;
                }])
                ->get();

            $allClasses = Classes::with(['teachers' => function ($query) use ($schoolYear) {
                $query->wherePivot('school_year_id', $schoolYear->id);
            }])->get();

            session()->flash('school_year_notice', 'Displaying teachers for School Year: ' . $selectedYear);
        }

        return view('admin.teachers.showAllTeachers', compact(
            'teachers',
            'schoolYears',
            'selectedYear',
            'allClasses',
            'currentYear',
            'reAssignableTeachers',
        ));
    }

    public function registerTeacher(Request $request)
    {
        // Validate the request data
        $request->validate([
            'firstName' => 'required|string|max:255',
            'lastName' => 'required|string|max:255',
            'middleName' => 'nullable|string|max:255',
            'extName' => 'nullable|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                function ($attribute, $value, $fail) {
                    // Check if email belongs to an existing teacher
                    if (User::where('email', $value)->where('role', 'teacher')->exists()) {
                        return $fail('This email is already registered to a teacher.');
                    }

                    // Check if email belongs to a parent
                    if (ParentInfo::where('parent_email', $value)->exists()) {
                        return $fail('This email is already registered to a parent.');
                    }

                    // Generic check in case other roles exist (like admin)
                    if (User::where('email', $value)->where('role', '!=', 'teacher')->exists()) {
                        return $fail('This email is already registered for another user.');
                    }
                },
            ],
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
            'selected_school_year' => 'required|string|exists:school_years,school_year',
        ]);

        // Get school year ID
        $schoolYear = SchoolYear::where('school_year', $request->selected_school_year)->first();

        if (!$schoolYear) {
            return back()->withErrors([
                'selected_school_year' => 'Invalid school year selected.'
            ])->withInput();
        }

        // Ensure advisory class is in assigned classes
        if ($request->advisory_class && !in_array($request->advisory_class, $request->assigned_classes)) {
            return back()->withErrors([
                'advisory_class' => 'Advisory Class must be one of the Assigned Classes.'
            ])->withInput();
        }

        // Check if advisory class already has an adviser for this school year
        if ($request->advisory_class) {
            $advisoryClass = Classes::find($request->advisory_class);
            if ($advisoryClass->teachers()
                ->wherePivot('school_year_id', $schoolYear->id)
                ->wherePivot('role', 'adviser')
                ->exists()
            ) {
                return back()->withErrors([
                    'advisory_class' => 'This class already has an adviser assigned for the selected school year.'
                ])->withInput();
            }
        }

        // Handle profile photo
        $profilePhotoPath = null;
        if ($request->hasFile('profile_photo')) {
            $profilePhotoPath = $request->file('profile_photo')->store('profile_photos', 'public');
        }

        // Create the teacher
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

        // Attach classes with role and school year
        foreach ($request->assigned_classes as $classId) {
            $role = ($classId == $request->advisory_class) ? 'adviser' : 'subject_teacher';
            $teacher->classes()->attach($classId, [
                'role' => $role,
                'school_year_id' => $schoolYear->id
            ]);
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
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                function ($attribute, $value, $fail) use ($id) {
                    // Check if another teacher already has this email
                    if (User::where('email', $value)->where('id', '!=', $id)->where('role', 'teacher')->exists()) {
                        return $fail('This email is already registered to another teacher.');
                    }

                    // Check if parent is using this email
                    if (ParentInfo::where('parent_email', $value)->exists()) {
                        return $fail('This email is already registered to a parent.');
                    }

                    // Generic check for other users (like admin)
                    if (User::where('email', $value)->where('id', '!=', $id)->where('role', '!=', 'teacher')->exists()) {
                        return $fail('This email is already registered for another user.');
                    }
                },
            ],
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
            'selected_school_year' => 'required|string|exists:school_years,school_year',
        ]);

        $schoolYear = SchoolYear::where('school_year', $request->selected_school_year)->firstOrFail();

        // Ensure advisory class is in assigned classes
        if ($request->advisory_class && !in_array($request->advisory_class, $request->assigned_classes)) {
            return back()->withErrors([
                'advisory_class' => 'Advisory Class must be one of the Assigned Classes.'
            ])->withInput();
        }

        // Check if advisory class already has an adviser for this school year (excluding current teacher)
        if ($request->advisory_class) {
            $advisoryClass = Classes::find($request->advisory_class);
            if ($advisoryClass->teachers()
                ->wherePivot('school_year_id', $schoolYear->id)
                ->wherePivot('role', 'adviser')
                ->where('users.id', '!=', $id)
                ->exists()
            ) {
                return back()->withErrors([
                    'advisory_class' => 'This class already has an adviser assigned for the selected school year.'
                ])->withInput();
            }
        }

        $teacher = User::findOrFail($id);

        // Handle profile photo
        if ($request->hasFile('profile_photo')) {
            $file = $request->file('profile_photo');
            $path = $file->store('profile_photos', 'public');

            if ($teacher->profile_photo && Storage::disk('public')->exists($teacher->profile_photo)) {
                Storage::disk('public')->delete($teacher->profile_photo);
            }

            $teacher->profile_photo = $path;
        }

        // Update teacher info
        $teacher->update([
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
        ]);

        // Detach only classes for this school year
        $teacher->classes()->wherePivot('school_year_id', $schoolYear->id)->detach();

        // Attach new assignments for selected school year
        foreach ($request->assigned_classes as $classId) {
            $role = ($classId == $request->advisory_class) ? 'adviser' : 'subject_teacher';
            $teacher->classes()->attach($classId, [
                'role' => $role,
                'school_year_id' => $schoolYear->id
            ]);
        }

        return redirect()->route('show.teachers')->with('success', 'Teacher updated successfully for the selected school year.');
    }

    public function editTeacher(Request $request, $id)
    {
        $selectedSchoolYear = $request->input('school_year', $this->getDefaultSchoolYear());
        $schoolYear = SchoolYear::where('school_year', $selectedSchoolYear)->firstOrFail();

        $teacher = User::with(['classes' => function ($query) use ($schoolYear) {
            $query->wherePivot('school_year_id', $schoolYear->id);
        }])->findOrFail($id);

        // Get all classes with teachers for the selected year
        $allClasses = Classes::with(['teachers' => function ($query) use ($schoolYear) {
            $query->wherePivot('school_year_id', $schoolYear->id);
        }])->get();

        $assignedClasses = $teacher->classes()->wherePivot('school_year_id', $schoolYear->id)->pluck('classes.id')->toArray();
        $advisoryClass = $teacher->classes()
            ->wherePivot('school_year_id', $schoolYear->id)
            ->wherePivot('role', 'adviser')
            ->pluck('classes.id')->first();

        $schoolYears = SchoolYear::pluck('school_year')->toArray();
        $currentSchoolYear = $this->getDefaultSchoolYear();

        return view('admin.teachers.editTeacher', compact(
            'teacher',
            'allClasses',
            'assignedClasses',
            'advisoryClass',
            'schoolYears',
            'currentSchoolYear',
            'selectedSchoolYear'
        ));
    }

    public function deleteTeacher(Request $request, $id)
    {
        $teacher = User::findOrFail($id);

        $selectedSchoolYear = $request->input('school_year', $this->getDefaultSchoolYear());
        $schoolYear = SchoolYear::where('school_year', $selectedSchoolYear)->firstOrFail();

        // Delete profile photo if exists
        if ($teacher->profile_photo && Storage::disk('public')->exists($teacher->profile_photo)) {
            Storage::disk('public')->delete($teacher->profile_photo);
        }

        // Detach classes for the selected school year
        $teacher->classes()->wherePivot('school_year_id', $schoolYear->id)->detach();

        // Delete teacher if no classes are left
        if ($teacher->classes()->count() === 0) {
            $teacher->delete();
        }

        return redirect()->route('show.teachers')->with('success', 'Teacher details deleted successfully for the selected school year!');
    }

    public function teacherInfo(Request $request, $id)
    {
        $selectedSchoolYear = $request->query('school_year', $this->getDefaultSchoolYear());
        $schoolYear = SchoolYear::where('school_year', $selectedSchoolYear)->firstOrFail();

        $teacher = User::with(['classes' => function ($query) use ($schoolYear) {
            $query->wherePivot('school_year_id', $schoolYear->id);
        }])->findOrFail($id);

        $assignedClasses = $teacher->classes()->wherePivot('school_year_id', $schoolYear->id)->get();
        $advisoryClass = $teacher->classes()->wherePivot('school_year_id', $schoolYear->id)->wherePivot('role', 'adviser')->first();

        return view('admin.teachers.teacherInfo', compact('teacher', 'assignedClasses', 'advisoryClass', 'selectedSchoolYear'));
    }

    public function reassignment(Request $request)
    {
        $request->validate([
            'teacher_id' => 'required|exists:users,id',
            'selected_school_year' => 'required|string|exists:school_years,school_year',
            'reassign_classes' => 'required|array|min:1',
            'reassign_classes.*' => 'exists:classes,id',
            'reassign_advisory_class' => 'nullable|in:' . implode(',', $request->input('reassign_classes', [])),
        ]);

        $teacherId = $request->teacher_id;
        $selectedYear = $request->selected_school_year;
        $classIds = $request->reassign_classes;
        $advisoryClassId = $request->reassign_advisory_class;

        $schoolYear = SchoolYear::where('school_year', $selectedYear)->firstOrFail();

        // Remove any existing assignments for this teacher in that school year (optional safety step)
        DB::table('class_user')
            ->where('user_id', $teacherId)
            ->where('school_year_id', $schoolYear->id)
            ->delete();

        $now = now();
        foreach ($classIds as $classId) {
            DB::table('class_user')->insert([
                'user_id' => $teacherId,
                'class_id' => $classId,
                'school_year_id' => $schoolYear->id,
                'status' => 'active',
                'role' => $advisoryClassId == $classId ? 'adviser' : 'subject_teacher',
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        return redirect()->back()->with('success', 'Teacher reassigned successfully to selected classes.');
    }

    private function getDefaultSchoolYear()
    {
        $now = now();
        $year = $now->year;
        $cutoff = now()->copy()->setMonth(6)->setDay(1);
        $start = $now->lt($cutoff) ? $year - 1 : $year;
        return $start . '-' . ($start + 1);
    }
}
