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
                            <div class="text-light">All Teachers</div>
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
                <a href="{{ route('user.management') }}" class="menu-link">
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
                    <h4 class="card-title mb-3">User Management</h4>
                    {{-- <p class="text-muted">Manage all users in one place. Control access, assign roles, and monitor
                        activity.</p> --}}

                    <!-- Search & Filters -->
                    <div class="row g-2 mb-3 align-items-center">
                        <div class="col-md-4 col-sm-6">
                            <input type="text" class="form-control" placeholder="Search..." id="userSearch">
                        </div>
                        <div class="col-md-2 col-sm-3">
                            <select id="roleFilter" class="form-select">
                                <option value="">All Roles</option>
                                <option value="admin">Admin</option>
                                <option value="teacher">Teacher</option>
                                <option value="parent">Parent</option>
                            </select>
                        </div>
                        <div class="col-md-2 col-sm-3">
                            <select id="statusFilter" class="form-select">
                                <option value="">All Status</option>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                                <option value="suspended">Suspended</option>
                                <option value="banned">Banned</option>
                            </select>
                        </div>
                        <div class="col-md-4 text-end">
                            <button class="btn btn-sm btn-primary">Export</button>
                            <a href="{{ route('register') }}" class="btn btn-sm btn-success">+ Add User</a>
                        </div>
                    </div>

                    <!-- Table -->
                    <div class="table-responsive">
                        <table class="table table-hover align-middle" id="userTable">
                            <thead class="table-light text-center">
                                <tr>
                                    <th style="width: 20%;">Full Name</th>
                                    <th style="width: 15%;">Last Active</th>
                                    <th style="width: 15%;">Role</th>
                                    <th style="width: 15%;">Email</th>
                                    <th style="width: 10%;">Access Status</th>
                                    <th style="width: 10%;">Joined</th>
                                    <th style="width: 15%;" class="text-center">Actions</th>
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
                                                    'assetsDashboard/img/profile_pictures/admin_profile.png',
                                                ),
                                                'teacher' => asset(
                                                    'assetsDashboard/img/profile_pictures/teachers_default_profile.jpg',
                                                ),
                                                'parent' => asset(
                                                    'assetsDashboard/img/profile_pictures/parents_default_profile.png',
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

                                        <!-- Full Name with profile photo -->
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="{{ $profilePhoto }}" alt="{{ $user->full_name }}"
                                                    class="rounded-circle me-2" width="36" height="36">
                                                <span>{{ $user->full_name }}</span>
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
                                        <td class="text-center">{{ $user->created_at->format('M d, Y') }}</td>

                                        <!-- Actions -->
                                        <td class="text-center">
                                            <a href="#" class="btn btn-sm btn-outline-primary">Edit</a>
                                            <a href="#" class="btn btn-sm btn-outline-danger">Delete</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-start px-3 py-3 border-top">
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

    <!-- Overlay -->
    <div class="layout-overlay layout-menu-toggle"></div>
@endsection

@push('scripts')
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

    <script>
        let allUserRows = [];
        const visibleRowsMap = {};
        let currentPage = 1; // 🔑 keep track of current page

        function paginateUsers(tableId, paginationId, rowsPerPage = 10, maxVisiblePages = 10) {
            const table = document.getElementById(tableId);
            const pagination = document.getElementById(paginationId);
            const rows = visibleRowsMap[tableId] || [];

            function showPage(page) {
                const totalPages = Math.ceil(rows.length / rowsPerPage);
                currentPage = Math.min(Math.max(1, page), totalPages); // update current page safely
                const start = (currentPage - 1) * rowsPerPage;
                const end = start + rowsPerPage;

                // Hide all first
                allUserRows.forEach(r => r.style.display = "none");

                // Show only the slice
                rows.slice(start, end).forEach(r => r.style.display = "table-row");

                // Build pagination
                pagination.innerHTML = "";
                if (totalPages <= 1) return;

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

            showPage(currentPage); // 🔑 keep current page after refresh/filter
        }

        document.addEventListener("DOMContentLoaded", () => {
            const tableId = "userTable";
            const paginationId = "userPagination";
            allUserRows = Array.from(document.querySelectorAll("#userTable tbody tr.user-row"));

            // Initialize
            visibleRowsMap[tableId] = allUserRows;
            paginateUsers(tableId, paginationId);

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
                currentPage = 1; // reset to page 1 when filtering
                paginateUsers(tableId, paginationId);
            }
        });
    </script>

    <script>
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

        .small-circle-icon {
            font-size: 0.6rem;
            /* adjust until it feels right */
            vertical-align: middle;
        }
    </style>
@endpush
