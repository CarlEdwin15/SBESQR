@extends('./layouts.main')

@section('title', 'Admin | All Students')


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
                            <li class="menu-item active">
                                <a href="{{ route('show.students') }}" class="menu-link bg-dark text-light">
                                    <div class="text-danger">All Students</div>
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
                            <li class="menu-item">
                                <a href="" class="menu-link bg-dark text-light">
                                    <div class="text-light">Graduated Students</div>
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
                        </span> All Students
                    </h4>

                    <div class="text-center mb-3">
                        <h3 class="text-primary">
                            All Students Enrolled in School Year: {{ $selectedYear }}
                        </h3>
                    </div>

                    <div class="row mb-3 align-items-center">
                        {{-- Search Bar --}}
                        <div class="col-md-6 d-flex justify-content-start">
                            <div class="d-flex align-items-center w-100" style="max-width: 400px;">
                                <i class="bx bx-search fs-4 lh-0 me-2"></i>
                                <input type="text" id="studentSearch" class="form-control border-1 shadow-none"
                                    placeholder="Search..." aria-label="Search..." />
                            </div>
                        </div>

                        {{-- School Year Selection --}}
                        <div class="col-md-6 d-flex justify-content-end">
                            <form method="GET" action="{{ route('show.students') }}"
                                class="d-flex align-items-center w-100" style="max-width: 350px;">
                                <label for="school_year" class="form-label mb-0 me-2">School Year:</label>
                                <select name="school_year" id="school_year" class="form-select me-2"
                                    onchange="this.form.submit()">
                                    @foreach ($schoolYears as $year)
                                        <option value="{{ $year }}"
                                            {{ $year == $selectedYear ? 'selected' : '' }}>
                                            {{ $year }}
                                        </option>
                                    @endforeach
                                </select>
                            </form>

                            {{-- "Now" button --}}
                            <form method="GET" action="{{ route('show.students') }}">
                                <input type="hidden" name="school_year"
                                    value="{{ $currentYear . '-' . ($currentYear + 1) }}">
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
                        <div class="card d-flex flex-column mb-4 grade-card" data-grade="{{ strtolower($grade) }}">
                            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                                <h5 class="fw-bold text-primary mb-0">{{ $grade }}</h5>
                                <a href="" class="btn btn-success btn-sm">
                                    <i class="bx bx-printer"></i> Export
                                </a>
                            </div>

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
                                            <th style="width: 15%;">Emergency Contact No.</th>
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
                                            <tr class="student-row"
                                                data-name="{{ strtolower($student->student_lName . ' ' . $student->student_fName . ' ' . $student->student_mName . ' ' . $student->student_extName) }}"
                                                data-section="{{ strtolower(optional($student->class->first())->section) }}"
                                                data-grade="{{ strtolower(optional($student->class->first())->formatted_grade_level) }}"
                                                data-lrn="{{ strtolower($student->student_lrn) }}">
                                                <td>{{ $student->student_lName }},
                                                    {{ $student->student_fName }}
                                                    {{ $student->student_mName }}
                                                    {{ $student->student_extName }}</td>
                                                <td>
                                                    @if ($student->student_photo)
                                                        <img src="{{ asset('storage/' . $student->student_photo) }}"
                                                            alt="Profile Photo" width="30" height="30"
                                                            style="object-fit: cover; border-radius: 50%;">
                                                    @else
                                                        <img src="{{ asset('assetsDashboard/img/student_profile_pictures/student_default_profile.jpg') }}"
                                                            alt="No Profile" width="30" height="30"
                                                            style="object-fit: cover; border-radius: 50%;">
                                                    @endif
                                                </td>
                                                <td>{{ $student->student_lrn }}</td>
                                                <td>{{ optional($student->class->first())->formatted_grade_level }}
                                                    - {{ optional($student->class->first())->section }}</td>
                                                <td>
                                                    @php
                                                        $status =
                                                            optional($student->class->first())->pivot
                                                                ->enrollment_status ?? 'N/A';
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
                                                            optional($student->class->first())->pivot
                                                                ->enrollment_type ?? 'N/A';
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
                                                <td>{{ $student->parentInfo->emergCont_phone ?? 'N/A' }}</td>
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
                                                                <a class="dropdown-item text-warning"
                                                                    href="{{ route('edit.student', ['id' => $student->id]) }}">
                                                                    <i class="bx bx-edit-alt me-1"></i> Edit
                                                                </a>
                                                                <button type="button" class="dropdown-item text-danger"
                                                                    onclick="confirmUnenroll({{ $student->id }}, '{{ $student->student_fName }}', '{{ $student->student_lName }}')">
                                                                    <i class="bx bx-user-x me-1"></i> Unenroll
                                                                </button>
                                                                <form id="unenroll-form-{{ $student->id }}"
                                                                    action="{{ route('unenroll.student', $student->id) }}"
                                                                    method="POST" style="display: none;">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                </form>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr class="text-muted">
                                                <td colspan="7">No students enrolled in {{ $grade }}.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <!-- Pagination for this grade -->
                            <div class="d-flex justify-content-start px-3 py-2 border-top">
                                <nav aria-label="Page navigation">
                                    <ul class="pagination mb-0" id="pagination-{{ Str::slug($grade) }}"></ul>
                                </nav>
                            </div>
                        </div>
                    @endforeach
                    {{-- / Card --}}

                    <hr class="my-5" />

                </div>
                <!-- / Content wrapper -->

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
        // pagination function
        const paginators = {}; // Global reference for pagination functions
        const visibleRowsMap = {}; // Store filtered rows for each table

        function paginateTable(tableId, paginationId, rowsPerPage = 5, maxVisiblePages = 5) {
            const table = document.getElementById(tableId);
            const pagination = document.getElementById(paginationId);

            function showPage(page) {
                const rows = visibleRowsMap[tableId] || Array.from(table.querySelectorAll("tbody tr"));

                const totalPages = Math.ceil(rows.length / rowsPerPage);
                const start = (page - 1) * rowsPerPage;
                const end = start + rowsPerPage;

                rows.forEach((row, index) => {
                    row.style.display = (index >= start && index < end) ? "" : "none";
                });

                pagination.innerHTML = "";
                const ul = document.createElement("ul");
                ul.className = "pagination mb-0";

                const prev = document.createElement("li");
                prev.className = `page-item prev ${page === 1 ? 'disabled' : ''}`;
                prev.innerHTML =
                    `<a class="page-link text-primary" href="javascript:void(0);"><i class="tf-icon bx bx-chevrons-left"></i></a>`;
                prev.onclick = () => page > 1 && showPage(page - 1);
                ul.appendChild(prev);

                let startPage = Math.max(1, page - Math.floor(maxVisiblePages / 2));
                let endPage = Math.min(totalPages, startPage + maxVisiblePages - 1);
                if (endPage - startPage + 1 < maxVisiblePages) {
                    startPage = Math.max(1, endPage - maxVisiblePages + 1);
                }

                for (let i = startPage; i <= endPage; i++) {
                    const li = document.createElement("li");
                    li.className = `page-item ${i === page ? 'active' : ''}`;
                    li.innerHTML =
                        `<a class="page-link ${i === page ? 'bg-primary text-white border-primary' : 'text-primary'}" href="javascript:void(0);">${i}</a>`;
                    li.onclick = () => showPage(i);
                    ul.appendChild(li);
                }

                const next = document.createElement("li");
                next.className = `page-item next ${page === totalPages ? 'disabled' : ''}`;
                next.innerHTML =
                    `<a class="page-link text-primary" href="javascript:void(0);"><i class="tf-icon bx bx-chevrons-right"></i></a>`;
                next.onclick = () => page < totalPages && showPage(page + 1);
                ul.appendChild(next);

                pagination.appendChild(ul);
            }

            paginators[tableId] = () => showPage(1);

            // On load, store all rows
            visibleRowsMap[tableId] = Array.from(table.querySelectorAll("tbody tr.student-row"));
            showPage(1);
        }

        document.addEventListener("DOMContentLoaded", () => {
            const gradeLevels = @json(array_keys($groupedStudents->toArray()));
            gradeLevels.forEach(grade => {
                const slug = grade.toLowerCase().replace(/\s+/g, '-');
                paginateTable(`table-${slug}`, `pagination-${slug}`);
            });
        });
    </script>

    <script>
        // Search functionality
        document.addEventListener("DOMContentLoaded", function() {
            const searchInput = document.getElementById("studentSearch");
            const noResultsMessage = document.getElementById("noResultsMessage");

            searchInput.addEventListener("input", function() {
                const query = this.value.trim().toLowerCase();
                const gradeCards = document.querySelectorAll(".grade-card");

                let overallMatch = false;

                gradeCards.forEach(card => {
                    const table = card.querySelector("table");
                    const rows = table.querySelectorAll("tbody tr.student-row");
                    const noStudentsRow = table.querySelector("tr.no-students-row");
                    const pagination = card.querySelector(".pagination");
                    const tableId = table.id;

                    let anyMatch = false;

                    rows.forEach(row => {
                        const name = row.dataset.name;
                        const grade = row.dataset.grade;
                        const section = row.dataset.section;
                        const lrn = row.dataset.lrn;
                        const gradeSection = `${grade} - ${section}`;

                        const isMatch =
                            query === "" ||
                            name.includes(query) ||
                            grade.includes(query) ||
                            section.includes(query) ||
                            lrn.includes(query) ||
                            gradeSection.includes(query);

                        row.style.display = isMatch ? "table-row" : "none";
                        if (isMatch) anyMatch = true;
                    });

                    visibleRowsMap[tableId] = Array.from(rows).filter(row => row.style.display !==
                        "none");

                    if (anyMatch) {
                        card.classList.remove("d-none");
                        if (noStudentsRow) noStudentsRow.classList.add("d-none");
                        if (paginators[tableId]) paginators[tableId]();
                        overallMatch = true;
                    } else {
                        card.classList.add("d-none");
                        if (pagination) pagination.innerHTML = "";
                    }
                });

                // Show/hide "No search found" message
                if (!overallMatch && query !== "") {
                    noResultsMessage.classList.remove("d-none");
                } else {
                    noResultsMessage.classList.add("d-none");
                }

                // Reset everything if query is empty
                if (query === "") {
                    gradeCards.forEach(card => {
                        card.classList.remove("d-none");

                        const table = card.querySelector("table");
                        const rows = table.querySelectorAll("tbody tr.student-row");
                        const noStudentsRow = table.querySelector("tr.no-students-row");
                        const tableId = table.id;

                        rows.forEach(row => {
                            row.style.display = "table-row";
                        });

                        if (noStudentsRow) noStudentsRow.classList.add("d-none");

                        visibleRowsMap[tableId] = Array.from(rows);
                        if (paginators[tableId]) paginators[tableId]();
                    });
                }
            });
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
    </style>
@endpush
