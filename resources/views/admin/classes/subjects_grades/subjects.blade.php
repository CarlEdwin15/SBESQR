@extends('./layouts.main')

@section('title', 'Admin | Subjects Management')

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
            <li class="menu-item active open">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons bx bx-objects-horizontal-left"></i>
                    <div>Classes</div>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item active">
                        <a href="{{ route('all.classes') }}" class="menu-link bg-dark text-light">
                            <div class="text-warning">All Classes</div>
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
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <h4 class="fw-bold text-warning mb-0">
                    <span class="text-muted fw-light">
                        <a class="text-muted fw-light" href="{{ route('home') }}">Dashboard</a> /
                        <a class="text-muted fw-light" href="{{ route('all.classes') }}">Classes</a> /
                        <a class="text-muted fw-light"
                            href="{{ route('classes.showClass', ['grade_level' => $class->grade_level, 'section' => $class->section]) }}?school_year={{ $selectedYear }}">
                            {{ ucfirst($class->grade_level) }} - {{ $class->section }}
                        </a> /
                    </span>
                    Subjects Management
                </h4>
            </div>
        </div>

        <h3 class="text-center text-info fw-bold mb-4">
            Subjects for {{ ucfirst($class->grade_level) }} - {{ $class->section }} ({{ $selectedYear }})
        </h3>

        <!-- SUBJECT LIST CARDS (same as teacher view) -->
        <div class="card p-4 shadow-sm">
            <div class="card-body">
                @if ($classSubjects->isEmpty())
                    <div class="text-center py-5">
                        <i class="bi bi-book text-info display-4"></i>
                        <h5 class="mt-3 fw-bold text-secondary">No subjects yet</h5>
                        <p class="text-muted">No subjects have been added for this class and school year.</p>
                    </div>
                @else
                    <div class="row g-4">
                        @foreach ($classSubjects as $classSubject)
                            <div class="col-md-4 col-sm-6">
                                <div class="card subject-card border-0 shadow-sm rounded-4 h-100 overflow-hidden">
                                    @php
                                        $bgIndex = rand(1, 3);
                                        $color1 = '#' . substr(md5($classSubject->subject->name), 0, 6);
                                        $color2 = '#' . substr(md5(strrev($classSubject->subject->name)), 0, 6);
                                    @endphp

                                    <a href="{{ route('classes.grades', [
                                        'grade_level' => $class->grade_level,
                                        'section' => $class->section,
                                        'subject' => $classSubject->subject->id,
                                    ]) }}?school_year={{ $selectedYear }}"
                                        class="text-white text-decoration-none">
                                        <div class="card-header text-white border-0 position-relative p-4"
                                            style="
                                        background:
                                            linear-gradient(135deg, {{ $color1 }}cc, {{ $color2 }}cc),
                                            url('{{ asset("assetsDashboard/img/subject-card-bg/bg$bgIndex.jpg") }}');
                                        background-size: cover;
                                        background-position: center;
                                        height: 140px;
                                        ">
                                            <h4 class="card-title text-white fw-bold mb-1">
                                                {{ $classSubject->subject->name }}
                                            </h4>
                                            <p class="subject-desc text-auto small mb-0">
                                                {{ $classSubject->description }}
                                            </p>


                                            <div class="avatar-wrapper position-absolute"
                                                style="bottom: -20px; right: 20px;">
                                                <img src="https://ui-avatars.com/api/?name={{ urlencode($classSubject->subject->name) }}&background=random"
                                                    class="rounded-circle border border-3 border-white shadow-sm"
                                                    width="60" height="60" alt="Subject Avatar">
                                            </div>
                                        </div>
                                    </a>

                                    <div class="card-body pt-2">
                                        <div class="d-flex align-items-center">
                                            <img src="{{ $classSubject->teacher->profile_photo ? asset('public/uploads/' . $classSubject->teacher->profile_photo) : asset('assetsDashboard/img/profile_pictures/teacher_default_profile.jpg') }}"
                                                alt="Teacher Profile" class="rounded-circle me-2" width="30"
                                                height="30">
                                            <small class="text-muted">
                                                <span class="fw-semibold">
                                                    {{ $classSubject->teacher->firstName ?? 'Unknown' }}
                                                    {{ $classSubject->teacher->lastName ?? '' }}
                                                </span>
                                            </small>
                                        </div>
                                    </div>

                                    <!-- Card Footer -->
                                    <div
                                        class="card-footer bg-white border-0 d-flex justify-content-end align-items-center">
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
    <!-- End Content wrapper -->

@endsection

@push('styles')
    <style>
        /* Subject Cards */
        .subject-card {
            transition: transform 0.25s ease, box-shadow 0.25s ease;
        }

        .subject-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
        }

        /* Avatar */
        .avatar-wrapper img {
            transition: transform 0.25s ease;
        }

        .subject-card:hover .avatar-wrapper img {
            transform: scale(1.2);
        }

        /* Description truncation */
        .subject-desc {
            display: -webkit-box;
            -webkit-line-clamp: 3;
            /* show max 3 lines */
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        /* Dropdown polish */
        .dropdown-menu {
            border: none;
            font-size: 14px;
        }

        .dropdown-item i {
            font-size: 16px;
            vertical-align: middle;
        }
    </style>
@endpush
