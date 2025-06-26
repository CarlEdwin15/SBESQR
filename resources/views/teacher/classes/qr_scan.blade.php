@extends('layouts.main')

@section('title', 'Teacher | Scan Attendance')

@section('content')
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">

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
                                <a href="" class="menu-link bg-dark text-light">
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
                                    <div class="text-danger">My Classes</div>
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

            <!-- Layout container -->
            <div class="layout-page">

                <!-- Navbar -->
                <nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme"
                    id="layout-navbar">
                    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
                        <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
                            <i class="bx bx-menu bx-sm"></i>
                        </a>
                    </div>

                    <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">

                        <ul class="navbar-nav flex-row align-items-center ms-auto">
                            <!-- User -->
                            <li class="nav-item navbar-dropdown dropdown-user dropdown">
                                <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);"
                                    data-bs-toggle="dropdown">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar">
                                            @auth
                                                @php
                                                    $profilePhoto = Auth::user()->profile_photo
                                                        ? asset('storage/' . Auth::user()->profile_photo)
                                                        : asset(
                                                            'assetsDashboard/img/profile_pictures/teachers_default_profile.jpg',
                                                        );
                                                @endphp
                                                <img src="{{ $profilePhoto }}" alt="Profile Photo"
                                                    class="w-px-40 h-auto rounded-circle" />
                                            @else
                                                <img src="{{ asset('assetsDashboard/img/profile_pictures/teachers_default_profile.jpg') }}"
                                                    alt="Default Profile Photo" class="w-px-40 h-auto rounded-circle" />
                                            @endauth
                                        </div>
                                        @auth
                                            <span class="fw-semibold ms-2">{{ Auth::user()->firstName }}</span>
                                        @endauth
                                    </div>
                                </a>

                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <a class="dropdown-item" href="#">
                                            <div class="d-flex align-items-center">
                                                <div class="avatar">
                                                    @auth
                                                        @php
                                                            $profilePhoto = Auth::user()->profile_photo
                                                                ? asset('storage/' . Auth::user()->profile_photo)
                                                                : asset(
                                                                    'assetsDashboard/img/profile_pictures/teachers_default_profile.jpg',
                                                                );
                                                        @endphp
                                                        <img src="{{ $profilePhoto }}" alt="Profile Photo"
                                                            class="w-px-40 h-auto rounded-circle" />
                                                    @else
                                                        <img src="{{ asset('assetsDashboard/img/profile_pictures/teachers_default_profile.jpg') }}"
                                                            alt="Default Profile Photo"
                                                            class="w-px-40 h-auto rounded-circle" />
                                                    @endauth
                                                </div>
                                                @auth
                                                    <span class="fw-semibold ms-2">{{ Auth::user()->firstName }}</span>
                                                @endauth
                                            </div>

                                        </a>
                                    </li>

                                    <li>
                                        <div class="dropdown-divider"></div>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="#">
                                            <i class="bx bx-user me-2"></i>
                                            <span class="align-middle">My Profile</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('account.settings') }}">
                                            <i class="bx bx-cog me-2"></i>
                                            <span class="align-middle">Settings</span>
                                        </a>
                                    </li>

                                    <li>
                                        <div class="dropdown-divider"></div>
                                    </li>
                                    <form id="logout-form" method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <x-dropdown-link class="dropdown-item" href="{{ route('logout') }}"
                                            onclick="event.preventDefault(); confirmLogout();">
                                            <i class="bx bx-power-off me-2"></i>
                                            {{ __('Log Out') }}
                                        </x-dropdown-link>
                                    </form>

                                </ul>
                            </li>
                            <!--/ User -->
                        </ul>
                    </div>
                </nav>
                <!-- / Navbar -->

                <!-- Content wrapper -->
                <div class="container-xxl flex-grow-1 container-p-y">
                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <div>
                            <h4 class="fw-bold text-warning mb-0">
                                <span class="text-muted fw-light">
                                    <a class="text-muted fw-light" href="{{ route('home') }}">Dashboard</a> /
                                    <a class="text-muted fw-light" href="{{ route('teacher.myClasses') }}">Classes</a> /
                                    <a class="text-muted fw-light"
                                        href="{{ route('teacher.myClass', ['grade_level' => $grade, 'section' => $section]) }}">
                                        {{ ucfirst($grade) }} - {{ $section }} </a> /
                                </span>
                                Scan Attendance
                            </h4>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-end mb-3">
                        <a href="{{ route('teacher.attendanceHistory', ['grade_level' => $class->grade_level, 'section' => $class->section]) }}"
                            class="btn btn-danger">Back</a>
                    </div>

                    {{-- Card --}}
                    <div class="card">
                        <div class="card-header">
                            <h4 class="fw-bold mb-0 text-center text-warning">{{ $schedule->subject_name }} <span
                                    class="text-muted">|</span>
                                <span class="text-info">{{ ucfirst($grade) }} - {{ $section }} </span>
                            </h4>
                        </div>

                        <div class="container my-4">
                            <div class="row">
                                <!-- Left: Class Info -->
                                <div class="col-md-6 mb-3">
                                    <div class="card text-center p-3">

                                        <h5 class="fw-bold mb-0 text-muted">📅
                                            {{ \Carbon\Carbon::parse($date)->format('l, F j, Y') }}</h5>

                                        @if ($schedule)
                                            <h5 class="fw-bold mb-2 text-primary">🕒
                                                {{ \Carbon\Carbon::parse($schedule->start_time)->format('h:i A') }} -
                                                {{ \Carbon\Carbon::parse($schedule->end_time)->format('h:i A') }}</h5>
                                        @else
                                            <h5 class="text-danger fw-bold mb-0">Schedule not found</h5>
                                        @endif

                                        <div class="my-1">
                                            <video id="preview" width="100%" height="370px" autoplay class="rounded"
                                                style="object-fit: cover; object-position: center;" playsinline></video>
                                        </div>

                                        <h5 class="fw-bold text-primary">{{ strtoupper($grade) }} - {{ $section }}
                                        </h5>

                                        <h5 class="text-muted mb-0">
                                            @if ($gracePeriod === -1)
                                                @php
                                                    $endTimeFormatted = \Carbon\Carbon::parse(
                                                        $schedule->end_time,
                                                    )->format('g:i A');
                                                @endphp
                                                <p><strong class="text-danger">Note:</strong> <span
                                                        class="text-warning">Students can only be
                                                        marked as <span class="text-success">present</span> within
                                                        <strong
                                                            class="text-success">{{ $endTimeFormatted }}</strong></span>
                                                </p>
                                            @elseif ($gracePeriod === 0)
                                                @php
                                                    $startTimeFormatted = \Carbon\Carbon::parse(
                                                        $schedule->start_time,
                                                    )->format('g:i A');
                                                @endphp
                                                <p><strong>No grace period</strong> — students must scan by
                                                    <strong>{{ $startTimeFormatted }}</strong>.
                                                </p>
                                            @else
                                                @php
                                                    $lateTime = \Carbon\Carbon::parse($schedule->start_time)
                                                        ->addMinutes($gracePeriod)
                                                        ->format('g:i A');
                                                @endphp
                                                <p><strong>Note:</strong> Marked as <span class="text-warning">LATE</span>
                                                    after <strong>{{ $lateTime }}</strong> ({{ $gracePeriod }} min
                                                    grace).</p>
                                            @endif
                                        </h5>


                                    </div>
                                </div>

                                <!-- Right: Student List -->
                                <div class="col-md-6">
                                    <div class="card p-3">
                                        <h6 class="fw-bold">LIST OF STUDENTS</h6>
                                        <div class="table-responsive">
                                            <table class="table align-middle table-hover table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th class="text-center">Name</th>
                                                        <th class="text-center">Status</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($students->sortBy('full_name') as $student)
                                                        @php
                                                            $att = $student->attendances->firstWhere('date', $date);
                                                            $status = $att->status ?? null;
                                                        @endphp
                                                        <tr data-student-id="{{ $student->id }}"
                                                            @if ($status === 'present') class="table-success"
                                                            @elseif ($status === 'late') class="table-warning"
                                                            @elseif ($status === 'absent') class="table-danger" @endif>
                                                            <td class="text-center">{{ $student->full_name }}</td>
                                                            <td class="text-center">
                                                                {{-- Display attendance status --}}
                                                                <span
                                                                    class="badge status-badge
            @if ($status === 'present') bg-success
            @elseif ($status === 'absent') bg-danger
            @elseif ($status === 'late') bg-warning text-dark
            @else bg-secondary @endif
        ">
                                                                    {{ ucfirst($status ?? '-') }}
                                                                </span>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                        <div id="qr-result" class="mt-2 text-success fw-bold"></div>
                                    </div>
                                </div>
                                <!-- /Right: Student List -->

                                <audio id="success-sound" src="{{ asset('sounds/attendance_present.mp3') }}"
                                    preload="auto"></audio>

                            </div>
                        </div>
                    </div>
                    {{-- /Card --}}


                    <hr class="my-5" />
                </div>
                <!-- Content wrapper -->


            </div>
            <!-- / Layout page -->
        </div>

        <!-- Overlay -->
        <div class="layout-overlay layout-menu-toggle"></div>
    </div>
    <!-- / Layout wrapper -->
@endsection

@push('scripts')
    <script src="{{ asset('js/qr/instascan.min.js') }}"></script>

    <script>
        const scheduleId = @json($schedule_id);
        const grade = @json($grade);
        const section = @json($section);
        const date = @json($date);
        const grace = {{ $gracePeriod ?? 0 }}; // ✅ Passed from controller

        let scanner = new Instascan.Scanner({
            video: document.getElementById('preview'),
            mirror: true
        });

        const qrResult = document.getElementById('qr-result');
        let scanning = false;

        scanner.addListener('scan', function(content) {
            if (scanning) return;
            scanning = true;

            qrResult.innerText = '🔍 Processing scan...';

            try {
                const data = JSON.parse(content);

                fetch('{{ route('teacher.markAttendanceFromQR') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            student_id: data.student_id,
                            grade: grade,
                            section: section,
                            date: date,
                            schedule_id: scheduleId,
                            grace: grace // ✅ Send grace period
                        })
                    })
                    .then(res => res.json())
                    .then(res => {
                        if (res.success) {
                            document.getElementById('success-sound').play(); // 🔔 Play sound on success

                            qrResult.classList.remove('text-danger');
                            qrResult.classList.add('text-success');
                            qrResult.innerText = '✔️ Attendance marked for: ' + res.student;

                            const row = document.querySelector(`tr[data-student-id="${res.student_id}"]`);
                            if (row) {
                                // 🧼 Clean up old table-* classes
                                row.classList.remove('table-success', 'table-warning', 'table-danger',
                                    'table-secondary', 'table-info');

                                // 🆕 Add new row color based on status
                                row.classList.add(getRowClass(res.status));

                                // ✅ Update badge
                                const badge = row.querySelector('.status-badge');
                                if (badge) {
                                    badge.textContent = capitalize(res.status);
                                    badge.className = 'badge status-badge ' + getStatusClass(res.status);
                                }
                            }

                            setTimeout(() => {
                                scanning = false;
                                qrResult.innerText = '';
                            }, 2500);
                        } else {
                            qrResult.classList.remove('text-success');
                            qrResult.classList.add('text-danger');
                            qrResult.innerText = '❌ ' + res.message;

                            setTimeout(() => {
                                scanning = false;
                                qrResult.innerText = '';
                            }, 3000);
                        }
                    })
                    .catch(err => {
                        qrResult.classList.remove('text-success');
                        qrResult.classList.add('text-danger');
                        qrResult.innerText = '❌ Network error.';
                        scanning = false;
                    });

            } catch (e) {
                qrResult.classList.remove('text-success');
                qrResult.classList.add('text-danger');
                qrResult.innerText = '❌ Invalid QR code format.';
                scanning = false;
            }
        });

        Instascan.Camera.getCameras().then(cameras => {
            if (cameras.length > 0) scanner.start(cameras[0]);
            else alert('No cameras found.');
        }).catch(e => {
            console.error(e);
            alert('Camera error: ' + e);
        });

        function getStatusClass(status) {
            switch (status) {
                case 'present':
                    return 'bg-success';
                case 'absent':
                    return 'bg-danger';
                case 'late':
                    return 'bg-warning text-dark';
                case 'excused':
                    return 'bg-info';
                default:
                    return 'bg-secondary';
            }
        }

        function capitalize(str) {
            return str.charAt(0).toUpperCase() + str.slice(1);
        }

        function getRowClass(status) {
            switch (status) {
                case 'present':
                    return 'table-success';
                case 'absent':
                    return 'table-danger';
                case 'late':
                    return 'table-warning';
                case 'excused':
                    return 'table-info';
                default:
                    return 'table-secondary';
            }
        }
    </script>
@endpush
