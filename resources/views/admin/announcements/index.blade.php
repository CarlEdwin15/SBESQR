@extends('./layouts.main')

@section('title', 'Admin | Announcements')

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
                        <a href="{{ route('student.management') }}" class="menu-link bg-dark text-light">
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
                    <div class="text-light">School Fees</div>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item">
                        <a href="{{ route('admin.payments.index') }}" class="menu-link bg-dark text-light">
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
                <button class="btn btn-primary d-flex justify-content-center align-items-center" data-bs-toggle="modal"
                    data-bs-target="#createAnnouncementModal">
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
                <div class="accordion-item mb-2 announcement-item
                                @if ($announcement->computed_status == 'active') active-announcement @endif"
                    data-title="{{ strtolower($announcement->title) }}"
                    data-body="{{ strtolower(strip_tags($announcement->body)) }}">

                    <h2 class="accordion-header" id="heading{{ $announcement->id }}">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#collapse{{ $announcement->id }}" aria-expanded="false"
                            aria-controls="collapse{{ $announcement->id }}">
                            {{ $announcement->formatted_published }}
                            | 📢 {{ $announcement->title }}
                            <span
                                class="ms-2 badge
                                            @if ($announcement->computed_status == 'active') bg-success
                                            @elseif ($announcement->computed_status == 'inactive') bg-secondary
                                            @else bg-warning @endif">
                                {{ ucfirst($announcement->computed_status) }}
                            </span>
                        </button>
                    </h2>

                    <div id="collapse{{ $announcement->id }}" class="accordion-collapse collapse"
                        aria-labelledby="heading{{ $announcement->id }}" data-bs-parent="#announcementAccordion">

                        <div class="accordion-body" style="font-family: sans-serif;">

                            <div class="mt-3 mb-3 text-end">
                                <button class="btn btn-warning" data-bs-toggle="modal"
                                    data-bs-target="#editAnnouncementModal"
                                    onclick="loadEditModal({{ $announcement->id }})">
                                    <i class='bx bx-edit-alt'></i>
                                    <span class="d-none d-sm-inline">Edit</span>
                                </button>

                                <form id="delete-form-{{ $announcement->id }}"
                                    action="{{ route('announcements.destroy', $announcement->id) }}" method="POST"
                                    class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-danger"
                                        onclick="confirmDelete({{ $announcement->id }}, '{{ $announcement->title }}')">
                                        <i class='bx bx-trash'></i>
                                        <span class="d-none d-sm-inline">Delete</span>
                                    </button>
                                </form>
                            </div>

                            <h2 class="text-center text-warning fw-bold mb-4">{{ $announcement->title }}</h2>

                            <div class="row">
                                <div class="col-md-8 border-end">
                                    <div class="mb-4">
                                        <div class="border rounded p-3 bg-light quill-content">
                                            {!! $announcement->body !!}
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="border rounded p-3 bg-light">
                                        <p><strong>Author:</strong> {{ $announcement->author_name }}</p>
                                        <p><strong>Published:</strong> {{ $announcement->formatted_published }}
                                        </p>
                                        <p><strong>School Year:</strong>
                                            {{ $announcement->schoolYear->school_year ?? 'N/A' }}</p>
                                        <p><strong>Effective Date:</strong>
                                            {{ $announcement->formatted_effective }} -
                                            {{ $announcement->formatted_end }}</p>
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
        {{-- /Card Content --}}

        {{-- Create Modal --}}
        <div class="modal fade" id="createAnnouncementModal" tabindex="-1"
            aria-labelledby="createAnnouncementModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <form method="POST" action="{{ route('announcements.store') }}" id="createAnnouncementForm">
                        @csrf
                        <div class="modal-header">
                            <h3 class="modal-title fw-bold text-center text-primary" id="createAnnouncementModalLabel">
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
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        {{-- /Create Modal --}}

        {{-- Edit Modal --}}
        <div class="modal fade" id="editAnnouncementModal" tabindex="-1" aria-labelledby="editAnnouncementModalLabel"
            aria-hidden="true">
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
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        {{-- /Edit Modal --}}

        {{-- /Card --}}

    </div>
    <!-- Content wrapper -->

@endsection

@push('scripts')

    <script src="https://js.pusher.com/8.4.0/pusher.min.js"></script>

    <!-- Pusher JS for Real-time Notifications -->
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

    <!-- Sweet Alert for Delete -->
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

    <!-- Sweet Alert for Logout -->
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

    <!-- Quill JS -->
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

    <!-- Quill Editor Initialization for Edit Form -->
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
                placeholder: 'Write your announcement here...',
                modules: {
                    toolbar: {
                        container: [
                            [{
                                'font': Font.whitelist
                            }],
                            [{
                                'size': ['small', false, 'large', 'huge']
                            }],
                            ['bold', 'italic', 'underline'],
                            [{
                                'color': []
                            }, {
                                'background': []
                            }],
                            [{
                                'align': []
                            }],
                            [{
                                'list': 'ordered'
                            }, {
                                'list': 'bullet'
                            }],
                            ['link', 'image'], // ✅ keep image button
                            ['clean']
                        ],
                        handlers: {
                            image: function() {
                                const input = document.createElement('input');
                                input.setAttribute('type', 'file');
                                input.setAttribute('accept', 'image/*');
                                input.click();

                                input.onchange = () => {
                                    const file = input.files[0];
                                    if (file) {
                                        const formData = new FormData();
                                        formData.append('image', file);

                                        fetch("{{ route('announcements.uploadImage') }}", {
                                                method: 'POST',
                                                headers: {
                                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                                },
                                                body: formData
                                            })
                                            .then(res => {
                                                if (!res.ok) throw res;
                                                return res.json();
                                            })
                                            .then(data => {
                                                if (data.url) {
                                                    const range = quill.getSelection();
                                                    quill.insertEmbed(range.index, 'image', data.url);
                                                } else {
                                                    alert('Image upload failed');
                                                }
                                            })
                                            .catch(async (err) => {
                                                let msg = 'Image upload failed';
                                                if (err.json) {
                                                    const errorData = await err.json();
                                                    if (errorData.errors && errorData.errors
                                                        .image) {
                                                        msg = errorData.errors.image.join(', ');
                                                    }
                                                }
                                                alert(msg);
                                            });
                                    }
                                };
                            }
                        }
                    }
                },
                formats: [
                    'font', 'size', 'bold', 'italic', 'underline',
                    'list', 'color', 'background',
                    'align', 'link', 'image'
                ]
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

    <!-- Search Functionality -->
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

    <!-- Toastr Notification for Success Messages -->
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

        .active-announcement {
            border-left: 4px solid #198754;
            /* green for active */
        }
    </style>
@endpush
