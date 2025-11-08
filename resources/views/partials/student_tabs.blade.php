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
                                    <button
                                        class="accordion-button collapsed fw-bold text-dark d-flex justify-content-between align-items-center"
                                        type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapse{{ $index }}">
                                        <span>
                                            {{ $schoolYear }} —
                                            {{ $classItem->formatted_grade_level }} -
                                            {{ $classItem->section }}
                                        </span>
                                        <div>
                                            <span class="badge {{ $badgeClass }} ms-3">{{ ucfirst($status) }}</span>
                                            @if ($status === 'enrolled')
                                                <span class="badge bg-primary ms-2">Current Class</span>
                                            @endif
                                        </div>
                                        @php
                                            $adviser = $classItem->advisers
                                                ->where('pivot.school_year_id', $classItem->pivot->school_year_id)
                                                ->first();
                                        @endphp
                                        <span>
                                            @if ($adviser)
                                                <small class="text-muted ms-2">Adviser:
                                                    {{ $adviser->full_name }}</small>
                                            @endif
                                        </span>
                                    </button>
                                </h2>
                                <div id="collapse{{ $index }}" class="accordion-collapse collapse">
                                    <div class="accordion-body">
                                        @if (empty($subjectsWithGrades))
                                            <div class="p-3 mb-3 rounded-3 bg-light text-center border">
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
                                No parents linked to this student.
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

        </div>
    </div>

</div>

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js"></script>

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
    </style>
@endpush
