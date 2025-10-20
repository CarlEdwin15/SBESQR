@extends('./layouts.main')

@section('title', 'Teacher | My Class')

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
                    </span>
                    {{ ucfirst(str_replace('_', ' ', $class->grade_level)) }} - {{ $class->section }}
                    ({{ $selectedYear }})
                </h4>

            </div>
        </div>

        <div class="d-flex justify-content-between align-items-center">
            <a href="{{ route('teacher.myClasses', ['grade_level' => $class->grade_level, 'school_year' => $selectedYear]) }}"
                class="btn btn-danger mb-3 d-flex align-items-center">
                <i class='bx bx-chevrons-left'></i>
                <span class="d-none d-sm-block">Back</span>
            </a>
        </div>

        <div class="card p-4 shadow-sm mb-4">
            <h4 class="fw-bold text-warning text-center">Class Management</h4>
            <div class="row g-4 mb-4">
                <!-- Students -->
                <div class="col-md-4">
                    <div class="card card-hover border-0 shadow-sm h-100 bg-light">
                        <div class="card-body d-flex align-items-center">
                            <div class="me-3 flex-shrink-0">
                                @if ($class)
                                    <img src="{{ asset('assetsDashboard/img/icons/dashIcon/studentIcon.png') }}"
                                        alt="Students" class="rounded" style="width:90px; height:90px;" />
                                @endif
                            </div>
                            <div>
                                <h5 class="fw-semibold mb-2">Students</h5>
                                <div class="display-6 fw-bold text-primary">{{ $studentCount }}</div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /Students -->

                <!-- Attendance Today -->
                <div class="col-md-4">
                    <a href="{{ route('teacher.attendanceHistory', [
                        'grade_level' => $class->grade_level,
                        'section' => $class->section,
                    ]) }}?school_year={{ $selectedYear }}&date={{ now()->format('Y-m-d') }}"
                        class="card card-hover border-0 shadow-sm h-100 bg-light text-decoration-none text-dark">
                        <div class="card-body d-flex align-items-center">
                            <div class="me-3 flex-shrink-0">
                                @if ($class)
                                    <img src="{{ asset('assetsDashboard/img/icons/dashIcon/attendanceIcon.png') }}"
                                        alt="Attendance Today" class="rounded" style="width:90px; height:90px;" />
                                @endif
                            </div>
                            <div>
                                <h5 class="fw-semibold mb-2">Attendance Today</h5>
                                <div class="display-6 fw-bold text-success">{{ $attendanceToday }}%</div>
                            </div>
                        </div>
                    </a>
                </div>
                <!-- /Attendance Today -->

                <!-- Teacher -->
                <div class="col-md-4">
                    <div class="card card-hover border-0 shadow-sm h-100 bg-light">
                        <div class="card-body d-flex align-items-center">
                            <div class="me-3 flex-shrink-0">
                                @if (isset($class->adviser) && $class->adviser)
                                    @if ($class->adviser->gender === 'male')
                                        <img src="{{ asset('assetsDashboard/img/icons/dashIcon/adviser.png') }}"
                                            alt="Adviser (Male)" class="rounded" style="width:90px; height:90px;" />
                                    @elseif($class->adviser->gender === 'female')
                                        <img src="{{ asset('assetsDashboard/img/icons/dashIcon/teacherIcon.png') }}"
                                            alt="Adviser (Female)" class="rounded" style="width:90px; height:90px;" />
                                    @endif
                                @else
                                    <img src="{{ asset('assetsDashboard/img/icons/dashIcon/teacherIcon.png') }}"
                                        alt="No Adviser" class="rounded" style="width:90px; height:90px; opacity:0.5;" />
                                @endif
                            </div>
                            <div>
                                <h5 class="fw-semibold mb-2">Adviser</h5>
                                <div class="fw-bold text-primary">
                                    {{ $class->adviser->firstName ?? 'N/A' }}
                                    {{ $class->adviser->lastName ?? '' }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /Teacher -->

            </div>

            <!-- Card Links -->
            <div class="row g-3 mb-5 d-flex justify-content-center">

                <!-- Schedules -->
                <div class="col-md-3">
                    <a href="{{ route('teacher.mySchedule', ['grade_level' => $class->grade_level, 'section' => $class->section]) }}?school_year={{ $selectedYear }}"
                        class="card card-hover border-0 shadow-sm text-center py-4 bg-primary text-white h-100">
                        <i class="bi bi-clock-history fs-2 mb-2"></i>
                        <div class="fw-semibold">Schedules</div>
                    </a>
                </div>

                <!-- Attendance Records -->
                <div class="col-md-3">
                    <a href="{{ route('teacher.myAttendanceRecord', ['grade_level' => $class->grade_level, 'section' => $class->section]) }}?school_year={{ $selectedYear }}"
                        class="card card-hover border-0 shadow-sm text-center py-4 bg-info text-white h-100">
                        <i class="bi bi-clipboard-check fs-2 mb-2"></i>
                        <div class="fw-semibold">Attendance Records</div>
                    </a>
                </div>

                <!-- Master's List -->
                <div class="col-md-3">
                    <a href="{{ route('teacher.myClassMasterList', ['grade_level' => $class->grade_level, 'section' => $class->section]) }}?school_year={{ $selectedYear }}"
                        class="card card-hover border-0 shadow-sm text-center py-4 bg-success text-white h-100">
                        <i class="bi bi-list-ul fs-2 mb-2"></i>
                        <div class="fw-semibold">Master's List</div>
                    </a>
                </div>

                <!-- Subjects & Grades -->
                <div class="col-md-3">
                    <a href="{{ route('teacher.myClassSubject', ['grade_level' => $class->grade_level, 'section' => $class->section]) }}?school_year={{ $selectedYear }}"
                        class="card card-hover border-0 shadow-sm text-center py-4 bg-warning text-white h-100">
                        <i class="bx bx-book fs-2 mb-4"></i>
                        <div class="fw-semibold">Subjects & Grades</div>
                    </a>
                </div>
            </div>

        </div>
    </div>
    <!-- Content wrapper -->

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
@endpush

@push('styles')
    <!-- Main CSS File -->
    <link href="{{ asset('assets/css/main.css') }}" rel="stylesheet" />
    <link href="{{ asset('assetsDashboard/vendor/css/core.css') }}" rel="stylesheet" />
    <link href="{{ asset('assetsDashboard/vendor/css/theme-default.css') }}" rel="stylesheet" />

    <!-- Vendor CSS Files -->
    <link href="{{ asset('assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet" />

    <style>
        .card-hover {
            transition: all 0.3s ease;
        }

        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.12);
        }

        .card-body img {
            transition: transform 0.2s ease;
        }

        .card-body img:hover {
            transform: scale(1.08);
        }
    </style>
@endpush
