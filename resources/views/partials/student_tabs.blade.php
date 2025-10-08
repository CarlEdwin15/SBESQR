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
                <h5 class="fw-bold text-primary mb-3">Classes and Grades</h5>

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
                                                @if (request()->is('teacher/*'))
                                                    <!-- Teacher-only export button -->
                                                    <a href="{{ route('teacher.student.card', [
                                                        'student_id' => $student->id,
                                                        'school_year' => $classItem->pivot->school_year_id,
                                                    ]) }}"
                                                        target="_blank" class="btn btn-success btn-sm">
                                                        <i class="bx bx-printer"></i> Export
                                                    </a>
                                                @endif
                                            </div>
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
                                                                <td class="text-center">
                                                                    {{ $item['quarters']->firstWhere('quarter', 1)['grade'] ?? '-' }}
                                                                </td>
                                                                <td class="text-center">
                                                                    {{ $item['quarters']->firstWhere('quarter', 2)['grade'] ?? '-' }}
                                                                </td>
                                                                <td class="text-center">
                                                                    {{ $item['quarters']->firstWhere('quarter', 3)['grade'] ?? '-' }}
                                                                </td>
                                                                <td class="text-center">
                                                                    {{ $item['quarters']->firstWhere('quarter', 4)['grade'] ?? '-' }}
                                                                </td>
                                                                <td class="text-center">
                                                                    @if ($item['final_average'] !== null)
                                                                        <strong>{{ $item['final_average'] }}</strong>
                                                                    @else
                                                                        <span class="text-muted">-</span>
                                                                    @endif
                                                                </td>
                                                                <td class="text-center">
                                                                    @if ($item['remarks'] === 'passed')
                                                                        <span
                                                                            class="badge bg-label-success fw-bold">Passed</span>
                                                                    @elseif($item['remarks'] === 'failed')
                                                                        <span
                                                                            class="badge bg-label-danger fw-bold">Failed</span>
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
                                                @php $ga = $generalAverages[$classItem->id]; @endphp
                                                <div
                                                    class="p-3 rounded-3 border bg-light d-flex justify-content-between">
                                                    <span class="fw-bold">General Average:</span>
                                                    <span>
                                                        <strong>{{ $ga['general_average'] }}</strong>
                                                        @if ($ga['remarks'] === 'passed')
                                                            <span class="badge bg-label-success ms-2">Passed</span>
                                                        @else
                                                            <span class="badge bg-label-danger ms-2">Failed</span>
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
                                        <img src="{{ asset('storage/' . $parent->profile_photo) }}"
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
                                        Last seen: {{ $parent->last_seen ?? 'Not available' }}
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
                        ->with(['schoolYear', 'class', 'schedule']) // ✅ use schedule, not subject
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const calendarEl = document.getElementById('attendanceCalendar');
            if (!calendarEl) return;

            // Determine if we’re on a small screen
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

            // ✅ Re-render on window resize to toggle between text ↔ symbols
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

    <style>
        @media (max-width: 576px) {
            #attendanceCalendar .fc-daygrid-day-number {
                font-size: 0.75rem;
            }

            #attendanceCalendar .fc-event-title {
                font-size: 0.8rem;
            }
        }
    </style>
@endpush

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.css" rel="stylesheet">

    <style>
        #attendanceCalendar td.fc-daygrid-day.fc-day-today>.fc-daygrid-day-frame {
            background-color: var(--bs-info, #0d6efd) !important;
            color: #fff !important;
        }
    </style>
@endpush
