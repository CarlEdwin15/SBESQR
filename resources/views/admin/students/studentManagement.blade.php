@extends('./layouts.main')

@section('title', 'Admin | Student Management')

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

    {{-- @if (session('error'))
        <script>
            alert("{{ session('error') }}");
        </script>
    @endif --}}

    <!-- Content wrapper -->
    <div class="content-wrapper">

        <!-- Content -->
        <div class="container-xxl container-p-y">

            <h4 class="fw-bold py-3 mb-4 text-warning">
                <span class="text-muted fw-light">
                    <a class="text-muted fw-light" href="{{ route('home') }}">Dashboard / </a>
                    Students /
                </span>
                Student Management
            </h4>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Student Management Card -->
            <div class="card shadow-sm">
                <div class="card-body">
                    <h3 class="card-title mb-3 fw-bold">Student Management</h3>

                    <!-- Search & Filters -->
                    <div class="row g-2 mb-3 justify-content-between align-items-center">
                        <div class="col-md-4 col-sm-6">
                            <input type="text" class="form-control" placeholder="Search students..."
                                id="studentSearch">
                        </div>

                        <div class="col-md-5 col-sm-6 d-flex gap-2 justify-content-end">
                            <!-- Bulk Delete Button (moved here, left of status filter) -->
                            <button type="button" class="btn btn-danger d-flex align-items-center" id="bulkDeleteBtn"
                                style="display: none; opacity: 0;">
                                <i class="bx bx-user-x me-1"></i>
                                <span class="d-none d-sm-block">Delete Selected</span>
                            </button>

                            <!-- Status Filter -->
                            <select id="statusFilter" class="form-select" style="max-width: 180px;">
                                <option value="">All Status</option>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                                <option value="graduated">Graduated</option>
                            </select>
                        </div>
                    </div>

                    <hr class="my-4" />

                    <!-- Table length -->
                    <div class="d-flex justify-content-between align-items-center gap-2 mb-2">
                        <div>
                            <select id="tableLength" class="form-select">
                                <option value="10" selected>10</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                            </select>
                        </div>

                        <!-- Add Student & Import Excel Buttons -->
                        <div class="d-flex gap-2 mb-3">

                            <!-- Add Student Button -->
                            <button type="button" class="btn btn-primary d-flex align-items-center"
                                data-bs-toggle="modal" data-bs-target="#addStudentModal">
                                <i class="bx bx-user-plus me-1"></i> <span
                                    class="align-items-center d-none d-sm-block">Add Student</span>
                            </button>

                            <!-- Import Excel Button -->
                            <button type="button" class="btn btn-success d-flex align-items-center"
                                data-bs-toggle="modal" data-bs-target="#importExcelModal">
                                <i class="bx bx-file me-1"></i> <span
                                    class="align-items-center d-none d-sm-block">Import</span>
                            </button>

                            <!-- Download Excel Template Button -->
                            <a href="{{ route('students.downloadTemplate') }}"
                                class="btn btn-label-secondary me-4 d-flex align-items-center">
                                <i class="bx bx-export me-1"></i> <span
                                    class="align-items-center d-none d-sm-block">Download Template</span>
                            </a>
                        </div>
                    </div>

                    <!-- Table -->
                    <div class="table-responsive">
                        <table class="table table-hover align-middle" id="studentTable">
                            <thead class="table-light text-center">
                                <tr>
                                    <th style="width: 1%;">
                                        <input type="checkbox" id="selectAllStudents" style="cursor: pointer;">
                                    </th>
                                    <th class="text-start" style="width: 20%;">Full Name</th>
                                    <th style="width: 5%;">LRN</th>
                                    <th style="width: 5%;">Status</th>
                                    <th style="width: 5%;">Age</th>
                                    <th style="width: 20%; display: none;" id="graduatedYearHeader">School Year Graduated
                                    </th>
                                    <th style="width: 20%;">Address</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($students as $student)
                                    <tr class="student-row text-center" data-name="{{ strtolower($student->full_name) }}"
                                        data-status="{{ strtolower($student->status) }}" data-id="{{ $student->id }}">
                                        <td>
                                            <input type="checkbox" class="student-checkbox" value="{{ $student->id }}"
                                                style="cursor: pointer;">
                                        </td>
                                        <td class="text-start">
                                            <div class="d-flex align-items-center">
                                                <a href="{{ route('student.info', ['id' => $student->id, 'school_year' => $schoolYearId]) }}"
                                                    class="d-flex align-items-center text-decoration-none"
                                                    style="gap: 0.5rem;">
                                                    @if ($student->student_photo)
                                                        <img src="{{ asset('public/uploads/' . $student->student_photo) }}"
                                                            alt="Profile Photo" width="35" height="35"
                                                            class="rounded-circle">
                                                    @else
                                                        <img src="{{ asset('assetsDashboard/img/student_profile_pictures/student_default_profile.jpg') }}"
                                                            alt="No Profile" width="35" height="35"
                                                            class="rounded-circle">
                                                    @endif

                                                    <div class="d-flex align-items-center">
                                                        <span class="fw-semibold">{{ $student->full_name }}</span>
                                                        <i class="{{ $student->sex_icon }} ms-2"
                                                            style="font-size: 1rem; {{ strtolower($student->sex_icon) === 'bx-male-sign' ? 'color:#0dcaf0;' : 'color:#dc3545;' }}"></i>
                                                    </div>
                                                </a>
                                            </div>
                                        </td>
                                        <td>{{ $student->student_lrn ?? '-' }}</td>
                                        <td>
                                            @php
                                                $displayStatus = match ($student->status) {
                                                    'enrolled' => 'Active',
                                                    'not_enrolled', 'archived' => 'Inactive',
                                                    'graduated' => 'Graduated',
                                                    default => ucfirst(str_replace('_', ' ', $student->status)),
                                                };

                                                $badgeClass = match ($displayStatus) {
                                                    'Active' => 'bg-label-success',
                                                    'Inactive' => 'bg-label-secondary',
                                                    'Graduated' => 'bg-label-info',
                                                    default => 'bg-label-warning',
                                                };
                                            @endphp

                                            <span class="badge {{ $badgeClass }}">
                                                {{ $displayStatus }}
                                            </span>
                                        </td>
                                        <td>
                                            @if ($student->student_dob)
                                                {{ \Carbon\Carbon::parse($student->student_dob)->age }} years old
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="graduated-year-cell" style="display: none;">
                                            @if ($student->status === 'graduated')
                                                {{ $student->graduated_school_year ?? '-' }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="text-start">
                                            @if ($student->address)
                                                {{ $student->address->house_no ? $student->address->house_no . ', ' : '' }}
                                                {{ $student->address->street_name ? $student->address->street_name . ', ' : '' }}
                                                {{ $student->address->barangay ? $student->address->barangay . ', ' : '' }}
                                                {{ $student->address->municipality_city ? $student->address->municipality_city . ', ' : '' }}
                                                {{ $student->address->province ? $student->address->province . ', ' : '' }}
                                                {{ $student->address->zip_code ?? '' }}
                                            @else
                                                <span class="text-muted">N/A</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination + Info -->
                    <div class="d-flex justify-content-between align-items-center px-3 py-3 border-top">
                        <div id="tableInfo" class="text-muted small"></div>
                        <nav aria-label="Page navigation">
                            <ul class="pagination mb-0" id="studentPagination"></ul>
                        </nav>
                    </div>
                </div>
            </div>
            <!-- /Student Management Card -->
        </div>

        <div class="content-backdrop fade"></div>
    </div>
    <!-- /Content wrapper -->

    <!-- Add Student Modal -->
    <div class="modal fade" id="addStudentModal" tabindex="-1" data-bs-backdrop="static"
        aria-labelledby="addStudentModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('store.student') }}" id="studentRegistrationForm" method="POST"
                    enctype="multipart/form-data">
                    @csrf

                    <div class="modal-header text-white">
                        <h4 class="modal-title text-info fw-bold" id="addStudentModalLabel">Add New Student</h4>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <div class="row">
                            <!-- Profile Photo -->
                            <div class="col d-flex align-items-start align-items-sm-center gap-4 mt-3 mb-3">
                                <div class="mb-3">
                                    <img id="photo-preview"
                                        src="{{ asset('assetsDashboard/img/student_profile_pictures/student_default_profile.jpg') }}"
                                        alt="Profile Preview" width="100" height="100"
                                        style="object-fit: cover; border-radius: 5%">
                                </div>

                                <div class="button-wrapper">
                                    <label for="upload" class="btn btn-warning me-2 mb-2">
                                        <span>Upload new photo</span>
                                        <input type="file" id="upload" name="student_profile_photo"
                                            class="account-file-input" hidden accept="image/png, image/jpeg" />
                                    </label>
                                    <button type="button" class="btn btn-outline-secondary account-image-reset mb-2"
                                        id="reset-photo">
                                        Reset
                                    </button>
                                    <p class="text-muted mb-0">Allowed JPG or PNG. Max size of 2MB</p>
                                </div>
                            </div>
                        </div>

                        <div>
                            <h5 class="fw-bold text-primary mb-3">Student's Personal Information</h5>
                        </div>

                        <div class="row">
                            <!-- LRN -->
                            <div class="col mb-3">
                                <label for="student_lrn" class="form-label fw-bold">LRN (Learner's Reference No.)</label>
                                <input type="text" name="student_lrn" id="student_lrn" class="form-control"
                                    placeholder="Enter Student's LRN" required autofocus
                                    value="{{ old('student_lrn') }}" />
                            </div>

                            <!-- Gender -->
                            <div class="col mb-3">
                                <label for="student_sex" class="form-label fw-bold">Gender</label>
                                <select name="student_sex" id="student_sex" class="form-select" required>
                                    <option value="" selected disabled>Select Gender</option>
                                    <option value="male" {{ old('student_sex') == 'male' ? 'selected' : '' }}>Male
                                    </option>
                                    <option value="female" {{ old('student_sex') == 'female' ? 'selected' : '' }}>Female
                                    </option>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <!-- First Name -->
                            <div class="col mb-3">
                                <label for="student_fName" class="form-label fw-bold">First Name</label>
                                <input type="text" name="student_fName" id="student_fName" class="form-control"
                                    placeholder="Enter First Name" required value="{{ old('student_fName') }}" />
                            </div>

                            <!-- Middle Name -->
                            <div class="col mb-3">
                                <label for="student_mName" class="form-label fw-bold">Middle Name</label>
                                <input type="text" name="student_mName" id="student_mName" class="form-control"
                                    placeholder="Enter Middle Name" value="{{ old('student_mName') }}" />
                            </div>
                        </div>

                        <div class="row">
                            <!-- Last Name -->
                            <div class="col mb-3">
                                <label for="student_lName" class="form-label fw-bold">Last Name</label>
                                <input type="text" name="student_lName" id="student_lName" class="form-control"
                                    placeholder="Enter Last Name" required value="{{ old('student_lName') }}" />
                            </div>

                            <!-- Extension -->
                            <div class="col mb-3">
                                <label for="student_extName" class="form-label fw-bold">Extension Name <span
                                        class="text-muted">(optional)</span></label>
                                <input type="text" name="student_extName" id="student_extName" class="form-control"
                                    placeholder="e.g Jr., III" value="{{ old('student_extName') }}" />
                            </div>
                        </div>

                        <div class="row">
                            <!-- Place of Birth -->
                            <div class="col mb-3">
                                <label for="student_pob" class="form-label fw-bold">Place of Birth</label>
                                <input type="text" name="student_pob" id="student_pob" class="form-control"
                                    placeholder="Municipality/City" required value="{{ old('student_pob') }}" />
                            </div>

                            <!-- Date of Birth -->
                            <div class="col mb-3">
                                <label for="student_dob" class="form-label fw-bold">Date of Birth</label>
                                <input type="date" name="student_dob" id="student_dob" class="form-control"
                                    max="{{ date('Y-m-d') }}" value="{{ old('student_dob') }}" required />
                            </div>
                        </div>

                        <hr class="my-4" />
                        <h5 class="fw-bold mb-3 text-primary">Address</h5>

                        <div class="row">
                            <div class="col mb-3">
                                <label for="house_no" class="form-label fw-bold">House No.</label>
                                <input type="text" name="house_no" id="house_no" class="form-control"
                                    placeholder="Enter House No." value="{{ old('house_no') }}" required/>
                            </div>
                            <div class="col mb-3">
                                <label for="street_name" class="form-label fw-bold">Street Name</label>
                                <input type="text" name="street_name" id="street_name" class="form-control"
                                    placeholder="Enter Street Name" value="{{ old('street_name') }}" required/>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col mb-3">
                                <label for="barangay" class="form-label fw-bold">Barangay</label>
                                <input type="text" name="barangay" id="barangay" class="form-control"
                                    placeholder="Enter Barangay" value="{{ old('barangay') }}" required/>
                            </div>
                            <div class="col mb-3">
                                <label for="municipality_city" class="form-label fw-bold">Municipality/City</label>
                                <input type="text" name="municipality_city" id="municipality_city"
                                    class="form-control" placeholder="Enter Municipality or City"
                                    value="{{ old('municipality_city') }}" required />
                            </div>
                        </div>

                        <div class="row">
                            <div class="col mb-3">
                                <label for="province" class="form-label fw-bold">Province</label>
                                <input type="text" name="province" id="province" class="form-control"
                                    placeholder="Enter Province" value="{{ old('province') }}" required/>
                            </div>
                            <div class="col mb-3">
                                <label for="zip_code" class="form-label fw-bold">Zip Code</label>
                                <input type="text" name="zip_code" id="zip_code" class="form-control"
                                    placeholder="Enter Zip Code" value="{{ old('zip_code') }}" required/>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer d-flex justify-content-between">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Add Student</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- /Add Student Modal -->

    <!-- Import Students via Excel Modal -->
    <div class="modal fade" id="importExcelModal" tabindex="-1" data-bs-backdrop="static"
        aria-labelledby="importExcelModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <form action="{{ route('students.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <!-- Modal Header -->
                    <div class="modal-header text-white">
                        <h5 class="modal-title fw-bold text-info" id="importExcelModalLabel">
                            Import Students via Excel
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>

                    <!-- Modal Body -->
                    <div class="modal-body">
                        <!-- File Upload Section -->
                        <div class="mb-4">
                            <label for="excel_file" class="form-label fw-bold">Select File</label>
                            <input type="file" name="excel_file" id="excel_file" class="form-control" required
                                accept=".xlsx,.xls,.csv">
                            <small class="text-muted">
                                Allowed formats: <strong>.xlsx, .xls</strong>
                            </small>
                        </div>

                        <hr class="my-4">

                        <!-- Instructions Section -->
                        <div>
                            <h6 class="fw-bold text-primary mb-3">
                                <i class="bx bx-info-circle me-1"></i> Excel Format Guide
                            </h6>

                            <div class="alert alert-light border shadow-sm small">
                                <p class="mb-2">Ensure your Excel file includes these column headers exactly as shown:
                                </p>
                                <ul class="list-unstyled ms-3 mb-0">
                                    <li><i class="bx bx-chevron-right text-success"></i> <b>lrn</b> – Required, 12 digits
                                        starting with <code>112828</code></li>
                                    <li><i class="bx bx-chevron-right text-success"></i> <b>first_name</b> – Required</li>
                                    <li><i class="bx bx-chevron-right text-success"></i> <b>middle_name</b> – Optional</li>
                                    <li><i class="bx bx-chevron-right text-success"></i> <b>last_name</b> – Required</li>
                                    <li><i class="bx bx-chevron-right text-success"></i> <b>extension_name</b> – Optional
                                    </li>
                                    <li><i class="bx bx-chevron-right text-success"></i> <b>dob</b> – Optional (Format:
                                        <code>YYYY-MM-DD</code>)
                                    </li>
                                    <li><i class="bx bx-chevron-right text-success"></i> <b>sex</b> – Required
                                        (<code>male</code> or <code>female</code>)</li>
                                    <li><i class="bx bx-chevron-right text-success"></i> <b>place_of_birth</b> – Optional
                                    </li>
                                    <li><i class="bx bx-chevron-right text-success"></i> <b>house_no</b>,
                                        <b>street_name</b>, <b>barangay</b>, <b>municipality_city</b>, <b>province</b>,
                                        <b>zip_code</b> – Optional
                                    </li>
                                </ul>
                            </div>

                            <div class="mt-3 text-muted small">
                                💡 Tip: You can
                                <a href="{{ route('students.downloadTemplate') }}" class="fw-bold text-success">
                                    download the sample Excel template
                                </a>
                                and fill it out for easy importing.
                            </div>
                        </div>
                    </div>

                    <!-- Modal Footer -->
                    <div class="modal-footer d-flex justify-content-between">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                            <i class="bx bx-x me-1"></i> Cancel
                        </button>
                        <button type="submit" class="btn btn-success fw-bold">
                            <i class="bx bx-upload me-1"></i> Import Students
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- /Import Students via Excel Modal -->

@endsection

@push('scripts')
    <script>
        // register alert
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('studentRegistrationForm');
            const registerBtn = document.getElementById('registerStudentBtn');

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
                    title: "Register Student?",
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
        // Show success toast if session has success message
        @if (session('success'))
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: '{{ session('success') }}',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                customClass: {
                    container: 'my-swal-container'
                },
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
        // alert for upload and preview profile in registration
        const uploadInput = document.getElementById('upload');
        const previewImg = document.getElementById('photo-preview');
        const resetBtn = document.getElementById('reset-photo');
        const defaultImage = "{{ asset('assetsDashboard/img/student_profile_pictures/student_default_profile.jpg') }}";

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

    <!-- Student Management Table Script -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const searchInput = document.getElementById("studentSearch");
            const statusFilter = document.getElementById("statusFilter");
            const table = document.getElementById("studentTable");
            const pagination = document.getElementById("studentPagination");
            const tableLengthSelect = document.getElementById("tableLength");
            const tableInfo = document.getElementById("tableInfo");
            const selectAllCheckbox = document.getElementById("selectAllStudents");
            const bulkDeleteBtn = document.getElementById("bulkDeleteBtn");

            let currentPage = 1;
            let rowsPerPage = parseInt(tableLengthSelect.value);
            let rows = []; // will hold current DOM rows
            let filteredRows = []; // rows after applying search/status

            // Refresh rows collection from DOM (call after DOM changes like deletion)
            function refreshRowCollections() {
                rows = Array.from(table.querySelectorAll("tbody tr"));
            }

            // Utility: return the checkbox element inside a row (or null)
            function getRowCheckbox(row) {
                return row.querySelector(".student-checkbox");
            }

            // ---- Filtering ----
            function filterRows() {
                const search = searchInput.value.trim().toLowerCase();
                const status = statusFilter.value;

                // Rebuild filteredRows from the current DOM rows
                filteredRows = rows.filter(row => {
                    const name = (row.dataset.name || "").toLowerCase();
                    const lrn = (row.querySelector("td:nth-child(3)")?.textContent || "").toLowerCase();
                    const rawStatus = (row.dataset.status || "").toLowerCase();

                    let displayStatus;
                    if (rawStatus === "enrolled") displayStatus = "active";
                    else if (rawStatus === "graduated") displayStatus = "graduated";
                    else if (["archived", "not_enrolled"].includes(rawStatus)) displayStatus = "inactive";
                    else displayStatus = rawStatus;

                    const matchesSearch = (name.includes(search) || lrn.includes(search));
                    const matchesStatus = !status || displayStatus === status;

                    return matchesSearch && matchesStatus;
                });

                // Reset page to 1 when filters change
                currentPage = 1;
                renderTable();
            }

            // ---- Rendering ----
            function renderTable() {
                const totalRows = filteredRows.length;
                const totalPages = Math.max(1, Math.ceil(totalRows / rowsPerPage));
                // Clamp currentPage to valid range
                currentPage = Math.max(1, Math.min(currentPage, totalPages));

                // Hide all rows (from the current DOM rows)
                rows.forEach(r => r.style.display = "none");

                // Compute start/end and display current page rows
                const start = (currentPage - 1) * rowsPerPage;
                const end = start + rowsPerPage;
                const pageRows = filteredRows.slice(start, end);
                pageRows.forEach(r => r.style.display = "");

                // Update table info
                const startRow = totalRows === 0 ? 0 : start + 1;
                const endRow = Math.min(end, totalRows);
                tableInfo.textContent = `Showing ${startRow}-${endRow} of ${totalRows} students`;

                // Rebuild pagination
                renderPagination(totalPages);

                // Graduated column toggle
                toggleGraduatedColumn();

                // Reset select all for visible page and update delete button
                selectAllCheckbox.checked = false;
                updateBulkDeleteState();
            }

            function renderPagination(totalPages) {
                pagination.innerHTML = "";
                if (totalPages <= 1) return;

                const maxVisible = 5;
                let startPage = Math.max(1, currentPage - Math.floor(maxVisible / 2));
                let endPage = Math.min(totalPages, startPage + maxVisible - 1);
                if (endPage - startPage + 1 < maxVisible)
                    startPage = Math.max(1, endPage - maxVisible + 1);

                pagination.appendChild(createPageItem("«", currentPage > 1, () => {
                    currentPage--;
                    renderTable();
                }));

                for (let i = startPage; i <= endPage; i++) {
                    pagination.appendChild(createPageItem(i, true, () => {
                        currentPage = i;
                        renderTable();
                    }, i === currentPage));
                }

                pagination.appendChild(createPageItem("»", currentPage < totalPages, () => {
                    currentPage++;
                    renderTable();
                }));
            }

            function createPageItem(label, enabled, onClick, active = false) {
                const li = document.createElement("li");
                li.className = "page-item " + (active ? "active" : "") + (!enabled ? " disabled" : "");
                const a = document.createElement("a");
                a.className = "page-link";
                a.href = "#";
                a.textContent = label;
                a.addEventListener("click", e => {
                    e.preventDefault();
                    if (enabled) onClick();
                });
                li.appendChild(a);
                return li;
            }

            function toggleGraduatedColumn() {
                const graduatedHeader = document.getElementById("graduatedYearHeader");
                const graduatedCells = document.querySelectorAll(".graduated-year-cell");

                if (statusFilter.value === "graduated") {
                    graduatedHeader.style.display = "";
                    graduatedCells.forEach(cell => cell.style.display = "");
                } else {
                    graduatedHeader.style.display = "none";
                    graduatedCells.forEach(cell => cell.style.display = "none");
                }
            }

            // ---- Checkboxes & Bulk Delete UI ----

            // Start hidden
            bulkDeleteBtn.style.display = "none";
            bulkDeleteBtn.style.opacity = "0";

            // Compute visible checkbox elements on the current page
            function getVisibleCheckboxesOnPage() {
                const start = (currentPage - 1) * rowsPerPage;
                const end = start + rowsPerPage;
                const pageRows = filteredRows.slice(start, end);
                return pageRows.map(r => getRowCheckbox(r)).filter(Boolean);
            }

            function updateBulkDeleteState() {
                const visibleCheckboxes = getVisibleCheckboxesOnPage();
                const checkedCount = visibleCheckboxes.filter(cb => cb.checked).length;

                if (checkedCount > 0) {
                    bulkDeleteBtn.style.display = "inline-flex";
                    setTimeout(() => {
                        bulkDeleteBtn.style.opacity = "1";
                    }, 10);
                } else {
                    bulkDeleteBtn.style.opacity = "0";
                    setTimeout(() => {
                        bulkDeleteBtn.style.display = "none";
                    }, 300);
                }
            }

            // Select-all only applies to visible page checkboxes
            selectAllCheckbox.addEventListener("change", function() {
                const visibleCheckboxes = getVisibleCheckboxesOnPage();
                visibleCheckboxes.forEach(cb => (cb.checked = selectAllCheckbox.checked));
                updateBulkDeleteState();
            });

            // Use event delegation for checkbox changes inside the table (works for dynamic rows)
            table.addEventListener("change", function(e) {
                if (e.target && e.target.classList && e.target.classList.contains("student-checkbox")) {
                    // if any checkbox unchecked, uncheck header selectAll
                    if (!e.target.checked) selectAllCheckbox.checked = false;
                    // if all visible are checked, set selectAll true
                    const visibleCheckboxes = getVisibleCheckboxesOnPage();
                    if (visibleCheckboxes.length > 0 && visibleCheckboxes.every(cb => cb.checked)) {
                        selectAllCheckbox.checked = true;
                    }
                    updateBulkDeleteState();
                }
            });

            // ---- Bulk delete with refresh ----
            bulkDeleteBtn.addEventListener("click", function() {
                // collect ALL selected ids across the table (not only visible)
                const selectedIds = Array.from(document.querySelectorAll(".student-checkbox:checked")).map(
                    cb => cb.value);
                if (selectedIds.length === 0) return;

                Swal.fire({
                    title: `Delete ${selectedIds.length} selected student(s)?`,
                    text: "This action cannot be undone.",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#d33",
                    cancelButtonColor: "#6c757d",
                    confirmButtonText: "Yes, delete them",
                    cancelButtonText: "Cancel",
                    customClass: {
                        container: 'my-swal-container'
                    }
                }).then(result => {
                    if (result.isConfirmed) {
                        fetch(`{{ route('students.bulkDelete') }}`, {
                                method: "DELETE",
                                headers: {
                                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                                    "Content-Type": "application/json"
                                },
                                body: JSON.stringify({
                                    ids: selectedIds
                                })
                            })
                            .then(res => res.json())
                            .then(data => {
                                Swal.fire({
                                    title: "Deleted!",
                                    text: data.message,
                                    icon: "success",
                                    timer: 1500,
                                    showConfirmButton: false,
                                    customClass: {
                                        container: 'my-swal-container'
                                    }
                                });

                                // Animate remove for rows currently in DOM (others will be removed when we refresh)
                                selectedIds.forEach(id => {
                                    const row = document.querySelector(
                                        `tr[data-id="${id}"]`);
                                    if (row) {
                                        row.style.transition = "opacity 0.35s ease";
                                        row.style.opacity = "0";
                                    }
                                });

                                // Wait for animations then actually remove and refresh
                                setTimeout(() => {
                                    selectedIds.forEach(id => {
                                        const row = document.querySelector(
                                            `tr[data-id="${id}"]`);
                                        if (row) row.remove();
                                    });

                                    // Refresh our rows collection from DOM, reapply filters and render
                                    refreshRowCollections();
                                    filterRows
                                        (); // this will call renderTable() and keep pagination/info correct
                                }, 400);
                            })
                            .catch(err => {
                                console.error(err);
                                Swal.fire({
                                    title: "Error!",
                                    text: "Something went wrong while deleting students.",
                                    icon: "error",
                                    customClass: {
                                        container: 'my-swal-container'
                                    }
                                });
                            });
                    }
                });
            });

            // ---- Event bindings for filters/pagination/length ----
            searchInput.addEventListener("input", () => {
                // any typing should reset to page 1 and update
                filterRows();
            });

            statusFilter.addEventListener("change", () => {
                filterRows();
                toggleGraduatedColumn();
            });

            tableLengthSelect.addEventListener("change", function() {
                rowsPerPage = parseInt(this.value) || 10;
                currentPage = 1;
                renderTable();
            });

            // ---- Initialize collections and render ----
            refreshRowCollections();
            filterRows();
        });
    </script>

    <!-- Import Students via Excel Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('#importExcelModal form');
            const fileInput = document.querySelector('#excel_file');

            form.addEventListener('submit', function(e) {
                const file = fileInput.files[0];
                if (!file) {
                    Swal.fire({
                        title: "No File Selected",
                        text: "⚠️ Please select a file before importing.",
                        icon: "warning",
                        confirmButtonText: "OK",
                        customClass: {
                            container: 'my-swal-container'
                        }
                    });
                    e.preventDefault();
                    return;
                }

                const allowedExtensions = ['xlsx', 'xls', 'csv'];
                const ext = file.name.split('.').pop().toLowerCase();
                if (!allowedExtensions.includes(ext)) {
                    Swal.fire({
                        title: "Invalid File Type",
                        text: "❌ Please upload a .xlsx, .xls, or .csv file.",
                        icon: "error",
                        confirmButtonText: "OK",
                        customClass: {
                            container: 'my-swal-container'
                        }
                    });
                    e.preventDefault();
                    return;
                }

                if (file.size > 5 * 1024 * 1024) {
                    Swal.fire({
                        title: "File Too Large",
                        text: "⚠️ Please upload a file smaller than 5MB.",
                        icon: "warning",
                        confirmButtonText: "OK",
                        customClass: {
                            container: 'my-swal-container'
                        }
                    });
                    e.preventDefault();
                    return;
                }

                // ✅ Show SweetAlert loading while submitting
                Swal.fire({
                    title: "Uploading...",
                    text: "Please wait while we validate your Excel file.",
                    icon: "info",
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    didOpen: () => {
                        Swal.showLoading();
                    },
                    customClass: {
                        container: 'my-swal-container'
                    }
                });

                // Disable button + change text
                const btn = form.querySelector('button[type="submit"]');
                btn.disabled = true;
                btn.innerHTML = '<i class="bx bx-loader bx-spin me-1"></i> Validating...';
            });
        });
    </script>

    <!-- Import Result Alert Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @if (session('success'))
                const importedCount = parseInt("{{ session('imported_count') ?? 0 }}");
                const importStatus = "{{ session('import_status') ?? '' }}";
                const hasErrors = `{!! session('import_errors') !!}`.trim().length > 0;
                let title = "Import Complete";
                let icon = "success";

                // 🔹 Case 1: No rows imported → total failure
                if (importedCount === 0 || importStatus === "error") {
                    title = "Import Error";
                    icon = "error";
                }
                // 🔹 Case 2: Some imported but some with validation errors
                else if (importedCount > 0 && hasErrors) {
                    title = "Import Incomplete";
                    icon = "warning";
                }
                // 🔹 Case 3: Imported successfully, only duplicates (non-error)
                else {
                    title = "Import Complete";
                    icon = "success";
                }
            @endif
        });
    </script>
@endpush

@push('styles')
    <style>
        .btn-label-secondary {
            background-color: #f5f5f9;
            border: 1px solid #dcdcdc;
            color: #333;
        }

        .btn-label-secondary:hover {
            background-color: #e8e8ee;
        }

        .dropdown-menu a i {
            font-size: 1rem;
        }

        #bulkDeleteBtn {
            transition: opacity 0.3s ease, visibility 0.3s ease;
        }
    </style>
@endpush
