@extends('./layouts.main')

@section('title', 'Parent | Dashboard')

@section('content')
    <!-- Menu -->
    <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
        <div class="app-brand bg-dark">
            <a href="{{ url('/home') }}" class="app-brand-link">
                <img src="{{ asset('assets/img/logo.png') }}" alt="Logo" class="app-brand-logo">
                <span class="app-brand-text menu-text fw-bolder text-warning" style="padding: 9px">Parent's
                    <span class="text-warning">Dashboard</span>
                </span>
            </a>
        </div>

        <ul class="menu-inner py-1 bg-dark">

            <!-- Dashboard sidebar-->
            <li class="menu-item active">
                <a href="{{ '/home ' }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-home-circle"></i>
                    <div class="text-warning">Dashboard</div>
                </a>
            </li>

            {{-- My Children sidebar --}}
            <li class="menu-item">
                <a href="{{ route('parent.children.index') }}" class="menu-link bg-dark text-light">
                    <i class="menu-icon tf-icons bx bx-child text-light"></i>
                    <div class="text-light">My Children</div>
                </a>
            </li>

            {{-- School Fees sidebar --}}
            <li class="menu-item">
                <a href="{{ route('parent.school-fees.index') }}" class="menu-link bg-dark text-light">
                    <i class="menu-icon tf-icons bx bx-wallet-alt text-light"></i>
                    <div class="text-light">School Fees</div>
                </a>
            </li>

            {{-- Announcements sidebar --}}
            <li class="menu-item">
                <a href="{{ route('parent.announcements.index') }}" class="menu-link bg-dark text-light">
                    <i class="menu-icon tf-icons bx bxs-megaphone text-light"></i>
                    <div class="text-light">Announcements</div>
                </a>
            </li>

            {{-- SMS Logs sidebar --}}
            <li class="menu-item">
                <a href="{{ route('parent.sms-logs.index') }}" class="menu-link bg-dark text-light">
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

    <!-- Content Wrapper -->
    <div class="container-xxl container-p-y">

        <div class="row mb-4 g-3">
            <!-- My Children Card -->
            <div class="col-6 col-md-3">
                <div class="card h-100 card-hover">
                    <a href="{{ route('parent.children.index') }}" class="card-body">
                        <div class="card-title d-flex align-items-start justify-content-between">
                            <div class="avatar flex-shrink-0">
                                <img src="{{ asset('assetsDashboard/img/icons/dashIcon/studentIcon.png') }}"
                                    alt="My Children" class="rounded" />
                            </div>
                        </div>
                        <span class="fw-semibold d-block mb-1 text-primary">My Children</span>
                        <h3 class="card-title mb-2"></h3>
                    </a>
                </div>
            </div>

            <!-- School Fees Card -->
            <div class="col-6 col-md-3">
                <div class="card h-100 card-hover">
                    <a href="{{ route('parent.school-fees.index') }}" class="card-body">
                        <div class="card-title d-flex align-items-start justify-content-between">
                            <div class="avatar flex-shrink-0">
                                <img src="{{ asset('assetsDashboard/img/icons/dashIcon/school-fees.png') }}"
                                    alt="School Fees" class="rounded" />
                            </div>
                        </div>
                        <span class="fw-semibold d-block mb-1 text-primary">School Fees</span>
                        <h3 class="card-title mb-2"></h3>
                    </a>
                </div>
            </div>

            <!-- Announcements Card -->
            <div class="col-6 col-md-3">
                <div class="card h-100 card-hover">
                    <a href="{{ route('parent.announcements.index') }}" class="card-body">
                        <div class="card-title d-flex align-items-start justify-content-between">
                            <div class="avatar flex-shrink-0">
                                <img src="{{ asset('assetsDashboard/img/icons/dashIcon/announcement.png') }}"
                                    alt="Announcements" class="rounded" />
                            </div>
                        </div>
                        <span class="fw-semibold d-block mb-1 text-primary">Announcements</span>
                        <h3 class="card-title text-nowrap mb-2"></h3>
                    </a>
                </div>
            </div>

            <!-- SMS Logs Card -->
            <div class="col-6 col-md-3">
                <div class="card h-100 card-hover">
                    <a href="{{ route('parent.sms-logs.index') }}" class="card-body">
                        <div class="card-title d-flex align-items-start justify-content-between">
                            <div class="avatar flex-shrink-0">
                                <img src="{{ asset('assetsDashboard/img/icons/dashIcon/sms.png') }}"
                                    alt="SMS Logs" class="rounded" />
                            </div>
                        </div>
                        <span class="fw-semibold d-block mb-1 text-primary">SMS Logs</span>
                        <h3 class="card-title mb-2"></h3>
                    </a>
                </div>
            </div>
        </div>


    </div>
    <!-- /Content Wrapper -->
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
    </style>
@endpush
