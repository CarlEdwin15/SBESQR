@extends('./layouts.main')

@section('title', 'Admin | Dashboard')

@section('content')

    <!-- Menu -->
    <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
        <div class="app-brand bg-dark">
            <a href="{{ url('/home') }}" class="app-brand-link">
                <img src="{{ asset('assets/img/logo.png') }}" alt="Logo" class="app-brand-logo">
                <span class="app-brand-text menu-text fw-bolder text-white" style="padding: 9px">ADMIN
                    Dashboard</span>
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

            <!-- Teachers sidebar -->
            <li class="menu-item">
                <a href="javascript:void(0);" class="menu-link menu-toggle bg-dark text-light">
                    <i class="menu-icon tf-icons bx bx-user-pin text-light"></i>
                    <div class="text-light">Teachers</div>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item">
                        <a href="{{ route('show.teachers') }}" class="menu-link bg-dark text-light">
                            <div class="text-light">Teacher Management</div>
                        </a>
                    </li>
                </ul>
            </li>

            {{-- Students sidebar --}}
            <li class="menu-item">
                <a href="javascript:void(0);" class="menu-link menu-toggle bg-dark text-light">
                    <i class="menu-icon tf-icons bx bxs-graduation text-light"></i>
                    <div class="text-light">Students</div>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item">
                        <a href="{{ route('student.management') }}" class="menu-link bg-dark text-light">
                            <div class="text-light">Student Management</div>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="{{ route('show.students') }}" class="menu-link bg-dark text-light">
                            <div class="text-light">Student Enrollment</div>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="{{ route('students.promote.view') }}" class="menu-link bg-dark text-light">
                            <div class="text-light">Re-Enrollment / Promotion</div>
                        </a>
                    </li>
                </ul>
            </li>

            {{-- Classes sidebar --}}
            <li class="menu-item">
                <a href="javascript:void(0);" class="menu-link menu-toggle bg-dark text-light">
                    <i class="menu-icon tf-icons bx bx-objects-horizontal-left text-light"></i>
                    <div class="text-light">Classes</div>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item">
                        <a href="{{ route('all.classes') }}" class="menu-link bg-dark text-light">
                            <div class="text-light">All Classes</div>
                        </a>
                    </li>
                </ul>
            </li>

            {{-- Announcement sidebar --}}
            <li class="menu-item">
                <a href="javascript:void(0);" class="menu-link menu-toggle bg-dark text-light">
                    <i class="menu-icon tf-icons bx bxs-megaphone text-light"></i>
                    <div class="text-light">Announcements</div>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item">
                        <a href="{{ route('announcements.index') }}" class="menu-link bg-dark text-light">
                            <div class="text-light">All Announcements</div>
                        </a>
                    </li>
                </ul>
            </li>

            {{-- Payments sidebar --}}
            <li class="menu-item">
                <a href="javascript:void(0);" class="menu-link menu-toggle bg-dark text-light">
                    <i class="menu-icon tf-icons bx bx-wallet-alt text-light"></i>
                    <div class="text-light">School Fees</div>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item">
                        <a href="{{ route('admin.school-fees.index') }}" class="menu-link bg-dark text-light">
                            <div class="text-light">All School Fees</div>
                        </a>
                    </li>
                </ul>
            </li>

            {{-- User Management sidebar --}}
            <li class="menu-item">
                <a href="{{ route('admin.user.management') }}" class="menu-link bg-dark text-light">
                    <i class='bx bxs-user-account me-3 text-light'></i>
                    <div class="text-light"> User Management</div>
                </a>
            </li>

            {{-- Account Settings sidebar --}}
            <li class="menu-item">
                <a href="{{ route('admin.account.settings') }}" class="menu-link bg-dark text-light">
                    <i class="bx bx-cog me-3 text-light"></i>
                    <div class="text-light"> Account Settings</div>
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
            use Illuminate\Support\Carbon;
            use App\Models\Student;
            use App\Models\User;
            use App\Models\Classes;
            use App\Models\ClassStudent;
            use App\Models\SchoolYear;

            // Get the current school year
            $now = now();
            $currentSchoolYear = SchoolYear::where('start_date', '<=', $now)->where('end_date', '>=', $now)->first();

            $schoolYearId = $currentSchoolYear ? $currentSchoolYear->id : null;
            $schoolYearText = $currentSchoolYear ? $currentSchoolYear->school_year : 'N/A';

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

            // Student Statistics
            $totalStudents = Student::count();

            // Active students (enrolled in current school year)
            $activeStudents = $schoolYearId
                ? ClassStudent::where('school_year_id', $schoolYearId)
                    ->whereIn('enrollment_status', ['enrolled', 'archived'])
                    ->distinct('student_id')
                    ->count('student_id')
                : 0;

            // Total graduated students from ALL school years
            $totalGraduatedStudents = ClassStudent::where('enrollment_status', 'graduated')
                ->distinct('student_id')
                ->count('student_id');

            // Graduated students in current school year only
            $graduatedStudentsCurrentSY = $schoolYearId
                ? ClassStudent::where('school_year_id', $schoolYearId)
                    ->where('enrollment_status', 'graduated')
                    ->distinct('student_id')
                    ->count('student_id')
                : 0;

            // Inactive students (total - active - total graduated)
            $inactiveStudents = $totalStudents - $activeStudents - $totalGraduatedStudents;

            // Teacher Statistics
            $totalTeachers = User::where('role', 'teacher')->count();

            // Active teachers (assigned to classes in current school year)
            $activeTeachers = $schoolYearId
                ? \DB::table('class_user')
                    ->where('school_year_id', $schoolYearId)
                    ->whereIn('user_id', function ($query) {
                        $query->select('id')->from('users')->where('role', 'teacher');
                    })
                    ->distinct('user_id')
                    ->count('user_id')
                : 0;

            // Inactive teachers (total - active)
            $inactiveTeachers = $totalTeachers - $activeTeachers;

            // Total classes
            $totalClasses = Classes::count();

            // Active classes: must have students AND an adviser assigned for the current school year
            $activeClasses = $schoolYearId
                ? Classes::whereHas('students', function ($query) use ($schoolYearId) {
                    $query->where('school_year_id', $schoolYearId);
                })
                    ->whereHas('advisers', function ($query) use ($schoolYearId) {
                        $query->where('class_user.school_year_id', $schoolYearId);
                    })
                    ->count()
                : 0;

            // Inactive classes: total - active
            $inactiveClasses = $totalClasses - $activeClasses;

            // User Statistics
            $totalAdmins = User::where('role', 'admin')->count();
            $totalTeachers = User::where('role', 'teacher')->count();
            $totalParents = User::where('role', 'parent')->count();
            $totalUsers = $totalAdmins + $totalTeachers + $totalParents;

            // Gender Statistics for Current School Year
            $genderStats = [];
            if ($schoolYearId) {
                $genderData = ClassStudent::where('school_year_id', $schoolYearId)
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

            // Enrollment by Grade Level for Current School Year
            $enrollmentByGrade = [];
            if ($schoolYearId) {
                $enrollmentData = ClassStudent::where('class_student.school_year_id', $schoolYearId)
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
        @endphp

        <div class="container-xxl container-p-y">

            <div class="row mb-4 g-3">
                <!-- Student Card -->
                <div class="col-6 col-md-3">
                    <div class="card h-100 card-hover">
                        <a href="{{ route('student.management') }}" class="card-body">

                            <!-- Top row: Image + Title + Total -->
                            <div class="d-flex align-items-center mb-3">

                                <!-- Image Icon -->
                                <div class="me-3">
                                    <img src="{{ asset('assetsDashboard/img/icons/dashIcon/studentIcon.png') }}"
                                        alt="Students" class="rounded" width="50" height="50">
                                </div>

                                <!-- Title & Total -->
                                <div class="d-flex flex-column align-items-center ms-auto">
                                    <h5 class="fw-semibold text-primary mb-2">Students</h5>
                                    <h1 class="mb-0 fw-semibold">{{ $totalStudents }}</h1>
                                </div>

                            </div>


                            <!-- Stats: Active / Inactive / Graduated -->
                            <div class="d-flex flex-column">
                                <div class="d-flex justify-content-between">
                                    <span class="d-flex align-items-center text-success">
                                        <i class="bx bx-user-check me-1"></i> Active:
                                    </span>
                                    <span class="text-success">{{ $activeStudents }}</span>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span class="d-flex align-items-center text-secondary">
                                        <i class="bx bx-user-x me-1"></i> Inactive:
                                    </span>
                                    <span class="text-secondary">{{ $inactiveStudents }}</span>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span class="d-flex align-items-center text-info">
                                        <i class="bx bxs-graduation me-1"></i> Graduated:
                                    </span>
                                    <span class="text-info">{{ $totalGraduatedStudents }}</span>
                                </div>
                            </div>

                        </a>
                    </div>
                </div>

                <!-- Teachers Card -->
                <div class="col-6 col-md-3">
                    <div class="card h-100 card-hover">
                        <a href="{{ route('show.teachers') }}" class="card-body">

                            <!-- Top row: Image + Title + Total -->
                            <div class="d-flex align-items-center mb-3">

                                <!-- Image Icon -->
                                <div class="me-3">
                                    <img src="{{ asset('assetsDashboard/img/icons/dashIcon/teacherIcon.png') }}"
                                        alt="Teachers" class="rounded" width="50" height="50">
                                </div>

                                <!-- Title & Total -->
                                <div class="d-flex flex-column align-items-center ms-auto">
                                    <h5 class="fw-semibold text-primary mb-2">Teachers</h5>
                                    <h1 class="mb-0 fw-semibold">{{ $totalTeachers }}</h1>
                                </div>

                            </div>

                            <!-- Stats: Active / Inactive -->
                            <div class="d-flex flex-column">
                                <div class="d-flex justify-content-between">
                                    <span class="d-flex align-items-center text-success">
                                        <i class="bx bx-user-check me-1"></i> Active:
                                    </span>
                                    <span class="text-success">{{ $activeTeachers }}</span>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span class="d-flex align-items-center text-secondary">
                                        <i class="bx bx-user-x me-1"></i> Inactive:
                                    </span>
                                    <span class="text-secondary">{{ $inactiveTeachers }}</span>
                                </div>
                            </div>

                        </a>
                    </div>
                </div>

                <!-- Classes Card -->
                <div class="col-6 col-md-3">
                    <div class="card h-100 card-hover">
                        <a href="{{ route('all.classes') }}" class="card-body">

                            <!-- Top row: Image + Title + Total -->
                            <div class="d-flex align-items-center mb-3">

                                <!-- Image Icon -->
                                <div class="me-3">
                                    <img src="{{ asset('assetsDashboard/img/icons/dashIcon/classroomIcon.png') }}"
                                        alt="Classes" class="rounded" width="50" height="50">
                                </div>

                                <!-- Title & Total -->
                                <div class="d-flex flex-column align-items-center ms-auto">
                                    <h5 class="fw-semibold text-primary mb-2">Classes</h5>
                                    <h1 class="mb-0 fw-semibold">{{ $totalClasses }}</h1>
                                </div>

                            </div>

                            <!-- Stats: Active / Inactive -->
                            <div class="d-flex flex-column">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="d-flex align-items-center text-success">
                                        <i class="bx bx-check-square me-1"></i> Active:
                                    </span>
                                    <span class="text-success">{{ $activeClasses }}</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="d-flex align-items-center text-secondary">
                                        <i class="bx bx-x-circle me-1"></i> Inactive:
                                    </span>
                                    <span class="text-secondary">{{ $inactiveClasses }}</span>
                                </div>
                            </div>

                        </a>
                    </div>
                </div>

                <!-- Users Card -->
                <div class="col-6 col-md-3">
                    <div class="card h-100 card-hover">
                        <a href="{{ route('admin.user.management') }}" class="card-body">

                            <!-- Top row: Image + Title + Total -->
                            <div class="d-flex align-items-center mb-3">

                                <!-- Image Icon -->
                                <div class="me-3">
                                    <img src="{{ asset('assetsDashboard/img/icons/dashIcon/total_users.png') }}"
                                        alt="Users" class="rounded" width="50" height="50">
                                </div>

                                <!-- Title & Total -->
                                <div class="d-flex flex-column align-items-center ms-auto">
                                    <h5 class="fw-semibold text-primary mb-2">Users</h5>
                                    <h1 class="mb-0 fw-semibold">{{ $totalUsers }}</h1>
                                </div>

                            </div>

                            <!-- Stats: Admin / Teacher / Parent -->
                            <div class="d-flex flex-column">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <span class="d-flex align-items-center text-info">
                                        <i class="bx bx-cog me-1"></i> Admin:
                                    </span>
                                    <span class="text-info">{{ $totalAdmins }}</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <span class="d-flex align-items-center text-primary">
                                        <i class="bx bx-book-reader me-1"></i> Teacher:
                                    </span>
                                    <span class="text-primary">{{ $totalTeachers }}</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="d-flex align-items-center text-warning">
                                        <i class="bx bx-home me-1"></i> Parent:
                                    </span>
                                    <span class="text-warning">{{ $totalParents }}</span>
                                </div>
                            </div>

                        </a>
                    </div>
                </div>

                {{-- <!-- School Fees Card -->
                <div class="col-6 col-md-3">
                    <div class="card h-100 card-hover">
                        <a href="{{ route('admin.user.management') }}" class="card-body">

                            <!-- Top row: Image + Title + Total -->
                            <div class="d-flex align-items-center mb-3">

                                <!-- Image Icon -->
                                <div class="me-3">
                                    <img src="{{ asset('assetsDashboard/img/icons/dashIcon/school-fee.png') }}"
                                        alt="School Fees" class="rounded" width="50" height="50">
                                </div>

                                <!-- Title & Total -->
                                <div class="d-flex flex-column align-items-center ms-auto">
                                    <h5 class="fw-semibold text-primary mb-1">School Fees</h5>
                                    <h1 class="mb-0">{{ $totalUsers }}</h1>
                                </div>

                            </div>

                            <!-- Stats: Admin / Teacher / Parent -->
                            <div class="d-flex flex-column">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <span class="d-flex align-items-center text-info">
                                        <i class="bx bx-cog me-1"></i> Admin:
                                    </span>
                                    <span class="text-info">{{ $totalAdmins }}</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <span class="d-flex align-items-center text-primary">
                                        <i class="bx bx-book-reader me-1"></i> Teacher:
                                    </span>
                                    <span class="text-primary">{{ $totalTeachers }}</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="d-flex align-items-center text-warning">
                                        <i class="bx bx-home me-1"></i> Parent:
                                    </span>
                                    <span class="text-warning">{{ $totalParents }}</span>
                                </div>
                            </div>

                        </a>
                    </div>
                </div> --}}

                {{-- <!-- Announcements Card -->
                <div class="col-6 col-md-3">
                    <div class="card h-100 card-hover">
                        <a href="{{ route('admin.user.management') }}" class="card-body">

                            <!-- Top row: Image + Title + Total -->
                            <div class="d-flex align-items-center mb-3">

                                <!-- Image Icon -->
                                <div class="me-3">
                                    <img src="{{ asset('assetsDashboard/img/icons/dashIcon/announcement.png') }}"
                                        alt="Announcements" class="rounded" width="50" height="50">
                                </div>

                                <!-- Title & Total -->
                                <div class="d-flex flex-column align-items-center ms-auto">
                                    <h5 class="fw-semibold text-primary mb-1">Announcements</h5>
                                    <h1 class="mb-0">{{ $totalUsers }}</h1>
                                </div>

                            </div>

                            <!-- Stats: Admin / Teacher / Parent -->
                            <div class="d-flex flex-column">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <span class="d-flex align-items-center text-info">
                                        <i class="bx bx-cog me-1"></i> Admin:
                                    </span>
                                    <span class="text-info">{{ $totalAdmins }}</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <span class="d-flex align-items-center text-primary">
                                        <i class="bx bx-book-reader me-1"></i> Teacher:
                                    </span>
                                    <span class="text-primary">{{ $totalTeachers }}</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="d-flex align-items-center text-warning">
                                        <i class="bx bx-home me-1"></i> Parent:
                                    </span>
                                    <span class="text-warning">{{ $totalParents }}</span>
                                </div>
                            </div>

                        </a>
                    </div>
                </div> --}}
            </div>

            <!-- Enrollees and Gender Chart Section (Compact) -->
            <div class="row">
                <!-- Total Enrollees Chart -->
                <div class="col-md-7 col-lg-7 mb-3">
                    <div class="card h-100">
                        <div class="card-body p-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h6 class="card-title m-0">Total enrollees for School Year {{ $schoolYearText }}</h6>
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
                <div class="col-md-6 col-lg-4 col-xl-4 order-0 mb-4">
                    <div class="card h-100">
                        <div class="card-header d-flex justify-content-between align-items-center mb-2">
                            <div class="card-title mb-0">
                                <h5 class="m-0 me-2">Student Gender Ratio</h5>
                                <small class="text-muted">Total: {{ $genderStats['total'] ?? 0 }} Students</small>
                            </div>
                            {{-- <div class="dropdown">
                                <button class="btn btn-sm btn-info text-white dropdown-toggle" type="button"
                                    id="genderYearDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                    {{ $schoolYearText }}
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="genderYearDropdown">
                                    @foreach ($schoolYears as $sy)
                                        <li>
                                            <a class="dropdown-item gender-year-filter" href="#"
                                                data-year="{{ $sy->id }}">
                                                {{ $sy->school_year }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div> --}}
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div class="d-flex flex-column align-items-center gap-1">
                                    <h2 class="mb-2">{{ $genderStats['total'] ?? 0 }}</h2>
                                    <span>Total Students</span>
                                    <span>for SY: {{ $schoolYearText }}</span>
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
                                            <small class="text-muted">{{ $genderStats['female_count'] ?? 0 }}
                                                Students</small>
                                        </div>
                                        <div class="user-progress">
                                            <small
                                                class="fw-semibold">{{ $genderStats['female_percentage'] ?? 0 }}%</small>
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
                                            <small class="text-muted">{{ $genderStats['male_count'] ?? 0 }}
                                                Students</small>
                                        </div>
                                        <div class="user-progress">
                                            <small class="fw-semibold">{{ $genderStats['male_percentage'] ?? 0 }}%</small>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <!-- Gender Distribution Card -->
            </div>
            <!-- / Enrollees and Gender Chart Section (Compact) -->

        </div>
        <!-- / Content -->


        <div class="content-backdrop fade"></div>
    </div>
    <!-- Content wrapper -->

    <!-- Overlay -->
    <div class="layout-overlay layout-menu-toggle"></div>
@endsection

@push('scripts')
    <script src="https://js.pusher.com/8.4.0/pusher.min.js"></script>

    <script>
        Pusher.logToConsole = true;

        var pusher = new Pusher("{{ env('VITE_PUSHER_APP_KEY') }}", {
            cluster: "{{ env('VITE_PUSHER_APP_CLUSTER') }}"
        });

        var userRole = "{{ Auth::user()->role ?? 'parent' }}"; // fallback for parents
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
                .nextElementSibling; // ul.dropdown-menu

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
                // insert after header (second child of ul)
                dropdown.insertAdjacentHTML("afterbegin", newItem);
            }
        });

        if (Notification.permission !== "granted") {
            Notification.requestPermission();
        }
    </script>

    <!-- Include Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Chart Initialization Script -->
    <script>
        // Global variables to store chart instances
        let enrolleesChartInstance = null;
        let genderChartInstance = null;

        // Initialize Enrollees Chart
        function initializeEnrolleesChart(enrollmentData, gradeLabels) {
            const ctx1 = document.getElementById('enrolleesChart').getContext('2d');

            // Destroy existing chart if it exists
            if (enrolleesChartInstance) {
                enrolleesChartInstance.destroy();
            }

            enrolleesChartInstance = new Chart(ctx1, {
                type: 'bar',
                data: {
                    labels: gradeLabels,
                    datasets: [{
                        label: 'Enrollees',
                        data: enrollmentData,
                        backgroundColor: [
                            '#FF8A8A', '#82E6E6', '#FFE852', '#C9A5FF',
                            '#8AFF8A', '#8A8AFF', '#FF8AFF'
                        ],
                        borderRadius: 8
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 10
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });
        }

        // Initialize Gender Chart
        function initializeGenderChart(femalePercentage, malePercentage, femaleCount, maleCount) {
            const chartGenderStatistics = document.querySelector('#genderStatisticsChart');

            // Clear existing chart
            if (chartGenderStatistics) {
                chartGenderStatistics.innerHTML = '';
            }

            const genderChartConfig = {
                chart: {
                    height: 165,
                    width: 130,
                    type: 'donut'
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
                            // Display actual count instead of percentage
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
                                    formatter: function() {
                                        return '100%';
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
            }
        }

        // Update UI with gender data
        function updateGenderUI(genderData) {
            // Update total students count
            const totalElement = document.querySelector('.card-body .d-flex.flex-column.align-items-center.gap-1 h2.mb-2');
            if (totalElement) {
                totalElement.textContent = genderData.total || 0;
            }

            // Update female stats
            const femaleCountElement = document.querySelector('li.d-flex.mb-3 small.text-muted');
            const femalePercentageElement = document.querySelector('li.d-flex.mb-3 small.fw-semibold');
            if (femaleCountElement) {
                femaleCountElement.textContent = (genderData.female_count || 0) + ' Students';
            }
            if (femalePercentageElement) {
                femalePercentageElement.textContent = (genderData.female_percentage || 0) + '%';
            }

            // Update male stats
            const maleCountElement = document.querySelector('li.d-flex:not(.mb-3) small.text-muted');
            const malePercentageElement = document.querySelector('li.d-flex:not(.mb-3) small.fw-semibold');
            if (maleCountElement) {
                maleCountElement.textContent = (genderData.male_count || 0) + ' Students';
            }
            if (malePercentageElement) {
                malePercentageElement.textContent = (genderData.male_percentage || 0) + '%';
            }

            // Update total in card header
            const headerTotalElement = document.querySelector('.card-header .text-muted');
            if (headerTotalElement) {
                headerTotalElement.textContent = 'Total: ' + (genderData.total || 0) + ' Students';
            }
        }

        // Update chart titles with school year
        function updateChartTitles(schoolYearText) {
            // Update enrollees chart title
            const enrolleesTitle = document.querySelector('.card-body .card-title.m-0');
            if (enrolleesTitle) {
                enrolleesTitle.textContent = 'Total enrollees for School Year ' + schoolYearText;
            }

            // Update gender chart "for SY" text
            const genderSyElement = document.querySelector(
            '.d-flex.flex-column.align-items-center.gap-1 span:nth-child(3)');
            if (genderSyElement) {
                genderSyElement.textContent = 'for SY: ' + schoolYearText;
            }
        }

        // AJAX for school year filtering
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

            // Enrollees chart filter
            document.querySelectorAll('.school-year-filter').forEach(item => {
                item.addEventListener('click', function(e) {
                    e.preventDefault();
                    const schoolYearId = this.getAttribute('data-year');
                    const schoolYearText = this.textContent.trim();

                    updateEnrolleesChart(schoolYearId);
                    updateDropdownText('yearDropdown', schoolYearText);
                    updateChartTitles(schoolYearText);

                    // Also update gender chart to keep them in sync
                    updateGenderChart(schoolYearId);
                    updateDropdownText('genderYearDropdown', schoolYearText);
                });
            });

            // Gender chart filter
            document.querySelectorAll('.gender-year-filter').forEach(item => {
                item.addEventListener('click', function(e) {
                    e.preventDefault();
                    const schoolYearId = this.getAttribute('data-year');
                    const schoolYearText = this.textContent.trim();

                    updateGenderChart(schoolYearId);
                    updateDropdownText('genderYearDropdown', schoolYearText);
                    updateChartTitles(schoolYearText);

                    // Also update enrollees chart to keep them in sync
                    updateEnrolleesChart(schoolYearId);
                    updateDropdownText('yearDropdown', schoolYearText);
                });
            });
        });

        function updateDropdownText(dropdownId, text) {
            const dropdown = document.getElementById(dropdownId);
            if (dropdown) {
                dropdown.textContent = text;
            }
        }

        function updateEnrolleesChart(schoolYearId) {
            fetch(`/admin/dashboard/enrollment-data?school_year_id=${schoolYearId}`)
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
                    // Show error message to user
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Failed to load enrollment data for the selected school year.',
                    });
                });
        }

        function updateGenderChart(schoolYearId) {
            fetch(`/admin/dashboard/gender-data?school_year_id=${schoolYearId}`)
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
                })
                .catch(error => {
                    console.error('Error fetching gender data:', error);
                    // Show error message to user
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Failed to load gender data for the selected school year.',
                    });
                });
        }
    </script>

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

        .report-list-item {
            padding: 8px 0;
        }
    </style>
@endpush
