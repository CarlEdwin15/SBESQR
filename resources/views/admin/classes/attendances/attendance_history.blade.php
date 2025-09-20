@extends('./layouts.main')

@section('title', 'Admin | ' . ucfirst(str_replace('_', ' ', $class->grade_level)) . ' - ' . $class->section)

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
            <li class="menu-item active open">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons bx bx-objects-horizontal-left"></i>
                    <div>Classes</div>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item active">
                        <a href="{{ route('all.classes') }}" class="menu-link bg-dark text-light">
                            <div class="text-warning">All Classes</div>
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
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <h4 class="fw-bold text-warning mb-0">
                    <span class="text-muted fw-light">
                        <a class="text-muted fw-light" href="{{ route('home') }}">Dashboard</a> /
                        <a class="text-muted fw-light" href="{{ route('all.classes') }}">Classes</a> /
                        <a class="text-muted fw-light"
                            href="{{ route('classes.showClass', ['grade_level' => $class->grade_level, 'section' => $class->section]) }}?school_year={{ $selectedYear }}">
                            {{ ucfirst($class->grade_level) }} - {{ $class->section }} </a> /
                    </span>
                    Attendance History
                </h4>
            </div>
        </div>

        <h2 class="card-title mb-5 fw-bold text-primary text-center">
            Attendance History {{ ucfirst($class->grade_level) }} - {{ $class->section }}
            ({{ $selectedYear }})
        </h2>

        <div class="d-flex justify-content-between align-items-center mb-2 mt-2">
            <a href="{{ route('classes.attendance.records', ['grade_level' => $class->grade_level, 'section' => $class->section, 'school_year' => $selectedYear]) }}"
                class="btn btn-danger d-flex align-items-center gap-2">
                <i class='bx bx-chevrons-left'></i>
                <span class="d-none d-sm-block">Back</span>
            </a>

            {{-- Date Selection Form --}}
            <form id="dateForm" method="GET">
                <input type="hidden" name="school_year" value="{{ $selectedYear }}">
                <div class="d-flex gap-2 align-items-end">
                    <div>
                        <input type="date" id="date" name="date" class="form-control"
                            value="{{ $targetDate }}">
                    </div>
                    <button class="btn btn-primary me-2 d-flex align-items-center gap-2" type="submit">
                        <i class='bx bx-filter'></i>
                        <span class="d-none d-sm-block">Filter</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- Attendance History -->
        <div class="accordion mt-4" id="attendanceAccordion">
            @php
                // Find the nearest schedule to current time
                $now = \Carbon\Carbon::now();
                $nearestIndex = null;
                $minDiff = null;
                foreach ($schedules as $idx => $schedule) {
                    $start = \Carbon\Carbon::parse($schedule->start_time);
                    $end = \Carbon\Carbon::parse($schedule->end_time);

                    // If today is not the same as $targetDate, use $targetDate for comparison
                    $dateToUse = \Carbon\Carbon::parse($targetDate)->toDateString();
                    $start = $start->copy()->setDateFrom(\Carbon\Carbon::parse($dateToUse));
                    $end = $end->copy()->setDateFrom(\Carbon\Carbon::parse($dateToUse));
                    // If now is between start and end, diff is 0
                    if ($now->between($start, $end)) {
                        $nearestIndex = $idx;
                        break;
                    }
                    // Otherwise, find the minimum absolute diff
                    $diff = min(abs($now->diffInSeconds($start, false)), abs($now->diffInSeconds($end, false)));
                    if (is_null($minDiff) || $diff < $minDiff) {
                        $minDiff = $diff;
                        $nearestIndex = $idx;
                    }
                }
            @endphp
            @forelse ($schedules as $index => $schedule)
                @php
                    $collapseId = 'scheduleCollapse' . $schedule->id;
                    $headingId = 'heading' . $schedule->id;
                    $isOpen = $index === $nearestIndex;
                @endphp

                <div class="accordion-item card mb-2">
                    <h2 class="accordion-header" id="{{ $headingId }}">
                        <button class="accordion-button{{ $isOpen ? '' : ' collapsed' }}" type="button"
                            data-bs-toggle="collapse" data-bs-target="#{{ $collapseId }}"
                            aria-expanded="{{ $isOpen ? 'true' : 'false' }}" aria-controls="{{ $collapseId }}">
                            {{ $schedule->subject_name }} | {{ $schedule->day }}
                            ({{ \Carbon\Carbon::parse($schedule->start_time)->format('g:i A') }} -
                            {{ \Carbon\Carbon::parse($schedule->end_time)->format('g:i A') }})
                            -
                            {{ \Carbon\Carbon::parse($targetDate)->format('F j, Y') }}
                        </button>
                    </h2>

                    <div id="{{ $collapseId }}" class="accordion-collapse collapse{{ $isOpen ? ' show' : '' }}"
                        aria-labelledby="{{ $headingId }}" data-bs-parent="#attendanceAccordion">

                        <h2 class="text-warning text-center fw-bold">{{ $schedule->subject_name }}</h2>
                        <div class="accordion-body">

                            <input type="hidden" name="school_year" value="{{ $selectedYear }}">
                            <input type="hidden" name="schedule_id" value="{{ $schedule->id }}">
                            <input type="hidden" name="class_id" value="{{ $class->id }}">
                            <input type="hidden" name="teacher_id" value="{{ auth()->id() }}">
                            <input type="hidden" name="date" value="{{ $targetDate }}">


                            {{-- MALE STUDENTS --}}
                            <div class="table-responsive mb-4">
                                <table class="table table-hover table-bordered align-middle">
                                    <thead class="table-info">
                                        <tr class="text-center">
                                            <th style="width: 40px; text-align: center;">No.</th>
                                            <th style="text-align: center;">Male || Name</th>
                                            <th style="width: 160px; text-align: center;">Status</th>
                                            <th style="width: 120px; text-align: center;">Time In</th>
                                            <th style="width: 120px; text-align: center;">Time Out</th>
                                            <th style="width: 150px; text-align: center;">Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php $maleIndex = 1; @endphp
                                        @foreach ($students->where('gender', 'Male')->sortBy(fn($s) => strtolower($s->student_lName . ' ' . $s->student_fName . ' ' . $s->student_mName)) as $student)
                                            @php
                                                $existing = $attendancesGrouped[$schedule->id][$student->id] ?? null;
                                                $status = $existing->status ?? null;
                                                $badgeClass = match ($status) {
                                                    'present' => 'bg-label-success',
                                                    'late' => 'bg-label-warning',
                                                    'absent' => 'bg-label-danger',
                                                    'excused' => 'bg-label-dark',
                                                    default => 'bg-label-secondary',
                                                };
                                                $badgeLabel = match ($status) {
                                                    'present', 'late', 'absent', 'excused' => ucfirst($status),
                                                    default => 'No record',
                                                };
                                            @endphp
                                            <tr>
                                                <td class="text-center">{{ $maleIndex++ }}</td>
                                                <td>{{ $student->student_lName }},
                                                    {{ $student->student_fName }}
                                                    {{ $student->student_mName }}
                                                    {{ $student->student_extName }}</td>
                                                <td>
                                                    <div class="d-flex justify-content-center align-items-center gap-2">
                                                        <span
                                                            class="badge {{ $badgeClass }}">{{ $badgeLabel }}</span>
                                                    </div>
                                                </td>
                                                <td class="text-center">
                                                    {{ $existing?->time_in ? \Carbon\Carbon::parse($existing->time_in)->format('g:i A') : '-' }}
                                                </td>
                                                <td class="text-center">
                                                    {{ $existing?->time_out ? \Carbon\Carbon::parse($existing->time_out)->format('g:i A') : '-' }}
                                                </td>

                                                <td class="text-center">
                                                    {{ \Carbon\Carbon::parse($targetDate)->format('F j, Y') }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            {{-- FEMALE STUDENTS --}}
                            <div class="table-responsive">
                                <table class="table table-hover table-bordered align-middle">
                                    <thead class="table-danger">
                                        <tr class="text-center">
                                            <th style="width: 40px; text-align: center;">No.</th>
                                            <th style="text-align: center;">Female || Name</th>
                                            <th style="width: 160px; text-align: center;">Status</th>
                                            <th style="width: 120px; text-align: center;">Time In</th>
                                            <th style="width: 120px; text-align: center;">Time Out</th>
                                            <th style="width: 150px; text-align: center;">Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php $femaleIndex = 1; @endphp
                                        @foreach ($students->where('gender', 'Female')->sortBy(fn($s) => strtolower($s->student_lName . ' ' . $s->student_fName . ' ' . $s->student_mName)) as $student)
                                            @php
                                                $existing = $attendancesGrouped[$schedule->id][$student->id] ?? null;
                                                $status = $existing->status ?? null;
                                                $badgeClass = match ($status) {
                                                    'present' => 'bg-label-success',
                                                    'late' => 'bg-label-warning',
                                                    'absent' => 'bg-label-danger',
                                                    'excused' => 'bg-label-dark',
                                                    default => 'bg-label-secondary',
                                                };
                                                $badgeLabel = match ($status) {
                                                    'present', 'late', 'absent', 'excused' => ucfirst($status),
                                                    default => 'No record',
                                                };
                                            @endphp
                                            <tr>
                                                <td class="text-center">{{ $femaleIndex++ }}</td>
                                                <td>{{ $student->student_lName }},
                                                    {{ $student->student_fName }}
                                                    {{ $student->student_mName }}</td>
                                                <td>
                                                    <div class="d-flex justify-content-center align-items-center gap-2">
                                                        <span
                                                            class="badge {{ $badgeClass }}">{{ $badgeLabel }}</span>
                                                    </div>
                                                </td>
                                                <td class="text-center">
                                                    {{ $existing?->time_in ? \Carbon\Carbon::parse($existing->time_in)->format('g:i A') : '-' }}
                                                </td>
                                                <td class="text-center">
                                                    {{ $existing?->time_out ? \Carbon\Carbon::parse($existing->time_out)->format('g:i A') : '-' }}
                                                </td>
                                                <td class="text-center">
                                                    {{ \Carbon\Carbon::parse($targetDate)->format('F j, Y') }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-info text-center mb-0">
                        No schedules for this date
                        ({{ \Carbon\Carbon::parse($targetDate)->format('l, F j, Y') }})
                    </div>
                </div>
            @endforelse
        </div>
        <!-- /Attendance History -->

    </div>
    <!-- Content wrapper -->

@endsection

@push('scripts')
    <script>
        // search bar
        document.getElementById('studentSearch').addEventListener('keyup', function() {
            const searchValue = this.value.toLowerCase();
            const rows = document.querySelectorAll('#studentTable tbody .student-row');

            rows.forEach(row => {
                const rowText = row.innerText.toLowerCase();
                row.style.display = rowText.includes(searchValue) ? '' : 'none';
            });
        });
    </script>

    <script>
        // delete button alert
        function confirmDelete(student_id, student_fName, student_lName) {
            Swal.fire({
                title: `Delete ${student_fName} ${student_lName}'s record?`,
                text: "This action cannot be undone.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#6c757d",
                confirmButtonText: "Yes, delete it!",
                cancelButtonText: "Cancel",
                customClass: {
                    container: 'my-swal-container'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: "Deleting...",
                        text: "Please wait while we remove the record.",
                        icon: "info",
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        customClass: {
                            container: 'my-swal-container'
                        },
                        didOpen: () => {
                            setTimeout(() => {
                                document.getElementById('delete-form-' + student_id).submit();
                            }, 1000);
                        }
                    });
                }
            });
        }
    </script>

    <script>
        // alert after a success edit or delete of teacher's info
        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: '{{ session('success') }}',
                confirmButtonColor: '#3085d6',
                customClass: {
                    container: 'my-swal-container'
                }
            });
        @endif
    </script>

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
        // alert for upload and preview profile in registration
        const uploadInput = document.getElementById('upload');
        const previewImg = document.getElementById('photo-preview');
        const resetBtn = document.getElementById('reset-photo');
        const defaultImage = "{{ asset('assetsDashboard/img/student_profile_pictures/student_default_profile.jpg') }}";

        uploadInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImg.src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        });

        resetBtn.addEventListener('click', function() {
            uploadInput.value = '';
            previewImg.src = defaultImage;
        });
    </script>

    <script>
        // Date selection form submission
        document.getElementById('dateForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const selectedDate = document.getElementById('date').value;
            const schoolYear = document.querySelector('input[name="school_year"]').value;

            const baseUrl = "{{ url('attendance-history/' . $class->grade_level . '/' . $class->section) }}";
            const finalUrl = selectedDate ?
                `${baseUrl}/${selectedDate}?school_year=${encodeURIComponent(schoolYear)}` :
                `${baseUrl}?school_year=${encodeURIComponent(schoolYear)}`;

            window.location.href = finalUrl;
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
@endpush
