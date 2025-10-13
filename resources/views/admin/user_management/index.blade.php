@extends('./layouts.main')

@section('title', 'Admin | User Management')

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

        <!-- Content -->
        <div class="container-xxl container-p-y">

            <!-- Cards -->
            <div class="row mb-4 g-3">
                <!-- Total Users -->
                <div class="col-6 col-md-3">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="card-title d-flex align-items-start justify-content-between">
                                <div class="avatar flex-shrink-0">
                                    <img src="{{ asset('assetsDashboard/img/icons/dashIcon/total_users.png') }}"
                                        alt="" class="rounded" />
                                </div>
                            </div>
                            <span class="fw-semibold d-block mb-1 text-primary">Total Users</span>
                            <h3 class="card-title mb-2">{{ $stats['totalUsers'] }}</h3>
                        </div>
                    </div>
                </div>

                <!-- Teachers -->
                <div class="col-6 col-md-3">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="card-title d-flex align-items-start justify-content-between">
                                <div class="avatar flex-shrink-0">
                                    <img src="{{ asset('assetsDashboard/img/icons/dashIcon/teachers.png') }}"
                                        alt="" class="rounded" />
                                </div>
                            </div>
                            <span class="fw-semibold d-block mb-1 text-primary">Teachers</span>
                            <h3 class="card-title mb-2">{{ $stats['totalTeachers'] }}</h3>
                        </div>
                    </div>
                </div>

                <!-- Admins -->
                <div class="col-6 col-md-3">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="card-title d-flex align-items-start justify-content-between">
                                <div class="avatar flex-shrink-0">
                                    <img src="{{ asset('assetsDashboard/img/icons/dashIcon/system-administration.png') }}"
                                        alt="" class="rounded" />
                                </div>
                            </div>
                            <span class="fw-semibold d-block mb-1 text-primary">Admins</span>
                            <h3 class="card-title mb-2">{{ $stats['totalAdmins'] }}</h3>
                        </div>
                    </div>
                </div>

                <!-- Parents -->
                <div class="col-6 col-md-3">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="card-title d-flex align-items-start justify-content-between">
                                <div class="avatar flex-shrink-0">
                                    <img src="{{ asset('assetsDashboard/img/icons/dashIcon/parents.png') }}"
                                        alt="" class="rounded" />
                                </div>
                            </div>
                            <span class="fw-semibold d-block mb-1 text-primary">Parents</span>
                            <h3 class="card-title mb-2">{{ $stats['totalParents'] }}</h3>
                        </div>
                    </div>
                </div>
            </div>

            <!-- User Management Table -->
            <div class="card shadow-sm">
                <div class="card-body">
                    <h3 class="card-title mb-3 fw-bold">User Management</h3>
                    {{-- <p class="text-muted">Manage all users in one place. Control access, assign roles, and monitor
                        activity.</p> --}}

                    <!-- Search & Filters -->
                    <div class="row g-2 mb-3 align-items-center">
                        <div class="col-md-4 col-sm-6">
                            <input type="text" class="form-control" placeholder="Search..." id="userSearch">
                        </div>
                        <div class="col-md-4 col-sm-3">
                            <select id="roleFilter" class="form-select">
                                <option value="">All Roles</option>
                                <option value="admin">Admin</option>
                                <option value="teacher">Teacher</option>
                                <option value="parent">Parent</option>
                            </select>
                        </div>
                        <div class="col-md-4 col-sm-3">
                            <select id="statusFilter" class="form-select">
                                <option value="">All Status</option>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                                <option value="suspended">Suspended</option>
                                <option value="banned">Banned</option>
                            </select>
                        </div>
                    </div>

                    <hr class="my-4" />

                    <!-- Search & Filters -->
                    <div class="d-flex justify-content-between align-items-center gap-2 mb-2">

                        <!-- Left: Table Length Selector + Bulk Dropdown -->
                        <div class="d-flex align-items-center gap-2">
                            <!-- Table Length Selector -->
                            <div>
                                <select id="tableLength" class="form-select">
                                    <option value="10" selected>10</option>
                                    <option value="25">25</option>
                                    <option value="50">50</option>
                                    <option value="100">100</option>
                                </select>
                            </div>

                            <!-- Bulk Status Dropdown Settings -->
                            <form id="bulkStatusForm" action="{{ route('admin.users.bulkUpdateStatus') }}"
                                method="POST" class="d-flex gap-2 d-none">
                                @csrf
                                <div id="bulkUserIds"></div> <!-- Will hold <input name="user_ids[]"> dynamically -->

                                <!-- Styled like per-row dropdown -->
                                <div class="dropdown d-inline-block">
                                    <button class="btn btn-outline-primary dropdown-toggle" type="button"
                                        id="bulkStatusDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                        Set Status
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="bulkStatusDropdown">
                                        <li>
                                            <button type="submit" class="dropdown-item text-success"
                                                onclick="setBulkStatus('active')">
                                                <i class="bx bx-check-circle text-success me-1"></i> Activate
                                            </button>
                                        </li>
                                        <li>
                                            <button type="submit" class="dropdown-item text-secondary"
                                                onclick="setBulkStatus('inactive')">
                                                <i class="bx bx-power-off text-secondary me-1"></i> Deactivate
                                            </button>
                                        </li>
                                        <li>
                                            <button type="submit" class="dropdown-item text-warning"
                                                onclick="setBulkStatus('suspended')">
                                                <i class="bx bx-pause-circle text-warning me-1"></i> Suspend
                                            </button>
                                        </li>
                                        <li>
                                            <button type="submit" class="dropdown-item text-danger"
                                                onclick="setBulkStatus('banned')">
                                                <i class="bx bx-block text-danger me-1"></i> Ban
                                            </button>
                                        </li>
                                    </ul>
                                </div>
                            </form>
                        </div>

                        <!-- Right: Add User Button -->
                        <div class="d-flex gap-2">
                            <button class="btn btn-primary d-flex align-items-center gap-1" id="openAddUserRole">
                                <i class="bx bx-user-plus"></i>
                                <span class="d-none d-sm-block"> Add User</span>
                            </button>
                        </div>
                    </div>
                    <!-- /Search & Filters -->

                    <!-- Table -->
                    <div class="table-responsive">
                        <table class="table table-hover align-middle" id="userTable">
                            <thead class="table-light text-center">
                                <tr>
                                    <th style="width: 1%;">
                                        <input class="form-check-input me-1" type="checkbox" id="selectAll">
                                    </th>
                                    <th class="text-start" style="width: 38%;">Full Name</th>
                                    <th style="width: 15%;">Last Active</th>
                                    <th style="width: 10%;">Role</th>
                                    <th style="width: 10%;">Email</th>
                                    <th style="width: 10%;">Access Status</th>
                                    <th style="width: 15%;">Joined</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $user)
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
                                                'admin' => asset(
                                                    'assetsDashboard/img/profile_pictures/admin_default_profile.jpg',
                                                ),
                                                'teacher' => asset(
                                                    'assetsDashboard/img/profile_pictures/teacher_default_profile.jpg',
                                                ),
                                                'parent' => asset(
                                                    'assetsDashboard/img/profile_pictures/parent_default_profile.jpg',
                                                ),
                                                default => 'https://ui-avatars.com/api/?name=' .
                                                    urlencode($user->full_name),
                                            };
                                        }

                                        // Role icon
                                        $roleIcon = match (strtolower($user->role)) {
                                            'admin' => '<i class="bx bx-cog text-info me-1"></i>',
                                            'teacher' => '<i class="bx bx-book-reader text-primary me-1"></i>',
                                            'parent' => '<i class="bx bx-home-heart text-warning me-1"></i>',
                                            default => '<i class="bi bi-person-fill text-secondary me-1"></i>',
                                        };

                                        $roleTextClass = match (strtolower($user->role)) {
                                            'admin' => 'text-info',
                                            'teacher' => 'text-primary',
                                            'parent' => 'text-warning',
                                            default => 'text-secondary',
                                        };
                                    @endphp

                                    <tr class="user-row" data-id="{{ $user->id }}"
                                        data-name="{{ strtolower($user->full_name) }}"
                                        data-email="{{ strtolower($user->email) }}"
                                        data-role="{{ strtolower($user->role) }}"
                                        data-status="{{ strtolower($user->status) }}">

                                        <td class="text-center">
                                            <input type="checkbox" class="user-checkbox form-check-input me-1"
                                                value="{{ $user->id }}">
                                        </td>
                                        <!-- Full Name with profile photo -->
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <a href="{{ route('admin.user.info', ['id' => $user->id]) }}">
                                                    <img src="{{ $profilePhoto }}" class="rounded-circle me-2"
                                                        width="30" height="30">
                                                    <span>{{ $user->full_name }}</span>
                                                </a>
                                            </div>
                                        </td>

                                        <!-- Last Active -->
                                        <td class="text-center last-active">
                                            @if ($user->is_online)
                                                <span class="badge bg-success">
                                                    <i class="bx bxs-circle me-1 small-circle-icon"></i>
                                                    Online
                                                </span>
                                            @else
                                                @if ($user->last_seen === 'Not signed in yet')
                                                    <span class="text-muted fw-bold">
                                                        {{ $user->last_seen }}
                                                    </span>
                                                @else
                                                    <span class="text-muted">
                                                        Active {{ $user->last_seen }}
                                                    </span>
                                                @endif
                                            @endif
                                        </td>

                                        <!-- Role -->
                                        <td class="text-center">
                                            <span class="{{ $roleTextClass }}">
                                                {!! $roleIcon !!} {{ ucfirst($user->role) }}
                                            </span>
                                        </td>

                                        <td class="text-center">{{ $user->email }}</td>

                                        <!-- Status -->
                                        <td class="text-center">
                                            <span
                                                class="badge
                                            @if ($user->status === 'active') bg-label-success
                                            @elseif($user->status === 'inactive') bg-label-secondary
                                            @elseif($user->status === 'suspended') bg-label-warning
                                            @elseif($user->status === 'banned') bg-label-danger @endif">
                                                {{ ucfirst($user->status) }}
                                            </span>
                                        </td>

                                        <!-- Joined -->
                                        <td>{{ $user->created_at->format('M d, Y') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination + Info -->
                    <div class="d-flex justify-content-between align-items-center px-3 py-3 border-top">
                        <div id="tableInfo" class="text-muted small"></div>
                        <nav aria-label="Page navigation">
                            <ul class="pagination mb-0" id="userPagination"></ul>
                        </nav>
                    </div>
                </div>
            </div>
            <!-- /User Management Table -->

        </div>
        <!-- / Content -->


        <div class="content-backdrop fade"></div>
    </div>
    <!-- Content wrapper -->

    <!-- Add Admin Modal -->
    <div class="modal fade" id="addAdminModal" data-bs-backdrop="static" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <form class="modal-content" action="{{ route('admin.user.create') }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="role" value="admin">

                <div class="modal-header">
                    <h4 class="modal-title fw-bold text-primary">REGISTER NEW ADMIN</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <!-- Profile Photo Upload with Preview and Default -->
                    <div class="row">
                        <div class="col mb-3 d-flex align-items-start align-items-sm-center gap-4">
                            <div class="mb-3">
                                <img id="photo-preview-admin"
                                    src="{{ asset('assetsDashboard/img/profile_pictures/admin_default_profile.jpg') }}"
                                    alt="Profile Preview" width="100" height="100" class="profile-preview"
                                    style="object-fit: cover; border-radius: 5%">
                            </div>

                            <div class="button-wrapper">
                                <label for="upload-admin" class="btn btn-warning me-2 mb-2" tabindex="0">
                                    <span class="d-none d-sm-block">Upload new photo</span>
                                    <i class="bx bx-upload d-block d-sm-none"></i>
                                    <input type="file" id="upload-admin" name="profile_photo"
                                        class="account-file-input" hidden accept="image/png, image/jpeg" />
                                </label>

                                <button type="button" class="btn btn-outline-secondary account-image-reset mb-2"
                                    id="reset-photo-admin">
                                    <i class="bx bx-reset d-block d-sm-none"></i>
                                    <span class="d-none d-sm-block">Reset</span>
                                </button>

                                <p class="text-muted mb-0">Allowed JPG or PNG. Max size of 2MB</p>
                            </div>
                        </div>
                    </div>
                    <!-- /Profile Photo Upload with Preview and Default -->

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">First Name</label>
                            <input type="text" class="form-control" name="firstName" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Middle Name</label>
                            <input type="text" class="form-control" name="middleName">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Last Name</label>
                            <input type="text" class="form-control" name="lastName" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Extension Name</label>
                            <input type="text" class="form-control" name="extName">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Email</label>
                            <input type="email" class="form-control" name="email" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Phone</label>
                            <input type="text" class="form-control" name="phone">
                        </div>
                        <!-- Password Input -->
                        <div class="col-md-6 form-password-toggle">
                            <label class="form-label fw-bold" for="password">Password</label>
                            <div class="input-group input-group-merge">
                                <input type="password" id="password" class="form-control" name="password" required
                                    autocomplete="new-password" placeholder="Enter your Password" />

                                @error('password')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror

                                <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                            </div>
                        </div>

                        <!-- Confirm Password Input -->
                        <div class="col-md-6 form-password-toggle">
                            <label class="form-label fw-bold" for="password_confirmation">Confirm Password</label>
                            <div class="input-group input-group-merge">
                                <input type="password" id="password_confirmation" class="form-control"
                                    name="password_confirmation" required autocomplete="new-password"
                                    placeholder="Confirm your Password" />

                                @error('password_confirmation')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror

                                <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" id="registerAdminBtn" class="btn btn-primary">Save Admin</button>
                </div>
            </form>
        </div>
    </div>
    <!-- /Add Admin Modal -->

    <!-- Add Teacher Modal -->
    <div class="modal fade" id="addTeacherModal" data-bs-backdrop="static" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <form class="modal-content" action="{{ route('admin.user.create') }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="role" value="teacher">

                <div class="modal-header">
                    <h4 class="modal-title fw-bold text-primary">REGISTER NEW TEACHER</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <!-- Profile Photo Upload with Preview and Default -->
                    <div class="row">
                        <div class="col mb-3 d-flex align-items-start align-items-sm-center gap-4">
                            <div class="mb-3">
                                <img id="photo-preview-teacher"
                                    src="{{ asset('assetsDashboard/img/profile_pictures/teacher_default_profile.jpg') }}"
                                    alt="Profile Preview" width="100" height="100" class="profile-preview"
                                    style="object-fit: cover; border-radius: 5%">
                            </div>

                            <div class="button-wrapper">
                                <label for="upload-teacher" class="btn btn-warning me-2 mb-2" tabindex="0">
                                    <span class="d-none d-sm-block">Upload new photo</span>
                                    <i class="bx bx-upload d-block d-sm-none"></i>
                                    <input type="file" id="upload-teacher" name="profile_photo"
                                        class="account-file-input" hidden accept="image/png, image/jpeg" />
                                </label>

                                <button type="button" class="btn btn-outline-secondary account-image-reset mb-2"
                                    id="reset-photo-teacher">
                                    <i class="bx bx-reset d-block d-sm-none"></i>
                                    <span class="d-none d-sm-block">Reset</span>
                                </button>

                                <p class="text-muted mb-0">Allowed JPG or PNG. Max size of 2MB</p>
                            </div>
                        </div>
                    </div>
                    <!-- /Profile Photo Upload -->

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">School Year</label>
                            <select name="selected_school_year" class="form-select" required>
                                <option value="" disabled
                                    {{ old('selected_school_year', $selectedYear) ? '' : 'selected' }}>Select School Year
                                </option>
                                @foreach ($schoolYears as $year)
                                    <option value="{{ $year }}"
                                        {{ old('selected_school_year', $selectedYear) == $year ? 'selected' : '' }}>
                                        {{ $year }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">First Name</label>
                            <input type="text" class="form-control" name="firstName" value="{{ old('firstName') }}"
                                required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Middle Name</label>
                            <input type="text" class="form-control" name="middleName"
                                value="{{ old('middleName') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Last Name</label>
                            <input type="text" class="form-control" name="lastName" value="{{ old('lastName') }}"
                                required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Extension Name</label>
                            <input type="text" class="form-control" name="extName" value="{{ old('extName') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Email</label>
                            <input type="email" class="form-control" name="email" value="{{ old('email') }}"
                                required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Phone</label>
                            <input type="text" class="form-control" name="phone" value="{{ old('phone') }}">
                        </div>

                        <!-- Gender Field -->
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Gender</label>
                            <select class="form-select" name="gender" required>
                                <option value="" disabled {{ old('gender') ? '' : 'selected' }}>Select Gender
                                </option>
                                <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                                <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                            </select>
                        </div>

                        <!-- Date of Birth Field -->
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Date of Birth</label>
                            <input type="date" class="form-control" name="dob" value="{{ old('dob') }}">
                        </div>

                        <!-- Assigned Classes -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Assign Classes</label>
                            <select name="assigned_classes[]" id="assigned_classes" multiple required>
                                @foreach ($allClasses as $class)
                                    @php
                                        $adviser = $class->teachers->where('pivot.role', 'adviser')->first();
                                        $hasAdviser = !is_null($adviser);
                                        $isSelected = collect(old('assigned_classes'))->contains($class->id);
                                    @endphp
                                    <option value="{{ $class->id }}" {{ $isSelected ? 'selected' : '' }}>
                                        {{ strtoupper($class->formattedGradeLevel ?? $class->grade_level) }}
                                        - {{ $class->section }}
                                        @if ($hasAdviser)
                                            ({{ $adviser->firstName }} {{ $adviser->lastName }})
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                            <small class="form-text text-muted text-center">
                                You can select multiple classes
                            </small>
                        </div>

                        <!-- Advisory Class -->
                        <div class="col-md-6 mb-3">
                            <label for="advisory_class" class="form-label fw-bold">Select Advisory Class</label>
                            <select name="advisory_class" id="advisory_class" class="form-select"
                                data-old="{{ old('advisory_class') }}">
                                <option value="">-- Select advisory class from assigned --</option>
                                {{-- Options injected dynamically --}}
                            </select>
                            <small class="form-text text-muted text-center">Select an advisory
                                class from the assigned classes, or leave empty if none.</small>
                        </div>

                        <!-- Password Input -->
                        <div class="col-md-6 form-password-toggle">
                            <label class="form-label fw-bold" for="password">Password</label>
                            <div class="input-group input-group-merge">
                                <input type="password" id="password" class="form-control" name="password" required
                                    autocomplete="new-password" placeholder="Enter your Password" />

                                @error('password')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror

                                <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                            </div>
                        </div>

                        <!-- Confirm Password Input -->
                        <div class="col-md-6 form-password-toggle">
                            <label class="form-label fw-bold" for="password_confirmation">Confirm Password</label>
                            <div class="input-group input-group-merge">
                                <input type="password" id="password_confirmation" class="form-control"
                                    name="password_confirmation" required autocomplete="new-password"
                                    placeholder="Confirm your Password" />

                                @error('password_confirmation')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror

                                <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                            </div>
                        </div>

                        <hr class="my-4" />

                        <h5 class="fw-bold text-primary">Address</h5>

                        <div class="row">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">House No.</label>
                                <input type="text" class="form-control" name="house_no"
                                    value="{{ old('house_no') }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Street Name</label>
                                <input type="text" class="form-control" name="street_name"
                                    value="{{ old('street_name') }}">
                            </div>
                        </div>

                        <div class="row mt-2">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Barangay</label>
                                <input type="text" class="form-control" name="barangay"
                                    value="{{ old('barangay') }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Municipality/City</label>
                                <input type="text" class="form-control" name="municipality_city"
                                    value="{{ old('municipality_city') }}">
                            </div>
                        </div>

                        <div class="row mt-2">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Province</label>
                                <input type="text" class="form-control" name="province"
                                    value="{{ old('province') }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Zip Code</label>
                                <input type="text" class="form-control" name="zip_code"
                                    value="{{ old('zip_code') }}">
                            </div>
                        </div>

                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" id="registerTeacherBtn" class="btn btn-primary">Save Teacher</button>
                </div>
            </form>
        </div>
    </div>
    <!-- /Add Teacher Modal -->

    <!-- Add Parent Modal -->
    <div class="modal fade" id="addParentModal" data-bs-backdrop="static" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <form class="modal-content" action="{{ route('admin.user.create') }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="role" value="parent">

                <div class="modal-header">
                    <h4 class="modal-title fw-bold text-primary">REGISTER NEW PARENT</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <!-- Profile Photo Upload with Preview -->
                    <div class="row">
                        <div class="col mb-3 d-flex align-items-start align-items-sm-center gap-4">
                            <div class="mb-3">
                                <img id="photo-preview-parent"
                                    src="{{ asset('assetsDashboard/img/profile_pictures/parent_default_profile.jpg') }}"
                                    width="100" height="100" class="profile-preview"
                                    style="object-fit: cover; border-radius: 5%; background-color: #e9ecef;">
                            </div>

                            <div class="button-wrapper">
                                <label for="upload-parent" class="btn btn-warning me-2 mb-2" tabindex="0">
                                    <span class="d-none d-sm-block">Upload new photo</span>
                                    <i class="bx bx-upload d-block d-sm-none"></i>
                                    <input type="file" id="upload-parent" name="profile_photo"
                                        class="account-file-input" hidden accept="image/png, image/jpeg" />
                                </label>

                                <button type="button" class="btn btn-outline-secondary account-image-reset mb-2"
                                    id="reset-photo-parent">
                                    <i class="bx bx-reset d-block d-sm-none"></i>
                                    <span class="d-none d-sm-block">Reset</span>
                                </button>

                                <p class="text-muted mb-0">Allowed JPG or PNG. Max size of 2MB</p>
                            </div>
                        </div>
                    </div>
                    <!-- /Profile Photo Upload with Preview -->

                    <div class="row g-3 mt-3">

                        <!-- Names & Contact -->
                        <div class="col-md-6">
                            <label class="form-label fw-bold">First Name</label>
                            <input type="text" class="form-control" name="firstName" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Middle Name</label>
                            <input type="text" class="form-control" name="middleName">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Last Name</label>
                            <input type="text" class="form-control" name="lastName" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Extension Name</label>
                            <input type="text" class="form-control" name="extName">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Email</label>
                            <input type="email" class="form-control" name="email" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Phone</label>
                            <input type="tel" class="form-control" name="phone">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Parent Type</label>
                            <select class="form-select" name="parent_type" required>
                                <option value="" disabled selected>Select type</option>
                                <option value="mother">Mother</option>
                                <option value="father">Father</option>
                                <option value="guardian">Guardian</option>
                            </select>
                            <small class="text-muted">Select Type(Mother, Father, or Guardian)</small>
                        </div>

                        <!-- Date of Birth Field -->
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Date of Birth</label>
                            <input type="date" class="form-control" name="dob" value="{{ old('dob') }}">
                        </div>
                    </div>

                    <!-- Link Students -->
                    <div class="col-12 mt-3">
                        <label class="form-label fw-bold">Link Students</label>
                        <select id="link_students" name="students[]" multiple required></select>
                        <small class="form-text text-muted">You can search and select multiple students.</small>
                    </div>

                    <hr class="my-4" />
                    <h5 class="fw-bold text-primary">Address</h5>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">House No.</label>
                            <input type="text" class="form-control" name="house_no" value="{{ old('house_no') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Street Name</label>
                            <input type="text" class="form-control" name="street_name"
                                value="{{ old('street_name') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Barangay</label>
                            <input type="text" class="form-control" name="barangay" value="{{ old('barangay') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Municipality/City</label>
                            <input type="text" class="form-control" name="municipality_city"
                                value="{{ old('municipality_city') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Province</label>
                            <input type="text" class="form-control" name="province" value="{{ old('province') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Zip Code</label>
                            <input type="text" class="form-control" name="zip_code" value="{{ old('zip_code') }}">
                        </div>
                    </div>

                    <div class="alert alert-info text-warning mt-3">
                        <i class="bx bx-info-circle fs-4 text-warning"></i> Parent accounts don’t require a password.
                        They log in via Google using their registered email.
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary text-white">Save Parent</button>
                </div>
            </form>
        </div>
    </div>
    <!-- /Add Parent Modal -->

    <!-- Overlay -->
    <div class="layout-overlay layout-menu-toggle"></div>
@endsection

@push('scripts')
    <!-- SweetAlert2 for Logout Confirmation -->
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

    <!-- Pagination, Search, Filter Logic -->
    <script>
        let allUserRows = [];
        const visibleRowsMap = {};
        let currentPage = 1;
        let rowsPerPage = 10; // default

        function paginateUsers(tableId, paginationId, maxVisiblePages = 10) {
            const pagination = document.getElementById(paginationId);
            const rows = visibleRowsMap[tableId] || [];

            function showPage(page) {
                const totalEntries = rows.length;
                const totalPages = Math.max(1, Math.ceil(totalEntries / rowsPerPage));
                currentPage = Math.min(Math.max(1, page), totalPages);
                const start = (currentPage - 1) * rowsPerPage;
                const end = Math.min(start + rowsPerPage, totalEntries);

                // Hide all first
                allUserRows.forEach(r => r.style.display = "none");

                // Show only the slice
                rows.slice(start, end).forEach(r => r.style.display = "table-row");

                // Update info text
                const tableInfo = document.getElementById("tableInfo");
                if (totalEntries > 0) {
                    tableInfo.textContent = `Showing ${start + 1} to ${end} of ${totalEntries} entries`;
                } else {
                    tableInfo.textContent = "Showing 0 to 0 of 0 entries";
                }

                // Build pagination (always visible)
                pagination.innerHTML = "";
                const ul = document.createElement("ul");
                ul.className = "pagination mb-0";

                // Prev
                const prev = document.createElement("li");
                prev.className = `page-item ${currentPage === 1 ? 'disabled' : ''}`;
                prev.innerHTML = `<a class="page-link text-primary" href="javascript:void(0);">&laquo;</a>`;
                prev.onclick = () => currentPage > 1 && showPage(currentPage - 1);
                ul.appendChild(prev);

                let startPage = Math.max(1, currentPage - Math.floor(maxVisiblePages / 2));
                let endPage = Math.min(totalPages, startPage + maxVisiblePages - 1);

                for (let i = startPage; i <= endPage; i++) {
                    const li = document.createElement("li");
                    li.className = `page-item ${i === currentPage ? 'active' : ''}`;
                    li.innerHTML = `<a class="page-link" href="javascript:void(0);">${i}</a>`;
                    li.onclick = () => showPage(i);
                    ul.appendChild(li);
                }

                // Next
                const next = document.createElement("li");
                next.className = `page-item ${currentPage === totalPages ? 'disabled' : ''}`;
                next.innerHTML = `<a class="page-link text-primary" href="javascript:void(0);">&raquo;</a>`;
                next.onclick = () => currentPage < totalPages && showPage(currentPage + 1);
                ul.appendChild(next);

                pagination.appendChild(ul);
            }

            showPage(currentPage);
        }

        document.addEventListener("DOMContentLoaded", () => {
            const tableId = "userTable";
            const paginationId = "userPagination";
            allUserRows = Array.from(document.querySelectorAll("#userTable tbody tr.user-row"));

            // Initialize
            visibleRowsMap[tableId] = allUserRows;
            paginateUsers(tableId, paginationId);

            // Table length selector
            document.getElementById("tableLength").addEventListener("change", function() {
                rowsPerPage = parseInt(this.value);
                currentPage = 1;
                paginateUsers(tableId, paginationId);
            });

            // Search + Filter
            document.getElementById("userSearch").addEventListener("input", filterUsers);
            document.getElementById("roleFilter").addEventListener("change", filterUsers);
            document.getElementById("statusFilter").addEventListener("change", filterUsers);

            function filterUsers() {
                const query = document.getElementById("userSearch").value.trim().toLowerCase();
                const role = document.getElementById("roleFilter").value;
                const status = document.getElementById("statusFilter").value;

                const filteredRows = allUserRows.filter(row => {
                    const matchesSearch =
                        query === "" ||
                        row.dataset.name.includes(query) ||
                        row.dataset.email.includes(query);
                    const matchesRole = role === "" || row.dataset.role === role;
                    const matchesStatus = status === "" || row.dataset.status === status;
                    return matchesSearch && matchesRole && matchesStatus;
                });

                visibleRowsMap[tableId] = filteredRows;
                currentPage = 1;
                paginateUsers(tableId, paginationId);
            }
        });
    </script>

    <!-- Auto-refresh User Online Status -->
    <script>
        // Auto-refresh user online status every 1s
        function refreshUserStatus() {
            fetch("{{ url('/admin/user-status-refresh') }}")
                .then(res => res.json())
                .then(data => {
                    let tbody = document.querySelector("#userTable tbody");
                    let rows = Array.from(document.querySelectorAll("tr.user-row"));

                    // Update status cells
                    rows.forEach(row => {
                        let userId = row.dataset.id;
                        let lastActiveCell = row.querySelector(".last-active");

                        if (data[userId]) {
                            if (data[userId].is_online) {
                                lastActiveCell.innerHTML = `
                                <span class="badge bg-success">
                                    <i class="bx bxs-circle me-1 small-circle-icon"></i>
                                    Online
                                </span>`;
                                row.dataset.online = "1";
                            } else {
                                if (data[userId].last_seen === "Not signed in yet") {
                                    lastActiveCell.innerHTML = `
                                    <span class="text-muted fw-bold">
                                        Not signed in yet
                                    </span>`;
                                } else {
                                    lastActiveCell.innerHTML = `
                                    <span class="text-muted">
                                        Active ${data[userId].last_seen}
                                    </span>`;
                                }
                                row.dataset.online = "0";
                            }
                        }
                    });

                    // Reorder rows: online first, then alphabetically
                    rows.sort((a, b) => {
                        if (a.dataset.online !== b.dataset.online) {
                            return b.dataset.online - a.dataset.online;
                        }
                        return a.dataset.name.localeCompare(b.dataset.name);
                    });

                    rows.forEach(row => tbody.appendChild(row));

                    // ✅ Don’t reset filters when refreshing
                    allUserRows = Array.from(document.querySelectorAll("#userTable tbody tr.user-row"));

                    // If filters are active, re-run filterUsers()
                    const query = document.getElementById("userSearch").value.trim();
                    const role = document.getElementById("roleFilter").value;
                    const status = document.getElementById("statusFilter").value;

                    if (query || role || status) {
                        filterUsers(); // 🔑 keep filter active
                    } else {
                        visibleRowsMap["userTable"] = allUserRows;
                        paginateUsers("userTable", "userPagination");
                    }
                })
                .catch(err => console.error("Error refreshing user status:", err));
        }

        // Run every 30s (use 1000ms only for testing)
        setInterval(refreshUserStatus, 1000);
    </script>

    <!-- Select All and Bulk Actions -->
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const selectAll = document.getElementById("selectAll");
            const bulkForm = document.getElementById("bulkStatusForm");
            const bulkUserIdsContainer = document.getElementById("bulkUserIds");

            // ✅ Helper: check if any visible checkbox is selected
            function toggleBulkForm() {
                const visibleCheckboxes = Array.from(document.querySelectorAll("#userTable tbody tr.user-row"))
                    .filter(row => row.style.display !== "none") // only visible
                    .map(row => row.querySelector(".user-checkbox"));

                const anyChecked = visibleCheckboxes.some(cb => cb.checked);
                bulkForm.classList.toggle("d-none", !anyChecked);
            }

            // ✅ Highlight rows when checkbox is toggled
            function toggleRowHighlight(cb) {
                const row = cb.closest("tr");
                if (cb.checked) {
                    row.classList.add("row-highlight");
                } else {
                    row.classList.remove("row-highlight");
                }
            }

            // ✅ Select all only visible rows
            selectAll.addEventListener("change", () => {
                const visibleCheckboxes = Array.from(document.querySelectorAll(
                        "#userTable tbody tr.user-row"))
                    .filter(row => row.style.display !== "none") // only visible
                    .map(row => row.querySelector(".user-checkbox"));

                visibleCheckboxes.forEach(cb => {
                    cb.checked = selectAll.checked;
                    toggleRowHighlight(cb); // highlight or remove highlight
                });

                toggleBulkForm();
            });

            // ✅ Individual checkbox toggle
            document.querySelectorAll(".user-checkbox").forEach(cb => {
                cb.addEventListener("change", () => {
                    toggleRowHighlight(cb);
                    toggleBulkForm();
                });
            });

            // ✅ Collect checked IDs before submit
            bulkForm.addEventListener("submit", (e) => {
                const selected = Array.from(document.querySelectorAll(".user-checkbox:checked"))
                    .map(cb => cb.value);

                if (selected.length === 0) {
                    e.preventDefault();
                    alert("Please select at least one user.");
                    return;
                }

                // Clear old inputs
                bulkUserIdsContainer.innerHTML = "";

                // Create hidden inputs for each selected user
                selected.forEach(id => {
                    const input = document.createElement("input");
                    input.type = "hidden";
                    input.name = "user_ids[]";
                    input.value = id;
                    bulkUserIdsContainer.appendChild(input);
                });
            });
        });
    </script>

    <!-- Bulk Status Update Logic -->
    <script>
        function setBulkStatus(status) {
            event.preventDefault(); // prevent immediate submit
            const bulkForm = document.getElementById("bulkStatusForm");
            const checkboxes = document.querySelectorAll(".user-checkbox:checked");
            const bulkUserIdsContainer = document.getElementById("bulkUserIds");

            if (checkboxes.length === 0) {
                Swal.fire({
                    icon: "warning",
                    title: "No users selected",
                    text: "Please select at least one user.",
                    confirmButtonColor: "#3085d6",
                });
                return;
            }

            let statusLabel = status.charAt(0).toUpperCase() + status.slice(1);

            Swal.fire({
                title: `Are you sure?`,
                text: `You are about to set ${checkboxes.length} user(s) to "${statusLabel}".`,
                icon: "question",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: `Yes, set to ${statusLabel}`,
                cancelButtonText: "Cancel"
            }).then((result) => {
                if (result.isConfirmed) {
                    // Clear previous inputs
                    bulkUserIdsContainer.innerHTML = "";

                    // Add selected IDs
                    checkboxes.forEach(cb => {
                        const input = document.createElement("input");
                        input.type = "hidden";
                        input.name = "user_ids[]";
                        input.value = cb.value;
                        bulkUserIdsContainer.appendChild(input);
                    });

                    // Add status input
                    const statusInput = document.createElement("input");
                    statusInput.type = "hidden";
                    statusInput.name = "status";
                    statusInput.value = status;
                    bulkUserIdsContainer.appendChild(statusInput);

                    // Submit form
                    bulkForm.submit();
                }
            });
        }
    </script>

    <!-- Profile photo preview & reset for all roles -->
    <script>
        ['teacher', 'admin', 'parent'].forEach(role => {
            const uploadInput = document.getElementById(`upload-${role}`);
            const previewImg = document.getElementById(`photo-preview-${role}`);
            const resetBtn = document.getElementById(`reset-photo-${role}`);
            const defaultImage = `/assetsDashboard/img/profile_pictures/${role}_default_profile.jpg`;

            if (uploadInput && previewImg && resetBtn) {
                uploadInput.addEventListener('change', function(e) {
                    const file = e.target.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = e => previewImg.src = e.target.result;
                        reader.readAsDataURL(file);
                    }
                });

                resetBtn.addEventListener('click', function() {
                    uploadInput.value = '';
                    previewImg.src = defaultImage;
                });
            }
        });
    </script>

    <!-- Add User Role Selection -->
    <script>
        document.getElementById('openAddUserRole').addEventListener('click', function() {
            Swal.fire({
                title: 'Select User Role',
                text: 'Which type of user would you like to add?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Continue',
                input: 'select',
                customClass: {
                    container: 'my-swal-container'
                },
                inputOptions: {
                    admin: 'Admin',
                    teacher: 'Teacher',
                    parent: 'Parent'
                },
                inputPlaceholder: 'Choose a role',
                preConfirm: (role) => {
                    if (!role) {
                        Swal.showValidationMessage('Please select a role first');
                    }
                    return role;
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    const role = result.value;
                    if (role === 'admin') {
                        new bootstrap.Modal(document.getElementById('addAdminModal')).show();
                    } else if (role === 'teacher') {
                        new bootstrap.Modal(document.getElementById('addTeacherModal')).show();
                    } else if (role === 'parent') {
                        new bootstrap.Modal(document.getElementById('addParentModal')).show();
                    }
                }
            });
        });
    </script>

    <!-- Teacher Registration Validation and Confirmation -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('#addTeacherModal form');
            const registerBtn = document.getElementById('registerTeacherBtn');

            registerBtn.addEventListener('click', function(e) {
                e.preventDefault();

                let allFilled = true;
                let passwordErrors = [];
                form.querySelectorAll('[required]').forEach(field => {
                    if (!field.value.trim()) {
                        allFilled = false;
                        field.classList.add('is-invalid');
                    } else {
                        field.classList.remove('is-invalid');
                    }
                });

                const password = form.password.value;
                const passwordConfirm = form.password_confirmation.value;

                if (password.length < 8) passwordErrors.push("Password must be at least 8 characters.");
                if (!/[a-z]/.test(password)) passwordErrors.push(
                    "Password must include at least one lowercase letter.");
                if (!/[A-Z]/.test(password)) passwordErrors.push(
                    "Password must include at least one uppercase letter.");
                if (!/[0-9]/.test(password)) passwordErrors.push(
                    "Password must include at least one number.");
                if (!/[@$!%*#?&]/.test(password)) passwordErrors.push(
                    "Password must include at least one special character (@$!%*#?&).");
                if (password !== passwordConfirm) passwordErrors.push(
                    "Password confirmation does not match.");

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

                if (passwordErrors.length) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Invalid Password',
                        html: passwordErrors.join('<br>'),
                        confirmButtonColor: '#dc3545',
                        customClass: {
                            container: 'my-swal-container'
                        }
                    });
                    return;
                }

                Swal.fire({
                    title: "Register Teacher?",
                    text: "Are you sure all the details are correct?",
                    icon: "question",
                    showCancelButton: true,
                    confirmButtonColor: "#06D001",
                    cancelButtonColor: "#6c757d",
                    confirmButtonText: "Yes, register",
                    cancelButtonText: "Cancel",
                    customClass: {
                        container: 'my-swal-container'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    </script>

    <!-- Admin Registration Validation and Confirmation -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('#addAdminModal form');
            const registerBtn = document.getElementById('registerAdminBtn');

            registerBtn.addEventListener('click', function(e) {
                e.preventDefault();

                let allFilled = true;
                let passwordErrors = [];
                form.querySelectorAll('[required]').forEach(field => {
                    if (!field.value.trim()) {
                        allFilled = false;
                        field.classList.add('is-invalid');
                    } else {
                        field.classList.remove('is-invalid');
                    }
                });

                const password = form.password.value;
                const passwordConfirm = form.password_confirmation.value;

                if (password.length < 8) passwordErrors.push("Password must be at least 8 characters.");
                if (!/[a-z]/.test(password)) passwordErrors.push(
                    "Password must include at least one lowercase letter.");
                if (!/[A-Z]/.test(password)) passwordErrors.push(
                    "Password must include at least one uppercase letter.");
                if (!/[0-9]/.test(password)) passwordErrors.push(
                    "Password must include at least one number.");
                if (!/[@$!%*#?&]/.test(password)) passwordErrors.push(
                    "Password must include at least one special character (@$!%*#?&).");
                if (password !== passwordConfirm) passwordErrors.push(
                    "Password confirmation does not match.");

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

                if (passwordErrors.length) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Invalid Password',
                        html: passwordErrors.join('<br>'),
                        confirmButtonColor: '#dc3545',
                        customClass: {
                            container: 'my-swal-container'
                        }
                    });
                    return;
                }

                Swal.fire({
                    title: "Register Admin?",
                    text: "Are you sure all the details are correct?",
                    icon: "question",
                    showCancelButton: true,
                    confirmButtonColor: "#06D001",
                    cancelButtonColor: "#6c757d",
                    confirmButtonText: "Yes, register",
                    cancelButtonText: "Cancel",
                    customClass: {
                        container: 'my-swal-container'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });

            // Advisory class options update
            const classesWithAdvisers = @json($allClasses->filter(fn($class) => $class->teachers->where('pivot.role', 'adviser')->isNotEmpty())->pluck('id'));
            const assignedSelect = document.getElementById('assigned_classes');
            const advisorySelect = document.getElementById('advisory_class');
            const oldAdvisory = advisorySelect.dataset.old;

            function updateAdvisoryOptions() {
                const selectedAssigned = Array.from(assignedSelect.selectedOptions).map(opt => parseInt(opt.value));
                advisorySelect.innerHTML = '<option value="">-- Select advisory class from assigned --</option>';
                Array.from(assignedSelect.options).forEach(option => {
                    const classId = parseInt(option.value);
                    if (selectedAssigned.includes(classId) && !classesWithAdvisers.includes(classId)) {
                        const newOption = document.createElement('option');
                        newOption.value = option.value;
                        newOption.textContent = option.textContent;
                        if (oldAdvisory && option.value === oldAdvisory) newOption.selected = true;
                        advisorySelect.appendChild(newOption);
                    }
                });
            }
            updateAdvisoryOptions();
            assignedSelect.addEventListener('change', updateAdvisoryOptions);
        });
    </script>

    <!-- Parent Registration Validation and Confirmation -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Profile photo preview & reset
            const fileInputParent = document.getElementById('upload-parent');
            const resetBtnParent = document.getElementById('reset-photo-parent');
            const imagePreviewParent = document.getElementById('photo-preview-parent');
            const originalImageParent = imagePreviewParent.src;

            if (fileInputParent) {
                fileInputParent.addEventListener('change', e => {
                    const file = e.target.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = ev => imagePreviewParent.src = ev.target.result;
                        reader.readAsDataURL(file);
                    }
                });
            }

            if (resetBtnParent) {
                resetBtnParent.addEventListener('click', () => {
                    fileInputParent.value = '';
                    imagePreviewParent.src = originalImageParent;
                });
            }

            // TomSelect for linking students
            if (document.getElementById('link_students')) {
                new TomSelect('#link_students', {
                    plugins: ['remove_button'],
                    maxItems: null,
                    placeholder: "Search students by name or LRN...",
                    valueField: 'id',
                    labelField: 'text',
                    searchField: 'text',
                    load: function(query, callback) {
                        if (!query.length) return callback();
                        fetch("{{ route('students.search') }}?q=" + encodeURIComponent(query))
                            .then(response => response.json())
                            .then(data => {
                                callback(data.map(student => ({
                                    id: student.id,
                                    text: student.student_lrn + " - " + student
                                        .student_fName + " " + student.student_lName
                                })));
                            }).catch(() => {
                                callback();
                            });
                    }
                });
            }
        });
    </script>

    <!-- SweetAlert for success and error messages -->
    {{-- <script>
        // Success alert
        @if (session('success'))
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: "{{ session('success') }}",
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                customClass: {
                    container: 'my-swal-container'
                }
            });
        @endif
    </script> --}}

    <script>
        new TomSelect('#assigned_classes', {
            plugins: ['remove_button'],
            maxItems: null,
            placeholder: "Select classes...",
        });
    </script>
@endpush

@push('styles')
    <style>
        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .card-hover {
            transition: all 0.3s ease;
        }

        .small-circle-icon {
            font-size: 0.6rem;
            /* adjust until it feels right */
            vertical-align: middle;
        }

        /* Make checkboxes use pointer cursor */
        .user-checkbox,
        #selectAll {
            cursor: pointer;
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

        .row-highlight {
            background-color: #e6f7ff !important;
            /* light blue highlight */
            transition: background-color 0.3s ease;
        }
    </style>
@endpush
