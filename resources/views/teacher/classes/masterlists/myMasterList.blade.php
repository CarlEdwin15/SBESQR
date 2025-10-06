@extends('./layouts.main')

@section('title', 'Teacher | Master List')

@section('content')

    <!-- Menu -->
    <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
        <div class="app-brand bg-dark">
            <a href="{{ url('/home') }}" class="app-brand-link">
                <img src="{{ asset('assets/img/logo.png') }}" alt="Logo" class="app-brand-logo">
                <span class="app-brand-text menu-text fw-bolder text-warning" style="padding: 9px">Teacher's
                    <span class="text-warning">Management</span>
                </span>
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


            {{-- Students sidebar --}}
            <li class="menu-item">
                <a href="javascript:void(0)" class="menu-link menu-toggle bg-dark text-light">
                    <i class="menu-icon tf-icons bx bxs-graduation text-light"></i>
                    <div class="text-light">Students</div>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item">
                        <a href="{{ route('teacher.my.students') }}" class="menu-link bg-dark text-light">
                            <div class="text-light">My Students</div>
                        </a>
                    </li>
                </ul>
            </li>

            {{-- Classes sidebar --}}
            <li class="menu-item active open">
                <a href="javascript:void(0)" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons bx bx-notepad"></i>
                    <div>Classes</div>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item active">
                        <a href="{{ route('teacher.myClasses') }}" class="menu-link bg-dark text-light">
                            <div class="text-warning">My Classes</div>
                        </a>
                    </li>
                </ul>
            </li>

            {{-- SMS Logs sidebar --}}
            <li class="menu-item">
                <a href="" class="menu-link bg-dark text-light">
                    <i class="bx bx-message-check me-3 text-light"></i>
                    <div class="text-light">SMS Logs</div>
                </a>
            </li>

            {{-- Account Settings sidebar --}}
            <li class="menu-item">
                <a href="" class="menu-link bg-dark text-light">
                    <i class="bx bx-cog me-3 text-light"></i>
                    <div class="text-light">Account Settings</div>
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
                        <a class="text-muted fw-light" href="{{ route('teacher.myClasses') }}">Classes</a> /
                        <a class="text-muted fw-light"
                            href="{{ route('teacher.myClass', ['grade_level' => $class->grade_level, 'section' => $class->section, 'school_year' => $selectedYear]) }}">
                            {{ ucfirst($class->grade_level) }} - {{ $class->section }} ({{ $selectedYear }})
                        </a> /
                    </span>
                    Master List
                </h4>
            </div>
        </div>

        <h3 class="mb-1 text-center fw-bold text-info">Class Master List ({{ $selectedYear }})</h3><br>

        <div class="d-flex justify-content-between align-items-center">
            <a href="{{ route('teacher.myClass', ['grade_level' => $class->grade_level, 'section' => $class->section, 'school_year' => $selectedYear]) }}"
                class="btn btn-danger mb-3 d-flex align-items-center">
                <i class='bx bx-chevrons-left'></i>
                <span class="d-none d-sm-block">Back</span>
            </a>
        </div>

        <div class="card p-4 shadow-sm">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h3 class="fw-bold mb-2 text-primary">{{ $class->formatted_grade_level }} -
                    {{ $class->section }}</h3>
            </div>

            <h5 class="text-center">Adviser:</h5>

            @if ($class->adviser)
                <h5 class="text-info text-center mb-4 fw-bold">
                    {{ $class->adviser->firstName ?? 'N/A' }} {{ $class->adviser->lastName ?? '' }}
                </h5>
            @else
                <h6 class="text-danger text-center mb-4">No teacher assigned</h6>
            @endif

            <div class="row">
                <!-- Male Table -->
                <div class="col-md-6">
                    <table class="table table-hover table-bordered" id="studentTable">
                        <thead class="table-info">
                            <tr class="text-center">
                                <th style="width: 5%;">NO.</th>
                                <th style="width: 10%;">PHOTO</th>
                                <th>MALE</th>
                                <th>LRN</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $maleCount = 1; @endphp
                            @foreach ($students->where('student_sex', 'male')->sortBy(function ($student) {
            return $student->student_lName . ' ' . $student->student_fName . ' ' . $student->student_mName;
        }) as $student)
                                <tr class="t-row"
                                    data-href="{{ route('teacher.student.info', ['id' => $student->id, 'school_year' => $schoolYearId]) }}">
                                    <td class="text-center">{{ $maleCount++ }}</td>
                                    <td class="text-center">
                                        <img src="{{ $student->student_photo ? asset('storage/' . $student->student_photo) : asset('assetsDashboard/img/student_profile_pictures/student_default_profile.jpg') }}"
                                            alt="Student Photo" class="rounded-circle me-2 student-photo"
                                            style="width: 40px; height: 40px;">
                                    </td>
                                    <td>
                                        {{ $student->student_lName }}, {{ $student->student_fName }}
                                        {{ $student->student_extName }}
                                        @if (!empty($student->student_mName))
                                            {{ strtoupper(substr($student->student_mName, 0, 1)) }}.
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        {{ $student->student_lrn }}
                                    </td>
                                </tr>
                            @endforeach
                            @if ($maleCount === 1)
                                <tr>
                                    <td colspan="3" class="text-center">No male students enrolled.</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
                <!-- /Male Table -->

                <!-- Female Table -->
                <div class="col-md-6">
                    <table class="table table-bordered" id="studentTable">
                        <thead class="table-danger">
                            <tr class="text-center">
                                <th style="width: 5%;">NO.</th>
                                <th style="width: 10%;">PHOTO</th>
                                <th>FEMALE</th>
                                <th>LRN</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $femaleCount = 1; @endphp
                            @foreach ($students->where('student_sex', 'female')->sortBy(function ($student) {
            return $student->student_lName . ' ' . $student->student_fName . ' ' . $student->student_mName;
        }) as $student)
                                <tr class="t-row"
                                    data-href="{{ route('teacher.student.info', ['id' => $student->id, 'school_year' => $schoolYearId]) }}">
                                    <td class="text-center">{{ $femaleCount++ }}</td>
                                    <td class="text-center">
                                        <img src="{{ $student->student_photo ? asset('storage/' . $student->student_photo) : asset('assetsDashboard/img/student_profile_pictures/student_default_profile.jpg') }}"
                                            alt="Student Photo" class="rounded-circle me-2 student-photo"
                                            style="width: 40px; height: 40px;">
                                    </td>
                                    <td>
                                        {{ $student->student_lName }}, {{ $student->student_fName }}
                                        {{ $student->student_extName }}
                                        @if (!empty($student->student_mName))
                                            {{ strtoupper(substr($student->student_mName, 0, 1)) }}.
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        {{ $student->student_lrn }}
                                    </td>
                                </tr>
                            @endforeach
                            @if ($femaleCount === 1)
                                <tr>
                                    <td colspan="3" class="text-center">No female students enrolled.</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
                <!-- /Female Table -->
            </div>
        </div>
    </div>
    <!-- End Content wrapper -->

@endsection

@push('scripts')
    <!-- Include Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        // logout confirmation
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

@push('styles')
    <!-- Main CSS File -->
    <link href="{{ asset('assets/css/main.css') }}" rel="stylesheet" />
    <link href="{{ asset('assetsDashboard/vendor/css/core.css') }}" rel="stylesheet" />
    <link href="{{ asset('assetsDashboard/vendor/css/theme-default.css') }}" rel="stylesheet" />

    <!-- Vendor CSS Files -->
    <link href="{{ asset('assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet" />

    <style>
        .student-photo {
            width: 45px;
            height: 45px;
            object-fit: cover;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .student-photo:hover {
            transform: scale(1.1);
            box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.2);
        }
    </style>
@endpush
