{{-- Recipients --}}
<div class="mb-3 position-relative">
    <label for="recipients" class="form-label fw-bold d-flex justify-content-between align-items-center">
        <span>Select Recipients</span>
        <button type="button" id="openUserModal" class="btn btn-sm btn-outline-primary">
            <i class="bi bi-people"></i> Choose from Users List
        </button>
    </label>

    <select id="recipients" name="recipients[]" multiple required></select>

    <small class="form-text text-muted">
        You can search by name/email or click “Choose from list” to select multiple users.
    </small>
</div>

<!-- User Selection Modal -->
<div class="modal fade" id="userSelectModal" tabindex="-1" aria-labelledby="userSelectModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="userSelectModalLabel">Select Recipients</h5>
            </div>

            <div class="modal-body">

                <div class="col ms-auto mb-2">
                    <input type="text" id="userSearch" class="form-control form-control"
                        placeholder="Search name/email...">
                </div>

                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div class="col-auto">
                        <select id="tableLength" class="form-select w-auto">
                            <option value="5">5</option>
                            <option value="10" selected>10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                        </select>
                    </div>
                    <div class="col-auto">
                        <select id="roleFilter" class="form-select">
                            <option value="">All Roles</option>
                            <option value="admin">Admin</option>
                            <option value="teacher">Teacher</option>
                            <option value="parent">Parent</option>
                        </select>
                    </div>
                </div>

                <hr class="my-4" />

                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div>
                        <input type="checkbox" id="selectAllUsers" class="form-check-input me-2">
                        <label for="selectAllUsers" class="form-check-label">Select All</label>
                    </div>
                </div>

                <div id="userListContainer" class="border rounded p-2" style="max-height: 400px; overflow-y: auto;">
                    <p class="text-muted text-center">Loading users...</p>
                </div>

                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div id="tableInfo" class="text-muted small"></div>
                    <nav>
                        <ul class="pagination pagination mb-0" id="pagination"></ul>
                    </nav>
                </div>

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" id="addSelectedUsers" class="btn btn-primary">Add Selected</button>
            </div>
        </div>
    </div>
</div>

<div class="mb-3">
    <label for="title">Subject</label>
    <input type="text" name="title" value="{{ old('title', $announcement->title ?? '') }}" class="form-control"
        required>
    @error('title')
        <div class="text-danger">{{ $message }}</div>
    @enderror
</div>

{{-- Body --}}
<div class="mb-3">
    <label for="body">Body</label>

    <!-- Quill Visible Editor -->
    <div id="quill-editor" style="height: 200px;">{!! old('body', $announcement->body ?? '') !!}</div>

    <!-- Hidden input to store HTML content -->
    <input type="hidden" name="body" id="body">

    <!-- Client-side error display -->
    <div class="text-danger" id="body-error" style="display: none;"></div>

    <!-- Server-side error display -->
    @error('body')
        <div class="text-danger">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3 row">
    <div class="col">
        <label for="school_year_id">School Year</label>
        <select name="school_year_id" class="form-select">
            <option value="">None</option>
            @foreach ($schoolYears->filter(function ($year) {
        $now = now();
        $next = now()->copy()->addYear();
        return in_array($year->school_year, [$now->year . '-' . ($now->year + 1), $next->year . '-' . ($next->year + 1)]);
    }) as $year)
                <option value="{{ $year->id }}"
                    @if (isset($announcement)) {{ old('school_year_id', $announcement->school_year_id) == $year->id ? 'selected' : '' }}
                @elseif (isset($defaultSchoolYear))
                    {{ old('school_year_id', $defaultSchoolYear->id) == $year->id ? 'selected' : '' }} @endif>
                    {{ $year->school_year }}
                </option>
            @endforeach
        </select>
        @error('school_year_id')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="mb-3 row">
    {{-- Effective Form --}}
    <div class="col">
        <label for="effective_date">Effective From</label>
        <input type="date" name="effective_date"
            value="{{ old('effective_date', isset($announcement->effective_date) ? \Carbon\Carbon::parse($announcement->effective_date)->format('Y-m-d') : '') }}"
            class="form-control" min="{{ now()->format('Y-m-d') }}" required>
        @error('effective_date')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    {{-- End Date --}}
    <div class="col">
        <label for="end_date">End Date</label>
        <input type="date" name="end_date"
            value="{{ old('end_date', isset($announcement->end_date) ? \Carbon\Carbon::parse($announcement->end_date)->format('Y-m-d') : '') }}"
            class="form-control" min="{{ now()->format('Y-m-d') }}" required>
        @error('end_date')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
</div>


@push('scripts')
    <!-- Quill JS -->
    <script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>

    <!-- Quill Initialization -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const Font = Quill.import('formats/font');
            Font.whitelist = ['sans-serif', 'serif', 'monospace'];
            Quill.register(Font, true);

            const quill = new Quill('#quill-editor', {
                theme: 'snow',
                placeholder: 'Write your announcement here...',
                modules: {
                    toolbar: {
                        container: [
                            [{
                                'font': Font.whitelist
                            }],
                            [{
                                'size': ['small', false, 'large', 'huge']
                            }],
                            ['bold', 'italic', 'underline'],
                            [{
                                'color': []
                            }, {
                                'background': []
                            }],
                            [{
                                'align': []
                            }],
                            [{
                                'list': 'ordered'
                            }, {
                                'list': 'bullet'
                            }],
                            ['link', 'image'], // ✅ keep image button
                            ['clean']
                        ],
                        handlers: {
                            image: function() {
                                const input = document.createElement('input');
                                input.setAttribute('type', 'file');
                                input.setAttribute('accept', 'image/*');
                                input.click();

                                input.onchange = () => {
                                    const file = input.files[0];
                                    if (file) {
                                        const formData = new FormData();
                                        formData.append('image', file);

                                        fetch("{{ route('announcements.uploadImage') }}", {
                                                method: 'POST',
                                                headers: {
                                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                                },
                                                body: formData
                                            })
                                            .then(res => {
                                                if (!res.ok) throw res;
                                                return res.json();
                                            })
                                            .then(data => {
                                                if (data.url) {
                                                    const range = quill.getSelection();
                                                    quill.insertEmbed(range.index, 'image', data
                                                        .url);
                                                } else {
                                                    alert('Image upload failed');
                                                }
                                            })
                                            .catch(async (err) => {
                                                let msg = 'Image upload failed';
                                                if (err.json) {
                                                    const errorData = await err.json();
                                                    if (errorData.errors && errorData.errors
                                                        .image) {
                                                        msg = errorData.errors.image.join(
                                                            ', ');
                                                    }
                                                }
                                                alert(msg);
                                            });
                                    }
                                };
                            }
                        }
                    }
                },
                formats: [
                    'font', 'size', 'bold', 'italic', 'underline',
                    'list', 'color', 'background',
                    'align', 'link', 'image'
                ]
            });

            const form = document.getElementById('createAnnouncementForm');
            const bodyInput = document.getElementById('body');
            const errorDiv = document.getElementById('body-error');

            form.addEventListener('submit', function(e) {
                const htmlContent = quill.root.innerHTML.trim();
                bodyInput.value = htmlContent;

                if (quill.getLength() <= 1 || htmlContent === '<p><br></p>') {
                    e.preventDefault();
                    errorDiv.textContent = 'The Body field is required.';
                    errorDiv.style.display = 'block';
                    return;
                }

                errorDiv.style.display = 'none';
            });
        });
    </script>

    <!-- User Selection Modal Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const recipientsSelect = new TomSelect('#recipients', {
                plugins: ['remove_button'],
                maxItems: null,
                placeholder: "Search users by name or email...",
                valueField: 'id',
                labelField: 'text',
                searchField: 'text',
                load: function(query, callback) {
                    if (!query.length) return callback();
                    fetch(`{{ route('search.user') }}?q=${encodeURIComponent(query)}`)
                        .then(res => res.json())
                        .then(users => callback(users.map(u => ({
                            id: u.id,
                            text: `${u.firstName} ${u.lastName} (${u.email})`
                        }))))
                        .catch(() => callback());
                }
            });

            const modal = new bootstrap.Modal(document.getElementById('userSelectModal'));
            const userListContainer = document.getElementById('userListContainer');
            const roleFilter = document.getElementById('roleFilter');
            const openModalBtn = document.getElementById('openUserModal');

            const addSelectedBtn = document.getElementById('addSelectedUsers');
            // Function to update the "Add Selected" button label dynamically
            function updateAddSelectedButtonLabel() {
                const count = persistentSelectedUsers.size;
                if (count > 0) {
                    addSelectedBtn.textContent = `Add ${count} Selected`;
                } else {
                    addSelectedBtn.textContent = 'Add Selected';
                }
            }

            const tableLength = document.getElementById('tableLength');
            const userSearch = document.getElementById('userSearch');
            const selectAllUsers = document.getElementById('selectAllUsers');
            const selectAllLabel = document.querySelector('label[for="selectAllUsers"]');
            const tableInfo = document.getElementById('tableInfo');
            const pagination = document.getElementById('pagination');

            let allUsers = [];
            let filteredUsers = [];
            let currentPage = 1;
            let itemsPerPage = tableLength ? parseInt(tableLength.value) : 10;
            let persistentSelectedUsers = new Map();

            openModalBtn.addEventListener('click', function() {
                modal.show();
                loadUsers();
                updateSelectAllLabel(); // set initial label
                updateAddSelectedButtonLabel(); // initialize button label correctly
            });

            // clear the label when modal closes
            document.getElementById('userSelectModal').addEventListener('hidden.bs.modal', function() {
                updateAddSelectedButtonLabel();
            });

            roleFilter.addEventListener('change', () => {
                loadUsers();
                updateSelectAllLabel(); // update label dynamically
            });

            if (tableLength) {
                tableLength.addEventListener('change', () => {
                    itemsPerPage = parseInt(tableLength.value);
                    currentPage = 1;
                    renderUsers();
                });
            }

            if (userSearch) {
                userSearch.addEventListener('input', () => {
                    currentPage = 1;
                    filterUsers();
                });
            }

            if (selectAllUsers) {
                selectAllUsers.addEventListener('change', function() {
                    const checkboxes = document.querySelectorAll('.user-checkbox');
                    checkboxes.forEach(cb => {
                        cb.checked = this.checked;
                        cb.dispatchEvent(new Event('change'));
                    });
                    updateAddSelectedButtonLabel(); // ensure label updates after select all
                });
            }

            addSelectedBtn.addEventListener('click', function() {
                persistentSelectedUsers.forEach(u => {
                    if (!recipientsSelect.options[u.id]) {
                        recipientsSelect.addOption({
                            id: u.id,
                            text: u.text
                        });
                    }
                    recipientsSelect.addItem(u.id);
                });
                modal.hide();
            });

            function loadUsers() {
                const role = roleFilter.value || '';
                userListContainer.innerHTML = '<p class="text-muted text-center">Loading...</p>';

                fetch(`{{ route('search.user') }}?role=${encodeURIComponent(role)}`)
                    .then(res => res.json())
                    .then(users => {
                        allUsers = users;
                        filteredUsers = [...allUsers];
                        currentPage = 1;
                        renderUsers();
                    })
                    .catch(() => {
                        userListContainer.innerHTML =
                            '<p class="text-danger text-center">Failed to load users.</p>';
                    });
            }

            function filterUsers() {
                const query = userSearch.value.toLowerCase();
                filteredUsers = allUsers.filter(u =>
                    `${u.firstName} ${u.lastName} ${u.email}`.toLowerCase().includes(query)
                );
                renderUsers();
            }

            function renderUsers() {
                const start = (currentPage - 1) * itemsPerPage;
                const end = start + itemsPerPage;
                const paginated = filteredUsers.slice(start, end);

                if (paginated.length === 0) {
                    userListContainer.innerHTML = '<p class="text-muted text-center">No users found.</p>';
                    if (tableInfo) tableInfo.textContent = '';
                    if (pagination) pagination.innerHTML = '';
                    return;
                }

                userListContainer.innerHTML = paginated.map(u => {
                    const userText = `${u.firstName} ${u.lastName} (${u.email})`;
                    const isChecked = persistentSelectedUsers.has(u.id.toString()) ||
                        recipientsSelect.items.includes(u.id.toString()) ? 'checked' : '';
                    return `
                    <div class="form-check border-bottom py-2">
                        <input class="form-check-input user-checkbox" type="checkbox" value="${u.id}" id="user-${u.id}"
                            data-text="${userText}" ${isChecked}>
                        <label class="form-check-label" for="user-${u.id}">
                            <strong>${u.firstName} ${u.lastName}</strong>
                            <small>(${u.email})</small>
                        </label>
                    </div>
                `;
                }).join('');

                userListContainer.querySelectorAll('.user-checkbox').forEach(cb => {
                    cb.addEventListener('change', function() {
                        const id = this.value.toString();
                        const text = this.dataset.text;
                        if (this.checked) {
                            persistentSelectedUsers.set(id, {
                                id,
                                text
                            });
                        } else {
                            persistentSelectedUsers.delete(id);
                            recipientsSelect.removeItem(id);
                        }
                        updateAddSelectedButtonLabel();
                    });
                });

                const total = filteredUsers.length;
                if (tableInfo) {
                    tableInfo.textContent = `Showing ${start + 1} to ${Math.min(end, total)} of ${total} users`;
                }
                renderPagination(total);
            }

            function renderPagination(totalItems) {
                if (!pagination) return;
                const totalPages = Math.ceil(totalItems / itemsPerPage);
                pagination.innerHTML = '';

                if (totalPages <= 1) return;

                for (let i = 1; i <= totalPages; i++) {
                    const li = document.createElement('li');
                    li.className = `page-item ${i === currentPage ? 'active' : ''}`;
                    li.innerHTML = `<button class="page-link">${i}</button>`;
                    li.addEventListener('click', () => {
                        currentPage = i;
                        renderUsers();
                    });
                    pagination.appendChild(li);
                }
            }

            // Dynamic label updater
            function updateSelectAllLabel() {
                const role = roleFilter.value;
                let labelText = 'Select All Users';
                if (role) {
                    const capitalized = role.charAt(0).toUpperCase() + role.slice(1);
                    labelText = `Select All ${capitalized}${role.endsWith('s') ? '' : 's'}`;
                }
                selectAllLabel.textContent = labelText;
            }
        });
    </script>
@endpush


@push('styles')
    <!-- Tom Select CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tom-select/dist/css/tom-select.bootstrap5.min.css" rel="stylesheet">

    <!-- Tom Select JS -->
    <script src="https://cdn.jsdelivr.net/npm/tom-select/dist/js/tom-select.complete.min.js"></script>

    <!-- Quill CSS -->
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <style>
        .ts-control {
            min-height: 42px;
            border-radius: 0.375rem;
            border-color: #ced4da;
            font-size: 0.95rem;
        }

        .ts-control input {
            padding: 4px !important;
        }

        .ts-dropdown .option {
            padding: 6px 10px;
        }

        #userListContainer .form-check:hover {
            background-color: #f8f9fa;
        }

        #pagination .page-link {
            cursor: pointer;
        }
    </style>
@endpush
