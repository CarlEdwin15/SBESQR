@extends('./layouts.main')

@section('title', 'Teacher | My Schedules')

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
                            <h4 class="fw-bold text-warning mb-2">
                                <span class="text-muted fw-light">
                                    <a class="text-muted fw-light" href="{{ route('home') }}">Dashboard</a> /
                                    <a class="text-muted fw-light"
                                        href="{{ route('teacher.myClasses', ['grade_level' => $class->grade_level, 'section' => $class->section, 'school_year' => $selectedYear]) }}">Classes</a>
                                    /
                                    <a class="text-muted fw-light"
                                        href="{{ route('teacher.myClass', ['grade_level' => $class->grade_level, 'section' => $class->section, 'school_year' => $selectedYear]) }}">
                                        {{ ucfirst($class->grade_level) }} - {{ $class->section }} ({{ $selectedYear }})
                                    </a> /
                                </span>
                                Schedules
                            </h4>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center">
                        <a href="{{ route('teacher.myClass', ['grade_level' => $class->grade_level, 'section' => $class->section, 'school_year' => $selectedYear]) }}"
                            class="btn btn-danger mb-3 d-flex align-items-center">
                            <i class='bx bx-chevrons-left'></i>
                            <span class="d-none d-sm-block">Back</span>
                        </a>
                    </div>

                    {{-- Teacher Schedule Grid Display --}}
                    <div class="card">
                        <div class="container my-4">
                            <h3 class="text-center mb-4 fw-bold">
                                Schedules for <span class="text-info">{{ ucfirst($class->grade_level) }} -
                                    {{ $class->section }}</span>
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
                                                $showTime = $slotStart->minute == 0;
                                                $isLunchStart =
                                                    $slotStart->format('H:i') >= '12:00' &&
                                                    $slotStart->format('H:i') < '13:00';
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
                                                                $modalId = 'viewModal' . $sched->id;
                                                                $rowspan = ceil(
                                                                    $schedStart->diffInMinutes($schedEnd) / $step,
                                                                );
                                                                $bgColor = $day === $todayName ? '#6ec1e4' : '#ffab00';

                                                                $cellContent =
                                                                    '
                                            <div class="w-100 h-100 d-flex flex-column justify-content-center align-items-center text-center text-white fw-semibold hoverable-schedule-cell"
                                                style="background-color:' .
                                                                    $bgColor .
                                                                    '; padding: 10px 5px;"
                                                data-bs-toggle="modal" data-bs-target="#' .
                                                                    $modalId .
                                                                    '">
                                                <div style="font-size:25px; margin-bottom:50px">' .
                                                                    $sched->subject_name .
                                                                    '</div>
                                                <div style="margin-bottom:5px">' .
                                                                    ($sched->teacher
                                                                        ? 'Teacher: ' .
                                                                            $sched->teacher->firstName .
                                                                            ' ' .
                                                                            $sched->teacher->lastName
                                                                        : 'Teacher: N/A') .
                                                                    '</div>
                                                                    <div>' .
                                                                    \Carbon\Carbon::parse($sched->start_time)->format(
                                                                        'g:i A',
                                                                    ) .
                                                                    ' - ' .
                                                                    \Carbon\Carbon::parse($sched->end_time)->format(
                                                                        'g:i A',
                                                                    ) .
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

                                {{-- View-only modals for each schedule --}}
                                @foreach ($schedules as $sched)
                                    @php
                                        $modalId = 'viewModal' . $sched->id;
                                        $days = is_array($sched->day) ? $sched->day : json_decode($sched->day, true);
                                        $days = is_array($days) ? $days : [$sched->day];
                                    @endphp

                                    <div class="modal fade" id="{{ $modalId }}" tabindex="-1"
                                        aria-labelledby="{{ $modalId }}Label" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered modal-lg">
                                            <div class="modal-content rounded-4 shadow-lg">
                                                <div class="modal-header bg-info text-auto rounded-top-4">
                                                    <h5 class="modal-title fw-semibold" id="{{ $modalId }}Label">
                                                        Schedule Details</h5>
                                                    <button type="button" class="btn-close btn-close-white"
                                                        data-bs-dismiss="modal" aria-label="Close"></button>
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
                                                <div class="modal-footer bg-light justify-content-end">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Close</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

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
    <!-- Include Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

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
@endpush

@push('styles')
    <!-- Main CSS File -->
    <link href="{{ asset('assets/css/main.css') }}" rel="stylesheet" />
    <link href="{{ asset('assetsDashboard/vendor/css/core.css') }}" rel="stylesheet" />

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
