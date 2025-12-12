@extends('./layouts.main')

@php
    use Carbon\Carbon;
@endphp

@section('title', 'Parent | My Children Info')

@section('content')
    <!-- Menu -->
    <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
        <div class="app-brand bg-dark">
            <a href="{{ url('/home') }}" class="app-brand-link">
                <img src="{{ asset('assets/img/logo.png') }}" alt="Logo" class="app-brand-logo">
                <span class="app-brand-text menu-text fw-bolder text-light" style="padding: 9px">Parent's
                    <span class="text-light">Dashboard</span>
                </span>
            </a>

            <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large d-block d-xl-none">
                <i class="bx bx-chevron-left bx-sm align-middle"></i>
            </a>
        </div>

        <ul class="menu-inner py-1 bg-dark">

            <!-- Dashboard sidebar-->
            <li class="menu-item active">
                <a href="{{ '/home ' }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-home-circle"></i>
                    <div class="text-warning">My Children</div>
                </a>
            </li>

            {{-- School Fees sidebar --}}
            {{-- <li class="menu-item">
                <a href="{{ route('parent.school-fees.index') }}" class="menu-link bg-dark text-light">
                    <i class="menu-icon tf-icons bx bx-wallet-alt text-light"></i>
                    <div class="text-light">School Fees</div>
                </a>
            </li> --}}

            {{-- Announcements sidebar --}}
            {{-- <li class="menu-item">
                <a href="{{ route('parent.announcements.index') }}" class="menu-link bg-dark text-light">
                    <i class="menu-icon tf-icons bx bxs-megaphone text-light"></i>
                    <div class="text-light">Announcements</div>
                </a>
            </li> --}}

            {{-- Account Settings sidebar --}}
            <li class="menu-item">
                <a href="{{ route('parent.account.settings') }}" class="menu-link bg-dark text-light">
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
    <div class="container-xxl flex-grow-1 container-p-y">

        <!-- Breadcrumb -->
        <h4 class="fw-bold py-3 mb-4 text-warning">
            <span class="text-muted fw-light">
                <a class="text-muted fw-light" href="{{ route('home') }}">Dashboard / </a>
                <a class="text-muted fw-light" href="{{ route('parent.children.index') }}">My Children / </a>
            </span> My Child Information
        </h4>

        <div class="row">
            <!-- Left Profile Card -->
            <div class="col-md-4 mb-4">
                <div class="card shadow p-3 align-items-center text-center">
                    @if ($child->student_photo)
                        <img src="{{ asset('public/uploads/' . $child->student_photo) }}" alt="Student Photo"
                            class="mb-3 mt-2" style="object-fit: cover; height: 200px; width: 200px;">
                    @else
                        <img src="{{ asset('assetsDashboard/img/student_profile_pictures/student_default_profile.jpg') }}"
                            alt="Default Photo" class="mb-3 mt-2" style="object-fit: cover; height: 200px; width: 200px;">
                    @endif

                    <h5 class="fw-bold">{{ $child->student_fName }} {{ $child->student_lName }}</h5>

                    <!-- LRN -->
                    <div class="text-start mb-3">
                        <div><span class="fw-bold">LRN:</span> {{ $child->student_lrn }}</div>
                    </div>

                    <!-- Student Status Display (Updated to match admin) -->
                    <div class="mt-2 mb-3 text-center">
                        <span class="fw-bold">Student Status</span><br>

                        @php
                            $displayStatus = match ($studentStatus) {
                                'enrolled' => 'Enrolled',
                                'graduated' => 'Graduated',
                                'archived', 'not_enrolled' => 'Inactive',
                                'dropped' => 'Dropped',
                                default => ucfirst($studentStatus),
                            };

                            $badgeClass = match ($displayStatus) {
                                'Enrolled' => 'bg-label-success fw-bold',
                                'Inactive' => 'bg-label-secondary fw-bold',
                                'Dropped' => 'bg-label-danger fw-bold',
                                'Graduated' => 'bg-label-info fw-bold',
                                default => 'bg-label-warning fw-bold',
                            };
                        @endphp

                        <span class="badge {{ $badgeClass }} px-3 py-2">{{ $displayStatus }}</span><br>

                        @if (!empty($statusInfo))
                            <small class="text-muted d-block mt-1">{{ $statusInfo }}</small>
                        @endif
                    </div>

                    <div class="d-flex justify-content-center mt-3">
                        <a href="{{ route('parent.children.index') }}"
                            class="btn btn-danger me-2 d-flex align-items-center">
                            <i class='bx bx-chevrons-left'></i>
                            <span class="d-none d-sm-block">Back</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Right Tabs + Info -->
            <div class="col-md-8">
                @include('partials.student_tabs', [
                    'student' => $child,
                    'classHistory' => $classHistory,
                    'gradesByClass' => $gradesByClass,
                    'generalAverages' => $generalAverages,
                ])
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
