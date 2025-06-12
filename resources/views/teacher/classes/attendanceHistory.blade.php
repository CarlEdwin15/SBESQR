@extends('./layouts.main')

@section('title', 'Teacher | Attendance History')

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
                                    <a class="text-muted fw-light"
                                        href="{{ route('teacher.myClass', ['grade_level' => $class->grade_level, 'section' => $class->section]) }}">
                                        {{ ucfirst($class->grade_level) }} - {{ $class->section }} </a> /
                                </span>
                                Attendance History
                            </h4>
                        </div>
                    </div>

                    <a href="{{ route('teacher.myAttendanceRecord', ['grade_level' => $class->grade_level, 'section' => $class->section]) }}"
                        class="btn btn-danger mb-3">Back</a>

                    {{-- Date Selection Form --}}
                    <form id="dateForm" method="GET" class="mb-3">
                        <div class="d-flex gap-2 align-items-end">
                            <div>
                                <label for="date">Choose Date:</label>
                                <input type="date" id="date" name="date" class="form-control"
                                    value="{{ $targetDate }}">
                            </div>
                            <button class="btn btn-primary" type="submit">View</button>
                        </div>
                    </form>

                    {{-- Attendance Card --}}
                    <div class="card shadow-sm mb-4">
                        <div class="card-body">
                            <h4 class="card-title mb-2 text-warning">
                                Attendance History {{ ucfirst($class->grade_level) }} - {{ $class->section }}
                            </h4>
                            <p class="mb-1"><strong>Subject:</strong> {{ $schedule->subject_name }}</p>
                            <p class="mb-3">
                                <strong>Schedule:</strong> {{ $schedule->day }}
                                ({{ \Carbon\Carbon::parse($schedule->start_time)->format('g:i A') }} -
                                {{ \Carbon\Carbon::parse($schedule->end_time)->format('g:i A') }})
                            </p>

                            <form method="POST" action="{{ route('teacher.submitAttendance') }}">
                                @csrf
                                <input type="hidden" name="schedule_id" value="{{ $schedule->id }}">
                                <input type="hidden" name="class_id" value="{{ $class->id }}">
                                <input type="hidden" name="teacher_id" value="{{ auth()->id() }}">
                                <input type="hidden" name="date" value="{{ $targetDate }}">


                                {{-- MALE STUDENTS --}}
                                <table class="table table-responsive table-bordered align-middle mb-4">
                                    <thead class="table-info">
                                        <tr>
                                            <th style="width: 40px;">No.</th>
                                            <th>Name</th>
                                            <th style="width: 160px;">Status</th>
                                            <th style="width: 120px;">Time In</th>
                                            <th style="width: 120px;">Time Out</th>
                                            <th style="width: 160px;">Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php $maleIndex = 1; @endphp
                                        @foreach ($students->where('gender', 'Male')->sortBy(function ($student) {
            return strtolower($student->student_lName . ' ' . $student->student_fName . ' ' . $student->student_mName);
        }) as $student)
                                            @php $existing = $attendances[$student->id] ?? null; @endphp
                                            <tr>
                                                <td>{{ $maleIndex++ }}</td>
                                                <td>{{ $student->student_lName }}, {{ $student->student_fName }}
                                                    {{ $student->student_mName }}</td>
                                                <td>
                                                    <div class="d-flex justify-content-between align-items-center gap-2">
                                                        @php
                                                            $status = $existing->status ?? 'absent';
                                                            $badgeClass = match ($status) {
                                                                'present' => 'bg-label-success',
                                                                'late' => 'bg-label-warning',
                                                                'absent' => 'bg-label-danger',
                                                                default => 'bg-label-secondary',
                                                            };
                                                        @endphp
                                                        <span class="badge {{ $badgeClass }} text-uppercase">
                                                            {{ ucfirst($status) }}
                                                        </span>
                                                        <select name="attendance[{{ $student->id }}][status]"
                                                            class="form-select w-auto">
                                                            <option value="present"
                                                                {{ $existing && $existing->status == 'present' ? 'selected' : '' }}>
                                                                Present</option>
                                                            <option value="late"
                                                                {{ $existing && $existing->status == 'late' ? 'selected' : '' }}>
                                                                Late</option>
                                                            <option value="absent"
                                                                {{ !$existing || $existing->status == 'absent' ? 'selected' : '' }}>
                                                                Absent</option>
                                                        </select>
                                                    </div>
                                                </td>
                                                <td>{{ $existing?->time_in ?? '-' }}</td>
                                                <td>{{ $existing?->time_out ?? '-' }}</td>
                                                <td>{{ \Carbon\Carbon::parse($targetDate)->format('F j, Y') }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>

                                {{-- FEMALE STUDENTS --}}
                                <table class="table table-bordered align-middle">
                                    <thead class="table-danger">
                                        <tr>
                                            <th style="width: 40px;">No.</th>
                                            <th>Name</th>
                                            <th style="width: 160px;">Status</th>
                                            <th style="width: 120px;">Time In</th>
                                            <th style="width: 120px;">Time Out</th>
                                            <th style="width: 160px;">Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php $femaleIndex = 1; @endphp
                                        @foreach ($students->where('gender', 'Female')->sortBy(function ($student) {
            return strtolower($student->student_lName . ' ' . $student->student_fName . ' ' . $student->student_mName);
        }) as $student)
                                            @php $existing = $attendances[$student->id] ?? null; @endphp
                                            <tr>
                                                <td>{{ $femaleIndex++ }}</td>
                                                <td>{{ $student->student_lName }}, {{ $student->student_fName }}
                                                    {{ $student->student_mName }}</td>
                                                <td>
                                                    <div class="d-flex justify-content-between align-items-center gap-2">
                                                        @php
                                                            $status = $existing->status ?? 'absent';
                                                            $badgeClass = match ($status) {
                                                                'present' => 'bg-label-success',
                                                                'late' => 'bg-label-warning',
                                                                'absent' => 'bg-label-danger',
                                                                default => 'bg-label-secondary',
                                                            };
                                                        @endphp
                                                        <span class="badge {{ $badgeClass }} text-uppercase">
                                                            {{ ucfirst($status) }}
                                                        </span>
                                                        <select name="attendance[{{ $student->id }}][status]"
                                                            class="form-select w-auto">
                                                            <option value="present"
                                                                {{ $existing && $existing->status == 'present' ? 'selected' : '' }}>
                                                                Present</option>
                                                            <option value="late"
                                                                {{ $existing && $existing->status == 'late' ? 'selected' : '' }}>
                                                                Late</option>
                                                            <option value="absent"
                                                                {{ !$existing || $existing->status == 'absent' ? 'selected' : '' }}>
                                                                Absent</option>
                                                        </select>
                                                    </div>
                                                </td>
                                                <td>{{ $existing?->time_in ?? '-' }}</td>
                                                <td>{{ $existing?->time_out ?? '-' }}</td>
                                                <td>{{ \Carbon\Carbon::parse($targetDate)->format('F j, Y') }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>

                                <div class="mt-3 text-end">
                                    <button type="submit" class="btn btn-success">
                                        <i class="bx bx-check-circle"></i> Submit Attendance
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                    {{-- / Attendance Card --}}


                </div>
                <!-- End Content wrapper -->


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

    <script>
        document.getElementById('dateForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const selectedDate = document.getElementById('date').value;
            const baseUrl = "{{ url('attendanceHistory/' . $class->grade_level . '/' . $class->section) }}";

            // If no date, use current
            const finalUrl = selectedDate ? `${baseUrl}/${selectedDate}` : baseUrl;

            window.location.href = finalUrl;
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
    </style>
@endpush
