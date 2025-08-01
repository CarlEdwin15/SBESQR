@extends('./layouts.main')

@section('title', 'Admin | Edit Student')


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
                            <a class="text-muted fw-light" href="{{ route('show.students') }}">Students / </a>
                        </span> Edit Student's Information
                    </h4>

                    <hr class="my-4" />

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

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

                    <!-- Student's Details -->
                    <form class="modal-content" action="{{ route('update.student', ['id' => $student->id]) }}"
                        id="editStudentForm" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="modal-body">
                            <h5 class="fw-bold text-primary">{{ $student->student_fName }}
                                {{ $student->student_lName }}{{ $student->student_extName }}'s Details
                                <div class="row">

                                    <!-- Profile Photo Upload with Preview and Default -->
                                    <div class="row">
                                        <div class="col mt-4 mb-3 d-flex align-items-start align-items-sm-center gap-4">
                                            <div class="mb-3">
                                                @if ($student->student_photo)
                                                    <img id="photo-preview"
                                                        src="{{ asset('storage/' . $student->student_photo) }}"
                                                        alt="Profile Preview" width="100" height="100"
                                                        class="profile-preview"
                                                        style="object-fit: cover; border-radius: 5%">
                                                @else
                                                    <img id="photo-preview"
                                                        src="{{ asset('assetsDashboard/img/student_profile_pictures/student_default_profile.jpg') }}"
                                                        alt="Profile Preview" width="100" height="100"
                                                        class="profile-preview"
                                                        style="object-fit: cover; border-radius: 5%">
                                                @endif
                                            </div>

                                            <div class="button-wrapper">
                                                <label for="upload" class="btn btn-warning me-2 mb-2" tabindex="0">
                                                    <span class="d-none d-sm-block">Upload new photo</span>
                                                    <i class="bx bx-upload d-block d-sm-none"></i>

                                                    <input type="file" id="upload" name="student_profile_photo"
                                                        class="account-file-input" hidden
                                                        accept="image/png, image/jpeg" />
                                                </label>

                                                <button type="button"
                                                    class="btn btn-outline-secondary account-image-reset mb-2">
                                                    <i class="bx bx-reset d-block d-sm-none"></i>
                                                    <span class="d-none d-sm-block">Reset</span>
                                                </button>

                                                <p class="text-muted mb-0" style="font-size: 15px">Allowed JPG or PNG. Max
                                                    size of 2MB</p>
                                            </div>
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
                                        <select name="student_grade_level" id="student_grade_level" class="form-select"
                                            required>
                                            @php $studentClass = $student->class->first(); @endphp
                                            <option
                                                value="kindergarten"{{ $studentClass && $studentClass->grade_level == 'kindergarten' ? 'selected' : '' }}>
                                                Kindergarten</option>
                                            <option
                                                value="grade1"{{ $studentClass && $studentClass->grade_level == 'grade1' ? 'selected' : '' }}>
                                                Grade 1</option>
                                            <option
                                                value="grade2"{{ $studentClass && $studentClass->grade_level == 'grade2' ? 'selected' : '' }}>
                                                Grade 2</option>
                                            <option
                                                value="grade3"{{ $studentClass && $studentClass->grade_level == 'grade3' ? 'selected' : '' }}>
                                                Grade 3</option>
                                            <option
                                                value="grade4"{{ $studentClass && $studentClass->grade_level == 'grade4' ? 'selected' : '' }}>
                                                Grade 4</option>
                                            <option
                                                value="grade5"{{ $studentClass && $studentClass->grade_level == 'grade5' ? 'selected' : '' }}>
                                                Grade 5</option>
                                            <option
                                                value="grade6"{{ $studentClass && $studentClass->grade_level == 'grade6' ? 'selected' : '' }}>
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
                                                $studentSection = $student->class()->first();
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
                                            <option value="male"{{ $student->student_sex == 'Male' ? 'selected' : '' }}>
                                                Male</option>
                                            <option
                                                value="female"{{ $student->student_sex == 'Female' ? 'selected' : '' }}>
                                                Female</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row">

                                    <!-- First Name Field -->
                                    <div class="col mb-2 mt-2">
                                        <label for="student_fName" class="form-label fw-bold">First
                                            Name</label>
                                        <input type="text" name="student_fName" id="student_fName"
                                            class="form-control" value="{{ $student->student_fName }}" required />
                                    </div>

                                    <!-- Middle Name Field -->
                                    <div class="col mb-2 mt-2">
                                        <label for="student_mName" class="form-label fw-bold">Middle
                                            Name</label>
                                        <input type="text" name="student_mName" id="student_mName"
                                            class="form-control" value="{{ $student->student_mName }}" />
                                    </div>

                                </div>

                                <div class="row">

                                    <!-- Last Name Field -->
                                    <div class="col mb-2 mt-2">
                                        <label for="student_lName" class="form-label fw-bold">Last Name</label>
                                        <input type="text" name="student_lName" id="student_lName"
                                            class="form-control" value="{{ $student->student_lName }}" required />
                                    </div>

                                    <!-- Extension Name Field -->
                                    <div class="col mb-2 mt-2">
                                        <label for="student_extName" class="form-label fw-bold">Extension
                                            Name</label>
                                        <input type="text" name="student_extName" id="student_extName"
                                            class="form-control" value="{{ $student->student_extName }}" />
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

                                    <!-- Father's Phone No. Field -->
                                    <div class="col mb-2 mt-2">
                                        <label for="municipality_city"
                                            class="form-label fw-bold">Municipality/City</label>
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

                                    <!-- Father's Phone No. Field -->
                                    <div class="col mb-2 mt-2">
                                        <label for="zip_code" class="form-label fw-bold">Zip Code</label>
                                        <input type="text" name="zip_code" id="zip_code" class="form-control"
                                            value="{{ $student->address->zip_code }}" />
                                    </div>

                                </div>

                                <hr class="my-0 mb-4 mt-4" />

                                <!-- Father's Information-->
                                <h5 class="fw-bold mb-3 mt-3 text-primary">Father's Information</h5>

                                <div class="row">
                                    <!-- Father's First Name Field -->
                                    <div class="col mb-2 mt-2">
                                        <label for="student_fatherFName" class="form-label fw-bold">First
                                            Name</label>
                                        <input type="text" name="student_fatherFName" id="student_fatherFName"
                                            class="form-control" value="{{ $student->parentInfo->father_fName }}" />
                                    </div>

                                    <!-- Father's Middle Name Field -->
                                    <div class="col mb-2 mt-2">
                                        <label for="student_fatherMName" class="form-label fw-bold">Midlle
                                            Name</label>
                                        <input type="text" name="student_fatherMName" id="student_fatherMName"
                                            class="form-control" value="{{ $student->parentInfo->father_mName }}" />
                                    </div>
                                </div>

                                <div class="row">
                                    <!-- Father's Last Name Field -->
                                    <div class="col mb-2 mt-2">
                                        <label for="student_fatherLName" class="form-label fw-bold">Last
                                            Name</label>
                                        <input type="text" name="student_fatherLName" id="student_fatherLName"
                                            class="form-control" value="{{ $student->parentInfo->father_lName }}" />
                                    </div>

                                    <!-- Father's Phone No. Field -->
                                    <div class="col mb-2 mt-2">
                                        <label for="student_fatherPhone" class="form-label fw-bold">Phone No.</label>
                                        <input type="text" name="student_fatherPhone" id="student_fatherPhone"
                                            class="form-control" value="{{ $student->parentInfo->father_phone }}" />
                                    </div>

                                </div>

                                <!-- Mother's Information-->
                                <h5 class="fw-bold mb-3 mt-3 text-primary">Mother's Information</h5>

                                <div class="row">
                                    <!-- Mother's First Name Field -->
                                    <div class="col mb-2 mt-2">
                                        <label for="student_motherFName" class="form-label fw-bold">First
                                            Name</label>
                                        <input type="text" name="student_motherFName" id="student_motherFName"
                                            class="form-control" value="{{ $student->parentInfo->mother_fName }}" />
                                    </div>

                                    <!-- Mother's Middle Name Field -->
                                    <div class="col mb-2 mt-2">
                                        <label for="student_motherMName" class="form-label fw-bold">Middle
                                            Name</label>
                                        <input type="text" name="student_motherMName" id="student_motherMName"
                                            class="form-control" value="{{ $student->parentInfo->mother_mName }}" />
                                    </div>
                                </div>

                                <div class="row">
                                    <!-- Mother's Last Name Field -->
                                    <div class="col mb-2 mt-2">
                                        <label for="student_motherLName" class="form-label fw-bold">Last
                                            Name</label>
                                        <input type="text" name="student_motherLName" id="student_motherLName"
                                            class="form-control" value="{{ $student->parentInfo->mother_lName }}" />
                                    </div>

                                    <!-- Mother's Phone No. Field -->
                                    <div class="col mb-2 mt-2">
                                        <label for="student_motherPhone" class="form-label fw-bold">Phone No.</label>
                                        <input type="text" name="student_motherPhone" id="student_motherPhone"
                                            class="form-control" value="{{ $student->parentInfo->mother_phone }}" />
                                    </div>

                                </div>

                                <!-- Emergency Contact's Information-->
                                <h5 class="fw-bold mb-3 mt-3 text-primary">Emergency Contact's Information</h5>

                                <div class="row">
                                    <!-- Emergency Contact's First Name Field -->
                                    <div class="col mb-2 mt-2">
                                        <label for="student_emergcontFName" class="form-label fw-bold">First
                                            Name</label>
                                        <input type="text" name="student_emergcontFName" id="student_emergcontFName"
                                            class="form-control" value="{{ $student->parentInfo->emergcont_fName }}" />
                                    </div>

                                    <!-- Emergency Contact's Middle Name Field -->
                                    <div class="col mb-2 mt-2">
                                        <label for="student_emergcontMName" class="form-label fw-bold">Midlle
                                            Name</label>
                                        <input type="text" name="student_emergcontMName" id="student_emergcontMName"
                                            class="form-control" value="{{ $student->parentInfo->emergcont_mName }}" />
                                    </div>
                                </div>

                                <div class="row">
                                    <!-- Emergency Contact's Last Name Field -->
                                    <div class="col mb-2 mt-2">
                                        <label for="student_emergcontLName" class="form-label fw-bold">Last
                                            Name</label>
                                        <input type="text" name="student_emergcontLName" id="student_emergcontLName"
                                            class="form-control" value="{{ $student->parentInfo->emergcont_lName }}" />
                                    </div>

                                    <!-- Emergency Contact's Phone No. Field -->
                                    <div class="col mb-2 mt-2">
                                        <label for="student_emergcontPhone" class="form-label fw-bold">Phone No.</label>
                                        <input type="text" name="student_emergcontPhone" id="student_emergcontPhone"
                                            class="form-control" value="{{ $student->parentInfo->emergcont_phone }}" />
                                    </div>
                                </div>

                        </div>
                        <div class="modal-footer">

                        </div>
                    </form>

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
