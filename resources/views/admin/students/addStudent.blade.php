@extends('./layouts.main')

@section('title', 'Admin | Student Enrollment')


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
                    <li class="menu-item active open">
                        <a href="javascript:void(0);" class="menu-link menu-toggle">
                            <i class="menu-icon tf-icons bx bxs-graduation"></i>
                            <div>Students</div>
                        </a>
                        <ul class="menu-sub">
                            <li class="menu-item">
                                <a href="{{ route('show.students') }}" class="menu-link bg-dark text-light">
                                    <div class="text-light">All Students</div>
                                </a>
                            </li>
                            <li class="menu-item active">
                                <a href="{{ route('add.student') }}" class="menu-link bg-dark text-light">
                                    <div class="text-danger">Student Enrollment</div>
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

                    {{-- Account Settings sidebar --}}
                    <li class="menu-item">
                        <a href="" class="menu-link bg-dark text-light">
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
                            <a class="text-muted fw-light" href="{{ route('show.students') }}">Students / </a>
                        </span> Student Enrollement
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

                    <form class="modal-content" action="{{ route('store.student') }}" id="studentRegistrationForm"
                        method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="modal-body">
                            <div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <h4 class="fw-bold text-primary mb-0">Student's Personal Information</h4>
                                </div>
                            </div>

                            <div class="row">
                                <!-- Profile Photo and School Year Row -->
                                <div class="col d-flex align-items-start align-items-sm-center gap-4 mt-4 mb-3">

                                    <!-- Profile Photo -->
                                    <div class="mb-3">
                                        <img id="photo-preview"
                                            src="{{ asset('assetsDashboard/img/student_profile_pictures/student_default_profile.jpg') }}"
                                            alt="Profile Preview" width="100" height="100" class="profile-preview"
                                            style="object-fit: cover; border-radius: 5%">
                                    </div>

                                    <!-- Upload/Reset Buttons -->
                                    <div class="button-wrapper">
                                        <label for="upload" class="btn btn-warning me-2 mb-2" tabindex="0">
                                            <span class="d-none d-sm-block">Upload new photo</span>
                                            <i class="bx bx-upload d-block d-sm-none"></i>
                                            <input type="file" id="upload" name="student_profile_photo"
                                                class="account-file-input" hidden accept="image/png, image/jpeg" />
                                        </label>

                                        <button type="button" class="btn btn-outline-secondary account-image-reset mb-2"
                                            id="reset-photo">
                                            <i class="bx bx-reset d-block d-sm-none"></i>
                                            <span class="d-none d-sm-block">Reset</span>
                                        </button>

                                        <p class="text-muted mb-0">Allowed JPG or PNG. Max size of 2MB</p>
                                    </div>
                                </div>
                            </div>


                            <div class="row">
                                <!-- LRN Field -->
                                <div class="col mb-2 mt-2">
                                    <label for="student_lrn" class="form-label fw-bold">LRN (Learner's Reference
                                        No.)</label>
                                    <input type="text" name="student_lrn" id="student_lrn" class="form-control"
                                        list="datalistOptions" placeholder="Enter Student's LRN" required autofocus
                                        autocomplete="student_lrn" />

                                    <datalist id="datalistOptions">
                                        <option value="112828"></option>
                                    </datalist>
                                </div>
                                <!-- Grade Level Field -->
                                <div class="col mb-2 mt-2">
                                    <label for="student_grade_level" class="form-label fw-bold">Grade Level</label>
                                    <select name="student_grade_level" id="student_grade_level" class="form-select"
                                        required>
                                        <option value="" selected disabled>Select Grade Level</option>
                                        <option value="kindergarten">Kindergarten</option>
                                        <option value="grade1">Grade 1</option>
                                        <option value="grade2">Grade 2</option>
                                        <option value="grade3">Grade 3</option>
                                        <option value="grade4">Grade 4</option>
                                        <option value="grade5">Grade 5</option>
                                        <option value="grade6">Grade 6</option>
                                    </select>
                                </div>
                            </div>


                            <div class="row">
                                <!-- Section Field -->
                                <div class="col mb-2 mt-2">
                                    <label for="student_section" class="form-label fw-bold">Section</label>
                                    <select name="student_section" id="student_section" class="form-select" required>
                                        <option value="" selected disabled>Select Section</option>
                                        <option value="A">Section A</option>
                                        <option value="B">Section B</option>
                                        <option value="C">Section C</option>
                                        <option value="D">Section D</option>
                                        <option value="E">Section E</option>
                                        <option value="F">Section F</option>
                                    </select>
                                </div>

                                <!-- Gender Field -->
                                <div class="col mb-2 mt-2">
                                    <label for="student_sex" class="form-label fw-bold">Gender</label>
                                    <select name="student_sex" id="student_sex" class="form-select" required>
                                        <option value="" selected disabled>Select Gender</option>
                                        <option value="male">Male</option>
                                        <option value="female">Female</option>
                                    </select>
                                </div>
                            </div>


                            <div class="row">
                                <!-- First Name Field -->
                                <div class="col mb-2 mt-2">
                                    <label for="student_fName" class="form-label fw-bold">First Name</label>
                                    <input type="text" name="student_fName" id="student_fName" class="form-control"
                                        placeholder="Enter First Name" required autocomplete="student_fName" />
                                </div>

                                <!-- Middle Name Field -->
                                <div class="col mb-2 mt-2">
                                    <label for="student_mName" class="form-label fw-bold">Middle Name</label>
                                    <input type="text" name="student_mName" id="student_mName" class="form-control"
                                        placeholder="Enter Middle Name" autocomplete="student_mName" />
                                </div>
                            </div>

                            <div class="row">
                                <!-- Last Name Field -->
                                <div class="col mb-2 mt-2">
                                    <label for="student_lName" class="form-label fw-bold">Last Name</label>
                                    <input type="text" name="student_lName" id="student_lName" class="form-control"
                                        placeholder="Enter Last Name" required autocomplete="student_lName" />
                                </div>

                                <!-- Extension Name Field -->
                                <div class="col mb-2 mt-2">
                                    <label for="student_extName" class="form-label fw-bold">Extension Name</label>
                                    <input type="text" name="student_extName" id="student_extName"
                                        class="form-control" placeholder="e.g Jr., III (if applicable)" />
                                </div>
                            </div>


                            <div class="row">

                                <!-- Place of Birth Field-->
                                <div class="col mb-2 mt-2">
                                    <label for="student_pob" class="form-label fw-bold">Place of Birth</label>
                                    <input type="text" name="student_pob" id="student_pob" class="form-control"
                                        placeholder="Municipality/City" required />
                                </div>

                                <!-- Date Of Birth Field -->
                                <div class="col mb-2 mt-2">
                                    <label for="student_dob" class="form-label fw-bold">Date of Birth</label>
                                    <input class="form-control" name="student_dob" type="date" id="student_dob" />
                                </div>
                            </div>

                            <hr class="my-5" />

                            <!-- Address fields: split into parts to match controller -->
                            <h4 class="fw-bold mb-3 mt-4 text-primary">Address</h4>

                            <div class="row">
                                <div class="col mb-2 mt-2">
                                    <label for="house_no" class="form-label fw-bold">House No.</label>
                                    <input type="text" name="house_no" id="house_no" class="form-control"
                                        placeholder="Enter House No." />
                                </div>

                                <div class="col mb-2 mt-2">
                                    <label for="street_name" class="form-label fw-bold">Street Name</label>
                                    <input type="text" name="street_name" id="street_name" class="form-control"
                                        placeholder="Enter Street Name" />
                                </div>
                            </div>

                            <div class="row">
                                <div class="col mb-2 mt-2">
                                    <label for="barangay" class="form-label fw-bold">Barangay</label>
                                    <input type="text" name="barangay" id="barangay" class="form-control"
                                        placeholder="Enter Barangay" />
                                </div>

                                <div class="col mb-2 mt-2">
                                    <label for="municipality_city" class="form-label fw-bold">Municipality/City</label>
                                    <input type="text" name="municipality_city" id="municipality_city"
                                        class="form-control" placeholder="Enter Municipality or City" />
                                </div>
                            </div>

                            <div class="row">
                                <div class="col mb-2 mt-2">
                                    <label for="province" class="form-label fw-bold">Province</label>
                                    <input type="text" name="province" id="province" class="form-control"
                                        placeholder="Enter Province" />
                                </div>

                                <div class="col mb-2 mt-2">
                                    <label for="zip_code" class="form-label fw-bold">Zip Code</label>
                                    <input type="text" name="zip_code" id="zip_code" class="form-control"
                                        placeholder="Enter Zip Code" />
                                </div>
                            </div>


                            <hr class="my-5" />

                            <!-- Parent Info -->
                            <h4 class="fw-bold mb-3 mt-4 text-primary">Parent Information</h4>

                            <!-- Father's Info -->
                            <h5 class="fw-bold mb-3 mt-4 text-primary">Father's Information</h5>

                            <div class="row">
                                <!-- Father's Firt Name -->
                                <div class="col mb-2 mt-2">
                                    <label for="student_fatherFName" class="form-label fw-bold">First
                                        Name</label>
                                    <input type="text" name="student_fatherFName" id="student_fatherFName"
                                        class="form-control" placeholder="Enter Father's First Name" />
                                </div>

                                <!-- Father's Middle Name -->
                                <div class="col mb-2 mt-2">
                                    <label for="student_fatherMName" class="form-label fw-bold">Middle
                                        Name</label>
                                    <input type="text" name="student_fatherMName" id="student_fatherMName"
                                        class="form-control" placeholder="Enter Father's Middle Name" />
                                </div>
                            </div>

                            <div class="row">
                                <!-- Father's Last Name -->
                                <div class="col mb-2 mt-2">
                                    <label for="student_fatherLName" class="form-label fw-bold">Last Name</label>
                                    <input type="text" name="student_fatherLName" id="student_fatherLName"
                                        class="form-control" placeholder="Enter Father's Last Name" />
                                </div>

                                <!-- Father's Phone No. -->
                                <div class="col mb-2 mt-2">
                                    <label for="student_fatherPhone" class="form-label fw-bold">Phone No.</label>
                                    <input type="text" name="student_fatherPhone" id="student_fatherPhone"
                                        class="form-control" placeholder="Enter Father's Phone Number" />
                                </div>
                            </div>

                            <!-- Mother's Info -->
                            <h5 class="fw-bold mb-3 mt-4 text-primary">Mother's Information</h5>

                            <div class="row">
                                <!-- Mother's First Name -->
                                <div class="col mb-2 mt-2">
                                    <label for="student_motherFName" class="form-label fw-bold">First Name</label>
                                    <input type="text" name="student_motherFName" id="student_motherFName"
                                        class="form-control" placeholder="Enter Mother's First Name" />
                                </div>

                                <!-- Mother's Middle Name -->
                                <div class="col mb-2 mt-2">
                                    <label for="student_motherMName" class="form-label fw-bold">Middle Name</label>
                                    <input type="text" name="student_motherMName" id="student_motherMName"
                                        class="form-control" placeholder="Enter Mother's Middle Name" />
                                </div>
                            </div>

                            <div class="row">
                                <!-- Mother's Last Name -->
                                <div class="col mb-2 mt-2">
                                    <label for="student_motherLName" class="form-label fw-bold">Last Name</label>
                                    <input type="text" name="student_motherLName" id="student_motherLName"
                                        class="form-control" placeholder="Enter Mother's Last Name" />
                                </div>

                                <!-- Mother's Phone No. -->
                                <div class="col mb-2 mt-2">
                                    <label for="student_motherPhone" class="form-label fw-bold">Phone No.</label>
                                    <input type="text" name="student_motherPhone" id="student_motherPhone"
                                        class="form-control" placeholder="Enter Mother's Phone Number" />
                                </div>
                            </div>

                            <!-- Emergency Contact's Info -->
                            <h5 class="fw-bold mb-3 mt-4 text-primary">Emergency Contact's Information</h5>

                            <div class="row">
                                <!-- Emergency Contact's Firt Name -->
                                <div class="col mb-2 mt-2">
                                    <label for="student_emergContFName" class="form-label fw-bold">First
                                        Name</label>
                                    <input type="text" name="student_emergContFName" id="student_emergContFName"
                                        class="form-control" placeholder="Enter Emergency Contact's First Name" />
                                </div>

                                <!-- Emergency Contact's Middle Name -->
                                <div class="col mb-2 mt-2">
                                    <label for="student_emergContMName" class="form-label fw-bold">Middle
                                        Name</label>
                                    <input type="text" name="student_emergContMName" id="student_emergContMName"
                                        class="form-control" placeholder="Enter Emergency Contact's Middle Name" />
                                </div>
                            </div>

                            <div class="row">
                                <!-- Emergency Contact's Last Name -->
                                <div class="col mb-2 mt-2">
                                    <label for="student_emergContLName" class="form-label fw-bold">Last Name</label>
                                    <input type="text" name="student_emergContLName" id="student_emergContLName"
                                        class="form-control" placeholder="Enter Emergency Contact's Last Name" />
                                </div>

                                <!-- Emergency Contact's Phone No. -->
                                <div class="col mb-2 mt-2">
                                    <label for="student_emergContPhone" class="form-label fw-bold">Phone No.</label>
                                    <input type="phone" name="student_emergContPhone" id="student_emergContPhone"
                                        class="form-control" placeholder="Enter Emergency Contact's Phone Number" />
                                </div>
                            </div>

                        </div>

                        <div class="modal-footer d-flex justify-content-between">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Add Student</button>
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
@endpush
