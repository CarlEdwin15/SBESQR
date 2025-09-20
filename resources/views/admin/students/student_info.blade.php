@extends('./layouts.main')

@php
    use Carbon\Carbon;
@endphp


@section('title', 'Admin | Student Information')


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
        <h4 class="fw-bold py-3 mb-4 text-warning">
            <span class="text-muted fw-light">
                <a class="text-muted fw-light" href="{{ route('home') }}">Dashboard / </a>
                <a class="text-muted fw-light" href="{{ route('show.students') }}">Students / </a>
            </span> Student Information
        </h4>

        <h5 class="text-center text-success mt-2">
            School Year: {{ $schoolYear->school_year ?? 'N/A' }}
        </h5>
        <h4 class="mt-2 text-primary text-center fw-semibold">
            Student Profile:<br>
            {{ $student->student_fName }} {{ $student->student_lName }}
        </h4>

        <div class="d-flex justify-content-between align-items-center mb-1">
            <a href="{{ url()->previous() }}" style="margin: auto; margin-bottom: 10px; margin-left: 10px"
                class="btn btn-danger mt-3">Back</a>

            <a href="{{ route('edit.student', ['id' => $student->id]) }}" class="btn btn-warning mt-2 mb-2 me-2">Edit</a>

            <!-- Generate ID Form -->
            <form action="{{ route('students.generateID', $student->id) }}" method="GET">
                @csrf
                <button type="submit" class="btn btn-success">Generate ID</button>
            </form>
        </div>

        <ul class="nav nav-pills mb-3 nav-fill" role="tablist">
            <li class="nav-item">
                <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab"
                    data-bs-target="#profile-tab" aria-controls="profile-tab" aria-selected="true">
                    <i class="tf-icons bx bx-user"></i> Profile
                </button>
            </li>
            <li class="nav-item">
                <button type="button" class="nav-link" role="tab" data-bs-toggle="tab"
                    data-bs-target="#parents-tab" aria-controls="parents-tab" aria-selected="false">
                    <i class="tf-icons bx bx-group"></i> Parents
                </button>
            </li>
            <li class="nav-item">
                <button type="button" class="nav-link" role="tab" data-bs-toggle="tab"
                    data-bs-target="#emergency-tab" aria-controls="emergency-tab" aria-selected="false">
                    <i class="tf-icons bx bx-phone"></i> Emergency
                </button>
            </li>
            <li class="nav-item">
                <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#qr-tab"
                    aria-controls="qr-tab" aria-selected="false">
                    <i class="tf-icons bx bx-qr"></i> QR Code
                </button>
            </li>
        </ul>

        <!-- Student Details with Filled Pills -->
        <div class="card shadow">
            <div class="card-body">
                <div class="row">
                    <!-- Student Photo -->
                    <div class="col-md-4 mt-3 mb-3 text-center d-flex flex-column align-items-center">
                        @if ($student->student_photo)
                            <img src="{{ asset('storage/' . $student->student_photo) }}" alt="Student Photo"
                                class="img-thumbnail mb-3" style="object-fit: cover; height: 450px; width: 450px;">
                        @else
                            <img src="{{ asset('assetsDashboard/img/student_profile_pictures/student_default_profile.jpg') }}"
                                alt="Default Photo" class="img-thumbnail mb-3"
                                style="object-fit: cover; height: 450px; width: 450px;">
                        @endif
                    </div>

                    <!-- Student Info Tabs -->
                    <div class="col-md-8 mt-3 mb-3">
                        <div class="nav-align-top mb-4">

                            <div class="tab-content">
                                <!-- Profile Tab -->
                                <div class="tab-pane fade show active" id="profile-tab" role="tabpanel">
                                    <table class="table table-bordered">
                                        <tbody>
                                            <h5 class="text-info fw-bold">Student Information</h5>
                                            <tr>
                                                <th class="text-primary">LRN</th>
                                                <td>{{ $student->student_lrn }}</td>
                                            </tr>
                                            <tr>
                                                <th class="text-primary">Full Name</th>
                                                <td>{{ $student->student_fName }}
                                                    {{ $student->student_mName }}
                                                    {{ $student->student_lName }}</td>
                                            </tr>
                                            <tr>
                                                <th class="text-primary">Grade Level</th>
                                                <td>
                                                    @if ($class)
                                                        {{ $class->formatted_grade_level }} -
                                                        {{ $class->section }}
                                                    @else
                                                        <span class="text-danger">Not enrolled in selected
                                                            school year</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <th class="text-primary">School Year</th>
                                                <td>{{ $schoolYear->school_year ?? 'N/A' }}</td>
                                            </tr>
                                            <tr>
                                                <th class="text-primary">Date of Birth</th>
                                                <td>{{ Carbon::parse($student->student_dob)->format('F j, Y') }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <th class="text-primary">Sex</th>
                                                <td>{{ ucfirst($student->student_sex) }}</td>
                                            </tr>
                                            <tr>
                                                <th class="text-primary">Age</th>
                                                <td>{{ Carbon::parse($student->student_dob)->age }} years old
                                                </td>
                                            </tr>
                                            <tr>
                                                <th class="text-primary">Place of Birth</th>
                                                <td>{{ $student->address->pob }}</td>
                                            </tr>
                                            <tr>
                                                <th class="text-primary">Complete Address</th>
                                                <td>
                                                    {{ $student->address->house_no ?? 'N/A' }},
                                                    {{ $student->address->street_name ?? 'N/A' }},
                                                    {{ $student->address->barangay ?? 'N/A' }},
                                                    {{ $student->address->municipality_city ?? 'N/A' }},
                                                    {{ $student->address->province ?? 'N/A' }},
                                                    {{ $student->address->zip_code ?? 'N/A' }}
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <!-- Parents Tab -->
                                <div class="tab-pane fade" id="parents-tab" role="tabpanel">
                                    <table class="table table-bordered">
                                        <tbody>
                                            <tr>
                                                <th class="text-primary">Father's Name</th>
                                                <td>{{ $student->parents->father_fName ?? 'N/A' }}
                                                    {{ $student->parents->father_mName ?? 'N/A' }}
                                                    {{ $student->parents->father_lName ?? 'N/A' }}</td>
                                            </tr>
                                            <tr>
                                                <th class="text-primary">Father's Contact No.</th>
                                                <td>{{ $student->parents->father_phone ?? 'N/A' }}</td>
                                            </tr>
                                            <tr>
                                                <th class="text-primary">Mother's Name</th>
                                                <td>{{ $student->parents->mother_fName ?? 'N/A' }}
                                                    {{ $student->parents->mother_mName ?? 'N/A' }}
                                                    {{ $student->parents->mother_lName ?? 'N/A' }}</td>
                                            </tr>
                                            <tr>
                                                <th class="text-primary">Mother's Contact No.</th>
                                                <td>{{ $student->parents->mother_phone ?? 'N/A' }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <!-- Emergency Tab -->
                                <div class="tab-pane fade" id="emergency-tab" role="tabpanel">
                                    <table class="table table-bordered">
                                        <tbody>
                                            <tr>
                                                <th class="text-primary">Emergency Contact</th>
                                                <td>{{ $student->parents->emergcont_fName ?? 'N/A' }}
                                                    {{ $student->parents->emergcont_mName ?? 'N/A' }}
                                                    {{ $student->parents->emergcont_lName ?? 'N/A' }}</td>
                                            </tr>
                                            <tr>
                                                <th class="text-primary">Emergency Contact No.</th>
                                                <td>{{ $student->parents->emergcont_phone ?? 'N/A' }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <!-- QR Code Tab -->
                                <div class="tab-pane fade" id="qr-tab" role="tabpanel">
                                    {!! QrCode::size(200)->generate(json_encode(['student_id' => $student->id])) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- / Student Info Tabs -->
                </div>
            </div>
        </div>

        <hr class="my-5" />
    </div>
    <!-- / Content wrapper -->

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
