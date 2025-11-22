@extends('./layouts.main')

@section('title', 'Admin | Student Enrollment')

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
                    <li class="menu-item active">
                        <a href="{{ route('show.students') }}" class="menu-link bg-dark text-light">
                            <div class="text-warning">Student Enrollment</div>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="{{ route('students.promote.view') }}" class="menu-link bg-dark text-light">
                            <div class="text-light">Re-Enrollment / Promotion</div>
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
            </span> Student Enrollment
        </h4>

        <div class="text-center mb-3">
            <h3 class="text-primary">
                All Students Enrolled in School Year: {{ $selectedYear }}
            </h3>
        </div>

        <div class="row mb-3 align-items-center">
            {{-- Search Bar --}}
            <div class="col-md-6 d-flex justify-content-start gap-2">
                <div class="d-flex align-items-center w-100" style="max-width: 400px;">
                    <i class="bx bx-search fs-4 lh-0 me-2"></i>
                    <input type="text" id="studentSearch" class="form-control border-1 shadow-none"
                        placeholder="Search..." aria-label="Search..." />
                </div>

                <!-- Enrolled Student Button trigger modal -->
                <div class="d-flex align-items-center w-100">
                    <button type="button" class="btn btn-primary d-flex align-items-center" data-bs-toggle="modal"
                        data-bs-target="#registerModal">
                        <i class='bx bx-user-plus me-2'></i>
                        <span class="d-none d-sm-block">Enroll Student</span>
                    </button>
                </div>
            </div>

            {{-- School Year Selection --}}
            <div class="col-md-6 d-flex justify-content-end">
                <!-- School Year Dropdown -->
                <div class="dropdown">
                    <button class="btn btn-info text-white dropdown-toggle w-100 text-start" type="button"
                        id="yearDropdownStudents" data-bs-toggle="dropdown" aria-expanded="false">
                        {{ $selectedYear }}
                    </button>
                    <ul class="dropdown-menu w-100" aria-labelledby="yearDropdownStudents">
                        @foreach ($schoolYears as $year)
                            <li>
                                <a class="dropdown-item @if ($year === $selectedYear) active fw-bold @endif"
                                    href="{{ route(
                                        'show.students',
                                        array_filter([
                                            'school_year' => $year,
                                            'section' => $selectedSection ?? null,
                                        ]),
                                    ) }}">
                                    {{ $year }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <!-- "Now" button -->
                <form method="GET" action="{{ route('show.students') }}">
                    <input type="hidden" name="school_year" value="{{ $currentYear . '-' . ($currentYear + 1) }}">
                    @if ($selectedSection)
                        <input type="hidden" name="section" value="{{ $selectedSection }}">
                    @endif
                    <button type="submit" class="btn btn-sm btn-primary ms-2" style="height: 38px;">
                        Now
                    </button>
                </form>
            </div>
        </div>

        <div id="noResultsMessage" class="alert alert-info text-center d-none">
            No search found.
        </div>

        {{-- Card --}}
        @foreach ($groupedStudents as $grade => $students)
            @php
                // Calculate counts for header display - COUNT ALL STUDENTS IN THE LIST
                $totalStudents = $students->count();
                $studentCount = $totalStudents; // This now counts all students listed

                // Collect unique adviser and subject teacher counts
                $adviserCount = $students
                    ->map(fn($s) => optional($s->class->first())->adviser)
                    ->filter()
                    ->unique('id')
                    ->count();

                $subjectTeacherCount = $students
                    ->flatMap(fn($s) => optional($s->class->first())->subjectTeachers ?? [])
                    ->unique('id')
                    ->count();
            @endphp

            <div class="card d-flex flex-column mb-4 grade-card" data-grade="{{ strtolower($grade) }}">
                {{-- Header with toggle --}}
                <div class="card-header bg-light d-flex justify-content-between align-items-center collapse-toggle"
                    data-bs-toggle="collapse" data-bs-target="#collapse-{{ Str::slug($grade) }}" aria-expanded="false"
                    aria-controls="collapse-{{ Str::slug($grade) }}" style="cursor: pointer;">

                    <h5 class="fw-bold text-primary mb-0">{{ $grade }} (
                        @if ($studentCount > 0)
                            <span class="text-success fw-bold">{{ $studentCount }} students</span>
                        @else
                            <span class="text-muted">No students</span>
                        @endif)
                    </h5>

                    <i class="bx bx-chevron-down fs-4 transition-all"></i>
                </div>

                {{-- Collapsible Content --}}
                <div id="collapse-{{ Str::slug($grade) }}" class="collapse">
                    <div class="table-responsive text-nowrap">
                        <table class="table table-hover table-bordered text-center mb-0"
                            id="table-{{ Str::slug($grade) }}">
                            <thead>
                                <tr>
                                    <th style="min-width: 220px;">Full Name</th>
                                    <th style="width: 10%;">Photo</th>
                                    <th style="width: 15%;">LRN</th>
                                    <th style="width: 20%;">Grade & Section</th>
                                    <th style="width: 15%;">Enrollment Status</th>
                                    <th style="width: 15%;">Enrollment Type</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($students->sortBy([
                                                                        ['student_lName', 'asc'],
                                                                        ['student_fName', 'asc'],
                                                                        ['student_mName', 'asc'],
                                                                        ['student_extName', 'asc'],
                                                                    ]) as $student)
                                    {{-- Student rows unchanged --}}
                                    <tr class="student-row">
                                        <td>
                                            <a class="text-primary"
                                                href="{{ route('student.info', ['id' => $student->id, 'school_year' => $schoolYearId]) }}">
                                                {{ $student->student_lName }}, {{ $student->student_fName }}
                                                {{ $student->student_mName }} {{ $student->student_extName }}
                                            </a>
                                        </td>
                                        <td>
                                            <a class="text-primary"
                                                href="{{ route('student.info', ['id' => $student->id, 'school_year' => $schoolYearId]) }}">
                                                @if ($student->student_photo)
                                                    <img src="{{ asset('public/uploads/' . $student->student_photo) }}"
                                                        alt="Profile Photo" width="30" height="30"
                                                        style="object-fit: cover; border-radius: 50%;">
                                                @else
                                                    <img src="{{ asset('assetsDashboard/img/student_profile_pictures/student_default_profile.jpg') }}"
                                                        alt="No Profile" width="30" height="30"
                                                        style="object-fit: cover; border-radius: 50%;">
                                                @endif
                                            </a>
                                        </td>
                                        <td>{{ $student->student_lrn }}</td>
                                        <td>{{ optional($student->class->first())->formatted_grade_level }} -
                                            {{ optional($student->class->first())->section }}</td>
                                        <td>
                                            @php
                                                $status =
                                                    optional($student->class->first())->pivot->enrollment_status ??
                                                    'N/A';
                                                $badgeClass = match ($status) {
                                                    'enrolled' => 'bg-label-success',
                                                    'not_enrolled' => 'bg-label-secondary',
                                                    'archived' => 'bg-label-warning',
                                                    'graduated' => 'bg-label-info',
                                                    default => 'bg-label-dark',
                                                };
                                            @endphp
                                            <span class="badge {{ $badgeClass }} text-uppercase px-3 py-1">
                                                {{ strtoupper(str_replace('_', ' ', $status)) }}
                                            </span>
                                        </td>
                                        <td>
                                            @php
                                                $type =
                                                    optional($student->class->first())->pivot->enrollment_type ??
                                                    'regular';
                                                $badgeClass = match ($type) {
                                                    'regular' => 'bg-label-primary',
                                                    'transferee' => 'bg-label-info',
                                                    'returnee' => 'bg-label-warning',
                                                    default => 'bg-label-dark',
                                                };
                                            @endphp
                                            <span class="badge {{ $badgeClass }} text-uppercase px-3 py-1">
                                                {{ strtoupper(str_replace('_', ' ', $type)) }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="dropdown">
                                                <button class="btn p-0 dropdown-toggle hide-arrow"
                                                    data-bs-toggle="dropdown">
                                                    <i class="bx bx-dots-vertical-rounded"></i>
                                                </button>
                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item text-info"
                                                        href="{{ route('student.info', ['id' => $student->id, 'school_year' => $schoolYearId]) }}">
                                                        <i class="bx bxs-user-badge me-1"></i> View Profile
                                                    </a>
                                                    @if ($selectedYear == $currentYear . '-' . ($currentYear + 1))
                                                        <button type="button" class="dropdown-item text-danger"
                                                            onclick="confirmUnenroll({{ $student->id }}, '{{ $student->student_fName }}', '{{ $student->student_lName }}', '{{ $selectedYear }}')">
                                                            <i class="bx bx-user-x me-1"></i> Unenroll
                                                        </button>

                                                        <form id="unenroll-form-{{ $student->id }}"
                                                            action="{{ route('unenroll.student', $student->id) }}"
                                                            method="POST" style="display: none;">
                                                            @csrf
                                                            @method('DELETE')
                                                            <input type="hidden" name="school_year"
                                                                value="{{ $selectedYear }}">
                                                        </form>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr class="text-muted">
                                        <td colspan="8">No students in {{ $grade }}.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    <div class="d-flex justify-content-between align-items-center flex-wrap px-3 py-2 border-top gap-3">
                        <div class="d-flex align-items-center gap-2">
                            <label for="length-{{ Str::slug($grade) }}" class="mb-0">Show</label>
                            <select id="length-{{ Str::slug($grade) }}" class="form-select form-select-sm w-auto">
                                <option value="5" selected>5</option>
                                <option value="10">10</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                            </select>
                            <span>entries</span>
                        </div>
                        <div class="flex-grow-1 text-center">
                            <small id="info-{{ Str::slug($grade) }}" class="text-muted"></small>
                        </div>
                        <nav aria-label="Page navigation" class="ms-auto">
                            <ul class="pagination mb-0" id="pagination-{{ Str::slug($grade) }}"></ul>
                        </nav>
                    </div>
                </div>
            </div>
        @endforeach
        {{-- / Card --}}

        <hr class="my-5" />

    </div>
    <!-- / Content wrapper -->

    <!-- Enroll Students Modal -->
    <div class="modal fade" id="registerModal" tabindex="-1" aria-labelledby="registerModalLabel" aria-hidden="true"
        data-bs-backdrop="static">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <form method="POST" action="{{ route('assign.student.class') }}">
                    @csrf
                    <div class="modal-header text-white">
                        <h4 class="modal-title text-info fw-bold" id="registerModalLabel">Enroll Students</h4>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <div class="row g-3">
                            <!-- School Year -->
                            <div class="col-md-6">
                                <label class="form-label fw-bold">School Year</label>
                                <select name="school_year" id="enroll_school_year" class="form-select" required disabled>
                                    @foreach ($schoolYears as $year)
                                        <option value="{{ $year }}" @selected($year == $selectedYear)>
                                            {{ $year }}</option>
                                    @endforeach
                                </select>
                                <input type="hidden" name="school_year" value="{{ $selectedYear }}">
                            </div>

                            <!-- Enrollment Type -->
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Enrollment Type</label>
                                <select name="enrollment_type" id="enroll_type" class="form-select" required>
                                    <option value="regular">Regular</option>
                                    <option value="transferee">Transferee</option>
                                    <option value="returnee">Returnee</option>
                                </select>
                            </div>

                            <!-- Grade Level -->
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Grade Level</label>
                                <select name="grade_level" id="enroll_grade" class="form-select" required>
                                    <option value="">Select Grade</option>
                                    <option value="kindergarten">Kindergarten</option>
                                    <option value="grade1">Grade 1</option>
                                    <option value="grade2">Grade 2</option>
                                    <option value="grade3">Grade 3</option>
                                    <option value="grade4">Grade 4</option>
                                    <option value="grade5">Grade 5</option>
                                    <option value="grade6">Grade 6</option>
                                </select>
                            </div>

                            <!-- Section -->
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Section</label>
                                <select name="section" id="enroll_section" class="form-select" required>
                                    <option value="">Select Section</option>
                                    <option value="A">A</option>
                                    <option value="B">B</option>
                                    <option value="C">C</option>
                                    <option value="D">D</option>
                                    <option value="E">E</option>
                                    <option value="F">F</option>
                                </select>
                            </div>

                            <!-- Select Students -->
                            <div class="col-12 mt-3">
                                <label class="form-label fw-bold">Select Students to Enroll</label>
                                <select id="enroll_students" name="students[]" multiple required></select>
                                <small class="form-text text-muted">Search and select one or more students by name or
                                    LRN.</small>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Enroll Selected Students</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Enroll Students Modal -->

@endsection

@push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function() {

            // ========= GLOBAL STATE ==========
            const paginators = {};
            const visibleRowsMap = {};
            const tableSettings = {}; // { rowsPerPage, currentPage }

            // ========= PAGINATION FUNCTION ==========
            function paginateTable(tableId, paginationId, infoId, lengthSelectId, defaultRowsPerPage = 5,
                maxVisiblePages = 5) {
                const table = document.getElementById(tableId);
                const pagination = document.getElementById(paginationId);
                const info = document.getElementById(infoId);
                const lengthSelect = document.getElementById(lengthSelectId);

                tableSettings[tableId] = {
                    rowsPerPage: defaultRowsPerPage,
                    currentPage: 1
                };

                function updateInfo(start, end, total) {
                    info.textContent = total > 0 ?
                        `Showing ${start + 1} to ${end} of ${total} entries` :
                        "Showing 0 to 0 of 0 entries";
                }

                function showPage(page) {
                    const rows = visibleRowsMap[tableId] || Array.from(table.querySelectorAll(
                        "tbody tr.student-row"));
                    const totalRows = rows.length;
                    const rowsPerPage = tableSettings[tableId].rowsPerPage;
                    const totalPages = Math.ceil(totalRows / rowsPerPage);

                    if (page > totalPages) page = totalPages || 1;
                    tableSettings[tableId].currentPage = page;

                    const start = (page - 1) * rowsPerPage;
                    const end = Math.min(start + rowsPerPage, totalRows);

                    rows.forEach((row, index) => {
                        row.style.display = index >= start && index < end ? "" : "none";
                    });

                    updateInfo(start, end, totalRows);

                    pagination.innerHTML = "";
                    const ul = document.createElement("ul");
                    ul.className = "pagination mb-0";

                    // Prev
                    const prev = document.createElement("li");
                    prev.className = `page-item prev ${page === 1 ? "disabled" : ""}`;
                    prev.innerHTML =
                        `<a class="page-link text-primary" href="javascript:void(0);"><i class="tf-icon bx bx-chevrons-left"></i></a>`;
                    prev.onclick = () => page > 1 && showPage(page - 1);
                    ul.appendChild(prev);

                    // Page numbers
                    let startPage = Math.max(1, page - Math.floor(maxVisiblePages / 2));
                    let endPage = Math.min(totalPages, startPage + maxVisiblePages - 1);
                    if (endPage - startPage + 1 < maxVisiblePages) {
                        startPage = Math.max(1, endPage - maxVisiblePages + 1);
                    }

                    for (let i = startPage; i <= endPage; i++) {
                        const li = document.createElement("li");
                        li.className = `page-item ${i === page ? "active" : ""}`;
                        li.innerHTML =
                            `<a class="page-link ${i === page ? "bg-primary text-white border-primary" : "text-primary"}" href="javascript:void(0);">${i}</a>`;
                        li.onclick = () => showPage(i);
                        ul.appendChild(li);
                    }

                    // Next
                    const next = document.createElement("li");
                    next.className = `page-item next ${page === totalPages ? "disabled" : ""}`;
                    next.innerHTML =
                        `<a class="page-link text-primary" href="javascript:void(0);"><i class="tf-icon bx bx-chevrons-right"></i></a>`;
                    next.onclick = () => page < totalPages && showPage(page + 1);
                    ul.appendChild(next);

                    pagination.appendChild(ul);
                }

                // Length select event
                lengthSelect.addEventListener("change", function() {
                    tableSettings[tableId].rowsPerPage = parseInt(this.value, 10);
                    showPage(1);
                });

                visibleRowsMap[tableId] = Array.from(table.querySelectorAll("tbody tr.student-row"));
                showPage(1);
                paginators[tableId] = () => showPage(1);
            }

            // ========= SEARCH + FILTER ==========
            function setupSearchAndFilters() {
                const searchInput = document.getElementById("studentSearch");
                const noResultsMessage = document.getElementById("noResultsMessage");

                searchInput.addEventListener("input", function() {
                    const query = this.value.trim().toLowerCase();
                    const gradeCards = document.querySelectorAll(".grade-card");
                    let overallMatch = false;

                    gradeCards.forEach(card => {
                        const table = card.querySelector("table");
                        const rows = Array.from(table.querySelectorAll("tbody tr.student-row"));
                        const tableId = table.id;

                        let anyMatch = false;

                        rows.forEach(row => {
                            const text = row.textContent.toLowerCase();
                            const match = text.includes(query);
                            row.style.display = match ? "table-row" : "none";
                            if (match) anyMatch = true;
                        });

                        visibleRowsMap[tableId] = rows.filter(r => r.style.display !== "none");

                        if (anyMatch) {
                            card.classList.remove("d-none");
                            overallMatch = true;
                            if (paginators[tableId]) paginators[tableId]();
                        } else {
                            card.classList.add("d-none");
                        }
                    });

                    noResultsMessage.classList.toggle("d-none", overallMatch || query === "");

                    if (query === "") {
                        gradeCards.forEach(card => {
                            const table = card.querySelector("table");
                            const rows = Array.from(table.querySelectorAll("tbody tr.student-row"));
                            const tableId = table.id;

                            rows.forEach(r => r.style.display = "table-row");
                            visibleRowsMap[tableId] = rows;
                            if (paginators[tableId]) paginators[tableId]();
                            card.classList.remove("d-none");
                        });
                    }
                });
            }

            // ========= ACCORDION CHEVRON & ONE OPEN ==========
            function setupAccordion() {
                const headers = document.querySelectorAll(".collapse-toggle");

                headers.forEach(header => {
                    const icon = header.querySelector(".bx-chevron-down");
                    const targetId = header.getAttribute("data-bs-target");
                    const target = document.querySelector(targetId);

                    target.addEventListener("show.bs.collapse", () => {
                        document.querySelectorAll(".collapse.show").forEach(collapse => {
                            if (collapse !== target) new bootstrap.Collapse(collapse, {
                                toggle: true
                            });
                        });
                        icon.classList.add("rotate-180");
                    });

                    target.addEventListener("hide.bs.collapse", () => {
                        icon.classList.remove("rotate-180");
                    });
                });
            }

            // ========= INITIALIZATION ==========
            const gradeLevels = @json(array_keys($groupedStudents->toArray()));
            gradeLevels.forEach(grade => {
                const slug = grade.toLowerCase().replace(/\s+/g, "-");
                paginateTable(`table-${slug}`, `pagination-${slug}`, `info-${slug}`, `length-${slug}`);
            });

            setupSearchAndFilters();
            setupAccordion();
        });
    </script>

    <script>
        // delete button alert
        function confirmUnenroll(id, student_fName, student_lName) {
            Swal.fire({
                title: `Enenroll ${student_fName} ${student_lName} from the Current School Year??`,
                text: "The records will be removed.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#6c757d",
                confirmButtonText: "Yes, unenroll it!",
                cancelButtonText: "Cancel",
                customClass: {
                    container: 'my-swal-container'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: "Unenrolling...",
                        text: "Please wait while we remove the record.",
                        icon: "info",
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        customClass: {
                            container: 'my-swal-container'
                        },
                        didOpen: () => {
                            setTimeout(() => {
                                document.getElementById('unenroll-form-' + id).submit();
                            }, 1000);
                        }
                    });
                }
            });
        }
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

    {{-- Initialize TomSelect for Enrolling Students --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const schoolYearSelect = document.getElementById('enroll_school_year');

            if (document.getElementById('enroll_students')) {
                new TomSelect('#enroll_students', {
                    plugins: ['remove_button'],
                    maxItems: null,
                    placeholder: "Search students by name or LRN...",
                    valueField: 'id',
                    labelField: 'text',
                    searchField: 'text',
                    load: function(query, callback) {
                        if (!query.length) return callback();

                        const selectedYear = schoolYearSelect.value;

                        if (!selectedYear) {
                            alert('Please select a school year first.');
                            return callback();
                        }

                        // ✅ send both q (query) and school_year to controller
                        const url = "{{ route('students.search.not.enrolled') }}" +
                            "?q=" + encodeURIComponent(query) +
                            "&school_year=" + encodeURIComponent(selectedYear);

                        fetch(url)
                            .then(response => response.json())
                            .then(data => {
                                callback(data.map(student => ({
                                    id: student.id,
                                    text: student.student_lrn + " - " + student
                                        .student_fName + " " + student
                                        .student_mName + " " + student.student_lName
                                })));
                            })
                            .catch(() => callback());
                    }
                });
            }
        });
    </script>

    {{-- Rotate Chevron on Collapse --}}
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const headers = document.querySelectorAll('.collapse-toggle');

            headers.forEach(header => {
                const icon = header.querySelector('.bx-chevron-down');
                const targetId = header.getAttribute('data-bs-target');
                const target = document.querySelector(targetId);

                // When a card is shown
                target.addEventListener('show.bs.collapse', () => {
                    // Close all other collapses
                    document.querySelectorAll('.collapse').forEach(collapse => {
                        if (collapse !== target && collapse.classList.contains('show')) {
                            new bootstrap.Collapse(collapse, {
                                toggle: true
                            });
                        }
                    });
                    // Rotate the current icon
                    icon.classList.add('rotate-180');
                });

                // When a card is hidden
                target.addEventListener('hide.bs.collapse', () => {
                    icon.classList.remove('rotate-180');
                });
            });
        });
    </script>

    {{-- Add this after your error display --}}
    @if (session('auto_graduated'))
        <div class="alert alert-info">
            <i class="bx bx-graduation me-2"></i>
            {{ session('auto_graduated') }}
        </div>
    @endif
@endpush

@push('styles')
    <style>
        table td,
        table th {
            word-wrap: break-word;
            overflow-wrap: break-word;
            white-space: normal;
        }

        .grade-card {
            transition: all 0.3s ease;
        }

        .grade-card.d-none {
            display: none !important;
        }

        #noResultsMessage {
            margin-top: 1rem;
            font-weight: 500;
        }

        .rotate-180 {
            transform: rotate(180deg);
            transition: transform 0.3s ease;
        }

        .bx-chevron-down {
            transition: transform 0.3s ease;
        }

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
