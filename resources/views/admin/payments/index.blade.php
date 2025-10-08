@extends('./layouts.main')

@section('title', 'Admin | All School Fees')

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
                        <a href="{{ route('student.management') }}" class="menu-link bg-dark text-light">
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
        <h4 class="fw-bold py-3 mb-4 text-warning"><span class="text-muted fw-light">
                <a class="text-muted fw-light" href="{{ route('home') }}">Dashboard</a> /
                <a class="text-muted fw-light" href="{{ route('admin.payments.index') }}">School Fees</a> /
            </span>
            All School Fees
        </h4>

        {{-- Notification when year is changed --}}
        {{-- @if (session('school_year_notice'))
            <div class="alert alert-info alert-dismissible fade show mt-2 text-center text-primary fw-bold" role="alert"
                id="school-year-alert">
                {{ session('school_year_notice') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <script>
            setTimeout(function() {
                var alertElem = document.getElementById('school-year-alert');
                if (alertElem) {
                    var bsAlert = bootstrap.Alert.getOrCreateInstance(alertElem);
                    bsAlert.close();
                }
            }, 10000);
        </script> --}}

        {{-- Add Button & Classes Filter --}}
        <div class="d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-start">
                {{-- <a href="{{ session('back_url', url()->previous()) }}"
                    class="btn btn-danger mb-3 d-flex align-items-center me-2">
                    <i class='bx bx-chevrons-left'></i>
                    <span class="d-none d-sm-block">Back</span>
                </a> --}}

                @if ($selectedYear == $currentYear . '-' . ($currentYear + 1))
                    <!-- Add Payment Button -->
                    <button type="button" class="mb-3 d-flex align-items-center btn btn-primary" id="openAddPayment">
                        <i class='bx bxs-plus-square me-1 align-items-center'></i>
                        <span class="d-none d-sm-block">Add School Fee</span>
                    </button>
                @endif
            </div>

            {{-- Classes & School Year Selection --}}
            <div class="d-flex justify-content-end gap-2 mb-2">

                {{-- Classes Filter (Tom Select) --}}
                <form method="GET" action="{{ route('admin.payments.index') }}" style="min-width: 130px;">
                    <input type="hidden" name="school_year" value="{{ $selectedYear }}">
                    <select id="classFilter" name="class_id" autocomplete="off">
                        <option value="">All Classes</option>
                        @foreach ($allClasses as $class)
                            <option value="{{ $class->id }}" {{ $selectedClass == $class->id ? 'selected' : '' }}>
                                {{ $class->formattedGradeLevel ?? ucfirst($class->grade_level) }}
                                - {{ $class->section }}
                            </option>
                        @endforeach
                    </select>
                </form>

                {{-- School Year Filter (Tom Select) --}}
                <form method="GET" action="{{ route('admin.payments.index') }}" style="min-width: 100px;">
                    <input type="hidden" name="class_id" value="{{ $selectedClass }}">
                    <select id="yearFilter" name="school_year" autocomplete="off">
                        @foreach ($schoolYears as $year)
                            <option value="{{ $year }}" {{ $selectedYear == $year ? 'selected' : '' }}>
                                {{ $year }}
                            </option>
                        @endforeach
                    </select>
                </form>

                <!-- "Now" button -->
                <form method="GET" action="{{ route('admin.payments.index') }}">
                    <input type="hidden" name="school_year" value="{{ $currentYear . '-' . ($currentYear + 1) }}">
                    <input type="hidden" name="class_id" value="{{ request('class_id') }}">
                    <button type="submit" class="btn btn-sm btn-primary" style="height: 38px;">
                        Now
                    </button>
                </form>
            </div>
        </div>
        {{-- /Add Button & Classes Filter --}}

        {{-- Payment Statistics Cards --}}
        <div class="card p-4 shadow-sm">
            <div class="card-body">
                <h4 class="fw-bold mb-4">
                    @if ($selectedClass)
                        @php
                            $class = $allClasses->firstWhere('id', $selectedClass);
                        @endphp
                        School Fees for {{ $class->formattedGradeLevel ?? ucfirst($class->grade_level) }} -
                        {{ $class->section }} ({{ $selectedYear }})
                    @else
                        School Fees for All Classes ({{ $selectedYear }})
                    @endif
                </h4>

                @if ($payments->isEmpty())
                    <div class="text-center py-5">
                        <i class="bi bi-credit-card-2-front text-info display-4"></i>
                        <h5 class="mt-3 fw-bold text-secondary">No payments yet</h5>
                        <p class="text-muted">Start by adding a new payment record for this class.</p>
                    </div>
                @else
                    <div class="row g-4">
                        @foreach ($payments->groupBy('payment_name') as $paymentName => $groupedPayments)
                            @php
                                $first = $groupedPayments->first();
                                $totalStudents = $groupedPayments->count();
                                $paidCount = $groupedPayments->where('status', 'paid')->count();
                                $partialCount = $groupedPayments->where('status', 'partial')->count();
                                $unpaidCount = $groupedPayments->where('status', 'unpaid')->count();

                                // Gradient colors based on payment name
                                $color1 = '#' . substr(md5($paymentName), 0, 6);
                                $color2 = '#' . substr(md5(strrev($paymentName)), 0, 6);

                                // Collections
                                $totalExpected = $first->amount_due * $totalStudents;

                                $totalCollected = $groupedPayments->sum(function ($payment) {
                                    return $payment->total_paid;
                                });

                                $percentage = $totalExpected > 0 ? round(($totalCollected / $totalExpected) * 100) : 0;
                            @endphp

                            <div class="col-md-4 col-sm-6">
                                <div class="card payment-card border-0 shadow-lg rounded-4 h-100 overflow-hidden"
                                    data-paid="{{ $paidCount }}" data-partial="{{ $partialCount }}"
                                    data-unpaid="{{ $unpaidCount }}" data-total="{{ $totalStudents }}"
                                    data-percentage="{{ $percentage }}">
                                    <a href="{{ route('admin.payments.show', ['paymentName' => $paymentName, 'school_year' => $selectedYear, 'class_id' => $selectedClass]) }}"
                                        class="text-decoration-none text-dark">

                                        <!-- Card Header -->
                                        <div class="card-header text-white border-0 position-relative"
                                            style="background-color: {{ $color1 }}; height: 140px;">
                                            <div class="d-flex justify-content-between align-items-start">
                                                <h4 class="card-title text-white fw-bold mb-1">{{ $paymentName }}</h4>
                                            </div>
                                            <div class="position-absolute bottom-0 start-0 p-3 w-100"
                                                style="background: rgba(0, 0, 0, 0.2);">
                                                <p class="text-white mb-1">
                                                    <i class="bi bi-calendar-event me-1"></i> Due Date:
                                                    {{ \Carbon\Carbon::parse($first->due_date)->format('M d, Y') }}
                                                </p>
                                                <p class="text-white mb-0">
                                                    <i class="bi bi-cash-coin me-1"></i> Amount:
                                                    ₱{{ number_format($first->amount_due, 2) }}
                                                </p>
                                            </div>
                                        </div>
                                    </a>

                                    <!-- Card Body -->
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <div class="d-flex flex-column align-items-center gap-1">
                                                <h2 class="mb-2 fw-bold">{{ $totalStudents }}</h2>
                                                <span>Total Students</span>
                                            </div>
                                            <div class="paymentStatisticsChart"></div>
                                        </div>

                                        <ul class="p-0 m-0">
                                            <li class="d-flex mb-2 pb-1">
                                                <div class="avatar flex-shrink-0 me-3">
                                                    <span class="avatar-initial rounded bg-label-success"><i
                                                            class="bx bx-check-double fs-4"></i></span>
                                                </div>
                                                <div
                                                    class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                                    <div class="me-2">
                                                        <h6 class="mb-0">Paid</h6>
                                                        <small class="text-muted">Fully Paid</small>
                                                    </div>
                                                    <div class="user-progress">
                                                        <h4 class="fw-semibold">{{ $paidCount }}</h4>
                                                    </div>
                                                </div>
                                            </li>
                                            <li class="d-flex mb-2 pb-1">
                                                <div class="avatar flex-shrink-0 me-3">
                                                    <span class="avatar-initial rounded bg-label-warning text-dark"><i
                                                            class="bx bx-file fs-4 text-warning"></i></span>
                                                </div>
                                                <div
                                                    class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                                    <div class="me-2">
                                                        <h6 class="mb-0">Partial</h6>
                                                        <small class="text-muted">Partially Paid</small>
                                                    </div>
                                                    <div class="user-progress">
                                                        <h4 class="fw-semibold">{{ $partialCount }}</h4>
                                                    </div>
                                                </div>
                                            </li>
                                            <li class="d-flex">
                                                <div class="avatar flex-shrink-0 me-3">
                                                    <span class="avatar-initial rounded bg-label-danger"><i
                                                            class="bx bx-error-circle fs-4"></i></span>
                                                </div>
                                                <div
                                                    class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                                    <div class="me-2">
                                                        <h6 class="mb-0">Unpaid</h6>
                                                        <small class="text-muted">Not Paid</small>
                                                    </div>
                                                    <div class="user-progress">
                                                        <h4 class="fw-semibold">{{ $unpaidCount }}</h4>
                                                    </div>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>

                                    <!-- Card Footer -->
                                    <div
                                        class="card-footer bg-light border-0 d-flex justify-content-between align-items-center">
                                        <span class="text-muted small">Created on:
                                            {{ \Carbon\Carbon::parse($first->created_at)->format('M d, Y') }}</span>
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-outline-secondary rounded-circle"
                                                data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="bi bi-three-dots-vertical"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end shadow-sm rounded-3">
                                                <li>
                                                    <a href="javascript:void(0)"
                                                        class="dropdown-item text-danger delete-payment"
                                                        data-payment-name="{{ $paymentName }}">
                                                        <i class="bi bi-trash me-2"></i>Delete
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
        {{-- /Payment Statistics Cards --}}

    </div>
    <!-- Content wrapper -->

    <!-- Add Payment for Specific Students Modal -->
    <div class="modal fade" id="addPaymentStudentsModal" tabindex="-1" aria-labelledby="addPaymentStudentsModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <form class="modal-content" action="{{ route('admin.payments.create') }}" method="POST">
                @csrf
                <input type="hidden" name="school_year" value="{{ $selectedYear }}">

                <div class="modal-header">
                    <h5 class="modal-title fw-bold text-primary">Add School Fee for Specific Students</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label class="form-label">School Fee Name</label>
                        <input type="text" name="payment_name" class="form-control" required>
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label">Amount Due</label>
                        <input type="number" name="amount_due" class="form-control" step="0.01" required>
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label">Due Date</label>
                        <input type="date" name="due_date" class="form-control" required>
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label">Select Students (Enrolled in {{ $selectedYear }})</label>
                        <select name="class_student_ids[]" id="classStudentSelectMulti" class="tom-select" multiple
                            required>
                            {{-- Filled dynamically via AJAX --}}
                        </select>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Create</button>
                </div>
            </form>
        </div>
    </div>
    <!-- /Add Payment for Specific Students Modal -->

    <!-- Add Payment By Batch/Class Modal -->
    <div class="modal fade" id="addPaymentBatchModal" tabindex="-1" aria-labelledby="addPaymentBatchModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <form class="modal-content" action="{{ route('admin.payments.create') }}" method="POST">
                @csrf
                <input type="hidden" name="school_year" value="{{ $selectedYear }}">

                <div class="modal-header">
                    <h5 class="modal-title fw-bold text-primary">Add School Fee by Batch/Class</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label class="form-label">School Fee Name</label>
                        <input type="text" name="payment_name" class="form-control" required>
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label">Amount Due</label>
                        <input type="number" name="amount_due" class="form-control" step="0.01" required>
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label">Due Date</label>
                        <input type="date" name="due_date" class="form-control" required>
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label">Classes</label>
                        <select name="class_ids[]" id="classSelectBatch" class="tom-select" multiple required>
                            <option value="all"
                                {{ $totalEnrolled == 0 ? 'data-custom-class=text-danger disabled' : '' }}
                                {{ $totalEnrolled == 0 ? 'disabled' : '' }}>
                                All Classes
                                ({{ $totalEnrolled > 0 ? $totalEnrolled . ' students' : 'No students enrolled' }})
                            </option>
                            @foreach ($allClasses as $class)
                                <option value="{{ $class->id }}"
                                    {{ $class->enrolled_count == 0 ? 'data-custom-class=text-danger' : '' }}
                                    {{ $class->enrolled_count == 0 ? 'disabled' : '' }}>
                                    {{ $class->formattedGradeLevel ?? ucfirst($class->grade_level) }} -
                                    {{ $class->section }}
                                    ({{ $class->enrolled_count > 0 ? $class->enrolled_count . ' students' : 'No students enrolled' }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Create</button>
                </div>
            </form>
        </div>
    </div>
    <!-- /Add Payment By Batch/Class Modal -->

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

    <!-- Delete -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const deleteButtons = document.querySelectorAll('.delete-payment');

            deleteButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const paymentName = this.dataset.paymentName;

                    Swal.fire({
                        title: 'Are you sure?',
                        text: `You are about to delete all payments for "${paymentName}". This action cannot be undone.`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Yes, delete it!',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Send DELETE request via fetch
                            fetch(`{{ url('payments') }}/${encodeURIComponent(paymentName)}`, {
                                    method: 'DELETE',
                                    headers: {
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                        'Content-Type': 'application/json'
                                    },
                                })
                                .then(response => {
                                    if (response.ok) {
                                        Swal.fire(
                                            'Deleted!',
                                            'Payment(s) have been deleted.',
                                            'success'
                                        ).then(() => location.reload());
                                    } else {
                                        Swal.fire(
                                            'Error!',
                                            'There was a problem deleting the payment(s).',
                                            'error'
                                        );
                                    }
                                });
                        }
                    });
                });
            });
        });
    </script>

    <!-- Error -->
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

    <!-- Class Filter -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const classFilter = document.getElementById('classFilter');
            if (classFilter) {
                classFilter.addEventListener('change', function() {
                    this.form.submit(); // auto-submit the form on change
                });
            }
        });
    </script>

    <!-- Tom Select JS for Classes and School Year Filter -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Classes Filter
            new TomSelect("#classFilter", {
                placeholder: "Select a class...",
                allowEmptyOption: true,
                persist: false,
                create: false,
                plugins: ['dropdown_input'],
                onChange: function() {
                    this.input.form.submit();
                }
            });

            // School Year Filter
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

    <!-- SweetAlert2 and Tom Select for Add Payment Modal -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const addPaymentBtn = document.getElementById('openAddPayment');

            addPaymentBtn.addEventListener('click', function() {
                Swal.fire({
                    title: 'Select Payment Assignment',
                    text: 'Would you like to assign payments by Batch/Class or by Specific Students?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'By Batch/Class',
                    cancelButtonText: 'Specific Students',
                    reverseButtons: true,
                    customClass: {
                        container: 'my-swal-container'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        new bootstrap.Modal(document.getElementById('addPaymentBatchModal')).show();
                    } else if (result.dismiss === Swal.DismissReason.cancel) {
                        new bootstrap.Modal(document.getElementById('addPaymentStudentsModal'))
                            .show();
                    }
                });
            });

            // TomSelect init for Batch/Class
            new TomSelect("#classSelectBatch", {
                plugins: ['remove_button'],
                maxItems: null,
                placeholder: "Select one or more classes (or All Classes)",
                render: {
                    option: function(data, escape) {
                        let text = escape(data.text);
                        let customClass = data.customClass ? data.customClass : '';
                        let disabled = data.disabled ? 'opacity-50' : '';
                        return `<div class="${customClass} ${disabled}">${text}</div>`;
                    },
                    item: function(data, escape) {
                        let text = escape(data.text);
                        let customClass = data.customClass ? data.customClass : '';
                        return `<div class="${customClass}">${text}</div>`;
                    }
                },
                onInitialize: function() {
                    this.options = Object.fromEntries(Object.entries(this.options).map(([k, v]) => {
                        let optionEl = this.input.querySelector(`option[value="${k}"]`);
                        if (optionEl) {
                            if (optionEl.dataset.customClass) {
                                v.customClass = optionEl.dataset.customClass;
                            }
                            if (optionEl.disabled) {
                                v.disabled = true;
                            }
                        }
                        return [k, v];
                    }));
                },
                onChange: function(values) {
                    // ✅ If "all" is selected, remove any specific classes
                    if (values.includes("all") && values.length > 1) {
                        this.setValue(["all"]);
                    }
                }
            });

            // TomSelect init for Specific Students (AJAX search)
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
                        fetch("{{ route('class-students.search') }}?q=" + encodeURIComponent(query))
                            .then(response => response.json())
                            .then(data => {
                                callback(data.map(cs => {
                                    let fullName = cs.student.student_fName + " " + cs
                                        .student.student_lName;
                                    let gradeSection = (cs.class.formatted_grade_level ?
                                            " " + cs
                                            .class.formatted_grade_level : "") +
                                        (cs.class.section ? " - " + cs.class.section :
                                            "");
                                    return {
                                        id: cs.id,
                                        text: cs.student.student_lrn + " " + fullName +
                                            " (" + gradeSection + ")"
                                    };
                                }));
                            }).catch(() => {
                                callback();
                            });
                    }
                });

            }
        });
    </script>

    <!-- Donut Chart -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const config = {
                colors: {
                    success: '#28a745',
                    warning: '#ffc107',
                    danger: '#dc3545',
                }
            };
            const cardColor = '#fff';
            const headingColor = '#000';
            const axisColor = '#888';

            document.querySelectorAll('.payment-card').forEach(card => {
                const chartEl = card.querySelector('.paymentStatisticsChart');
                const paid = parseInt(card.dataset.paid);
                const partial = parseInt(card.dataset.partial);
                const unpaid = parseInt(card.dataset.unpaid);
                const total = parseInt(card.dataset.total);

                // Get percentage from card's badge or data attribute
                const percentage = parseFloat(card.dataset.percentage);

                if (chartEl) {
                    const chartConfig = {
                        chart: {
                            height: 165,
                            width: 130,
                            type: 'donut'
                        },
                        labels: ['Paid', 'Partial', 'Unpaid'],
                        series: [paid, partial, unpaid],
                        colors: [config.colors.success, config.colors.warning, config.colors.danger],
                        stroke: {
                            width: 5,
                            colors: cardColor
                        },
                        dataLabels: {
                            enabled: false
                        },
                        legend: {
                            show: false
                        },
                        plotOptions: {
                            pie: {
                                donut: {
                                    size: '75%',
                                    labels: {
                                        show: true,
                                        value: {
                                            fontSize: '1.5rem',
                                            color: headingColor,
                                            offsetY: -15
                                        },
                                        name: {
                                            offsetY: 20
                                        },
                                        total: {
                                            show: true,
                                            fontSize: '0.8125rem',
                                            color: axisColor,
                                            label: 'Collected',
                                            formatter: () => `${percentage}%`
                                        }
                                    }
                                }
                            }
                        }
                    };

                    new ApexCharts(chartEl, chartConfig).render();
                }
            });
        });
    </script>
@endpush

@push('styles')
    <!-- Include ApexCharts -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <!-- Main CSS File -->
    <link href="{{ asset('assets/css/main.css') }}" rel="stylesheet" />
    <link href="{{ asset('assetsDashboard/vendor/css/core.css') }}" rel="stylesheet" />
    <link href="{{ asset('assetsDashboard/vendor/css/theme-default.css') }}" rel="stylesheet" />

    <!-- Vendor CSS Files -->
    <link href="{{ asset('assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet" />

    <style>
        /* Payment Cards (same as subject cards) */
        .payment-card {
            transition: transform 0.25s ease, box-shadow 0.25s ease;
            cursor: pointer;
        }

        .payment-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
        }

        /* Avatar / Icon */
        .avatar-wrapper i {
            transition: transform 0.25s ease;
            font-size: 2rem;
        }

        .payment-card:hover .avatar-wrapper i {
            transform: scale(1.2);
        }

        /* Badge styling */
        .payment-card .badge {
            font-size: 0.75rem;
            padding: 0.45em 0.65em;
            border-radius: 0.5rem;
        }

        /* Text truncation if needed */
        .payment-card .card-title {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        /* Dropdown polish */
        .dropdown-menu {
            border: none;
            font-size: 14px;
        }

        .dropdown-item i {
            font-size: 16px;
            vertical-align: middle;
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
