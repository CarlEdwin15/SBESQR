<?php

namespace App\Http\Controllers;

use App\Models\Classes;
use App\Models\Student;
use App\Models\StudentAddress;
use App\Models\ParentInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index() {}

    /**
     * Display the specified resource.
     */
    public function show()
    {
        $students = Student::with(['address', 'parentInfo', 'class'])->get();
        return view('admin.students.showAllStudents', compact('students'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.students.addStudent');
    }

    public function store(Request $request)
    {
        // Custom validation message for LRN regex failure
        $messages = [
            'student_lrn.regex' => 'The LRN must start with "112828" and be exactly 12 digits long.',
        ];

        // Validate the incoming request data
        $validatedData = $request->validate([
            // LRN must start with 112828 and be 12 digits, also must be unique in 'students' table
            'student_lrn' => ['required', 'string', 'max:12', 'regex:/^112828[0-9]{6}$/', 'unique:students,student_lrn'],

            // Grade and section must be from predefined choices
            'student_grade_level' => 'required|in:kindergarten,grade1,grade2,grade3,grade4,grade5,grade6',
            'student_section' => 'required|in:A,B,C,D,E,F',

            // Student name fields
            'student_fName' => 'required|string|max:255',
            'student_mName' => 'nullable|string|max:255',
            'student_lName' => 'required|string|max:255',
            'student_extName' => 'nullable|string|max:45',

            // Student personal info
            'student_dob' => 'nullable|date',
            'student_sex' => 'required|in:male,female',
            'student_pob' => 'required|string|max:255',

            // Address fields
            'house_no' => 'nullable|string|max:255',
            'street_name' => 'nullable|string|max:255',
            'barangay' => 'nullable|string|max:255',
            'municipality_city' => 'nullable|string|max:255',
            'province' => 'nullable|string|max:255',
            'zip_code' => 'nullable|string|max:255',

            // Parent info
            'student_fatherFName' => 'nullable|string|max:255',
            'student_fatherMName' => 'nullable|string|max:255',
            'student_fatherLName' => 'nullable|string|max:255',
            'student_fatherPhone' => 'nullable|string|max:255',
            'student_motherFName' => 'nullable|string|max:255',
            'student_motherMName' => 'nullable|string|max:255',
            'student_motherLName' => 'nullable|string|max:255',
            'student_motherPhone' => 'nullable|string|max:255',
            'student_guardianFName' => 'nullable|string|max:255',
            'student_guardianMName' => 'nullable|string|max:255',
            'student_guardianLName' => 'nullable|string|max:255',
            'student_guardianPhone' => 'nullable|string|max:255',

            // Profile photo validation
            'student_profile_photo' => 'nullable|image|mimes:jpeg,png|max:2048',
        ], $messages);

        // Handle profile photo upload if provided
        $profilePhotoPath = null;
        if ($request->hasFile('student_profile_photo')) {
            $profilePhotoPath = $request->file('student_profile_photo')->store('student_profile_photos', 'public');
        }

        // Generate a unique QR code ID
        $qrCode = uniqid('QR');

        // Save student address data and get the address ID
        $address = StudentAddress::create([
            'house_no' => $request->house_no,
            'street_name' => $request->street_name,
            'barangay' => $request->barangay,
            'municipality_city' => $request->municipality_city,
            'province' => $request->province,
            'zip_code' => $request->zip_code,
            'country' => 'Philippines', // Default country
            'pob' => $request->student_pob, // Place of birth
        ]);

        // Save parent information and get the parent ID
        $parent = ParentInfo::create([
            'father_fName' => $request->student_fatherFName,
            'father_mName' => $request->student_fatherMName,
            'father_lName' => $request->student_fatherLName,
            'father_phone' => $request->student_fatherPhone,
            'mother_fName' => $request->student_motherFName,
            'mother_mName' => $request->student_motherMName,
            'mother_lName' => $request->student_motherLName,
            'mother_phone' => $request->student_motherPhone,
            'guardian_fName' => $request->student_guardianFName,
            'guardian_mName' => $request->student_guardianMName,
            'guardian_lName' => $request->student_guardianLName,
            'guardian_phone' => $request->student_guardianPhone,
        ]);

        // Retrieve class based on grade level and section
        $class = \App\Models\Classes::where('grade_level', $request->student_grade_level)
            ->where('section', $request->student_section)
            ->firstOrFail();

        // Save student information, associating with class, address, and parent info
        Student::create([
            'student_lrn' => $validatedData['student_lrn'],
            'student_fName' => $validatedData['student_fName'],
            'student_mName' => $validatedData['student_mName'] ?? null,
            'student_lName' => $validatedData['student_lName'],
            'student_extName' => $validatedData['student_extName'] ?? null,
            'student_dob' => $validatedData['student_dob'] ?? null,
            'student_sex' => ucfirst($validatedData['student_sex']), // Capitalize first letter
            'student_photo' => $profilePhotoPath,
            'qr_code' => $qrCode,
            'class_id' => $class->id,
            'address_id' => $address->id,
            'parent_id' => $parent->id,
        ]);

        // Redirect back to the student form with success message
        return redirect()->route('add.student')->with('success', 'Student enrolled successfully!');
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, $id)
    {
        $student = Student::with(['address', 'parentInfo', 'class'])->findOrFail($id);
        return view('admin.students.editStudent', compact('student'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $student = Student::with(['address', 'parentInfo', 'class'])->findOrFail($id);

        $messages = [
            'student_lrn.regex' => 'The LRN must start with "112828" and be exactly 12 digits long.',
        ];

        $validatedData = $request->validate([
            'student_lrn' => [
                'required',
                'string',
                'max:12',
                'regex:/^112828[0-9]{6}$/',
                'unique:students,student_lrn,' . $student->id . ',id',
            ],
            'student_grade_level' => 'required|in:kindergarten,grade1,grade2,grade3,grade4,grade5,grade6',
            'student_section' => 'required|in:A,B,C,D,E,F',
            'student_fName' => 'required|string|max:255',
            'student_mName' => 'nullable|string|max:255',
            'student_lName' => 'required|string|max:255',
            'student_extName' => 'nullable|string|max:45',
            'student_dob' => 'nullable|date',
            'student_sex' => 'required|in:male,female',
            'student_pob' => 'required|string|max:255',

            // Address fields
            'house_no' => 'nullable|string|max:255',
            'street_name' => 'nullable|string|max:255',
            'barangay' => 'nullable|string|max:255',
            'municipality_city' => 'nullable|string|max:255',
            'province' => 'nullable|string|max:255',
            'zip_code' => 'nullable|string|max:255',

            // Parent info fields
            'student_fatherFName' => 'nullable|string|max:255',
            'student_fatherMName' => 'nullable|string|max:255',
            'student_fatherLName' => 'nullable|string|max:255',
            'student_fatherPhone' => 'nullable|string|max:255',
            'student_motherFName' => 'nullable|string|max:255',
            'student_motherMName' => 'nullable|string|max:255',
            'student_motherLName' => 'nullable|string|max:255',
            'student_motherPhone' => 'nullable|string|max:255',
            'student_guardianFName' => 'nullable|string|max:255',
            'student_guardianMName' => 'nullable|string|max:255',
            'student_guardianLName' => 'nullable|string|max:255',
            'student_guardianPhone' => 'nullable|string|max:255',

            // Profile photo
            'student_profile_photo' => 'nullable|image|mimes:jpeg,png|max:2048',
        ], $messages);

        // Handle new profile photo
        if ($request->hasFile('student_profile_photo')) {
            if ($student->student_photo && Storage::disk('public')->exists($student->student_photo)) {
                Storage::disk('public')->delete($student->student_photo);
            }

            $profilePhotoPath = $request->file('student_profile_photo')->store('student_profile_photos', 'public');
            $student->student_photo = $profilePhotoPath;
        }

        // Update related address
        $student->address()->update([
            'house_no' => $request->house_no,
            'street_name' => $request->street_name,
            'barangay' => $request->barangay,
            'municipality_city' => $request->municipality_city,
            'province' => $request->province,
            'zip_code' => $request->zip_code,
            'country' => 'Philippines',
            'pob' => $request->student_pob,
        ]);

        // Update related parent info
        $student->parentInfo()->update([
            'father_fName' => $request->student_fatherFName,
            'father_mName' => $request->student_fatherMName,
            'father_lName' => $request->student_fatherLName,
            'father_phone' => $request->student_fatherPhone,
            'mother_fName' => $request->student_motherFName,
            'mother_mName' => $request->student_motherMName,
            'mother_lName' => $request->student_motherLName,
            'mother_phone' => $request->student_motherPhone,
            'guardian_fName' => $request->student_guardianFName,
            'guardian_mName' => $request->student_guardianMName,
            'guardian_lName' => $request->student_guardianLName,
            'guardian_phone' => $request->student_guardianPhone,
        ]);

        // Find and assign class
        $class = Classes::where('grade_level', $validatedData['student_grade_level'])
            ->where('section', $validatedData['student_section'])
            ->firstOrFail();

        // Update main student fields
        $student->update([
            'student_lrn' => $validatedData['student_lrn'],
            'student_fName' => $validatedData['student_fName'],
            'student_mName' => $validatedData['student_mName'] ?? null,
            'student_lName' => $validatedData['student_lName'],
            'student_extName' => $validatedData['student_extName'] ?? null,
            'student_dob' => $validatedData['student_dob'] ?? null,
            'student_sex' => ucfirst($validatedData['student_sex']),
            'class_id' => $class->id,
        ]);

        return redirect()->route('show.students')->with('success', 'Student updated successfully!');
    }



    /**
     * Remove the student data by ID from storage.
     */
    public function destroy($id)
    {
        $student = Student::with(['address', 'parentInfo', 'class'])->findOrFail($id);

        if ($student->student_photo && Storage::disk('public')->exists($student->student_photo)) {
            Storage::disk('public')->delete($student->student_photo);
        }

        $student->delete();

        return redirect()->back()->with('success', 'Student deleted successfully!');
    }

    /**
     * Show student data by ID.
     */
    public function showStudentInfo($id)
    {
        $student = Student::with(['address', 'parentInfo', 'class'])->findOrFail($id);
        return view('admin.students.student_info', compact('student'));
    }
}
