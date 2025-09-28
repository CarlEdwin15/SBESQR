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
                <div class="mb-4">
                    <h4 class="mb-2">Payment: {{ $paymentName }}</h4>
                    <div class="d-flex flex-wrap align-items-center text-muted small">
                        <strong>Amount Due:</strong> ₱{{ number_format($first->amount_due, 2) }}
                        <span class="mx-2">|</span>
                        <strong>Due Date:</strong> {{ \Carbon\Carbon::parse($first->due_date)->format('M d, Y') }}
                        @if (!empty($first->remarks))
                            <span class="mx-2">|</span>
                            <strong>Remarks:</strong> {{ $first->remarks }}
                        @endif
                    </div>
                </div>
            @endif

            <!-- Filters -->
            <div class="row g-2 mb-3">
                <div class="d-flex align-items-center gap-2">
                    <!-- Table Length Selector -->
                    <div>
                        <select id="tableLength" class="form-select">
                            <option value="10" selected>10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <input type="text" name="search" class="form-control" placeholder="Search student...">
                    </div>
                    <div class="col-md-3">
                        <select name="status" class="form-select">
                            <option value="">All Status</option>
                            <option value="paid">Paid</option>
                            <option value="partial">Partial</option>
                            <option value="unpaid">Unpaid</option>
                        </select>
                    </div>
                </div>
            </div>
            <!-- /Filters -->

            <!-- Payments Table -->
            <div class="table-responsive">
                <table id="paymentTable" class="table table-hover align-middle">
                    <thead class="table-primary text-center">
                        <tr>
                            <th style="width: 40px;">No.</th>
                            <th>Student</th>
                            <th>Gender</th>
                            <th>Status</th>
                            <th>Amount Paid</th>
                            <th>Amount Due</th>
                            <th>Date Paid</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($payments as $p)
                            <tr class="payment-row" data-name="{{ strtolower(optional($p->student)->full_name) }}"
                                data-status="{{ $p->status }}" data-class="{{ $p->classStudent->class->id ?? '' }}"
                                data-year="{{ $p->schoolYear->school_year ?? '' }}">

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
                                            <div>{{ $p->student->full_name ?? 'Unknown' }}</div>
                                        </div>
                                    </a>
                                </td>
                                <td class="text-center">{{ ucfirst($p->student->student_sex) ?? '—' }}</td>
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

                                        <form action="{{ route('teacher.payments.update', $p->id) }}" method="POST">
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
    <script>
        let allPaymentRows = [];
        let currentPage = 1;
        let rowsPerPage = 10; // default = 10
        const visiblePayments = {};

        function paginatePayments(tableId, paginationId, maxVisiblePages = 10) {
            const pagination = document.getElementById(paginationId);
            const rows = visiblePayments[tableId] || [];
            const tbody = document.querySelector(`#${tableId} tbody`);

            // remove any old "no results" row
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
                    // show slice + add numbering
                    rows.slice(start, end).forEach((r, i) => {
                        r.style.display = "table-row";
                        r.querySelector("td:first-child").textContent = start + i + 1;
                    });
                } else {
                    // insert "No results found"
                    const tr = document.createElement("tr");
                    tr.id = "noResultsRow";
                    tr.innerHTML = `<td colspan="7" class="text-center text-muted">No results found</td>`;
                    tbody.appendChild(tr);
                }

                // update info
                const tableInfo = document.getElementById("tableInfo");
                if (totalEntries > 0) {
                    tableInfo.textContent = `Showing ${start + 1} to ${end} of ${totalEntries} payments`;
                } else {
                    tableInfo.textContent = "Showing 0 to 0 of 0 payments";
                }

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
            }

            showPage(currentPage);
        }

        document.addEventListener("DOMContentLoaded", () => {
            const tableId = "paymentTable";
            const paginationId = "paymentPagination";
            allPaymentRows = Array.from(document.querySelectorAll("#paymentTable tbody tr.payment-row"));

            visiblePayments[tableId] = allPaymentRows;
            paginatePayments(tableId, paginationId);

            // rows per page selector
            document.getElementById("tableLength").addEventListener("change", function() {
                rowsPerPage = parseInt(this.value);
                currentPage = 1;
                paginatePayments(tableId, paginationId);
            });

            // search + filters
            const searchInput = document.querySelector("input[name='search']");
            const statusSelect = document.querySelector("select[name='status']");
            const classSelect = document.querySelector("select[name='class_id']");
            const yearSelect = document.querySelector("select[name='school_year']");

            function filterPayments() {
                const query = searchInput.value.trim().toLowerCase();
                const status = statusSelect.value;
                const classId = classSelect ? classSelect.value : "";
                const year = yearSelect ? yearSelect.value : "";

                // 🔹 Save filter state
                localStorage.setItem("paymentFilters", JSON.stringify({
                    query,
                    status,
                    classId,
                    year
                }));

                const filtered = allPaymentRows.filter(row => {
                    const matchesSearch = query === "" || row.dataset.name.includes(query);
                    const matchesStatus = status === "" || row.dataset.status === status;
                    const matchesClass = classId === "" || row.dataset.class === classId;
                    const matchesYear = year === "" || row.dataset.year === year;
                    return matchesSearch && matchesStatus && matchesClass && matchesYear;
                });

                visiblePayments[tableId] = filtered;
                currentPage = 1;
                paginatePayments(tableId, paginationId);
            }

            let searchTimeout;
            searchInput.addEventListener("input", function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(filterPayments, 300);
            });

            statusSelect.addEventListener("change", filterPayments);
            if (classSelect) classSelect.addEventListener("change", filterPayments);
            if (yearSelect) yearSelect.addEventListener("change", filterPayments);

            // 🔹 Restore saved filters
            const savedFilters = JSON.parse(localStorage.getItem("paymentFilters") || "{}");
            if (savedFilters.query) searchInput.value = savedFilters.query;
            if (savedFilters.status) statusSelect.value = savedFilters.status;
            if (classSelect && savedFilters.classId) classSelect.value = savedFilters.classId;
            if (yearSelect && savedFilters.year) yearSelect.value = savedFilters.year;

            filterPayments(); // apply after restoring
        });
    </script>
@endpush

@push('styles')
    <!-- Main CSS File -->
    <link href="{{ asset('assets/css/main.css') }}" rel="stylesheet" />
    <link href="{{ asset('assetsDashboard/vendor/css/core.css') }}" rel="stylesheet" />
    <link href="{{ asset('assetsDashboard/vendor/css/theme-default.css') }}" rel="stylesheet" />

    <!-- Vendor CSS Files -->
    <link href="{{ asset('assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet" />
@endpush
