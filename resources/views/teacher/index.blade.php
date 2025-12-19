@extends('./layouts.main')

@section('title', 'Teacher | Management')

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

            <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large d-block d-xl-none">
                <i class="bx bx-chevron-left bx-sm align-middle"></i>
            </a>
        </div>

        <ul class="menu-inner py-1 bg-dark">

            <!-- Dashboard sidebar-->
            <li class="menu-item active">
                <a href="{{ '/home ' }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-home-circle"></i>
                    <div class="text-warning">Dashboard</div>
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
            <li class="menu-item">
                <a href="javascript:void(0)" class="menu-link menu-toggle bg-dark text-light">
                    <i class="menu-icon tf-icons bx bx-notepad text-light"></i>
                    <div class="text-light">Classes</div>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item">
                        <a href="{{ route('teacher.myClasses') }}" class="menu-link bg-dark text-light">
                            <div class="text-light">My Classes</div>
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
    <div class="content-wrapper">
        <!-- Content -->
        @php
            use Illuminate\Support\Facades\Auth;
            use App\Models\Student;
            use App\Models\Classes;
            use App\Models\Attendance;
            use App\Models\User;
            use App\Models\SchoolYear;
            use Carbon\Carbon;
            use App\Models\ClassStudent;

            $teacher = Auth::user();

            // Get current school year
            $currentSchoolYear = SchoolYear::where('start_date', '<=', now())->where('end_date', '>=', now())->first();

            $studentCount = 0;
            $regularCount = 0;
            $transfereeCount = 0;
            $returneeCount = 0;
            $newlyEnrolledStudents = 0;
            $attendanceToday = 0;
            $schedule = null;

            // Advisory and subject class counts
            $advisoryClassCount = 0;
            $subjectClassCount = 0;

            $advisoryClass = $teacher
                ->advisoryClasses()
                ->wherePivot('school_year_id', $currentSchoolYear?->id)
                ->first();

            $subjectClass = $teacher->subjectClasses()->wherePivot('school_year_id', $currentSchoolYear?->id)->first();

            $class = $advisoryClass ?? $subjectClass;

            if ($class && $currentSchoolYear) {
                $studentsQuery = $class->students()->wherePivot('school_year_id', $currentSchoolYear->id);

                $studentCount = $studentsQuery->count();

                // Get counts by enrollment type
                $enrollmentTypeCounts = $studentsQuery
                    ->selectRaw('enrollment_type, COUNT(*) as count')
                    ->groupBy('enrollment_type')
                    ->pluck('count', 'enrollment_type')
                    ->toArray();

                $regularCount = $enrollmentTypeCounts['regular'] ?? 0;
                $transfereeCount = $enrollmentTypeCounts['transferee'] ?? 0;
                $returneeCount = $enrollmentTypeCounts['returnee'] ?? 0;

                $newlyEnrolledStudents = $studentsQuery->where('students.created_at', '>=', now()->subWeek())->count();

                $today = Carbon::now()->format('Y-m-d');
                $now = Carbon::now();
                $todayDayName = $now->format('l');

                $schedule = $class
                    ->schedules()
                    ->where('day', $todayDayName)
                    ->where('school_year_id', $currentSchoolYear->id)
                    ->orderBy('start_time')
                    ->get()
                    ->filter(function ($sched) use ($now) {
                        return Carbon::parse($sched->end_time)->gt($now);
                    })
                    ->first();

                if ($schedule) {
                    $presentCount = $schedule
                        ->attendances()
                        ->whereDate('date', $today)
                        ->whereIn('status', ['present', 'late'])
                        ->count();

                    $attendanceToday = $studentCount > 0 ? min(100, round(($presentCount / $studentCount) * 100)) : 0;
                }
            }

            // Get advisory and subject class counts
            if ($currentSchoolYear) {
                $advisoryClassCount = $teacher
                    ->advisoryClasses()
                    ->wherePivot('school_year_id', $currentSchoolYear->id)
                    ->count();

                $subjectClassCount = $teacher
                    ->subjectClasses()
                    ->wherePivot('school_year_id', $currentSchoolYear->id)
                    ->count();
            }

            $teacherClassesCount = $advisoryClassCount + $subjectClassCount;

            // Get all school years and filter to include only from first to current
            $allSchoolYears = SchoolYear::orderBy('start_date', 'asc')->get();

            if ($allSchoolYears->isNotEmpty()) {
                $firstSchoolYear = $allSchoolYears->first();

                if ($currentSchoolYear) {
                    // Filter school years from first to current (inclusive)
                    $schoolYears = $allSchoolYears
                        ->filter(function ($schoolYear) use ($firstSchoolYear, $currentSchoolYear) {
                            return $schoolYear->start_date >= $firstSchoolYear->start_date &&
                                $schoolYear->start_date <= $currentSchoolYear->start_date;
                        })
                        ->sortByDesc('start_date')
                        ->values();
                } else {
                    // If no current school year, just use all available school years
                    $schoolYears = $allSchoolYears->sortByDesc('start_date')->values();
                }
            } else {
                $schoolYears = collect();
            }

            $schoolYearId = $currentSchoolYear ? $currentSchoolYear->id : null;
            $schoolYearText = $currentSchoolYear ? $currentSchoolYear->school_year : 'N/A';

            // Gender Statistics for Current School Year (Teacher's classes only)
            $genderStats = [];
            if ($schoolYearId) {
                    // Get teacher's class IDs
                $teacherClassIds = $teacher
                    ->classes()
                    ->wherePivot('school_year_id', $schoolYearId)
                    ->pluck('classes.id')
                    ->toArray();

                $genderData = ClassStudent::where('school_year_id', $schoolYearId)
                    ->whereIn('class_id', $teacherClassIds)
                    ->whereIn('enrollment_status', ['enrolled', 'archived'])
                    ->join('students', 'class_student.student_id', '=', 'students.id')
                    ->selectRaw(
                        '
            COUNT(DISTINCT students.id) as total,
            SUM(CASE WHEN LOWER(students.student_sex) IN ("f", "female") THEN 1 ELSE 0 END) as female_count,
            SUM(CASE WHEN LOWER(students.student_sex) IN ("m", "male") THEN 1 ELSE 0 END) as male_count
                ',
                    )
                    ->first();

                $genderStats['total'] = $genderData->total ?? 0;
                $genderStats['female_count'] = $genderData->female_count ?? 0;
                $genderStats['male_count'] = $genderData->male_count ?? 0;
                $genderStats['female_percentage'] =
                    $genderStats['total'] > 0
                        ? round(($genderStats['female_count'] / $genderStats['total']) * 100, 1)
                        : 0;
                $genderStats['male_percentage'] =
                    $genderStats['total'] > 0
                        ? round(($genderStats['male_count'] / $genderStats['total']) * 100, 1)
                        : 0;
            }

            // Enrollment by Grade Level for Current School Year (Teacher's classes only)
                $enrollmentByGrade = [];
                if ($schoolYearId) {
                    // Get teacher's class IDs
                $teacherClassIds = $teacher
                    ->classes()
                    ->wherePivot('school_year_id', $schoolYearId)
                    ->pluck('classes.id')
                    ->toArray();

                $enrollmentData = ClassStudent::where('class_student.school_year_id', $schoolYearId)
                    ->whereIn('class_student.class_id', $teacherClassIds)
                    ->whereIn('class_student.enrollment_status', ['enrolled', 'archived'])
                    ->join('classes', 'class_student.class_id', '=', 'classes.id')
                    ->selectRaw('classes.grade_level, COUNT(DISTINCT class_student.student_id) as student_count')
                    ->groupBy('classes.grade_level')
                    ->get();

                // Format the data for chart
                $gradeLevels = ['kindergarten', 'grade1', 'grade2', 'grade3', 'grade4', 'grade5', 'grade6'];
                $enrollmentByGrade = [];

                foreach ($gradeLevels as $grade) {
                    $found = $enrollmentData->firstWhere('grade_level', $grade);
                    $enrollmentByGrade[] = $found ? $found->student_count : 0;
                }
            } else {
                $enrollmentByGrade = [0, 0, 0, 0, 0, 0, 0];
            }

            // Total enrollees for teacher's classes
                    $totalEnrollees = $genderStats['total'] ?? 0;
        @endphp

        <div class="container-xxl container-p-y">
            <!-- Top Stats Cards -->
            <div class="row mb-3 g-3">
                <div class="col-12 col-md-6 col-lg-3">

                    <!-- STUDENTS -->
                    <div class="card card-hover">
                        <a href="{{ route('teacher.my.students') }}" class="card-body">
                            <div class="d-flex align-items-center mb-5">
                                <div class="me-3">
                                    <img src="{{ asset('assetsDashboard/img/icons/dashIcon/studentIcon.png') }}"
                                        width="50" height="50">
                                </div>
                                <div class="d-flex flex-column align-items-center ms-auto">
                                    <h5 class="fw-semibold text-primary mb-2 d-none d-sm-block">Students</h5>
                                    <h6 class="fw-semibold text-primary mb-2 d-sm-block d-sm-none">Students</h6>
                                    <h1 class="fw-semibold mb-0">{{ $studentCount }}</h1>
                                </div>
                            </div>

                            <div class="d-flex flex-column">
                                <div class="d-flex justify-content-between">
                                    <span class="d-flex align-items-center text-primary">
                                        <i class="bx bx-user-check me-1"></i> Regular:
                                    </span>
                                    <span class="text-primary">{{ $regularCount }}</span>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span class="d-flex align-items-center text-info">
                                        <i class="bx bx-transfer me-1"></i> Transferee:
                                    </span>
                                    <span class="text-info">{{ $transfereeCount }}</span>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span class="d-flex align-items-center text-warning">
                                        <i class="bx bx-undo me-1"></i> Returnee:
                                    </span>
                                    <span class="text-warning">{{ $returneeCount }}</span>
                                </div>
                            </div>
                        </a>
                    </div>

                    <!-- CLASSES -->
                    <div class="card mt-3 card-hover">
                        <a href="{{ route('teacher.myClasses') }}" class="card-body">
                            <div class="d-flex align-items-center mb-5">
                                <div class="me-3">
                                    <img src="{{ asset('assetsDashboard/img/icons/dashIcon/classroomIcon.png') }}"
                                        width="50" height="50">
                                </div>
                                <div class="d-flex flex-column align-items-center ms-auto">
                                    <h6 class="fw-semibold text-primary mb-2 d-sm-none d-block">Classes</h6>
                                    <h5 class="fw-semibold text-primary mb-2 d-none d-sm-block">Classes</h5>
                                    <h1 class="fw-semibold mb-0">{{ $teacherClassesCount }}</h1>
                                </div>
                            </div>
                            <div class="d-flex flex-column">
                                <div class="d-flex justify-content-between">
                                    <span class="d-flex align-items-center text-warning">
                                        <i class="bx bx-check-square me-1"></i> Advisory Class:
                                    </span>
                                    <span class="text-warning">{{ $advisoryClassCount }}</span>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span class="d-flex align-items-center text-secondary">
                                        <i class='bx bx-minus-circle me-1'></i>Subject-Based Class:
                                    </span>
                                    <span class="text-secondary">{{ $subjectClassCount }}</span>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>

                <!-- Replace the entire attendance card section (around line 270-400) with this: -->

                @if ($class)
                    <div class="col-12 col-xxl-6 mb-6">
                        <div class="card h-100">
                            <div class="row row-bordered g-0 h-100">

                                <!-- LEFT SIDE : TODAY'S SCHEDULES -->
                                <div class="col-md-6">
                                    <div class="card-header d-flex align-items-center justify-content-between">
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="avatar flex-shrink-0">
                                                <img src="{{ asset('assetsDashboard/img/icons/dashIcon/attendanceIcon.png') }}"
                                                    alt="Attendance" class="rounded" />
                                            </div>
                                            <div>
                                                <h5 class="card-title mb-0 text-primary">Today's Schedules</h5>
                                                <small class="text-muted">{{ now()->format('F j, Y') }}</small>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card-body">
                                        @php
                                            $today = now()->format('Y-m-d');
                                            $todayDayName = now()->format('l');

                                            $todaySchedules = $class
                                                ->schedules()
                                                ->where('day', ucfirst($todayDayName))
                                                ->where('school_year_id', $currentSchoolYear?->id)
                                                ->orderBy('start_time')
                                                ->get();

                                            $now = \Carbon\Carbon::now();
                                            $nearestIndex = null;
                                            $minDiff = null;

                                            foreach ($todaySchedules as $idx => $schedule) {
                                                $start = \Carbon\Carbon::parse($schedule->start_time)->setDateFrom(
                                                    now(),
                                                );
                                                $end = \Carbon\Carbon::parse($schedule->end_time)->setDateFrom(now());

                                                if ($now->between($start, $end)) {
                                                    $nearestIndex = $idx;
                                                    break;
                                                }

                                                $diff = min(
                                                    abs($now->diffInSeconds($start, false)),
                                                    abs($now->diffInSeconds($end, false)),
                                                );

                                                if (is_null($minDiff) || $diff < $minDiff) {
                                                    $minDiff = $diff;
                                                    $nearestIndex = $idx;
                                                }
                                            }
                                        @endphp

                                        <div class="accordion" id="todaySchedulesAccordion">

                                            @forelse ($todaySchedules as $index => $schedule)
                                                @php
                                                    $collapseId = 'todayScheduleCollapse' . $schedule->id;
                                                    $headingId = 'todayHeading' . $schedule->id;
                                                    $isOpen = $index === $nearestIndex;

                                                    $presentCount = $schedule
                                                        ->attendances()
                                                        ->whereDate('date', $today)
                                                        ->whereIn('status', ['present', 'late'])
                                                        ->count();

                                                    $totalStudents = $studentCount;
                                                    $attendancePercentage =
                                                        $totalStudents > 0
                                                            ? round(($presentCount / $totalStudents) * 100)
                                                            : 0;

                                                    // Check if current time is within the schedule
                                                    $scheduleStart = \Carbon\Carbon::parse(
                                                        $schedule->start_time,
                                                    )->setDateFrom(now());
                                                    $scheduleEnd = \Carbon\Carbon::parse(
                                                        $schedule->end_time,
                                                    )->setDateFrom(now());
                                                    $now = \Carbon\Carbon::now();
                                                    $isWithinScheduleTime = $now->between($scheduleStart, $scheduleEnd);
                                                @endphp

                                                <div class="accordion-item border mb-2">
                                                    <h2 class="accordion-header" id="{{ $headingId }}">
                                                        <button
                                                            class="accordion-button {{ $isOpen ? '' : 'collapsed' }} py-2"
                                                            type="button" data-bs-toggle="collapse"
                                                            data-bs-target="#{{ $collapseId }}"
                                                            aria-expanded="{{ $isOpen ? 'true' : 'false' }}">

                                                            <div class="d-flex justify-content-between w-100">
                                                                <div>
                                                                    <small
                                                                        class="text-muted d-block">{{ $schedule->subject_name }}</small>
                                                                    <!-- Change text color based on schedule time -->
                                                                    <small
                                                                        class="{{ $isWithinScheduleTime ? 'text-danger fw-bold' : 'text-primary' }}">
                                                                        {{ \Carbon\Carbon::parse($schedule->start_time)->format('g:i A') }}
                                                                        -
                                                                        {{ \Carbon\Carbon::parse($schedule->end_time)->format('g:i A') }}
                                                                    </small>
                                                                </div>
                                                                <div class="text-end">
                                                                    <span
                                                                        class="badge bg-label-success">{{ $presentCount }}
                                                                        Present</span>
                                                                    <!-- Remove the attendance percentage line -->
                                                                    <!-- <small class="d-block text-muted">{{ $attendancePercentage }}%</small> -->
                                                                </div>
                                                            </div>
                                                        </button>
                                                    </h2>

                                                    <div id="{{ $collapseId }}"
                                                        class="accordion-collapse collapse {{ $isOpen ? 'show' : '' }}">
                                                        <div class="accordion-body p-2">
                                                            <div class="d-flex flex-column gap-2">
                                                                <!-- QR Scanner Button (only show if within schedule time) -->
                                                                @if ($isWithinScheduleTime)
                                                                    <button
                                                                        class="btn btn-warning btn-sm d-flex align-items-center justify-content-center gap-2"
                                                                        onclick="chooseGracePeriod(
                                    '{{ route('teacher.scanAttendance', [$class->grade_level, $class->section, $today, $schedule->id]) }}?mark_absent=true',
                                    '{{ $schedule->start_time }}',
                                    '{{ $schedule->end_time }}'
                                )">
                                                                        <i class='bx bx-scan'></i> Start QR Attendance
                                                                    </button>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @empty
                                                <div class="text-center py-4">
                                                    <i class="bx bx-calendar-x text-muted fs-4"></i>
                                                    <p class="text-muted mb-0 mt-2">No schedules for today</p>
                                                </div>
                                            @endforelse
                                        </div>

                                        <!-- ADDED: Attendance Details Button at bottom of card -->
                                        @if ($todaySchedules->count() > 0)
                                            <div class="mt-3 pt-3 border-top">
                                                <a href="{{ route('teacher.myAttendanceRecord', ['grade_level' => $class->grade_level, 'section' => $class->section]) }}?school_year={{ $selectedYear ?? ($currentSchoolYear->school_year ?? '') }}"
                                                    class="btn btn-outline-primary btn-sm d-flex align-items-center justify-content-center gap-2 w-100">
                                                    <i class='bx bx-history'></i> View Attendance History
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <!-- RIGHT SIDE : TODAY'S OVERVIEW -->
                                <div class="col-md-6">
                                    <!-- Attendance Card -->
                                    <div class="card-header d-flex justify-content-between align-items-center mb-0">
                                        <div class="justify-content-center align-items-center card-title mb-0">
                                            <h5 class="text-primary m-0 me-2">
                                                Attendance Overview for Today
                                            </h5>
                                        </div>
                                    </div>

                                    <div class="card-body">
                                        <div class="d-flex justify-content-center align-items-center">
                                            <div id="studentTypeChart"></div>
                                        </div>
                                        <ul class="p-0 m-0">
                                            <li class="d-flex mb-3">
                                                <div class="avatar flex-shrink-0 me-3">
                                                    <span class="avatar-initial rounded bg-label-success">
                                                        <i class="bx bx-user"></i>
                                                    </span>
                                                </div>
                                                <div class="d-flex w-100 justify-content-between">
                                                    <div>
                                                        <h6 class="mb-0">Present</h6>
                                                        <small class="text-muted" id="regularCountText">
                                                            {{ $studentTypeStats['regular_count'] ?? 0 }} Students
                                                        </small>
                                                    </div>
                                                    <div class="user-progress">
                                                        <h6 class="fw-semibold" id="regularPercentageText">
                                                            {{ $studentTypeStats['regular_percentage'] ?? 0 }}%
                                                        </h6>
                                                    </div>
                                                </div>
                                            </li>
                                            <li class="d-flex mb-3">
                                                <div class="avatar flex-shrink-0 me-3">
                                                    <span class="avatar-initial rounded bg-label-warning">
                                                        <i class="bx bx-undo"></i>
                                                    </span>
                                                </div>
                                                <div class="d-flex w-100 justify-content-between">
                                                    <div>
                                                        <h6 class="mb-0">Late</h6>
                                                        <small class="text-muted" id="returneeCountText">
                                                            {{ $studentTypeStats['returnee_count'] ?? 0 }} Students
                                                        </small>
                                                    </div>
                                                    <div class="user-progress">
                                                        <h6 class="fw-semibold" id="returneePercentageText">
                                                            {{ $studentTypeStats['returnee_percentage'] ?? 0 }}%
                                                        </h6>
                                                    </div>
                                                </div>
                                            </li>
                                            <li class="d-flex mb-3">
                                                <div class="avatar flex-shrink-0 me-3">
                                                    <span class="avatar-initial rounded bg-label-danger">
                                                        <i class="bx bx-transfer"></i>
                                                    </span>
                                                </div>
                                                <div class="d-flex w-100 justify-content-between">
                                                    <div>
                                                        <h6 class="mb-0">Absent</h6>
                                                        <small class="text-muted" id="absentCountText">
                                                            {{ $studentTypeStats['absent_count'] ?? 0 }} Students
                                                        </small>
                                                    </div>
                                                    <div class="user-progress">
                                                        <h6 class="fw-semibold" id="transfereePercentageText">
                                                            {{ $studentTypeStats['transferee_percentage'] ?? 0 }}%
                                                        </h6>
                                                    </div>
                                                </div>
                                            </li>
                                            <li class="d-flex">
                                                <div class="avatar flex-shrink-0 me-3">
                                                    <span class="avatar-initial rounded bg-label-secondary">
                                                        <i class="bx bx-transfer"></i>
                                                    </span>
                                                </div>
                                                <div class="d-flex w-100 justify-content-between">
                                                    <div>
                                                        <h6 class="mb-0">Excused</h6>
                                                        <small class="text-muted" id="transfereeCountText">
                                                            {{ $studentTypeStats['transferee_count'] ?? 0 }} Students
                                                        </small>
                                                    </div>
                                                    <div class="user-progress">
                                                        <h6 class="fw-semibold" id="transfereePercentageText">
                                                            {{ $studentTypeStats['transferee_percentage'] ?? 0 }}%
                                                        </h6>
                                                    </div>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Recent Users Card -->
                <div class="col-12 col-md-6 col-lg-3">
                    <div class="card h-100">
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <div class="me-3 d-none d-sm-block">
                                <img src="{{ asset('assetsDashboard/img/icons/dashIcon/active-user.png') }}"
                                    width="50" height="50">
                            </div>
                            <div class="me-1 d-block d-sm-none">
                                <img src="{{ asset('assetsDashboard/img/icons/dashIcon/active-user.png') }}"
                                    width="40" height="40">
                            </div>
                            <div class="d-flex flex-column align-items-center ms-auto">
                                <h5 class="fw-semibold text-primary mb-2 d-none d-sm-block">Recent Users</h5>
                                <h6 class="fw-semibold text-primary mb-2 d-sm-none d-block">Recent Users</h6>
                                <h1 class="fw-semibold mb-0" id="activeUsersCountTeacher">0</h1>
                            </div>
                        </div>

                        <!-- Filter Controls -->
                        <div class="card-body border-bottom py-2">
                            <div class="row g-2 align-items-center">
                                <!-- Role Filter -->
                                <div class="col-md-4 col-6">
                                    <div class="d-flex align-items-center">
                                        <select id="roleFilterTeacher" class="form-select form-select-sm">
                                            <option value="all">All Roles</option>
                                            <option value="admin">Admin</option>
                                            <option value="teacher">Teacher</option>
                                            <option value="parent">Parent</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Items Per Page -->
                                <div class="col-md-4 col-6">
                                    <div class="d-flex align-items-center justify-content-md-center">
                                        <select id="itemsPerPageTeacher" class="form-select form-select-sm">
                                            <option value="5">5</option>
                                            <option value="10" selected>10</option>
                                            <option value="15">15</option>
                                            <option value="20">20</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Search Input -->
                                <div class="col-md-4">
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text bg-transparent">
                                            <i class="bx bx-search"></i>
                                        </span>
                                        <input type="text" id="userSearchTeacher" class="form-control"
                                            placeholder="Search users..." style="border-left: 0;">
                                        <button class="btn btn-outline-secondary" type="button" id="clearSearchTeacher"
                                            style="display: none;">
                                            <i class="bx bx-x"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Fixed height container for users list -->
                        <div class="card-body p-0" style="height: 250px; display: flex; flex-direction: column;">
                            <div class="active-users-list flex-grow-1" style="overflow-y: auto;">
                                <ul class="p-0 m-0" id="activeUsersListTeacher">
                                    <!-- Users will be loaded here dynamically -->
                                    <li class="text-center py-3">
                                        <div class="spinner-border spinner-border-sm text-primary" role="status">
                                            <span class="visually-hidden">Loading...</span>
                                        </div>
                                        <small class="text-muted d-block mt-1">Loading users...</small>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <!-- Pagination and Search Info -->
                        <div class="card-footer py-2">
                            <div class="d-flex justify-content-between align-items-center">
                                <div id="paginationInfoTeacher" class="text-muted small">
                                    Showing <span id="showingCountTeacher">0</span> to <span
                                        id="showingEndTeacher">0</span> of <span id="totalCountTeacher">0</span> users
                                </div>
                                <nav aria-label="User pagination">
                                    <ul class="pagination pagination-sm mb-0" id="usersPaginationTeacher">
                                        <!-- Pagination will be generated here -->
                                    </ul>
                                </nav>
                            </div>
                            <div id="searchStatusTeacher" class="text-muted small mt-1 text-center">
                                <!-- Search status will be shown here -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Enrollees and Gender Chart Section (Compact) -->
            <div class="row">
                <!-- Total Enrollees Chart -->
                <div class="col-md-7 col-lg-8 mb-3">
                    <div class="card h-100">
                        <div class="card-body p-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h6 class="card-title m-0 d-sm-none d-block text-primary">Total Enrollees for School Year
                                    {{ $schoolYearText }}</h6>
                                <h4 class="card-title m-0 d-sm-block d-none text-primary">Total Enrollees for School Year
                                    {{ $schoolYearText }}</h4>
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-info text-white dropdown-toggle" type="button"
                                        id="yearDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                        {{ $schoolYearText }}
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="yearDropdown">
                                        @foreach ($schoolYears as $sy)
                                            <li>
                                                <a class="dropdown-item school-year-filter" href="#"
                                                    data-year="{{ $sy->id }}">
                                                    {{ $sy->school_year }}
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                            <canvas id="enrolleesChart" height="140"></canvas>
                        </div>
                    </div>
                </div>
                <!-- / Total Enrollees Chart -->

                <!-- Gender Distribution Card -->
                <div class="col-md-6 col-lg-4 col-xl-4 order-0 mb-2">
                    <div class="card h-100">
                        <div class="card-header d-flex justify-content-between align-items-center mb-0">
                            <div class="justify-content-center align-items-center card-title mb-0">
                                <!-- Web View Title -->
                                <h5 class="text-primary m-0 me-2 d-none d-sm-block">
                                    Student Gender Ratio for SY:
                                    <span class="school-year-display">{{ $schoolYearText }}</span>
                                </h5>
                                <!-- Mobile View Title -->
                                <h6 class="text-primary m-0 me-2 d-sm-none d-block">
                                    Student Gender Ratio for SY:
                                    <span class="school-year-display">{{ $schoolYearText }}</span>
                                </h6>
                            </div>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-info text-white dropdown-toggle" type="button"
                                    id="genderYearDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                    <span class="current-school-year">{{ $schoolYearText }}</span>
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="genderYearDropdown">
                                    @foreach ($schoolYears as $sy)
                                        <li>
                                            <a class="dropdown-item gender-year-filter" href="#"
                                                data-year="{{ $sy->id }}" data-year-text="{{ $sy->school_year }}">
                                                {{ $sy->school_year }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>

                        <!-- Grade Level & Section Filters -->
                        <div class="card-body">
                            <div class="row g-2">
                                <div class="col-6">
                                    <select id="gradeLevelFilter" class="form-select form-select-sm">
                                        <option value="">All Grade Levels</option>
                                        @foreach (['kindergarten', 'grade1', 'grade2', 'grade3', 'grade4', 'grade5', 'grade6'] as $grade)
                                            <option value="{{ $grade }}">
                                                {{ ucfirst(str_replace('grade', 'Grade ', $grade)) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-6">
                                    <select id="sectionFilter" class="form-select form-select-sm" disabled>
                                        <option value="">All Sections</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div class="d-flex flex-column align-items-center gap-1">
                                    <h2 class="mb-2" id="genderTotalStudents">{{ $genderStats['total'] ?? 0 }}</h2>
                                    <span>Total Students</span>
                                    <!-- School year display that updates dynamically -->
                                    <span id="genderSchoolYearText" class="school-year-text">for SY:
                                        {{ $schoolYearText }}</span>
                                    <small class="text-muted" id="genderFilterText"></small>
                                </div>
                                <div id="genderStatisticsChart"></div>
                            </div>

                            <ul class="p-0 m-0">
                                <li class="d-flex mb-3">
                                    <div class="avatar flex-shrink-0 me-3">
                                        <span class="avatar-initial rounded bg-label-danger">
                                            <i class="bx bx-female"></i>
                                        </span>
                                    </div>
                                    <div class="d-flex w-100 justify-content-between">
                                        <div>
                                            <h6 class="mb-0">Female</h6>
                                            <small class="text-muted"
                                                id="femaleCount">{{ $genderStats['female_count'] ?? 0 }} Students</small>
                                        </div>
                                        <div class="user-progress">
                                            <h6 class="fw-semibold" id="femalePercentage">
                                                {{ $genderStats['female_percentage'] ?? 0 }}%</h6>
                                        </div>
                                    </div>
                                </li>
                                <li class="d-flex">
                                    <div class="avatar flex-shrink-0 me-3">
                                        <span class="avatar-initial rounded bg-label-info">
                                            <i class="bx bx-male"></i>
                                        </span>
                                    </div>
                                    <div class="d-flex w-100 justify-content-between">
                                        <div>
                                            <h6 class="mb-0">Male</h6>
                                            <small class="text-muted"
                                                id="maleCount">{{ $genderStats['male_count'] ?? 0 }} Students</small>
                                        </div>
                                        <div class="user-progress">
                                            <h6 class="fw-semibold" id="malePercentage">
                                                {{ $genderStats['male_percentage'] ?? 0 }}%</h6>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <!-- / Enrollees and Gender Chart Section (Compact) -->

        </div>
        <!-- / Content -->

        <div class="content-backdrop fade"></div>
    </div>
    <!-- Content wrapper -->

    <!-- User Info Modal -->
    <div class="modal fade" id="userInfoModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header text-white">
                    <h4 class="modal-title fw-bold">
                        <i class="bx bx-user-circle me-2"></i>
                        <span id="modalUserName">User Information</span>
                    </h4>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <!-- Modal Body -->
                <div class="modal-body p-0">
                    <!-- Loading State -->
                    <div id="userInfoLoading" class="text-center py-5">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-3 text-muted">Loading user information...</p>
                    </div>

                    <!-- Content Container -->
                    <div id="userInfoContent" class="d-none">
                        <!-- Profile Header -->
                        <div class="row align-items-center bg-light p-4">
                            <div class="col-auto">
                                <img id="modalProfilePhoto" src="" alt="Profile Photo"
                                    class="rounded-circle border border-3 border-white shadow" width="100"
                                    height="100" style="object-fit: cover;">
                            </div>
                            <div class="col">
                                <h4 class="mb-1" id="modalFullName"></h4>
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <span class="badge" id="modalRoleBadge"></span>
                                    <span class="badge" id="modalStatusBadge"></span>
                                    <span class="badge bg-info" id="modalOnlineStatus">
                                        <i class="bx bx-wifi-0 me-1"></i> Offline
                                    </span>
                                </div>
                                <div class="text-muted small">
                                    <i class="bx bx-envelope me-1"></i> <span id="modalEmail"></span>
                                </div>
                            </div>
                        </div>

                        <!-- Tabs -->
                        <ul class="nav nav-tabs px-4 pt-3" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#profileTab"
                                    type="button">
                                    <i class="bx bx-user me-1"></i> Profile
                                </button>
                            </li>
                            <li class="nav-item" role="presentation" id="classesTabLi">
                                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#classesTab"
                                    type="button">
                                    <i class="bx bx-book-content me-1"></i> Classes
                                </button>
                            </li>
                            <li class="nav-item" role="presentation" id="childrenTabLi">
                                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#childrenTab"
                                    type="button">
                                    <i class="bx bx-group me-1"></i> Children
                                </button>
                            </li>
                        </ul>

                        <!-- Tab Content -->
                        <div class="tab-content p-4">
                            <!-- Profile Tab -->
                            <div class="tab-pane fade show active" id="profileTab">
                                <h5 class="fw-bold text-primary mb-3">Personal Information</h5>
                                <div class="row">
                                    <!-- Basic Info -->
                                    <div class="col-md-6">
                                        <div class="info-item mb-3">
                                            <label class="fw-bold text-muted small">Full Name</label>
                                            <p class="mb-0" id="infoFullName"></p>
                                        </div>
                                        <div class="info-item mb-3">
                                            <label class="fw-bold text-muted small">Email</label>
                                            <p class="mb-0" id="infoEmail"></p>
                                        </div>
                                        <div class="info-item mb-3">
                                            <label class="fw-bold text-muted small">Phone</label>
                                            <p class="mb-0" id="infoPhone"></p>
                                        </div>
                                        <div class="info-item mb-3">
                                            <label class="fw-bold text-muted small">Gender</label>
                                            <p class="mb-0" id="infoGender"></p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="info-item mb-3">
                                            <label class="fw-bold text-muted small">Date of Birth</label>
                                            <p class="mb-0" id="infoDob"></p>
                                        </div>
                                        <div class="info-item mb-3">
                                            <label class="fw-bold text-muted small">Age</label>
                                            <p class="mb-0" id="infoAge"></p>
                                        </div>
                                        <div class="info-item mb-3">
                                            <label class="fw-bold text-muted small">Account Created</label>
                                            <p class="mb-0" id="infoCreatedAt"></p>
                                        </div>
                                        <div class="info-item mb-3">
                                            <label class="fw-bold text-muted small">Last Active</label>
                                            <p class="mb-0" id="infoLastSeen"></p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Address Section -->
                                <div id="addressSection">
                                    <hr class="my-4">
                                    <h5 class="fw-bold text-primary mb-3">Address</h5>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="info-item mb-3">
                                                <label class="fw-bold text-muted small">House No.</label>
                                                <p class="mb-0" id="infoHouseNo"></p>
                                            </div>
                                            <div class="info-item mb-3">
                                                <label class="fw-bold text-muted small">Street</label>
                                                <p class="mb-0" id="infoStreet"></p>
                                            </div>
                                            <div class="info-item mb-3">
                                                <label class="fw-bold text-muted small">Barangay</label>
                                                <p class="mb-0" id="infoBarangay"></p>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="info-item mb-3">
                                                <label class="fw-bold text-muted small">City/Municipality</label>
                                                <p class="mb-0" id="infoCity"></p>
                                            </div>
                                            <div class="info-item mb-3">
                                                <label class="fw-bold text-muted small">Province</label>
                                                <p class="mb-0" id="infoProvince"></p>
                                            </div>
                                            <div class="info-item mb-3">
                                                <label class="fw-bold text-muted small">Zip Code</label>
                                                <p class="mb-0" id="infoZipCode"></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Classes Tab (for teachers) -->
                            <div class="tab-pane fade" id="classesTab">
                                <h5 class="fw-bold text-primary mb-3">Assigned Classes</h5>
                                <div id="classesContent">
                                    <!-- Classes will be loaded here -->
                                </div>
                            </div>

                            <!-- Children Tab (for parents) -->
                            <div class="tab-pane fade" id="childrenTab">
                                <h5 class="fw-bold text-primary mb-3">Children</h5>
                                <div id="childrenContent">
                                    <!-- Children will be loaded here -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Overlay -->
    <div class="layout-overlay layout-menu-toggle"></div>

@endsection

@push('scripts')
    <!-- Pusher Notification Script -->
    <script src="https://js.pusher.com/8.4.0/pusher.min.js"></script>
    <script>
        Pusher.logToConsole = true;

        var pusher = new Pusher("{{ env('VITE_PUSHER_APP_KEY') }}", {
            cluster: "{{ env('VITE_PUSHER_APP_CLUSTER') }}"
        });

        var userRole = "{{ Auth::user()->role ?? 'parent' }}";
        var channel = pusher.subscribe('announcements.' + userRole);

        channel.bind('new-announcement', function(data) {
            // Show browser notification
            if (Notification.permission === "granted") {
                new Notification("📢 New Announcement", {
                    body: data.announcement.title
                });
            }

            // Update badge count in real-time
            let badge = document.querySelector(".badge-notifications");
            if (badge) {
                let current = parseInt(badge.textContent.trim()) || 0;
                badge.textContent = current + 1;
                badge.style.display = "inline-block";
            }

            // Prepend new notification into dropdown
            let dropdown = document.querySelector("#notificationDropdown")
                .nextElementSibling;

            if (dropdown) {
                let newItem = `
                <li>
                    <a class="dropdown-item d-flex align-items-start gap-2 py-3" href="#">
                        <div class="rounded-circle bg-primary text-white d-flex justify-content-center align-items-center"
                            style="width:36px; height:36px;">📢</div>
                        <div>
                            <strong>${data.announcement.title}</strong>
                            <div class="text-muted small">${data.announcement.body.replace(/(<([^>]+)>)/gi, "").substring(0,40)}...</div>
                            <small class="text-muted">just now</small>
                        </div>
                        <span class="ms-auto text-primary mt-1"><i class="bx bxs-circle"></i></span>
                    </a>
                </li>
            `;
                dropdown.insertAdjacentHTML("afterbegin", newItem);
            }
        });

        if (Notification.permission !== "granted") {
            Notification.requestPermission();
        }
    </script>

    <!-- Logout Confirmation Script -->
    <script>
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

    <!-- Active Users Management for Teacher Dashboard -->
    <script>
        // Global variables for state management
        let allUsersTeacher = [];
        let filteredUsersTeacher = [];
        let currentPageTeacher = 1;
        let itemsPerPageTeacher = 10;
        let currentRoleFilterTeacher = 'all';
        let currentSearchTermTeacher = '';

        function loadActiveUsersTeacher() {
            fetch("{{ route('teacher.active-users') }}")
                .then(res => res.json())
                .then(users => {
                    allUsersTeacher = users;
                    applyFiltersAndPaginationTeacher();
                })
                .catch(err => {
                    console.error('Error loading active users:', err);
                    const container = document.getElementById('activeUsersListTeacher');
                    container.innerHTML = `
                <li class="text-center py-3 text-danger">
                    <i class="bx bx-error-alt fs-4"></i>
                    <small class="d-block">Failed to load users</small>
                </li>
            `;
                });
        }

        function applyFiltersAndPaginationTeacher() {
            // Apply role filter
            let tempFilteredUsers = allUsersTeacher.filter(user => {
                if (currentRoleFilterTeacher === 'all') return true;
                return user.role.toLowerCase() === currentRoleFilterTeacher;
            });

            // Apply search filter
            filteredUsersTeacher = tempFilteredUsers.filter(user => {
                if (!currentSearchTermTeacher) return true;

                const searchTerm = currentSearchTermTeacher.toLowerCase();
                const userName = user.name ? user.name.toLowerCase() : '';
                const userEmail = user.email ? user.email.toLowerCase() : '';
                const userRole = user.role ? user.role.toLowerCase() : '';

                return userName.includes(searchTerm) ||
                    userEmail.includes(searchTerm) ||
                    userRole.includes(searchTerm);
            });

            // Update count
            const countElement = document.getElementById('activeUsersCountTeacher');
            countElement.textContent = filteredUsersTeacher.length;

            // Calculate pagination
            const totalPages = Math.ceil(filteredUsersTeacher.length / itemsPerPageTeacher);
            currentPageTeacher = Math.min(Math.max(1, currentPageTeacher), totalPages);

            const startIndex = (currentPageTeacher - 1) * itemsPerPageTeacher;
            const endIndex = Math.min(startIndex + itemsPerPageTeacher, filteredUsersTeacher.length);
            const currentUsers = filteredUsersTeacher.slice(startIndex, endIndex);

            // Update display
            displayUsersTeacher(currentUsers);
            updatePaginationTeacher(totalPages, startIndex, endIndex);
            updatePaginationInfoTeacher(startIndex, endIndex, filteredUsersTeacher.length);
            updateSearchStatusTeacher();
        }

        function displayUsersTeacher(users) {
            const container = document.getElementById('activeUsersListTeacher');

            // Add updating class for smooth transition
            container.classList.add('updating');

            if (users.length === 0) {
                let message = 'No users found';
                if (currentSearchTermTeacher) {
                    message = `No users found for "${currentSearchTermTeacher}"`;
                } else if (currentRoleFilterTeacher !== 'all') {
                    message = `No ${currentRoleFilterTeacher} users found`;
                }

                container.innerHTML = `
                <li class="text-center py-4" style="min-height: 150px; display: flex; flex-direction: column; justify-content: center;">
                    <i class="bx bx-user-x fs-1 text-muted"></i>
                    <p class="text-muted mb-0 mt-2">${message}</p>
                </li>
            `;
                container.classList.remove('updating');
                return;
            }

            container.innerHTML = users.map(user => {
                const isOnline = user.is_online;
                const statusIndicator = isOnline ?
                    `<span class="position-absolute bottom-0 end-0 bg-success border border-white rounded-circle" style="width: 10px; height: 10px;"></span>` :
                    `<span class="position-absolute bottom-0 end-0 bg-secondary border border-white rounded-circle" style="width: 10px; height: 10px;"></span>`;

                const statusText = isOnline ?
                    `<small class="text-success">
                    <i class="bx bxs-circle me-1" style="font-size: 0.6rem;"></i>
                    Online
                </small>` :
                    `<small class="text-muted">
                    <i class="bx bx-time me-1" style="font-size: 0.6rem;"></i>
                    ${formatLastSeenTeacher(user.last_seen)}
                </small>`;

                const profilePhoto = getProfilePhotoUrlTeacher(user);
                const roleDisplay = getRoleDisplayTeacher(user.role);

                // Highlight search term in name
                let displayName = user.name;
                if (currentSearchTermTeacher) {
                    const regex = new RegExp(`(${currentSearchTermTeacher})`, 'gi');
                    displayName = user.name.replace(regex, '<mark class="bg-warning px-1 rounded">$1</mark>');
                }

                return `
                <li class="d-flex align-items-center px-3 py-2 border-bottom" style="min-height: 60px; cursor: pointer;" onclick="viewUserInfo(${user.id})">
                    <div class="flex-shrink-0 me-3 position-relative">
                        <img src="${profilePhoto}"
                            alt="${user.name}"
                            class="rounded-circle"
                            width="40"
                            height="40"
                            style="object-fit: cover;">
                        ${statusIndicator}
                    </div>
                    <div class="flex-grow-1">
                        <h6 class="mb-0 text-truncate" style="max-width: 120px;">${displayName}</h6>
                        <div class="d-flex align-items-center">
                            ${roleDisplay}
                        </div>
                    </div>
                    <div class="flex-shrink-0 text-end">
                        ${statusText}
                    </div>
                </li>
            `;
            }).join('');

            // Remove updating class after render
            setTimeout(() => {
                container.classList.remove('updating');
            }, 300);
        }

        function updatePaginationTeacher(totalPages, startIndex, endIndex) {
            const paginationContainer = document.getElementById('usersPaginationTeacher');

            if (totalPages <= 1) {
                paginationContainer.innerHTML = '';
                return;
            }

            let paginationHTML = '';

            // Previous button
            paginationHTML += `
            <li class="page-item ${currentPageTeacher === 1 ? 'disabled' : ''}">
                <a class="page-link" href="javascript:void(0)" onclick="changePageTeacher(${currentPageTeacher - 1})">
                    <i class="bx bx-chevron-left"></i>
                </a>
            </li>
        `;

            // Always show only 2 page numbers
            let startPage = currentPageTeacher;
            let endPage = Math.min(currentPageTeacher + 1, totalPages);

            // Adjust if we're at the end - show previous page instead
            if (endPage === totalPages && totalPages > 2) {
                startPage = totalPages - 1;
                endPage = totalPages;
            } else if (totalPages === 1) {
                startPage = 1;
                endPage = 1;
            }

            // Generate the 2 page numbers
            for (let i = startPage; i <= endPage; i++) {
                paginationHTML += `
                <li class="page-item ${i === currentPageTeacher ? 'active' : ''}">
                    <a class="page-link" href="javascript:void(0)" onclick="changePageTeacher(${i})">${i}</a>
                </li>
            `;
            }

            // Next button
            paginationHTML += `
            <li class="page-item ${currentPageTeacher === totalPages ? 'disabled' : ''}">
                <a class="page-link" href="javascript:void(0)" onclick="changePageTeacher(${currentPageTeacher + 1})">
                    <i class="bx bx-chevron-right"></i>
                </a>
            </li>
        `;

            paginationContainer.innerHTML = paginationHTML;
        }

        function updatePaginationInfoTeacher(startIndex, endIndex, total) {
            const showingCount = document.getElementById('showingCountTeacher');
            const showingEnd = document.getElementById('showingEndTeacher');
            const totalCount = document.getElementById('totalCountTeacher');

            if (total === 0) {
                showingCount.textContent = '0';
                showingEnd.textContent = '0';
                totalCount.textContent = '0';
            } else {
                showingCount.textContent = startIndex + 1;
                showingEnd.textContent = endIndex;
                totalCount.textContent = total;
            }
        }

        function updateSearchStatusTeacher() {
            const searchStatus = document.getElementById('searchStatusTeacher');

            // Update search status
            if (currentSearchTermTeacher) {
                searchStatus.innerHTML = `Search results for: "${currentSearchTermTeacher}"`;
                searchStatus.classList.remove('text-muted');
                searchStatus.classList.add('text-info');
            } else if (currentRoleFilterTeacher !== 'all') {
                searchStatus.innerHTML = `Filtered by: ${currentRoleFilterTeacher}`;
                searchStatus.classList.remove('text-muted');
                searchStatus.classList.add('text-primary');
            } else {
                // Remove the "Showing all users" text completely
                searchStatus.innerHTML = '';
                searchStatus.classList.remove('text-info', 'text-primary');
                searchStatus.classList.add('text-muted');
            }
        }

        function changePageTeacher(page) {
            currentPageTeacher = page;
            applyFiltersAndPaginationTeacher();

            // Scroll to top of user list smoothly
            const userList = document.querySelector('.active-users-list');
            if (userList) {
                userList.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            }
        }

        // Helper function to format last seen time
        function formatLastSeenTeacher(lastSeen) {
            if (!lastSeen || lastSeen === 'Not signed in yet') {
                return 'Never';
            }

            if (lastSeen.includes('seconds') || lastSeen.includes('second')) {
                return 'just now';
            } else if (lastSeen.includes('minute')) {
                return `${lastSeen.replace('ago', '').trim()}`;
            } else if (lastSeen.includes('hour')) {
                return `${lastSeen.replace('ago', '').trim()}`;
            } else if (lastSeen.includes('day')) {
                return `${lastSeen.replace('ago', '').trim()}`;
            } else if (lastSeen.includes('week')) {
                return `${lastSeen.replace('ago', '').trim()}`;
            } else if (lastSeen.includes('month')) {
                return `${lastSeen.replace('ago', '').trim()}`;
            } else {
                return `${lastSeen}`;
            }
        }

        // Profile photo URL logic
        function getProfilePhotoUrlTeacher(user) {
            if (user.profile_photo_url) {
                return user.profile_photo_url;
            }

            if (user.profile_photo) {
                if (user.profile_photo.startsWith('http://') || user.profile_photo.startsWith('https://')) {
                    return user.profile_photo;
                } else {
                    return "{{ asset('public/uploads/') }}/" + user.profile_photo;
                }
            } else {
                const role = user.role.toLowerCase();
                switch (role) {
                    case 'admin':
                        return "{{ asset('assetsDashboard/img/profile_pictures/admin_default_profile.jpg') }}";
                    case 'teacher':
                        return "{{ asset('assetsDashboard/img/profile_pictures/teacher_default_profile.jpg') }}";
                    case 'parent':
                        return "{{ asset('assetsDashboard/img/profile_pictures/parent_default_profile.jpg') }}";
                    default:
                        return 'https://ui-avatars.com/api/?name=' + encodeURIComponent(user.name);
                }
            }
        }

        // Role display with icon and color
        function getRoleDisplayTeacher(role) {
            const roleLower = role.toLowerCase();
            let icon = '';
            let colorClass = '';
            let displayText = role.charAt(0).toUpperCase() + role.slice(1);

            switch (roleLower) {
                case 'admin':
                    icon = '<i class="bx bx-cog text-info me-1"></i>';
                    colorClass = 'text-info';
                    break;
                case 'teacher':
                    icon = '<i class="bx bx-book-reader text-primary me-1"></i>';
                    colorClass = 'text-primary';
                    break;
                case 'parent':
                    icon = '<i class="bx bx-home-heart text-warning me-1"></i>';
                    colorClass = 'text-warning';
                    break;
                default:
                    icon = '<i class="bi bi-person-fill text-secondary me-1"></i>';
                    colorClass = 'text-secondary';
            }

            return `<small class="${colorClass} text-capitalize d-flex align-items-center">${icon} ${displayText}</small>`;
        }

        // Debounce function for search
        function debounceTeacher(func, wait) {
            let timeout;
            return function executedFunction(...args) {
                const later = () => {
                    clearTimeout(timeout);
                    func(...args);
                };
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
            };
        }

        // Event listeners for filters
        document.addEventListener('DOMContentLoaded', function() {
            // Role filter
            const roleFilterTeacher = document.getElementById('roleFilterTeacher');
            if (roleFilterTeacher) {
                roleFilterTeacher.addEventListener('change', function() {
                    currentRoleFilterTeacher = this.value;
                    currentPageTeacher = 1;
                    applyFiltersAndPaginationTeacher();
                });
            }

            // Items per page
            const itemsPerPageSelect = document.getElementById('itemsPerPageTeacher');
            if (itemsPerPageSelect) {
                itemsPerPageSelect.addEventListener('change', function() {
                    itemsPerPageTeacher = parseInt(this.value);
                    currentPageTeacher = 1;
                    applyFiltersAndPaginationTeacher();
                });
            }

            // Search input with debounce
            const searchInputTeacher = document.getElementById('userSearchTeacher');
            const clearSearchBtnTeacher = document.getElementById('clearSearchTeacher');

            if (searchInputTeacher) {
                const handleSearch = debounceTeacher(function() {
                    currentSearchTermTeacher = searchInputTeacher.value.trim();
                    currentPageTeacher = 1;

                    // Show/hide clear button
                    if (clearSearchBtnTeacher) {
                        if (currentSearchTermTeacher) {
                            clearSearchBtnTeacher.style.display = 'block';
                        } else {
                            clearSearchBtnTeacher.style.display = 'none';
                        }
                    }

                    applyFiltersAndPaginationTeacher();
                }, 300);

                searchInputTeacher.addEventListener('input', handleSearch);
            }

            // Clear search
            if (clearSearchBtnTeacher) {
                clearSearchBtnTeacher.addEventListener('click', function() {
                    if (searchInputTeacher) {
                        searchInputTeacher.value = '';
                        currentSearchTermTeacher = '';
                        currentPageTeacher = 1;
                        clearSearchBtnTeacher.style.display = 'none';
                        applyFiltersAndPaginationTeacher();
                        searchInputTeacher.focus();
                    }
                });
            }

            // Load initial data
            loadActiveUsersTeacher();

            // Auto-refresh every 30 seconds
            setInterval(loadActiveUsersTeacher, 30000);
        });
    </script>

    <!-- User Info Modal Script -->
    <script>
        // User Info Modal Functions
        let userInfoModal = null;

        // Initialize modal when page loads
        document.addEventListener('DOMContentLoaded', function() {
            userInfoModal = new bootstrap.Modal(document.getElementById('userInfoModal'));
        });

        // Function to view user info
        function viewUserInfo(userId) {
            // Show loading state
            document.getElementById('userInfoLoading').classList.remove('d-none');
            document.getElementById('userInfoContent').classList.add('d-none');

            // Show modal
            userInfoModal.show();

            // Fetch user data
            fetch(`/teacher/user-info/${userId}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(userData => {
                    // Update modal with user data
                    updateUserInfoModal(userData);

                    // Hide loading, show content
                    document.getElementById('userInfoLoading').classList.add('d-none');
                    document.getElementById('userInfoContent').classList.remove('d-none');
                })
                .catch(error => {
                    console.error('Error loading user info:', error);
                    document.getElementById('userInfoLoading').innerHTML = `
                <div class="text-center py-5 text-danger">
                    <i class="bx bx-error-alt fs-1"></i>
                    <p class="mt-3">Failed to load user information</p>
                    <button class="btn btn-outline-primary btn-sm" onclick="viewUserInfo(${userId})">
                        <i class="bx bx-refresh me-1"></i> Try Again
                    </button>
                </div>
            `;
                });
        }

        // Function to update modal with user data
        function updateUserInfoModal(userData) {
            // Update header
            document.getElementById('modalUserName').textContent = userData.name;
            document.getElementById('modalFullName').textContent = userData.name;
            document.getElementById('modalEmail').textContent = userData.email;

            // Update profile photo
            const profilePhoto = document.getElementById('modalProfilePhoto');
            profilePhoto.src = userData.profile_photo || getDefaultProfilePhoto(userData.role);
            profilePhoto.alt = userData.name;

            // Update role badge
            const roleBadge = document.getElementById('modalRoleBadge');
            roleBadge.textContent = userData.role.charAt(0).toUpperCase() + userData.role.slice(1);
            roleBadge.className = 'badge ' + getRoleBadgeClass(userData.role);

            // Update status badge
            const statusBadge = document.getElementById('modalStatusBadge');
            statusBadge.textContent = userData.status.charAt(0).toUpperCase() + userData.status.slice(1);
            statusBadge.className = 'badge ' + getStatusBadgeClass(userData.status);

            // Update online status
            const onlineBadge = document.getElementById('modalOnlineStatus');
            if (userData.is_online) {
                onlineBadge.className = 'badge bg-success';
                onlineBadge.innerHTML = 'Online';
            } else {
                onlineBadge.className = 'badge bg-secondary';
                onlineBadge.innerHTML = ` ${userData.last_seen || 'Offline'}`;
            }

            // Update profile tab information
            document.getElementById('infoFullName').textContent = userData.name;
            document.getElementById('infoEmail').textContent = userData.email;
            document.getElementById('infoPhone').textContent = userData.phone || 'N/A';
            document.getElementById('infoGender').textContent = userData.gender ? userData.gender.charAt(0).toUpperCase() +
                userData.gender.slice(1) : 'N/A';
            document.getElementById('infoDob').textContent = userData.dob || 'N/A';
            document.getElementById('infoAge').textContent = userData.age ? userData.age + ' years old' : 'N/A';
            document.getElementById('infoCreatedAt').textContent = userData.created_at;
            document.getElementById('infoLastSeen').textContent = userData.last_seen || 'Never';

            // Update address section
            const addressSection = document.getElementById('addressSection');
            if (userData.address && (
                    userData.address.house_no ||
                    userData.address.street_name ||
                    userData.address.barangay ||
                    userData.address.municipality_city ||
                    userData.address.province ||
                    userData.address.zip_code
                )) {
                addressSection.classList.remove('d-none');
                document.getElementById('infoHouseNo').textContent = userData.address.house_no || 'N/A';
                document.getElementById('infoStreet').textContent = userData.address.street_name || 'N/A';
                document.getElementById('infoBarangay').textContent = userData.address.barangay || 'N/A';
                document.getElementById('infoCity').textContent = userData.address.municipality_city || 'N/A';
                document.getElementById('infoProvince').textContent = userData.address.province || 'N/A';
                document.getElementById('infoZipCode').textContent = userData.address.zip_code || 'N/A';
            } else {
                addressSection.classList.add('d-none');
            }

            // Handle role-specific tabs
            updateRoleSpecificTabs(userData);
        }

        // Function to update role-specific tabs
        function updateRoleSpecificTabs(userData) {
            // Show/hide tabs based on role
            const classesTabLi = document.getElementById('classesTabLi');
            const childrenTabLi = document.getElementById('childrenTabLi');
            const classesTab = document.getElementById('classesTab');
            const childrenTab = document.getElementById('childrenTab');

            // Reset all tabs
            classesTabLi.classList.add('d-none');
            childrenTabLi.classList.add('d-none');

            // Show appropriate tabs based on role
            if (userData.role === 'teacher' && userData.classes) {
                classesTabLi.classList.remove('d-none');
                updateClassesTab(userData.classes);
            } else if (userData.role === 'parent' && userData.children) {
                childrenTabLi.classList.remove('d-none');
                updateChildrenTab(userData.children);
            }

            // Activate first tab
            document.querySelector('.nav-tabs .nav-link').click();
        }

        // Function to update classes tab
        function updateClassesTab(classesData) {
            const classesContent = document.getElementById('classesContent');

            if (!classesData || Object.keys(classesData).length === 0) {
                classesContent.innerHTML = `
            <div class="alert alert-secondary text-center">
                <i class="bx bx-info-circle me-1"></i>
                No classes assigned to this teacher.
            </div>
        `;
                return;
            }

            let html = '';
            Object.entries(classesData).forEach(([schoolYear, classes]) => {
                html += `
            <div class="card shadow-sm border-0 mb-3">
                <div class="card-header bg-light">
                    <h6 class="mb-0 fw-bold">${schoolYear}</h6>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        ${classes.map(cls => `
                                                                                                                                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                                                                                                                                            <div>
                                                                                                                                                                <span class="fw-bold">${cls.grade_level.toUpperCase()} - ${cls.section}</span>
                                                                                                                                                            </div>
                                                                                                                                                            <span class="badge ${cls.role === 'adviser' ? 'bg-success' : 'bg-info'}">
                                                                                                                                                                ${cls.role.charAt(0).toUpperCase() + cls.role.slice(1)}
                                                                                                                                                            </span>
                                                                                                                                                        </li>
                                                                                                                                                    `).join('')}
                    </ul>
                </div>
            </div>
        `;
            });

            classesContent.innerHTML = html;
        }

        // Function to update children tab
        function updateChildrenTab(childrenData) {
            const childrenContent = document.getElementById('childrenContent');

            if (!childrenData || childrenData.length === 0) {
                childrenContent.innerHTML = `
            <div class="alert alert-secondary text-center">
                <i class="bx bx-info-circle me-1"></i>
                No children linked to this parent.
            </div>
        `;
                return;
            }

            let html = '<div class="row g-3">';
            childrenData.forEach(child => {
                html += `
            <div class="col-md-6">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body text-center p-3">
                        <img src="${child.profile_photo}"
                             alt="${child.name}"
                             class="rounded-circle mb-3 shadow-sm"
                             style="object-fit: cover; width: 80px; height: 80px;">
                        <h6 class="fw-bold mb-1">${child.name}</h6>
                        <div class="small text-muted mb-2">
                            <i class="bx bx-id-card me-1"></i>
                            LRN: ${child.lrn || 'Not available'}
                        </div>
                    </div>
                </div>
            </div>
        `;
            });
            html += '</div>';

            childrenContent.innerHTML = html;
        }

        // Helper function for role badge classes
        function getRoleBadgeClass(role) {
            switch (role.toLowerCase()) {
                case 'admin':
                    return 'bg-info';
                case 'teacher':
                    return 'bg-primary';
                case 'parent':
                    return 'bg-warning';
                default:
                    return 'bg-secondary';
            }
        }

        // Helper function for status badge classes
        function getStatusBadgeClass(status) {
            switch (status.toLowerCase()) {
                case 'active':
                    return 'bg-label-success';
                case 'inactive':
                    return 'bg-label-secondary';
                case 'suspended':
                    return 'bg-label-warning';
                case 'banned':
                    return 'bg-label-danger';
                default:
                    return 'bg-label-dark';
            }
        }

        // Helper function for default profile photo
        function getDefaultProfilePhoto(role) {
            switch (role.toLowerCase()) {
                case 'admin':
                    return "{{ asset('assetsDashboard/img/profile_pictures/admin_default_profile.jpg') }}";
                case 'teacher':
                    return "{{ asset('assetsDashboard/img/profile_pictures/teacher_default_profile.jpg') }}";
                case 'parent':
                    return "{{ asset('assetsDashboard/img/profile_pictures/parent_default_profile.jpg') }}";
                default:
                    return "{{ asset('assetsDashboard/img/profile_pictures/default_profile.jpg') }}";
            }
        }
    </script>

    <!-- Dashboard Student Table with Filters and Pagination -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            console.log("Dashboard Student Table Script Loaded");

            // DOM Elements
            const studentsContainer = document.getElementById('studentsContainer');
            const studentsLoading = document.getElementById('studentsLoading');
            const dashboardNoResults = document.getElementById('dashboardNoResults');
            const dashboardStudentSearch = document.getElementById('dashboardStudentSearch');
            const clearSearchDashboard = document.getElementById('clearSearchDashboard');
            const dashboardSchoolYear = document.getElementById('dashboardSchoolYear');
            const dashboardClassFilter = document.getElementById('dashboardClassFilter');
            const filterStatusDashboard = document.getElementById('filterStatusDashboard');
            const refreshStudentsBtn = document.getElementById('refreshStudentsBtn');
            const studentsTableBody = document.getElementById('studentsTableBody');
            const tableInfo = document.getElementById('tableInfo');
            const studentsPagination = document.getElementById('studentsPagination');

            // Gender ratio card elements
            const genderRatioSchoolYear = document.getElementById('genderRatioSchoolYear');
            const genderRatioSchoolYearMobile = document.getElementById('genderRatioSchoolYearMobile');
            const currentSchoolYearDisplay = document.getElementById('currentSchoolYearDisplay');
            const genderSchoolYearText = document.getElementById('genderSchoolYearText');
            const genderTotalStudents = document.getElementById('genderTotalStudents');
            const femaleCount = document.getElementById('femaleCount');
            const maleCount = document.getElementById('maleCount');
            const femalePercentage = document.getElementById('femalePercentage');
            const malePercentage = document.getElementById('malePercentage');
            const genderFilterText = document.getElementById('genderFilterText');

            // Student type card elements
            const currentStudentTypeSchoolYearDisplay = document.getElementById(
                'currentStudentTypeSchoolYearDisplay');
            const studentTypeSchoolYearText = document.getElementById('studentTypeSchoolYearText');
            const studentTypeTotal = document.getElementById('studentTypeTotal');
            const regularCountText = document.getElementById('regularCountText');
            const returneeCountText = document.getElementById('returneeCountText');
            const transfereeCountText = document.getElementById('transfereeCountText');
            const regularPercentageText = document.getElementById('regularPercentageText');
            const returneePercentageText = document.getElementById('returneePercentageText');
            const transfereePercentageText = document.getElementById('transfereePercentageText');
            const studentTypeFilterText = document.getElementById('studentTypeFilterText');

            // State variables
            let currentPage = 1;
            const perPage = 5;
            let currentSearchTerm = '';
            let currentSchoolYear = dashboardSchoolYear.value;
            let currentClass = 'all';
            let totalStudents = 0;
            let lastPage = 1;
            let currentSchoolYearId = null;

            // Initialize
            loadClasses();
            loadStudents();

            // Event Listeners
            dashboardSchoolYear.addEventListener('change', function() {
                currentSchoolYear = this.value;
                currentPage = 1;
                loadClasses();
                // Update stats when school year changes
                updateGenderRatioCard(true); // Pass true to fetch from server
                updateStudentTypeCard(true); // Pass true to fetch from server
            });

            dashboardClassFilter.addEventListener('change', function() {
                currentClass = this.value;
                currentPage = 1;
                loadStudents();
                // Update stats when class filter changes
                updateGenderRatioCard(true); // Pass true to fetch from server
                updateStudentTypeCard(true); // Pass true to fetch from server
            });

            refreshStudentsBtn.addEventListener('click', function() {
                currentPage = 1;
                loadClasses();
                // Update stats when refreshing
                updateGenderRatioCard(true);
                updateStudentTypeCard(true);
            });

            // Search functionality with debounce
            let searchTimeout;
            dashboardStudentSearch.addEventListener('input', function() {
                currentSearchTerm = this.value.trim();

                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    currentPage = 1;
                    loadStudents();
                }, 300);

                // Show/hide clear button
                if (clearSearchDashboard) {
                    if (currentSearchTerm) {
                        clearSearchDashboard.style.display = 'block';
                    } else {
                        clearSearchDashboard.style.display = 'none';
                    }
                }
            });

            clearSearchDashboard.addEventListener('click', function() {
                dashboardStudentSearch.value = '';
                currentSearchTerm = '';
                currentPage = 1;
                loadStudents();
                this.style.display = 'none';
            });

            // Load classes for the selected school year
            function loadClasses() {
                fetch(`/teacher/dashboard/classes?school_year=${encodeURIComponent(currentSchoolYear)}`, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                'content')
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            updateClassFilter(data.classes);
                            // Load students after classes are loaded
                            loadStudents();
                        } else {
                            console.error('Error loading classes:', data.message);
                            updateClassFilter([]);
                            loadStudents();
                        }
                    })
                    .catch(error => {
                        console.error('Error loading classes:', error);
                        updateClassFilter([]);
                        loadStudents();
                    });
            }

            function updateClassFilter(classes) {
                dashboardClassFilter.innerHTML = '<option value="all">All Classes</option>';

                classes.forEach(cls => {
                    const option = document.createElement('option');
                    option.value = cls.id;
                    option.textContent = cls.name;
                    dashboardClassFilter.appendChild(option);
                });

                // Reset to "All Classes" when school year changes
                dashboardClassFilter.value = 'all';
                currentClass = 'all';
            }

            // Load students with filters and pagination
            function loadStudents() {
                // Show loading, hide table
                studentsLoading.classList.remove('d-none');
                studentsContainer.classList.add('d-none');
                dashboardNoResults.classList.add('d-none');

                // Build query parameters
                const params = new URLSearchParams({
                    school_year: currentSchoolYear,
                    class: currentClass,
                    page: currentPage,
                    per_page: perPage
                });

                if (currentSearchTerm) {
                    params.append('search', currentSearchTerm);
                }

                // Update filter status
                updateFilterStatus();

                // Fetch data
                fetch(`/teacher/dashboard/students?${params.toString()}`, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                'content')
                        }
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }
                        return response.json();
                    })
                    .then(data => {
                        console.log('Students data received:', data);

                        if (!data.success) {
                            throw new Error(data.message || 'Failed to load students');
                        }

                        // Store the school year ID from the response
                        currentSchoolYearId = data.schoolYearId;

                        // Hide loading
                        studentsLoading.classList.add('d-none');

                        if (data.students.length === 0) {
                            dashboardNoResults.textContent = 'No students found for the selected filters.';
                            dashboardNoResults.classList.remove('d-none');
                            studentsContainer.classList.add('d-none');
                        } else {
                            renderStudents(data.students);
                            updatePagination(data.pagination);
                            updateTableInfo(data.pagination);
                            studentsContainer.classList.remove('d-none');
                            dashboardNoResults.classList.add('d-none');
                        }

                        // Update stats cards (for display in table header, not total stats)
                        updateGenderRatioCard(false); // false = use displayed students
                        updateStudentTypeCard(false); // false = use displayed students
                    })
                    .catch(error => {
                        console.error('Error loading students:', error);
                        studentsLoading.classList.add('d-none');
                        dashboardNoResults.textContent = `Error: ${error.message}. Please try again.`;
                        dashboardNoResults.classList.remove('d-none');
                        studentsContainer.classList.add('d-none');
                    });
            }

            function renderStudents(students) {
                studentsTableBody.innerHTML = '';

                students.forEach(student => {
                    const classInfo = student.class;
                    const gradeSection = classInfo ?
                        `${classInfo.formatted_grade_level || classInfo.grade_level} - ${classInfo.section}` :
                        'N/A';
                    const enrollmentStatus = classInfo && classInfo.pivot ?
                        (classInfo.pivot.enrollment_status || 'N/A') : 'N/A';
                    const enrollmentType = classInfo && classInfo.pivot ?
                        (classInfo.pivot.enrollment_type || 'N/A') : 'N/A';

                    // Status badge classes
                    const statusBadgeClass = getStatusBadgeClass(enrollmentStatus);
                    const typeBadgeClass = getTypeBadgeClass(enrollmentType);

                    // Student name
                    const fullName =
                        `${student.student_lName || ''}, ${student.student_fName || ''} ${student.student_mName || ''} ${student.student_extName || ''}`
                        .trim();

                    // Student info URL - use currentSchoolYearId or fallback
                    const schoolYearParam = currentSchoolYearId ?
                        `?school_year=${currentSchoolYearId}` :
                        (classInfo && classInfo.pivot && classInfo.pivot.school_year_id ?
                            `?school_year=${classInfo.pivot.school_year_id}` :
                            '');

                    const studentInfoUrl = `/teacher/studentInfo/${student.id}${schoolYearParam}`;

                    const row = document.createElement('tr');
                    row.className = 'dashboard-student-row';
                    row.setAttribute('data-name', fullName.toLowerCase());
                    row.setAttribute('data-lrn', (student.student_lrn || '').toLowerCase());
                    row.setAttribute('data-grade-section', gradeSection.toLowerCase());
                    row.setAttribute('data-enrollment-status', enrollmentStatus.toLowerCase());
                    row.setAttribute('data-enrollment-type', enrollmentType.toLowerCase());
                    row.setAttribute('data-gender', (student.gender || '').toLowerCase());
                    row.setAttribute('data-is-female', (student.gender || '').toLowerCase() === 'f' ||
                        (student.gender || '').toLowerCase() === 'female' ? '1' : '0');
                    row.setAttribute('data-is-male', (student.gender || '').toLowerCase() === 'm' ||
                        (student.gender || '').toLowerCase() === 'male' ? '1' : '0');

                    // Combined column with photo, name, and LRN
                    const studentPhoto = student.student_photo ?
                        `<img src="/public/uploads/${student.student_photo}" alt="Profile" width="40" height="40" style="object-fit: cover; border-radius: 50%;">` :
                        `<img src="/assetsDashboard/img/student_profile_pictures/student_default_profile.jpg" alt="No Profile" width="40" height="40" style="object-fit: cover; border-radius: 50%;">`;

                    row.innerHTML = `
            <td>
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0 me-3">
                        <a href="${studentInfoUrl}">
                            ${studentPhoto}
                        </a>
                    </div>
                    <div class="flex-grow-1">
                        <a href="${studentInfoUrl}" class="text-decoration-none text-dark fw-semibold d-block mb-1">
                            ${fullName}
                        </a>
                        <div class="text-muted small">
                            <span>${student.student_lrn || 'N/A'}</span>
                        </div>
                    </div>
                </div>
            </td>
            <td class="align-middle text-center">
                <span class="fw-medium">${gradeSection}</span>
            </td>
            <td class="align-middle text-center">
                <span class="badge ${statusBadgeClass} text-uppercase px-3 py-1">
                    ${enrollmentStatus.replace('_', ' ').toUpperCase()}
                </span>
            </td>
            <td class="align-middle text-center">
                <span class="badge ${typeBadgeClass} text-uppercase px-3 py-1">
                    ${enrollmentType.replace('_', ' ').toUpperCase()}
                </span>
            </td>
        `;

                    studentsTableBody.appendChild(row);
                });
            }

            function updatePagination(pagination) {
                studentsPagination.innerHTML = '';
                totalStudents = pagination.total;
                lastPage = pagination.last_page;

                if (lastPage <= 1) {
                    return;
                }

                // Previous button
                const prevLi = createPaginationItem('prev', currentPage === 1, 'Previous', () => {
                    if (currentPage > 1) {
                        currentPage--;
                        loadStudents();
                    }
                });
                studentsPagination.appendChild(prevLi);

                // Page numbers
                const maxVisiblePages = 5;
                let startPage = Math.max(1, currentPage - Math.floor(maxVisiblePages / 2));
                let endPage = Math.min(lastPage, startPage + maxVisiblePages - 1);

                if (endPage - startPage + 1 < maxVisiblePages) {
                    startPage = Math.max(1, endPage - maxVisiblePages + 1);
                }

                // First page ellipsis
                if (startPage > 1) {
                    const firstLi = createPaginationItem('page', false, '1', () => {
                        currentPage = 1;
                        loadStudents();
                    });
                    studentsPagination.appendChild(firstLi);

                    if (startPage > 2) {
                        const ellipsisLi = createPaginationItem('ellipsis', true, '...', null);
                        studentsPagination.appendChild(ellipsisLi);
                    }
                }

                // Page numbers
                for (let i = startPage; i <= endPage; i++) {
                    const pageLi = createPaginationItem('page', i === currentPage, i.toString(), () => {
                        currentPage = i;
                        loadStudents();
                    });
                    studentsPagination.appendChild(pageLi);
                }

                // Last page ellipsis
                if (endPage < lastPage) {
                    if (endPage < lastPage - 1) {
                        const ellipsisLi = createPaginationItem('ellipsis', true, '...', null);
                        studentsPagination.appendChild(ellipsisLi);
                    }

                    const lastLi = createPaginationItem('page', false, lastPage.toString(), () => {
                        currentPage = lastPage;
                        loadStudents();
                    });
                    studentsPagination.appendChild(lastLi);
                }

                // Next button
                const nextLi = createPaginationItem('next', currentPage === lastPage, 'Next', () => {
                    if (currentPage < lastPage) {
                        currentPage++;
                        loadStudents();
                    }
                });
                studentsPagination.appendChild(nextLi);
            }

            function createPaginationItem(type, disabled, text, onClick) {
                const li = document.createElement('li');
                li.className =
                    `page-item ${disabled ? 'disabled' : ''} ${type === 'page' && text === currentPage.toString() ? 'active' : ''}`;

                if (type === 'prev' || type === 'next') {
                    const icon = type === 'prev' ? 'bx bx-chevron-left' : 'bx bx-chevron-right';
                    li.innerHTML = `<a class="page-link" href="javascript:void(0);"><i class="${icon}"></i></a>`;
                } else if (type === 'ellipsis') {
                    li.innerHTML = `<span class="page-link">${text}</span>`;
                } else {
                    li.innerHTML = `<a class="page-link" href="javascript:void(0);">${text}</a>`;
                }

                if (!disabled && onClick) {
                    li.addEventListener('click', onClick);
                }

                return li;
            }

            function updateTableInfo(pagination) {
                const from = pagination.from || 0;
                const to = pagination.to || 0;
                const total = pagination.total || 0;

                tableInfo.textContent = `Showing ${from} to ${to} of ${total} entries`;
            }

            function updateFilterStatus() {
                let statusText = `School Year: ${currentSchoolYear}`;

                if (currentClass !== 'all') {
                    const selectedClass = dashboardClassFilter.options[dashboardClassFilter.selectedIndex];
                    statusText += ` | Class: ${selectedClass.text}`;
                }

                if (currentSearchTerm) {
                    statusText += ` | Search: "${currentSearchTerm}"`;
                }

                statusText += ` | Page: ${currentPage}`;

                filterStatusDashboard.textContent = statusText;
            }

            function updateGenderRatioCard(fetchFromServer = false) {
                if (fetchFromServer) {
                    // Fetch gender statistics from server for the selected filters
                    fetchGenderStatistics();
                } else {
                    // Calculate from displayed students (for table info)
                    calculateDisplayedGenderStatistics();
                }
            }

            function updateStudentTypeCard(fetchFromServer = false) {
                if (fetchFromServer) {
                    // Fetch student type statistics from server for the selected filters
                    fetchStudentTypeStatistics();
                } else {
                    // Calculate from displayed students (for table info)
                    calculateDisplayedStudentTypeStatistics();
                }
            }

            function fetchGenderStatistics() {
                // Get the school year ID for the selected school year
                const selectedSchoolYearText = dashboardSchoolYear.value;

                // First, get the school year ID
                fetch(`/teacher/get-school-year-id?school_year=${encodeURIComponent(selectedSchoolYearText)}`, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                'content')
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success && data.school_year_id) {
                            const schoolYearId = data.school_year_id;
                            const classId = currentClass !== 'all' ? currentClass : null;

                            // Build URL for gender data
                            let url = `/teacher/gender-data?school_year_id=${schoolYearId}`;
                            if (classId) {
                                url += `&class_id=${classId}`;
                            }

                            // Fetch gender data
                            return fetch(url, {
                                headers: {
                                    'X-Requested-With': 'XMLHttpRequest',
                                    'Accept': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                        .getAttribute('content')
                                }
                            });
                        } else {
                            throw new Error('Could not get school year ID');
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        updateGenderCardDisplay(data);
                    })
                    .catch(error => {
                        console.error('Error fetching gender statistics:', error);
                        // Fallback to displayed students
                        calculateDisplayedGenderStatistics();
                    });
            }

            function fetchStudentTypeStatistics() {
                // Get the school year ID for the selected school year
                const selectedSchoolYearText = dashboardSchoolYear.value;

                // First, get the school year ID
                fetch(`/teacher/get-school-year-id?school_year=${encodeURIComponent(selectedSchoolYearText)}`, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                'content')
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success && data.school_year_id) {
                            const schoolYearId = data.school_year_id;
                            const classId = currentClass !== 'all' ? currentClass : null;

                            // Build URL for student type data
                            let url = `/teacher/student-type-data?school_year_id=${schoolYearId}`;
                            if (classId) {
                                url += `&class_id=${classId}`;
                            }

                            // Fetch student type data
                            return fetch(url, {
                                headers: {
                                    'X-Requested-With': 'XMLHttpRequest',
                                    'Accept': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                        .getAttribute('content')
                                }
                            });
                        } else {
                            throw new Error('Could not get school year ID');
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        updateStudentTypeCardDisplay(data);
                    })
                    .catch(error => {
                        console.error('Error fetching student type statistics:', error);
                        // Fallback to displayed students
                        calculateDisplayedStudentTypeStatistics();
                    });
            }

            function updateGenderCardDisplay(stats) {
                // Update gender card display with server data
                const total = stats.total || 0;
                const femaleCount = stats.female_count || 0;
                const maleCount = stats.male_count || 0;
                const femalePercentage = stats.female_percentage || 0;
                const malePercentage = stats.male_percentage || 0;

                genderTotalStudents.textContent = total;
                femaleCountElement.textContent = `${femaleCount} Students`;
                maleCountElement.textContent = `${maleCount} Students`;
                femalePercentageElement.textContent = `${femalePercentage}%`;
                malePercentageElement.textContent = `${malePercentage}%`;

                // Update filter text
                let filterText = '';
                if (currentClass !== 'all') {
                    const selectedClass = dashboardClassFilter.options[dashboardClassFilter.selectedIndex];
                    filterText = `Class: ${selectedClass.text}`;
                }
                genderFilterText.textContent = filterText ? `(${filterText})` : '';

                // Update chart
                if (window.genderChart) {
                    window.genderChart.updateSeries([femalePercentage, malePercentage]);
                }
            }

            function updateStudentTypeCardDisplay(stats) {
                // Update student type card display with server data
                const total = stats.total || 0;
                const regularCount = stats.regular_count || 0;
                const returneeCount = stats.returnee_count || 0;
                const transfereeCount = stats.transferee_count || 0;
                const regularPercentage = stats.regular_percentage || 0;
                const returneePercentage = stats.returnee_percentage || 0;
                const transfereePercentage = stats.transferee_percentage || 0;

                studentTypeTotal.textContent = total;
                regularCountText.textContent = `${regularCount} Students`;
                returneeCountText.textContent = `${returneeCount} Students`;
                transfereeCountText.textContent = `${transfereeCount} Students`;
                regularPercentageText.textContent = `${regularPercentage}%`;
                returneePercentageText.textContent = `${returneePercentage}%`;
                transfereePercentageText.textContent = `${transfereePercentage}%`;

                // Update filter text
                let filterText = '';
                if (currentClass !== 'all') {
                    const selectedClass = dashboardClassFilter.options[dashboardClassFilter.selectedIndex];
                    filterText = `Class: ${selectedClass.text}`;
                }
                studentTypeFilterText.textContent = filterText ? `(${filterText})` : '';

                // Update chart
                if (window.studentTypeChart) {
                    window.studentTypeChart.updateSeries([regularPercentage, returneePercentage,
                        transfereePercentage
                    ]);
                }
            }

            function calculateDisplayedGenderStatistics() {
                const rows = document.querySelectorAll('#studentsTableBody tr.dashboard-student-row');
                let total = rows.length;
                let femaleCount = 0;
                let maleCount = 0;

                rows.forEach(row => {
                    const isFemale = row.getAttribute('data-is-female') === '1';
                    const isMale = row.getAttribute('data-is-male') === '1';

                    if (isFemale) femaleCount++;
                    if (isMale) maleCount++;
                });

                const femalePercentage = total > 0 ? Math.round((femaleCount / total) * 100) : 0;
                const malePercentage = total > 0 ? Math.round((maleCount / total) * 100) : 0;

                // This is only for the table display, not the main cards
                console.log(`Displayed Gender Stats - Total: ${total}, Female: ${femaleCount}, Male: ${maleCount}`);
            }

            function calculateDisplayedStudentTypeStatistics() {
                const rows = document.querySelectorAll('#studentsTableBody tr.dashboard-student-row');
                let total = rows.length;
                let regularCount = 0;
                let returneeCount = 0;
                let transfereeCount = 0;

                rows.forEach(row => {
                    const enrollmentType = row.getAttribute('data-enrollment-type');
                    if (enrollmentType === 'regular') regularCount++;
                    if (enrollmentType === 'returnee') returneeCount++;
                    if (enrollmentType === 'transferee') transfereeCount++;
                });

                // This is only for the table display, not the main cards
                console.log(
                    `Displayed Student Type Stats - Total: ${total}, Regular: ${regularCount}, Returnee: ${returneeCount}, Transferee: ${transfereeCount}`
                );
            }

            function getStatusBadgeClass(status) {
                switch (status.toLowerCase()) {
                    case 'enrolled':
                        return 'bg-label-success';
                    case 'not_enrolled':
                        return 'bg-label-secondary';
                    case 'archived':
                        return 'bg-label-warning';
                    case 'graduated':
                        return 'bg-label-info';
                    default:
                        return 'bg-label-dark';
                }
            }

            function getTypeBadgeClass(type) {
                switch (type.toLowerCase()) {
                    case 'regular':
                        return 'bg-label-primary';
                    case 'transferee':
                        return 'bg-label-info';
                    case 'returnee':
                        return 'bg-label-warning';
                    default:
                        return 'bg-label-dark';
                }
            }

            // Initial load of stats from server
            updateGenderRatioCard(true);
            updateStudentTypeCard(true);
        });
    </script>

    <!-- Chart Initialization Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Gender Chart
            const genderChartElement = document.getElementById('genderStatisticsChart');
            if (genderChartElement) {
                // Store initial data for tooltip formatting
                window.genderChartData = {
                    femaleCount: {{ $genderStats['female_count'] ?? 0 }},
                    maleCount: {{ $genderStats['male_count'] ?? 0 }},
                    femalePercentage: {{ $genderStats['female_percentage'] ?? 0 }},
                    malePercentage: {{ $genderStats['male_percentage'] ?? 0 }}
                };

                window.genderChart = new ApexCharts(genderChartElement, {
                    chart: {
                        type: 'donut',
                        height: 165,
                        width: 130
                    },
                    series: [{{ $genderStats['female_percentage'] ?? 0 }},
                        {{ $genderStats['male_percentage'] ?? 0 }}
                    ],
                    labels: ['Female', 'Male'],
                    colors: ['#FF5B5B', '#2AD3E6'],
                    plotOptions: {
                        pie: {
                            donut: {
                                size: '75%',
                                labels: {
                                    show: true,
                                    total: {
                                        show: true,
                                        label: 'Gender Ratio',
                                        fontSize: '0.8125rem',
                                        color: '#aaa',
                                        formatter: function(w) {
                                            return '0%'
                                        }
                                    }
                                }
                            }
                        }
                    },
                    tooltip: {
                        y: {
                            formatter: function(value, {
                                seriesIndex
                            }) {
                                if (seriesIndex === 0) {
                                    return window.genderChartData.femaleCount + ' students';
                                } else {
                                    return window.genderChartData.maleCount + ' students';
                                }
                            }
                        }
                    },
                    legend: {
                        show: false
                    },
                    dataLabels: {
                        enabled: false
                    }
                });
                window.genderChart.render();
            }

            // Initialize Student Type Chart
            const studentTypeChartElement = document.getElementById('studentTypeChart');
            if (studentTypeChartElement) {
                // Store initial data for tooltip formatting
                window.studentTypeChartData = {
                    regularCount: {{ $studentTypeStats['regular_count'] ?? 0 }},
                    returneeCount: {{ $studentTypeStats['returnee_count'] ?? 0 }},
                    transfereeCount: {{ $studentTypeStats['transferee_count'] ?? 0 }},
                    regularPercentage: {{ $studentTypeStats['regular_percentage'] ?? 0 }},
                    returneePercentage: {{ $studentTypeStats['returnee_percentage'] ?? 0 }},
                    transfereePercentage: {{ $studentTypeStats['transferee_percentage'] ?? 0 }}
                };

                window.studentTypeChart = new ApexCharts(studentTypeChartElement, {
                    chart: {
                        type: 'donut',
                        height: 165,
                        width: 130
                    },
                    series: [{{ $studentTypeStats['regular_percentage'] ?? 0 }},
                        {{ $studentTypeStats['returnee_percentage'] ?? 0 }},
                        {{ $studentTypeStats['transferee_percentage'] ?? 0 }}
                    ],
                    labels: ['Regular', 'Returnee', 'Transferee'],
                    colors: ['#696CFF', '#FFAB00', '#03C3EC'],
                    plotOptions: {
                        pie: {
                            donut: {
                                size: '75%',
                                labels: {
                                    show: true,
                                    total: {
                                        show: true,
                                        label: 'Student Type',
                                        fontSize: '0.8125rem',
                                        color: '#aaa',
                                        formatter: function(w) {
                                            return '0%'
                                        }
                                    }
                                }
                            }
                        }
                    },
                    tooltip: {
                        y: {
                            formatter: function(value, {
                                seriesIndex
                            }) {
                                if (seriesIndex === 0) {
                                    return window.studentTypeChartData.regularCount + ' students';
                                } else if (seriesIndex === 1) {
                                    return window.studentTypeChartData.returneeCount + ' students';
                                } else {
                                    return window.studentTypeChartData.transfereeCount + ' students';
                                }
                            }
                        }
                    },
                    legend: {
                        show: false
                    },
                    dataLabels: {
                        enabled: false
                    }
                });
                window.studentTypeChart.render();
            }
        });
    </script>

    <!-- Total Enrollees and Gender Chart Script (same as admin) -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Global variables to store chart instances and shared state
        let enrolleesChartInstance = null;
        let genderChartInstance = null;
        let currentSchoolYearId = {{ $schoolYearId ?? 'null' }};
        let currentSchoolYearText = '{{ $schoolYearText }}';
        let currentGenderGradeLevel = '';
        let currentGenderSection = '';

        // Initialize Enrollees Chart with smooth transitions
        function initializeEnrolleesChart(enrollmentData, gradeLabels) {
            const ctx1 = document.getElementById('enrolleesChart').getContext('2d');

            // Add loading state to chart container
            const chartContainer = document.getElementById('enrolleesChart').parentElement;
            chartContainer.classList.add('chart-loading');

            // Destroy existing chart if it exists
            if (enrolleesChartInstance) {
                enrolleesChartInstance.destroy();
            }

            // Small delay to ensure smooth transition
            setTimeout(() => {
                enrolleesChartInstance = new Chart(ctx1, {
                    type: 'bar',
                    data: {
                        labels: gradeLabels,
                        datasets: [{
                            label: 'Enrollees',
                            data: enrollmentData,
                            backgroundColor: [
                                '#F4F754', '#8CE4FF', '#FF2DD1', '#E62727',
                                '#687FE5', '#C9A5FF', '#FFA239'
                            ],
                            borderRadius: 8,
                            borderWidth: 0
                        }]
                    },
                    options: {
                        responsive: true,
                        animation: {
                            duration: 1000,
                            easing: 'easeOutQuart',
                            animateScale: true,
                            animateRotate: true
                        },
                        transitions: {
                            show: {
                                animations: {
                                    x: {
                                        from: 0
                                    },
                                    y: {
                                        from: 0
                                    }
                                }
                            },
                            hide: {
                                animations: {
                                    x: {
                                        to: 0
                                    },
                                    y: {
                                        to: 0
                                    }
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    stepSize: 10
                                },
                                grid: {
                                    color: 'rgba(0, 0, 0, 0.1)',
                                }
                            },
                            x: {
                                grid: {
                                    display: false
                                }
                            }
                        },
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                                titleColor: '#fff',
                                bodyColor: '#fff',
                                borderColor: 'rgba(255, 255, 255, 0.1)',
                                borderWidth: 1,
                                cornerRadius: 8,
                                animation: {
                                    duration: 300
                                }
                            }
                        }
                    }
                });

                // Remove loading state after chart is rendered
                setTimeout(() => {
                    chartContainer.classList.remove('chart-loading');
                    chartContainer.classList.add('chart-loaded');
                }, 500);

            }, 100);
        }

        // Initialize Gender Chart with dynamic total display
        function initializeGenderChart(femalePercentage, malePercentage, femaleCount, maleCount) {
            const chartGenderStatistics = document.querySelector('#genderStatisticsChart');

            // Add loading state
            chartGenderStatistics.classList.add('chart-loading');

            // Clear existing chart
            if (chartGenderStatistics) {
                chartGenderStatistics.innerHTML = '';
            }

            // Calculate total students
            const totalStudents = femaleCount + maleCount;

            // Small delay for smooth transition
            setTimeout(() => {
                const genderChartConfig = {
                    chart: {
                        height: 165,
                        width: 130,
                        type: 'donut',
                        animations: {
                            enabled: true,
                            easing: 'easeout',
                            speed: 800,
                            animateGradually: {
                                enabled: true,
                                delay: 150
                            },
                            dynamicAnimation: {
                                enabled: true,
                                speed: 350
                            }
                        }
                    },
                    labels: ['Female', 'Male'],
                    series: [femalePercentage, malePercentage],
                    colors: ['#FF5B5B', '#2AD3E6'],
                    stroke: {
                        width: 5,
                        colors: '#fff'
                    },
                    dataLabels: {
                        enabled: false
                    },
                    legend: {
                        show: false
                    },
                    grid: {
                        padding: {
                            top: 0,
                            bottom: 0,
                            right: 15
                        }
                    },
                    tooltip: {
                        enabled: true,
                        y: {
                            formatter: function(value, {
                                seriesIndex
                            }) {
                                if (seriesIndex === 0) {
                                    return femaleCount + ' Students';
                                } else {
                                    return maleCount + ' Students';
                                }
                            },
                            title: {
                                formatter: function(seriesName) {
                                    return seriesName;
                                }
                            }
                        }
                    },
                    plotOptions: {
                        pie: {
                            donut: {
                                size: '75%',
                                labels: {
                                    show: true,
                                    value: {
                                        fontSize: '1.5rem',
                                        fontFamily: 'Public Sans',
                                        color: '#333',
                                        offsetY: -15,
                                        formatter: function(val) {
                                            return parseInt(val) + '%';
                                        }
                                    },
                                    name: {
                                        offsetY: 20,
                                        fontFamily: 'Public Sans'
                                    },
                                    total: {
                                        show: true,
                                        fontSize: '0.8125rem',
                                        color: '#aaa',
                                        label: 'Gender Ratio',
                                        formatter: function(w) {
                                            // Calculate actual total percentage (should be 100% unless there are no students)
                                            const totalPercentage = totalStudents > 0 ?
                                                Math.round(femalePercentage + malePercentage) : 0;
                                            return totalPercentage + '%';
                                        }
                                    }
                                }
                            }
                        }
                    }
                };

                if (chartGenderStatistics) {
                    genderChartInstance = new ApexCharts(chartGenderStatistics, genderChartConfig);
                    genderChartInstance.render();

                    // Remove loading state
                    setTimeout(() => {
                        chartGenderStatistics.classList.remove('chart-loading');
                        chartGenderStatistics.classList.add('chart-loaded');
                    }, 500);
                }
            }, 100);
        }

        // Function to load sections based on grade level and school year
        function loadSections(gradeLevel, schoolYearId) {
            const sectionFilter = document.getElementById('sectionFilter');

            if (!gradeLevel || !schoolYearId) {
                sectionFilter.innerHTML = '<option value="">All Sections</option>';
                sectionFilter.disabled = true;
                return;
            }

            sectionFilter.disabled = true;
            sectionFilter.innerHTML = '<option value="">Loading sections...</option>';

            fetch(`/teacher/dashboard/grade-sections?school_year_id=${schoolYearId}&grade_level=${gradeLevel}`)
                .then(response => response.json())
                .then(sections => {
                    let options = '<option value="">All Sections</option>';
                    sections.forEach(section => {
                        options += `<option value="${section}">${section}</option>`;
                    });
                    sectionFilter.innerHTML = options;
                    sectionFilter.disabled = false;

                    // Restore previous section selection if available
                    if (currentGenderSection && sections.includes(currentGenderSection)) {
                        sectionFilter.value = currentGenderSection;
                    }
                })
                .catch(error => {
                    console.error('Error loading sections:', error);
                    sectionFilter.innerHTML = '<option value="">All Sections</option>';
                    sectionFilter.disabled = false;
                });
        }

        // Function to update gender chart with filters
        function updateGenderChartWithFilters(schoolYearId, gradeLevel = '', section = '') {
            const chartContainer = document.querySelector('#genderStatisticsChart');
            if (chartContainer) {
                chartContainer.classList.add('chart-loading');
            }

            // Build query parameters
            let url = `/teacher/dashboard/gender-data-filtered?school_year_id=${schoolYearId}`;
            if (gradeLevel) url += `&grade_level=${gradeLevel}`;
            if (section) url += `&section=${section}`;

            fetch(url)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    initializeGenderChart(
                        data.female_percentage,
                        data.male_percentage,
                        data.female_count,
                        data.male_count
                    );
                    updateGenderUI(data);
                    updateFilterText(gradeLevel, section);
                })
                .catch(error => {
                    console.error('Error fetching filtered gender data:', error);
                    if (chartContainer) {
                        chartContainer.classList.remove('chart-loading');
                    }
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Failed to load gender data for the selected filters.',
                    });
                });
        }

        // Function to update filter display text
        function updateFilterText(gradeLevel, section) {
            const filterText = document.getElementById('genderFilterText');
            let textParts = [];

            if (gradeLevel) {
                const gradeDisplay = gradeLevel === 'kindergarten' ? 'Kindergarten' :
                    gradeLevel.replace('grade', 'Grade ');
                textParts.push(gradeDisplay);
            }

            if (section) {
                textParts.push(`Section ${section}`);
            }

            if (textParts.length > 0) {
                filterText.textContent = textParts.join(' - ');
                filterText.style.display = 'block';
            } else {
                filterText.textContent = '';
                filterText.style.display = 'none';
            }
        }

        // Update UI with gender data
        function updateGenderUI(genderData) {
            const elementsToUpdate = [{
                    selector: '#genderTotalStudents',
                    value: genderData.total || 0,
                    transition: 'count-up'
                },
                {
                    selector: '#femaleCount',
                    value: (genderData.female_count || 0) + ' Students',
                    transition: 'fade'
                },
                {
                    selector: '#femalePercentage',
                    value: (genderData.female_percentage || 0) + '%',
                    transition: 'fade'
                },
                {
                    selector: '#maleCount',
                    value: (genderData.male_count || 0) + ' Students',
                    transition: 'fade'
                },
                {
                    selector: '#malePercentage',
                    value: (genderData.male_percentage || 0) + '%',
                    transition: 'fade'
                }
            ];

            elementsToUpdate.forEach(item => {
                const element = document.querySelector(item.selector);
                if (element) {
                    if (item.transition === 'fade') {
                        element.style.opacity = '0.5';
                        element.textContent = item.value;
                        setTimeout(() => {
                            element.style.opacity = '1';
                        }, 300);
                    } else if (item.transition === 'count-up') {
                        const targetValue = parseInt(item.value);
                        const currentValue = parseInt(element.textContent) || 0;
                        animateCount(element, currentValue, targetValue, 800);
                    } else {
                        element.textContent = item.value;
                    }
                }
            });
        }

        // Animate number counting
        function animateCount(element, start, end, duration) {
            const range = end - start;
            const startTime = performance.now();

            function updateCount(currentTime) {
                const elapsed = currentTime - startTime;
                const progress = Math.min(elapsed / duration, 1);

                // Easing function for smooth animation
                const easeOutQuart = 1 - Math.pow(1 - progress, 4);
                const currentValue = Math.floor(start + (range * easeOutQuart));

                element.textContent = currentValue;

                if (progress < 1) {
                    requestAnimationFrame(updateCount);
                } else {
                    element.textContent = end;
                }
            }

            requestAnimationFrame(updateCount);
        }

        // Update chart titles with school year
        function updateChartTitles(schoolYearText) {
            const elementsToUpdate = [{
                    selector: '.card-body .card-title.m-0.d-sm-block.d-none',
                    text: 'Total Enrollees for School Year ' + schoolYearText
                },
                {
                    selector: '.card-body .card-title.m-0.d-sm-none.d-block',
                    text: 'Total Enrollees for School Year ' + schoolYearText
                },
                {
                    selector: '#genderSchoolYearText',
                    text: 'for SY: ' + schoolYearText
                },
                {
                    selector: '.school-year-display',
                    text: schoolYearText
                },
                {
                    selector: '.current-school-year',
                    text: schoolYearText
                }
            ];

            elementsToUpdate.forEach(item => {
                const element = document.querySelector(item.selector);
                if (element) {
                    element.style.opacity = '0.7';
                    element.textContent = item.text;
                    setTimeout(() => {
                        element.style.opacity = '1';
                    }, 300);
                }
            });
        }

        // Enhanced filter functions with loading states
        function updateEnrolleesChart(schoolYearId) {
            const chartContainer = document.getElementById('enrolleesChart').parentElement;
            chartContainer.classList.add('chart-loading');

            fetch(`/teacher/dashboard/enrollment-data?school_year_id=${schoolYearId}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    initializeEnrolleesChart(data.enrollment_data, data.grade_labels);
                })
                .catch(error => {
                    console.error('Error fetching enrollment data:', error);
                    chartContainer.classList.remove('chart-loading');
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Failed to load enrollment data for the selected school year.',
                    });
                });
        }

        // Synchronized school year change function
        function changeSchoolYear(schoolYearId, schoolYearText, source) {
            // Update global state
            currentSchoolYearId = schoolYearId;
            currentSchoolYearText = schoolYearText;

            // Update both dropdowns
            updateDropdownText('yearDropdown', schoolYearText);
            updateDropdownText('genderYearDropdown', schoolYearText);

            // Update chart titles
            updateChartTitles(schoolYearText);

            // Update enrollees chart
            updateEnrolleesChart(schoolYearId);

            // Update gender chart with current filters
            updateGenderChartWithFilters(schoolYearId, currentGenderGradeLevel, currentGenderSection);

            // Reload sections if grade level is selected
            if (currentGenderGradeLevel) {
                loadSections(currentGenderGradeLevel, schoolYearId);
            } else {
                // Reset section filter
                document.getElementById('sectionFilter').innerHTML = '<option value="">All Sections</option>';
                document.getElementById('sectionFilter').disabled = true;
            }
        }

        function updateDropdownText(dropdownId, text) {
            const dropdown = document.getElementById(dropdownId);
            if (dropdown) {
                dropdown.style.opacity = '0.7';
                dropdown.textContent = text;
                setTimeout(() => {
                    dropdown.style.opacity = '1';
                }, 300);
            }
        }

        // AJAX for school year filtering with synchronized behavior
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize charts with current data
            const initialEnrollmentData = @json($enrollmentByGrade);
            const initialGradeLabels = ['Kindergarten', 'Grade 1', 'Grade 2', 'Grade 3', 'Grade 4', 'Grade 5',
                'Grade 6'
            ];

            initializeEnrolleesChart(initialEnrollmentData, initialGradeLabels);

            const initialFemalePercentage = {{ $genderStats['female_percentage'] ?? 0 }};
            const initialMalePercentage = {{ $genderStats['male_percentage'] ?? 0 }};
            const initialFemaleCount = {{ $genderStats['female_count'] ?? 0 }};
            const initialMaleCount = {{ $genderStats['male_count'] ?? 0 }};

            initializeGenderChart(initialFemalePercentage, initialMalePercentage, initialFemaleCount,
                initialMaleCount);

            // Event listeners for synchronized school year filters
            document.querySelectorAll('.school-year-filter').forEach(item => {
                item.addEventListener('click', function(e) {
                    e.preventDefault();
                    const schoolYearId = this.getAttribute('data-year');
                    const schoolYearText = this.textContent.trim();

                    changeSchoolYear(schoolYearId, schoolYearText, 'enrollees');
                });
            });

            document.querySelectorAll('.gender-year-filter').forEach(item => {
                item.addEventListener('click', function(e) {
                    e.preventDefault();
                    const schoolYearId = this.getAttribute('data-year');
                    const schoolYearText = this.textContent.trim();

                    changeSchoolYear(schoolYearId, schoolYearText, 'gender');
                });
            });

            // Event listeners for grade level and section filters
            document.getElementById('gradeLevelFilter').addEventListener('change', function() {
                currentGenderGradeLevel = this.value;

                // Reset section filter when grade level changes
                document.getElementById('sectionFilter').value = '';
                currentGenderSection = '';

                if (currentGenderGradeLevel && currentSchoolYearId) {
                    loadSections(currentGenderGradeLevel, currentSchoolYearId);
                } else {
                    document.getElementById('sectionFilter').disabled = true;
                    document.getElementById('sectionFilter').innerHTML =
                        '<option value="">All Sections</option>';
                }

                updateGenderChartWithFilters(currentSchoolYearId, currentGenderGradeLevel,
                    currentGenderSection);
            });

            document.getElementById('sectionFilter').addEventListener('change', function() {
                currentGenderSection = this.value;
                updateGenderChartWithFilters(currentSchoolYearId, currentGenderGradeLevel,
                    currentGenderSection);
            });

            // Initialize with current school year
            if (currentSchoolYearId) {
                updateGenderChartWithFilters(currentSchoolYearId);
            }
        });
    </script>

    <!-- QR Scanner Grace Period Script -->
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
    <style>
        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .card-hover {
            transition: all 0.3s ease;
        }

        /* Chart loading styles */
        .chart-loading {
            position: relative;
            opacity: 0.7;
        }

        .chart-loading::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 30px;
            height: 30px;
            border: 3px solid #f3f3f3;
            border-top: 3px solid #3498db;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            z-index: 10;
        }

        .chart-loaded {
            animation: fadeInUp 0.8s ease-out;
        }

        @keyframes spin {
            0% {
                transform: translate(-50%, -50%) rotate(0deg);
            }

            100% {
                transform: translate(-50%, -50%) rotate(360deg);
            }
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
@endpush
