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

            <div class="dashboard-grid mb-3">
                <!-- STUDENTS -->
                <div class="card h-100 card-hover">
                    <a href="{{ route('student.management') }}" class="card-body">
                        <div class="d-flex align-items-center mb-5">
                            <div class="me-3">
                                <img src="{{ asset('assetsDashboard/img/icons/dashIcon/studentIcon.png') }}"
                                    width="50" height="50">
                            </div>
                            <div class="d-flex flex-column align-items-center ms-auto">
                                <h5 class="fw-semibold text-primary mb-2 d-none d-sm-block">Students</h5>
                                <h6 class="fw-semibold text-primary mb-2 d-sm-block d-sm-none">Students</h6>
                                <h1 class="fw-semibold mb-0">{{ $totalStudents }}</h1>
                            </div>
                        </div>

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

                <!-- TEACHERS -->
                <div class="card h-100 card-hover">
                    <a href="{{ route('show.teachers') }}" class="card-body">
                        <div class="d-flex align-items-center mb-5">
                            <div class="me-3">
                                <img src="{{ asset('assetsDashboard/img/icons/dashIcon/teacherIcon.png') }}"
                                    width="50" height="50">
                            </div>
                            <div class="d-flex flex-column align-items-center ms-auto">
                                <h5 class="fw-semibold text-primary mb-2 d-sm-block d-none">Teachers</h5>
                                <h6 class="fw-semibold text-primary mb-2 d-sm-none d-block">Teachers</h6>
                                <h1 class="fw-semibold mb-0">{{ $totalTeachers }}</h1>
                            </div>
                        </div>
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

                <!-- SCHOOL FEES CARD -->
                @php
                    use App\Models\Payment;
                    use App\Models\PaymentRequest;

                    // Get all school years for the filter
                    $allSchoolYears = SchoolYear::orderBy('start_date', 'asc')->get();

                    if ($allSchoolYears->isNotEmpty()) {
                        $firstSchoolYear = $allSchoolYears->first();

                        if ($currentSchoolYear) {
                            // Filter school years from first to current (inclusive)
                            $schoolYearsForFilter = $allSchoolYears
                                ->filter(function ($schoolYear) use ($firstSchoolYear, $currentSchoolYear) {
                                    return $schoolYear->start_date >= $firstSchoolYear->start_date &&
                                        $schoolYear->start_date <= $currentSchoolYear->start_date;
                                })
                                ->sortByDesc('start_date')
                                ->values();
                        } else {
                            // If no current school year, just use all available school years
                            $schoolYearsForFilter = $allSchoolYears->sortByDesc('start_date')->values();
                        }
                    } else {
                        $schoolYearsForFilter = collect();
                    }

                    // Get selected school year from request or use current
                    $selectedSchoolYearFilter = request()->get(
                        'school_fee_year',
                        $currentSchoolYear ? $currentSchoolYear->school_year : null,
                    );

                    // Get school fees for selected school year, grouped by payment name
                    $schoolFees = Payment::with([
                        'classStudent.student',
                        'classStudent.class',
                        'classStudent.schoolYear',
                    ])
                        ->whereHas('classStudent.schoolYear', function ($q) use ($selectedSchoolYearFilter) {
                            if ($selectedSchoolYearFilter) {
                                $q->where('school_year', $selectedSchoolYearFilter);
                            }
                        })
                        ->get()
                        ->groupBy('payment_name');

                    $totalSchoolFees = $schoolFees->count();

                    // Get pending payment requests count for each payment name
                    $paymentRequestsCounts = [];
                    foreach ($schoolFees as $paymentName => $groupedPayments) {
                        $pendingCount = PaymentRequest::where('status', 'pending')
                            ->whereHas('payment', function ($q) use ($paymentName, $selectedSchoolYearFilter) {
                                $q->where('payment_name', $paymentName);
                                if ($selectedSchoolYearFilter) {
                                    $q->whereHas('classStudent.schoolYear', function ($q2) use (
                                        $selectedSchoolYearFilter,
                                    ) {
                                        $q2->where('school_year', $selectedSchoolYearFilter);
                                    });
                                }
                            })
                            ->count();

                        $paymentRequestsCounts[$paymentName] = $pendingCount;
                    }
                @endphp

                <div class="card h-100 grid-span-rows" id="schoolFeesDashboardCard">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <!-- Image Icon -->
                        <div class="me-3 d-none d-sm-block">
                            <img src="{{ asset('assetsDashboard/img/icons/dashIcon/school-fee.png') }}" alt="School Fees"
                                class="rounded" width="50" height="50">
                        </div>
                        <div class="me-3 d-block d-sm-none">
                            <img src="{{ asset('assetsDashboard/img/icons/dashIcon/school-fee.png') }}" alt="School Fees"
                                class="rounded" width="75" height="75">
                        </div>

                        <!-- Title & Total -->
                        <div class="d-flex flex-column align-items-center ms-auto">
                            <h5 class="fw-semibold text-primary mb-1 d-none d-sm-block">School Fees</h5>
                            <h2 class="fw-semibold text-primary mb-1 d-sm-none d-block">School Fees</h2>
                            <h1 class="mb-0" id="schoolFeesTotalCount">{{ $totalSchoolFees }}</h1>
                        </div>
                    </div>

                    <!-- School Year Filter -->
                    <div class="card-body border-bottom py-2">
                        <div class="row g-2 align-items-center">
                            <div class="col-12">
                                <select id="schoolFeeYearFilter" class="form-select form-select-sm">
                                    @foreach ($schoolYearsForFilter as $schoolYear)
                                        <option value="{{ $schoolYear->school_year }}"
                                            {{ $selectedSchoolYearFilter == $schoolYear->school_year ? 'selected' : '' }}>
                                            {{ $schoolYear->school_year }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="card-body p-0" style="display: flex; flex-direction: column; height: 100%;">
                        <!-- Loading State -->
                        <div id="schoolFeesLoading" class="text-center py-4" style="display: none;">
                            <div class="spinner-border spinner-border-sm text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <small class="text-muted d-block mt-1">Loading school fees...</small>
                        </div>

                        <!-- School Fees Content -->
                        <div id="schoolFeesContent">
                            @if ($schoolFees->count() > 0)
                                <!-- Scrollable School Fees List -->
                                <div class="school-fees-scrollable" style="flex: 1; overflow-y: auto; max-height: 250px;">
                                    <ul class="p-0 m-0" id="schoolFeesList">
                                        @foreach ($schoolFees->take(7) as $paymentName => $groupedPayments)
                                            @php
                                                $firstPayment = $groupedPayments->first();
                                                $totalStudents = $groupedPayments->count();
                                                $paidCount = $groupedPayments->where('status', 'paid')->count();
                                                $partialCount = $groupedPayments->where('status', 'partial')->count();
                                                $unpaidCount = $groupedPayments->where('status', 'unpaid')->count();

                                                // Calculate collection percentage
                                                $totalExpected = $firstPayment->amount_due * $totalStudents;
                                                $totalCollected = $groupedPayments->sum('total_paid');
                                                $percentage =
                                                    $totalExpected > 0
                                                        ? round(($totalCollected / $totalExpected) * 100)
                                                        : 0;

                                                // Generate color based on payment name for consistency
                                                $color1 = '#' . substr(md5($paymentName), 0, 6);

                                                // Get pending requests count for this payment
                                                $pendingRequestsCount = $paymentRequestsCounts[$paymentName] ?? 0;

                                                // FIX: Ensure school year is properly set with fallback
                                                $schoolYearForUrl =
                                                    $selectedSchoolYearFilter ?:
                                                    ($currentSchoolYear
                                                        ? $currentSchoolYear->school_year
                                                        : '2024-2025');
                                                $schoolYearForData =
                                                    $selectedSchoolYearFilter ?:
                                                    ($currentSchoolYear
                                                        ? $currentSchoolYear->school_year
                                                        : '2024-2025');
                                            @endphp

                                            <!-- School Fee Item - ENTIRE ITEM IS NOW CLICKABLE -->
                                            <li class="d-flex school-fee-item border-bottom clickable-school-fee-item"
                                                data-payment-name="{{ $paymentName }}"
                                                data-pending-count="{{ $pendingRequestsCount }}"
                                                data-school-year="{{ $schoolYearForData }}"
                                                data-url="{{ route('admin.school-fees.show', [
                                                    'paymentName' => $paymentName,
                                                    'school_year' => $schoolYearForUrl,
                                                ]) }}">
                                                <div class="flex-shrink-0 me-3">
                                                    <div class="rounded-circle d-flex align-items-center justify-content-center text-white"
                                                        style="width: 40px; height: 40px; background-color: {{ $color1 }};">
                                                        <i class="bx bx-wallet fs-6"></i>
                                                    </div>
                                                </div>

                                                <div class="flex-grow-1" style="min-width: 0;">
                                                    <div class="d-flex justify-content-between align-items-start mb-1">
                                                        <!-- Payment Name - Now as span instead of link since entire item is clickable -->
                                                        <div
                                                            class="text-decoration-none text-dark flex-grow-1 payment-name-container">
                                                            <h6 class="mb-0 text-truncate" title="{{ $paymentName }}">
                                                                {{ $paymentName }}
                                                            </h6>
                                                        </div>

                                                        <!-- Notification Badge for Payment Requests -->
                                                        @if ($pendingRequestsCount > 0)
                                                            <span
                                                                class="badge rounded-pill bg-danger ms-2 school-fee-notification-badge
                                                @if ($pendingRequestsCount > 0) pulse-badge @endif"
                                                                style="font-size: 0.65rem; min-width: 20px; height: 20px; display: flex; align-items: center; justify-content: center;"
                                                                data-payment-name="{{ $paymentName }}"
                                                                data-school-year="{{ $schoolYearForData }}">
                                                                <span
                                                                    class="pending-count">{{ $pendingRequestsCount }}</span>
                                                                <span class="visually-hidden">pending payment
                                                                    requests</span>
                                                            </span>
                                                        @else
                                                            <span
                                                                class="badge rounded-pill bg-danger ms-2 school-fee-notification-badge"
                                                                style="font-size: 0.65rem; min-width: 20px; height: 20px; display: none; align-items: center; justify-content: center;"
                                                                data-payment-name="{{ $paymentName }}"
                                                                data-school-year="{{ $schoolYearForData }}">
                                                                <span class="pending-count">0</span>
                                                            </span>
                                                        @endif
                                                    </div>

                                                    <!-- Payment Details -->
                                                    <div class="mt-0">
                                                        <small class="text-muted">
                                                            Due:
                                                            {{ \Carbon\Carbon::parse($firstPayment->due_date)->format('M d, Y') }}
                                                        </small>
                                                        <small class="text-muted">
                                                            <span class="mx-2">•</span>
                                                            ₱{{ number_format($firstPayment->amount_due, 2) }}
                                                        </small>
                                                    </div>

                                                    <!-- Progress bar -->
                                                    <div class="mt-1">
                                                        <div class="d-flex justify-content-between small text-muted mb-1">
                                                            <span>Collection Progress</span>
                                                            <span>{{ $percentage }}%</span>
                                                        </div>
                                                        <div class="progress" style="height: 6px;">
                                                            <div class="progress-bar" role="progressbar"
                                                                style="width: {{ $percentage }}%; background-color: {{ $color1 }};"
                                                                aria-valuenow="{{ $percentage }}" aria-valuemin="0"
                                                                aria-valuemax="100">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @else
                                <div class="text-center py-2"
                                    style="flex: 1; display: flex; flex-direction: column; justify-content: center;">
                                    <i class="bx bx-wallet fs-1 text-muted"></i>
                                    <p class="text-muted mb-0 mt-2" id="schoolFeesEmptyMessage">
                                        @if ($selectedSchoolYearFilter)
                                            No school fees for {{ $selectedSchoolYearFilter }}
                                        @else
                                            No school fees created yet
                                        @endif
                                    </p>
                                    <small class="text-muted" id="schoolFeesEmptySubmessage">
                                        @if ($selectedSchoolYearFilter)
                                            Create school fees for this school year to track payments
                                        @else
                                            Create school fees to track payments
                                        @endif
                                    </small>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- View All Button -->
                    <div class="card-footer text-center" id="schoolFeesFooter">
                        @if ($totalSchoolFees > 0)
                            <a href="{{ route('admin.school-fees.index', ['school_year' => $selectedSchoolYearFilter]) }}"
                                class="btn btn-sm btn-primary" id="viewAllSchoolFeesBtn">
                                <i class="bx bx-list-ul me-1"></i>
                                View All School Fees
                            </a>
                        @else
                            <a href="{{ route('admin.school-fees.index', ['school_year' => $selectedSchoolYearFilter]) }}"
                                class="btn btn-sm btn-outline-primary" id="createSchoolFeesBtn">
                                <i class="bx bx-plus me-1"></i>
                                Create School Fees
                            </a>
                        @endif
                    </div>
                </div>

                <!-- RECENT USERS -->
                <div class="card h-100 grid-span-rows">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <div class="me-3 d-none d-sm-block">
                            <img src="{{ asset('assetsDashboard/img/icons/dashIcon/active-user.png') }}" width="50"
                                height="50">
                        </div>
                        <div class="me-1 d-block d-sm-none">
                            <img src="{{ asset('assetsDashboard/img/icons/dashIcon/active-user.png') }}" width="75"
                                height="75">
                        </div>
                        <div class="d-flex flex-column align-items-center ms-auto">
                            <h5 class="fw-semibold text-primary mb-2 d-none d-sm-block">Recent Users</h5>
                            <h2 class="fw-semibold text-primary mb-2 d-sm-none d-block">Recent Users</h2>
                            <h1 class="fw-semibold mb-0" id="activeUsersCount">0</h1>
                        </div>
                    </div>

                    <!-- Filter Controls -->
                    <div class="card-body border-bottom">
                        <div class="row g-2 align-items-center">
                            <!-- Role Filter -->
                            <div class="col-md-4 col-6">
                                <div class="d-flex align-items-center">
                                    <select id="roleFilter" class="form-select form-select-sm">
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
                                    <select id="itemsPerPage" class="form-select form-select-sm">
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
                                    <input type="text" id="userSearch" class="form-control"
                                        placeholder="Search users..." style="border-left: 0;">
                                    <button class="btn btn-outline-secondary" type="button" id="clearSearch"
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
                            <ul class="p-0 m-0" id="activeUsersList">
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
                            <div id="paginationInfo" class="text-muted small">
                                Showing <span id="showingCount">0</span> to <span id="showingEnd">0</span> of <span
                                    id="totalCount">0</span> users
                            </div>
                            <nav aria-label="User pagination">
                                <ul class="pagination pagination-sm mb-0" id="usersPagination">
                                    <!-- Pagination will be generated here -->
                                </ul>
                            </nav>
                        </div>
                        <div id="searchStatus" class="text-muted small mt-1 text-center">
                            <!-- Search status will be shown here -->
                        </div>
                    </div>
                </div>

                <!-- CLASSES -->
                <div class="card h-100 card-hover">
                    <a href="{{ route('all.classes') }}" class="card-body">
                        <div class="d-flex align-items-center mb-5">
                            <div class="me-3">
                                <img src="{{ asset('assetsDashboard/img/icons/dashIcon/classroomIcon.png') }}"
                                    width="50" height="50">
                            </div>
                            <div class="d-flex flex-column align-items-center ms-auto">
                                <h6 class="fw-semibold text-primary mb-2 d-sm-none d-block">Classes</h6>
                                <h5 class="fw-semibold text-primary mb-2 d-none d-sm-block">Classes</h5>
                                <h1 class="fw-semibold mb-0">{{ $totalClasses }}</h1>
                            </div>
                        </div>
                        <div class="d-flex flex-column">
                            <div class="d-flex justify-content-between">
                                <span class="d-flex align-items-center text-success">
                                    <i class="bx bx-check-square me-1"></i> Active:
                                </span>
                                <span class="text-success">{{ $activeClasses }}</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="d-flex align-items-center text-secondary">
                                    <i class="bx bx-x-circle me-1"></i> Inactive:
                                </span>
                                <span class="text-secondary">{{ $inactiveClasses }}</span>
                            </div>
                        </div>
                    </a>
                </div>

                <!-- USERS -->
                <div class="card h-100 card-hover">
                    <a href="{{ route('admin.user.management') }}" class="card-body">
                        <div class="d-flex align-items-center mb-5">
                            <div class="me-3">
                                <img src="{{ asset('assetsDashboard/img/icons/dashIcon/total_users.png') }}"
                                    width="50" height="50">
                            </div>
                            <div class="d-flex flex-column align-items-center ms-auto">
                                <h6 class="fw-semibold text-primary mb-2 d-sm-none d-sm-block">Users</h6>
                                <h5 class="fw-semibold text-primary mb-2 d-none d-sm-block">Users</h5>
                                <h1 class="fw-semibold mb-0">{{ $totalUsers }}</h1>
                            </div>
                        </div>
                        <div class="d-flex flex-column">
                            <div class="d-flex justify-content-between">
                                <span class="d-flex align-items-center text-info">
                                    <i class="bx bx-cog me-1"></i> Admin:
                                </span>
                                <span class="text-info">{{ $totalAdmins }}</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="d-flex align-items-center text-primary">
                                    <i class="bx bx-book-reader me-1"></i> Teacher:
                                </span>
                                <span class="text-primary">{{ $totalTeachers }}</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="d-flex align-items-center text-warning">
                                    <i class="bx bx-home-heart me-1"></i> Parent:
                                </span>
                                <span class="text-warning">{{ $totalParents }}</span>
                            </div>
                        </div>
                    </a>
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
                        <div class="card-body border-bottom py-2">
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

    {{-- <!-- School Fees Card -->
    <div class="col-6 col-md-3">
        <div class="card h-100 card-hover">
            <a href="{{ route('admin.user.management') }}" class="card-body">

                <!-- Top row: Image + Title + Total -->
                <div class="d-flex align-items-center mb-3">

                    <!-- Image Icon -->
                    <div class="me-3">
                        <img src="{{ asset('assetsDashboard/img/icons/dashIcon/school-fee.png') }}" alt="School Fees"
                            class="rounded" width="50" height="50">
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
    </div>

    <!-- Announcements Card -->
    <div class="col-6 col-md-3">
        <div class="card h-100 card-hover">
            <a href="{{ route('admin.user.management') }}" class="card-body">

                <!-- Top row: Image + Title + Total -->
                <div class="d-flex align-items-center mb-3">

                    <!-- Image Icon -->
                    <div class="me-3">
                        <img src="{{ asset('assetsDashboard/img/icons/dashIcon/announcement.png') }}" alt="Announcements"
                            class="rounded" width="50" height="50">
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

    <!-- Overlay -->
    <div class="layout-overlay layout-menu-toggle"></div>
@endsection

@push('scripts')
    <script src="https://js.pusher.com/8.4.0/pusher.min.js"></script>

    <!-- Pusher Notification Script -->
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

            fetch(`/admin/dashboard/grade-sections?school_year_id=${schoolYearId}&grade_level=${gradeLevel}`)
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
            let url = `/admin/dashboard/gender-data-filtered?school_year_id=${schoolYearId}`;
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

    <!-- Active Users Management with Filtering, Search and Pagination -->
    <script>
        // Global variables for state management
        let allUsers = [];
        let filteredUsers = [];
        let currentPage = 1;
        let itemsPerPage = 10;
        let currentRoleFilter = 'all';
        let currentSearchTerm = '';

        function loadActiveUsers() {
            fetch("{{ route('admin.active-users') }}")
                .then(res => res.json())
                .then(users => {
                    allUsers = users;
                    applyFiltersAndPagination();
                })
                .catch(err => {
                    console.error('Error loading active users:', err);
                    const container = document.getElementById('activeUsersList');
                    container.innerHTML = `
                <li class="text-center py-3 text-danger">
                    <i class="bx bx-error-alt fs-4"></i>
                    <small class="d-block">Failed to load users</small>
                </li>
            `;
                });
        }

        function applyFiltersAndPagination() {
            // Apply role filter
            let tempFilteredUsers = allUsers.filter(user => {
                if (currentRoleFilter === 'all') return true;
                return user.role.toLowerCase() === currentRoleFilter;
            });

            // Apply search filter
            filteredUsers = tempFilteredUsers.filter(user => {
                if (!currentSearchTerm) return true;

                const searchTerm = currentSearchTerm.toLowerCase();
                const userName = user.name ? user.name.toLowerCase() : '';
                const userEmail = user.email ? user.email.toLowerCase() : '';
                const userRole = user.role ? user.role.toLowerCase() : '';

                return userName.includes(searchTerm) ||
                    userEmail.includes(searchTerm) ||
                    userRole.includes(searchTerm);
            });

            // Update count
            const countElement = document.getElementById('activeUsersCount');
            countElement.textContent = filteredUsers.length;

            // Calculate pagination
            const totalPages = Math.ceil(filteredUsers.length / itemsPerPage);
            currentPage = Math.min(Math.max(1, currentPage), totalPages);

            const startIndex = (currentPage - 1) * itemsPerPage;
            const endIndex = Math.min(startIndex + itemsPerPage, filteredUsers.length);
            const currentUsers = filteredUsers.slice(startIndex, endIndex);

            // Update display
            displayUsers(currentUsers);
            updatePagination(totalPages, startIndex, endIndex);
            updatePaginationInfo(startIndex, endIndex, filteredUsers.length);
            updateSearchStatus();
        }

        function displayUsers(users) {
            const container = document.getElementById('activeUsersList');

            // Add updating class for smooth transition
            container.classList.add('updating');

            if (users.length === 0) {
                let message = 'No users found';
                if (currentSearchTerm) {
                    message = `No users found for "${currentSearchTerm}"`;
                } else if (currentRoleFilter !== 'all') {
                    message = `No ${currentRoleFilter} users found`;
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
                    ${formatLastSeen(user.last_seen)}
                </small>`;

                const profilePhoto = getProfilePhotoUrl(user);
                const roleDisplay = getRoleDisplay(user.role);

                // Highlight search term in name
                let displayName = user.name;
                if (currentSearchTerm) {
                    const regex = new RegExp(`(${currentSearchTerm})`, 'gi');
                    displayName = user.name.replace(regex, '<mark class="bg-warning px-1 rounded">$1</mark>');
                }

                return `
                <li class="d-flex align-items-center px-3 py-2 border-bottom" style="min-height: 60px;">
                    <div class="flex-shrink-0 me-3 position-relative">
                    <a href="/admin/userInfo/${user.id}">
                        <img src="${profilePhoto}"
                             alt="${user.name}"
                             class="rounded-circle"
                             width="40"
                             height="40"
                             style="object-fit: cover;">
                        ${statusIndicator}
                    </a>
                    </div>
                    <div class="flex-grow-1">
                        <a href="/admin/userInfo/${user.id}">
                        <h6 class="mb-0 text-truncate" style="max-width: 120px;">${displayName}</h6>
                        <div class="d-flex align-items-center">
                            ${roleDisplay}
                        </div>
                        </a>
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

        function updatePagination(totalPages, startIndex, endIndex) {
            const paginationContainer = document.getElementById('usersPagination');

            if (totalPages <= 1) {
                paginationContainer.innerHTML = '';
                return;
            }

            let paginationHTML = '';

            // Previous button
            paginationHTML += `
                <li class="page-item ${currentPage === 1 ? 'disabled' : ''}">
                    <a class="page-link" href="javascript:void(0)" onclick="changePage(${currentPage - 1})">
                        <i class="bx bx-chevron-left"></i>
                    </a>
                </li>
                `;

            // Always show only 2 page numbers
            let startPage = currentPage;
            let endPage = Math.min(currentPage + 1, totalPages);

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
        <li class="page-item ${i === currentPage ? 'active' : ''}">
            <a class="page-link" href="javascript:void(0)" onclick="changePage(${i})">${i}</a>
        </li>
        `;
            }

            // Next button
            paginationHTML += `
                    <li class="page-item ${currentPage === totalPages ? 'disabled' : ''}">
                        <a class="page-link" href="javascript:void(0)" onclick="changePage(${currentPage + 1})">
                            <i class="bx bx-chevron-right"></i>
                        </a>
                    </li>
                    `;

            paginationContainer.innerHTML = paginationHTML;
        }

        function updatePaginationInfo(startIndex, endIndex, total) {
            const showingCount = document.getElementById('showingCount');
            const showingEnd = document.getElementById('showingEnd');
            const totalCount = document.getElementById('totalCount');

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

        function updateSearchStatus() {
            const searchStatus = document.getElementById('searchStatus');

            // Update search status
            if (currentSearchTerm) {
                searchStatus.innerHTML = `Search results for: "${currentSearchTerm}"`;
                searchStatus.classList.remove('text-muted');
                searchStatus.classList.add('text-info');
            } else if (currentRoleFilter !== 'all') {
                searchStatus.innerHTML = `Filtered by: ${currentRoleFilter}`;
                searchStatus.classList.remove('text-muted');
                searchStatus.classList.add('text-primary');
            } else {
                // Remove the "Showing all users" text completely
                searchStatus.innerHTML = '';
                searchStatus.classList.remove('text-info', 'text-primary');
                searchStatus.classList.add('text-muted');
            }
        }

        function changePage(page) {
            currentPage = page;
            applyFiltersAndPagination();

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
        function formatLastSeen(lastSeen) {
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
        function getProfilePhotoUrl(user) {
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
        function getRoleDisplay(role) {
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
        function debounce(func, wait) {
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
            document.getElementById('roleFilter').addEventListener('change', function() {
                currentRoleFilter = this.value;
                currentPage = 1; // Reset to first page when filter changes
                applyFiltersAndPagination();
            });

            // Items per page
            document.getElementById('itemsPerPage').addEventListener('change', function() {
                itemsPerPage = parseInt(this.value);
                currentPage = 1; // Reset to first page when items per page changes
                applyFiltersAndPagination();
            });

            // Search input with debounce
            const searchInput = document.getElementById('userSearch');
            const clearSearchBtn = document.getElementById('clearSearch');

            const handleSearch = debounce(function() {
                currentSearchTerm = searchInput.value.trim();
                currentPage = 1; // Reset to first page when search changes

                // Show/hide clear button
                if (currentSearchTerm) {
                    clearSearchBtn.style.display = 'block';
                } else {
                    clearSearchBtn.style.display = 'none';
                }

                applyFiltersAndPagination();
            }, 300);

            searchInput.addEventListener('input', handleSearch);

            // Clear search
            clearSearchBtn.addEventListener('click', function() {
                searchInput.value = '';
                currentSearchTerm = '';
                currentPage = 1; // Reset to first page when clearing search
                clearSearchBtn.style.display = 'none';
                applyFiltersAndPagination();
                searchInput.focus();
            });

            // Load initial data
            loadActiveUsers();

            // Auto-refresh every 30 seconds
            setInterval(loadActiveUsers, 30000);
        });
    </script>

    <!-- School Fees Real-time Notification System -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const schoolFeesList = document.getElementById('schoolFeesList');
            let lastCheckedAt = localStorage.getItem('schoolFeesLastChecked') || new Date().toISOString();

            // Initialize notification display
            updateAllNotificationBadges();

            // Function to update notification badge for a specific payment
            function updateNotificationBadge(paymentName, pendingCount) {
                const badge = document.querySelector(
                    `.school-fee-notification-badge[data-payment-name="${paymentName}"]`);
                const countSpan = badge?.querySelector('.pending-count');

                if (badge && countSpan) {
                    if (pendingCount > 0) {
                        countSpan.textContent = pendingCount;
                        badge.style.display = 'flex';

                        // Add pulse animation for new requests
                        if (pendingCount > 0) {
                            badge.classList.add('pulse-badge');
                        } else {
                            badge.classList.remove('pulse-badge');
                        }
                    } else {
                        badge.style.display = 'none';
                    }
                }
            }

            // Function to update all notification badges
            function updateAllNotificationBadges() {
                const schoolFeeItems = document.querySelectorAll('.school-fee-item');

                schoolFeeItems.forEach(item => {
                    const paymentName = item.dataset.paymentName;
                    const currentCount = parseInt(item.dataset.pendingCount) || 0;
                    updateNotificationBadge(paymentName, currentCount);
                });
            }

            // // Real-time updates using polling (every 30 seconds)
            // function checkForNewPaymentRequests() {
            //     fetch('{{ route('admin.payment-requests.check-dashboard') }}', {
            //             method: 'GET',
            //             headers: {
            //                 'Content-Type': 'application/json',
            //                 'X-Requested-With': 'XMLHttpRequest'
            //             }
            //         })
            //         .then(response => response.json())
            //         .then(data => {
            //             if (data.success) {
            //                 // Update counts for each payment
            //                 if (data.payment_counts) {
            //                     Object.entries(data.payment_counts).forEach(([paymentName, pendingCount]) => {
            //                         updateNotificationBadge(paymentName, pendingCount);

            //                         // Update the data attribute for future reference
            //                         const item = document.querySelector(
            //                             `.school-fee-item[data-payment-name="${paymentName}"]`);
            //                         if (item) {
            //                             item.dataset.pendingCount = pendingCount;
            //                         }
            //                     });
            //                 }

            //                 // Show notification for new requests
            //                 if (data.total_new_count > 0) {
            //                     showNewRequestNotification(data.total_new_count);
            //                 }
            //             }
            //         })
            //         .catch(error => console.error('Error checking payment requests:', error));
            // }

            // // Show desktop notification for new requests
            // function showNewRequestNotification(count) {
            //     if (Notification.permission === 'granted') {
            //         new Notification(`New Payment Request${count > 1 ? 's' : ''}`, {
            //             body: `You have ${count} new payment request${count > 1 ? 's' : ''} pending review`,
            //             icon: '{{ asset('assetsDashboard/img/logo.png') }}',
            //             tag: 'payment-requests-dashboard',
            //             url: '{{ route('admin.school-fees.index') }}'
            //         });
            //     }
            // }

            // Request notification permission
            if ('Notification' in window && Notification.permission === 'default') {
                Notification.requestPermission();
            }

            // Start polling for updates
            setInterval(checkForNewPaymentRequests, 30000); // Check every 30 seconds

            // Enhanced click behavior for school fee items
            document.querySelectorAll('.school-fee-item').forEach(item => {
                const paymentName = item.dataset.paymentName;
                const schoolYear = item.dataset.schoolYear;
                const pendingCount = parseInt(item.dataset.pendingCount) || 0;

                item.style.cursor = 'pointer';
                item.addEventListener('click', function(e) {
                    // Don't trigger if clicking on the link or badge directly
                    if (e.target.closest('a') || e.target.closest(
                            '.school-fee-notification-badge')) {
                        return;
                    }

                    e.preventDefault();

                    if (pendingCount > 0) {
                        // Store the intention to open payment requests for this specific payment with school year
                        localStorage.setItem('openPaymentRequests', 'true');
                        localStorage.setItem('targetPaymentName', paymentName);
                        localStorage.setItem('targetSchoolYear', schoolYear);
                        localStorage.setItem('schoolFeesLastChecked', new Date().toISOString());
                    }

                    // Navigate to the specific school fee page with school year context
                    const url =
                        '{{ route('admin.school-fees.show', ['paymentName' => ':paymentName', 'school_year' => ':schoolYear']) }}'
                        .replace(':paymentName', paymentName)
                        .replace(':schoolYear', schoolYear);
                    window.location.href = url;
                });
            });

            // Enhanced click behavior for notification badges
            document.querySelectorAll('.school-fee-notification-badge').forEach(badge => {
                badge.style.cursor = 'pointer';
                badge.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();

                    const paymentName = this.dataset.paymentName;
                    const schoolYear = this.dataset.schoolYear;

                    // Store the intention to open payment requests with school year context
                    localStorage.setItem('openPaymentRequests', 'true');
                    localStorage.setItem('targetPaymentName', paymentName);
                    localStorage.setItem('targetSchoolYear', schoolYear);
                    localStorage.setItem('schoolFeesLastChecked', new Date().toISOString());

                    // Navigate to the specific school fee page with school year context
                    const url =
                        '{{ route('admin.school-fees.show', ['paymentName' => ':paymentName', 'school_year' => ':schoolYear']) }}'
                        .replace(':paymentName', paymentName)
                        .replace(':schoolYear', schoolYear);
                    window.location.href = url;
                });
            });

            // Check if we need to auto-open payment requests modal on school fees page
            function checkAutoOpenModal() {
                const shouldOpenRequests = localStorage.getItem('openPaymentRequests');
                const targetPaymentName = localStorage.getItem('targetPaymentName');
                const targetSchoolYear = localStorage.getItem('targetSchoolYear');
                const currentPath = window.location.pathname;

                if (shouldOpenRequests === 'true' && targetPaymentName && targetSchoolYear) {
                    // Check if we're on the correct school fee page
                    const isOnTargetPage = currentPath.includes('school-fees') &&
                        currentPath.includes(targetPaymentName);

                    if (isOnTargetPage) {
                        // Clear the flags
                        localStorage.removeItem('openPaymentRequests');
                        localStorage.removeItem('targetPaymentName');
                        localStorage.removeItem('targetSchoolYear');

                        // Look for payment requests button and click it
                        setTimeout(() => {
                            const paymentRequestsBtn = document.getElementById('paymentRequestsBtn');
                            if (paymentRequestsBtn) {
                                paymentRequestsBtn.click();

                                // After modal opens, ensure the correct school year filter is applied
                                setTimeout(() => {
                                    const modal = document.getElementById('paymentRequestsModal');
                                    if (modal) {
                                        modal.scrollIntoView({
                                            behavior: 'smooth',
                                            block: 'start'
                                        });

                                        // Set the school year filter in the modal if it exists
                                        const schoolYearFilter = document.querySelector(
                                            'select[name="school_year"]');
                                        if (schoolYearFilter && targetSchoolYear) {
                                            schoolYearFilter.value = targetSchoolYear;
                                            // Trigger change event to refresh the data
                                            const event = new Event('change');
                                            schoolYearFilter.dispatchEvent(event);
                                        }
                                    }
                                }, 500);
                            }
                        }, 1000); // Delay to ensure page is fully loaded
                    }
                }
            }

            // Run auto-open check when page loads (for school fees pages)
            if (window.location.pathname.includes('school-fees')) {
                checkAutoOpenModal();
            }
        });
    </script>

    <!-- School Year Filter for School Fees -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const schoolFeeYearFilter = document.getElementById('schoolFeeYearFilter');

            if (schoolFeeYearFilter) {
                schoolFeeYearFilter.addEventListener('change', function() {
                    const selectedYear = this.value;

                    // Create a URL with the selected school year filter
                    const currentUrl = new URL(window.location.href);
                    currentUrl.searchParams.set('school_fee_year', selectedYear);

                    // Reload the page with the new filter
                    window.location.href = currentUrl.toString();
                });
            }
        });
    </script>

    <!-- Enhanced School Fee Items Click Handling -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Enhanced click handling for entire school fee items
            document.querySelectorAll('.clickable-school-fee-item').forEach(item => {
                const paymentName = item.dataset.paymentName;
                const schoolYear = item.dataset.schoolYear;
                const pendingCount = parseInt(item.dataset.pendingCount) || 0;
                const targetUrl = item.dataset.url;

                // Make the entire item visually clickable
                item.style.cursor = 'pointer';
                item.classList.add('clickable-item');

                item.addEventListener('click', function(e) {
                    // Don't trigger if clicking on specific elements that have their own handlers
                    if (e.target.closest('.school-fee-notification-badge') ||
                        e.target.closest('.progress') ||
                        e.target.closest('.progress-bar')) {
                        return;
                    }

                    e.preventDefault();
                    e.stopPropagation();

                    console.log('School fee item clicked:', {
                        paymentName: paymentName,
                        schoolYear: schoolYear,
                        pendingCount: pendingCount,
                        targetUrl: targetUrl
                    });

                    // Store the intention to open payment requests if there are pending requests
                    if (pendingCount > 0) {
                        localStorage.setItem('openPaymentRequests', 'true');
                        localStorage.setItem('targetPaymentName', paymentName);
                        localStorage.setItem('targetSchoolYear', schoolYear);
                        localStorage.setItem('schoolFeesLastChecked', new Date().toISOString());

                        console.log('Setting auto-open flags for payment requests');
                    }

                    // Navigate to the specific school fee page
                    console.log('Redirecting to:', targetUrl);
                    window.location.href = targetUrl;
                });

                // Add hover effects
                item.addEventListener('mouseenter', function() {
                    this.style.backgroundColor = '#f8f9fa';
                    this.style.transform = 'translateX(5px)';
                });

                item.addEventListener('mouseleave', function() {
                    this.style.backgroundColor = '';
                    this.style.transform = '';
                });
            });

            // Enhanced click behavior for notification badges (separate from item click)
            document.querySelectorAll('.school-fee-notification-badge').forEach(badge => {
                badge.style.cursor = 'pointer';

                badge.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();

                    const paymentName = this.dataset.paymentName;
                    const schoolYear = this.dataset.schoolYear;

                    console.log('Notification badge clicked:', {
                        paymentName: paymentName,
                        schoolYear: schoolYear
                    });

                    // Store the intention to open payment requests with school year context
                    localStorage.setItem('openPaymentRequests', 'true');
                    localStorage.setItem('targetPaymentName', paymentName);
                    localStorage.setItem('targetSchoolYear', schoolYear);
                    localStorage.setItem('schoolFeesLastChecked', new Date().toISOString());

                    // Navigate to the specific school fee page
                    const url =
                        '{{ route('admin.school-fees.show', ['paymentName' => ':paymentName', 'school_year' => ':schoolYear']) }}'
                        .replace(':paymentName', encodeURIComponent(paymentName))
                        .replace(':schoolYear', encodeURIComponent(schoolYear));

                    console.log('Redirecting to:', url);
                    window.location.href = url;
                });
            });

            // Enhanced auto-open payment requests modal function
            function checkAutoOpenModal() {
                const shouldOpenRequests = localStorage.getItem('openPaymentRequests');
                const targetPaymentName = localStorage.getItem('targetPaymentName');
                const targetSchoolYear = localStorage.getItem('targetSchoolYear');
                const currentPath = window.location.pathname;

                console.log('Auto-open check:', {
                    shouldOpenRequests,
                    targetPaymentName,
                    targetSchoolYear,
                    currentPath
                });

                if (shouldOpenRequests === 'true' && targetPaymentName && targetSchoolYear) {
                    // Check if we're on the correct school fee page
                    const isOnTargetPage = currentPath.includes('school-fees') &&
                        currentPath.includes(targetPaymentName);

                    console.log('Is on target page:', isOnTargetPage);

                    if (isOnTargetPage) {
                        // Clear the flags
                        localStorage.removeItem('openPaymentRequests');
                        localStorage.removeItem('targetPaymentName');
                        localStorage.removeItem('targetSchoolYear');

                        console.log('Auto-opening payment requests modal...');

                        // Open the payment requests modal
                        setTimeout(() => {
                            const paymentRequestsBtn = document.getElementById('paymentRequestsBtn');
                            if (paymentRequestsBtn) {
                                console.log('Found payment requests button, clicking...');
                                paymentRequestsBtn.click();

                                // After modal opens, ensure the correct school year filter is applied
                                setTimeout(() => {
                                    const modal = document.getElementById('paymentRequestsModal');
                                    if (modal) {
                                        console.log('Modal opened, ensuring correct data...');

                                        // Force refresh of the requests table if needed
                                        const searchInput = document.getElementById(
                                            'requestsSearch');
                                        if (searchInput) {
                                            searchInput.value = '';
                                            const event = new Event('input', {
                                                bubbles: true
                                            });
                                            searchInput.dispatchEvent(event);
                                        }
                                    }
                                }, 500);
                            } else {
                                console.error('Payment requests button not found');
                            }
                        }, 1000);
                    }
                }
            }

            // Run auto-open check when page loads (for school fees pages)
            if (window.location.pathname.includes('school-fees')) {
                checkAutoOpenModal();
            }
        });
    </script>

    <!-- School Year Filter for School Fees - AJAX Version -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const schoolFeeYearFilter = document.getElementById('schoolFeeYearFilter');
            const schoolFeesCard = document.getElementById('schoolFeesDashboardCard');
            const schoolFeesContent = document.getElementById('schoolFeesContent');
            const schoolFeesLoading = document.getElementById('schoolFeesLoading');
            const schoolFeesTotalCount = document.getElementById('schoolFeesTotalCount');
            const schoolFeesList = document.getElementById('schoolFeesList');
            const schoolFeesEmptyMessage = document.getElementById('schoolFeesEmptyMessage');
            const schoolFeesEmptySubmessage = document.getElementById('schoolFeesEmptySubmessage');
            const schoolFeesFooter = document.getElementById('schoolFeesFooter');
            const viewAllSchoolFeesBtn = document.getElementById('viewAllSchoolFeesBtn');
            const createSchoolFeesBtn = document.getElementById('createSchoolFeesBtn');

            if (schoolFeeYearFilter) {
                schoolFeeYearFilter.addEventListener('change', function() {
                    const selectedYear = this.value;
                    loadSchoolFeesData(selectedYear);
                });
            }

            function loadSchoolFeesData(schoolYear) {
                // Show loading state
                if (schoolFeesLoading) schoolFeesLoading.style.display = 'block';
                if (schoolFeesContent) schoolFeesContent.style.display = 'none';

                // Add loading class to card
                schoolFeesCard.classList.add('card-loading');

                // Make AJAX request
                fetch(`/admin/dashboard/school-fees-data?school_year=${encodeURIComponent(schoolYear)}`, {
                        method: 'GET',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                'content')
                        }
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        updateSchoolFeesCard(data, schoolYear);
                    })
                    .catch(error => {
                        console.error('Error loading school fees data:', error);
                        // Fallback to page reload if AJAX fails
                        const currentUrl = new URL(window.location.href);
                        currentUrl.searchParams.set('school_fee_year', schoolYear);
                        window.location.href = currentUrl.toString();
                    })
                    .finally(() => {
                        // Hide loading state
                        if (schoolFeesLoading) schoolFeesLoading.style.display = 'none';
                        if (schoolFeesContent) schoolFeesContent.style.display = 'block';
                        schoolFeesCard.classList.remove('card-loading');
                    });
            }

            function updateSchoolFeesCard(data, schoolYear) {
                // Update total count
                if (schoolFeesTotalCount) {
                    schoolFeesTotalCount.textContent = data.total_count;
                }

                // Update school fees list
                if (schoolFeesList && data.school_fees && data.school_fees.length > 0) {
                    schoolFeesList.innerHTML = data.school_fees.map(fee => `
                    <li class="d-flex school-fee-item border-bottom clickable-school-fee-item"
                        data-payment-name="${fee.payment_name}"
                        data-pending-count="${fee.pending_requests_count}"
                        data-school-year="${schoolYear}"
                        data-url="${fee.url}">
                        <div class="flex-shrink-0 me-3">
                            <div class="rounded-circle d-flex align-items-center justify-content-center text-white"
                                style="width: 40px; height: 40px; background-color: ${fee.color};">
                                <i class="bx bx-wallet fs-6"></i>
                            </div>
                        </div>

                        <div class="flex-grow-1" style="min-width: 0;">
                            <div class="d-flex justify-content-between align-items-start mb-1">
                                <div class="text-decoration-none text-dark flex-grow-1 payment-name-container">
                                    <h6 class="mb-0 text-truncate" title="${fee.payment_name}">
                                        ${fee.payment_name}
                                    </h6>
                                </div>

                                ${fee.pending_requests_count > 0 ? `
                                                    <span class="badge rounded-pill bg-danger ms-2 school-fee-notification-badge pulse-badge"
                                                        style="font-size: 0.65rem; min-width: 20px; height: 20px; display: flex; align-items: center; justify-content: center;"
                                                        data-payment-name="${fee.payment_name}"
                                                        data-school-year="${schoolYear}">
                                                        <span class="pending-count">${fee.pending_requests_count}</span>
                                                        <span class="visually-hidden">pending payment requests</span>
                                                    </span>
                                                ` : `
                                                    <span class="badge rounded-pill bg-danger ms-2 school-fee-notification-badge"
                                                        style="font-size: 0.65rem; min-width: 20px; height: 20px; display: none; align-items: center; justify-content: center;"
                                                        data-payment-name="${fee.payment_name}"
                                                        data-school-year="${schoolYear}">
                                                        <span class="pending-count">0</span>
                                                    </span>
                                                `}
                            </div>

                            <div class="mt-0">
                                <small class="text-muted">
                                    Due: ${fee.due_date}
                                </small>
                                <small class="text-muted">
                                    <span class="mx-2">•</span>
                                    ₱${fee.amount_due}
                                </small>
                            </div>

                            <div class="mt-1">
                                <div class="d-flex justify-content-between small text-muted mb-1">
                                    <span>Collection Progress</span>
                                    <span>${fee.percentage}%</span>
                                </div>
                                <div class="progress" style="height: 6px;">
                                    <div class="progress-bar" role="progressbar"
                                        style="width: ${fee.percentage}%; background-color: ${fee.color};"
                                        aria-valuenow="${fee.percentage}" aria-valuemin="0"
                                        aria-valuemax="100">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                `).join('');

                    // Show the list and hide empty state
                    schoolFeesList.style.display = 'block';
                    if (schoolFeesEmptyMessage) schoolFeesEmptyMessage.style.display = 'none';
                    if (schoolFeesEmptySubmessage) schoolFeesEmptySubmessage.style.display = 'none';
                } else {
                    // Show empty state
                    if (schoolFeesList) schoolFeesList.style.display = 'none';
                    if (schoolFeesEmptyMessage) {
                        schoolFeesEmptyMessage.textContent = `No school fees for ${schoolYear}`;
                        schoolFeesEmptyMessage.style.display = 'block';
                    }
                    if (schoolFeesEmptySubmessage) {
                        schoolFeesEmptySubmessage.textContent =
                            'Create school fees for this school year to track payments';
                        schoolFeesEmptySubmessage.style.display = 'block';
                    }
                }

                // Update footer buttons
                updateFooterButtons(data.total_count > 0, schoolYear);

                // Re-attach click event handlers
                attachSchoolFeeItemClickHandlers();
            }

            function updateFooterButtons(hasFees, schoolYear) {
                if (!schoolFeesFooter) return;

                if (hasFees) {
                    schoolFeesFooter.innerHTML = `
                    <a href="/admin/school-fees?school_year=${encodeURIComponent(schoolYear)}"
                       class="btn btn-sm btn-primary" id="viewAllSchoolFeesBtn">
                        <i class="bx bx-list-ul me-1"></i>
                        View All School Fees
                    </a>
                `;
                } else {
                    schoolFeesFooter.innerHTML = `
                    <a href="/admin/school-fees?school_year=${encodeURIComponent(schoolYear)}"
                       class="btn btn-sm btn-outline-primary" id="createSchoolFeesBtn">
                        <i class="bx bx-plus me-1"></i>
                        Create School Fees
                    </a>
                `;
                }
            }

            function attachSchoolFeeItemClickHandlers() {
                // Re-attach click handlers to school fee items
                document.querySelectorAll('.clickable-school-fee-item').forEach(item => {
                    item.addEventListener('click', function(e) {
                        if (e.target.closest('.school-fee-notification-badge') ||
                            e.target.closest('.progress') ||
                            e.target.closest('.progress-bar')) {
                            return;
                        }

                        e.preventDefault();
                        e.stopPropagation();

                        const paymentName = this.dataset.paymentName;
                        const schoolYear = this.dataset.schoolYear;
                        const pendingCount = parseInt(this.dataset.pendingCount) || 0;
                        const targetUrl = this.dataset.url;

                        if (pendingCount > 0) {
                            localStorage.setItem('openPaymentRequests', 'true');
                            localStorage.setItem('targetPaymentName', paymentName);
                            localStorage.setItem('targetSchoolYear', schoolYear);
                            localStorage.setItem('schoolFeesLastChecked', new Date().toISOString());
                        }

                        window.location.href = targetUrl;
                    });
                });

                // Re-attach click handlers to notification badges
                document.querySelectorAll('.school-fee-notification-badge').forEach(badge => {
                    badge.addEventListener('click', function(e) {
                        e.preventDefault();
                        e.stopPropagation();

                        const paymentName = this.dataset.paymentName;
                        const schoolYear = this.dataset.schoolYear;

                        localStorage.setItem('openPaymentRequests', 'true');
                        localStorage.setItem('targetPaymentName', paymentName);
                        localStorage.setItem('targetSchoolYear', schoolYear);
                        localStorage.setItem('schoolFeesLastChecked', new Date().toISOString());

                        const url = '/admin/school-fees/' + encodeURIComponent(paymentName) +
                            '?school_year=' + encodeURIComponent(schoolYear);
                        window.location.href = url;
                    });
                });
            }

            // Initialize click handlers
            attachSchoolFeeItemClickHandlers();
        });
    </script>
@endpush

@push('styles')
    <style>
        /* Notification badge styles for school fee items */
        .school-fee-notification-badge {
            animation: pulse 2s infinite;
            flex-shrink: 0;
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
                box-shadow: 0 0 0 0 rgba(220, 53, 69, 0.7);
            }

            50% {
                transform: scale(1.05);
                box-shadow: 0 0 0 5px rgba(220, 53, 69, 0);
            }

            100% {
                transform: scale(1);
                box-shadow: 0 0 0 0 rgba(220, 53, 69, 0);
            }
        }

        /* ===== DASHBOARD GRID LAYOUT ===== */
        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            grid-auto-rows: auto;
            gap: 1rem;
        }

        .grid-span-rows {
            grid-row: span 2;
            min-height: 400px;
        }

        /* ===== RESPONSIVE BREAKPOINTS ===== */
        /* Tablet View (2 columns) */
        @media (max-width: 991px) {
            .dashboard-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 0.75rem;
            }

            .dashboard-grid>.card-hover {
                grid-column: span 1 !important;
            }

            .grid-span-rows:nth-of-type(3),
            .grid-span-rows:nth-of-type(4) {
                grid-column: span 2 !important;
            }

            /* Card reordering for mobile */
            .dashboard-grid>.card-hover:nth-of-type(5) {
                order: 3;
            }

            .dashboard-grid>.card-hover:nth-of-type(6) {
                order: 4;
            }

            .dashboard-grid>.grid-span-rows:nth-of-type(3) {
                order: 5;
            }

            .dashboard-grid>.grid-span-rows:nth-of-type(4) {
                order: 6;
            }

            .grid-span-rows {
                grid-row: auto !important;
                min-height: 350px;
            }

            .card {
                width: 100%;
            }
        }

        /* Mobile View (2 columns) */
        @media (max-width: 767px) {
            .dashboard-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 0.75rem;
            }

            .dashboard-grid>.card-hover {
                grid-column: span 1 !important;
            }

            .grid-span-rows:nth-of-type(3),
            .grid-span-rows:nth-of-type(4) {
                grid-column: span 2 !important;
            }

            /* Card reordering for mobile */
            .dashboard-grid>.card-hover:nth-of-type(5) {
                order: 3;
            }

            .dashboard-grid>.card-hover:nth-of-type(6) {
                order: 4;
            }

            .dashboard-grid>.grid-span-rows:nth-of-type(3) {
                order: 5;
            }

            .dashboard-grid>.grid-span-rows:nth-of-type(4) {
                order: 6;
            }

            .grid-span-rows {
                grid-row: auto !important;
                min-height: 300px;
            }

            /* MOBILE HEIGHT FIX: Increase recent user card height */
            .grid-span-rows .card-body[style*="height: 250px"] {
                height: 300px !important;
            }

            .grid-span-rows .active-users-list {
                height: 220px !important;
            }

            #activeUsersList li {
                min-height: 70px !important;
                padding: 12px 16px !important;
            }
        }

        /* ===== CARD STYLES ===== */
        .card-hover {
            transition: all 0.3s ease;
        }

        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .card.h-100 {
            height: 100% !important;
            display: flex;
            flex-direction: column;
        }

        .report-list-item {
            padding: 8px 0;
        }

        /* ===== RECENT USERS CARD STYLES ===== */
        .active-users-list {
            flex: 1;
            min-height: 0;
            scrollbar-width: thin;
            scrollbar-color: #c1c1c1 transparent;
            -webkit-overflow-scrolling: touch;
        }

        .active-users-list::-webkit-scrollbar {
            width: 6px;
        }

        .active-users-list::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 3px;
        }

        .active-users-list::-webkit-scrollbar-thumb {
            background-color: #c1c1c1;
            border-radius: 10px;
        }

        .active-users-list::-webkit-scrollbar-thumb:hover {
            background-color: #a8a8a8;
        }

        .card-body[style*="height: 250px"] {
            flex-shrink: 0;
        }

        #activeUsersList li {
            min-height: 60px;
            display: flex;
            align-items: center;
            transition: background-color 0.2s ease;
        }

        #activeUsersList li:hover {
            background-color: #f8f9fa;
        }

        #activeUsersList .text-center {
            height: 100px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        #activeUsersList .text-center.py-4 {
            height: 150px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        /* ===== PAGINATION STYLES ===== */
        /* FIX: Consistent pagination button sizes */
        #usersPagination .page-link {
            min-width: 38px !important;
            height: 38px !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            padding: 0.375rem 0.75rem !important;
        }

        #usersPagination .page-item:first-child .page-link,
        #usersPagination .page-item:last-child .page-link {
            min-width: 38px !important;
            height: 38px !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
        }

        #usersPagination .page-link i {
            font-size: 1rem !important;
            line-height: 1 !important;
        }

        #usersPagination .page-item.active .page-link {
            min-width: 38px !important;
            height: 38px !important;
            background-color: #007bff;
            border-color: #007bff;
        }

        #usersPagination .page-item.disabled .page-link {
            min-width: 38px !important;
            height: 38px !important;
            color: #6c757d;
            pointer-events: none;
            background-color: #fff;
            border-color: #dee2e6;
        }

        #usersPagination .page-link:hover {
            background-color: #e9ecef;
            border-color: #dee2e6;
        }

        #usersPagination {
            transition: all 0.3s ease;
        }

        /* Mobile pagination adjustments */
        @media (max-width: 575px) {
            #usersPagination .page-link {
                min-width: 36px !important;
                height: 36px !important;
                padding: 0.25rem 0.5rem !important;
                font-size: 0.875rem !important;
            }

            #usersPagination .page-item:first-child .page-link,
            #usersPagination .page-item:last-child .page-link {
                min-width: 36px !important;
                height: 36px !important;
            }
        }

        /* ===== SEARCH & FILTER STYLES ===== */
        #userSearch:focus {
            border-color: #86b7fe;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
        }

        .input-group-text {
            border-right: 0;
        }

        mark.bg-warning {
            background-color: #ffc107 !important;
            padding: 0.1rem 0.2rem;
            border-radius: 0.2rem;
        }

        #clearSearch {
            border-left: 0;
            transition: all 0.2s ease;
        }

        #clearSearch:hover {
            background-color: #f8f9fa;
            border-color: #86b7fe;
        }

        /* ===== CARD FOOTER ===== */
        .card-footer {
            flex-shrink: 0;
            background: white;
            border-top: 1px solid rgba(0, 0, 0, 0.125);
        }

        /* ===== STATUS & TEXT STYLES ===== */
        .text-truncate {
            max-width: 120px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .text-success small,
        .text-muted small {
            font-size: 0.75rem;
            white-space: nowrap;
        }

        .position-relative .bg-success {
            box-shadow: 0 0 0 2px #fff;
        }

        .position-relative .bg-secondary {
            box-shadow: 0 0 0 2px #fff;
            opacity: 0.7;
        }

        #searchStatus.text-info,
        #searchStatus.text-primary {
            font-weight: 500;
        }

        /* ===== CHART STYLES ===== */
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

        canvas {
            transition: opacity 0.5s ease-in-out;
        }

        /* ===== TRANSITIONS & ANIMATIONS ===== */
        #activeUsersList {
            transition: opacity 0.3s ease;
        }

        #activeUsersList.updating {
            opacity: 0.7;
        }

        .dropdown-toggle {
            transition: all 0.3s ease;
        }

        .card-body h1,
        .card-body h2,
        .card-body h5,
        .card-body h6,
        .card-body span,
        .card-body small {
            transition: all 0.3s ease;
        }

        .chartjs-tooltip {
            animation: tooltipFadeIn 0.3s ease-out;
        }

        /* ===== KEYFRAMES ===== */
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

        @keyframes tooltipFadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* ===== FILTER CONTROLS STYLES ===== */
        .card-body.border-bottom {
            padding: 1rem !important;
        }

        /* Improved filter controls layout */
        #roleFilter,
        #itemsPerPage {
            min-width: 120px;
        }

        /* Better spacing for filter controls */
        .row.g-3.align-items-center {
            margin: -0.5rem;
        }

        .row.g-3.align-items-center>[class*="col-"] {
            padding: 0.5rem;
        }

        /* Mobile optimization for filter controls */
        @media (max-width: 767px) {
            .card-body.border-bottom {
                padding: 0.75rem !important;
            }

            .row.g-3.align-items-center {
                margin: -0.25rem;
            }

            .row.g-3.align-items-center>[class*="col-"] {
                padding: 0.25rem;
            }

            #roleFilter,
            #itemsPerPage {
                min-width: 100px;
            }
        }

        /* Ensure proper alignment in web view */
        @media (min-width: 768px) {
            .row.g-3.align-items-center {
                display: flex;
                align-items: center;
                justify-content: space-between;
            }

            .col-md-4 {
                flex: 0 0 auto;
                width: auto;
            }

            .col-md-4:last-child {
                flex: 1;
                max-width: 300px;
            }
        }

        /* Better form label styling */
        .form-label.small.text-muted {
            font-size: 0.875rem;
            font-weight: 500;
        }

        /* Improved search input */
        #userSearch {
            border-radius: 0 0.375rem 0.375rem 0;
        }

        .input-group-sm>.form-control {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
        }

        /* Filter section styling */
        #genderFilterText {
            font-size: 0.8rem;
            font-weight: 500;
            color: #6c757d;
        }

        /* Responsive adjustments */
        @media (max-width: 576px) {
            .card-body.border-bottom.py-2 .row.g-2 {
                margin: -0.25rem;
            }

            .card-body.border-bottom.py-2 .col-6 {
                padding: 0.25rem;
            }
        }

        /* Make dropdown menus scrollable */
        .dropdown-menu {
            max-height: 300px;
            overflow-y: auto;
            scrollbar-width: thin;
            scrollbar-color: #c1c1c1 #f8f9fa;
        }

        .dropdown-menu::-webkit-scrollbar {
            width: 6px;
        }

        .dropdown-menu::-webkit-scrollbar-track {
            background: #f8f9fa;
            border-radius: 3px;
        }

        .dropdown-menu::-webkit-scrollbar-thumb {
            background-color: #c1c1c1;
            border-radius: 10px;
        }

        .dropdown-menu::-webkit-scrollbar-thumb:hover {
            background-color: #a8a8a8;
        }

        /* Ensure dropdown items are properly styled */
        .dropdown-item {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            padding: 0.5rem 1rem;
        }

        .dropdown-item:hover {
            background-color: #e9ecef;
        }

        /* School Fees Scrollable Styles */
        .school-fees-scrollable {
            scrollbar-width: thin;
            scrollbar-color: #c1c1c1 transparent;
            -webkit-overflow-scrolling: touch;
        }

        .school-fees-scrollable::-webkit-scrollbar {
            width: 6px;
        }

        .school-fees-scrollable::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 3px;
            margin: 5px;
        }

        .school-fees-scrollable::-webkit-scrollbar-thumb {
            background-color: #c1c1c1;
            border-radius: 10px;
        }

        .school-fees-scrollable::-webkit-scrollbar-thumb:hover {
            background-color: #a8a8a8;
        }

        /* School fee item hover effects */
        .school-fee-item {
            transition: all 0.3s ease;
            border-radius: 8px;
            padding: 12px 8px;
            margin: 4px 0;
        }

        .school-fee-item:hover {
            background-color: #f8f9fa;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        /* Ensure proper text truncation with badge */
        .school-fee-item .flex-grow-1 {
            min-width: 0;
            overflow: hidden;
        }

        .school-fee-item h6.text-truncate {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            flex-shrink: 1;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .school-fees-scrollable {
                max-height: 250px !important;
            }

            .school-fee-item {
                padding: 10px 6px;
                margin: 3px 0;
            }

            .school-fee-item:hover {
                transform: translateX(3px);
            }
        }
    </style>

    {{-- <style>


        /* School fee item hover effects */
        .school-fee-item {
            transition: all 0.3s ease;
            border-radius: 8px;
            padding: 12px 8px;
            margin: 4px 0;
        }

        .school-fee-item:hover {
            background-color: #f8f9fa;
            transform: translateX(5px);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        /* Badge positioning */
        .school-fee-item .d-flex.justify-content-between {
            align-items: flex-start;
        }

        .school-fee-item h6 {
            margin-right: 8px;
            line-height: 1.2;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .school-fee-notification-badge {
                animation: none;
                /* Disable animation on mobile for performance */
                font-size: 0.6rem !important;
                min-width: 18px !important;
                height: 18px !important;
            }

            .school-fee-item {
                padding: 10px 6px;
                margin: 3px 0;
            }

            .school-fee-item:hover {
                transform: translateX(3px);
            }
        }

        /* Ensure proper text truncation with badge */
        .school-fee-item .flex-grow-1 {
            min-width: 0;
            overflow: hidden;
        }

        .school-fee-item h6.text-truncate {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            flex-shrink: 1;
        }

        /* Smooth transitions for badge updates */
        .school-fee-notification-badge {
            transition: all 0.3s ease;
        }

        /* Highlight effect for items with pending requests */
        .school-fee-item[data-pending-count="0"] {
            opacity: 0.9;
        }

        .school-fee-item[data-pending-count="0"]:hover {
            opacity: 1;
        }
    </style> --}}
@endpush
