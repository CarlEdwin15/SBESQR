@extends('./layouts.main')

@section('title', 'Admin | Grades Management')

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
            <li class="menu-item">
                <a href="javascript:void(0);" class="menu-link menu-toggle bg-dark text-light">
                    <i class="menu-icon tf-icons bx bxs-graduation text-light"></i>
                    <div class="text-light">Students</div>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item">
                        <a href="{{ route('student.management') }}" class="menu-link bg-dark text-light">
                            <div class="text-light">Student Management</div>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="{{ route('show.students') }}" class="menu-link bg-dark text-light">
                            <div class="text-light">Student Enrollment</div>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="{{ route('students.promote.view') }}" class="menu-link bg-dark text-light">
                            <div class="text-light">Class Re-Enrollment</div>
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

    <!-- Content wrapper -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <h4 class="fw-bold text-warning mb-0">
                    <span class="text-muted fw-light">
                        <a class="text-muted fw-light" href="{{ route('home') }}">Dashboard</a> /
                        <a class="text-muted fw-light" href="{{ route('all.classes') }}">Classes</a> /
                        <a class="text-muted fw-light"
                            href="{{ route('classes.showClass', [
                                'grade_level' => $class->grade_level,
                                'section' => $class->section,
                            ]) }}?school_year={{ $selectedYear }}">
                            {{ ucfirst($class->grade_level) }} - {{ $class->section }}
                        </a> /
                        <a class="text-muted fw-light"
                            href="{{ route('classes.subjects', [
                                'grade_level' => $class->grade_level,
                                'section' => $class->section,
                            ]) }}?school_year={{ $selectedYear }}">
                            Subjects
                        </a> /
                    </span>
                    {{ $classSubject->subject->name }}
                </h4>
            </div>
        </div>
        <h4 class="text-info fw-bold mb-3 text-center">{{ $class->formatted_grade_level }} - {{ $class->section }}
            (Grades Overview)</h4>

        <div class="text-start mt-3">
            <a href="{{ route('classes.subjects', [
            'grade_level' => $class->grade_level,
            'section' => $class->section,
            ]) }}?school_year={{ $selectedYear }}"
            class="btn btn-danger mb-2">
            <i class="bx bx-chevron-left"></i> Back
            </a>
        </div>

        <div class="card p-4 shadow-sm">
            {{-- MALE STUDENTS --}}
            <div class="table-responsive mb-4">
                <table class="table table-hover table-bordered align-middle">
                    <thead class="table-info text-center">
                        <tr>
                            <th style="width: 40px;">No.</th>
                            <th style="width: 5%;">Photo</th>
                            <th style="width: 20%;">Male || Name</th>
                            <th>1st Quarter</th>
                            <th>2nd Quarter</th>
                            <th>3rd Quarter</th>
                            <th>4th Quarter</th>
                            <th>Final Grade</th>
                            <th>Remarks</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $maleIndex = 1; @endphp
                        @foreach ($students->where('gender', 'male')->sortBy(fn($s) => strtolower($s->student_lName . ' ' . $s->student_fName)) as $student)
                            @php
                                $quarters = [1, 2, 3, 4];
                                $grades = [];

                                // Collect quarterly grades
                                foreach ($quarters as $q) {
                                    $gradeRecord = optional($student->quarterlyGrades)->firstWhere(
                                        'quarter.quarter',
                                        $q,
                                    );
                                    $grades[$q] = $gradeRecord->final_grade ?? '';
                                }

                                // Count only valid (non-empty) grades
                                $validGrades = array_filter($grades, fn($g) => $g !== '');

                                // Check if all 4 quarters have grades
                                $allQuartersHaveGrades = count($validGrades) === 4;

                                // Compute final average only if all quarters are complete
                                $finalAverage = $allQuartersHaveGrades
                                    ? $student->finalSubjectGrades->first()->final_grade ??
                                        round(array_sum($validGrades) / 4)
                                    : '';

                                // Remarks only if complete
                                $remarks = $allQuartersHaveGrades ? ($finalAverage >= 75 ? 'PASSED' : 'FAILED') : '';
                            @endphp

                            <tr>
                                <td class="text-center">{{ $maleIndex++ }}</td>
                                <td class="text-center">
                                    <img src="{{ $student->student_photo ? asset('public/uploads/' . $student->student_photo) : asset('assetsDashboard/img/student_profile_pictures/student_default_profile.jpg') }}"
                                        alt="Photo" class="rounded-circle" style="width: 40px; height: 40px;">
                                </td>
                                <td>{{ $student->student_lName }}, {{ $student->student_fName }}
                                    {{ $student->student_mName }}</td>

                                @foreach ($quarters as $q)
                                    <td class="text-center">{{ $grades[$q] }}</td>
                                @endforeach

                                {{-- Final Grade + Remarks (hidden if incomplete) --}}
                                <td class="text-center fw-bold">
                                    {{ $allQuartersHaveGrades ? $finalAverage : '' }}
                                </td>
                                <td class="text-center fw-semibold text-uppercase">
                                    {{ $allQuartersHaveGrades ? $remarks : '' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- FEMALE STUDENTS --}}
            <div class="table-responsive">
                <table class="table table-hover table-bordered align-middle">
                    <thead class="table-danger text-center">
                        <tr>
                            <th style="width: 40px;">No.</th>
                            <th style="width: 5%;">Photo</th>
                            <th style="width: 20%;">Female || Name</th>
                            <th>1st Quarter</th>
                            <th>2nd Quarter</th>
                            <th>3rd Quarter</th>
                            <th>4th Quarter</th>
                            <th>Final Grade</th>
                            <th>Remarks</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $femaleIndex = 1; @endphp
                        @foreach ($students->where('gender', 'female')->sortBy(fn($s) => strtolower($s->student_lName . ' ' . $s->student_fName)) as $student)
                            @php
                                $quarters = [1, 2, 3, 4];
                                $grades = [];

                                foreach ($quarters as $q) {
                                    $gradeRecord = optional($student->quarterlyGrades)->firstWhere(
                                        'quarter.quarter',
                                        $q,
                                    );
                                    $grades[$q] = $gradeRecord->final_grade ?? '';
                                }

                                $validGrades = array_filter($grades, fn($g) => $g !== '');
                                $allQuartersHaveGrades = count($validGrades) === 4;

                                $finalAverage = $allQuartersHaveGrades
                                    ? $student->finalSubjectGrades->first()->final_grade ??
                                        round(array_sum($validGrades) / 4)
                                    : '';

                                $remarks = $allQuartersHaveGrades ? ($finalAverage >= 75 ? 'PASSED' : 'FAILED') : '';
                            @endphp

                            <tr>
                                <td class="text-center">{{ $femaleIndex++ }}</td>
                                <td class="text-center">
                                    <img src="{{ $student->student_photo ? asset('public/uploads/' . $student->student_photo) : asset('assetsDashboard/img/student_profile_pictures/student_default_profile.jpg') }}"
                                        alt="Photo" class="rounded-circle" style="width: 40px; height: 40px;">
                                </td>
                                <td>{{ $student->student_lName }}, {{ $student->student_fName }}
                                    {{ $student->student_mName }}</td>

                                @foreach ($quarters as $q)
                                    <td class="text-center">{{ $grades[$q] }}</td>
                                @endforeach

                                <td class="text-center fw-bold">
                                    {{ $allQuartersHaveGrades ? $finalAverage : '' }}
                                </td>
                                <td class="text-center fw-semibold text-uppercase">
                                    {{ $allQuartersHaveGrades ? $remarks : '' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- End Content wrapper -->

@endsection

@push('styles')
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

        .active-quarter {
            background-color: #e0fdd0 !important;
            /* Bootstrap bg-warning */
        }
    </style>
@endpush
