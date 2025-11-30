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

            {{-- School Fees sidebar --}}
            <li class="menu-item active open">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons bx bx-wallet-alt"></i>
                    <div>School Fees</div>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item active">
                        <a href="{{ route('admin.school-fees.index') }}" class="menu-link bg-dark text-light">
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
        <!-- Breadcrumb -->
        <h4 class="fw-bold py-3 mb-4 text-warning">
            <span class="text-muted fw-light">
                <a class="text-muted fw-light" href="{{ route('home') }}">Dashboard</a> /
                <a class="text-muted fw-light" href="{{ route('admin.school-fees.index') }}">
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

                    <!-- Right side: Payment History & Payment Requests Buttons -->
                    <div class="d-flex gap-2 mt-2 mt-sm-0">
                        <!-- Payment Requests Button with Notification Badge -->
                        <button type="button" class="btn btn-warning d-flex align-items-center position-relative"
                            id="paymentRequestsBtn" data-bs-toggle="modal" data-bs-target="#paymentRequestsModal">
                            <i class='bx bx-money me-1'></i>
                            <span class="d-none d-sm-block">Payment Requests</span>

                            <!-- Notification Badge -->
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                                id="paymentRequestsBadge" style="display: none;">
                                <span id="pendingRequestsCount">0</span>
                                <span class="visually-hidden">pending payment requests</span>
                            </span>
                        </button>

                        <!-- Payment History Button -->
                        <button type="button" class="btn btn-info d-flex align-items-center" id="paymentHistoryBtn"
                            data-bs-toggle="modal" data-bs-target="#paymentHistoryModal">
                            <i class='bx bx-credit-card me-1'></i>
                            <span class="d-none d-sm-block">All Payments History</span>
                        </button>
                    </div>
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
                            <th>Latest Payment Method</th>
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
                                                ? asset('public/uploads/' . $p->student->student_photo)
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

                                <!-- Last Payment Method -->
                                <td class="text-center">
                                    {{ $p->latestPaymentMethod() ?? '—' }}
                                </td>

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

                                            <button type="button" class="btn btn-info d-flex align-items-center"
                                                data-bs-target="#studentHistoryModal{{ $p->id }}"
                                                data-bs-toggle="modal" data-bs-dismiss="modal">
                                                <i class="bx bx-history me-1"></i> View History
                                            </button>
                                        </div>

                                        <!-- Payment Progress -->
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold">Payment Progress</label>
                                            <input type="text" class="form-control"
                                                value="₱{{ number_format($p->total_paid, 2) }} / ₱{{ number_format($p->amount_due, 2) }}"
                                                readonly>
                                        </div>

                                        <!-- Remaining Balance -->
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold">Remaining Balance</label>
                                            <input type="text" class="form-control"
                                                value="₱{{ number_format(max($p->amount_due - $p->total_paid, 0), 2) }}"
                                                readonly>
                                        </div>

                                        <!-- Hidden field to store the selected method -->
                                        <input type="hidden" name="payment_method"
                                            id="hidden_payment_method_{{ $p->id }}" value="cash_on_hand">

                                        <!-- Payment Method -->
                                        {{-- <div class="mb-3">
                                            <label class="form-label fw-semibold">Payment Method</label>
                                            <div class="d-flex flex-wrap gap-3">
                                                <label
                                                    class="payment-option p-3 border rounded-3 d-flex align-items-center gap-2">
                                                    <input type="radio" name="payment_method_{{ $p->id }}"
                                                        value="cash_on_hand" checked>
                                                    <i class="bx bx-money text-success fs-4"></i> Cash on Hand
                                                </label>

                                                <label
                                                    class="payment-option p-3 border rounded-3 d-flex align-items-center gap-2">
                                                    <input type="radio" name="payment_method_{{ $p->id }}"
                                                        value="gcash">
                                                    <i class="bx bxl-paypal text-primary fs-4"></i> GCash
                                                </label>

                                                <label
                                                    class="payment-option p-3 border rounded-3 d-flex align-items-center gap-2">
                                                    <input type="radio" name="payment_method_{{ $p->id }}"
                                                        value="credit_card">
                                                    <i class="bx bx-credit-card text-warning fs-4"></i> Credit Card
                                                </label>
                                            </div>
                                        </div> --}}

                                        <!-- GCash Section -->
                                        <div id="gcash_section_{{ $p->id }}" class="d-none mb-3">
                                            <label class="form-label fw-semibold">GCash Reference No.</label>
                                            <input type="text" name="gcash_ref" class="form-control"
                                                placeholder="Enter GCash reference number (optional)">
                                        </div>

                                        <!-- Amount -->
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold">Amount to Pay</label>
                                            <input type="number" step="0.01" name="amount_paid" class="form-control"
                                                min="0.01" max="{{ max($p->amount_due - $p->total_paid, 0) }}"
                                                required>
                                            <small class="text-muted">Max:
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
                                    </div>

                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn btn-primary">Confirm Payment</button>
                                    </div>
                                </form>

                                {{-- <form action="{{ route('admin.payments.add', $p->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')

                                    <!-- Hidden field to store the selected method -->
                                    <input type="hidden" name="payment_method"
                                        id="hidden_payment_method_{{ $p->id }}" value="cash_on_hand">

                                    <div class="modal-body">
                                        <!-- Payment Method -->
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold">Payment Method</label>
                                            <div class="d-flex flex-wrap gap-3">
                                                <label
                                                    class="payment-option p-3 border rounded-3 d-flex align-items-center gap-2">
                                                    <input type="radio" name="payment_method_{{ $p->id }}"
                                                        value="cash_on_hand" checked>
                                                    <i class="bx bx-money text-success fs-4"></i> Cash on Hand
                                                </label>

                                                <label
                                                    class="payment-option p-3 border rounded-3 d-flex align-items-center gap-2">
                                                    <input type="radio" name="payment_method_{{ $p->id }}"
                                                        value="gcash">
                                                    <i class="bx bxl-paypal text-primary fs-4"></i> GCash
                                                </label>

                                                <label
                                                    class="payment-option p-3 border rounded-3 d-flex align-items-center gap-2">
                                                    <input type="radio" name="payment_method_{{ $p->id }}"
                                                        value="credit_card">
                                                    <i class="bx bx-credit-card text-warning fs-4"></i> Credit Card
                                                </label>
                                            </div>
                                        </div>

                                        <!-- GCash Section -->
                                        <div id="gcash_section_{{ $p->id }}" class="d-none mb-3">
                                            <label class="form-label fw-semibold">GCash Reference No.</label>
                                            <input type="text" name="gcash_ref" class="form-control"
                                                placeholder="Enter GCash reference number (optional)">
                                        </div>

                                        <!-- Amount -->
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold">Amount to Pay</label>
                                            <input type="number" step="0.01" name="amount_paid" class="form-control"
                                                min="0.01" max="{{ max($p->amount_due - $p->total_paid, 0) }}"
                                                required>
                                        </div>
                                    </div>

                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn btn-primary">Confirm Payment</button>
                                    </div>
                                </form> --}}
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
                                                        <th>Payment Method</th>
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
                                                            <td>{{ $history->payment_method_name }}</td>
                                                            <td>{{ $history->payment_date->format('M d, Y h:i A') }}</td>
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
                                            <th>Payment Method</th>
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
                                                        'method' => $history->payment_method_name,
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
                                                <td class="text-center">{{ $h['method'] }}</td>
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

            <!-- Payment Requests Modal -->
            <div class="modal fade" id="paymentRequestsModal" data-bs-backdrop="static" tabindex="-1"
                aria-labelledby="paymentRequestsLabel" aria-hidden="true">
                <div class="modal-dialog modal-xl modal-dialog-centered">
                    <div class="modal-content shadow-lg">
                        <div class="modal-header">
                            <h5 class="modal-title" id="paymentRequestsLabel">
                                Payment Requests for {{ $paymentName }}
                                <!-- New Requests Indicator -->
                                <span class="badge bg-info text-dark ms-2" id="newRequestsIndicator"
                                    style="display: none;">
                                    <i class="bx bx-message-alt-add me-1"></i>
                                    <span id="newRequestsCount">0</span> New Requests
                                </span>
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <!-- Search & Filters -->
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <input type="text" id="requestsSearch" class="form-control w-50"
                                    placeholder="Search student or parent...">

                                <div class="d-flex gap-2">
                                    <!-- New Requests Filter -->
                                    <select id="requestsNewFilter" class="form-select w-auto">
                                        <option value="all">All Requests</option>
                                        <option value="new">New Requests</option>
                                        <option value="pending">All Pending</option>
                                        <option value="approved">Approved</option>
                                        <option value="denied">Denied</option>
                                    </select>

                                    <select id="requestsStatusFilter" class="form-select w-auto">
                                        <option value="all">All Status</option>
                                        <option value="pending">Pending</option>
                                        <option value="approved">Approved</option>
                                        <option value="denied">Denied</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Requests Table -->
                            <div class="table-responsive">
                                <table id="requestsTable" class="table table-hover">
                                    <thead>
                                        <tr class="text-center">
                                            <th>#</th>
                                            <th>Student</th>
                                            <th>Parent</th>
                                            <th>Amount</th>
                                            <th>Payment Method</th>
                                            <th>Reference No.</th>
                                            <th>Receipt</th> <!-- New Receipt Column -->
                                            <th>Status</th>
                                            <th>Requested At</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            use App\Models\PaymentRequest;
                                            $paymentRequests = PaymentRequest::with([
                                                'payment.classStudent.student',
                                                'payment.classStudent.class',
                                                'parent',
                                            ])
                                                ->whereHas('payment', function ($query) use (
                                                    $paymentName,
                                                    $selectedYear,
                                                ) {
                                                    $query
                                                        ->where('payment_name', $paymentName)
                                                        ->whereHas('classStudent.schoolYear', function ($q) use (
                                                            $selectedYear,
                                                        ) {
                                                            $q->where('school_year', $selectedYear);
                                                        });
                                                })
                                                ->orderBy('requested_at', 'desc')
                                                ->get();

                                            // Calculate new requests (last 24 hours)
                                            $newRequestsCount = $paymentRequests
                                                ->where('status', 'pending')
                                                ->filter(function ($request) {
                                                    return $request->requested_at->gt(now()->subDay());
                                                })
                                                ->count();

                                            $totalPendingCount = $paymentRequests->where('status', 'pending')->count();
                                        @endphp

                                        @forelse ($paymentRequests as $index => $request)
                                            @php
                                                $student = $request->payment->classStudent->student ?? null;
                                                $parent = $request->parent ?? null;
                                                $class = $request->payment->classStudent->class ?? null;
                                                $isNew =
                                                    $request->status === 'pending' &&
                                                    $request->requested_at->gt(now()->subDay());
                                            @endphp
                                            <tr class="request-row @if ($isNew) new-request @endif"
                                                data-student="{{ strtolower($student->full_name ?? 'Unknown') }}"
                                                data-parent="{{ strtolower($parent->full_name ?? 'Unknown') }}"
                                                data-status="{{ $request->status }}"
                                                data-is-new="{{ $isNew ? 'true' : 'false' }}"
                                                data-requested-at="{{ $request->requested_at->timestamp }}">
                                                <td class="text-center">
                                                    @if ($isNew)
                                                        <span class="badge bg-info rounded-circle p-1 me-1"
                                                            title="New Request">
                                                            <i class="bx bx-message-alt-add bx-xs"></i>
                                                        </span>
                                                    @endif
                                                    @if ($isNew)
                                                        <br><small class="text-info fw-bold">NEW</small>
                                                    @endif
                                                    {{ $index + 1 }}
                                                </td>
                                                <td>
                                                    @if ($student)
                                                        <div class="d-flex align-items-center">
                                                            <img src="{{ $student->student_photo ? asset('public/uploads/' . $student->student_photo) : asset('assetsDashboard/img/student_profile_pictures/student_default_profile.jpg') }}"
                                                                class="rounded-circle me-2"
                                                                style="width: 35px; height: 35px; object-fit: cover;">
                                                            <div>
                                                                <div class="fw-semibold">{{ $student->full_name }}</div>
                                                                <small class="text-muted">
                                                                    @if ($class)
                                                                        {{ $class->formatted_grade_level ?? ucfirst($class->grade_level) }}
                                                                        - {{ $class->section }}
                                                                    @endif
                                                                </small>
                                                            </div>
                                                        </div>
                                                    @else
                                                        Unknown Student
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($parent)
                                                        <div class="d-flex align-items-center">
                                                            <img src="{{ $parent->profile_photo ? asset('public/uploads/' . $parent->profile_photo) : asset('assetsDashboard/img/parent_profile_pictures/parent_default_profile.jpg') }}"
                                                                class="rounded-circle me-2"
                                                                style="width: 35px; height: 35px; object-fit: cover;">
                                                            <div>
                                                                <div class="fw-semibold">{{ $parent->firstName }}
                                                                    {{ $parent->lastName }}</div>
                                                                <small class="text-muted">
                                                                    {{ $parent->email ?? 'No email' }}
                                                                </small>
                                                            </div>
                                                        </div>
                                                    @else
                                                        —
                                                    @endif
                                                </td>
                                                <td class="text-center">₱{{ number_format($request->amount_paid, 2) }}
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge bg-secondary text-capitalize">
                                                        {{ str_replace('_', ' ', $request->payment_method) }}
                                                    </span>
                                                </td>
                                                <td class="text-center">
                                                    {{ $request->reference_number ?? '—' }}
                                                </td>
                                                <td class="text-center">
                                                    @if ($request->receipt_image)
                                                        <button type="button"
                                                            class="btn btn-sm btn-outline-primary view-receipt"
                                                            data-receipt-url="{{ Storage::url($request->receipt_image) }}"
                                                            data-student-name="{{ $student->full_name ?? 'Unknown' }}"
                                                            data-parent-name="{{ $parent ? $parent->firstName . ' ' . $parent->lastName : 'Unknown' }}"
                                                            data-amount="₱{{ number_format($request->amount_paid, 2) }}"
                                                            title="View Receipt">
                                                            <i class="bx bx-image-alt"></i> View
                                                        </button>
                                                    @else
                                                        <span class="text-muted">No receipt</span>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    @if ($request->status === 'pending')
                                                        <span class="badge bg-warning text-dark">Pending</span>
                                                    @elseif($request->status === 'approved')
                                                        <span class="badge bg-success">Approved</span>
                                                    @else
                                                        <span class="badge bg-danger">Denied</span>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    {{ \Carbon\Carbon::parse($request->requested_at)->format('M d, Y h:i A') }}
                                                </td>
                                                <td class="text-center">
                                                    @if ($request->status === 'pending')
                                                        <div class="btn-group btn-group-sm">
                                                            <button type="button" class="btn btn-success approve-request"
                                                                data-request-id="{{ $request->id }}"
                                                                title="Approve Request">
                                                                <i class="bx bx-check"></i>
                                                            </button>
                                                            <button type="button" class="btn btn-danger deny-request"
                                                                data-request-id="{{ $request->id }}"
                                                                title="Deny Request">
                                                                <i class="bx bx-x"></i>
                                                            </button>
                                                        </div>
                                                    @else
                                                        <small class="text-muted">Reviewed</small>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="10" class="text-center text-muted py-4">
                                                    <i class="bx bx-money fs-1"></i>
                                                    <p class="mt-2 mb-0">No payment requests found</p>
                                                    <small>All payment requests will appear here</small>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /Payment Requests Modal -->

            <!-- Bulk Add Payment Modal -->
            <div class="modal fade" id="bulkAddPaymentModal" data-bs-backdrop="static" tabindex="-1"
                aria-labelledby="bulkAddPaymentLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content shadow-lg">
                        <div class="modal-header">
                            <h4 class="modal-title text-primary fw-bold" id="bulkAddPaymentLabel">Bulk Add Payment</h4>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>

                        <form id="bulkAddPaymentFormSubmit" action="{{ route('admin.payments.bulkAddPayment') }}"
                            method="POST">
                            @csrf
                            <div id="bulkAddPaymentIds"></div>

                            <div class="modal-body">
                                <p class="text-muted">You are adding a payment to
                                    <span class="text-danger" id="bulkSelectedCount">0</span> Students.
                                </p>

                                <!-- Hidden field to store the selected method -->
                                <input type="hidden" name="payment_method"
                                    id="hidden_payment_method_{{ $p->id }}" value="cash_on_hand">

                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Amount to Pay</label>
                                    <input type="number" step="0.01" min="0" name="amount_paid"
                                        class="form-control" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Payment Date</label>
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
            <div class="modal fade" id="addStudentsModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
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
                                <h4 class="modal-title text-primary fw-bold">Add Students to School Fee</h4>
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

            <!-- Denial Remarks Modal -->
            <div class="modal fade" id="denialRemarksModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Reason for Denial</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="denialRemarksTextarea" class="form-label">Please provide a reason for denying
                                    this payment request:</label>
                                <textarea class="form-control" id="denialRemarksTextarea" rows="4" placeholder="Enter your remarks here..."
                                    required></textarea>
                                <div class="invalid-feedback">Please provide a reason for denial.</div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-danger" id="confirmDenialBtn">Deny Request</button>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <!-- /Payments Card -->
    </div>
    <!-- /Content wrapper -->

    <!-- Receipt View Modal -->
    <div class="modal fade" id="receiptViewModal" tabindex="-1" aria-labelledby="receiptViewModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="receiptViewModalLabel">Payment Receipt</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <strong>Student:</strong>
                                <span id="receiptStudentName">-</span>
                            </div>
                            <div class="mb-3">
                                <strong>Parent:</strong>
                                <span id="receiptParentName">-</span>
                            </div>
                            <div class="mb-3">
                                <strong>Amount:</strong>
                                <span id="receiptAmount">-</span>
                            </div>
                        </div>
                        <div class="col-md-6 text-end">
                            <div class="mb-3">
                                <small class="text-muted">Click image to zoom</small>
                            </div>
                        </div>
                    </div>

                    <div class="text-center">
                        <img id="receiptImage" src="" alt="Payment Receipt"
                            class="img-fluid rounded shadow-sm receipt-image" style="max-height: 500px; cursor: pointer;"
                            data-bs-toggle="modal" data-bs-target="#receiptZoomModal">
                    </div>

                    <div class="mt-3 text-center">
                        <button type="button" class="btn btn-outline-secondary btn-sm" onclick="downloadReceipt()">
                            <i class="bx bx-download me-1"></i> Download Receipt
                        </button>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Receipt Zoom Modal -->
    <div class="modal fade" id="receiptZoomModal" tabindex="-1" aria-labelledby="receiptZoomModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="receiptZoomModalLabel">Receipt Preview</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <img id="receiptZoomImage" src="" alt="Payment Receipt" class="img-fluid">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Overlay -->
    <div class="layout-overlay layout-menu-toggle"></div>

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

    <!-- Individual Add Payment Confirmation -->
    <script>
        document.addEventListener('submit', function(e) {
            const form = e.target;

            // Match any form posting to /admin/payments/add/{id}
            if (form.action.includes('admin/payments/add')) {
                e.preventDefault();

                const amountInput = form.querySelector('input[name="amount_paid"]');
                const amount = amountInput ? parseFloat(amountInput.value || 0) : 0;

                if (!amount || amount <= 0) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Invalid Amount',
                        text: 'Please enter a valid payment amount before confirming.',
                        confirmButtonColor: '#dc3545',
                    });
                    return;
                }

                Swal.fire({
                    title: 'Confirm Payment?',
                    text: `You are about to record a payment of ₱${amount.toFixed(2)}.`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#06D001',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes, confirm payment',
                    cancelButtonText: 'Cancel',
                    customClass: {
                        container: 'my-swal-container'
                    }
                }).then(result => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            }
        });
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

    <!-- Dynamic Payment Method Script -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            document.querySelectorAll('[id^="addPaymentModal"]').forEach(modal => {
                const modalId = modal.id.replace('addPaymentModal', '');
                const radios = modal.querySelectorAll(`input[name="payment_method_${modalId}"]`);
                const gcashSection = document.getElementById(`gcash_section_${modalId}`);
                const hiddenInput = document.getElementById(`hidden_payment_method_${modalId}`);

                radios.forEach(radio => {
                    radio.addEventListener("change", function() {
                        // Update hidden input
                        hiddenInput.value = this.value;

                        // Show/hide GCash section
                        gcashSection.classList.toggle("d-none", this.value !== "gcash");

                        // Highlight selection
                        radios.forEach(r => r.closest('.payment-option').classList.remove(
                            'border-primary', 'bg-primary-subtle'));
                        this.closest('.payment-option').classList.add('border-primary',
                            'bg-primary-subtle');
                    });
                });
            });
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

    <!-- Payment Requests Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Filter payment requests
            function filterPaymentRequests() {
                const searchTerm = document.getElementById('requestsSearch').value.toLowerCase();
                const statusFilter = document.getElementById('requestsStatusFilter').value;
                const rows = document.querySelectorAll('#requestsTable .request-row');

                rows.forEach(row => {
                    const student = row.dataset.student || '';
                    const parent = row.dataset.parent || '';
                    const status = row.dataset.status || '';

                    const matchesSearch = student.includes(searchTerm) || parent.includes(searchTerm);
                    const matchesStatus = statusFilter === 'all' || status === statusFilter;

                    row.style.display = matchesSearch && matchesStatus ? '' : 'none';
                });
            }

            // Event listeners for filtering
            document.getElementById('requestsSearch').addEventListener('input', filterPaymentRequests);
            document.getElementById('requestsStatusFilter').addEventListener('change', filterPaymentRequests);

            // Approve/Deny request handlers
            document.querySelectorAll('.approve-request').forEach(btn => {
                btn.addEventListener('click', function() {
                    const requestId = this.dataset.requestId;
                    handleRequestAction(requestId, 'approve');
                });
            });

            document.querySelectorAll('.deny-request').forEach(btn => {
                btn.addEventListener('click', function() {
                    const requestId = this.dataset.requestId;
                    handleRequestAction(requestId, 'deny');
                });
            });

            // Updated deny request handler using Bootstrap modal
            function handleRequestAction(requestId, action) {
                const actionText = action === 'approve' ? 'approve' : 'deny';
                const actionColor = action === 'approve' ? '#06D001' : '#d33';

                if (action === 'approve') {
                    // Direct approval without remarks
                    Swal.fire({
                        title: `Approve Payment Request?`,
                        text: `Are you sure you want to approve this payment request?`,
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: actionColor,
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: `Yes, approve it`,
                        cancelButtonText: 'Cancel',
                        customClass: {
                            container: 'my-swal-container'
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            submitRequestAction(requestId, action, '');
                        }
                    });
                } else {
                    // Use Bootstrap modal for denial remarks
                    const denialModal = new bootstrap.Modal(document.getElementById('denialRemarksModal'));
                    const remarksTextarea = document.getElementById('denialRemarksTextarea');
                    const confirmDenialBtn = document.getElementById('confirmDenialBtn');

                    // Reset form
                    remarksTextarea.value = '';
                    remarksTextarea.classList.remove('is-invalid');

                    // Remove previous event listeners
                    const newConfirmBtn = confirmDenialBtn.cloneNode(true);
                    confirmDenialBtn.parentNode.replaceChild(newConfirmBtn, confirmDenialBtn);

                    // Add new event listener
                    newConfirmBtn.addEventListener('click', function() {
                        const remarks = remarksTextarea.value.trim();

                        if (!remarks) {
                            remarksTextarea.classList.add('is-invalid');
                            return;
                        }

                        // Close the modal
                        denialModal.hide();

                        // Submit the denial
                        submitRequestAction(requestId, action, remarks);
                    });

                    // Show the modal
                    denialModal.show();

                    // Focus the textarea when modal is shown
                    document.getElementById('denialRemarksModal').addEventListener('shown.bs.modal', function() {
                        remarksTextarea.focus();
                    });
                }
            }

            function submitRequestAction(requestId, action, remarks = '') {
                // Show loading state
                const buttons = document.querySelectorAll(`[data-request-id="${requestId}"]`);
                buttons.forEach(btn => {
                    btn.disabled = true;
                    if (action === 'approve') {
                        btn.innerHTML = '<i class="bx bx-loader bx-spin"></i>';
                    } else {
                        btn.innerHTML = '<i class="bx bx-loader bx-spin"></i>';
                    }
                });

                // Create form data
                const formData = new FormData();
                formData.append('_token', '{{ csrf_token() }}');
                formData.append('_method', 'POST');

                if (remarks) {
                    formData.append('admin_remarks', remarks);
                }

                // Determine the correct URL
                const url = `/admin/payment-requests/${requestId}/${action}`;

                fetch(url, {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: formData
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: `Request ${action === 'approve' ? 'Approved' : 'Denied'}`,
                                text: data.message,
                                confirmButtonColor: '#06D001',
                                customClass: {
                                    container: 'my-swal-container'
                                }
                            }).then(() => {
                                // Reload the page to reflect changes
                                location.reload();
                            });
                        } else {
                            throw new Error(data.message || 'Action failed');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: error.message || 'Failed to process request. Please try again.',
                            confirmButtonColor: '#d33',
                            customClass: {
                                container: 'my-swal-container'
                            }
                        });
                    })
                    .finally(() => {
                        // Reset button state
                        buttons.forEach(btn => {
                            btn.disabled = false;
                            if (action === 'approve') {
                                btn.innerHTML = '<i class="bx bx-check"></i>';
                            } else {
                                btn.innerHTML = '<i class="bx bx-x"></i>';
                            }
                        });
                    });
            }

            // Initialize filtering when modal opens
            const requestsModal = document.getElementById('paymentRequestsModal');
            if (requestsModal) {
                requestsModal.addEventListener('shown.bs.modal', function() {
                    filterPaymentRequests();
                });
            }
        });
    </script>

    <!-- Payment Requests Real-time Notification System -->
    <script>
        // Payment Requests Real-time Notification System
        document.addEventListener('DOMContentLoaded', function() {
            let pendingRequestsCount = {{ $totalPendingCount }};
            let newRequestsCount = {{ $newRequestsCount }};
            let lastCheckedAt = localStorage.getItem('paymentRequestsLastChecked') || new Date().toISOString();

            // Initialize badge display
            updateNotificationBadge();
            updateNewRequestsIndicator();

            // Function to update notification badge
            function updateNotificationBadge() {
                const badge = document.getElementById('paymentRequestsBadge');
                const countSpan = document.getElementById('pendingRequestsCount');

                if (pendingRequestsCount > 0) {
                    countSpan.textContent = pendingRequestsCount;
                    badge.style.display = 'block';

                    // Add pulse animation for new requests
                    if (newRequestsCount > 0) {
                        badge.classList.add('pulse-badge');
                    } else {
                        badge.classList.remove('pulse-badge');
                    }
                } else {
                    badge.style.display = 'none';
                }
            }

            // Function to update new requests indicator in modal
            function updateNewRequestsIndicator() {
                const indicator = document.getElementById('newRequestsIndicator');
                const countSpan = document.getElementById('newRequestsCount');

                if (newRequestsCount > 0) {
                    countSpan.textContent = newRequestsCount;
                    indicator.style.display = 'inline-block';
                } else {
                    indicator.style.display = 'none';
                }
            }

            // Real-time updates using polling (every 30 seconds)
            function checkForNewRequests() {
                fetch('{{ route('admin.payment-requests.check-new') }}', {
                        method: 'GET',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            if (data.pending_count !== pendingRequestsCount || data.new_count !==
                                newRequestsCount) {
                                pendingRequestsCount = data.pending_count;
                                newRequestsCount = data.new_count;
                                updateNotificationBadge();
                                updateNewRequestsIndicator();

                                // Show notification for new requests
                                if (data.new_count > 0 && !document.getElementById('paymentRequestsModal')
                                    .classList.contains('show')) {
                                    showNewRequestNotification(data.new_count);
                                }
                            }
                        }
                    })
                    .catch(error => console.error('Error checking new requests:', error));
            }

            // Show desktop notification for new requests
            function showNewRequestNotification(count) {
                if (Notification.permission === 'granted') {
                    new Notification(`New Payment Request${count > 1 ? 's' : ''}`, {
                        body: `You have ${count} new payment request${count > 1 ? 's' : ''} pending review`,
                        icon: '{{ asset('assetsDashboard/img/logo.png') }}',
                        tag: 'payment-requests'
                    });
                }
            }

            // Request notification permission
            if ('Notification' in window && Notification.permission === 'default') {
                Notification.requestPermission();
            }

            // Start polling for updates
            setInterval(checkForNewRequests, 30000); // Check every 30 seconds

            // Mark requests as seen when modal is opened
            const requestsModal = document.getElementById('paymentRequestsModal');
            requestsModal.addEventListener('shown.bs.modal', function() {
                // Update last checked time
                lastCheckedAt = new Date().toISOString();
                localStorage.setItem('paymentRequestsLastChecked', lastCheckedAt);

                // Reset new requests count for current session
                newRequestsCount = 0;
                updateNotificationBadge();
                updateNewRequestsIndicator();

                // Remove new indicators from visible rows
                document.querySelectorAll('.new-request').forEach(row => {
                    row.classList.remove('new-request');
                    row.dataset.isNew = 'false';
                });
            });

            // Enhanced filtering for payment requests
            function filterPaymentRequests() {
                const searchTerm = document.getElementById('requestsSearch').value.toLowerCase();
                const statusFilter = document.getElementById('requestsStatusFilter').value;
                const newFilter = document.getElementById('requestsNewFilter').value;
                const rows = document.querySelectorAll('#requestsTable .request-row');

                rows.forEach(row => {
                    const student = row.dataset.student || '';
                    const parent = row.dataset.parent || '';
                    const status = row.dataset.status || '';
                    const isNew = row.dataset.isNew === 'true';
                    const requestedAt = parseInt(row.dataset.requestedAt) * 1000;
                    const isRecent = (Date.now() - requestedAt) < (24 * 60 * 60 * 1000); // Within 24 hours

                    const matchesSearch = student.includes(searchTerm) || parent.includes(searchTerm);
                    const matchesStatus = statusFilter === 'all' || status === statusFilter;
                    const matchesNewFilter = newFilter === 'all' ||
                        (newFilter === 'new' && isNew && isRecent) ||
                        (newFilter === 'pending' && status === 'pending');

                    row.style.display = matchesSearch && matchesStatus && matchesNewFilter ? '' : 'none';
                });
            }

            // Event listeners for enhanced filtering
            document.getElementById('requestsSearch').addEventListener('input', filterPaymentRequests);
            document.getElementById('requestsStatusFilter').addEventListener('change', filterPaymentRequests);
            document.getElementById('requestsNewFilter').addEventListener('change', filterPaymentRequests);

            // Update counts when requests are approved/denied
            function updateRequestCounts() {
                pendingRequestsCount = Math.max(0, pendingRequestsCount - 1);
                newRequestsCount = Math.max(0, newRequestsCount - 1);
                updateNotificationBadge();
                updateNewRequestsIndicator();
            }

            // Modify existing approve/deny handlers to update counts
            document.querySelectorAll('.approve-request, .deny-request').forEach(btn => {
                btn.addEventListener('click', function() {
                    // Update counts immediately for better UX
                    updateRequestCounts();
                });
            });
        });
    </script>

    <!-- Bulk Add Payment Confirmation -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const bulkForm = document.getElementById('bulkAddPaymentFormSubmit');

            if (bulkForm) {
                bulkForm.addEventListener('submit', function(e) {
                    e.preventDefault();

                    const amount = parseFloat(this.querySelector('input[name="amount_paid"]').value || 0);
                    const count = parseInt(document.getElementById('bulkSelectedCount')?.textContent ||
                        '0');

                    if (!amount || amount <= 0) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Invalid Amount',
                            text: 'Please enter a valid amount before confirming.',
                            confirmButtonColor: '#dc3545',
                        });
                        return;
                    }

                    if (count === 0) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'No Students Selected',
                            text: 'Please select at least one student before adding a payment.',
                            confirmButtonColor: '#dc3545',
                        });
                        return;
                    }

                    Swal.fire({
                        title: 'Confirm Bulk Payment?',
                        html: `You are about to record a payment of <b>₱${amount.toFixed(2)}</b> for <b>${count}</b> student(s).`,
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#06D001',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Yes, confirm all',
                        cancelButtonText: 'Cancel',
                        customClass: {
                            container: 'my-swal-container'
                        }
                    }).then(result => {
                        if (result.isConfirmed) {
                            this.submit();
                        }
                    });
                });
            }
        });
    </script>

    <!-- Receipt Viewing Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Receipt viewing functionality
            document.querySelectorAll('.view-receipt').forEach(btn => {
                btn.addEventListener('click', function() {
                    const receiptUrl = this.dataset.receiptUrl;
                    const studentName = this.dataset.studentName;
                    const parentName = this.dataset.parentName;
                    const amount = this.dataset.amount;

                    // Set modal content
                    document.getElementById('receiptStudentName').textContent = studentName;
                    document.getElementById('receiptParentName').textContent = parentName;
                    document.getElementById('receiptAmount').textContent = amount;
                    document.getElementById('receiptImage').src = receiptUrl;
                    document.getElementById('receiptZoomImage').src = receiptUrl;

                    // Store current receipt URL for download
                    document.getElementById('receiptImage').dataset.downloadUrl = receiptUrl;

                    // Show the modal
                    const receiptModal = new bootstrap.Modal(document.getElementById(
                        'receiptViewModal'));
                    receiptModal.show();
                });
            });

            // Enhanced filtering to include receipt data
            function filterPaymentRequests() {
                const searchTerm = document.getElementById('requestsSearch').value.toLowerCase();
                const statusFilter = document.getElementById('requestsStatusFilter').value;
                const newFilter = document.getElementById('requestsNewFilter').value;
                const rows = document.querySelectorAll('#requestsTable .request-row');

                rows.forEach(row => {
                    const student = row.dataset.student || '';
                    const parent = row.dataset.parent || '';
                    const status = row.dataset.status || '';
                    const isNew = row.dataset.isNew === 'true';
                    const requestedAt = parseInt(row.dataset.requestedAt) * 1000;
                    const isRecent = (Date.now() - requestedAt) < (24 * 60 * 60 * 1000);

                    // Enhanced search to include receipt info
                    const receiptBtn = row.querySelector('.view-receipt');
                    const hasReceipt = receiptBtn ? 'has receipt' : 'no receipt';

                    const matchesSearch = searchTerm === '' ||
                        student.includes(searchTerm) ||
                        parent.includes(searchTerm) ||
                        hasReceipt.includes(searchTerm) ||
                        row.textContent.toLowerCase().includes(searchTerm);

                    const matchesStatus = statusFilter === 'all' || status === statusFilter;
                    const matchesNewFilter = newFilter === 'all' ||
                        (newFilter === 'new' && isNew && isRecent) ||
                        (newFilter === 'pending' && status === 'pending');

                    row.style.display = matchesSearch && matchesStatus && matchesNewFilter ? '' : 'none';
                });
            }

            // Update event listeners to include receipt filtering
            document.getElementById('requestsSearch').addEventListener('input', filterPaymentRequests);
            document.getElementById('requestsStatusFilter').addEventListener('change', filterPaymentRequests);
            document.getElementById('requestsNewFilter').addEventListener('change', filterPaymentRequests);
        });

        // Download receipt function
        function downloadReceipt() {
            const receiptUrl = document.getElementById('receiptImage').dataset.downloadUrl;
            if (receiptUrl) {
                const link = document.createElement('a');
                link.href = receiptUrl;
                link.download = 'payment_receipt_' + new Date().getTime() + '.jpg';
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
            }
        }

        // Initialize when modal opens
        const requestsModal = document.getElementById('paymentRequestsModal');
        if (requestsModal) {
            requestsModal.addEventListener('shown.bs.modal', function() {
                // Re-initialize receipt viewers
                document.querySelectorAll('.view-receipt').forEach(btn => {
                    btn.addEventListener('click', function() {
                        const receiptUrl = this.dataset.receiptUrl;
                        const studentName = this.dataset.studentName;
                        const parentName = this.dataset.parentName;
                        const amount = this.dataset.amount;

                        document.getElementById('receiptStudentName').textContent = studentName;
                        document.getElementById('receiptParentName').textContent = parentName;
                        document.getElementById('receiptAmount').textContent = amount;
                        document.getElementById('receiptImage').src = receiptUrl;
                        document.getElementById('receiptZoomImage').src = receiptUrl;
                        document.getElementById('receiptImage').dataset.downloadUrl = receiptUrl;
                    });
                });
            });
        }
    </script>

    <!-- Enhanced Auto-open Payment Requests Modal Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            function autoOpenPaymentRequestsModal() {
                const shouldOpenRequests = localStorage.getItem('openPaymentRequests');
                const targetPaymentName = localStorage.getItem('targetPaymentName');
                const targetSchoolYear = localStorage.getItem('targetSchoolYear');
                const currentPaymentName = '{{ $paymentName }}';
                const currentSchoolYear = '{{ $selectedYear }}';

                console.log('Auto-open check:', {
                    shouldOpenRequests,
                    targetPaymentName,
                    targetSchoolYear,
                    currentPaymentName,
                    currentSchoolYear
                });

                // Check if we should open payment requests and if we're on the correct page
                if (shouldOpenRequests === 'true' &&
                    targetPaymentName === currentPaymentName &&
                    targetSchoolYear === currentSchoolYear) {

                    console.log('Conditions met, opening payment requests modal...');

                    // Clear the flags
                    localStorage.removeItem('openPaymentRequests');
                    localStorage.removeItem('targetPaymentName');
                    localStorage.removeItem('targetSchoolYear');

                    // Open the payment requests modal
                    setTimeout(() => {
                        const paymentRequestsBtn = document.getElementById('paymentRequestsBtn');
                        if (paymentRequestsBtn) {
                            console.log('Found payment requests button, clicking...');
                            paymentRequestsBtn.click();

                            // Ensure the modal loads with the correct data
                            setTimeout(() => {
                                const modal = document.getElementById('paymentRequestsModal');
                                if (modal) {
                                    console.log('Modal opened, ensuring correct data...');

                                    // The modal should automatically show the correct data
                                    // since the backend query filters by paymentName and school_year

                                    // Force refresh of the requests table if needed
                                    const searchInput = document.getElementById('requestsSearch');
                                    if (searchInput) {
                                        searchInput.value = '';
                                        const event = new Event('input', {
                                            bubbles: true
                                        });
                                        searchInput.dispatchEvent(event);
                                    }
                                }
                            }, 1000);
                        } else {
                            console.error('Payment requests button not found');
                        }
                    }, 1000);
                } else {
                    console.log('Auto-open conditions not met:', {
                        shouldOpenRequests,
                        targetPaymentName,
                        targetSchoolYear,
                        currentPaymentName,
                        currentSchoolYear
                    });
                }
            }

            // Run auto-open check
            autoOpenPaymentRequestsModal();
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

        /* Payment Requests Styles */
        .request-row:hover {
            background-color: #f8f9fa;
        }

        .btn-group-sm>.btn {
            padding: 0.25rem 0.5rem;
            font-size: 0.75rem;
        }

        /* Status badges */
        .badge.bg-warning {
            color: #000 !important;
        }

        /* Modal responsiveness */
        @media (max-width: 768px) {
            #paymentRequestsModal .modal-dialog {
                margin: 0.5rem;
            }

            #requestsTable {
                font-size: 0.875rem;
            }

            .btn-group-sm>.btn {
                padding: 0.2rem 0.4rem;
                font-size: 0.7rem;
            }
        }

        /* Notification badge styles */
        .pulse-badge {
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
                box-shadow: 0 0 0 0 rgba(220, 53, 69, 0.7);
            }

            50% {
                transform: scale(1.05);
                box-shadow: 0 0 0 10px rgba(220, 53, 69, 0);
            }

            100% {
                transform: scale(1);
                box-shadow: 0 0 0 0 rgba(220, 53, 69, 0);
            }
        }

        /* New request row highlighting */
        .new-request {
            background-color: #fff3cd !important;
            border-left: 4px solid #ffc107;
            animation: slideIn 0.5s ease-out;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(-10px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        /* Badge animations */
        .badge.bg-info {
            animation: glow 2s ease-in-out infinite alternate;
        }

        @keyframes glow {
            from {
                box-shadow: 0 0 5px #0dcaf0;
            }

            to {
                box-shadow: 0 0 15px #0dcaf0, 0 0 20px #0dcaf0;
            }
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .pulse-badge {
                animation: none;
                /* Disable animation on mobile for performance */
            }

            .new-request {
                border-left: 3px solid #ffc107;
            }
        }

        /* Receipt View Styles */
        .receipt-image {
            transition: transform 0.3s ease;
            border: 2px solid #e9ecef;
        }

        .receipt-image:hover {
            transform: scale(1.02);
            border-color: #0d6efd;
        }

        .view-receipt {
            transition: all 0.3s ease;
        }

        .view-receipt:hover {
            transform: translateY(-1px);
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        }

        /* Parent photo styling */
        .parent-photo {
            border: 2px solid #dee2e6;
        }

        .parent-photo:hover {
            border-color: #6c757d;
        }

        /* Modal enhancements */
        #receiptViewModal .modal-body,
        #receiptZoomModal .modal-body {
            background-color: #f8f9fa;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .receipt-image {
                max-height: 300px !important;
            }

            #requestsTable td,
            #requestsTable th {
                padding: 0.5rem;
                font-size: 0.875rem;
            }

            .view-receipt {
                padding: 0.25rem 0.5rem;
                font-size: 0.75rem;
            }
        }

        /* Loading state for receipt images */
        .receipt-loading {
            background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
            background-size: 200% 100%;
            animation: loading 1.5s infinite;
        }

        @keyframes loading {
            0% {
                background-position: 200% 0;
            }

            100% {
                background-position: -200% 0;
            }
        }

        /* Badge enhancements for receipt status */
        .badge.bg-secondary {
            font-size: 0.75em;
        }
    </style>
@endpush
