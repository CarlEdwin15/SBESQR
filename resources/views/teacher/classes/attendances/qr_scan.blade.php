@extends('layouts.main')

@section('title', 'Teacher | Scan Attendance')

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
                            href="{{ route('teacher.myClass', ['grade_level' => $grade_level, 'section' => $section, 'school_year' => $selectedYear]) }}">
                            {{ ucfirst($grade_level) }} - {{ $section }} ({{ $selectedYear }}) </a> /
                    </span>
                    Scan Attendance
                </h4>
            </div>
        </div>

        @php
            $carbonDate = \Carbon\Carbon::parse($date);
            $day = $carbonDate->format('D'); // Mon, Tue, etc.
            $isToday = $carbonDate->isToday();

            $startTime = \Carbon\Carbon::parse($schedule->start_time)->format('H:i');
            $endTime = \Carbon\Carbon::parse($schedule->end_time)->format('H:i');
        @endphp

        <div class="d-flex justify-content-between align-items-end mb-3">
            <!-- Back Button -->
            <a href="{{ route('teacher.attendanceHistory', ['grade_level' => $class->grade_level, 'section' => $class->section]) }}?school_year={{ $selectedYear }}&date={{ $carbonDate->format('Y-m-d') }}"
                class="btn btn-danger d-flex align-items-center gap-2">
                <i class='bx bx-chevrons-left'></i>
                <span class="d-none d-sm-block">Back</span>
            </a>

            <!-- Time-Out Group -->
            <div class="d-flex align-items-center flex-wrap gap-2">
                <label for="custom-timeout" class="fw-bold text-muted mb-0 d-none d-sm-block">Select
                    Time-Out:</label>
                <input type="time" id="custom-timeout" class="form-control" style="width: 130px;"
                    value="{{ $endTime }}" data-start="{{ $startTime }}" data-end="{{ $endTime }}">
                <button class="btn btn-primary d-flex align-items-center gap-2" onclick="setCustomTimeout()">
                    <i class='bx bx-list-check'></i>
                    <span class="d-none d-sm-block">Apply</span>
                </button>
            </div>
        </div>

        {{-- Card --}}
        <div class="card">
            <div class="card-header">
                <h4 class="fw-bold mb-0 text-center text-warning">{{ $schedule->subject_name }} <span
                        class="text-muted">|</span>
                    <span class="text-info">{{ ucfirst($grade_level) }} - {{ $section }} </span>
                </h4>
            </div>

            <div class="container my-4">
                <div class="row">
                    <!-- Left: Class Info -->
                    <div class="col-md-6 mb-3">

                        <div class="card text-center p-3">

                            <h5 class="fw-bold mb-3 text-muted">📅
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

                        </div>

                        <div class="mt-3 text-center">
                            <h5 class="text-muted mb-0">
                                @if ($gracePeriod === -1)
                                    @php
                                        $endTimeFormatted = \Carbon\Carbon::parse($schedule->end_time)->format('g:i A');
                                    @endphp
                                    <p><strong class="text-danger">Note:</strong> <span class="text-warning">Students can
                                            only be
                                            marked as <span class="text-success">present</span> within
                                            <strong class="text-success">{{ $endTimeFormatted }}</strong></span>
                                    </p>
                                @elseif ($gracePeriod === 0)
                                    @php
                                        $startTimeFormatted = \Carbon\Carbon::parse($schedule->start_time)->format(
                                            'g:i A',
                                        );
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
                        <div id="qr-result" class="mb-2 text-success fw-bold"></div>
                        <div class="card p-3">
                            <h6 class="fw-bold">LIST OF STUDENTS</h6>
                            <div class="table-responsive">
                                <table class="table align-middle table-hover table-bordered">
                                    <thead>
                                        <tr>
                                            <th class="text-center">Name</th>
                                            <th class="text-center">Photo</th>
                                            <th class="text-center">Status</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($students->sortBy('full_name')->values() as $student)
                                            @php
                                                $att = $student->attendances->firstWhere('date', $date);
                                                $status = $att->status ?? null;

                                                $rowClass = match ($status) {
                                                    'present' => 'table-success',
                                                    'late' => 'table-warning',
                                                    'absent' => 'table-danger',
                                                    'excused' => 'table-secondary',
                                                    default => '',
                                                };

                                                $badgeClass = match ($status) {
                                                    'present' => 'bg-success',
                                                    'late' => 'bg-warning',
                                                    'absent' => 'bg-danger',
                                                    'excused' => 'bg-dark',
                                                    default => 'bg-secondary',
                                                };
                                            @endphp
                                            <tr data-student-id="{{ $student->id }}" class="{{ $rowClass }}">
                                                <td>
                                                    <span
                                                        class="fw-bold text-primary">{{ $student->student_lName }}</span>,
                                                    {{ $student->student_fName }}
                                                    {{ $student->student_mName }}
                                                    {{ $student->student_extName }}
                                                    <span><i class="{{ $student->sex_icon }}"></i></span>
                                                </td>

                                                <td class="text-center">
                                                    <img src="{{ $student->student_photo ? asset('storage/' . $student->student_photo) : asset('assetsDashboard/img/student_profile_pictures/student_default_profile.jpg') }}"
                                                        alt="Student Photo" class="rounded-circle me-2 student-photo"
                                                        style="width: 40px; height: 40px;">
                                                </td>

                                                <td class="text-center">
                                                    {{-- Display attendance status --}}
                                                    <span class="badge status-badge {{ $badgeClass }}">
                                                        {{ ucfirst($status ?? '-') }}
                                                    </span>
                                                </td>

                                                <td class="text-center">
                                                    {{-- Dropdown for manual attendance --}}
                                                    <div class="dropdown">
                                                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                                            data-bs-toggle="dropdown">
                                                            <i class="bx bx-dots-vertical-rounded"></i>
                                                        </button>
                                                        <div class="dropdown-menu">
                                                            <button type="button"
                                                                class="dropdown-item text-success attendance-btn"
                                                                data-status="present"
                                                                data-student-id="{{ $student->id }}">
                                                                <i class="bx bx-user-check me-1"></i> Present
                                                            </button>
                                                            <button type="button"
                                                                class="dropdown-item text-warning attendance-btn"
                                                                data-status="late" data-student-id="{{ $student->id }}">
                                                                <i class="bx bx-alarm me-1"></i> Late
                                                            </button>
                                                            <button type="button"
                                                                class="dropdown-item text-danger attendance-btn"
                                                                data-status="absent"
                                                                data-student-id="{{ $student->id }}">
                                                                <i class="bx bx-user-x me-1"></i> Absent
                                                            </button>
                                                            <button type="button"
                                                                class="dropdown-item text-secondary attendance-btn"
                                                                data-status="excused"
                                                                data-student-id="{{ $student->id }}">
                                                                <i class="bx bx-calendar-x me-1"></i> Excused
                                                            </button>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!-- /Right: Student List -->


                    <audio id="success-sound-1" src="{{ asset('sounds/attendance_present.mp3') }}"
                        preload="auto"></audio>

                    <audio id="success-sound-2" src="{{ asset('sounds/attendance_present1.m4a') }}"
                        preload="auto"></audio>

                    <audio id="error-sound" src="{{ asset('sounds/attendance_invalid.m4a') }}" preload="auto"></audio>

                </div>
            </div>
        </div>
        {{-- /Card --}}


        <!-- Floating Attendance Notification -->
        <div id="attendance-popup" class="position-fixed top-0 start-50 translate-middle-x d-none"
            style="margin-top: 20px; z-index: 9999;">
            <div class="alert alert-success shadow-lg d-flex align-items-center justify-content-between gap-3 px-4 py-3 rounded-3 border-0"
                id="popup-content" style="min-width: 320px;">
                <div class="d-flex align-items-center gap-3">
                    <div id="popup-icon" class="fs-3">✅</div>
                    <div>
                        <h6 class="mb-0 fw-bold" id="popup-name">John Doe</h6>
                        <small id="popup-status">Marked as Present</small>
                    </div>
                </div>
            </div>
        </div>


        <hr class="my-5" />
    </div>
    <!-- Content wrapper -->

@endsection

@push('scripts')
    <script src="{{ asset('js/qr/instascan.min.js') }}"></script>

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

    <script>
        // Initialize QR scanner
        const scheduleId = @json($schedule_id);
        const grade_level = @json($grade_level);
        const section = @json($section);
        const date = @json($date);
        const grace = {{ $gracePeriod ?? 60 }}; // Set default grace period to 60 minutes if not set

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

            const customTimeout = document.getElementById('custom-timeout').value;

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
                            grade_level: grade_level,
                            section: section,
                            date: date,
                            schedule_id: scheduleId,
                            grace: grace,
                            custom_timeout: customTimeout
                        })
                    })
                    .then(res => res.json())
                    .then(res => {
                        if (res.success) {
                            document.getElementById('success-sound-1').play();
                            document.getElementById('success-sound-2').play();

                            showPopup(res.student, res.status);

                            const row = document.querySelector(`tr[data-student-id="${res.student_id}"]`);
                            if (row) {
                                row.classList.remove('table-success', 'table-warning', 'table-danger',
                                    'table-secondary', 'table-info');
                                row.classList.add(getRowClass(res.status));

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
                            document.getElementById('error-sound').play(); // 🔊 Play error sound
                            flashErrorBorder(); // ✨ Optional visual feedback

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
                        document.getElementById('error-sound').play();
                        flashErrorBorder();

                        qrResult.classList.remove('text-success');
                        qrResult.classList.add('text-danger');
                        qrResult.innerText = '❌ Network error.';
                        scanning = false;
                    });

            } catch (e) {
                document.getElementById('error-sound').play();
                flashErrorBorder();

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

        // Optional: Add a flash effect to video on error
        function flashErrorBorder() {
            const video = document.getElementById('preview');
            video.classList.add('qr-error');
            setTimeout(() => {
                video.classList.remove('qr-error');
            }, 600);
        }

        function getStatusClass(status) {
            switch (status) {
                case 'present':
                    return 'bg-success';
                case 'absent':
                    return 'bg-danger';
                case 'late':
                    return 'bg-warning';
                case 'excused':
                    return 'bg-dark';
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
                    return 'table-secondary';
                default:
                    return 'table-secondary';
            }
        }

        let popupTimeout; // Global timeout tracker

        // Function to show the floating popup
        function showPopup(studentName, status) {
            const popup = document.getElementById('attendance-popup');
            const content = document.getElementById('popup-content');
            const name = document.getElementById('popup-name');
            const statusText = document.getElementById('popup-status');
            const icon = document.getElementById('popup-icon');

            name.textContent = studentName;
            statusText.textContent = `Marked as ${capitalize(status)}`;

            // 🎨 Change color based on status
            let colorClass = 'alert-success';
            let emoji = '✅';
            if (status === 'late') {
                colorClass = 'alert-warning';
                emoji = '⏰';
            } else if (status === 'absent') {
                colorClass = 'alert-danger';
                emoji = '❌';
            } else if (status === 'excused') {
                colorClass = 'alert-dark';
                emoji = '📘';
            }

            content.className =
                `alert ${colorClass} shadow-lg d-flex align-items-center justify-content-between gap-3 px-4 py-3 rounded-3 border-0`;
            icon.textContent = emoji;

            popup.classList.remove('d-none');
            popup.style.opacity = 1;

            // Clear any previous timeout so new notifications reset the timer
            if (popupTimeout) {
                clearTimeout(popupTimeout);
            }

            // Set a new timeout to hide the popup after 3.5s of inactivity
            popupTimeout = setTimeout(() => {
                popup.style.opacity = 0;
                setTimeout(() => popup.classList.add('d-none'), 600);
            }, 3500);
        }

        // Function to set custom timeout
        let customTimeout = document.getElementById('custom-timeout').value;

        // Function to apply custom timeout
        function setCustomTimeout() {
            customTimeout = document.getElementById('custom-timeout').value;

            function formatTime(timeStr) {
                if (!timeStr) return '';
                const [hour, minute] = timeStr.split(':');
                let h = parseInt(hour, 10);
                const m = minute;
                const ampm = h >= 12 ? 'PM' : 'AM';
                h = h % 12;
                if (h === 0) h = 12;
                return `${h}:${m} ${ampm}`;
            }

            Swal.fire({
                icon: 'success',
                title: 'Time-Out Applied!',
                text: 'Custom time-out set to: ' + formatTime(customTimeout),
                timer: 2500,
                showConfirmButton: false,
                customClass: {
                    container: 'my-swal-container'
                }
            });
        }
    </script>

    <script>
        // Handle manual attendance button clicks with SweetAlert confirmation
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('.attendance-btn').forEach(button => {
                button.addEventListener('click', () => {
                    const studentId = button.getAttribute('data-student-id');
                    const status = button.getAttribute('data-status');
                    const statusLabel = capitalize(status);

                    // Determine SweetAlert confirm button color based on status
                    let confirmButtonColor = '#3085d6'; // default
                    switch (status) {
                        case 'present':
                            confirmButtonColor = '#28a745'; // Bootstrap success
                            break;
                        case 'late':
                            confirmButtonColor = '#ffc107'; // Bootstrap warning
                            break;
                        case 'absent':
                            confirmButtonColor = '#dc3545'; // Bootstrap danger
                            break;
                        case 'excused':
                            confirmButtonColor = '#6c757d'; // Bootstrap secondary
                            break;
                    }

                    // Show SweetAlert confirmation
                    Swal.fire({
                        title: `Mark as ${statusLabel}?`,
                        text: "Are you sure you want to update the student's attendance?",
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: confirmButtonColor,
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Yes, mark it!',
                        cancelButtonText: 'Cancel',
                        customClass: {
                            container: 'my-swal-container'
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Proceed to mark attendance
                            fetch('{{ route('teacher.markManualAttendance') }}', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                    },
                                    body: JSON.stringify({
                                        student_id: studentId,
                                        status: status,
                                        date: date,
                                        schedule_id: scheduleId,
                                        custom_timeout: document.getElementById(
                                            'custom-timeout')?.value ?? '',
                                        school_year: @json($selectedYear) // ✅ Add this line (Blade will render the actual year)
                                    })
                                })
                                .then(res => res.json())
                                .then(res => {
                                    if (res.success) {
                                        const row = document.querySelector(
                                            `tr[data-student-id="${res.student_id}"]`
                                        );
                                        if (row) {
                                            row.classList.remove('table-success',
                                                'table-warning', 'table-danger',
                                                'table-info', 'table-secondary');
                                            row.classList.add(getRowClass(res.status));

                                            const badge = row.querySelector(
                                                '.status-badge');
                                            if (badge) {
                                                badge.textContent = capitalize(res
                                                    .status);
                                                badge.className =
                                                    'badge status-badge ' +
                                                    getStatusClass(res.status);
                                            }
                                        }

                                        showPopup(res.student, res.status);
                                        document.getElementById('success-sound-1')
                                            .play();
                                        document.getElementById('success-sound-2')
                                            .play();
                                    } else {
                                        Swal.fire('Error', res.message, 'error');
                                    }
                                })
                                .catch(() => Swal.fire('Network Error',
                                    'Failed to update attendance.', 'error'));
                        }
                    });
                });
            });

            // Utility function: capitalize string
            function capitalize(str) {
                return str.charAt(0).toUpperCase() + str.slice(1);
            }
        });
    </script>
@endpush

@push('styles')
    <style>
        .qr-error {
            border: 3px solid red;
            animation: flash 0.7s ease-in-out 2;
        }

        @keyframes flash {

            0%,
            100% {
                border-color: transparent;
            }

            50% {
                border-color: red;
            }
        }
    </style>
@endpush
