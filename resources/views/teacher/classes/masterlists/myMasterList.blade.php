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

            {{-- Account Settings sidebar --}}
            <li class="menu-item">
                <a href="{{ route('teacher.account.settings') }}" class="menu-link bg-dark text-light">
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

        <h3 class="mb-1 text-center fw-bold text-info">Class Master List of {{ $class->formatted_grade_level }} -
            {{ $class->section }} ({{ $selectedYear }})</h3><br>

        <div class="d-flex justify-content-between align-items-center">
            <a href="{{ route('teacher.myClass', ['grade_level' => $class->grade_level, 'section' => $class->section, 'school_year' => $selectedYear]) }}"
                class="btn btn-danger mb-3 d-flex align-items-center">
                <i class='bx bx-chevrons-left'></i>
                <span class="d-none d-sm-block">Back</span>
            </a>

            <!-- Bulk Print Buttons -->
            <div class="d-flex gap-2">
                <button id="bulkPrintBtn" class="btn btn-success mb-3 d-flex align-items-center d-none">
                    <i class='bx bx-printer me-1'></i>
                    <span class="d-none d-sm-block">Print Selected Grades</span>
                </button>

                <button id="toggleCheckboxes" class="btn btn-warning mb-3 d-flex align-items-center">
                    <i class='bx bx-check-square me-1'></i>
                    <span class="d-none d-sm-block">Select Students for Printing</span>
                </button>
            </div>
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
                                    ? asset('storage/' . $class->adviser->profile_photo)
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
                            <th rowspan="2" style="width: 5%;" class="checkbox-column d-none">SELECT</th>
                            <th rowspan="2" style="width: 10%;">PHOTO</th>
                            <th rowspan="2">NAME</th>
                            <th rowspan="2">LRN</th>
                            @if ($isAdviser)
                                <th colspan="4" class="text-center">Grade Preview Permission For Parents</th>
                            @endif
                        </tr>
                        @if ($isAdviser)
                            <tr>
                                <th style="width: 5%;">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input select-all" type="checkbox" data-quarter="q1"
                                            id="selectAllQ1">
                                        <label class="form-check-label fw-bold" for="selectAllQ1">Q1</label>
                                    </div>
                                </th>
                                <th style="width: 5%;">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input select-all" type="checkbox" data-quarter="q2"
                                            id="selectAllQ2">
                                        <label class="form-check-label fw-bold" for="selectAllQ2">Q2</label>
                                    </div>
                                </th>
                                <th style="width: 5%;">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input select-all" type="checkbox" data-quarter="q3"
                                            id="selectAllQ3">
                                        <label class="form-check-label fw-bold" for="selectAllQ3">Q3</label>
                                    </div>
                                </th>
                                <th style="width: 5%;">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input select-all" type="checkbox" data-quarter="q4"
                                            id="selectAllQ4">
                                        <label class="form-check-label fw-bold" for="selectAllQ4">Q4</label>
                                    </div>
                                </th>
                            </tr>
                        @endif
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
                                <td class="text-center checkbox-column d-none">
                                    <div class="form-check">
                                        <input class="form-check-input student-checkbox" type="checkbox"
                                            value="{{ $student->id }}"
                                            data-student-name="{{ $student->student_lName }}, {{ $student->student_fName }} {{ $student->student_extName }}">
                                    </div>
                                </td>
                                <td class="text-center">
                                    <a
                                        href="{{ route('teacher.student.info', ['id' => $student->id, 'school_year' => $schoolYearId]) }}">
                                        <img src="{{ $student->student_photo ? asset('storage/' . $student->student_photo) : asset('assetsDashboard/img/student_profile_pictures/student_default_profile.jpg') }}"
                                            alt="Student Photo" class="rounded-circle me-2 student-photo"
                                            style="width: 40px; height: 40px;">
                                    </a>
                                </td>
                                <td class="text-start">
                                    <div class="d-flex align-items-center">
                                        <a href="{{ route('teacher.student.info', ['id' => $student->id, 'school_year' => $schoolYearId]) }}"
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
                                @if ($isAdviser)
                                    @if ($classStudent)
                                        <td class="text-center">
                                            <div class="grade-permission-checkboxes">
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input grade-permission" type="checkbox"
                                                        data-student-id="{{ $student->id }}" data-quarter="q1"
                                                        {{ $classStudent->q1_allow_view ? 'checked' : '' }}>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="grade-permission-checkboxes">
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input grade-permission" type="checkbox"
                                                        data-student-id="{{ $student->id }}" data-quarter="q2"
                                                        {{ $classStudent->q2_allow_view ? 'checked' : '' }}>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="grade-permission-checkboxes">
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input grade-permission" type="checkbox"
                                                        data-student-id="{{ $student->id }}" data-quarter="q3"
                                                        {{ $classStudent->q3_allow_view ? 'checked' : '' }}>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="grade-permission-checkboxes">
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input grade-permission" type="checkbox"
                                                        data-student-id="{{ $student->id }}" data-quarter="q4"
                                                        {{ $classStudent->q4_allow_view ? 'checked' : '' }}>
                                                </div>
                                            </div>
                                        </td>
                                    @else
                                        <td colspan="4" class="text-center text-muted">Not enrolled</td>
                                    @endif
                                @endif
                            </tr>
                        @endforeach

                        @if ($allStudents->isEmpty())
                            <tr>
                                <td colspan="{{ $isAdviser ? 9 : 5 }}" class="text-center text-muted py-4">No students
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

    <!-- Grade Permission Toggle Script - Only load if user is adviser -->
    @if ($isAdviser)
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const checkboxes = document.querySelectorAll('.grade-permission');
                const selectAllCheckboxes = document.querySelectorAll('.select-all');
                const quarters = ['q1', 'q2', 'q3', 'q4'];

                // Function to update grade view permission (AJAX)
                async function updatePermission(studentId, quarter, isChecked, silent = false) {
                    if (!silent) {
                        Swal.fire({
                            title: 'Updating...',
                            text: 'Please wait',
                            allowOutsideClick: false,
                            customClass: {
                                container: 'my-swal-container'
                            },
                            didOpen: () => Swal.showLoading()
                        });
                    }

                    try {
                        const response = await fetch('{{ route('teacher.update.grade.permission') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json',
                            },
                            body: JSON.stringify({
                                student_id: studentId,
                                class_id: '{{ $class->id }}',
                                school_year_id: '{{ $schoolYearId }}',
                                quarter: quarter,
                                allow_view: isChecked
                            })
                        });

                        const data = await response.json();
                        if (!response.ok) throw new Error(data.message || 'Request failed');

                        if (!silent) {
                            Swal.close();
                            Swal.fire({
                                toast: true,
                                text: data.message,
                                position: 'top-end',
                                icon: 'success',
                                title: "Success!",
                                showConfirmButton: false,
                                showCloseButton: true,
                                timer: 3000,
                                timerProgressBar: true,
                                customClass: {
                                    container: 'my-swal-container'
                                }
                            });
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        Swal.close();
                        if (!silent) {
                            Swal.fire({
                                title: 'Error!',
                                text: error.message || 'Something went wrong. Please try again.',
                                icon: 'error',
                                customClass: {
                                    container: 'my-swal-container'
                                },
                            });
                        }
                    }
                }

                // Helper to sync "Select All" checkboxes with current student states
                function syncSelectAllState(quarter) {
                    const quarterCheckboxes = document.querySelectorAll(`.grade-permission[data-quarter="${quarter}"]`);
                    const selectAll = document.querySelector(`.select-all[data-quarter="${quarter}"]`);
                    if (selectAll && quarterCheckboxes.length > 0) {
                        const allChecked = Array.from(quarterCheckboxes).every(cb => cb.checked);
                        selectAll.checked = allChecked;
                    }
                }

                // Get filtered students based on current search and gender filter
                function getFilteredStudents(quarter) {
                    const searchTerm = document.getElementById('masterListSearch').value.toLowerCase();
                    const genderFilter = document.getElementById('genderFilter').value;
                    const allCheckboxes = document.querySelectorAll(`.grade-permission[data-quarter="${quarter}"]`);

                    return Array.from(allCheckboxes).filter(checkbox => {
                        const row = checkbox.closest('tr.student-row');
                        if (!row) return false;

                        const name = row.dataset.name;
                        const lrn = row.dataset.lrn;
                        const gender = row.dataset.gender;

                        const matchesSearch = !searchTerm ||
                            name.includes(searchTerm) ||
                            lrn.includes(searchTerm);

                        const matchesGender = genderFilter === 'all' || gender === genderFilter;

                        return matchesSearch && matchesGender;
                    });
                }

                // Get count of students by gender for accurate messaging
                function getStudentCountByGender(quarter) {
                    const filteredCheckboxes = getFilteredStudents(quarter);
                    let maleCount = 0;
                    let femaleCount = 0;

                    filteredCheckboxes.forEach(checkbox => {
                        const row = checkbox.closest('tr.student-row');
                        if (row) {
                            if (row.dataset.gender === 'male') maleCount++;
                            else if (row.dataset.gender === 'female') femaleCount++;
                        }
                    });

                    return {
                        total: filteredCheckboxes.length,
                        male: maleCount,
                        female: femaleCount
                    };
                }

                // Attach listener to individual student checkboxes
                checkboxes.forEach(checkbox => {
                    checkbox.addEventListener('change', function() {
                        const studentId = this.dataset.studentId;
                        const quarter = this.dataset.quarter;
                        const isChecked = this.checked;

                        updatePermission(studentId, quarter, isChecked);
                        syncSelectAllState(quarter); // real-time sync
                    });
                });

                // Handle Select All checkboxes with gender filter awareness
                selectAllCheckboxes.forEach(selectAll => {
                    selectAll.addEventListener('change', async function() {
                        const quarter = this.dataset.quarter;
                        const isChecked = this.checked;
                        const genderFilter = document.getElementById('genderFilter').value;

                        // Get filtered students based on current search and gender filter
                        const filteredCheckboxes = getFilteredStudents(quarter);
                        const studentCounts = getStudentCountByGender(quarter);

                        if (filteredCheckboxes.length === 0) {
                            Swal.fire({
                                title: 'No Students',
                                text: 'There are no students matching your current filters.',
                                icon: 'warning',
                                confirmButtonColor: "#3085d6",
                                customClass: {
                                    container: 'my-swal-container'
                                },
                            });
                            this.checked = !isChecked; // revert the checkbox
                            return;
                        }

                        let actionText = isChecked ? 'enable' : 'disable';
                        let targetDescription = '';

                        // Create accurate description based on current filters
                        if (genderFilter === 'all') {
                            targetDescription = `all ${studentCounts.total} students`;
                        } else if (genderFilter === 'male') {
                            targetDescription =
                                `all ${studentCounts.male} male student${studentCounts.male !== 1 ? 's' : ''}`;
                        } else if (genderFilter === 'female') {
                            targetDescription =
                                `all ${studentCounts.female} female student${studentCounts.female !== 1 ? 's' : ''}`;
                        }

                        const result = await Swal.fire({
                            title: `Are you sure?`,
                            text: `Do you want to ${actionText} ${quarter.toUpperCase()} grade viewing for ${targetDescription}?`,
                            icon: "question",
                            showCancelButton: true,
                            confirmButtonText: "Yes, proceed",
                            cancelButtonText: "Cancel",
                            confirmButtonColor: "#3085d6",
                            cancelButtonColor: "#d33",
                            customClass: {
                                container: 'my-swal-container'
                            },
                        });

                        if (result.isConfirmed) {
                            Swal.fire({
                                title: 'Updating...',
                                text: `Please wait while we update ${targetDescription}.`,
                                allowOutsideClick: false,
                                customClass: {
                                    container: 'my-swal-container'
                                },
                                didOpen: () => Swal.showLoading()
                            });

                            const promises = [];
                            filteredCheckboxes.forEach(cb => {
                                if (cb.checked !== isChecked) {
                                    cb.checked = isChecked;
                                    promises.push(updatePermission(cb.dataset.studentId,
                                        quarter, isChecked, true));
                                }
                            });

                            await Promise.all(promises);

                            Swal.close();
                            Swal.fire({
                                toast: true,
                                showConfirmButton: false,
                                timer: 3000,
                                timerProgressBar: true,
                                position: 'top-end',
                                showCloseButton: true,
                                title: 'Success!',
                                text: `${quarter.toUpperCase()} grade view permissions updated for ${targetDescription}.`,
                                icon: 'success',
                                customClass: {
                                    container: 'my-swal-container'
                                },
                            });

                            // Update the select all checkbox state
                            syncSelectAllState(quarter);
                        } else {
                            this.checked = !isChecked; // revert if cancelled
                        }
                    });
                });

                // Update select all checkboxes when filters change
                document.getElementById('masterListSearch').addEventListener('input', function() {
                    quarters.forEach(q => syncSelectAllState(q));
                });

                document.getElementById('genderFilter').addEventListener('change', function() {
                    quarters.forEach(q => syncSelectAllState(q));
                });

                // Initialize "Select All" checkboxes on page load
                quarters.forEach(q => syncSelectAllState(q));

                // Expose update function globally (for any external script use)
                window.updatePermission = updatePermission;
            });
        </script>
    @endif

    <!-- Bulk Print Grades Script -->
    <script>
        bulkPrintBtn.addEventListener('click', function() {
            const selectedStudents = Array.from(document.querySelectorAll('.student-checkbox:checked'));

            if (selectedStudents.length === 0) {
                Swal.fire({
                    title: 'No Students Selected',
                    text: 'Please select at least one student to print grades.',
                    icon: 'warning',
                    confirmButtonColor: "#3085d6",
                    customClass: {
                        container: 'my-swal-container'
                    },
                });
                return;
            }

            const studentIds = selectedStudents.map(checkbox => checkbox.value);
            const studentNames = selectedStudents.map(checkbox => checkbox.dataset.studentName);

            // Show confirmation with selected students
            Swal.fire({
                title: 'Print Grades for Selected Students?',
                html: `You are about to print grades for <strong>${selectedStudents.length}</strong> student(s):<br><br>
              <div style="max-height: 200px; overflow-y: auto; text-align: left;">
                ${studentNames.map(name => `• ${name}`).join('<br>')}
              </div>`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Yes, Print',
                cancelButtonText: 'Cancel',
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                customClass: {
                    container: 'my-swal-container'
                },
                width: '600px'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Show loading
                    Swal.fire({
                        title: 'Generating PDF...',
                        text: 'Please wait while we prepare the grade slips.',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        },
                        customClass: {
                            container: 'my-swal-container'
                        }
                    });

                    // Prepare the form data
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = '{{ route('teacher.bulk.print.grades') }}';
                    form.target = '_blank';

                    // Add CSRF token
                    const csrfToken = document.createElement('input');
                    csrfToken.type = 'hidden';
                    csrfToken.name = '_token';
                    csrfToken.value = '{{ csrf_token() }}';
                    form.appendChild(csrfToken);

                    // Add student IDs
                    studentIds.forEach(studentId => {
                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = 'student_ids[]';
                        input.value = studentId;
                        form.appendChild(input);
                    });

                    // Add class and school year info
                    const classInput = document.createElement('input');
                    classInput.type = 'hidden';
                    classInput.name = 'class_id';
                    classInput.value = '{{ $class->id }}';
                    form.appendChild(classInput);

                    const syInput = document.createElement('input');
                    syInput.type = 'hidden';
                    syInput.name = 'school_year_id';
                    syInput.value = '{{ $schoolYearId }}';
                    form.appendChild(syInput);

                    // Append form to body and submit
                    document.body.appendChild(form);
                    form.submit();
                    document.body.removeChild(form);

                    // Close loading
                    Swal.close();
                }
            });
        });
    </script>

    <!-- Bulk Print Toggle and Functionality Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toggleCheckboxesBtn = document.getElementById('toggleCheckboxes');
            const bulkPrintBtn = document.getElementById('bulkPrintBtn');
            const indexColumns = document.querySelectorAll('.index-column');
            const checkboxColumns = document.querySelectorAll('.checkbox-column');
            let checkboxesVisible = false;

            // Toggle checkboxes visibility
            toggleCheckboxesBtn.addEventListener('click', function() {
                checkboxesVisible = !checkboxesVisible;

                if (checkboxesVisible) {
                    // Show checkboxes, hide index numbers
                    indexColumns.forEach(col => col.classList.add('d-none'));
                    checkboxColumns.forEach(col => col.classList.remove('d-none'));
                    bulkPrintBtn.classList.remove('d-none');
                    toggleCheckboxesBtn.innerHTML =
                        '<i class="bx bx-x me-1"></i><span class="d-none d-sm-block">Cancel Selection</span>';
                    toggleCheckboxesBtn.classList.remove('btn-warning');
                    toggleCheckboxesBtn.classList.add('btn-secondary');
                } else {
                    // Hide checkboxes, show index numbers
                    indexColumns.forEach(col => col.classList.remove('d-none'));
                    checkboxColumns.forEach(col => col.classList.add('d-none'));
                    bulkPrintBtn.classList.add('d-none');
                    toggleCheckboxesBtn.innerHTML =
                        '<i class="bx bx-check-square me-1"></i><span class="d-none d-sm-block">Select Students for Printing</span>';
                    toggleCheckboxesBtn.classList.remove('btn-secondary');
                    toggleCheckboxesBtn.classList.add('btn-warning');

                    // Uncheck all checkboxes when hiding
                    document.querySelectorAll('.student-checkbox').forEach(checkbox => {
                        checkbox.checked = false;
                    });

                    // Hide bulk print button
                    bulkPrintBtn.classList.add('d-none');
                }
            });

            // Show/hide bulk print button based on checkbox selection
            document.addEventListener('change', function(e) {
                if (e.target.classList.contains('student-checkbox')) {
                    const selectedCount = document.querySelectorAll('.student-checkbox:checked').length;
                    if (selectedCount > 0 && checkboxesVisible) {
                        bulkPrintBtn.classList.remove('d-none');
                    } else {
                        bulkPrintBtn.classList.add('d-none');
                    }
                }
            });

            // Bulk print functionality
            bulkPrintBtn.addEventListener('click', function() {
                const selectedStudents = Array.from(document.querySelectorAll('.student-checkbox:checked'));

                if (selectedStudents.length === 0) {
                    Swal.fire({
                        title: 'No Students Selected',
                        text: 'Please select at least one student to print grades.',
                        icon: 'warning',
                        confirmButtonColor: "#3085d6",
                        customClass: {
                            container: 'my-swal-container'
                        },
                    });
                    return;
                }

                const studentIds = selectedStudents.map(checkbox => checkbox.value);
                const studentNames = selectedStudents.map(checkbox => checkbox.dataset.studentName);

                // Show confirmation with selected students
                Swal.fire({
                    title: 'Print Grades for Selected Students?',
                    html: `You are about to print grades for <strong>${selectedStudents.length}</strong> student(s):<br><br>
                      <div style="max-height: 200px; overflow-y: auto; text-align: left;">
                        ${studentNames.map(name => `• ${name}`).join('<br>')}
                      </div>`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, Print',
                    cancelButtonText: 'Cancel',
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    customClass: {
                        container: 'my-swal-container'
                    },
                    width: '600px'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Show loading
                        Swal.fire({
                            title: 'Generating PDF...',
                            text: 'Please wait while we prepare the grade slips.',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            },
                            customClass: {
                                container: 'my-swal-container'
                            }
                        });

                        // Prepare the form data
                        const form = document.createElement('form');
                        form.method = 'POST';
                        form.action = '{{ route('teacher.bulk.print.grades') }}';
                        form.target = '_blank';

                        // Add CSRF token
                        const csrfToken = document.createElement('input');
                        csrfToken.type = 'hidden';
                        csrfToken.name = '_token';
                        csrfToken.value = '{{ csrf_token() }}';
                        form.appendChild(csrfToken);

                        // Add student IDs
                        studentIds.forEach(studentId => {
                            const input = document.createElement('input');
                            input.type = 'hidden';
                            input.name = 'student_ids[]';
                            input.value = studentId;
                            form.appendChild(input);
                        });

                        // Add class and school year info
                        const classInput = document.createElement('input');
                        classInput.type = 'hidden';
                        classInput.name = 'class_id';
                        classInput.value = '{{ $class->id }}';
                        form.appendChild(classInput);

                        const syInput = document.createElement('input');
                        syInput.type = 'hidden';
                        syInput.name = 'school_year_id';
                        syInput.value = '{{ $schoolYearId }}';
                        form.appendChild(syInput);

                        // Append form to body and submit
                        document.body.appendChild(form);
                        form.submit();
                        document.body.removeChild(form);

                        // Close loading
                        Swal.close();
                    }
                });
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
            cursor: pointer;
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
    </style>
@endpush
