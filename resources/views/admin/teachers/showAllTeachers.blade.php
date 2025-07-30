@extends('./layouts.main')

@section('title', 'Admin | All Teachers')


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
                                    <div class="text-warning">All Teachers</div>
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
                            <a class="text-muted fw-light" href="{{ route('show.teachers') }}">Teachers / </a>
                        </span> All Teachers
                    </h4>

                    {{-- Notification when year is changed --}}
                    @if (session('school_year_notice'))
                        <div class="alert alert-info alert-dismissible fade show mt-2 text-center text-primary fw-bold"
                            role="alert" id="school-year-alert">
                            {{ session('school_year_notice') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <script>
                        setTimeout(function() {
                            var alertElem = document.getElementById('school-year-alert');
                            if (alertElem) {
                                var bsAlert = bootstrap.Alert.getOrCreateInstance(alertElem);
                                bsAlert.close();
                            }
                        }, 10000);
                    </script>

                    <!-- Modal Backdrop -->
                    <div class="col-lg-4 col-md-3">

                        <div class="mt-3">
                            {{-- @php
                                $now = now();
                                $year = $now->year;
                                $cutoff = $now->copy()->setMonth(6)->setDay(1);
                                $currentYear = $now->lt($cutoff) ? $year - 1 : $year;

                                $allowedYears = [
                                    $currentYear . '-' . ($currentYear + 1),
                                    $currentYear + 1 . '-' . ($currentYear + 2),
                                ];
                            @endphp

                            @if (in_array($selectedYear, $allowedYears))
                                <!-- Button trigger modal -->
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#registerModal"
                                    style="margin: auto; margin-bottom: 30px; margin-left: 10px">
                                    Register New Teacher
                                </button>
                            @endif --}}

                            <!-- Register Modal -->
                            <div class="modal fade" id="registerModal" data-bs-backdrop="static" tabindex="-1">

                                <div class="modal-dialog">
                                    <form class="modal-content" action="{{ route('register.teacher') }}" method="POST"
                                        enctype="multipart/form-data">
                                        @csrf
                                        <div class="modal-header">
                                            <h4 class="modal-title fw-bold text-info" id="registerModalTitle">
                                                REGISTER NEW TEACHER
                                            </h4>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">

                                            <h5 class="fw-bold text-primary">Teacher's Personal Information</h5>

                                            <input type="hidden" name="selected_school_year"
                                                value="{{ $selectedYear }}">

                                            <!-- Profile Photo Upload with Preview and Default -->
                                            <div class="row">
                                                <div class="col mb-3 d-flex align-items-start align-items-sm-center gap-4">
                                                    <div class="mb-3">
                                                        <img id="photo-preview"
                                                            src="{{ asset('assetsDashboard/img/profile_pictures/teachers_default_profile.jpg') }}"
                                                            alt="Profile Preview" width="100" height="100"
                                                            class="profile-preview"
                                                            style="object-fit: cover; border-radius: 5%">
                                                    </div>

                                                    <div class="button-wrapper">
                                                        <label for="upload" class="btn btn-warning me-2 mb-2"
                                                            tabindex="0">
                                                            <span class="d-none d-sm-block">Upload new photo</span>
                                                            <i class="bx bx-upload d-block d-sm-none"></i>
                                                            <input type="file" id="upload" name="profile_photo"
                                                                class="account-file-input" hidden
                                                                accept="image/png, image/jpeg" />
                                                        </label>

                                                        <button type="button"
                                                            class="btn btn-outline-secondary account-image-reset mb-2"
                                                            id="reset-photo">
                                                            <i class="bx bx-reset d-block d-sm-none"></i>
                                                            <span class="d-none d-sm-block">Reset</span>
                                                        </button>

                                                        <p class="text-muted mb-0">Allowed JPG or PNG. Max size of 2MB</p>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <!-- First Name Field -->
                                                <div class="col mb-3">
                                                    <label for="firstName" class="form-label fw-bold">First
                                                        Name</label>
                                                    <input type="text" name="firstName" id="firstName"
                                                        class="form-control" placeholder="Enter First Name" required
                                                        value="{{ old('firstName') }}" autofocus
                                                        autocomplete="firstName" />
                                                </div>

                                                <!-- Middle Name Field -->
                                                <div class="col mb-3">
                                                    <label for="middleName" class="form-label fw-bold">Middle
                                                        Name</label>
                                                    <input type="text" name="middleName" id="middleName"
                                                        class="form-control" placeholder="Enter Middle Name" required
                                                        value="{{ old('middleName') }}" autofocus
                                                        autocomplete="middleName" />
                                                </div>

                                            </div>

                                            <div class="row g-4">

                                                <!-- Last Name Field -->
                                                <div class="col mb-3">
                                                    <label for="lastName" class="form-label fw-bold">Last Name</label>
                                                    <input type="text" name="lastName" id="lastName"
                                                        class="form-control" placeholder="Enter Last Name" required
                                                        value="{{ old('lastName') }}" autofocus
                                                        autocomplete="lastName" />
                                                </div>

                                                <!-- Extension Name Field -->
                                                <div class="col mb-3">
                                                    <label for="extName" class="form-label fw-bold">Extension
                                                        Name</label>
                                                    <input type="text" name="extName" id="extName"
                                                        class="form-control" placeholder="Enter Ext. Name(if applicable)"
                                                        value="{{ old('extName') }}" />
                                                </div>
                                            </div>

                                            <div class="row">
                                                <!-- Email Field -->
                                                <div class="col mb-3">
                                                    <label for="email" class="form-label fw-bold">Email</label>
                                                    <input type="email" name="email" id="email"
                                                        class="form-control @error('email') is-invalid @enderror"
                                                        placeholder="xxxx@xxx.xx" required autocomplete="email"
                                                        value="{{ old('email') }}" />

                                                    @error('email')
                                                        <div class="invalid-feedback">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>


                                                <!-- Phone Field -->
                                                <div class="col mb-3">
                                                    <label for="phone" class="form-label fw-bold">Phone</label>
                                                    <input type="phone" name="phone" id="phone"
                                                        class="form-control" placeholder="Enter Phone Number"
                                                        value="{{ old('phone') }}" autofocus autocomplete="phone" />
                                                </div>
                                            </div>

                                            <div class="row">
                                                <!-- Gender Field -->
                                                <div class="col mb-3">
                                                    <label for="gender" class="form-label fw-bold">Gender</label>
                                                    <select name="gender" id="gender" class="form-select" required>
                                                        <option value="" disabled
                                                            {{ old('gender') ? '' : 'selected' }}>Select Gender</option>
                                                        <option value="male"
                                                            {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                                                        <option value="female"
                                                            {{ old('gender') == 'female' ? 'selected' : '' }}>Female
                                                        </option>
                                                    </select>
                                                </div>

                                                <!-- Date of Birth Field -->
                                                <div class="col mb-3">
                                                    <label for="dob" class="form-label fw-bold">Date of
                                                        Birth</label>
                                                    <input class="form-control" name="dob" type="date"
                                                        id="dob" value="{{ old('dob') }}" />
                                                </div>
                                            </div>

                                            <hr class="my-4" />


                                            <h5 class="fw-bold text-primary">Classes</h5>

                                            <div class="row">
                                                <!-- Assigned Classes as Multi-select Dropdown -->
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label fw-bold">Assign Classes</label>
                                                    <select name="assigned_classes[]" id="assigned_classes" multiple
                                                        required>
                                                        @foreach ($allClasses as $class)
                                                            @php
                                                                $adviser = $class->teachers
                                                                    ->where('pivot.role', 'adviser')
                                                                    ->first();
                                                                $hasAdviser = !is_null($adviser);
                                                                $isSelected = collect(
                                                                    old('assigned_classes'),
                                                                )->contains($class->id);
                                                            @endphp
                                                            <option value="{{ $class->id }}"
                                                                {{ $isSelected ? 'selected' : '' }}>
                                                                {{ strtoupper($class->formattedGradeLevel ?? $class->grade_level) }}
                                                                - {{ $class->section }}
                                                                @if ($hasAdviser)
                                                                    ({{ $adviser->firstName }} {{ $adviser->lastName }})
                                                                @endif
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <small class="form-text text-muted text-center">
                                                        You can select multiple classes
                                                    </small>
                                                </div>

                                                <!-- Advisory Class Dropdown -->
                                                <div class="col-md-6 mb-3">
                                                    <label for="advisory_class" class="form-label fw-bold">Select Advisory
                                                        Class</label>
                                                    <select name="advisory_class" id="advisory_class" class="form-select"
                                                        data-old="{{ old('advisory_class') }}">
                                                        <option value="">-- Select advisory class from assigned --
                                                        </option>
                                                        {{-- Options will be dynamically injected --}}
                                                    </select>
                                                    <small class="form-text text-muted text-center">Select an advisory
                                                        class from the assigned classes, or leave empty if none.</small>
                                                </div>
                                            </div>

                                            <hr class="my-4" />

                                            <h5 class="fw-bold text-primary">Address</h5>

                                            <div class="row">
                                                <!-- House No. field -->
                                                <div class="col mb-3">
                                                    <label for="house_no" class="form-label fw-bold">House No.</label>
                                                    <input type="text" name="house_no" id="house_no"
                                                        class="form-control" placeholder="Enter House No."
                                                        value="{{ old('house_no') }}" />
                                                </div>

                                                <!-- Street Name field -->
                                                <div class="col mb-3">
                                                    <label for="street_name" class="form-label fw-bold">Street
                                                        Name</label>
                                                    <input type="text" name="street_name" id="street_name"
                                                        class="form-control" placeholder="Enter Street Name"
                                                        value="{{ old('street_name') }}" />
                                                </div>
                                            </div>

                                            <div class="row">
                                                <!-- Barangay field -->
                                                <div class="col mb-3">
                                                    <label for="barangay" class="form-label fw-bold">Barangay</label>
                                                    <input type="text" name="barangay" id="barangay"
                                                        class="form-control" placeholder="Enter Barangay"
                                                        value="{{ old('barangay') }}" />
                                                </div>

                                                <!-- Municipality/City field -->
                                                <div class="col mb-3">
                                                    <label for="municipality_city"
                                                        class="form-label fw-bold">Municipality/City</label>
                                                    <input type="text" name="municipality_city" id="municipality_city"
                                                        class="form-control" placeholder="Enter Municipality/City"
                                                        value="{{ old('municipality_city') }}" />
                                                </div>
                                            </div>

                                            <div class="row">
                                                <!-- Province field -->
                                                <div class="col mb-3">
                                                    <label for="province" class="form-label fw-bold">Province</label>
                                                    <input type="text" name="province" id="province"
                                                        class="form-control" placeholder="Enter Province"
                                                        value="{{ old('province') }}" />
                                                </div>

                                                <!-- Zip Code field -->
                                                <div class="col mb-3">
                                                    <label for="zip_code" class="form-label fw-bold">Zip Code</label>
                                                    <input type="text" name="zip_code" id="zip_code"
                                                        class="form-control" placeholder="Enter Zip Code"
                                                        value="{{ old('zip_code') }}" />
                                                </div>
                                            </div>

                                            <hr class="my-4" />

                                            <h5 class="fw-bold text-primary">Password</h5>

                                            <!-- Password Input -->
                                            <div class="mb-3 form-password-toggle">
                                                <label class="form-label fw-bold" for="password">Password</label>

                                                <div class="input-group input-group-merge">
                                                    <input type="password" id="password" class="form-control"
                                                        name="password" required autocomplete="new-password"
                                                        placeholder="Enter your Password" />

                                                    @error('password')
                                                        <div class="text-danger mt-1">{{ $message }}</div>
                                                    @enderror


                                                    <span class="input-group-text cursor-pointer"><i
                                                            class="bx bx-hide"></i></span>
                                                </div>
                                            </div>

                                            <!-- Confirm Password Input -->
                                            <div class="mb-3 form-password-toggle">
                                                <label class="form-label fw-bold" for="password_confirmation">Confirm
                                                    Password</label>
                                                <div class="input-group input-group-merge">
                                                    <input type="password" id="password_confirmation"
                                                        class="form-control" name="password_confirmation" required
                                                        autocomplete="new-password" placeholder="Confirm your Password" />
                                                    @error('password')
                                                        <div class="text-danger mt-1">{{ $message }}</div>
                                                    @enderror
                                                    <span class="input-group-text cursor-pointer"><i
                                                            class="bx bx-hide"></i></span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-outline-secondary"
                                                data-bs-dismiss="modal">
                                                Close
                                            </button>
                                            <button type="submit" class="btn btn-primary"
                                                id="registerTeacherBtn">Register</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <!-- /Register Modal -->

                            <!-- Re-assign Modal -->
                            <div class="modal fade" id="reAssignModal" data-bs-backdrop="static" tabindex="-1">
                                <div class="modal-dialog">
                                    <form class="modal-content" action="{{ route('teacher.reassignment') }}"
                                        method="POST">
                                        @csrf
                                        <div class="modal-header">
                                            <h4 class="modal-title fw-bold text-info" id="reAssignModalTitle">
                                                RE-ASSIGN TEACHER TO THE SELECTED SCHOOL YEAR
                                            </h4>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <input type="hidden" name="selected_school_year"
                                                value="{{ $selectedYear }}">

                                            <!-- Select Teacher -->
                                            <div class="mb-3">
                                                <label for="teacher_id" class="form-label fw-bold">Select Teacher</label>
                                                <select name="teacher_id" id="teacher_id" class="form-select" required>
                                                    <option value="">-- Select Teacher --</option>
                                                    @foreach ($reAssignableTeachers as $teacher)
                                                        @php
                                                            $archivedYears = $teacher->classes
                                                                ->filter(function ($class) use ($selectedYear) {
                                                                    return $class->pivot->status === 'archived';
                                                                })
                                                                ->map(function ($class) {
                                                                    return $class->pivot->school_year_id;
                                                                })
                                                                ->unique()
                                                                ->values();

                                                            $archivedYearLabels = \App\Models\SchoolYear::whereIn(
                                                                'id',
                                                                $archivedYears,
                                                            )
                                                                ->pluck('school_year')
                                                                ->implode(', ');
                                                        @endphp
                                                        <option value="{{ $teacher->id }}">
                                                            {{ $teacher->firstName }} {{ $teacher->lastName }}
                                                            (Archived: {{ $archivedYearLabels }})
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <!-- Select Classes -->
                                            <div class="mb-3">
                                                <label for="reassign_classes" class="form-label fw-bold">Assign to
                                                    Classes</label>
                                                <select name="reassign_classes[]" id="reassign_classes" multiple required>
                                                    @foreach ($allClasses as $class)
                                                        @php
                                                            $adviser = $class->teachers
                                                                ->where('pivot.role', 'adviser')
                                                                ->first();
                                                            $hasAdviser = !is_null($adviser);
                                                        @endphp
                                                        <option value="{{ $class->id }}">
                                                            {{ strtoupper($class->formattedGradeLevel ?? $class->grade_level) }}
                                                            - {{ $class->section }}
                                                            @if ($hasAdviser)
                                                                ({{ $adviser->firstName }} {{ $adviser->lastName }})
                                                            @endif
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <small class="form-text text-muted">Hold Ctrl or Cmd to select multiple
                                                    classes.</small>
                                            </div>

                                            <!-- Select Advisory Class -->
                                            <div class="mb-3">
                                                <label for="reassign_advisory_class" class="form-label fw-bold">Select
                                                    Advisory Class</label>
                                                <select name="reassign_advisory_class" id="reassign_advisory_class"
                                                    class="form-select">
                                                    <option value="">-- Select advisory class from assigned --
                                                    </option>
                                                </select>
                                                <small class="form-text text-muted">Must be one of the selected
                                                    classes.</small>
                                            </div>
                                        </div>

                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-outline-secondary"
                                                data-bs-dismiss="modal">
                                                Close
                                            </button>
                                            <button type="submit" class="btn btn-primary"
                                                id="reAssignTeacherBtn">Re-Assign</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <!-- /Re-assign Modal -->

                            @if ($errors->has('email'))
                                <script>
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Registration Error',
                                        text: '{{ $errors->first('email') }}',
                                        confirmButtonColor: '#dc3545',
                                        customClass: {
                                            container: 'my-swal-container'
                                        }
                                    });
                                </script>
                            @endif

                        </div>
                    </div>
                    <!--/ Modal Backdrop -->

                    <div class="d-flex justify-content-between align-items-center flex-wrap mb-3">
                        <div class="d-flex gap-1 mb-2 mb-md-0">
                            <!-- Register Button trigger modal -->
                            <button type="button" class="btn btn-primary d-flex align-items-center"
                                data-bs-toggle="modal" data-bs-target="#registerModal">
                                <i class='bx bx-user-plus me-2'></i>
                                <span class="d-none d-sm-block">Register New Teacher</span>
                            </button>

                            <!-- Re-Assignment Button trigger modal -->
                            <button type="button" class="btn btn-warning d-flex align-items-center"
                                data-bs-toggle="modal" data-bs-target="#reAssignModal">
                                <i class='bx bx-repost me-2'></i>
                                <span class="d-none d-sm-block">Re-Assign Teacher</span>
                            </button>
                        </div>

                        <!-- Filter Form -->
                        <div class="d-flex align-items-center gap-2">
                            <form method="GET" action="" class="d-flex align-items-center">
                                <span class="d-none d-sm-block form-label me-2 mb-0">School Year</span>
                                <select name="school_year" id="school_year" class="form-select"
                                    onchange="this.form.submit()">
                                    @foreach ($schoolYears as $year)
                                        <option value="{{ $year }}"
                                            {{ $year == $selectedYear ? 'selected' : '' }}>
                                            {{ $year }}
                                        </option>
                                    @endforeach
                                </select>
                            </form>

                            <!-- "Now" button -->
                            <form method="GET" action="{{ route('show.teachers') }}"
                                class="d-flex align-items-center">
                                <input type="hidden" name="school_year"
                                    value="{{ $currentYear . '-' . ($currentYear + 1) }}">
                                <button type="submit" class="btn btn-sm btn-primary">
                                    Now
                                </button>
                            </form>
                        </div>
                    </div>

                    {{-- Card --}}
                    <div class="card">
                        <div class="card-header">
                            <h3 class="text-center text-info fw-bold mb-4">Teacher Management</h3>

                            <div
                                class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-2 mb-3">
                                <!-- Search input -->
                                <div class="d-flex align-items-center w-100" style="max-width: 600px;">
                                    <i class="bx bx-search fs-4 lh-0 me-2"></i>
                                    <input type="text" id="teacherSearch" class="form-control border-1 shadow-none"
                                        placeholder="Search..." aria-label="Search..." />
                                </div>

                                <!-- Export button -->
                                <div class="ms-md-auto">
                                    <a href="{{ route('export.teachers') }}" class="btn btn-success"
                                        style="padding: 8px 12px;">
                                        <i class="bx bx-printer"></i> Export to Excel
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive text-nowrap">
                            <table class="table table-hover table-bordered text-center" id="teachersTable">
                                <thead>
                                    <tr>
                                        <th>Full Name</th>
                                        <th>Photo</th>
                                        <th><span class="badge bg-warning mb-1"
                                                style="font-size: 0.95em; vertical-align: middle;">Advisory Class
                                                <i class="bx bxs-star text-auto ms-1" title="Advisory Class"
                                                    style="font-size: 0.95em; vertical-align: middle;"></i></span><br>
                                            <span class="badge bg-secondary"
                                                style="font-size: 0.95em; vertical-align: middle;">Subject-based
                                                Classes</span>
                                        </th>
                                        <th>Email</th>
                                        <th>Status</th>
                                        <th>Contact No.</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="table-border-bottom-0">
                                    @forelse ($teachers as $teacher)
                                        <tr class="teacher-row">
                                            <td>{{ $teacher->firstName }} {{ $teacher->middleName }}
                                                {{ $teacher->lastName }} {{ $teacher->extName }}</td>
                                            <td>
                                                @if ($teacher->profile_photo)
                                                    <img src="{{ asset('storage/' . $teacher->profile_photo) }}"
                                                        alt="Profile Photo" width="55" height="55"
                                                        style="object-fit: cover; border-radius: 50%;">
                                                @else
                                                    <img src="{{ asset('assetsDashboard/img/profile_pictures/teachers_default_profile.jpg') }}"
                                                        alt="no profile" width="55" height="55"
                                                        style="object-fit: cover; border-radius: 50%">
                                                @endif
                                            </td>
                                            <td>
                                                @if ($teacher->classes && count($teacher->classes))
                                                    @foreach ($teacher->classes as $class)
                                                        @php
                                                            $role = $class->pivot->role ?? null;
                                                            $isAdvisory = $role === 'adviser';
                                                            $badgeClass = $isAdvisory ? 'bg-warning text-black' : 'bg-secondary';
                                                        @endphp

                                                        <span class="badge {{ $badgeClass }} text-auto mb-1">
                                                            {{ strtoupper($class->formattedGradeLevel ?? $class->grade_level) }}
                                                            - Section {{ $class->section }}
                                                            @if ($isAdvisory)
                                                                <i class="bx bxs-star text-auto ms-1"
                                                                    title="Advisory Class"
                                                                    style="font-size: 0.85em; vertical-align: middle;"></i>
                                                            @endif
                                                        </span>

                                                        @if (!$loop->last)
                                                            <br>
                                                        @endif
                                                    @endforeach
                                                @else
                                                    <span class="text-muted">No class assigned</span>
                                                @endif
                                            </td>
                                            <td>{{ $teacher->email }}</td>
                                            <td>
                                                @php
                                                    $status =
                                                        optional($teacher->classes->first())->pivot->status ?? 'N/A';
                                                    $badgeClass = match ($status) {
                                                        'active' => 'bg-label-success',
                                                        'archived' => 'bg-label-warning',
                                                        default => 'bg-label-dark',
                                                    };
                                                @endphp
                                                <span class="badge {{ $badgeClass }} text-uppercase px-3 py-1">
                                                    {{ strtoupper(str_replace('_', ' ', $status)) }}
                                                </span>
                                            </td>
                                            <td>{{ $teacher->phone }}</td>
                                            <td>
                                                <div class="dropdown">
                                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                                        data-bs-toggle="dropdown">
                                                        <i class="bx bx-dots-vertical-rounded"></i>
                                                    </button>
                                                    <div class="dropdown-menu">
                                                        <a class="dropdown-item text-info"
                                                            href="{{ route('teacher.info', ['id' => $teacher->id, 'school_year' => $selectedYear]) }}">
                                                            <i class="bx bxs-user-badge me-1"></i> View Profile
                                                        </a>
                                                        <a class="dropdown-item text-warning"
                                                            href="{{ route('edit.teacher', ['id' => $teacher->id, 'school_year' => $selectedYear]) }}">
                                                            <i class="bx bx-edit-alt me-1"></i> Edit
                                                        </a>
                                                        @php
                                                            $selectedYear = request('school_year'); // Or pass explicitly
                                                        @endphp

                                                        <button type="button" class="dropdown-item text-danger"
                                                            onclick="confirmDelete({{ $teacher->id }}, '{{ $teacher->firstName }}', '{{ $teacher->lastName }}', '{{ $selectedYear }}')">
                                                            <i class="bx bx-trash me-1"></i> Delete
                                                        </button>

                                                        <!-- Hidden form to submit delete -->
                                                        <form id="delete-form-{{ $teacher->id }}-{{ $selectedYear }}"
                                                            action="{{ route('delete.teacher', ['id' => $teacher->id, 'school_year' => $selectedYear]) }}"
                                                            method="POST" style="display: none;">
                                                            @csrf
                                                            @method('DELETE')
                                                        </form>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center text-danger fw-bold">No teachers
                                                found.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    {{-- / Card --}}


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
        // Search bar filter
        document.getElementById('teacherSearch').addEventListener('keyup', function() {
            const searchValue = this.value.toLowerCase();
            document.querySelectorAll('#teachersTable tbody .teacher-row').forEach(row => {
                row.style.display = row.innerText.toLowerCase().includes(searchValue) ? '' : 'none';
            });
        });

        // Delete confirmation
        function confirmDelete(teacherId, firstName, lastName, schoolYear) {
            Swal.fire({
                title: `Remove ${firstName} ${lastName} from ${schoolYear}?`,
                text: "They will be unassigned from all classes for this school year only.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#6c757d",
                confirmButtonText: "Yes, remove",
                cancelButtonText: "Cancel",
                customClass: {
                    container: 'my-swal-container'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(`delete-form-${teacherId}-${schoolYear}`).submit();
                }
            });
        }

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

        // Profile photo preview and reset
        const uploadInput = document.getElementById('upload');
        const previewImg = document.getElementById('photo-preview');
        const resetBtn = document.getElementById('reset-photo');
        const defaultImage = "{{ asset('assetsDashboard/img/profile_pictures/teachers_default_profile.jpg') }}";

        uploadInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = e => previewImg.src = e.target.result;
                reader.readAsDataURL(file);
            }
        });

        resetBtn.addEventListener('click', function() {
            uploadInput.value = '';
            previewImg.src = defaultImage;
        });

        // Register Teacher Modal Form Validation and Submission
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('#registerModal form');
            const registerBtn = document.getElementById('registerTeacherBtn');

            registerBtn.addEventListener('click', function(e) {
                e.preventDefault();

                let allFilled = true;
                let passwordErrors = [];
                form.querySelectorAll('[required]').forEach(field => {
                    if (!field.value.trim()) {
                        allFilled = false;
                        field.classList.add('is-invalid');
                    } else {
                        field.classList.remove('is-invalid');
                    }
                });

                const password = form.password.value;
                const passwordConfirm = form.password_confirmation.value;

                if (password.length < 8) passwordErrors.push("Password must be at least 8 characters.");
                if (!/[a-z]/.test(password)) passwordErrors.push(
                    "Password must include at least one lowercase letter.");
                if (!/[A-Z]/.test(password)) passwordErrors.push(
                    "Password must include at least one uppercase letter.");
                if (!/[0-9]/.test(password)) passwordErrors.push(
                    "Password must include at least one number.");
                if (!/[@$!%*#?&]/.test(password)) passwordErrors.push(
                    "Password must include at least one special character (@$!%*#?&).");
                if (password !== passwordConfirm) passwordErrors.push(
                    "Password confirmation does not match.");

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

                if (passwordErrors.length) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Invalid Password',
                        html: passwordErrors.join('<br>'),
                        confirmButtonColor: '#dc3545',
                        customClass: {
                            container: 'my-swal-container'
                        }
                    });
                    return;
                }

                Swal.fire({
                    title: "Register Teacher?",
                    text: "Are you sure all the details are correct?",
                    icon: "question",
                    showCancelButton: true,
                    confirmButtonColor: "#06D001",
                    cancelButtonColor: "#6c757d",
                    confirmButtonText: "Yes, register",
                    cancelButtonText: "Cancel",
                    customClass: {
                        container: 'my-swal-container'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });

            // Advisory class options update
            const classesWithAdvisers = @json($allClasses->filter(fn($class) => $class->teachers->where('pivot.role', 'adviser')->isNotEmpty())->pluck('id'));
            const assignedSelect = document.getElementById('assigned_classes');
            const advisorySelect = document.getElementById('advisory_class');
            const oldAdvisory = advisorySelect.dataset.old;

            function updateAdvisoryOptions() {
                const selectedAssigned = Array.from(assignedSelect.selectedOptions).map(opt => parseInt(opt.value));
                advisorySelect.innerHTML = '<option value="">-- Select advisory class from assigned --</option>';
                Array.from(assignedSelect.options).forEach(option => {
                    const classId = parseInt(option.value);
                    if (selectedAssigned.includes(classId) && !classesWithAdvisers.includes(classId)) {
                        const newOption = document.createElement('option');
                        newOption.value = option.value;
                        newOption.textContent = option.textContent;
                        if (oldAdvisory && option.value === oldAdvisory) newOption.selected = true;
                        advisorySelect.appendChild(newOption);
                    }
                });
            }
            updateAdvisoryOptions();
            assignedSelect.addEventListener('change', updateAdvisoryOptions);
        });

        // Re-assignment Modal Form Validation and Submission
        document.addEventListener('DOMContentLoaded', function() {
            const reassignForm = document.querySelector('#reAssignModal form');
            const reassignBtn = document.getElementById('reAssignTeacherBtn');
            const classSelect = document.getElementById('reassign_classes');
            const advisorySelect = document.getElementById('reassign_advisory_class');

            // IDs of classes that already have advisers
            const reassignClassesWithAdvisers = @json($allClasses->filter(fn($class) => $class->teachers->where('pivot.role', 'adviser')->isNotEmpty())->pluck('id'));

            function updateAdvisoryClassOptions() {
                const selected = Array.from(classSelect.selectedOptions).map(opt => parseInt(opt.value));
                advisorySelect.innerHTML = '<option value="">-- Select advisory class from assigned --</option>';
                Array.from(classSelect.options).forEach(opt => {
                    const id = parseInt(opt.value);
                    if (selected.includes(id) && !reassignClassesWithAdvisers.includes(id)) {
                        const newOpt = document.createElement('option');
                        newOpt.value = opt.value;
                        newOpt.textContent = opt.textContent;
                        advisorySelect.appendChild(newOpt);
                    }
                });
            }

            classSelect.addEventListener('change', updateAdvisoryClassOptions);
            updateAdvisoryClassOptions();

            reassignBtn.addEventListener('click', function(e) {
                e.preventDefault();

                let valid = true;
                reassignForm.querySelectorAll('[required]').forEach(input => {
                    if (!input.value.trim()) {
                        valid = false;
                        input.classList.add('is-invalid');
                    } else {
                        input.classList.remove('is-invalid');
                    }
                });

                if (!valid) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Incomplete Form',
                        text: 'Please fill in all required fields.',
                        confirmButtonColor: '#dc3545',
                        customClass: {
                            container: 'my-swal-container'
                        }
                    });
                    return;
                }

                Swal.fire({
                    title: "Confirm Re-Assignment?",
                    text: "Are you sure you want to re-assign this teacher?",
                    icon: "question",
                    showCancelButton: true,
                    confirmButtonColor: "#06D001",
                    cancelButtonColor: "#6c757d",
                    confirmButtonText: "Yes, re-assign",
                    cancelButtonText: "Cancel",
                    customClass: {
                        container: 'my-swal-container'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        reassignForm.submit();
                    }
                });
            });
        });

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

        // Registration error alert (email)
        @if ($errors->has('email'))
            Swal.fire({
                icon: 'error',
                title: 'Registration Error',
                text: '{{ $errors->first('email') }}',
                confirmButtonColor: '#dc3545',
                customClass: {
                    container: 'my-swal-container'
                }
            });
        @endif
    </script>

    <script src="https://cdn.jsdelivr.net/npm/tom-select/dist/js/tom-select.complete.min.js"></script>

    <script>
        new TomSelect('#assigned_classes', {
            plugins: ['remove_button'],
            maxItems: null,
            placeholder: "Select classes...",
        });

        new TomSelect('#reassign_classes', {
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
