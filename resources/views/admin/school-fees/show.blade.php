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
                <div class="col-md-3">
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

                                <!-- Row Number (FIXED: Simple numbering without extra span) -->
                                <td class="text-center">
                                    {{ $loop->iteration }}
                                </td>

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
                                    @if ($p->latestPaymentMethod())
                                        @php
                                            $method = strtolower($p->latestPaymentMethod());
                                            $imagePath = '';

                                            if ($method === 'gcash') {
                                                $imagePath = asset('assetsDashboard/img/icons/unicons/gcash_logo.png');
                                            } elseif ($method === 'paymaya') {
                                                $imagePath = asset(
                                                    'assetsDashboard/img/icons/unicons/paymaya_logo.png',
                                                );
                                            } elseif ($method === 'cash on hand' || $method === 'cash_on_hand') {
                                                $imagePath = asset('assetsDashboard/img/icons/unicons/coh.png');
                                            } else {
                                                $imagePath = ''; // Default or fallback
                                            }
                                        @endphp

                                        @if ($imagePath)
                                            <img src="{{ $imagePath }}" alt="{{ $p->latestPaymentMethod() }}"
                                                title="{{ $p->latestPaymentMethod() }}"
                                                style="width: 100px; height: auto; object-fit: contain;">
                                        @else
                                            {{ $p->latestPaymentMethod() ?? '—' }}
                                        @endif
                                    @else
                                        —
                                    @endif
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
                                                <i class="bx bx-history me-1"></i>
                                                <span class="d-none d-sm-block">View History</span>
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
                                                            <td class="text-center">
                                                                @php
                                                                    $method = strtolower($history->payment_method_name);
                                                                    $imagePath = '';

                                                                    if ($method === 'gcash') {
                                                                        $imagePath = asset(
                                                                            'assetsDashboard/img/icons/unicons/gcash_logo.png',
                                                                        );
                                                                    } elseif ($method === 'paymaya') {
                                                                        $imagePath = asset(
                                                                            'assetsDashboard/img/icons/unicons/paymaya_logo.png',
                                                                        );
                                                                    } elseif (
                                                                        $method === 'cash on hand' ||
                                                                        $method === 'cash_on_hand'
                                                                    ) {
                                                                        $imagePath = asset(
                                                                            'assetsDashboard/img/icons/unicons/coh.png',
                                                                        );
                                                                    }
                                                                @endphp

                                                                @if ($imagePath)
                                                                    <img src="{{ $imagePath }}"
                                                                        alt="{{ $history->payment_method_name }}"
                                                                        title="{{ $history->payment_method_name }}"
                                                                        style="width: 100px; height: auto; object-fit: contain;">
                                                                @else
                                                                    {{ $history->payment_method_name ?? '—' }}
                                                                @endif
                                                            </td>
                                                            <td>{{ $history->payment_date->format('M d, Y h:i A') }}</td>
                                                            <td>{{ $history->addedBy->full_name ?? '—' }}</td>
                                                            <td>
                                                                <form
                                                                    action="{{ route('admin.payments.history.delete', $history->id) }}"
                                                                    method="POST" class="delete-history-form"
                                                                    data-id="{{ $history->id }}">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="button"
                                                                        class="btn btn-sm btn-danger delete-history-btn">
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
                                                <td class="text-center">
                                                    @php
                                                        $method = strtolower($h['method']);
                                                        $imagePath = '';

                                                        if ($method === 'gcash') {
                                                            $imagePath = asset(
                                                                'assetsDashboard/img/icons/unicons/gcash_logo.png',
                                                            );
                                                        } elseif ($method === 'paymaya') {
                                                            $imagePath = asset(
                                                                'assetsDashboard/img/icons/unicons/paymaya_logo.png',
                                                            );
                                                        } elseif (
                                                            $method === 'cash on hand' ||
                                                            $method === 'cash_on_hand'
                                                        ) {
                                                            $imagePath = asset(
                                                                'assetsDashboard/img/icons/unicons/coh.png',
                                                            );
                                                        }
                                                    @endphp

                                                    @if ($imagePath)
                                                        <img src="{{ $imagePath }}" alt="{{ $h['method'] }}"
                                                            title="{{ $h['method'] }}"
                                                            style="width: 100px; height: auto; object-fit: contain;">
                                                    @else
                                                        {{ $h['method'] ?? '—' }}
                                                    @endif
                                                </td>
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
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <!-- Search, Filter & Table Length - Responsive Layout -->
                            <div class="row g-3 mb-3 align-items-center">
                                <!-- Search Input - Full width on mobile, auto on desktop -->
                                <div class="col-12 col-md-6 col-lg-5">
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="bx bx-search"></i>
                                        </span>
                                        <input type="text" id="requestsSearch" class="form-control"
                                            placeholder="Search student or parent...">
                                    </div>
                                </div>

                                <!-- Controls Group - Aligns horizontally on desktop, stacks on mobile -->
                                <div class="col-12 col-md-6 col-lg-7">
                                    <div class="d-flex flex-column flex-md-row gap-2 justify-content-md-end">
                                        <!-- Table Length Selector -->
                                        <div class="flex-shrink-0" style="min-width: 120px;">
                                            <select id="requestsTableLength" class="form-select">
                                                <option value="5">5 per page</option>
                                                <option value="10" selected>10 per page</option>
                                                <option value="25">25 per page</option>
                                                <option value="50">50 per page</option>
                                                <option value="100">100 per page</option>
                                            </select>
                                        </div>

                                        <!-- Filter Dropdown -->
                                        <div class="flex-shrink-0" style="min-width: 160px;">
                                            <select id="requestsFilter" class="form-select">
                                                <option value="all">All requests</option>
                                                <option value="new">New Requests</option>
                                                <option value="pending">Pending</option>
                                                <option value="approved">Approved</option>
                                                <option value="denied">Denied</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Requests Table -->
                            <div class="table-responsive">
                                <table id="requestsTable" class="table table-hover">
                                    <thead>
                                        <tr class="text-center">
                                            <th style="width: 60px;">#</th>
                                            <th>Student</th>
                                            <th>Parent</th>
                                            <th>Payment Method</th>
                                            <th>Status</th>
                                            <th>View Details</th>
                                            <th>Requested At</th>
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
                                        @endphp

                                        @forelse ($paymentRequests as $index => $request)
                                            @php
                                                $student = $request->payment->classStudent->student ?? null;
                                                $parent = $request->parent ?? null;
                                                $class = $request->payment->classStudent->class ?? null;
                                                $isNew =
                                                    $request->status === 'pending' &&
                                                    $request->requested_at->gt(now()->subDay());

                                                // Format payment method
                                                $methodBadges = [
                                                    'cash_on_hand' => [
                                                        'class' => 'bg-primary',
                                                        'text' => 'Cash on Hand',
                                                    ],
                                                    'gcash' => ['class' => 'bg-info', 'text' => 'GCash'],
                                                    'paymaya' => ['class' => 'bg-success', 'text' => 'PayMaya'],
                                                    'bank_transfer' => [
                                                        'class' => 'bg-secondary',
                                                        'text' => 'Bank Transfer',
                                                    ],
                                                ];

                                                $paymentMethod = $methodBadges[$request->payment_method] ?? [
                                                    'class' => 'bg-secondary',
                                                    'text' => ucwords(str_replace('_', ' ', $request->payment_method)),
                                                ];

                                                // Format status
                                                $statusBadges = [
                                                    'pending' => [
                                                        'class' => 'bg-warning text-dark',
                                                        'text' => 'Pending',
                                                    ],
                                                    'approved' => ['class' => 'bg-success', 'text' => 'Approved'],
                                                    'denied' => ['class' => 'bg-danger', 'text' => 'Denied'],
                                                ];

                                                $status = $statusBadges[$request->status] ?? [
                                                    'class' => 'bg-secondary',
                                                    'text' => ucfirst($request->status),
                                                ];

                                                // Check if this student has a pending payment request
                                                $hasPendingRequest = $request->status === 'pending';
                                            @endphp
                                            <tr class="request-row @if ($isNew) new-request @endif"
                                                data-request-id="{{ $request->id }}"
                                                data-student="{{ $student->full_name ?? 'Unknown' }}"
                                                data-parent="{{ $parent ? $parent->firstName . ' ' . $parent->lastName : 'Unknown' }}"
                                                data-amount="{{ number_format($request->amount_paid, 2) }}"
                                                data-payment-method="{{ $request->payment_method }}"
                                                data-reference-number="{{ $request->reference_number ?? '—' }}"
                                                data-receipt-url="{{ $request->receipt_image ? asset('public/uploads/' . $request->receipt_image) : '' }}"
                                                data-status="{{ $request->status }}"
                                                data-requested-at="{{ \Carbon\Carbon::parse($request->requested_at)->format('M d, Y h:i A') }}"
                                                data-reviewed-at="{{ $request->reviewed_at ? \Carbon\Carbon::parse($request->reviewed_at)->format('M d, Y h:i A') : '—' }}"
                                                data-admin-remarks="{{ $request->admin_remarks ?? 'No remarks' }}"
                                                data-is-new="{{ $isNew ? 'true' : 'false' }}"
                                                data-requested-at-timestamp="{{ $request->requested_at->timestamp }}"
                                                data-has-pending="{{ $hasPendingRequest ? 'true' : 'false' }}">
                                                <!-- Number column with red dot indicator -->
                                                <td class="text-center position-relative">
                                                    <div class="request-number-wrapper"
                                                        style="position: relative; display: inline-block; min-height: 30px;">
                                                        @if ($isNew)
                                                            <div class="small text-danger mt-1">
                                                                New
                                                            </div>
                                                        @endif
                                                        <!-- Row number will be set dynamically by JavaScript -->
                                                        <span class="request-number">{{ $index + 1 }}</span>
                                                        @if ($hasPendingRequest)
                                                            <!-- RED DOT indicator for pending requests -->
                                                            <div class="pending-notification-indicator"
                                                                data-bs-toggle="tooltip" data-bs-placement="top"
                                                                title="Pending payment request">
                                                                <span class="notification-dot"></span>
                                                            </div>
                                                        @endif

                                                    </div>
                                                </td>
                                                <!-- Student column without red dot -->
                                                <td>
                                                    @if ($student)
                                                        <div class="d-flex align-items-center">
                                                            <img src="{{ $student->student_photo ? asset('public/uploads/' . $student->student_photo) : asset('assetsDashboard/img/student_profile_pictures/student_default_profile.jpg') }}"
                                                                class="rounded-circle me-2"
                                                                style="width: 35px; height: 35px; object-fit: cover;">
                                                            <div>
                                                                <div class="fw-semibold">
                                                                    {{ $student->full_name }}
                                                                </div>
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

                                                <!-- Payment Method Column -->
                                                <td class="text-center">
                                                    @php
                                                        $method = strtolower($request->payment_method);
                                                        $imagePath = '';

                                                        if ($method === 'gcash') {
                                                            $imagePath = asset(
                                                                'assetsDashboard/img/icons/unicons/gcash_logo.png',
                                                            );
                                                        } elseif ($method === 'paymaya') {
                                                            $imagePath = asset(
                                                                'assetsDashboard/img/icons/unicons/paymaya_logo.png',
                                                            );
                                                        } elseif (
                                                            $method === 'cash on hand' ||
                                                            $method === 'cash_on_hand'
                                                        ) {
                                                            $imagePath = asset(
                                                                'assetsDashboard/img/icons/unicons/coh.png',
                                                            );
                                                        }
                                                    @endphp

                                                    @if ($imagePath)
                                                        <img src="{{ $imagePath }}"
                                                            alt="{{ $request->payment_method }}"
                                                            title="{{ $request->payment_method }}"
                                                            style="width: 100px; height: auto; object-fit: contain;">
                                                    @else
                                                        {{ ucwords(str_replace('_', ' ', $request->payment_method)) ?? '—' }}
                                                    @endif
                                                </td>

                                                <!-- Status Column -->
                                                <td class="text-center">
                                                    <span class="badge {{ $status['class'] }}">
                                                        {{ $status['text'] }}
                                                    </span>
                                                </td>

                                                <td class="text-center">
                                                    <button type="button"
                                                        class="btn btn-sm btn-outline-primary view-request-details">
                                                        <i class="bx bx-show me-1"></i> <span
                                                            class="d-none d-sm-block">View</span>
                                                    </button>
                                                </td>
                                                <td class="text-center">
                                                    {{ \Carbon\Carbon::parse($request->requested_at)->format('M d, Y h:i A') }}
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center text-muted py-4">
                                                    <i class="bx bx-money fs-1"></i>
                                                    <p class="mt-2 mb-0">No payment requests found</p>
                                                    <small>All payment requests will appear here</small>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <!-- Pagination + Info -->
                            <div class="d-flex justify-content-between align-items-center px-2 py-2 border-top mt-3">
                                <div id="requestsInfo" class="text-muted small">Loading...</div>
                                <nav aria-label="Page navigation">
                                    <ul class="pagination mb-0" id="requestsPagination"></ul>
                                </nav>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>

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

        </div>
        <!-- /Payments Card -->
    </div>
    <!-- /Content wrapper -->

    <!-- Enhanced View Payment Request Modal -->
    <div class="modal fade" id="viewRequestModal" tabindex="-1" aria-labelledby="viewRequestModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewRequestModalLabel">Payment Request Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" data-bs-backdrop="static"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row">
                        <!-- Left Column: Request Details -->
                        <div class="col-md-6">
                            <h6 class="mb-3 text-primary fw-bold">Request Information</h6>

                            <div class="request-details">
                                <div class="mb-3">
                                    <div class="text-muted small mb-1">Student</div>
                                    <div class="fw-semibold" id="requestStudentName">-</div>
                                </div>

                                <div class="mb-3">
                                    <div class="text-muted small mb-1">Parent</div>
                                    <div class="fw-semibold" id="requestParentName">-</div>
                                </div>

                                <div class="mb-3">
                                    <div class="text-muted small mb-1">Amount</div>
                                    <div class="fw-semibold text-success" id="requestAmount">-</div>
                                </div>

                                <div class="mb-3">
                                    <div class="text-muted small mb-1">Payment Method</div>
                                    <div class="fw-semibold" id="requestPaymentMethod">-</div>
                                </div>

                                <div class="mb-3">
                                    <div class="text-muted small mb-1">Reference No.</div>
                                    <div class="fw-semibold" id="requestReferenceNo">-</div>
                                </div>

                                <div class="mb-3">
                                    <div class="text-muted small mb-1">Status</div>
                                    <div class="fw-semibold" id="requestStatus">-</div>
                                </div>

                                <div class="mb-3">
                                    <div class="text-muted small mb-1">Requested At</div>
                                    <div class="fw-semibold" id="requestRequestedAt">-</div>
                                </div>

                                <div class="mb-3">
                                    <div class="text-muted small mb-1">Reviewed At</div>
                                    <div class="fw-semibold" id="requestReviewedAt">-</div>
                                </div>
                            </div>
                        </div>

                        <!-- Right Column: Receipt Preview - UPDATED -->
                        <div class="col-md-6">
                            <h6 class="mb-3 text-primary fw-bold">Payment Receipt</h6>

                            <div
                                class="receipt-preview d-flex flex-column justify-content-center align-items-center h-100">
                                <div id="receiptImageContainer"
                                    class="w-100 h-100 d-flex justify-content-center align-items-center mb-3"
                                    style="min-height: 300px;">
                                    <!-- Centered and auto-enlarged receipt image -->
                                    <img id="requestReceiptImage" src="" alt="Payment Receipt"
                                        class="img-fluid receipt-image"
                                        style="max-height: 100%; max-width: 100%; object-fit: contain;">
                                </div>

                                <div class="mt-2" id="noReceiptMessage" style="display: none;">
                                    <div class="text-center py-4">
                                        <i class="bx bx-receipt text-muted" style="font-size: 3rem;"></i>
                                        <p class="text-muted mt-2 mb-0">No receipt uploaded</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Admin Actions (only for pending requests) -->
                            <div class="mt-1" id="requestActionsSection">
                                <div class="d-flex gap-2">
                                    <button type="button" class="btn btn-success btn-sm flex-fill"
                                        id="approveRequestBtn">
                                        <i class="bx bx-check me-1"></i> Approve Request
                                    </button>
                                    <button type="button" class="btn btn-danger btn-sm flex-fill" id="denyRequestBtn">
                                        <i class="bx bx-x me-1"></i> Deny Request
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Admin Remarks (for reviewed requests) -->
                    <div class="row mt-3" id="adminRemarksSection" style="display: none;">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header bg-light">
                                    <strong class="text-primary">Admin Remarks</strong>
                                </div>
                                <div class="card-body">
                                    <p id="adminRemarksText" class="mb-0">-</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Receipt Preview Modal (can be removed if no longer needed, but keeping for compatibility) -->
    <div class="modal fade" id="receiptPreviewModal" tabindex="-1" aria-labelledby="receiptPreviewModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="receiptPreviewModalLabel">Receipt Preview</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center">
                        <img id="fullReceiptImage" src="" alt="Receipt Preview"
                            class="img-fluid rounded shadow receipt-zoom-image"
                            style="max-height: 70vh; width: auto; object-fit: contain;">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Denial Remarks Modal -->
    <div class="modal fade" id="denialRemarksModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Reason for Denial</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
                        // FIXED: Simple number assignment without extra span
                        const rowNumberCell = r.querySelector("td:nth-child(2)");
                        if (rowNumberCell) {
                            rowNumberCell.textContent = start + i + 1;
                        }
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

    <!-- Enhanced Payment Requests Script with Filtering, Search, Pagination, and Red Dot Indicators -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // DOM elements
            const requestsSearch = document.getElementById('requestsSearch');
            const requestsFilter = document.getElementById('requestsFilter');
            const requestsTable = document.getElementById('requestsTable');
            const requestsTbody = requestsTable.querySelector('tbody');
            const pagination = document.getElementById('requestsPagination');
            const tableInfo = document.getElementById('requestsInfo');

            // Get all rows from the table
            const rows = Array.from(requestsTbody.querySelectorAll('.request-row'));

            // Pagination variables
            let currentPage = 1;
            let rowsPerPage = 10;
            let filteredRows = [...rows];

            // Initialize
            initPaymentRequestsTable();

            function initPaymentRequestsTable() {
                // Store original data on each row
                rows.forEach((row, index) => {
                    // Store the original row number
                    const numberWrapper = row.querySelector('.request-number-wrapper');
                    if (numberWrapper) {
                        const numberSpan = numberWrapper.querySelector('.request-number');
                        if (numberSpan) {
                            numberSpan.textContent = index + 1;
                        }
                    }
                });

                // Event listeners
                requestsSearch.addEventListener('input', debounce(filterAndPaginate, 300));
                requestsFilter.addEventListener('change', filterAndPaginate);

                // Table length selector
                const requestsTableLength = document.getElementById('requestsTableLength');
                if (requestsTableLength) {
                    requestsTableLength.addEventListener('change', function() {
                        rowsPerPage = parseInt(this.value);
                        currentPage = 1;
                        renderTable();
                    });
                }

                // Initial render
                filterAndPaginate();

                // Initialize Bootstrap tooltips for red dots
                if (typeof bootstrap !== 'undefined') {
                    const tooltipTriggerList = [].slice.call(document.querySelectorAll(
                        '[data-bs-toggle="tooltip"]'));
                    tooltipTriggerList.map(function(tooltipTriggerEl) {
                        return new bootstrap.Tooltip(tooltipTriggerEl);
                    });
                }
            }

            function filterRows() {
                const searchTerm = requestsSearch.value.trim().toLowerCase();
                const filterValue = requestsFilter.value;

                filteredRows = rows.filter(row => {
                    // Get row data
                    const student = row.dataset.student || '';
                    const parent = row.dataset.parent || '';
                    const status = row.dataset.status || '';
                    const paymentMethod = row.dataset.paymentMethod || '';
                    const amount = row.dataset.amount || '';
                    const requestedAt = row.dataset.requestedAt || '';
                    const isNew = row.dataset.isNew === 'true';
                    const hasPending = row.dataset.hasPending === 'true';

                    // Combine searchable text
                    const searchableText = (student + ' ' + parent + ' ' +
                        paymentMethod + ' ' + amount + ' ' +
                        requestedAt).toLowerCase();

                    // Apply search filter
                    const matchesSearch = !searchTerm || searchableText.includes(searchTerm);

                    // Apply status filter
                    let matchesFilter = true;
                    if (filterValue !== 'all') {
                        if (filterValue === 'new') {
                            const requestedAtTimestamp = parseInt(row.dataset.requestedAtTimestamp || 0) *
                                1000;
                            const isRecent = isRequestRecent(requestedAtTimestamp);
                            matchesFilter = isNew && isRecent && status === 'pending';
                        } else {
                            matchesFilter = status === filterValue;
                        }
                    }

                    return matchesSearch && matchesFilter;
                });
            }

            function isRequestRecent(timestamp) {
                if (!timestamp) return false;
                const requestedDate = new Date(timestamp);
                const now = new Date();
                const hoursDifference = (now - requestedDate) / (1000 * 60 * 60);
                return hoursDifference <= 24; // Within 24 hours
            }

            function renderTable() {
                // Clear table
                requestsTbody.innerHTML = '';

                // Calculate pagination
                const totalRows = filteredRows.length;
                const totalPages = Math.max(1, Math.ceil(totalRows / rowsPerPage));
                currentPage = Math.min(Math.max(1, currentPage), totalPages);

                const startIndex = (currentPage - 1) * rowsPerPage;
                const endIndex = Math.min(startIndex + rowsPerPage, totalRows);
                const pageRows = filteredRows.slice(startIndex, endIndex);

                // Add rows to table
                if (pageRows.length > 0) {
                    pageRows.forEach((row, index) => {
                        const newRow = row.cloneNode(true);

                        // Update row number
                        const numberWrapper = newRow.querySelector('.request-number-wrapper');
                        if (numberWrapper) {
                            const numberSpan = numberWrapper.querySelector('.request-number');
                            if (numberSpan) {
                                numberSpan.textContent = startIndex + index + 1;
                            }
                        }

                        // Reattach event listeners for view button
                        const viewBtn = newRow.querySelector('.view-request-details');
                        if (viewBtn) {
                            viewBtn.addEventListener('click', function() {
                                const row = this.closest('.request-row');
                                showRequestDetails(row);
                            });
                        }

                        // Reinitialize Bootstrap tooltip for red dot
                        const redDotIndicator = newRow.querySelector('.pending-notification-indicator');
                        if (redDotIndicator && typeof bootstrap !== 'undefined') {
                            new bootstrap.Tooltip(redDotIndicator);
                        }

                        requestsTbody.appendChild(newRow);
                    });
                } else {
                    // Show no results message
                    const noResultsRow = document.createElement('tr');
                    noResultsRow.innerHTML = `
                    <td colspan="7" class="text-center text-muted py-4">
                        <i class="bx bx-search fs-1"></i>
                        <p class="mt-2 mb-0">No payment requests found</p>
                        <small>Try adjusting your search or filter criteria</small>
                    </td>
                `;
                    requestsTbody.appendChild(noResultsRow);
                }

                // Update table info
                tableInfo.textContent = totalRows > 0 ?
                    `Showing ${startIndex + 1} to ${endIndex} of ${totalRows} requests` :
                    'Showing 0 to 0 of 0 requests';

                // Render pagination
                renderPagination(totalPages);
            }

            function renderPagination(totalPages) {
                pagination.innerHTML = '';

                if (totalPages <= 1) return;

                // Previous button
                const prevLi = document.createElement('li');
                prevLi.className = `page-item ${currentPage === 1 ? 'disabled' : ''}`;
                prevLi.innerHTML = `<a class="page-link" href="#" aria-label="Previous">&laquo;</a>`;
                prevLi.addEventListener('click', (e) => {
                    e.preventDefault();
                    if (currentPage > 1) {
                        currentPage--;
                        renderTable();
                    }
                });
                pagination.appendChild(prevLi);

                // Page numbers
                const maxVisible = 5;
                let startPage = Math.max(1, currentPage - Math.floor(maxVisible / 2));
                let endPage = Math.min(totalPages, startPage + maxVisible - 1);

                if (endPage - startPage + 1 < maxVisible) {
                    startPage = Math.max(1, endPage - maxVisible + 1);
                }

                for (let i = startPage; i <= endPage; i++) {
                    const pageLi = document.createElement('li');
                    pageLi.className = `page-item ${i === currentPage ? 'active' : ''}`;
                    pageLi.innerHTML = `<a class="page-link" href="#">${i}</a>`;
                    pageLi.addEventListener('click', (e) => {
                        e.preventDefault();
                        currentPage = i;
                        renderTable();
                    });
                    pagination.appendChild(pageLi);
                }

                // Next button
                const nextLi = document.createElement('li');
                nextLi.className = `page-item ${currentPage === totalPages ? 'disabled' : ''}`;
                nextLi.innerHTML = `<a class="page-link" href="#" aria-label="Next">&raquo;</a>`;
                nextLi.addEventListener('click', (e) => {
                    e.preventDefault();
                    if (currentPage < totalPages) {
                        currentPage++;
                        renderTable();
                    }
                });
                pagination.appendChild(nextLi);
            }

            function filterAndPaginate() {
                filterRows();
                currentPage = 1; // Reset to first page on filter/search
                renderTable();
            }

            function debounce(func, wait) {
                let timeout;
                return function executedFunction(...args) {
                    const later = () => {
                        clearTimeout(timeout);
                        func(...args);
                    };
                    clearTimeout(timeout);
                    timeout = setTimeout(later, wait);
                };
            }

            // View request details function
            function showRequestDetails(row) {
                // Your existing view request details logic here
                const requestId = row.dataset.requestId;
                const studentName = row.dataset.student;
                const parentName = row.dataset.parent;
                const amount = row.dataset.amount;
                const paymentMethod = row.dataset.paymentMethod;
                const referenceNumber = row.dataset.referenceNumber;
                const receiptUrl = row.dataset.receiptUrl;
                const status = row.dataset.status;
                const requestedAt = row.dataset.requestedAt;
                const reviewedAt = row.dataset.reviewedAt;
                const adminRemarks = row.dataset.adminRemarks;

                // Set modal content
                document.getElementById('requestStudentName').textContent = studentName;
                document.getElementById('requestParentName').textContent = parentName;
                document.getElementById('requestAmount').textContent = '₱' + amount;
                document.getElementById('requestPaymentMethod').innerHTML = formatPaymentMethodImage(paymentMethod);
                document.getElementById('requestReferenceNo').textContent = referenceNumber;
                document.getElementById('requestStatus').innerHTML = formatStatusBadge(status);
                document.getElementById('requestRequestedAt').textContent = requestedAt;
                document.getElementById('requestReviewedAt').textContent = reviewedAt;
                document.getElementById('adminRemarksText').textContent = adminRemarks;

                // Handle receipt image
                const receiptImage = document.getElementById('requestReceiptImage');
                const receiptImageContainer = document.getElementById('receiptImageContainer');
                const noReceiptMessage = document.getElementById('noReceiptMessage');

                if (receiptUrl) {
                    // Use the actual image path from storage
                    const fullReceiptUrl = receiptUrl.startsWith('http') ? receiptUrl :
                        '{{ asset('') }}' + receiptUrl;

                    receiptImage.src = fullReceiptUrl;
                    receiptImage.style.display = 'block';
                    noReceiptMessage.style.display = 'none';
                    receiptImageContainer.style.display = 'flex'; // Enable flex for centering

                    // Remove click functionality (no more modal preview)
                    receiptImage.style.cursor = 'default';
                    receiptImage.removeAttribute('data-bs-toggle');
                    receiptImage.removeAttribute('data-bs-target');

                    // Apply auto-sizing
                    applyAutoEnlargedReceipt(receiptImage, receiptImageContainer);
                } else {
                    receiptImage.style.display = 'none';
                    noReceiptMessage.style.display = 'block';
                    receiptImageContainer.style.display = 'flex'; // Still flex for centering

                    // Remove click functionality if no receipt
                    receiptImage.style.cursor = 'default';
                    receiptImage.removeAttribute('data-bs-toggle');
                    receiptImage.removeAttribute('data-bs-target');
                }

                function applyAutoEnlargedReceipt(imageElement, containerElement) {
                    // Reset styles
                    imageElement.style.maxHeight = '';
                    imageElement.style.maxWidth = '';
                    imageElement.style.width = 'auto';
                    imageElement.style.height = 'auto';

                    // Get container dimensions
                    const containerWidth = containerElement.clientWidth;
                    const containerHeight = containerElement.clientHeight;

                    // Let the image auto-enlarge based on its natural size
                    // while respecting container boundaries
                    imageElement.style.maxHeight = '100%';
                    imageElement.style.maxWidth = '100%';
                    imageElement.style.objectFit = 'contain';

                    // Center the image
                    imageElement.style.margin = 'auto';
                    containerElement.style.display = 'flex';
                    containerElement.style.justifyContent = 'center';
                    containerElement.style.alignItems = 'center';
                }

                // Handle actions based on status
                const actionsSection = document.getElementById('requestActionsSection');
                const remarksSection = document.getElementById('adminRemarksSection');

                if (status === 'pending') {
                    actionsSection.style.display = 'block';
                    remarksSection.style.display = 'none';

                    document.getElementById('approveRequestBtn').onclick = function() {
                        handleRequestAction(requestId, 'approve');
                    };

                    document.getElementById('denyRequestBtn').onclick = function() {
                        handleRequestAction(requestId, 'deny');
                    };
                } else {
                    actionsSection.style.display = 'none';
                    remarksSection.style.display = 'block';
                }

                // Show the modal
                const viewModal = new bootstrap.Modal(document.getElementById('viewRequestModal'));
                viewModal.show();
            }

            // Helper functions
            function formatPaymentMethodImage(method) {
                const methodLower = method.toLowerCase();
                let imagePath = '';
                let altText = '';

                if (methodLower === 'gcash') {
                    imagePath = '{{ asset('assetsDashboard/img/icons/unicons/gcash_logo.png') }}';
                    altText = 'GCash';
                    return `<img src="${imagePath}" alt="${altText}" title="${altText}"
                     style="width: 100px; height: auto; object-fit: contain;"
                     class="payment-method-image">`;
                } else if (methodLower === 'paymaya') {
                    imagePath = '{{ asset('assetsDashboard/img/icons/unicons/paymaya_logo.png') }}';
                    altText = 'PayMaya';
                    return `<img src="${imagePath}" alt="${altText}" title="${altText}"
                     style="width: 100px; height: auto; object-fit: contain;"
                     class="payment-method-image">`;
                } else if (methodLower === 'cash_on_hand' || methodLower === 'cash on hand') {
                    imagePath = '{{ asset('assetsDashboard/img/icons/unicons/coh.png') }}';
                    altText = 'Cash on Hand';
                    return `<img src="${imagePath}" alt="${altText}" title="${altText}"
                     style="width: 100px; height: auto; object-fit: contain;"
                     class="payment-method-image">`;
                } else if (methodLower === 'bank_transfer' || methodLower === 'bank transfer') {
                    altText = 'Bank Transfer';
                    return `<span class="badge bg-secondary">${ucwords(method.replace(/_/g, ' '))}</span>`;
                } else {
                    return `<span class="badge bg-secondary">${ucwords(method.replace(/_/g, ' '))}</span>`;
                }
            }

            function formatStatusBadge(status) {
                const badges = {
                    'pending': '<span class="badge bg-warning text-dark">Pending</span>',
                    'approved': '<span class="badge bg-success">Approved</span>',
                    'denied': '<span class="badge bg-danger">Denied</span>'
                };
                return badges[status] || `<span class="badge bg-secondary">${ucfirst(status)}</span>`;
            }

            function ucfirst(str) {
                return str.charAt(0).toUpperCase() + str.slice(1);
            }

            function ucwords(str) {
                return str.replace(/\b\w/g, function(char) {
                    return char.toUpperCase();
                }).replace(/_/g, ' ');
            }

            // Handle approve/deny actions
            function handleRequestAction(requestId, action) {
                const actionText = action === 'approve' ? 'approve' : 'deny';
                const actionColor = action === 'approve' ? '#06D001' : '#d33';

                if (action === 'approve') {
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
                    const denialModal = new bootstrap.Modal(document.getElementById('denialRemarksModal'));
                    const remarksTextarea = document.getElementById('denialRemarksTextarea');
                    const confirmDenialBtn = document.getElementById('confirmDenialBtn');

                    remarksTextarea.value = '';
                    remarksTextarea.classList.remove('is-invalid');

                    const newConfirmBtn = confirmDenialBtn.cloneNode(true);
                    confirmDenialBtn.parentNode.replaceChild(newConfirmBtn, confirmDenialBtn);

                    newConfirmBtn.addEventListener('click', function() {
                        const remarks = remarksTextarea.value.trim();

                        if (!remarks) {
                            remarksTextarea.classList.add('is-invalid');
                            return;
                        }

                        denialModal.hide();
                        const viewModal = bootstrap.Modal.getInstance(document.getElementById(
                            'viewRequestModal'));
                        if (viewModal) viewModal.hide();

                        submitRequestAction(requestId, action, remarks);
                    });

                    denialModal.show();
                }
            }

            function submitRequestAction(requestId, action, remarks = '') {
                Swal.fire({
                    title: 'Processing...',
                    text: 'Please wait while we process your request',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                const formData = new FormData();
                formData.append('_token', '{{ csrf_token() }}');
                formData.append('_method', 'POST');

                if (remarks) {
                    formData.append('admin_remarks', remarks);
                }

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
                        Swal.close();
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
                                location.reload();
                            });
                        } else {
                            throw new Error(data.message || 'Action failed');
                        }
                    })
                    .catch(error => {
                        Swal.close();
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
                    });
            }

            // Initialize view buttons on original rows
            document.querySelectorAll('.view-request-details').forEach(btn => {
                btn.addEventListener('click', function() {
                    const row = this.closest('.request-row');
                    showRequestDetails(row);
                });
            });

            // Initialize when modal opens
            const requestsModal = document.getElementById('paymentRequestsModal');
            if (requestsModal) {
                requestsModal.addEventListener('shown.bs.modal', function() {
                    // Re-initialize if needed
                    initPaymentRequestsTable();

                    // Initialize Bootstrap tooltips
                    if (typeof bootstrap !== 'undefined') {
                        const tooltipTriggerList = [].slice.call(document.querySelectorAll(
                            '[data-bs-toggle="tooltip"]'));
                        tooltipTriggerList.map(function(tooltipTriggerEl) {
                            return new bootstrap.Tooltip(tooltipTriggerEl);
                        });
                    }
                });
            }
        });
    </script>

    <!-- Payment Requests Real-time Notification System -->
    <script>
        // Payment Requests Real-time Notification System
        document.addEventListener('DOMContentLoaded', function() {
            // Pass PHP variables to JavaScript
            const currentPaymentName = '{{ $paymentName }}';
            const currentSchoolYear = '{{ $selectedYear }}';

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
                // Include payment name and school year in the request
                fetch(`{{ route('admin.payment-requests.check-new') }}?payment_name=${encodeURIComponent(currentPaymentName)}&school_year=${encodeURIComponent(currentSchoolYear)}`, {
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
                        body: `You have ${count} new payment request${count > 1 ? 's' : ''} pending review for ${currentPaymentName}`,
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
                const filterValue = document.getElementById('requestsFilter').value;
                const rows = document.querySelectorAll('#requestsTable .request-row');

                rows.forEach(row => {
                    const student = row.dataset.student || '';
                    const parent = row.dataset.parent || '';
                    const status = row.dataset.status || '';
                    const isNew = row.dataset.isNew === 'true';
                    const requestedAt = parseInt(row.dataset.requestedAtTimestamp || 0) * 1000;
                    const isRecent = (Date.now() - requestedAt) < (24 * 60 * 60 * 1000); // Within 24 hours

                    const matchesSearch = student.includes(searchTerm) || parent.includes(searchTerm);

                    // Determine matches based on the single filter
                    let matchesFilter = true;
                    switch (filterValue) {
                        case 'all':
                            matchesFilter = true;
                            break;
                        case 'new':
                            matchesFilter = isNew && isRecent && status === 'pending';
                            break;
                        case 'pending':
                            matchesFilter = status === 'pending';
                            break;
                        case 'approved':
                            matchesFilter = status === 'approved';
                            break;
                        case 'denied':
                            matchesFilter = status === 'denied';
                            break;
                        default:
                            matchesFilter = true;
                    }

                    row.style.display = matchesSearch && matchesFilter ? '' : 'none';
                });
            }

            // Update event listeners for the single filter
            document.getElementById('requestsSearch').addEventListener('input', filterPaymentRequests);
            document.getElementById('requestsFilter').addEventListener('change', filterPaymentRequests);

            // Event listeners for enhanced filtering
            document.getElementById('requestsSearch').addEventListener('input', filterPaymentRequests);

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

        // Helper functions for formatting
        function ucfirst(str) {
            return str.charAt(0).toUpperCase() + str.slice(1);
        }

        function ucwords(str) {
            return str.replace(/\b\w/g, function(char) {
                return char.toUpperCase();
            }).replace(/_/g, ' ');
        }
    </script>

    <!-- View Payment Request Modal Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // View request details
            document.querySelectorAll('.view-request-details').forEach(btn => {
                btn.addEventListener('click', function() {
                    const row = this.closest('.request-row');
                    const requestId = row.dataset.requestId;
                    const studentName = row.dataset.student;
                    const parentName = row.dataset.parent;
                    const amount = row.dataset.amount;
                    const paymentMethod = row.dataset.paymentMethod;
                    const referenceNumber = row.dataset.referenceNumber;
                    const receiptUrl = row.dataset.receiptUrl;
                    const status = row.dataset.status;
                    const requestedAt = row.dataset.requestedAt;
                    const reviewedAt = row.dataset.reviewedAt;
                    const adminRemarks = row.dataset.adminRemarks;

                    // Set modal content
                    document.getElementById('requestStudentName').textContent = studentName;
                    document.getElementById('requestParentName').textContent = parentName;
                    document.getElementById('requestAmount').textContent = '₱' + amount;

                    // UPDATED: Display image for GCASH and PAYMAYA in Payment Method
                    document.getElementById('requestPaymentMethod').innerHTML =
                        formatPaymentMethodImage(paymentMethod);

                    document.getElementById('requestReferenceNo').textContent = referenceNumber;
                    document.getElementById('requestStatus').innerHTML = formatStatusBadge(status);
                    document.getElementById('requestRequestedAt').textContent = requestedAt;
                    document.getElementById('requestReviewedAt').textContent = reviewedAt;
                    document.getElementById('adminRemarksText').textContent = adminRemarks;

                    // Handle receipt image with responsive sizing
                    const receiptImage = document.getElementById('requestReceiptImage');
                    const fullReceiptImage = document.getElementById('fullReceiptImage');
                    const noReceiptMessage = document.getElementById('noReceiptMessage');
                    const receiptImageContainer = document.getElementById('receiptImageContainer');

                    if (receiptUrl) {
                        // Use the actual image path from storage
                        const fullReceiptUrl = receiptUrl.startsWith('http') ? receiptUrl :
                            '{{ asset('') }}' + receiptUrl;

                        receiptImage.src = fullReceiptUrl;
                        fullReceiptImage.src = fullReceiptUrl;
                        receiptImage.style.display = 'block';
                        noReceiptMessage.style.display = 'none';

                        // Apply responsive sizing
                        applyResponsiveReceiptSizing(receiptImage);

                        // Make image clickable
                        receiptImage.style.cursor = 'pointer';
                        receiptImage.dataset.bsToggle = 'modal';
                        receiptImage.dataset.bsTarget = '#receiptPreviewModal';
                    } else {
                        receiptImage.style.display = 'none';
                        noReceiptMessage.style.display = 'block';

                        // Remove click functionality if no receipt
                        receiptImage.style.cursor = 'default';
                        receiptImage.removeAttribute('data-bs-toggle');
                        receiptImage.removeAttribute('data-bs-target');
                    }

                    // Handle actions based on status
                    const actionsSection = document.getElementById('requestActionsSection');
                    const remarksSection = document.getElementById('adminRemarksSection');

                    if (status === 'pending') {
                        actionsSection.style.display = 'block';
                        remarksSection.style.display = 'none';

                        // Set up action buttons
                        document.getElementById('approveRequestBtn').onclick = function() {
                            handleRequestAction(requestId, 'approve');
                        };

                        document.getElementById('denyRequestBtn').onclick = function() {
                            handleRequestAction(requestId, 'deny');
                        };
                    } else {
                        actionsSection.style.display = 'none';
                        remarksSection.style.display = 'block';
                    }

                    // Show the modal
                    const viewModal = new bootstrap.Modal(document.getElementById(
                        'viewRequestModal'));
                    viewModal.show();
                });
            });

            // NEW: Function to display images for GCASH and PAYMAYA
            function formatPaymentMethodImage(method) {
                const methodLower = method.toLowerCase();
                let imagePath = '';
                let altText = '';
                let titleText = '';

                if (methodLower === 'gcash') {
                    imagePath = '{{ asset('assetsDashboard/img/icons/unicons/gcash_logo.png') }}';
                    altText = 'GCash';
                    titleText = 'GCash';
                    return `<img src="${imagePath}" alt="${altText}" title="${titleText}"
                         style="width: 100px; height: auto; object-fit: contain;"
                         class="payment-method-image">`;
                } else if (methodLower === 'paymaya') {
                    imagePath = '{{ asset('assetsDashboard/img/icons/unicons/paymaya_logo.png') }}';
                    altText = 'PayMaya';
                    titleText = 'PayMaya';
                    return `<img src="${imagePath}" alt="${altText}" title="${titleText}"
                         style="width: 100px; height: auto; object-fit: contain;"
                         class="payment-method-image">`;
                } else if (methodLower === 'cash_on_hand' || methodLower === 'cash on hand') {
                    imagePath = '{{ asset('assetsDashboard/img/icons/unicons/coh.png') }}';
                    altText = 'Cash on Hand';
                    titleText = 'Cash on Hand';
                    return `<img src="${imagePath}" alt="${altText}" title="${titleText}"
                         style="width: 100px; height: auto; object-fit: contain;"
                         class="payment-method-image">`;
                } else if (methodLower === 'bank_transfer' || methodLower === 'bank transfer') {
                    altText = 'Bank Transfer';
                    titleText = 'Bank Transfer';
                    return `<span class="badge bg-secondary">${ucwords(method.replace(/_/g, ' '))}</span>`;
                } else {
                    return `<span class="badge bg-secondary">${ucwords(method.replace(/_/g, ' '))}</span>`;
                }
            }

            // Function to apply responsive receipt sizing
            function applyResponsiveReceiptSizing(imageElement) {
                // Remove any existing inline styles that might interfere
                imageElement.style.maxHeight = '';
                imageElement.style.maxWidth = '';

                // Set responsive sizing
                if (window.innerWidth < 768) { // Mobile
                    imageElement.style.maxHeight = '200px';
                    imageElement.style.width = 'auto';
                } else { // Desktop
                    imageElement.style.maxHeight = '250px';
                    imageElement.style.width = 'auto';
                }

                // Ensure proper object-fit
                imageElement.style.objectFit = 'contain';
            }

            // Format status badge
            function formatStatusBadge(status) {
                const badges = {
                    'pending': '<span class="badge bg-warning text-dark">Pending</span>',
                    'approved': '<span class="badge bg-success">Approved</span>',
                    'denied': '<span class="badge bg-danger">Denied</span>'
                };
                return badges[status] || `<span class="badge bg-secondary">${ucfirst(status)}</span>`;
            }

            // Helper functions
            function ucfirst(str) {
                return str.charAt(0).toUpperCase() + str.slice(1);
            }

            function ucwords(str) {
                return str.replace(/\b\w/g, function(char) {
                    return char.toUpperCase();
                }).replace(/_/g, ' ');
            }

            // Handle approve/deny actions
            function handleRequestAction(requestId, action) {
                const actionText = action === 'approve' ? 'approve' : 'deny';
                const actionColor = action === 'approve' ? '#06D001' : '#d33';

                if (action === 'approve') {
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
                    // Use the existing denial remarks modal
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

                        // Close the view modal too
                        const viewModal = bootstrap.Modal.getInstance(document.getElementById(
                            'viewRequestModal'));
                        if (viewModal) viewModal.hide();

                        // Submit the denial
                        submitRequestAction(requestId, action, remarks);
                    });

                    // Show the modal
                    denialModal.show();
                }
            }

            // Submit request action
            function submitRequestAction(requestId, action, remarks = '') {
                // Show loading state
                Swal.fire({
                    title: 'Processing...',
                    text: 'Please wait while we process your request',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
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
                        Swal.close();
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
                        Swal.close();
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
                    });
            }

            // Apply responsive sizing on window resize
            window.addEventListener('resize', function() {
                const receiptImage = document.getElementById('requestReceiptImage');
                const receiptImageContainer = document.getElementById('receiptImageContainer');

                if (receiptImage && receiptImage.style.display !== 'none' && receiptImageContainer) {
                    applyAutoEnlargedReceipt(receiptImage, receiptImageContainer);
                }
            });
        });
    </script>

    <!-- Payment History Delete with SweetAlert -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Handle payment history delete buttons
            document.addEventListener('click', function(e) {
                if (e.target.classList.contains('delete-history-btn') ||
                    e.target.closest('.delete-history-btn')) {
                    e.preventDefault();

                    const button = e.target.classList.contains('delete-history-btn') ?
                        e.target :
                        e.target.closest('.delete-history-btn');
                    const form = button.closest('.delete-history-form');
                    const historyId = form ? form.dataset.id : '';

                    Swal.fire({
                        title: 'Are you sure?',
                        text: "You are about to delete this payment transaction. This action cannot be undone!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Yes, delete it!',
                        cancelButtonText: 'Cancel',
                        customClass: {
                            container: 'my-swal-container'
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Show loading state
                            Swal.fire({
                                title: 'Deleting...',
                                text: 'Please wait while we delete the transaction',
                                allowOutsideClick: false,
                                didOpen: () => {
                                    Swal.showLoading();
                                }
                            });

                            // Submit the form
                            form.submit();
                        }
                    });
                }
            });

            // Handle form submission to prevent default behavior
            document.querySelectorAll('.delete-history-form').forEach(form => {
                form.addEventListener('submit', function(e) {
                    // If we're here, it means the form was submitted programmatically
                    // after SweetAlert confirmation, so we don't prevent default
                    return true;
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

    <!-- Vendor CSS Files -->
    <link href="{{ asset('assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet" />

    <style>
        /* View Request Modal Styles */
        #viewRequestModal .modal-body {
            max-height: 70vh;
            overflow-y: auto;
        }

        #requestStatus .badge {
            font-size: 0.85em;
            padding: 0.4em 0.8em;
        }

        .receipt-image {
            border: 2px solid #e9ecef;
            border-radius: 8px;
            transition: all 0.3s ease;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .receipt-image:hover {
            transform: scale(1.02);
            border-color: #0d6efd;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        .receipt-zoom-image {
            border: 2px solid #e9ecef;
            border-radius: 8px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
        }

        #requestActionsSection .btn {
            min-width: 150px;
            padding: 0.5rem 1.5rem;
        }

        #noReceiptMessage {
            padding: 2rem;
            background-color: #f8f9fa;
            border-radius: 8px;
            border: 2px dashed #dee2e6;
        }

        /* Payment Method Image Styles */
        .payment-method-image {
            transition: transform 0.2s ease;
            vertical-align: middle;
        }

        .payment-method-image:hover {
            transform: scale(1.05);
        }

        /* RED DOT NOTIFICATION STYLES FOR PAYMENT REQUESTS MODAL */
        .pending-notification-indicator {
            position: absolute;
            top: -3px;
            left: -10px;
            z-index: 2;
            width: 10px;
            height: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .notification-dot {
            width: 8px;
            height: 8px;
            background-color: #ff0000;
            border-radius: 50%;
            animation: pulse-animation 1.5s infinite;
            box-shadow: 0 0 0 0 rgba(255, 11, 11, 0.7);
            cursor: pointer;
        }

        .notification-dot:hover {
            transform: scale(1.2);
            background-color: #f61212;
        }

        @keyframes pulse-animation {
            0% {
                box-shadow: 0 0 0 0 rgba(255, 0, 0, 0.7);
            }

            70% {
                box-shadow: 0 0 0 5px rgba(255, 107, 107, 0);
            }

            100% {
                box-shadow: 0 0 0 0 rgba(255, 107, 107, 0);
            }
        }

        /* Position row numbers properly in payment requests modal */
        #requestsTable td.text-center.position-relative>div {
            position: relative;
            display: inline-block;
            padding-left: 8px;
            /* Space for the dot on the left */
            min-height: 20px;
        }

        /* Main payment table numbering - simple styling */
        #paymentTable td.text-center {
            padding: 8px 4px;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .pending-notification-indicator {
                top: -2px;
                left: -8px;
            }

            .notification-dot {
                width: 6px;
                height: 6px;
            }

            #requestsTable td.text-center.position-relative>div {
                padding-left: 6px;
            }
        }

        /* Responsive adjustments for main modal */
        @media (max-width: 768px) {
            #viewRequestModal .modal-dialog {
                margin: 0.5rem;
            }

            #viewRequestModal .receipt-image {
                max-height: 200px !important;
            }

            #requestActionsSection .btn {
                min-width: auto;
                padding: 0.4rem 0.75rem;
                font-size: 0.9rem;
            }

            .payment-method-image {
                max-width: 80px;
            }

            #receiptPreviewModal .modal-dialog {
                margin: 0.5rem;
            }

            #receiptPreviewModal .receipt-zoom-image {
                max-height: 50vh;
            }
        }

        /* Tablet adjustments */
        @media (min-width: 769px) and (max-width: 1024px) {
            #viewRequestModal .receipt-image {
                max-height: 220px !important;
            }
        }

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
                box-shadow: 0 0 0 0 rgba(255, 0, 25, 0.7);
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

        /* Enhanced View Request Modal Styles */
        #viewRequestModal .modal-body {
            max-height: 80vh;
            overflow-y: auto;
        }

        #viewRequestModal .request-details {
            background: white;
            padding: 1.25rem;
            border-radius: 8px;
            border: 1px solid #e9ecef;
        }

        #viewRequestModal .receipt-preview {
            background: white;
            padding: 1.25rem;
            border-radius: 8px;
            border: 1px solid #e9ecef;
            min-height: 350px;
        }

        #viewRequestModal .clickable-image {
            cursor: pointer !important;
        }

        #viewRequestModal .clickable-image:hover {
            transform: scale(1.02);
            border-color: #0d6efd;
        }

        #receiptPreviewModal .modal-dialog {
            max-width: 90%;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            #viewRequestModal .modal-dialog {
                margin: 0.5rem;
            }

            #viewRequestModal .receipt-preview {
                min-height: 250px;
            }

            #viewRequestModal #requestActionsSection .btn {
                min-width: auto;
                padding: 0.4rem 0.75rem;
            }

            #receiptPreviewModal .modal-dialog {
                margin: 0.5rem;
            }
        }

        @media (min-width: 769px) and (max-width: 1024px) {
            #viewRequestModal .receipt-preview {
                min-height: 300px;
            }
        }
    </style>
@endpush
