@extends('layouts.main')

@section('title', 'Payments Management')

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

            {{-- Payments sidebar --}}
            <li class="menu-item">
                <a href="javascript:void(0);" class="menu-link menu-toggle bg-dark text-light">
                    <i class="menu-icon tf-icons bx bx-wallet-alt text-light"></i>
                    <div class="text-light">Payments</div>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item">
                        <a href="" class="menu-link bg-dark text-light">
                            <div class="text-light">All Payments</div>
                        </a>
                    </li>
                </ul>
            </li>

            {{-- SMS Logs sidebar --}}
            <li class="menu-item">
                <a href="" class="menu-link bg-dark text-light">
                    <i class="bx bx-message-check me-3 text-light"></i>
                    <div class="text-light">SMS Logs</div>
                </a>
            </li>

            {{-- Account Settings sidebar --}}
            <li class="menu-item">
                <a href="" class="menu-link bg-dark text-light">
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
                    Payments
                </h4>
            </div>
        </div>

        <div class="d-flex justify-content-start align-items-center">
            <a href="{{ route('teacher.myClass', ['grade_level' => $class->grade_level, 'section' => $class->section, 'school_year' => $selectedYear]) }}"
                class="btn btn-danger mb-3 d-flex align-items-center me-2">
                <i class='bx bx-chevrons-left'></i>
                <span class="d-none d-sm-block">Back</span>
            </a>

            <!-- Add Payment Button -->
            <button type="button" class="mb-3 d-flex align-items-center btn btn-primary" data-bs-toggle="modal"
                data-bs-target="#addPaymentModal">
                + Add Payment
            </button>
        </div>


        <div class="card p-4 shadow-sm">
            <h4 class="fw-bold mb-4">Payment Records for {{ $class->formatted_grade_level }} - {{ $class->section }}</h4>

            @if ($payments->isEmpty())
                <div class="alert alert-info mb-0">
                    No payments have been created for this class yet.
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
                            <a href="{{ route('teacher.payments.show', [
                                'grade_level' => $class->grade_level,
                                'section' => $class->section,
                                'paymentName' => $paymentName,
                                'school_year' => $selectedYear,
                            ]) }}"
                                class="text-decoration-none text-dark">
                                <div class="card payment-card h-100">
                                    <div class="card-body d-flex flex-column justify-content-between">
                                        <div>
                                            <h5 class="card-title fw-bold text-gradient mb-2">
                                                {{ $paymentName }}
                                            </h5>
                                            <p class="mb-1">
                                                <strong>Amount Due:</strong> ₱{{ number_format($first->amount_due, 2) }}
                                            </p>
                                            <p class="mb-1">
                                                <strong>Due Date:</strong>
                                                {{ \Carbon\Carbon::parse($first->due_date)->format('M d, Y') }}
                                            </p>
                                            <p class="mb-2">
                                                <strong>Created:</strong>
                                                {{ \Carbon\Carbon::parse($first->date_created)->format('M d, Y') }}
                                            </p>
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


        <!-- Add Payment Modal -->
        <div class="modal fade" id="addPaymentModal" tabindex="-1" aria-labelledby="addPaymentModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <form class="modal-content" id="addPaymentForm"
                    action="{{ route('teacher.payments.create', ['grade_level' => $class->grade_level, 'section' => $class->section]) }}"
                    method="POST">
                    @csrf

                    <input type="hidden" name="school_year" value="{{ $selectedYear }}">

                    <div class="modal-header">
                        <h5 class="modal-title fw-bold text-primary" id="addPaymentModalLabel">Add New Payment</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
                            <label class="form-label">Remarks (optional)</label>
                            <textarea name="remarks" class="form-control" rows="3" placeholder="e.g. For June tuition"></textarea>
                            @if (!empty($first->remarks))
                                <p class="mb-1"><strong>Remarks:</strong> {{ $first->remarks }}</p>
                            @endif
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success">Create</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <!-- SweetAlert2 -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const addPaymentForm = document.getElementById("addPaymentForm");

            addPaymentForm.addEventListener("submit", function(e) {
                e.preventDefault(); // stop default submit

                Swal.fire({
                    title: "Create new payment?",
                    text: "This will add a payment record for all students in this class.",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#28a745",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Yes, create it",
                    cancelButtonText: "Cancel",
                    customClass: {
                        container: 'my-swal-container'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: "Creating Payment...",
                            text: "Please wait while we save the new payment.",
                            icon: "info",
                            allowOutsideClick: false,
                            showConfirmButton: false,
                            customClass: {
                                container: 'my-swal-container'
                            },
                            didOpen: () => {
                                Swal.showLoading(); // animated spinner
                            }
                        });

                        // submit after short delay
                        setTimeout(() => {
                            addPaymentForm.submit();
                        }, 800);
                    }
                });
            });
        });
    </script>
@endpush

@push('styles')
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
    </style>
@endpush
