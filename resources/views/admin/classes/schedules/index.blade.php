@extends('./layouts.main')

@section('title', 'Admin | Schedules')


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
                                    <div class="text-light">All Teacherss</div>
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
                                <a href="" class="menu-link bg-dark text-light">
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
                                    <div class="text-danger">All Classes</div>
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
                    <h4 class="fw-bold text-warning mb-2">
                        <span class="text-muted fw-light">
                            <a class="text-muted fw-light" href="{{ route('home') }}">Dashboard</a> /
                            <a class="text-muted fw-light" href="{{ route('all.classes') }}">Classes</a> /
                            <a class="text-muted fw-light"
                                href="{{ route('classes.showClass', ['grade_level' => $class->grade_level, 'section' => $class->section]) }}">
                                {{ ucfirst($class->grade_level) }} - {{ $class->section }} </a> /
                        </span>
                        Schedules
                    </h4>

                    <!-- Modal Backdrop -->
                    <div class="col-lg-4 col-md-3">

                        <div class="mt-3">

                            <div class="d-flex align-items-center gap-2 mb-3 mt-5">
                                <button type="button" class="btn btn-danger d-flex align-items-center gap-1"
                                    onclick="handleCancel()">
                                    <i class="bx bx-left-arrow-alt"></i>
                                    <span class="d-sm-block">Back</span>
                                </button>
                                <button type="button" class="btn btn-primary d-flex align-items-center gap-1"
                                    data-bs-toggle="modal" data-bs-target="#backDropModal"
                                    style="margin-top: 0; margin-bottom: 0;">
                                    Add New Schedule
                                    <i class="bx bx-calendar-plus ms-2"></i>
                                </button>
                            </div>
                            <script>
                                function handleCancel() {
                                    window.location.href =
                                        "{{ route('classes.showClass', ['grade_level' => $class->grade_level, 'section' => $class->section]) }}";
                                }
                            </script>

                            <!-- Add Schedule Modal -->
                            <div class="modal fade" id="backDropModal" data-bs-backdrop="static" tabindex="-1">
                                <div class="modal-dialog">
                                    <form class="modal-content"
                                        action="{{ route('classes.addSchedule', ['grade_level' => $class->grade_level, 'section' => $class->section]) }}"
                                        method="POST" enctype="multipart/form-data">

                                        @csrf
                                        <div class="modal-header">
                                            <h4 class="modal-title fw-bold text-info" id="backDropModalTitle">
                                                ADD NEW SCHEDULE
                                            </h4>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>

                                        <div class="modal-body">

                                            <!-- Subject Name -->
                                            <div class="mb-3">
                                                <label for="subject_name" class="form-label fw-semibold">Subject
                                                    Name</label>
                                                <input type="text" class="form-control" id="subject_name"
                                                    name="subject_name" required>
                                            </div>

                                            <!-- Teacher Selection -->
                                            <div class="mb-3">
                                                <label for="teacher_id" class="form-label fw-semibold">Teacher</label>
                                                <select class="form-select" id="teacher_id" name="teacher_id" required>
                                                    <option value="" selected disabled>Select Teacher</option>
                                                    @foreach ($teachers as $teacher)
                                                        @php
                                                            $role = $teacher->pivot->role;
                                                            $roleLabel = match ($role) {
                                                                'adviser' => ' (Adviser)',
                                                                'subject_teacher' => ' (Subject Teacher)',
                                                                'both' => ' (Adviser & Subject Teacher)',
                                                                default => '',
                                                            };
                                                        @endphp
                                                        <option value="{{ $teacher->id }}">
                                                            {{ $teacher->firstName }}
                                                            {{ $teacher->lastName }}{{ $roleLabel }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>


                                            <!-- Day Selection -->
                                            <div class="mb-3">
                                                <label class="form-label fw-semibold">Day</label>
                                                <div class="d-flex flex-wrap gap-3">
                                                    @foreach (['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'] as $day)
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox"
                                                                name="days[]" value="{{ $day }}"
                                                                id="day_{{ strtolower($day) }}">
                                                            <label class="form-check-label"
                                                                for="day_{{ strtolower($day) }}">{{ $day }}</label>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>

                                            <!-- Start and End Time -->
                                            <div class="mb-3 row">
                                                <div class="col">
                                                    <label for="start_time" class="form-label fw-semibold">Start
                                                        Time</label>
                                                    <input type="time" class="form-control" id="start_time"
                                                        name="start_time" required>
                                                </div>
                                                <div class="col">
                                                    <label for="end_time" class="form-label fw-semibold">End Time</label>
                                                    <input type="time" class="form-control" id="end_time"
                                                        name="end_time" required>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-outline-secondary"
                                                data-bs-dismiss="modal">
                                                Close
                                            </button>
                                            <button type="submit" class="btn btn-primary"
                                                id="registerTeacherBtn">Add</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <!-- /Add Schedule Modal -->


                        </div>
                    </div>

                    {{-- Card --}}
                    <div class="card">
                        <div class="card-header">
                            <h4 class="fw-bold mb-4 text-center">Schedules for <span
                                    class="text-info">{{ ucfirst($class->grade_level) }} - {{ $class->section }} </span>
                            </h4>

                            <div class="container-xxl flex-grow-1 container-p-y">
                                <div class="row g-4 mb-4">
                                    @php
                                        // Group schedules by subject, teacher, start_time, end_time
                                        $grouped = [];
                                        foreach ($schedules as $schedule) {
                                            $key =
                                                $schedule->subject_name .
                                                '|' .
                                                ($schedule->teacher ? $schedule->teacher->id : '0') .
                                                '|' .
                                                $schedule->start_time .
                                                '|' .
                                                $schedule->end_time;
                                            if (!isset($grouped[$key])) {
                                                $grouped[$key] = [
                                                    'subject_name' => $schedule->subject_name,
                                                    'teacher' => $schedule->teacher,
                                                    'start_time' => $schedule->start_time,
                                                    'end_time' => $schedule->end_time,
                                                    'days' => [],
                                                ];
                                            }
                                            // Handle day as array or string
                                            if (is_array($schedule->day)) {
                                                $grouped[$key]['days'] = array_merge(
                                                    $grouped[$key]['days'],
                                                    $schedule->day,
                                                );
                                            } elseif (is_string($schedule->day)) {
                                                $decoded = json_decode($schedule->day, true);
                                                if (is_array($decoded)) {
                                                    $grouped[$key]['days'] = array_merge(
                                                        $grouped[$key]['days'],
                                                        $decoded,
                                                    );
                                                } else {
                                                    $grouped[$key]['days'][] = $schedule->day;
                                                }
                                            }
                                        }

                                        $dayOrder = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];

                                        // Remove duplicate days and sort
                                        foreach ($grouped as &$item) {
                                            $item['days'] = array_unique($item['days']);

                                            // Custom sort using array_intersect to preserve the correct order
                                            $item['days'] = array_values(array_intersect($dayOrder, $item['days']));
                                        }
                                        unset($item);
                                    @endphp

                                    @forelse ($grouped as $group)
                                        <div class="col-md-4">
                                            <div class="card card-hover border-0 shadow-sm h-100"
                                                style="background: linear-gradient(160deg, #d0e7ff 50%, #007bff 100%);">
                                                <div class="card-body text-center">
                                                    <div class="mb-2">
                                                        <h4 class="fw-semibold mb-1 text-primary">
                                                            {{ $group['subject_name'] }}</h4>
                                                        <i class="bi bi-calendar3 fs-1"></i>
                                                    </div>
                                                    <h6 class="fw-semibold mb-1 text-dark">
                                                        Teacher:
                                                        @if ($group['teacher'])
                                                            {{ $group['teacher']->firstName }}
                                                            {{ $group['teacher']->lastName }}
                                                        @else
                                                            <span class="text-muted">N/A</span>
                                                        @endif
                                                    </h6>
                                                    <h6 class="fw-semibold mb-1">
                                                        Schedule:
                                                        {{ implode(', ', $group['days']) }}
                                                    </h6>
                                                    <div class="display-6 fw-bold text-dark">
                                                        {{ \Carbon\Carbon::createFromFormat('H:i:s', $group['start_time'])->format('g:i A') }}
                                                        -
                                                        {{ \Carbon\Carbon::createFromFormat('H:i:s', $group['end_time'])->format('g:i A') }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="col-12">
                                            <div class="alert alert-info text-center mb-0">
                                                No schedules found for this class.
                                            </div>
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>


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
        // search bar
        document.getElementById('teacherSearch').addEventListener('keyup', function() {
            const searchValue = this.value.toLowerCase();
            const rows = document.querySelectorAll('#teachersTable tbody .teacher-row');

            rows.forEach(row => {
                const rowText = row.innerText.toLowerCase();
                row.style.display = rowText.includes(searchValue) ? '' : 'none';
            });
        });
    </script>

    <script>
        // register alert
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('#backDropModal form');
            const registerBtn = document.getElementById('registerTeacherBtn');

            registerBtn.addEventListener('click', function(e) {
                e.preventDefault(); // Prevent default form submission

                // Manually check all required fields
                const requiredFields = form.querySelectorAll('[required]');
                let allFilled = true;

                requiredFields.forEach(field => {
                    if (!field.value.trim()) {
                        allFilled = false;
                        field.classList.add('is-invalid'); // optional: Bootstrap red border
                    } else {
                        field.classList.remove('is-invalid');
                    }
                });

                if (!allFilled) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Incomplete Form',
                        text: 'Please fill in all required fields before submitting.',
                        confirmButtonColor: '#dc3545',
                        customClass: {
                            container: 'my-swal-container'
                        }
                    });
                    return;
                }

                // If all fields are filled, show confirmation alert
                Swal.fire({
                    title: "Add Schedule?",
                    text: "Are you sure all the details are correct?",
                    icon: "question",
                    showCancelButton: true,
                    confirmButtonColor: "#06D001",
                    cancelButtonColor: "#6c757d",
                    confirmButtonText: "Yes, Add",
                    cancelButtonText: "Cancel",
                    customClass: {
                        container: 'my-swal-container'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: "Adding...",
                            text: "Please wait while we process the adding of schedule.",
                            icon: "info",
                            allowOutsideClick: false,
                            showConfirmButton: false,
                            timer: 1200,
                            customClass: {
                                container: 'my-swal-container'
                            },
                            willClose: () => {
                                form.submit();
                            }
                        });
                    }
                });
            });
        });
    </script>

    <script>
        // delete button alert
        function confirmDelete(teacherId, firstName, lastName) {
            Swal.fire({
                title: `Delete ${firstName} ${lastName}'s record?`,
                text: "This action cannot be undone.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#6c757d",
                confirmButtonText: "Yes, delete it!",
                cancelButtonText: "Cancel",
                customClass: {
                    container: 'my-swal-container'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: "Deleting...",
                        text: "Please wait while we remove the record.",
                        icon: "info",
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        customClass: {
                            container: 'my-swal-container'
                        },
                        didOpen: () => {
                            setTimeout(() => {
                                document.getElementById('delete-form-' + teacherId).submit();
                            }, 1000);
                        }
                    });
                }
            });
        }
    </script>





    <script>
        // alert after a success edit or delete of teacher's info
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

    <script>
        // alert for logout
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
                        icon: "success"
                    });
                    document.getElementById('logout-form').submit();
                }
            });
        }
    </script>

    <script>
        // alert for upload and preview profile in registration
        const uploadInput = document.getElementById('upload');
        const previewImg = document.getElementById('photo-preview');
        const resetBtn = document.getElementById('reset-photo');
        const defaultImage = "{{ asset('assetsDashboard/img/profile_pictures/teachers_default_profile.jpg') }}";

        uploadInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImg.src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        });

        resetBtn.addEventListener('click', function() {
            uploadInput.value = '';
            previewImg.src = defaultImage;
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
        .card-hover {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            cursor: pointer;
        }

        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.2);
        }
    </style>
@endpush
