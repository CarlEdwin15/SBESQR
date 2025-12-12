<!-- Tabs -->
<ul class="nav nav-pills mb-3" role="tablist">
    <li class="nav-item">
        <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#profile">
            <i class="bx bx-user-pin me-1"></i>
            <span class="d-none d-sm-block">Profile</span>
        </button>
    </li>
    <li class="nav-item">
        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#classes">
            <i class="bx bx-book-content me-1"></i>
            <span class="d-none d-sm-block">Classes &amp; Grades</span>
        </button>
    </li>
    <li class="nav-item">
        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#parents">
            <i class="bx bx-user me-1"></i>
            <span class="d-none d-sm-block">Parents</span>
        </button>
    </li>
    <li class="nav-item">
        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#attendances">
            <i class="bx bx-calendar-check me-1"></i>
            <span class="d-none d-sm-block">Attendances</span>
        </button>
    </li>
    <li class="nav-item">
        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#school-fees">
            <i class='bx bx-wallet me-1'></i>
            <span class="d-none d-sm-block">School Fees</span>
        </button>
    </li>
</ul>

<div class="card shadow">
    <div class="card-body">
        <!-- Tab Content -->
        <div class="tab-content">
            <!-- Profile -->
            <div class="tab-pane fade show active" id="profile">
                <h5 class="fw-bold text-primary mb-3">Student Information</h5>

                <div class="row mb-2">
                    <div class="col-sm-4 fw-bold">Learner's Reference No.(LRN):</div>
                    <div class="col-sm-8">{{ $student->student_lrn }}</div>
                </div>
                <div class="row mb-2">
                    <div class="col-sm-4 fw-bold">Date of Birth:</div>
                    <div class="col-sm-8">
                        {{ \Carbon\Carbon::parse($student->student_dob)->format('F j, Y') }}
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-sm-4 fw-bold">Sex:</div>
                    <div class="col-sm-8">{{ ucfirst($student->student_sex) }}</div>
                </div>
                <div class="row mb-2">
                    <div class="col-sm-4 fw-bold">Age:</div>
                    <div class="col-sm-8">
                        {{ \Carbon\Carbon::parse($student->student_dob)->age }} years old
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-sm-4 fw-bold">Place of Birth:</div>
                    <div class="col-sm-8">{{ $student->address->pob }}</div>
                </div>
                <div class="row mb-2">
                    <div class="col-sm-4 fw-bold">Current Address:</div>
                    <div class="col-sm-8">
                        {{ $student->address->house_no ?? 'N/A' }},
                        {{ $student->address->street_name ?? 'N/A' }},
                        {{ $student->address->barangay ?? 'N/A' }},
                        {{ $student->address->municipality_city ?? 'N/A' }},
                        {{ $student->address->province ?? 'N/A' }},
                        {{ $student->address->zip_code ?? 'N/A' }}
                    </div>
                </div>

                <!-- QR Code -->
                <h5 class="fw-bold text-primary mt-4 mb-3">QR Code</h5>
                <div class="text-center">
                    {!! QrCode::size(200)->generate(json_encode(['student_id' => $student->id])) !!}
                </div>
            </div>

            <!-- Classes & Grades -->
            <div class="tab-pane fade" id="classes">

                <!-- Add Export Form 10 Button Here -->
                @php
                    $user = auth()->user();
                @endphp
                @if ($user && $user->role === 'admin')
                    <div class="d-flex justify-content-between mb-3">
                        <h5 class="fw-bold text-primary mb-3">Classes and Grades</h5>
                        <a href="{{ route('teacher.student.form10', ['student_id' => $student->id]) }}" target="_blank"
                            class="btn btn-success btn-sm align-items-center">
                            <i class="bx bx-printer"></i> Export Form 10 (Cumulative Grades)
                        </a>
                    </div>
                @endif

                @if ($classHistory->isEmpty())
                    <p class="text-muted">No classes found for this student.</p>
                @else
                    <div class="accordion" id="classHistoryAccordion">
                        @foreach ($classHistory as $index => $classItem)
                            @php
                                $schoolYear =
                                    \App\Models\SchoolYear::find($classItem->pivot->school_year_id)?->school_year ??
                                    'N/A';
                                $status = $classItem->pivot->enrollment_status;
                                $badgeClass = match ($status) {
                                    'enrolled' => 'bg-success',
                                    'archived' => 'bg-warning',
                                    default => 'bg-secondary',
                                };

                                // Determine header background class based on status
                                $headerClass = match ($status) {
                                    'enrolled' => 'bg-label-success',
                                    default => '',
                                };

                                $subjectsWithGrades = $gradesByClass[$classItem->id] ?? [];

                                // Check if ALL quarters are enabled for viewing for parents
                                $canViewAllQuarters = true;
                                $canViewFinalGrades = true;
                                if (auth()->user() && auth()->user()->role === 'parent') {
                                    $classStudent = $student->classStudents
                                        ->where('class_id', $classItem->id)
                                        ->where('school_year_id', $classItem->pivot->school_year_id)
                                        ->first();

                                    if ($classStudent) {
                                        // Check if all quarters (1-4) are enabled
                                        $canViewAllQuarters =
                                            $classStudent->q1_allow_view &&
                                            $classStudent->q2_allow_view &&
                                            $classStudent->q3_allow_view &&
                                            $classStudent->q4_allow_view;

                                        // Final grades and general average are only visible when all quarters are enabled
                                        $canViewFinalGrades = $canViewAllQuarters;
                                    } else {
                                        $canViewAllQuarters = false;
                                        $canViewFinalGrades = false;
                                    }
                                }
                            @endphp

                            <div class="accordion-item border-0 shadow-sm mb-2 rounded-3">
                                <h2 class="accordion-header" id="heading{{ $index }}">
                                    <!-- Modified button: Added header background class -->
                                    <button
                                        class="accordion-button collapsed fw-bold text-dark d-flex justify-content-between align-items-center {{ $headerClass }}"
                                        type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapse{{ $index }}">
                                        <span>
                                            {{ $schoolYear }} —
                                            {{ $classItem->formatted_grade_level }} -
                                            {{ $classItem->section }}
                                        </span>
                                        <div>
                                            <!-- Status badge (optional, you can remove if you want) -->
                                            <span
                                                class="badge d-none d-sm-block {{ $badgeClass }} ms-3">{{ ucfirst($status) }}</span>
                                            <!-- Removed the "Current Class" badge -->
                                        </div>
                                        @php
                                            $adviser = $classItem->advisers
                                                ->where('pivot.school_year_id', $classItem->pivot->school_year_id)
                                                ->first();
                                        @endphp
                                        <span>
                                            @if ($adviser)
                                                <small class="text-muted ms-2 d-none d-sm-block">Adviser:
                                                    {{ $adviser->full_name }}</small>
                                            @endif
                                        </span>
                                    </button>
                                </h2>
                                <div id="collapse{{ $index }}" class="accordion-collapse collapse">
                                    <div class="accordion-body">
                                        @if (empty($subjectsWithGrades))
                                            <div class="p-3 mt-3 mb-3 rounded-3 bg-light text-center border">
                                                <p class="text-warning fw-bold mb-0">No grades found for this school
                                                    year.</p>
                                            </div>
                                        @else
                                            <div class="d-flex justify-content-between align-items-center mb-3 mt-2">
                                                <h5 class="fw-bold text-primary">Grades</h5>
                                                @php
                                                    $user = auth()->user();
                                                    $isAdviserOfThisClass = false;

                                                    // Check if user is teacher AND adviser of this specific class
                                                    if ($user && $user->role === 'teacher') {
                                                        $isAdviserOfThisClass = $classItem->advisers
                                                            ->where(
                                                                'pivot.school_year_id',
                                                                $classItem->pivot->school_year_id,
                                                            )
                                                            ->contains('id', $user->id);
                                                    }
                                                @endphp

                                                @if ($isAdviserOfThisClass)
                                                    <!-- Teacher(adviser)-only export button -->
                                                    <a href="{{ route('teacher.student.card', [
                                                        'student_id' => $student->id,
                                                        'school_year' => $classItem->pivot->school_year_id,
                                                    ]) }}"
                                                        target="_blank" class="btn btn-success btn-sm">
                                                        <i class="bx bx-printer"></i> Export
                                                    </a>
                                                @endif
                                            </div>

                                            <!-- Grade Viewing Status Alert for Parents -->
                                            @if (auth()->user() && auth()->user()->role === 'parent' && !$canViewAllQuarters)
                                                <div class="alert alert-warning mb-3">
                                                    <i class="bx bx-info-circle"></i>
                                                    <strong>Note:</strong> Final grades and general average will be
                                                    visible only when all quarterly grades are enabled for viewing by
                                                    the teacher.
                                                </div>
                                            @endif

                                            <div class="table-responsive">
                                                <table class="table table-bordered table-hover">
                                                    <thead class="table-light">
                                                        <tr class="text-center">
                                                            <th>Subject</th>
                                                            <th>Q1</th>
                                                            <th>Q2</th>
                                                            <th>Q3</th>
                                                            <th>Q4</th>
                                                            <th>Final Grade</th>
                                                            <th>Remarks</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($subjectsWithGrades as $item)
                                                            <tr>
                                                                <td>{{ $item['subject'] }}</td>

                                                                {{-- Quarter Grades --}}
                                                                @foreach ([1, 2, 3, 4] as $q)
                                                                    @php
                                                                        $grade =
                                                                            $item['quarters']->firstWhere(
                                                                                'quarter',
                                                                                $q,
                                                                            )['grade'] ?? null;
                                                                        $gradeColor = '';

                                                                        // Apply DepEd rounding like in grade slip
                                                                        $roundedGrade =
                                                                            $grade !== null ? round($grade) : null;

                                                                        // Check if parent can view this quarter's grade
$canViewGrade = true;
if (
    auth()->user() &&
    auth()->user()->role === 'parent'
) {
    $classStudent = $student->classStudents
        ->where('class_id', $classItem->id)
        ->where(
            'school_year_id',
            $classItem->pivot->school_year_id,
        )
        ->first();

    // Use the correct column names
    $quarterColumn = 'q' . $q . '_allow_view'; // This becomes 'q1_allow_view', etc.
    $canViewGrade =
        $classStudent &&
        $classStudent->$quarterColumn;
}

if (
    is_numeric($roundedGrade) &&
    $canViewGrade
) {
    if ($roundedGrade >= 90) {
        $gradeColor = 'text-success';
    } elseif ($roundedGrade >= 85) {
        $gradeColor = 'text-success';
    } elseif ($roundedGrade >= 80) {
        $gradeColor = 'text-warning';
    } elseif ($roundedGrade >= 75) {
        $gradeColor = 'text-warning';
    } else {
        $gradeColor = 'text-danger fw-semibold';
                                                                            }
                                                                        }
                                                                    @endphp
                                                                    <td class="text-center {{ $gradeColor }}">
                                                                        @if ($grade !== null)
                                                                            @if (auth()->user() && auth()->user()->role === 'parent' && !$canViewGrade)
                                                                                <span class="text-muted"
                                                                                    title="Grade viewing not enabled by teacher">
                                                                                    <i class="bx bx-lock-alt"></i>
                                                                                </span>
                                                                            @else
                                                                                {{ $roundedGrade }}
                                                                            @endif
                                                                        @else
                                                                            -
                                                                        @endif
                                                                    </td>
                                                                @endforeach

                                                                {{-- Final Grade --}}
                                                                @php
                                                                    $finalAverage = $item['final_average'] ?? null;
                                                                    $finalColor = '';
                                                                    $finalRemarks = null;

                                                                    // Apply DepEd rounding like in grade slip
                                                                    $roundedFinal =
                                                                        $finalAverage !== null
                                                                            ? round($finalAverage)
                                                                            : null;

                                                                    if (is_numeric($roundedFinal)) {
                                                                        if ($roundedFinal >= 90) {
                                                                            $finalColor = 'text-success fw-bold';
                                                                        } elseif ($roundedFinal >= 85) {
                                                                            $finalColor = 'text-success';
                                                                        } elseif ($roundedFinal >= 80) {
                                                                            $finalColor = 'text-warning';
                                                                        } elseif ($roundedFinal >= 75) {
                                                                            $finalColor = 'text-warning';
                                                                        } else {
                                                                            $finalColor = 'text-danger fw-semibold';
                                                                        }

                                                                        // Corrected passing logic - match grade slip
                                                                        $finalRemarks =
                                                                            $roundedFinal >= 75 ? 'Passed' : 'Failed';
                                                                    }
                                                                @endphp

                                                                <td class="text-center {{ $finalColor }}">
                                                                    @if ($finalAverage !== null)
                                                                        @if (auth()->user() && auth()->user()->role === 'parent' && !$canViewFinalGrades)
                                                                            <span class="text-muted"
                                                                                title="Final grades are visible only when all quarterly grades are enabled">
                                                                                <i class="bx bx-lock-alt"></i>
                                                                            </span>
                                                                        @else
                                                                            <strong>{{ $roundedFinal }}</strong>
                                                                        @endif
                                                                    @else
                                                                        <span class="text-muted">-</span>
                                                                    @endif
                                                                </td>

                                                                <td class="text-center">
                                                                    @if ($finalRemarks === 'Passed')
                                                                        @if (auth()->user() && auth()->user()->role === 'parent' && !$canViewFinalGrades)
                                                                            <span class="text-muted"
                                                                                title="Remarks are visible only when all quarterly grades are enabled">
                                                                                <i class="bx bx-lock-alt"></i>
                                                                            </span>
                                                                        @else
                                                                            <span
                                                                                class="badge bg-label-success">Passed</span>
                                                                        @endif
                                                                    @elseif ($finalRemarks === 'Failed')
                                                                        @if (auth()->user() && auth()->user()->role === 'parent' && !$canViewFinalGrades)
                                                                            <span class="text-muted"
                                                                                title="Remarks are visible only when all quarterly grades are enabled">
                                                                                <i class="bx bx-lock-alt"></i>
                                                                            </span>
                                                                        @else
                                                                            <span
                                                                                class="badge bg-label-danger">Failed</span>
                                                                        @endif
                                                                    @else
                                                                        <span class="text-muted">-</span>
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>

                                            <h5 class="fw-bold text-primary mt-3 mb-3">General Average</h5>
                                            @if (!empty($generalAverages[$classItem->id]))
                                                @php
                                                    $ga = $generalAverages[$classItem->id];
                                                    // Apply DepEd rounding like in grade slip
                                                    $gaValue = round($ga['general_average']);
                                                    $gaColor = '';

                                                    if ($gaValue >= 90) {
                                                        $gaColor = 'text-success fw-bold';
                                                    } elseif ($gaValue >= 85) {
                                                        $gaColor = 'text-success';
                                                    } elseif ($gaValue >= 80) {
                                                        $gaColor = 'text-warning';
                                                    } elseif ($gaValue >= 75) {
                                                        $gaColor = 'text-warning';
                                                    } else {
                                                        $gaColor = 'text-danger fw-semibold';
                                                    }

                                                    // Match grade slip remarks
                                                    $gaRemarks = $gaValue >= 75 ? 'Promoted' : 'Retained';
                                                @endphp

                                                <div
                                                    class="p-3 rounded-3 border bg-light d-flex justify-content-between">
                                                    <span class="fw-bold">General Average:</span>
                                                    <span class="{{ $gaColor }}">
                                                        @if (auth()->user() && auth()->user()->role === 'parent' && !$canViewFinalGrades)
                                                            <span class="text-muted"
                                                                title="General average is visible only when all quarterly grades are enabled">
                                                                <i class="bx bx-lock-alt me-1"></i>
                                                            </span>
                                                        @else
                                                            <strong>{{ $gaValue }}</strong>
                                                            @if ($gaRemarks === 'Promoted')
                                                                <span
                                                                    class="badge bg-label-success ms-2 fw-bold">Promoted</span>
                                                            @else
                                                                <span
                                                                    class="badge bg-label-danger ms-2 fw-bold">Retained</span>
                                                            @endif
                                                        @endif
                                                    </span>
                                                </div>
                                            @else
                                                <div class="p-3 rounded-3 border bg-light text-center">
                                                    <span class="text-muted">General Average not available (incomplete
                                                        final grades).</span>
                                                </div>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Parents -->
            <div class="tab-pane fade" id="parents">
                <h5 class="fw-bold text-primary mb-4">Linked Parents
                </h5>

                <div class="row g-3">
                    @forelse ($student->parents as $parent)
                        <div class="col-md-6 col-lg-4">
                            <div class="card shadow-sm border-0 h-100">
                                <div class="card-body text-center p-4">
                                    <!-- Profile Photo -->
                                    @if ($parent->profile_photo)
                                        <img src="{{ asset('public/uploads/' . $parent->profile_photo) }}"
                                            alt="Parent Photo" class="rounded-circle mb-3 shadow-sm"
                                            style="object-fit: cover; width: 100px; height: 100px;">
                                    @else
                                        <img src="{{ asset('assetsDashboard/img/profile_pictures/parent_default_profile.jpg') }}"
                                            alt="Default Photo" class="rounded-circle mb-3 shadow-sm"
                                            style="object-fit: cover; width: 100px; height: 100px;">
                                    @endif

                                    <!-- Name -->
                                    <h6 class="fw-bold mb-1">{{ $parent->full_name }}</h6>

                                    <!-- Role / Type -->
                                    @if ($parent->parent_type)
                                        <span class="badge bg-label-primary mb-2 text-capitalize">
                                            {{ $parent->parent_type }}
                                        </span>
                                    @endif

                                    <!-- Contact Info -->
                                    <div class="small text-muted mb-1">
                                        <i class="bx bx-envelope me-1"></i> {{ $parent->email ?? 'No email' }}
                                    </div>

                                    @if ($parent->phone)
                                        <div class="small text-muted">
                                            <i class="bx bx-phone me-1"></i> {{ $parent->phone }}
                                        </div>
                                    @endif
                                </div>

                                <!-- Footer Info -->
                                <div class="card-footer bg-light text-center py-2">
                                    <small class="text-muted">
                                        Last active: {{ $parent->last_seen ?? 'Not available' }}
                                    </small>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="alert alert-secondary text-center">
                                <i class="bx bx-info-circle me-1"></i>
                                No parents linked yet to this student.
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Attendances -->
            <div class="tab-pane fade" id="attendances">
                <h5 class="fw-bold text-primary mb-3">Attendance Calendar</h5>

                @php
                    use Carbon\Carbon;

                    $attendanceRecords = $student
                        ->attendances()
                        ->with(['schoolYear', 'class', 'schedule'])
                        ->get()
                        ->map(function ($att) {
                            $subject = $att->schedule->subject_name ?? 'N/A';
                            $startTime = $att->schedule->start_time
                                ? Carbon::parse($att->schedule->start_time)->format('g:i A')
                                : null;
                            $endTime = $att->schedule->end_time
                                ? Carbon::parse($att->schedule->end_time)->format('g:i A')
                                : null;

                            $formattedTime = $startTime && $endTime ? "($startTime - $endTime)" : '';

                            // Tooltip format similar to admin/teacher attendance
                            $tooltip = strtoupper($att->status ?? 'ABSENT') . " | {$subject} | {$formattedTime}";

                            return [
                                'date' => Carbon::parse($att->date)->toDateString(),
                                'status' => ucfirst($att->status ?? 'Absent'),
                                'remarks' => $tooltip,
                            ];
                        });
                @endphp

                @if ($attendanceRecords->isEmpty())
                    <div class="alert alert-secondary text-center">
                        No attendance records found for this student.
                    </div>
                @else
                    <div class="table-responsive p-2">
                        <div id="attendanceCalendar" class="p-2 border rounded bg-light shadow-sm"></div>
                    </div>
                @endif
            </div>

            <!-- School Fees -->
            <div class="tab-pane fade" id="school-fees">
                <h5 class="fw-bold text-primary mb-4">School Fees</h5>

                @if ($classHistory->isEmpty())
                    <div class="p-3 mt-3 mb-3">
                        <p class="text-muted">No school fee records found for this student.</p>
                    </div>
                @else
                    <div class="accordion" id="schoolFeesAccordion">
                        @foreach ($classHistory as $index => $classItem)
                            @php
                                $schoolYear =
                                    \App\Models\SchoolYear::find($classItem->pivot->school_year_id)?->school_year ??
                                    'N/A';
                                $status = $classItem->pivot->enrollment_status;
                                $badgeClass = match ($status) {
                                    'enrolled' => 'bg-success',
                                    'archived' => 'bg-warning',
                                    'graduated' => 'bg-info',
                                    default => 'bg-secondary',
                                };

                                // Determine if this is the current class
                                $isCurrentClass = $status === 'enrolled';

                                // Get payments for this student in this school year
                                $payments = $student
                                    ->payments()
                                    ->with(['histories.addedBy'])
                                    ->whereHas('classStudent', function ($query) use ($classItem, $student) {
                                        $query
                                            ->where('student_id', $student->id)
                                            ->where('school_year_id', $classItem->pivot->school_year_id);
                                    })
                                    ->get();

                                $totalDue = $payments->sum('amount_due');
                                $totalPaid = $payments->sum(function ($payment) {
                                    return $payment->histories->sum('amount_paid');
                                });
                                $balance = $totalDue - $totalPaid;
                            @endphp

                            <div class="accordion-item border-0 shadow-sm mb-3 mt-2 rounded-3">
                                <h2 class="accordion-header" id="schoolFeesHeading{{ $index }}">
                                    <!-- Modified button: Added conditional class for current class -->
                                    <button
                                        class="accordion-button collapsed fw-bold text-dark d-flex justify-content-between align-items-center {{ $isCurrentClass ? 'bg-label-success' : '' }}"
                                        type="button" data-bs-toggle="collapse"
                                        data-bs-target="#schoolFeesCollapse{{ $index }}">
                                        <span>
                                            {{ $schoolYear }} —
                                            {{ $classItem->formatted_grade_level }} -
                                            {{ $classItem->section }}
                                        </span>
                                    </button>
                                </h2>

                                <div id="schoolFeesCollapse{{ $index }}" class="accordion-collapse collapse"
                                    aria-labelledby="schoolFeesHeading{{ $index }}"
                                    data-bs-parent="#schoolFeesAccordion">
                                    <div class="accordion-body">
                                        @if ($payments->isEmpty())
                                            <div class="p-3 mb-3 rounded-3 bg-light text-center border">
                                                <p class="text-warning fw-bold mb-0">No school fee records found for
                                                    this school year.</p>
                                            </div>
                                        @else
                                            <!-- Mini Cards -->
                                            <div class="row g-3 mb-3 mt-3">

                                                <!-- Remaining Balance Card -->
                                                <div class="col-12 col-md-4">
                                                    <div class="card shadow-sm text-center h-100">
                                                        <div class="card-body">
                                                            <div class="fw-bold">Remaining Balance</div>
                                                            <div
                                                                class="fw-bold fs-6 {{ $balance > 0 ? 'text-danger' : 'text-success' }}">
                                                                ₱{{ number_format($balance, 2) }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Total Paid Card -->
                                                <div class="col-12 col-md-4">
                                                    <div class="card shadow-sm text-center h-100">
                                                        <div class="card-body">
                                                            <div class="fw-bold">Total Paid</div>
                                                            <div class="text-success fs-6">
                                                                ₱{{ number_format($totalPaid, 2) }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Total Due Card -->
                                                <div class="col-12 col-md-4">
                                                    <div class="card shadow-sm text-center h-100">
                                                        <div class="card-body">
                                                            <div class="fw-bold">Total Due</div>
                                                            <div class="text-primary fs-6">
                                                                ₱{{ number_format($totalDue, 2) }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>


                                            <div class="row g-4">
                                                @php
                                                    // Collect all payments from all school years
                                                    $allPayments = collect();
                                                    foreach ($classHistory as $classItem) {
                                                        $payments = $student
                                                            ->payments()
                                                            ->with(['histories.addedBy', 'classStudent'])
                                                            ->whereHas('classStudent', function ($query) use (
                                                                $classItem,
                                                                $student,
                                                            ) {
                                                                $query
                                                                    ->where('student_id', $student->id)
                                                                    ->where(
                                                                        'school_year_id',
                                                                        $classItem->pivot->school_year_id,
                                                                    );
                                                            })
                                                            ->get();

                                                        // Add school year info to each payment
                                                        $schoolYear =
                                                            \App\Models\SchoolYear::find(
                                                                $classItem->pivot->school_year_id,
                                                            )?->school_year ?? 'N/A';
                                                        foreach ($payments as $payment) {
                                                            $payment->school_year = $schoolYear;
                                                            $payment->class_info =
                                                                $classItem->formatted_grade_level .
                                                                ' - ' .
                                                                $classItem->section;
                                                            $allPayments->push($payment);
                                                        }
                                                    }

                                                    // Sort payments: unpaid first, then partial, then paid.
                                                    $sortedPayments = $allPayments->sort(function ($a, $b) {
                                                        $statusOrder = ['unpaid' => 0, 'partial' => 1, 'paid' => 2];
                                                        $aOrder = $statusOrder[$a->status] ?? 3;
                                                        $bOrder = $statusOrder[$b->status] ?? 3;

                                                        if ($aOrder === $bOrder) {
                                                            return $a->total_paid <=> $b->total_paid;
                                                        }
                                                        return $aOrder <=> $bOrder;
                                                    });
                                                @endphp

                                                @if ($sortedPayments->isEmpty())
                                                    <div class="text-center py-1">
                                                        <i class="bi bi-credit-card-2-front text-info display-4"></i>
                                                        <h5 class="mt-3 fw-bold text-secondary">No School Fees Records
                                                        </h5>
                                                        <p class="text-muted">There are currently no payment records
                                                            for this student.</p>
                                                    </div>
                                                @else
                                                    @foreach ($sortedPayments as $payment)
                                                        @php
                                                            $paid = $payment->histories->sum('amount_paid');
                                                            $paymentBalance = $payment->amount_due - $paid;

                                                            // Header color based on payment status
                                                            if ($paid >= $payment->amount_due) {
                                                                $headerClass = 'bg-success bg-gradient';
                                                                $statusText = 'Paid';
                                                                $statusBg = 'bg-label-success';
                                                            } elseif ($paid > 0) {
                                                                $headerClass = 'bg-warning bg-gradient';
                                                                $statusText = 'Partial';
                                                                $statusBg = 'bg-label-warning';
                                                            } else {
                                                                $headerClass = 'bg-danger bg-gradient';
                                                                $statusText = 'Unpaid';
                                                                $statusBg = 'bg-label-danger';
                                                            }
                                                        @endphp

                                                        <div class="col-md-6 col-lg-6 py-1">
                                                            <div
                                                                class="card payment-card border-0 shadow-lg rounded-4 h-100 overflow-hidden">
                                                                <!-- Card Header as Modal Trigger -->
                                                                <a href="javascript:void(0);"
                                                                    class="text-decoration-none text-dark"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#paymentProgressModal{{ $payment->id }}">
                                                                    <div class="card-header text-white border-0 position-relative {{ $headerClass }}"
                                                                        style="height: 140px; cursor: pointer;">
                                                                        <!-- Header Content Layout -->
                                                                        <div
                                                                            class="d-flex justify-content-between align-items-center h-100">
                                                                            <!-- Left: Payment Info -->
                                                                            <div class="text-start">
                                                                                <h1
                                                                                    class="card-title fw-bold text-white mb-3">
                                                                                    {{ $payment->payment_name }}
                                                                                </h1>
                                                                                <p class="mb-0 small">
                                                                                    Due:
                                                                                    {{ \Carbon\Carbon::parse($payment->due_date)->format('M d, Y') }}
                                                                                </p>
                                                                                <p class="mb-0 small mt-1">
                                                                                    {{ $payment->class_info }} SY:
                                                                                    {{ $payment->school_year }}
                                                                                </p>
                                                                            </div>

                                                                            {{-- <!-- Right: Status Badge -->
                                                                            <div class="text-center">
                                                                                <span
                                                                                    class="badge {{ $statusBg }} text-dark px-3 py-2 fw-bold fs-6">
                                                                                    {{ strtoupper($statusText) }}
                                                                                </span>
                                                                            </div> --}}
                                                                        </div>
                                                                    </div>
                                                                </a>

                                                                <!-- Card Body -->
                                                                <div class="payment-body mt-4">
                                                                    <ul class="p-0 m-0">
                                                                        @if ($statusText === 'Paid')
                                                                            <li class="d-flex mb-2 pb-1">
                                                                                <div class="avatar flex-shrink-0 me-3">
                                                                                    <span
                                                                                        class="avatar-initial rounded bg-label-success">
                                                                                        <i
                                                                                            class="bx bx-check-double fs-4"></i>
                                                                                    </span>
                                                                                </div>
                                                                                <div
                                                                                    class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                                                                    <div class="me-2">
                                                                                        <h6 class="mb-0">Paid</h6>
                                                                                        <small class="text-muted">Fully
                                                                                            Paid</small>
                                                                                    </div>
                                                                                    <div class="user-progress">
                                                                                        <h5 class="fw-semibold mb-0">
                                                                                            ₱{{ number_format($paid, 2) }}
                                                                                            /
                                                                                            ₱{{ number_format($payment->amount_due, 2) }}
                                                                                        </h5>
                                                                                    </div>
                                                                                </div>
                                                                            </li>
                                                                        @elseif($statusText === 'Partial')
                                                                            <li class="d-flex mb-2 pb-1">
                                                                                <div class="avatar flex-shrink-0 me-3">
                                                                                    <span
                                                                                        class="avatar-initial rounded bg-label-warning text-dark">
                                                                                        <i
                                                                                            class="bx bx-file fs-4 text-warning"></i>
                                                                                    </span>
                                                                                </div>
                                                                                <div
                                                                                    class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                                                                    <div class="me-2">
                                                                                        <h6 class="mb-0">Partial</h6>
                                                                                        <small
                                                                                            class="text-muted">Partially
                                                                                            Paid</small>
                                                                                    </div>
                                                                                    <div class="user-progress">
                                                                                        <h5
                                                                                            class="fw-semibold mb-0 text-warning">
                                                                                            ₱{{ number_format($paid, 2) }}
                                                                                            /
                                                                                            ₱{{ number_format($payment->amount_due, 2) }}
                                                                                        </h5>
                                                                                    </div>
                                                                                </div>
                                                                            </li>
                                                                        @else
                                                                            <li class="d-flex">
                                                                                <div class="avatar flex-shrink-0 me-3">
                                                                                    <span
                                                                                        class="avatar-initial rounded bg-label-danger">
                                                                                        <i
                                                                                            class="bx bx-error-circle fs-4"></i>
                                                                                    </span>
                                                                                </div>
                                                                                <div
                                                                                    class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                                                                    <div class="me-2">
                                                                                        <h6 class="mb-0">Unpaid</h6>
                                                                                        <small class="text-muted">Not
                                                                                            Paid</small>
                                                                                    </div>
                                                                                    <div class="user-progress">
                                                                                        <h5
                                                                                            class="fw-semibold mb-0 text-danger">
                                                                                            ₱{{ number_format($paid, 2) }}
                                                                                            /
                                                                                            ₱{{ number_format($payment->amount_due, 2) }}
                                                                                        </h5>
                                                                                    </div>
                                                                                </div>
                                                                            </li>
                                                                        @endif
                                                                    </ul>
                                                                </div>

                                                                <!-- Card Footer -->
                                                                <div
                                                                    class="card-footer bg-light border-0 small text-muted text-center">
                                                                    <span>Created on:
                                                                        {{ $payment->created_at->format('M d, Y') }}</span>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <!-- Payment Progress Modal -->
                                                        <div class="modal fade"
                                                            id="paymentProgressModal{{ $payment->id }}"
                                                            tabindex="-1"
                                                            aria-labelledby="paymentProgressLabel{{ $payment->id }}"
                                                            data-bs-backdrop="static" aria-hidden="true">
                                                            <div class="modal-dialog modal-dialog-centered">
                                                                <div class="modal-content shadow-lg rounded-4">
                                                                    <!-- Modal Header -->
                                                                    <div class="modal-header text-white">
                                                                        <button type="button"
                                                                            class="btn-close btn-close-white"
                                                                            data-bs-dismiss="modal"
                                                                            aria-label="Close"></button>
                                                                    </div>

                                                                    <!-- Modal Body -->
                                                                    <div class="modal-body">
                                                                        <!-- Student Info -->
                                                                        <div
                                                                            class="d-flex align-items-center justify-content-between mb-4">
                                                                            <div
                                                                                class="d-flex flex-column flex-sm-row align-items-center text-center text-sm-start">
                                                                                <img src="{{ $student->student_photo ? asset('public/uploads/' . $student->student_photo) : asset('assetsDashboard/img/student_profile_pictures/student_default_profile.jpg') }}"
                                                                                    class="border border-3 border-warning me-sm-3 mt-1"
                                                                                    style="width: 150px; height: 150px; object-fit: cover;">

                                                                                <div class="mt-2 mt-sm-0">
                                                                                    <h6 class="fw-bold mb-1">
                                                                                        {{ $payment->payment_name }}
                                                                                    </h6>
                                                                                    <h6 class="fw-bold mb-1">
                                                                                        {{ $student->full_name }}</h6>
                                                                                    <small class="text-muted">
                                                                                        {{ $payment->class_info }} |
                                                                                        SY: {{ $payment->school_year }}
                                                                                    </small>
                                                                                </div>
                                                                            </div>


                                                                            <button type="button"
                                                                                class="btn btn-info"
                                                                                data-bs-target="#paymentHistoryModal{{ $payment->id }}"
                                                                                data-bs-toggle="modal"
                                                                                data-bs-dismiss="modal">
                                                                                <i class="bx bx-history me-1"></i>
                                                                                <span class="d-none d-sm-block">View
                                                                                    History</span>
                                                                            </button>
                                                                        </div>

                                                                        <!-- Payment Progress -->
                                                                        <div class="mb-3">
                                                                            <label
                                                                                class="form-label fw-semibold">Payment
                                                                                Progress</label>
                                                                            <input type="text" class="form-control"
                                                                                value="₱{{ number_format($paid, 2) }} / ₱{{ number_format($payment->amount_due, 2) }}"
                                                                                readonly>
                                                                        </div>

                                                                        <!-- Remaining Balance -->
                                                                        <div class="mb-3">
                                                                            <label
                                                                                class="form-label fw-semibold">Remaining
                                                                                Balance</label>
                                                                            <input type="text" class="form-control"
                                                                                value="₱{{ number_format(max($paymentBalance, 0), 2) }}"
                                                                                readonly>
                                                                        </div>

                                                                        <!-- Due Date -->
                                                                        <div class="mb-3">
                                                                            <label class="form-label fw-semibold">Due
                                                                                Date</label>
                                                                            <input type="text" class="form-control"
                                                                                value="{{ \Carbon\Carbon::parse($payment->due_date)->format('F d, Y') }}"
                                                                                readonly>
                                                                        </div>

                                                                        <!-- Payment Status -->
                                                                        <div class="mb-3">
                                                                            <label
                                                                                class="form-label fw-semibold">Payment
                                                                                Status</label>
                                                                            <input type="text"
                                                                                class="form-control {{ $statusBg }} fw-semibold text-start border-0"
                                                                                value="{{ strtoupper($statusText) }}"
                                                                                readonly style="font-weight: 600;">
                                                                        </div>
                                                                    </div>

                                                                    <!-- Modal Footer -->
                                                                    <div
                                                                        class="modal-footer d-flex justify-content-end">
                                                                        <button type="button"
                                                                            class="btn btn-secondary"
                                                                            data-bs-dismiss="modal">Close</button>

                                                                        <!-- In your blade file, find the Add Payment button and add this logic -->
                                                                        @if (auth()->user() && auth()->user()->role === 'parent')
                                                                            @php
                                                                                // Count previous attempts for this payment by this parent
                                                                                $previousAttemptsCount = \App\Models\PaymentRequest::where(
                                                                                    'payment_id',
                                                                                    $payment->id,
                                                                                )
                                                                                    ->where('parent_id', auth()->id())
                                                                                    ->whereIn('status', [
                                                                                        'pending',
                                                                                        'denied',
                                                                                    ])
                                                                                    ->count();
                                                                                $canRequestPayment =
                                                                                    $previousAttemptsCount < 3;
                                                                                $remainingAttempts =
                                                                                    3 - $previousAttemptsCount;
                                                                            @endphp

                                                                            <button type="button"
                                                                                class="btn btn-primary add-payment-btn"
                                                                                data-payment-id="{{ $payment->id }}"
                                                                                data-payment-name="{{ $payment->payment_name }}"
                                                                                data-student-name="{{ $student->full_name }}"
                                                                                data-total-paid="{{ $paid }}"
                                                                                data-amount-due="{{ $payment->amount_due }}"
                                                                                data-balance="{{ max($paymentBalance, 0) }}"
                                                                                @if (!$canRequestPayment) disabled @endif>
                                                                                <i class="bx bx-credit-card me-1"></i>
                                                                                Add Payment
                                                                            </button>

                                                                            @if (!$canRequestPayment)
                                                                                <div
                                                                                    class="alert alert-warning mt-2 small">
                                                                                    <i class="bx bx-error"></i>
                                                                                    You have used all 3 payment request
                                                                                    attempts. Please contact the
                                                                                    administrator.
                                                                                </div>
                                                                            @elseif($remainingAttempts < 3)
                                                                                <div class="text-muted small mt-1">
                                                                                    <i class="bx bx-info-circle"></i>
                                                                                    {{ $remainingAttempts }} attempt(s)
                                                                                    remaining
                                                                                </div>
                                                                            @endif
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!-- /Payment Progress Modal -->

                                                        <!-- Payment History Modal -->
                                                        <div class="modal fade"
                                                            id="paymentHistoryModal{{ $payment->id }}"
                                                            tabindex="-1"
                                                            aria-labelledby="paymentHistoryLabel{{ $payment->id }}"
                                                            data-bs-backdrop="static" aria-hidden="true">
                                                            <div class="modal-dialog modal-lg modal-dialog-centered">
                                                                <div class="modal-content shadow-lg rounded-4">
                                                                    <div class="modal-header text-primary">
                                                                        <h5 class="modal-title fw-bold">Payment History
                                                                            - {{ $student->full_name }}</h5>
                                                                        <button type="button"
                                                                            class="btn-close btn-close-white"
                                                                            data-bs-dismiss="modal"
                                                                            aria-label="Close"></button>
                                                                    </div>

                                                                    <div class="modal-body">
                                                                        <!-- Tabs for Payment History and Payment Requests -->
                                                                        <ul class="nav nav-tabs mb-3"
                                                                            id="paymentTabs{{ $payment->id }}"
                                                                            role="tablist">
                                                                            <li class="nav-item" role="presentation">
                                                                                <button class="nav-link active"
                                                                                    id="history-tab-{{ $payment->id }}"
                                                                                    data-bs-toggle="tab"
                                                                                    data-bs-target="#history-{{ $payment->id }}"
                                                                                    type="button" role="tab">
                                                                                    <i class="bx bx-history me-1"></i>
                                                                                    Official Payments
                                                                                </button>
                                                                            </li>
                                                                            <li class="nav-item" role="presentation">
                                                                                <button class="nav-link"
                                                                                    id="requests-tab-{{ $payment->id }}"
                                                                                    data-bs-toggle="tab"
                                                                                    data-bs-target="#requests-{{ $payment->id }}"
                                                                                    type="button" role="tab">
                                                                                    <i
                                                                                        class="bx bx-time-five me-1"></i>
                                                                                    Payment Requests
                                                                                    @if ($payment->paymentRequests()->where('status', 'pending')->count() > 0)
                                                                                        <span
                                                                                            class="badge bg-warning ms-1">{{ $payment->paymentRequests()->where('status', 'pending')->count() }}</span>
                                                                                    @endif
                                                                                </button>
                                                                            </li>
                                                                        </ul>

                                                                        <div class="tab-content"
                                                                            id="paymentTabsContent{{ $payment->id }}">
                                                                            <!-- Official Payment History Tab -->
                                                                            <div class="tab-pane fade show active"
                                                                                id="history-{{ $payment->id }}"
                                                                                role="tabpanel">
                                                                                @if ($payment->histories->count() > 0)
                                                                                    <div class="table-responsive"
                                                                                        style="max-height: 350px; overflow-y: auto;">
                                                                                        <table
                                                                                            class="table table-sm table-hover align-middle">
                                                                                            <thead class="table-light">
                                                                                                <tr
                                                                                                    class="text-center">
                                                                                                    <th>#</th>
                                                                                                    <th>Amount</th>
                                                                                                    <th>Status</th>
                                                                                                    <th>Payment Method
                                                                                                    </th>
                                                                                                    <th>Recorded By</th>
                                                                                                    <th>Date</th>
                                                                                                </tr>
                                                                                            </thead>
                                                                                            <tbody>
                                                                                                @foreach ($payment->histories as $i => $history)
                                                                                                    <tr
                                                                                                        class="text-center">
                                                                                                        <td>{{ $i + 1 }}
                                                                                                        </td>
                                                                                                        <td>₱{{ number_format($history->amount_paid, 2) }}
                                                                                                        </td>
                                                                                                        <td>
                                                                                                            <span
                                                                                                                class="badge bg-label-success">
                                                                                                                <i
                                                                                                                    class="bx bx-check-circle me-1"></i>
                                                                                                                Approved
                                                                                                            </span>
                                                                                                        </td>

                                                                                                        <!-- Payment Method -->
                                                                                                        <td
                                                                                                            class="text-center">
                                                                                                            @php
                                                                                                                $method = strtolower(
                                                                                                                    $history->payment_method,
                                                                                                                );
                                                                                                                $imagePath =
                                                                                                                    '';

                                                                                                                if (
                                                                                                                    $method ===
                                                                                                                    'gcash'
                                                                                                                ) {
                                                                                                                    $imagePath = asset(
                                                                                                                        'assetsDashboard/img/icons/unicons/gcash_logo.png',
                                                                                                                    );
                                                                                                                } elseif (
                                                                                                                    $method ===
                                                                                                                    'paymaya'
                                                                                                                ) {
                                                                                                                    $imagePath = asset(
                                                                                                                        'assetsDashboard/img/icons/unicons/paymaya_logo.png',
                                                                                                                    );
                                                                                                                } elseif (
                                                                                                                    $method ===
                                                                                                                        'cash_on_hand' ||
                                                                                                                    $method ===
                                                                                                                        'cash on hand'
                                                                                                                ) {
                                                                                                                    $imagePath = asset(
                                                                                                                        'assetsDashboard/img/icons/unicons/coh.png',
                                                                                                                    );
                                                                                                                }
                                                                                                            @endphp

                                                                                                            @if ($imagePath)
                                                                                                                <img src="{{ $imagePath }}"
                                                                                                                    alt="{{ $history->payment_method }}"
                                                                                                                    title="{{ $history->payment_method }}"
                                                                                                                    style="width: 100px; height: auto; object-fit: contain;">
                                                                                                            @else
                                                                                                                {{ $history->payment_method_name ?? '—' }}
                                                                                                            @endif
                                                                                                        </td>
                                                                                                        <td>{{ $history->addedBy->full_name ?? '—' }}
                                                                                                        </td>
                                                                                                        <td>{{ \Carbon\Carbon::parse($history->payment_date)->format('M d, Y h:i A') }}
                                                                                                        </td>
                                                                                                    </tr>
                                                                                                @endforeach
                                                                                            </tbody>
                                                                                        </table>
                                                                                    </div>
                                                                                @else
                                                                                    <p
                                                                                        class="text-muted small mb-0 text-center">
                                                                                        No payment history found.</p>
                                                                                @endif
                                                                            </div>

                                                                            <!-- Payment Requests Tab -->
                                                                            <div class="tab-pane fade"
                                                                                id="requests-{{ $payment->id }}"
                                                                                role="tabpanel">
                                                                                @php
                                                                                    // Get payment requests for this payment
                                                                                    $paymentRequests = $payment
                                                                                        ->paymentRequests()
                                                                                        ->with(['parent'])
                                                                                        ->orderBy(
                                                                                            'requested_at',
                                                                                            'desc',
                                                                                        )
                                                                                        ->get();
                                                                                @endphp

                                                                                @if ($paymentRequests->count() > 0)
                                                                                    <div class="table-responsive"
                                                                                        style="max-height: 350px; overflow-y: auto;">
                                                                                        <table
                                                                                            class="table table-sm table-hover align-middle">
                                                                                            <thead class="table-light">
                                                                                                <tr
                                                                                                    class="text-center">
                                                                                                    <th>#</th>
                                                                                                    <th>Requested By
                                                                                                    </th>
                                                                                                    <th>Amount</th>
                                                                                                    <th>Status</th>
                                                                                                    <th>Payment Method
                                                                                                    </th>
                                                                                                    <th>Requested At
                                                                                                    </th>
                                                                                                    <th>Actions</th>
                                                                                                </tr>
                                                                                            </thead>
                                                                                            <tbody>
                                                                                                @foreach ($paymentRequests as $i => $request)
                                                                                                    @php
                                                                                                        $statusBadgeClass = match (
                                                                                                            $request->status
                                                                                                        ) {
                                                                                                            'pending'
                                                                                                                => 'bg-label-warning',
                                                                                                            'approved'
                                                                                                                => 'bg-label-success',
                                                                                                            'denied'
                                                                                                                => 'bg-label-danger',
                                                                                                            default
                                                                                                                => 'bg-label-secondary',
                                                                                                        };

                                                                                                        $statusIcon = match (
                                                                                                            $request->status
                                                                                                        ) {
                                                                                                            'pending'
                                                                                                                => 'bx bx-time',
                                                                                                            'approved'
                                                                                                                => 'bx bx-check-circle',
                                                                                                            'denied'
                                                                                                                => 'bx bx-x-circle',
                                                                                                            default
                                                                                                                => 'bx bx-question-mark',
                                                                                                        };
                                                                                                    @endphp
                                                                                                    <tr
                                                                                                        class="text-center">
                                                                                                        <td>{{ $i + 1 }}
                                                                                                        </td>
                                                                                                        <td>{{ $request->parent->full_name ?? '—' }}
                                                                                                        </td>
                                                                                                        <td>₱{{ number_format($request->amount_paid, 2) }}
                                                                                                        </td>
                                                                                                        <td>
                                                                                                            <span
                                                                                                                class="badge {{ $statusBadgeClass }}">
                                                                                                                <i
                                                                                                                    class="{{ $statusIcon }} me-1"></i>
                                                                                                                {{ ucfirst($request->status) }}
                                                                                                            </span>
                                                                                                            @if ($request->admin_remarks)
                                                                                                                <br>
                                                                                                                <small
                                                                                                                    class="text-muted">{{ $request->admin_remarks }}</small>
                                                                                                            @endif
                                                                                                        </td>
                                                                                                        <td>
                                                                                                            @php
                                                                                                                $method = strtolower(
                                                                                                                    $request->payment_method,
                                                                                                                );
                                                                                                                $imagePath =
                                                                                                                    '';

                                                                                                                if (
                                                                                                                    $method ===
                                                                                                                    'gcash'
                                                                                                                ) {
                                                                                                                    $imagePath = asset(
                                                                                                                        'assetsDashboard/img/icons/unicons/gcash_logo.png',
                                                                                                                    );
                                                                                                                } elseif (
                                                                                                                    $method ===
                                                                                                                    'paymaya'
                                                                                                                ) {
                                                                                                                    $imagePath = asset(
                                                                                                                        'assetsDashboard/img/icons/unicons/paymaya_logo.png',
                                                                                                                    );
                                                                                                                }
                                                                                                            @endphp

                                                                                                            @if ($imagePath)
                                                                                                                <img src="{{ $imagePath }}"
                                                                                                                    alt="{{ $request->payment_method }}"
                                                                                                                    title="{{ $request->payment_method }}"
                                                                                                                    style="width: 80px; height: auto; object-fit: contain;">
                                                                                                            @else
                                                                                                                {{ $request->payment_method_name ?? '—' }}
                                                                                                            @endif
                                                                                                        </td>
                                                                                                        <td>{{ \Carbon\Carbon::parse($request->requested_at)->format('M d, Y h:i A') }}
                                                                                                        </td>
                                                                                                        <td>
                                                                                                            @if (auth()->user()->role === 'admin' && $request->status === 'pending')
                                                                                                                <div class="btn-group btn-group-sm"
                                                                                                                    role="group">
                                                                                                                    <button
                                                                                                                        type="button"
                                                                                                                        class="btn btn-success btn-sm approve-request-btn"
                                                                                                                        data-request-id="{{ $request->id }}"
                                                                                                                        data-payment-id="{{ $payment->id }}">
                                                                                                                        <i
                                                                                                                            class="bx bx-check"></i>
                                                                                                                    </button>
                                                                                                                    <button
                                                                                                                        type="button"
                                                                                                                        class="btn btn-danger btn-sm deny-request-btn"
                                                                                                                        data-request-id="{{ $request->id }}"
                                                                                                                        data-payment-id="{{ $payment->id }}">
                                                                                                                        <i
                                                                                                                            class="bx bx-x"></i>
                                                                                                                    </button>
                                                                                                                </div>
                                                                                                            @elseif($request->receipt_image)
                                                                                                                <button
                                                                                                                    type="button"
                                                                                                                    class="btn btn-info btn-sm view-receipt-btn"
                                                                                                                    data-receipt-url="{{ asset('public/uploads/' . $request->receipt_image) }}">
                                                                                                                    <i
                                                                                                                        class="bx bx-image-alt"></i>
                                                                                                                </button>
                                                                                                            @endif
                                                                                                        </td>
                                                                                                    </tr>
                                                                                                @endforeach
                                                                                            </tbody>
                                                                                        </table>
                                                                                    </div>

                                                                                    <!-- Admin Remarks Modal -->
                                                                                    <div class="modal fade"
                                                                                        id="adminRemarksModal{{ $payment->id }}"
                                                                                        tabindex="-1"
                                                                                        aria-hidden="true">
                                                                                        <div
                                                                                            class="modal-dialog modal-dialog-centered">
                                                                                            <div class="modal-content">
                                                                                                <div
                                                                                                    class="modal-header">
                                                                                                    <h5
                                                                                                        class="modal-title">
                                                                                                        Payment Request
                                                                                                        Review</h5>
                                                                                                    <button
                                                                                                        type="button"
                                                                                                        class="btn-close"
                                                                                                        data-bs-dismiss="modal"
                                                                                                        aria-label="Close"></button>
                                                                                                </div>
                                                                                                <form
                                                                                                    id="reviewRequestForm{{ $payment->id }}">
                                                                                                    @csrf
                                                                                                    <input
                                                                                                        type="hidden"
                                                                                                        name="request_id"
                                                                                                        id="requestId{{ $payment->id }}">
                                                                                                    <input
                                                                                                        type="hidden"
                                                                                                        name="action"
                                                                                                        id="action{{ $payment->id }}">

                                                                                                    <div
                                                                                                        class="modal-body">
                                                                                                        <div
                                                                                                            class="mb-3">
                                                                                                            <label
                                                                                                                for="adminRemarks{{ $payment->id }}"
                                                                                                                class="form-label">Remarks
                                                                                                                (Optional)
                                                                                                            </label>
                                                                                                            <textarea class="form-control" id="adminRemarks{{ $payment->id }}" name="remarks" rows="3"
                                                                                                                placeholder="Enter remarks for this payment request..."></textarea>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                    <div
                                                                                                        class="modal-footer">
                                                                                                        <button
                                                                                                            type="button"
                                                                                                            class="btn btn-secondary"
                                                                                                            data-bs-dismiss="modal">Cancel</button>
                                                                                                        <button
                                                                                                            type="submit"
                                                                                                            class="btn btn-primary"
                                                                                                            id="submitReviewBtn{{ $payment->id }}">
                                                                                                            Submit
                                                                                                            Review
                                                                                                        </button>
                                                                                                    </div>
                                                                                                </form>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                @else
                                                                                    <p
                                                                                        class="text-muted small mb-0 text-center">
                                                                                        No payment requests found.</p>
                                                                                @endif
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <div
                                                                        class="modal-footer d-flex justify-content-between">
                                                                        <button type="button"
                                                                            class="btn btn-secondary"
                                                                            data-bs-dismiss="modal">Close</button>
                                                                        <button type="button" class="btn btn-primary"
                                                                            data-bs-target="#paymentProgressModal{{ $payment->id }}"
                                                                            data-bs-toggle="modal"
                                                                            data-bs-dismiss="modal">
                                                                            <i class="bx bx-left-arrow-alt me-1"></i>
                                                                            Back to Progress
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!-- /Payment History Modal -->

                                                        <!-- Receipt Preview Modal -->
                                                        <div class="modal fade" id="receiptPreviewModal"
                                                            tabindex="-1" aria-hidden="true">
                                                            <div class="modal-dialog modal-lg modal-dialog-centered">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title">Receipt Preview</h5>
                                                                        <button type="button" class="btn-close"
                                                                            data-bs-dismiss="modal"
                                                                            aria-label="Close"></button>
                                                                    </div>
                                                                    <div class="modal-body text-center">
                                                                        <img id="receiptImage" src=""
                                                                            alt="Receipt"
                                                                            class="img-fluid rounded-3">
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button"
                                                                            class="btn btn-secondary"
                                                                            data-bs-dismiss="modal">Close</button>
                                                                        <a id="downloadReceiptBtn" href="#"
                                                                            class="btn btn-primary" download>
                                                                            <i class="bx bx-download me-1"></i>
                                                                            Download
                                                                        </a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <!-- Add Payment Modal - GCash -->
                                                        <div class="modal fade"
                                                            id="addPaymentModalGcash{{ $payment->id }}"
                                                            tabindex="-1"
                                                            aria-labelledby="addPaymentLabelGcash{{ $payment->id }}"
                                                            data-bs-backdrop="static" aria-hidden="true">
                                                            <div class="modal-dialog modal-lg modal-dialog-centered">
                                                                <div
                                                                    class="modal-content shadow-lg rounded-4 border-0">

                                                                    <!-- Modal Header -->
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title fw-bold text-primary">
                                                                            Add Payment via GCash -
                                                                            {{ $student->full_name }}
                                                                        </h5>
                                                                        <button type="button"
                                                                            class="btn-close btn-close-white"
                                                                            data-bs-dismiss="modal"
                                                                            aria-label="Close"></button>
                                                                    </div>

                                                                    <!-- Modal Body + Form -->
                                                                    @if (auth()->user() && auth()->user()->role === 'parent')
                                                                        <form
                                                                            action="{{ route('parent.addPayment', $payment->id) }}"
                                                                            method="POST"
                                                                            enctype="multipart/form-data"
                                                                            id="gcashForm{{ $payment->id }}">
                                                                            @csrf
                                                                            <input type="hidden"
                                                                                name="payment_method" value="gcash">
                                                                            <input type="hidden" name="final_submit"
                                                                                id="finalSubmitGcash{{ $payment->id }}"
                                                                                value="0">
                                                                            <div class="modal-body">
                                                                                <div class="row">
                                                                                    <!-- Left Column: Payment Details -->
                                                                                    <div class="col-md-6">
                                                                                        <!-- Payment Progress -->
                                                                                        <div class="mb-3">
                                                                                            <label
                                                                                                class="form-label fw-semibold">Payment
                                                                                                Progress</label>
                                                                                            <input type="text"
                                                                                                class="form-control"
                                                                                                value="₱{{ number_format($paid, 2) }} / ₱{{ number_format($payment->amount_due, 2) }}"
                                                                                                readonly>
                                                                                        </div>

                                                                                        <!-- Remaining Balance -->
                                                                                        <div class="mb-3">
                                                                                            <label
                                                                                                class="form-label fw-semibold">Remaining
                                                                                                Balance</label>
                                                                                            <input type="text"
                                                                                                class="form-control"
                                                                                                value="₱{{ number_format(max($payment->amount_due - $paid, 0), 2) }}"
                                                                                                readonly>
                                                                                        </div>

                                                                                        <!-- Amount to Pay -->
                                                                                        <div class="mb-4">
                                                                                            <label
                                                                                                for="amount_paid_gcash_{{ $payment->id }}"
                                                                                                class="form-label fw-semibold text-primary">Amount
                                                                                                to Pay *</label>
                                                                                            <input type="number"
                                                                                                name="amount_paid"
                                                                                                id="amount_paid_gcash_{{ $payment->id }}"
                                                                                                class="form-control required-field amount-to-pay"
                                                                                                min="1"
                                                                                                max="{{ $payment->amount_due - $paid }}"
                                                                                                placeholder="Enter payment amount (max: ₱{{ number_format($payment->amount_due - $paid, 2) }})"
                                                                                                required
                                                                                                data-max-amount="{{ $payment->amount_due - $paid }}">
                                                                                            <div
                                                                                                class="invalid-feedback">
                                                                                                Please enter a valid
                                                                                                amount to pay (max:
                                                                                                ₱{{ number_format($payment->amount_due - $paid, 2) }}).
                                                                                            </div>
                                                                                            <small
                                                                                                class="text-muted d-block mt-1">
                                                                                                Maximum allowed:
                                                                                                ₱{{ number_format($payment->amount_due - $paid, 2) }}
                                                                                            </small>
                                                                                        </div>

                                                                                        <!-- GCash Payment Section -->
                                                                                        <div
                                                                                            class="border rounded-4 shadow-sm bg-white p-4 mb-4">
                                                                                            <div
                                                                                                class="d-flex align-items-center mb-3">
                                                                                                <img src="{{ asset('assetsDashboard/img/icons/unicons/gcash_logo.png') }}"
                                                                                                    alt="GCash"
                                                                                                    width="90"
                                                                                                    class="me-4">
                                                                                                <div>
                                                                                                    <h6
                                                                                                        class="fw-bold mb-0 text-success">
                                                                                                        Pay with GCash
                                                                                                    </h6>
                                                                                                    <small
                                                                                                        class="text-muted">Fast
                                                                                                        & secure mobile
                                                                                                        payment</small>
                                                                                                </div>
                                                                                            </div>

                                                                                            <hr
                                                                                                class="text-muted my-3">

                                                                                            <div
                                                                                                class="bg-light p-3 rounded-3 mb-3">
                                                                                                <p
                                                                                                    class="fw-semibold mb-2 text-success">
                                                                                                    Payment Details</p>
                                                                                                <p class="mb-1">
                                                                                                    <strong>Account
                                                                                                        Name:</strong>
                                                                                                    <span
                                                                                                        class="text-warning">Carl
                                                                                                        Edwin Conde
                                                                                                        (SBES
                                                                                                        TREASURER)
                                                                                                    </span>
                                                                                                </p>
                                                                                                <p class="mb-0">
                                                                                                    <strong>GCash
                                                                                                        Number:</strong>
                                                                                                    <span
                                                                                                        class="text-warning">0951-932-2506</span>
                                                                                                </p>
                                                                                            </div>

                                                                                            <div class="mb-3">
                                                                                                <label
                                                                                                    class="form-label fw-semibold text-primary">GCash
                                                                                                    Reference
                                                                                                    Number *</label>
                                                                                                <input type="text"
                                                                                                    name="reference_number"
                                                                                                    id="reference_number_gcash_{{ $payment->id }}"
                                                                                                    class="form-control border-primary required-field"
                                                                                                    placeholder="Enter 13-digit GCash Ref. No."
                                                                                                    required>
                                                                                                <small
                                                                                                    class="text-muted">Found
                                                                                                    in your GCash
                                                                                                    transaction
                                                                                                    receipt</small>
                                                                                                <div
                                                                                                    class="invalid-feedback">
                                                                                                    Please enter your
                                                                                                    GCash reference
                                                                                                    number.</div>
                                                                                            </div>

                                                                                            <div class="mb-3">
                                                                                                <label
                                                                                                    class="form-label fw-semibold text-primary">Upload
                                                                                                    GCash Receipt
                                                                                                    Screenshot *</label>
                                                                                                <input type="file"
                                                                                                    name="receipt_image"
                                                                                                    id="receipt_image_gcash_{{ $payment->id }}"
                                                                                                    class="form-control required-field"
                                                                                                    accept="image/*"
                                                                                                    required>
                                                                                                <small
                                                                                                    class="text-muted">Upload
                                                                                                    a clear screenshot
                                                                                                    of your payment
                                                                                                    receipt</small>
                                                                                                <div
                                                                                                    class="invalid-feedback">
                                                                                                    Please upload your
                                                                                                    receipt screenshot.
                                                                                                </div>
                                                                                            </div>

                                                                                            <!-- Preview of uploaded image -->
                                                                                            <div class="mb-3"
                                                                                                id="receipt_preview_gcash_{{ $payment->id }}"
                                                                                                style="display: none;">
                                                                                                <label
                                                                                                    class="form-label fw-semibold text-primary">Receipt
                                                                                                    Preview</label>
                                                                                                <div
                                                                                                    class="border rounded-3 p-2 text-center">
                                                                                                    <img id="preview_image_gcash_{{ $payment->id }}"
                                                                                                        src="#"
                                                                                                        alt="Receipt Preview"
                                                                                                        class="img-fluid rounded-2"
                                                                                                        style="height: auto; width: auto; max-width: 100%; max-height: 300px;">
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <!-- Terms and Conditions -->
                                                                                        <div class="form-check mb-3">
                                                                                            <input
                                                                                                class="form-check-input required-field"
                                                                                                type="checkbox"
                                                                                                id="termsGcash{{ $payment->id }}"
                                                                                                required>
                                                                                            <label
                                                                                                class="form-check-label small text-info"
                                                                                                for="termsGcash{{ $payment->id }}">
                                                                                                I confirm that I have
                                                                                                completed the payment
                                                                                                via GCash and uploaded
                                                                                                the correct
                                                                                                receipt *
                                                                                            </label>
                                                                                            <div
                                                                                                class="invalid-feedback">
                                                                                                You must accept the
                                                                                                terms and conditions.
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>

                                                                                    <!-- Right Column: QR Code -->
                                                                                    <div class="col-md-6">
                                                                                        <div class="sticky-top"
                                                                                            style="top: 20px;">
                                                                                            <div class="text-center">
                                                                                                <div
                                                                                                    class="border rounded-4 shadow-lg p-4 bg-white mb-3">
                                                                                                    <img src="{{ asset('assetsDashboard/img/backgrounds/gcash_qr.png') }}"
                                                                                                        alt="GCash QR"
                                                                                                        class="img-fluid rounded-3">
                                                                                                </div>
                                                                                                <div
                                                                                                    class="small text-muted mb-2">
                                                                                                    <p class="mb-1">
                                                                                                        Use the GCash
                                                                                                        app to scan this
                                                                                                        QR code</p>
                                                                                                    <p class="mb-0">
                                                                                                        Make sure the
                                                                                                        amount matches
                                                                                                        your payment</p>
                                                                                                </div>

                                                                                                <!-- QR Instructions -->
                                                                                                <div
                                                                                                    class="bg-light rounded-4 p-3 text-start">
                                                                                                    <p
                                                                                                        class="fw-semibold text-primary mb-2">
                                                                                                        How to Pay:</p>
                                                                                                    <ol
                                                                                                        class="small mb-0 ps-3">
                                                                                                        <li>Open GCash
                                                                                                            app</li>
                                                                                                        <li>Tap "Scan
                                                                                                            QR"</li>
                                                                                                        <li>Scan the QR
                                                                                                            code above
                                                                                                        </li>
                                                                                                        <li>Enter the
                                                                                                            payment
                                                                                                            amount</li>
                                                                                                        <li>Complete the
                                                                                                            transaction
                                                                                                        </li>
                                                                                                        <li>Save your
                                                                                                            receipt
                                                                                                            screenshot
                                                                                                        </li>
                                                                                                    </ol>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>

                                                                            <!-- Modal Footer -->
                                                                            <div
                                                                                class="modal-footer d-flex justify-content-between">
                                                                                <button type="button"
                                                                                    class="btn btn-secondary"
                                                                                    data-bs-dismiss="modal">
                                                                                    <i class="bx bx-x me-1"></i> Cancel
                                                                                </button>
                                                                                <button type="button"
                                                                                    class="btn btn-success d-flex align-items-center"
                                                                                    onclick="validateAndShowGcashReview({{ $payment->id }})">
                                                                                    <i
                                                                                        class="bx bx-check-circle me-1"></i>
                                                                                    Review & Submit Payment
                                                                                </button>
                                                                            </div>
                                                                        </form>
                                                                    @else
                                                                        <div class="alert alert-info text-center p-4">
                                                                            <i class="bx bx-info-circle fs-4 mb-2"></i>
                                                                            <p class="mb-0">Only parents can add
                                                                                payments.</p>
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!-- /Add Payment Modal - GCash -->

                                                        <!-- Add Payment Modal - PayMaya -->
                                                        <div class="modal fade"
                                                            id="addPaymentModalPaymaya{{ $payment->id }}"
                                                            tabindex="-1"
                                                            aria-labelledby="addPaymentLabelPaymaya{{ $payment->id }}"
                                                            data-bs-backdrop="static" aria-hidden="true">
                                                            <div class="modal-dialog modal-lg modal-dialog-centered">
                                                                <div
                                                                    class="modal-content shadow-lg rounded-4 border-0">

                                                                    <!-- Modal Header -->
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title fw-bold text-primary">
                                                                            Add Payment via PayMaya -
                                                                            {{ $student->full_name }}
                                                                        </h5>
                                                                        <button type="button"
                                                                            class="btn-close btn-close-white"
                                                                            data-bs-dismiss="modal"
                                                                            aria-label="Close"></button>
                                                                    </div>

                                                                    <!-- Modal Body + Form -->
                                                                    @if (auth()->user() && auth()->user()->role === 'parent')
                                                                        <form
                                                                            action="{{ route('parent.addPayment', $payment->id) }}"
                                                                            method="POST"
                                                                            enctype="multipart/form-data"
                                                                            id="paymayaForm{{ $payment->id }}">
                                                                            @csrf
                                                                            <input type="hidden"
                                                                                name="payment_method" value="paymaya">
                                                                            <input type="hidden" name="final_submit"
                                                                                id="finalSubmitPaymaya{{ $payment->id }}"
                                                                                value="0">
                                                                            <div class="modal-body">
                                                                                <div class="row">
                                                                                    <!-- Left Column: Payment Details -->
                                                                                    <div class="col-md-6">
                                                                                        <!-- Payment Progress -->
                                                                                        <div class="mb-3">
                                                                                            <label
                                                                                                class="form-label fw-semibold">Payment
                                                                                                Progress</label>
                                                                                            <input type="text"
                                                                                                class="form-control"
                                                                                                value="₱{{ number_format($paid, 2) }} / ₱{{ number_format($payment->amount_due, 2) }}"
                                                                                                readonly>
                                                                                        </div>

                                                                                        <!-- Remaining Balance -->
                                                                                        <div class="mb-3">
                                                                                            <label
                                                                                                class="form-label fw-semibold">Remaining
                                                                                                Balance</label>
                                                                                            <input type="text"
                                                                                                class="form-control"
                                                                                                value="₱{{ number_format(max($payment->amount_due - $paid, 0), 2) }}"
                                                                                                readonly>
                                                                                        </div>

                                                                                        <!-- Amount to Pay -->
                                                                                        <div class="mb-4">
                                                                                            <label
                                                                                                for="amount_paid_paymaya_{{ $payment->id }}"
                                                                                                class="form-label fw-semibold text-primary">Amount
                                                                                                to Pay *</label>
                                                                                            <input type="number"
                                                                                                name="amount_paid"
                                                                                                id="amount_paid_paymaya_{{ $payment->id }}"
                                                                                                class="form-control required-field amount-to-pay"
                                                                                                min="1"
                                                                                                max="{{ $payment->amount_due - $paid }}"
                                                                                                placeholder="Enter payment amount (max: ₱{{ number_format($payment->amount_due - $paid, 2) }})"
                                                                                                required
                                                                                                data-max-amount="{{ $payment->amount_due - $paid }}">
                                                                                            <div
                                                                                                class="invalid-feedback">
                                                                                                Please enter a valid
                                                                                                amount to pay (max:
                                                                                                ₱{{ number_format($payment->amount_due - $paid, 2) }}).
                                                                                            </div>
                                                                                            <small
                                                                                                class="text-muted d-block mt-1">
                                                                                                Maximum allowed:
                                                                                                ₱{{ number_format($payment->amount_due - $paid, 2) }}
                                                                                            </small>
                                                                                        </div>

                                                                                        <!-- PayMaya Payment Section -->
                                                                                        <div
                                                                                            class="border rounded-4 shadow-sm bg-white p-4 mb-4">
                                                                                            <div
                                                                                                class="d-flex align-items-center mb-3">
                                                                                                <img src="{{ asset('assetsDashboard/img/icons/unicons/paymaya_logo.png') }}"
                                                                                                    alt="PayMaya"
                                                                                                    width="120"
                                                                                                    class="me-2">
                                                                                                <div>
                                                                                                    <h6
                                                                                                        class="fw-bold mb-0 text-success">
                                                                                                        Pay with PayMaya
                                                                                                    </h6>
                                                                                                    <small
                                                                                                        class="text-muted">Fast
                                                                                                        & secure mobile
                                                                                                        payment</small>
                                                                                                </div>
                                                                                            </div>

                                                                                            <hr
                                                                                                class="text-muted my-3">

                                                                                            <div
                                                                                                class="bg-light p-3 rounded-3 mb-3">
                                                                                                <p
                                                                                                    class="fw-semibold mb-2 text-success">
                                                                                                    Payment Details</p>
                                                                                                <p class="mb-1">
                                                                                                    <strong>Account
                                                                                                        Name:</strong>
                                                                                                    <span
                                                                                                        class="text-warning">Carl
                                                                                                        Edwin Conde
                                                                                                        (SBES
                                                                                                        TREASURER)</span>
                                                                                                </p>
                                                                                                <p class="mb-0">
                                                                                                    <strong>PayMaya
                                                                                                        Number:</strong><br>
                                                                                                    <span
                                                                                                        class="text-warning">0951-932-2506</span>
                                                                                                </p>
                                                                                            </div>

                                                                                            <div class="mb-3">
                                                                                                <label
                                                                                                    class="form-label fw-semibold text-primary">PayMaya
                                                                                                    Reference
                                                                                                    Number *</label>
                                                                                                <input type="text"
                                                                                                    name="reference_number"
                                                                                                    id="reference_number_paymaya_{{ $payment->id }}"
                                                                                                    class="form-control border-primary required-field"
                                                                                                    placeholder="Enter PayMaya Ref. No."
                                                                                                    required>
                                                                                                <small
                                                                                                    class="text-muted">Found
                                                                                                    in your PayMaya
                                                                                                    transaction
                                                                                                    receipt</small>
                                                                                                <div
                                                                                                    class="invalid-feedback">
                                                                                                    Please enter your
                                                                                                    PayMaya reference
                                                                                                    number.</div>
                                                                                            </div>

                                                                                            <div class="mb-3">
                                                                                                <label
                                                                                                    class="form-label fw-semibold text-primary">Upload
                                                                                                    PayMaya Receipt
                                                                                                    Screenshot *</label>
                                                                                                <input type="file"
                                                                                                    name="receipt_image"
                                                                                                    id="receipt_image_paymaya_{{ $payment->id }}"
                                                                                                    class="form-control required-field"
                                                                                                    accept="image/*"
                                                                                                    required>
                                                                                                <small
                                                                                                    class="text-muted">Upload
                                                                                                    a clear screenshot
                                                                                                    of your payment
                                                                                                    receipt</small>
                                                                                                <div
                                                                                                    class="invalid-feedback">
                                                                                                    Please upload your
                                                                                                    receipt screenshot.
                                                                                                </div>
                                                                                            </div>

                                                                                            <!-- Preview of uploaded image -->
                                                                                            <div class="mb-3"
                                                                                                id="receipt_preview_paymaya_{{ $payment->id }}"
                                                                                                style="display: none;">
                                                                                                <label
                                                                                                    class="form-label fw-semibold text-primary">Receipt
                                                                                                    Preview</label>
                                                                                                <div
                                                                                                    class="border rounded-3 p-2 text-center">
                                                                                                    <img id="preview_image_paymaya_{{ $payment->id }}"
                                                                                                        src="#"
                                                                                                        alt="Receipt Preview"
                                                                                                        class="img-fluid rounded-2"
                                                                                                        style="height: auto; width: auto; max-width: 100%; max-height: 300px;">
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <!-- Terms and Conditions -->
                                                                                        <div class="form-check mb-3">
                                                                                            <input
                                                                                                class="form-check-input required-field"
                                                                                                type="checkbox"
                                                                                                id="termsPaymaya{{ $payment->id }}"
                                                                                                required>
                                                                                            <label
                                                                                                class="form-check-label small text-info"
                                                                                                for="termsPaymaya{{ $payment->id }}">
                                                                                                I confirm that I have
                                                                                                completed the payment
                                                                                                via PayMaya and uploaded
                                                                                                the correct
                                                                                                receipt *
                                                                                            </label>
                                                                                            <div
                                                                                                class="invalid-feedback">
                                                                                                You must accept the
                                                                                                terms and conditions.
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>

                                                                                    <!-- Right Column: QR Code -->
                                                                                    <div class="col-md-6">
                                                                                        <div class="sticky-top"
                                                                                            style="top: 20px;">
                                                                                            <div class="text-center">
                                                                                                <div
                                                                                                    class="border rounded-4 shadow-lg p-4 bg-white mb-3">
                                                                                                    <img src="{{ asset('assetsDashboard/img/backgrounds/maya_qr.png') }}"
                                                                                                        alt="PayMaya QR"
                                                                                                        class="img-fluid rounded-3">
                                                                                                </div>
                                                                                                <div
                                                                                                    class="small text-muted mb-4">
                                                                                                    <p class="mb-1">
                                                                                                        Use the PayMaya
                                                                                                        app to scan this
                                                                                                        QR code</p>
                                                                                                    <p class="mb-0">
                                                                                                        Make sure the
                                                                                                        amount matches
                                                                                                        your payment</p>
                                                                                                </div>

                                                                                                <!-- QR Instructions -->
                                                                                                <div
                                                                                                    class="bg-light rounded-4 p-3 text-start">
                                                                                                    <p
                                                                                                        class="fw-semibold text-primary mb-2">
                                                                                                        How to Pay:</p>
                                                                                                    <ol
                                                                                                        class="small mb-0 ps-3">
                                                                                                        <li>Open PayMaya
                                                                                                            app</li>
                                                                                                        <li>Tap "Scan
                                                                                                            QR"</li>
                                                                                                        <li>Scan the QR
                                                                                                            code above
                                                                                                        </li>
                                                                                                        <li>Enter the
                                                                                                            payment
                                                                                                            amount</li>
                                                                                                        <li>Complete the
                                                                                                            transaction
                                                                                                        </li>
                                                                                                        <li>Save your
                                                                                                            receipt
                                                                                                            screenshot
                                                                                                        </li>
                                                                                                    </ol>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>

                                                                            <!-- Modal Footer -->
                                                                            <div
                                                                                class="modal-footer d-flex justify-content-between">
                                                                                <button type="button"
                                                                                    class="btn btn-secondary"
                                                                                    data-bs-dismiss="modal">
                                                                                    <i class="bx bx-x me-1"></i> Cancel
                                                                                </button>
                                                                                <button type="button"
                                                                                    class="btn btn-primary d-flex align-items-center"
                                                                                    onclick="validateAndShowPaymayaReview({{ $payment->id }})">
                                                                                    <i
                                                                                        class="bx bx-check-circle me-1"></i>
                                                                                    Review & Submit Payment
                                                                                </button>
                                                                            </div>
                                                                        </form>
                                                                    @else
                                                                        <div class="alert alert-info text-center p-4">
                                                                            <i class="bx bx-info-circle fs-4 mb-2"></i>
                                                                            <p class="mb-0">Only parents can add
                                                                                payments.</p>
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!-- /Add Payment Modal - PayMaya -->

                                                        <!-- Review & Confirmation Modal (for both GCash and PayMaya) -->
                                                        <div class="modal fade"
                                                            id="paymentReviewModal{{ $payment->id }}"
                                                            tabindex="-1"
                                                            aria-labelledby="paymentReviewLabel{{ $payment->id }}"
                                                            data-bs-backdrop="static" aria-hidden="true">
                                                            <div class="modal-dialog modal-lg modal-dialog-centered">
                                                                <div
                                                                    class="modal-content shadow-lg rounded-4 border-0">
                                                                    <!-- Modal Header -->
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title fw-bold">
                                                                            <i class="bx bx-check-circle me-2"></i>
                                                                            Review Your Payment
                                                                        </h5>
                                                                        <button type="button"
                                                                            class="btn-close btn-close-white"
                                                                            data-bs-dismiss="modal"
                                                                            aria-label="Close"></button>
                                                                    </div>

                                                                    <!-- Modal Body -->
                                                                    <div class="modal-body">
                                                                        <div class="alert alert-info mb-4">
                                                                            <i class="bx bx-info-circle me-2"></i>
                                                                            <strong>Please review your payment details
                                                                                carefully.</strong> Once submitted, you
                                                                            cannot edit this
                                                                            payment request.
                                                                        </div>

                                                                        <div class="row">
                                                                            <!-- Left Column: Payment Summary -->
                                                                            <div class="col-md-6">
                                                                                <div
                                                                                    class="border rounded-4 shadow-sm p-4 mb-4">
                                                                                    <h6
                                                                                        class="fw-bold text-primary mb-3">
                                                                                        Payment Summary
                                                                                    </h6>

                                                                                    <div class="mb-3">
                                                                                        <div
                                                                                            class="d-flex justify-content-between mb-2">
                                                                                            <span
                                                                                                class="text-muted">Student:</span>
                                                                                            <span class="fw-semibold"
                                                                                                id="reviewStudentName{{ $payment->id }}"></span>
                                                                                        </div>
                                                                                        <div
                                                                                            class="d-flex justify-content-between mb-2">
                                                                                            <span
                                                                                                class="text-muted">Payment:</span>
                                                                                            <span class="fw-semibold"
                                                                                                id="reviewPaymentName{{ $payment->id }}"></span>
                                                                                        </div>
                                                                                        <div
                                                                                            class="d-flex justify-content-between mb-2">
                                                                                            <span
                                                                                                class="text-muted">Payment
                                                                                                Method:</span>
                                                                                            <span class="fw-semibold"
                                                                                                id="reviewPaymentMethod{{ $payment->id }}"></span>
                                                                                        </div>
                                                                                        <div
                                                                                            class="d-flex justify-content-between mb-2">
                                                                                            <span
                                                                                                class="text-muted">Amount
                                                                                                to Pay:</span>
                                                                                            <span
                                                                                                class="fw-semibold text-success"
                                                                                                id="reviewAmountPaid{{ $payment->id }}"></span>
                                                                                        </div>
                                                                                        <div
                                                                                            class="d-flex justify-content-between">
                                                                                            <span
                                                                                                class="text-muted">Reference
                                                                                                Number:</span>
                                                                                            <span class="fw-semibold"
                                                                                                id="reviewReferenceNumber{{ $payment->id }}"></span>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>

                                                                                <div
                                                                                    class="bg-light rounded-4 p-3 mb-3">
                                                                                    <h6
                                                                                        class="fw-bold text-primary mb-2">
                                                                                        Payment Details
                                                                                    </h6>
                                                                                    <div class="mb-2">
                                                                                        <strong>Account
                                                                                            Name:</strong><br>
                                                                                        <div class="text-warning">Carl
                                                                                            Edwin Conde (SBES TREASURER)
                                                                                        </div>
                                                                                    </div>
                                                                                    <div>
                                                                                        <strong>Contact
                                                                                            Number:</strong><br>
                                                                                        <div class="text-warning">
                                                                                            0951-932-2506</div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>

                                                                            <!-- Right Column: Receipt Preview -->
                                                                            <div class="col-md-6">
                                                                                <div class="sticky-top"
                                                                                    style="top: 20px;">
                                                                                    <div
                                                                                        class="border rounded-4 shadow-sm p-4 bg-white">
                                                                                        <h6
                                                                                            class="fw-bold text-primary mb-3 text-center">
                                                                                            Receipt Preview
                                                                                        </h6>
                                                                                        <div class="text-center mb-3">
                                                                                            <div
                                                                                                class="receipt-preview-container bg-light rounded-3 p-3">
                                                                                                <img id="reviewReceiptImage{{ $payment->id }}"
                                                                                                    src="#"
                                                                                                    alt="Receipt Screenshot"
                                                                                                    class="img-fluid rounded-2"
                                                                                                    style="height: auto; width: auto; max-width: 100%; max-height: 300px;">
                                                                                            </div>
                                                                                            <p
                                                                                                class="small text-muted mt-2">
                                                                                                This is the receipt you
                                                                                                uploaded
                                                                                            </p>
                                                                                        </div>

                                                                                        <div
                                                                                            class="alert alert-warning small">
                                                                                            <i
                                                                                                class="bx bx-check-circle me-1"></i>
                                                                                            <strong>Verification:</strong>
                                                                                            Please ensure that all
                                                                                            details match your actual
                                                                                            payment receipt.
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>

                                                                        <!-- Confirmation Checkbox -->
                                                                        <div class="form-check mt-3">
                                                                            <input
                                                                                class="form-check-input required-field"
                                                                                type="checkbox"
                                                                                id="confirmPaymentDetails{{ $payment->id }}"
                                                                                required>
                                                                            <label
                                                                                class="form-check-label fw-semibold text-primary"
                                                                                for="confirmPaymentDetails{{ $payment->id }}">
                                                                                I have reviewed all payment details and
                                                                                confirm they are correct *
                                                                            </label>
                                                                            <div class="invalid-feedback">You must
                                                                                confirm that all details are correct.
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <!-- Modal Footer -->
                                                                    <div
                                                                        class="modal-footer d-flex justify-content-between border-top pt-3">
                                                                        <button type="button"
                                                                            class="btn btn-secondary"
                                                                            onclick="backToPaymentForm({{ $payment->id }})">
                                                                            <i class="bx bx-left-arrow-alt me-1"></i>
                                                                            Back to Edit
                                                                        </button>
                                                                        <button type="button" class="btn btn-success"
                                                                            onclick="submitFinalPayment({{ $payment->id }})"
                                                                            id="finalSubmitBtn{{ $payment->id }}">
                                                                            <i class="bx bx-check-circle me-1"></i>
                                                                            Confirm & Submit Payment
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!-- /Review & Confirmation Modal -->
                                                    @endforeach
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                </div>
                        @endforeach
                    </div>
                @endif

                <!-- Payment History Modals -->
                @foreach ($classHistory as $index => $classItem)
                    @php
                        $payments = $student
                            ->payments()
                            ->with(['histories.addedBy'])
                            ->whereHas('classStudent', function ($query) use ($classItem, $student) {
                                $query
                                    ->where('student_id', $student->id)
                                    ->where('school_year_id', $classItem->pivot->school_year_id);
                            })
                            ->get();
                    @endphp

                    @foreach ($payments as $payment)
                        @if ($payment->histories->count() > 0)
                            <div class="modal fade" id="paymentHistoryModal{{ $payment->id }}" tabindex="-1"
                                aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Payment History - {{ $payment->payment_name }}
                                            </h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="table-responsive">
                                                <table class="table table-bordered">
                                                    <thead class="table-light">
                                                        <tr>
                                                            <th>Date</th>
                                                            <th>Amount Paid</th>
                                                            <th>Payment Method</th>
                                                            <th>Processed By</th>
                                                            <th>Receipt</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($payment->histories as $history)
                                                            <tr>
                                                                <td>{{ \Carbon\Carbon::parse($history->payment_date)->format('M d, Y h:i A') }}
                                                                </td>
                                                                <td class="text-success">
                                                                    ₱{{ number_format($history->amount_paid, 2) }}
                                                                </td>
                                                                <td>{{ ucfirst($history->payment_method) }}</td>
                                                                <td>{{ $history->addedBy->full_name ?? 'System' }}
                                                                </td>
                                                                <td>
                                                                    @if ($history->receipt_image)
                                                                        <a href="{{ asset('storage/' . $history->receipt_image) }}"
                                                                            target="_blank"
                                                                            class="btn btn-sm btn-outline-info">
                                                                            View Receipt
                                                                        </a>
                                                                    @else
                                                                        <span class="text-muted">No receipt</span>
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                @endforeach
            </div>
        </div>

    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <!-- Attendance Calendar Script -->
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const calendarEl = document.getElementById('attendanceCalendar');
                if (!calendarEl) return;

                // Determine if we're on a small screen
                function isMobile() {
                    return window.innerWidth <= 576; // Bootstrap "sm" breakpoint
                }

                // Map statuses to icons (mobile) and colors
                const statusMap = {
                    'present': {
                        label: '✓',
                        color: '#198754'
                    }, // success
                    'absent': {
                        label: '✕',
                        color: '#dc3545'
                    }, // danger
                    'late': {
                        label: 'L',
                        color: '#ffc107'
                    }, // warning
                    'excused': {
                        label: 'E',
                        color: '#6c757d'
                    }, // secondary
                    'default': {
                        label: '-',
                        color: '#6c757d'
                    } // fallback
                };

                // Load event data from Blade
                const events = [
                    @foreach ($attendanceRecords as $record)
                        {
                            title: "{{ $record['status'] }}",
                            start: "{{ $record['date'] }}",
                            status: "{{ strtolower($record['status']) }}",
                            remarks: "{{ $record['remarks'] }}",
                        },
                    @endforeach
                ];

                // Function to transform events depending on screen size
                function formatEvents(mobile) {
                    return events.map(e => {
                        const map = statusMap[e.status] || statusMap.default;
                        return {
                            title: mobile ? map.label : e.title,
                            start: e.start,
                            color: map.color,
                            textColor: '#fff',
                            extendedProps: {
                                remarks: e.remarks
                            }
                        };
                    });
                }

                let calendar = new FullCalendar.Calendar(calendarEl, {
                    initialView: 'dayGridMonth',
                    height: 'auto',
                    headerToolbar: {
                        left: 'prev,next today',
                        center: 'title',
                        right: ''
                    },
                    events: formatEvents(isMobile()),

                    eventDidMount: function(info) {
                        if (info.event.extendedProps.remarks) {
                            new bootstrap.Tooltip(info.el, {
                                title: info.event.extendedProps.remarks,
                                placement: 'top',
                                trigger: 'hover',
                                container: 'body'
                            });
                        }
                    },

                    eventContent: function(arg) {
                        return {
                            html: `<div style="display:flex;justify-content:center;align-items:center;height:100%;">${arg.event.title}</div>`
                        };
                    }
                });

                calendar.render();

                // Re-render calendar on tab show
                document.querySelector('[data-bs-target="#attendances"]')
                    ?.addEventListener('shown.bs.tab', () => calendar.updateSize());

                // Re-render on window resize to toggle between text ↔ symbols
                window.addEventListener('resize', () => {
                    calendar.removeAllEvents();
                    calendar.addEventSource(formatEvents(isMobile()));
                    calendar.render();
                });
            });
        </script>

        <!-- Payment Method Selection with SweetAlert (Updated with Images) -->
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Handle Add Payment button click
                document.querySelectorAll('.add-payment-btn').forEach(button => {
                    button.addEventListener('click', function(e) {
                        e.preventDefault();

                        const paymentId = this.getAttribute('data-payment-id');
                        const paymentName = this.getAttribute('data-payment-name');
                        const studentName = this.getAttribute('data-student-name');
                        const totalPaid = this.getAttribute('data-total-paid');
                        const amountDue = this.getAttribute('data-amount-due');
                        const balance = this.getAttribute('data-balance');

                        // Store data for later use
                        sessionStorage.setItem('currentPaymentId', paymentId);
                        sessionStorage.setItem('currentPaymentName', paymentName);
                        sessionStorage.setItem('currentStudentName', studentName);

                        Swal.fire({
                            title: 'Select Payment Method',
                            html: `
                                <div class="row g-3">
                                    <div class="col-6">
                                        <div class="payment-option p-3 border rounded-3 text-center cursor-pointer" data-method="gcash">
                                            <img src="{{ asset('assetsDashboard/img/icons/unicons/gcash_logo.png') }}" alt="GCash" class="img-fluid mb-2" style="height: 50px;">
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="payment-option p-3 border rounded-3 text-center cursor-pointer" data-method="paymaya">
                                            <img src="{{ asset('assetsDashboard/img/icons/unicons/paymaya_logo.png') }}" alt="PayMaya" class="img-fluid mb-2" style="height: 50px;">
                                        </div>
                                    </div>
                                </div>
                            `,
                            confirmButtonText: 'Continue',
                            showConfirmButton: true, // <--- MUST BE TRUE
                            reverseButtons: false,
                            buttonsStyling: false,
                            width: '500px',
                            customClass: {
                                container: 'my-swal-container',
                                confirmButton: 'btn btn-primary mt-3 w-100',
                            },

                            didOpen: () => {
                                document.querySelectorAll('.payment-option').forEach(
                                    option => {
                                        option.addEventListener('click', function() {

                                            document.querySelectorAll(
                                                    '.payment-option')
                                                .forEach(opt => opt.classList
                                                    .remove('border-primary',
                                                        'bg-primary-subtle'));

                                            this.classList.add('border-primary',
                                                'bg-primary-subtle');

                                            const method = this.getAttribute(
                                                'data-method');
                                            sessionStorage.setItem(
                                                'selectedMethod', method);

                                            Swal.getConfirmButton().style
                                                .display = 'inline-block';
                                        });
                                    });

                                const confirmButton = Swal.getConfirmButton();
                                confirmButton.style.display = 'none';

                                confirmButton.addEventListener('click', () => {
                                    const method = sessionStorage.getItem(
                                        'selectedMethod');
                                    if (method === 'gcash') {
                                        new bootstrap.Modal(document.getElementById(
                                            `addPaymentModalGcash${paymentId}`
                                        )).show();
                                    } else if (method === 'paymaya') {
                                        new bootstrap.Modal(document.getElementById(
                                            `addPaymentModalPaymaya${paymentId}`
                                        )).show();
                                    }
                                    Swal.close();
                                });
                            }
                        });

                    });
                });
            });
        </script>

        <!-- Form Submission Handler with Confirmation Modal -->
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Handle form submissions
                document.querySelectorAll('form[id^="gcashForm"], form[id^="paymayaForm"]').forEach(form => {
                    form.addEventListener('submit', function(e) {
                        e.preventDefault();

                        const formData = new FormData(this);
                        const paymentId = this.id.replace('gcashForm', '').replace('paymayaForm', '');
                        const isGcash = this.id.startsWith('gcashForm');

                        // Get the current form values
                        const amountPaid = this.querySelector('input[name="amount_paid"]').value;
                        const referenceNumber = this.querySelector('input[name="reference_number"]')
                            .value;
                        const receiptImage = this.querySelector('input[name="receipt_image"]').files[0];

                        // Store data for confirmation modal
                        const paymentName = sessionStorage.getItem('currentPaymentName');
                        const studentName = sessionStorage.getItem('currentStudentName');

                        // First, show loading state
                        const submitBtn = this.querySelector('button[type="submit"]');
                        const originalText = submitBtn.innerHTML;
                        submitBtn.innerHTML = '<i class="bx bx-loader bx-spin me-1"></i> Processing...';
                        submitBtn.disabled = true;

                        // Create a temporary image URL for receipt preview
                        let receiptPreviewUrl = '';
                        if (receiptImage) {
                            receiptPreviewUrl = URL.createObjectURL(receiptImage);
                        }

                        // Simulate AJAX submission (replace with actual fetch if needed)
                        setTimeout(() => {
                            // Close current modal
                            const currentModal = bootstrap.Modal.getInstance(this.closest(
                                '.modal'));
                            if (currentModal) {
                                currentModal.hide();
                            }

                            // Update confirmation modal content
                            document.getElementById('confirmationPaymentName').textContent =
                                paymentName;
                            document.getElementById('confirmationStudentName').textContent =
                                studentName;
                            document.getElementById('confirmationAmountPaid').textContent =
                                '₱' + parseFloat(amountPaid).toFixed(2);
                            document.getElementById('confirmationPaymentMethod').textContent =
                                isGcash ? 'GCash' : 'PayMaya';
                            document.getElementById('confirmationReferenceNumber').textContent =
                                referenceNumber;

                            if (receiptPreviewUrl) {
                                document.getElementById('confirmationReceiptImage').src =
                                    receiptPreviewUrl;
                            }

                            // Show confirmation modal
                            const confirmationModal = new bootstrap.Modal(document
                                .getElementById(
                                    `paymentConfirmationModal${paymentId}`));
                            confirmationModal.show();

                            // Reset form button
                            submitBtn.innerHTML = originalText;
                            submitBtn.disabled = false;

                            // Actually submit the form after showing confirmation
                            setTimeout(() => {
                                this.submit();
                            }, 100);

                        }, 1500); // Simulated processing time
                    });
                });
            });

            function viewPaymentHistory(paymentId) {
                // Close confirmation modal
                const confirmationModal = bootstrap.Modal.getInstance(document.getElementById(
                    `paymentConfirmationModal${paymentId}`));
                if (confirmationModal) {
                    confirmationModal.hide();
                }

                // Show payment history modal
                const historyModal = new bootstrap.Modal(document.getElementById(
                    `paymentHistoryModal${paymentId}`));
                historyModal.show();
            }
        </script>

        <script>
            // Add this to your existing JavaScript code
            document.addEventListener('DOMContentLoaded', function() {
                // Handle approve/deny payment request buttons
                document.querySelectorAll('.approve-request-btn, .deny-request-btn').forEach(button => {
                    button.addEventListener('click', function() {
                        const requestId = this.getAttribute('data-request-id');
                        const paymentId = this.getAttribute('data-payment-id');
                        const action = this.classList.contains('approve-request-btn') ? 'approve' :
                            'deny';

                        // Set values in the modal
                        document.getElementById(`requestId${paymentId}`).value = requestId;
                        document.getElementById(`action${paymentId}`).value = action;

                        // Update submit button text based on action
                        const submitBtn = document.getElementById(`submitReviewBtn${paymentId}`);
                        if (action === 'approve') {
                            submitBtn.classList.remove('btn-primary');
                            submitBtn.classList.add('btn-success');
                            submitBtn.innerHTML = '<i class="bx bx-check me-1"></i> Approve Request';
                        } else {
                            submitBtn.classList.remove('btn-success');
                            submitBtn.classList.add('btn-danger');
                            submitBtn.innerHTML = '<i class="bx bx-x me-1"></i> Deny Request';
                        }

                        // Show the remarks modal
                        new bootstrap.Modal(document.getElementById(`adminRemarksModal${paymentId}`))
                            .show();
                    });
                });

                // Handle form submission for payment request review
                document.querySelectorAll('[id^="reviewRequestForm"]').forEach(form => {
                    form.addEventListener('submit', function(e) {
                        e.preventDefault();

                        const paymentId = this.id.replace('reviewRequestForm', '');
                        const requestId = document.getElementById(`requestId${paymentId}`).value;
                        const action = document.getElementById(`action${paymentId}`).value;
                        const remarks = document.getElementById(`adminRemarks${paymentId}`).value;

                        const submitBtn = document.getElementById(`submitReviewBtn${paymentId}`);
                        const originalText = submitBtn.innerHTML;
                        submitBtn.innerHTML = '<i class="bx bx-loader bx-spin me-1"></i> Processing...';
                        submitBtn.disabled = true;

                        // Send AJAX request
                        fetch(`/admin/payment-requests/${requestId}/review`, {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector(
                                        'meta[name="csrf-token"]').getAttribute('content')
                                },
                                body: JSON.stringify({
                                    action: action,
                                    remarks: remarks
                                })
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    // Close the remarks modal
                                    const remarksModal = bootstrap.Modal.getInstance(document
                                        .getElementById(`adminRemarksModal${paymentId}`));
                                    if (remarksModal) {
                                        remarksModal.hide();
                                    }

                                    // Show success message
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Success!',
                                        text: `Payment request has been ${action}d.`,
                                        confirmButtonText: 'OK'
                                    }).then(() => {
                                        // Reload the page to show updated status
                                        location.reload();
                                    });
                                } else {
                                    throw new Error(data.message || 'Failed to process request');
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error!',
                                    text: error.message ||
                                        'Failed to process request. Please try again.',
                                    confirmButtonText: 'OK'
                                });
                            })
                            .finally(() => {
                                submitBtn.innerHTML = originalText;
                                submitBtn.disabled = false;
                            });
                    });
                });

                // Handle view receipt button
                document.querySelectorAll('.view-receipt-btn').forEach(button => {
                    button.addEventListener('click', function() {
                        const receiptUrl = this.getAttribute('data-receipt-url');
                        document.getElementById('receiptImage').src = receiptUrl;
                        document.getElementById('downloadReceiptBtn').href = receiptUrl;

                        new bootstrap.Modal(document.getElementById('receiptPreviewModal')).show();
                    });
                });

                // Initialize all tab panes
                document.querySelectorAll('[id^="paymentHistoryModal"]').forEach(modal => {
                    modal.addEventListener('shown.bs.modal', function() {
                        const paymentId = this.id.replace('paymentHistoryModal', '');

                        // Initialize tab functionality
                        const tabTriggers = [].slice.call(document.querySelectorAll(
                            `#paymentTabs${paymentId} button`));
                        tabTriggers.forEach(triggerEl => {
                            const tabTrigger = new bootstrap.Tab(triggerEl);
                            triggerEl.addEventListener('click', function(event) {
                                event.preventDefault();
                                tabTrigger.show();
                            });
                        });
                    });
                });
            });
        </script>

        <script>
            // Add this to your existing JavaScript in the blade file
            document.addEventListener('DOMContentLoaded', function() {
                // Handle Add Payment button click with attempt checking
                document.querySelectorAll('.add-payment-btn').forEach(button => {
                    button.addEventListener('click', function(e) {
                        const paymentId = this.getAttribute('data-payment-id');

                        // Check attempts via AJAX before showing payment method selection
                        fetch(`/parent/check-attempts/${paymentId}`, {
                                method: 'GET',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector(
                                        'meta[name="csrf-token"]').getAttribute('content')
                                }
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (!data.can_request) {
                                    Swal.fire({
                                        icon: 'warning',
                                        title: 'Max Attempts Reached',
                                        html: `
                            <div class="text-center">
                                <i class="bx bx-error-circle display-4 text-warning mb-3"></i>
                                <h5 class="fw-bold">Payment Request Limit Reached</h5>
                                <p class="text-muted">
                                    You have used all 3 payment request attempts for this fee.
                                </p>
                                <div class="alert alert-info small text-start mt-3">
                                    <i class="bx bx-info-circle me-1"></i>
                                    <strong>What to do:</strong>
                                    <ul class="mb-0 mt-2">
                                        <li>Contact the school administrator</li>
                                        <li>Visit the school office for assistance</li>
                                        <li>Call: (123) 456-7890</li>
                                    </ul>
                                </div>
                            </div>
                        `,
                                        confirmButtonText: 'Okay',
                                        confirmButtonColor: '#3085d6',
                                    });
                                    return;
                                }

                                if (data.remaining_attempts === 1) {
                                    // Warning on last attempt
                                    Swal.fire({
                                        icon: 'warning',
                                        title: 'Last Attempt',
                                        text: 'This is your final payment request attempt. Please ensure all details are correct.',
                                        confirmButtonText: 'Continue',
                                        showCancelButton: true,
                                        cancelButtonText: 'Cancel'
                                    }).then((result) => {
                                        if (result.isConfirmed) {
                                            // Continue with existing payment method selection code
                                            showPaymentMethodSelection(paymentId);
                                        }
                                    });
                                } else {
                                    // Continue normally
                                    showPaymentMethodSelection(paymentId);
                                }
                            });

                        // Your existing function for showing payment method selection
                        function showPaymentMethodSelection(paymentId) {
                            const paymentName = button.getAttribute('data-payment-name');
                            const studentName = button.getAttribute('data-student-name');
                            const totalPaid = button.getAttribute('data-total-paid');
                            const amountDue = button.getAttribute('data-amount-due');
                            const balance = button.getAttribute('data-balance');

                            // Store data for later use
                            sessionStorage.setItem('currentPaymentId', paymentId);
                            sessionStorage.setItem('currentPaymentName', paymentName);
                            sessionStorage.setItem('currentStudentName', studentName);

                            // Your existing SweetAlert for payment method selection
                            Swal.fire({
                                title: 'Select Payment Method',
                                html: `
                        <div class="row g-3">
                            <div class="col-6">
                                <div class="payment-option p-3 border rounded-3 text-center cursor-pointer" data-method="gcash">
                                    <img src="{{ asset('assetsDashboard/img/icons/unicons/gcash_logo.png') }}" alt="GCash" class="img-fluid mb-2" style="height: 50px;">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="payment-option p-3 border rounded-3 text-center cursor-pointer" data-method="paymaya">
                                    <img src="{{ asset('assetsDashboard/img/icons/unicons/paymaya_logo.png') }}" alt="PayMaya" class="img-fluid mb-2" style="height: 50px;">
                                </div>
                            </div>
                        </div>
                    `,
                                confirmButtonText: 'Continue',
                                showConfirmButton: true,
                                reverseButtons: false,
                                buttonsStyling: false,
                                width: '500px',
                                customClass: {
                                    container: 'my-swal-container',
                                    confirmButton: 'btn btn-primary mt-3 w-100',
                                },
                                didOpen: () => {
                                    // Your existing payment option selection code
                                }
                            });
                        }
                    });
                });
            });
        </script>

        <script>
            // File upload preview for both GCash and PayMaya
            document.addEventListener('DOMContentLoaded', function() {
                // Handle file upload preview for GCash
                document.querySelectorAll('input[id^="receipt_image_gcash_"]').forEach(input => {
                    input.addEventListener('change', function() {
                        const paymentId = this.id.replace('receipt_image_gcash_', '');
                        const preview = document.getElementById(`preview_image_gcash_${paymentId}`);
                        const previewContainer = document.getElementById(
                            `receipt_preview_gcash_${paymentId}`);

                        if (this.files && this.files[0]) {
                            const reader = new FileReader();

                            reader.onload = function(e) {
                                preview.src = e.target.result;
                                previewContainer.style.display = 'block';
                            }

                            reader.readAsDataURL(this.files[0]);
                        } else {
                            previewContainer.style.display = 'none';
                        }
                    });
                });

                // Handle file upload preview for PayMaya
                document.querySelectorAll('input[id^="receipt_image_paymaya_"]').forEach(input => {
                    input.addEventListener('change', function() {
                        const paymentId = this.id.replace('receipt_image_paymaya_', '');
                        const preview = document.getElementById(`preview_image_paymaya_${paymentId}`);
                        const previewContainer = document.getElementById(
                            `receipt_preview_paymaya_${paymentId}`);

                        if (this.files && this.files[0]) {
                            const reader = new FileReader();

                            reader.onload = function(e) {
                                preview.src = e.target.result;
                                previewContainer.style.display = 'block';
                            }

                            reader.readAsDataURL(this.files[0]);
                        } else {
                            previewContainer.style.display = 'none';
                        }
                    });
                });

                // Real-time validation for required fields
                document.querySelectorAll('.required-field').forEach(field => {
                    field.addEventListener('input', function() {
                        validateField(this);
                    });
                    field.addEventListener('change', function() {
                        validateField(this);
                    });
                });

                // Special validation for amount fields
                document.querySelectorAll('.amount-to-pay').forEach(field => {
                    field.addEventListener('input', function() {
                        validateAmountField(this);
                    });
                    field.addEventListener('change', function() {
                        validateAmountField(this);
                    });
                    field.addEventListener('blur', function() {
                        validateAmountField(this);
                    });
                });
            });

            // Validate amount field specifically
            function validateAmountField(field) {
                const maxAmount = parseFloat(field.getAttribute('data-max-amount'));
                const enteredAmount = parseFloat(field.value);

                if (!field.value.trim()) {
                    field.classList.add('is-invalid');
                    field.classList.remove('is-valid');
                    field.nextElementSibling.textContent = 'Please enter an amount to pay.';
                    return false;
                } else if (isNaN(enteredAmount) || enteredAmount <= 0) {
                    field.classList.add('is-invalid');
                    field.classList.remove('is-valid');
                    field.nextElementSibling.textContent = 'Please enter a valid amount greater than 0.';
                    return false;
                } else if (enteredAmount > maxAmount) {
                    field.classList.add('is-invalid');
                    field.classList.remove('is-valid');
                    field.nextElementSibling.textContent = `Amount cannot exceed ₱${maxAmount.toFixed(2)}.`;
                    return false;
                } else {
                    field.classList.remove('is-invalid');
                    field.classList.add('is-valid');
                    field.nextElementSibling.textContent = 'Please enter a valid amount to pay.';
                    return true;
                }
            }

            // Validate individual field
            function validateField(field) {
                if (field.type === 'checkbox') {
                    if (!field.checked) {
                        field.classList.add('is-invalid');
                        field.classList.remove('is-valid');
                    } else {
                        field.classList.remove('is-invalid');
                        field.classList.add('is-valid');
                    }
                } else if (field.type === 'file') {
                    if (!field.files || field.files.length === 0) {
                        field.classList.add('is-invalid');
                        field.classList.remove('is-valid');
                    } else {
                        field.classList.remove('is-invalid');
                        field.classList.add('is-valid');
                    }
                } else if (field.classList.contains('amount-to-pay')) {
                    // Use the specialized amount validation
                    validateAmountField(field);
                } else {
                    if (!field.value.trim()) {
                        field.classList.add('is-invalid');
                        field.classList.remove('is-valid');
                    } else {
                        field.classList.remove('is-invalid');
                        field.classList.add('is-valid');
                    }
                }
            }

            // Validate all required fields in GCash form
            function validateAndShowGcashReview(paymentId) {
                const form = document.getElementById(`gcashForm${paymentId}`);
                const requiredFields = form.querySelectorAll('.required-field');
                let isValid = true;

                // Validate each required field
                requiredFields.forEach(field => {
                    if (field.classList.contains('amount-to-pay')) {
                        if (!validateAmountField(field)) {
                            isValid = false;
                        }
                    } else {
                        validateField(field);

                        if (field.type === 'checkbox') {
                            if (!field.checked) isValid = false;
                        } else if (field.type === 'file') {
                            if (!field.files || field.files.length === 0) isValid = false;
                        } else {
                            if (!field.value.trim()) isValid = false;
                        }
                    }
                });

                if (!isValid) {
                    // Scroll to first invalid field
                    const firstInvalid = form.querySelector('.is-invalid');
                    if (firstInvalid) {
                        firstInvalid.scrollIntoView({
                            behavior: 'smooth',
                            block: 'center'
                        });
                        firstInvalid.focus();
                    }
                    return;
                }

                // All fields valid, proceed to show review
                showGcashReviewModal(paymentId);
            }

            // Validate all required fields in PayMaya form
            function validateAndShowPaymayaReview(paymentId) {
                const form = document.getElementById(`paymayaForm${paymentId}`);
                const requiredFields = form.querySelectorAll('.required-field');
                let isValid = true;

                // Validate each required field
                requiredFields.forEach(field => {
                    if (field.classList.contains('amount-to-pay')) {
                        if (!validateAmountField(field)) {
                            isValid = false;
                        }
                    } else {
                        validateField(field);

                        if (field.type === 'checkbox') {
                            if (!field.checked) isValid = false;
                        } else if (field.type === 'file') {
                            if (!field.files || field.files.length === 0) isValid = false;
                        } else {
                            if (!field.value.trim()) isValid = false;
                        }
                    }
                });

                if (!isValid) {
                    // Scroll to first invalid field
                    const firstInvalid = form.querySelector('.is-invalid');
                    if (firstInvalid) {
                        firstInvalid.scrollIntoView({
                            behavior: 'smooth',
                            block: 'center'
                        });
                        firstInvalid.focus();
                    }
                    return;
                }

                // All fields valid, proceed to show review
                showPaymayaReviewModal(paymentId);
            }

            // Show GCash Review Modal
            function showGcashReviewModal(paymentId) {
                // Get form values
                const amountPaid = document.getElementById(`amount_paid_gcash_${paymentId}`).value;
                const referenceNumber = document.getElementById(`reference_number_gcash_${paymentId}`).value;
                const receiptImage = document.getElementById(`receipt_image_gcash_${paymentId}`).files[0];

                // Get payment details from session storage
                const paymentName = sessionStorage.getItem('currentPaymentName');
                const studentName = sessionStorage.getItem('currentStudentName');

                // Update review modal content
                document.getElementById(`reviewStudentName${paymentId}`).textContent = studentName;
                document.getElementById(`reviewPaymentName${paymentId}`).textContent = paymentName;
                document.getElementById(`reviewPaymentMethod${paymentId}`).textContent = 'GCash';
                document.getElementById(`reviewAmountPaid${paymentId}`).textContent = '₱' + parseFloat(amountPaid).toFixed(2);
                document.getElementById(`reviewReferenceNumber${paymentId}`).textContent = referenceNumber;

                // Show receipt preview
                if (receiptImage) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        document.getElementById(`reviewReceiptImage${paymentId}`).src = e.target.result;
                    };
                    reader.readAsDataURL(receiptImage);
                }

                // Store form data for later submission
                sessionStorage.setItem(`pendingPayment_${paymentId}`, JSON.stringify({
                    method: 'gcash',
                    amountPaid: amountPaid,
                    referenceNumber: referenceNumber,
                    receiptImage: receiptImage
                }));

                // Close current modal
                const currentModal = bootstrap.Modal.getInstance(document.getElementById(`addPaymentModalGcash${paymentId}`));
                if (currentModal) {
                    currentModal.hide();
                }

                // Reset confirmation checkbox in review modal
                const confirmCheckbox = document.getElementById(`confirmPaymentDetails${paymentId}`);
                confirmCheckbox.checked = false;
                confirmCheckbox.classList.remove('is-valid', 'is-invalid');

                // Add validation for confirmation checkbox
                confirmCheckbox.addEventListener('change', function() {
                    validateField(this);
                });

                // Show review modal
                const reviewModal = new bootstrap.Modal(document.getElementById(`paymentReviewModal${paymentId}`));
                reviewModal.show();
            }

            // Show PayMaya Review Modal
            function showPaymayaReviewModal(paymentId) {
                // Get form values
                const amountPaid = document.getElementById(`amount_paid_paymaya_${paymentId}`).value;
                const referenceNumber = document.getElementById(`reference_number_paymaya_${paymentId}`).value;
                const receiptImage = document.getElementById(`receipt_image_paymaya_${paymentId}`).files[0];

                // Get payment details from session storage
                const paymentName = sessionStorage.getItem('currentPaymentName');
                const studentName = sessionStorage.getItem('currentStudentName');

                // Update review modal content
                document.getElementById(`reviewStudentName${paymentId}`).textContent = studentName;
                document.getElementById(`reviewPaymentName${paymentId}`).textContent = paymentName;
                document.getElementById(`reviewPaymentMethod${paymentId}`).textContent = 'PayMaya';
                document.getElementById(`reviewAmountPaid${paymentId}`).textContent = '₱' + parseFloat(amountPaid).toFixed(2);
                document.getElementById(`reviewReferenceNumber${paymentId}`).textContent = referenceNumber;

                // Show receipt preview
                if (receiptImage) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        document.getElementById(`reviewReceiptImage${paymentId}`).src = e.target.result;
                    };
                    reader.readAsDataURL(receiptImage);
                }

                // Store form data for later submission
                sessionStorage.setItem(`pendingPayment_${paymentId}`, JSON.stringify({
                    method: 'paymaya',
                    amountPaid: amountPaid,
                    referenceNumber: referenceNumber,
                    receiptImage: receiptImage
                }));

                // Close current modal
                const currentModal = bootstrap.Modal.getInstance(document.getElementById(`addPaymentModalPaymaya${paymentId}`));
                if (currentModal) {
                    currentModal.hide();
                }

                // Reset confirmation checkbox in review modal
                const confirmCheckbox = document.getElementById(`confirmPaymentDetails${paymentId}`);
                confirmCheckbox.checked = false;
                confirmCheckbox.classList.remove('is-valid', 'is-invalid');

                // Add validation for confirmation checkbox
                confirmCheckbox.addEventListener('change', function() {
                    validateField(this);
                });

                // Show review modal
                const reviewModal = new bootstrap.Modal(document.getElementById(`paymentReviewModal${paymentId}`));
                reviewModal.show();
            }

            // Go back to edit payment form
            function backToPaymentForm(paymentId) {
                // Get stored payment data
                const paymentData = JSON.parse(sessionStorage.getItem(`pendingPayment_${paymentId}`));

                // Close review modal
                const reviewModal = bootstrap.Modal.getInstance(document.getElementById(`paymentReviewModal${paymentId}`));
                if (reviewModal) {
                    reviewModal.hide();
                }

                // Re-open the appropriate payment form
                if (paymentData.method === 'gcash') {
                    const gcashModal = new bootstrap.Modal(document.getElementById(`addPaymentModalGcash${paymentId}`));
                    gcashModal.show();
                } else if (paymentData.method === 'paymaya') {
                    const paymayaModal = new bootstrap.Modal(document.getElementById(`addPaymentModalPaymaya${paymentId}`));
                    paymayaModal.show();
                }
            }

            // Submit final payment after review
            function submitFinalPayment(paymentId) {
                // Validate the confirmation checkbox
                const confirmCheckbox = document.getElementById(`confirmPaymentDetails${paymentId}`);
                if (!confirmCheckbox.checked) {
                    validateField(confirmCheckbox);
                    confirmCheckbox.scrollIntoView({
                        behavior: 'smooth',
                        block: 'center'
                    });
                    confirmCheckbox.focus();
                    return;
                }

                // Get stored payment data
                const paymentData = JSON.parse(sessionStorage.getItem(`pendingPayment_${paymentId}`));

                // Get the appropriate form
                let form;
                if (paymentData.method === 'gcash') {
                    form = document.getElementById(`gcashForm${paymentId}`);
                } else {
                    form = document.getElementById(`paymayaForm${paymentId}`);
                }

                // Set final submit flag
                document.getElementById(
                        `finalSubmit${paymentData.method.charAt(0).toUpperCase() + paymentData.method.slice(1)}${paymentId}`)
                    .value = "1";

                // Show loading state
                Swal.fire({
                    title: 'Submitting Payment...',
                    text: 'Please wait while we process your payment request.',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                // Submit the form
                form.submit();

                // Clear stored data
                sessionStorage.removeItem(`pendingPayment_${paymentId}`);
            }
        </script>

        <!-- Tom Select JS for School Year Filter -->
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                new TomSelect("#schoolFeesYearFilter", {
                    placeholder: "Select school year...",
                    persist: false,
                    create: false,
                    plugins: ['dropdown_input'],
                    onChange: function() {
                        this.input.form.submit();
                    }
                });
            });
        </script>
    @endpush

    @push('styles')
        <!-- Bootstrap 5 Calendar (FullCalendar) -->
        <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.css" rel="stylesheet">

        <!-- Responsive styles for calendar -->
        <style>
            @media (max-width: 576px) {
                #attendanceCalendar .fc-daygrid-day-number {
                    font-size: 0.75rem;
                }

                #attendanceCalendar .fc-event-title {
                    font-size: 0.8rem;
                }
            }

            #attendanceCalendar td.fc-daygrid-day.fc-day-today>.fc-daygrid-day-frame {
                background-color: var(--bs-info, #0d6efd) !important;
                color: #fff !important;
            }

            .payment-card {
                transition: transform 0.25s ease, box-shadow 0.25s ease;
                cursor: pointer;
            }

            .payment-option.border-primary {
                border-width: 2px !important;
                transition: 0.2s ease;
            }

            .payment-option.bg-primary-subtle {
                background-color: #e3f2fd !important;
                transition: 0.2s ease;
            }

            .payment-card:hover {
                transform: translateY(-6px);
                box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
            }

            .payment-card .card-title {
                font-size: 1.1rem;
                display: -webkit-box;
                -webkit-line-clamp: 2;
                -webkit-box-orient: vertical;
                overflow: hidden;
            }

            .payment-card .badge {
                font-size: 0.75rem;
                border-radius: 0.5rem;
            }

            .payment-card img {
                transition: transform 0.3s ease;
            }

            .payment-card:hover img {
                transform: scale(1.05);
            }

            /* SweetAlert Custom Styles */
            .swal2-popup {
                border-radius: 1rem !important;
            }

            .swal2-title {
                color: #2c3e50 !important;
                font-weight: 600 !important;
            }

            .cursor-pointer {
                cursor: pointer !important;
            }

            .payment-option:hover {
                transform: translateY(-2px);
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
                transition: all 0.2s ease;
            }

            .payment-option {
                transition: all 0.2s ease;
            }

            /* Confirmation Modal Styles */
            .icon-container {
                width: 80px;
                height: 80px;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .receipt-preview-container {
                max-width: 100%;
                overflow: hidden;
            }

            /* Slight overlay tint for better contrast */
            .card-header.bg-success,
            .card-header.bg-warning,
            .card-header.bg-danger {
                background-image: linear-gradient(to bottom right, rgba(0, 0, 0, 0.05), rgba(0, 0, 0, 0.1));
            }

            .border-dashed {
                border-style: dashed !important;
            }

            .payment-section img[src*="gcash_logo"] {
                filter: drop-shadow(0 0 4px rgba(0, 123, 255, 0.3));
            }

            .ts-control {
                background-color: #e0f7fa;
                border-color: #42a5f5;
            }

            .ts-control .item {
                border-radius: 4px;
                padding: 3px 8px;
                margin-right: 4px;
            }

            .ts-dropdown .option.active {
                background-color: #e3f2fd;
                color: #1976d2;
            }

            .card-body {
                flex: 1 1 auto;
                padding: 0.3rem 0.3rem;
            }

            .payment-body {
                padding: 1rem 1rem;
            }
        </style>
    @endpush
