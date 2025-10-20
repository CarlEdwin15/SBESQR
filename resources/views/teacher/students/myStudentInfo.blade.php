@extends('./layouts.main')
@php
    use Carbon\Carbon;
@endphp

@section('title', 'Teacher | Student Info')

@section('content')

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

            {{-- Account Settings sidebar --}}
            <li class="menu-item">
                <a href="{{ route('teacher.account.settings') }}" class="menu-link bg-dark text-light">
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
                        <img src="{{ asset('storage/' . $student->student_photo) }}" alt="Student Photo" class="mb-3 mt-2"
                            style="object-fit: cover; height: 200px; width: 200px;">
                    @else
                        <img src="{{ asset('assetsDashboard/img/student_profile_pictures/student_default_profile.jpg') }}"
                            alt="Default Photo" class="mb-3 mt-2" style="object-fit: cover; height: 200px; width: 200px;">
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
                        <a href="{{ session('back_url', url()->previous()) }}" class="btn btn-danger me-2 d-flex align-items-center">
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
                @include('partials.student_tabs')
            </div>
        </div>

    </div>
    <!-- /Content wrapper -->

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
