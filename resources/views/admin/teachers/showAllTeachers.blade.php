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
                                <a href="" class="menu-link bg-dark text-light">
                                    <div class="text-light">ID Generation</div>
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
                        <a href="javascript:void(0);" class="menu-link menu-toggle bg-dark text-light">
                            <i class="menu-icon tf-icons bx bx-objects-horizontal-left text-light"></i>
                            <div class="text-light">Grade & Section</div>
                        </a>
                        <ul class="menu-sub">
                            <li class="menu-item">
                                <a href="{{ route('all.grade.levels') }}" class="menu-link bg-dark text-light">
                                    <div class="text-light">Grade Levels</div>
                                </a>
                            </li>
                            <li class="menu-item">
                                <a href="" class="menu-link bg-dark text-light">
                                    <div class="text-light">Sections</div>
                                </a>
                            </li>
                        </ul>
                    </li>

                    {{-- Reports sidebar --}}
                    <li class="menu-item">
                        <a href="javascript:void(0);" class="menu-link menu-toggle bg-dark text-light">
                            <i class="menu-icon tf-icons bx bx-detail text-light"></i>
                            <div class="text-light">Reports</div>
                        </a>
                        <ul class="menu-sub">
                            <li class="menu-item">
                                <a href="pages-misc-error.html" class="menu-link bg-dark text-light">
                                    <div class="text-light">All Reports</div>
                                </a>
                            </li>
                            <li class="menu-item">
                                <a href="pages-misc-under-maintenance.html" class="menu-link bg-dark text-light">
                                    <div class="text-light">All Reports</div>
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
                <div class="container-xxl flex-grow-1 container-p-y">
                    <h4 class="fw-bold py-3 mb-4 text-warning"><span class="text-muted fw-light">
                            <a class="text-muted fw-light" href="{{ route('home') }}">Dashboard / </a>
                            <a class="text-muted fw-light" href="{{ route('show.teachers') }}">Teachers / </a>
                        </span> All Teachers
                    </h4>

                    <!-- Modal Backdrop -->
                    <div class="col-lg-4 col-md-3">

                        <div class="mt-3">
                            <!-- Button trigger modal -->
                            <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                                data-bs-target="#backDropModal"
                                style="margin: auto; margin-bottom: 30px; margin-left: 10px">
                                Register New Teacher
                            </button>

                            <!-- Register Modal -->
                            <div class="modal fade" id="backDropModal" data-bs-backdrop="static" tabindex="-1">

                                <div class="modal-dialog">
                                    <form class="modal-content" action="{{ route('register.teacher') }}" method="POST"
                                        enctype="multipart/form-data">
                                        @csrf
                                        <div class="modal-header">
                                            <h4 class="modal-title fw-bold text-primary" id="backDropModalTitle">
                                                REGISTER NEW TEACHER
                                            </h4>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">

                                            <h5 class="fw-bold text-primary">Teacher's Personal Information</h5>

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
                                                        autofocus autocomplete="firstName" />
                                                </div>

                                                <!-- Middle Name Field -->
                                                <div class="col mb-3">
                                                    <label for="middleName" class="form-label fw-bold">Middle
                                                        Name</label>
                                                    <input type="text" name="middleName" id="middleName"
                                                        class="form-control" placeholder="Enter Middle Name" required
                                                        autofocus autocomplete="middleName" />
                                                </div>

                                            </div>

                                            <div class="row g-4">

                                                <!-- Last Name Field -->
                                                <div class="col mb-3">
                                                    <label for="lastName" class="form-label fw-bold">Last Name</label>
                                                    <input type="text" name="lastName" id="lastName"
                                                        class="form-control" placeholder="Enter Last Name" required
                                                        autofocus autocomplete="lastName" />
                                                </div>

                                                <!-- Extension Name Field -->
                                                <div class="col mb-3">
                                                    <label for="extName" class="form-label fw-bold">Extension
                                                        Name</label>
                                                    <input type="text" name="extName" id="extName"
                                                        class="form-control"
                                                        placeholder="Enter Ext. Name(if applicable)" />
                                                </div>
                                            </div>

                                            <div class="row">
                                                <!-- Email Field -->
                                                <div class="col mb-3">
                                                    <label for="email" class="form-label fw-bold">Email</label>
                                                    <input type="email" name="email" id="email"
                                                        class="form-control" placeholder="xxxx@xxx.xx" required autofocus
                                                        autocomplete="email" />
                                                </div>

                                                <!-- Phone Field -->
                                                <div class="col mb-3">
                                                    <label for="phone" class="form-label fw-bold">Phone</label>
                                                    <input type="phone" name="phone" id="phone"
                                                        class="form-control" placeholder="Enter Phone Number" required
                                                        autofocus autocomplete="phone" />
                                                </div>
                                            </div>

                                            <div class="row">

                                                <!-- Grade Level Assigned Field -->
                                                <div class="col mb-3">
                                                    <label for="grade_level_assigned" class="form-label fw-bold">Grade
                                                        Level Assigned</label>
                                                    <select name="grade_level_assigned" id="grade_level_assigned"
                                                        class="form-select" required>
                                                        <option value="" selected disabled>Select Grade Level
                                                        </option>
                                                        <option value="kindergarten">Kindergarten</option>
                                                        <option value="grade1">Grade 1</option>
                                                        <option value="grade2">Grade 2</option>
                                                        <option value="grade3">Grade 3</option>
                                                        <option value="grade4">Grade 4</option>
                                                        <option value="grade5">Grade 5</option>
                                                        <option value="grade6">Grade 6</option>
                                                    </select>
                                                </div>

                                                <!-- Section Assigned Field -->
                                                <div class="col mb-3">
                                                    <label for="section_assigned" class="form-label fw-bold">Section
                                                        Assigned</label>
                                                    <select name="section_assigned" id="section_assigned"
                                                        class="form-select" required>
                                                        <option value="" selected disabled>Select Section
                                                        </option>
                                                        <option value="A">A</option>
                                                        <option value="B">B</option>
                                                        <option value="C">C</option>
                                                        <option value="D">D</option>
                                                        <option value="E">E</option>
                                                        <option value="F">F</option>
                                                    </select>
                                                </div>
                                            </div>


                                            <div class="row">
                                                <!-- Gender Field -->
                                                <div class="col mb-3">
                                                    <label for="gender" class="form-label fw-bold">Gender</label>
                                                    <select name="gender" id="gender" class="form-select" required>
                                                        <option value="" selected disabled>Select Gender</option>
                                                        <option value="male">Male</option>
                                                        <option value="female">Female</option>
                                                    </select>
                                                </div>

                                                <!-- Date of Birth Field -->
                                                <div class="col mb-3">
                                                    <label for="dob" class="form-label fw-bold">Date of
                                                        Birth</label>
                                                    <input class="form-control" name="dob" type="date"
                                                        id="dob" />
                                                </div>
                                            </div>

                                            <hr class="my-4" />

                                            <h5 class="fw-bold text-primary">Address</h5>

                                            <div class="row">
                                                <!-- House No. field -->
                                                <div class="col mb-3">
                                                    <label for="house_no" class="form-label fw-bold">House No.</label>
                                                    <input type="text" name="house_no" id="house_no"
                                                        class="form-control" placeholder="Enter House No." />
                                                </div>

                                                <!-- Street Name field -->
                                                <div class="col mb-3">
                                                    <label for="street_name" class="form-label fw-bold">Street
                                                        Name</label>
                                                    <input type="text" name="street_name" id="street_name"
                                                        class="form-control" placeholder="Enter Street Name" />
                                                </div>
                                            </div>

                                            <div class="row">
                                                <!-- Barangay field -->
                                                <div class="col mb-3">
                                                    <label for="barangay" class="form-label fw-bold">Barangay</label>
                                                    <input type="text" name="barangay" id="barangay"
                                                        class="form-control" placeholder="Enter Barangay" />
                                                </div>

                                                <!-- Municipality/City field -->
                                                <div class="col mb-3">
                                                    <label for="municipality_city"
                                                        class="form-label fw-bold">Municipality/City</label>
                                                    <input type="text" name="municipality_city" id="municipality_city"
                                                        class="form-control" placeholder="Enter Municipality/City" />
                                                </div>
                                            </div>

                                            <div class="row">
                                                <!-- Province field -->
                                                <div class="col mb-3">
                                                    <label for="province" class="form-label fw-bold">Province</label>
                                                    <input type="text" name="province" id="province"
                                                        class="form-control" placeholder="Enter Province" />
                                                </div>

                                                <!-- Zip Code field -->
                                                <div class="col mb-3">
                                                    <label for="zip_code" class="form-label fw-bold">Zip Code</label>
                                                    <input type="text" name="zip_code" id="zip_code"
                                                        class="form-control" placeholder="Enter Zip Code" />
                                                </div>
                                            </div>

                                            <hr class="my-4" />

                                            <h5 class="fw-bold text-primary">Password</h5>

                                            <!-- Password Input -->
                                            <div class="mb-3 form-password-toggle">
                                                <div class="d-flex justify-content-between">
                                                    <label class="form-label fw-bold" for="password">Password</label>
                                                </div>

                                                <div class="input-group input-group-merge">
                                                    <input type="password" id="password" class="form-control"
                                                        name="password" required autocomplete="new-password"
                                                        placeholder="Enter your Password" />
                                                    <span class="input-group-text cursor-pointer"><i
                                                            class="bx bx-hide"></i></span>
                                                </div>
                                            </div>

                                            <!-- Confirm Password Input -->
                                            <div class="mb-3 form-password-toggle">
                                                <div class="d-flex justify-content-between">
                                                    <label class="form-label fw-bold" for="password_confirmation">Confirm
                                                        Password</label>
                                                </div>

                                                <div class="input-group input-group-merge">
                                                    <input type="password" id="password_confirmation"
                                                        class="form-control" name="password_confirmation" required
                                                        autocomplete="new-password" placeholder="Confirm your Password" />
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
                                            <button type="submit" class="btn btn-danger"
                                                id="registerTeacherBtn">Register</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <!-- /Register Modal -->

                        </div>
                    </div>

                    {{-- Card --}}
                    <div class="card">
                        <div class="card-header">
                            <h5 class="fw-bold mb-4">All Teachers</h5>

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
                                        <th>Email</th>
                                        <th>Photo</th>
                                        <th>Assigned Grade & Section</th>
                                        <th>Contact No.</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="table-border-bottom-0">
                                    @foreach ($teachers as $teacher)
                                        <tr class="teacher-row">
                                            <td>{{ $teacher->lastName }}, {{ $teacher->firstName }}, {{ $teacher->extName }} {{ $teacher->middleName }}</td>
                                            <td>{{ $teacher->email }}</td>
                                            <td>
                                                @if ($teacher->profile_photo)
                                                    <img src="{{ asset('storage/' . $teacher->profile_photo) }}"
                                                        alt="Profile Photo" width="30" height="30"
                                                        style="object-fit: cover; border-radius: 50%;">
                                                @else
                                                    <img src="{{ asset('assetsDashboard/img/profile_pictures/teachers_default_profile.jpg') }}"
                                                        alt="no profile" width="30" height="30"
                                                        style="object-fit: cover; border-radius: 50%">
                                                @endif
                                            </td>
                                            <td>{{ $teacher->class->formatted_grade_level }} -
                                                {{ ucfirst($teacher->class->section) }}</td>
                                            <td>{{ $teacher->phone }}</td>
                                            <td>
                                                <div class="dropdown">
                                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                                        data-bs-toggle="dropdown">
                                                        <i class="bx bx-dots-vertical-rounded"></i>
                                                    </button>
                                                    <div class="dropdown-menu">
                                                        <a class="dropdown-item text-info"
                                                            href="{{ route('teacher.info', ['id' => $teacher->id]) }}">
                                                            <i class="bx bxs-user-badge me-1"></i> View Profile
                                                        </a>
                                                        <a class="dropdown-item text-warning"
                                                            href="{{ route('edit.teacher', ['id' => $teacher->id]) }}">
                                                            <i class="bx bx-edit-alt me-1"></i> Edit
                                                        </a>
                                                        <button type="button" class="dropdown-item text-danger"
                                                            onclick="confirmDelete({{ $teacher->id }}, '{{ $teacher->firstName }}', '{{ $teacher->lastName }}')">
                                                            <i class="bx bx-trash me-1"></i> Delete
                                                        </button>

                                                        <!-- Hidden form to submit delete -->
                                                        <form id="delete-form-{{ $teacher->id }}"
                                                            action="{{ route('delete.teacher', $teacher->id) }}"
                                                            method="POST" style="display: none;">
                                                            @csrf
                                                            @method('DELETE')
                                                        </form>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
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
                    title: "Register Teacher?",
                    text: "Are you sure all the details are correct?",
                    icon: "question",
                    showCancelButton: true,
                    confirmButtonColor: "#dc3545",
                    cancelButtonColor: "#6c757d",
                    confirmButtonText: "Yes, register",
                    cancelButtonText: "Cancel",
                    customClass: {
                        container: 'my-swal-container'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: "Registering...",
                            text: "Please wait while we process the registration.",
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
