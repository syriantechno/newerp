@extends('layouts.user_type.auth')

@section('content')
    <div class="container-fluid py-4">

        {{-- ===== Page Header ===== --}}
        <div class="row mb-3">
            <div class="col-12 d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="ni ni-lock-circle-open text-primary me-2"></i>
                    Permissions Matrix
                </h5>
                <a href="{{ route('settings.index') }}" class="btn btn-outline-secondary btn-sm">
                    ← Back to Settings
                </a>
            </div>
        </div>

        {{-- ===== Roles & Permissions ===== --}}
        <div class="row">
            {{-- ==== Left Side (Roles) ==== --}}
            <div class="col-md-3 mb-3">
                <div class="card shadow-sm border-radius-xl h-100">
                    <div class="card-body p-3">
                        <h6 class="text-secondary text-uppercase text-xs mb-3">Roles</h6>
                        <ul class="list-group">
                            @foreach($roles as $role)
                                <li class="list-group-item d-flex justify-content-between align-items-center
                                {{ isset($activeRole) && $activeRole && $activeRole->id === $role->id ? 'bg-primary text-white' : '' }}">
                                    <a href="{{ route('settings.permissions.matrix', $role->id) }}"
                                       class="{{ isset($activeRole) && $activeRole && $activeRole->id === $role->id ? 'text-white fw-bold' : 'text-dark' }}">
                                        {{ $role->name }}
                                    </a>
                                    @if(isset($activeRole) && $activeRole && $activeRole->id === $role->id)
                                        <i class="ni ni-check-bold text-white"></i>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>

            {{-- ==== Right Side (Permissions Table) ==== --}}
            <div class="col-md-9">
                @if(!$activeRole)
                    <div class="alert alert-info">Select a role from the left panel to edit its permissions.</div>
                @else
                    <form method="POST" action="{{ route('settings.permissions.sync', $activeRole) }}">
                        @csrf
                        <div class="card shadow-sm border-radius-xl">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h6 class="mb-0">
                                    <i class="ni ni-key-25 text-warning me-2"></i>
                                    Editing: <strong>{{ $activeRole->name }}</strong>
                                </h6>
                                <button class="btn btn-primary btn-sm">💾 Save Changes</button>
                            </div>

                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle mb-0">
                                        <thead class="bg-light">
                                        <tr>
                                            <th class="px-4">Module</th>
                                            <th class="text-center">View</th>
                                            <th class="text-center">Add</th>
                                            <th class="text-center">Edit</th>
                                            <th class="text-center">Delete</th>
                                            <th class="text-center">All (*)</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($permissions as $module => $list)
                                            @php
                                                $byAction = $list->keyBy('action');
                                                $checked = fn($a) => $activeRole->permissions->contains('id', $byAction[$a]->id ?? 0);
                                            @endphp
                                            <tr>
                                                <td class="px-4 fw-semibold text-dark">{{ ucfirst($module) }}</td>
                                                @foreach(['view','add','edit','delete','*'] as $act)
                                                    <td class="text-center">
                                                        @if(isset($byAction[$act]))
                                                            <input type="checkbox"
                                                                   name="permission_ids[]"
                                                                   value="{{ $byAction[$act]->id }}"
                                                                   {{ $checked($act) ? 'checked' : '' }}
                                                                   class="form-check-input">
                                                        @endif
                                                    </td>
                                                @endforeach
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="card-footer text-end">
                                <button class="btn btn-primary btn-sm">Save Permissions</button>
                            </div>
                        </div>
                    </form>
                @endif
            </div>
        </div>
    </div>
@endsection
