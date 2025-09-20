@extends('./layouts.main')

@section('title', 'Admin | Masters List')

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
            <li class="menu-item active open">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons bx bx-objects-horizontal-left"></i>
                    <div>Classes</div>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item active">
                        <a href="{{ route('all.classes') }}" class="menu-link bg-dark text-light">
                            <div class="text-warning">All Classes</div>
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
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <h4 class="fw-bold text-warning mb-0">
                    <span class="text-muted fw-light">
                        <a class="text-muted fw-light" href="{{ route('home') }}">Dashboard</a> /
                        <a class="text-muted fw-light" href="{{ route('all.classes') }}">Classes</a> /
                        <a class="text-muted fw-light"
                            href="{{ route('classes.showClass', ['grade_level' => $class->grade_level, 'section' => $class->section]) }}?school_year={{ $selectedYear }}">
                            {{ ucfirst($class->grade_level) }} - {{ $class->section }} </a> /
                    </span>
                    Master’s List
                </h4>
            </div>
        </div>

        <a href="{{ url()->previous() }}" class="btn btn-danger mb-3">Back</a>
        <div class="card p-4 shadow-sm">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h5 class="fw-bold mb-2">School Year: {{ $selectedYear }}</h5>

                <!-- Export List Form -->
                <form action="" method="GET">
                    @csrf
                    <button type="submit" class="btn btn-success ms-md-auto">
                        <i class="bx bx-printer"></i>Export List
                    </button>
                </form>
            </div>

            <h3 class="mb-4 text-center fw-bold">List of Students for
                {{ $class->formatted_grade_level }} -
                {{ $class->section }}</h3><br>
            <h5 class="text-center">Adviser:</h5>

            @if ($class->adviser)
                <h5 class="text-info text-center mb-4">
                    {{ $class->adviser->firstName ?? 'N/A' }} {{ $class->adviser->lastName ?? '' }}
                </h5>
            @else
                <h6 class="text-danger text-center mb-4">No teacher assigned</h6>
            @endif

            <div class="row">
                <!-- Male Table -->
                <div class="col-md-6">
                    <table class="table table-bordered text-center" id="studentTable">
                        <thead class="table-info">
                            <tr>
                                <th>NO.</th>
                                <th>MALE</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $maleCount = 1; @endphp
                            @foreach ($students->where('student_sex', 'male')->sortBy(function ($student) {
            return $student->student_lName . ' ' . $student->student_fName . ' ' . $student->student_mName;
        }) as $student)
                                <tr class="student-row">
                                    <td>{{ $maleCount++ }}</td>
                                    <td>
                                        {{ $student->student_lName }}, {{ $student->student_fName }}
                                        {{ $student->student_extName }}
                                        @if (!empty($student->student_mName))
                                            {{ strtoupper(substr($student->student_mName, 0, 1)) }}.
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            @if ($maleCount === 1)
                                <tr>
                                    <td colspan="2">No male students enrolled.</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>

                <!-- Female Table -->
                <div class="col-md-6">
                    <table class="table table-bordered text-center" id="studentTable">
                        <thead class="table-danger">
                            <tr>
                                <th>NO.</th>
                                <th>FEMALE</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $femaleCount = 1; @endphp
                            @foreach ($students->where('student_sex', 'female')->sortBy(function ($student) {
            return $student->student_lName . ' ' . $student->student_fName . ' ' . $student->student_mName;
        }) as $student)
                                <tr class="student-row">
                                    <td>{{ $femaleCount++ }}</td>
                                    <td>
                                        {{ $student->student_lName }}, {{ $student->student_fName }}
                                        {{ $student->student_extName }}
                                        @if (!empty($student->student_mName))
                                            {{ strtoupper(substr($student->student_mName, 0, 1)) }}.
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            @if ($femaleCount === 1)
                                <tr>
                                    <td colspan="2">No female students enrolled.</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- End Content wrapper -->

@endsection

@push('scripts')
    <script>
        // search bar
        document.getElementById('studentSearch').addEventListener('keyup', function() {
            const searchValue = this.value.toLowerCase();
            const rows = document.querySelectorAll('#studentTable tbody .student-row');

            rows.forEach(row => {
                const rowText = row.innerText.toLowerCase();
                row.style.display = rowText.includes(searchValue) ? '' : 'none';
            });
        });
    </script>

    <script>
        // delete button alert
        function confirmDelete(student_id, student_fName, student_lName) {
            Swal.fire({
                title: `Delete ${student_fName} ${student_lName}'s record?`,
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
                                document.getElementById('delete-form-' + student_id).submit();
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

@push('styles')
    <!-- Main CSS File -->
    <link href="{{ asset('assets/css/main.css') }}" rel="stylesheet" />
    <link href="{{ asset('assetsDashboard/vendor/css/core.css') }}" rel="stylesheet" />

    <!-- Vendor CSS Files -->
    <link href="{{ asset('assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet" />

    <style>
        .card-hover {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            cursor: pointer;
        }

        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.2);
        }
    </style>
@endpush
