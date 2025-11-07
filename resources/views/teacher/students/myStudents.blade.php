@extends('./layouts.main')

@section('title', 'Teacher | My Students')

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
            <li class="menu-item active open">
                <a class="menu-link menu-toggle ">
                    <i class="menu-icon tf-icons bx bxs-graduation"></i>
                    <div>Students</div>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item active">
                        <a href="{{ route('teacher.my.students') }}" class="menu-link bg-dark text-light">
                            <div class="text-warning">My Students</div>
                        </a>
                    </li>
                </ul>
            </li>

            {{-- Classes sidebar --}}
            <li class="menu-item">
                <a class="menu-link menu-toggle bg-dark text-light">
                    <i class="menu-icon tf-icons bx bx-notepad text-light"></i>
                    <div class="text-light">Classes</div>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item">
                        <a href="{{ route('teacher.myClasses') }}" class="menu-link bg-dark text-light">
                            <div class="text-light">My Class</div>
                        </a>
                    </li>
                </ul>
            </li>

            {{-- Account Settings sidebar --}}
            <li class="menu-item">
                <a href="{{ route('teacher.account.settings') }}" class="menu-link bg-dark text-light">
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
                <a class="text-muted fw-light" href="{{ route('teacher.my.students') }}">
                    Students /
                </a>
            </span> My Students
        </h4>

        <div class="text-center mb-3">
            <h3 class="text-primary">
                All Students Enrolled in School Year: {{ $selectedYear }}
            </h3>
        </div>

        <div class="row mb-3 align-items-center">

            <div class="d-flex justify-content-between flex-wrap align-items-center">
                {{-- Search Bar --}}
                <div class="d-flex gap-1 mb-2 mb-md-0 d-flex align-items-center">
                    <i class="bx bx-search fs-4 lh-0 me-2 d-none d-sm-block"></i>
                    <input type="text" id="studentSearch" class="form-control border-1 shadow-none"
                        placeholder="Search..." aria-label="Search..." />
                </div>

                {{-- School Year Selection --}}
                <div class="d-flex align-items-center">
                    <form method="GET" action="{{ route('teacher.my.students') }}" class="d-flex align-items-center">
                        <label for="school_year" class="form-label d-none d-sm-block mb-0 me-2">School
                            Year:</label>
                        <select name="school_year" id="school_year" class="form-select me-2" onchange="this.form.submit()">
                            @foreach ($schoolYears as $year)
                                <option value="{{ $year }}" {{ $year == $selectedYear ? 'selected' : '' }}>
                                    {{ $year }}
                                </option>
                            @endforeach
                        </select>
                    </form>


                    {{-- "Now" button --}}
                    <form class="d-flex align-items-center" method="GET" action="{{ route('teacher.my.students') }}">
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
        </div>

        <div id="noResultsMessage" class="alert alert-info text-center d-none">
            No search found.
        </div>

        {{-- Show alert if no students enrolled for this school year --}}
        @if ($groupedStudents->isEmpty())
            <div class="alert alert-warning text-center">
                No enrolled students found for School Year
                <strong>{{ $selectedYear }}</strong>.
                <br>
                <span class="text-primary">
                    Please check back later or contact the administration for more information.
                </span>
            </div>
        @endif

        {{-- Card --}}
        @foreach ($groupedStudents as $grade => $students)
            <div class="card d-flex flex-column mb-4 grade-card" data-grade="{{ strtolower($grade) }}">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold text-primary mb-0">{{ $grade }}</h5>
                    {{-- <a href="" class="btn btn-success btn-sm">
                        <i class="bx bx-printer"></i> Export
                    </a> --}}
                </div>

                <div class="table-responsive text-nowrap">
                    <table class="table table-hover table-bordered text-center mb-0" id="table-{{ Str::slug($grade) }}">
                        <thead>
                            <tr>
                                <th style="min-width: 220px;">Full Name</th>
                                <th style="width: 10%;">Photo</th>
                                <th style="width: 15%;">LRN</th>
                                <th style="width: 20%;">Grade & Section</th>
                                <th style="width: 15%;">Enrollment Status</th>
                                <th style="width: 15%;">Enrollment Type</th>
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
                                    data-href="{{ route('teacher.student.info', ['id' => $student->id, 'school_year' => $schoolYearId]) }}"
                                    data-name="{{ strtolower($student->student_lName . ' ' . $student->student_fName . ' ' . $student->student_mName . ' ' . $student->student_extName) }}"
                                    data-section="{{ strtolower(optional($student->class->first())->section) }}"
                                    data-grade="{{ strtolower(optional($student->class->first())->formatted_grade_level) }}"
                                    data-lrn="{{ strtolower($student->student_lrn) }}"
                                    data-enrollment_status="{{ strtolower(optional($student->class->first())->pivot->enrollment_status ?? '') }}"
                                    data-enrollment_type="{{ strtolower(optional($student->class->first())->pivot->enrollment_type ?? '') }}">
                                    <td>
                                        <a href="{{ route('teacher.student.info', ['id' => $student->id, 'school_year' => $schoolYearId]) }}"
                                            class="text-decoration-none text-dark fw-semibold">
                                            {{ $student->student_lName }},
                                            {{ $student->student_fName }}
                                            {{ $student->student_mName }}
                                            {{ $student->student_extName }}
                                        </a>
                                    </td>

                                    <td>
                                        <a
                                            href="{{ route('teacher.student.info', ['id' => $student->id, 'school_year' => $schoolYearId]) }}">
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
                                    <td>{{ optional($student->class->first())->formatted_grade_level }}
                                        - {{ optional($student->class->first())->section }}</td>
                                    <td>
                                        @php
                                            $status =
                                                optional($student->class->first())->pivot->enrollment_status ?? 'N/A';
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
                                            $type = optional($student->class->first())->pivot->enrollment_type ?? 'N/A';
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
                                </tr>
                            @empty
                                <tr class="text-muted">
                                    <td colspan="7">No students enrolled in {{ $grade }}.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination, table length, and info -->
                <div class="d-flex justify-content-between align-items-center flex-wrap px-3 py-2 border-top">
                    <!-- Table length -->
                    <div class="d-flex align-items-center mb-2 mb-md-0">
                        <label class="me-2 mb-0">Show</label>
                        <select class="form-select form-select-sm w-auto" id="length-{{ Str::slug($grade) }}">
                            <option value="5" selected>5</option>
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="all">All</option>
                        </select>
                        <label class="ms-2 mb-0">entries</label>
                    </div>

                    <!-- Table info text -->
                    <div id="table-info-{{ Str::slug($grade) }}" class="text-muted small mb-2 mb-md-0">
                        Showing 1 to 5 of {{ count($students) }} entries
                    </div>

                    <!-- Pagination -->
                    <nav aria-label="Page navigation">
                        <ul class="pagination mb-0" id="pagination-{{ Str::slug($grade) }}"></ul>
                    </nav>
                </div>
            </div>
        @endforeach
        {{-- / Card --}}

        <hr class="my-5" />

    </div>
    <!-- Content wrapper -->

@endsection

@push('scripts')
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

    <script>
        const paginators = {}; // Global pagination references
        const visibleRowsMap = {}; // Store filtered rows per table
        const currentPageMap = {}; // Track current page per table
        const tableLengthMap = {}; // Track selected rows per page

        function paginateTable(tableId, paginationId, rowsPerPage = 5, maxVisiblePages = 5) {
            const table = document.getElementById(tableId);
            const pagination = document.getElementById(paginationId);
            const gradeSlug = tableId.replace('table-', '');
            const info = document.getElementById(`table-info-${gradeSlug}`);
            const lengthSelect = document.getElementById(`length-${gradeSlug}`);

            tableLengthMap[tableId] = rowsPerPage;
            currentPageMap[tableId] = 1;

            function showPage(page) {
                const rows = visibleRowsMap[tableId] || Array.from(table.querySelectorAll("tbody tr"));
                const totalRows = rows.length;
                const perPage = tableLengthMap[tableId] === "all" ? totalRows : parseInt(tableLengthMap[tableId]);
                const totalPages = Math.ceil(totalRows / perPage);
                const start = (page - 1) * perPage;
                const end = start + perPage;

                rows.forEach((row, index) => {
                    row.style.display = (index >= start && index < end) ? "" : "none";
                });

                // Update table info text
                const startEntry = totalRows === 0 ? 0 : start + 1;
                const endEntry = Math.min(end, totalRows);
                info.textContent = `Showing ${startEntry} to ${endEntry} of ${totalRows} entries`;

                // Build pagination
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
                currentPageMap[tableId] = page;
            }

            // Handle change in table length
            if (lengthSelect) {
                lengthSelect.addEventListener("change", function() {
                    tableLengthMap[tableId] = this.value;
                    showPage(1);
                });
            }

            // On load
            visibleRowsMap[tableId] = Array.from(table.querySelectorAll("tbody tr.student-row"));
            showPage(1);
            paginators[tableId] = () => showPage(1);
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
                        const enrollment_status = row.dataset.enrollment_status;
                        const enrollment_type = row.dataset.enrollment_type;
                        const gradeSection = `${grade} - ${section}`;

                        const isMatch =
                            query === "" ||
                            name.includes(query) ||
                            grade.includes(query) ||
                            section.includes(query) ||
                            lrn.includes(query) ||
                            enrollment_status.includes(query) ||
                            enrollment_type.includes(query) ||
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
@endpush
