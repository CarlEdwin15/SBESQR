@extends('./layouts.main')

@section('title', 'Admin | Account Settings')

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
                    <i class="menu-icon tf-icons bx bx-user-pin"></i>
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
            <li class="menu-item">
                <a href="javascript:void(0);" class="menu-link menu-toggle bg-dark text-light">
                    <i class="menu-icon tf-icons bx bxs-graduation text-light"></i>
                    <div class="text-light">Students</div>
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
                    <li class="menu-item">
                        <a href="{{ route('students.promote.view') }}" class="menu-link bg-dark text-light">
                            <div class="text-light">Student Promotion</div>
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

            {{-- Account Settings sidebar --}}
            <li class="menu-item active">
                <a href="{{ route('account.settings') }}" class="menu-link">
                    <i class="menu-icon bx bx-cog"></i>
                    <div>Account Settings</div>
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
            </span> Account Settings
        </h4>

        <hr class="my-4" />

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="d-flex justify-content-start mb-3 gap-2">
            <button type="button" class="btn btn-danger d-flex align-items-center gap-1" onclick="handleCancel()">
                <i class="bx bx-arrow-back"></i>
                <span class="d-sm-block">Back</span>
                {{-- {{ url()->previous() }} --}}
            </button>
            <button type="submit" class="btn btn-primary d-flex align-items-center gap-1" id="saveChangesBtn">
                <i class="bx bx-save"></i>
                <span class="d-sm-block">Save</span>
            </button>
        </div>

        <!-- User's Details -->
        <form action="{{ route('update.admin', ['id' => auth()->user()->id]) }}" method="POST"
            enctype="multipart/form-data">
            @csrf

            <div class="mb-3">
                <label for="profile_photo" class="form-label">Profile Photo</label><br>
                <img src="{{ asset('storage/' . auth()->user()->profile_photo) }}" alt="Profile Photo" width="100">
                <input type="file" name="profile_photo" class="form-control mt-2" accept="image/*">
            </div>

            <div class="mb-3">
                <label for="firstName" class="form-label">First Name</label>
                <input type="text" name="firstName" class="form-control" value="{{ auth()->user()->firstName }}"
                    required>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" name="email" class="form-control" value="{{ auth()->user()->email }}" required>
            </div>

            <hr>

            <div class="mb-3">
                <label for="current_password" class="form-label">Current Password</label>
                <input type="password" name="current_password" class="form-control">
            </div>

            <div class="mb-3">
                <label for="new_password" class="form-label">New Password</label>
                <input type="password" name="new_password" class="form-control">
            </div>

            <div class="mb-3">
                <label for="confirm_password" class="form-label">Confirm Password</label>
                <input type="password" name="confirm_password" class="form-control">
            </div>

            <button type="submit" class="btn btn-primary">Update Settings</button>
        </form>
        <!-- /User's Details -->

    </div>
    <!-- / Content wrapper -->

    <hr class="my-5" />

@endsection

@push('scripts')
    <script>
        //cancel button alert in registration
        let formChanged = false;
        const form = document.getElementById('editTeacherForm');

        // Detect form changes
        form.addEventListener('input', () => {
            formChanged = true;
        });

        // Cancel button logic
        function handleCancel() {
            if (!formChanged) {
                // If form is untouched, just redirect
                window.location.href = "{{ route('show.teachers') }}";
            } else {
                // If form has been changed, ask for confirmation
                Swal.fire({
                    title: "Cancel editing and discard any changes?",
                    showDenyButton: true,
                    showCancelButton: true,
                    showConfirmButton: false,
                    confirmButtonText: "Save",
                    denyButtonText: `Don't save`,
                    cancelButtonText: "Cancel",
                    customClass: {
                        container: 'my-swal-container'
                    }
                }).then((result) => {
                    if (result.isDenied) {
                        form.reset();
                        window.location.href = "{{ route('show.teachers') }}";
                    }
                    // If cancelled, do nothing
                });
            }
        }

        //save button
        document.getElementById('saveChangesBtn').addEventListener('click', function() {
            Swal.fire({
                title: "Save changes?",
                text: "Are you sure you want to update this teacher's details?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#28a745",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, save it",
                cancelButtonText: "Cancel",
                customClass: {
                    container: 'my-swal-container'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: "Saving...",
                        text: "Please wait while we update the details.",
                        icon: "info",
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        timer: 1000,
                        customClass: {
                            container: 'my-swal-container'
                        },
                        willClose: () => {
                            document.getElementById('editTeacherForm')
                                .submit(); // Submit the form after short delay
                        }
                    });
                }
            });
        });
    </script>

    <script>
        //logout button
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
        // image profile upload/preview
        document.addEventListener('DOMContentLoaded', function() {
            const fileInput = document.getElementById('upload');
            const resetBtn = document.querySelector('.account-image-reset');
            const imagePreview = document.querySelector('.profile-preview');

            if (!fileInput || !resetBtn || !imagePreview) {
                console.warn('Required elements not found.');
                return;
            }

            const originalImageSrc = imagePreview.src;

            fileInput.addEventListener('change', function(event) {
                const file = event.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        imagePreview.src = e.target.result;
                    };
                    reader.readAsDataURL(file);
                }
            });

            resetBtn.addEventListener('click', function() {
                fileInput.value = ''; // Clear the file input
                imagePreview.src = originalImageSrc; // Reset image
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const assignedSelect = document.getElementById('assigned_classes');
            const advisorySelect = document.getElementById('advisory_class');
            const allOptions = Array.from(assignedSelect.options);

            function updateAdvisoryOptions() {
                const selectedAssigned = Array.from(assignedSelect.selectedOptions).map(opt => opt.value);
                const selectedAdvisory = advisorySelect.value;

                // Clear current advisory options
                advisorySelect.innerHTML = '<option value=""> No advisory class </option>';

                allOptions.forEach(option => {
                    if (selectedAssigned.includes(option.value)) {
                        const newOption = document.createElement('option');
                        newOption.value = option.value;
                        newOption.textContent = option.textContent;

                        if (option.value === selectedAdvisory) {
                            newOption.selected = true;
                        }

                        advisorySelect.appendChild(newOption);
                    }
                });
            }

            // Initialize on page load
            updateAdvisoryOptions();

            // Update on change
            assignedSelect.addEventListener('change', updateAdvisoryOptions);
        });
    </script>
@endpush
