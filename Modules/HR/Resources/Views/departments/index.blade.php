@extends('layouts.user_type.auth')

@section('content')
    <div class="container-fluid py-4">
        <div class="row mb-3">
            <div class="col-12 d-flex align-items-center justify-content-between">
                <h4 class="mb-0">Departments</h4>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createDeptModal">Add Department</button>
            </div>
        </div>

        {{-- Filters --}}
        <div class="card mb-3">
            <div class="card-body">
                <form method="GET" action="{{ route('hr.departments.index') }}" class="row g-2">
                    <div class="col-md-3">
                        <input type="text" name="search" value="{{ $filters['search'] ?? '' }}" class="form-control" placeholder="Search name/code/description">
                    </div>
                    <div class="col-md-2">
                        <select name="status" class="form-select">
                            <option value="">Status (All)</option>
                            <option value="active"   @selected(($filters['status'] ?? '')==='active')>Active</option>
                            <option value="inactive" @selected(($filters['status'] ?? '')==='inactive')>Inactive</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="parent_id" class="form-select">
                            <option value="">Parent (Any)</option>
                            @foreach($allDepartments as $d)
                                <option value="{{ $d->id }}" @selected(($filters['parent_id'] ?? '')==$d->id)>{{ $d->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <input type="number" name="company_id" value="{{ $filters['company_id'] ?? '' }}" class="form-control" placeholder="Company ID">
                    </div>
                    <div class="col-md-2 d-flex">
                        <button class="btn btn-outline-primary w-100 me-2">Filter</button>
                        <a href="{{ route('hr.departments.index') }}" class="btn btn-outline-secondary w-100">Reset</a>
                    </div>
                </form>
            </div>
        </div>

        {{-- Table --}}
        <div class="card">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table align-items-center mb-0">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th>Code</th>
                            <th>Parent</th>
                            <th>Manager</th>
                            <th class="text-center">Employees</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($departments as $dept)
                            <tr>
                                <td class="text-sm">{{ $dept->name }}</td>
                                <td class="text-sm">{{ $dept->code }}</td>
                                <td class="text-sm">{{ optional($dept->parent)->name }}</td>
                                <td class="text-sm">{{ optional($dept->manager)->name }}</td>
                                <td class="text-center">{{ $dept->employees_count }}</td>
                                <td>
                                <span class="badge bg-{{ $dept->status === 'active' ? 'success' : 'secondary' }}">
                                    {{ ucfirst($dept->status) }}
                                </span>
                                </td>
                                <td class="text-end">
                                    <button
                                        class="btn btn-sm btn-outline-primary"
                                        data-bs-toggle="modal"
                                        data-bs-target="#editDeptModal"
                                        data-id="{{ $dept->id }}"
                                        data-name="{{ $dept->name }}"
                                        data-code="{{ $dept->code }}"
                                        data-desc="{{ $dept->description }}"
                                        data-company="{{ $dept->company_id }}"
                                        data-parent="{{ $dept->parent_id }}"
                                        data-manager="{{ $dept->manager_id }}"
                                        data-status="{{ $dept->status }}"
                                    >Edit</button>

                                    <form action="{{ route('hr.departments.destroy', $dept->id) }}" method="POST" class="d-inline-block" onsubmit="return confirm('Delete this department?');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="text-center text-muted py-4">No departments found.</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="p-3">
                    {{ $departments->links() }}
                </div>
            </div>
        </div>
    </div>

    {{-- Create Modal --}}
    <div class="modal fade" id="createDeptModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <form class="modal-content" method="POST" action="{{ route('hr.departments.store') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Add Department</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @include('hr::departments.partials.form', ['mode' => 'create'])
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary">Save</button>
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Edit Modal --}}
    <div class="modal fade" id="editDeptModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <form class="modal-content" method="POST" id="editDeptForm">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Edit Department</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @include('hr::departments.partials.form', ['mode' => 'edit'])
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary">Update</button>
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        // Debug: console prints for safe testing
        console.log('[HR][Departments] page loaded');

        const editModal = document.getElementById('editDeptModal');
        editModal?.addEventListener('show.bs.modal', (event) => {
            const btn = event.relatedTarget;
            const id = btn?.getAttribute('data-id');
            console.log('[HR][Departments] edit open for id=', id);

            const form = document.getElementById('editDeptForm');
            form.action = "{{ url('/hr/departments') }}/" + id;

            form.querySelector('[name="name"]').value        = btn.getAttribute('data-name') || '';
            form.querySelector('[name="code"]').value        = btn.getAttribute('data-code') || '';
            form.querySelector('[name="description"]').value = btn.getAttribute('data-desc') || '';
            form.querySelector('[name="company_id"]').value  = btn.getAttribute('data-company') || '';
            form.querySelector('[name="parent_id"]').value   = btn.getAttribute('data-parent') || '';
            form.querySelector('[name="manager_id"]').value  = btn.getAttribute('data-manager') || '';
            form.querySelector('[name="status"]').value      = btn.getAttribute('data-status') || 'active';
        });
    </script>
@endpush
