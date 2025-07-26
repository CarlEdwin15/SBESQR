@extends('./layouts.main')

@section('title', 'Admin | Edit Teacher')

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
                    <li class="menu-item active open">
                        <a href="javascript:void(0);" class="menu-link menu-toggle">
                            <i class="menu-icon tf-icons bx bx-user-pin"></i>
                            <div>Teachers</div>
                        </a>

                        <ul class="menu-sub">
                            <li class="menu-item active">
                                <a href="{{ route('show.teachers') }}" class="menu-link bg-dark text-light">
                                    <div class="text-danger">All Teacherss</div>
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
                        {{-- <div class="navbar-nav align-items-center">
                            <div class="nav-item d-flex align-items-center">
                                <i class="bx bx-search fs-4 lh-0"></i>
                                <input type="text" class="form-control border-0 shadow-none" placeholder="Search..."
                                    aria-label="Search..." />
                            </div>
                        </div> --}}
                        <!-- /Search -->

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
                                        <div class="dropdown-item" href="{{ route('logout') }}"
                                            onclick="event.preventDefault(); confirmLogout();">
                                            <i class="bx bx-power-off me-2"></i>
                                            {{ __('Log Out') }}
                                        </div>
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
                            <a class="text-muted fw-light" href="{{ route('show.teachers') }}">Teachers / </a>
                        </span> Edit Teacher's Information
                    </h4>

                    <hr class="my-4" />

                    <div class="d-flex justify-content-start mb-3 gap-2">
                        <button type="button" class="btn btn-danger d-flex align-items-center gap-1"
                            onclick="handleCancel()">
                            <i class="bx bx-arrow-back"></i>
                            <span class="d-sm-block">Back</span>
                        </button>
                        <button type="submit" class="btn btn-primary d-flex align-items-center gap-1"
                            id="saveChangesBtn">
                            <i class="bx bx-save"></i>
                            <span class="d-sm-block">Save</span>
                        </button>
                    </div>

                    <!-- Teacher's Details -->
                    <form class="modal-content" action="{{ route('update.teacher', ['id' => $teacher->id]) }}"
                        id="editTeacherForm" method="POST" enctype="multipart/form-data">
                        @csrf

                        <input type="hidden" name="selected_school_year" value="{{ $selectedSchoolYear }}">

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <strong>There were some problems with your input.</strong>
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="modal-body">
                            <h4 class="fw-bold text-primary">{{ $teacher->firstName }} {{ $teacher->lastName }}'s
                                Details</h4>
                            <div class="row">

                                <!-- Profile Photo Upload with Preview and Default -->
                                <div class="row">
                                    <div class="col mt-4 mb-3 d-flex align-items-start align-items-sm-center gap-4">
                                        <div class="mb-3">
                                            @if ($teacher->profile_photo)
                                                <img id="photo-preview"
                                                    src="{{ asset('storage/' . $teacher->profile_photo) }}"
                                                    alt="Profile Preview" width="100" height="100"
                                                    class="profile-preview" style="object-fit: cover; border-radius: 5%">
                                            @else
                                                <img id="photo-preview"
                                                    src="{{ asset('assetsDashboard/img/profile_pictures/teachers_default_profile.jpg') }}"
                                                    alt="Profile Preview" width="100" height="100"
                                                    class="profile-preview" style="object-fit: cover; border-radius: 5%">
                                            @endif
                                        </div>

                                        <div class="button-wrapper">
                                            <label for="upload" class="btn btn-warning me-2 mb-2" tabindex="0">
                                                <span class="d-none d-sm-block">Upload new photo</span>
                                                <i class="bx bx-upload d-block d-sm-none"></i>

                                                <input type="file" id="upload" name="profile_photo"
                                                    class="account-file-input" hidden accept="image/png, image/jpeg" />
                                            </label>

                                            <button type="button"
                                                class="btn btn-outline-secondary account-image-reset mb-2">
                                                <i class="bx bx-reset d-block d-sm-none"></i>
                                                <span class="d-none d-sm-block">Reset</span>
                                            </button>

                                            <p class="text-muted mb-0">Allowed JPG or PNG. Max size of 2MB</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr class="my-0" />

                            <div class="row">
                                <!-- Assigned Classes (Multi-select) -->
                                <div class="col mb-3">
                                    <label for="assigned_classes" class="form-label fw-bold">
                                        Assigned Classes <span class="text-danger">*</span>
                                    </label>
                                    <select name="assigned_classes[]" id="assigned_classes" class="mb-2"
                                        multiple required>
                                        @foreach ($allClasses as $class)
                                            @php
                                                // Get adviser for this class for the selected school year
                                                $adviser = $class->teachers->firstWhere('pivot.role', 'adviser');
                                            @endphp
                                            <option value="{{ $class->id }}"
                                                {{ in_array($class->id, $assignedClasses) ? 'selected' : '' }}>
                                                {{ strtoupper($class->formattedGradeLevel ?? $class->grade_level) }} -
                                                {{ $class->section }}
                                                @if ($adviser)
                                                    ({{ $adviser->firstName }} {{ $adviser->lastName }})
                                                @endif
                                            </option>
                                        @endforeach
                                    </select>
                                    <small class="text-muted">Hold Ctrl (Cmd on Mac) to select multiple</small>
                                </div>

                                <!-- Advisory Class -->
                                <div class="col mb-3">
                                    <label for="advisory_class" class="form-label fw-bold">Advisory Class</label>
                                    <select name="advisory_class" id="advisory_class" class="form-select">
                                        <option value="">No advisory class</option>
                                        @foreach ($assignedClasses as $assignedClassId)
                                            @php
                                                $class = $allClasses->firstWhere('id', $assignedClassId);
                                            @endphp
                                            @if ($class)
                                                <option value="{{ $class->id }}"
                                                    {{ $class->id == $advisoryClass ? 'selected' : '' }}>
                                                    {{ strtoupper($class->formattedGradeLevel ?? $class->grade_level) }} -
                                                    {{ $class->section }}
                                                </option>
                                            @endif
                                        @endforeach
                                    </select>
                                    <small class="text-muted">Must be one of the assigned classes</small>
                                </div>
                            </div>

                            <div class="row">

                                <!-- First Name Field -->
                                <div class="col mb-2 mt-2">
                                    <label for="firstName" class="form-label fw-bold">First
                                        Name</label>
                                    <input type="text" name="firstName" id="firstName" class="form-control"
                                        value="{{ $teacher->firstName }}" required />
                                </div>

                                <!-- Middle Name Field -->
                                <div class="col mb-2 mt-2">
                                    <label for="middleName" class="form-label fw-bold">Middle
                                        Name</label>
                                    <input type="text" name="middleName" id="middleName" class="form-control"
                                        value="{{ $teacher->middleName }}" />
                                </div>

                            </div>

                            <div class="row">
                                <!-- Last Name Field -->
                                <div class="col mb-2 mt-2">
                                    <label for="lastName" class="form-label fw-bold">Last Name</label>
                                    <input type="text" name="lastName" id="lastName" class="form-control"
                                        value="{{ $teacher->lastName }}" required />
                                </div>

                                <!-- Last Name Field -->
                                <div class="col mb-2 mt-2">
                                    <label for="extName" class="form-label fw-bold">Extension Name</label>
                                    <input type="text" name="extName" id="extName" class="form-control"
                                        value="{{ $teacher->extName }}" />
                                </div>
                            </div>

                            <div class="row">
                                <!-- Email Field -->
                                <div class="col mb-3">
                                    <label for="email" class="form-label fw-bold">Email</label>
                                    <input type="email" name="email" id="email" class="form-control"
                                        value="{{ $teacher->email }}" />
                                </div>

                                <!-- Phone Field -->
                                <div class="col mb-3">
                                    <label for="phone" class="form-label fw-bold">Phone No.</label>
                                    <input type="phone" name="phone" id="phone" class="form-control"
                                        value="{{ $teacher->phone }}" />
                                </div>
                            </div>

                            <div class="row">
                                <!-- Gender Field -->
                                <div class="col mb-2 mt-2">
                                    <label for="gender" class="form-label fw-bold">Gender</label>
                                    <select name="gender" id="gender" class="form-select" required>
                                        <option value="male"{{ $teacher->gender == 'Male' ? 'selected' : '' }}>
                                            Male</option>
                                        <option value="female"{{ $teacher->gender == 'Female' ? 'selected' : '' }}>
                                            Female</option>
                                    </select>
                                </div>

                                <!-- Date of Birth Field -->
                                <div class="col mb-2 mt-2">
                                    <label for="dob" class="form-label fw-bold">Date of
                                        Birth</label>
                                    <input class="form-control" value="{{ $teacher->dob }}" name="dob"
                                        type="date" id="dob" />
                                </div>
                            </div>

                            <hr class="my-4" />

                            <h5 class="fw-bold text-primary">{{ $teacher->firstName }} {{ $teacher->lastName }}'s
                                Addresses</h5>

                            <div class="row">
                                <!-- House No. field -->
                                <div class="col mb-3">
                                    <label for="house_no" class="form-label fw-bold">House No.</label>
                                    <input type="text" value="{{ $teacher->house_no }}" name="house_no"
                                        id="house_no" class="form-control" />
                                </div>

                                <!-- Street Name field -->
                                <div class="col mb-3">
                                    <label for="street_name" class="form-label fw-bold">Street
                                        Name</label>
                                    <input type="text" value="{{ $teacher->street_name }}" name="street_name"
                                        id="street_name" class="form-control" />
                                </div>
                            </div>

                            <div class="row">
                                <!-- Barangay field -->
                                <div class="col mb-3">
                                    <label for="barangay" class="form-label fw-bold">Barangay</label>
                                    <input type="text" value="{{ $teacher->barangay }}" name="barangay"
                                        id="barangay" class="form-control" />
                                </div>

                                <!-- Municipality/City field -->
                                <div class="col mb-3">
                                    <label for="municipality_city" class="form-label fw-bold">Municipality/City</label>
                                    <input type="text" value="{{ $teacher->municipality_city }}"
                                        name="municipality_city" id="municipality_city" class="form-control" />
                                </div>
                            </div>

                            <div class="row">
                                <!-- Province field -->
                                <div class="col mb-3">
                                    <label for="province" class="form-label fw-bold">Province</label>
                                    <input type="text" value="{{ $teacher->province }}" name="province"
                                        id="province" class="form-control">
                                </div>

                                <!-- Zip Code field -->
                                <div class="col mb-3">
                                    <label for="zip_code" class="form-label fw-bold">Zip Code</label>
                                    <input type="text" value="{{ $teacher->zip_code }}" name="zip_code"
                                        id="zip_code" class="form-control" />
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">

                        </div>
                    </form>
                    <!-- /Teacher's Details -->

                </div>
                <!-- / Content wrapper -->
                <hr class="my-5" />
            </div>
            <!-- /Account -->
        </div>
    </div>
    <!-- / Layout Wrapper -->


@endsection

@push('scripts')
    <script>
        //cancel button alert in registration
        let formChanged = false;
        const form = document.getElementById('editTeacherForm');

        // Detect form changes
        form.addEventListener('input', () => {
            formChanged = true;
        });

        // Cancel button logic
        function handleCancel() {
            if (!formChanged) {
                // If form is untouched, just redirect
                window.location.href = "{{ route('show.teachers') }}";
            } else {
                // If form has been changed, ask for confirmation
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
                        window.location.href = "{{ route('show.teachers') }}";
                    }
                    // If cancelled, do nothing
                });
            }
        }

        //save button
        document.getElementById('saveChangesBtn').addEventListener('click', function() {
            Swal.fire({
                title: "Save changes?",
                text: "Are you sure you want to update this teacher's details?",
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
                            document.getElementById('editTeacherForm')
                                .submit(); // Submit the form after short delay
                        }
                    });
                }
            });
        });
    </script>

    <script>
        //logout button
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
        // image profile upload/preview
        document.addEventListener('DOMContentLoaded', function() {
            const fileInput = document.getElementById('upload');
            const resetBtn = document.querySelector('.account-image-reset');
            const imagePreview = document.querySelector('.profile-preview');

            if (!fileInput || !resetBtn || !imagePreview) {
                console.warn('Required elements not found.');
                return;
            }

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
                fileInput.value = ''; // Clear the file input
                imagePreview.src = originalImageSrc; // Reset image
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const assignedSelect = document.getElementById('assigned_classes');
            const advisorySelect = document.getElementById('advisory_class');

            // This array will be filled with IDs of classes that already have advisers
            const classesWithAdvisers = @json($allClasses->filter(fn($class) => $class->teachers->where('pivot.role', 'adviser')->isNotEmpty())->pluck('id'));
            const oldAdvisory = "{{ $advisoryClass }}";

            function updateAdvisoryOptions() {
                const selectedAssigned = Array.from(assignedSelect.selectedOptions).map(opt => opt.value);

                advisorySelect.innerHTML = '<option value=""> No advisory class </option>';

                Array.from(assignedSelect.options).forEach(option => {
                    const classId = option.value;

                    // Allow only assigned classes that do NOT have an adviser already, OR that are already selected as advisory
                    if (selectedAssigned.includes(classId) && (!classesWithAdvisers.includes(parseInt(
                            classId)) || classId === oldAdvisory)) {
                        const newOption = document.createElement('option');
                        newOption.value = classId;
                        newOption.textContent = option.textContent;

                        if (classId === oldAdvisory) {
                            newOption.selected = true;
                        }

                        advisorySelect.appendChild(newOption);
                    }
                });
            }

            updateAdvisoryOptions();
            assignedSelect.addEventListener('change', updateAdvisoryOptions);
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/tom-select/dist/js/tom-select.complete.min.js"></script>
    <script>
        new TomSelect('#assigned_classes', {
            plugins: ['remove_button'],
            maxItems: null,
            placeholder: "Select classes...",
        });
    </script>
@endpush
@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/tom-select/dist/css/tom-select.css" rel="stylesheet">
    <style>
        .ts-control {
            background-color: #e0f7fa;
            border-color: #42a5f5;
        }

        .ts-control .item {
            background-color: #4dd0e1;
            color: white;
            border-radius: 4px;
            padding: 3px 8px;
            margin-right: 4px;
        }

        .ts-dropdown .option.active {
            background-color: #e3f2fd;
            color: #1976d2;
        }
    </style>
@endpush
