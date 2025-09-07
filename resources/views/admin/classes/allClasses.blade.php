@extends('./layouts.main')

@section('title', 'Admin | All Classes')


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

                    <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
                        <!-- Search -->
                        <div class="navbar-nav align-items-center">

                        </div>
                        <!-- /Search -->

                        <ul class="navbar-nav flex-row align-items-center ms-auto">

                            <!-- User Profile-->
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
                                            <div class="d-flex">

                                                <div class="flex-shrink-0 me-3">
                                                    <div class="avatar">
                                                        <img src="{{ asset('assetsDashboard/img/profile_pictures/admin_profile.png') }}"
                                                            alt class="w-px-40 h-auto rounded-circle" />
                                                    </div>
                                                </div>

                                                <div class="flex-grow-1">
                                                    <span class="fw-semibold d-block">{{ Auth::user()->firstName }}</span>
                                                    <small class="text-muted">Admin</small>
                                                </div>

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
                    <h4 class="fw-bold py-3 mb-4 text-warning"><span class="text-muted fw-light">
                            <a class="text-muted fw-light" href="{{ route('home') }}">Dashboard / </a>
                            <a class="text-muted fw-light" href="{{ route('all.classes') }}"> Classes / </a>
                        </span> All Classes
                    </h4>

                    <h5 class="alert alert-primary alert-dismissible fade show mt-2 text-center text-primary fw-bold"
                        role="alert">Showing Grade Levels in <strong class="text-warning"> Section
                            {{ $section }}</strong> for
                        School Year <strong class="text-warning">{{ $selectedYear }}</strong>
                    </h5>

                    {{-- Section and School Year Selection --}}
                    <div class="d-flex justify-content-end align-items-end flex-wrap mb-3 gap-2">

                        {{-- Section Dropdown --}}
                        <div class="dropdown d-flex flex-column">
                            <label class="d-none d-sm-block form-label mb-1">Select Section</label>
                            <button class="btn btn-info text-white dropdown-toggle" type="button"
                                id="sectionDropdown" data-bs-toggle="dropdown" aria-expanded="false">
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
                            <button class="btn btn-info text-white dropdown-toggle" type="button"
                                id="yearDropdown" data-bs-toggle="dropdown" aria-expanded="false">
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
                        <form method="GET" action="{{ route('all.classes') }}"
                            class="d-flex flex-column align-items-start">
                            <label class="form-label invisible mb-1">Now</label> {{-- To align vertically --}}
                            <input type="hidden" name="school_year"
                                value="{{ $currentYear . '-' . ($currentYear + 1) }}">
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

            </div>
            <!-- / Layout page -->
        </div>
    </div>
    <!-- / Layout wrapper -->

    <!-- Place this tag in your head or just before your close body tag. -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>





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
