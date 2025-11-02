@extends('layouts.main')

@section('title', 'Teacher | Grades')

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

            {{-- Account Settings sidebar --}}
            <li class="menu-item">
                <a href="{{ route('teacher.account.settings') }}" class="menu-link bg-dark text-light">
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
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <h4 class="fw-bold text-warning mb-0">
                    <span class="text-muted fw-light">
                        <a class="text-muted fw-light" href="{{ route('home') }}">Dashboard</a> /
                        <a class="text-muted fw-light" href="{{ route('teacher.myClasses') }}">Classes</a> /
                        <a class="text-muted fw-light"
                            href="{{ route('teacher.myClass', ['grade_level' => $class->grade_level, 'section' => $class->section, 'school_year' => $selectedYear]) }}">
                            {{ ucfirst($class->grade_level) }} - {{ $class->section }} ({{ $selectedYear }})
                        </a> /
                        <a class="text-muted fw-light"
                            href="{{ route('teacher.myClassSubject', ['grade_level' => $class->grade_level, 'section' => $class->section, 'school_year' => $selectedYear]) }}">
                            Grades
                        </a> /
                    </span>
                    {{ $classSubject->subject->name }}
                </h4>
            </div>
        </div>

        <h3 class="text-center text-info fw-bold mb-4"> {{ $classSubject->subject->name }}</h3>

        <div class="d-flex justify-content-between align-items-center flex-wrap mb-3">
            <div class="d-flex gap-2 mb-2 mb-md-0">
                <a href="{{ route('teacher.myClassSubject', ['grade_level' => $class->grade_level, 'section' => $class->section, 'school_year' => $selectedYear]) }}"
                    class="btn btn-danger d-flex align-items-center">
                    <i class='bx bx-chevrons-left'></i>
                    <span class="d-none d-sm-block">Back</span>
                </a>
            </div>
            {{-- <div>
                <a href="{{ route('teacher.subjects.export', [
                    'grade_level' => $class->grade_level,
                    'section' => $class->section,
                    'subject_id' => $classSubject->id,
                ]) }}"
                    class="btn btn-success d-flex align-items-center">
                    <i class='bx bx-printer me-2'></i><span class="d-none d-sm-block">Export</span>
                </a>

            </div> --}}
        </div>

        <div class="card p-4 shadow-sm">
            <h4 class="text-primary fw-bold mb-3">{{ $class->formatted_grade_level }} - {{ $class->section }}
            </h4>

            <form
                action="{{ route('teacher.subjects.saveGrades', [
                    'grade_level' => $class->grade_level,
                    'section' => $class->section,
                    'subject_id' => $classSubject->id,
                    'school_year' => $selectedYear,
                ]) }}"
                class="save-grades-form" method="POST">
                @csrf

                {{-- MALE STUDENTS --}}
                <div class="table-responsive mb-4">
                    @if (session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <table class="table table-hover table-bordered align-middle">
                        <thead class="table-info">
                            <tr class="text-center">
                                <th style="width: 40px;">No.</th>
                                <th style="width: 5%;">PHOTO</th>
                                <th style="width: 20%">Male || Name</th>
                                <th class="toggle-edit text-decoration-underline cursor-pointer"
                                    @if ($canEdit) data-quarter="1" @endif>
                                    1st Quarter
                                </th>
                                <th class="toggle-edit text-decoration-underline cursor-pointer"
                                    @if ($canEdit) data-quarter="2" @endif>
                                    2nd Quarter
                                </th>
                                <th class="toggle-edit text-decoration-underline cursor-pointer"
                                    @if ($canEdit) data-quarter="3" @endif>
                                    3rd Quarter
                                </th>
                                <th class="toggle-edit text-decoration-underline cursor-pointer"
                                    @if ($canEdit) data-quarter="4" @endif>
                                    4th Quarter
                                </th>
                                <th>Final Grade</th>
                                <th>Remarks</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $maleIndex = 1; @endphp
                            @foreach ($students->where('gender', 'male')->sortBy(fn($s) => strtolower($s->student_lName . ' ' . $s->student_fName)) as $student)
                                <tr data-student="{{ $student->id }}">
                                    <td class="text-center">{{ $maleIndex++ }}</td>
                                    <td class="text-center">
                                        <a
                                            href="{{ route('teacher.student.info', ['id' => $student->id, 'school_year' => $schoolYearId]) }}">
                                            <img src="{{ $student->student_photo ? asset('storage/' . $student->student_photo) : asset('assetsDashboard/img/student_profile_pictures/student_default_profile.jpg') }}"
                                                alt="Student Photo" class="rounded-circle me-2 student-photo"
                                                style="width: 40px; height: 40px;">
                                        </a>
                                    </td>
                                    <td>{{ $student->student_lName }}, {{ $student->student_fName }}
                                        {{ $student->student_mName }}</td>

                                    {{-- Quarters --}}
                                    @foreach ([1, 2, 3, 4] as $q)
                                        @php
                                            $grade =
                                                $student->quarterlyGrades->firstWhere('quarter.quarter', $q)
                                                    ->final_grade ?? '';
                                            // Inline color logic
                                            $gradeColor = '';
                                            if (is_numeric($grade)) {
                                                if ($grade >= 90) {
                                                    $gradeColor = 'text-success';
                                                }
                                                // Outstanding
                                                elseif ($grade >= 85) {
                                                    $gradeColor = 'text-success';
                                                }
                                                // Very Satisfactory
                                                elseif ($grade >= 80) {
                                                    $gradeColor = 'text-warning';
                                                }
                                                // Satisfactory
                                                elseif ($grade >= 75) {
                                                    $gradeColor = 'text-warning';
                                                }
                                                // Fairly Satisfactory
                                                else {
                                                    $gradeColor = 'text-danger fw-semibold';
                                                } // Did Not Meet Expectations
                                            }
                                        @endphp
                                        <td class="text-center">
                                            @if ($canEdit)
                                                <input type="number"
                                                    name="grades[{{ $student->id }}][q{{ $q }}]"
                                                    class="form-control quarter-input quarter-{{ $q }} {{ $gradeColor }}"
                                                    value="{{ $grade }}" min="0" max="100"
                                                    step="0.01" disabled>
                                            @else
                                                <span class="{{ $gradeColor }}">{{ $grade }}</span>
                                            @endif
                                        </td>
                                    @endforeach

                                    {{-- Final Grade + Remarks --}}
                                    @php
                                        $quarterGrades = [];
                                        foreach ([1, 2, 3, 4] as $q) {
                                            $quarterGrades[] =
                                                $student->quarterlyGrades->firstWhere('quarter.quarter', $q)
                                                    ->final_grade ?? null;
                                        }

                                        // Check if all 4 quarters exist
                                        $allQuartersHaveGrades =
                                            count(array_filter($quarterGrades, fn($g) => $g !== null)) === 4;

                                        $finalAverage = $allQuartersHaveGrades
                                            ? round(array_sum($quarterGrades) / 4)
                                            : '';

                                        $remarks = $allQuartersHaveGrades
                                            ? ($finalAverage >= 75
                                                ? 'PASSED'
                                                : 'FAILED')
                                            : '';
                                    @endphp

                                    @php
                                        $finalColor = '';
                                        if (is_numeric($finalAverage)) {
                                            if ($finalAverage >= 90) {
                                                $finalColor = 'text-success fw-bold';
                                            } elseif ($finalAverage >= 85) {
                                                $finalColor = 'text-success fw-bold';
                                            } elseif ($finalAverage >= 80) {
                                                $finalColor = 'text-warning fw-bold';
                                            } elseif ($finalAverage >= 75) {
                                                $finalColor = 'text-warning fw-bold';
                                            } else {
                                                $finalColor = 'text-danger fw-semibold';
                                            }
                                        }
                                    @endphp
                                    <td class="text-center">
                                        <span class="final-grade {{ $finalColor }}">{{ $finalAverage }}</span>
                                    </td>

                                    </td>
                                    <td class="text-center"><span
                                            class="remarks fw-semibold text-uppercase">{{ $remarks }}</span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- FEMALE STUDENTS --}}
                <div class="table-responsive">
                    <table class="table table-hover table-bordered align-middle">
                        <thead class="table-danger">
                            <tr class="text-center">
                                <th style="width: 40px;">No.</th>
                                <th style="width: 5%;">Photo</th>
                                <th style="width: 20%">Female || Name</th>
                                <th class="toggle-edit text-decoration-underline cursor-pointer"
                                    @if ($canEdit) data-quarter="1" @endif>
                                    1st Quarter
                                </th>
                                <th class="toggle-edit text-decoration-underline cursor-pointer"
                                    @if ($canEdit) data-quarter="2" @endif>
                                    2nd Quarter
                                </th>
                                <th class="toggle-edit text-decoration-underline cursor-pointer"
                                    @if ($canEdit) data-quarter="3" @endif>
                                    3rd Quarter
                                </th>
                                <th class="toggle-edit text-decoration-underline cursor-pointer"
                                    @if ($canEdit) data-quarter="4" @endif>
                                    4th Quarter
                                </th>
                                <th>Final Grade</th>
                                <th>Remarks</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $femaleIndex = 1; @endphp
                            @foreach ($students->where('gender', 'female')->sortBy(fn($s) => strtolower($s->student_lName . ' ' . $s->student_fName)) as $student)
                                <tr data-student="{{ $student->id }}">
                                    <td class="text-center">{{ $femaleIndex++ }}</td>
                                    <td class="text-center">
                                        <a
                                            href="{{ route('teacher.student.info', ['id' => $student->id, 'school_year' => $schoolYearId]) }}">
                                            <img src="{{ $student->student_photo ? asset('storage/' . $student->student_photo) : asset('assetsDashboard/img/student_profile_pictures/student_default_profile.jpg') }}"
                                                alt="Student Photo" class="rounded-circle me-2 student-photo"
                                                style="width: 40px; height: 40px;">
                                        </a>
                                    </td>
                                    <td>{{ $student->student_lName }}, {{ $student->student_fName }}
                                        {{ $student->student_mName }}</td>

                                    {{-- Quarters --}}
                                    @foreach ([1, 2, 3, 4] as $q)
                                        @php
                                            $grade =
                                                $student->quarterlyGrades->firstWhere('quarter.quarter', $q)
                                                    ->final_grade ?? '';
                                            // Inline color logic
                                            $gradeColor = '';
                                            if (is_numeric($grade)) {
                                                if ($grade >= 90) {
                                                    $gradeColor = 'text-success fw-bold';
                                                }
                                                // Outstanding
                                                elseif ($grade >= 85) {
                                                    $gradeColor = 'text-success';
                                                }
                                                // Very Satisfactory
                                                elseif ($grade >= 80) {
                                                    $gradeColor = 'text-warning';
                                                }
                                                // Satisfactory
                                                elseif ($grade >= 75) {
                                                    $gradeColor = 'text-warning';
                                                }
                                                // Fairly Satisfactory
                                                else {
                                                    $gradeColor = 'text-danger fw-semibold';
                                                } // Did Not Meet Expectations
                                            }
                                        @endphp
                                        <td class="text-center">
                                            @if ($canEdit)
                                                <input type="number"
                                                    name="grades[{{ $student->id }}][q{{ $q }}]"
                                                    class="form-control quarter-input quarter-{{ $q }} {{ $gradeColor }}"
                                                    value="{{ $grade }}" min="0" max="100"
                                                    step="0.01" disabled>
                                            @else
                                                <span class="{{ $gradeColor }}">{{ $grade }}</span>
                                            @endif
                                        </td>
                                    @endforeach

                                    {{-- Final Grade + Remarks --}}
                                    @php
                                        $quarterGrades = [];
                                        foreach ([1, 2, 3, 4] as $q) {
                                            $quarterGrades[] =
                                                $student->quarterlyGrades->firstWhere('quarter.quarter', $q)
                                                    ->final_grade ?? null;
                                        }

                                        // Check if all 4 quarters exist
                                        $allQuartersHaveGrades =
                                            count(array_filter($quarterGrades, fn($g) => $g !== null)) === 4;

                                        $finalAverage = $allQuartersHaveGrades
                                            ? round(array_sum($quarterGrades) / 4)
                                            : '';

                                        $remarks = $allQuartersHaveGrades
                                            ? ($finalAverage >= 75
                                                ? 'PASSED'
                                                : 'FAILED')
                                            : '';
                                    @endphp

                                    @php
                                        $finalColor = '';
                                        if (is_numeric($finalAverage)) {
                                            if ($finalAverage >= 90) {
                                                $finalColor = 'text-success fw-bold';
                                            } elseif ($finalAverage >= 85) {
                                                $finalColor = 'text-success';
                                            } elseif ($finalAverage >= 80) {
                                                $finalColor = 'text-warning';
                                            } elseif ($finalAverage >= 75) {
                                                $finalColor = 'text-warning';
                                            } else {
                                                $finalColor = 'text-danger fw-semibold';
                                            }
                                        }
                                    @endphp
                                    <td class="text-center">
                                        <span class="final-grade {{ $finalColor }}">{{ $finalAverage }}</span>
                                    </td>

                                    </td>
                                    <td class="text-center"><span
                                            class="remarks fw-semibold text-uppercase">{{ $remarks }}</span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if ($canEdit)
                    <button type="submit" class="btn btn-primary mt-3">Save Grades</button>
                @endif
            </form>
        </div>

    </div>
    <!-- End Content wrapper -->

@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/tom-select/dist/js/tom-select.complete.min.js"></script>

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

    <script>
        // Live update final grades and remarks when inputs change
        document.addEventListener("DOMContentLoaded", function() {
            function updateFinalGrade(row) {
                const inputs = row.querySelectorAll(".quarter-input");
                const finalGradeEl = row.querySelector(".final-grade");
                const remarksEl = row.querySelector(".remarks");

                let total = 0,
                    count = 0;
                inputs.forEach(i => {
                    const val = parseFloat(i.value);
                    if (!isNaN(val)) {
                        total += val;
                        count++;
                    }
                });

                // Reset before recalculating
                finalGradeEl.className = "final-grade";
                remarksEl.className = "remarks fw-semibold text-uppercase";

                if (count === 4) {
                    const avg = Math.round(total / 4);
                    finalGradeEl.textContent = avg;

                    // Apply color coding logic
                    let colorClass = "",
                        remarks = "";
                    if (avg >= 90) {
                        colorClass = "text-success fw-bold"; // Outstanding
                        remarks = "PASSED";
                    } else if (avg >= 85) {
                        colorClass = "text-success"; // Very Satisfactory
                        remarks = "PASSED";
                    } else if (avg >= 80) {
                        colorClass = "text-warning"; // Satisfactory
                        remarks = "PASSED";
                    } else if (avg >= 75) {
                        colorClass = "text-warning"; // Fairly Satisfactory
                        remarks = "PASSED";
                    } else {
                        colorClass = "text-danger fw-semibold"; // Did Not Meet Expectations
                        remarks = "FAILED";
                    }

                    finalGradeEl.classList.add(...colorClass.split(" "));
                    remarksEl.textContent = remarks;
                    remarksEl.classList.add(
                        remarks === "PASSED" ? "text-success" : "text-danger"
                    );
                } else {
                    // Incomplete quarters
                    finalGradeEl.textContent = "";
                    remarksEl.textContent = "";
                }
            }

            // Apply to all inputs
            document.querySelectorAll(".quarter-input").forEach(input => {
                input.addEventListener("input", function() {
                    updateFinalGrade(input.closest("tr"));
                });
                updateFinalGrade(input.closest("tr")); // Initial run
            });

            // Fixed: select form correctly
            const gradeForm = document.querySelector(".save-grades-form");
            if (gradeForm) {
                gradeForm.addEventListener("submit", function(e) {
                    e.preventDefault();
                    const thisForm = this;
                    const saveBtn = thisForm.querySelector("button[type='submit']");

                    Swal.fire({
                        title: "Save Grades?",
                        text: "Are you sure you want to save these grades?",
                        icon: "question",
                        showCancelButton: true,
                        confirmButtonText: "Yes, save it!",
                        cancelButtonText: "Cancel",
                        customClass: {
                            container: "my-swal-container"
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Disable button + show spinner
                            saveBtn.disabled = true;
                            saveBtn.innerHTML =
                                `<span class="spinner-border spinner-border-sm me-2"></span> Saving...`;

                            let formData = new FormData(thisForm);

                            fetch(thisForm.action, {
                                    method: "POST",
                                    headers: {
                                        "X-CSRF-TOKEN": document.querySelector(
                                            "input[name='_token']").value,
                                        "Accept": "application/json"
                                    },
                                    body: formData
                                })
                                .then(response => {
                                    if (response.ok) {
                                        Swal.fire({
                                            toast: true,
                                            position: 'top-end',
                                            title: "Success!",
                                            text: "Grades have been saved.",
                                            icon: "success",
                                            showConfirmButton: false,
                                            timer: 3000,
                                            timerProgressBar: true,
                                            customClass: {
                                                container: "my-swal-container"
                                            }
                                        }).then(() => {
                                            window.location.reload();
                                        });
                                    } else {
                                        return response.json().then(data => {
                                            throw new Error(data.message ||
                                                "Failed to save grades.");
                                        });
                                    }
                                })
                                .catch(error => {
                                    Swal.fire({
                                        title: "Error!",
                                        text: error.message,
                                        icon: "error",
                                        customClass: {
                                            container: "my-swal-container"
                                        }
                                    });
                                })
                                .finally(() => {
                                    // Re-enable button if error
                                    saveBtn.disabled = false;
                                    saveBtn.innerHTML = "Save Grades";
                                });
                        }
                    });
                });
            }
        });
    </script>

    <!-- Validate grades on form submit -->
    <script>
        // Validate input grades before submitting (range 60–100)
        document.addEventListener("DOMContentLoaded", function() {
            const gradeForm = document.querySelector(".save-grades-form");
            if (!gradeForm) return;

            gradeForm.addEventListener("submit", function(e) {
                const inputs = gradeForm.querySelectorAll(".quarter-input:not([disabled])");
                let invalidInputs = [];

                inputs.forEach(input => {
                    const value = parseFloat(input.value);
                    if (isNaN(value)) return; // skip empty ones

                    if (value < 60 || value > 100) {
                        invalidInputs.push(input);
                        input.classList.add("is-invalid");
                    } else {
                        input.classList.remove("is-invalid");
                    }
                });

                if (invalidInputs.length > 0) {
                    e.preventDefault();

                    Swal.fire({
                        icon: "error",
                        title: "Invalid Grade(s) Detected!",
                        text: "Please ensure all grades are between 60 and 100 only.",
                        customClass: {
                            container: "my-swal-container"
                        }
                    });

                    invalidInputs[0].focus();
                }
            });
        });
    </script>

    <script>
        // Toggle enabling quarter inputs (per student validation)
        document.querySelectorAll(".toggle-edit").forEach(th => {
            th.addEventListener("click", function() {
                if (!this.dataset.quarter) return;

                const quarter = parseInt(this.dataset.quarter);
                const quarterInputs = document.querySelectorAll(`input.quarter-${quarter}`);
                const columnCells = document.querySelectorAll(
                    `th[data-quarter="${quarter}"], td:nth-child(${quarter + 3})`
                );

                let anyEnabled = Array.from(quarterInputs).some(input => !input.disabled);

                if (!anyEnabled) {
                    // Ask before enabling
                    Swal.fire({
                        title: `Enable Editing?`,
                        text: `Enable input for Quarter ${quarter}? Only students with complete previous quarters will be editable.`,
                        icon: "question",
                        showCancelButton: true,
                        confirmButtonText: "Yes, enable",
                        cancelButtonText: "Cancel",
                        customClass: {
                            container: "my-swal-container"
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            quarterInputs.forEach(input => {
                                const row = input.closest("tr");
                                const studentId = row.dataset.student;

                                // ✅ Check previous quarters for this student
                                let missingPrev = false;
                                if (quarter > 1) {
                                    for (let prev = 1; prev < quarter; prev++) {
                                        const prevInput = row.querySelector(
                                            `.quarter-${prev}`);
                                        const prevVal = prevInput ? prevInput.value.trim() :
                                            "";
                                        if (!prevVal) {
                                            missingPrev = true;
                                            break;
                                        }
                                    }
                                }

                                if (!missingPrev) {
                                    input.disabled = false;
                                    input.classList.remove("locked-quarter");
                                } else {
                                    input.disabled = true;
                                    input.classList.add("locked-quarter");
                                }
                            });

                            // Highlight the column
                            columnCells.forEach(cell => cell.classList.add("active-quarter"));
                            th.classList.add("text-dark");
                        }
                    });
                } else {
                    // Ask before disabling
                    Swal.fire({
                        title: `Disable Editing?`,
                        text: `Do you want to disable editing for Quarter ${quarter}?`,
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonText: "Yes, disable",
                        cancelButtonText: "Cancel",
                        customClass: {
                            container: "my-swal-container"
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            quarterInputs.forEach(input => input.disabled = true);
                            columnCells.forEach(cell => cell.classList.remove("active-quarter"));
                            th.classList.remove("text-dark");
                            th.classList.add("text-muted");
                        }
                    });
                }
            });
        });
    </script>
@endpush

@push('styles')
    <!-- Main CSS File -->
    <link href="{{ asset('assets/css/main.css') }}" rel="stylesheet" />
    <link href="{{ asset('assetsDashboard/vendor/css/core.css') }}" rel="stylesheet" />
    <link href="{{ asset('assetsDashboard/vendor/css/theme-default.css') }}" rel="stylesheet" />

    <!-- Vendor CSS Files -->
    <link href="{{ asset('assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet" />

    <link href="https://cdn.jsdelivr.net/npm/tom-select/dist/css/tom-select.css" rel="stylesheet">

    <style>
        .student-photo {
            width: 45px;
            height: 45px;
            object-fit: cover;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .student-photo:hover {
            transform: scale(1.1);
            box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.2);
        }

        .active-quarter {
            background-color: #e0fdd0 !important;
            /* Bootstrap bg-warning */
        }

        .is-invalid {
            border-color: #dc3545 !important;
            background-color: #f8d7da !important;
        }

        .locked-quarter {
            background-color: #f2f2f2 !important;
            cursor: not-allowed;
            opacity: 0.6;
        }
    </style>
@endpush
