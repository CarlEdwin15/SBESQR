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
                            <div>Dashboard</div>
                        </a>
                    </li>

                    <!-- Teachers sidebar -->
                    <li class="menu-item">
                        <a class="menu-link menu-toggle bg-dark text-light">
                            <i class="menu-icon tf-icons bx bx-user-pin text-light"></i>
                            <div class="text-light">Teachers</div>
                        </a>

                        <ul class="menu-sub">
                            <li class="menu-item">
                                <a href="{{ route('show.teachers') }}" class="menu-link bg-dark text-light">
                                    <div class="text-light">All Teacherss</div>
                                </a>
                            </li>
                        </ul>
                    </li>

                    {{-- Students sidebar --}}
                    <li class="menu-item">
                        <a class="menu-link menu-toggle bg-dark text-light">
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
                                <a href="" class="menu-link bg-dark text-light">
                                    <div class="text-light">Student Promotion</div>
                                </a>
                            </li>
                        </ul>
                    </li>

                    {{-- Classes sidebar --}}
                    <li class="menu-item">
                        <a class="menu-link menu-toggle bg-dark text-light">
                            <i class="menu-icon tf-icons bx bx-objects-horizontal-left text-light"></i>
                            <div class="text-light">Classes</div>
                        </a>
                        <ul class="menu-sub">
                            <li class="menu-item">
                                <a href="{{ route('all.grade.levels') }}" class="menu-link bg-dark text-light">
                                    <div class="text-light">Grade Levels</div>
                                </a>
                            </li>
                        </ul>
                    </li>

                    {{-- Account Settings sidebar --}}
                    <li class="menu-item">
                        <a href="cards-basic.html" class="menu-link bg-dark text-light">
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

                        <ul class="navbar-nav flex-row align-items-center ms-auto">
                            <!-- User -->
                            <li class="nav-item navbar-dropdown dropdown-user dropdown">
                                <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);"
                                    data-bs-toggle="dropdown">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar">
                                            <img src="{{ asset('assetsDashboard/img/profile_pictures/admin_profile.png') }}"
                                                alt class="w-px-40 h-auto rounded-circle" />
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
                                                            alt="Default Profile Photo" class="w-px-40 h-auto rounded-circle" />
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
                                        <a class="dropdown-item" href="#">
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
                        $totalStudents = \App\Models\Student::count();
                        $totalTeachers = \App\Models\User::where('role', 'teacher')->count();
                        $newlyEnrolledStudents = \App\Models\Student::where(
                            'created_at',
                            '>=',
                            now()->subMonth(),
                        )->count();
                    @endphp

                    <div class="container-xxl container-p-y">

                        <div class="row mb-4 g-3">

                            <!-- Student Card -->
                            <div class="col-6 col-md-3">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <div class="card-title d-flex align-items-start justify-content-between">
                                            <div class="avatar flex-shrink-0">
                                                <a href="{{ route('show.students') }}">
                                                    <img src="{{ asset('assetsDashboard/img/icons/dashIcon/studentIcon.png') }}"
                                                        alt="Students" class="rounded" />
                                                </a>
                                            </div>
                                            <div class="dropdown">
                                                <button class="btn p-0" type="button" data-bs-toggle="dropdown">
                                                    <i class="bx bx-dots-vertical-rounded"></i>
                                                </button>
                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item" href="{{ route('show.students') }}">View
                                                        More</a>
                                                </div>
                                            </div>
                                        </div>
                                        <span class="fw-semibold d-block mb-1">Students</span>
                                        <h3 class="card-title mb-2">{{ $totalStudents }}</h3>
                                    </div>
                                </div>
                            </div>

                            <!-- Teachers Card -->
                            <div class="col-6 col-md-3">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <div class="card-title d-flex align-items-start justify-content-between">
                                            <div class="avatar flex-shrink-0">
                                                <a href="{{ route('show.teachers') }}">
                                                    <img src="{{ asset('assetsDashboard/img/icons/dashIcon/teacherIcon.png') }}"
                                                        alt="Teachers" class="rounded" />
                                                </a>
                                            </div>
                                            <div class="dropdown">
                                                <button class="btn p-0" type="button" data-bs-toggle="dropdown">
                                                    <i class="bx bx-dots-vertical-rounded"></i>
                                                </button>
                                                <div class="dropdown-menu dropdown-menu-end">
                                                    <a class="dropdown-item" href="{{ route('show.teachers') }}">View
                                                        More</a>
                                                </div>
                                            </div>
                                        </div>
                                        <span class="fw-semibold d-block mb-1">Teachers</span>
                                        <h3 class="card-title mb-2">{{ $totalTeachers }}</h3>
                                    </div>
                                </div>
                            </div>

                            <!-- Classes Card -->
                            <div class="col-6 col-md-3">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <div class="card-title d-flex align-items-start justify-content-between">
                                            <div class="avatar flex-shrink-0">
                                                <img src="{{ asset('assetsDashboard/img/icons/dashIcon/classroomIcon.png') }}"
                                                    alt="Classes" class="rounded" />
                                            </div>
                                            <div class="dropdown">
                                                <button class="btn p-0" type="button" data-bs-toggle="dropdown">
                                                    <i class="bx bx-dots-vertical-rounded"></i>
                                                </button>
                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item" href="javascript:void(0);">View More</a>
                                                </div>
                                            </div>
                                        </div>
                                        <span class="d-block mb-1">Classes</span>
                                        <h3 class="card-title text-nowrap mb-2">0</h3>
                                    </div>
                                </div>
                            </div>

                            <!-- Newly Enrolled Card -->
                            <div class="col-6 col-md-3">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <div class="card-title d-flex align-items-start justify-content-between">
                                            <div class="avatar flex-shrink-0">
                                                <a href="{{ route('show.students') }}">
                                                    <img src="{{ asset('assetsDashboard/img/icons/dashIcon/newStudent.png') }}"
                                                        alt="Newly Enrolled" class="rounded" />
                                                </a>
                                            </div>
                                            <div class="dropdown">
                                                <button class="btn p-0" type="button" data-bs-toggle="dropdown">
                                                    <i class="bx bx-dots-vertical-rounded"></i>
                                                </button>
                                                <div class="dropdown-menu dropdown-menu-end">
                                                    <a class="dropdown-item" href="javascript:void(0);">View More</a>
                                                </div>
                                            </div>
                                        </div>
                                        <span class="fw-semibold d-block mb-1">Newly Enrolled</span>
                                        <h3 class="card-title mb-2">{{ $newlyEnrolledStudents }}</h3>
                                    </div>
                                </div>
                            </div>

                        </div>

                        {{-- <!-- Total Revenue -->
                        <div class="col-12 col-lg-8 order-2 order-md-3 order-lg-2 mb-4">
                            <div class="card">
                                <div class="row row-bordered g-0">
                                    <div class="col-md-8">
                                        <h5 class="card-header m-0 me-2 pb-3">Total Revenue</h5>
                                        <div id="totalRevenueChart" class="px-2"></div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="card-body">
                                            <div class="text-center">
                                                <div class="dropdown">
                                                    <button class="btn btn-sm btn-outline-primary dropdown-toggle"
                                                        type="button" id="growthReportId" data-bs-toggle="dropdown"
                                                        aria-haspopup="true" aria-expanded="false">
                                                        2022
                                                    </button>
                                                    <div class="dropdown-menu dropdown-menu-end"
                                                        aria-labelledby="growthReportId">
                                                        <a class="dropdown-item" href="javascript:void(0);">2021</a>
                                                        <a class="dropdown-item" href="javascript:void(0);">2020</a>
                                                        <a class="dropdown-item" href="javascript:void(0);">2019</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="growthChart"></div>

                                        <div class="text-center fw-semibold pt-3 mb-2">62% Company Growth</div>

                                        <div
                                            class="d-flex px-xxl-4 px-lg-2 p-4 gap-xxl-3 gap-lg-1 gap-3 justify-content-between">
                                            <div class="d-flex">
                                                <div class="me-2">
                                                    <span class="badge bg-label-primary p-2"><i
                                                            class="bx bx-dollar text-primary"></i></span>
                                                </div>
                                                <div class="d-flex flex-column">
                                                    <small>2022</small>
                                                    <h6 class="mb-0">$32.5k</h6>
                                                </div>
                                            </div>
                                            <div class="d-flex">
                                                <div class="me-2">
                                                    <span class="badge bg-label-info p-2"><i
                                                            class="bx bx-wallet text-info"></i></span>
                                                </div>
                                                <div class="d-flex flex-column">
                                                    <small>2021</small>
                                                    <h6 class="mb-0">$41.2k</h6>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--/ Total Revenue --> --}}


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
