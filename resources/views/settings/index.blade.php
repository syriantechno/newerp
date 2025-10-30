@extends('layouts.user_type.auth')

@section('content')
    <div class="container-fluid py-4">

        <div class="row">
            {{-- ========== Settings Sidebar ========== --}}
            <div class="col-md-3">
                <div class="card shadow-sm border-radius-xl">
                    <div class="card-body p-3">
                        <h6 class="text-uppercase text-xs text-secondary mb-3">Settings</h6>
                        <ul class="nav flex-column" id="settings-menu">
                            <li class="nav-item mb-2">
                                <a href="#" class="nav-link active" data-section="general">
                                    <i class="ni ni-settings text-primary me-2"></i> General
                                </a>
                            </li>
                            <li class="nav-item mb-2">
                                <a href="#" class="nav-link" data-section="modules">
                                    <i class="ni ni-app text-warning me-2"></i> Modules Manager
                                </a>
                            </li>
                            <li class="nav-item mb-2">
                                <a href="#" class="nav-link" data-section="messages">
                                    <i class="ni ni-email-83 text-primary me-2"></i> Messages
                                </a>
                            </li>

                            <li class="nav-item mb-2">
                                <a href="#" class="nav-link" data-section="notifications">
                                    <i class="ni ni-bell-55 text-success me-2"></i> Notifications
                                </a>
                            </li>
                            <li class="nav-item mb-2">
                                <a href="#" class="nav-link" data-section="users">
                                    <i class="ni ni-single-02 text-info me-2"></i> Users & Roles
                                </a>
                            </li>
                            <li class="nav-item mb-2">
                                <a href="#" class="nav-link" data-section="appearance">
                                    <i class="ni ni-palette text-danger me-2"></i> Appearance
                                </a>
                            </li>
                            <li class="nav-item mb-2">
                                <a href="#" class="nav-link" data-section="system">
                                    <i class="ni ni-laptop text-dark me-2"></i> System Info
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            {{-- ========== Settings Content Area ========== --}}
            <div class="col-md-9">
                <div class="card shadow-sm border-radius-xl" id="settings-content">
                    <div class="card-body p-4" id="settings-section-general">
                        <h5 class="mb-3"><i class="ni ni-settings text-primary me-2"></i> General Settings</h5>
                        <p class="text-sm text-muted">Here you can configure general system settings such as name, logo, language, and timezone.</p>
                        <hr>
                        <div class="mb-3">
                            <label class="form-label">System Name</label>
                            <input type="text" class="form-control" placeholder="ERP System">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Timezone</label>
                            <select class="form-select">
                                <option>UTC</option>
                                <option selected>Asia/Dubai</option>
                                <option>Europe/London</option>
                            </select>
                        </div>
                    </div>

                    {{-- placeholder sections --}}
                    <div class="card-body p-4 d-none" id="settings-section-modules">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="mb-0"><i class="ni ni-app text-warning me-2"></i> Modules Manager</h5>
                            <form method="POST" action="{{ route('modules.sync') }}">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-outline-primary">
                                    🔄 Sync Modules
                                </button>
                            </form>
                        </div>
                        <p class="text-sm text-muted">Synchronize and manage module configurations from <code>module.json</code> files.</p>

                        @if(session('success'))
                            <div class="alert alert-success mt-3">{{ session('success') }}</div>
                        @endif
                        @if(session('error'))
                            <div class="alert alert-danger mt-3">{{ session('error') }}</div>
                        @endif

                        <hr>

                        <table class="table table-hover align-middle" id="modules-table">
                            <thead>
                            <tr>
                                <th>Name</th>
                                <th>Label</th>
                                <th>Icon</th>
                                <th>Status</th>
                                <th>Order</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php
                                use App\Models\ModuleSetting;
                                $modules = ModuleSetting::orderBy('order')->get();
                            @endphp
                            @foreach($modules as $mod)
                                <tr data-id="{{ $mod->id }}">
                                    <td>{{ $mod->name }}</td>
                                    <td>{{ $mod->label }}</td>
                                    <td><i class="{{ $mod->icon }}"></i></td>
                                    <td>
                       <span class="badge {{ $mod->active ? 'bg-success' : 'bg-secondary' }}">
                        {{ $mod->active ? 'Active' : 'Disabled' }}
                       </span>
                                    </td>
                                    <td>{{ $mod->order }}</td>
                                    <td>
                                        <button class="btn btn-sm {{ $mod->active ? 'btn-outline-danger' : 'btn-outline-success' }} toggle-btn">
                                            {{ $mod->active ? 'Disable' : 'Enable' }}
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>

                    </div>
                    <div class="card-body p-4 d-none" id="settings-section-messages">

                    <div class="card shadow-sm border-0">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h6 class="mb-0">🗂 Message Templates</h6>
                                <button type="submit" form="messagesForm" class="btn btn-primary btn-sm">💾 Save</button>
                            </div>
                            <div class="card-body">
                                <form id="messagesForm" method="POST" action="{{ route('settings.messages.save') }}">
                                    @csrf
                                    <table class="table table-sm align-middle">
                                        <thead>
                                        <tr>
                                            <th style="width: 30%">Key</th>
                                            <th>Message Text</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($messages as $key => $value)
                                            <tr>
                                                <td><input type="text" name="messages[{{ $key }}][key]" value="{{ $key }}" readonly class="form-control form-control-sm"></td>
                                                <td><input type="text" name="messages[{{ $key }}]" value="{{ $value }}" class="form-control form-control-sm"></td>
                                            </tr>
                                        @endforeach
                                        <tr class="table-light">
                                            <td colspan="2" class="text-center text-muted small">+ Add New</td>
                                        </tr>
                                        <tr>
                                            <td><input type="text" name="messages[new.key]" placeholder="new.key" class="form-control form-control-sm"></td>
                                            <td><input type="text" name="messages[new.value]" placeholder="Message content..." class="form-control form-control-sm"></td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-4 d-none" id="settings-section-notifications">
                        <h5><i class="ni ni-bell-55 text-success me-2"></i> Notifications</h5>
                        <p class="text-sm text-muted">Configure audio alerts, email preferences, and notification behavior.</p>
                    </div>
                    <div class="card-body p-4 d-none" id="settings-section-users">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="mb-0"><i class="ni ni-single-02 text-info me-2"></i> Users & Roles</h5>
                            <a href="{{ route('settings.roles.index') }}" class="btn btn-outline-primary btn-sm">
                                ⚙️ Manage Roles
                            </a>
                        </div>

                        <p class="text-sm text-muted mb-3">
                            View all system users and assign roles directly.
                        </p>

                        @php
                            use App\Models\User;
                            use App\Models\Role;
                            $users = User::with('roles')->orderBy('id')->get();
                            $roles = Role::orderBy('name')->get();
                        @endphp

                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="bg-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Current Role</th>
                                    <th class="text-end">Assign Role</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($users as $u)
                                    <tr>
                                        <td>{{ $u->id }}</td>
                                        <td>{{ $u->name }}</td>
                                        <td>{{ $u->email }}</td>
                                        <td>
                                            @if($u->roles->isEmpty())
                                                <span class="badge bg-secondary">None</span>
                                            @else
                                                @foreach($u->roles as $r)
                                                    <span class="badge bg-info text-dark">{{ $r->name }}</span>
                                                @endforeach
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            <form method="POST" action="{{ route('settings.users.assignRole') }}" class="d-flex justify-content-end gap-2">
                                                @csrf
                                                <input type="hidden" name="user_id" value="{{ $u->id }}">
                                                <select name="role_id" class="form-select form-select-sm w-auto">
                                                    @foreach($roles as $r)
                                                        <option value="{{ $r->id }}" {{ $u->roles->contains('id', $r->id) ? 'selected' : '' }}>
                                                            {{ $r->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <button type="submit" class="btn btn-sm btn-success">Assign</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>

                        <hr>
                        <div class="text-end">
                            <a href="{{ route('settings.permissions.matrix') }}" class="btn btn-outline-success btn-sm">
                                🔐 Permissions Matrix
                            </a>
                        </div>
                    </div>
                    <div class="card-body p-4 d-none" id="settings-section-appearance">
                        <h5><i class="ni ni-palette text-danger me-2"></i> Appearance</h5>
                        <p class="text-sm text-muted">Theme and layout customization options.</p>
                    </div>
                    <div class="card-body p-4 d-none" id="settings-section-system">
                        <h5><i class="ni ni-laptop text-dark me-2"></i> System Info</h5>
                        <p class="text-sm text-muted">System version, PHP info, and diagnostics.</p>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- ========= Simple Section Switch Script ========= --}}
    <script>
        document.querySelectorAll('#settings-menu a').forEach(link => {
            link.addEventListener('click', e => {
                e.preventDefault();
                document.querySelectorAll('#settings-menu a').forEach(l => l.classList.remove('active'));
                link.classList.add('active');

                const section = link.dataset.section;
                document.querySelectorAll('[id^="settings-section-"]').forEach(div => div.classList.add('d-none'));
                document.getElementById(`settings-section-${section}`).classList.remove('d-none');
            });
        });
    </script>
    <script>
        document.querySelectorAll('.toggle-btn').forEach(btn => {
            btn.addEventListener('click', async e => {
                const tr = e.target.closest('tr');
                const id = tr.dataset.id;

                const res = await fetch(`/settings/modules/toggle/${id}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                });

                const data = await res.json();
                if (data.success) {
                    const badge = tr.querySelector('.badge');
                    const button = tr.querySelector('.toggle-btn');

                    if (data.active) {
                        badge.className = 'badge bg-success';
                        badge.textContent = 'Active';
                        button.className = 'btn btn-sm btn-outline-danger toggle-btn';
                        button.textContent = 'Disable';
                    } else {
                        badge.className = 'badge bg-secondary';
                        badge.textContent = 'Disabled';
                        button.className = 'btn btn-sm btn-outline-success toggle-btn';
                        button.textContent = 'Enable';
                    }
                }
            });
        });
    </script>

@endsection
