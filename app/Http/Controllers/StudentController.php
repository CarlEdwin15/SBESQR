<?php

namespace App\Http\Controllers;

use App\Exports\StudentTemplateExport;
use App\Models\Classes;
use App\Models\ClassStudent;
use App\Models\ClassSubject;
use App\Models\Payment;
use App\Models\Student;
use App\Models\StudentAddress;
use App\Models\SchoolYear;
use App\Models\User;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\StudentsImport;

class StudentController extends Controller
{
    // For searching students (global)
    public function search(Request $request)
    {
        $term = $request->get('q');

        $students = Student::query()
            ->where('student_lrn', 'like', "%{$term}%")
            ->orWhere(function ($q) use ($term) {
                $q->where('student_fName', 'like', "%{$term}%")
                    ->orWhere('student_lName', 'like', "%{$term}%");
            })
            ->limit(10)
            ->get(['id', 'student_lrn', 'student_fName', 'student_lName']);

        return response()->json($students);
    }

    // For searching students NOT currently enrolled in the selected school year
    public function searchNotEnrolled(Request $request)
    {
        $term = trim($request->get('q'));
        $selectedYear = $request->get('school_year');

        $schoolYear = SchoolYear::where('school_year', $selectedYear)->first();

        if (!$schoolYear) {
            return response()->json([]);
        }

        // Get students who are actively enrolled in this year (to exclude them)
        $enrolledIds = ClassStudent::where('school_year_id', $schoolYear->id)
            ->where('enrollment_status', 'enrolled')
            ->pluck('student_id')
            ->toArray();

        // Get students who are marked as graduated in ANY school year (to exclude them)
        $graduatedIds = ClassStudent::where('enrollment_status', 'graduated')
            ->pluck('student_id')
            ->toArray();

        // Combine both lists of IDs to exclude
        $excludedIds = array_unique(array_merge($enrolledIds, $graduatedIds));

        // Include students who are NOT enrolled AND NOT graduated
        $students = Student::query()
            ->whereNotIn('id', $excludedIds)
            ->where(function ($q) use ($term) {
                $q->where('student_lrn', 'like', "%{$term}%")
                    ->orWhere('student_fName', 'like', "%{$term}%")
                    ->orWhere('student_mName', 'like', "%{$term}%")
                    ->orWhere('student_lName', 'like', "%{$term}%");
            })
            ->limit(10)
            ->get(['id', 'student_lrn', 'student_fName', 'student_mName', 'student_lName']);

        return response()->json($students);
    }

    // For searching students whose currently enrolled to current school year
    public function classStudentSearch(Request $request)
    {
        $term = trim($request->get('q'));

        // Get default school year string
        $defaultSchoolYear = $this->getDefaultSchoolYear();
        $schoolYear = SchoolYear::where('school_year', $defaultSchoolYear)->first();

        if (!$schoolYear) {
            return response()->json([]);
        }

        // Query enrolled students for that school year
        $students = ClassStudent::with(['student', 'class'])
            ->where('school_year_id', $schoolYear->id)
            ->where('enrollment_status', 'enrolled')
            ->where(function ($q) use ($term) {
                // Student search
                $q->whereHas('student', function ($studentQ) use ($term) {
                    $studentQ->where('student_lrn', 'like', "%{$term}%")
                        ->orWhereRaw("CONCAT(student_fName, ' ', student_lName) LIKE ?", ["%{$term}%"]);
                })
                    // Class search (formatted grade level + section)
                    ->orWhereHas('class', function ($classQ) use ($term) {
                        $classQ->whereRaw("CONCAT(
                        CASE grade_level
                            WHEN 'kindergarten' THEN 'Kindergarten'
                            WHEN 'grade1' THEN 'Grade 1'
                            WHEN 'grade2' THEN 'Grade 2'
                            WHEN 'grade3' THEN 'Grade 3'
                            WHEN 'grade4' THEN 'Grade 4'
                            WHEN 'grade5' THEN 'Grade 5'
                            WHEN 'grade6' THEN 'Grade 6'
                            ELSE grade_level
                        END,
                        ' - ',
                        section
                    ) LIKE ?", ["%{$term}%"]);
                    });
            })
            ->limit(10)
            ->get();

        return response()->json($students->map(function ($cs) {
            return [
                'id' => $cs->id,
                'student' => [
                    'student_lrn'   => $cs->student->student_lrn,
                    'student_fName' => $cs->student->student_fName,
                    'student_lName' => $cs->student->student_lName,
                ],
                'class' => [
                    'formatted_grade_level' => $cs->class->formatted_grade_level ?? '',
                    'section'               => $cs->class->section ?? '',
                ],
            ];
        }));
    }

    // For searching students whose currently enrolled but not included to the selected payment
    public function classStudentSearchExcludePayment(Request $request)
    {
        $term        = trim($request->get('q'));
        $year        = $request->get('year');
        $paymentName = $request->get('payment_name');

        if (!$year || !$paymentName) {
            Log::info("Missing year or payment name", compact('year', 'paymentName'));
            return response()->json([]);
        }

        $schoolYear = SchoolYear::where('school_year', $year)->first();
        if (!$schoolYear) {
            Log::info("School year not found", ['year' => $year]);
            return response()->json([]);
        }

        // Get IDs of students who already have this payment (including soft-deleted)
        $excludedIds = Payment::withTrashed()
            ->where('payment_name', $paymentName)
            ->pluck('class_student_id')
            ->toArray();

        Log::info("Excluded IDs", $excludedIds);

        $students = ClassStudent::with(['student', 'class'])
            ->where('school_year_id', $schoolYear->id)
            ->where('enrollment_status', 'enrolled')
            ->whereNotIn('id', $excludedIds)
            ->when($term, function ($q) use ($term) {
                $q->whereHas('student', function ($studentQ) use ($term) {
                    $studentQ->where('student_lrn', 'like', "%{$term}%")
                        ->orWhereRaw("CONCAT(student_fName, ' ', student_lName) LIKE ?", ["%{$term}%"]);
                });
            })
            ->limit(10)
            ->get();

        Log::info("Found students", $students->pluck('id')->toArray());

        return response()->json($students->map(function ($cs) {
            return [
                'id'   => $cs->id,
                'text' => $cs->student->student_lrn . ' ' .
                    $cs->student->student_fName . ' ' .
                    $cs->student->student_lName .
                    ' (' . ($cs->class->formatted_grade_level ?? '') .
                    ($cs->class->section ? ' - ' . $cs->class->section : '') . ')',
            ];
        }));
    }

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

        // Filter to include only saved years <= current school year
        $schoolYears = collect($savedYears)
            ->filter(function ($sy) use ($currentYear) {
                $start = (int) substr($sy, 0, 4);
                return $start <= $currentYear;
            })
            ->values()
            ->toArray();

        // Ensure current year is included if missing
        if (!in_array($currentSchoolYear, $schoolYears)) {
            $schoolYears[] = $currentSchoolYear;
        }

        // Sort ascending
        usort($schoolYears, fn($a, $b) => intval(substr($a, 0, 4)) <=> intval(substr($b, 0, 4)));

        $selectedYear = $request->query('school_year', $currentSchoolYear);
        $selectedSection = $request->query('section');
        $selectedEnrollmentType = $request->query('enrollment_type', 'all'); // New filter

        $schoolYear = SchoolYear::where('school_year', $selectedYear)->first();
        $currentSchoolYearRecord = SchoolYear::where('school_year', $currentSchoolYear)->first();

        $schoolYearId = optional($schoolYear)->id;

        // 🔥 NEW: Auto-graduate eligible Grade 6 students for previous school years
        if ($schoolYear && $schoolYear->school_year !== $currentSchoolYear) {
            $this->autoGraduateEligibleStudents($schoolYear, $currentSchoolYear);
        }

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

            $students = Student::whereHas('class', function ($query) use ($schoolYear, $selectedSection, $selectedEnrollmentType) {
                $query->where('class_student.school_year_id', $schoolYear->id);

                if (!empty($selectedSection)) {
                    $query->where('section', $selectedSection);
                }

                // NEW: Filter by enrollment type
                if ($selectedEnrollmentType !== 'all') {
                    $query->where('class_student.enrollment_type', $selectedEnrollmentType);
                }
            })->with([
                'address',
                'parents',
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

        return view('admin.students.studentEnrollment', compact(
            'groupedStudents',
            'schoolYears',
            'selectedYear',
            'sections',
            'selectedSection',
            'currentYear',
            'schoolYearId',
            'selectedEnrollmentType' // Pass the selected enrollment type to view
        ));
    }

    public function assignClass(Request $request)
    {
        $validated = $request->validate([
            'students' => 'required|array',
            'students.*' => 'exists:students,id',
            'grade_level' => 'required|in:kindergarten,grade1,grade2,grade3,grade4,grade5,grade6',
            'section' => 'required|in:A,B,C,D,E,F',
            'school_year' => 'required|exists:school_years,school_year',
            'enrollment_type' => 'required|in:regular,transferee,returnee',
        ]);

        $class = Classes::firstOrCreate([
            'grade_level' => $validated['grade_level'],
            'section' => $validated['section'],
        ]);

        $schoolYear = SchoolYear::where('school_year', $validated['school_year'])->firstOrFail();

        foreach ($validated['students'] as $studentId) {
            // Avoid duplicate enrollments in the same SY
            DB::table('class_student')
                ->where('student_id', $studentId)
                ->where('school_year_id', $schoolYear->id)
                ->delete();

            DB::table('class_student')->insert([
                'student_id' => $studentId,
                'class_id' => $class->id,
                'school_year_id' => $schoolYear->id,
                'enrollment_status' => 'enrolled',
                'enrollment_type' => $validated['enrollment_type'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return redirect()->back()->with('success', 'Selected students enrolled successfully!');
    }

    public function studentManagement()
    {
        // Determine the current school year
        $now = now();
        $year = $now->year;
        $cutoff = $now->copy()->setMonth(6)->setDay(1);
        $currentYear = $now->lt($cutoff) ? $year - 1 : $year;
        $currentSchoolYear = $currentYear . '-' . ($currentYear + 1);

        $schoolYear = SchoolYear::where('school_year', $currentSchoolYear)->first();
        $schoolYearId = optional($schoolYear)->id;

        // Get students with their latest enrollments and related data
        $students = Student::with([
            'address',
            'classStudents.schoolYear' => function ($query) {
                $query->orderBy('created_at', 'desc');
            }
        ])->get();

        $students->each(function ($student) use ($schoolYearId) {
            // Find the enrollment for the *current* school year first
            $currentEnrollment = $student->classStudents
                ->firstWhere('school_year_id', $schoolYearId);

            if ($currentEnrollment) {
                $student->status = $currentEnrollment->enrollment_status;
            } else {
                // Fallback to their most recent record if not currently enrolled
                $latestEnrollment = $student->classStudents->sortByDesc('created_at')->first();
                $student->status = $latestEnrollment->enrollment_status ?? 'not_enrolled';
            }

            // Determine graduated school year
            $graduatedRecord = $student->classStudents->firstWhere('enrollment_status', 'graduated');
            if ($graduatedRecord && $graduatedRecord->schoolYear) {
                $student->graduated_school_year = $graduatedRecord->schoolYear->school_year;
            } else {
                $student->graduated_school_year = null;
            }
        });

        // Sort alphabetically
        $students = $students->sortBy('full_name', SORT_NATURAL | SORT_FLAG_CASE);

        return view('admin.students.studentManagement', compact('students', 'schoolYearId'));
    }

    public function store(Request $request)
    {
        $messages = [
            'student_lrn.regex' => 'The LRN must start with "112828" and be exactly 12 digits long.',
        ];

        $validatedData = $request->validate([
            'student_lrn' => ['required', 'string', 'max:12', 'regex:/^112828[0-9]{6}$/', 'unique:students,student_lrn'],
            'student_fName' => 'required|string|max:255',
            'student_mName' => 'nullable|string|max:255',
            'student_lName' => 'required|string|max:255',
            'student_extName' => 'nullable|string|max:45',
            'student_dob' => 'nullable|date',
            'student_sex' => 'required|in:male,female',
            'student_pob' => 'required|string|max:255',
            'student_profile_photo' => 'nullable|image|mimes:jpeg,png|max:2048',
        ], $messages);

        // Handle profile photo
        $profilePhotoPath = $request->hasFile('student_profile_photo')
            ? $request->file('student_profile_photo')->store('student_profile_photos', 'public')
            : null;

        // QR Code
        $qrCode = uniqid('QR');

        // Address
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

        // Create Student
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
        ]);

        return redirect()->route('student.management')->with('success', 'Student added successfully! You can now assign them to a class.');
    }

    public function import(Request $request)
    {
        ini_set('max_execution_time', 120);

        $request->validate([
            'excel_file' => 'required|file|mimetypes:text/csv,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);

        try {
            $file = $request->file('excel_file');

            // Quick lightweight validation before fully loading spreadsheet
            $ext = strtolower($file->getClientOriginalExtension());
            $validExtensions = ['xlsx', 'xls', 'csv'];

            if (!in_array($ext, $validExtensions)) {
                return back()->with('error', "Invalid file type. Please upload a .xlsx, .xls, or .csv file.")->withInput();
            }

            // Limit file size to avoid long parsing on huge/malformed files (optional safety)
            if ($file->getSize() > 5 * 1024 * 1024) {
                return back()->with('error', "File too large. Please upload a file smaller than 5MB.")->withInput();
            }

            // FAST HEADER VALIDATION (before loading full workbook)
            $firstBytes = file_get_contents($file->getRealPath(), false, null, 0, 4096); // only read first 4KB
            if (trim($firstBytes) === '') {
                return back()->with('error', "The uploaded file appears empty. Please use the proper student template.")->withInput();
            }

            // For CSV files → directly read first line instead of loading PhpSpreadsheet
            if ($ext === 'csv') {
                $handle = fopen($file->getRealPath(), 'r');
                $firstRow = fgetcsv($handle);
                fclose($handle);

                if (!$firstRow) {
                    return back()->with('error', "The CSV file is empty or unreadable.")->withInput();
                }

                // 🩹 Normalize headers: trim, remove quotes/BOM, and lowercase
                $normalizedHeaders = array_map(function ($h) {
                    $h = trim($h, " \t\n\r\0\x0B\"'");
                    // Remove UTF-8 BOM if present
                    $h = preg_replace('/^\xEF\xBB\xBF/', '', $h);
                    return strtolower($h);
                }, $firstRow);

                if (!in_array('lrn', $normalizedHeaders)) {
                    return back()->with('error', "Missing required 'lrn' column. Please use the provided Excel template.")->withInput();
                }
            }

            // Safe full load only after confirming file has content
            $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReaderForFile($file->getPathname());
            $reader->setReadDataOnly(true); // make it faster by ignoring styles
            $spreadsheet = $reader->load($file->getPathname());
            $sheet = $spreadsheet->getActiveSheet();

            // Header validation (your existing logic)
            $firstRow = null;
            for ($i = 1; $i <= 5; $i++) {
                $row = $sheet->rangeToArray("A{$i}:N{$i}", null, true, false)[0];
                if (in_array('lrn', array_map('strtolower', $row))) {
                    $firstRow = $row;
                    break;
                }
            }

            if (!$firstRow) {
                return back()->with('error', " Missing required column: 'lrn'. Please use the provided Excel template.")->withInput();
            }

            $headers = array_map(fn($h) => strtolower(trim($h)), $firstRow);
            $requiredHeaders = [
                'lrn',
                'first_name',
                'middle_name',
                'last_name',
                'extension_name',
                'dob',
                'sex',
                'place_of_birth',
                'house_no',
                'street_name',
                'barangay',
                'municipality_city',
                'province',
                'zip_code'
            ];

            foreach ($requiredHeaders as $header) {
                if (!in_array($header, $headers)) {
                    return back()->with('error', " Missing required column: '{$header}'. Please use the provided Excel template.")->withInput();
                }
            }

            // Proceed with import only after passing header check
            $import = new \App\Imports\StudentsImport();
            \Maatwebsite\Excel\Facades\Excel::import($import, $file);

            $summary = " Imported: {$import->imported}";
            if ($import->duplicates > 0) $summary .= " | Duplicates: {$import->duplicates}";
            $errorDetails = implode("<br>", $import->errors);

            return redirect()->route('student.management')->with([
                'success' => $summary,
                'import_errors' => $errorDetails ?: null,
                'imported_count' => $import->imported,
                'import_status' => $import->imported > 0 ? 'success' : 'error',
            ]);
        } catch (\PhpOffice\PhpSpreadsheet\Reader\Exception $e) {
            return back()->with('error', "🚫 Invalid or corrupted Excel file. Please use the official student template.")->withInput();
        } catch (\Exception $e) {
            return back()->with('error', "🚫 Import failed: {$e->getMessage()}")->withInput();
        }
    }

    public function downloadTemplate()
    {
        return \Maatwebsite\Excel\Facades\Excel::download(
            new \App\Exports\StudentTemplateExport,
            'student_template.xlsx'
        );
    }

    public function edit(Request $request, $id)
    {
        $selectedSchoolYear = $request->query('school_year', $this->getDefaultSchoolYear());
        $schoolYear = SchoolYear::where('school_year', $selectedSchoolYear)->firstOrFail();

        $student = Student::with(['address', 'parents', 'class' => function ($query) use ($schoolYear) {
            $query->where('class_student.school_year_id', $schoolYear->id);
        }])->findOrFail($id);

        // Determine student status for this school year
        $currentEnrollment = $student->classStudents()
            ->where('school_year_id', $schoolYear->id)
            ->first();

        $studentStatus = $currentEnrollment ? $currentEnrollment->enrollment_status : 'not_enrolled';
        $isActive = $studentStatus === 'enrolled';

        return view('admin.students.editStudent', [
            'student' => $student,
            'selectedSchoolYear' => $selectedSchoolYear,
            'schoolYearId' => $schoolYear->id,
            'isActive' => $isActive,
            'studentStatus' => $studentStatus,
        ]);
    }

    public function update(Request $request, $id)
    {
        $student = Student::findOrFail($id);

        // Get the student's current status
        $selectedSchoolYear = $request->input('selected_school_year') ?? $this->getDefaultSchoolYear();
        $schoolYear = SchoolYear::where('school_year', $selectedSchoolYear)->first();

        $currentEnrollment = $student->classStudents()
            ->where('school_year_id', $schoolYear->id)
            ->first();

        $isActive = $currentEnrollment && $currentEnrollment->enrollment_status === 'enrolled';

        // Base validation rules (always required)
        $validationRules = [
            'student_lrn' => [
                'required',
                'string',
                'max:12',
                'regex:/^112828[0-9]{6}$/',
                'unique:students,student_lrn,' . $student->id . ',id',
            ],
            'student_fName' => 'required|string|max:255',
            'student_mName' => 'nullable|string|max:255',
            'student_lName' => 'required|string|max:255',
            'student_extName' => 'nullable|string|max:45',
            'student_dob' => 'nullable|date',
            'student_sex' => 'required|in:male,female',
            'student_pob' => 'required|string|max:255',
            'student_parentEmail' => [
                'nullable',
                'string',
                'max:255',
                'email',
                function ($attribute, $value, $fail) {
                    if (!$value) return;
                    $user = User::where('email', $value)->first();
                    if ($user && $user->role !== 'parent') {
                        $fail('This email is already used for ' . $user->role . ' account');
                    }
                },
            ],
            'student_profile_photo' => 'nullable|image|mimes:jpeg,png|max:2048',
        ];

        // Only require class fields if student is active
        if ($isActive) {
            $validationRules['student_grade_level'] = 'required|in:kindergarten,grade1,grade2,grade3,grade4,grade5,grade6';
            $validationRules['student_section'] = 'required|in:A,B,C,D,E,F';
            $validationRules['enrollment_type'] = 'required|in:regular,transferee,returnee';
        } else {
            // Make class fields optional for inactive students
            $validationRules['student_grade_level'] = 'nullable|in:kindergarten,grade1,grade2,grade3,grade4,grade5,grade6';
            $validationRules['student_section'] = 'nullable|in:A,B,C,D,E,F';
            $validationRules['enrollment_type'] = 'nullable|in:regular,transferee,returnee';
        }

        $validatedData = $request->validate($validationRules);

        // Handle profile photo
        if ($request->hasFile('student_profile_photo')) {
            $profilePhotoPath = $request->file('student_profile_photo')->store('student_profile_photos', 'public');
        } else {
            $profilePhotoPath = $student->student_photo;
        }

        // Update Address
        if ($student->address) {
            $student->address->update([
                'house_no' => $request->house_no,
                'street_name' => $request->street_name,
                'barangay' => $request->barangay,
                'municipality_city' => $request->municipality_city,
                'province' => $request->province,
                'zip_code' => $request->zip_code,
                'country' => 'Philippines',
                'pob' => $request->student_pob,
            ]);
        }

        // Update Student
        $student->update([
            'student_lrn' => $validatedData['student_lrn'],
            'student_fName' => $validatedData['student_fName'],
            'student_mName' => $validatedData['student_mName'] ?? null,
            'student_lName' => $validatedData['student_lName'],
            'student_extName' => $validatedData['student_extName'] ?? null,
            'student_dob' => $validatedData['student_dob'] ?? null,
            'student_sex' => $validatedData['student_sex'],
            'student_photo' => $profilePhotoPath,
        ]);

        // Handle Parent User
        if ($request->student_parentEmail) {
            $parent = User::where('email', $request->student_parentEmail)
                ->where('role', 'parent')
                ->first();

            if (!$parent) {
                $parent = User::create([
                    'firstName' => $request->student_fatherFName ?? $request->student_motherFName ?? 'Parent',
                    'lastName'  => $request->student_fatherLName ?? $request->student_motherLName ?? 'Unknown',
                    'email'     => $request->student_parentEmail,
                    'role'      => 'parent',
                    'password'  => bcrypt('default123'),
                    'phone'     => $request->student_fatherPhone ?? $request->student_motherPhone,
                ]);
            }

            // Attach or sync parent pivot
            $student->parents()->syncWithoutDetaching([$parent->id]);
        }

        // Only update class information if student is active AND class fields are provided
        if ($isActive && $request->filled('student_grade_level') && $request->filled('student_section')) {
            // Get selected school year
            $selectedSchoolYear = $request->input('selected_school_year') ?? $this->getDefaultSchoolYear();
            [$start, $end] = explode('-', $selectedSchoolYear);

            $schoolYear = SchoolYear::firstOrCreate(
                ['school_year' => $selectedSchoolYear],
                [
                    'start_date' => "$start-06-01",
                    'end_date'   => "$end-03-31",
                ]
            );

            // Find or create the class
            $class = Classes::firstOrCreate([
                'grade_level' => $validatedData['student_grade_level'],
                'section'     => $validatedData['student_section'],
            ]);

            // Check if student already has a class record for this school year
            $existingPivot = $student->class()
                ->wherePivot('school_year_id', $schoolYear->id)
                ->first();

            // Determine enrollment type
            $enrollmentType = $validatedData['enrollment_type'] ?? null;

            if ($existingPivot) {
                if (!$enrollmentType) {
                    // fallback to old enrollment type if none provided
                    $enrollmentType = $existingPivot->pivot->enrollment_type;
                }

                // Update existing pivot row
                $student->class()->updateExistingPivot($existingPivot->id, [
                    'class_id'        => $class->id,
                    'school_year_id'  => $schoolYear->id,
                    'enrollment_type' => $enrollmentType,
                ]);
            } else {
                // If no pivot exists for this school year, attach new
                $student->class()->attach($class->id, [
                    'school_year_id'  => $schoolYear->id,
                    'enrollment_type' => $enrollmentType ?? 'regular',
                ]);
            }
        }

        return redirect()->back()->with('success', 'Student updated successfully!');
    }

    public function showStudentInfo($id, Request $request)
    {
        $schoolYearId = $request->query('school_year');

        $student = Student::with(['address', 'parents', 'classStudents.schoolYear', 'class'])->findOrFail($id);

        $class = $student->class()->where('school_year_id', $schoolYearId)->first();
        $schoolYear = SchoolYear::find($schoolYearId);

        // Determine status
        $latestEnrollment = $student->classStudents()->latest()->first();
        $studentStatus = $latestEnrollment->enrollment_status ?? 'not_enrolled';

        // Determine additional info
        $statusInfo = null;

        if ($studentStatus === 'enrolled' && $class) {
            $grade = $class->formatted_grade_level ?? null;
            $section = $class->section ?? null;
            $gradeSection = $grade ? $grade . ($section ? ' - ' . $section : '') : null;

            $statusInfo = $gradeSection
                ? "{$gradeSection} for SY {$schoolYear->school_year}"
                : "For SY {$schoolYear->school_year}";
        } elseif (in_array($studentStatus, ['archived', 'not_enrolled'])) {
            $lastEnrollment = $student->classStudents()
                ->where('enrollment_status', 'enrolled')
                ->latest()
                ->first();

            $lastSY = $lastEnrollment?->schoolYear?->school_year;
            $statusInfo = $lastSY ? "Last Enrolled: {$lastSY}" : 'No recent enrollment';
        } elseif ($studentStatus === 'graduated') {
            $graduatedRecord = $student->classStudents()
                ->where('enrollment_status', 'graduated')
                ->latest()
                ->first();

            $gradSY = $graduatedRecord?->schoolYear?->school_year;
            $statusInfo = $gradSY ? "Graduated: {$gradSY}" : 'Graduation year not recorded';
        }

        // --- Class history (for grade tabs) ---
        $classHistory = $student->class()
            ->with(['schoolYear', 'advisers' => function ($q) {
                $q->wherePivot('role', 'adviser');
            }])
            ->get();

        $classHistory = $classHistory
            ->sortByDesc(fn($c) => $c->pivot->enrollment_status === 'enrolled' ? 2 : 1)
            ->sortByDesc(fn($c) => $c->pivot->school_year_id)
            ->values();

        // --- Grades per class ---
        $gradesByClass = [];
        $generalAverages = [];

        foreach ($classHistory as $classItem) {
            $classSubjects = $classItem->classSubjects()
                ->with(['subject', 'quarters.quarterlyGrades' => function ($q) use ($student) {
                    $q->where('student_id', $student->id);
                }])
                ->where('school_year_id', $classItem->pivot->school_year_id)
                ->get();

            $subjectsWithGrades = [];
            $finalGrades = [];

            foreach ($classSubjects as $classSubject) {
                $quarters = $classSubject->quarters->map(function ($quarter) use ($student) {
                    return [
                        'quarter' => $quarter->quarter,
                        'grade' => optional($quarter->quarterlyGrades->first())->final_grade,
                    ];
                });

                $allQuartersHaveGrades = $quarters->every(fn($q) => $q['grade'] !== null);

                $finalAverage = null;
                $remarks = null;

                if ($allQuartersHaveGrades) {
                    $grades = $quarters->pluck('grade')->all();
                    $finalAverage = round(array_sum($grades) / 4, 2);
                    $remarks = $finalAverage >= 75 ? 'passed' : 'failed';
                    $finalGrades[] = $finalAverage;
                }

                $subjectsWithGrades[] = [
                    'subject' => $classSubject->subject->name,
                    'quarters' => $quarters,
                    'final_average' => $finalAverage,
                    'remarks' => $remarks,
                ];
            }

            $gradesByClass[$classItem->id] = $subjectsWithGrades;

            // Compute General Average - MATCHING GRADE SLIP LOGIC
            $totalSubjects = count($classSubjects);
            $completedSubjects = count($finalGrades);

            if ($totalSubjects > 0 && $completedSubjects === $totalSubjects) {
                // Apply DepEd rounding to each subject's final average FIRST
                $roundedFinalGrades = array_map('round', $finalGrades);

                // Then calculate general average from rounded grades
                $generalAverage = round(array_sum($roundedFinalGrades) / $completedSubjects);
                $remarks = $generalAverage >= 75 ? 'passed' : 'failed';

                $generalAverages[$classItem->id] = [
                    'general_average' => $generalAverage,
                    'remarks' => $remarks,
                ];
            } else {
                $generalAverages[$classItem->id] = null;
            }
        }

        // Store back URL
        $previous = url()->previous();
        if ($previous !== $request->fullUrl()) {
            session(['back_url' => $previous]);
        }

        return view('admin.students.student_info', compact(
            'student',
            'class',
            'schoolYear',
            'schoolYearId',
            'classHistory',
            'gradesByClass',
            'generalAverages',
            'studentStatus',
            'statusInfo'
        ));
    }

    public function delete($id)
    {
        $student = Student::findOrFail($id);

        // Delete related records
        $student->parents()->detach();
        $student->class()->detach();
        $student->classStudents()->delete();
        $student->payments()->delete();
        $student->address()->delete();

        // Finally, delete the student
        $student->delete();

        return redirect()->route('student.management')->with('success', 'Student and all related records have been deleted.');
    }

    public function unenroll(Request $request, $id)
    {
        $student = Student::findOrFail($id);

        // 🧩 Determine target school year (either passed in request or default)
        $schoolYearString = $request->input('school_year') ?? $this->getDefaultSchoolYear();

        $schoolYear = SchoolYear::where('school_year', $schoolYearString)->first();

        if (!$schoolYear) {
            return redirect()->back()->with('error', 'Selected school year not found.');
        }

        // 🧩 Find the class_student pivot entry for this student & school year
        $classStudent = DB::table('class_student')
            ->where('student_id', $student->id)
            ->where('school_year_id', $schoolYear->id)
            ->first();

        if (!$classStudent) {
            return redirect()->back()->with('error', 'Student is not enrolled in the selected school year.');
        }

        // 🧩 Unenroll student (preserve record, but mark as not enrolled)
        DB::table('class_student')
            ->where('student_id', $student->id)
            ->where('school_year_id', $schoolYear->id)
            ->update([
                'class_id' => null, // remove class link
                'enrollment_status' => 'not_enrolled',
                'enrollment_type' => null,
                'updated_at' => now(),
            ]);

        return redirect()
            ->route('show.students', ['school_year' => $schoolYear->school_year])
            ->with('success', "{$student->student_fName} {$student->student_lName} has been unenrolled from {$schoolYear->school_year}.");
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids', []);

        if (empty($ids)) {
            return response()->json(['message' => 'No students selected.'], 400);
        }

        $students = Student::whereIn('id', $ids)->get();

        foreach ($students as $student) {
            $student->parents()->detach();
            $student->class()->detach();
            $student->classStudents()->delete();
            $student->payments()->delete();
            $student->address()->delete();
            $student->delete();
        }

        return response()->json(['message' => 'Selected students deleted successfully.']);
    }

    // public function searchClasses(Request $request)
    // {
    //     $searchTerm = $request->get('search');
    //     $schoolYear = $request->get('school_year');
    //     $section = $request->get('section');

    //     $classes = DB::table('classes')
    //         ->join('class_student as cs_selected', 'classes.id', '=', 'cs_selected.class_id')
    //         ->join('students', 'students.id', '=', 'cs_selected.student_id')
    //         ->leftJoin('class_student as cs_curr', function ($join) use ($schoolYear) {
    //             $currentSchoolYearRecord = SchoolYear::where('school_year', '>', $schoolYear)->first();
    //             if ($currentSchoolYearRecord) {
    //                 $join->on('cs_curr.student_id', '=', 'cs_selected.student_id')
    //                     ->where('cs_curr.school_year_id', $currentSchoolYearRecord->id);
    //             }
    //         })
    //         ->where('cs_selected.school_year_id', function ($query) use ($schoolYear) {
    //             $query->select('id')
    //                 ->from('school_years')
    //                 ->where('school_year', $schoolYear)
    //                 ->limit(1);
    //         })
    //         ->where('classes.section', $section)
    //         ->where(function ($query) use ($searchTerm) {
    //             $query->where('students.student_fName', 'like', "%{$searchTerm}%")
    //                 ->orWhere('students.student_lName', 'like', "%{$searchTerm}%")
    //                 ->orWhere('students.student_mName', 'like', "%{$searchTerm}%")
    //                 ->orWhere('students.student_lrn', 'like', "%{$searchTerm}%");
    //         })
    //         ->select(
    //             'classes.grade_level',
    //             'classes.section',
    //             'classes.id',
    //             DB::raw('COUNT(DISTINCT cs_selected.student_id) as total_students'),
    //             DB::raw('COUNT(CASE WHEN cs_curr.enrollment_status = "enrolled" THEN 1 END) as reenrolled_count'),
    //             DB::raw('COUNT(CASE WHEN cs_selected.enrollment_status = "graduated" THEN 1 END) as graduated_count')
    //         )
    //         ->groupBy('classes.grade_level', 'classes.section', 'classes.id')
    //         ->get();

    //     return response()->json($classes);
    // }

    public function showPromotionView(Request $request)
    {
        $currentSchoolYear = $this->getDefaultSchoolYear();
        $defaultSchoolYear = $this->getDefaultSchoolYear();

        $previousStartYear = explode('-', $currentSchoolYear)[0] - 1;
        $previousSchoolYear = $previousStartYear . '-' . ($previousStartYear + 1);

        // Get available school years - only previous years (before current school year)
        $availableSchoolYears = SchoolYear::where('school_year', '<', $currentSchoolYear)
            ->orderBy('school_year', 'desc')
            ->pluck('school_year')
            ->toArray();

        // If no previous school years found, use only the immediate previous year
        if (empty($availableSchoolYears)) {
            $availableSchoolYears = [$previousSchoolYear];
        }

        // Set selected school year (default to previous school year)
        $selectedSchoolYear = $request->get('school_year', $previousSchoolYear);
        $selectedSchoolYearRecord = SchoolYear::where('school_year', $selectedSchoolYear)->first();

        if (!$selectedSchoolYearRecord) {
            // If selected school year doesn't exist, default to the first available previous year
            $selectedSchoolYear = $availableSchoolYears[0] ?? $previousSchoolYear;
            $selectedSchoolYearRecord = SchoolYear::where('school_year', $selectedSchoolYear)->first();

            if (!$selectedSchoolYearRecord) {
                return redirect()->back()->with('error', 'No valid previous school years found.');
            }
        }

        // Only check for promotion eligibility if it's the previous school year
        if ($selectedSchoolYear === $previousSchoolYear) {
            if (now()->lt(Carbon::parse($selectedSchoolYearRecord->end_date))) {
                return redirect()->back()->with('error', 'Promotion is not allowed until the school year has ended.');
            }

            // Auto-graduate eligible students only for previous school year
            $graduatedCount = $this->autoGraduateEligibleStudents($selectedSchoolYearRecord, $currentSchoolYear);
            if ($graduatedCount > 0) {
                session()->flash('auto_graduated', "Automatically graduated {$graduatedCount} eligible Grade 6 students.");
            }
        }

        // Fetch available sections from the selected school year
        $sections = DB::table('classes')
            ->join('class_student', 'classes.id', '=', 'class_student.class_id')
            ->where('class_student.school_year_id', $selectedSchoolYearRecord->id)
            ->distinct()
            ->pluck('section');

        // Set default selectedSection if none provided
        $selectedSection = $request->get('section', $sections->first());
        $gradeLevel = $request->get('grade_level');

        // If grade level and section selected, show promoteStudents view
        if ($gradeLevel && $selectedSection) {
            $currentSchoolYearRecord = SchoolYear::where('school_year', $currentSchoolYear)->first();

            // Get students for the selected school year and section
            $activeStudents = DB::table('class_student AS cs_selected')
                ->join('students', 'students.id', '=', 'cs_selected.student_id')
                ->join('classes', 'classes.id', '=', 'cs_selected.class_id')
                ->leftJoin('class_student AS cs_curr', function ($join) use ($currentSchoolYearRecord) {
                    $join->on('cs_curr.student_id', '=', 'cs_selected.student_id')
                        ->where('cs_curr.school_year_id', $currentSchoolYearRecord->id ?? null);
                })
                ->leftJoin('classes AS current_class', function ($join) {
                    $join->on('current_class.id', '=', 'cs_curr.class_id');
                })
                ->leftJoin('general_averages', function ($join) use ($selectedSchoolYearRecord) {
                    $join->on('general_averages.student_id', '=', 'students.id')
                        ->where('general_averages.school_year_id', $selectedSchoolYearRecord->id);
                })
                ->where('cs_selected.school_year_id', $selectedSchoolYearRecord->id)
                ->where('classes.grade_level', $gradeLevel)
                ->where('classes.section', $selectedSection)
                ->select(
                    'students.*',
                    'cs_selected.class_id',
                    'cs_selected.enrollment_status as previous_enrollment_status',
                    'cs_selected.enrollment_type as previous_enrollment_type',
                    'cs_selected.updated_at as previous_enrollment_updated',
                    'cs_curr.enrollment_status as current_enrollment_status',
                    'cs_curr.enrollment_type as current_enrollment_type',
                    'cs_curr.class_id as current_class_id',
                    'cs_curr.school_year_id as current_school_year_id',
                    'cs_curr.created_at as re_enrollment_date',
                    'cs_curr.updated_at as re_enrollment_updated_date',
                    'classes.grade_level',
                    'classes.section',
                    'cs_selected.school_year_id',
                    'current_class.grade_level as current_grade_level',
                    'current_class.section as current_section',
                    'general_averages.general_average',
                    'general_averages.remarks as promotion_status'
                )
                ->get();

            // Add promotion eligibility to each student
            $activeStudents = $activeStudents->map(function ($student) use ($gradeLevel, $currentSchoolYear, $selectedSchoolYear) {
                $student->promotion_eligibility = $this->determinePromotionEligibility($student, $gradeLevel);

                // Add additional info for already re-enrolled students
                if ($student->current_enrollment_status === 'enrolled') {
                    $enrollmentType = $student->current_enrollment_type ?? 'regular';
                    $reEnrollmentDate = $student->re_enrollment_updated_date ?? $student->re_enrollment_date ?? now();
                    $reEnrollmentSchoolYear = $student->current_school_year_id
                        ? SchoolYear::find($student->current_school_year_id)?->school_year
                        : $currentSchoolYear;

                    $student->re_enrollment_info = [
                        'school_year' => $reEnrollmentSchoolYear,
                        'grade_level' => $student->current_grade_level,
                        'section' => $student->current_section,
                        'enrollment_type' => $enrollmentType,
                        're_enrollment_date' => $reEnrollmentDate
                    ];
                }

                // For graduated students, add graduation info
                if ($student->current_enrollment_status === 'graduated' || $student->previous_enrollment_status === 'graduated') {
                    $graduationDate = $student->previous_enrollment_updated ?? $student->re_enrollment_updated_date ?? now();
                    $student->graduation_info = [
                        'school_year' => $selectedSchoolYear,
                        'grade_level' => $student->grade_level,
                        'section' => $student->section,
                        'graduation_date' => $graduationDate,
                        'graduation_status' => 'Graduated'
                    ];
                }

                return $student;
            });

            return view('admin.students.promoteStudents', compact(
                'activeStudents',
                'gradeLevel',
                'selectedSection',
                'previousSchoolYear',
                'currentSchoolYear',
                'defaultSchoolYear',
                'selectedSchoolYear',
                'availableSchoolYears'
            ));
        }

        // Get classes for the selected school year with accurate student counts
        $classes = DB::table('classes')
            ->join('class_student as cs_selected', 'classes.id', '=', 'cs_selected.class_id')
            ->leftJoin('class_student as cs_curr', function ($join) use ($currentSchoolYear) {
                $join->on('cs_curr.student_id', '=', 'cs_selected.student_id')
                    ->where('cs_curr.school_year_id', '=', function ($query) use ($currentSchoolYear) {
                        $query->select('id')
                            ->from('school_years')
                            ->where('school_year', $currentSchoolYear)
                            ->limit(1);
                    });
            })
            ->where('cs_selected.school_year_id', $selectedSchoolYearRecord->id)
            ->where('classes.section', $selectedSection)
            ->select(
                'classes.grade_level',
                'classes.section',
                'classes.id',
                // Count students available for promotion (not enrolled in current SY AND not graduated)
                DB::raw('COUNT(CASE WHEN (cs_curr.enrollment_status = "not_enrolled" OR cs_curr.enrollment_status IS NULL) AND cs_selected.enrollment_status != "graduated" THEN 1 END) as promotable_count'),
                // Count total students in this class from selected SY
                DB::raw('COUNT(DISTINCT cs_selected.student_id) as total_students'),
                // Count already re-enrolled students
                DB::raw('COUNT(CASE WHEN cs_curr.enrollment_status = "enrolled" THEN 1 END) as reenrolled_count'),
                // Count graduated students (for Grade 6)
                DB::raw('COUNT(CASE WHEN cs_selected.enrollment_status = "graduated" THEN 1 END) as graduated_count')
            )
            ->groupBy('classes.grade_level', 'classes.section', 'classes.id')
            ->get();

        // Add search data to each class
        $classes = $classes->map(function ($class) use ($selectedSchoolYear) {
            $class->student_names = $this->getClassStudentNames($class, $selectedSchoolYear);
            $class->student_lrns = $this->getClassStudentLRNs($class, $selectedSchoolYear);
            return $class;
        });

        return view('admin.students.selectClassPromotion', compact(
            'classes',
            'previousSchoolYear',
            'currentSchoolYear',
            'defaultSchoolYear',
            'sections',
            'selectedSection',
            'availableSchoolYears',
            'selectedSchoolYear'
        ));
    }

    private function getClassStudentNames($class, $selectedSchoolYear)
    {
        $studentNames = DB::table('class_student AS cs_selected')
            ->join('students', 'students.id', '=', 'cs_selected.student_id')
            ->join('classes', 'classes.id', '=', 'cs_selected.class_id')
            ->where('cs_selected.school_year_id', function ($query) use ($selectedSchoolYear) {
                $query->select('id')
                    ->from('school_years')
                    ->where('school_year', $selectedSchoolYear)
                    ->limit(1);
            })
            ->where('classes.grade_level', $class->grade_level)
            ->where('classes.section', $class->section)
            ->select(
                'students.student_fName',
                'students.student_mName',
                'students.student_lName',
                'students.student_extName'
            )
            ->get()
            ->map(function ($student) {
                return trim("{$student->student_lName}, {$student->student_fName} {$student->student_mName} {$student->student_extName}");
            })
            ->implode('|');

        return $studentNames;
    }

    private function getClassStudentLRNs($class, $selectedSchoolYear)
    {
        $studentLRNs = DB::table('class_student AS cs_selected')
            ->join('students', 'students.id', '=', 'cs_selected.student_id')
            ->join('classes', 'classes.id', '=', 'cs_selected.class_id')
            ->where('cs_selected.school_year_id', function ($query) use ($selectedSchoolYear) {
                $query->select('id')
                    ->from('school_years')
                    ->where('school_year', $selectedSchoolYear)
                    ->limit(1);
            })
            ->where('classes.grade_level', $class->grade_level)
            ->where('classes.section', $class->section)
            ->pluck('students.student_lrn')
            ->implode('|');

        return $studentLRNs;
    }

    private function determinePromotionEligibility($student, $currentGradeLevel)
    {
        // If student is currently graduated but being ungraduated, show them as retainable
        if ($student->current_enrollment_status === 'graduated') {
            return [
                'status' => 'retained_graduation',
                'eligible' => false,
                'message' => 'Retainable in Grade 6',
                'average' => $student->general_average
            ];
        }

        $average = $student->general_average;
        $remarks = $student->promotion_status;

        // Define passing criteria
        $passingGrade = 75; // Minimum passing grade
        $isGraduating = $currentGradeLevel === 'grade6';

        if ($isGraduating) {
            // For Grade 6 students - check if they can graduate
            if ($average >= $passingGrade && $remarks === 'passed') {
                return [
                    'status' => 'eligible_graduation',
                    'eligible' => true,
                    'message' => 'Eligible for Graduation',
                    'average' => $average
                ];
            } else {
                return [
                    'status' => 'retained_graduation',
                    'eligible' => false,
                    'message' => 'Retainable in Grade 6',
                    'average' => $average
                ];
            }
        } else {
            // For other grades - check if they can be promoted
            if ($average >= $passingGrade && $remarks === 'passed') {
                return [
                    'status' => 'eligible_promotion',
                    'eligible' => true,
                    'message' => 'Promotable',
                    'average' => $average
                ];
            } else {
                return [
                    'status' => 'retained',
                    'eligible' => false,
                    'message' => 'Retainable',
                    'average' => $average
                ];
            }
        }
    }

    public function promoteStudents(Request $request)
    {
        $request->validate([
            'selected_students' => 'required|array',
            'batch_grade' => 'required|string',
        ]);

        // Remove duplicate student IDs
        $selectedStudents = array_unique($request->input('selected_students'));

        $grade = $request->input('batch_grade');
        $section = $request->input('batch_section');
        $currentSchoolYearStr = $request->input('next_school_year');
        $currentGradeLevel = $request->input('current_grade_level');

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

        foreach ($selectedStudents as $studentId) {
            if ($grade === 'graduated') {
                // 🔥 KEY CHANGE: Preserve the original enrollment type when graduating
                $previousEnrollment = DB::table('class_student')
                    ->where('student_id', $studentId)
                    ->where('school_year_id', $previousSchoolYear->id)
                    ->first();

                $originalEnrollmentType = $previousEnrollment->enrollment_type ?? 'regular';

                DB::table('class_student')
                    ->where('student_id', $studentId)
                    ->where('school_year_id', $previousSchoolYear->id)
                    ->update([
                        'enrollment_status' => 'graduated',
                        'enrollment_type' => $originalEnrollmentType, // Preserve original type
                        'updated_at' => now(),
                    ]);

                // Remove future enrollment
                DB::table('class_student')
                    ->where('student_id', $studentId)
                    ->where('school_year_id', $schoolYear->id)
                    ->where('enrollment_status', 'not_enrolled')
                    ->delete();
            } else {
                $previousEnrollment = DB::table('class_student')
                    ->where('student_id', $studentId)
                    ->where('school_year_id', $previousSchoolYear->id)
                    ->first();

                // 🔥 KEY CHANGE: Better logic for determining enrollment type
                $originalEnrollmentType = $previousEnrollment->enrollment_type ?? 'regular';

                // Determine the new enrollment type
                $enrollmentType = $originalEnrollmentType;

                // If student is being retained in the same grade, mark as returnee
                if ($grade === $currentGradeLevel) {
                    $enrollmentType = 'returnee';
                }
                // If student is being promoted, keep their original type (regular/transferee)

                // Archive all other enrollments except current and previous
                DB::table('class_student')
                    ->where('student_id', $studentId)
                    ->whereNotIn('school_year_id', [$schoolYear->id, $previousSchoolYear->id])
                    ->update(['enrollment_status' => 'archived']);

                // Check if student already has an enrollment for the current school year
                $existingEnrollment = DB::table('class_student')
                    ->where('student_id', $studentId)
                    ->where('school_year_id', $schoolYear->id)
                    ->first();

                if ($existingEnrollment) {
                    // Update existing enrollment
                    DB::table('class_student')
                        ->where('student_id', $studentId)
                        ->where('school_year_id', $schoolYear->id)
                        ->update([
                            'class_id' => $newClass->id,
                            'enrollment_status' => 'enrolled',
                            'enrollment_type' => $enrollmentType,
                            'updated_at' => now(),
                        ]);
                } else {
                    try {
                        // Create new enrollment
                        DB::table('class_student')->insert([
                            'student_id' => $studentId,
                            'class_id' => $newClass->id,
                            'school_year_id' => $schoolYear->id,
                            'enrollment_status' => 'enrolled',
                            'enrollment_type' => $enrollmentType,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    } catch (\Illuminate\Database\QueryException $e) {
                        if ($e->getCode() == 23000) {
                            logger()->warning("Duplicate enrollment attempt for student {$studentId} in class {$newClass->id} for school year {$schoolYear->id}");
                            continue;
                        }
                        throw $e;
                    }
                }

                // Also update the previous year's enrollment status to 'archived'
                DB::table('class_student')
                    ->where('student_id', $studentId)
                    ->where('school_year_id', $previousSchoolYear->id)
                    ->update([
                        'enrollment_status' => 'archived',
                        'updated_at' => now(),
                    ]);
            }
        }

        return redirect()->route('students.promote.view', [
            'grade_level' => $request->input('current_grade_level', $grade),
            'section' => $request->input('current_section', $section)
        ])->with('success', 'Selected students promoted successfully!');
    }

    private function autoGraduateEligibleStudents($previousSchoolYearRecord, $currentSchoolYear)
    {
        try {
            // Get current school year record
            $currentSchoolYearRecord = SchoolYear::where('school_year', $currentSchoolYear)->first();

            if (!$currentSchoolYearRecord) {
                return 0;
            }

            // Find Grade 6 students from previous school year who are eligible for graduation
            $eligibleGraduates = DB::table('class_student AS cs_prev')
                ->join('students', 'students.id', '=', 'cs_prev.student_id')
                ->join('classes', 'classes.id', '=', 'cs_prev.class_id')
                ->leftJoin('general_averages', function ($join) use ($previousSchoolYearRecord) {
                    $join->on('general_averages.student_id', '=', 'students.id')
                        ->where('general_averages.school_year_id', $previousSchoolYearRecord->id);
                })
                ->where('cs_prev.school_year_id', $previousSchoolYearRecord->id)
                ->where('classes.grade_level', 'grade6')
                ->where('cs_prev.enrollment_status', 'enrolled') // Only currently enrolled Grade 6 students
                ->select(
                    'students.id as student_id',
                    'cs_prev.id as class_student_id',
                    'cs_prev.enrollment_type', // 🔥 Get the enrollment type
                    'general_averages.general_average',
                    'general_averages.remarks',
                    'cs_prev.updated_at'
                )
                ->get();

            $graduatedCount = 0;

            foreach ($eligibleGraduates as $student) {
                // Check if student is eligible for graduation (average >= 75 and passed)
                if ($student->general_average >= 75 && $student->remarks === 'passed') {

                    // 🔥 KEY CHANGE: Preserve the original enrollment type when auto-graduating
                    DB::table('class_student')
                        ->where('id', $student->class_student_id)
                        ->update([
                            'enrollment_status' => 'graduated',
                            'enrollment_type' => $student->enrollment_type, // Preserve original type
                            'updated_at' => now(),
                        ]);

                    // Remove any "not_enrolled" record for current school year
                    DB::table('class_student')
                        ->where('student_id', $student->student_id)
                        ->where('school_year_id', $currentSchoolYearRecord->id)
                        ->where('enrollment_status', 'not_enrolled')
                        ->delete();

                    $graduatedCount++;
                }
            }

            // Log the automatic graduation (optional)
            if ($graduatedCount > 0) {
                Log::info("Automatically graduated {$graduatedCount} Grade 6 students for SY {$previousSchoolYearRecord->school_year}");
            }

            return $graduatedCount;
        } catch (\Exception $e) {
            Log::error("Error in autoGraduateEligibleStudents: " . $e->getMessage());
            return 0;
        }
    }

    public function bulkUnenroll(Request $request)
    {
        try {
            $studentIds = $request->input('student_ids', []);
            $currentSchoolYear = $this->getDefaultSchoolYear();
            $currentSchoolYearRecord = SchoolYear::where('school_year', $currentSchoolYear)->first();

            if (!$currentSchoolYearRecord) {
                return response()->json([
                    'success' => false,
                    'message' => 'Current school year not found.'
                ], 404);
            }

            $unenrolledCount = 0;

            foreach ($studentIds as $studentId) {
                // Find the current enrollment for this student
                $currentEnrollment = DB::table('class_student')
                    ->where('student_id', $studentId)
                    ->where('school_year_id', $currentSchoolYearRecord->id)
                    ->where('enrollment_status', 'enrolled')
                    ->first();

                if ($currentEnrollment) {
                    // 🔥 KEY CHANGE: Preserve the enrollment type when unenrolling
                    $originalEnrollmentType = $currentEnrollment->enrollment_type ?? 'regular';

                    DB::table('class_student')
                        ->where('student_id', $studentId)
                        ->where('school_year_id', $currentSchoolYearRecord->id)
                        ->update([
                            'enrollment_status' => 'not_enrolled',
                            'class_id' => null, // Remove class assignment
                            'enrollment_type' => $originalEnrollmentType, // Preserve original type
                            'updated_at' => now(),
                        ]);

                    $unenrolledCount++;
                }
            }

            return response()->json([
                'success' => true,
                'message' => "Successfully unenrolled {$unenrolledCount} student(s) from current school year with their enrollment types preserved."
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error unenrolling students: ' . $e->getMessage()
            ], 500);
        }
    }

    public function bulkUngraduate(Request $request)
    {
        try {
            $studentIds = $request->input('student_ids', []);

            if (empty($studentIds)) {
                return response()->json([
                    'success' => false,
                    'message' => 'No students selected for ungraduation.'
                ], 400);
            }

            $currentSchoolYear = $this->getDefaultSchoolYear();
            $currentSchoolYearRecord = SchoolYear::where('school_year', $currentSchoolYear)->first();

            if (!$currentSchoolYearRecord) {
                return response()->json([
                    'success' => false,
                    'message' => 'Current school year not found.'
                ], 404);
            }

            $previousStartYear = explode('-', $currentSchoolYear)[0] - 1;
            $previousSchoolYear = $previousStartYear . '-' . ($previousStartYear + 1);
            $previousSchoolYearRecord = SchoolYear::where('school_year', $previousSchoolYear)->first();

            if (!$previousSchoolYearRecord) {
                return response()->json([
                    'success' => false,
                    'message' => 'Previous school year not found.'
                ], 404);
            }

            $ungraduatedCount = 0;
            $errors = [];

            foreach ($studentIds as $studentId) {
                try {
                    // Verify student exists
                    $student = Student::find($studentId);
                    if (!$student) {
                        $errors[] = "Student ID {$studentId} not found.";
                        continue;
                    }

                    // Find the student's enrollment in the PREVIOUS school year (where they were graduated)
                    $previousEnrollment = DB::table('class_student')
                        ->where('student_id', $studentId)
                        ->where('school_year_id', $previousSchoolYearRecord->id)
                        ->where('enrollment_status', 'graduated')
                        ->first();

                    if (!$previousEnrollment) {
                        $errors[] = "Student {$studentId} is not graduated or already ungraduated.";
                        continue;
                    }

                    // 🔥 KEY CHANGE: Preserve the original enrollment type when ungraduating
                    $originalEnrollmentType = $previousEnrollment->enrollment_type ?? 'regular';

                    DB::table('class_student')
                        ->where('student_id', $studentId)
                        ->where('school_year_id', $previousSchoolYearRecord->id)
                        ->update([
                            'enrollment_status' => 'archived',
                            'enrollment_type' => $originalEnrollmentType, // Preserve original type
                            'updated_at' => now(),
                        ]);

                    // Remove any current school year enrollment (if exists)
                    $currentEnrollment = DB::table('class_student')
                        ->where('student_id', $studentId)
                        ->where('school_year_id', $currentSchoolYearRecord->id)
                        ->first();

                    if ($currentEnrollment) {
                        // If they have current enrollment, update it to not_enrolled
                        DB::table('class_student')
                            ->where('student_id', $studentId)
                            ->where('school_year_id', $currentSchoolYearRecord->id)
                            ->update([
                                'enrollment_status' => 'not_enrolled',
                                'class_id' => null,
                                'enrollment_type' => $originalEnrollmentType, // Preserve original type
                                'updated_at' => now(),
                            ]);
                    } else {
                        // Create a not_enrolled record for current school year
                        DB::table('class_student')->insert([
                            'student_id' => $studentId,
                            'class_id' => null,
                            'school_year_id' => $currentSchoolYearRecord->id,
                            'enrollment_status' => 'not_enrolled',
                            'enrollment_type' => $originalEnrollmentType, // Preserve original type
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }

                    $ungraduatedCount++;
                } catch (\Exception $e) {
                    $errors[] = "Error processing student {$studentId}: " . $e->getMessage();
                    Log::error("Error ungraduating student {$studentId}: " . $e->getMessage());
                }
            }

            $message = "Successfully ungraduated {$ungraduatedCount} student(s). They have been moved back to 'Available for Re-Enrollment' with their original enrollment type preserved.";
            if (!empty($errors)) {
                $message .= " " . count($errors) . " error(s) occurred.";
                Log::warning('Ungraduate errors: ' . implode(', ', $errors));
            }

            return response()->json([
                'success' => $ungraduatedCount > 0,
                'message' => $message,
                'ungraduated_count' => $ungraduatedCount,
                'errors' => $errors
            ]);
        } catch (\Exception $e) {
            Log::error('Error in bulkUngraduate: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'System error while ungraduating students: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getDefaultSchoolYear()
    {
        $now = now();
        $year = $now->year;
        $cutoff = now()->copy()->setMonth(6)->setDay(1);
        $start = $now->lt($cutoff) ? $year - 1 : $year;
        return $start . '-' . ($start + 1);
    }
}
