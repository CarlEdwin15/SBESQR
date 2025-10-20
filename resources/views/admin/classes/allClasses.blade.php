@extends('./layouts.main')

@section('title', 'Admin | All Classes')

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
            <li class="menu-item">
                <a href="{{ '/home ' }}" class="menu-link bg-dark text-light">
                    <i class="menu-icon tf-icons bx bx-home-circle text-light"></i>
                    <div class="text-light">Dashboard</div>
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
                            <div class="text-light">Student Promotion</div>
                        </a>
                    </li>
                </ul>
            </li>

            {{-- Classes sidebar --}}
            <li class="menu-item active open">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons bx bx-objects-horizontal-left"></i>
                    <div>Classes</div>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item active">
                        <a href="{{ route('all.classes') }}" class="menu-link bg-dark text-light">
                            <div class="text-warning">All Classes</div>
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

    <!-- Content wrapper -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4 text-warning"><span class="text-muted fw-light">
                <a class="text-muted fw-light" href="{{ route('home') }}">Dashboard / </a>
                <a class="text-muted fw-light" href="{{ route('all.classes') }}"> Classes / </a>
            </span> All Classes
        </h4>

        <h5 id="autoDismissAlert"
            class="alert alert-primary alert-dismissible fade show mt-2 text-center text-primary fw-bold" role="alert">
            Showing Grade Levels in
            <strong class="text-warning">Section {{ $section }}</strong>
            for School Year
            <strong class="text-warning">{{ $selectedYear }}</strong>

            <!-- Dismiss button -->
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </h5>

        <script>
            // Auto dismiss after 5 seconds
            setTimeout(() => {
                const alertElement = document.getElementById('autoDismissAlert');
                if (alertElement) {
                    const alert = bootstrap.Alert.getOrCreateInstance(alertElement);
                    alert.close();
                }
            }, 5000); // 5000ms = 5 seconds
        </script>

        {{-- Section and School Year Selection --}}
        <div class="d-flex justify-content-end align-items-end flex-wrap mb-3 gap-2">

            {{-- Section Dropdown --}}
            <div class="dropdown d-flex flex-column">
                <label class="d-none d-sm-block form-label mb-1">Select Section</label>
                <button class="btn btn-info text-white dropdown-toggle" type="button" id="sectionDropdown"
                    data-bs-toggle="dropdown" aria-expanded="false">
                    Section {{ $section }}
                </button>
                <ul class="dropdown-menu" aria-labelledby="sectionDropdown">
                    @foreach ($sections as $s)
                        <li>
                            <a class="dropdown-item"
                                href="{{ route('all.classes', [
                                    'school_year' => $selectedYear,
                                    'section' => $s,
                                ]) }}">
                                Section {{ $s }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>

            {{-- School Year Dropdown --}}
            <div class="dropdown d-flex flex-column">
                <label class="d-none d-sm-block form-label mb-1">School Year</label>
                <button class="btn btn-info text-white dropdown-toggle" type="button" id="yearDropdown"
                    data-bs-toggle="dropdown" aria-expanded="false">
                    {{ $selectedYear }}
                </button>
                <ul class="dropdown-menu" aria-labelledby="yearDropdown">
                    @foreach ($schoolYears as $year)
                        <li>
                            <a class="dropdown-item"
                                href="{{ route('all.classes', [
                                    'school_year' => $year,
                                    'section' => $section,
                                ]) }}">
                                {{ $year }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>

            {{-- "Now" Button --}}
            <form method="GET" action="{{ route('all.classes') }}" class="d-flex flex-column align-items-start">
                <label class="form-label invisible mb-1">Now</label> {{-- To align vertically --}}
                <input type="hidden" name="school_year" value="{{ $currentYear . '-' . ($currentYear + 1) }}">
                <input type="hidden" name="section" value="{{ $section }}">
                <button type="submit" class="btn btn-primary">Now</button>
            </form>

        </div>
        {{-- / Section and School Year Selection --}}


        <!-- Card for All Grade Levels by Section -->
        <section id="services" class="services section">
            <div class="container" data-aos="fade-up" data-aos-delay="100">
                <div class="row gy-5">
                    @php $iconIndex = 1; @endphp

                    @foreach ($classes as $class)
                        <div class="col-xl-4 col-md-6" data-aos="zoom-in">
                            <div class="service-item">
                                <div class="img">
                                    <img src="{{ asset('assets/img/classes/' . strtolower($class->grade_level) . '.jpg') }}"
                                        class="img-fluid" alt="" />
                                </div>
                                <div class="details position-relative">
                                    <div class="icon">
                                        @if ($class->grade_level === 'kindergarten')
                                            <i class="fa-solid fa-child"></i>
                                        @else
                                            <i class="fa-solid fa-{{ $iconIndex }}"></i>
                                            @php $iconIndex++; @endphp
                                        @endif
                                    </div>
                                    <a href="{{ route('classes.showClass', ['grade_level' => $class->grade_level, 'section' => $class->section]) }}?school_year={{ $selectedYear }}"
                                        class="stretched-link">
                                        <h3>
                                            @if (strtolower($class->grade_level) === 'kindergarten')
                                                Kindergarten
                                            @else
                                                Grade {{ preg_replace('/[^0-9]/', '', $class->grade_level) }}
                                            @endif
                                            - {{ $class->section }}
                                        </h3>
                                        <h5>Adviser:</h5>

                                        @if ($class->adviser)
                                            <h5 class="text-info">{{ $class->adviser->firstName }}
                                                {{ $class->adviser->lastName }}</h5>
                                        @else
                                            <h6 class="text-warning">No adviser assigned</h6>
                                        @endif

                                        @php
                                            $studentCount = \App\Models\ClassStudent::where('class_id', $class->id)
                                                ->whereHas('schoolYear', function ($q) use ($selectedYear) {
                                                    $q->where('school_year', $selectedYear);
                                                })
                                                ->whereIn('enrollment_status', ['enrolled', 'active'])
                                                ->count();
                                        @endphp

                                        @if ($studentCount > 0)
                                            <span class="text-success fw-bold">{{ $studentCount }}</span> students
                                            enrolled
                                        @else
                                            <span class="text-muted">No students enrolled</span>
                                        @endif
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach

                </div>
            </div>
        </section>
        <!-- /Card for All Grade Levels by Section -->

        <hr class="my-5" />

    </div>
    <!-- Content wrapper -->

@endsection

@push('scripts')
    <script>
        // Logout confirmation
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
                    document.getElementById('logout-form').submit();
                }
            });
        }

        // Success alert
        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: '{{ session('success') }}',
                confirmButtonColor: '#3085d6',
                customClass: {
                    container: 'my-swal-container'
                }
            });
        @endif
    </script>

    <!-- Font Awesome -->
    <script src="https://kit.fontawesome.com/ab677fe211.js" crossorigin="anonymous"></script>
@endpush

@push('styles')
    <!-- Main CSS File -->
    <link href="{{ asset('assets/css/main.css') }}" rel="stylesheet" />
    <link href="{{ asset('assetsDashboard/vendor/css/core.css') }}" rel="stylesheet" />
    <link href="{{ asset('assetsDashboard/vendor/css/theme-default.css') }}" rel="stylesheet" />

    <!-- Vendor CSS Files -->
    <link href="{{ asset('assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet" />
@endpush
