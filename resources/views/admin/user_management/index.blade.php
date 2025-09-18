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
                    <h4 class="card-title mb-3">User Management</h4>
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

                    <div class="d-flex justify-content-between align-items-center gap-2 mb-2">

                        <!-- Left: Table Length Selector -->
                        <div>
                            <select id="tableLength" class="form-select">
                                <option value="10" selected>10</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                            </select>
                        </div>

                        <!-- Right: Bulk Actions + Buttons -->
                        <div class="d-flex gap-2">

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

                            <a href="{{ route('register') }}" class="btn btn-success">+ Add User</a>
                        </div>
                    </div>

                    <!-- Table -->
                    <div class="table-responsive">
                        <table class="table table-hover align-middle" id="userTable">
                            <thead class="table-light text-center">
                                <tr>
                                    <th style="width: 5%;">
                                        <input class="form-check-input me-1" type="checkbox" id="selectAll">
                                    </th>
                                    <th class="text-start" style="width: 35%;">Full Name</th>
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

                                        <td class="text-center">
                                            <input type="checkbox" class="user-checkbox form-check-input me-1"
                                                value="{{ $user->id }}">
                                        </td>
                                        <!-- Full Name with profile photo -->
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <a href="{{ route('admin.user.info', ['id' => $user->id]) }}">
                                                    <img src="{{ $profilePhoto }}" alt="{{ $user->full_name }}"
                                                        class="rounded-circle me-2" width="30" height="30">
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
            const checkboxes = document.querySelectorAll(".user-checkbox");
            const bulkForm = document.getElementById("bulkStatusForm");
            const bulkUserIdsContainer = document.getElementById("bulkUserIds");

            // Helper: check if any checkbox is selected
            function toggleBulkForm() {
                const anyChecked = Array.from(checkboxes).some(cb => cb.checked);
                bulkForm.classList.toggle("d-none", !anyChecked);
            }

            // Select all toggle
            selectAll.addEventListener("change", () => {
                checkboxes.forEach(cb => cb.checked = selectAll.checked);
                toggleBulkForm();
            });

            // Individual checkbox toggle
            checkboxes.forEach(cb => {
                cb.addEventListener("change", toggleBulkForm);
            });

            // Collect checked IDs before submit
            bulkForm.addEventListener("submit", (e) => {
                const selected = Array.from(checkboxes)
                    .filter(cb => cb.checked)
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

        /* Make checkboxes use pointer cursor */
        .user-checkbox,
        #selectAll {
            cursor: pointer;
        }
    </style>
@endpush
