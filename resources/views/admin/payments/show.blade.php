@extends('./layouts.main')

@section('title', 'Admin | School Fees')

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
                    <div>School Fees</div>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item active">
                        <a href="{{ route('admin.payments.index') }}" class="menu-link bg-dark text-light">
                            <div class="text-warning">All School Fees</div>
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
                    School Fees
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
                            <span class="avatar-initial rounded bg-label-primary me-2 p-2">
                                <i class="bx bx-user fs-4 text-primary"></i>
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
                                <i class="bx bx-file fs-4 text-warning"></i>
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

                <div class="mb-4 d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-2 fw-bold text-info">School Fee Name: {{ $paymentName }}</h4>
                        <div class="d-flex flex-wrap align-items-center text-muted">
                            <strong>Amount Due:</strong> ₱{{ number_format($first->amount_due, 2) }}
                            <span class="mx-2">|</span>
                            <strong>Due Date:</strong> {{ \Carbon\Carbon::parse($first->due_date)->format('M d, Y') }}
                            <span class="mx-2">|</span>
                            <strong>Collected:</strong>
                            ₱<span id="collectedValue">{{ number_format($totalCollected, 2) }}</span> /
                            ₱<span id="expectedValue">{{ number_format($totalExpected, 2) }}</span>
                        </div>
                    </div>

                    <!-- Bulk Remove Button & Add Students Button -->
                    <div class="d-flex justify-content-end gap-2">
                        <!-- Bulk Remove Button -->
                        <form id="bulkRemoveForm" action="{{ route('admin.payments.bulkRemoveStudents') }}"
                            method="POST" class="gap-2 d-none" style="flex-wrap: nowrap;">
                            @csrf
                            <div id="bulkRemoveIds"></div> <!-- Hidden inputs for selected removals -->

                            <button type="button" class="btn btn-outline-danger d-flex align-items-center"
                                id="bulkRemoveBtn">
                                <i class='bx bx-user-x me-1'></i>
                                <span class="d-none d-sm-block">Remove Students</span>
                            </button>
                        </form>

                        <!-- Add Students Button -->
                        <button type="button" class="btn btn-outline-success d-flex align-items-center"
                            id="addStudentsBtn" data-bs-toggle="modal" data-bs-target="#addStudentsModal">
                            <i class='bx bx-user-plus me-1'></i>
                            <span class="d-none d-sm-block">Add Students</span>
                        </button>
                    </div>
                </div>
            @endif

            <!-- Search & Status Filter -->
            <div class="d-flex justify-content-between align-items-center">
                <!-- Search Input -->
                <div class="col-md-6">
                    <input type="text" name="search" class="form-control" placeholder="Search student or class...">
                </div>

                <div class="d-flex align-items-end gap-2">
                    <!-- Status Filter -->
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
            <!-- /Search & Status Filter -->

            <hr class="my-3" />

            <!-- Table Length, Bulk Add Payment, & Payment History Button -->
            <div class="row g-2 mb-3">
                <div class="d-flex justify-content-between align-items-center flex-wrap">
                    <!-- Left side: Table Length + Bulk Add -->
                    <div class="d-flex align-items-center gap-2 flex-wrap">
                        <!-- Table Length Selector -->
                        <select id="tableLength" class="form-select" style="width: auto;">
                            <option value="10" selected>10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>

                        <!-- Bulk Add Button -->
                        <form id="bulkPaymentForm" action="{{ route('admin.payments.bulkAddPayment') }}" method="POST"
                            class="d-none">
                            @csrf
                            <div id="bulkPaymentIds"></div>
                            <button type="button" class="btn btn-outline-primary d-flex align-items-center"
                                id="bulkAddPaymentBtn" data-bs-toggle="modal" data-bs-target="#bulkAddPaymentModal">
                                <i class='bx bx-add-to-queue me-1'></i>
                                <span class="d-none d-sm-block">Bulk Add Payment</span>
                            </button>
                        </form>
                    </div>

                    <!-- Right side: Payment History Button -->
                    <button type="button" class="btn btn-info d-flex align-items-center mt-2 mt-sm-0"
                        id="paymentHistoryBtn" data-bs-toggle="modal" data-bs-target="#paymentHistoryModal">
                        <i class='bx bx-credit-card me-1'></i>
                        <span class="d-none d-sm-block">All Payments History</span>
                    </button>
                </div>
            </div>
            <!-- /Table Length, Bulk Add Payment, & Payment History Button -->

            <!-- Payments Table -->
            <div class="table-responsive">
                <table id="paymentTable" class="table table-hover align-middle">
                    <thead class="table-primary text-center">
                        <tr>
                            <th style="width: 40px;">
                                <input class="chkbox" type="checkbox" id="selectAllPayments">
                            </th>
                            <th style="width: 40px;">No.</th>
                            <th>Student</th>
                            <th>Class</th>
                            <th>Status</th>
                            <th>Amount Paid</th>
                            <th>Amount Due</th>
                            <th>Last Payment Date</th>
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
                                data-year="{{ $p->schoolYear->school_year ?? '' }}"
                                data-amount-paid="{{ $p->total_paid }}" data-amount-due="{{ $p->amount_due }}">

                                <!-- Checkbox -->
                                <td class="text-center">
                                    <input type="checkbox" class="payment-checkbox" value="{{ $p->id }}">
                                </td>

                                <!-- Row Number (JS handles numbering) -->
                                <td class="text-center"></td>

                                <!-- Student Column -->
                                <td>
                                    <a href="#" data-bs-toggle="modal"
                                        data-bs-target="#addPaymentModal{{ $p->id }}">
                                        <div class="d-flex align-items-center">
                                            <img src="{{ $p->student && $p->student->student_photo
                                                ? asset('storage/' . $p->student->student_photo)
                                                : asset('assetsDashboard/img/student_profile_pictures/student_default_profile.jpg') }}"
                                                class="rounded-circle me-2"
                                                style="width: 40px; height: 40px; object-fit: cover;">
                                            <span>{{ $p->student->full_name ?? 'Unknown' }}</span>
                                            @if ($p->student && $p->student->student_sex)
                                                @if (strtolower($p->student->student_sex) === 'male')
                                                    <i class="bx bx-male me-1 text-primary"></i>
                                                @elseif (strtolower($p->student->student_sex) === 'female')
                                                    <i class="bx bx-female me-1 text-danger"></i>
                                                @endif
                                            @endif
                                        </div>
                                    </a>
                                </td>

                                <!-- Class Column -->
                                <td class="text-center">
                                    @if ($p->classStudent && $p->classStudent->class)
                                        {{ ucfirst($p->classStudent->class->formatted_grade_level) }} -
                                        {{ $p->classStudent->class->section }}
                                    @else
                                        —
                                    @endif
                                </td>

                                <!-- Status Column -->
                                <td class="text-center">
                                    @if ($p->status === 'paid')
                                        <span class="badge bg-success">Paid</span>
                                    @elseif ($p->status === 'partial')
                                        <span class="badge bg-warning text-dark">Partial</span>
                                    @else
                                        <span class="badge bg-danger">Unpaid</span>
                                    @endif
                                </td>

                                <!-- Amount Paid -->
                                <td class="text-center">₱{{ number_format($p->total_paid, 2) }}</td>

                                <!-- Amount Due -->
                                <td class="text-center">₱{{ number_format($p->amount_due, 2) }}</td>

                                <!-- Last Payment Date -->
                                <td class="text-center">
                                    {{ $p->latestPaymentDate() ? $p->latestPaymentDate()->format('M d, Y || h:i A') : '—' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                @foreach ($payments as $p)
                    <!-- Individual Add Payment Modal -->
                    <div class="modal fade" id="addPaymentModal{{ $p->id }}" tabindex="-1"
                        aria-labelledby="addPaymentLabel{{ $p->id }}" data-bs-backdrop="static"
                        aria-hidden="true">
                        <div class="modal-dialog modal-lg modal-dialog-centered">
                            <div class="modal-content shadow-lg">

                                <!-- Modal Body -->
                                <form action="{{ route('admin.payments.add', $p->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-body">

                                        <!-- Student Info -->
                                        <div class="d-flex align-items-center justify-content-between mb-3">
                                            <div class="d-flex align-items-center">
                                                <img src="{{ $p->student && $p->student->student_photo
                                                    ? asset('assetsDashboard/img/student_profile_pictures/' . $p->student->student_photo)
                                                    : asset('assetsDashboard/img/student_profile_pictures/student_default_profile.jpg') }}"
                                                    class="rounded me-3"
                                                    style="width: 70px; height: 70px; object-fit: cover;">
                                                <div>
                                                    <strong>{{ $p->student->full_name ?? 'Unknown' }}</strong><br>
                                                    <strong>
                                                        @if ($p->classStudent && $p->classStudent->class)
                                                            {{ ucfirst($p->classStudent->class->formatted_grade_level) }} -
                                                            {{ $p->classStudent->class->section }}
                                                        @else
                                                            —
                                                        @endif
                                                    </strong>
                                                </div>
                                            </div>
                                            <!-- Jump to History -->
                                            <button type="button" class="btn btn-info d-flex align-items-center"
                                                data-bs-target="#studentHistoryModal{{ $p->id }}"
                                                data-bs-toggle="modal" data-bs-dismiss="modal">
                                                <i class="bx bx-history me-1"></i>
                                                <span class="d-none d-sm-block">View History</span>
                                            </button>
                                        </div>

                                        <!-- Payment Progress -->
                                        <div class="mb-3">
                                            <label class="form-label">Payment Progress</label>
                                            <input type="text" class="form-control"
                                                value="₱{{ number_format($p->total_paid, 2) }} / ₱{{ number_format($p->amount_due, 2) }}"
                                                readonly>
                                        </div>

                                        <!-- Balance -->
                                        <div class="mb-3">
                                            <label class="form-label">Remaining Balance</label>
                                            <input type="text" class="form-control"
                                                value="₱{{ number_format(max($p->amount_due - $p->total_paid, 0), 2) }}"
                                                readonly>
                                        </div>

                                        <!-- Amount to Pay -->
                                        <div class="mb-3">
                                            <label for="amountPaid{{ $p->id }}" class="form-label">Amount
                                                to Pay</label>
                                            <input type="number" step="0.01" min="0" name="amount_paid"
                                                id="amountPaid{{ $p->id }}" class="form-control"
                                                data-balance="{{ max($p->amount_due - $p->total_paid, 0) }}" required>
                                            <small class="text-muted">Maximum allowed:
                                                ₱{{ number_format(max($p->amount_due - $p->total_paid, 0), 2) }}</small>
                                        </div>

                                        <!-- Current Status -->
                                        <div class="mb-3">
                                            <small class="text-muted">Current Status:
                                                @if ($p->status === 'paid')
                                                    <span class="badge bg-success">Paid</span>
                                                @elseif ($p->status === 'partial')
                                                    <span class="badge bg-warning text-dark">Partial</span>
                                                @else
                                                    <span class="badge bg-danger">Unpaid</span>
                                                @endif
                                            </small>
                                        </div>

                                        <hr>
                                    </div>

                                    <!-- Modal Footer -->
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn btn-primary">Add Payment</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Individual Student Payment History Modal -->
                    <div class="modal fade" id="studentHistoryModal{{ $p->id }}" tabindex="-1"
                        aria-labelledby="studentHistoryLabel{{ $p->id }}" data-bs-backdrop="static"
                        aria-hidden="true">
                        <div class="modal-dialog modal-lg modal-dialog-centered">
                            <div class="modal-content shadow-lg">

                                <div class="modal-body">
                                    <!-- Student Info -->
                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                        <div class="d-flex align-items-center">
                                            <img src="{{ $p->student && $p->student->student_photo
                                                ? asset('assetsDashboard/img/student_profile_pictures/' . $p->student->student_photo)
                                                : asset('assetsDashboard/img/student_profile_pictures/student_default_profile.jpg') }}"
                                                class="rounded me-3"
                                                style="width: 70px; height: 70px; object-fit: cover;">
                                            <div>
                                                <strong>{{ $p->student->full_name ?? 'Unknown' }}</strong><br>
                                                <strong>
                                                    @if ($p->classStudent && $p->classStudent->class)
                                                        {{ ucfirst($p->classStudent->class->formatted_grade_level) }} -
                                                        {{ $p->classStudent->class->section }}
                                                    @else
                                                        —
                                                    @endif
                                                </strong>
                                            </div>
                                        </div>
                                        <!-- Back to Add Payment -->
                                        <button type="button" class="btn btn-primary d-flex align-items-center"
                                            data-bs-target="#addPaymentModal{{ $p->id }}" data-bs-toggle="modal"
                                            data-bs-dismiss="modal">
                                            <i class="bx bx-left-arrow-alt me-1"></i>
                                            <span class="d-none d-sm-block">Back to Payment</span>
                                        </button>
                                    </div>

                                    <!-- Payment Progress (same as in Add Payment Modal) -->
                                    <div class="mb-3">
                                        <label class="form-label">Total Payment Amount</label>
                                        <input type="text" class="form-control"
                                            value="₱{{ number_format($p->total_paid, 2) }} / ₱{{ number_format($p->amount_due, 2) }}"
                                            readonly>
                                    </div>

                                    @if ($p->paymentHistories->count() > 0)
                                        <div class="table-responsive" style="max-height: 350px; overflow-y: auto;">
                                            <table class="table table-sm table-hover align-middle">
                                                <thead class="table-light">
                                                    <tr class="text-center">
                                                        <th>#</th>
                                                        <th>Amount</th>
                                                        <th>Date</th>
                                                        <th>Added By</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($p->paymentHistories as $i => $history)
                                                        <tr class="text-center">
                                                            <td>{{ $i + 1 }}</td>
                                                            <td>₱{{ number_format($history->amount_paid, 2) }}</td>
                                                            <td>{{ $history->payment_date->format('M d, Y h:i A') }}
                                                            </td>
                                                            <td>{{ $history->addedBy->full_name ?? '—' }}</td>
                                                            <td>
                                                                <form
                                                                    action="{{ route('admin.payments.history.delete', $history->id) }}"
                                                                    method="POST"
                                                                    onsubmit="return confirm('Delete this transaction?');">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="btn btn-sm btn-danger">
                                                                        <i class="bx bx-trash"></i>
                                                                    </button>
                                                                </form>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @else
                                        <p class="text-muted small mb-0">No payment history found.</p>
                                    @endif
                                </div>

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <!-- /Payments Table -->



            <!-- All Students Payment History Modal -->
            <div class="modal fade" id="paymentHistoryModal" data-bs-backdrop="static" tabindex="-1"
                aria-labelledby="paymentHistoryLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content shadow-lg">
                        <div class="modal-header">
                            <h5 class="modal-title" id="paymentHistoryLabel">All Payments History</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">

                            <!-- Search & Filters -->
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <input type="text" id="historySearch" class="form-control w-50"
                                    placeholder="Search student or added by...">

                                <select id="historyLength" class="form-select w-auto">
                                    <option value="10" selected>10</option>
                                    <option value="25">25</option>
                                    <option value="50">50</option>
                                    <option value="100">100</option>
                                </select>
                            </div>

                            <!-- History Table -->
                            <div class="table-responsive">
                                <table id="historyTable" class="table table-hover">
                                    <thead>
                                        <tr class="text-center">
                                            <th>#</th>
                                            <th>Student</th>
                                            <th>Amount Paid</th>
                                            <th>Payment Date</th>
                                            <th>Added By</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $allHistories = collect();
                                            foreach ($payments as $payment) {
                                                foreach ($payment->paymentHistories as $history) {
                                                    $allHistories->push([
                                                        'student' => $payment->student->full_name ?? 'Unknown',
                                                        'amount' => $history->amount_paid,
                                                        'date' => $history->payment_date,
                                                        'addedBy' => $history->addedBy->full_name ?? '—',
                                                    ]);
                                                }
                                            }
                                            $allHistories = $allHistories->sortBy('date')->values();
                                        @endphp

                                        @forelse ($allHistories as $index => $h)
                                            <tr class="history-row" data-student="{{ strtolower($h['student']) }}"
                                                data-addedby="{{ strtolower($h['addedBy']) }}">
                                                <td></td>
                                                <td>{{ $h['student'] }}</td>
                                                <td class="text-center">₱{{ number_format($h['amount'], 2) }}</td>
                                                <td class="text-center">{{ $h['date']->format('M d, Y h:i A') }}</td>
                                                <td class="text-center">{{ $h['addedBy'] }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center text-muted">No payment history
                                                    found.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <!-- Pagination Info + Controls -->
                            <div class="d-flex justify-content-between align-items-center px-2 py-2 border-top">
                                <div id="historyInfo" class="text-muted small"></div>
                                <nav aria-label="Page navigation">
                                    <ul class="pagination mb-0" id="historyPagination"></ul>
                                </nav>
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /All Students Payment History Modal -->

            <!-- Bulk Add Payment Modal -->
            <div class="modal fade" id="bulkAddPaymentModal" data-bs-backdrop="static" tabindex="-1"
                aria-labelledby="bulkAddPaymentLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content shadow-lg">
                        <div class="modal-header">
                            <h4 class="modal-title text-primary fw-bold" id="bulkAddPaymentLabel">Bulk Add Payment to
                                Selected
                                Students</h4>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <form id="bulkAddPaymentFormSubmit" action="{{ route('admin.payments.bulkAddPayment') }}"
                            method="POST">
                            @csrf
                            <div id="bulkAddPaymentIds"></div> <!-- dynamic IDs -->

                            <div class="modal-body">
                                <p class="text-muted">You are adding a payment to
                                    <span class="text-danger" id="bulkSelectedCount">0</span>
                                    <span class="text-danger">Students.</span>
                                </p>

                                <div class="mb-3">
                                    <label class="form-label">Amount to Pay</label>
                                    <input type="number" step="0.01" min="0" name="amount_paid"
                                        class="form-control" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Payment Date</label>
                                    <input type="datetime-local" name="payment_date" class="form-control"
                                        value="{{ now()->format('Y-m-d\TH:i') }}" required>
                                </div>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary">Confirm Payment</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- /Bulk Add Payment Modal -->

            <!-- Pagination + Info -->
            <div class="d-flex justify-content-between align-items-center px-3 py-3 border-top">
                <div id="tableInfo" class="text-muted small"></div>
                <nav aria-label="Page navigation">
                    <ul class="pagination mb-0" id="paymentPagination"></ul>
                </nav>
            </div>
            <!-- /Pagination + Info -->

            <!-- Add Students Modal -->
            <div class="modal fade" id="addStudentsModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content shadow-lg">
                        <form action="{{ route('admin.payments.addStudents', $paymentName) }}" method="POST">
                            @csrf

                            {{-- Persist which school year and class filter the admin was on --}}
                            <input type="hidden" name="school_year" value="{{ $selectedYear }}">
                            <input type="hidden" name="class_id" value="{{ $selectedClass ?? '' }}">

                            {{-- Copy reference values from the first payment (if available) --}}
                            <input type="hidden" name="amount_due" value="{{ optional($first)->amount_due ?? '' }}">
                            <input type="hidden" name="due_date" value="{{ optional($first)->due_date ?? '' }}">

                            <div class="modal-header">
                                <h4 class="modal-title text-primary fw-bold">Add Students to Payment</h4>
                                <button type="button" class="btn-close btn-close-white"
                                    data-bs-dismiss="modal"></button>
                            </div>

                            <div class="modal-body">
                                <div class="form-group mb-3">
                                    <label class="form-label">Select Students (Enrolled in {{ $selectedYear }})</label>
                                    <select name="class_student_ids[]" id="classStudentSelectMulti" class="tom-select"
                                        multiple required>
                                        {{-- Options filled dynamically via AJAX --}}
                                    </select>
                                </div>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-success">Add Students</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- /Add Students Modal -->


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

    <!-- Pagination, Search, Filter & Bulk Actions for Payment Table-->
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
                    `Showing ${start + 1} to ${end} of ${totalEntries} students` :
                    "Showing 0 to 0 of 0 students";

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

                    // Update Select All based on visible checkboxes only
                    const visibleCheckboxes = Array.from(document.querySelectorAll(".payment-checkbox"))
                        .filter(cb => cb.closest("tr").style.display !== "none");

                    selectAll.checked = visibleCheckboxes.length > 0 && visibleCheckboxes.every(c => c.checked);

                    // Toggle bulk form
                    const bulkForm = document.getElementById("bulkPaymentForm");
                    bulkForm.classList.toggle("d-none", selectedPayments.size === 0);
                };
            });

            // Select-All checkbox
            selectAll.addEventListener("change", () => {
                const visibleCheckboxes = Array.from(document.querySelectorAll(".payment-checkbox"))
                    .filter(cb => cb.closest("tr").style.display !== "none"); // only visible

                visibleCheckboxes.forEach(cb => {
                    cb.checked = selectAll.checked;
                    const val = cb.value;
                    if (cb.checked) selectedPayments.add(val);
                    else selectedPayments.delete(val);

                    cb.closest("tr").classList.toggle("row-highlight", cb.checked);
                });

                // Bulk form toggle based on whether at least one is selected
                const bulkForm = document.getElementById("bulkPaymentForm");
                bulkForm.classList.toggle("d-none", selectedPayments.size === 0);
            });
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

            function updateSelectAllCheckbox() {
                const visibleCheckboxes = Array.from(document.querySelectorAll(".payment-checkbox"))
                    .filter(cb => cb.closest("tr").style.display !== "none");

                selectAll.checked = visibleCheckboxes.length > 0 &&
                    visibleCheckboxes.every(cb => cb.checked);
            }

            // Individual checkboxes
            document.querySelectorAll(".payment-checkbox").forEach(cb => {
                cb.addEventListener("change", () => {
                    toggleRowHighlight(cb);
                    updateSelectAllCheckbox();
                    toggleBulkForm();
                });
            });

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
        });

        // Bulk status confirmation & submit
        function setBulkPaymentStatus(status, event) {
            event.preventDefault();

            const bulkIdsContainer = document.getElementById("bulkPaymentIds");
            const selected = Array.from(document.querySelectorAll(".payment-checkbox:checked"))
                .filter(cb => cb.closest("tr").style.display !== "none");

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
                cancelButtonText: "Cancel",
                customClass: {
                    container: 'my-swal-container'
                }
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

                    document.getElementById("bulkPaymentForm").submit();
                }
            });
        }
    </script>

    <!-- Bulk Add Payment Script -->
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const bulkBtn = document.getElementById("bulkAddPaymentBtn");
            const bulkIdsContainer = document.getElementById("bulkAddPaymentIds");
            const bulkCount = document.getElementById("bulkSelectedCount");

            if (bulkBtn) {
                bulkBtn.addEventListener("click", () => {
                    bulkIdsContainer.innerHTML = "";
                    const selected = Array.from(document.querySelectorAll(".payment-checkbox:checked"));
                    bulkCount.textContent = selected.length;

                    if (selected.length === 0) {
                        Swal.fire({
                            icon: "warning",
                            title: "No payments selected",
                            text: "Please select at least one student before adding a payment.",
                            confirmButtonColor: "#3085d6"
                        });
                        const modal = bootstrap.Modal.getInstance(document.getElementById(
                            "bulkAddPaymentModal"));
                        if (modal) modal.hide();
                        return;
                    }

                    selected.forEach(cb => {
                        const input = document.createElement("input");
                        input.type = "hidden";
                        input.name = "payment_ids[]";
                        input.value = cb.value;
                        bulkIdsContainer.appendChild(input);
                    });
                });
            }
        });
    </script>

    <!-- Bulk Remove Payment Script -->
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const bulkFormAdd = document.getElementById("bulkPaymentForm");
            const bulkFormRemove = document.getElementById("bulkRemoveForm");
            const bulkRemoveBtn = document.getElementById("bulkRemoveBtn");
            const bulkRemoveIds = document.getElementById("bulkRemoveIds");

            if (bulkRemoveBtn) {
                bulkRemoveBtn.addEventListener("click", () => {
                    bulkRemoveIds.innerHTML = "";

                    const selected = Array.from(document.querySelectorAll(".payment-checkbox:checked"))
                        .filter(cb => cb.closest("tr").style.display !== "none"); // only visible

                    if (selected.length === 0) {
                        Swal.fire({
                            icon: "warning",
                            title: "No students selected",
                            text: "Please select at least one student to remove.",
                            confirmButtonColor: "#3085d6"
                        });
                        return;
                    }

                    // Ask confirmation via SweetAlert
                    Swal.fire({
                        title: "Are you sure?",
                        text: `You are about to remove ${selected.length} student(s) from this payment list. This action cannot be undone.`,
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#d33",
                        cancelButtonColor: "#6c757d",
                        confirmButtonText: "Yes, remove them",
                        cancelButtonText: "Cancel"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // inject hidden inputs into form
                            selected.forEach(cb => {
                                const input = document.createElement("input");
                                input.type = "hidden";
                                input.name = "payment_ids[]";
                                input.value = cb.value;
                                bulkRemoveIds.appendChild(input);
                            });

                            bulkFormRemove.submit();
                        }
                    });
                });
            }

            function toggleBulkForms() {
                const anyChecked = Array.from(document.querySelectorAll(".payment-checkbox"))
                    .some(cb => cb.checked);

                bulkFormAdd.classList.toggle("d-none", !anyChecked);
                bulkFormRemove.classList.toggle("d-none", !anyChecked);
            }

            document.querySelectorAll(".payment-checkbox").forEach(cb => {
                cb.addEventListener("change", toggleBulkForms);
            });

            document.getElementById("selectAllPayments").addEventListener("change", toggleBulkForms);
        });
    </script>

    <!-- Pagination, Search, & Filter for Payment Histories -->
    <script>
        let allHistoryRows = [];
        let visibleHistories = {};
        let historyPage = 1;
        let historyRowsPerPage = 10;

        // PAGINATION for History
        function paginateHistories(tableId, paginationId, maxVisiblePages = 5) {
            const pagination = document.getElementById(paginationId);
            const rows = visibleHistories[tableId] || [];
            const tbody = document.querySelector(`#${tableId} tbody`);

            // Remove old "No results" row if it exists
            const noResultRow = tbody.querySelector(".no-results-row");
            if (noResultRow) noResultRow.remove();

            function showPage(page) {
                const totalEntries = rows.length;
                const totalPages = Math.max(1, Math.ceil(totalEntries / historyRowsPerPage));
                historyPage = Math.min(Math.max(1, page), totalPages);
                const start = (historyPage - 1) * historyRowsPerPage;
                const end = Math.min(start + historyRowsPerPage, totalEntries);

                // Hide all rows
                allHistoryRows.forEach(r => r.style.display = "none");

                if (totalEntries > 0) {
                    rows.slice(start, end).forEach((r, i) => {
                        r.style.display = "table-row";
                        r.querySelector("td:first-child").textContent = start + i + 1;
                    });
                } else {
                    // If no results, insert temporary row
                    const tr = document.createElement("tr");
                    tr.className = "no-results-row";
                    tr.innerHTML = `<td colspan="5" class="text-center text-muted">No results found</td>`;
                    tbody.appendChild(tr);
                }

                document.getElementById("historyInfo").textContent =
                    totalEntries > 0 ?
                    `Showing ${start + 1} to ${end} of ${totalEntries} histories` :
                    "Showing 0 to 0 of 0 histories";

                // Rebuild pagination
                pagination.innerHTML = "";
                const prev = document.createElement("li");
                prev.className = `page-item ${historyPage === 1 ? 'disabled' : ''}`;
                prev.innerHTML = `<a class="page-link" href="#">«</a>`;
                prev.onclick = (e) => {
                    e.preventDefault();
                    if (historyPage > 1) showPage(historyPage - 1);
                };
                pagination.appendChild(prev);

                let startPage = Math.max(1, historyPage - Math.floor(maxVisiblePages / 2));
                let endPage = Math.min(totalPages, startPage + maxVisiblePages - 1);

                for (let i = startPage; i <= endPage; i++) {
                    const li = document.createElement("li");
                    li.className = `page-item ${i === historyPage ? 'active' : ''}`;
                    li.innerHTML = `<a class="page-link" href="#">${i}</a>`;
                    li.onclick = (e) => {
                        e.preventDefault();
                        showPage(i);
                    };
                    pagination.appendChild(li);
                }

                const next = document.createElement("li");
                next.className = `page-item ${historyPage === totalPages ? 'disabled' : ''}`;
                next.innerHTML = `<a class="page-link" href="#">»</a>`;
                next.onclick = (e) => {
                    e.preventDefault();
                    if (historyPage < totalPages) showPage(historyPage + 1);
                };
                pagination.appendChild(next);
            }

            showPage(historyPage);
        }

        // FILTER Histories
        function filterHistories() {
            const tableId = "historyTable";
            const query = document.getElementById("historySearch").value.trim().toLowerCase();

            const filtered = allHistoryRows.filter(row => {
                const student = row.dataset.student || "";
                const addedBy = row.dataset.addedby || "";
                return query === "" || student.includes(query) || addedBy.includes(query);
            });

            visibleHistories[tableId] = filtered;
            historyPage = 1;
            paginateHistories(tableId, "historyPagination");
        }

        // INIT when modal opens
        document.addEventListener("DOMContentLoaded", () => {
            allHistoryRows = Array.from(document.querySelectorAll("#historyTable tbody tr.history-row"));
            visibleHistories["historyTable"] = allHistoryRows;
            filterHistories();

            document.getElementById("historySearch").addEventListener("input", () => setTimeout(filterHistories,
                300));
            document.getElementById("historyLength").addEventListener("change", function() {
                historyRowsPerPage = parseInt(this.value);
                historyPage = 1;
                paginateHistories("historyTable", "historyPagination");
            });

            // Re-init each time modal opens
            const historyModal = document.getElementById("paymentHistoryModal");
            historyModal.addEventListener("shown.bs.modal", () => {
                filterHistories();
            });
        });
    </script>

    <!-- Add Payment Validation for preventing overpayment -->
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            document.querySelectorAll("input[name='amount_paid']").forEach(input => {
                input.addEventListener("input", () => {
                    const maxBalance = parseFloat(input.dataset.balance);
                    if (parseFloat(input.value) > maxBalance) {
                        input.setCustomValidity("Payment exceeds remaining balance (₱" + maxBalance
                            .toFixed(2) + ")");
                    } else {
                        input.setCustomValidity("");
                    }
                });
            });
        });
    </script>

    @if (session('bulk_error'))
        <script>
            document.addEventListener("DOMContentLoaded", () => {
                Swal.fire({
                    icon: "error",
                    title: "Bulk Payment Failed",
                    text: "{{ session('bulk_error') }}",
                    confirmButtonColor: "#d33",
                    customClass: {
                        container: 'my-swal-container'
                    },
                });
            });
        </script>
    @endif

    <!-- To calculate collected/expected collection dynamically -->
    <script>
        function updateCollected() {
            let rows = document.querySelectorAll('.payment-row');
            let totalCollected = 0;
            let totalExpected = 0;

            rows.forEach(row => {
                let paid = parseFloat(row.dataset.amountPaid || 0);
                let due = parseFloat(row.dataset.amountDue || 0);

                totalCollected += paid;
                totalExpected += due;
            });

            // update UI
            document.getElementById('collectedValue').textContent = totalCollected.toLocaleString('en-PH', {
                minimumFractionDigits: 2
            });
            document.getElementById('expectedValue').textContent = totalExpected.toLocaleString('en-PH', {
                minimumFractionDigits: 2
            });
        }

        // Run on page load
        updateCollected();

        // Optionally re-run after filtering, searching, adding/deleting payments
        document.addEventListener('paymentsUpdated', updateCollected);
    </script>

    <!-- Tom Select for adding students to a payment -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            if (document.getElementById('classStudentSelectMulti')) {
                new TomSelect('#classStudentSelectMulti', {
                    plugins: ['remove_button'],
                    maxItems: null,
                    placeholder: "Search enrolled students...",
                    valueField: 'id',
                    labelField: 'text',
                    searchField: 'text',
                    load: function(query, callback) {
                        if (!query.length) return callback();

                        const url = "{{ route('class-students.search.exclude-payment') }}" +
                            "?q=" + encodeURIComponent(query) +
                            "&year={{ $selectedYear }}" +
                            "&payment_name={{ $paymentName }}";

                        fetch(url)
                            .then(response => response.json())
                            .then(data => callback(data))
                            .catch(() => callback());
                    }
                });
            }
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

    <style>
        .row-highlight {
            background-color: #e0f7fa !important;
            /* light cyan highlight */
        }

        .chkbox {
            cursor: pointer;
        }

        .payment-checkbox {
            cursor: pointer;
        }

        .ts-control {
            background-color: #e0f7fa;
            border-color: #42a5f5;
        }

        .ts-control .item {
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
