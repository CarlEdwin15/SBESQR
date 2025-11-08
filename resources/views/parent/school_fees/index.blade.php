@extends('./layouts.main')

@section('title', 'Parent | School Fees')

@section('content')
    <!-- Menu -->
    <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
        <div class="app-brand bg-dark">
            <a href="{{ url('/home') }}" class="app-brand-link">
                <img src="{{ asset('assets/img/logo.png') }}" alt="Logo" class="app-brand-logo">
                <span class="app-brand-text menu-text fw-bolder text-light" style="padding: 9px">Parent's
                    <span class="text-light">Dashboard</span>
                </span>
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
                    <div class="text-light">My Children</div>
                </a>
            </li>

            {{-- School Fees sidebar --}}
            <li class="menu-item active">
                <a href="{{ route('parent.school-fees.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-wallet-alt"></i>
                    <div class="text-warning">School Fees</div>
                </a>
            </li>

            {{-- Account Settings sidebar --}}
            <li class="menu-item">
                <a href="{{ route('parent.account.settings') }}" class="menu-link bg-dark text-light">
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

    <!-- Content Wrapper -->
    <div class="container-xxl container-p-y">
        <h4 class="fw-bold py-3 mb-4 text-warning">
            <span class="text-muted fw-light">
                <a class="text-muted fw-light" href="{{ url('/home') }}">Dashboard / </a>
            </span> School Fees
        </h4>

        <div class="card shadow-sm">
            <div class="card-body">
                <div class="container py-4">

                    {{-- Header & School Year Filter --}}
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h3 class="fw-bold">My Children’s School Fees</h3>

                        {{-- School Year Filter --}}
                        <form method="GET" action="{{ route('parent.school-fees.index') }}" style="min-width: 150px;">
                            <select id="yearFilter" name="school_year" autocomplete="off">
                                @foreach ($schoolYears as $year)
                                    <option value="{{ $year }}" {{ $selectedYear == $year ? 'selected' : '' }}>
                                        {{ $year }}
                                    </option>
                                @endforeach
                            </select>
                        </form>
                    </div>

                    <h5 class="text-muted mb-4">
                        Showing payments for School Year: <strong>{{ $selectedYear }}</strong>
                    </h5>

                    {{-- Payment Cards --}}
                    @if ($payments->isEmpty())
                        <div class="text-center py-5">
                            <i class="bi bi-credit-card-2-front text-info display-4"></i>
                            <h5 class="mt-3 fw-bold text-secondary">No School Fees Yet</h5>
                            <p class="text-muted">There are currently no payment records for your children.</p>
                        </div>
                    @else
                        <div class="row g-4">
                            @php
                                // Sort payments: unpaid first, then partial, then paid.
                                // If two have the same status, sort by least amount paid.
                                $sortedPayments = $payments->sort(function ($a, $b) {
                                    $statusOrder = ['unpaid' => 0, 'partial' => 1, 'paid' => 2];
                                    $aOrder = $statusOrder[$a->status] ?? 3;
                                    $bOrder = $statusOrder[$b->status] ?? 3;

                                    if ($aOrder === $bOrder) {
                                        return $a->total_paid <=> $b->total_paid;
                                    }
                                    return $aOrder <=> $bOrder;
                                });
                            @endphp

                            @foreach ($sortedPayments as $payment)
                                @php
                                    $student = $payment->classStudent->student;
                                    $class = $payment->classStudent->class;
                                    $photo = $student->student_photo
                                        ? asset('public/uploads/' . $student->student_photo)
                                        : asset(
                                            'assetsDashboard/img/student_profile_pictures/student_default_profile.jpg',
                                        );

                                    // Header color based on payment status
                                    switch ($payment->status) {
                                        case 'paid':
                                            $headerClass = 'bg-success bg-gradient';
                                            break;
                                        case 'partial':
                                            $headerClass = 'bg-warning bg-gradient';
                                            break;
                                        default:
                                            $headerClass = 'bg-danger bg-gradient';
                                            break;
                                    }
                                @endphp

                                <div class="col-md-6 col-lg-4">
                                    <div class="card payment-card border-0 shadow-lg rounded-4 h-100 overflow-hidden">

                                        <!-- Card Header as Modal Trigger -->
                                        <a href="javascript:void(0);" class="text-decoration-none text-dark"
                                            data-bs-toggle="modal"
                                            data-bs-target="#paymentProgressModal{{ $payment->id }}">

                                            <div class="card-header text-white border-0 position-relative {{ $headerClass }}"
                                                style="height: 140px; cursor: pointer;">
                                                <!-- Header Content Layout -->
                                                <div class="d-flex justify-content-between align-items-center h-100">
                                                    <!-- Left: Payment Info -->
                                                    <div class="text-start">
                                                        <h4 class="card-title fw-bold text-white mb-1">
                                                            {{ $payment->payment_name }}
                                                        </h4>
                                                        <p class="mb-0 small">
                                                            <i class="bi bi-calendar-event me-1"></i>
                                                            Due:
                                                            {{ \Carbon\Carbon::parse($payment->due_date)->format('M d, Y') }}
                                                        </p>
                                                    </div>

                                                    <!-- Right: Student Info -->
                                                    <div class="text-center">
                                                        <img src="{{ $photo }}" alt="Student Photo"
                                                            class="rounded-circle border border-3 border-light shadow-sm mb-2"
                                                            width="65" height="65" style="object-fit: cover;">
                                                        <h6 class="fw-bold text-white mb-0">{{ $student->full_name }}</h6>
                                                        <p class="mb-0 small text-white">
                                                            {{ $class->formattedGradeLevel }} - {{ $class->section }}
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>

                                        <!-- Card Body -->
                                        <div class="card-body mt-4">
                                            {{-- Dynamic payment summary based on status --}}
                                            <ul class="p-0 m-0">
                                                @if ($payment->status === 'paid')
                                                    <li class="d-flex mb-2 pb-1">
                                                        <div class="avatar flex-shrink-0 me-3">
                                                            <span class="avatar-initial rounded bg-label-success">
                                                                <i class="bx bx-check-double fs-4"></i>
                                                            </span>
                                                        </div>
                                                        <div
                                                            class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                                            <div class="me-2">
                                                                <h6 class="mb-0">Paid</h6>
                                                                <small class="text-muted">Fully Paid</small>
                                                            </div>
                                                            <div class="user-progress">
                                                                <h5 class="fw-semibold mb-0">
                                                                    ₱{{ number_format($payment->total_paid, 2) }} /
                                                                    ₱{{ number_format($payment->amount_due, 2) }}
                                                                </h5>
                                                            </div>
                                                        </div>
                                                    </li>
                                                @elseif($payment->status === 'partial')
                                                    <li class="d-flex mb-2 pb-1">
                                                        <div class="avatar flex-shrink-0 me-3">
                                                            <span
                                                                class="avatar-initial rounded bg-label-warning text-dark">
                                                                <i class="bx bx-file fs-4 text-warning"></i>
                                                            </span>
                                                        </div>
                                                        <div
                                                            class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                                            <div class="me-2">
                                                                <h6 class="mb-0">Partial</h6>
                                                                <small class="text-muted">Partially Paid</small>
                                                            </div>
                                                            <div class="user-progress">
                                                                <h5 class="fw-semibold mb-0 text-warning">
                                                                    ₱{{ number_format($payment->total_paid, 2) }} /
                                                                    ₱{{ number_format($payment->amount_due, 2) }}
                                                                </h5>
                                                            </div>
                                                        </div>
                                                    </li>
                                                @else
                                                    <li class="d-flex">
                                                        <div class="avatar flex-shrink-0 me-3">
                                                            <span class="avatar-initial rounded bg-label-danger">
                                                                <i class="bx bx-error-circle fs-4"></i>
                                                            </span>
                                                        </div>
                                                        <div
                                                            class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                                            <div class="me-2">
                                                                <h6 class="mb-0">Unpaid</h6>
                                                                <small class="text-muted">Not Paid</small>
                                                            </div>
                                                            <div class="user-progress">
                                                                <h5 class="fw-semibold mb-0 text-danger">
                                                                    ₱{{ number_format($payment->total_paid, 2) }} /
                                                                    ₱{{ number_format($payment->amount_due, 2) }}
                                                                </h5>
                                                            </div>
                                                        </div>
                                                    </li>
                                                @endif
                                            </ul>
                                        </div>

                                        <!-- Card Footer -->
                                        <div class="card-footer bg-light border-0 small text-muted text-center">
                                            <span>Created on: {{ $payment->created_at->format('M d, Y') }}</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Payment Progress Modal -->
                                <div class="modal fade" id="paymentProgressModal{{ $payment->id }}" tabindex="-1"
                                    aria-labelledby="paymentProgressLabel{{ $payment->id }}" data-bs-backdrop="static"
                                    aria-hidden="true">
                                    <div class="modal-dialog modal-lg modal-dialog-centered">
                                        <div class="modal-content shadow-lg rounded-4">

                                            <!-- Modal Header -->
                                            <div class="modal-header text-white">
                                                <button type="button" class="btn-close btn-close-white"
                                                    data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>

                                            <!-- Modal Body -->
                                            <div class="modal-body">
                                                <!-- Student Info -->
                                                <div class="d-flex align-items-center justify-content-between mb-4">
                                                    <div class="d-flex align-items-center">
                                                        <img src="{{ $photo }}"
                                                            class="rounded-circle border border-3 border-warning me-3"
                                                            style="width: 70px; height: 70px; object-fit: cover;">
                                                        <div>
                                                            <h6 class="fw-bold mb-1">{{ $payment->payment_name }}</h6>
                                                            <h6 class="fw-bold mb-1">{{ $student->full_name }}</h6>
                                                            <small class="text-muted">{{ $class->formattedGradeLevel }} -
                                                                {{ $class->section }}</small>
                                                        </div>
                                                    </div>

                                                    <button type="button" class="btn btn-info"
                                                        data-bs-target="#paymentHistoryModal{{ $payment->id }}"
                                                        data-bs-toggle="modal" data-bs-dismiss="modal">
                                                        <i class="bx bx-history me-1"></i> View History
                                                    </button>
                                                </div>

                                                <!-- Payment Progress -->
                                                <div class="mb-3">
                                                    <label class="form-label fw-semibold">Payment Progress</label>
                                                    <input type="text" class="form-control"
                                                        value="₱{{ number_format($payment->total_paid, 2) }} / ₱{{ number_format($payment->amount_due, 2) }}"
                                                        readonly>
                                                </div>

                                                <!-- Remaining Balance -->
                                                <div class="mb-3">
                                                    <label class="form-label fw-semibold">Remaining Balance</label>
                                                    <input type="text" class="form-control"
                                                        value="₱{{ number_format(max($payment->amount_due - $payment->total_paid, 0), 2) }}"
                                                        readonly>
                                                </div>

                                                <!-- Payment Status -->
                                                <div class="mb-3">
                                                    <label class="form-label fw-semibold">Payment Status</label>

                                                    @php
                                                        // Determine background and text color based on status
                                                        $statusBg = match ($payment->status) {
                                                            'paid' => 'bg-label-success',
                                                            'partial' => 'bg-label-warning',
                                                            default => 'bg-label-danger',
                                                        };
                                                        $statusText = ucfirst($payment->status);
                                                    @endphp

                                                    <input type="text"
                                                        class="form-control {{ $statusBg }} fw-semibold text-start border-0"
                                                        value="{{ strtoupper($statusText) }}" readonly
                                                        style="font-weight: 600;">
                                                </div>
                                            </div>

                                            <!-- Modal Footer -->
                                            <div class="modal-footer d-flex justify-content-end">
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Close</button>

                                                <button type="button" class="btn btn-primary"
                                                    data-bs-target="#addPaymentModal{{ $payment->id }}"
                                                    data-bs-toggle="modal" data-bs-dismiss="modal">
                                                    <i class="bx bx-credit-card me-1"></i> Add Payment
                                                </button>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                <!-- /Payment Progress Modal -->

                                <!-- Payment History Modal -->
                                <div class="modal fade" id="paymentHistoryModal{{ $payment->id }}" tabindex="-1"
                                    aria-labelledby="paymentHistoryLabel{{ $payment->id }}" data-bs-backdrop="static"
                                    aria-hidden="true">
                                    <div class="modal-dialog modal-lg modal-dialog-centered">
                                        <div class="modal-content shadow-lg rounded-4">

                                            <div class="modal-header text-primary">
                                                <h5 class="modal-title fw-bold">Payment History -
                                                    {{ $student->full_name }}</h5>
                                                <button type="button" class="btn-close btn-close-white"
                                                    data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>

                                            <div class="modal-body">
                                                @if ($payment->histories->count() > 0)
                                                    <div class="table-responsive"
                                                        style="max-height: 350px; overflow-y: auto;">
                                                        <table class="table table-sm table-hover align-middle">
                                                            <thead class="table-light">
                                                                <tr class="text-center">
                                                                    <th>#</th>
                                                                    <th>Amount</th>
                                                                    <th>Payment Method</th>
                                                                    <th>Date</th>
                                                                    <th>Recorded By</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach ($payment->histories as $i => $history)
                                                                    <tr class="text-center">
                                                                        <td>{{ $i + 1 }}</td>
                                                                        <td>₱{{ number_format($history->amount_paid, 2) }}
                                                                        </td>
                                                                        <td>{{ $history->payment_method_name }}</td>
                                                                        <td>{{ $history->payment_date->format('M d, Y h:i A') }}
                                                                        </td>
                                                                        <td>{{ $history->addedBy->full_name ?? '—' }}</td>
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                @else
                                                    <p class="text-muted small mb-0 text-center">No payment history found.
                                                    </p>
                                                @endif
                                            </div>

                                            <div class="modal-footer d-flex justify-content-between">
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Close</button>
                                                <button type="button" class="btn btn-primary"
                                                    data-bs-target="#paymentProgressModal{{ $payment->id }}"
                                                    data-bs-toggle="modal" data-bs-dismiss="modal">
                                                    <i class="bx bx-left-arrow-alt me-1"></i> Back to Progress
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- /Payment History Modal -->

                                <!-- Add Payment Modal -->
                                <div class="modal fade" id="addPaymentModal{{ $payment->id }}" tabindex="-1"
                                    aria-labelledby="addPaymentLabel{{ $payment->id }}" data-bs-backdrop="static"
                                    aria-hidden="true">
                                    <div class="modal-dialog modal-md modal-dialog-centered">
                                        <div class="modal-content shadow-lg rounded-4 border-0">

                                            <!-- Modal Header -->
                                            <div class="modal-header text-white">
                                                <h5 class="modal-title fw-bold">
                                                    Add Payment - {{ $student->full_name }}
                                                </h5>
                                                <button type="button" class="btn-close btn-close-white"
                                                    data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>

                                            <!-- Modal Body + Form -->
                                            <form action="{{ route('parent.addPayment', $payment->id) }}" method="POST"
                                                enctype="multipart/form-data">
                                                @csrf
                                                <div class="modal-body">

                                                    <!-- Payment Progress -->
                                                    <div class="mb-3">
                                                        <label class="form-label">Payment Progress</label>
                                                        <input type="text" class="form-control"
                                                            value="₱{{ number_format($payment->total_paid, 2) }} / ₱{{ number_format($payment->amount_due, 2) }}"
                                                            readonly>
                                                    </div>

                                                    <!-- Remaining Balance -->
                                                    <div class="mb-3">
                                                        <label class="form-label">Remaining Balance</label>
                                                        <input type="text" class="form-control"
                                                            value="₱{{ number_format(max($payment->amount_due - $payment->total_paid, 0), 2) }}"
                                                            readonly>
                                                    </div>

                                                    <!-- Amount to Pay -->
                                                    <div class="mt-4">
                                                        <label for="amount_paid_{{ $payment->id }}"
                                                            class="form-label fw-semibold">Amount to Pay</label>
                                                        <input type="number" name="amount_paid"
                                                            id="amount_paid_{{ $payment->id }}" class="form-control"
                                                            min="1"
                                                            max="{{ $payment->amount_due - $payment->total_paid }}"
                                                            placeholder="Enter payment amount" required>
                                                    </div>

                                                    <hr class="my-4" />

                                                    <!-- Payment Method -->
                                                    <label class="form-label fw-semibold mb-3">Select Payment
                                                        Method</label>

                                                    <div class="d-flex flex-column gap-2">
                                                        <!-- Cash on Hand -->
                                                        <label
                                                            class="payment-option d-flex align-items-center justify-content-between border rounded px-3 py-1"
                                                            style="cursor: pointer;">
                                                            <div class="d-flex align-items-center">
                                                                <img src="{{ asset('assetsDashboard/img/icons/unicons/coh.png') }}"
                                                                    alt="Cash" width="35" height="20"
                                                                    class="me-2">
                                                                <div class="lh-sm">
                                                                    <div class="fw-semibold small">Cash on Hand</div>
                                                                    <small class="text-muted">Pay at the school
                                                                        treasurer/admin</small>
                                                                </div>
                                                            </div>
                                                            <input type="radio" name="payment_method"
                                                                id="cash_method_{{ $payment->id }}" value="cash_on_hand"
                                                                class="form-check-input mt-0 fs-6" required checked>
                                                        </label>

                                                        <!-- GCash -->
                                                        <label
                                                            class="payment-option d-flex align-items-center justify-content-between border rounded px-3 py-1"
                                                            style="cursor: pointer;">
                                                            <div class="d-flex align-items-center">
                                                                <img src="{{ asset('assetsDashboard/img/icons/unicons/gcash_logo.png') }}"
                                                                    alt="GCash" width="35" height="20"
                                                                    class="me-2">
                                                                <div class="lh-sm">
                                                                    <div class="fw-semibold small">GCash</div>
                                                                    <small class="text-muted">Use your GCash
                                                                        account</small>
                                                                </div>
                                                            </div>
                                                            <input type="radio" name="payment_method"
                                                                id="gcash_method_{{ $payment->id }}" value="gcash"
                                                                class="form-check-input mt-0 fs-6" required>
                                                        </label>

                                                        @error('payment_method')
                                                            <small
                                                                class="text-danger d-block mt-2">{{ $message }}</small>
                                                        @enderror
                                                    </div>

                                                    <!-- CASH ON HAND Section -->
                                                    <div class="payment-section mt-3"
                                                        id="cash_section_{{ $payment->id }}">
                                                        <div class="alert alert-info small mb-3">
                                                            <i class="bi bi-info-circle me-1"></i>
                                                            Please hand your payment directly to the school treasurer/admin
                                                            for confirmation.
                                                        </div>
                                                    </div>

                                                    <!-- GCash Section -->
                                                    <div class="payment-section d-none mt-3"
                                                        id="gcash_section_{{ $payment->id }}">
                                                        <div class="border rounded-4 shadow-sm bg-white p-4">
                                                            <div class="d-flex align-items-center mb-3">
                                                                <img src="{{ asset('assetsDashboard/img/icons/unicons/gcash_logo.png') }}"
                                                                    alt="GCash" width="90" class="me-2">
                                                                <div>
                                                                    <h6 class="fw-bold mb-0 text-primary">Pay with GCash
                                                                    </h6>
                                                                    <small class="text-muted">Fast & secure mobile
                                                                        payment</small>
                                                                </div>
                                                            </div>

                                                            <hr class="text-muted my-3">

                                                            <div class="text-center mb-4">
                                                                <p class="fw-semibold mb-1 text-dark">Scan QR to Pay</p>
                                                                <img src="{{ asset('assetsDashboard/img/backgrounds/qr.png') }}"
                                                                    alt="GCash QR"
                                                                    class="img-fluid rounded-3 border shadow-sm mb-2"
                                                                    style="max-width: 180px;">
                                                                <div class="small text-muted">Use the GCash app to scan
                                                                    this QR code</div>
                                                            </div>

                                                            <div class="bg-light p-3 rounded-3 mb-3">
                                                                <p class="fw-semibold mb-2 text-primary">Payment Details
                                                                </p>
                                                                <p class="mb-1"><strong>Account Name:</strong> St. Mary’s
                                                                    Academy</p>
                                                                <p class="mb-0"><strong>GCash Number:</strong>
                                                                    0917-123-4567</p>
                                                            </div>

                                                            <div class="mb-3">
                                                                <label class="form-label fw-semibold">GCash Reference
                                                                    Number</label>
                                                                <input type="text" name="gcash_reference"
                                                                    class="form-control border-primary"
                                                                    placeholder="Enter 13-digit GCash Ref. No.">
                                                            </div>

                                                            <div class="mb-3">
                                                                <label class="form-label fw-semibold">Upload GCash Receipt
                                                                    Screenshot</label>
                                                                <input type="file" name="gcash_receipt"
                                                                    class="form-control" accept="image/*">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Modal Footer -->
                                                <div class="modal-footer d-flex justify-content-between">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">
                                                        <i class="bx bx-left-arrow-alt me-1"></i> Back
                                                    </button>

                                                    <button type="submit" class="btn btn-success">
                                                        <i class="bx bx-check-circle me-1"></i> Confirm Payment
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <!-- /Add Payment Modal -->
                            @endforeach
                        </div>
                    @endif

                </div>
            </div>
        </div>

    </div>
    <!-- /Content Wrapper -->
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

    <!-- Tom Select JS for School Year Filter -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            new TomSelect("#yearFilter", {
                placeholder: "Select school year...",
                persist: false,
                create: false,
                plugins: ['dropdown_input'],
                onChange: function() {
                    this.input.form.submit();
                }
            });
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            document.querySelectorAll('[id^="addPaymentModal"]').forEach(modal => {
                const paymentId = modal.id.replace("addPaymentModal", "");
                const radios = modal.querySelectorAll('input[name="payment_method"]');

                const sections = {
                    cash_on_hand: modal.querySelector(`#cash_section_${paymentId}`),
                    gcash: modal.querySelector(`#gcash_section_${paymentId}`)
                };

                // Initial visibility
                const initialChecked = modal.querySelector('input[name="payment_method"]:checked');
                Object.entries(sections).forEach(([key, section]) => {
                    if (!initialChecked || initialChecked.value !== key) {
                        section?.classList.add("d-none");
                    }
                });

                // Highlight the selected method
                if (initialChecked) {
                    initialChecked.closest('.payment-option')?.classList.add('border-primary',
                        'bg-primary-subtle');
                }

                // Radio change listener
                radios.forEach(radio => {
                    radio.addEventListener("change", function() {
                        Object.values(sections).forEach(section => section?.classList.add(
                            "d-none"));
                        const selected = this.value;
                        if (sections[selected]) sections[selected].classList.remove(
                            "d-none");

                        modal.querySelectorAll('.payment-option').forEach(opt =>
                            opt.classList.remove('border-primary', 'bg-primary-subtle')
                        );
                        this.closest('.payment-option')?.classList.add('border-primary',
                            'bg-primary-subtle');
                    });
                });
            });
        });
    </script>
@endpush

@push('styles')
    <style>
        .payment-card {
            transition: transform 0.25s ease, box-shadow 0.25s ease;
            cursor: pointer;
        }

        .payment-option.border-primary {
            border-width: 2px !important;
            transition: 0.2s ease;
        }

        .payment-option.bg-primary-subtle {
            background-color: #e3f2fd !important;
            transition: 0.2s ease;
        }

        .payment-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
        }

        .payment-card .card-title {
            font-size: 1.1rem;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .payment-card .badge {
            font-size: 0.75rem;
            border-radius: 0.5rem;
        }

        .payment-card img {
            transition: transform 0.3s ease;
        }

        .payment-card:hover img {
            transform: scale(1.05);
        }

        /* Slight overlay tint for better contrast */
        .card-header.bg-success,
        .card-header.bg-warning,
        .card-header.bg-danger {
            background-image: linear-gradient(to bottom right, rgba(0, 0, 0, 0.05), rgba(0, 0, 0, 0.1));
        }

        .border-dashed {
            border-style: dashed !important;
        }

        .payment-section img[src*="gcash_logo"] {
            filter: drop-shadow(0 0 4px rgba(0, 123, 255, 0.3));
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
