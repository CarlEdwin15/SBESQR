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
                            <div class="text-light">Teacher Management</div>
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
                        <a href="{{ route('student.management') }}" class="menu-link bg-dark text-light">
                            <div class="text-warning">Student Management</div>
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
                <a class="text-muted fw-light" href="{{ route('student.management') }}">Students / </a>
            </span> Student Information
        </h4>

        {{-- <div class="d-flex justify-content-between align-items-center mb-1">
            <a href="{{ url()->previous() }}" style="margin: auto; margin-bottom: 10px; margin-left: 10px"
                class="btn btn-danger mt-3">Back</a>

            <a href="{{ route('edit.student', ['id' => $student->id]) }}" class="btn btn-warning mt-2 mb-2 me-2">Edit</a>

            <!-- Generate ID Form -->
            <form action="{{ route('students.generateID', $student->id) }}" method="GET">
                @csrf
                <button type="submit" class="btn btn-success">Generate ID</button>
            </form>
        </div> --}}


        <div class="row">
            <!-- Left Profile Card -->
            <div class="col-md-4 mb-4">
                <div class="card shadow p-3 align-items-center text-center">
                    @if ($student->student_photo)
                        <img src="{{ asset('storage/' . $student->student_photo) }}" alt="Student Photo"
                            class="mb-1 mt-2" style="object-fit: cover; height: 200px; width: 200px;">
                    @else
                        <img src="{{ asset('assetsDashboard/img/student_profile_pictures/student_default_profile.jpg') }}"
                            alt="Default Photo" class="mb-1 mt-2"
                            style="object-fit: cover; height: 200px; width: 200px;">
                    @endif

                    <div class="d-flex justify-content-center align-items-center mt-3">
                        <!-- Generate ID Form -->
                        <form action="{{ route('students.generateID', $student->id) }}" method="GET">
                            @csrf
                            <button type="submit" class="btn btn-success mb-3 me-2 d-flex align-items-center">
                                <i class='bx bxs-id-card me-1'></i>
                                <span class="d-none d-sm-block">Generate ID</span>
                            </button>
                        </form>
                    </div>

                    <h5 class="fw-bold">{{ $student->student_fName }} {{ $student->student_lName }}</h5>

                    <!-- Student Status Display -->
                    <div class="mt-2 mb-3 text-center">
                        <span class="fw-bold">Student Status</span><br>

                        @php
                            $displayStatus = match ($studentStatus) {
                                'enrolled' => 'Active',
                                'graduated' => 'Graduated',
                                'archived', 'not_enrolled' => 'Inactive',
                                default => ucfirst($studentStatus),
                            };

                            $badgeClass = match ($displayStatus) {
                                'Active' => 'bg-label-success fw-bold',
                                'Inactive' => 'bg-label-secondary fw-bold',
                                'Graduated' => 'bg-label-info fw-bold',
                                default => 'bg-label-warning fw-bold',
                            };
                        @endphp

                        <span class="badge {{ $badgeClass }} px-3 py-2">{{ $displayStatus }}</span><br>

                        @if (!empty($statusInfo))
                            <small class="text-muted d-block mt-1">{{ $statusInfo }}</small>
                        @endif
                    </div>

                    <!-- Action Buttons -->
                    <div class="d-flex justify-content-center align-items-center mt-3">
                        <!-- Back Button -->
                        <a href="{{ session('back_url', url()->previous()) }}"
                            class="btn btn-secondary me-2 d-flex align-items-center">
                            <i class='bx bx-chevrons-left'></i>
                            <span class="d-none d-sm-block">Back</span>
                        </a>
                        <!-- Edit Button -->
                        <a href="" class="btn btn-warning me-2 d-flex align-items-center" data-bs-toggle="modal"
                            data-bs-target="#editStudentModal">
                            <i class='bx bx-edit me-1'></i>
                            <span class="d-none d-sm-block">Edit</span>
                        </a>

                        <!-- Delete Button -->
                        <form action="{{ route('delete.student', $student->id) }}" method="POST"
                            class="d-inline delete-student-form">
                            @csrf
                            @method('DELETE')
                            <button type="button"
                                class="btn btn-danger me-2 d-flex align-items-center delete-student-btn">
                                <i class='bx bx-trash me-1'></i>
                                <span class="d-none d-sm-block">Delete</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Right Tabs + Info -->
            <div class="col-md-8">
                @include('partials.student_tabs')
            </div>
        </div>

        <!-- Edit Student Modal -->
        <div class="modal fade" id="editStudentModal" data-bs-backdrop="static" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <form class="modal-content" action="{{ route('update.student', ['id' => $student->id]) }}"
                    id="editStudentForm" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="modal-header">
                        <h4 class="modal-title fw-bold text-primary">
                            Edit {{ $student->student_fName }} {{ $student->student_lName }}'s Details
                        </h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">

                        <div class="row">
                            <!-- Profile Photo Upload with Preview and Default -->
                            <div class="row">
                                <div class="col mt-4 mb-3 d-flex align-items-start align-items-sm-center gap-4">
                                    <div class="mb-3">
                                        @if ($student->student_photo)
                                            <img id="photo-preview"
                                                src="{{ asset('storage/' . $student->student_photo) }}"
                                                alt="Profile Preview" width="100" height="100"
                                                class="profile-preview" style="object-fit: cover; border-radius: 5%">
                                        @else
                                            <img id="photo-preview"
                                                src="{{ asset('assetsDashboard/img/student_profile_pictures/student_default_profile.jpg') }}"
                                                alt="Profile Preview" width="100" height="100"
                                                class="profile-preview" style="object-fit: cover; border-radius: 5%">
                                        @endif
                                    </div>

                                    <div class="button-wrapper">
                                        <label for="upload" class="btn btn-warning me-2 mb-2" tabindex="0">
                                            <span class="d-none d-sm-block">Upload new photo</span>
                                            <i class="bx bx-upload d-block d-sm-none"></i>

                                            <input type="file" id="upload" name="student_profile_photo"
                                                class="account-file-input" hidden accept="image/png, image/jpeg" />
                                        </label>

                                        <button type="button" class="btn btn-outline-secondary account-image-reset mb-2">
                                            <i class="bx bx-reset d-block d-sm-none"></i>
                                            <span class="d-none d-sm-block">Reset</span>
                                        </button>

                                        <p class="text-muted mb-0" style="font-size: 15px">Allowed JPG or PNG. Max
                                            size of 2MB</p>
                                    </div>
                                </div>

                                <!-- Enrollment Type Field -->
                                <div class="col mb-2 mt-2">
                                    @php
                                        $currentClass = $student->class->first();
                                        $enrollmentType = $currentClass ? $currentClass->pivot->enrollment_type : null;
                                    @endphp

                                    <label for="enrollment_type" class="form-label fw-bold">Enrollment Type</label>
                                    <select name="enrollment_type" id="enrollment_type" class="form-select" required>
                                        <option value="regular"
                                            {{ old('enrollment_type', $enrollmentType) == 'regular' ? 'selected' : '' }}>
                                            Regular</option>
                                        <option value="transferee"
                                            {{ old('enrollment_type', $enrollmentType) == 'transferee' ? 'selected' : '' }}>
                                            Transferee</option>
                                        <option value="returnee"
                                            {{ old('enrollment_type', $enrollmentType) == 'returnee' ? 'selected' : '' }}>
                                            Returnee</option>
                                    </select>
                                </div>
                            </div>

                            <hr class="my-0" />

                            <!-- LRN Field -->
                            <div class="col mb-2 mt-3">
                                <label for="student_lrn" class="form-label fw-bold">LRN (Learner's Refference
                                    No.)</label>
                                <input type="text" name="student_lrn" id="student_lrn" class="form-control"
                                    list="datalistOptions" value="{{ $student->student_lrn }}" required />

                                <datalist id="datalistOptions">
                                    <option value="112828"></option>
                                </datalist>
                            </div>

                            <!-- Grade Level Field -->
                            <div class="col mb-2 mt-3">
                                <label for="student_grade_level" class="form-label fw-bold">Grade
                                    Level</label>
                                @php
                                    $studentClass = $student
                                        ->class()
                                        ->wherePivot('school_year_id', $selectedSchoolYearId ?? $schoolYear->id)
                                        ->first();
                                @endphp

                                <select name="student_grade_level" id="student_grade_level" class="form-select" required>
                                    <option value="kindergarten"
                                        {{ $studentClass && $studentClass->grade_level == 'kindergarten' ? 'selected' : '' }}>
                                        Kindergarten</option>
                                    <option value="grade1"
                                        {{ $studentClass && $studentClass->grade_level == 'grade1' ? 'selected' : '' }}>
                                        Grade 1</option>
                                    <option value="grade2"
                                        {{ $studentClass && $studentClass->grade_level == 'grade2' ? 'selected' : '' }}>
                                        Grade 2</option>
                                    <option value="grade3"
                                        {{ $studentClass && $studentClass->grade_level == 'grade3' ? 'selected' : '' }}>
                                        Grade 3</option>
                                    <option value="grade4"
                                        {{ $studentClass && $studentClass->grade_level == 'grade4' ? 'selected' : '' }}>
                                        Grade 4</option>
                                    <option value="grade5"
                                        {{ $studentClass && $studentClass->grade_level == 'grade5' ? 'selected' : '' }}>
                                        Grade 5</option>
                                    <option value="grade6"
                                        {{ $studentClass && $studentClass->grade_level == 'grade6' ? 'selected' : '' }}>
                                        Grade 6</option>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Section Field -->
                            <div class="col mb-2 mt-2">
                                <label for="student_section" class="form-label fw-bold">Section</label>
                                <select name="student_section" id="student_section" class="form-select" required>
                                    @php
                                        $studentSection = $student
                                            ->class()
                                            ->wherePivot('school_year_id', $selectedSchoolYearId ?? $schoolYear->id)
                                            ->first();
                                    @endphp
                                    <option
                                        value="A"{{ $studentSection && $studentSection->section == 'A' ? 'selected' : '' }}>
                                        Section A</option>
                                    <option
                                        value="B"{{ $studentSection && $studentSection->section == 'B' ? 'selected' : '' }}>
                                        Section B</option>
                                    <option
                                        value="C"{{ $studentSection && $studentSection->section == 'C' ? 'selected' : '' }}>
                                        Section C</option>
                                    <option
                                        value="D"{{ $studentSection && $studentSection->section == 'D' ? 'selected' : '' }}>
                                        Section D</option>
                                    <option
                                        value="E"{{ $studentSection && $studentSection->section == 'E' ? 'selected' : '' }}>
                                        Section E</option>
                                    <option
                                        value="F"{{ $studentSection && $studentSection->section == 'F' ? 'selected' : '' }}>
                                        Section F</option>
                                </select>
                            </div>

                            <!-- Gender Field -->
                            <div class="col mb-2 mt-2">
                                <label for="student_sex" class="form-label fw-bold">Gender</label>
                                <select name="student_sex" id="student_sex" class="form-select" required>
                                    <option value="male"{{ $student->student_sex == 'male' ? 'selected' : '' }}>
                                        Male</option>
                                    <option value="female"{{ $student->student_sex == 'female' ? 'selected' : '' }}>
                                        Female</option>
                                </select>
                            </div>
                        </div>

                        <div class="row">

                            <!-- First Name Field -->
                            <div class="col mb-2 mt-2">
                                <label for="student_fName" class="form-label fw-bold">First
                                    Name</label>
                                <input type="text" name="student_fName" id="student_fName" class="form-control"
                                    value="{{ $student->student_fName }}" required />
                            </div>

                            <!-- Middle Name Field -->
                            <div class="col mb-2 mt-2">
                                <label for="student_mName" class="form-label fw-bold">Middle
                                    Name</label>
                                <input type="text" name="student_mName" id="student_mName" class="form-control"
                                    value="{{ $student->student_mName }}" />
                            </div>

                        </div>

                        <div class="row">

                            <!-- Last Name Field -->
                            <div class="col mb-2 mt-2">
                                <label for="student_lName" class="form-label fw-bold">Last Name</label>
                                <input type="text" name="student_lName" id="student_lName" class="form-control"
                                    value="{{ $student->student_lName }}" required />
                            </div>

                            <!-- Extension Name Field -->
                            <div class="col mb-2 mt-2">
                                <label for="student_extName" class="form-label fw-bold">Extension
                                    Name</label>
                                <input type="text" name="student_extName" id="student_extName" class="form-control"
                                    value="{{ $student->student_extName }}" />
                            </div>

                        </div>

                        <div class="row">

                            <!-- Place of Birth Field -->
                            <div class="col mb-2 mt-2">
                                <label for="student_pob" class="form-label fw-bold">Place of Birth</label>
                                <input type="text" name="student_pob" id="student_pob" class="form-control"
                                    value="{{ $student->address->pob }}" />
                            </div>

                            <!-- Date of Birth Field -->
                            <div class="col mb-2 mt-2">
                                <label for="student_dob" class="form-label fw-bold">Date of Birth</label>
                                <input class="form-control" name="student_dob" type="date"
                                    value="{{ $student->student_dob }}" id="student_dob" />
                            </div>
                        </div>

                        <hr class="my-0 mb-4 mt-4" />

                        <!-- Student Addresses -->
                        <h5 class="fw-bold mb-3 mt-3 text-primary">Student Address</h5>

                        <div class="row">
                            <!-- House No. Field -->
                            <div class="col mb-2 mt-2">
                                <label for="house_no" class="form-label fw-bold">House No.</label>
                                <input type="text" name="house_no" id="house_no" class="form-control"
                                    value="{{ $student->address->house_no }}" />
                            </div>

                            <!-- Street Name Field -->
                            <div class="col mb-2 mt-2">
                                <label for="street_name" class="form-label fw-bold">Street Name</label>
                                <input type="text" name="street_name" id="street_name" class="form-control"
                                    value="{{ $student->address->street_name }}" />
                            </div>
                        </div>

                        <div class="row">
                            <!-- Barangay Name Field -->
                            <div class="col mb-2 mt-2">
                                <label for="barangay" class="form-label fw-bold">Barangay</label>
                                <input type="text" name="barangay" id="barangay" class="form-control"
                                    value="{{ $student->address->barangay }}" />
                            </div>

                            <!-- Municipality Name Field -->
                            <div class="col mb-2 mt-2">
                                <label for="municipality_city" class="form-label fw-bold">Municipality/City</label>
                                <input type="text" name="municipality_city" id="municipality_city"
                                    class="form-control" value="{{ $student->address->municipality_city }}" />
                            </div>

                        </div>

                        <div class="row">
                            <!-- Barangay Name Field -->
                            <div class="col mb-2 mt-2">
                                <label for="province" class="form-label fw-bold">Province</label>
                                <input type="text" name="province" id="province" class="form-control"
                                    value="{{ $student->address->province }}" />
                            </div>

                            <!-- Zip Code Field -->
                            <div class="col mb-2 mt-2">
                                <label for="zip_code" class="form-label fw-bold">Zip Code</label>
                                <input type="text" name="zip_code" id="zip_code" class="form-control"
                                    value="{{ $student->address->zip_code }}" />
                            </div>
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                            onclick="handleCancel()">Cancel</button>
                        <button type="submit" id="saveChangesBtn" class="btn btn-success">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
        <!-- /Edit Student Modal -->

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

            // Show success toast if session has success message
            @if (session('success'))
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: "{{ session('success') }}",
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    customClass: {
                        container: 'my-swal-container'
                    },
                });
            @endif
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const deleteForms = document.querySelectorAll('.delete-student-form');

            deleteForms.forEach(form => {
                const deleteButton = form.querySelector('.delete-student-btn');

                deleteButton.addEventListener('click', function(e) {
                    e.preventDefault();

                    Swal.fire({
                        title: 'Are you sure?',
                        text: "This action will permanently delete the student's record and related data.",
                        icon: 'warning',
                        showCancelButton: true,
                        cancelButtonColor: '#6c757d',
                        confirmButtonColor: '#d33',
                        confirmButtonText: 'Yes, delete it!',
                        cancelButtonText: 'Cancel',
                        reverseButtons: true,
                        customClass: {
                            container: 'my-swal-container'
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });
        });
    </script>
@endpush
