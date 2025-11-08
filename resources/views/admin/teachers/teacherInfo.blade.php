@extends('./layouts.main')

@php
    use Carbon\Carbon;
@endphp

@section('title', 'Admin | Teacher Information')

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
            <li class="menu-item active open">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons bx bx-user-pin"></i>
                    <div>Teachers</div>
                </a>

                <ul class="menu-sub">
                    <li class="menu-item active">
                        <a href="{{ route('show.teachers') }}" class="menu-link bg-dark text-light">
                            <div class="text-warning">Teacher Management</div>
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
                            <div class="text-light">Class Re-Enrollment</div>
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
                            <div class="text-light">Classes</div>
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
                <a class="text-muted fw-light" href="{{ route('show.teachers') }}">Teachers / </a>
            </span> Teacher's Information
        </h4>

        <!-- Teacher's Details -->
        <div class="card shadow">
            <div class="card-body">
                <div class="row">
                    <h4 class="mt-2 text-primary text-center" style="font-weight: 600;">Teacher Profile:
                        <br>{{ $teacher->firstName }} {{ $teacher->lastName }}
                    </h4>
                    {{-- Teacher Photo --}}
                    <div class="col-md-4 mt-3 mb-3 text-center d-flex flex-column align-items-center">
                        @if ($teacher->profile_photo)
                            <img src="{{ asset('public/uploads/' . $teacher->profile_photo) }}" alt="Teacher Photo"
                                class="img-thumbnail mb-3" style="object-fit: cover; height: 450px; width: 450px;">
                        @else
                            <img src="{{ asset('assetsDashboard/img/profile_pictures/teacher_default_profile.jpg') }}"
                                alt="Default Photo" class="img-thumbnail mb-3"
                                style="object-fit: cover; height: 450px; width: 450px;">
                        @endif
                    </div>

                    {{-- Teacher Details --}}
                    <div class="col-md-8 mt-3 mb-3">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <th class="text-primary">Full Name</th>
                                    <td>{{ ucfirst($teacher->firstName) }} {{ ucfirst($teacher->middleName) }}
                                        {{ ucfirst($teacher->lastName) }} {{ ucfirst($teacher->extName) }}
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-primary">School Year</th>
                                    <td>{{ $selectedSchoolYear }}</td>
                                </tr>
                                <tr>
                                    <th class="text-primary">Advisory Class</th>
                                    <td>
                                        @php
                                            $advisoryClass = null;
                                            if ($teacher->classes && count($teacher->classes)) {
                                                foreach ($teacher->classes as $class) {
                                                    if (($class->pivot->role ?? null) === 'adviser') {
                                                        $advisoryClass = $class;
                                                        break;
                                                    }
                                                }
                                            }
                                        @endphp
                                        @if ($advisoryClass)
                                            <span class="badge bg-warning text-auto mb-1">
                                                {{ strtoupper($advisoryClass->formattedGradeLevel ?? $advisoryClass->grade_level) }}
                                                - Section {{ $advisoryClass->section }}
                                                <i class="bx bxs-star text-auto ms-1" title="Advisory Class"
                                                    style="font-size: 0.85em; vertical-align: middle;"></i>
                                            </span>
                                        @else
                                            <span class="text-muted">No Advisory Class</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-primary">Subject-Based Classes</th>
                                    <td>
                                        @php
                                            $assignedClasses = [];
                                            if ($teacher->classes && count($teacher->classes)) {
                                                foreach ($teacher->classes as $class) {
                                                    if (($class->pivot->role ?? null) !== 'adviser') {
                                                        $assignedClasses[] = $class;
                                                    }
                                                }
                                            }
                                        @endphp
                                        @if (count($assignedClasses))
                                            @foreach ($assignedClasses as $class)
                                                <span class="badge bg-secondary text-auto mb-1">
                                                    {{ strtoupper($class->formattedGradeLevel ?? $class->grade_level) }}
                                                    - Section {{ $class->section }}
                                                </span>
                                                @if (!$loop->last)
                                                    <br>
                                                @endif
                                            @endforeach
                                        @else
                                            <span class="text-muted">No Subject-Based Class</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-primary">Email</th>
                                    <td>{{ $teacher->email }}</td>
                                </tr>
                                <tr>
                                    <th class="text-primary">Age</th>
                                    <td>
                                        {{ Carbon::parse($teacher->dob)->age }} years old
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-primary">Date of Birth</th>
                                    <td>{{ \Carbon\Carbon::parse($teacher->dob)->format('F j, Y') }}
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-primary">Gender</th>
                                    <td>{{ ucfirst($teacher->gender) }}</td>
                                </tr>
                                <tr>
                                    <th class="text-primary">Contact No.</th>
                                    <td>{{ $teacher->phone }}</td>
                                </tr>
                                <tr>
                                    <th class="text-primary">Address</th>
                                    <td>
                                        {{ $teacher->house_no }},
                                        {{ $teacher->street_name }},
                                        {{ $teacher->barangay }},
                                        {{ $teacher->municipality_city }},
                                        {{ $teacher->province }},
                                        {{ $teacher->zip_code }}
                                    </td>

                                </tr>
                            </tbody>
                        </table>

                        <a href="{{ route('show.teachers') }}" class="btn btn-secondary mt-3">Back</a>
                    </div>
                </div>
            </div>
        </div>
        <!-- / Student's Details -->

        <hr class="my-5" />

    </div>
    <!-- Content wrapper -->

    <!-- Overlay -->
    <div class="layout-overlay layout-menu-toggle"></div>

@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Form change detection
            let formChanged = false;
            const form = document.getElementById('editStudentForm');
            const saveBtn = document.getElementById('saveChangesBtn');

            if (form) {
                form.addEventListener('input', () => {
                    formChanged = true;
                });
            }

            // Cancel button logic
            window.handleCancel = function() {
                if (!formChanged) {
                    window.location.href = "{{ route('show.students') }}";
                } else {
                    Swal.fire({
                        title: "Cancel editing and discard any changes?",
                        showDenyButton: true,
                        showCancelButton: true,
                        showConfirmButton: false,
                        confirmButtonText: "Save",
                        denyButtonText: `Don't save`,
                        cancelButtonText: "Cancel",
                        customClass: {
                            container: 'my-swal-container'
                        }
                    }).then((result) => {
                        if (result.isDenied) {
                            form.reset();
                            window.location.href = "{{ route('show.students') }}";
                        }
                    });
                }
            };

            // Save button logic
            if (saveBtn && form) {
                saveBtn.addEventListener('click', function(e) {
                    e.preventDefault(); // Prevent default form submission

                    Swal.fire({
                        title: "Save changes?",
                        text: "Are you sure you want to update this student's details?",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#28a745",
                        cancelButtonColor: "#d33",
                        confirmButtonText: "Yes, save it",
                        cancelButtonText: "Cancel",
                        customClass: {
                            container: 'my-swal-container'
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            Swal.fire({
                                title: "Saving...",
                                text: "Please wait while we update the details.",
                                icon: "info",
                                allowOutsideClick: false,
                                showConfirmButton: false,
                                timer: 1000,
                                customClass: {
                                    container: 'my-swal-container'
                                },
                                willClose: () => {
                                    form.submit(); // Corrected the syntax
                                }
                            });
                        }
                    });
                });
            }

            // Logout confirmation
            window.confirmLogout = function() {
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
                            timer: 800,
                            showConfirmButton: false
                        }).then(() => {
                            document.getElementById('logout-form').submit();
                        });
                    }
                });
            };

            // Profile image upload preview and reset
            const fileInput = document.getElementById('upload');
            const resetBtn = document.querySelector('.account-image-reset');
            const imagePreview = document.querySelector('.profile-preview');

            if (fileInput && resetBtn && imagePreview) {
                const originalImageSrc = imagePreview.src;

                fileInput.addEventListener('change', function(event) {
                    const file = event.target.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            imagePreview.src = e.target.result;
                        };
                        reader.readAsDataURL(file);
                    }
                });

                resetBtn.addEventListener('click', function() {
                    fileInput.value = '';
                    imagePreview.src = originalImageSrc;
                });
            }
        });
    </script>
@endpush
