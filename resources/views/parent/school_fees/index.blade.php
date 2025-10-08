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
        </div>

        <ul class="menu-inner py-1 bg-dark">

            <!-- Dashboard sidebar-->
            <li class="menu-item">
                <a href="{{ '/home ' }}" class="menu-link bg-dark text-light">
                    <i class="menu-icon tf-icons bx bx-home-circle text-light"></i>
                    <div class="text-light">Dashboard</div>
                </a>
            </li>

            {{-- My Children sidebar --}}
            <li class="menu-item">
                <a href="{{ route('parent.children.index') }}" class="menu-link bg-dark text-light">
                    <i class="menu-icon tf-icons bx bx-child text-light"></i>
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

            {{-- Announcements sidebar --}}
            <li class="menu-item">
                <a href="{{ route('parent.announcements.index') }}" class="menu-link bg-dark text-light">
                    <i class="menu-icon tf-icons bx bxs-megaphone text-light"></i>
                    <div class="text-light">Announcements</div>
                </a>
            </li>

            {{-- SMS Logs sidebar --}}
            <li class="menu-item">
                <a href="{{ route('parent.sms-logs.index') }}" class="menu-link bg-dark text-light">
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
                    <h3 class="fw-bold mb-4">My Children’s School Fees</h3>

                    @if ($payments->isEmpty())
                        <div class="text-center py-5">
                            <i class="bi bi-credit-card-2-front text-info display-4"></i>
                            <h5 class="mt-3 fw-bold text-secondary">No School Fees Yet</h5>
                            <p class="text-muted">There are currently no payment records for your children.</p>
                        </div>
                    @else
                        <div class="row g-4">
                            @foreach ($payments as $payment)
                                @php
                                    $student = $payment->classStudent->student;
                                    $class = $payment->classStudent->class;
                                    $sy = $payment->classStudent->schoolYear;
                                    $photo = $student->student_photo
                                        ? asset('storage/' . $student->student_photo)
                                        : asset('assetsDashboard/img/student_profile_pictures/student_default_profile.jpg');
                                @endphp

                                <div class="col-md-6 col-lg-4">
                                    <div class="card border-0 shadow-lg rounded-4 h-100 overflow-hidden">
                                        <!-- Student Photo -->
                                        <div class="text-center mt-3">
                                            <img src="{{ $photo }}" alt="Student Photo"
                                                class="rounded-circle shadow-sm border border-3 border-warning"
                                                width="90" height="90" style="object-fit: cover;">
                                        </div>

                                        <!-- Card Header -->
                                        <div class="card-header bg-transparent text-center border-0">
                                            <h5 class="mb-1 fw-bold text-info">{{ $payment->payment_name }}</h5>
                                            <small class="text-muted">{{ $sy->school_year ?? 'N/A' }}</small>
                                        </div>

                                        <!-- Card Body -->
                                        <div class="card-body text-center">
                                            <h6 class="fw-bold text-dark mb-2">{{ $student->full_name }}</h6>
                                            <p class="mb-1"><strong>Class:</strong> {{ $class->formattedGradeLevel }} -
                                                {{ $class->section }}</p>
                                            <p class="mb-1"><strong>Due Date:</strong>
                                                {{ \Carbon\Carbon::parse($payment->due_date)->format('M d, Y') }}</p>
                                            <p class="mb-1"><strong>Amount Due:</strong>
                                                ₱{{ number_format($payment->amount_due, 2) }}</p>
                                            <p class="mb-3"><strong>Amount Paid:</strong>
                                                ₱{{ number_format($payment->total_paid, 2) }}</p>

                                            @if ($payment->status === 'paid')
                                                <span class="badge bg-success px-3 py-2">Paid</span>
                                            @elseif($payment->status === 'partial')
                                                <span class="badge bg-warning text-dark px-3 py-2">Partially Paid</span>
                                            @else
                                                <span class="badge bg-danger px-3 py-2">Unpaid</span>
                                            @endif
                                        </div>

                                        <!-- Footer -->
                                        <div class="card-footer bg-light text-center small text-muted border-0">
                                            Created on: {{ $payment->created_at->format('M d, Y') }}
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>

    </div>
    <!-- /Content Wrapper -->
@endsection
