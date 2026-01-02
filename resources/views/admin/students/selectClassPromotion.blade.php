@extends('./layouts.main')

@section('title', 'Admin | Re-Enrollment / Promotion')

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
            <li class="menu-item active open">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons bx bxs-graduation"></i>
                    <div class="text-info">Students</div>
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
                    <li class="menu-item active">
                        <a href="{{ route('students.promote') }}" class="menu-link bg-dark text-light">
                            <div class="text-warning">Re-Enrollment / Promotion</div>
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
            <li class="menu-item">
                <a href="{{ route('admin.user.management') }}" class="menu-link bg-dark text-light">
                    <i class='bx bxs-user-account me-3 text-light'></i>
                    <div class="text-light"> User Management</div>
                </a>
            </li>

            {{-- Account Settings sidebar --}}
            <li class="menu-item">
                <a href="{{ route('admin.account.settings') }}" class="menu-link bg-dark text-light">
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
        <h4 class="fw-bold py-3 mb-4 text-warning"><span class="text-muted fw-light">
                <a class="text-muted fw-light" href="{{ route('home') }}">Dashboard / </a>
                <a class="text-muted fw-light" href="{{ route('student.management') }}"> Students / </a>
            </span> Re-Enroll Students
        </h4>

        <h2 class="text-center text-info fw-bold">Re-Enrollment / Promotion for {{ $currentSchoolYear }}</h2>

        {{-- Combined Filters and Search Card --}}
        <div class="card mb-4">
            <div class="card-body">
                <div class="row g-3">
                    {{-- Search Function --}}
                    <div class="col-md-7">
                        <div class="input-group">
                            <input type="text" id="studentSearch" class="form-control"
                                placeholder="Search by name or LRN..." aria-label="Search students">
                            <button class="btn btn-outline-secondary" type="button" id="clearSearch"
                                title="Clear search">
                                <i class="bx bx-x"></i>
                            </button>
                        </div>
                    </div>

                    {{-- Section Selection --}}
                    <div class="col-md-2">
                        <form method="GET" action="{{ route('students.promote.view') }}" id="sectionForm"
                            class="mb-0">
                            <input type="hidden" name="school_year" value="{{ $selectedSchoolYear }}">
                            <select name="section" id="section" class="form-select" onchange="this.form.submit()">
                                @foreach ($sections as $s)
                                    <option value="{{ $s }}" {{ $selectedSection == $s ? 'selected' : '' }}>
                                        Section {{ $s }}
                                    </option>
                                @endforeach
                            </select>
                        </form>
                    </div>

                    {{-- School Year Selection --}}
                    <div class="col-md-3">
                        <form method="GET" action="{{ route('students.promote.view') }}" id="schoolYearForm"
                            class="mb-0">
                            <select name="school_year" id="school_year" class="form-select"
                                onchange="this.form.submit()">
                                @foreach ($availableSchoolYears as $schoolYear)
                                    <option value="{{ $schoolYear }}"
                                        {{ $selectedSchoolYear == $schoolYear ? 'selected' : '' }}>
                                        SY: {{ $schoolYear }}
                                        @if ($schoolYear == $previousSchoolYear)
                                            (Most Recent)
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                        </form>
                    </div>
                </div>

                {{-- Quick Stats --}}
                <div class="row mt-3 pt-3 border-top">
                    <div class="col-12">
                        <div class="d-flex flex-wrap gap-4">
                            <div class="d-flex align-items-center">
                                <span class="badge bg-dark rounded-pill p-2 me-2">
                                    <i class="bx bx-user"></i>
                                </span>
                                <div>
                                    <small class="text-muted d-block">Total Students</small>
                                    <strong>{{ $classes->sum('total_students') }}</strong>
                                </div>
                            </div>
                            <div class="d-flex align-items-center">
                                <span class="badge bg-success rounded-pill p-2 me-2">
                                    <i class="bx bx-check-circle"></i>
                                </span>
                                <div>
                                    <small class="text-muted d-block">Re-enrolled</small>
                                    <strong>{{ $classes->sum('reenrolled_count') }}</strong>
                                </div>
                            </div>
                            <div class="d-flex align-items-center">
                                <span class="badge bg-info rounded-pill p-2 me-2">
                                    <i class="bx bxs-graduation"></i>
                                </span>
                                <div>
                                    <small class="text-muted d-block">Graduated</small>
                                    <strong>{{ $classes->sum('graduated_count') }}</strong>
                                </div>
                            </div>
                            <div class="d-flex align-items-center">
                                <span class="badge bg-warning rounded-pill p-2 me-2">
                                    <i class="bx bx-user-plus"></i>
                                </span>
                                <div>
                                    <small class="text-muted d-block">Pending Re-enrollment</small>
                                    <strong>{{ $classes->sum('promotable_count') }}</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- / Quick Stats --}}

            </div>
        </div>
        {{-- / Combined Filters and Search Card --}}

        <!-- Information Alert -->
        {{-- @if ($selectedSchoolYear == $previousSchoolYear)
            <div class="alert alert-info">
                <i class="bx bx-info-circle me-2"></i>
                <strong>Action Available:</strong> You are viewing the most recent previous school year
                ({{ $previousSchoolYear }}).
                Promotion/Re-enrollment actions are available for this year.
            </div>
        @else
            <div class="alert alert-warning">
                <i class="bx bx-time me-2"></i>
                <strong>View Only:</strong> You are viewing historical data from SY: {{ $selectedSchoolYear }}.
                Promotion actions are only available for the most recent previous school year ({{ $previousSchoolYear }}).
            </div>
        @endif --}}

        <!-- Card for All Grade Levels by Section -->
        <section id="services" class="services section">
            <div class="container" data-aos="fade-up" data-aos-delay="100">
                <div class="row gy-5" id="classesContainer">
                    @php $iconIndex = 1; @endphp

                    @foreach ($classes as $class)
                        <div class="col-xl-4 col-md-6 class-card" data-aos="zoom-in"
                            data-student-names="{{ $class->student_names ?? '' }}"
                            data-student-lrns="{{ $class->student_lrns ?? '' }}">
                            <div class="service-item {{ $class->promotable_count == 0 ? 'completed-class' : '' }}">
                                <div class="img">
                                    <img src="{{ asset('assets/img/classes/' . strtolower($class->grade_level) . '.jpg') }}"
                                        class="img-fluid {{ $class->promotable_count == 0 ? 'darkened-image' : '' }}"
                                        alt="" />
                                    @if ($class->promotable_count == 0)
                                        <div class="completed-badge">
                                            <i class="fas fa-check-circle"></i>
                                            <span>Completed</span>
                                        </div>
                                    @endif
                                </div>
                                <div class="details position-relative">
                                    <div class="icon {{ $class->promotable_count == 0 ? 'completed-icon' : '' }}">
                                        @if ($class->grade_level === 'kindergarten')
                                            <i class="fa-solid fa-child"></i>
                                        @else
                                            <i class="fa-solid fa-{{ $iconIndex }}"></i>
                                            @php $iconIndex++; @endphp
                                        @endif
                                    </div>
                                    <a href="{{ route('students.promote.view') }}?grade_level={{ $class->grade_level }}&section={{ $class->section }}&school_year={{ $selectedSchoolYear }}"
                                        class="stretched-link">
                                        <h3 class="{{ $class->promotable_count == 0 ? 'text-muted' : '' }}">
                                            @if (strtolower($class->grade_level) === 'kindergarten')
                                                Kindergarten
                                            @else
                                                Grade
                                                {{ preg_replace('/[^0-9]/', '', $class->grade_level) }}
                                            @endif
                                            - {{ $class->section }}
                                        </h3>

                                        <div class="student-stats mt-2">
                                            <div class="d-flex justify-content-between align-items-center mb-1">
                                                <small class="text-dark">Total Students:</small>
                                                <strong
                                                    class="{{ $class->promotable_count == 0 ? 'text-muted' : 'text-dark' }}">
                                                    {{ $class->total_students }}
                                                </strong>
                                            </div>
                                            <div class="d-flex justify-content-between align-items-center mb-1">
                                                <small class="text-warning">Ready for Re-Enrollment:</small>
                                                <strong class="text-warning">
                                                    {{ $class->promotable_count }}
                                                </strong>
                                            </div>
                                            <div class="d-flex justify-content-between align-items-center mb-1">
                                                <small class="text-success">Re-enrolled:</small>
                                                <strong class="text-success">
                                                    {{ $class->reenrolled_count }}
                                                </strong>
                                            </div>
                                            @if ($class->graduated_count > 0)
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <small class="text-info">Graduated:</small>
                                                    <strong class="text-info">
                                                        {{ $class->graduated_count }}
                                                    </strong>
                                                </div>
                                            @endif
                                        </div>

                                        @if ($class->promotable_count > 0)
                                            <div class="mt-2">
                                                <span class="badge bg-label-warning text-dark">
                                                    <strong>{{ $class->promotable_count }}</strong>
                                                    student{{ $class->promotable_count > 1 ? 's' : '' }} ready for
                                                    re-enrollment
                                                </span>
                                            </div>
                                        @else
                                            <div class="mt-2">
                                                <span class="badge bg-success">
                                                    <i class="fas fa-check-circle me-1"></i>
                                                    All students processed
                                                </span>
                                            </div>
                                        @endif
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach

                    @if ($classes->isEmpty())
                        <div class="col-12">
                            <div class="alert alert-warning text-center">
                                No classes found for this section in the selected school year.
                            </div>
                        </div>
                    @endif

                </div>

                {{-- No Results Message --}}
                <div id="noResults" class="d-none">
                    <div class="col-12">
                        <div class="alert alert-info text-center">
                            <i class="bx bx-search-alt me-2"></i>
                            No classes found matching your search criteria.
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- /Card for All Grade Levels by Section -->

        <hr class="my-5" />

    </div>
    <!-- Content wrapper -->

@endsection

@push('scripts')
    <!-- Place this tag in your head or just before your close body tag. -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>

    <script>
        // Logout confirmation
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
                    document.getElementById('logout-form').submit();
                }
            });
        }

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

        // Update section form when school year changes
        document.getElementById('school_year').addEventListener('change', function() {
            document.getElementById('sectionForm').querySelector('input[name="school_year"]').value = this.value;
        });

        // Search functionality
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('studentSearch');
            const clearSearch = document.getElementById('clearSearch');
            const classCards = document.querySelectorAll('.class-card');
            const classesContainer = document.getElementById('classesContainer');
            const noResults = document.getElementById('noResults');

            function performSearch() {
                const searchTerm = searchInput.value.toLowerCase().trim();
                let hasVisibleResults = false;

                classCards.forEach(card => {
                    const studentNames = card.getAttribute('data-student-names').toLowerCase();
                    const studentLRNs = card.getAttribute('data-student-lrns').toLowerCase();

                    const matchesName = studentNames.includes(searchTerm);
                    const matchesLRN = studentLRNs.includes(searchTerm);

                    if (searchTerm === '' || matchesName || matchesLRN) {
                        card.style.display = 'block';
                        hasVisibleResults = true;
                    } else {
                        card.style.display = 'none';
                    }
                });

                // Show/hide no results message
                if (!hasVisibleResults && searchTerm !== '') {
                    noResults.classList.remove('d-none');
                    classesContainer.classList.add('d-none');
                } else {
                    noResults.classList.add('d-none');
                    classesContainer.classList.remove('d-none');
                }
            }

            // Search on input
            searchInput.addEventListener('input', performSearch);

            // Clear search
            clearSearch.addEventListener('click', function() {
                searchInput.value = '';
                performSearch();
                searchInput.focus();
            });

            // Search on Enter key
            searchInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    performSearch();
                }
            });
        });
    </script>

    <!-- Font Awesome -->
    <script src="https://kit.fontawesome.com/ab677fe211.js" crossorigin="anonymous"></script>
@endpush

@push('styles')
    <!-- Main CSS File -->
    <link href="{{ asset('assets/css/main.css') }}" rel="stylesheet" />
    <link href="{{ asset('assetsDashboard/vendor/css/core.css') }}" rel="stylesheet" />
    <link href="{{ asset('assetsDashboard/vendor/css/theme-default.css') }}" rel="stylesheet" />

    <!-- Vendor CSS Files -->
    <link href="{{ asset('assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet" />

    <style>
        .completed-class {
            opacity: 0.7;
        }

        .completed-class .service-item {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
        }

        .darkened-image {
            filter: brightness(0.7);
            transition: filter 0.3s ease;
        }

        .completed-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            background: rgba(40, 167, 69, 0.95);
            color: white;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 0.75rem;
            font-weight: bold;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .completed-badge i {
            font-size: 0.7rem;
        }

        .completed-icon {
            color: #6c757d !important;
        }

        .service-item {
            position: relative;
            transition: all 0.3s ease;
            border-radius: 8px;
            overflow: hidden;
        }

        .student-stats {
            background: rgba(255, 255, 255, 0.9);
            padding: 8px;
            border-radius: 6px;
            border: 1px solid #e9ecef;
        }

        .img {
            position: relative;
            overflow: hidden;
        }

        .service-item:hover .darkened-image {
            filter: brightness(0.8);
        }

        .text-muted {
            color: #6c757d !important;
        }

        .badge {
            font-size: 0.75rem;
            padding: 4px 8px;
        }

        /* Search styles */
        #studentSearch {
            border-right: none;
        }

        #clearSearch {
            border-left: none;
            border-color: #ced4da;
        }

        #clearSearch:hover {
            background-color: #f8f9fa;
            border-color: #ced4da;
        }

        .class-card {
            transition: all 0.3s ease;
        }
    </style>
@endpush
