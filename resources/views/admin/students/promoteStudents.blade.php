@extends('layouts.main')

@section('title', 'Promote Students')

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
                            <div class="text-light">All Teachers</div>
                        </a>
                    </li>
                </ul>
            </li>

            {{-- Students sidebar --}}
            <li class="menu-item active open">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons bx bxs-graduation"></i>
                    <div>Students</div>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item">
                        <a href="{{ route('show.students') }}" class="menu-link bg-dark text-light">
                            <div class="text-light">All Students</div>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="{{ route('add.student') }}" class="menu-link bg-dark text-light">
                            <div class="text-light">Student Enrollment</div>
                        </a>
                    </li>
                    <li class="menu-item active">
                        <a href="{{ route('students.promote.view') }}" class="menu-link bg-dark text-light">
                            <div class="text-warning">Student Promotion</div>
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
                            <div class="text-light">Classes</div>
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
        <h4 class="fw-bold py-3 mb-4 text-warning"><span class="text-muted fw-light">
                <a class="text-muted fw-light" href="{{ route('home') }}">Dashboard / </a>
                <a class="text-muted fw-light" href="{{ route('show.students') }}">Students / </a>
            </span> Student Promotion
        </h4>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="mb-4 text-center">
            <h4 class="fw-bold text-primary">
                Promote Students from {{ $previousSchoolYear }} to {{ $currentSchoolYear }}
            </h4>
        </div>

        <h4 class="fw-bold text-primary text-center">{{ strtoupper($gradeLevel) }} - {{ $selectedSection }}
        </h4>

        <form action="{{ route('students.promote') }}" method="POST" enctype="multipart/form-data"
            class="p-4 bg-white rounded shadow-sm">
            @csrf

            <!-- Search & Promotion Selection -->
            <div class="d-flex gap-2 align-items-center mb-4">
                <input type="search" class="form-control" placeholder="Search Student..." id="studentSearch">

                @php
                    $promotionMap = [
                        'kindergarten' => ['kindergarten', 'grade1'],
                        'grade1' => ['grade1', 'grade2'],
                        'grade2' => ['grade2', 'grade3'],
                        'grade3' => ['grade3', 'grade4'],
                        'grade4' => ['grade4', 'grade5'],
                        'grade5' => ['grade5', 'grade6'],
                        'grade6' => ['grade6', 'graduated'],
                    ];
                @endphp

                <select class="form-select w-auto" name="batch_grade" id="batchGrade" required>
                    <option value="" disabled selected>Promote To Grade</option>
                    @foreach ($promotionMap[$gradeLevel] ?? [] as $level)
                        @php
                            $label = $level === $gradeLevel ? '(returnee)' : '(promoted)';
                            $formatted = ucfirst($level) . " $label";
                        @endphp
                        <option value="{{ $level }}">{{ $formatted }}</option>
                    @endforeach
                </select>

                <select class="form-select w-auto" name="batch_section" id="batchSection" required>
                    <option value="" disabled selected>Promote To Section</option>
                    @foreach (['A', 'B', 'C', 'D', 'E', 'F'] as $section)
                        <option value="{{ $section }}">{{ $section }}</option>
                    @endforeach
                </select>
            </div>

            <input type="hidden" name="next_school_year" value="{{ $currentSchoolYear }}">

            <div class="row">
                <!-- MALE STUDENTS -->
                <div class="table-responsive mb-4">
                    <h4 class="d-flex justify-content-between align-items-center text-primary fw-bold">
                        Male Students

                    </h4>
                    <table class="table table-hover table-bordered align-middle" id="maleStudentTable">
                        <thead class="table-info text-center">
                            <tr>
                                <th style="width: 60px;"><input type="checkbox" id="selectAllMale"></th>
                                <th style="width: 40px;">No.</th>
                                <th>Student Name</th>
                                <th>Enrollment Status</th>
                                <th>Student LRN</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $maleIndex = 1; @endphp
                            @foreach ($activeStudents->where('student_sex', 'male')->sortBy(fn($s) => strtolower($s->student_lName . ' ' . $s->student_fName . ' ' . $s->student_mName)) as $student)
                                <tr>
                                    <td class="text-center">
                                        <input type="checkbox" name="selected_male_students[]"
                                            value="{{ $student->id }}">
                                    </td>
                                    <td class="text-center">{{ $maleIndex++ }}</td>
                                    <td>{{ $student->student_lName }}, {{ $student->student_fName }}
                                        {{ $student->student_mName }} {{ $student->student_extName }}</td>
                                    <td class="text-center">
                                        @php
                                            $status = $student->current_enrollment_status ?? 'N/A';
                                            $badgeClass = match ($status) {
                                                'enrolled' => 'bg-label-success',
                                                'not_enrolled' => 'bg-label-secondary',
                                                'archived' => 'bg-label-warning',
                                                'graduated' => 'bg-label-info',
                                                default => 'bg-label-dark',
                                            };
                                        @endphp
                                        <span class="badge {{ $badgeClass }} text-uppercase px-3 py-1">
                                            {{ strtoupper(str_replace('_', ' ', $status)) }}
                                        </span>
                                    </td>
                                    <td class="text-center">{{ $student->student_lrn }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>


                <!-- FEMALE STUDENTS -->
                <div class="table-responsive mb-4">
                    <h4 class="d-flex justify-content-between align-items-center text-primary fw-bold">
                        Female Students
                    </h4>
                    <table class="table table-hover table-bordered align-middle" id="femaleStudentTable">
                        <thead class="table-danger text-center">
                            <tr>
                                <th style="width: 60px;"><input type="checkbox" id="selectAllFemale"></th>
                                <th style="width: 40px;">No.</th>
                                <th>Student Name</th>
                                <th>Enrollment Status</th>
                                <th>Student LRN</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $femaleIndex = 1; @endphp
                            @foreach ($activeStudents->where('student_sex', 'female')->sortBy(fn($s) => strtolower($s->student_lName . ' ' . $s->student_fName . ' ' . $s->student_mName)) as $student)
                                <tr>
                                    <td class="text-center">
                                        <input type="checkbox" name="selected_female_students[]"
                                            value="{{ $student->id }}">
                                    </td>
                                    <td class="text-center">{{ $femaleIndex++ }}</td>
                                    <td>{{ $student->student_lName }}, {{ $student->student_fName }}
                                        {{ $student->student_mName }} {{ $student->student_extName }}</td>
                                    <td class="text-center">
                                        @php
                                            $status = $student->current_enrollment_status ?? 'N/A';
                                            $badgeClass = match ($status) {
                                                'enrolled' => 'bg-label-success',
                                                'not_enrolled' => 'bg-label-secondary',
                                                'archived' => 'bg-label-warning',
                                                'graduated' => 'bg-label-info',
                                                default => 'bg-label-dark',
                                            };
                                        @endphp
                                        <span class="badge {{ $badgeClass }} text-uppercase px-3 py-1">
                                            {{ strtoupper(str_replace('_', ' ', $status)) }}
                                        </span>
                                    </td>
                                    <td class="text-center">{{ $student->student_lrn }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            </div>


            <!-- Hidden merged inputs -->
            <div id="mergedStudentInputs"></div>

            <!-- Submit -->
            <div class="d-flex justify-content-end mt-3">
                <button type="submit" class="btn btn-primary">Promote Selected Students</button>
            </div>
        </form>

        <!-- Scripts -->
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Disable section select if grade is 'graduated'
                const gradeSelect = document.getElementById('batchGrade');
                const sectionSelect = document.getElementById('batchSection');

                // Watch for grade changes
                gradeSelect.addEventListener('change', function() {
                    if (this.value === 'graduated') {
                        sectionSelect.disabled = true;
                        sectionSelect.required = false;
                        sectionSelect.closest('.form-select').classList.add(
                            'd-none'); // Optional: hide visually
                    } else {
                        sectionSelect.disabled = false;
                        sectionSelect.required = true;
                        sectionSelect.closest('.form-select').classList.remove('d-none');
                    }
                });

                const form = document.querySelector('form[action="{{ route('students.promote') }}"]');

                // Select Male Students
                document.getElementById("selectAllMale").addEventListener("click", function() {
                    document.querySelectorAll('input[name="selected_male_students[]"]').forEach(cb => cb
                        .checked = this.checked);
                });

                // Select Female Students
                document.getElementById("selectAllFemale").addEventListener("click", function() {
                    document.querySelectorAll('input[name="selected_female_students[]"]').forEach(cb => cb
                        .checked = this.checked);
                });

                // Search
                document.getElementById("studentSearch").addEventListener("input", function() {
                    const val = this.value.toLowerCase();
                    document.querySelectorAll("#maleStudentTable tbody tr, #femaleStudentTable tbody tr")
                        .forEach(row => {
                            const text = row.innerText.toLowerCase();
                            row.style.display = text.includes(val) ? "" : "none";
                        });
                });

                // Merge checkboxes on submit
                form.addEventListener('submit', function(e) {
                    const mergedContainer = document.getElementById('mergedStudentInputs');
                    mergedContainer.innerHTML = '';

                    const maleChecked = document.querySelectorAll(
                        'input[name="selected_male_students[]"]:checked');
                    const femaleChecked = document.querySelectorAll(
                        'input[name="selected_female_students[]"]:checked');
                    const allChecked = [...maleChecked, ...femaleChecked];

                    if (allChecked.length === 0) {
                        e.preventDefault();
                        alert("Please select at least one student to promote.");
                        return;
                    }

                    // Create hidden inputs for each selected student
                    allChecked.forEach(cb => {
                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = 'selected_students[]';
                        input.value = cb.value;
                        mergedContainer.appendChild(input);
                    });
                });
            });
        </script>



        <hr class="my-5" />

    </div>
    <!-- Content wrapper -->

@endsection

@push('scripts')

    <script>
        // alert after a success edit or delete of teacher's info
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
    </script>

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
@endpush
