@extends('./layouts.main')

@section('title', 'Admin | Announcements')


@section('content')

    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">

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
                    <li class="menu-item active open">
                        <a href="javascript:void(0);" class="menu-link menu-toggle">
                            <i class="menu-icon tf-icons bx bxs-megaphone"></i>
                            <div>Announcements</div>
                        </a>
                        <ul class="menu-sub">
                            <li class="menu-item active">
                                <a href="{{ route('announcements.index') }}" class="menu-link bg-dark text-light">
                                    <div class="text-warning">All Announcements</div>
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
                        <!-- Search -->
                        <div class="navbar-nav align-items-center">

                        </div>
                        <!-- /Search -->

                        <ul class="navbar-nav flex-row align-items-center ms-auto">

                            <!-- Notification Dropdown -->
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle hide-arrow" href="#" id="notificationDropdown"
                                    role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class='bx bx-bell fs-4'></i>
                                    @if ($notifications->count())
                                        <span class="badge bg-danger rounded-pill badge-notifications">
                                            {{ $notifications->count() }}
                                        </span>
                                    @endif
                                </a>

                                <ul class="dropdown-menu dropdown-menu-end shadow border-0"
                                    aria-labelledby="notificationDropdown"
                                    style="min-width: 350px; max-height: 400px; overflow-y: auto;">

                                    <li class="px-3 pt-2">
                                        <h6 class="mb-1 d-flex justify-content-between">
                                            Notifications
                                            <span class="badge bg-light-primary text-primary fw-bold">
                                                {{ $notifications->count() }} New
                                            </span>
                                        </h6>
                                    </li>

                                    @forelse($notifications as $notif)
                                        <li>
                                            <a class="dropdown-item d-flex align-items-start gap-2 py-3" href="#">
                                                <div class="rounded-circle bg-primary text-white d-flex justify-content-center align-items-center"
                                                    style="width:36px; height:36px;">
                                                    📢
                                                </div>
                                                <div>
                                                    <strong>{{ $notif->title }}</strong>
                                                    <div class="text-muted small">{!! Str::limit(strip_tags($notif->body), 40) !!}</div>
                                                    <small
                                                        class="text-muted">{{ $notif->created_at->diffForHumans() }}</small>
                                                </div>
                                                <span class="ms-auto text-primary mt-1">
                                                    <i class="bx bxs-circle"></i>
                                                </span>
                                            </a>
                                        </li>
                                    @empty
                                        <li>
                                            <div class="dropdown-item text-center text-muted py-3">
                                                No notifications
                                            </div>
                                        </li>
                                    @endforelse

                                    <li>
                                        <hr class="dropdown-divider my-0">
                                    </li>
                                    <li>
                                        <a href="{{ route('announcements.index') }}"
                                            class="dropdown-item text-center text-primary fw-semibold py-2">
                                            View all announcements
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <!-- /Notification Dropdown -->

                            <!-- User Profile-->
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
                                                            'assetsDashboard/img/profile_pictures/admin_profile.png',
                                                        );
                                                @endphp
                                                <img src="{{ $profilePhoto }}" alt="Profile Photo"
                                                    class="w-px-40 h-auto rounded-circle" />
                                            @else
                                                <img src="{{ asset('assetsDashboard/img/profile_pictures/admin_profile.png') }}"
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
                                            <div class="d-flex">

                                                <div class="flex-shrink-0 me-3">
                                                    <div class="avatar">
                                                        <img src="{{ asset('assetsDashboard/img/profile_pictures/admin_profile.png') }}"
                                                            alt class="w-px-40 h-auto rounded-circle" />
                                                    </div>
                                                </div>

                                                <div class="flex-grow-1">
                                                    <span class="fw-semibold d-block">{{ Auth::user()->firstName }}</span>
                                                    <small class="text-muted">Admin</small>
                                                </div>

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
                    <h4 class="fw-bold text-warning mb-2">
                        <span class="text-muted fw-light">
                            <a class="text-muted fw-light" href="{{ route('home') }}">Dashboard</a> /
                            <a class="text-muted fw-light" href="{{ route('announcements.index') }}">Announcement</a> /
                        </span>
                        All Announcements
                    </h4>

                    <h3 class="text-center text-primary fw-bold mt-5 mb-5"> 📢 Announcement Management</h3>


                    {{-- Add New and Filters --}}
                    <div class="row align-items-end mb-3 gy-2">
                        {{-- Search input (full-width on mobile, left-aligned on desktop) --}}
                        <div class="col-12 col-md-4 d-flex align-items-center gap-2">
                            <input type="text" id="announcementSearch" class="form-control border-1 shadow-none"
                                placeholder="Search title or body..." />
                        </div>

                        {{-- Add New Button (left on mobile, center on desktop) --}}
                        <div class="col-6 col-md-4">
                            <button class="btn btn-primary d-flex justify-content-center align-items-center"
                                data-bs-toggle="modal" data-bs-target="#createAnnouncementModal">
                                <i class='bx bx-message-alt-add me-2'></i>
                                <span class="d-none d-sm-inline">New Announcement</span>
                            </button>
                        </div>

                        {{-- School Year Filter + Now Button (right on mobile, right-aligned on desktop) --}}
                        <div class="col-6 col-md-4 d-flex justify-content-between align-items-end gap-2">
                            <form method="GET" action="{{ route('announcements.index') }}"
                                class="d-flex align-items-center gap-2 flex-grow-1">
                                <span class="form-label mb-0 d-none d-sm-inline">School Year</span>
                                <select name="school_year" class="form-select" onchange="this.form.submit()">
                                    <option value="">All</option>
                                    @foreach ($schoolYears as $year)
                                        <option value="{{ $year->id }}"
                                            {{ request('school_year') == $year->id ? 'selected' : '' }}>
                                            {{ $year->school_year }}
                                        </option>
                                    @endforeach
                                </select>
                            </form>

                            <form method="GET" action="{{ route('announcements.index') }}">
                                <input type="hidden" name="school_year" value="{{ $defaultSchoolYear->id }}">
                                <button type="submit" class="btn btn-primary">
                                    Now
                                </button>
                            </form>
                        </div>
                    </div>

                    @if (request('search') || request('school_year'))
                        <div class="alert alert-info">
                            Showing results for:
                            @if (request('search'))
                                <strong>Search:</strong> "{{ request('search') }}"
                            @endif
                            @if (request('search') && request('school_year'))
                                |
                            @endif
                            @if (request('school_year'))
                                <strong>School Year:</strong>
                                {{ $schoolYears->firstWhere('id', request('school_year'))?->school_year ?? 'N/A' }}
                            @endif
                        </div>
                    @endif

                    {{-- Card Content --}}
                    <div class="accordion" id="announcementAccordion">
                        @forelse($announcements as $announcement)
                            <div class="accordion-item mb-2 announcement-item"
                                data-title="{{ strtolower($announcement->title) }}"
                                data-body="{{ strtolower(strip_tags($announcement->body)) }}">

                                <h2 class="accordion-header" id="heading{{ $announcement->id }}">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapse{{ $announcement->id }}" aria-expanded="false"
                                        aria-controls="collapse{{ $announcement->id }}">
                                        {{ $announcement->date_published ? \Carbon\Carbon::parse($announcement->date_published)->format('M d, Y') : 'Draft' }}
                                        | 📢 {{ $announcement->title }} —
                                        <span
                                            class="ms-2 badge
                            @if ($announcement->status == 'active') bg-success
                            @elseif ($announcement->status == 'inactive') bg-secondary
                            @else bg-dark @endif">
                                            {{ ucfirst($announcement->status) }}
                                        </span>
                                    </button>
                                </h2>
                                <div id="collapse{{ $announcement->id }}" class="accordion-collapse collapse"
                                    aria-labelledby="heading{{ $announcement->id }}"
                                    data-bs-parent="#announcementAccordion">

                                    <div class="accordion-body" style="font-family: sans-serif;">
                                        <h2 class="text-center text-warning fw-bold mb-4">{{ $announcement->title }}</h2>

                                        <!-- Buttons -->
                                        <div class="mt-3 mb-3 text-end">
                                            <button class="btn btn-warning" data-bs-toggle="modal"
                                                data-bs-target="#editAnnouncementModal"
                                                onclick="loadEditModal({{ $announcement->id }})">
                                                <i class='bx bx-edit-alt'></i>
                                                <span class="d-none d-sm-inline">Edit</span>
                                            </button>

                                            <form id="delete-form-{{ $announcement->id }}"
                                                action="{{ route('announcements.destroy', $announcement->id) }}"
                                                method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btn btn-danger"
                                                    onclick="confirmDelete({{ $announcement->id }}, '{{ $announcement->title }}')">
                                                    <i class='bx bx-trash'></i>
                                                    <span class="d-none d-sm-inline">Delete</span>
                                                </button>
                                            </form>
                                        </div>

                                        <div class="row">
                                            <!-- Left / Center Column (Body + Recipients + Published) -->
                                            <div class="col-md-8 border-end">
                                                <!-- Description -->
                                                <div class="mb-4">
                                                    <div class="border rounded p-3 bg-light"
                                                        style="white-space: pre-wrap;">
                                                        {!! $announcement->body !!}
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Right Column (Dates & School Year) -->
                                            <div class="col-md-4">
                                                <div class="border rounded p-3 bg-light">
                                                    <p><strong>Recipients:</strong>
                                                        {{ ucfirst($announcement->recipients) }}
                                                    </p>
                                                    <p><strong>Published:</strong>
                                                        {{ $announcement->date_published ? \Carbon\Carbon::parse($announcement->date_published)->format('M d, Y | h:i:A') : 'Draft' }}
                                                    </p>

                                                    <p><strong>School Year:</strong>
                                                        {{ $announcement->schoolYear->school_year ?? 'N/A' }}</p>
                                                    <p><strong>Effective Date:</strong>
                                                        {{ $announcement->effective_date ? \Carbon\Carbon::parse($announcement->effective_date)->format('M d, Y') : 'N/A' }}
                                                        -
                                                        {{ $announcement->end_date ? \Carbon\Carbon::parse($announcement->end_date)->format('M d, Y') : 'N/A' }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        @empty
                            <div id="noResultsMessage"
                                class="alert alert-warning alert-dismissible fade show mt-2 text-center text-primary fw-bold">
                                No announcements found.
                            </div>
                        @endforelse
                    </div>

                    {{-- Create Modal --}}
                    <div class="modal fade" id="createAnnouncementModal" tabindex="-1"
                        aria-labelledby="createAnnouncementModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <form method="POST" action="{{ route('announcements.store') }}"
                                    id="createAnnouncementForm">
                                    @csrf
                                    <div class="modal-header">
                                        <h3 class="modal-title fw-bold text-center text-primary"
                                            id="createAnnouncementModalLabel">
                                            New Announcement</h3>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        @include('admin.announcements._form', [
                                            'announcement' => null,
                                            'schoolYears' => $schoolYears,
                                            'defaultSchoolYear' => $defaultSchoolYear ?? null,
                                        ])
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-success">Publish</button>
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Cancel</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    {{-- /Create Modal --}}

                    {{-- Edit Modal --}}
                    <div class="modal fade" id="editAnnouncementModal" tabindex="-1"
                        aria-labelledby="editAnnouncementModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <form method="POST" id="editAnnouncementForm">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-header">
                                        <h5 class="modal-title">Edit Announcement</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body" id="editModalBody">
                                        {{-- The form fields will be injected here by JavaScript --}}
                                        <div class="text-center">
                                            <div class="spinner-border text-primary" role="status"></div>
                                            <p>Loading form...</p>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-warning">Update</button>
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Cancel</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    {{-- /Edit Modal --}}

                    {{-- /Card --}}

                </div>
                <!-- Content wrapper -->

            </div>
            <!-- / Layout page -->
        </div>
    </div>
    <!-- / Layout wrapper -->

    <!-- Place this tag in your head or just before your close body tag. -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>


@endsection

@push('scripts')

    <script src="https://js.pusher.com/8.4.0/pusher.min.js"></script>
    <script>
        Pusher.logToConsole = true;

        var pusher = new Pusher("{{ env('VITE_PUSHER_APP_KEY') }}", {
            cluster: "{{ env('VITE_PUSHER_APP_CLUSTER') }}"
        });

        var userRole = "{{ Auth::user()->role ?? 'parent' }}"; // fallback for parents
        var channel = pusher.subscribe('announcements.' + userRole);

        channel.bind('new-announcement', function(data) {
            // Show browser notification
            if (Notification.permission === "granted") {
                new Notification("📢 New Announcement", {
                    body: data.announcement.title
                });
            }

            // Update badge count in real-time
            let badge = document.querySelector(".badge-notifications");
            if (badge) {
                let current = parseInt(badge.textContent.trim()) || 0;
                badge.textContent = current + 1;
                badge.style.display = "inline-block";
            }

            // Prepend new notification into dropdown
            let dropdown = document.querySelector("#notificationDropdown")
                .nextElementSibling; // ul.dropdown-menu

            if (dropdown) {
                let newItem = `
                <li>
                    <a class="dropdown-item d-flex align-items-start gap-2 py-3" href="#">
                        <div class="rounded-circle bg-primary text-white d-flex justify-content-center align-items-center"
                            style="width:36px; height:36px;">📢</div>
                        <div>
                            <strong>${data.announcement.title}</strong>
                            <div class="text-muted small">${data.announcement.body.replace(/(<([^>]+)>)/gi, "").substring(0,40)}...</div>
                            <small class="text-muted">just now</small>
                        </div>
                        <span class="ms-auto text-primary mt-1"><i class="bx bxs-circle"></i></span>
                    </a>
                </li>
            `;
                // insert after header (second child of ul)
                dropdown.insertAdjacentHTML("afterbegin", newItem);
            }
        });

        if (Notification.permission !== "granted") {
            Notification.requestPermission();
        }
    </script>

    <script>
        function confirmDelete(announcementId, title) {
            Swal.fire({
                title: `Delete announcement "${title}"?`,
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
                                document.getElementById('delete-form-' + announcementId)
                                    .submit();
                            }, 1000);
                        }
                    });
                }
            });
        }
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
        function loadEditModal(id) {
            const modalBody = document.getElementById('editModalBody');
            const form = document.getElementById('editAnnouncementForm');

            modalBody.innerHTML = `
        <div class="text-center">
            <div class="spinner-border text-primary" role="status"></div>
            <p>Loading form...</p>
        </div>
        `;

            fetch(`/announcements/${id}/edit`)
                .then(response => response.text())
                .then(html => {
                    modalBody.innerHTML = html;
                    form.action = `/announcements/${id}`;

                    // Delay needed to ensure DOM is updated before init
                    setTimeout(() => initEditQuillEditor(), 50);
                })
                .catch(() => {
                    modalBody.innerHTML = '<div class="alert alert-danger">Failed to load the form.</div>';
                });
        }
    </script>

    <script>
        function initEditQuillEditor() {
            const editorContainer = document.querySelector('#edit-quill-editor');
            if (!editorContainer) return;

            // Ensure the Font module is registered before use
            const Font = Quill.import('formats/font');
            Font.whitelist = ['sans-serif', 'serif', 'monospace'];
            Quill.register(Font, true);

            const quill = new Quill('#edit-quill-editor', {
                theme: 'snow',
                placeholder: 'Edit your announcement...',
                modules: {
                    toolbar: [
                        [{
                            'font': Font.whitelist
                        }],
                        [{
                            'size': ['small', false, 'large', 'huge']
                        }],
                        ['bold', 'italic', 'underline'],
                        [{
                            'color': []
                        }],
                        [{
                            'list': 'ordered'
                        }, {
                            'list': 'bullet'
                        }],
                        ['clean']
                    ]
                },
                formats: ['font', 'size', 'bold', 'italic', 'underline', 'list', 'color']
            });

            const editForm = document.getElementById('editAnnouncementForm');
            const bodyInput = document.getElementById('edit-body');
            const errorDiv = document.getElementById('edit-body-error');

            editForm.addEventListener('submit', function(e) {
                const htmlContent = quill.root.innerHTML.trim();
                bodyInput.value = htmlContent;

                if (quill.getLength() <= 1 || htmlContent === '<p><br></p>') {
                    e.preventDefault();
                    errorDiv.textContent = 'The Body field is required.';
                    errorDiv.style.display = 'block';
                    return;
                }

                errorDiv.style.display = 'none';
            });
        }
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const searchInput = document.getElementById("announcementSearch");
            const announcementItems = document.querySelectorAll(".announcement-item");
            const noResultsMessage = document.getElementById("noResultsMessage"); // Optional

            searchInput.addEventListener("input", function() {
                const query = this.value.trim().toLowerCase();
                let matchFound = false;

                announcementItems.forEach(item => {
                    const title = item.dataset.title || "";
                    const body = item.dataset.body || "";
                    const isMatch = title.includes(query) || body.includes(query);

                    item.style.display = isMatch ? "block" : "none";
                    if (isMatch) matchFound = true;
                });

                if (noResultsMessage) {
                    noResultsMessage.classList.toggle("d-none", matchFound || query === "");
                }
            });
        });
    </script>


    <!-- jQuery (only once) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <!-- Toastr JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.4/toastr.min.js"></script>

    @if (session('success'))
        <script>
            $(document).ready(function() {
                toastr.success(@json(session('success')), "Success", {
                    positionClass: "toast-top-right",
                    timeOut: 3000,
                    closeButton: true,
                    progressBar: true,
                    iconClass: 'toast-success'
                });
            });
        </script>
    @endif
@endpush

@push('styles')
    <!-- Main CSS File -->
    <link href="{{ asset('assets/css/main.css') }}" rel="stylesheet" />
    <link href="{{ asset('assetsDashboard/vendor/css/core.css') }}" rel="stylesheet" />
    <link href="{{ asset('assetsDashboard/vendor/css/theme-default.css') }}" rel="stylesheet" />

    <!-- Vendor CSS Files -->
    <link href="{{ asset('assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet" />

    <!-- Toastr CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.4/toastr.min.css" rel="stylesheet" />

    <style>
        .toast-success {
            background-color: #65ce69 !important;
            font-weight: bold;
        }

        .toast-title {
            font-size: 16px;
        }

        .toast-message {
            font-size: 14px;
        }

        .toast {
            z-index: 99999 !important;
        }
    </style>

    <style>
        .hoverable-schedule-cell {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            cursor: pointer;
        }

        .hoverable-schedule-cell:hover {
            transform: scale(1.02);
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }

        .ql-size-small {
            font-size: 0.75em;
        }

        .ql-size-large {
            font-size: 1.5em;
        }

        .ql-size-huge {
            font-size: 2.5em;
        }

        .ql-font-sans-serif {
            font-family: sans-serif;
        }

        .ql-font-serif {
            font-family: serif;
        }

        .ql-font-monospace {
            font-family: monospace;
        }
    </style>
@endpush
