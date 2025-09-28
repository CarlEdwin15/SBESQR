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
        <h4 class="fw-bold py-3 mb-4 text-warning"><span class="text-muted fw-light">
                <a class="text-muted fw-light" href="{{ route('home') }}">Dashboard</a> /
                <a class="text-muted fw-light" href="{{ route('all.classes') }}">Payments</a> /
            </span>
            All Payments
        </h4>

        {{-- Notification when year is changed --}}
        @if (session('school_year_notice'))
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
        </script>


        <div class="d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-start">
                {{-- <a href="{{ session('back_url', url()->previous()) }}"
                    class="btn btn-danger mb-3 d-flex align-items-center me-2">
                    <i class='bx bx-chevrons-left'></i>
                    <span class="d-none d-sm-block">Back</span>
                </a> --}}

                <!-- Add Payment Button -->
                <!-- Add Payment Button -->
                <button type="button" class="mb-3 d-flex align-items-center btn btn-primary" id="openAddPayment">
                    + Add Payment
                </button>
            </div>

            {{-- Classes & School Year Selection --}}
            <div class="d-flex justify-content-end gap-2">

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

        <div class="card p-4 shadow-sm">
            <h4 class="fw-bold mb-4">
                @if ($selectedClass)
                    @php
                        $class = $allClasses->firstWhere('id', $selectedClass);
                    @endphp
                    Payment Records for {{ $class->formattedGradeLevel ?? ucfirst($class->grade_level) }} -
                    {{ $class->section }}
                    ({{ $selectedYear }})
                @else
                    Payment Records for All Classes ({{ $selectedYear }})
                @endif
            </h4>

            @if ($payments->isEmpty())
                <div class="alert alert-info mb-0">
                    No payments found for this school year.
                </div>
            @else
                <div class="row g-3">
                    @foreach ($payments->groupBy('payment_name') as $paymentName => $groupedPayments)
                        @php
                            $first = $groupedPayments->first();
                            $totalStudents = $groupedPayments->count();
                            $paidCount = $groupedPayments->where('status', 'paid')->count();
                            $partialCount = $groupedPayments->where('status', 'partial')->count();
                            $unpaidCount = $groupedPayments->where('status', 'unpaid')->count();
                        @endphp

                        <div class="col-md-4 col-lg-3">
                            <a href="{{ route('admin.payments.show', [
                                'paymentName' => $paymentName,
                                'school_year' => $selectedYear,
                                'class_id' => $selectedClass,
                            ]) }}"
                                class="text-decoration-none text-dark">
                                <div class="card payment-card h-100">
                                    <div class="card-body d-flex flex-column justify-content-between">
                                        <div>
                                            <h5 class="card-title fw-bold text-gradient mb-2">
                                                {{ $paymentName }}
                                            </h5>
                                            <p class="mb-1"><strong>Amount Due:</strong>
                                                ₱{{ number_format($first->amount_due, 2) }}</p>
                                            <p class="mb-1"><strong>Due Date:</strong>
                                                {{ \Carbon\Carbon::parse($first->due_date)->format('M d, Y') }}</p>
                                            <p class="mb-2"><strong>Created:</strong>
                                                {{ \Carbon\Carbon::parse($first->date_created)->format('M d, Y') }}</p>
                                        </div>
                                        <div class="mt-3">
                                            <span class="badge bg-success me-1">Paid: {{ $paidCount }}</span>
                                            <span class="badge bg-warning text-dark me-1">Partial:
                                                {{ $partialCount }}</span>
                                            <span class="badge bg-danger me-1">Unpaid: {{ $unpaidCount }}</span>
                                            <span class="badge bg-secondary">Total: {{ $totalStudents }}</span>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

    </div>
    <!-- Content wrapper -->

    <!-- Add Payment By Batch/Class Modal -->
    <div class="modal fade" id="addPaymentBatchModal" tabindex="-1" aria-labelledby="addPaymentBatchModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form class="modal-content" action="{{ route('admin.payments.create') }}" method="POST">
                @csrf
                <input type="hidden" name="school_year" value="{{ $selectedYear }}">

                <div class="modal-header">
                    <h5 class="modal-title fw-bold text-primary">Add Payment by Batch/Class</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label class="form-label">Payment Name</label>
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
                        <label class="form-label">Class</label>
                        <select name="class_id" id="classSelectBatch" class="tom-select" required>
                            <option value="">-- Select Class --</option>
                            @foreach ($allClasses as $class)
                                <option value="{{ $class->id }}">
                                    {{ $class->formattedGradeLevel ?? ucfirst($class->grade_level) }} -
                                    {{ $class->section }}
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

    <!-- Add Payment for Specific Students Modal -->
    <div class="modal fade" id="addPaymentStudentsModal" tabindex="-1" aria-labelledby="addPaymentStudentsModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form class="modal-content" action="{{ route('admin.payments.create') }}" method="POST">
                @csrf
                <input type="hidden" name="school_year" value="{{ $selectedYear }}">

                <div class="modal-header">
                    <h5 class="modal-title fw-bold text-primary">Add Payment for Specific Students</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label class="form-label">Payment Name</label>
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
                placeholder: "Select a Class"
            });

            // 🔹 TomSelect init for Specific Students (AJAX search)
            if (document.getElementById('classStudentSelectMulti')) {
                new TomSelect('#classStudentSelectMulti', {
                    plugins: ['remove_button'],
                    maxItems: null,
                    placeholder: "Search enrolled students...",
                    valueField: 'id', // now returns class_student.id
                    labelField: 'text',
                    searchField: 'text',
                    load: function(query, callback) {
                        if (!query.length) return callback();
                        fetch("{{ route('class-students.search') }}?q=" + encodeURIComponent(query))
                            .then(response => response.json())
                            .then(data => {
                                callback(data.map(cs => ({
                                    id: cs.id, // class_student.id
                                    text: cs.student.student_lrn + " - " + cs
                                        .student.student_fName + " " + cs.student
                                        .student_lName
                                })));
                            }).catch(() => {
                                callback();
                            });

                    }
                });
            }
        });
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
@endpush

@push('styles')
    <!-- Main CSS File -->
    <link href="{{ asset('assets/css/main.css') }}" rel="stylesheet" />
    <link href="{{ asset('assetsDashboard/vendor/css/core.css') }}" rel="stylesheet" />
    <link href="{{ asset('assetsDashboard/vendor/css/theme-default.css') }}" rel="stylesheet" />

    <!-- Vendor CSS Files -->
    <link href="{{ asset('assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet" />

    <style>
        .payment-card {
            transition: all 0.35s ease;
            border-radius: 1rem;
            background-color: #ffffff;
            border: 1px solid #f0f0f0;
        }

        .payment-card:hover {
            transform: translateY(-10px) scale(1.04);
            box-shadow: 0 16px 30px rgba(0, 0, 0, 0.15);
            background-color: #f8fbff;
        }

        /* Gradient text for titles */
        .text-gradient {
            background: linear-gradient(45deg, #007bff, #00c6ff);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        /* Badge spacing */
        .payment-card .badge {
            font-size: 0.75rem;
            padding: 0.45em 0.65em;
            border-radius: 0.5rem;
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
