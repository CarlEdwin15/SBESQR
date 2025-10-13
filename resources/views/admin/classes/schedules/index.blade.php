@extends('./layouts.main')

@section('title', 'Admin | Schedules')

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
                    <div class="text-light">School Fees</div>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item">
                        <a href="{{ route('admin.school-fees.index') }}" class="menu-link bg-dark text-light">
                            <div class="text-light">All School Fees</div>
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
        <h4 class="fw-bold text-warning mb-2">
            <span class="text-muted fw-light">
                <a class="text-muted fw-light" href="{{ route('home') }}">Dashboard</a> /
                <a class="text-muted fw-light" href="{{ route('all.classes') }}">Classes</a> /
                <a class="text-muted fw-light"
                    href="{{ route('classes.showClass', ['grade_level' => $class->grade_level, 'section' => $class->section]) }}">
                    {{ ucfirst($class->grade_level) }} - {{ $class->section }} </a> /
            </span>
            Schedules
        </h4>

        <!-- Modal Backdrop -->
        <div class="col-lg-4 col-md-3">
            <div class="mt-3">
                <div class="d-flex align-items-center gap-2 mb-3 mt-5">
                    <button type="button" class="btn btn-danger d-flex align-items-center gap-1"
                        onclick="handleCancel()">
                        <i class='bx bx-chevrons-left'></i>
                        <span class="d-none d-sm-block">Back</span>
                    </button>
                    <button type="button" class="btn btn-primary d-flex align-items-center gap-1" data-bs-toggle="modal"
                        data-bs-target="#backDropModal">
                        <i class="bx bx-calendar-plus ms-2"></i>
                        <span class="d-none d-sm-block">Add New Schedule</span>
                    </button>
                </div>
                <script>
                    function handleCancel() {
                        window.location.href =
                            "{{ route('classes.showClass', ['grade_level' => $class->grade_level, 'section' => $class->section, 'school_year' => $selectedYear]) }}";
                    }
                </script>

                {{-- Display schedule conflict error --}}
                @if ($errors->has('schedule_conflict'))
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            Swal.fire({
                                icon: 'error',
                                title: 'Schedule Conflict',
                                text: @json($errors->first('schedule_conflict')),
                                confirmButtonText: 'OK',
                                customClass: {
                                    container: 'my-swal-container'
                                }

                            }).then(() => {
                                const modal = new bootstrap.Modal(document.getElementById('backDropModal'));
                                modal.show();
                            });
                        });
                    </script>
                @endif

                <!-- Add Schedule Modal -->
                <div class="modal fade" id="backDropModal" data-bs-backdrop="static" tabindex="-1">
                    <div class="modal-dialog">

                        <form class="modal-content"
                            action="{{ route('classes.addSchedule', ['grade_level' => $class->grade_level, 'section' => $class->section]) }}"
                            method="POST" enctype="multipart/form-data">

                            @csrf
                            <div class="modal-header">
                                <h4 class="modal-title fw-bold text-info" id="backDropModalTitle">
                                    ADD NEW SCHEDULE
                                </h4>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>

                            <div class="modal-body">

                                <input type="hidden" name="school_year" value="{{ $selectedYear }}">

                                <!-- Subject Name -->
                                <div class="mb-3">
                                    <label for="subject_name" class="form-label fw-semibold">Subject
                                        Name</label>
                                    <input type="text" class="form-control" id="subject_name" name="subject_name"
                                        required>
                                </div>

                                <!-- Teacher Selection -->
                                <div class="mb-3">
                                    <label for="teacher_id" class="form-label fw-semibold">Teacher</label>
                                    <select class="form-select" id="teacher_id" name="teacher_id" required>
                                        <option value="" selected disabled>
                                            {{ $teachers->isEmpty() ? 'No teachers available for the Selected School Year' : 'Select Teacher' }}
                                        </option>
                                        @foreach ($teachers as $teacher)
                                            @php
                                                $role = $teacher->pivot->role;
                                                $roleLabel = match ($role) {
                                                    'adviser' => ' (Adviser)',
                                                    'subject_teacher' => ' (Subject Teacher)',
                                                    default => '',
                                                };
                                            @endphp
                                            <option value="{{ $teacher->id }}">
                                                {{ $teacher->firstName }}
                                                {{ $teacher->lastName }}{{ $roleLabel }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>


                                <!-- Day Selection -->
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Day</label>
                                    <div class="d-flex flex-wrap gap-3">
                                        @foreach (['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'] as $day)
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="days[]"
                                                    value="{{ $day }}" id="day_{{ strtolower($day) }}">
                                                <label class="form-check-label"
                                                    for="day_{{ strtolower($day) }}">{{ $day }}</label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <!-- Start and End Time -->
                                <div class="mb-3 row">
                                    <div class="col">
                                        <label for="start_time" class="form-label fw-semibold">Start
                                            Time</label>
                                        <input type="time" class="form-control" id="start_time" name="start_time"
                                            required>
                                    </div>
                                    <div class="col">
                                        <label for="end_time" class="form-label fw-semibold">End Time</label>
                                        <input type="time" class="form-control" id="end_time" name="end_time"
                                            required>
                                    </div>
                                </div>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                    Close
                                </button>
                                <button type="submit" class="btn btn-primary" id="registerTeacherBtn">Add</button>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- /Add Schedule Modal -->

            </div>
        </div>

        {{-- Card --}}
        <div class="card">
            <div class="container my-4">
                <h3 class="text-center mb-4 fw-bold">
                    Schedule for <span class="text-info">{{ ucfirst($class->grade_level) }} -
                        {{ $class->section }} ({{ $selectedYear }})</span>
                </h3>

                <div class="table-responsive">
                    <table class="table text-center table-bordered align-middle">
                        <thead class="table-primary">
                            <tr>
                                <th style="width: 8%;">Time</th>
                                @foreach (['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'] as $day)
                                    <th style="width: 10%;">{{ $day }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                use Carbon\Carbon;

                                $start = Carbon::createFromTime(7, 0);
                                $end = Carbon::createFromTime(18, 0);
                                $step = 30;
                                $rendered = [];
                                $todayName = Carbon::now()->format('l');
                            @endphp

                            @while ($start < $end)
                                @php
                                    $slotStart = $start->copy();
                                    $slotEnd = $start->copy()->addMinutes($step);
                                    $nextSlot = $slotEnd->copy();
                                    $showTime = $slotStart->minute == 0;
                                    $isLunchStart =
                                        $slotStart->format('H:i') >= '12:00' && $slotStart->format('H:i') < '13:00';
                                @endphp

                                <tr
                                    style="height: 40px; @if ($isLunchStart) background-color: #944040; @endif">
                                    @if ($showTime)
                                        <td class="fw-semibold text-nowrap @if ($isLunchStart) text-white @endif"
                                            rowspan="2">
                                            {{ $slotStart->format('g:i A') }} -
                                            {{ $slotStart->copy()->addHour()->format('g:i A') }}
                                        </td>
                                    @endif

                                    @foreach (['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'] as $day)
                                        @php
                                            $key = $day . '-' . $slotStart->format('H:i');
                                            if (!empty($rendered[$key])) {
                                                continue;
                                            }

                                            $cellContent = '';
                                            $rowspan = 1;

                                            foreach ($schedules as $sched) {
                                                $days = is_array($sched->day)
                                                    ? $sched->day
                                                    : json_decode($sched->day, true);
                                                $days = is_array($days) ? $days : [$sched->day];

                                                $schedStart = Carbon::parse($sched->start_time);
                                                $schedEnd = Carbon::parse($sched->end_time);

                                                if (
                                                    in_array($day, $days) &&
                                                    $schedStart < $slotEnd &&
                                                    $schedEnd > $slotStart
                                                ) {
                                                    $modalId = 'editModal' . $sched->id;
                                                    $rowspan = ceil($schedStart->diffInMinutes($schedEnd) / $step);
                                                    $bgColor = $day === $todayName ? '#6ec1e4' : '#ffab00';

                                                    $roleLabel = 'Teacher: ';
                                                    if ($sched->teacher && $sched->teacher->pivot) {
                                                        switch ($sched->teacher->pivot->role ?? '') {
                                                            case 'adviser':
                                                                $roleLabel = 'Adviser: ';
                                                                break;
                                                            case 'subject_teacher':
                                                                $roleLabel = 'Subject Teacher: ';
                                                                break;
                                                        }
                                                    }

                                                    $cellContent =
                                                        '
                                                                    <div class="w-100 h-100 d-flex flex-column justify-content-center align-items-center text-center text-white fw-semibold hoverable-schedule-cell"
                                                                        style="background-color:' .
                                                        $bgColor .
                                                        '; padding: 10px 5px;"
                                                                        data-bs-toggle="modal" data-bs-target="#viewModal' .
                                                        $sched->id .
                                                        '">
                                                                        <div style="font-size:25px; margin-bottom:50px">' .
                                                        $sched->subject_name .
                                                        '</div>
                                                                        <div style="margin-bottom:5px">' .
                                                        ($sched->teacher
                                                            ? $roleLabel .
                                                                $sched->teacher->firstName .
                                                                ' ' .
                                                                $sched->teacher->lastName
                                                            : '<span class="text-muted">Teacher: N/A</span>') .
                                                        '
                                                                        </div>
                                                                        <div>' .
                                                        \Carbon\Carbon::parse($sched->start_time)->format('g:i A') .
                                                        ' - ' .
                                                        \Carbon\Carbon::parse($sched->end_time)->format('g:i A') .
                                                        '
                                                                        </div>
                                                                    </div>';

                                                    for ($i = 0; $i < $rowspan; $i++) {
                                                        $rendered[
                                                            $day .
                                                                '-' .
                                                                $slotStart
                                                                    ->copy()
                                                                    ->addMinutes($i * $step)
                                                                    ->format('H:i')
                                                        ] = true;
                                                    }

                                                    break;
                                                }
                                            }

                                            echo $cellContent
                                                ? '<td rowspan="' .
                                                    $rowspan .
                                                    '" class="align-middle p-0" style="height:' .
                                                    $rowspan * 40 .
                                                    'px;">' .
                                                    $cellContent .
                                                    '</td>'
                                                : '<td></td>';
                                        @endphp
                                    @endforeach
                                </tr>

                                @php $start->addMinutes($step); @endphp
                            @endwhile
                        </tbody>

                    </table>

                    @foreach ($schedules as $sched)
                        @php
                            $modalId = 'viewModal' . $sched->id;
                            $editModalId = 'editModal' . $sched->id;
                            $days = is_array($sched->day) ? $sched->day : json_decode($sched->day, true);
                            $days = is_array($days) ? $days : [$sched->day];
                        @endphp

                        <!-- Viewing Modal -->
                        <div class="modal fade" id="{{ $modalId }}" tabindex="-1"
                            aria-labelledby="{{ $modalId }}Label" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-lg">
                                <div class="modal-content rounded-4 shadow-lg">
                                    <div class="modal-header bg-info text-auto rounded-top-4">
                                        <h5 class="modal-title fw-semibold" id="{{ $modalId }}Label">
                                            Schedule Details</h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body px-4 py-3">
                                        <h4 class="fw-bold text-primary text-center mb-3">
                                            {{ $sched->subject_name }}</h4>
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <h6 class="fw-semibold text-muted mb-1">Teacher:</h6>
                                                <p class="mb-0">
                                                    {{ $sched->teacher ? $sched->teacher->firstName . ' ' . $sched->teacher->lastName : 'TBA' }}
                                                </p>
                                            </div>
                                            <div class="col-md-6">
                                                <h6 class="fw-semibold text-muted mb-1">Days:</h6>
                                                <p class="mb-0">{{ implode(', ', $days) }}</p>
                                            </div>
                                            <div class="col-md-12 mt-2">
                                                <h6 class="fw-semibold text-muted mb-1">Time:</h6>
                                                <p class="mb-0">
                                                    {{ \Carbon\Carbon::parse($sched->start_time)->format('g:i A') }}
                                                    -
                                                    {{ \Carbon\Carbon::parse($sched->end_time)->format('g:i A') }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer bg-light justify-content-between">
                                        <button type="button" class="btn btn-warning text-white" data-bs-toggle="modal"
                                            data-bs-target="#{{ $editModalId }}" data-bs-dismiss="modal">
                                            <i class="bi bi-pencil-square me-1"></i> Edit
                                        </button>

                                        <form
                                            action="{{ route('classes.deleteSchedule', [
                                                'grade_level' => $class->grade_level,
                                                'section' => $class->section,
                                                'schedule_id' => $sched->id,
                                            ]) }}"
                                            method="POST"
                                            onsubmit="return confirm('Are you sure you want to delete this schedule?');">
                                            @csrf
                                            @method('DELETE')
                                            <input type="hidden" name="school_year"
                                                value="{{ request('school_year') }}">
                                            <input type="hidden" name="schedule_id" value="{{ $sched->id }}">

                                            <button type="submit" class="btn btn-danger">
                                                <i class="bi bi-trash me-1"></i> Delete
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Edit Modal -->
                        <div class="modal fade" id="{{ $editModalId }}" tabindex="-1"
                            aria-labelledby="{{ $editModalId }}Label" aria-hidden="true">
                            <div class="modal-dialog">
                                <form class="modal-content"
                                    action="{{ route('classes.editSchedule', ['grade_level' => $class->grade_level, 'section' => $class->section]) }}"
                                    method="POST">
                                    @csrf

                                    <input type="hidden" name="school_year" value="{{ request('school_year') }}">
                                    <input type="hidden" name="schedule_id" value="{{ $sched->id }}">

                                    <div class="modal-header bg-warning text-white">
                                        <h5 class="modal-title" id="{{ $editModalId }}Label">Edit Schedule
                                        </h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <!-- Subject -->
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold">Subject Name</label>
                                            <input type="text" name="subject_name" class="form-control"
                                                value="{{ $sched->subject_name }}" required>
                                            <input type="hidden" name="original_subject_name"
                                                value="{{ $sched->subject_name }}">
                                        </div>

                                        <!-- Teacher -->
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold">Teacher</label>
                                            <select name="teacher_id" class="form-select" required>
                                                <option value="" disabled>Select Teacher</option>
                                                @foreach ($teachers as $teacher)
                                                    <option value="{{ $teacher->id }}"
                                                        {{ optional($sched->teacher)->id == $teacher->id ? 'selected' : '' }}>
                                                        {{ $teacher->firstName }} {{ $teacher->lastName }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <!-- Days -->
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold">Days</label>
                                            <div class="d-flex flex-wrap gap-3">
                                                @php
                                                    $schedDay = $sched->day;
                                                @endphp

                                                <div class="mb-3">
                                                    <label class="form-label fw-semibold">Day</label>
                                                    <div class="d-flex flex-wrap gap-3">
                                                        @foreach (['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'] as $day)
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox"
                                                                    name="days[]" value="{{ $day }}"
                                                                    id="edit_day_{{ $sched->id }}_{{ strtolower($day) }}"
                                                                    {{ $day === $schedDay ? 'checked' : 'disabled' }}>
                                                                <label class="form-check-label"
                                                                    for="edit_day_{{ $sched->id }}_{{ strtolower($day) }}">{{ $day }}</label>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>

                                            </div>
                                        </div>

                                        <!-- Time -->
                                        <div class="mb-3 row">
                                            <div class="col">
                                                <label class="form-label fw-semibold">Start Time</label>
                                                <input type="time" name="start_time" class="form-control"
                                                    value="{{ \Carbon\Carbon::parse($sched->start_time)->format('H:i') }}"
                                                    required>
                                            </div>
                                            <div class="col">
                                                <label class="form-label fw-semibold">End Time</label>
                                                <input type="time" name="end_time" class="form-control"
                                                    value="{{ \Carbon\Carbon::parse($sched->end_time)->format('H:i') }}"
                                                    required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer bg-light">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn btn-success">Save Changes</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @endforeach

                </div>

            </div>

        </div>
        {{-- /Card --}}


        <hr class="my-5" />

    </div>
    <!-- Content wrapper -->

@endsection

@push('scripts')
    <script>
        // register alert
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('#backDropModal form');
            const registerBtn = document.getElementById('registerTeacherBtn');

            registerBtn.addEventListener('click', function(e) {
                e.preventDefault(); // Prevent default form submission

                // Manually check all required fields
                const requiredFields = form.querySelectorAll('[required]');
                let allFilled = true;

                requiredFields.forEach(field => {
                    if (!field.value.trim()) {
                        allFilled = false;
                        field.classList.add('is-invalid'); // optional: Bootstrap red border
                    } else {
                        field.classList.remove('is-invalid');
                    }
                });

                if (!allFilled) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Incomplete Form',
                        text: 'Please fill in all required fields before submitting.',
                        confirmButtonColor: '#dc3545',
                        customClass: {
                            container: 'my-swal-container'
                        }
                    });
                    return;
                }

                // If all fields are filled, show confirmation alert
                Swal.fire({
                    title: "Add Schedule?",
                    text: "Are you sure all the details are correct?",
                    icon: "question",
                    showCancelButton: true,
                    confirmButtonColor: "#06D001",
                    cancelButtonColor: "#6c757d",
                    confirmButtonText: "Yes, Add",
                    cancelButtonText: "Cancel",
                    customClass: {
                        container: 'my-swal-container'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: "Adding...",
                            text: "Please wait while we process the adding of schedule.",
                            icon: "info",
                            allowOutsideClick: false,
                            showConfirmButton: false,
                            timer: 1200,
                            customClass: {
                                container: 'my-swal-container'
                            },
                            willClose: () => {
                                form.submit();
                            }
                        });
                    }
                });
            });
        });
    </script>

    <script>
        // delete button alert
        function confirmDelete(teacherId, firstName, lastName) {
            Swal.fire({
                title: `Delete ${firstName} ${lastName}'s record?`,
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
                                document.getElementById('delete-form-' + teacherId).submit();
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
                        icon: "success"
                    });
                    document.getElementById('logout-form').submit();
                }
            });
        }
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
        .hoverable-schedule-cell {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            cursor: pointer;
        }

        .hoverable-schedule-cell:hover {
            transform: scale(1.02);
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }
    </style>
@endpush
