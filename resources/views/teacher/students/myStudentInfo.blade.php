@extends('./layouts.main')
@php
    use Carbon\Carbon;
@endphp

@section('title', 'Teacher | Student Info')

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
                    <li class="menu-item active open">
                        <a class="menu-link menu-toggle ">
                            <i class="menu-icon tf-icons bx bxs-graduation"></i>
                            <div>Students</div>
                        </a>
                        <ul class="menu-sub">
                            <li class="menu-item active">
                                <a href="{{ route('teacher.my.students') }}" class="menu-link bg-dark text-light">
                                    <div class="text-warning">My Students</div>
                                </a>
                            </li>
                        </ul>
                    </li>

                    {{-- Classes sidebar --}}
                    <li class="menu-item">
                        <a class="menu-link menu-toggle bg-dark text-light">
                            <i class="menu-icon tf-icons bx bx-notepad text-light"></i>
                            <div class="text-light">Classes</div>
                        </a>
                        <ul class="menu-sub">
                            <li class="menu-item">
                                <a href="{{ route('teacher.myClasses') }}" class="menu-link bg-dark text-light">
                                    <div class="text-light">My Class</div>
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
                                                            alt="Default Profile Photo" class="w-px-40 h-auto rounded-circle" />
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
                    <!-- Breadcrumb -->
                    <h4 class="fw-bold py-3 mb-4 text-warning">
                        <span class="text-muted fw-light">
                            <a class="text-muted fw-light" href="{{ route('home') }}">Dashboard / </a>
                            <a class="text-muted fw-light" href="{{ route('teacher.my.students') }}">Students /</a>
                        </span> Student Information
                    </h4>

                    <div class="row">
                        <!-- Left Profile Card -->
                        <div class="col-md-4 mb-4">
                            <div class="card shadow p-3 align-items-center text-center">
                                @if ($student->student_photo)
                                    <img src="{{ asset('storage/' . $student->student_photo) }}" alt="Student Photo"
                                        class="mb-3 mt-2" style="object-fit: cover; height: 200px; width: 200px;">
                                @else
                                    <img src="{{ asset('assetsDashboard/img/student_profile_pictures/student_default_profile.jpg') }}"
                                        alt="Default Photo" class="mb-3 mt-2"
                                        style="object-fit: cover; height: 200px; width: 200px;">
                                @endif

                                <h5 class="fw-bold">{{ $student->student_fName }} {{ $student->student_lName }}</h5>

                                <!-- LRN -->
                                <div class="text-start mb-3">
                                    <div><span class="fw-bold">LRN:</span> {{ $student->student_lrn }}</div>
                                </div>

                                <!-- Enrollment Status -->
                                <div class="mt-2 mb-3">
                                    <span class="fw-bold">Enrollment Status:</span><br>
                                    @if ($class && $class->pivot->enrollment_status)
                                        @php
                                            $status = $class->pivot->enrollment_status;
                                            $badgeClass = match ($status) {
                                                'enrolled' => 'bg-label-success fw-bold',
                                                'archived' => 'bg-label-warning fw-bold',
                                                default => 'bg-label-secondary fw-bold',
                                            };
                                        @endphp
                                        <span class="badge {{ $badgeClass }} px-3 py-2">
                                            {{ ucfirst($status) }}
                                        </span>
                                    @else
                                        <span class="text-muted">N/A</span>
                                    @endif
                                </div>

                                <div class="d-flex justify-content-center mt-3">
                                    <!-- Back Button -->
                                    <a href="{{ url()->previous() }}"
                                        class="btn btn-danger me-2 d-flex align-items-center">
                                        <i class='bx bx-chevrons-left'></i>
                                        <span class="d-none d-sm-block">Back</span>
                                    </a>
                                    <!-- Edit Button -->
                                    <a href="{{ route('teacher.edit.student', $student->id) }}"
                                        class="btn btn-warning d-flex align-items-center">
                                        <i class='bx bx-edit me-1'></i>
                                        <span class="d-none d-sm-block">Edit</span>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Right Tabs + Info -->
                        <div class="col-md-8">

                            <!-- Tabs -->
                            <ul class="nav nav-pills mb-3" role="tablist">
                                <li class="nav-item">
                                    <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#profile">
                                        <i class="bx bx-user-pin me-1"></i>
                                        <span class="d-none d-sm-block">Profile</span>
                                    </button>
                                </li>
                                <li class="nav-item">
                                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#classes">
                                        <i class="bx bx-book-content me-1"></i>
                                        <span class="d-none d-sm-block">Classes &amp; Grades</span>
                                    </button>
                                </li>
                            </ul>

                            <div class="card shadow">
                                <div class="card-body">
                                    <!-- Tab Content -->
                                    <div class="tab-content">
                                        <!-- Profile -->
                                        <div class="tab-pane fade show active" id="profile">
                                            <h5 class="fw-bold text-primary mb-3">Student Information</h5>
                                            <div class="row mb-2">
                                                <div class="col-sm-4 fw-bold">Date of Birth:</div>
                                                <div class="col-sm-8">
                                                    {{ Carbon::parse($student->student_dob)->format('F j, Y') }}
                                                </div>
                                            </div>
                                            <div class="row mb-2">
                                                <div class="col-sm-4 fw-bold">Sex:</div>
                                                <div class="col-sm-8">{{ ucfirst($student->student_sex) }}</div>
                                            </div>
                                            <div class="row mb-2">
                                                <div class="col-sm-4 fw-bold">Age:</div>
                                                <div class="col-sm-8">
                                                    {{ Carbon::parse($student->student_dob)->age }} years old
                                                </div>
                                            </div>
                                            <div class="row mb-2">
                                                <div class="col-sm-4 fw-bold">Place of Birth:</div>
                                                <div class="col-sm-8">{{ $student->address->pob }}</div>
                                            </div>
                                            <div class="row mb-2">
                                                <div class="col-sm-4 fw-bold">Current Address:</div>
                                                <div class="col-sm-8">
                                                    {{ $student->address->house_no ?? 'N/A' }},
                                                    {{ $student->address->street_name ?? 'N/A' }},
                                                    {{ $student->address->barangay ?? 'N/A' }},
                                                    {{ $student->address->municipality_city ?? 'N/A' }},
                                                    {{ $student->address->province ?? 'N/A' }},
                                                    {{ $student->address->zip_code ?? 'N/A' }}
                                                </div>
                                            </div>

                                            <!-- Parents Info -->
                                            <h5 class="fw-bold text-primary mt-4 mb-3">Parent Information</h5>
                                            <div class="row mb-2">
                                                <div class="col-sm-4 fw-bold">Father's Name:</div>
                                                <div class="col-sm-8">{{ $student->parentInfo->father_fName ?? 'N/A' }}
                                                    {{ $student->parentInfo->father_lName ?? 'N/A' }}
                                                </div>
                                            </div>
                                            <div class="row mb-2">
                                                <div class="col-sm-4 fw-bold">Father's Contact No.:</div>
                                                <div class="col-sm-8">
                                                    {{ $student->parentInfo->father_phone ?? 'N/A' }}
                                                </div>
                                            </div>
                                            <div class="row mb-2">
                                                <div class="col-sm-4 fw-bold">Mother's Name:</div>
                                                <div class="col-sm-8">{{ $student->parentInfo->mother_fName ?? 'N/A' }}
                                                    {{ $student->parentInfo->mother_lName ?? 'N/A' }}
                                                </div>
                                            </div>
                                            <div class="row mb-2">
                                                <div class="col-sm-4 fw-bold">Mother's Contact No.:</div>
                                                <div class="col-sm-8">
                                                    {{ $student->parentInfo->mother_phone ?? 'N/A' }}
                                                </div>
                                            </div>

                                            <!-- Emergency Contact -->
                                            <h5 class="fw-bold text-primary mt-4 mb-3">Emergency Contact</h5>
                                            <div class="row mb-2">
                                                <div class="col-sm-4 fw-bold">Name:</div>
                                                <div class="col-sm-8">
                                                    {{ $student->parentInfo->emergcont_fName ?? 'N/A' }}
                                                    {{ $student->parentInfo->emergcont_lName ?? 'N/A' }}
                                                </div>
                                            </div>
                                            <div class="row mb-2">
                                                <div class="col-sm-4 fw-bold">Phone:</div>
                                                <div class="col-sm-8">
                                                    {{ $student->parentInfo->emergcont_phone ?? 'N/A' }}
                                                </div>
                                            </div>

                                            <!-- QR Code -->
                                            <h5 class="fw-bold text-primary mt-4 mb-3">QR Code</h5>
                                            <div class="text-center">
                                                {!! QrCode::size(200)->generate(json_encode(['student_id' => $student->id])) !!}
                                            </div>
                                        </div>

                                        <!-- Classes & Grades -->
                                        <div class="tab-pane fade" id="classes">
                                            <h5 class="fw-bold text-primary mb-3">Classes and Grades</h5>

                                            @if ($classHistory->isEmpty())
                                                <p class="text-muted">No classes found for this student.</p>
                                            @else
                                                <div class="accordion" id="classHistoryAccordion">
                                                    @foreach ($classHistory as $index => $classItem)
                                                        @php
                                                            $schoolYear =
                                                                \App\Models\SchoolYear::find(
                                                                    $classItem->pivot->school_year_id,
                                                                )?->school_year ?? 'N/A';
                                                            $status = $classItem->pivot->enrollment_status;
                                                            $badgeClass = match ($status) {
                                                                'enrolled' => 'bg-success',
                                                                'archived' => 'bg-warning',
                                                                default => 'bg-secondary',
                                                            };

                                                            // Get only the grades for this class
                                                            $subjectsWithGrades = $gradesByClass[$classItem->id] ?? [];
                                                        @endphp

                                                        <div class="accordion-item border-0 shadow-sm mb-2 rounded-3">
                                                            <h2 class="accordion-header" id="heading{{ $index }}">
                                                                <button
                                                                    class="accordion-button collapsed fw-bold text-dark d-flex justify-content-between align-items-center"
                                                                    type="button" data-bs-toggle="collapse"
                                                                    data-bs-target="#collapse{{ $index }}"
                                                                    aria-expanded="false"
                                                                    aria-controls="collapse{{ $index }}">

                                                                    <span>
                                                                        {{ $schoolYear }} —
                                                                        {{ $classItem->formatted_grade_level }} -
                                                                        {{ $classItem->section }}
                                                                    </span>

                                                                    <div>
                                                                        <span
                                                                            class="badge {{ $badgeClass }} ms-3">{{ ucfirst($status) }}</span>
                                                                        @if ($status === 'enrolled')
                                                                            <span class="badge bg-primary ms-2">Current
                                                                                Class</span>
                                                                        @endif
                                                                    </div>

                                                                    @php
                                                                        $adviser = $classItem->advisers
                                                                            ->where(
                                                                                'pivot.school_year_id',
                                                                                $classItem->pivot->school_year_id,
                                                                            )
                                                                            ->first();
                                                                    @endphp

                                                                    <span>
                                                                        @if ($adviser)
                                                                            <small class="text-muted ms-2">Adviser:
                                                                                {{ $adviser->full_name }}</small>
                                                                        @endif
                                                                    </span>
                                                                </button>

                                                            </h2>

                                                            <div id="collapse{{ $index }}"
                                                                class="accordion-collapse collapse"
                                                                aria-labelledby="heading{{ $index }}"
                                                                data-bs-parent="#classHistoryAccordion">

                                                                <div class="accordion-body">
                                                                    @if (empty($subjectsWithGrades))
                                                                        <div
                                                                            class="p-3 mb-3 rounded-3 bg-light text-center border">
                                                                            <p class="text-warning fw-bold   mb-0">No
                                                                                grades found for
                                                                                this school year.</p>
                                                                        </div>
                                                                    @else
                                                                        <div
                                                                            class="d-flex justify-content-between align-items-center mb-3 mt-2">
                                                                            <h5 class="fw-bold text-primary">Grades
                                                                            </h5>
                                                                            <a href=""
                                                                                class="btn btn-success btn-sm">
                                                                                <i class="bx bx-printer"></i> Export
                                                                            </a>
                                                                        </div>
                                                                        <div class="table-responsive">
                                                                            <table
                                                                                class="table table-bordered table-hover">
                                                                                <thead class="table-light">
                                                                                    <tr class="text-center">
                                                                                        <th>Subject</th>
                                                                                        <th>Q1</th>
                                                                                        <th>Q2</th>
                                                                                        <th>Q3</th>
                                                                                        <th>Q4</th>
                                                                                        <th>Final Grade</th>
                                                                                        <th>Remarks</th>
                                                                                    </tr>
                                                                                </thead>
                                                                                <tbody>
                                                                                    @foreach ($subjectsWithGrades as $item)
                                                                                        <tr>
                                                                                            <td>{{ $item['subject'] }}</td>
                                                                                            <td class="text-center">
                                                                                                {{ $item['quarters']->firstWhere('quarter', 1)['grade'] ?? '-' }}
                                                                                            </td>
                                                                                            <td class="text-center">
                                                                                                {{ $item['quarters']->firstWhere('quarter', 2)['grade'] ?? '-' }}
                                                                                            </td>
                                                                                            <td class="text-center">
                                                                                                {{ $item['quarters']->firstWhere('quarter', 3)['grade'] ?? '-' }}
                                                                                            </td>
                                                                                            <td class="text-center">
                                                                                                {{ $item['quarters']->firstWhere('quarter', 4)['grade'] ?? '-' }}
                                                                                            </td>
                                                                                            <td class="text-center">
                                                                                                @if ($item['final_average'] !== null)
                                                                                                    <strong>{{ $item['final_average'] }}</strong>
                                                                                                @else
                                                                                                    <span
                                                                                                        class="text-muted">-</span>
                                                                                                @endif
                                                                                            </td>
                                                                                            <td class="text-center">
                                                                                                @if ($item['remarks'] === 'passed')
                                                                                                    <span
                                                                                                        class="badge bg-label-success fw-bold">Passed</span>
                                                                                                @elseif($item['remarks'] === 'failed')
                                                                                                    <span
                                                                                                        class="badge bg-label-danger fw-bold">Failed</span>
                                                                                                @else
                                                                                                    <span
                                                                                                        class="text-muted">-</span>
                                                                                                @endif
                                                                                            </td>
                                                                                        </tr>
                                                                                    @endforeach
                                                                                </tbody>
                                                                            </table>
                                                                        </div>

                                                                        <h5 class="fw-bold text-primary mt-3 mb-3">General
                                                                            Average</h5>

                                                                        @if (!empty($generalAverages[$classItem->id]))
                                                                            @php $ga = $generalAverages[$classItem->id]; @endphp
                                                                            <div
                                                                                class="p-3 rounded-3 border bg-light d-flex justify-content-between">
                                                                                <span class="fw-bold">General
                                                                                    Average:</span>
                                                                                <span>
                                                                                    <strong>{{ $ga['general_average'] }}</strong>
                                                                                    @if ($ga['remarks'] === 'passed')
                                                                                        <span
                                                                                            class="badge bg-label-success ms-2">Passed</span>
                                                                                    @else
                                                                                        <span
                                                                                            class="badge bg-label-danger ms-2">Failed</span>
                                                                                    @endif
                                                                                </span>
                                                                            </div>
                                                                        @else
                                                                            <div
                                                                                class="p-3 rounded-3 border bg-light text-center">
                                                                                <span class="text-muted">General Average
                                                                                    not available (incomplete final
                                                                                    grades).</span>
                                                                            </div>
                                                                        @endif

                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                        <!-- /Classes -->

                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                </div>
                <!-- /Content wrapper -->

            </div>
            <!-- / Layout page -->
        </div>

        <!-- Overlay -->
        <div class="layout-overlay layout-menu-toggle"></div>
    </div>
    <!-- / Layout wrapper -->
@endsection

@push('scripts')
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
