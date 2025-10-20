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
            {{-- Account Settings sidebar --}}
            <li class="menu-item">
                <a href="{{ route('teacher.account.settings') }}" class="menu-link bg-dark text-light">
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
                        <a class="text-muted fw-light"
                            href="{{ route('teacher.payments.index', ['grade_level' => $class->grade_level, 'section' => $class->section]) }}?school_year={{ $selectedYear }}">
                            Payments /
                        </a>
                    </span>
                    {{ $paymentName }}
                </h4>
            </div>
        </div>

        <!-- Payment Summary Cards -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div class="card mb-6 w-100">
                <div class="card-widget-separator-wrapper">
                    <div class="card-body card-widget-separator">
                        <div class="row gy-4 gy-sm-1">

                            <!-- Total Students -->
                            <div class="col-sm-6 col-lg-3">
                                <div
                                    class="d-flex justify-content-between align-items-center card-widget-1 border-end pb-4 pb-sm-0">
                                    <div>
                                        <h4 class="mb-0">{{ $totalStudents }}</h4>
                                        <p class="mb-0">Total Students</p>
                                    </div>
                                    <div class="avatar me-sm-6 w-px-42 h-px-42">
                                        <span class="avatar-initial rounded bg-label-secondary text-heading">
                                            <i class="icon-base bx bx-user icon-26px"></i>
                                        </span>
                                    </div>
                                </div>
                                <hr class="d-none d-sm-block d-lg-none me-6">
                            </div>

                            <!-- Partial -->
                            <div class="col-sm-6 col-lg-3">
                                <div
                                    class="d-flex justify-content-between align-items-center card-widget-2 border-end pb-4 pb-sm-0">
                                    <div>
                                        <h4 class="mb-0">{{ $partialCount }}</h4>
                                        <p class="mb-0">Partial</p>
                                    </div>
                                    <div class="avatar me-lg-6 w-px-42 h-px-42">
                                        <span class="avatar-initial rounded bg-label-warning text-dark">
                                            <i class="icon-base bx bx-file icon-26px"></i>
                                        </span>
                                    </div>
                                </div>
                                <hr class="d-none d-sm-block d-lg-none">
                            </div>

                            <!-- Paid -->
                            <div class="col-sm-6 col-lg-3">
                                <div
                                    class="d-flex justify-content-between align-items-center border-end pb-4 pb-sm-0 card-widget-3">
                                    <div>
                                        <h4 class="mb-0">{{ $paidCount }}</h4>
                                        <p class="mb-0">Paid</p>
                                    </div>
                                    <div class="avatar me-sm-6 w-px-42 h-px-42">
                                        <span class="avatar-initial rounded bg-label-success text-heading">
                                            <i class="icon-base bx bx-check-double icon-26px"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- Unpaid -->
                            <div class="col-sm-6 col-lg-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h4 class="mb-0">{{ $unpaidCount }}</h4>
                                        <p class="mb-0">Unpaid</p>
                                    </div>
                                    <div class="avatar w-px-42 h-px-42">
                                        <span class="avatar-initial rounded bg-label-danger text-heading">
                                            <i class="icon-base bx bx-error-circle icon-26px"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payments Table -->
        <div class="card p-4 shadow-sm">
            @if ($first)
                <div class="mb-3">

                    <h4 class="mb-3">Payment: {{ $paymentName }}</h4>

                    <strong>Amount Due:</strong> ₱{{ number_format($first->amount_due, 2) }}
                    <span class="mx-2">|</span>
                    <strong>Due Date:</strong> {{ \Carbon\Carbon::parse($first->due_date)->format('M d, Y') }}
                    @if (!empty($first->remarks))
                        <span class="mx-2">|</span>
                        <strong>Remarks:</strong> {{ $first->remarks }}
                    @endif

                    {{-- <div class="d-flex justify-content-end mb-3">
                        <!-- Back button -->
                        <a href="{{ route('teacher.payments.index', [
                            'grade_level' => $class->grade_level,
                            'section' => $class->section,
                            'school_year' => $selectedYear,
                        ]) }}"
                            class="btn btn-danger mb-3 d-flex align-items-center ms-3">
                            <i class='bx bx-chevrons-left'></i>
                            <span class="d-none d-sm-block">Back</span>
                        </a>
                    </div> --}}
                </div>
            @endif

            {{-- MALE STUDENTS --}}
            <div class="table-responsive mb-4">
                <table class="table table-hover table-bordered align-middle">
                    <thead class="table-info">
                        <tr class="text-center">
                            <th style="width: 40px;">No.</th>
                            <th>Male || Name</th>
                            <th>Status</th>
                            <th>Amount Paid</th>
                            <th>Amount Due</th>
                            <th>Date Paid</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $maleIndex = 1; @endphp
                        @foreach ($payments->filter(fn($p) => optional($p->student)->gender === 'male')->sortBy(fn($p) => strtolower(optional($p->student)->student_lName . ' ' . optional($p->student)->student_fName . ' ' . optional($p->student)->student_mName)) as $p)
                            <tr class="{{ $p->status === 'paid' ? 'table-success' : '' }}">
                                <td class="text-center">{{ $maleIndex++ }}</td>
                                <td>
                                    <a href="#" data-bs-toggle="modal"
                                        data-bs-target="#editPaymentModal{{ $p->id }}">
                                        <div class="d-flex align-items-center">
                                            <img src="{{ $p->student && $p->student->student_photo
                                                ? asset('assetsDashboard/img/student_profile_pictures/' . $p->student->student_photo)
                                                : asset('assetsDashboard/img/student_profile_pictures/student_default_profile.jpg') }}"
                                                alt="Student Photo" class="rounded-circle me-2"
                                                style="width: 40px; height: 40px; object-fit: cover;">
                                            <div>
                                                {{ optional($p->student)->student_lName }},
                                                {{ optional($p->student)->student_fName }}
                                                {{ optional($p->student)->student_mName }}
                                                {{ optional($p->student)->student_extName }}
                                            </div>
                                        </div>
                                    </a>
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

                            {{-- Modal for editing this student's payment --}}
                            <div class="modal fade" id="editPaymentModal{{ $p->id }}" tabindex="-1"
                                aria-labelledby="editPaymentModalLabel{{ $p->id }}" aria-hidden="true">
                                <div class="modal-dialog">
                                    <form method="POST" action="{{ route('teacher.payments.update', $p->id) }}">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-content">
                                            <div class="modal-header d-flex align-items-center">
                                                <h5 class="modal-title" id="editPaymentModalLabel{{ $p->id }}">
                                                    Edit Payment for {{ optional($p->student)->full_name }}
                                                </h5>
                                                <img src="{{ $p->student && $p->student->student_photo
                                                    ? asset('assetsDashboard/img/student_profile_pictures/' . $p->student->student_photo)
                                                    : asset('assetsDashboard/img/student_profile_pictures/student_default_profile.jpg') }}"
                                                    alt="Student Photo" class="rounded-circle ms-3"
                                                    style="width: 80px; height: 80px; object-fit: cover;">
                                                <button type="button" class="btn-close ms-auto" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label>Amount Paid</label>
                                                    <input type="number" step="0.01" name="amount_paid"
                                                        class="form-control" value="{{ $p->amount_paid }}">
                                                    <small class="text-muted">Status and Date Paid will update
                                                        automatically.</small>
                                                </div>

                                                <div class="mb-3">
                                                    <label>Remarks</label>
                                                    <textarea name="remarks" class="form-control">{{ $p->remarks }}</textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" class="btn btn-primary">Save Changes</button>
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Cancel</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- FEMALE STUDENTS --}}
            <div class="table-responsive">
                <table class="table table-hover table-bordered align-middle">
                    <thead class="table-danger">
                        <tr class="text-center">
                            <th style="width: 40px;">No.</th>
                            <th>Female || Name</th>
                            <th>Status</th>
                            <th>Amount Paid</th>
                            <th>Amount Due</th>
                            <th>Date Paid</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $femaleIndex = 1; @endphp
                        @foreach ($payments->filter(fn($p) => optional($p->student)->gender === 'female')->sortBy(fn($p) => strtolower(optional($p->student)->student_lName . ' ' . optional($p->student)->student_fName . ' ' . optional($p->student)->student_mName)) as $p)
                            <tr class="{{ $p->status === 'paid' ? 'table-success' : '' }}">
                                <td class="text-center">{{ $femaleIndex++ }}</td>
                                <td>
                                    <a href="#" data-bs-toggle="modal"
                                        data-bs-target="#editPaymentModal{{ $p->id }}">
                                        <div class="d-flex align-items-center">
                                            <img src="{{ $p->student && $p->student->student_photo
                                                ? asset('assetsDashboard/img/student_profile_pictures/' . $p->student->student_photo)
                                                : asset('assetsDashboard/img/student_profile_pictures/student_default_profile.jpg') }}"
                                                alt="Student Photo" class="rounded-circle me-2"
                                                style="width: 40px; height: 40px; object-fit: cover;">
                                            <div>
                                                {{ optional($p->student)->student_lName }},
                                                {{ optional($p->student)->student_fName }}
                                                {{ optional($p->student)->student_mName }}
                                                {{ optional($p->student)->student_extName }}
                                            </div>
                                        </div>
                                    </a>
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

                            {{-- Modal --}}
                            <div class="modal fade" id="editPaymentModal{{ $p->id }}" tabindex="-1"
                                aria-labelledby="editPaymentModalLabel{{ $p->id }}" aria-hidden="true">
                                <div class="modal-dialog">
                                    <form method="POST" action="{{ route('teacher.payments.update', $p->id) }}">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-content">
                                            <div class="modal-header d-flex align-items-center">
                                                <h5 class="modal-title" id="editPaymentModalLabel{{ $p->id }}">
                                                    Edit Payment for {{ optional($p->student)->full_name }}
                                                </h5>
                                                <img src="{{ $p->student && $p->student->student_photo
                                                    ? asset('assetsDashboard/img/student_profile_pictures/' . $p->student->student_photo)
                                                    : asset('assetsDashboard/img/student_profile_pictures/student_default_profile.jpg') }}"
                                                    alt="Student Photo" class="rounded-circle ms-3"
                                                    style="width: 80px; height: 80px; object-fit: cover;">
                                                <button type="button" class="btn-close ms-auto" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label>Amount Paid</label>
                                                    <input type="number" step="0.01" name="amount_paid"
                                                        class="form-control" value="{{ $p->amount_paid }}">
                                                    <small class="text-muted">Status and Date Paid will update
                                                        automatically.</small>
                                                </div>

                                                <div class="mb-3">
                                                    <label>Remarks</label>
                                                    <textarea name="remarks" class="form-control">{{ $p->remarks }}</textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" class="btn btn-primary">Save Changes</button>
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Cancel</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>
    </div>
    <!-- / Content wrapper -->
@endsection
