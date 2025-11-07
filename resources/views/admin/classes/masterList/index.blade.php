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
                            href="{{ route('classes.showClass', ['grade_level' => $class->grade_level, 'section' => $class->section]) }}?school_year={{ $selectedYear }}">
                            {{ ucfirst($class->grade_level) }} - {{ $class->section }} ({{ $selectedYear }})
                        </a> /
                    </span>
                    Master List
                </h4>
            </div>
        </div>

        <h3 class="mb-1 text-center fw-bold text-info">Class Master List of {{ $class->formatted_grade_level }} -
            {{ $class->section }} ({{ $selectedYear }})</h3><br>

        <div class="d-flex justify-content-between align-items-center">
            <a href="{{ url()->previous() }}" class="btn btn-danger mb-3 d-flex align-items-center">
                <i class='bx bx-chevrons-left'></i>
                <span class="d-none d-sm-block">Back</span>
            </a>
        </div>

        <div class="card p-3 shadow-sm mb-2">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">

                <!-- Adviser Info -->
                <div class="d-flex align-items-center">
                    @if ($class->adviser)
                        <!-- Adviser Details -->
                        <div class="d-flex align-items-center">
                            <!-- Profile Photo -->
                            <div class="position-relative me-3">
                                <img src="{{ $class->adviser->profile_photo
                                    ? asset('public/uploads/' . $class->adviser->profile_photo)
                                    : asset('assetsDashboard/img/profile_pictures/teacher_default_profile.jpg') }}"
                                    alt="Adviser Photo" style="width: 65px; height: 65px; object-fit: cover;">
                            </div>

                            <!-- Adviser Info (Name + Email + Label) -->
                            <div class="d-flex flex-column">
                                <h5 class="text-info fw-bold mb-1">
                                    {{ $class->adviser->firstName ?? 'N/A' }} {{ $class->adviser->lastName ?? '' }}
                                </h5>
                                <p class="text-muted mb-1 small">
                                    {{ $class->adviser->email ?? 'No email available' }}
                                </p>
                                <span class="text-secondary fw-semibold small">Adviser</span>
                            </div>
                        </div>
                    @else
                        <h6 class="text-danger mb-0">No adviser assigned</h6>
                    @endif
                </div>
            </div>
        </div>

        <div class="card p-4 shadow-sm">
            <!-- Header Controls -->
            <div class="row g-3 justify-content-between align-items-center">
                <!-- Search Box -->
                <div class="col-md-12 col-sm-6">
                    <div class="input-group">
                        <span class="input-group-text bg-transparent border-end-0">
                            <i class="bx bx-search fs-5"></i>
                        </span>
                        <input type="text" id="masterListSearch" class="form-control border-start-0 shadow-none"
                            placeholder="Search by Name or LRN..." aria-label="Search...">
                    </div>
                </div>
            </div>

            <hr class="my-4" />

            <div class="d-flex justify-content-between align-items-center flex-wrap mb-3">
                <!-- Table Length Selector (Start) -->
                <div class="d-flex align-items-center mb-2">
                    <label class="me-2 mb-0 d-none d-sm-block">Show</label>
                    <select class="form-select w-auto" id="tableLength">
                        <option value="5" selected>5</option>
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="all">All</option>
                    </select>
                    <label class="ms-2 mb-0 d-none d-sm-block">entries</label>
                </div>

                <!-- Gender Filter (End) -->
                <div class="d-flex justify-content-end mb-2">
                    <select id="genderFilter" class="form-select w-auto">
                        <option value="all">All Genders</option>
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                    </select>
                </div>
            </div>

            <!-- No Results Message -->
            <div id="noResultsMessage" class="alert alert-info text-center d-none">
                No students found matching your search.
            </div>

            <!-- Main Table -->
            <div class="table-responsive">
                <table class="table table-hover table-bordered text-center" id="masterListTable">
                    <thead class="table-primary align-middle text-center">
                        <tr>
                            <th rowspan="2" style="width: 5%;" class="index-column">NO.</th>
                            <th rowspan="2" style="width: 10%;">PHOTO</th>
                            <th rowspan="2">NAME</th>
                            <th rowspan="2">LRN</th>
                            <th colspan="4" class="text-center">Grade Preview Permission For Parents</th>
                        </tr>
                        <tr>
                            <th style="width: 5%;" class="text-center">Q1</th>
                            <th style="width: 5%;" class="text-center">Q2</th>
                            <th style="width: 5%;" class="text-center">Q3</th>
                            <th style="width: 5%;" class="text-center">Q4</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $allStudents = $students->sortBy(function ($student) {
                                return $student->student_lName .
                                    ' ' .
                                    $student->student_fName .
                                    ' ' .
                                    $student->student_mName;
                            });
                        @endphp

                        @foreach ($allStudents as $index => $student)
                            @php
                                $classStudent = $student->classStudents
                                    ->where('class_id', $class->id)
                                    ->where('school_year_id', $schoolYearId)
                                    ->first();
                            @endphp
                            <tr class="student-row"
                                data-name="{{ strtolower($student->student_lName . ' ' . $student->student_fName . ' ' . $student->student_mName . ' ' . $student->student_extName) }}"
                                data-lrn="{{ strtolower($student->student_lrn) }}"
                                data-gender="{{ strtolower($student->student_sex) }}"
                                data-student-id="{{ $student->id }}">
                                <td class="text-center index-column">{{ $index + 1 }}</td>
                                <td class="text-center">
                                    <a
                                        href="{{ route('student.info', ['id' => $student->id, 'school_year' => $schoolYearId]) }}">
                                        <img src="{{ $student->student_photo ? asset('public/uploads/' . $student->student_photo) : asset('assetsDashboard/img/student_profile_pictures/student_default_profile.jpg') }}"
                                            alt="Student Photo" class="rounded-circle me-2 student-photo"
                                            style="width: 40px; height: 40px;">
                                    </a>
                                </td>
                                <td class="text-start">
                                    <div class="d-flex align-items-center">
                                        <a href="{{ route('student.info', ['id' => $student->id, 'school_year' => $schoolYearId]) }}"
                                            class="text-decoration-none text-dark d-flex align-items-center">
                                            {{ $student->student_lName }}, {{ $student->student_fName }}
                                            {{ $student->student_extName }}
                                            @if (!empty($student->student_mName))
                                                {{ strtoupper(substr($student->student_mName, 0, 1)) }}.
                                            @endif
                                            <span class="ms-2 gender-icon">
                                                @if ($student->student_sex === 'male')
                                                    <i class="bx bx-male text-primary fs-5" title="Male"></i>
                                                @elseif ($student->student_sex === 'female')
                                                    <i class="bx bx-female text-pink fs-5" title="Female"></i>
                                                @else
                                                    <i class="bx bx-user fs-5 text-muted" title="Unknown"></i>
                                                @endif
                                            </span>
                                        </a>
                                    </div>
                                </td>
                                <td class="text-center">
                                    {{ $student->student_lrn }}
                                </td>
                                @if ($classStudent)
                                    <td class="text-center">
                                        <div class="grade-permission-checkboxes">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input grade-permission" type="checkbox"
                                                    data-student-id="{{ $student->id }}" data-quarter="q1"
                                                    {{ $classStudent->q1_allow_view ? 'checked' : '' }} disabled>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="grade-permission-checkboxes">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input grade-permission" type="checkbox"
                                                    data-student-id="{{ $student->id }}" data-quarter="q2"
                                                    {{ $classStudent->q2_allow_view ? 'checked' : '' }} disabled>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="grade-permission-checkboxes">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input grade-permission" type="checkbox"
                                                    data-student-id="{{ $student->id }}" data-quarter="q3"
                                                    {{ $classStudent->q3_allow_view ? 'checked' : '' }} disabled>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="grade-permission-checkboxes">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input grade-permission" type="checkbox"
                                                    data-student-id="{{ $student->id }}" data-quarter="q4"
                                                    {{ $classStudent->q4_allow_view ? 'checked' : '' }} disabled>
                                            </div>
                                        </div>
                                    </td>
                                @else
                                    <td colspan="4" class="text-center text-muted">Not enrolled</td>
                                @endif
                            </tr>
                        @endforeach

                        @if ($allStudents->isEmpty())
                            <tr>
                                <td colspan="8" class="text-center text-muted py-4">No students
                                    enrolled in this class.
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>

            <!-- Pagination, table length, and info -->
            <div class="d-flex justify-content-between align-items-center flex-wrap px-3 py-2 border-top">

                <!-- Table info text -->
                <div id="tableInfo" class="text-muted text-start small mb-2 mb-md-0">
                    Showing 1 to {{ min(5, $allStudents->count()) }} of {{ $allStudents->count() }} entries
                </div>

                <!-- Pagination -->
                <nav aria-label="Page navigation">
                    <ul class="pagination mb-0" id="pagination"></ul>
                </nav>
            </div>
        </div>
    </div>
    <!-- End Content wrapper -->

@endsection

@push('scripts')
    <!-- Include Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- DataTables JS + CSS -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>

    <!-- Logout Confirmation Script -->
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

    <!-- Table Pagination and Search Script -->
    <script>
        let currentPage = 1;
        let rowsPerPage = 5;
        let filteredRows = [];

        function initializeTable() {
            const table = document.getElementById('masterListTable');
            const allRows = Array.from(table.querySelectorAll('tbody tr.student-row'));
            filteredRows = allRows;

            updateTableDisplay();
        }

        function updateTableDisplay() {
            const table = document.getElementById('masterListTable');
            const allRows = Array.from(table.querySelectorAll('tbody tr.student-row'));
            const noResultsMessage = document.getElementById('noResultsMessage');
            const tableInfo = document.getElementById('tableInfo');

            // Apply filters
            const searchTerm = document.getElementById('masterListSearch').value.toLowerCase();
            const genderFilter = document.getElementById('genderFilter').value;

            filteredRows = allRows.filter(row => {
                const name = row.dataset.name;
                const lrn = row.dataset.lrn;
                const gender = row.dataset.gender;

                const matchesSearch = !searchTerm ||
                    name.includes(searchTerm) ||
                    lrn.includes(searchTerm);

                const matchesGender = genderFilter === 'all' || gender === genderFilter;

                return matchesSearch && matchesGender;
            });

            // Show/hide no results message
            if (filteredRows.length === 0 && (searchTerm || genderFilter !== 'all')) {
                noResultsMessage.classList.remove('d-none');
            } else {
                noResultsMessage.classList.add('d-none');
            }

            // Hide all rows first
            allRows.forEach(row => row.style.display = 'none');

            // Calculate pagination
            const totalRows = filteredRows.length;
            const totalPages = rowsPerPage === 'all' ? 1 : Math.ceil(totalRows / rowsPerPage);
            currentPage = Math.min(currentPage, totalPages || 1);

            const startIndex = rowsPerPage === 'all' ? 0 : (currentPage - 1) * rowsPerPage;
            const endIndex = rowsPerPage === 'all' ? totalRows : startIndex + rowsPerPage;

            // Show rows for current page
            filteredRows.forEach((row, index) => {
                if (index >= startIndex && index < endIndex) {
                    row.style.display = '';
                    // Update row numbers
                    const numberCell = row.querySelector('td:first-child');
                    if (numberCell) {
                        numberCell.textContent = startIndex + index + 1;
                    }
                }
            });

            // Update table info
            const startEntry = totalRows === 0 ? 0 : startIndex + 1;
            const endEntry = Math.min(endIndex, totalRows);
            tableInfo.textContent = `Showing ${startEntry} to ${endEntry} of ${totalRows} entries`;

            // Update pagination
            updatePagination(totalPages);
        }

        function updatePagination(totalPages) {
            const pagination = document.getElementById('pagination');
            pagination.innerHTML = '';

            if (rowsPerPage === 'all' || totalPages <= 1) {
                return;
            }

            // Previous button
            const prevLi = document.createElement('li');
            prevLi.className = `page-item prev ${currentPage === 1 ? 'disabled' : ''}`;
            prevLi.innerHTML =
                `<a class="page-link text-primary" href="javascript:void(0);"><i class="tf-icon bx bx-chevrons-left"></i></a>`;
            prevLi.onclick = () => {
                if (currentPage > 1) {
                    currentPage--;
                    updateTableDisplay();
                }
            };
            pagination.appendChild(prevLi);

            // Page numbers
            const maxVisiblePages = 5;
            let startPage = Math.max(1, currentPage - Math.floor(maxVisiblePages / 2));
            let endPage = Math.min(totalPages, startPage + maxVisiblePages - 1);

            if (endPage - startPage + 1 < maxVisiblePages) {
                startPage = Math.max(1, endPage - maxVisiblePages + 1);
            }

            for (let i = startPage; i <= endPage; i++) {
                const pageLi = document.createElement('li');
                pageLi.className = `page-item ${i === currentPage ? 'active' : ''}`;
                pageLi.innerHTML =
                    `<a class="page-link ${i === currentPage ? 'bg-primary text-white border-primary' : 'text-primary'}" href="javascript:void(0);">${i}</a>`;
                pageLi.onclick = () => {
                    currentPage = i;
                    updateTableDisplay();
                };
                pagination.appendChild(pageLi);
            }

            // Next button
            const nextLi = document.createElement('li');
            nextLi.className = `page-item next ${currentPage === totalPages ? 'disabled' : ''}`;
            nextLi.innerHTML =
                `<a class="page-link text-primary" href="javascript:void(0);"><i class="tf-icon bx bx-chevrons-right"></i></a>`;
            nextLi.onclick = () => {
                if (currentPage < totalPages) {
                    currentPage++;
                    updateTableDisplay();
                }
            };
            pagination.appendChild(nextLi);
        }

        // Event Listeners
        document.addEventListener('DOMContentLoaded', function() {
            initializeTable();

            // Search input
            document.getElementById('masterListSearch').addEventListener('input', function() {
                currentPage = 1;
                updateTableDisplay();
            });

            // Gender filter
            document.getElementById('genderFilter').addEventListener('change', function() {
                currentPage = 1;
                updateTableDisplay();
            });

            // Table length
            document.getElementById('tableLength').addEventListener('change', function() {
                rowsPerPage = this.value === 'all' ? 'all' : parseInt(this.value);
                currentPage = 1;
                updateTableDisplay();
            });
        });
    </script>
@endpush

@push('styles')
    <!-- Main CSS File -->
    <link href="{{ asset('assets/css/main.css') }}" rel="stylesheet" />
    <link href="{{ asset('assetsDashboard/vendor/css/core.css') }}" rel="stylesheet" />
    <link href="{{ asset('assetsDashboard/vendor/css/theme-default.css') }}" rel="stylesheet" />

    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">

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

        .grade-permission-checkboxes {
            display: flex;
            justify-content: center;
            gap: 10px;
        }

        .form-check-inline {
            margin-right: 0;
        }

        /* Make all checkboxes show a pointer cursor on hover */
        .form-check-input {
            cursor: not-allowed;
        }

        /* Optional: also make their labels clickable */
        .form-check-label {
            cursor: pointer;
        }

        th .form-check-inline {
            margin: 0 4px;
        }

        th .form-check-label {
            font-size: 0.8rem;
        }

        .text-pink {
            color: #e83e8c !important;
        }

        .student-row:hover {
            background-color: #f8f9fa;
            cursor: default;
        }

        .gender-icon {
            opacity: 0.8;
            transition: opacity 0.2s ease;
        }

        .gender-icon:hover {
            opacity: 1;
        }

        .checkbox-column {
            vertical-align: middle;
        }

        .student-checkbox {
            transform: scale(1.2);
        }

        /* Select All header styling */
        .checkbox-column-header .form-check {
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0;
            height: 100%;
        }

        .checkbox-column-header .form-check-input {
            margin-right: 8px;
            transform: scale(1.1);
        }

        .checkbox-column-header .form-check-label {
            font-size: 0.85rem;
            font-weight: bold;
            white-space: nowrap;
        }

        /* Ensure proper vertical alignment for all header cells */
        .table-primary th {
            vertical-align: middle !important;
        }
    </style>
@endpush
