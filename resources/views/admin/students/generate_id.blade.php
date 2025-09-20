@extends('./layouts.main')

@php
    use Carbon\Carbon;
@endphp

@section('title', 'Admin | Generated Student ID')

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
                            <div class="text-light">Teacher's Class Management</div>
                        </a>
                    </li>
                </ul>
            </li>

            {{-- Students sidebar --}}
            <li class="menu-item active open">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons bx bxs-graduation"></i>
                    <div>Students</div>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item active">
                        <a href="{{ route('show.students') }}" class="menu-link bg-dark text-light">
                            <div class="text-warning">All Students</div>
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
                <a class="text-muted fw-light" href="{{ route('show.students') }}">Students / </a>
                <a class="text-muted fw-light" href="{{ route('student.info', $student->id) }}">Student
                    Information / </a>
            </span> Generated Student ID
        </h4>

        <div class="d-flex justify-content-between align-items-center mb-2">
            <a href="{{ route('show.students') }}" style="margin: auto; margin-bottom: 10px; margin-left: 10px"
                class="btn btn-danger mt-3">Back</a>

            <!-- Generate ID Form -->
            <form action="{{ route('students.downloadID', $student->id) }}" method="GET">
                @csrf
                <button type="submit" class="btn btn-success"
                    style="margin: auto; margin-bottom: 10px; margin-left: 10px">Download ID</button>
            </form>
        </div>

        <!-- Student's Details -->
        <div class="card shadow">
            <div class="card-body">
                <div class="row">
                    <div class="d-flex flex-wrap justify-content-center gap-4 mt-4">
                        {{-- FRONT SIDE --}}
                        <div class="card p-3 shadow"
                            style="width: 320px; height: 510px; background-image: url('{{ asset('assetsDashboard/img/id_layout/front_bg_id.png') }}'); background-size: cover; background-repeat: no-repeat; background-position: center;">
                            <div>
                                <div class="mb-1 text-center">
                                    <img src="{{ asset('assets/img/logo.png') }}" alt="School Logo"
                                        style="width: 75px;">
                                </div>

                                <div class="text-center mb-2">
                                    <h5 class="mb-0 fw-bold text-uppercase" style="font-size: 16px;">
                                        Sta. Barbara Elementary School</h5>
                                    <p class="mb-0" style="font-size: 12px;">Sta. Barbara, Nabua, Camarines
                                        Sur
                                    </p>
                                </div>
                            </div>

                            <div class="text-center mb-2">
                                @if ($student->student_photo)
                                    <img src="{{ asset('storage/' . $student->student_photo) }}" alt="Student Photo"
                                        class="border"
                                        style="width: 1.5in; height: 1.5in; object-fit: cover; border: 6px solid #000; border-radius: 2px; box-shadow: 0 0 5px #0190d2;">
                                @else
                                    <img src="{{ asset('assetsDashboard/img/student_profile_pictures/student_default_profile.jpg') }}"
                                        alt="Default Photo" class="border"
                                        style="width: 1.5in; height: 1.5in; object-fit: cover; border: 6px solid #000; border-radius: 2px; box-shadow: 0 0 5px #0190d2;">
                                @endif
                            </div>


                            <div class="text-center mb-2">
                                <h6 class="mb-0 fw-bold text-uppercase" style="font-size: 14px;">
                                    {{ $student->student_fName }} {{ $student->student_mName }}
                                    {{ $student->student_lName }}
                                </h6>
                            </div>

                            <div class="text-center" style="font-size: 12px;">
                                <p class="mb-1"><strong>Date of Birth:</strong>
                                    {{ \Carbon\Carbon::parse($student->student_dob)->format('F j, Y') }}</p>
                                <p class="mb-1"><strong>Address:</strong> Zone
                                    {{ $student->address->barangay }}, Sta. Barbara, Nabua, Camarines Sur</p>
                            </div>
                        </div>

                        {{-- BACK SIDE --}}
                        <div class="card p-3 shadow"
                            style="width: 320px; height: 510px; background-image: url('{{ asset('assetsDashboard/img/id_layout/back_bg_id.png') }}'); background-size: cover; background-repeat: no-repeat; background-position: center;">

                            <h6 class="fw-bold text-center mb-2" style="font-size: 10px;">IN CASE OF EMERGENCY
                                PLEASE NOTIFY</h6>

                            <div style="font-size: 11px;">
                                <p class="mb-1">Name:
                                    <strong>{{ $student->parents->emergcont_fName ?? 'N/A' }}
                                        {{ $student->parents->emergcont_lName ?? '' }}</strong>
                                </p>
                                <p class="mb-1">Address:
                                    <strong>{{ $student->address->barangay ?? 'N/A' }},
                                        {{ $student->address->municipality_city ?? 'N/A' }},
                                        {{ $student->address->province ?? 'N/A' }}</strong>
                                </p>
                                <p class="mb-1">Contact No.:
                                    <strong>{{ $student->parents->emergcont_phone ?? 'N/A' }}</strong>
                                </p>
                            </div>

                            {{-- Validation Table --}}
                            <table class="table table-bordered text-center table-sm mt-1 mb-1"
                                style="border: 1px solid black;">
                                <thead>
                                    <tr>
                                        <th style="border: 1px solid black;">School Year</th>
                                        <th style="border: 1px solid black;">Signature</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @for ($i = 0; $i < 7; $i++)
                                        <tr style="height: 20px;">
                                            <td style="border: 1px solid black;"></td>
                                            <td style="border: 1px solid black;"></td>
                                        </tr>
                                    @endfor
                                </tbody>
                            </table>



                            {{-- QR Code --}}
                            <div class="text-center mt-1 mb-1">
                                <div style="display: inline-block; border: 2px solid #000; padding: 5px;">
                                    {!! QrCode::size(150)->generate(
                                        json_encode([
                                            'student_id' => $student->id,
                                        ]),
                                    ) !!}
                                </div>
                            </div>

                            <p class="text-center" style="font-size: 13px;"><strong>LRN:</strong>
                                {{ $student->student_lrn }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- / Student's Details -->

        <hr class="my-5" />

    </div>
    <!-- Content wrapper -->

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
