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
            $totalUsers = \App\Models\User::count();
        @endphp

        <div class="container-xxl container-p-y">

            <div class="row mb-4 g-3">

                <!-- Student Card -->
                <div class="col-6 col-md-3">
                    <div class="card h-100 card-hover">
                        <a href="{{ route('student.management') }}" class="card-body">
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

                <!-- Total Users Card -->
                <div class="col-6 col-md-3">
                    <div class="card h-100 card-hover">
                        <a href="{{ route('admin.user.management') }}" class="card-body">
                            <div class="card-title d-flex align-items-start justify-content-between">
                                <div class="avatar flex-shrink-0">
                                    <img src="{{ asset('assetsDashboard/img/icons/dashIcon/total_users.png') }}"
                                        alt="Total Users" class="rounded" />
                                </div>
                            </div>
                            <span class="fw-semibold d-block mb-1 text-primary">Total Users</span>
                            <h3 class="card-title mb-2">{{ $totalUsers }}</h3>
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
                                    <button class="btn btn-sm btn-info text-white dropdown-toggle" type="button"
                                        id="yearDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                        2025-2026
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="yearDropdown">
                                        <li><a class="dropdown-item" href="#">(Previous School
                                                Year(2024))</a></li>
                                        <li><a class="dropdown-item" href="#">(Previous School
                                                Year(2023))</a></li>
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
                                <small class="text-muted">Total: 2,000 Students</small>
                            </div>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-info text-white dropdown-toggle" type="button"
                                    id="yearDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                    2025-2026
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="yearDropdown">
                                    <li><a class="dropdown-item" href="#">(Previous School
                                            Year(2024))</a></li>
                                    <li><a class="dropdown-item" href="#">(Previous School
                                            Year(2023))</a></li>
                                </ul>
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
        // Enrollees Chart
        const ctx1 = document.getElementById('enrolleesChart').getContext('2d');

        new Chart(ctx1, {
            type: 'bar',
            data: {
                labels: ['Kindergarten', 'Grade1', 'Grade2', 'Grade3', 'Grade', 'Grade5', 'Grade6'],
                datasets: [{
                    label: 'Enrollees',
                    data: [45, 35, 42, 50, 46, 34, 43],
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
