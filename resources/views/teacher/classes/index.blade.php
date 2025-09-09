@extends('./layouts.main')

@section('title', 'Teacher | My Class')

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
            <li class="menu-item">
                <a href="javascript:void(0)" class="menu-link menu-toggle bg-dark text-light">
                    <i class="menu-icon tf-icons bx bxs-graduation text-light"></i>
                    <div class="text-light">Students</div>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item">
                        <a href="{{ route('teacher.my.students') }}" class="menu-link bg-dark text-light">
                            <div class="text-light">My Students</div>
                        </a>
                    </li>
                </ul>
            </li>

            {{-- Classes sidebar --}}
            <li class="menu-item active open">
                <a href="javascript:void(0)" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons bx bx-notepad"></i>
                    <div>Classes</div>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item active">
                        <a href="{{ route('teacher.myClasses') }}" class="menu-link bg-dark text-light">
                            <div class="text-warning">My Classes</div>
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

            {{-- SMS Logs sidebar --}}
            <li class="menu-item">
                <a href="" class="menu-link bg-dark text-light">
                    <i class="bx bx-message-check me-3 text-light"></i>
                    <div class="text-light">SMS Logs</div>
                </a>
            </li>

            {{-- Account Settings sidebar --}}
            <li class="menu-item">
                <a href="" class="menu-link bg-dark text-light">
                    <i class="bx bx-cog me-3 text-light"></i>
                    <div class="text-light">Account Settings</div>
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
                <a class="text-muted fw-light" href="{{ route('teacher.myClasses') }}"> Classes / </a>
            </span> My Classes
        </h4>

        <!-- Filter Section -->

        <h5 class="alert alert-info alert-dismissible fade show mt-2 text-center text-primary fw-bold" role="alert"
            id="school-year-alert">
            Showing Classes for School Year <strong>{{ $selectedYear }}</strong>.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </h5>

        <script>
            setTimeout(function() {
                var alertElem = document.getElementById('school-year-alert');
                if (alertElem) {
                    var bsAlert = bootstrap.Alert.getOrCreateInstance(alertElem);
                    bsAlert.close();
                }
            }, 10000);
        </script>

        {{-- School Year Selection --}}
        <div class="row mb-3">
            <div class="col-12 d-flex justify-content-end align-items-end gap-2">
                {{-- School Year Filter Form --}}
                <form method="GET" action="{{ route('teacher.myClasses') }}" class="d-flex flex-column">
                    <label for="school_year" class="form-label mb-1">School Year</label>
                    <select name="school_year" id="school_year" class="form-select" onchange="this.form.submit()"
                        style="min-width: 150px;">
                        @foreach ($schoolYears as $year)
                            <option value="{{ $year }}" {{ $year == $selectedYear ? 'selected' : '' }}>
                                {{ $year }}
                            </option>
                        @endforeach
                    </select>
                </form>

                {{-- "Now" Button Form --}}
                <form method="GET" action="{{ route('teacher.myClasses') }}"
                    class="d-flex flex-column align-items-start">
                    <label class="form-label invisible mb-1">Now</label> {{-- Keeps vertical alignment --}}
                    <input type="hidden" name="school_year" value="{{ $currentYear . '-' . ($currentYear + 1) }}">
                    <input type="hidden" name="section" value="{{ $section }}">
                    <button type="submit" class="btn btn-primary">
                        Now
                    </button>
                </form>
            </div>
        </div>
        {{-- /School Year Selection --}}

        <!-- Card for Grade Levels by Section Assigned To Teacher -->
        <section id="services" class="services section">
            <div class="container" data-aos="fade-up" data-aos-delay="100">
                <div class="row gy-5">

                    @if ($classes->isEmpty())
                        <div class="col-12">
                            <div class="alert alert-warning text-center">
                                No Assigned Classes for <strong>{{ $selectedSection }}</strong>
                                School Year
                                <strong>{{ $selectedYear }}</strong>.
                                <br>
                                <span class="text-primary">Please check back later or contact the
                                    administration for more information.</span>
                            </div>
                        </div>
                    @else
                        @php $iconIndex = 1; @endphp
                        @foreach ($classes as $class)
                            <div class="col-xl-4 col-md-6" data-aos="zoom-in">
                                <div class="service-item">
                                    <div class="img">
                                        <img src="{{ asset('assets/img/classes/' . strtolower($class->grade_level) . '.jpg') }}"
                                            class="img-fluid" alt="" />
                                    </div>
                                    <div class="details position-relative">
                                        <div class="icon">
                                            @if ($class->grade_level === 'kindergarten')
                                                <i class="fa-solid fa-child"></i>
                                            @else
                                                <i class="fa-solid fa-{{ $iconIndex }}"></i>
                                                @php $iconIndex++; @endphp
                                            @endif
                                        </div>
                                        <a href="{{ route('teacher.myClass', ['grade_level' => $class->grade_level, 'section' => $class->section]) }}?school_year={{ $selectedYear }}"
                                            class="stretched-link">
                                            <h3>
                                                @if (strtolower($class->grade_level) === 'kindergarten')
                                                    Kindergarten
                                                @else
                                                    Grade
                                                    {{ preg_replace('/[^0-9]/', '', $class->grade_level) }}
                                                @endif
                                                - {{ $class->section }}
                                            </h3>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif

                </div>
            </div>
        </section>
        <!-- /Card for All Grade Levels by Section -->

    </div>
    <!-- Content wrapper -->

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
