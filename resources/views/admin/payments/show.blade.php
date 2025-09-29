@extends('./layouts.main')

@section('title', 'Admin | Payments')

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
            <li class="menu-item">
                <a href="javascript:void(0);" class="menu-link menu-toggle bg-dark text-light">
                    <i class="menu-icon tf-icons bx bx-objects-horizontal-left text-light"></i>
                    <div class="text-light">Classes</div>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item">
                        <a href="{{ route('all.classes') }}" class="menu-link bg-dark text-light">
                            <div class="text-light">All Classes</div>
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
            <li class="menu-item active open">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons bx bx-wallet-alt"></i>
                    <div>Payments</div>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item active">
                        <a href="" class="menu-link bg-dark text-light">
                            <div class="text-warning">All Payments</div>
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
        <!-- Breadcrumb -->
        <h4 class="fw-bold py-3 mb-4 text-warning">
            <span class="text-muted fw-light">
                <a class="text-muted fw-light" href="{{ route('home') }}">Dashboard</a> /
                <a class="text-muted fw-light"
                    href="{{ route('admin.payments.index', ['school_year' => $selectedYear, 'class_id' => $selectedClass]) }}">
                    Payments
                </a> /
            </span>
            {{ $paymentName }}
        </h4>

        <!-- Summary Cards -->
        <div class="row mb-4 g-3">
            <div class="col-6 col-md-3">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-2">
                            <span class="avatar-initial rounded bg-label-secondary me-2 p-2">
                                <i class="bx bx-user fs-4 text-dark"></i>
                            </span>
                            <span class="fw-semibold text-primary">Total Students</span>
                        </div>
                        <h3 class="mb-0">{{ $totalCount }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-2">
                            <span class="avatar-initial rounded bg-label-success me-2 p-2">
                                <i class="bx bx-check-double fs-4"></i>
                            </span>
                            <span class="fw-semibold text-success">Paid</span>
                        </div>
                        <h3 class="mb-0">{{ $paidCount }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-2">
                            <span class="avatar-initial rounded bg-label-warning me-2 p-2">
                                <i class="bx bx-file fs-4 text-dark"></i>
                            </span>
                            <span class="fw-semibold text-warning">Partial</span>
                        </div>
                        <h3 class="mb-0">{{ $partialCount }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-2">
                            <span class="avatar-initial rounded bg-label-danger me-2 p-2">
                                <i class="bx bx-error-circle fs-4"></i>
                            </span>
                            <span class="fw-semibold text-danger">Unpaid</span>
                        </div>
                        <h3 class="mb-0">{{ $unpaidCount }}</h3>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Summary Cards -->

        <!-- Payments Card -->
        <div class="card p-4 shadow-sm">
            @if ($first)
                @php
                    $totalExpected = $first->amount_due * count($payments);
                    $totalCollected = $payments->sum('amount_paid');
                @endphp

                <div class="mb-4">
                    <h4 class="mb-2">Payment: {{ $paymentName }}</h4>
                    <div class="d-flex flex-wrap align-items-center text-muted small">
                        <strong>Amount Due:</strong> ₱{{ number_format($first->amount_due, 2) }}
                        <span class="mx-2">|</span>
                        <strong>Due Date:</strong> {{ \Carbon\Carbon::parse($first->due_date)->format('M d, Y') }}
                        <span class="mx-2">|</span>
                        <strong>Collected:</strong>
                        ₱{{ number_format($totalCollected, 2) }}/₱{{ number_format($totalExpected, 2) }}
                    </div>
                </div>
            @endif

            <!-- Filters -->
            <div class="row g-2">
                <div class="d-flex justify-content-between align-items-center">
                    <!-- Left side: Table Length + Search -->
                    <div class="d-flex align-items-center gap-2">
                        <!-- Table Length Selector -->
                        <div class="col-md-3">
                            <select id="tableLength" class="form-select">
                                <option value="10" selected>10</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                            </select>
                        </div>

                        <!-- Search Input -->
                        <div class="col-md-12">
                            <input type="text" name="search" class="form-control"
                                placeholder="Search student or class...">
                        </div>

                        <form id="bulkPaymentForm" action="{{ route('admin.payments.bulkUpdate') }}" method="POST"
                            class="d-flex gap-2 d-none">
                            @csrf
                            <div id="bulkPaymentIds"></div> <!-- Hidden inputs for selected payments -->

                            <div class="dropdown">
                                <button class="btn btn-outline-primary dropdown-toggle" type="button"
                                    id="bulkPaymentDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                    Set Status
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="bulkPaymentDropdown">
                                    <li>
                                        <button type="submit" class="dropdown-item text-success"
                                            onclick="setBulkPaymentStatus('paid', event)">Paid</button>
                                    </li>
                                    <li>
                                        <button type="submit" class="dropdown-item text-warning"
                                            onclick="setBulkPaymentStatus('partial', event)">Partial</button>
                                    </li>
                                    <li>
                                        <button type="submit" class="dropdown-item text-danger"
                                            onclick="setBulkPaymentStatus('unpaid', event)">Unpaid</button>
                                    </li>
                                </ul>
                            </div>
                        </form>
                    </div>

                    <div class="d-flex align-items-end gap-2">
                        <!-- Right side: Status Filter -->
                        <div class="col-md-12">
                            <select name="status" class="form-select">
                                <option value="">All Status</option>
                                <option value="paid">Paid</option>
                                <option value="partial">Partial</option>
                                <option value="unpaid">Unpaid</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /Filters -->

            <hr class="my-3" />

            <!-- Payments Table -->
            <div class="table-responsive">
                <table id="paymentTable" class="table table-hover align-middle">
                    <thead class="table-primary text-center">
                        <tr>
                            <th style="width: 40px;">
                                <input type="checkbox" id="selectAllPayments">
                            </th>
                            <th style="width: 40px;">No.</th>
                            <th>Student</th>
                            <th>Class</th>
                            <th>Status</th>
                            <th>Amount Paid</th>
                            <th>Amount Due</th>
                            <th>Date Paid</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($payments as $p)
                            <tr class="payment-row
                                @if ($p->status === 'paid') table-success
                                @elseif($p->status === 'partial') table-warning @endif"
                                data-name="{{ strtolower(optional($p->student)->full_name) }}"
                                data-status="{{ $p->status }}" data-class="{{ $p->classStudent->class->id ?? '' }}"
                                data-class-name="{{ strtolower(optional($p->classStudent->class)->formatted_grade_level . ' - ' . optional($p->classStudent->class)->section) }}"
                                data-gender="{{ strtolower(optional($p->student)->student_sex) }}"
                                data-year="{{ $p->schoolYear->school_year ?? '' }}">

                                <td class="text-center">
                                    <input type="checkbox" class="payment-checkbox" value="{{ $p->id }}">
                                </td>
                                <td class="text-center"></td> {{-- JS will number this --}}
                                <td>
                                    <a href="#" data-bs-toggle="modal"
                                        data-bs-target="#editPaymentModal{{ $p->id }}">
                                        <div class="d-flex align-items-center">
                                            <img src="{{ $p->student && $p->student->student_photo
                                                ? asset('assetsDashboard/img/student_profile_pictures/' . $p->student->student_photo)
                                                : asset('assetsDashboard/img/student_profile_pictures/student_default_profile.jpg') }}"
                                                class="rounded-circle me-2"
                                                style="width: 40px; height: 40px; object-fit: cover;">


                                            <span>{{ $p->student->full_name ?? 'Unknown' }}</span>
                                            @if ($p->student && $p->student->student_sex)
                                                @if (strtolower($p->student->student_sex) === 'male')
                                                    <i class="bx bx-male me-1 text-primary"></i>
                                                @elseif (strtolower($p->student->student_sex) === 'female')
                                                    <i class="bx bx-female me-1 text-danger"></i>
                                                @else
                                                    <span class="badge bg-secondary mt-1">—</span>
                                                @endif
                                            @endif

                                        </div>
                                    </a>
                                </td>
                                <td class="text-center">
                                    @if ($p->classStudent && $p->classStudent->class)
                                        {{ ucfirst($p->classStudent->class->formatted_grade_level) }} -
                                        {{ $p->classStudent->class->section }}
                                    @else
                                        —
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if ($p->status === 'paid')
                                        <span class="badge bg-success">Paid</span>
                                    @elseif ($p->status === 'partial')
                                        <span class="badge bg-warning text-dark">Partial</span>
                                    @else
                                        <span class="badge bg-danger">Unpaid</span>
                                    @endif
                                </td>
                                <td class="text-center">₱{{ number_format($p->amount_paid ?? 0, 2) }}</td>
                                <td class="text-center">₱{{ number_format($p->amount_due ?? 0, 2) }}</td>
                                <td class="text-center">
                                    {{ $p->date_paid ? \Carbon\Carbon::parse($p->date_paid)->format('M d, Y') : '—' }}
                                </td>
                            </tr>

                            <!-- 🔹 Modal should be INSIDE loop -->
                            <div class="modal fade" id="editPaymentModal{{ $p->id }}" tabindex="-1"
                                aria-labelledby="editPaymentLabel{{ $p->id }}" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content shadow-lg">
                                        <div class="modal-header bg-primary text-white">
                                            <h5 class="modal-title" id="editPaymentLabel{{ $p->id }}">
                                                Update Payment - {{ $p->student->full_name ?? 'Unknown' }}
                                            </h5>
                                            <button type="button" class="btn-close btn-close-white"
                                                data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>

                                        <form action="{{ route('admin.payments.update', $p->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')

                                            <div class="modal-body">
                                                <!-- Amount Due (readonly) -->
                                                <div class="mb-3">
                                                    <label class="form-label">Amount Due</label>
                                                    <input type="text" class="form-control"
                                                        value="₱{{ number_format($p->amount_due, 2) }}" readonly>
                                                </div>

                                                <!-- Amount Paid -->
                                                <div class="mb-3">
                                                    <label for="amountPaid{{ $p->id }}" class="form-label">Amount
                                                        to Pay</label>
                                                    <input type="number" step="0.01" min="0"
                                                        name="amount_paid" id="amountPaid{{ $p->id }}"
                                                        value="{{ $p->amount_paid ?? 0 }}" class="form-control" required>
                                                </div>

                                                <!-- Remarks -->
                                                <div class="mb-3">
                                                    <label for="remarks{{ $p->id }}"
                                                        class="form-label">Remarks</label>
                                                    <textarea name="remarks" id="remarks{{ $p->id }}" rows="2" class="form-control">{{ $p->remarks }}</textarea>
                                                </div>

                                                <!-- Current Status -->
                                                <div class="mb-2">
                                                    <small class="text-muted">
                                                        Current Status:
                                                        @if ($p->status === 'paid')
                                                            <span class="badge bg-success">Paid</span>
                                                        @elseif ($p->status === 'partial')
                                                            <span class="badge bg-warning text-dark">Partial</span>
                                                        @else
                                                            <span class="badge bg-danger">Unpaid</span>
                                                        @endif
                                                    </small>
                                                </div>
                                            </div>

                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-light"
                                                    data-bs-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-primary">Update Payment</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <!-- /Modal -->
                        @endforeach
                    </tbody>
                </table>
            </div>
            <!-- /Payments Table -->

            <!-- Pagination + Info -->
            <div class="d-flex justify-content-between align-items-center px-3 py-3 border-top">
                <div id="tableInfo" class="text-muted small"></div>
                <nav aria-label="Page navigation">
                    <ul class="pagination mb-0" id="paymentPagination"></ul>
                </nav>
            </div>
            <!-- /Pagination + Info -->

        </div>
        <!-- /Payments Card -->
    </div>
    <!-- /Content wrapper -->

@endsection

@push('scripts')
    <!-- Logout -->
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
                        toast: true,
                        position: 'top-end',
                        title: "Logged out Successfully!",
                        icon: "success",
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                        customClass: {
                            container: 'my-swal-container'
                        }
                    });
                    document.getElementById('logout-form').submit();
                }
            });
        }
    </script>

    <!-- Error Alert Message -->
    <script>
        @if ($errors->any())
            Swal.fire({
                icon: 'error',
                title: 'Registration Error',
                html: `{!! implode('<br>', $errors->all()) !!}`,
                confirmButtonColor: '#dc3545',
                customClass: {
                    container: 'my-swal-container'
                }
            });
        @endif
    </script>

    <!-- Pagination, Search, Filter Logic -->
    <!-- Pagination, Search, Filter & Bulk Actions -->
    <script>
        let allPaymentRows = [];
        let currentPage = 1;
        let rowsPerPage = 10;
        const visiblePayments = {};
        const selectedPayments = new Set(); // track selected rows across pages

        // PAGINATION LOGIC
        function paginatePayments(tableId, paginationId, maxVisiblePages = 5) {
            const pagination = document.getElementById(paginationId);
            const rows = visiblePayments[tableId] || [];
            const tbody = document.querySelector(`#${tableId} tbody`);

            // remove old "no results"
            const oldNoResults = document.querySelector("#noResultsRow");
            if (oldNoResults) oldNoResults.remove();

            function showPage(page) {
                const totalEntries = rows.length;
                const totalPages = Math.max(1, Math.ceil(totalEntries / rowsPerPage));
                currentPage = Math.min(Math.max(1, page), totalPages);
                const start = (currentPage - 1) * rowsPerPage;
                const end = Math.min(start + rowsPerPage, totalEntries);

                // hide all
                allPaymentRows.forEach(r => r.style.display = "none");

                if (totalEntries > 0) {
                    rows.slice(start, end).forEach((r, i) => {
                        r.style.display = "table-row";
                        r.querySelector("td:nth-child(2)").textContent = start + i + 1;

                        // restore checkbox selection
                        const cb = r.querySelector(".payment-checkbox");
                        cb.checked = selectedPayments.has(cb.value);
                        r.classList.toggle("row-highlight", cb.checked);
                    });
                } else {
                    const tr = document.createElement("tr");
                    tr.id = "noResultsRow";
                    tr.innerHTML = `<td colspan="8" class="text-center text-muted">No results found</td>`;
                    tbody.appendChild(tr);
                }

                // update info
                const tableInfo = document.getElementById("tableInfo");
                tableInfo.textContent = totalEntries > 0 ?
                    `Showing ${start + 1} to ${end} of ${totalEntries} payments` :
                    "Showing 0 to 0 of 0 payments";

                // build pagination
                pagination.innerHTML = "";
                const prev = document.createElement("li");
                prev.className = `page-item ${currentPage === 1 ? 'disabled' : ''}`;
                prev.innerHTML = `<a class="page-link text-primary" href="javascript:void(0);">&laquo;</a>`;
                prev.onclick = () => currentPage > 1 && showPage(currentPage - 1);
                pagination.appendChild(prev);

                let startPage = Math.max(1, currentPage - Math.floor(maxVisiblePages / 2));
                let endPage = Math.min(totalPages, startPage + maxVisiblePages - 1);

                for (let i = startPage; i <= endPage; i++) {
                    const li = document.createElement("li");
                    li.className = `page-item ${i === currentPage ? 'active' : ''}`;
                    li.innerHTML = `<a class="page-link" href="javascript:void(0);">${i}</a>`;
                    li.onclick = () => showPage(i);
                    pagination.appendChild(li);
                }

                const next = document.createElement("li");
                next.className = `page-item ${currentPage === totalPages ? 'disabled' : ''}`;
                next.innerHTML = `<a class="page-link text-primary" href="javascript:void(0);">&raquo;</a>`;
                next.onclick = () => currentPage < totalPages && showPage(currentPage + 1);
                pagination.appendChild(next);

                // rebind checkbox events for current page
                bindCheckboxEvents();
            }

            showPage(currentPage);
        }

        // FILTER LOGIC
        function filterPayments() {
            const tableId = "paymentTable";
            const searchInput = document.querySelector("input[name='search']");
            const statusSelect = document.querySelector("select[name='status']");
            const classSelect = document.querySelector("select[name='class_id']");
            const yearSelect = document.querySelector("select[name='school_year']");

            const query = searchInput.value.trim().toLowerCase();
            const status = statusSelect.value;
            const classId = classSelect ? classSelect.value : "";
            const year = yearSelect ? yearSelect.value : "";

            localStorage.setItem("paymentFilters", JSON.stringify({
                query,
                status,
                classId,
                year
            }));

            const filtered = allPaymentRows.filter(row => {
                const name = row.dataset.name || "";
                const className = row.dataset.className || "";
                const gender = row.dataset.gender || "";

                const matchesSearch =
                    query === "" ||
                    name.includes(query) ||
                    className.includes(query) ||
                    gender.includes(query);

                const matchesStatus = status === "" || row.dataset.status === status;
                const matchesClass = classId === "" || row.dataset.class === classId;
                const matchesYear = year === "" || row.dataset.year === year;

                return matchesSearch && matchesStatus && matchesClass && matchesYear;
            });

            visiblePayments[tableId] = filtered;
            currentPage = 1;
            paginatePayments(tableId, "paymentPagination");
        }

        // CHECKBOX & BULK ACTIONS
        function bindCheckboxEvents() {
            const selectAll = document.getElementById("selectAllPayments");
            const bulkForm = document.getElementById("bulkPaymentForm");

            function toggleBulkForm() {
                const anyChecked = Array.from(document.querySelectorAll(".payment-checkbox"))
                    .filter(cb => cb.closest("tr").style.display !== "none")
                    .some(cb => cb.checked);

                bulkForm.classList.toggle("d-none", !anyChecked);
            }

            function toggleRowHighlight(cb) {
                const row = cb.closest("tr");
                row.classList.toggle("row-highlight", cb.checked);
            }

            document.querySelectorAll(".payment-checkbox").forEach(cb => {
                cb.onchange = () => {
                    const val = cb.value;
                    if (cb.checked) selectedPayments.add(val);
                    else selectedPayments.delete(val);

                    cb.closest("tr").classList.toggle("row-highlight", cb.checked && cb.closest("tr").style
                        .display !== "none");

                    // Update Select All checkbox
                    const allCheckboxes = Array.from(document.querySelectorAll(".payment-checkbox"));
                    selectAll.checked = allCheckboxes.length > 0 && allCheckboxes.every(cb => selectedPayments
                        .has(cb.value));

                    // Toggle bulk form
                    const bulkForm = document.getElementById("bulkPaymentForm");
                    bulkForm.classList.toggle("d-none", selectedPayments.size === 0);
                };
            });

            // Select-All checkbox
            selectAll.onchange = () => {
                const allCheckboxes = Array.from(document.querySelectorAll(".payment-checkbox"));

                allCheckboxes.forEach(cb => {
                    cb.checked = selectAll.checked;
                    const val = cb.value;
                    if (cb.checked) selectedPayments.add(val);
                    else selectedPayments.delete(val);

                    // Highlight only if row is visible
                    cb.closest("tr").classList.toggle("row-highlight", cb.checked && cb.closest("tr").style
                        .display !== "none");
                });

                // Bulk form toggle based on whether at least one is selected
                const bulkForm = document.getElementById("bulkPaymentForm");
                bulkForm.classList.toggle("d-none", selectedPayments.size === 0);
            };
        }

        // BULK STATUS
        function setBulkPaymentStatus(status, event) {
            event.preventDefault();
            const bulkForm = document.getElementById("bulkPaymentForm");
            const bulkIdsContainer = document.getElementById("bulkPaymentIds");

            if (selectedPayments.size === 0) {
                Swal.fire({
                    icon: "warning",
                    title: "No payments selected",
                    text: "Please select at least one payment.",
                    confirmButtonColor: "#3085d6"
                });
                return;
            }

            Swal.fire({
                title: "Are you sure?",
                text: `You are about to set ${selectedPayments.size} payment(s) to "${status.toUpperCase()}".`,
                icon: "question",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: `Yes, set to ${status.toUpperCase()}`,
                cancelButtonText: "Cancel"
            }).then((result) => {
                if (result.isConfirmed) {
                    bulkIdsContainer.innerHTML = "";
                    selectedPayments.forEach(val => {
                        const input = document.createElement("input");
                        input.type = "hidden";
                        input.name = "payment_ids[]";
                        input.value = val;
                        bulkIdsContainer.appendChild(input);
                    });

                    const statusInput = document.createElement("input");
                    statusInput.type = "hidden";
                    statusInput.name = "status";
                    statusInput.value = status;
                    bulkIdsContainer.appendChild(statusInput);

                    bulkForm.submit();
                }
            });
        }

        // DOM CONTENT LOADED
        document.addEventListener("DOMContentLoaded", () => {
            const tableId = "paymentTable";
            allPaymentRows = Array.from(document.querySelectorAll("#paymentTable tbody tr.payment-row"));
            visiblePayments[tableId] = allPaymentRows;

            // restore filters
            const savedFilters = JSON.parse(localStorage.getItem("paymentFilters") || "{}");
            const searchInput = document.querySelector("input[name='search']");
            const statusSelect = document.querySelector("select[name='status']");
            const classSelect = document.querySelector("select[name='class_id']");
            const yearSelect = document.querySelector("select[name='school_year']");

            if (savedFilters.query) searchInput.value = savedFilters.query;
            if (savedFilters.status) statusSelect.value = savedFilters.status;
            if (classSelect && savedFilters.classId) classSelect.value = savedFilters.classId;
            if (yearSelect && savedFilters.year) yearSelect.value = savedFilters.year;

            filterPayments();

            // event listeners
            searchInput.addEventListener("input", () => setTimeout(filterPayments, 300));
            statusSelect.addEventListener("change", filterPayments);
            if (classSelect) classSelect.addEventListener("change", filterPayments);
            if (yearSelect) yearSelect.addEventListener("change", filterPayments);

            document.getElementById("tableLength").addEventListener("change", function() {
                rowsPerPage = parseInt(this.value);
                currentPage = 1;
                paginatePayments(tableId, "paymentPagination");
            });
        });
    </script>

    <!-- Select All & Bulk Actions -->
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const selectAll = document.getElementById("selectAllPayments");
            const bulkForm = document.getElementById("bulkPaymentForm");
            const bulkIdsContainer = document.getElementById("bulkPaymentIds");

            function toggleBulkForm() {
                const anyChecked = Array.from(document.querySelectorAll(".payment-checkbox"))
                    .filter(cb => cb.closest("tr").style.display !== "none")
                    .some(cb => cb.checked);

                bulkForm.classList.toggle("d-none", !anyChecked);
            }

            function toggleRowHighlight(cb) {
                const row = cb.closest("tr");
                row.classList.toggle("row-highlight", cb.checked);
            }

            // Select-All checkbox
            selectAll.addEventListener("change", () => {
                const visibleCheckboxes = Array.from(document.querySelectorAll(".payment-checkbox"))
                    .filter(cb => cb.closest("tr").style.display !== "none");

                visibleCheckboxes.forEach(cb => {
                    cb.checked = selectAll.checked;
                    toggleRowHighlight(cb);
                });

                toggleBulkForm();
            });

            // Individual checkboxes
            document.querySelectorAll(".payment-checkbox").forEach(cb => {
                cb.addEventListener("change", () => {
                    toggleRowHighlight(cb);

                    const visibleCheckboxes = Array.from(document.querySelectorAll(
                            ".payment-checkbox"))
                        .filter(cb => cb.closest("tr").style.display !== "none");

                    selectAll.checked = visibleCheckboxes.length > 0 && visibleCheckboxes.every(
                        cb => cb.checked);

                    toggleBulkForm();
                });
            });
        });

        // Bulk status confirmation & submit
        function setBulkPaymentStatus(status, event) {
            event.preventDefault();

            const bulkForm = document.getElementById("bulkPaymentForm");
            const bulkIdsContainer = document.getElementById("bulkPaymentIds");
            const selected = Array.from(document.querySelectorAll(".payment-checkbox:checked"));

            if (selected.length === 0) {
                Swal.fire({
                    icon: "warning",
                    title: "No payments selected",
                    text: "Please select at least one payment.",
                    confirmButtonColor: "#3085d6"
                });
                return;
            }

            Swal.fire({
                title: "Are you sure?",
                text: `You are about to set ${selected.length} payment(s) to "${status.toUpperCase()}".`,
                icon: "question",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: `Yes, set to ${status.toUpperCase()}`,
                cancelButtonText: "Cancel"
            }).then((result) => {
                if (result.isConfirmed) {
                    bulkIdsContainer.innerHTML = "";
                    selected.forEach(cb => {
                        const input = document.createElement("input");
                        input.type = "hidden";
                        input.name = "payment_ids[]";
                        input.value = cb.value;
                        bulkIdsContainer.appendChild(input);
                    });

                    const statusInput = document.createElement("input");
                    statusInput.type = "hidden";
                    statusInput.name = "status";
                    statusInput.value = status;
                    bulkIdsContainer.appendChild(statusInput);

                    bulkForm.submit();
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
        .row-highlight {
            background-color: #e0f7fa !important;
            /* light cyan highlight */
        }
    </style>
@endpush
