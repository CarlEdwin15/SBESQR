@extends('./layouts.main')

@section('title', 'Parent | Dashboard')

@section('content')
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">

            <!-- Menu -->
            <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
                <div class="app-brand bg-dark">
                    <a href="{{ url('/home') }}" class="app-brand-link">
                        <img src="{{ asset('assets/img/logo.png') }}" alt="Logo" class="app-brand-logo">
                        <span class="app-brand-text menu-text fw-bolder text-warning" style="padding: 9px">Parent's
                            <span class="text-warning">Management</span>
                        </span>
                    </a>
                </div>

                <ul class="menu-inner py-1 bg-dark">

                    <!-- Dashboard sidebar-->
                    <li class="menu-item active">
                        <a href="{{ '/home ' }}" class="menu-link">
                            <i class="menu-icon tf-icons bx bx-home-circle"></i>
                            <div>Dashboard</div>
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
                                <a href="" class="menu-link bg-dark text-light">
                                    <div class="text-light">My Students</div>
                                </a>
                            </li>
                        </ul>
                    </li>

                    {{-- Classes sidebar --}}
                    <li class="menu-item">
                        <a href="javascript:void(0)" class="menu-link menu-toggle bg-dark text-light">
                            <i class="menu-icon tf-icons bx bx-notepad text-light"></i>
                            <div class="text-light">Classes</div>
                        </a>
                        <ul class="menu-sub">
                            <li class="menu-item">
                                <a href="{{ route('teacher.myClasses') }}" class="menu-link bg-dark text-light">
                                    <div class="text-light">My Classes</div>
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

                        <ul class="navbar-nav flex-row align-items-center ms-auto">

                            <button id="enablePush" class="btn btn-outline-primary">Enable browser notifications</button>

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
                                                <div class="rounded-circle d-flex justify-content-center align-items-center overflow-hidden"
                                                    style="width:36px; height:36px; background-color:#f8f9fa;">
                                                    <img src="assets/img/logo.png" alt="Logo" class="img-fluid"
                                                        style="width:100%; height:100%; object-fit:contain;">
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

                            <!-- User -->
                            <li class="nav-item navbar-dropdown dropdown-user dropdown">
                                <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);"
                                    data-bs-toggle="dropdown">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar">
                                            @php use Illuminate\Support\Str; @endphp

                                            @auth
                                                @php
                                                    $profilePhoto = Auth::user()->profile_photo;

                                                    if (!$profilePhoto) {
                                                        // Fallback if none is stored
                                                        $profilePhoto = asset(
                                                            'assetsDashboard/img/profile_pictures/teachers_default_profile.jpg',
                                                        );
                                                    } elseif (
                                                        !Str::startsWith($profilePhoto, ['http://', 'https://'])
                                                    ) {
                                                        // Stored locally
                                                        $profilePhoto = asset('storage/' . $profilePhoto);
                                                    }
                                                @endphp

                                                <img src="{{ $profilePhoto }}" alt="Profile Photo"
                                                    class="w-px-40 h-auto rounded-circle" />
                                            @else
                                                <img src="{{ asset('assetsDashboard/img/profile_pictures/teachers_default_profile.jpg') }}"
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
                                            <div class="d-flex align-items-center">
                                                <div class="avatar">
                                                    @auth
                                                        @php
                                                            $profilePhoto = Auth::user()->profile_photo;

                                                            if (!$profilePhoto) {
                                                                // Fallback if none is stored
                                                                $profilePhoto = asset(
                                                                    'assetsDashboard/img/profile_pictures/teachers_default_profile.jpg',
                                                                );
                                                            } elseif (
                                                                !Str::startsWith($profilePhoto, ['http://', 'https://'])
                                                            ) {
                                                                // Stored locally
                                                                $profilePhoto = asset('storage/' . $profilePhoto);
                                                            }
                                                        @endphp

                                                        <img src="{{ $profilePhoto }}" alt="Profile Photo"
                                                            class="w-px-40 h-auto rounded-circle" />
                                                    @else
                                                        <img src="{{ asset('assetsDashboard/img/profile_pictures/teachers_default_profile.jpg') }}"
                                                            alt="Default Profile Photo"
                                                            class="w-px-40 h-auto rounded-circle" />
                                                    @endauth
                                                </div>
                                                @auth
                                                    <span class="fw-semibold ms-2">{{ Auth::user()->firstName }}</span>
                                                @endauth
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

                <!-- / Content wrapper -->

            </div>
            <!-- / Layout page -->
        </div>

        <!-- Overlay -->
        <div class="layout-overlay layout-menu-toggle"></div>
    </div>
    <!-- / Layout wrapper -->
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

    <!-- Include Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Chart Initialization Script -->
    <script>
        // Enrollees Chart
        const ctx1 = document.getElementById('enrolleesChart').getContext('2d');
        new Chart(ctx1, {
            type: 'bar',
            data: {
                labels: ['Kndg', 'G1', 'G2', 'G3', 'G4', 'G5', 'G6'],
                datasets: [{
                    label: 'Enrollees',
                    data: [45, 35, 42, 155, 46, 34, 43],
                    backgroundColor: [
                        '#FF8A8A', '#82E6E6', '#FFE852', '#C9A5FF',
                        '#FF8A8A', '#82E6E6', '#FFE852'
                    ],
                    borderRadius: 8
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 10
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });

        // Gender Chart
        // Gender Statistics Chart
        const chartGenderStatistics = document.querySelector('#genderStatisticsChart');

        const genderChartConfig = {
            chart: {
                height: 165,
                width: 130,
                type: 'donut'
            },
            labels: ['Female', 'Male'],
            series: [60, 40],
            colors: ['#FF5B5B', '#2AD3E6'], // Red for Female, Blue for Male
            stroke: {
                width: 5,
                colors: '#fff'
            },
            dataLabels: {
                enabled: false,
                formatter: function(val) {
                    return parseInt(val) + '%';
                }
            },
            legend: {
                show: false
            },
            grid: {
                padding: {
                    top: 0,
                    bottom: 0,
                    right: 15
                }
            },
            plotOptions: {
                pie: {
                    donut: {
                        size: '75%',
                        labels: {
                            show: true,
                            value: {
                                fontSize: '1.5rem',
                                fontFamily: 'Public Sans',
                                color: '#333',
                                offsetY: -15,
                                formatter: function(val) {
                                    return parseInt(val) + '%';
                                }
                            },
                            name: {
                                offsetY: 20,
                                fontFamily: 'Public Sans'
                            },
                            total: {
                                show: true,
                                fontSize: '0.8125rem',
                                color: '#aaa',
                                label: 'Gender Ratio',
                                formatter: function() {
                                    return '100%';
                                }
                            }
                        }
                    }
                }
            }
        };

        if (chartGenderStatistics) {
            const genderChart = new ApexCharts(chartGenderStatistics, genderChartConfig);
            genderChart.render();
        }
    </script>

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
