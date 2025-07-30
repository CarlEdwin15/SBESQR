@extends('./layouts.main')

@section('title', 'Admin | Dashboard')

@section('content')
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">

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
                                    <div class="text-light">All Teachers</div>
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
                                <a href="{{ route('show.students') }}" class="menu-link bg-dark text-light">
                                    <div class="text-light">All Students</div>
                                </a>
                            </li>
                            <li class="menu-item">
                                <a href="{{ route('add.student') }}" class="menu-link bg-dark text-light">
                                    <div class="text-light">Student Enrollment</div>
                                </a>
                            </li>
                            <li class="menu-item">
                                <a href="{{ route('students.promote.view') }}" class="menu-link bg-dark text-light">
                                    <div class="text-light">Student Promotion</div>
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
                                <a href="" class="menu-link bg-dark text-light">
                                    <div class="text-light">All Announcements</div>
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

                    {{-- Account Settings sidebar --}}
                    <li class="menu-item">
                        <a href="{{ route('account.settings') }}" class="menu-link bg-dark text-light">
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

                    {{-- @php
                        $now = now();
                        $year = $now->year;
                        $cutoff = $now->copy()->setMonth(6)->setDay(1);
                        $startYear = $now->lt($cutoff) ? $year - 1 : $year;
                        $schoolYear = $startYear . '-' . ($startYear + 1);
                    @endphp

                    <div class="d-flex align-items-center ms-3">
                        <h5 class="mb-0">Current School Year: {{ $schoolYear }}</h5>
                    </div> --}}

                    @php
                        $nowPH = now('Asia/Manila')->format('Y-m-d H:i:s');
                    @endphp

                    <div class="d-flex align-items-center ms-3 text-primary">
                        <h6 class="mb-0"><span id="realtime-clock">{{ $nowPH }}</span></h6>
                    </div>

                    <script>
                        let currentTime = new Date("{{ $nowPH }} GMT+0800");

                        function updateClock() {
                            currentTime.setSeconds(currentTime.getSeconds() + 1);
                            const formatted = currentTime.toLocaleString('en-PH', {
                                weekday: 'long', // Include day name
                                year: 'numeric',
                                month: 'long',
                                day: '2-digit',
                                hour: '2-digit',
                                minute: '2-digit',
                                second: '2-digit',
                                hour12: true,
                                timeZone: 'Asia/Manila'
                            }).replace(' at ', ' '); // Remove the word "at"
                            document.getElementById('realtime-clock').textContent = formatted;
                        }

                        setInterval(updateClock, 1000);
                    </script>

                    <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">

                        <ul class="navbar-nav flex-row align-items-center ms-auto">
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
                                                            'assetsDashboard/img/profile_pictures/admin_profile.png',
                                                        );
                                                @endphp
                                                <img src="{{ $profilePhoto }}" alt="Profile Photo"
                                                    class="w-px-40 h-auto rounded-circle" />
                                            @else
                                                <img src="{{ asset('assetsDashboard/img/profile_pictures/admin_profile.png') }}"
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
                                                                    'assetsDashboard/img/profile_pictures/admin_profile.png',
                                                                );
                                                        @endphp
                                                        <img src="{{ $profilePhoto }}" alt="Profile Photo"
                                                            class="w-px-40 h-auto rounded-circle" />
                                                    @else
                                                        <img src="{{ asset('assetsDashboard/img/profile_pictures/admin_profile.png') }}"
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
                        use Illuminate\Support\Carbon;

                        // Get the default school year
                        $now = now();
                        $year = $now->year;
                        $cutoff = $now->copy()->setMonth(6)->setDay(1);
                        $startYear = $now->lt($cutoff) ? $year - 1 : $year;
                        $schoolYearStart = Carbon::create($startYear, 6, 1);
                        $schoolYearEnd = Carbon::create($startYear + 1, 5, 31)->endOfDay();

                        // Totals
                        $totalStudents = \App\Models\Student::count();
                        $totalTeachers = \App\Models\User::where('role', 'teacher')->count();
                        $totalClasses = \App\Models\Classes::count();

                        // Newly enrolled students within current school year
                        $newlyEnrolledStudents = \App\Models\Student::whereBetween('created_at', [
                            $schoolYearStart,
                            $schoolYearEnd,
                        ])->count();
                    @endphp

                    <div class="container-xxl container-p-y">

                        <div class="row mb-4 g-3">

                            <!-- Student Card -->
                            <div class="col-6 col-md-3">
                                <div class="card h-100 card-hover">
                                    <a href="{{ route('show.students') }}" class="card-body">
                                        <div class="card-title d-flex align-items-start justify-content-between">
                                            <div class="avatar flex-shrink-0">

                                                <img src="{{ asset('assetsDashboard/img/icons/dashIcon/studentIcon.png') }}"
                                                    alt="Students" class="rounded" />
                                            </div>
                                        </div>
                                        <span class="fw-semibold d-block mb-1 text-primary">Students</span>
                                        <h3 class="card-title mb-2">{{ $totalStudents }}</h3>
                                    </a>
                                </div>
                            </div>

                            <!-- Teachers Card -->
                            <div class="col-6 col-md-3">
                                <div class="card h-100 card-hover">
                                    <a href="{{ route('show.teachers') }}" class="card-body">
                                        <div class="card-title d-flex align-items-start justify-content-between">
                                            <div class="avatar flex-shrink-0">
                                                <img src="{{ asset('assetsDashboard/img/icons/dashIcon/teacherIcon.png') }}"
                                                    alt="Teachers" class="rounded" />

                                            </div>
                                        </div>
                                        <span class="fw-semibold d-block mb-1 text-primary">Teachers</span>
                                        <h3 class="card-title mb-2">{{ $totalTeachers }}</h3>
                                    </a>
                                </div>
                            </div>

                            <!-- Classes Card -->
                            <div class="col-6 col-md-3">
                                <div class="card h-100 card-hover">
                                    <a href="{{ route('all.classes') }}" class="card-body">
                                        <div class="card-title d-flex align-items-start justify-content-between">
                                            <div class="avatar flex-shrink-0">

                                                <img src="{{ asset('assetsDashboard/img/icons/dashIcon/classroomIcon.png') }}"
                                                    alt="Classes" class="rounded" />

                                            </div>
                                        </div>
                                        <span class="fw-semibold d-block mb-1 text-primary">Classes</span>
                                        <h3 class="card-title text-nowrap mb-2">{{ $totalClasses }}</h3>
                                    </a>
                                </div>
                            </div>

                            <!-- Newly Enrolled Card -->
                            <div class="col-6 col-md-3">
                                <div class="card h-100 card-hover">
                                    <a href="{{ route('show.students') }}" class="card-body">
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

                        <!-- Enrollees and Gender Chart Section (Compact) -->
                        <div class="row">
                            <!-- Total Enrollees Chart -->
                            <div class="col-md-7 col-lg-7 mb-3">
                                <div class="card h-100">
                                    <div class="card-body p-3">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <h6 class="card-title m-0">Total enrollees for School Year 2025-2026</h6>
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
                            <!-- / Total Enrollees Chart -->

                            <!-- Gender Distribution Card -->
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
                            <!-- Gender Distribution Card -->

                        </div>
                        <!-- / Enrollees and Gender Chart Section (Compact) -->

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
