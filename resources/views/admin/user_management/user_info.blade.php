@extends('./layouts.main')

@section('title', 'Admin | User Info')

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
            <li class="menu-item">
                <a href="javascript:void(0);" class="menu-link menu-toggle bg-dark text-light">
                    <i class="menu-icon tf-icons bx bx-wallet-alt text-light"></i>
                    <div class="text-light">Payments</div>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item">
                        <a href="{{ route('payments.index') }}" class="menu-link bg-dark text-light">
                            <div class="text-light">All Payments</div>
                        </a>
                    </li>
                </ul>
            </li>

            {{-- User Management sidebar --}}
            <li class="menu-item active">
                <a href="{{ route('admin.user.management') }}" class="menu-link">
                    <i class='bx bxs-user-account me-3'></i>
                    <div class="text-warning"> User Management</div>
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
    <div class="content-wrapper">

        <!-- Content wrapper -->
        <div class="container-xxl flex-grow-1 container-p-y">
            <!-- Breadcrumb -->
            <h4 class="fw-bold py-3 mb-4 text-warning">
                <span class="text-muted fw-light">
                    <a class="text-muted fw-light" href="{{ route('home') }}">Dashboard / </a>
                    <a class="text-muted fw-light" href="{{ route('admin.user.management') }}">User Management /</a>
                </span> User Information
            </h4>

            <div class="row">
                <!-- Left Profile Card -->
                <div class="col-md-4 mb-4">
                    <div class="card shadow p-3 align-items-center text-center">
                        @php
                            // Profile photo logic
                            if ($user->profile_photo) {
                                if (Str::startsWith($user->profile_photo, ['http://', 'https://'])) {
                                    $profilePhoto = $user->profile_photo; // external (Google, etc.)
                                } else {
                                    $profilePhoto = asset('storage/' . $user->profile_photo); // stored locally
                                }
                            } else {
                                // No profile photo → role-based fallback
                                $profilePhoto = match ($user->role) {
                                    'admin' => asset('assetsDashboard/img/profile_pictures/admin_default_profile.jpg'),
                                    'teacher' => asset(
                                        'assetsDashboard/img/profile_pictures/teacher_default_profile.jpg',
                                    ),
                                    'parent' => asset(
                                        'assetsDashboard/img/profile_pictures/parent_default_profile.jpg',
                                    ),
                                    default => 'https://ui-avatars.com/api/?name=' . urlencode($user->full_name),
                                };
                            }
                        @endphp

                        <img src="{{ $profilePhoto }}" alt="{{ $user->full_name }}" class="mb-3 mt-2"
                            style="object-fit: cover; height: 200px; width: 200px;">

                        <h5 class="fw-bold">{{ $user->full_name }}</h5>

                        <!-- Role -->
                        <div class="mb-2">
                            <span class="badge bg-primary">{{ ucfirst($user->role) }}</span>
                        </div>

                        <!-- Status -->
                        <div class="mb-3">
                            <span class="fw-bold">Status:</span><br>
                            @php
                                $statusClass = match ($user->status) {
                                    'active' => 'bg-label-success fw-bold',
                                    'inactive' => 'bg-label-secondary fw-bold',
                                    'suspended' => 'bg-label-warning fw-bold',
                                    'banned' => 'bg-label-danger fw-bold',
                                    default => 'bg-label-dark fw-bold',
                                };
                            @endphp
                            <span class="badge {{ $statusClass }} px-3 py-2">
                                {{ ucfirst($user->status) }}
                            </span>
                        </div>

                        <div class="d-flex justify-content-center mt-3 gap-2">
                            <!-- Back Button -->
                            <a href="{{ url()->previous() }}" class="btn btn-secondary d-flex align-items-center">
                                <i class='bx bx-chevrons-left'></i>
                                <span class="d-none d-sm-block">Back</span>
                            </a>
                            <!-- Edit Button -->
                            <a href="{{ route('admin.user.management') }}"
                                class="btn btn-warning d-flex align-items-center">
                                <i class='bx bx-edit me-1'></i>
                                <span class="d-none d-sm-block">Edit</span>
                            </a>
                            <!-- Edit Button -->
                            <a href="{{ route('admin.user.management') }}"
                                class="btn btn-danger d-flex align-items-center">
                                <i class='bx bx-trash me-1'></i>
                                <span class="d-none d-sm-block">Delete</span>
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
                                <i class="bx bx-user me-1"></i>
                                <span class="d-none d-sm-block">Profile</span>
                            </button>
                        </li>

                        @if ($user->role === 'teacher')
                            <li class="nav-item">
                                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#classes">
                                    <i class="bx bx-book-content me-1"></i>
                                    <span class="d-none d-sm-block">Classes</span>
                                </button>
                            </li>
                        @elseif($user->role === 'parent')
                            <li class="nav-item">
                                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#students">
                                    <i class="bx bx-group me-1"></i>
                                    <span class="d-none d-sm-block">Children</span>
                                </button>
                            </li>
                        @endif
                    </ul>

                    <div class="card shadow">
                        <div class="card-body">
                            <!-- Tab Content -->
                            <div class="tab-content">
                                <!-- Profile -->
                                <div class="tab-pane fade show active" id="profile">
                                    <h5 class="fw-bold text-primary mb-3">User Information</h5>

                                    <div class="row mb-2">
                                        <div class="col-sm-4 fw-bold">Email:</div>
                                        <div class="col-sm-8">{{ $user->email }}</div>
                                    </div>

                                    <div class="row mb-2">
                                        <div class="col-sm-4 fw-bold">Age:</div>
                                        <div class="col-sm-8">{{ \Carbon\Carbon::parse($user->dob)->age }} years old</div>
                                    </div>

                                    <div class="row mb-2">
                                        <div class="col-sm-4 fw-bold">Gender:</div>
                                        <div class="col-sm-8">{{ ucfirst($user->gender) }}</div>
                                    </div>

                                    <div class="row mb-2">
                                        <div class="col-sm-4 fw-bold">Contact Number:</div>
                                        <div class="col-sm-8">{{ $user->contact_number ?? 'N/A' }}</div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-sm-4 fw-bold">
                                            Date of Birth:
                                        </div>
                                        <div class="col-sm-8">
                                            {{ $user->dob ? \Carbon\Carbon::parse($user->dob)->format('F j, Y') : 'N/A' }}
                                        </div>
                                    </div>

                                    <div class="row mb-2">
                                        <div class="col-sm-4 fw-bold">Joined:</div>
                                        <div class="col-sm-8">{{ $user->created_at->format('F j, Y') }}</div>
                                    </div>

                                    <div class="row mb-2">
                                        <div class="col-sm-4 fw-bold">Last Active:</div>
                                        <div class="col-sm-8">{{ $user->last_seen ?? 'N/A' }}</div>
                                    </div>

                                    @if ($user->role === 'parent' && $user->parent_type)
                                        <div class="row mb-2">
                                            <div class="col-sm-4 fw-bold">Parent Type:</div>
                                            <div class="col-sm-8 text-capitalize">{{ $user->parent_type }}</div>
                                        </div>
                                    @endif

                                    @if (
                                        $user->house_no ||
                                            $user->street_name ||
                                            $user->barangay ||
                                            $user->municipality_city ||
                                            $user->province ||
                                            $user->zip_code)
                                        <hr>
                                        <h6 class="fw-bold text-primary mt-3">Address</h6>

                                        @if ($user->house_no)
                                            <div class="row mb-2">
                                                <div class="col-sm-4 fw-bold">House No.:</div>
                                                <div class="col-sm-8">{{ $user->house_no }}</div>
                                            </div>
                                        @endif

                                        @if ($user->street_name)
                                            <div class="row mb-2">
                                                <div class="col-sm-4 fw-bold">Street:</div>
                                                <div class="col-sm-8">{{ $user->street_name }}</div>
                                            </div>
                                        @endif

                                        @if ($user->barangay)
                                            <div class="row mb-2">
                                                <div class="col-sm-4 fw-bold">Barangay:</div>
                                                <div class="col-sm-8">{{ $user->barangay }}</div>
                                            </div>
                                        @endif

                                        @if ($user->municipality_city)
                                            <div class="row mb-2">
                                                <div class="col-sm-4 fw-bold">Municipality/City:</div>
                                                <div class="col-sm-8">{{ $user->municipality_city }}</div>
                                            </div>
                                        @endif

                                        @if ($user->province)
                                            <div class="row mb-2">
                                                <div class="col-sm-4 fw-bold">Province:</div>
                                                <div class="col-sm-8">{{ $user->province }}</div>
                                            </div>
                                        @endif

                                        @if ($user->zip_code)
                                            <div class="row mb-2">
                                                <div class="col-sm-4 fw-bold">Zip Code:</div>
                                                <div class="col-sm-8">{{ $user->zip_code }}</div>
                                            </div>
                                        @endif
                                    @endif
                                </div>

                                <!-- Teacher Classes -->
                                @if ($user->role === 'teacher')
                                    <div class="tab-pane fade" id="classes">
                                        <h5 class="fw-bold text-primary mb-3">Assigned Classes</h5>

                                        @if ($classes->isEmpty())
                                            <p class="text-muted">No classes assigned to this teacher yet.</p>
                                        @else
                                            <div class="accordion" id="teacherClassAccordion">
                                                @foreach ($classes as $index => $classGroup)
                                                    @php
                                                        // $index is the school year label (because controller keyed by label)
                                                        $schoolYearLabel = $index;
                                                        // create a safe id for collapse (avoid spaces/chars)
                                                        $collapseId =
                                                            'collapse-' .
                                                            Str::slug($schoolYearLabel) .
                                                            '-' .
                                                            $loop->index;
                                                    @endphp

                                                    <div class="accordion-item border-0 shadow-sm mb-2 rounded-3">
                                                        <h2 class="accordion-header" id="heading-{{ $loop->index }}">
                                                            <button
                                                                class="accordion-button collapsed fw-bold text-dark d-flex justify-content-between align-items-center"
                                                                type="button" data-bs-toggle="collapse"
                                                                data-bs-target="#{{ $collapseId }}"
                                                                aria-expanded="false"
                                                                aria-controls="{{ $collapseId }}">
                                                                <div>
                                                                    {{ $schoolYearLabel }}
                                                                </div>

                                                                <div class="text-end">
                                                                    <span
                                                                        class="badge bg-primary ms-2">{{ $classGroup->count() }}
                                                                        class{{ $classGroup->count() > 1 ? 'es' : '' }}</span>
                                                                </div>
                                                            </button>
                                                        </h2>

                                                        <div id="{{ $collapseId }}" class="accordion-collapse collapse"
                                                            aria-labelledby="heading-{{ $loop->index }}"
                                                            data-bs-parent="#teacherClassAccordion">
                                                            <div class="accordion-body p-0">
                                                                <ul class="list-group list-group-flush">
                                                                    @foreach ($classGroup->sortByDesc(fn($c) => $c->pivot->role === 'adviser') as $class)
                                                                        <li
                                                                            class="list-group-item d-flex justify-content-between align-items-center">
                                                                            <div>
                                                                                {{ strtoupper($class->formattedGradeLevel ?? $class->grade_level) }}
                                                                                - {{ $class->section }}
                                                                            </div>

                                                                            <div class="text-end">
                                                                                <span
                                                                                    class="badge {{ $class->pivot->role === 'adviser' ? 'bg-success' : 'bg-info' }}">
                                                                                    {{ ucfirst($class->pivot->role) }}
                                                                                </span>
                                                                                {{-- Optional: show the pivot status if present --}}
                                                                                @if (isset($class->pivot->status))
                                                                                    <span
                                                                                        class="badge bg-secondary ms-1">{{ ucfirst($class->pivot->status) }}</span>
                                                                                @endif
                                                                            </div>
                                                                        </li>
                                                                    @endforeach
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                @endif

                                <!-- Parent Children -->
                                @if ($user->role === 'parent')
                                    <div class="tab-pane fade" id="students">
                                        <h5 class="fw-bold text-primary mb-3">Children</h5>

                                        @if ($students->isEmpty())
                                            <p class="text-muted">No children linked to this parent.</p>
                                        @else
                                            <ul class="list-group">
                                                @foreach ($students as $student)
                                                    <li class="list-group-item">
                                                        <strong>{{ $student->student_fName }}
                                                            {{ $student->student_lName }}</strong><br>
                                                        <small>LRN: {{ $student->student_lrn }}</small>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Content wrapper -->

        <div class="content-backdrop fade"></div>
    </div>
    <!-- Content wrapper -->

    <!-- Overlay -->
    <div class="layout-overlay layout-menu-toggle"></div>
@endsection

@push('scripts')
    <!-- Logout -->
    <script>
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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

    <style>
        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .card-hover {
            transition: all 0.3s ease;
        }
    </style>
@endpush
