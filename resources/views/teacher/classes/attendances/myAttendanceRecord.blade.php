@extends('./layouts.main')

@section('title', 'Teacher | Attendance Records')

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

            {{-- SMS Logs sidebar --}}
            <li class="menu-item">
                <a href="" class="menu-link bg-dark text-light">
                    <i class="bx bx-message-check me-3 text-light"></i>
                    <div class="text-light">SMS Logs</div>
                </a>
            </li>

            {{-- Account Settings sidebar --}}
            <li class="menu-item">
                <a href="" class="menu-link bg-dark text-light">
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
                    Attendance Records
                </h4>
            </div>
        </div>

        <div class="d-flex justify-content-between mb-3 align-items-center">
            <a href="{{ route('teacher.myClass', ['grade_level' => $class->grade_level, 'section' => $class->section, 'school_year' => $selectedYear]) }}"
                class="btn btn-danger d-flex align-items-center"><i class='bx bx-chevrons-left'></i>
                <span class="d-none d-sm-block">Back</span>
            </a>

            <a href="{{ route('export.sf2', [
                'grade_level' => $class->grade_level,
                'section' => $class->section,
                'school_year' => $selectedYear,
                'month' => $monthParam,
            ]) }}"
                class="btn btn-success d-flex align-items-center">
                <i class='bx bx-printer me-2'></i><span class="d-none d-sm-block">Export</span>
            </a>
        </div>

        <div class="alert alert-primary alert-dismissible fade show fw-bold mb-4 text-center" role="alert"
            id="attendance-alert">
            Showing Attendance Record for
            {{ \Carbon\Carbon::createFromFormat('Y-m', $monthParam)->format('F, Y') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <script>
            setTimeout(function() {
                var alertElem = document.getElementById('attendance-alert');
                if (alertElem) {
                    var bsAlert = bootstrap.Alert.getOrCreateInstance(alertElem);
                    bsAlert.close();
                }
            }, 5000);
        </script>

        <!-- Attendance Record Card -->
        <div class="card p-4 shadow-sm">
            <div class="d-flex justify-content-between mb-3 align-items-center">
                <h3 class="fw-bold mb-0 text-primary">{{ $class->formatted_grade_level }} -
                    {{ $class->section }}</h3>


            </div>

            <div class="text-center mb-4">
                <h5 class="fw-bold text-info">Daily Attendance Reports of Learners for School Year
                    {{ $selectedYear }}</h5>
                @if (empty($scheduleDays) || count($scheduleDays) === 0)
                    <div class="alert alert-warning alert-dismissible fade show fw-bold mb-0" role="alert">
                        You have no schedules yet for this class
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
            </div>

            <div class="d-flex mb-3">
                <div class="ms-auto d-flex align-items-end">
                    <!-- Month Picker -->
                    <form method="GET"
                        action="{{ route('teacher.myAttendanceRecord', ['grade_level' => $class->grade_level, 'section' => $class->section]) }}"
                        class="d-flex align-items-end gap-2">

                        <input type="hidden" name="school_year" value="{{ $selectedYear }}">

                        <label for="month" class="mb-0 d-none d-sm-block">Date:</label>

                        <input type="month" name="month" id="month" class="form-control"
                            value="{{ $monthParam }}"
                            min="{{ \Carbon\Carbon::parse($selectedYearObj->start_date)->format('Y-m') }}"
                            max="{{ \Carbon\Carbon::parse($selectedYearObj->end_date)->format('Y-m') }}">

                        <button type="submit" class="btn btn-primary d-flex align-items-center gap-1">
                            <i class='bx bx-filter'></i><span class="d-none d-sm-block">Filter</span>
                        </button>
                    </form>
                </div>
            </div>

            <!-- Attendance Table -->
            <div class="table-responsive">
                <table class="table table-hover table-bordered table-sm text-center align-middle mb-0">
                    <thead>
                        <tr>
                            <th rowspan="2">NO.</th>
                            <th rowspan="2" style="min-width: 140px;">NAME</th>

                            @php
                                $fridayIndexes = [];
                                $todayIndex = null;
                            @endphp

                            @foreach ($calendarDates as $i => $date)
                                @php
                                    $carbonDate = \Carbon\Carbon::parse($date);
                                    $day = $carbonDate->format('D'); // Mon, Tue, etc.
                                    $isToday = $carbonDate->isToday();
                                    $isScheduled = in_array($day, $scheduleDays);
                                    $isFriday = $day === 'Fri';

                                    if ($isFriday) {
                                        $fridayIndexes[] = $i;
                                    }
                                    if ($isToday) {
                                        $todayIndex = $i;
                                    }

                                    $classes = [];
                                    if ($isToday) {
                                        $classes[] = 'bg-info text-white';
                                    } elseif ($isScheduled) {
                                        $classes[] = 'bg-warning text-dark';
                                    }
                                    if ($isFriday) {
                                        $classes[] = 'week-end';
                                    }
                                    if ($isToday) {
                                        $classes[] = 'today-column';
                                    }
                                @endphp
                                <th class="{{ implode(' ', $classes) }}">
                                    @if ($isScheduled)
                                        <a href="{{ route('teacher.attendanceHistory', [$class->grade_level, $class->section]) }}?school_year={{ $selectedYear }}&date={{ $carbonDate->format('Y-m-d') }}"
                                            style="text-decoration: none; color: inherit;">
                                            {{ $carbonDate->format('M j') }}<br>
                                            <small>{{ $day }}</small>
                                        </a>
                                    @else
                                        {{ $carbonDate->format('M j') }}<br>
                                        <small>{{ $day }}</small>
                                    @endif
                                </th>
                            @endforeach

                            <th class="bg-danger text-white no-hover-bg" rowspan="2" style="min-width: 100px;">Monthly
                                ABSENT</th>
                            <th class="bg-success text-white no-hover-bg" rowspan="2" style="min-width: 100px;">
                                Monthly PRESENT</th>
                            <th rowspan="2" style="min-width: 140px;">REMARKS</th>
                        </tr>
                    </thead>

                    <tbody>
                        @php
                            $genderGroups = [
                                'Male' => 'table-info bg-opacity-25',
                                'Female' => 'table-danger bg-opacity-25',
                            ];

                            $genderCounts = [
                                'Male' => collect($students)
                                    ->filter(fn($s) => strtolower(trim($s->student_sex)) === 'male')
                                    ->count(),
                                'Female' => collect($students)
                                    ->filter(fn($s) => strtolower(trim($s->student_sex)) === 'female')
                                    ->count(),
                            ];

                            $index = 1;
                        @endphp

                        @foreach ($genderGroups as $gender => $rowClass)
                            @php $genderIndex = 1; @endphp
                            @foreach (collect($students)->filter(function ($student) use ($gender) {
                return strtolower(trim($student->student_sex)) === strtolower($gender);
            })->sortBy(function ($student) {
                return $student->student_lName . $student->student_fName . $student->student_mName;
            }) as $student)
                                <tr>
                                    <td>{{ $genderIndex++ }}</td>
                                    <td class="text-start">
                                        <span class="fw-bold text-primary">{{ $student->student_lName }}</span>,
                                        {{ $student->student_fName }}
                                        {{ $student->student_extName }}
                                        @if (!empty($student->student_mName))
                                            {{ strtoupper(substr($student->student_mName, 0, 1)) }}.
                                        @endif
                                    </td>

                                    @foreach ($calendarDates as $i => $date)
                                        @php
                                            $isWeekEnd = in_array($i, $fridayIndexes);
                                            $isTodayCol = $i === $todayIndex;

                                            $cellClass = '';
                                            if ($isWeekEnd) {
                                                $cellClass .= ' week-end';
                                            }
                                            if ($isTodayCol) {
                                                $cellClass .= ' today-column bg-info text-white';
                                            }

                                            $symbols = $attendanceData[$student->id]['by_date'][$date] ?? [];

                                            $dayAbbrev = \Carbon\Carbon::parse($date)->format('D');
                                            $daySchedule = $schedules[$dayAbbrev] ?? null;

                                            $formattedSchedule = $daySchedule
                                                ? '(' .
                                                    \Carbon\Carbon::parse($daySchedule->start_time)->format('g:i A') .
                                                    ' - ' .
                                                    \Carbon\Carbon::parse($daySchedule->end_time)->format('g:i A') .
                                                    ')'
                                                : '';

                                            $statusTitles = [
                                                '✓' => 'Present ' . $formattedSchedule,
                                                'X' => 'Absent ' . $formattedSchedule,
                                                'L' => 'Late ' . $formattedSchedule,
                                                'E' => 'Excused ' . $formattedSchedule,
                                            ];
                                        @endphp

                                        <td class="{{ $cellClass }}">
                                            @if (!empty($symbols))
                                                @foreach ($symbols as $symbolEntry)
                                                    @php
                                                        $symbol = $symbolEntry['status'];
                                                        $start = \Carbon\Carbon::parse(
                                                            $symbolEntry['start_time'],
                                                        )->format('g:i A');
                                                        $end = \Carbon\Carbon::parse($symbolEntry['end_time'])->format(
                                                            'g:i A',
                                                        );
                                                        $subject = $symbolEntry['subject_name'] ?? 'N/A';

                                                        $title = match ($symbol) {
                                                            '✓' => "PRESENT | $subject | ($start - $end)",
                                                            'X' => "ABSENT | $subject | ($start - $end)",
                                                            'L' => "LATE | $subject | ($start - $end)",
                                                            'E' => "EXCUSED | $subject | ($start - $end)",
                                                            default => ucfirst($symbol),
                                                        };

                                                        // Add a class for white border if today
                                                        $symbolBorderClass = $isTodayCol
                                                            ? 'attendance-symbol-border'
                                                            : '';
                                                        $symbolColorClass = $isTodayCol
                                                            ? 'text-white'
                                                            : match ($symbol) {
                                                                '✓' => 'text-success',
                                                                'X' => 'text-danger',
                                                                'L' => 'text-warning',
                                                                'E' => 'text-primary',
                                                                default => '',
                                                            };
                                                    @endphp

                                                    <span class="me-1" data-bs-toggle="tooltip"
                                                        title="{{ $title }}">
                                                        <span
                                                            class="fw-bold {{ $symbolBorderClass }} {{ $symbolColorClass }}">{{ $symbol }}</span>
                                                    </span>
                                                @endforeach
                                            @else
                                                <span data-bs-toggle="tooltip" title="No Record">-</span>
                                            @endif
                                        </td>
                                    @endforeach

                                    <td class="bg-danger text-white no-hover-bg">
                                        {{ $attendanceData[$student->id]['absent'] }}</td>
                                    <td class="bg-success text-white no-hover-bg">
                                        {{ $attendanceData[$student->id]['present'] }}</td>
                                    <td></td>
                                </tr>
                            @endforeach

                            <!-- Gender Totals -->
                            <tr class="fw-bold {{ $rowClass }}">
                                <td>{{ $genderCounts[$gender] }}</td>
                                <td>{{ $gender }} | Total Per Day</td>
                                @foreach ($gender === 'Male' ? $maleTotals : $femaleTotals as $i => $total)
                                    @php
                                        $cellClass = '';
                                        if (in_array($i, $fridayIndexes)) {
                                            $cellClass .= ' week-end';
                                        }
                                        if ($i === $todayIndex) {
                                            $cellClass .= ' today-column bg-info text-white';
                                        }
                                    @endphp
                                    <td class="{{ $cellClass }}">{{ $total }}</td>
                                @endforeach
                                <td class="bg-danger text-white fs-5 no-hover-bg">
                                    {{-- This aligns with Monthly ABSENT --}}
                                    {{ $gender === 'Male' ? $maleTotalAbsent : $femaleTotalAbsent }}</td>
                                <td class="bg-success text-white fs-5 no-hover-bg">
                                    {{-- This aligns with Monthly PRESENT --}}
                                    {{ $gender === 'Male' ? $maleTotalPresent : $femaleTotalPresent }}</td>
                                <td></td>
                            </tr>
                        @endforeach

                        <!-- Combined Total Per Day -->
                        <tr class="fw-bold table-success bg-opacity-25">
                            <td>{{ $genderCounts['Male'] + $genderCounts['Female'] }}</td>
                            <td>Combined | Total per Day</td>
                            @foreach ($calendarDates as $i => $date)
                                @php
                                    $cellClass = '';
                                    if (in_array($i, $fridayIndexes)) {
                                        $cellClass .= ' week-end';
                                    }
                                    if ($i === $todayIndex) {
                                        $cellClass .= ' today-column bg-info text-white';
                                    }
                                @endphp
                                <td class="{{ $cellClass }}">{{ $combinedTotals[$date] ?? 0 }}</td>
                            @endforeach
                            <td class="bg-danger text-white fs-5 no-hover-bg">{{ $totalAbsent }}</td>
                            <td class="bg-success text-white fs-5 no-hover-bg">{{ $totalPresent }}</td>
                            <td></td>
                        </tr>
                    </tbody>

                </table>
            </div>
            <!-- / Attendance Table -->
        </div>
        <!-- / Attendance Record Card -->

    </div>
    <!-- / Content wrapper -->

@endsection

@push('scripts')
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
        // Tooltip initialization
        document.addEventListener('DOMContentLoaded', function() {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });
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

        .week-end {
            border-right: 3px solid #000 !important;
        }

        .today-column {
            background-color: #0dcaf0 !important;
            /* Bootstrap info */
            color: white !important;
        }

        /* NEW: Universal override */
        tr:hover .no-hover-bg.bg-danger {
            background-color: #dc3545 !important;
            color: white !important;
        }

        tr:hover .no-hover-bg.bg-success {
            background-color: #198754 !important;
            color: white !important;
        }
    </style>
@endpush
