@extends('./layouts.main')

@section('title', 'Admin | Class Promotion')


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
            <li class="menu-item active open">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons bx bxs-graduation"></i>
                    <div class="text-info">Students</div>
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
                    <li class="menu-item active">
                        <a href="{{ route('students.promote') }}" class="menu-link bg-dark text-light">
                            <div class="text-warning">Class Re-Enrollment</div>
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
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4 text-warning"><span class="text-muted fw-light">
                <a class="text-muted fw-light" href="{{ route('home') }}">Dashboard / </a>
                <a class="text-muted fw-light" href="{{ route('show.students') }}"> Students / </a>
            </span> Promote Students
        </h4>

        <h2 class="text-center text-info fw-bold">Class Re-Enrollment for {{ $currentSchoolYear }}</h2>

        {{-- Section Selection --}}
        <div class="row mb-4 d-flex justify-content-between align-items-center">
            {{-- Section Selection --}}
            <div class="col-md-4">
                <form method="GET" action="{{ route('students.promote.view') }}">
                    <label for="section" class="form-label">Select Section</label>
                    <select name="section" id="section" class="form-select" onchange="this.form.submit()">
                        @foreach ($sections as $s)
                            <option value="{{ $s }}" {{ $selectedSection == $s ? 'selected' : '' }}>
                                Section {{ $s }}
                            </option>
                        @endforeach
                    </select>
                </form>
            </div>
        </div>
        {{-- / Section Selection --}}

        <!-- Card for All Grade Levels by Section -->
        <section id="services" class="services section">
            <div class="container" data-aos="fade-up" data-aos-delay="100">
                <div class="row gy-5">
                    {{-- @if ($classes->isEmpty())
                                    <div class="col-12">
                                        <div class="alert alert-warning text-center">
                                            No students available for promotion in this section.
                                        </div>
                                    </div>
                                @endif --}}

                    @php $iconIndex = 1; @endphp

                    @foreach ($classes as $class)
                        @if ($class->promotable_count > 0)
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
                                        <a href="{{ route('students.promote.view') }}?grade_level={{ $class->grade_level }}&section={{ $class->section }}"
                                            class="stretched-link">
                                            <h3>
                                                @if (strtolower($class->grade_level) === 'kindergarten')
                                                    Kindergarten
                                                @else
                                                    Grade
                                                    {{ preg_replace('/[^0-9]/', '', $class->grade_level) }}
                                                @endif
                                                - {{ $class->section }}
                                            </h3>
                                            <h5 class="text-primary mt-2">
                                                {{ $class->promotable_count }}
                                                student{{ $class->promotable_count > 1 ? 's' : '' }} ready for
                                                promotion
                                            </h5>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach

                    @if ($classes->where('promotable_count', '>', 0)->isEmpty())
                        <div class="col-12">
                            <div class="alert alert-warning text-center">
                                No students available for promotion in this section.
                            </div>
                        </div>
                    @endif

                </div>
            </div>
        </section>
        <!-- /Card for All Grade Levels by Section -->

        <hr class="my-5" />

    </div>
    <!-- Content wrapper -->

@endsection

@push('scripts')
    <!-- Place this tag in your head or just before your close body tag. -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>

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
