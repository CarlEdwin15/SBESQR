@extends('layouts.main')

@section('title', 'Re-Enroll Students')

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
                    <li class="menu-item active">
                        <a href="{{ route('students.promote.view') }}" class="menu-link bg-dark text-light">
                            <div class="text-warning">Re-Enrollment / Promotion</div>
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

    <!-- Content wrapper -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4 text-warning"><span class="text-muted fw-light">
                <a class="text-muted fw-light" href="{{ route('home') }}">Dashboard / </a>
                <a class="text-muted fw-light" href="{{ route('student.management') }}">Students / </a>
                <a class="text-muted fw-light" href="{{ route('students.promote') }}">Re-Enrollment / Promotion / </a>
            </span> Student Re-Enrollment
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

        {{-- Add this after your error display --}}
        @if (session('auto_graduated'))
            <div class="alert alert-info">
                <i class="bx bx-graduation me-2"></i>
                {{ session('auto_graduated') }}
            </div>
        @endif

        <div class="mb-4 text-center">
            <h4 class="fw-bold text-primary">
                Re-Enroll Students from {{ $previousSchoolYear }} to {{ $currentSchoolYear }}
            </h4>
        </div>

        <h4 class="fw-bold text-primary text-center">{{ strtoupper($gradeLevel) }} - {{ $selectedSection }}
        </h4>

        <form action="{{ route('students.promote') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <input type="hidden" name="next_school_year" value="{{ $currentSchoolYear }}">
            <input type="hidden" name="current_grade_level" value="{{ $gradeLevel }}">
            <input type="hidden" name="current_section" value="{{ $selectedSection }}">

            <!-- Global Search Bar -->
            <div class="card mb-2">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="input-group">
                                <span class="input-group-text"><i class="bx bx-search"></i></span>
                                <input type="search" class="form-control"
                                    placeholder="Search students across all sections..." id="globalSearch">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Accordion for Student Groups -->
            <div class="accordion" id="studentAccordion">

                <!-- Not Re-Enrolled Students Accordion Item -->
                <div class="accordion-item mb-2 border-0 shadow-sm">
                    <h2 class="accordion-header" id="notReenrolledHeading">
                        <button class="accordion-button bg-opacity-10 border-warning border-start border-5 py-3"
                            type="button" data-bs-toggle="collapse" data-bs-target="#notReenrolledCollapse"
                            aria-expanded="true" aria-controls="notReenrolledCollapse">
                            <i class="bx bx-user-plus me-2"></i>
                            Students Available for Re-Enrollment/Promotion
                            <span class="badge bg-label-info ms-2" id="notReenrolledCount">
                                {{ $activeStudents->where('current_enrollment_status', 'not_enrolled')->count() }}
                                students
                            </span>
                        </button>
                    </h2>
                    <div id="notReenrolledCollapse" class="accordion-collapse collapse show"
                        aria-labelledby="notReenrolledHeading" data-bs-parent="#studentAccordion">
                        <div class="accordion-body">

                            <!-- Controls Section - Organized Layout -->
                            <div class="row justify-content-between align-items-center mb-3">
                                <!-- Left Side: Buttons -->
                                <div class="col-lg-6 col-md-12 mb-3 mb-lg-0">
                                    <div
                                        class="d-flex flex-column flex-md-row gap-2 justify-content-lg-start justify-content-md-start">
                                        <!-- Initial Action Buttons -->
                                        <div class="d-flex gap-2 justify-content-center justify-content-md-start"
                                            id="initialButtons">
                                            <button type="button"
                                                class="btn btn-outline-success flex-fill flex-md-grow-0"
                                                id="reEnrollPromotedBtn">
                                                <i class="bx bx-check-circle me-1"></i>Promoted
                                            </button>
                                            <button type="button"
                                                class="btn btn-outline-warning flex-fill flex-md-grow-0"
                                                id="reEnrollRetainedBtn">
                                                <i class="bx bx-error-circle me-1"></i>Retained
                                            </button>
                                        </div>

                                        <!-- Submit & Cancel Buttons - Hidden by Default -->
                                        <div class="d-flex gap-2 justify-content-center justify-content-md-start d-none"
                                            id="submitButtonContainer">
                                            <button type="submit" class="btn btn-success flex-fill flex-md-grow-0"
                                                id="submitPromotedBtn">
                                                <i class="bx bx-check-circle me-1"></i>Re-Enroll Promoted
                                                <span
                                                    class="selected-count-badge bg-white text-success ms-1 px-2 py-1 rounded"
                                                    id="promotedCount">0</span>
                                            </button>
                                            <button type="submit" class="btn btn-warning flex-fill flex-md-grow-0 d-none"
                                                id="submitRetainedBtn">
                                                <i class="bx bx-error-circle me-1"></i>Re-Enroll Retained
                                                <span
                                                    class="selected-count-badge bg-white text-warning ms-1 px-2 py-1 rounded"
                                                    id="retainedCount">0</span>
                                            </button>
                                            <button type="button" class="btn btn-secondary flex-fill flex-md-grow-0"
                                                id="cancelSelectionBtn">
                                                <i class="bx bx-x me-1"></i>Cancel
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <!-- Right Side: Dropdowns -->
                                <div class="col-md-4 col-12">
                                    <div class="d-flex gap-2 align-items-center justify-content-lg-end justify-content-md-start d-none"
                                        id="reEnrollDropdowns">
                                        @php
                                            $promotionMap = [
                                                'kindergarten' => ['kindergarten', 'grade1'],
                                                'grade1' => ['grade1', 'grade2'],
                                                'grade2' => ['grade2', 'grade3'],
                                                'grade3' => ['grade3', 'grade4'],
                                                'grade4' => ['grade4', 'grade5'],
                                                'grade5' => ['grade5', 'grade6'],
                                                'grade6' => ['grade6', 'graduated'],
                                            ];
                                        @endphp

                                        <select class="form-select flex-grow-1" name="batch_grade" id="batchGrade"
                                            required>
                                            <option value="" disabled selected>Grade</option>
                                            @foreach ($promotionMap[$gradeLevel] ?? [] as $level)
                                                @php
                                                    $label = $level === $gradeLevel ? '(returnee)' : '(promoted)';
                                                    $formatted = ucfirst($level) . " $label";
                                                @endphp
                                                <option value="{{ $level }}">{{ $formatted }}</option>
                                            @endforeach
                                        </select>

                                        <select class="form-select flex-grow-1" name="batch_section" id="batchSection"
                                            required>
                                            <option value="" disabled selected>Section</option>
                                            @foreach (['A', 'B', 'C', 'D', 'E', 'F'] as $section)
                                                <option value="{{ $section }}">{{ $section }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <hr class="my-3" />

                            <!-- Combined Students Table - Not Re-Enrolled -->
                            <div class="table-responsive">
                                <table class="table table-hover table-bordered align-middle" id="notReenrolledTable">
                                    <thead class="table-primary text-center">
                                        <tr>
                                            <th style="width: 60px;" id="notReenrolledHeader">
                                                <span class="header-number">No.</span>
                                                <span class="header-checkbox d-none">
                                                    <input type="checkbox" id="selectAllNotReenrolled">
                                                </span>
                                            </th>
                                            <th style="width: 25%">Student Name</th>
                                            <th>Re-Enrollment Status</th>
                                            <th>Final Average</th>
                                            <th>Enrollment Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $notReenrolledStudents = $activeStudents->where(
                                                'current_enrollment_status',
                                                'not_enrolled',
                                            );
                                            $notReenrolledIndex = 1;
                                        @endphp
                                        @foreach ($notReenrolledStudents->sortBy(fn($s) => strtolower($s->student_lName . ' ' . $s->student_fName . ' ' . $s->student_mName)) as $student)
                                            @php
                                                $eligibility = $student->promotion_eligibility;
                                                $average = $eligibility['average'];

                                                // Determine average color
                                                $averageColor = 'text-muted';
                                                if ($average) {
                                                    if ($average < 75) {
                                                        $averageColor = 'text-danger';
                                                    } elseif ($average >= 75 && $average <= 84) {
                                                        $averageColor = 'text-warning';
                                                    } elseif ($average >= 85) {
                                                        $averageColor = 'text-success';
                                                    }
                                                }

                                                $badgeClass = match ($eligibility['status']) {
                                                    'eligible_promotion' => 'bg-label-success',
                                                    'eligible_graduation' => 'bg-label-info',
                                                    'retained' => 'bg-label-warning',
                                                    'retained_graduation' => 'bg-label-danger',
                                                    'no_grades' => 'bg-label-secondary',
                                                    default => 'bg-label-secondary',
                                                };

                                                $icon = match ($eligibility['status']) {
                                                    'eligible_promotion', 'eligible_graduation' => '✓',
                                                    'retained', 'retained_graduation' => '⚠',
                                                    'no_grades' => '?',
                                                    default => '',
                                                };
                                            @endphp
                                            <tr class="{{ !$student->promotion_eligibility['eligible'] ? '' : '' }}"
                                                data-eligible="{{ $student->promotion_eligibility['eligible'] ? 'true' : 'false' }}"
                                                data-gender="{{ $student->student_sex }}">
                                                <td class="text-center">
                                                    <span class="row-number">{{ $notReenrolledIndex++ }}</span>
                                                    <span class="row-checkbox d-none">
                                                        <input type="checkbox" name="selected_students[]"
                                                            value="{{ $student->id }}"
                                                            {{ !$student->promotion_eligibility['eligible'] ? 'data-warning="true"' : '' }}>
                                                    </span>
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <!-- Student Image -->
                                                        <div class="flex-shrink-0 me-3">
                                                            @if ($student->student_photo)
                                                                <img src="{{ asset('public/uploads/' . $student->student_photo) }}"
                                                                    alt="{{ $student->student_fName }} {{ $student->student_lName }}"
                                                                    class="rounded-circle student-avatar"
                                                                    style="width: 40px; height: 40px; object-fit: cover;">
                                                            @else
                                                                <img src="{{ asset('assetsDashboard/img/student_profile_pictures/student_default_profile.jpg') }}"
                                                                    alt="No Profile" width="40" height="40"
                                                                    class="rounded-circle student-avatar"
                                                                    style="object-fit: cover;">
                                                            @endif
                                                        </div>
                                                        <div class="flex-grow-1">
                                                            <div class="fw-semibold text-dark">
                                                                {{ $student->student_lName }},
                                                                {{ $student->student_fName }}
                                                                @if ($student->student_mName)
                                                                    {{ substr($student->student_mName, 0, 1) }}.
                                                                @endif
                                                                @if ($student->student_extName)
                                                                    {{ $student->student_extName }}
                                                                @endif

                                                                <!-- Gender Icon -->
                                                                @if ($student->student_sex == 'male')
                                                                    <i class="bx bx-male text-primary me-1 fs-6"
                                                                        title="Male"></i>
                                                                @else
                                                                    <i class="bx bx-female text-pink me-1 fs-6"
                                                                        title="Female"></i>
                                                                @endif
                                                            </div>
                                                            <div class="d-flex align-items-center mt-1">

                                                            </div>
                                                            <div class="d-flex align-items-center mt-1">
                                                                <code
                                                                    class="text-dark bg-light px-2 py-1 rounded">{{ $student->student_lrn }}</code>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge {{ $badgeClass }} text-white px-3 py-2">
                                                        <span class="me-1">{{ $icon }}</span>
                                                        {{ $eligibility['message'] }}
                                                    </span>
                                                </td>
                                                <td class="text-center">
                                                    @if ($average)
                                                        <span class="fw-bold {{ $averageColor }} fs-6">
                                                            {{ number_format($average) }}
                                                        </span>
                                                    @else
                                                        <span class="text-muted fst-italic">N/A</span>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    @php
                                                        $status = $student->current_enrollment_status ?? 'N/A';
                                                        $badgeClass = match ($status) {
                                                            'enrolled' => 'bg-label-success',
                                                            'not_enrolled' => 'bg-label-dark',
                                                            'archived' => 'bg-label-warning',
                                                            'graduated' => 'bg-label-info',
                                                            default => 'bg-label-dark',
                                                        };
                                                    @endphp
                                                    <span class="badge {{ $badgeClass }} text-uppercase px-3 py-2">
                                                        {{ strtoupper(str_replace('_', ' ', $status)) }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Pagination for Not Re-Enrolled -->
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <div class="d-flex gap-2 align-items-center">
                                    <select class="form-select form-select-sm" id="notReenrolledTableLength"
                                        style="width: auto;">
                                        <option value="5">5</option>
                                        <option value="10" selected>10</option>
                                        <option value="25">25</option>
                                        <option value="50">50</option>
                                    </select>
                                    <span class="text-muted">entries per page</span>
                                </div>
                                <div class="text-muted" id="notReenrolledTableInfo">
                                    Showing 0 to 0 of 0 entries
                                </div>
                                <nav aria-label="Not re-enrolled students pagination">
                                    <ul class="pagination justify-content-center mb-0" id="notReenrolledPagination">
                                        <!-- Pagination will be generated by JavaScript -->
                                    </ul>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Already Re-Enrolled Students Accordion Item -->
                <div class="accordion-item mb-2 border-0 shadow-sm">
                    <h2 class="accordion-header" id="alreadyReenrolledHeading">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#alreadyReenrolledCollapse" aria-expanded="false"
                            aria-controls="alreadyReenrolledCollapse">
                            <i class="bx bx-user-check me-2"></i>
                            Re-Enrolled Students
                            <span class="badge bg-label-success ms-2" id="alreadyReenrolledCount">
                                {{ $activeStudents->where('current_enrollment_status', 'enrolled')->count() }} students
                            </span>
                        </button>
                    </h2>
                    <div id="alreadyReenrolledCollapse" class="accordion-collapse collapse"
                        aria-labelledby="alreadyReenrolledHeading" data-bs-parent="#studentAccordion">
                        <div class="accordion-body">

                            <!-- Unenroll Controls - Hidden by Default -->
                            <div class="row justify-content-between align-items-center mb-3">
                                <div class="col-lg-3 col-md-8 mb-3">
                                    <div
                                        class="d-flex flex-column flex-md-row gap-2 justify-content-lg-start justify-content-md-start">
                                        <!-- Unenroll Button -->
                                        <div class="d-flex gap-2 justify-content-center justify-content-md-start"
                                            id="unenrollInitialButtons">
                                            <button type="button" class="btn btn-outline-danger flex-fill flex-md-grow-0"
                                                id="unenrollStudentsBtn">
                                                <i class="bx bx-user-x me-1"></i>Unenroll Students
                                            </button>
                                        </div>

                                        <!-- Submit & Cancel Buttons - Hidden by Default -->
                                        <div class="d-flex gap-2 justify-content-center justify-content-md-start d-none"
                                            id="unenrollSubmitContainer">
                                            <button type="button" class="btn btn-danger flex-fill flex-md-grow-0"
                                                id="submitUnenrollBtn">
                                                <i class="bx bx-user-x me-1"></i>Confirm Unenroll
                                                <span
                                                    class="selected-count-badge bg-white text-danger ms-1 px-2 py-1 rounded"
                                                    id="unenrollCount">0</span>
                                            </button>
                                            <button type="button" class="btn btn-secondary flex-fill flex-md-grow-0"
                                                id="cancelUnenrollBtn">
                                                <i class="bx bx-x me-1"></i>Cancel
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <hr class="my-3" />

                            <!-- Combined Students Table - Already Re-Enrolled -->
                            <div class="table-responsive">
                                <table class="table table-hover table-bordered align-middle" id="alreadyReenrolledTable">
                                    <thead class="table-success text-center">
                                        <tr>
                                            <th style="width: 60px;" id="alreadyReenrolledHeader">
                                                <span class="already-header-number">No.</span>
                                                <span class="already-header-checkbox d-none">
                                                    <input type="checkbox" id="selectAllAlreadyReenrolled">
                                                </span>
                                            </th>
                                            <th style="width: 25%;">Student Name</th>
                                            <th>Re-Enrolled To</th>
                                            <th>Enrollment Type</th>
                                            <th>Re-Enrolled Date</th>
                                            <th>Previous Class</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $alreadyReenrolledStudents = $activeStudents->where(
                                                'current_enrollment_status',
                                                'enrolled',
                                            );
                                            $alreadyReenrolledIndex = 1;
                                        @endphp
                                        @foreach ($alreadyReenrolledStudents->sortBy(fn($s) => strtolower($s->student_lName . ' ' . $s->student_fName . ' ' . $s->student_mName)) as $student)
                                            @php
                                                // Get enrollment type - prioritize current enrollment type, fallback to re_enrollment_info
                                                $enrollmentType =
                                                    $student->current_enrollment_type ??
                                                    ($student->re_enrollment_info['enrollment_type'] ?? 'regular');

                                                // Get re-enrollment date
                                                $reEnrollmentDate =
                                                    $student->re_enrollment_info['re_enrollment_date'] ?? now();

                                                // Format the date properly
                                                $formattedDate = \Carbon\Carbon::parse($reEnrollmentDate)->format(
                                                    'M j, Y || g:i A',
                                                );

                                                // Determine badge class based on enrollment type
                                                $typeBadgeClass = match ($enrollmentType) {
                                                    'regular' => 'bg-label-primary',
                                                    'transferee' => 'bg-label-info',
                                                    'returnee' => 'bg-label-warning',
                                                    default => 'bg-label-secondary',
                                                };

                                                // Check if this is current school year enrollment
                                                $isCurrentSchoolYear = $student->current_school_year_id
                                                    ? App\Models\SchoolYear::find($student->current_school_year_id)
                                                            ?->school_year === $currentSchoolYear
                                                    : false;
                                            @endphp
                                            <tr data-gender="{{ $student->student_sex }}">
                                                <td class="text-center">
                                                    <span
                                                        class="already-row-number">{{ $alreadyReenrolledIndex++ }}</span>
                                                    <span class="already-row-checkbox d-none">
                                                        <input type="checkbox" name="unenroll_students[]"
                                                            value="{{ $student->id }}">
                                                    </span>
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <!-- Student Image -->
                                                        <div class="flex-shrink-0 me-3">
                                                            @if ($student->student_photo)
                                                                <img src="{{ asset('public/uploads/' . $student->student_photo) }}"
                                                                    alt="{{ $student->student_fName }} {{ $student->student_lName }}"
                                                                    class="rounded-circle student-avatar"
                                                                    style="width: 40px; height: 40px; object-fit: cover;">
                                                            @else
                                                                <img src="{{ asset('assetsDashboard/img/student_profile_pictures/student_default_profile.jpg') }}"
                                                                    alt="No Profile" width="40" height="40"
                                                                    class="rounded-circle student-avatar"
                                                                    style="object-fit: cover;">
                                                            @endif
                                                        </div>
                                                        <div class="flex-grow-1">
                                                            <div class="fw-semibold text-dark">
                                                                {{ $student->student_lName }},
                                                                {{ $student->student_fName }}
                                                                @if ($student->student_mName)
                                                                    {{ substr($student->student_mName, 0, 1) }}.
                                                                @endif
                                                                @if ($student->student_extName)
                                                                    {{ $student->student_extName }}
                                                                @endif

                                                                <!-- Gender Icon -->
                                                                @if ($student->student_sex == 'male')
                                                                    <i class="bx bx-male text-primary me-1 fs-6"
                                                                        title="Male"></i>
                                                                @else
                                                                    <i class="bx bx-female text-pink me-1 fs-6"
                                                                        title="Female"></i>
                                                                @endif
                                                            </div>
                                                            <div class="d-flex align-items-center mt-1">

                                                            </div>
                                                            <div class="d-flex align-items-center mt-1">
                                                                <code
                                                                    class="text-dark bg-light px-2 py-1 rounded">{{ $student->student_lrn }}</code>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="text-center fw-bold text-success">
                                                    <div class="fw-bold">
                                                        @if (isset($student->current_grade_level) && isset($student->current_section))
                                                            {{ ucfirst($student->current_grade_level) }} -
                                                            {{ $student->current_section }}
                                                        @else
                                                            <span class="text-muted">Not Assigned</span>
                                                        @endif
                                                    </div>
                                                    <small class="text-muted">
                                                        SY:
                                                        @if (isset($student->re_enrollment_info['school_year']))
                                                            {{ $student->re_enrollment_info['school_year'] }}
                                                        @else
                                                            {{ $student->current_school_year_id
                                                                ? \App\Models\SchoolYear::find($student->current_school_year_id)?->school_year
                                                                : $currentSchoolYear }}
                                                        @endif
                                                    </small>
                                                </td>
                                                <td class="text-center">
                                                    <span
                                                        class="badge {{ $typeBadgeClass }} text-white px-3 py-2 text-uppercase">
                                                        {{ $enrollmentType }}
                                                    </span>
                                                    @if ($enrollmentType === 'returnee')
                                                        <br>
                                                        <small class="text-muted mt-1">Same class as previous</small>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    <div class="text-dark">{{ $formattedDate }}</div>
                                                </td>
                                                <td class="text-center">
                                                    <span class="text-dark">{{ ucfirst($gradeLevel) }} -
                                                        {{ $selectedSection }}</span>
                                                    <br>
                                                    <small class="text-muted">(Previous SY:
                                                        {{ $previousSchoolYear }})</small>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            @if ($alreadyReenrolledStudents->count() == 0)
                                <div class="text-center py-4">
                                    <i class="bx bx-user-x bx-lg text-muted mb-3"></i>
                                    <p class="text-muted">No students have been re-enrolled from this class yet.</p>
                                </div>
                            @endif

                            <!-- Pagination for Already Re-Enrolled -->
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <div class="d-flex gap-2 align-items-center">
                                    <select class="form-select form-select-sm" id="alreadyReenrolledTableLength"
                                        style="width: auto;">
                                        <option value="5">5</option>
                                        <option value="10" selected>10</option>
                                        <option value="25">25</option>
                                        <option value="50">50</option>
                                    </select>
                                    <span class="text-muted">entries per page</span>
                                </div>
                                <div class="text-muted" id="alreadyReenrolledTableInfo">
                                    Showing 0 to 0 of 0 entries
                                </div>
                                <nav aria-label="Already re-enrolled students pagination">
                                    <ul class="pagination justify-content-center mb-0" id="alreadyReenrolledPagination">
                                        <!-- Pagination will be generated by JavaScript -->
                                    </ul>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Graduated Students Accordion Item - Only for Grade 6 -->
                @if ($gradeLevel === 'grade6')
                    <div class="accordion-item mb-2 border-0 shadow-sm" id="graduatedStudentsSection">
                        <h2 class="accordion-header" id="graduatedHeading">
                            <button class="accordion-button collapsed bg-opacity-10 border-info border-start border-5 py-3"
                                type="button" data-bs-toggle="collapse" data-bs-target="#graduatedCollapse"
                                aria-expanded="false" aria-controls="graduatedCollapse">
                                <i class="bx bxs-graduation me-2"></i>
                                Graduated Students
                                <span class="badge bg-label-info ms-2" id="graduatedCount">
                                    {{ $activeStudents->where('current_enrollment_status', 'graduated')->count() +
                                        $activeStudents->where('previous_enrollment_status', 'graduated')->count() }}
                                    students
                                </span>
                            </button>
                        </h2>
                        <div id="graduatedCollapse" class="accordion-collapse collapse"
                            aria-labelledby="graduatedHeading" data-bs-parent="#studentAccordion">
                            <div class="accordion-body">

                                <!-- Ungraduate Controls - Hidden by Default -->
                                <div class="row justify-content-between align-items-center mb-3">
                                    <div class="col-lg-3 col-md-8 mb-3">
                                        <div
                                            class="d-flex flex-column flex-md-row gap-2 justify-content-lg-start justify-content-md-start">
                                            <!-- Ungraduate Button -->
                                            <div class="d-flex gap-2 justify-content-center justify-content-md-start"
                                                id="ungraduateInitialButtons">
                                                <button type="button"
                                                    class="btn btn-outline-warning flex-fill flex-md-grow-0"
                                                    id="ungraduateStudentsBtn">
                                                    <i class="bx bx-undo me-1"></i>Ungraduate Students
                                                </button>
                                            </div>

                                            <!-- Submit & Cancel Buttons - Hidden by Default -->
                                            <div class="d-flex gap-2 justify-content-center justify-content-md-start d-none"
                                                id="ungraduateSubmitContainer">
                                                <button type="button" class="btn btn-warning flex-fill flex-md-grow-0"
                                                    id="submitUngraduateBtn">
                                                    <i class="bx bx-undo me-1"></i>Confirm Ungraduate
                                                    <span
                                                        class="selected-count-badge bg-white text-warning ms-1 px-2 py-1 rounded"
                                                        id="ungraduateCount">0</span>
                                                </button>
                                                <button type="button" class="btn btn-secondary flex-fill flex-md-grow-0"
                                                    id="cancelUngraduateBtn">
                                                    <i class="bx bx-x me-1"></i>Cancel
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <hr class="my-3" />

                                <!-- Graduated Students Table -->
                                <div class="table-responsive">
                                    <table class="table table-hover table-bordered align-middle" id="graduatedTable">
                                        <thead class="table-info text-center">
                                            <tr>
                                                <th style="width: 60px;" id="graduatedHeader">
                                                    <span class="graduated-header-number">No.</span>
                                                    <span class="graduated-header-checkbox d-none">
                                                        <input type="checkbox" id="selectAllGraduated">
                                                    </span>
                                                </th>
                                                <th style="width: 25%;">Student Name</th>
                                                <th>Status</th>
                                                <th>Final Average</th>
                                                <th>Date Promoted</th>
                                                <th>Previous Class</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                // Combine students who are currently graduated or were graduated in previous enrollment
                                                $graduatedStudents = $activeStudents->filter(function ($student) {
                                                    return $student->current_enrollment_status === 'graduated' ||
                                                        $student->previous_enrollment_status === 'graduated';
                                                });
                                                $graduatedIndex = 1;
                                            @endphp
                                            @foreach ($graduatedStudents->sortBy(fn($s) => strtolower($s->student_lName . ' ' . $s->student_fName . ' ' . $s->student_mName)) as $student)
                                                @php
                                                    $eligibility = $student->promotion_eligibility;
                                                    $average = $eligibility['average'];

                                                    // Determine average color
                                                    $averageColor = 'text-muted';
                                                    if ($average) {
                                                        if ($average < 75) {
                                                            $averageColor = 'text-danger';
                                                        } elseif ($average >= 75 && $average <= 84) {
                                                            $averageColor = 'text-warning';
                                                        } elseif ($average >= 85) {
                                                            $averageColor = 'text-success';
                                                        }
                                                    }

                                                    // Get promotion/graduation date
                                                    $promotionDate =
                                                        $student->graduation_info['graduation_date'] ??
                                                        ($student->previous_enrollment_updated ??
                                                            ($student->re_enrollment_updated_date ?? now()));
                                                    $formattedPromotionDate = \Carbon\Carbon::parse(
                                                        $promotionDate,
                                                    )->format('M j, Y || g:i A');
                                                @endphp
                                                <tr data-gender="{{ $student->student_sex }}">
                                                    <td class="text-center">
                                                        <span class="graduated-row-number">{{ $graduatedIndex++ }}</span>
                                                        <span class="graduated-row-checkbox d-none">
                                                            <input type="checkbox" name="ungraduate_students[]"
                                                                value="{{ $student->id }}">
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <!-- Student Image -->
                                                            <div class="flex-shrink-0 me-3">
                                                                @if ($student->student_photo)
                                                                    <img src="{{ asset('public/uploads/' . $student->student_photo) }}"
                                                                        alt="{{ $student->student_fName }} {{ $student->student_lName }}"
                                                                        class="rounded-circle student-avatar"
                                                                        style="width: 40px; height: 40px; object-fit: cover;">
                                                                @else
                                                                    <img src="{{ asset('assetsDashboard/img/student_profile_pictures/student_default_profile.jpg') }}"
                                                                        alt="No Profile" width="40" height="40"
                                                                        class="rounded-circle student-avatar"
                                                                        style="object-fit: cover;">
                                                                @endif
                                                            </div>
                                                            <div class="flex-grow-1">
                                                                <div class="fw-semibold text-dark">
                                                                    {{ $student->student_lName }},
                                                                    {{ $student->student_fName }}
                                                                    @if ($student->student_mName)
                                                                        {{ substr($student->student_mName, 0, 1) }}.
                                                                    @endif
                                                                    @if ($student->student_extName)
                                                                        {{ $student->student_extName }}
                                                                    @endif

                                                                    <!-- Gender Icon -->
                                                                    @if ($student->student_sex == 'male')
                                                                        <i class="bx bx-male text-primary me-1 fs-6"
                                                                            title="Male"></i>
                                                                    @else
                                                                        <i class="bx bx-female text-pink me-1 fs-6"
                                                                            title="Female"></i>
                                                                    @endif
                                                                </div>
                                                                <div class="d-flex align-items-center mt-1">
                                                                    <code
                                                                        class="text-dark bg-light px-2 py-1 rounded">{{ $student->student_lrn }}</code>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="text-center">
                                                        <span class="badge bg-label-info text-white px-3 py-2">
                                                            <i class="bx bx-graduation me-1"></i>
                                                            GRADUATED
                                                        </span>
                                                    </td>
                                                    <td class="text-center">
                                                        @if ($average)
                                                            <span class="fw-bold {{ $averageColor }} fs-6">
                                                                {{ number_format($average) }}
                                                            </span>
                                                        @else
                                                            <span class="text-muted fst-italic">N/A</span>
                                                        @endif
                                                    </td>
                                                    <td class="text-center">
                                                        <div class="text-dark">{{ $formattedPromotionDate }}</div>
                                                    </td>
                                                    <td class="text-center">
                                                        <span class="text-dark">{{ ucfirst($gradeLevel) }} -
                                                            {{ $selectedSection }}</span>
                                                        <br>
                                                        <small class="text-muted">(Previous SY:
                                                            {{ $previousSchoolYear }})</small>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                @if ($graduatedStudents->count() == 0)
                                    <div class="text-center py-4">
                                        <i class="bx bx-graduation bx-lg text-muted mb-3"></i>
                                        <p class="text-muted">No students have graduated from this class yet.</p>
                                    </div>
                                @endif

                                <!-- Pagination for Graduated Students -->
                                <div class="d-flex justify-content-between align-items-center mt-3">
                                    <div class="d-flex gap-2 align-items-center">
                                        <select class="form-select form-select-sm" id="graduatedTableLength"
                                            style="width: auto;">
                                            <option value="5">5</option>
                                            <option value="10" selected>10</option>
                                            <option value="25">25</option>
                                            <option value="50">50</option>
                                        </select>
                                        <span class="text-muted">entries per page</span>
                                    </div>
                                    <div class="text-muted" id="graduatedTableInfo">
                                        Showing 0 to 0 of 0 entries
                                    </div>
                                    <nav aria-label="Graduated students pagination">
                                        <ul class="pagination justify-content-center mb-0" id="graduatedPagination">
                                            <!-- Pagination will be generated by JavaScript -->
                                        </ul>
                                    </nav>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Hidden merged inputs -->
            <div id="mergedStudentInputs"></div>
        </form>

        <hr class="my-5" />

    </div>
    <!-- Content wrapper -->

@endsection

@push('styles')
    <style>
        .text-pink {
            color: #e83e8c !important;
        }

        .pagination .page-link {
            color: #6c757d;
        }

        .pagination .page-item.active .page-link {
            background-color: #007bff;
            border-color: #007bff;
        }

        /* Student avatar styles */
        .student-avatar {
            border: 2px solid #228ffc;
        }

        .student-avatar-placeholder {
            border: 2px solid #228ffc;
        }

        /* Enhanced badge styles */
        .badge {
            font-size: 0.75em;
            font-weight: 600;
        }

        /* Average score indicators */
        .average-excellent {
            color: #28a745;
            font-weight: bold;
        }

        .average-average {
            color: #ffc107;
            font-weight: bold;
        }

        .average-poor {
            color: #dc3545;
            font-weight: bold;
        }

        /* Status badge enhancements */
        .bg-label-success {
            background-color: rgba(40, 167, 69, 0.1) !important;
            color: #28a745 !important;
            border: 1px solid rgba(40, 167, 69, 0.2);
        }

        .bg-label-dark {
            background-color: rgba(108, 117, 125, 0.1) !important;
            color: #6c757d !important;
            border: 1px solid rgba(108, 117, 125, 0.2);
        }

        .bg-label-warning {
            background-color: rgba(255, 193, 7, 0.1) !important;
            color: #856404 !important;
            border: 1px solid rgba(255, 193, 7, 0.2);
        }

        .bg-label-info {
            background-color: rgba(23, 162, 184, 0.1) !important;
            color: #0c5460 !important;
            border: 1px solid rgba(23, 162, 184, 0.2);
        }

        .bg-label-danger {
            background-color: rgba(220, 53, 69, 0.1) !important;
            color: #dc3545 !important;
            border: 1px solid rgba(220, 53, 69, 0.2);
        }

        .bg-label-primary {
            background-color: rgba(0, 123, 255, 0.1) !important;
            color: #007bff !important;
            border: 1px solid rgba(0, 123, 255, 0.2);
        }

        .bg-label-secondary {
            background-color: rgba(134, 142, 150, 0.1) !important;
            color: #868e96 !important;
            border: 1px solid rgba(134, 142, 150, 0.2);
        }

        .bg-label-light {
            background-color: rgba(248, 249, 250, 0.1) !important;
            color: #f8f9fa !important;
            border: 1px solid rgba(248, 249, 250, 0.2);
        }

        .bg-label-muted {
            background-color: rgba(173, 181, 189, 0.1) !important;
            color: #adb5bd !important;
            border: 1px solid rgba(173, 181, 189, 0.2);
        }

        .student-avatar {
            border: 2px solid #228ffc;
            background-color: #f8f9fa;
            /* Fallback background */
        }

        .student-avatar-placeholder {
            border: 2px solid #228ffc;
            background-color: #6c757d;
        }

        /* Ensure images don't break the layout if they fail to load */
        .student-avatar:before {
            content: "";
            display: block;
            padding-top: 100%;
            /* Maintain aspect ratio */
        }

        .unenroll-btn {
            border-width: 1px;
        }

        /* Loading state for unenroll button */
        .unenroll-btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        /* Highlight checked rows for both tables */
        #notReenrolledTable tbody tr.row-checked,
        #alreadyReenrolledTable tbody tr.row-checked {
            background-color: rgba(0, 123, 255, 0.1) !important;
            border-left: 3px solid #007bff;
        }

        /* Remove hover effects and animations */
        .table-hover tbody tr {
            transition: none;
        }

        .table-hover tbody tr:hover {
            background-color: inherit;
            transform: none;
        }

        .btn-outline-warning {
            border-color: #ffc107;
            color: #ffc107;
        }

        .btn-outline-warning:hover {
            background-color: #ffc107;
            border-color: #ffc107;
            color: #000;
        }
    </style>
@endpush

@push('scripts')
    <!-- Enhanced Scripts -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let selectionMode = 'none'; // 'none', 'promoted', 'retained'
            let unenrollMode = false; // Track unenroll selection mode
            let ungraduateMode = false; // Track ungraduate selection mode
            let currentPageNotReenrolled = 1;
            let currentPageAlreadyReenrolled = 1;
            let currentPageGraduated = 1;
            let rowsPerPageNotReenrolled = 10;
            let rowsPerPageAlreadyReenrolled = 10;
            let rowsPerPageGraduated = 10;

            // Initialize all tables and functionality for ALL grade levels
            initializePromotionFunctionality();

            function initializePromotionFunctionality() {
                // graduated accordion element - only exists for grade 6
                const graduatedAccordion = document.getElementById('graduatedCollapse');
                const graduatedHeading = document.getElementById('graduatedHeading');

                // Initialize graduated table if it exists (only for grade 6)
                const graduatedTable = document.getElementById('graduatedTable');
                if (graduatedTable) {
                    initializeGraduatedTable();
                }

                // Get the dropdown container
                const reEnrollDropdowns = document.getElementById('reEnrollDropdowns');
                const batchGradeSelect = document.getElementById('batchGrade');
                const batchSectionSelect = document.getElementById('batchSection');
                const globalSearch = document.getElementById('globalSearch');
                const submitButtonContainer = document.getElementById('submitButtonContainer');
                const submitPromotedBtn = document.getElementById('submitPromotedBtn');
                const submitRetainedBtn = document.getElementById('submitRetainedBtn');

                // Unenroll elements
                const unenrollInitialButtons = document.getElementById('unenrollInitialButtons');
                const unenrollSubmitContainer = document.getElementById('unenrollSubmitContainer');
                const unenrollStudentsBtn = document.getElementById('unenrollStudentsBtn');
                const submitUnenrollBtn = document.getElementById('submitUnenrollBtn');
                const cancelUnenrollBtn = document.getElementById('cancelUnenrollBtn');

                // Ungraduate elements
                const ungraduateInitialButtons = document.getElementById('ungraduateInitialButtons');
                const ungraduateSubmitContainer = document.getElementById('ungraduateSubmitContainer');
                const ungraduateStudentsBtn = document.getElementById('ungraduateStudentsBtn');
                const submitUngraduateBtn = document.getElementById('submitUngraduateBtn');
                const cancelUngraduateBtn = document.getElementById('cancelUngraduateBtn');

                // Accordion elements
                const notReenrolledAccordion = document.getElementById('notReenrolledCollapse');
                const alreadyReenrolledAccordion = document.getElementById('alreadyReenrolledCollapse');
                const notReenrolledHeading = document.getElementById('notReenrolledHeading');
                const alreadyReenrolledHeading = document.getElementById('alreadyReenrolledHeading');

                // Grade level mapping for promotion
                const gradePromotionMap = {
                    'kindergarten': ['kindergarten', 'grade1'],
                    'grade1': ['grade1', 'grade2'],
                    'grade2': ['grade2', 'grade3'],
                    'grade3': ['grade3', 'grade4'],
                    'grade4': ['grade4', 'grade5'],
                    'grade5': ['grade5', 'grade6'],
                    'grade6': ['grade6', 'graduated']
                };

                // Current grade level from hidden input
                const currentGradeLevel = document.querySelector('input[name="current_grade_level"]').value;

                // Initialize tables with pagination - start with checkboxes HIDDEN
                initializeTableWithPagination('notReenrolledTable', 'notReenrolledTableInfo',
                    'notReenrolledPagination', 'notReenrolledTableLength',
                    rowsPerPageNotReenrolled, currentPageNotReenrolled);

                initializeTableWithPagination('alreadyReenrolledTable', 'alreadyReenrolledTableInfo',
                    'alreadyReenrolledPagination', 'alreadyReenrolledTableLength',
                    rowsPerPageAlreadyReenrolled, currentPageAlreadyReenrolled);

                // Initialize graduated table
                function initializeGraduatedTable() {
                    initializeTableWithPagination('graduatedTable', 'graduatedTableInfo',
                        'graduatedPagination', 'graduatedTableLength',
                        rowsPerPageGraduated, currentPageGraduated);
                }

                // Enhanced Global Search functionality with debouncing
                let searchTimeout;
                globalSearch.addEventListener('input', function() {
                    const searchTerm = this.value.toLowerCase().trim();

                    // Clear previous timeout
                    clearTimeout(searchTimeout);

                    // Set new timeout with shorter delay for better responsiveness
                    searchTimeout = setTimeout(() => {
                        performSmartSearch(searchTerm);
                    }, 150); // Reduced from 300ms to 150ms for better responsiveness
                });

                // Also trigger search on paste and clear
                globalSearch.addEventListener('paste', function() {
                    setTimeout(() => {
                        const searchTerm = this.value.toLowerCase().trim();
                        performSmartSearch(searchTerm);
                    }, 100);
                });

                globalSearch.addEventListener('search', function() {
                    const searchTerm = this.value.toLowerCase().trim();
                    performSmartSearch(searchTerm);
                });

                // Smart Search Function
                function performSmartSearch(searchTerm) {
                    if (!searchTerm) {
                        // If search is empty, reset to default state
                        resetSearchState();
                        return;
                    }

                    // Search in all tables with enhanced accuracy
                    const notReenrolledResults = searchInTable('notReenrolledTable', searchTerm);
                    const alreadyReenrolledResults = searchInTable('alreadyReenrolledTable', searchTerm);

                    // Search in graduated table if it exists
                    let graduatedResults = 0;
                    const graduatedTable = document.getElementById('graduatedTable');
                    if (graduatedTable) {
                        graduatedResults = searchInTable('graduatedTable', searchTerm);
                    }

                    // Smart accordion management - allow multiple to open simultaneously
                    manageAccordions(notReenrolledResults, alreadyReenrolledResults, graduatedResults);

                    // Update pagination after filtering
                    updateTableWithPagination('notReenrolledTable', 'notReenrolledTableInfo',
                        'notReenrolledPagination', 'notReenrolledTableLength',
                        rowsPerPageNotReenrolled, 1);

                    updateTableWithPagination('alreadyReenrolledTable', 'alreadyReenrolledTableInfo',
                        'alreadyReenrolledPagination', 'alreadyReenrolledTableLength',
                        rowsPerPageAlreadyReenrolled, 1);

                    // Update graduated table pagination if it exists
                    if (graduatedTable) {
                        updateTableWithPagination('graduatedTable', 'graduatedTableInfo',
                            'graduatedPagination', 'graduatedTableLength',
                            rowsPerPageGraduated, 1);
                    }

                    // Update select all state after search
                    updateSelectAllCheckbox();
                    updateUnenrollSelectAllCheckbox();
                    updateUngraduateSelectAllCheckbox();

                    // Update counts with search results
                    updateSearchResultCounts(notReenrolledResults, alreadyReenrolledResults, graduatedResults);
                }

                // Enhanced Search in specific table with better accuracy
                function searchInTable(tableId, searchTerm) {
                    const table = document.getElementById(tableId);
                    const rows = table.querySelectorAll('tbody tr');
                    let visibleRows = 0;

                    rows.forEach(row => {
                        // Get student name from the second column (index 1)
                        const nameCell = row.querySelector('td:nth-child(2)');
                        if (!nameCell) {
                            row.style.display = 'none';
                            return;
                        }

                        // Extract student name text more accurately
                        const nameDiv = nameCell.querySelector('.fw-semibold.text-dark');
                        const studentName = nameDiv ? nameDiv.textContent.toLowerCase() : nameCell
                            .textContent
                            .toLowerCase();

                        // Extract LRN from the code element
                        const lrnElement = nameCell.querySelector('code');
                        const studentLRN = lrnElement ? lrnElement.textContent.toLowerCase() : '';

                        // Enhanced search matching - more accurate and reliable
                        const matchesSearch = doesStudentMatchSearch(studentName, studentLRN, searchTerm);

                        // Apply visibility based on search match
                        if (matchesSearch) {
                            row.style.display = '';
                            visibleRows++;
                        } else {
                            row.style.display = 'none';
                        }
                    });

                    return visibleRows;
                }

                // Enhanced search matching function
                function doesStudentMatchSearch(studentName, studentLRN, searchTerm) {
                    if (!searchTerm) return true;

                    // Clean and normalize the search term
                    const cleanSearchTerm = searchTerm.toLowerCase().trim();

                    // If search term is empty after cleaning, show all
                    if (!cleanSearchTerm) return true;

                    // Check if LRN matches exactly (partial matches allowed)
                    if (studentLRN && studentLRN.includes(cleanSearchTerm)) {
                        return true;
                    }

                    // Enhanced name matching
                    if (studentName) {
                        // Remove extra spaces and normalize the name
                        const cleanStudentName = studentName.replace(/\s+/g, ' ').trim();

                        // Split search term into words for more accurate matching
                        const searchWords = cleanSearchTerm.split(/\s+/).filter(word => word.length > 0);

                        // Check if all search words are found in the student name
                        const allWordsMatch = searchWords.every(word =>
                            cleanStudentName.includes(word)
                        );

                        if (allWordsMatch) {
                            return true;
                        }

                        // Additional matching: check for partial matches in different name formats
                        // Remove commas and check without formatting
                        const nameWithoutCommas = cleanStudentName.replace(/,/g, '');
                        if (nameWithoutCommas.includes(cleanSearchTerm)) {
                            return true;
                        }

                        // Check last name, first name format more accurately
                        const nameParts = cleanStudentName.split(',');
                        if (nameParts.length >= 2) {
                            const lastName = nameParts[0].trim();
                            const firstName = nameParts[1].trim();

                            if (lastName.includes(cleanSearchTerm) || firstName.includes(cleanSearchTerm)) {
                                return true;
                            }

                            // Check combined first and last name without comma
                            const combinedName = (firstName + ' ' + lastName).toLowerCase();
                            if (combinedName.includes(cleanSearchTerm)) {
                                return true;
                            }
                        }
                    }

                    return false;
                }

                // Enhanced Smart accordion management - allow multiple to be open simultaneously
                function manageAccordions(notReenrolledCount, alreadyReenrolledCount, graduatedCount = 0) {
                    // Use Bootstrap Collapse directly with proper initialization
                    const notReenrolledCollapse = new bootstrap.Collapse(notReenrolledAccordion, {
                        toggle: false
                    });
                    const alreadyReenrolledCollapse = new bootstrap.Collapse(alreadyReenrolledAccordion, {
                        toggle: false
                    });

                    // Remove the data-bs-parent attribute temporarily to allow independent operation
                    notReenrolledAccordion.removeAttribute('data-bs-parent');
                    alreadyReenrolledAccordion.removeAttribute('data-bs-parent');

                    // Handle graduated accordion if it exists
                    let graduatedCollapse = null;
                    let graduatedButton = null;
                    const graduatedSection = document.getElementById('graduatedStudentsSection');

                    if (graduatedSection && graduatedAccordion) {
                        graduatedCollapse = new bootstrap.Collapse(graduatedAccordion, {
                            toggle: false
                        });
                        graduatedAccordion.removeAttribute('data-bs-parent');
                        graduatedButton = graduatedHeading.querySelector('.accordion-button');
                    }

                    // Open accordions based on search results - multiple can be open simultaneously
                    const hasNotReenrolled = notReenrolledCount > 0;
                    const hasAlreadyReenrolled = alreadyReenrolledCount > 0;
                    const hasGraduated = graduatedCount > 0;

                    if (hasNotReenrolled) notReenrolledCollapse.show();
                    else notReenrolledCollapse.hide();

                    if (hasAlreadyReenrolled) alreadyReenrolledCollapse.show();
                    else alreadyReenrolledCollapse.hide();

                    if (graduatedCollapse) {
                        if (hasGraduated) graduatedCollapse.show();
                        else graduatedCollapse.hide();
                    }

                    // Update button states to show expanded/collapsed status
                    const notReenrolledButton = notReenrolledHeading.querySelector('.accordion-button');
                    const alreadyReenrolledButton = alreadyReenrolledHeading.querySelector('.accordion-button');

                    if (notReenrolledButton) {
                        if (hasNotReenrolled) notReenrolledButton.classList.remove('collapsed');
                        else notReenrolledButton.classList.add('collapsed');
                    }

                    if (alreadyReenrolledButton) {
                        if (hasAlreadyReenrolled) alreadyReenrolledButton.classList.remove('collapsed');
                        else alreadyReenrolledButton.classList.add('collapsed');
                    }

                    if (graduatedButton) {
                        if (hasGraduated) graduatedButton.classList.remove('collapsed');
                        else graduatedButton.classList.add('collapsed');
                    }

                    // Restore data-bs-parent after a short delay to maintain accordion behavior
                    setTimeout(() => {
                        notReenrolledAccordion.setAttribute('data-bs-parent', '#studentAccordion');
                        alreadyReenrolledAccordion.setAttribute('data-bs-parent', '#studentAccordion');
                        if (graduatedAccordion) {
                            graduatedAccordion.setAttribute('data-bs-parent', '#studentAccordion');
                        }
                    }, 500);
                }

                // Reset search state
                function resetSearchState() {
                    // Show all rows in all tables
                    document.querySelectorAll('#notReenrolledTable tbody tr, #alreadyReenrolledTable tbody tr')
                        .forEach(
                            row => {
                                row.style.display = '';
                            });

                    // Show graduated table rows if it exists
                    const graduatedTable = document.getElementById('graduatedTable');
                    if (graduatedTable) {
                        document.querySelectorAll('#graduatedTable tbody tr').forEach(row => {
                            row.style.display = '';
                        });
                    }

                    // Reset to default accordion state (first one open)
                    const notReenrolledCollapse = new bootstrap.Collapse(notReenrolledAccordion, {
                        toggle: false
                    });
                    const alreadyReenrolledCollapse = new bootstrap.Collapse(alreadyReenrolledAccordion, {
                        toggle: false
                    });

                    notReenrolledCollapse.show();
                    alreadyReenrolledCollapse.hide();

                    // Hide graduated accordion by default
                    const graduatedSection = document.getElementById('graduatedStudentsSection');
                    if (graduatedSection && graduatedAccordion) {
                        const graduatedCollapse = new bootstrap.Collapse(graduatedAccordion, {
                            toggle: false
                        });
                        graduatedCollapse.hide();
                        const graduatedButton = graduatedHeading.querySelector('.accordion-button');
                        if (graduatedButton) graduatedButton.classList.add('collapsed');
                    }

                    // Update pagination for all tables
                    updateTableWithPagination('notReenrolledTable', 'notReenrolledTableInfo',
                        'notReenrolledPagination', 'notReenrolledTableLength',
                        rowsPerPageNotReenrolled, 1);

                    updateTableWithPagination('alreadyReenrolledTable', 'alreadyReenrolledTableInfo',
                        'alreadyReenrolledPagination', 'alreadyReenrolledTableLength',
                        rowsPerPageAlreadyReenrolled, 1);

                    // Update graduated table pagination if it exists
                    if (graduatedTable) {
                        updateTableWithPagination('graduatedTable', 'graduatedTableInfo',
                            'graduatedPagination', 'graduatedTableLength',
                            rowsPerPageGraduated, 1);
                    }

                    // Reset counts
                    updateSearchResultCounts();
                }

                // Update search result counts
                function updateSearchResultCounts(notReenrolledCount = null, alreadyReenrolledCount = null,
                    graduatedCount = null) {
                    const searchTerm = globalSearch.value.toLowerCase().trim();

                    if (!searchTerm) {
                        // Reset to original counts - FIXED: Use proper PHP count for graduated students
                        const originalNotReenrolled =
                            {{ $activeStudents->where('current_enrollment_status', 'not_enrolled')->count() }};
                        const originalAlreadyReenrolled =
                            {{ $activeStudents->where('current_enrollment_status', 'enrolled')->count() }};
                        const originalGraduated =
                            {{ $activeStudents->filter(function ($student) {
                                    return $student->current_enrollment_status === 'graduated' ||
                                        $student->previous_enrollment_status === 'graduated';
                                })->count() }};

                        document.getElementById('notReenrolledCount').textContent =
                            `${originalNotReenrolled} students`;
                        document.getElementById('alreadyReenrolledCount').textContent =
                            `${originalAlreadyReenrolled} students`;

                        // Update graduated count if the element exists
                        const graduatedCountElement = document.getElementById('graduatedCount');
                        if (graduatedCountElement) {
                            graduatedCountElement.textContent = `${originalGraduated} students`;
                        }
                        return;
                    }

                    // Update with search result counts
                    if (notReenrolledCount !== null) {
                        document.getElementById('notReenrolledCount').textContent =
                        `${notReenrolledCount} students`;
                    }

                    if (alreadyReenrolledCount !== null) {
                        document.getElementById('alreadyReenrolledCount').textContent =
                            `${alreadyReenrolledCount} students`;
                    }

                    // Update graduated count if the element exists
                    if (graduatedCount !== null) {
                        const graduatedCountElement = document.getElementById('graduatedCount');
                        if (graduatedCountElement) {
                            graduatedCountElement.textContent = `${graduatedCount} students`;
                        }
                    }
                }

                // Quick Selection Buttons - FIXED: Now works for ALL grade levels
                document.getElementById('reEnrollPromotedBtn').addEventListener('click', function() {
                    if (selectionMode === 'promoted') {
                        cancelSelectionMode();
                    } else {
                        selectionMode = 'promoted';
                        toggleCheckboxMode(true);
                        showPromotedStudentsOnly();
                        showReEnrollDropdowns();
                        showSubmitButton('promoted');
                        updateGradeDropdownForPromoted();
                        updateButtonStates();
                        updateSelectedCount();
                        updateSelectAllCheckbox();
                    }
                });

                document.getElementById('reEnrollRetainedBtn').addEventListener('click', function() {
                    if (selectionMode === 'retained') {
                        cancelSelectionMode();
                    } else {
                        selectionMode = 'retained';
                        toggleCheckboxMode(true);
                        showRetainedStudentsOnly();
                        showReEnrollDropdowns();
                        showSubmitButton('retained');
                        updateGradeDropdownForRetained();
                        updateButtonStates();
                        updateSelectedCount();
                        updateSelectAllCheckbox();
                    }
                });

                document.getElementById('cancelSelectionBtn').addEventListener('click', function() {
                    cancelSelectionMode();
                });

                // Unenroll functionality - FIXED: Now works for ALL grade levels
                document.getElementById('unenrollStudentsBtn').addEventListener('click', function() {
                    if (unenrollMode) {
                        cancelUnenrollMode();
                    } else {
                        unenrollMode = true;
                        toggleUnenrollCheckboxMode(true);
                        updateUnenrollButtonStates();
                        updateUnenrollSelectedCount();
                        updateUnenrollSelectAllCheckbox();
                    }
                });

                document.getElementById('cancelUnenrollBtn').addEventListener('click', function() {
                    cancelUnenrollMode();
                });

                document.getElementById('submitUnenrollBtn').addEventListener('click', function() {
                    confirmUnenrollStudents();
                });

                // Ungraduate functionality - Only for Grade 6
                if (document.getElementById('ungraduateStudentsBtn')) {
                    document.getElementById('ungraduateStudentsBtn').addEventListener('click', function() {
                        if (ungraduateMode) {
                            cancelUngraduateMode();
                        } else {
                            ungraduateMode = true;
                            toggleUngraduateCheckboxMode(true);
                            updateUngraduateButtonStates();
                            updateUngraduateSelectedCount();
                            updateUngraduateSelectAllCheckbox();
                        }
                    });

                    document.getElementById('cancelUngraduateBtn').addEventListener('click', function() {
                        cancelUngraduateMode();
                    });

                    document.getElementById('submitUngraduateBtn').addEventListener('click', function() {
                        confirmUngraduateStudents();
                    });
                }

                // Update grade dropdown for promoted students
                function updateGradeDropdownForPromoted() {
                    const promotionOptions = gradePromotionMap[currentGradeLevel];

                    if (promotionOptions && promotionOptions.length > 1) {
                        // For promoted students, show only the next grade level (+1)
                        const nextGradeLevel = promotionOptions[1]; // The second option is the promoted level

                        batchGradeSelect.innerHTML = '';

                        const option = document.createElement('option');
                        option.value = nextGradeLevel;

                        // Format the display text
                        let displayText = nextGradeLevel === 'graduated' ?
                            'Graduated' :
                            nextGradeLevel.charAt(0).toUpperCase() + nextGradeLevel.slice(1).replace('grade',
                                ' Grade ');

                        option.textContent = `${displayText} (promoted)`;
                        batchGradeSelect.appendChild(option);

                        // Auto-select the promoted option
                        batchGradeSelect.value = nextGradeLevel;

                        // For Grade 6 graduation, hide section dropdown
                        updateSectionDropdownState();
                    }
                }

                // Update grade dropdown for retained students
                function updateGradeDropdownForRetained() {
                    const promotionOptions = gradePromotionMap[currentGradeLevel];

                    if (promotionOptions && promotionOptions.length > 0) {
                        // For retained students, show only the current grade level
                        batchGradeSelect.innerHTML = '';

                        const option = document.createElement('option');
                        option.value = currentGradeLevel;

                        // Format the display text
                        let displayText = currentGradeLevel === 'graduated' ?
                            'Graduated' :
                            currentGradeLevel.charAt(0).toUpperCase() + currentGradeLevel.slice(1).replace('grade',
                                ' Grade ');

                        option.textContent = `${displayText} (returnee)`;
                        batchGradeSelect.appendChild(option);

                        // Auto-select the retained option
                        batchGradeSelect.value = currentGradeLevel;

                        // For Grade 6 graduation, hide section dropdown
                        updateSectionDropdownState();
                    }
                }

                // Handle grade selection change to update section dropdown
                batchGradeSelect.addEventListener('change', function() {
                    updateSectionDropdownState();
                });

                // Update section dropdown state based on grade selection
                function updateSectionDropdownState() {
                    if (batchGradeSelect.value === 'graduated') {
                        batchSectionSelect.disabled = true;
                        batchSectionSelect.required = false;
                        batchSectionSelect.value = ''; // Clear selection
                        batchSectionSelect.closest('.form-select').classList.add('d-none');
                    } else {
                        batchSectionSelect.disabled = false;
                        batchSectionSelect.required = true;
                        batchSectionSelect.closest('.form-select').classList.remove('d-none');
                    }
                }

                // Show re-enroll dropdowns
                function showReEnrollDropdowns() {
                    reEnrollDropdowns.classList.remove('d-none');

                    // Initialize section dropdown state
                    updateSectionDropdownState();
                }

                // Hide re-enroll dropdowns
                function hideReEnrollDropdowns() {
                    reEnrollDropdowns.classList.add('d-none');
                }

                // Show submit button based on selection mode
                function showSubmitButton(mode) {
                    submitButtonContainer.classList.remove('d-none');
                    if (mode === 'promoted') {
                        submitPromotedBtn.classList.remove('d-none');
                        submitRetainedBtn.classList.add('d-none');
                    } else if (mode === 'retained') {
                        submitPromotedBtn.classList.add('d-none');
                        submitRetainedBtn.classList.remove('d-none');
                    }
                }

                // Hide submit button
                function hideSubmitButton() {
                    submitButtonContainer.classList.add('d-none');
                    submitPromotedBtn.classList.add('d-none');
                    submitRetainedBtn.classList.add('d-none');
                }

                // Show only promoted students
                function showPromotedStudentsOnly() {
                    const rows = document.querySelectorAll('#notReenrolledTable tbody tr');
                    rows.forEach(row => {
                        if (row.getAttribute('data-eligible') === 'true') {
                            row.style.display = '';
                        } else {
                            row.style.display = 'none';
                        }
                    });
                    updateTableWithPagination('notReenrolledTable', 'notReenrolledTableInfo',
                        'notReenrolledPagination', 'notReenrolledTableLength',
                        rowsPerPageNotReenrolled, 1);
                }

                // Show only retained students
                function showRetainedStudentsOnly() {
                    const rows = document.querySelectorAll('#notReenrolledTable tbody tr');
                    rows.forEach(row => {
                        if (row.getAttribute('data-eligible') === 'false') {
                            row.style.display = '';
                        } else {
                            row.style.display = 'none';
                        }
                    });
                    updateTableWithPagination('notReenrolledTable', 'notReenrolledTableInfo',
                        'notReenrolledPagination', 'notReenrolledTableLength',
                        rowsPerPageNotReenrolled, 1);
                }

                // Show all students
                function showAllStudents() {
                    const rows = document.querySelectorAll('#notReenrolledTable tbody tr');
                    rows.forEach(row => {
                        row.style.display = '';
                    });
                    updateTableWithPagination('notReenrolledTable', 'notReenrolledTableInfo',
                        'notReenrolledPagination', 'notReenrolledTableLength',
                        rowsPerPageNotReenrolled, 1);
                }

                // Table initialization with pagination
                function initializeTableWithPagination(tableId, infoId, paginationId, lengthId, rowsPerPage,
                    currentPage) {
                    const lengthSelect = document.getElementById(lengthId);
                    lengthSelect.addEventListener('change', function() {
                        if (tableId === 'notReenrolledTable') {
                            rowsPerPageNotReenrolled = parseInt(this.value);
                            updateTableWithPagination(tableId, infoId, paginationId, lengthId,
                                rowsPerPageNotReenrolled, 1);
                        } else if (tableId === 'alreadyReenrolledTable') {
                            rowsPerPageAlreadyReenrolled = parseInt(this.value);
                            updateTableWithPagination(tableId, infoId, paginationId, lengthId,
                                rowsPerPageAlreadyReenrolled, 1);
                        } else if (tableId === 'graduatedTable') {
                            rowsPerPageGraduated = parseInt(this.value);
                            updateTableWithPagination(tableId, infoId, paginationId, lengthId,
                                rowsPerPageGraduated, 1);
                        }
                        updateSelectAllCheckbox();
                        updateUnenrollSelectAllCheckbox();
                        updateUngraduateSelectAllCheckbox();
                    });

                    updateTableWithPagination(tableId, infoId, paginationId, lengthId, rowsPerPage, currentPage);
                }

                // Update table display with pagination
                function updateTableWithPagination(tableId, infoId, paginationId, lengthId, rowsPerPage,
                    currentPage) {
                    const table = document.getElementById(tableId);
                    const rows = Array.from(table.querySelectorAll('tbody tr'));

                    // Filter rows that are not hidden by other filters (like promoted/retained mode)
                    const filteredRows = rows.filter(row => {
                        // Check if row should be visible based on current filters
                        if (tableId === 'notReenrolledTable' && selectionMode !== 'none') {
                            if (selectionMode === 'promoted') {
                                return row.getAttribute('data-eligible') === 'true';
                            } else if (selectionMode === 'retained') {
                                return row.getAttribute('data-eligible') === 'false';
                            }
                        }
                        return row.style.display !==
                        'none'; // Only include rows that are not hidden by search
                    });

                    const totalRows = filteredRows.length;
                    const totalPages = Math.ceil(totalRows / rowsPerPage);

                    // Ensure current page is valid
                    if (currentPage > totalPages) currentPage = totalPages;
                    if (currentPage < 1 && totalPages > 0) currentPage = 1;
                    if (totalRows === 0) currentPage = 1;

                    if (tableId === 'notReenrolledTable') {
                        currentPageNotReenrolled = currentPage;
                    } else if (tableId === 'alreadyReenrolledTable') {
                        currentPageAlreadyReenrolled = currentPage;
                    } else if (tableId === 'graduatedTable') {
                        currentPageGraduated = currentPage;
                    }

                    // Calculate start and end indices
                    const startIndex = (currentPage - 1) * rowsPerPage;
                    const endIndex = Math.min(startIndex + rowsPerPage, totalRows);

                    // Hide all rows first
                    rows.forEach(row => {
                        // Only hide if not already hidden by search
                        if (row.style.display !== 'none') {
                            row.style.display = 'none';
                        }
                    });

                    // Show rows for current page from filtered rows
                    for (let i = startIndex; i < endIndex; i++) {
                        if (filteredRows[i]) {
                            filteredRows[i].style.display = '';
                        }
                    }

                    // Update table info
                    const startRow = totalRows === 0 ? 0 : startIndex + 1;
                    const endRow = Math.min(endIndex, totalRows);
                    document.getElementById(infoId).textContent =
                        `Showing ${startRow} to ${endRow} of ${totalRows} entries`;

                    // Generate pagination
                    generatePagination(paginationId, currentPage, totalPages, tableId);

                    // Update select all state after pagination
                    updateSelectAllCheckbox();
                    updateUnenrollSelectAllCheckbox();
                    updateUngraduateSelectAllCheckbox();
                }

                // Generate pagination links
                function generatePagination(paginationId, currentPage, totalPages, tableId) {
                    const pagination = document.getElementById(paginationId);
                    pagination.innerHTML = '';

                    if (totalPages <= 1) return;

                    // Previous button
                    const prevLi = document.createElement('li');
                    prevLi.className = `page-item ${currentPage === 1 ? 'disabled' : ''}`;
                    prevLi.innerHTML =
                        `<a class="page-link" href="#" data-page="${currentPage - 1}"><i class="bx bx-chevron-left"></i></a>`;
                    pagination.appendChild(prevLi);

                    // Page numbers
                    const startPage = Math.max(1, currentPage - 2);
                    const endPage = Math.min(totalPages, currentPage + 2);

                    for (let i = startPage; i <= endPage; i++) {
                        const pageLi = document.createElement('li');
                        pageLi.className = `page-item ${i === currentPage ? 'active' : ''}`;
                        pageLi.innerHTML = `<a class="page-link" href="#" data-page="${i}">${i}</a>`;
                        pagination.appendChild(pageLi);
                    }

                    // Next button
                    const nextLi = document.createElement('li');
                    nextLi.className = `page-item ${currentPage === totalPages ? 'disabled' : ''}`;
                    nextLi.innerHTML =
                        `<a class="page-link" href="#" data-page="${currentPage + 1}"><i class="bx bx-chevron-right"></i></a>`;
                    pagination.appendChild(nextLi);

                    // Add click event listeners
                    pagination.querySelectorAll('.page-link').forEach(link => {
                        link.addEventListener('click', function(e) {
                            e.preventDefault();
                            const page = parseInt(this.getAttribute('data-page'));
                            if (tableId === 'notReenrolledTable') {
                                updateTableWithPagination(tableId, 'notReenrolledTableInfo',
                                    paginationId, 'notReenrolledTableLength',
                                    rowsPerPageNotReenrolled, page);
                            } else if (tableId === 'alreadyReenrolledTable') {
                                updateTableWithPagination(tableId, 'alreadyReenrolledTableInfo',
                                    paginationId, 'alreadyReenrolledTableLength',
                                    rowsPerPageAlreadyReenrolled, page);
                            } else if (tableId === 'graduatedTable') {
                                updateTableWithPagination(tableId, 'graduatedTableInfo',
                                    paginationId, 'graduatedTableLength',
                                    rowsPerPageGraduated, page);
                            }
                        });
                    });
                }

                // Select All functionality - ONLY when in selection mode
                document.getElementById("selectAllNotReenrolled").addEventListener("click", function() {
                    // Only work when in selection mode
                    if (selectionMode === 'none') return;

                    const isChecked = this.checked;
                    const visibleCheckboxes = getVisibleCheckboxes();

                    visibleCheckboxes.forEach(cb => {
                        cb.checked = isChecked;
                        updateRowHighlight(cb); // Update row highlight when select all changes
                    });
                    updateSelectedCount();
                    updateSelectionSummary();
                });

                // Unenroll Select All functionality
                document.getElementById("selectAllAlreadyReenrolled").addEventListener("click", function() {
                    // Only work when in unenroll mode
                    if (!unenrollMode) return;

                    const isChecked = this.checked;
                    const visibleCheckboxes = getVisibleUnenrollCheckboxes();

                    visibleCheckboxes.forEach(cb => {
                        cb.checked = isChecked;
                        updateUnenrollRowHighlight(cb);
                    });
                    updateUnenrollSelectedCount();
                });

                // Ungraduate Select All functionality
                if (document.getElementById("selectAllGraduated")) {
                    document.getElementById("selectAllGraduated").addEventListener("click", function() {
                        // Only work when in ungraduate mode
                        if (!ungraduateMode) return;

                        const isChecked = this.checked;
                        const visibleCheckboxes = getVisibleUngraduateCheckboxes();

                        visibleCheckboxes.forEach(cb => {
                            cb.checked = isChecked;
                            updateUngraduateRowHighlight(cb);
                        });
                        updateUngraduateSelectedCount();
                    });
                }

                // Get visible checkboxes (considering current filters and pagination)
                function getVisibleCheckboxes() {
                    return Array.from(document.querySelectorAll(
                        '#notReenrolledTable input[name="selected_students[]"]:not(:disabled)'
                    )).filter(cb => {
                        const row = cb.closest('tr');
                        return row && row.style.display !== 'none';
                    });
                }

                // Get visible unenroll checkboxes
                function getVisibleUnenrollCheckboxes() {
                    return Array.from(document.querySelectorAll(
                        '#alreadyReenrolledTable input[name="unenroll_students[]"]:not(:disabled)'
                    )).filter(cb => {
                        const row = cb.closest('tr');
                        return row && row.style.display !== 'none';
                    });
                }

                // Get visible ungraduate checkboxes
                function getVisibleUngraduateCheckboxes() {
                    return Array.from(document.querySelectorAll(
                        '#graduatedTable input[name="ungraduate_students[]"]:not(:disabled)'
                    )).filter(cb => {
                        const row = cb.closest('tr');
                        return row && row.style.display !== 'none';
                    });
                }

                // NEW: Update row highlight based on checkbox state
                function updateRowHighlight(checkbox) {
                    const row = checkbox.closest('tr');
                    if (checkbox.checked) {
                        row.classList.add('row-checked');
                    } else {
                        row.classList.remove('row-checked');
                    }
                }

                // Update unenroll row highlight
                function updateUnenrollRowHighlight(checkbox) {
                    const row = checkbox.closest('tr');
                    if (checkbox.checked) {
                        row.classList.add('row-checked');
                    } else {
                        row.classList.remove('row-checked');
                    }
                }

                // Update ungraduate row highlight
                function updateUngraduateRowHighlight(checkbox) {
                    const row = checkbox.closest('tr');
                    if (checkbox.checked) {
                        row.classList.add('row-checked');
                    } else {
                        row.classList.remove('row-checked');
                    }
                }

                // Individual checkbox change - ONLY when in selection mode
                document.querySelectorAll('input[name="selected_students[]"]').forEach(cb => {
                    cb.addEventListener('change', function() {
                        // Only process changes when in selection mode
                        if (selectionMode === 'none') {
                            this.checked = false; // Uncheck if not in selection mode
                            updateRowHighlight(this);
                            return;
                        }

                        updateRowHighlight(this); // Update row highlight when checkbox changes
                        updateSelectedCount();
                        updateSelectionSummary();
                        updateSelectAllCheckbox();
                    });
                });

                // Individual unenroll checkbox change
                document.querySelectorAll('input[name="unenroll_students[]"]').forEach(cb => {
                    cb.addEventListener('change', function() {
                        // Only process changes when in unenroll mode
                        if (!unenrollMode) {
                            this.checked = false; // Uncheck if not in unenroll mode
                            updateUnenrollRowHighlight(this);
                            return;
                        }

                        updateUnenrollRowHighlight(this);
                        updateUnenrollSelectedCount();
                        updateUnenrollSelectAllCheckbox();
                    });
                });

                // Individual ungraduate checkbox change
                if (document.querySelectorAll('input[name="ungraduate_students[]"]').length > 0) {
                    document.querySelectorAll('input[name="ungraduate_students[]"]').forEach(cb => {
                        cb.addEventListener('change', function() {
                            // Only process changes when in ungraduate mode
                            if (!ungraduateMode) {
                                this.checked = false; // Uncheck if not in ungraduate mode
                                updateUngraduateRowHighlight(this);
                                return;
                            }

                            updateUngraduateRowHighlight(this);
                            updateUngraduateSelectedCount();
                            updateUngraduateSelectAllCheckbox();
                        });
                    });
                }

                // Toggle between number and checkbox display
                function toggleCheckboxMode(showCheckboxes) {
                    const header = document.getElementById('notReenrolledHeader');
                    const headerNumber = header.querySelector('.header-number');
                    const headerCheckbox = header.querySelector('.header-checkbox');

                    if (showCheckboxes) {
                        headerNumber.classList.add('d-none');
                        headerCheckbox.classList.remove('d-none');
                    } else {
                        headerNumber.classList.remove('d-none');
                        headerCheckbox.classList.add('d-none');
                    }

                    // Toggle row display
                    document.querySelectorAll('#notReenrolledTable tbody tr').forEach(row => {
                        const rowNumber = row.querySelector('.row-number');
                        const rowCheckbox = row.querySelector('.row-checkbox');

                        if (showCheckboxes) {
                            rowNumber.classList.add('d-none');
                            rowCheckbox.classList.remove('d-none');
                        } else {
                            rowNumber.classList.remove('d-none');
                            rowCheckbox.classList.add('d-none');
                            // Remove highlighting when exiting checkbox mode
                            row.classList.remove('row-checked');
                        }
                    });

                    // Disable/enable checkboxes based on mode
                    const allCheckboxes = document.querySelectorAll('input[name="selected_students[]"]');
                    allCheckboxes.forEach(cb => {
                        if (!showCheckboxes) {
                            cb.checked = false; // Uncheck all when leaving selection mode
                            updateRowHighlight(cb);
                        }
                    });
                }

                // Toggle unenroll checkbox mode
                function toggleUnenrollCheckboxMode(showCheckboxes) {
                    const header = document.getElementById('alreadyReenrolledHeader');
                    const headerNumber = header.querySelector('.already-header-number');
                    const headerCheckbox = header.querySelector('.already-header-checkbox');

                    if (showCheckboxes) {
                        headerNumber.classList.add('d-none');
                        headerCheckbox.classList.remove('d-none');
                        unenrollInitialButtons.classList.add('d-none');
                        unenrollSubmitContainer.classList.remove('d-none');
                    } else {
                        headerNumber.classList.remove('d-none');
                        headerCheckbox.classList.add('d-none');
                        unenrollInitialButtons.classList.remove('d-none');
                        unenrollSubmitContainer.classList.add('d-none');
                    }

                    // Toggle row display
                    document.querySelectorAll('#alreadyReenrolledTable tbody tr').forEach(row => {
                        const rowNumber = row.querySelector('.already-row-number');
                        const rowCheckbox = row.querySelector('.already-row-checkbox');

                        if (showCheckboxes) {
                            rowNumber.classList.add('d-none');
                            rowCheckbox.classList.remove('d-none');
                        } else {
                            rowNumber.classList.remove('d-none');
                            rowCheckbox.classList.add('d-none');
                            // Remove highlighting when exiting checkbox mode
                            row.classList.remove('row-checked');
                        }
                    });

                    // Disable/enable checkboxes based on mode
                    const allUnenrollCheckboxes = document.querySelectorAll('input[name="unenroll_students[]"]');
                    allUnenrollCheckboxes.forEach(cb => {
                        if (!showCheckboxes) {
                            cb.checked = false; // Uncheck all when leaving unenroll mode
                            updateUnenrollRowHighlight(cb);
                        }
                    });
                }

                // Toggle ungraduate checkbox mode
                function toggleUngraduateCheckboxMode(showCheckboxes) {
                    const header = document.getElementById('graduatedHeader');
                    const headerNumber = header.querySelector('.graduated-header-number');
                    const headerCheckbox = header.querySelector('.graduated-header-checkbox');

                    if (showCheckboxes) {
                        headerNumber.classList.add('d-none');
                        headerCheckbox.classList.remove('d-none');
                        ungraduateInitialButtons.classList.add('d-none');
                        ungraduateSubmitContainer.classList.remove('d-none');
                    } else {
                        headerNumber.classList.remove('d-none');
                        headerCheckbox.classList.add('d-none');
                        ungraduateInitialButtons.classList.remove('d-none');
                        ungraduateSubmitContainer.classList.add('d-none');
                    }

                    // Toggle row display
                    document.querySelectorAll('#graduatedTable tbody tr').forEach(row => {
                        const rowNumber = row.querySelector('.graduated-row-number');
                        const rowCheckbox = row.querySelector('.graduated-row-checkbox');

                        if (showCheckboxes) {
                            rowNumber.classList.add('d-none');
                            rowCheckbox.classList.remove('d-none');
                        } else {
                            rowNumber.classList.remove('d-none');
                            rowCheckbox.classList.add('d-none');
                            // Remove highlighting when exiting checkbox mode
                            row.classList.remove('row-checked');
                        }
                    });

                    // Disable/enable checkboxes based on mode
                    const allUngraduateCheckboxes = document.querySelectorAll(
                    'input[name="ungraduate_students[]"]');
                    allUngraduateCheckboxes.forEach(cb => {
                        if (!showCheckboxes) {
                            cb.checked = false; // Uncheck all when leaving ungraduate mode
                            updateUngraduateRowHighlight(cb);
                        }
                    });
                }

                // Cancel selection mode - COMPLETELY reset everything
                function cancelSelectionMode() {
                    selectionMode = 'none';
                    toggleCheckboxMode(false);
                    showAllStudents();
                    hideReEnrollDropdowns();
                    hideSubmitButton();
                    clearAllSelections();
                    updateButtonStates();
                    updateSelectedCount();

                    // Reset grade dropdown to default state
                    resetGradeDropdown();

                    // Force remove all highlighting from ALL rows
                    document.querySelectorAll('#notReenrolledTable tbody tr').forEach(row => {
                        row.classList.remove('row-checked');
                        row.style.backgroundColor = ''; // Remove any inline background color
                    });

                    // Reset search if any
                    if (globalSearch.value) {
                        performSmartSearch(globalSearch.value);
                    }
                }

                // Cancel unenroll mode
                function cancelUnenrollMode() {
                    unenrollMode = false;
                    toggleUnenrollCheckboxMode(false);
                    clearAllUnenrollSelections();
                    updateUnenrollButtonStates();
                    updateUnenrollSelectedCount();

                    // Reset search if any
                    if (globalSearch.value) {
                        performSmartSearch(globalSearch.value);
                    }
                }

                // Cancel ungraduate mode
                function cancelUngraduateMode() {
                    ungraduateMode = false;
                    toggleUngraduateCheckboxMode(false);
                    clearAllUngraduateSelections();
                    updateUngraduateButtonStates();
                    updateUngraduateSelectedCount();

                    // Reset search if any
                    if (globalSearch.value) {
                        performSmartSearch(globalSearch.value);
                    }
                }

                // Reset grade dropdown to default state
                function resetGradeDropdown() {
                    batchGradeSelect.innerHTML = '<option value="" disabled selected>Grade</option>';

                    const promotionOptions = gradePromotionMap[currentGradeLevel];
                    if (promotionOptions) {
                        promotionOptions.forEach(level => {
                            const option = document.createElement('option');
                            option.value = level;

                            let displayText = level === 'graduated' ?
                                'Graduated' :
                                level.charAt(0).toUpperCase() + level.slice(1).replace('grade', ' Grade ');

                            let label = level === currentGradeLevel ? '(returnee)' : '(promoted)';
                            option.textContent = `${displayText} ${label}`;
                            batchGradeSelect.appendChild(option);
                        });
                    }

                    // Reset section dropdown
                    batchSectionSelect.disabled = false;
                    batchSectionSelect.required = true;
                    batchSectionSelect.closest('.form-select').classList.remove('d-none');
                }

                // Clear all selections
                function clearAllSelections() {
                    document.querySelectorAll('input[name="selected_students[]"]').forEach(cb => {
                        cb.checked = false;
                        updateRowHighlight(cb); // Update row highlight when clearing selections
                    });
                    document.getElementById('selectAllNotReenrolled').checked = false;
                    updateSelectedCount();
                    updateSelectionSummary();
                }

                // Clear all unenroll selections
                function clearAllUnenrollSelections() {
                    document.querySelectorAll('input[name="unenroll_students[]"]').forEach(cb => {
                        cb.checked = false;
                        updateUnenrollRowHighlight(cb);
                    });
                    document.getElementById('selectAllAlreadyReenrolled').checked = false;
                    updateUnenrollSelectedCount();
                }

                // Clear all ungraduate selections
                function clearAllUngraduateSelections() {
                    if (document.querySelectorAll('input[name="ungraduate_students[]"]').length > 0) {
                        document.querySelectorAll('input[name="ungraduate_students[]"]').forEach(cb => {
                            cb.checked = false;
                            updateUngraduateRowHighlight(cb);
                        });
                        document.getElementById('selectAllGraduated').checked = false;
                        updateUngraduateSelectedCount();
                    }
                }

                // Update select all checkbox
                function updateSelectAllCheckbox() {
                    const visibleCheckboxes = getVisibleCheckboxes();
                    const checkedCheckboxes = visibleCheckboxes.filter(cb => cb.checked);

                    const selectAll = document.getElementById('selectAllNotReenrolled');
                    if (visibleCheckboxes.length === 0) {
                        selectAll.checked = false;
                        selectAll.indeterminate = false;
                        selectAll.disabled = true;
                    } else {
                        selectAll.disabled = false;
                        selectAll.checked = visibleCheckboxes.length > 0 &&
                            checkedCheckboxes.length === visibleCheckboxes.length;
                        selectAll.indeterminate = checkedCheckboxes.length > 0 &&
                            checkedCheckboxes.length < visibleCheckboxes.length;
                    }
                }

                // Update unenroll select all checkbox
                function updateUnenrollSelectAllCheckbox() {
                    const visibleCheckboxes = getVisibleUnenrollCheckboxes();
                    const checkedCheckboxes = visibleCheckboxes.filter(cb => cb.checked);

                    const selectAll = document.getElementById('selectAllAlreadyReenrolled');
                    if (visibleCheckboxes.length === 0) {
                        selectAll.checked = false;
                        selectAll.indeterminate = false;
                        selectAll.disabled = true;
                    } else {
                        selectAll.disabled = false;
                        selectAll.checked = visibleCheckboxes.length > 0 &&
                            checkedCheckboxes.length === visibleCheckboxes.length;
                        selectAll.indeterminate = checkedCheckboxes.length > 0 &&
                            checkedCheckboxes.length < visibleCheckboxes.length;
                    }
                }

                // Update ungraduate select all checkbox
                function updateUngraduateSelectAllCheckbox() {
                    const selectAll = document.getElementById('selectAllGraduated');
                    if (!selectAll) return;

                    const visibleCheckboxes = getVisibleUngraduateCheckboxes();
                    const checkedCheckboxes = visibleCheckboxes.filter(cb => cb.checked);

                    if (visibleCheckboxes.length === 0) {
                        selectAll.checked = false;
                        selectAll.indeterminate = false;
                        selectAll.disabled = true;
                    } else {
                        selectAll.disabled = false;
                        selectAll.checked = visibleCheckboxes.length > 0 &&
                            checkedCheckboxes.length === visibleCheckboxes.length;
                        selectAll.indeterminate = checkedCheckboxes.length > 0 &&
                            checkedCheckboxes.length < visibleCheckboxes.length;
                    }
                }

                // Update button states
                function updateButtonStates() {
                    const promotedBtn = document.getElementById('reEnrollPromotedBtn');
                    const retainedBtn = document.getElementById('reEnrollRetainedBtn');
                    const cancelBtn = document.getElementById('cancelSelectionBtn');

                    // Show/hide cancel button and dropdowns
                    if (selectionMode !== 'none') {
                        cancelBtn.classList.remove('d-none');
                        promotedBtn.classList.add('d-none');
                        retainedBtn.classList.add('d-none');
                    } else {
                        cancelBtn.classList.add('d-none');
                        promotedBtn.classList.remove('d-none');
                        retainedBtn.classList.remove('d-none');
                    }

                    // Update button styles
                    promotedBtn.classList.remove('btn-success', 'btn-outline-success');
                    retainedBtn.classList.remove('btn-warning', 'btn-outline-warning');

                    if (selectionMode === 'promoted') {
                        promotedBtn.classList.add('btn-success');
                        retainedBtn.classList.add('btn-outline-warning');
                    } else if (selectionMode === 'retained') {
                        promotedBtn.classList.add('btn-outline-success');
                        retainedBtn.classList.add('btn-warning');
                    } else {
                        promotedBtn.classList.add('btn-outline-success');
                        retainedBtn.classList.add('btn-outline-warning');
                    }
                }

                // Update unenroll button states
                function updateUnenrollButtonStates() {
                    if (unenrollMode) {
                        unenrollStudentsBtn.classList.add('d-none');
                    } else {
                        unenrollStudentsBtn.classList.remove('d-none');
                    }
                }

                // Update ungraduate button states
                function updateUngraduateButtonStates() {
                    const ungraduateBtn = document.getElementById('ungraduateStudentsBtn');
                    if (!ungraduateBtn) return;

                    if (ungraduateMode) {
                        ungraduateBtn.classList.add('d-none');
                    } else {
                        ungraduateBtn.classList.remove('d-none');
                    }
                }

                // Update selection summary
                function updateSelectionSummary() {
                    const checked = document.querySelectorAll('input[name="selected_students[]"]:checked');
                    const retainedStudents = Array.from(checked).filter(cb => cb.hasAttribute('data-warning'));

                    // Show warning if retained students are selected
                    const warningElement = document.getElementById('retainedWarning');
                    if (retainedStudents.length > 0 && !warningElement) {
                        const warning = document.createElement('div');
                        warning.id = 'retainedWarning';
                        warning.className = 'alert alert-warning alert-dismissible fade show mt-3';

                        const form = document.querySelector('form[action="{{ route('students.promote') }}"]');
                        form.insertBefore(warning, form.querySelector('.d-flex.justify-content-end'));
                    } else if (retainedStudents.length === 0 && warningElement) {
                        warningElement.remove();
                    }
                }

                // Function to update the selected student count
                function updateSelectedCount() {
                    const selectedStudents = document.querySelectorAll('input[name="selected_students[]"]:checked');
                    const selectedCount = selectedStudents.length;

                    // Update the count badges
                    const promotedCount = document.getElementById('promotedCount');
                    const retainedCount = document.getElementById('retainedCount');

                    if (promotedCount) promotedCount.textContent = selectedCount;
                    if (retainedCount) retainedCount.textContent = selectedCount;

                    // Update button text based on count
                    const promotedBtn = document.getElementById('submitPromotedBtn');
                    const retainedBtn = document.getElementById('submitRetainedBtn');

                    if (selectedCount === 0) {
                        if (promotedBtn) {
                            promotedBtn.innerHTML =
                                '<i class="bx bx-check-circle me-1"></i>Re-Enroll Promoted <span class="selected-count-badge bg-white text-success ms-1 px-2 py-1 rounded" id="promotedCount">0</span>';
                        }
                        if (retainedBtn) {
                            retainedBtn.innerHTML =
                                '<i class="bx bx-error-circle me-1"></i>Re-Enroll Retained <span class="selected-count-badge bg-white text-warning ms-1 px-2 py-1 rounded" id="retainedCount">0</span>';
                        }
                    } else {
                        if (promotedBtn) {
                            promotedBtn.innerHTML =
                                `<i class="bx bx-check-circle me-1"></i>Re-Enroll ${selectedCount} Promoted Student${selectedCount !== 1 ? 's' : ''} <span class="selected-count-badge bg-white text-success ms-1 px-2 py-1 rounded" id="promotedCount">${selectedCount}</span>`;
                        }
                        if (retainedBtn) {
                            retainedBtn.innerHTML =
                                `<i class="bx bx-error-circle me-1"></i>Re-Enroll ${selectedCount} Retained Student${selectedCount !== 1 ? 's' : ''} <span class="selected-count-badge bg-white text-danger ms-1 px-2 py-1 rounded" id="retainedCount">${selectedCount}</span>`;
                        }
                    }
                }

                // Function to update the unenroll selected student count
                function updateUnenrollSelectedCount() {
                    const selectedStudents = document.querySelectorAll('input[name="unenroll_students[]"]:checked');
                    const selectedCount = selectedStudents.length;

                    // Update the count badge
                    const unenrollCount = document.getElementById('unenrollCount');
                    if (unenrollCount) unenrollCount.textContent = selectedCount;

                    // Update button text based on count
                    const unenrollBtn = document.getElementById('submitUnenrollBtn');

                    if (selectedCount === 0) {
                        if (unenrollBtn) {
                            unenrollBtn.innerHTML =
                                '<i class="bx bx-user-x me-1"></i>Confirm Unenroll <span class="selected-count-badge bg-white text-danger ms-1 px-2 py-1 rounded" id="unenrollCount">0</span>';
                        }
                    } else {
                        if (unenrollBtn) {
                            unenrollBtn.innerHTML =
                                `<i class="bx bx-user-x me-1"></i>Confirm Unenroll ${selectedCount} Student${selectedCount !== 1 ? 's' : ''} <span class="selected-count-badge bg-white text-danger ms-1 px-2 py-1 rounded" id="unenrollCount">${selectedCount}</span>`;
                        }
                    }
                }

                // Function to update the ungraduate selected student count
                function updateUngraduateSelectedCount() {
                    const ungraduateBtn = document.getElementById('submitUngraduateBtn');
                    if (!ungraduateBtn) return;

                    const selectedStudents = document.querySelectorAll(
                        'input[name="ungraduate_students[]"]:checked');
                    const selectedCount = selectedStudents.length;

                    // Update the count badge
                    const ungraduateCount = document.getElementById('ungraduateCount');
                    if (ungraduateCount) ungraduateCount.textContent = selectedCount;

                    // Update button text based on count
                    if (selectedCount === 0) {
                        ungraduateBtn.innerHTML =
                            '<i class="bx bx-undo me-1"></i>Confirm Ungraduate <span class="selected-count-badge bg-white text-warning ms-1 px-2 py-1 rounded" id="ungraduateCount">0</span>';
                    } else {
                        ungraduateBtn.innerHTML =
                            `<i class="bx bx-undo me-1"></i>Confirm Ungraduate ${selectedCount} Student${selectedCount !== 1 ? 's' : ''} <span class="selected-count-badge bg-white text-warning ms-1 px-2 py-1 rounded" id="ungraduateCount">${selectedCount}</span>`;
                    }
                }

                // Form submission with SweetAlert confirmation
                const form = document.querySelector('form[action="{{ route('students.promote') }}"]');
                form.addEventListener('submit', function(e) {
                    // Only allow submission when in selection mode
                    if (selectionMode === 'none') {
                        e.preventDefault();
                        Swal.fire({
                            icon: 'error',
                            title: 'No Selection Mode',
                            text: 'Please select either "Promoted" or "Retained" mode first.',
                            confirmButtonColor: '#3085d6',
                            customClass: {
                                container: 'my-swal-container'
                            },
                        });
                        return;
                    }

                    const mergedContainer = document.getElementById('mergedStudentInputs');
                    mergedContainer.innerHTML = '';

                    const checked = document.querySelectorAll('input[name="selected_students[]"]:checked');
                    const retainedStudents = Array.from(checked).filter(cb => cb.hasAttribute(
                        'data-warning'));

                    // Remove duplicate student IDs
                    const uniqueStudentIds = [...new Set(Array.from(checked).map(cb => cb.value))];

                    if (uniqueStudentIds.length === 0) {
                        e.preventDefault();
                        Swal.fire({
                            icon: 'error',
                            title: 'No Students Selected',
                            text: 'Please select at least one student to re-enroll.',
                            confirmButtonColor: '#3085d6',
                            customClass: {
                                container: 'my-swal-container'
                            },
                        });
                        return;
                    }

                    // Validate grade and section selection
                    if (!batchGradeSelect.value) {
                        e.preventDefault();
                        Swal.fire({
                            icon: 'error',
                            title: 'Grade Level Required',
                            text: 'Please select a grade level for the re-enrolled students.',
                            confirmButtonColor: '#3085d6'
                        });
                        return;
                    }

                    if (batchGradeSelect.value !== 'graduated' && !batchSectionSelect.value) {
                        e.preventDefault();
                        Swal.fire({
                            icon: 'error',
                            title: 'Section Required',
                            text: 'Please select a section for the re-enrolled students.',
                            confirmButtonColor: '#3085d6'
                        });
                        return;
                    }

                    // Add unique student IDs to form
                    uniqueStudentIds.forEach(studentId => {
                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = 'selected_students[]';
                        input.value = studentId;
                        mergedContainer.appendChild(input);
                    });

                    // Show confirmation dialog
                    e.preventDefault();

                    const actionType = selectionMode === 'promoted' ? 'promote' : 're-enroll as retained';
                    const studentCount = uniqueStudentIds.length;
                    const gradeLevel = batchGradeSelect.options[batchGradeSelect.selectedIndex].text;
                    const section = batchGradeSelect.value !== 'graduated' ?
                        ` in section ${batchSectionSelect.value}` : '';

                    Swal.fire({
                        title: `Confirm ${selectionMode === 'promoted' ? 'Promotion' : 'Re-enrollment'}`,
                        html: `You are about to ${actionType} <strong>${studentCount}</strong> student${studentCount !== 1 ? 's' : ''} to <strong>${gradeLevel}${section}</strong>.<br><br>Are you sure you want to continue?`,
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: `Yes, ${selectionMode === 'promoted' ? 'Promote' : 'Re-enroll'} Students`,
                        cancelButtonText: 'Cancel',
                        customClass: {
                            container: 'my-swal-container'
                        },
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });

                // Confirm unenroll students
                function confirmUnenrollStudents() {
                    const selectedStudents = document.querySelectorAll('input[name="unenroll_students[]"]:checked');
                    const selectedCount = selectedStudents.length;

                    if (selectedCount === 0) {
                        Swal.fire({
                            icon: 'error',
                            title: 'No Students Selected',
                            text: 'Please select at least one student to unenroll.',
                            confirmButtonColor: '#3085d6',
                            customClass: {
                                container: 'my-swal-container'
                            },
                        });
                        return;
                    }

                    // Get student IDs
                    const studentIds = Array.from(selectedStudents).map(cb => cb.value);

                    Swal.fire({
                        title: 'Confirm Unenroll',
                        html: `You are about to unenroll <strong>${selectedCount}</strong> student${selectedCount !== 1 ? 's' : ''} from the current school year.<br><br>This action will move them back to "Available for Re-Enrollment".<br><br>Are you sure you want to continue?`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Yes, Unenroll Students',
                        cancelButtonText: 'Cancel',
                        customClass: {
                            container: 'my-swal-container'
                        },
                    }).then((result) => {
                        if (result.isConfirmed) {
                            unenrollStudents(studentIds);
                        }
                    });
                }

                // Confirm ungraduate students
                function confirmUngraduateStudents() {
                    const selectedStudents = document.querySelectorAll(
                        'input[name="ungraduate_students[]"]:checked');
                    const selectedCount = selectedStudents.length;

                    if (selectedCount === 0) {
                        Swal.fire({
                            icon: 'error',
                            title: 'No Students Selected',
                            text: 'Please select at least one student to ungraduate.',
                            confirmButtonColor: '#3085d6',
                            customClass: {
                                container: 'my-swal-container'
                            },
                        });
                        return;
                    }

                    // Get student IDs
                    const studentIds = Array.from(selectedStudents).map(cb => cb.value);

                    Swal.fire({
                        title: 'Confirm Ungraduate',
                        html: `You are about to ungraduate <strong>${selectedCount}</strong> student${selectedCount !== 1 ? 's' : ''} from graduation status.<br><br>This action will:<br>
                   • Change their previous year status from "graduated" to "archived"<br>
                   • Preserve their eligibility data and grades<br>
                   • Remove their current school year enrollment<br>
                   • Move them to "Available for Re-Enrollment" as Grade 6 students<br><br>
                   Are you sure you want to continue?`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#f39c12',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Yes, Ungraduate Students',
                        cancelButtonText: 'Cancel',
                        customClass: {
                            container: 'my-swal-container'
                        },
                    }).then((result) => {
                        if (result.isConfirmed) {
                            ungraduateStudents(studentIds);
                        }
                    });
                }

                // Unenroll students via AJAX
                function unenrollStudents(studentIds) {
                    // Show loading state
                    submitUnenrollBtn.innerHTML = '<i class="bx bx-loader bx-spin me-1"></i>Processing...';
                    submitUnenrollBtn.disabled = true;

                    // Make AJAX request
                    fetch('{{ route('students.bulkUnenroll') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                    'content')
                            },
                            body: JSON.stringify({
                                student_ids: studentIds
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire({
                                    title: 'Success!',
                                    text: data.message,
                                    icon: 'success',
                                    confirmButtonColor: '#3085d6',
                                    customClass: {
                                        container: 'my-swal-container'
                                    }
                                }).then(() => {
                                    // Reload the page to reflect changes
                                    location.reload();
                                });
                            } else {
                                throw new Error(data.message);
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            Swal.fire({
                                title: 'Error!',
                                text: error.message || 'Failed to unenroll students. Please try again.',
                                icon: 'error',
                                confirmButtonColor: '#3085d6',
                                customClass: {
                                    container: 'my-swal-container'
                                }
                            });

                            // Reset button state
                            submitUnenrollBtn.innerHTML = '<i class="bx bx-user-x me-1"></i>Confirm Unenroll';
                            submitUnenrollBtn.disabled = false;
                        });
                }

                // Ungraduate students via AJAX
                function ungraduateStudents(studentIds) {
                    // Show loading state
                    submitUngraduateBtn.innerHTML = '<i class="bx bx-loader bx-spin me-1"></i>Processing...';
                    submitUngraduateBtn.disabled = true;

                    // Make AJAX request - FIXED: Use the correct route name
                    fetch('{{ route('students.bulkUngraduate') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                    'content')
                            },
                            body: JSON.stringify({
                                student_ids: studentIds
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire({
                                    title: 'Success!',
                                    text: data.message,
                                    icon: 'success',
                                    confirmButtonColor: '#3085d6',
                                    customClass: {
                                        container: 'my-swal-container'
                                    }
                                }).then(() => {
                                    // Reload the page to reflect changes
                                    location.reload();
                                });
                            } else {
                                throw new Error(data.message);
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            Swal.fire({
                                title: 'Error!',
                                text: error.message ||
                                    'Failed to ungraduate students. Please try again.',
                                icon: 'error',
                                confirmButtonColor: '#3085d6',
                                customClass: {
                                    container: 'my-swal-container'
                                }
                            });

                            // Reset button state
                            submitUngraduateBtn.innerHTML = '<i class="bx bx-undo me-1"></i>Confirm Ungraduate';
                            submitUngraduateBtn.disabled = false;
                        });
                }

                // Initialize - start with checkboxes HIDDEN
                toggleCheckboxMode(false); // Ensure checkboxes are hidden on page load
                toggleUnenrollCheckboxMode(false); // Ensure unenroll checkboxes are hidden on page load
                if (document.getElementById('ungraduateStudentsBtn')) {
                    toggleUngraduateCheckboxMode(false); // Ensure ungraduate checkboxes are hidden on page load
                }
                updateButtonStates();
                updateUnenrollButtonStates();
                updateUngraduateButtonStates();
                updateSelectedCount();
                updateUnenrollSelectedCount();
                updateUngraduateSelectedCount();
                updateSelectionSummary();
                updateSelectAllCheckbox();
                updateUnenrollSelectAllCheckbox();
                updateUngraduateSelectAllCheckbox();

                // Initialize grade dropdown in default state
                resetGradeDropdown();

                // Add manual accordion toggle handlers to maintain search state
                document.querySelectorAll('.accordion-button').forEach(button => {
                    button.addEventListener('click', function() {
                        // Small delay to ensure Bootstrap has processed the click
                        setTimeout(() => {
                            const searchTerm = globalSearch.value.toLowerCase().trim();
                            if (searchTerm) {
                                // Re-apply search filter after accordion toggle
                                performSmartSearch(searchTerm);
                            }
                        }, 100);
                    });
                });
            }
        });
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
        function updateAlreadyReenrolledTable() {
            const table = document.getElementById('alreadyReenrolledTable');
            const rows = Array.from(table.querySelectorAll('tbody tr'));

            // Filter rows to only show students from the current selected grade level
            const filteredRows = rows.filter(row => {
                // This table should only show students who were re-enrolled from the selected class
                return true; // All rows in this table are already filtered by the controller
            });

            // Update the count badge
            const alreadyReenrolledCount = document.getElementById('alreadyReenrolledCount');
            if (alreadyReenrolledCount) {
                alreadyReenrolledCount.textContent = `${filteredRows.length} students`;
            }
        }

        // Call this function after page load and after any updates
        document.addEventListener('DOMContentLoaded', function() {
            updateAlreadyReenrolledTable();

            // Also update when the accordion is shown
            const alreadyReenrolledCollapse = document.getElementById('alreadyReenrolledCollapse');
            if (alreadyReenrolledCollapse) {
                alreadyReenrolledCollapse.addEventListener('shown.bs.collapse', function() {
                    updateAlreadyReenrolledTable();
                });
            }
        });
    </script>

    <script>
        // alert after a success re-enrollment
        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: '{{ session('success') }}',
                confirmButtonColor: '#3085d6',
                customClass: {
                    container: 'my-swal-container'
                }
            }).then((result) => {
                // Refresh the page to update the already re-enrolled students list
                if (result.isConfirmed) {
                    location.reload();
                }
            });
        @endif
    </script>

    <script>
        // Initialize tooltips
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Bootstrap tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });

            // Add click animation to view buttons
            document.querySelectorAll('.btn-outline-primary').forEach(button => {
                button.addEventListener('click', function() {
                    this.style.transform = 'scale(0.95)';
                    setTimeout(() => {
                        this.style.transform = 'scale(1)';
                    }, 150);
                });
            });
        });

        // Enhanced table row selection feedback
        document.querySelectorAll('#notReenrolledTable tbody tr').forEach(row => {
            row.addEventListener('click', function(e) {
                // Only trigger if clicking on the row (not on checkboxes or buttons)
                if (!e.target.matches('input[type="checkbox"]') && !e.target.closest('button')) {
                    const checkbox = this.querySelector('input[type="checkbox"]');
                    if (checkbox && !checkbox.disabled) {
                        checkbox.checked = !checkbox.checked;
                        checkbox.dispatchEvent(new Event('change'));

                        // Add visual feedback
                        this.style.backgroundColor = checkbox.checked ? 'rgba(0, 123, 255, 0.1)' : '';
                    }
                }
            });
        });
    </script>

    <script>
        // Unenroll student functionality
        document.addEventListener('DOMContentLoaded', function() {
            // Handle unenroll button clicks
            document.addEventListener('click', function(e) {
                if (e.target.closest('.unenroll-btn')) {
                    const button = e.target.closest('.unenroll-btn');
                    const studentId = button.getAttribute('data-student-id');
                    const studentName = button.getAttribute('data-student-name');

                    confirmUnenroll(studentId, studentName);
                }
            });

            function confirmUnenroll(studentId, studentName) {
                Swal.fire({
                    title: 'Confirm Unenroll',
                    html: `Are you sure you want to unenroll <strong>${studentName}</strong> from the current school year?<br><br>
                      <span class="text-warning"><i class="bx bx-info-circle"></i> This action will move the student back to "Available for Re-Enrollment".</span>`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, Unenroll',
                    cancelButtonText: 'Cancel',
                    customClass: {
                        container: 'my-swal-container'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        unenrollStudent(studentId, studentName);
                    }
                });
            }

            function unenrollStudent(studentId, studentName) {
                // Show loading state
                const button = document.querySelector(`.unenroll-btn[data-student-id="${studentId}"]`);
                const originalHtml = button.innerHTML;
                button.innerHTML = '<i class="bx bx-loader bx-spin me-1"></i>Processing...';
                button.disabled = true;

                // Make AJAX request
                fetch(`/students/${studentId}/unenroll`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                'content')
                        },
                        body: JSON.stringify({})
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                title: 'Success!',
                                text: data.message,
                                icon: 'success',
                                confirmButtonColor: '#3085d6',
                                customClass: {
                                    container: 'my-swal-container'
                                }
                            }).then(() => {
                                // Reload the page to reflect changes
                                location.reload();
                            });
                        } else {
                            throw new Error(data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire({
                            title: 'Error!',
                            text: error.message || 'Failed to unenroll student. Please try again.',
                            icon: 'error',
                            confirmButtonColor: '#3085d6',
                            customClass: {
                                container: 'my-swal-container'
                            }
                        });

                        // Reset button state
                        button.innerHTML = originalHtml;
                        button.disabled = false;
                    });
            }

            // Update tooltips for unenroll buttons
            const unenrollButtons = document.querySelectorAll('.unenroll-btn');
            unenrollButtons.forEach(button => {
                new bootstrap.Tooltip(button);
            });
        });
    </script>
@endpush
