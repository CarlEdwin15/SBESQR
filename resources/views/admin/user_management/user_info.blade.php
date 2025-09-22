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
                            <a href="{{ session('back_url', url()->previous()) }}"
                                class="btn btn-secondary d-flex align-items-center">
                                <i class='bx bx-chevrons-left'></i>
                                <span class="d-none d-sm-block">Back</span>
                            </a>
                            <!-- Edit Button (opens modal) -->
                            <button class="btn btn-warning d-flex align-items-center" data-bs-toggle="modal"
                                data-bs-target="#edit{{ ucfirst($user->role) }}Modal">
                                <i class='bx bx-edit me-1'></i>
                                <span class="d-none d-sm-block">Edit</span>
                                {{ ucfirst($user->role) }}
                            </button>

                            <!-- Delete Button -->
                            <form action="{{ route('admin.user.delete', $user->id) }}" method="POST"
                                onsubmit="return confirm('Are you sure you want to delete this user?');">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger d-flex align-items-center">
                                    <i class='bx bx-trash me-1'></i>
                                    <span class="d-none d-sm-block">Delete</span>
                                </button>
                            </form>
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

                                        @if ($classesByYear->isEmpty())
                                            <p class="text-muted">No classes assigned to this teacher yet.</p>
                                        @else
                                            <div class="accordion" id="teacherClassAccordion">
                                                @foreach ($classesByYear as $schoolYearLabel => $classGroup)
                                                    @php
                                                        $collapseId = 'collapse-' . Str::slug($schoolYearLabel);
                                                    @endphp

                                                    <div class="accordion-item border-0 shadow-sm mb-2 rounded-3">
                                                        <h2 class="accordion-header" id="heading-{{ $loop->index }}">
                                                            <button
                                                                class="accordion-button collapsed fw-bold text-dark d-flex justify-content-between align-items-center"
                                                                type="button" data-bs-toggle="collapse"
                                                                data-bs-target="#{{ $collapseId }}"
                                                                aria-expanded="false"
                                                                aria-controls="{{ $collapseId }}">
                                                                <div>{{ $schoolYearLabel }}</div>
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
                                                                    @foreach ($classGroup->sortByDesc(fn($c) => $c->pivot->role === 'adviser' ? 1 : 0) as $class)
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

        <!-- Edit Admin Modal -->
        <div class="modal fade" id="editAdminModal" data-bs-backdrop="static" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <form class="modal-content" action="{{ route('admin.user.update', $user->id) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="role" value="admin">

                    <div class="modal-header">
                        <h4 class="modal-title fw-bold text-primary">EDIT ADMIN</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">First Name</label>
                                <input type="text" class="form-control" name="firstName"
                                    value="{{ old('firstName', $user->firstName) }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Middle Name</label>
                                <input type="text" class="form-control" name="middleName"
                                    value="{{ old('middleName', $user->middleName) }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Last Name</label>
                                <input type="text" class="form-control" name="lastName"
                                    value="{{ old('lastName', $user->lastName) }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Ext. Name</label>
                                <input type="text" class="form-control" name="extName"
                                    value="{{ old('extName', $user->extName) }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Email</label>
                                <input type="email" class="form-control" name="email"
                                    value="{{ old('email', $user->email) }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Phone</label>
                                <input type="text" class="form-control" name="phone"
                                    value="{{ old('phone', $user->phone) }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Status</label>
                                <select name="status" class="form-select" required>
                                    <option value="active"
                                        {{ old('status', $user->status) == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive"
                                        {{ old('status', $user->status) == 'inactive' ? 'selected' : '' }}>Inactive
                                    </option>
                                    <option value="suspended"
                                        {{ old('status', $user->status) == 'suspended' ? 'selected' : '' }}>Suspended
                                    </option>
                                    <option value="banned"
                                        {{ old('status', $user->status) == 'banned' ? 'selected' : '' }}>Banned</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Profile Photo</label>
                                <input type="file" class="form-control" name="profile_photo" accept="image/*">
                                @if ($user->profile_photo)
                                    <img src="{{ asset('storage/' . $user->profile_photo) }}" class="img-thumbnail mt-2"
                                        width="120">
                                @endif
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Password (leave blank to keep)</label>
                                <input type="password" class="form-control" name="password">
                                <input type="password" class="form-control mt-2" name="password_confirmation"
                                    placeholder="Confirm Password">
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Update Admin</button>
                    </div>
                </form>
            </div>
        </div>
        <!-- /Edit Admin Modal -->

        <!-- Edit Teacher Modal -->
        <div class="modal fade" id="editTeacherModal" data-bs-backdrop="static" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <form class="modal-content" id="editTeacherForm" action="{{ route('admin.user.update', $user->id) }}"
                    method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="role" value="teacher">

                    <div class="modal-header">
                        <h4 class="modal-title fw-bold text-primary">EDIT TEACHER</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">

                        <!-- Profile Photo -->
                        <div class="row mb-3">
                            <div class="col d-flex align-items-center gap-4">
                                <div>
                                    <img id="photo-preview"
                                        src="{{ $user->profile_photo ? asset('storage/' . $user->profile_photo) : asset('assetsDashboard/img/profile_pictures/teacher_default_profile.jpg') }}"
                                        width="100" height="100" class="profile-preview"
                                        style="object-fit: cover; border-radius:5%;">
                                </div>
                                <div class="button-wrapper">
                                    <label for="upload" class="btn btn-warning mb-2">
                                        <span class="d-none d-sm-block">Upload new photo</span>
                                        <i class="bx bx-upload d-block d-sm-none"></i>
                                        <input type="file" name="profile_photo" id="upload" hidden
                                            accept="image/png, image/jpeg">
                                    </label>
                                    <button type="button" class="btn btn-outline-secondary account-image-reset mb-2"
                                        id="reset-photo">
                                        <i class="bx bx-reset d-block d-sm-none"></i>
                                        <span class="d-none d-sm-block">Reset</span>
                                    </button>
                                    <p class="text-muted mb-0">Allowed JPG or PNG. Max size of 2MB</p>
                                </div>
                            </div>
                        </div>

                        <h5 class="text-primary fw-bold">Classes</h5>
                        <hr class="my-3">

                        <!-- Classes -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Assign Classes</label>
                                <select name="assigned_classes[]" id="assigned_classes" multiple required>
                                    @foreach ($classes as $class)
                                        @php
                                            $adviser = $class->teachers->where('pivot.role', 'adviser')->first();
                                            $isSelected = in_array(
                                                $class->id,
                                                old('assigned_classes', $assignedClasses ?? []),
                                            );
                                        @endphp
                                        <option value="{{ $class->id }}" {{ $isSelected ? 'selected' : '' }}>
                                            {{ strtoupper($class->formattedGradeLevel ?? $class->grade_level) }} -
                                            {{ $class->section }}
                                            @if ($adviser)
                                                (Adviser: {{ $adviser->firstName }} {{ $adviser->lastName }})
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                                <small class="form-text text-muted">You can select multiple classes</small>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">Advisory Class</label>
                                <select name="advisory_class" id="advisory_class" class="form-select">
                                    <option value="">-- None --</option>
                                    @foreach ($classes as $class)
                                        <option value="{{ $class->id }}"
                                            {{ old('advisory_class', $advisoryClass) == $class->id ? 'selected' : '' }}>
                                            {{ strtoupper($class->formattedGradeLevel ?? $class->grade_level) }} -
                                            {{ $class->section }}
                                        </option>
                                    @endforeach
                                </select>
                                <small class="text-muted">Must be one of the assigned classes</small>
                            </div>
                        </div>

                        <hr class="my-3">

                        <h5 class="text-primary fw-bold">Personal Information</h5>

                        <!-- Names & Contact -->
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">First Name</label>
                                <input type="text" class="form-control" name="firstName"
                                    value="{{ old('firstName', $user->firstName) }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Middle Name</label>
                                <input type="text" class="form-control" name="middleName"
                                    value="{{ old('middleName', $user->middleName) }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Last Name</label>
                                <input type="text" class="form-control" name="lastName"
                                    value="{{ old('lastName', $user->lastName) }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Extension Name</label>
                                <input type="text" class="form-control" name="extName"
                                    value="{{ old('extName', $user->extName) }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Email</label>
                                <input type="email" class="form-control" name="email"
                                    value="{{ old('email', $user->email) }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Phone</label>
                                <input type="text" class="form-control" name="phone"
                                    value="{{ old('phone', $user->phone) }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Gender</label>
                                <select class="form-select" name="gender">
                                    <option value="">-- Select --</option>
                                    <option value="male" {{ old('gender', $user->gender) == 'male' ? 'selected' : '' }}>
                                        Male</option>
                                    <option value="female"
                                        {{ old('gender', $user->gender) == 'female' ? 'selected' : '' }}>Female</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Date of Birth</label>
                                <input type="date" class="form-control" name="dob"
                                    value="{{ old('dob', $user->dob) }}">
                            </div>
                        </div>

                        <hr class="my-3">
                        <h5 class="text-primary fw-bold">Address</h5>

                        <div class="row mt-3">
                            <div class="col-md-4">
                                <label class="form-label fw-bold">House No.</label>
                                <input type="text" class="form-control" name="house_no"
                                    value="{{ old('house_no', $user->house_no) }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Street</label>
                                <input type="text" class="form-control" name="street_name"
                                    value="{{ old('street_name', $user->street_name) }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Barangay</label>
                                <input type="text" class="form-control" name="barangay"
                                    value="{{ old('barangay', $user->barangay) }}">
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-4">
                                <label class="form-label fw-bold">City/Municipality</label>
                                <input type="text" class="form-control" name="municipality_city"
                                    value="{{ old('municipality_city', $user->municipality_city) }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Province</label>
                                <input type="text" class="form-control" name="province"
                                    value="{{ old('province', $user->province) }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Zip Code</label>
                                <input type="text" class="form-control" name="zip_code"
                                    value="{{ old('zip_code', $user->zip_code) }}">
                            </div>
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Update Teacher</button>
                    </div>
                </form>
            </div>
        </div>
        <!-- /Edit Teacher Modal -->

        <!-- Edit Parent Modal -->
        <div class="modal fade" id="editParentModal" data-bs-backdrop="static" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <form class="modal-content" action="{{ route('admin.user.update', $user->id) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="role" value="parent">

                    <div class="modal-header">
                        <h4 class="modal-title fw-bold text-primary">EDIT PARENT</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">First Name</label>
                                <input type="text" class="form-control" name="firstName"
                                    value="{{ old('firstName', $user->firstName) }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Last Name</label>
                                <input type="text" class="form-control" name="lastName"
                                    value="{{ old('lastName', $user->lastName) }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Email</label>
                                <input type="email" class="form-control" name="email"
                                    value="{{ old('email', $user->email) }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Phone</label>
                                <input type="text" class="form-control" name="phone"
                                    value="{{ old('phone', $user->phone) }}">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">Parent Type</label>
                                <select name="parent_type" class="form-select" required>
                                    <option value="mother"
                                        {{ old('parent_type', $user->parent_type) == 'mother' ? 'selected' : '' }}>Mother
                                    </option>
                                    <option value="father"
                                        {{ old('parent_type', $user->parent_type) == 'father' ? 'selected' : '' }}>Father
                                    </option>
                                    <option value="guardian"
                                        {{ old('parent_type', $user->parent_type) == 'guardian' ? 'selected' : '' }}>
                                        Guardian</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">Status</label>
                                <select name="status" class="form-select" required>
                                    <option value="active"
                                        {{ old('status', $user->status) == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive"
                                        {{ old('status', $user->status) == 'inactive' ? 'selected' : '' }}>Inactive
                                    </option>
                                    <option value="suspended"
                                        {{ old('status', $user->status) == 'suspended' ? 'selected' : '' }}>Suspended
                                    </option>
                                    <option value="banned"
                                        {{ old('status', $user->status) == 'banned' ? 'selected' : '' }}>Banned</option>
                                </select>
                            </div>

                            <!-- Link Students -->
                            <div class="col-12">
                                <label class="form-label fw-bold">Link Students</label>
                                <select name="students[]" class="form-select" multiple>
                                    @foreach ($allStudents as $student)
                                        <option value="{{ $student->id }}"
                                            {{ collect(old('students', $user->children->pluck('id')))->contains($student->id) ? 'selected' : '' }}>
                                            {{ $student->firstName }} {{ $student->lastName }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">Profile Photo</label>
                                <input type="file" class="form-control" name="profile_photo" accept="image/*">
                                @if ($user->profile_photo)
                                    <img src="{{ asset('storage/' . $user->profile_photo) }}" class="img-thumbnail mt-2"
                                        width="120">
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Update Parent</button>
                    </div>
                </form>
            </div>
        </div>
        <!-- /Edit Parent Modal -->



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

    <!-- Profile Photo Preview & Reset, Advisory Class Logic, Confirmation Dialog -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Profile photo preview & reset
            const fileInput = document.getElementById('upload');
            const resetBtn = document.getElementById('reset-photo');
            const imagePreview = document.getElementById('photo-preview');
            const originalImage = imagePreview.src;

            if (fileInput) {
                fileInput.addEventListener('change', e => {
                    const file = e.target.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = ev => imagePreview.src = ev.target.result;
                        reader.readAsDataURL(file);
                    }
                });
            }

            if (resetBtn) {
                resetBtn.addEventListener('click', () => {
                    fileInput.value = '';
                    imagePreview.src = originalImage;
                });
            }

            // Advisory must be one of assigned classes
            const assignedSelect = document.getElementById('assigned_classes');
            const advisorySelect = document.getElementById('advisory_class');
            const oldAdvisory = "{{ $advisoryClass ?? '' }}";

            function updateAdvisoryOptions() {
                const selected = Array.from(assignedSelect.selectedOptions).map(o => o.value);
                const currentAdvisory = "{{ old('advisory_class', $advisoryClass ?? '') }}";
                advisorySelect.innerHTML = '<option value="">-- None --</option>';

                assignedSelect.querySelectorAll('option').forEach(opt => {
                    if (selected.includes(opt.value)) {
                        const option = document.createElement('option');
                        option.value = opt.value;
                        option.textContent = opt.text;
                        if (opt.value === currentAdvisory) option.selected = true;
                        advisorySelect.appendChild(option);
                    }
                });
            }

            if (assignedSelect && advisorySelect) {
                updateAdvisoryOptions();
                assignedSelect.addEventListener('change', updateAdvisoryOptions);

                new TomSelect('#assigned_classes', {
                    plugins: ['remove_button'],
                    maxItems: null,
                    placeholder: "Select classes...",
                });
            }

            // Confirmation dialog before update
            const form = document.getElementById("editTeacherForm");
            if (form) {
                form.addEventListener("submit", function(e) {
                    e.preventDefault();
                    Swal.fire({
                        title: "Confirm Update",
                        text: "Are you sure you want to update this teacher?",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonText: "Yes, update",
                        cancelButtonText: "Cancel",
                        customClass: {
                            container: 'my-swal-container'
                        },
                    }).then(result => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            }
        });
    </script>

    <!-- SweetAlert for success and error messages -->
    <script>
        // Success alert
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

        // Registration error alert (email)
        @if ($errors->has('email'))
            Swal.fire({
                icon: 'error',
                title: 'Registration Error',
                text: '{{ $errors->first('email') }}',
                confirmButtonColor: '#dc3545',
                customClass: {
                    container: 'my-swal-container'
                }
            });
        @endif
    </script>

    <script src="https://cdn.jsdelivr.net/npm/tom-select/dist/js/tom-select.complete.min.js"></script>
@endpush

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/tom-select/dist/css/tom-select.css" rel="stylesheet">

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

        .ts-control {
            background-color: #e0f7fa;
            border-color: #42a5f5;
        }

        .ts-control .item {
            background-color: #4dd0e1;
            color: white;
            border-radius: 4px;
            padding: 3px 8px;
            margin-right: 4px;
        }

        .ts-dropdown .option.active {
            background-color: #e3f2fd;
            color: #1976d2;
        }
    </style>
@endpush
