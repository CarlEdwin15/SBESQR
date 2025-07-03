@extends('./layouts.main')

@section('title', 'Teacher | My Class')

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
                                <a href="" class="menu-link bg-dark text-light">
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
                                    <div class="text-danger">My Classes</div>
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
                <div class="container-xxl flex-grow-1 container-p-y">
                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <div>
                            <h4 class="fw-bold text-warning mb-0">
                                <span class="text-muted fw-light">
                                    <a class="text-muted fw-light" href="{{ route('home') }}">Dashboard</a> /
                                    <a class="text-muted fw-light" href="{{ route('teacher.myClasses') }}">Classes</a> /
                                </span>
                                {{ ucfirst(str_replace('_', ' ', $class->grade_level)) }} - {{ $class->section }}
                            </h4>

                        </div>
                    </div>

                    <a href="{{ route('teacher.myClasses', ['grade_level' => $class->grade_level, 'section' => $class->section]) }}"
                        class="btn btn-danger mb-3">Back</a>

                    <div class="card p-4 shadow-sm mb-4">
                        <h4 class="fw-bold text-warning text-center">Class Details &amp; Management</h4>
                        <div class="row g-4 mb-4">
                            <!-- Students -->
                            <div class="col-md-4">
                                <div class="card card-hover border-0 shadow-sm h-100 bg-light">
                                    <div class="card-body text-center">
                                        <div class="card-title d-flex align-items-center justify-content-between">
                                            <div class="avatar">
                                                @if ($class)
                                                    <img src="{{ asset('assetsDashboard/img/icons/dashIcon/studentIcon.png') }}"
                                                        alt="Students" class="rounded"
                                                        style="width:110px; height:110px;" />
                                                @endif
                                            </div>
                                            <h5 class="fw-semibold mb-1">Students</h5>
                                        </div>
                                        <div class="display-6 fw-bold">{{ $studentCount }}</div>
                                    </div>
                                </div>
                            </div>
                            <!-- /Students -->

                            <!-- Attendance Today -->
                            <div class="col-md-4">
                                <a href="{{ route('teacher.attendanceHistory', ['grade_level' => $class->grade_level, 'section' => $class->section]) }}"
                                    class="card card-hover border-0 shadow-sm h-100 bg-light">
                                    <div class="card-body text-center">
                                        <div class="card-title d-flex align-items-center justify-content-between">
                                            <div class="avatar">
                                                @if ($class)
                                                    <img src="{{ asset('assetsDashboard/img/icons/dashIcon/attendanceIcon.png') }}"
                                                        alt="Students" class="rounded"
                                                        style="width:110px; height:110px;" />
                                                @endif
                                            </div>
                                        </div>
                                        <h5 class="fw-semibold mb-1">Attendance Today</h5>
                                        <div class="display-6 fw-bold">
                                            {{ $attendanceToday }}%
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <!-- / Attendance Today -->

                            <!-- Teacher -->
                            <div class="col-md-4">
                                <div class="card card-hover border-0 shadow-sm h-100 bg-light">
                                    <div class="card-body text-center">
                                        <div class="card-title d-flex align-items-center justify-content-between">
                                            <div class="avatar">
                                                @if (isset($class->adviser) && $class->adviser)
                                                    @if ($class->adviser->gender === 'male')
                                                        <img src="{{ asset('assetsDashboard/img/icons/dashIcon/adviser.png') }}"
                                                            alt="Adviser (Male)" class="rounded"
                                                            style="width:110px; height:110px;" />
                                                    @elseif($class->adviser->gender === 'female')
                                                        <img src="{{ asset('assetsDashboard/img/icons/dashIcon/teacherIcon.png') }}"
                                                            alt="Adviser (Female)" class="rounded"
                                                            style="width:110px; height:110px;" />
                                                    @endif
                                                @else
                                                    <p>No adviser assigned.</p>
                                                @endif
                                            </div>
                                        </div>
                                        <h5 class="fw-semibold mb-1">Adviser</h5>
                                        <div class="fw-bold text-primary">
                                            {{ $class->adviser->firstName ?? 'N/A' }}
                                            {{ $class->adviser->lastName ?? '' }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- / Teacher -->
                        </div>

                        <!-- Card Links -->
                        <div class="row g-3 mb-5">
                            <div class="col-md-3">
                                <a href="{{ route('teacher.mySchedule', ['grade_level' => $class->grade_level, 'section' => $class->section]) }}"
                                    class="card card-hover border-0 shadow-sm text-center py-4 bg-primary text-white h-100">
                                    <i class="bi bi-clock-history fs-2 mb-2"></i>
                                    <div class="fw-semibold">Schedules</div>
                                </a>
                            </div>
                            <div class="col-md-3">
                                <a href="{{ route('teacher.myAttendanceRecord', ['grade_level' => $class->grade_level, 'section' => $class->section]) }}"
                                    class="card card-hover border-0 shadow-sm text-center py-4 bg-info text-white h-100">
                                    <i class="bi bi-clipboard-check fs-2 mb-2"></i>
                                    <div class="fw-semibold">Attendances</div>
                                </a>
                            </div>
                            <div class="col-md-3">
                                <a href="{{ route('teacher.myClassMasterList', ['grade_level' => $class->grade_level, 'section' => $class->section]) }}"
                                    class="card card-hover border-0 shadow-sm text-center py-4 bg-success text-white h-100">
                                    <i class="bi bi-list-ul fs-2 mb-2"></i>
                                    <div class="fw-semibold">Master's List</div>
                                </a>
                            </div>

                            <div class="col-md-3">
                                <a href=""
                                    class="card card-hover border-0 shadow-sm text-center py-4 bg-warning text-white h-100">
                                    <i class="bx bx-message-check fs-2 mb-4"></i>
                                    <div class="fw-semibold">SMS Logs</div>
                                </a>
                            </div>
                        </div>
                    </div>
                    <hr class="my-5" />
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
    <!-- Main CSS File -->
    <link href="{{ asset('assets/css/main.css') }}" rel="stylesheet" />
    <link href="{{ asset('assetsDashboard/vendor/css/core.css') }}" rel="stylesheet" />

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
