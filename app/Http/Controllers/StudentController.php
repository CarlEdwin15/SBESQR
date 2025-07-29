<?php

namespace App\Http\Controllers;

use App\Models\Classes;
use App\Models\Student;
use App\Models\StudentAddress;
use App\Models\ParentInfo;
use App\Models\SchoolYear;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf; // Make sure you have barryvdh/laravel-dompdf installed
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class StudentController extends Controller
{

    public function show(Request $request)
    {
        $now = now();
        $year = $now->year;
        $cutoff = $now->copy()->setMonth(6)->setDay(1);
        $currentYear = $now->lt($cutoff) ? $year - 1 : $year;

        $currentSchoolYear = $currentYear . '-' . ($currentYear + 1);
        $nextSchoolYear = ($currentYear + 1) . '-' . ($currentYear + 2);

        // Fetch all saved school years from DB
        $savedYears = SchoolYear::pluck('school_year')->toArray();
        $savedStartYears = array_map(fn($sy) => (int)substr($sy, 0, 4), $savedYears);
        $minYear = !empty($savedStartYears) ? min($savedStartYears) : $currentYear;

        $schoolYears = [];
        for ($y = $minYear; $y <= $currentYear; $y++) {
            $schoolYears[] = $y . '-' . ($y + 1);
        }

        if (!in_array($currentSchoolYear, $schoolYears)) {
            $schoolYears[] = $currentSchoolYear;
        }

        $schoolYears[] = $nextSchoolYear;
        usort($schoolYears, fn($a, $b) => intval(substr($a, 0, 4)) <=> intval(substr($b, 0, 4)));

        $selectedYear = $request->query('school_year', $currentSchoolYear);
        $selectedSection = $request->query('section');

        $schoolYear = SchoolYear::where('school_year', $selectedYear)->first();
        $currentSchoolYearRecord = SchoolYear::where('school_year', $currentSchoolYear)->first();

        $schoolYearId = optional($schoolYear)->id;

        // 1. Auto-archive "enrolled" from past years
        if ($currentSchoolYearRecord) {
            DB::table('class_student')
                ->where('school_year_id', '<>', $currentSchoolYearRecord->id)
                ->where('enrollment_status', 'enrolled')
                ->update(['enrollment_status' => 'archived']);
        }

        // 2. Auto-insert "not_enrolled" record for current year
        if ($currentSchoolYearRecord) {
            $archivedStudentIds = DB::table('class_student')
                ->where('enrollment_status', 'archived')
                ->pluck('student_id')
                ->unique();

            foreach ($archivedStudentIds as $studentId) {
                $exists = DB::table('class_student')
                    ->where('student_id', $studentId)
                    ->where('school_year_id', $currentSchoolYearRecord->id)
                    ->exists();

                if (!$exists) {
                    DB::table('class_student')->insert([
                        'student_id' => $studentId,
                        'class_id' => null, // explicitly set to null for not enrolled
                        'school_year_id' => $currentSchoolYearRecord->id,
                        'enrollment_status' => 'not_enrolled',
                        'enrollment_type' => 'regular',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }

        // Delete 'not_enrolled' entries from school years that have ended
        $expiredSchoolYearIds = SchoolYear::where('end_date', '<', now())->pluck('id');
        DB::table('class_student')
            ->whereIn('school_year_id', $expiredSchoolYearIds)
            ->where('enrollment_status', 'not_enrolled')
            ->delete();

        $students = collect();
        $sections = [];

        if ($schoolYear) {
            $sections = Classes::whereHas('students', function ($query) use ($schoolYear) {
                $query->where('class_student.school_year_id', $schoolYear->id);
            })->pluck('section')->unique()->sort()->values()->all();

            $students = Student::whereHas('class', function ($query) use ($schoolYear, $selectedSection) {
                $query->where('class_student.school_year_id', $schoolYear->id);
                if (!empty($selectedSection)) {
                    $query->where('section', $selectedSection);
                }
            })->with([
                'address',
                'parentInfo',
                'class' => function ($query) use ($schoolYear) {
                    $query->where('class_student.school_year_id', $schoolYear->id);
                }
            ])->get();
        }

        $gradeLevels = [
            'Kindergarten',
            'Grade 1',
            'Grade 2',
            'Grade 3',
            'Grade 4',
            'Grade 5',
            'Grade 6',
        ];

        $groupedStudents = collect();
        foreach ($gradeLevels as $grade) {
            $groupedStudents[$grade] = $students->filter(function ($student) use ($grade, $schoolYear) {
                $classForYear = $student->class->firstWhere('pivot.school_year_id', $schoolYear->id);
                return optional($classForYear)->formatted_grade_level === $grade;
            });
        }

        return view('admin.students.showAllStudents', compact(
            'groupedStudents',
            'schoolYears',
            'selectedYear',
            'sections',
            'selectedSection',
            'currentYear',
            'schoolYearId',
        ));
    }

    public function create()
    {
        $now = now();
        $year = $now->year;

        // Determine current school year
        $cutoff = now()->copy()->setMonth(6)->setDay(1);
        if ($now->lt($cutoff)) {
            $currentYear = $year - 1;
        } else {
            $currentYear = $year;
        }

        // Generate school year options
        $schoolYears = [
            ($currentYear - 1) . '-' . $currentYear,
            $currentYear . '-' . ($currentYear + 1),
            ($currentYear + 1) . '-' . ($currentYear + 2),
        ];

        // Get selected school year from query, default to current
        $selectedYear = request()->query('school_year', $currentYear . '-' . ($currentYear + 1));

        // Optional flash message if just changed
        if (request()->has('school_year')) {
            session()->flash('school_year_notice', "Selected school year is: $selectedYear");
        }

        return view('admin.students.addStudent', compact('schoolYears', 'selectedYear', 'currentYear'));
    }

    public function store(Request $request)
    {
        // Custom validation message for LRN regex failure
        $messages = [
            'student_lrn.regex' => 'The LRN must start with "112828" and be exactly 12 digits long.',
        ];

        // Validate the incoming request data
        $validatedData = $request->validate([
            'student_lrn' => ['required', 'string', 'max:12', 'regex:/^112828[0-9]{6}$/', 'unique:students,student_lrn'],
            'student_grade_level' => 'required|in:kindergarten,grade1,grade2,grade3,grade4,grade5,grade6',
            'student_section' => 'required|in:A,B,C,D,E,F',
            'student_fName' => 'required|string|max:255',
            'student_mName' => 'nullable|string|max:255',
            'student_lName' => 'required|string|max:255',
            'student_extName' => 'nullable|string|max:45',
            'student_dob' => 'nullable|date',
            'student_sex' => 'required|in:male,female',
            'student_pob' => 'required|string|max:255',
            'enrollment_status' => 'required|in:enrolled,not_enrolled,archived,graduated',
            'enrollment_type' => 'required|in:regular,transferee',
            'house_no' => 'nullable|string|max:255',
            'street_name' => 'nullable|string|max:255',
            'barangay' => 'nullable|string|max:255',
            'municipality_city' => 'nullable|string|max:255',
            'province' => 'nullable|string|max:255',
            'zip_code' => 'nullable|string|max:255',
            'student_fatherFName' => 'nullable|string|max:255',
            'student_fatherMName' => 'nullable|string|max:255',
            'student_fatherLName' => 'nullable|string|max:255',
            'student_fatherPhone' => 'nullable|string|max:255',
            'student_motherFName' => 'nullable|string|max:255',
            'student_motherMName' => 'nullable|string|max:255',
            'student_motherLName' => 'nullable|string|max:255',
            'student_motherPhone' => 'nullable|string|max:255',
            'student_emergcontFName' => 'nullable|string|max:255',
            'student_emergcontMName' => 'nullable|string|max:255',
            'student_emergcontLName' => 'nullable|string|max:255',
            'student_emergcontPhone' => 'nullable|string|max:255',
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
            'country' => 'Philippines',
            'pob' => $request->student_pob,
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
            'emergcont_fName' => $request->student_emergcontFName,
            'emergcont_mName' => $request->student_emergcontMName,
            'emergcont_lName' => $request->student_emergcontLName,
            'emergcont_phone' => $request->student_emergcontPhone,
        ]);

        // Retrieve or create the class for the given year
        $class = Classes::firstOrCreate([
            'grade_level' => $validatedData['student_grade_level'],
            'section' => $validatedData['student_section'],
        ]);

        // Save student information, associating with class, address, and parent info
        $student = Student::create([
            'student_lrn' => $validatedData['student_lrn'],
            'student_fName' => $validatedData['student_fName'],
            'student_mName' => $validatedData['student_mName'] ?? null,
            'student_lName' => $validatedData['student_lName'],
            'student_extName' => $validatedData['student_extName'] ?? null,
            'student_dob' => $validatedData['student_dob'] ?? null,
            'student_sex' => $validatedData['student_sex'],
            'student_photo' => $profilePhotoPath,
            'qr_code' => $qrCode,
            'address_id' => $address->id,
            'parent_id' => $parent->id,
        ]);

        // Get selected school year from the form (or fallback to default logic)
        $selectedSchoolYear = $request->input('selected_school_year');
        if (!$selectedSchoolYear) {
            $selectedSchoolYear = $this->getDefaultSchoolYear(); // fallback just in case
        }

        // Ensure it exists or create it
        [$start, $end] = explode('-', $selectedSchoolYear);
        $schoolYear = SchoolYear::firstOrCreate(
            ['school_year' => $selectedSchoolYear],
            [
                'start_date' => "$start-06-01",
                'end_date' => "$end-03-31",
            ]
        );

        // Inactivate previous enrollments of this student
        DB::table('class_student')
            ->where('student_id', $student->id)
            ->update(['enrollment_status' => 'not_enrolled']);

        // Add new (enrolled) enrollment for this school year
        $student->class()->attach($class->id, [
            'school_year_id' => $schoolYear->id,
            'enrollment_status' => 'enrolled',
            'enrollment_type' => $validatedData['enrollment_type'],
        ]);


        // Redirect back to the student form with success message
        return redirect()->route('add.student')->with('success', 'Student enrolled successfully!');
    }

    public function edit(Request $request, $id)
    {
        $selectedSchoolYear = $request->query('school_year', $this->getDefaultSchoolYear());
        $schoolYear = SchoolYear::where('school_year', $selectedSchoolYear)->firstOrFail();

        $student = Student::with(['address', 'parentInfo', 'class' => function ($query) use ($schoolYear) {
            $query->where('class_student.school_year_id', $schoolYear->id);
        }])->findOrFail($id);

        return view('admin.students.editStudent', compact('student', 'selectedSchoolYear'));
    }

    public function update(Request $request, $id)
    {
        $selectedSchoolYear = $request->input('school_year', $this->getDefaultSchoolYear());
        $schoolYear = SchoolYear::where('school_year', $selectedSchoolYear)->firstOrFail();

        $student = Student::with(['address', 'parentInfo'])->findOrFail($id);

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
            'student_emergcontFName' => 'nullable|string|max:255',
            'student_emergcontMName' => 'nullable|string|max:255',
            'student_emergcontLName' => 'nullable|string|max:255',
            'student_emergcontPhone' => 'nullable|string|max:255',

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
            'emergcont_fName' => $request->student_emergcontFName,
            'emergcont_mName' => $request->student_emergcontMName,
            'emergcont_lName' => $request->student_emergcontLName,
            'emergcont_phone' => $request->student_emergcontPhone,
        ]);

        // Retrieve or create the class for the selected school year
        $class = Classes::firstOrCreate([
            'grade_level' => $validatedData['student_grade_level'],
            'section' => $validatedData['student_section'],
        ]);

        // Update enrollment record for the selected school year
        DB::table('class_student')
            ->where('student_id', $student->id)
            ->where('school_year_id', $schoolYear->id)
            ->update([
                'class_id' => $class->id,
                'updated_at' => now(),
            ]);

        // Update main student fields
        $student->update([
            'student_lrn' => $validatedData['student_lrn'],
            'student_fName' => $validatedData['student_fName'],
            'student_mName' => $validatedData['student_mName'] ?? null,
            'student_lName' => $validatedData['student_lName'],
            'student_extName' => $validatedData['student_extName'] ?? null,
            'student_dob' => $validatedData['student_dob'] ?? null,
            'student_sex' => ucfirst($validatedData['student_sex']),
        ]);

        return redirect()->route('show.students', ['school_year' => $selectedSchoolYear])->with('success', 'Student updated successfully!');
    }

    public function unenroll($id)
    {
        $student = Student::findOrFail($id);

        // Get the current school year
        $currentSchoolYear = $this->getDefaultSchoolYear();
        $currentSchoolYearRecord = SchoolYear::where('school_year', $currentSchoolYear)->first();

        if (!$currentSchoolYearRecord) {
            return redirect()->back()->with('error', 'Current school year not found.');
        }

        // Update the enrollment status to 'not_enrolled' for the current school year
        $updated = DB::table('class_student')
            ->where('student_id', $student->id)
            ->where('school_year_id', $currentSchoolYearRecord->id)
            ->update([
                'class_id' => null, // Remove class association
                'enrollment_status' => 'not_enrolled',
                'updated_at' => now(),
            ]);

        if ($updated === 0) {
            return redirect()->back()->with('error', 'Student is not enrolled in the current school year.');
        }

        return redirect()->route('show.students')->with('success', 'Student unenrolled successfully!');
    }

    public function showStudentInfo($id, Request $request)
    {
        $schoolYearId = $request->query('school_year');

        $student = Student::with(['address', 'parentInfo'])->findOrFail($id);

        $class = $student->class()->where('school_year_id', $schoolYearId)->first();

        $schoolYear = SchoolYear::find($schoolYearId);

        return view('admin.students.student_info', compact('student', 'class', 'schoolYear', 'schoolYearId'));
    }

    public function showPromotionView(Request $request)
    {
        $currentSchoolYear = $this->getDefaultSchoolYear();

        $previousStartYear = explode('-', $currentSchoolYear)[0] - 1;
        $previousSchoolYear = $previousStartYear . '-' . ($previousStartYear + 1);

        $previousSchoolYearRecord = SchoolYear::where('school_year', $previousSchoolYear)->first();

        if (!$previousSchoolYearRecord) {
            return redirect()->back()->with('error', 'Previous school year not found.');
        }

        if (now()->lt(Carbon::parse($previousSchoolYearRecord->end_date))) {
            return redirect()->back()->with('error', 'Promotion is not allowed until the previous school year has ended.');
        }

        // Fetch available sections from the previous school year
        $sections = DB::table('classes')
            ->join('class_student', 'classes.id', '=', 'class_student.class_id')
            ->where('class_student.school_year_id', $previousSchoolYearRecord->id)
            ->distinct()
            ->pluck('section');

        // Set default selectedSection if none provided
        $selectedSection = $request->get('section', $sections->first());
        $gradeLevel = $request->get('grade_level');

        // If grade level and section selected, show promoteStudents view
        if ($gradeLevel && $selectedSection) {
            $activeStudents = DB::table('class_student AS cs_prev')
                ->join('students', 'students.id', '=', 'cs_prev.student_id')
                ->join('classes', 'classes.id', '=', 'cs_prev.class_id')
                ->leftJoin('class_student AS cs_curr', function ($join) use ($currentSchoolYear) {
                    $join->on('cs_curr.student_id', '=', 'cs_prev.student_id')
                        ->where('cs_curr.school_year_id', '=', function ($query) use ($currentSchoolYear) {
                            $query->select('id')
                                ->from('school_years')
                                ->where('school_year', $currentSchoolYear)
                                ->limit(1);
                        });
                })
                ->where('cs_prev.school_year_id', $previousSchoolYearRecord->id)
                ->where('classes.grade_level', $gradeLevel)
                ->where('classes.section', $selectedSection)
                ->where('cs_curr.enrollment_status', 'not_enrolled')
                ->select(
                    'students.*',
                    'cs_prev.class_id',
                    'cs_prev.enrollment_status as previous_enrollment_status',
                    'cs_curr.enrollment_status as current_enrollment_status',
                    'classes.grade_level',
                    'classes.section',
                    'cs_prev.school_year_id'
                )
                ->get();

            return view('admin.students.promoteStudents', compact(
                'activeStudents',
                'gradeLevel',
                'selectedSection',
                'previousSchoolYear',
                'currentSchoolYear'
            ));
        }

        // Otherwise, show the class promotion selection view
        $classes = DB::table('classes')
            ->join('class_student as cs_prev', 'classes.id', '=', 'cs_prev.class_id')
            ->leftJoin('class_student as cs_curr', function ($join) use ($currentSchoolYear) {
                $join->on('cs_curr.student_id', '=', 'cs_prev.student_id')
                    ->where('cs_curr.school_year_id', '=', function ($query) use ($currentSchoolYear) {
                        $query->select('id')
                            ->from('school_years')
                            ->where('school_year', $currentSchoolYear)
                            ->limit(1);
                    });
            })
            ->where('cs_prev.school_year_id', $previousSchoolYearRecord->id)
            ->where('classes.section', $selectedSection)
            ->select(
                'classes.grade_level',
                'classes.section',
                'classes.id',
                DB::raw('COUNT(CASE WHEN cs_curr.enrollment_status = "not_enrolled" THEN 1 END) as promotable_count')
            )
            ->groupBy('classes.grade_level', 'classes.section', 'classes.id')
            ->get();

        return view('admin.students.selectClassPromotion', compact(
            'classes',
            'previousSchoolYear',
            'currentSchoolYear',
            'sections',
            'selectedSection'
        ));
    }

    public function promoteStudents(Request $request)
    {
        $request->validate([
            'selected_students' => 'required|array',
            'batch_grade' => 'required|string',
        ]);

        $grade = $request->input('batch_grade');
        $section = $request->input('batch_section');
        $currentSchoolYearStr = $request->input('next_school_year');

        [$start, $end] = explode('-', $currentSchoolYearStr);
        $schoolYear = SchoolYear::firstOrCreate(
            ['school_year' => $currentSchoolYearStr],
            ['start_date' => "$start-06-01", 'end_date' => "$end-03-31"]
        );

        $newClass = null;
        if ($grade !== 'graduated') {
            $newClass = Classes::firstOrCreate([
                'grade_level' => $grade,
                'section' => $section,
            ]);
        }

        $previousSchoolYearStart = $start - 1;
        $previousSchoolYearStr = $previousSchoolYearStart . '-' . ($previousSchoolYearStart + 1);
        $previousSchoolYear = SchoolYear::where('school_year', $previousSchoolYearStr)->first();

        foreach ($request->selected_students as $studentId) {

            if ($grade === 'graduated') {
                // 🟦 Set previous year enrollment to 'graduated'
                DB::table('class_student')
                    ->where('student_id', $studentId)
                    ->where('school_year_id', $previousSchoolYear->id)
                    ->update([
                        'enrollment_status' => 'graduated',
                        'enrollment_type' => null,
                    ]);

                // 🟥 Remove future enrollment
                DB::table('class_student')
                    ->where('student_id', $studentId)
                    ->where('school_year_id', $schoolYear->id)
                    ->where('enrollment_status', 'not_enrolled')
                    ->delete();
            } else {
                // 🟨 Archive all other enrollments
                DB::table('class_student')
                    ->where('student_id', $studentId)
                    ->where('school_year_id', '!=', $schoolYear->id)
                    ->update(['enrollment_status' => 'archived']);

                // 🟩 Promote student or insert if needed
                $previousEnrollment = DB::table('class_student')
                    ->where('student_id', $studentId)
                    ->where('school_year_id', $previousSchoolYear->id)
                    ->first();

                $enrollmentType = ($previousEnrollment && $previousEnrollment->class_id === $newClass->id) ? 'returnee' : 'regular';

                $updated = DB::table('class_student')
                    ->where('student_id', $studentId)
                    ->where('school_year_id', $schoolYear->id)
                    ->where('enrollment_status', 'not_enrolled')
                    ->update([
                        'class_id' => $newClass->id,
                        'enrollment_status' => 'enrolled',
                        'enrollment_type' => $enrollmentType,
                        'updated_at' => now(),
                    ]);

                if ($updated === 0) {
                    DB::table('class_student')->insert([
                        'student_id' => $studentId,
                        'class_id' => $newClass->id,
                        'school_year_id' => $schoolYear->id,
                        'enrollment_status' => 'enrolled',
                        'enrollment_type' => $enrollmentType,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }

        return redirect()->route('students.promote.view')->with('success', 'Selected students promoted successfully!');
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
