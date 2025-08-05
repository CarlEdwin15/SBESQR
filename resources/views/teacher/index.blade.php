@extends('./layouts.main')

@section('title', 'Teacher | Dashboard')

@section('content')
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">

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
                    <li class="menu-item active">
                        <a href="{{ '/home ' }}" class="menu-link">
                            <i class="menu-icon tf-icons bx bx-home-circle"></i>
                            <div>Dashboard</div>
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

                    {{-- Payments sidebar --}}
                    <li class="menu-item">
                        <a href="javascript:void(0);" class="menu-link menu-toggle bg-dark text-light">
                            <i class="menu-icon tf-icons bx bx-wallet-alt text-light"></i>
                            <div class="text-light">Payments</div>
                        </a>
                        <ul class="menu-sub">
                            <li class="menu-item">
                                <a href="" class="menu-link bg-dark text-light">
                                    <div class="text-light">All Payments</div>
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

            <!-- Layout container -->
            <div class="layout-page">
                <!-- Navbar -->

                <nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme"
                    id="layout-navbar">
                    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
                        <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
                            <i class="bx bx-menu bx-sm"></i>
                        </a>
                    </div>

                    <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">

                        <ul class="navbar-nav flex-row align-items-center ms-auto">

                            <!-- Notification Dropdown -->
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle hide-arrow" href="#" id="notificationDropdown"
                                    role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class='bx bx-bell fs-4'></i>
                                    <span class="badge bg-danger rounded-pill badge-notifications">3</span>
                                </a>

                                <ul class="dropdown-menu dropdown-menu-end shadow border-0"
                                    aria-labelledby="notificationDropdown"
                                    style="min-width: 350px; max-height: 400px; overflow-y: auto;">
                                    <li class="px-3 pt-2">
                                        <h6 class="mb-1 d-flex justify-content-between">
                                            Notification
                                            <span class="badge bg-light-primary text-primary fw-bold">3 New</span>
                                        </h6>
                                    </li>

                                    <!-- Sample Notifications -->
                                    <li>
                                        <a class="dropdown-item d-flex align-items-start gap-2 py-3" href="#">
                                            <img src="{{ asset('assetsDashboard/img/avatars/1.png') }}" alt="avatar"
                                                class="rounded-circle" width="36" height="36">
                                            <div>
                                                <strong>Congratulation Lettie 🎉</strong>
                                                <div class="text-muted small">Won the monthly best seller badge</div>
                                                <small class="text-muted">1h ago</small>
                                            </div>
                                            <span class="ms-auto text-primary mt-1"><i class="bx bxs-circle"></i></span>
                                        </a>
                                    </li>

                                    <li>
                                        <a class="dropdown-item d-flex align-items-start gap-2 py-3" href="#">
                                            <div class="rounded-circle bg-secondary text-white d-flex justify-content-center align-items-center"
                                                style="width:36px; height:36px;">CF</div>
                                            <div>
                                                <strong>Charles Franklin</strong>
                                                <div class="text-muted small">Accepted your connection</div>
                                                <small class="text-muted">12h ago</small>
                                            </div>
                                            <span class="ms-auto text-primary mt-1"><i class="bx bxs-circle"></i></span>
                                        </a>
                                    </li>

                                    <li>
                                        <hr class="dropdown-divider my-0">
                                    </li>

                                    <li>
                                        <a href="#" class="dropdown-item text-center text-primary fw-semibold py-2">
                                            View all notifications
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <!-- /Notification Dropdown -->

                            <!-- User -->
                            <li class="nav-item navbar-dropdown dropdown-user dropdown">
                                <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);"
                                    data-bs-toggle="dropdown">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar">
                                            @auth
                                                @php
                                                    $profilePhoto = Auth::user()->profile_photo
                                                        ? asset('storage/' . Auth::user()->profile_photo)
                                                        : asset(
                                                            'assetsDashboard/img/profile_pictures/teachers_default_profile.jpg',
                                                        );
                                                @endphp
                                                <img src="{{ $profilePhoto }}" alt="Profile Photo"
                                                    class="w-px-40 h-auto rounded-circle" />
                                            @else
                                                <img src="{{ asset('assetsDashboard/img/profile_pictures/teachers_default_profile.jpg') }}"
                                                    alt="Default Profile Photo" class="w-px-40 h-auto rounded-circle" />
                                            @endauth
                                        </div>
                                        @auth
                                            <span class="fw-semibold ms-2">{{ Auth::user()->firstName }}</span>
                                        @endauth
                                    </div>
                                </a>

                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <a class="dropdown-item" href="#">
                                            <div class="d-flex align-items-center">
                                                <div class="avatar">
                                                    @auth
                                                        @php
                                                            $profilePhoto = Auth::user()->profile_photo
                                                                ? asset('storage/' . Auth::user()->profile_photo)
                                                                : asset(
                                                                    'assetsDashboard/img/profile_pictures/teachers_default_profile.jpg',
                                                                );
                                                        @endphp
                                                        <img src="{{ $profilePhoto }}" alt="Profile Photo"
                                                            class="w-px-40 h-auto rounded-circle" />
                                                    @else
                                                        <img src="{{ asset('assetsDashboard/img/profile_pictures/teachers_default_profile.jpg') }}"
                                                            alt="Default Profile Photo"
                                                            class="w-px-40 h-auto rounded-circle" />
                                                    @endauth
                                                </div>
                                                @auth
                                                    <span class="fw-semibold ms-2">{{ Auth::user()->firstName }}</span>
                                                @endauth
                                            </div>

                                        </a>
                                    </li>

                                    <li>
                                        <div class="dropdown-divider"></div>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="#">
                                            <i class="bx bx-user me-2"></i>
                                            <span class="align-middle">My Profile</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('account.settings') }}">
                                            <i class="bx bx-cog me-2"></i>
                                            <span class="align-middle">Settings</span>
                                        </a>
                                    </li>

                                    <li>
                                        <div class="dropdown-divider"></div>
                                    </li>
                                    <form id="logout-form" method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <x-dropdown-link class="dropdown-item" href="{{ route('logout') }}"
                                            onclick="event.preventDefault(); confirmLogout();">
                                            <i class="bx bx-power-off me-2"></i>
                                            {{ __('Log Out') }}
                                        </x-dropdown-link>
                                    </form>

                                </ul>
                            </li>
                            <!--/ User -->
                        </ul>
                    </div>
                </nav>

                <!-- / Navbar -->


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

                        $teacher = Auth::user();

                        // Get current school year
                        $currentSchoolYear = SchoolYear::where('start_date', '<=', now())
                            ->where('end_date', '>=', now())
                            ->first();

                        $studentCount = 0;
                        $newlyEnrolledStudents = 0;
                        $attendanceToday = 0;
                        $schedule = null;

                        $advisoryClass = $teacher
                            ->advisoryClasses()
                            ->wherePivot('school_year_id', $currentSchoolYear?->id)
                            ->first();

                        $subjectClass = $teacher
                            ->subjectClasses()
                            ->wherePivot('school_year_id', $currentSchoolYear?->id)
                            ->first();

                        $class = $advisoryClass ?? $subjectClass;

                        if ($class && $currentSchoolYear) {
                            $studentsQuery = $class->students()->wherePivot('school_year_id', $currentSchoolYear->id);

                            $studentCount = $studentsQuery->count();

                            $newlyEnrolledStudents = $studentsQuery
                                ->where('students.created_at', '>=', now()->subWeek())
                                ->count();

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

                                $attendanceToday =
                                    $studentCount > 0 ? min(100, round(($presentCount / $studentCount) * 100)) : 0;
                            }
                        }

                        $teacherClassesCount =
                            $teacher
                                ->advisoryClasses()
                                ->wherePivot('school_year_id', $currentSchoolYear?->id)
                                ->count() +
                            $teacher->subjectClasses()->wherePivot('school_year_id', $currentSchoolYear?->id)->count();
                    @endphp

                    <div class="container-xxl container-p-y">

                        <div class="row mb-4 g-3">
                            <!-- My Students Card -->
                            <div class="col-6 col-md-3">
                                <div class="card h-100 card-hover">
                                    <a class="card-body" href="{{ route('teacher.my.students') }}">
                                        <div class="card-title d-flex align-items-start justify-content-between">
                                            <div class="avatar flex-shrink-0">
                                                <img src="{{ asset('assetsDashboard/img/icons/dashIcon/studentIcon.png') }}"
                                                    alt="Students" class="rounded" />
                                            </div>
                                        </div>
                                        <span class="fw-semibold d-block mb-1 text-primary">My Students</span>
                                        <h3 class="card-title mb-2">{{ $studentCount }}</h3>
                                    </a>
                                </div>
                            </div>

                            <!-- Teacher's Classes Card -->
                            <div class="col-6 col-md-3">
                                <div class="card h-100 card-hover">
                                    <a class="card-body" href="{{ route('teacher.myClasses') }}">
                                        <div class="card-title d-flex align-items-start justify-content-between">
                                            <div class="avatar flex-shrink-0">
                                                <img src="{{ asset('assetsDashboard/img/icons/dashIcon/classroomIcon.png') }}"
                                                    alt="Teachers" class="rounded" />
                                            </div>
                                        </div>
                                        <span class="fw-semibold d-block mb-1 text-primary">Classes</span>
                                        <h3 class="card-title mb-2">
                                            {{ $teacherClassesCount }}
                                        </h3>
                                    </a>
                                </div>
                            </div>

                            <!-- Attendance Card -->
                            @if ($class)
                                <div class="col-6 col-md-3">
                                    <div class="card h-100 card-hover">
                                        <a class="card-body"
                                            href="{{ route('teacher.attendanceHistory', ['grade_level' => $class->grade_level, 'section' => $class->section]) }}?school_year_id={{ $currentSchoolYear?->id }}&date={{ now()->format('Y-m-d') }}">
                                            <div class="card-title d-flex align-items-start justify-content-between">
                                                <div class="avatar flex-shrink-0">
                                                    <img src="{{ asset('assetsDashboard/img/icons/dashIcon/attendanceIcon.png') }}"
                                                        alt="Attendance" class="rounded" />
                                                </div>
                                            </div>
                                            <span class="d-block mb-1 text-primary">
                                                @if (isset($schedule))
                                                    {{ $schedule->subject_name ?? ($schedule->subject->name ?? 'Subject') }}
                                                    ({{ ucfirst($class->grade_level) }} - {{ ucfirst($class->section) }})
                                                    <br>
                                                    {{ Carbon::parse($schedule->start_time)->format('h:i A') }} -
                                                    {{ Carbon::parse($schedule->end_time)->format('h:i A') }}
                                                @else
                                                    No Upcoming Schedule
                                                @endif
                                            </span>

                                            <h3
                                                class="card-title text-nowrap mb-2 {{ !isset($schedule) ? 'd-none' : '' }}">
                                                {{ $attendanceToday }}%
                                            </h3>
                                        </a>
                                    </div>
                                </div>
                            @else
                                <div class="col-6 col-md-3">
                                    <div class="card h-100 card-hover">
                                        <a class="card-body" href="">
                                            <div class="card-title d-flex align-items-start justify-content-between">
                                                <div class="avatar flex-shrink-0">
                                                    <img src="{{ asset('assetsDashboard/img/icons/dashIcon/attendanceIcon.png') }}"
                                                        alt="Attendance" class="rounded" />
                                                </div>
                                            </div>
                                            <span class="d-block mb-1 text-warning">
                                                You are not assigned as to a classes this school year.
                                            </span>
                                        </a>
                                    </div>
                                </div>
                            @endif

                            <!-- Newly Enrolled Card -->
                            <div class="col-6 col-md-3">
                                <div class="card h-100 card-hover">
                                    <a class="card-body" href="">
                                        <div class="card-title d-flex align-items-start justify-content-between">
                                            <div class="avatar flex-shrink-0">
                                                <img src="{{ asset('assetsDashboard/img/icons/dashIcon/newStudent.png') }}"
                                                    alt="Newly Enrolled" class="rounded" />
                                            </div>
                                        </div>
                                        <span class="fw-semibold d-block mb-1 text-primary">Newly Enrolled</span>
                                        <h3 class="card-title mb-2">{{ $newlyEnrolledStudents }}</h3>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Charts Section -->
                        <div class="row">
                            <!-- Total Enrollees Chart -->
                            <div class="col-md-7 col-lg-7 mb-3">
                                <div class="card h-100">
                                    <div class="card-body p-3">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <h6 class="card-title m-0">Total enrollees as of 2025</h6>
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-info text-white dropdown-toggle"
                                                    type="button" id="yearDropdown" data-bs-toggle="dropdown"
                                                    aria-expanded="false">
                                                    2025
                                                </button>
                                                <ul class="dropdown-menu" aria-labelledby="yearDropdown">
                                                    <li><a class="dropdown-item" href="#">2024</a></li>
                                                    <li><a class="dropdown-item" href="#">2023</a></li>
                                                </ul>
                                            </div>
                                        </div>
                                        <canvas id="enrolleesChart" height="140"></canvas>
                                    </div>
                                </div>
                            </div>

                            <!-- Gender Ratio -->
                            <div class="col-md-6 col-lg-4 col-xl-4 order-0 mb-4">
                                <div class="card h-100">
                                    <div class="card-header d-flex align-items-center justify-content-between pb-0">
                                        <div class="card-title mb-0">
                                            <h5 class="m-0 me-2">Student Gender Ratio</h5>
                                            <small class="text-muted">Total: 2,000 Students</small>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <div class="d-flex flex-column align-items-center gap-1">
                                                <h2 class="mb-2">2,000</h2>
                                                <span>Total Students</span>
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
                                                        <small class="text-muted">1,200 Students</small>
                                                    </div>
                                                    <div class="user-progress">
                                                        <small class="fw-semibold">60%</small>
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
                                                        <small class="text-muted">800 Students</small>
                                                    </div>
                                                    <div class="user-progress">
                                                        <small class="fw-semibold">40%</small>
                                                    </div>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- / Content -->
                    <div class="content-backdrop fade"></div>
                </div>
                <!-- Content wrapper -->


            </div>
            <!-- / Layout page -->
        </div>

        <!-- Overlay -->
        <div class="layout-overlay layout-menu-toggle"></div>
    </div>
    <!-- / Layout wrapper -->
@endsection

@push('scripts')
    <!-- Include Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Chart Initialization Script -->
    <script>
        // Enrollees Chart
        const ctx1 = document.getElementById('enrolleesChart').getContext('2d');
        new Chart(ctx1, {
            type: 'bar',
            data: {
                labels: ['Kndg', 'G1', 'G2', 'G3', 'G4', 'G5', 'G6'],
                datasets: [{
                    label: 'Enrollees',
                    data: [45, 35, 42, 155, 46, 34, 43],
                    backgroundColor: [
                        '#FF8A8A', '#82E6E6', '#FFE852', '#C9A5FF',
                        '#FF8A8A', '#82E6E6', '#FFE852'
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

        // Gender Chart
        // Gender Statistics Chart
        const chartGenderStatistics = document.querySelector('#genderStatisticsChart');

        const genderChartConfig = {
            chart: {
                height: 165,
                width: 130,
                type: 'donut'
            },
            labels: ['Female', 'Male'],
            series: [60, 40],
            colors: ['#FF5B5B', '#2AD3E6'], // Red for Female, Blue for Male
            stroke: {
                width: 5,
                colors: '#fff'
            },
            dataLabels: {
                enabled: false,
                formatter: function(val) {
                    return parseInt(val) + '%';
                }
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
            const genderChart = new ApexCharts(chartGenderStatistics, genderChartConfig);
            genderChart.render();
        }
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
    </style>
@endpush
