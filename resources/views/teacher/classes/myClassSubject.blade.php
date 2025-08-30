@extends('./layouts.main')

@section('title', 'Teacher | Subjects')

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
                                        href="{{ route('teacher.myClass', ['grade_level' => $class->grade_level, 'section' => $class->section, 'school_year' => $selectedYear]) }}">
                                        {{ ucfirst($class->grade_level) }} - {{ $class->section }} ({{ $selectedYear }})
                                    </a> /
                                </span>
                                Subjects
                            </h4>
                        </div>
                    </div>

                    <h3 class="text-center text-info fw-bold mb-4">Subjects for {{ ucfirst($class->grade_level) }} -
                        {{ $class->section }}
                        ({{ $selectedYear }})</h3>

                    <div class="d-flex justify-content-between align-items-center flex-wrap mb-3">
                        <div class="d-flex gap-2 mb-2 mb-md-0">
                            <a href="{{ route('teacher.myClass', ['grade_level' => $class->grade_level, 'section' => $class->section, 'school_year' => $selectedYear]) }}"
                                class="btn btn-danger d-flex align-items-center">
                                <i class='bx bx-chevrons-left'></i>
                                <span class="d-none d-sm-block">Back</span>
                            </a>
                            <button type="button" class="btn btn-primary d-flex align-items-center"
                                data-bs-toggle="modal" data-bs-target="#addSubjectModal">
                                <i class='bx bx-book-add me-2'></i>
                                <span class="d-none d-sm-block">Add Subject</span>
                            </button>
                        </div>
                        <div>
                            <a href="" class="btn btn-success d-flex align-items-center">
                                <i class='bx bx-printer me-2'></i><span class="d-none d-sm-block">Export</span>
                            </a>
                        </div>
                    </div>

                    <div class="card p-4 shadow-sm">
                        <!-- Subject List -->
                        <div class="card-body">
                            @if ($classSubjects->isEmpty())
                                <div class="text-center py-5">
                                    <i class="bi bi-book text-info display-4"></i>
                                    <h5 class="mt-3 fw-bold text-secondary">No subjects yet</h5>
                                    <p class="text-muted">Start by adding a new subject to this class.</p>
                                </div>
                            @else
                                <div class="row g-4">
                                    @foreach ($classSubjects as $classSubject)
                                        <div class="col-md-4 col-sm-6">
                                            <div
                                                class="card subject-card border-0 shadow-sm rounded-4 h-100 overflow-hidden">

                                                @php
                                                    // Generate random background index (1 to 3)
                                                    $bgIndex = rand(1, 3);

                                                    // Pick random gradient colors based on subject name (same as before)
                                                    $color1 = '#' . substr(md5($classSubject->subject->name), 0, 6);
                                                    $color2 =
                                                        '#' . substr(md5(strrev($classSubject->subject->name)), 0, 6);
                                                @endphp

                                                <!-- Card Header -->
                                                <div class="card-header text-white border-0 position-relative p-4"
                                                    style="
                                                        background:
                                                            linear-gradient(135deg, {{ $color1 }}cc, {{ $color2 }}cc),
                                                            url('{{ asset("assetsDashboard/img/subject-card-bg/bg$bgIndex.jpg") }}');
                                                        background-size: cover;
                                                        background-position: center;
                                                        height: 140px;
                                                    ">
                                                    <h4 class="card-title text-white fw-bold mb-1">
                                                        {{ $classSubject->subject->name }}
                                                    </h4>
                                                    <p class="subject-desc text-auto small mb-0">
                                                        {{ $classSubject->description }}
                                                    </p>

                                                    <!-- Avatar -->
                                                    <div class="avatar-wrapper position-absolute"
                                                        style="bottom: -20px; right: 20px;">
                                                        <img src="https://ui-avatars.com/api/?name={{ urlencode($classSubject->subject->name) }}&background=random"
                                                            class="rounded-circle border border-3 border-white shadow-sm"
                                                            width="60" height="60" alt="Subject Avatar">
                                                    </div>
                                                </div>


                                                <!-- Card Body -->
                                                <div class="card-body pt-2">
                                                    <div class="d-flex align-items-center">
                                                        <div class="d-flex align-items-center">
                                                            <img src="{{ $classSubject->teacher->profile_photo ? asset('storage/' . $classSubject->teacher->profile_photo) : asset('assetsDashboard/img/profile_pictures/teachers_default_profile.jpg') }}"
                                                                alt="Teacher Profile" class="rounded-circle me-2"
                                                                width="30" height="30">
                                                            <small class="text-muted">
                                                                <span
                                                                    class="fw-semibold">{{ $classSubject->teacher->firstName ?? 'Unknown' }}
                                                                    {{ $classSubject->teacher->lastName ?? '' }}</span>
                                                            </small>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Card Footer -->
                                                <div
                                                    class="card-footer bg-white border-0 d-flex justify-content-end align-items-center">
                                                    <div class="dropdown">
                                                        <button class="btn btn-sm btn-outline-secondary rounded-circle"
                                                            data-bs-toggle="dropdown" aria-expanded="false">
                                                            <i class="bi bi-three-dots-vertical"></i>
                                                        </button>
                                                        <ul class="dropdown-menu dropdown-menu-end shadow-sm rounded-3">
                                                            <li><a class="dropdown-item text-warning" href="#"><i
                                                                        class="bi bi-pencil me-2"></i>Edit</a></li>
                                                            <li><a class="dropdown-item text-danger" href="#"><i
                                                                        class="bi bi-trash me-2"></i>Delete</a></li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                        <!-- /Subject List -->

                    </div>

                    <!-- Add Subject Modal -->
                    <div class="modal fade" id="addSubjectModal" data-bs-backdrop="static" tabindex="-1">
                        <div class="modal-dialog">
                            <form class="modal-content"
                                action="{{ route('teacher.subjects.create', ['grade_level' => $class->grade_level, 'section' => $class->section]) }}"
                                method="POST">
                                @csrf
                                <div class="modal-header">
                                    <h4 class="modal-title fw-bold text-info">
                                        ADD NEW SUBJECT
                                    </h4>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>

                                <div class="modal-body">
                                    <input type="hidden" name="selected_school_year" value="{{ $selectedYear }}">

                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Subject Name</label>
                                        <select id="subjectSelect" name="name" required>
                                            <option value="">-- Select Subject --</option>
                                            <option value="Araling Panlipunan">Araling Panlipunan</option>
                                            <option value="Edukasyon sa Pagpapakatao">Edukasyon sa Pagpapakatao</option>
                                            <option value="English">English</option>
                                            <option value="Filipino">Filipino</option>
                                            <option value="MAPEH">MAPEH</option>
                                            <option value="Mathematics">Mathematics</option>
                                            <option value="Mother Tongue">Mother Tongue</option>
                                            <option value="Science">Science</option>
                                            <option value="Technology and Livelihood Education">Technology and Livelihood
                                                Education</option>
                                            <option value="Others">Others...</option>
                                        </select>

                                        <!-- Hidden custom input -->
                                        <input type="text" id="customSubject" name="custom_name"
                                            class="form-control mt-2" placeholder="Enter custom subject name"
                                            style="display:none;">
                                    </div>

                                    <script>
                                        document.addEventListener("DOMContentLoaded", function() {
                                            const select = document.getElementById('subjectSelect');
                                            const customInput = document.getElementById('customSubject');

                                            select.addEventListener('change', function() {
                                                if (this.value === 'Others') {
                                                    customInput.style.display = 'block';
                                                    customInput.required = true;
                                                } else {
                                                    customInput.style.display = 'none';
                                                    customInput.required = false;
                                                }
                                            });
                                        });
                                    </script>

                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Description</label>
                                        <textarea name="description" class="form-control" rows="3" placeholder="Optional subject description"></textarea>
                                    </div>
                                </div>

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                        Close
                                    </button>
                                    <button type="submit" class="btn btn-primary">Add Subject</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <!-- /Add Subject Modal -->

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
    <script src="https://cdn.jsdelivr.net/npm/tom-select/dist/js/tom-select.complete.min.js"></script>

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
        document.addEventListener("DOMContentLoaded", function() {
            // Initialize Tom Select
            new TomSelect("#subjectSelect", {
                create: false, // keep "Others" handling instead of free typing
                sortField: {
                    field: "text"
                }
            });

            // Handle "Others" option for custom input
            const select = document.getElementById('subjectSelect');
            const customInput = document.getElementById('customSubject');

            select.addEventListener('change', function() {
                if (this.value === 'Others') {
                    customInput.style.display = 'block';
                    customInput.required = true;
                } else {
                    customInput.style.display = 'none';
                    customInput.required = false;
                }
            });
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

    <link href="https://cdn.jsdelivr.net/npm/tom-select/dist/css/tom-select.css" rel="stylesheet">

    <style>
        /* Tom Select custom look */
        .ts-control {
            background-color: #e0f7fa !important;
            border-color: #42a5f5 !important;
            border-radius: 6px !important;
            padding: 6px 10px !important;
            min-height: 42px;
            font-size: 14px;
        }

        .ts-control .item {
            background-color: #4dd0e1;
            color: white;
            border-radius: 4px;
            padding: 3px 8px;
            margin-right: 4px;
            font-weight: 500;
        }

        .ts-dropdown .option.active {
            background-color: #e3f2fd;
            color: #1976d2;
        }

        .ts-dropdown .option {
            padding: 8px 12px;
            border-radius: 4px;
            transition: background 0.2s ease;
        }

        /* Subject Cards */
        .subject-card {
            transition: transform 0.25s ease, box-shadow 0.25s ease;
            cursor: pointer;
        }

        .subject-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
        }

        /* Avatar */
        .avatar-wrapper img {
            transition: transform 0.25s ease;
        }

        .subject-card:hover .avatar-wrapper img {
            transform: scale(1.2);
        }

        /* Description truncation */
        .subject-desc {
            display: -webkit-box;
            -webkit-line-clamp: 3;
            /* show max 3 lines */
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        /* Dropdown polish */
        .dropdown-menu {
            border: none;
            font-size: 14px;
        }

        .dropdown-item i {
            font-size: 16px;
            vertical-align: middle;
        }
    </style>
@endpush
