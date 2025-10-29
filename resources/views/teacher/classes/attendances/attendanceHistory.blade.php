@extends('./layouts.main')

@section('title', 'Teacher | Attendance History')

@section('content')

    <!-- Menu -->
    <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
        <div class="app-brand bg-dark">
            <a href="{{ url('/home') }}" class="app-brand-link">
                <img src="{{ asset('assets/img/logo.png') }}" alt="Logo" class="app-brand-logo">
                <span class="app-brand-text menu-text fw-bolder text-warning" style="padding: 9px">Teacher's
                    <span class="text-warning">Management</span>
                </span>
            </a>
        </div>

        <ul class="menu-inner py-1 bg-dark">

            <!-- Dashboard sidebar-->
            <li class="menu-item">
                <a href="{{ '/home ' }}" class="menu-link bg-dark text-light">
                    <i class="menu-icon tf-icons bx bx-home-circle text-light"></i>
                    <div class="text-light">Dashboard</div>
                </a>
            </li>


            {{-- Students sidebar --}}
            <li class="menu-item">
                <a href="javascript:void(0)" class="menu-link menu-toggle bg-dark text-light">
                    <i class="menu-icon tf-icons bx bxs-graduation text-light"></i>
                    <div class="text-light">Students</div>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item">
                        <a href="{{ route('teacher.my.students') }}" class="menu-link bg-dark text-light">
                            <div class="text-light">My Students</div>
                        </a>
                    </li>
                </ul>
            </li>

            {{-- Classes sidebar --}}
            <li class="menu-item active open">
                <a href="javascript:void(0)" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons bx bx-notepad"></i>
                    <div>Classes</div>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item active">
                        <a href="{{ route('teacher.myClasses') }}" class="menu-link bg-dark text-light">
                            <div class="text-warning">My Classes</div>
                        </a>
                    </li>
                </ul>
            </li>

            {{-- Account Settings sidebar --}}
            <li class="menu-item">
                <a href="{{ route('teacher.account.settings') }}" class="menu-link bg-dark text-light">
                    <i class="bx bx-cog me-3 text-light"></i>
                    <div class="text-light">Account Settings</div>
                </a>
            </li>

            {{-- Log Out sidebar --}}
            <li class="menu-item">
                <form id="logout-form" method="POST" action="{{ route('logout') }}">
                    @csrf
                    <a class="menu-link bg-dark text-light" href="{{ route('logout') }}"
                        onclick="event.preventDefault(); confirmLogout();">
                        <i class="bx bx-power-off me-3 text-light"></i>
                        <div class="text-light">{{ __('Log Out') }}</div>
                    </a>
                </form>

            </li>

        </ul>
    </aside>
    <!-- / Menu -->

    <!-- Content wrapper -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <h4 class="fw-bold text-warning mb-0">
                    <span class="text-muted fw-light">
                        <a class="text-muted fw-light" href="{{ route('home') }}">Dashboard</a> /
                        <a class="text-muted fw-light" href="{{ route('teacher.myClasses') }}">Classes</a> /
                        <a class="text-muted fw-light"
                            href="{{ route('teacher.myClass', ['grade_level' => $class->grade_level, 'section' => $class->section, 'school_year' => $selectedYear]) }}">
                            {{ ucfirst($class->grade_level) }} - {{ $class->section }} ({{ $selectedYear }})
                        </a> /
                    </span>
                    Attendance History
                </h4>
            </div>
        </div>

        <h2 class="card-title mb-5 fw-bold text-primary text-center">
            Attendance History {{ ucfirst($class->grade_level) }} - {{ $class->section }}
            ({{ $selectedYear }})
        </h2>

        <div class="d-flex justify-content-between align-items-center mb-2 mt-2">
            <a href="{{ route('teacher.myAttendanceRecord', ['grade_level' => $class->grade_level, 'section' => $class->section, 'school_year' => $selectedYear]) }}"
                class="btn btn-danger d-flex align-items-center gap-2">
                <i class='bx bx-chevrons-left'></i>
                <span class="d-none d-sm-block">Back</span>
            </a>

            {{-- Date Selection Form --}}
            <form id="dateForm" method="GET">
                <input type="hidden" name="school_year" value="{{ $selectedYear }}">
                <div class="d-flex gap-2 align-items-end">
                    <div>
                        <input type="date" id="date" name="date" class="form-control"
                            value="{{ $targetDate }}">
                    </div>
                    <button class="btn btn-primary me-2 d-flex align-items-center gap-2" type="submit">
                        <i class='bx bx-filter'></i>
                        <span class="d-none d-sm-block">Filter</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- Attendance History -->
        <div class="accordion mt-4" id="attendanceAccordion">
            @php
                // Find the nearest schedule to current time
                $now = \Carbon\Carbon::now();
                $nearestIndex = null;
                $minDiff = null;
                foreach ($schedules as $idx => $schedule) {
                    $start = \Carbon\Carbon::parse($schedule->start_time);
                    $end = \Carbon\Carbon::parse($schedule->end_time);

                    // If today is not the same as $targetDate, use $targetDate for comparison
                    $dateToUse = \Carbon\Carbon::parse($targetDate)->toDateString();
                    $start = $start->copy()->setDateFrom(\Carbon\Carbon::parse($dateToUse));
                    $end = $end->copy()->setDateFrom(\Carbon\Carbon::parse($dateToUse));
                    // If now is between start and end, diff is 0
                    if ($now->between($start, $end)) {
                        $nearestIndex = $idx;
                        break;
                    }
                    // Otherwise, find the minimum absolute diff
                    $diff = min(abs($now->diffInSeconds($start, false)), abs($now->diffInSeconds($end, false)));
                    if (is_null($minDiff) || $diff < $minDiff) {
                        $minDiff = $diff;
                        $nearestIndex = $idx;
                    }
                }
            @endphp
            @forelse ($schedules as $index => $schedule)
                @php
                    $collapseId = 'scheduleCollapse' . $schedule->id;
                    $headingId = 'heading' . $schedule->id;
                    $isOpen = $index === $nearestIndex;
                @endphp

                <div class="accordion-item card mb-2">
                    <h2 class="accordion-header" id="{{ $headingId }}">
                        <button class="accordion-button{{ $isOpen ? '' : ' collapsed' }}" type="button"
                            data-bs-toggle="collapse" data-bs-target="#{{ $collapseId }}"
                            aria-expanded="{{ $isOpen ? 'true' : 'false' }}" aria-controls="{{ $collapseId }}">
                            {{ $schedule->subject_name }} | {{ $schedule->day }}
                            ({{ \Carbon\Carbon::parse($schedule->start_time)->format('g:i A') }} -
                            {{ \Carbon\Carbon::parse($schedule->end_time)->format('g:i A') }})
                            -
                            {{ \Carbon\Carbon::parse($targetDate)->format('F j, Y') }}
                        </button>
                    </h2>

                    <div id="{{ $collapseId }}" class="accordion-collapse collapse{{ $isOpen ? ' show' : '' }}"
                        aria-labelledby="{{ $headingId }}" data-bs-parent="#attendanceAccordion">

                        @php
                            $scheduleStart = \Carbon\Carbon::parse($schedule->start_time)
                                ->copy()
                                ->setDateFrom(\Carbon\Carbon::parse($targetDate));
                            $scheduleEnd = \Carbon\Carbon::parse($schedule->end_time)
                                ->copy()
                                ->setDateFrom(\Carbon\Carbon::parse($targetDate));
                            $now = \Carbon\Carbon::now();
                        @endphp

                        @if ($now->between($scheduleStart, $scheduleEnd))
                            <div class="d-flex align-items-center justify-content-between mb-3" style="padding: 0 20px;">
                                <button class="btn btn-primary my-2"
                                    onclick="chooseGracePeriod(
                                                    '{{ route('teacher.scanAttendance', [$class->grade_level, $class->section, $targetDate, $schedule->id]) }}?mark_absent=true',
                                                    '{{ $schedule->start_time }}',
                                                    '{{ $schedule->end_time }}')">
                                    <i class='bx bx-scan'></i> Start QR Attendance
                                    ({{ \Carbon\Carbon::parse($schedule->start_time)->format('g:i A') }} -
                                    {{ \Carbon\Carbon::parse($schedule->end_time)->format('g:i A') }})
                                </button>
                            </div>
                        @endif

                        <h2 class="text-warning text-center fw-bold">{{ $schedule->subject_name }}</h2>
                        <div class="accordion-body">
                            <form method="POST" action="{{ route('teacher.submitAttendance') }}"
                                class="attendance-form">
                                @csrf
                                <input type="hidden" name="school_year" value="{{ $selectedYear }}">
                                <input type="hidden" name="schedule_id" value="{{ $schedule->id }}">
                                <input type="hidden" name="class_id" value="{{ $class->id }}">
                                <input type="hidden" name="teacher_id" value="{{ auth()->id() }}">
                                <input type="hidden" name="date" value="{{ $targetDate }}">


                                {{-- MALE STUDENTS --}}
                                <div class="table-responsive mb-4">
                                    <table class="table table-hover table-bordered align-middle">
                                        <thead class="table-info">
                                            <tr class="text-center">
                                                <th style="width: 40px; text-align: center;">No.</th>
                                                <th style="text-align: center;">Male || Name</th>
                                                <th style="width: 160px; text-align: center;">Status</th>
                                                <th style="width: 120px; text-align: center;">Time In</th>
                                                <th style="width: 120px; text-align: center;">Time Out</th>
                                                <th style="width: 150px; text-align: center;">Date</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php $maleIndex = 1; @endphp
                                            @foreach ($students->where('gender', 'male')->sortBy(fn($s) => strtolower($s->student_lName . ' ' . $s->student_fName . ' ' . $s->student_mName)) as $student)
                                                @php
                                                    $existing =
                                                        $attendancesGrouped[$schedule->id][$student->id] ?? null;
                                                    $status = $existing->status ?? null;
                                                    $badgeClass = match ($status) {
                                                        'present' => 'bg-label-success',
                                                        'late' => 'bg-label-warning',
                                                        'absent' => 'bg-label-danger',
                                                        'excused' => 'bg-label-dark',
                                                        default => 'bg-label-secondary',
                                                    };
                                                    $badgeLabel = match ($status) {
                                                        'present', 'late', 'absent', 'excused' => ucfirst($status),
                                                        default => 'No record',
                                                    };
                                                @endphp
                                                <tr>
                                                    <td class="text-center">{{ $maleIndex++ }}</td>
                                                    <td>{{ $student->student_lName }},
                                                        {{ $student->student_fName }}
                                                        {{ $student->student_mName }}
                                                        {{ $student->student_extName }}</td>
                                                    <td>
                                                        <div
                                                            class="d-flex justify-content-between align-items-center gap-2">
                                                            <span
                                                                class="badge {{ $badgeClass }}">{{ $badgeLabel }}</span>
                                                            <select name="attendance[{{ $student->id }}][status]"
                                                                class="form-select w-auto">
                                                                <option value="present"
                                                                    {{ $status == 'present' ? 'selected' : '' }}>
                                                                    Present</option>
                                                                <option value="absent"
                                                                    {{ $status == 'absent' || is_null($status) ? 'selected' : '' }}>
                                                                    Absent</option>
                                                                <option value="late"
                                                                    {{ $status == 'late' ? 'selected' : '' }}>
                                                                    Late</option>
                                                                <option value="excused"
                                                                    {{ $status == 'excused' ? 'selected' : '' }}>
                                                                    Excused</option>
                                                            </select>
                                                        </div>
                                                    </td>
                                                    <td class="text-center">
                                                        {{ $existing?->time_in ? \Carbon\Carbon::parse($existing->time_in)->format('g:i A') : '-' }}
                                                    </td>
                                                    <td class="text-center">
                                                        {{ $existing?->time_out ? \Carbon\Carbon::parse($existing->time_out)->format('g:i A') : '-' }}
                                                    </td>

                                                    <td class="text-center">
                                                        {{ \Carbon\Carbon::parse($targetDate)->format('F j, Y') }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                {{-- FEMALE STUDENTS --}}
                                <div class="table-responsive">
                                    <table class="table table-hover table-bordered align-middle">
                                        <thead class="table-danger">
                                            <tr class="text-center">
                                                <th style="width: 40px; text-align: center;">No.</th>
                                                <th style="text-align: center;">Female || Name</th>
                                                <th style="width: 160px; text-align: center;">Status</th>
                                                <th style="width: 120px; text-align: center;">Time In</th>
                                                <th style="width: 120px; text-align: center;">Time Out</th>
                                                <th style="width: 150px; text-align: center;">Date</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php $femaleIndex = 1; @endphp
                                            @foreach ($students->where('gender', 'female')->sortBy(fn($s) => strtolower($s->student_lName . ' ' . $s->student_fName . ' ' . $s->student_mName)) as $student)
                                                @php
                                                    $existing =
                                                        $attendancesGrouped[$schedule->id][$student->id] ?? null;
                                                    $status = $existing->status ?? null;
                                                    $badgeClass = match ($status) {
                                                        'present' => 'bg-label-success',
                                                        'late' => 'bg-label-warning',
                                                        'absent' => 'bg-label-danger',
                                                        'excused' => 'bg-label-dark',
                                                        default => 'bg-label-secondary',
                                                    };
                                                    $badgeLabel = match ($status) {
                                                        'present', 'late', 'absent', 'excused' => ucfirst($status),
                                                        default => 'No record',
                                                    };
                                                @endphp
                                                <tr>
                                                    <td class="text-center">{{ $femaleIndex++ }}</td>
                                                    <td>{{ $student->student_lName }},
                                                        {{ $student->student_fName }}
                                                        {{ $student->student_mName }}</td>
                                                    <td>
                                                        <div
                                                            class="d-flex justify-content-between align-items-center gap-2">
                                                            <span
                                                                class="badge {{ $badgeClass }}">{{ $badgeLabel }}</span>
                                                            <select name="attendance[{{ $student->id }}][status]"
                                                                class="form-select w-auto">
                                                                <option value="present"
                                                                    {{ $status == 'present' ? 'selected' : '' }}>
                                                                    Present</option>
                                                                <option value="absent"
                                                                    {{ $status == 'absent' || is_null($status) ? 'selected' : '' }}>
                                                                    Absent</option>
                                                                <option value="late"
                                                                    {{ $status == 'late' ? 'selected' : '' }}>
                                                                    Late</option>
                                                                <option value="excused"
                                                                    {{ $status == 'excused' ? 'selected' : '' }}>
                                                                    Excused</option>
                                                            </select>
                                                        </div>
                                                    </td>
                                                    <td class="text-center">
                                                        {{ $existing?->time_in ? \Carbon\Carbon::parse($existing->time_in)->format('g:i A') : '-' }}
                                                    </td>
                                                    <td class="text-center">
                                                        {{ $existing?->time_out ? \Carbon\Carbon::parse($existing->time_out)->format('g:i A') : '-' }}
                                                    </td>
                                                    <td class="text-center">
                                                        {{ \Carbon\Carbon::parse($targetDate)->format('F j, Y') }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                @php
                                    // Determine if the schedule is done (current time is after end_time of this schedule for the selected date)
                                    $scheduleEnd = \Carbon\Carbon::parse($schedule->end_time)
                                        ->copy()
                                        ->setDateFrom(\Carbon\Carbon::parse($targetDate));
                                    $isScheduleDone = \Carbon\Carbon::now()->greaterThan($scheduleEnd);
                                @endphp

                                @if ($isScheduleDone)
                                    <div class="text-end mt-3">
                                        <button type="submit" class="btn btn-success">
                                            <i class="bx bx-check-circle"></i> Save Attendance
                                        </button>
                                    </div>
                                @endif
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-info text-center mb-0">
                        No schedules for this date
                        ({{ \Carbon\Carbon::parse($targetDate)->format('l, F j, Y') }})
                    </div>
                </div>
            @endforelse
        </div>
        <!-- /Attendance History -->

    </div>
    <!-- End Content wrapper -->

@endsection

@push('scripts')
    <!-- Include Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        // SweetAlert for attendance save
        document.querySelectorAll('.attendance-form').forEach(function(form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                const thisForm = this;
                Swal.fire({
                    title: 'Save Attendance?',
                    text: "Are you sure you want to save the attendance?",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, save it!',
                    cancelButtonText: 'Cancel',
                    customClass: {
                        container: 'my-swal-container'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Submit the form via AJAX to avoid page reload
                        let formData = new FormData(thisForm);
                        fetch(thisForm.action, {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector(
                                        'input[name="_token"]').value,
                                    'Accept': 'application/json'
                                },
                                body: formData
                            })
                            .then(response => {
                                if (response.ok) {
                                    Swal.fire({
                                        title: 'Success!',
                                        text: 'Attendance has been saved.',
                                        icon: 'success',
                                        timer: 2000,
                                        showConfirmButton: false,
                                        customClass: {
                                            container: 'my-swal-container'
                                        }
                                    }).then(() => {
                                        window.location.reload();
                                    });
                                } else {
                                    return response.json().then(data => {
                                        throw new Error(data.message ||
                                            'Failed to save attendance.');
                                    });
                                }
                            })
                            .catch(error => {
                                Swal.fire({
                                    title: 'Error!',
                                    text: error.message,
                                    icon: 'error',
                                    customClass: {
                                        container: 'my-swal-container'
                                    }
                                });
                            });
                    }
                });
            });
        });
    </script>

    <script>
        // logout confirmation
        function confirmLogout() {
            Swal.fire({
                title: "Are you sure?",
                text: "You want to log out?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, log out!",
                customClass: {
                    container: 'my-swal-container'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: "Logged out Successfully!",
                        icon: "success",
                        customClass: {
                            container: 'my-swal-container'
                        }
                    });
                    document.getElementById('logout-form').submit();
                }
            });
        }
    </script>

    <script>
        // Date selection form submission
        document.getElementById('dateForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const selectedDate = document.getElementById('date').value;
            const schoolYear = document.querySelector('input[name="school_year"]').value;

            const baseUrl = "{{ url('attendanceHistory/' . $class->grade_level . '/' . $class->section) }}";
            const finalUrl = selectedDate ?
                `${baseUrl}/${selectedDate}?school_year=${encodeURIComponent(schoolYear)}` :
                `${baseUrl}?school_year=${encodeURIComponent(schoolYear)}`;

            window.location.href = finalUrl;
        });
    </script>

    <script>
        // script for Grace Period Selection and Scanner Opening
        function chooseGracePeriod(scanUrlBase, startTime, endTime) {
            const options = ['none', 5, 10, 15, 20, 30, 60, 'specified'];

            Swal.fire({
                title: 'Select Grace Period for Marking as Late',
                input: 'select',
                inputOptions: Object.fromEntries(
                    options.map(min => [
                        min,
                        min === 'none' ? `None (marked as present until ${formatTime(endTime)})` :
                        min === 60 ?
                        '1 hour' :
                        min === 'specified' ?
                        'Other (Specify)' :
                        min + ' mins'
                    ])
                ),
                inputValue: 'none',
                showCancelButton: true,
                confirmButtonText: 'Next',
                cancelButtonText: 'Cancel',
                icon: 'question',
                customClass: {
                    container: 'my-swal-container'
                }
            }).then(result => {
                if (result.isConfirmed) {
                    let grace = result.value || 0;

                    // ✅ Handle "None" option
                    if (grace === 'none') {
                        grace = -1; // -1 means: present until end_time
                        openScanner(scanUrlBase, startTime, endTime, grace);
                        return;
                    }

                    // ✅ Handle other options
                    if (grace === 'specified') {
                        Swal.fire({
                            title: 'Specify Grace Period (minutes)',
                            input: 'number',
                            inputAttributes: {
                                min: 1,
                                max: 180,
                                step: 1
                            },
                            inputPlaceholder: 'Enter minutes (e.g. 7)',
                            showCancelButton: true,
                            confirmButtonText: 'Start Scanning',
                            cancelButtonText: 'Cancel',
                            icon: 'question',
                            customClass: {
                                container: 'my-swal-container'
                            },
                            preConfirm: (value) => {
                                if (!value || value < 1) {
                                    Swal.showValidationMessage(
                                        'Please enter a valid number of minutes');
                                    return false;
                                }
                                return value;
                            }
                        }).then(specifiedResult => {
                            if (specifiedResult.isConfirmed) {
                                grace = specifiedResult.value;
                                openScanner(scanUrlBase, startTime, endTime, grace);
                            } else if (specifiedResult.dismiss === Swal.DismissReason.cancel) {
                                Swal.fire({
                                    title: 'Cancelled',
                                    text: 'QR scanning not started.',
                                    icon: 'info',
                                    customClass: {
                                        container: 'my-swal-container'
                                    }
                                });
                            }
                        });
                    } else {
                        openScanner(scanUrlBase, startTime, endTime, grace);
                    }
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    Swal.fire({
                        title: 'Cancelled',
                        text: 'QR scanning not started.',
                        icon: 'info',
                        customClass: {
                            container: 'my-swal-container'
                        }
                    });
                }
            });
        }

        function openScanner(scanUrlBase, startTime, endTime, grace) {
            const scanUrl = scanUrlBase + (scanUrlBase.includes('?') ? '&' : '?') + `grace=${grace}`;
            Swal.fire({
                toast: true,
                icon: 'success',
                title: `Opening scanner for ${formatTime(startTime)} - ${formatTime(endTime)} (Grace: ${grace === -1 ? 'None' : grace + ' min'})`,
                position: 'top-end',
                showConfirmButton: false,
                timer: 2000,
                customClass: {
                    container: 'my-swal-container'
                }
            });
            setTimeout(() => {
                window.location.href = scanUrl;
            }, 300);
        }

        function formatTime(timeStr) {
            const [hour, minute] = timeStr.split(':');
            const date = new Date();
            date.setHours(hour, minute);
            return date.toLocaleTimeString([], {
                hour: '2-digit',
                minute: '2-digit'
            });
        }
    </script>
@endpush

@push('styles')
    <!-- Main CSS File -->
    <link href="{{ asset('assets/css/main.css') }}" rel="stylesheet" />
    <link href="{{ asset('assetsDashboard/vendor/css/core.css') }}" rel="stylesheet" />
    <link href="{{ asset('assetsDashboard/vendor/css/theme-default.css') }}" rel="stylesheet" />

    <!-- Vendor CSS Files -->
    <link href="{{ asset('assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet" />

    <style>
        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .card-hover {
            transition: all 0.3s ease;
        }
    </style>
@endpush
