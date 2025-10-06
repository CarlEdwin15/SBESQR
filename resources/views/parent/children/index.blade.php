@extends('./layouts.main')

@section('title', 'Parent | My Children')

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
        </div>

        <ul class="menu-inner py-1 bg-dark">

            <!-- Dashboard sidebar-->
            <li class="menu-item">
                <a href="{{ '/home ' }}" class="menu-link bg-dark text-light">
                    <i class="menu-icon tf-icons bx bx-home-circle text-light"></i>
                    <div class="text-light">Dashboard</div>
                </a>
            </li>

            {{-- SMS Logs sidebar --}}
            <li class="menu-item active">
                <a href="{{ route('parent.children.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-child"></i>
                    <div class="text-warning">My Children</div>
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

        <!-- Parent Dashboard Layout -->
        <div class="row">

            @php
                use Illuminate\Support\Facades\Auth;

                // Get the currently logged-in parent
                $parent = Auth::user();

                // Get their children via the relationship
                $children = $parent->children()->with('classStudents.class', 'schoolYears')->get();
            @endphp

            <div class="col-12 mb-4">
                <h4 class="fw-bold mb-3">👨‍👩‍👧‍👦 My Children</h4>
            </div>

            @if ($children->isEmpty())
                <div class="col-12">
                    <div class="alert alert-info">
                        You have no children linked to your account yet.
                    </div>
                </div>
            @else
                @foreach ($children as $child)
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card h-100 shadow-sm border-0">
                            <div class="card-body d-flex align-items-center">
                                <img src="{{ $child->student_photo ? asset('storage/' . $child->student_photo) : asset('images/default-avatar.png') }}"
                                    alt="Student Photo" class="rounded-circle me-3" width="70" height="70"
                                    style="object-fit: cover;">

                                <div>
                                    <h5 class="mb-1 fw-semibold">{{ $child->full_name }}</h5>
                                    <small class="text-muted">
                                        <i class="{{ $child->sex_icon }}"></i>
                                        {{ ucfirst($child->gender ?? 'N/A') }}
                                    </small>
                                    <br>

                                    @php
                                        $latestClass = optional($child->classStudents->last())->class;
                                    @endphp

                                    @if ($latestClass)
                                        <small class="text-secondary">
                                            {{ $latestClass->formatted_grade_level }} - Section
                                            {{ $latestClass->section }}
                                        </small>
                                    @else
                                        <small class="text-secondary">No class assigned yet</small>
                                    @endif

                                    <br>
                                    <small class="text-muted">
                                        LRN: {{ $child->student_lrn }}
                                    </small>
                                </div>
                            </div>

                            <div class="card-footer bg-transparent border-0 text-end">
                                <a href="{{ route('parent.children.show', $child->id) }}"
                                    class="btn btn-sm btn-outline-primary">
                                    View Profile
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif

        </div>
        <!-- /Parent Dashboard Layout -->

    </div>
    <!-- /Content Wrapper -->
@endsection
