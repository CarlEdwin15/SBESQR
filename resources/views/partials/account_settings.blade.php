<div class="card">
    <div class="card-header">
        <h3>Account Settings</h3>
    </div>
    <div class="card-body">
        {{-- Validation Errors --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route($updateRoute, $user->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            {{-- Basic Info --}}
            <div class="mb-3">
                <label for="firstName" class="form-label">First Name</label>
                <input type="text" name="firstName" id="firstName" class="form-control"
                    value="{{ old('firstName', $user->firstName) }}" required>
            </div>

            <div class="mb-3">
                <label for="middleName" class="form-label">Middle Name</label>
                <input type="text" name="middleName" id="middleName" class="form-control"
                    value="{{ old('middleName', $user->middleName) }}">
            </div>

            <div class="mb-3">
                <label for="lastName" class="form-label">Last Name</label>
                <input type="text" name="lastName" id="lastName" class="form-control"
                    value="{{ old('lastName', $user->lastName) }}">
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" id="email" class="form-control" value="{{ old('email', $user->email) }}"
                    readonly>

                {{-- Hidden input to actually submit the email --}}
                <input type="hidden" name="email" value="{{ old('email', $user->email) }}">
            </div>

            <div class="mb-3">
                <label for="profile_photo" class="form-label">Profile Photo</label>
                <input type="file" name="profile_photo" id="profile_photo" class="form-control">
                @if ($user->profile_photo)
                    <img src="{{ asset('storage/' . $user->profile_photo) }}" alt="Profile Photo" class="mt-2 rounded"
                        style="height: 100px;">
                @endif
            </div>

            {{-- Show Password Fields Only for Admin and Teacher --}}
            @if (auth()->user()->role === 'admin' || auth()->user()->role === 'teacher')
                <hr>
                <div class="mb-3">
                    <label for="current_password" class="form-label">Current Password</label>
                    <input type="password" name="current_password" id="current_password" class="form-control">
                </div>

                <div class="mb-3">
                    <label for="new_password" class="form-label">New Password</label>
                    <input type="password" name="new_password" id="new_password" class="form-control">
                </div>

                <div class="mb-3">
                    <label for="confirm_password" class="form-label">Confirm New Password</label>
                    <input type="password" name="confirm_password" id="confirm_password" class="form-control">
                </div>
            @endif

            <button type="submit" class="btn btn-primary">Update Account</button>
        </form>
    </div>
</div>
