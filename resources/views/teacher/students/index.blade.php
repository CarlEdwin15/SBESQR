@extends('./layouts.main')

@section('title', 'Teacher | My Students')

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

            {{-- Reports sidebar --}}
            <li class="menu-item">
                <a class="menu-link menu-toggle bg-dark text-light">
                    <i class="menu-icon tf-icons bx bx-detail text-light"></i>
                    <div class="text-light">Reports</div>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item">
                        <a href="" class="menu-link bg-dark text-light">
                            <div class="text-light">Overall Attendance</div>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="" class="menu-link bg-dark text-light">
                            <div class="text-light">Class Master List</div>
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
        <h4 class="fw-bold py-3 mb-4 text-warning"><span class="text-muted fw-light">
                <a class="text-muted fw-light" href="{{ route('home') }}">Dashboard / </a>
                <a class="text-muted fw-light" href="{{ route('teacher.my.students') }}">Students / </a>
            </span> Student's Info
        </h4>

        <!-- Student's Details -->
        <div class="card shadow">
            <div class="card-body">
                <div class="row">
                    {{-- Student Photo --}}
                    <div class="col-md-4 mt-3 mb-3 text-center d-flex flex-column align-items-center">
                        @if ($student->student_photo)
                            <img src="{{ asset('storage/' . $student->student_photo) }}" alt="Student Photo"
                                class="img-thumbnail mb-3" style="object-fit: cover; height: 450px; width: 450px;">
                        @else
                            <img src="{{ asset('assetsDashboard/img/student_profile_pictures/student_default_profile.jpg') }}"
                                alt="Default Photo" class="img-thumbnail mb-3"
                                style="object-fit: cover; height: 450px; width: 450px;">
                        @endif
                        <h4 class="mt-2 text-primary" style="font-weight: 600;">Student Profile:
                            <br>{{ $student->student_fName }} {{ $student->student_lName }}
                        </h4>
                    </div>

                    {{-- Student Details --}}
                    <div class="col-md-8 mt-3 mb-3">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <th>LRN</th>
                                    <td>{{ $student->student_lrn }}</td>
                                </tr>
                                <tr>
                                    <th>Grade Level</th>
                                    <td>{{ ucfirst($student->student_grade_level) }}</td>
                                </tr>
                                <tr>
                                    <th>Section</th>
                                    <td>{{ $student->student_section }}</td>
                                </tr>
                                <tr>
                                    <th>Date of Birth</th>
                                    <td>{{ $student->student_dob }}</td>
                                </tr>
                                <tr>
                                    <th>Sex</th>
                                    <td>{{ $student->student_sex }}</td>
                                </tr>
                                <tr>
                                    <th>Age</th>
                                    <td>{{ $student->student_age }}</td>
                                </tr>
                                <tr>
                                    <th>Place of Birth</th>
                                    <td>{{ $student->student_pob }}</td>
                                </tr>
                                <tr>
                                    <th>Address</th>
                                    <td>{{ $student->student_address }}</td>
                                </tr>
                                <tr>
                                    <th>Father's Name</th>
                                    <td>{{ $student->student_fatherFName }}
                                        {{ $student->student_fatherMName }}
                                        {{ $student->student_fatherLName }}</td>
                                </tr>
                                <tr>
                                    <th>Mother's Name</th>
                                    <td>{{ $student->student_motherFName }}
                                        {{ $student->student_motherMName }}
                                        {{ $student->student_motherLName }}</td>
                                </tr>
                                <tr>
                                    <th>Parent's Contact</th>
                                    <td>{{ $student->student_parentPhone }}</td>
                                </tr>
                                <tr>
                                    <th>QR Code</th>
                                    <td>{{ $student->qr_code }}</td>
                                </tr>
                            </tbody>
                        </table>

                        <a href="{{ route('teacher.my.students') }}" class="btn btn-secondary mt-3">Back</a>
                    </div>
                </div>
            </div>
        </div>
        <!-- / Student's Details -->

        <hr class="my-5" />

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
@endpush
