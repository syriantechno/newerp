@extends('layouts.user_type.auth')

@section('content')
    <div class="container-fluid py-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0">Departments</h5>
            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addDepartmentModal">
                <i class="fas fa-plus"></i> Add Department
            </button>
        </div>

        {{-- ✅ Unified DataTable Component --}}
        <x-datatable
            id="departmentsTable"
            route="{{ route('hr.departments.table') }}"
            :columns="['ID', 'Name', 'Code', 'Status', 'Actions']"
        />
    </div>

    {{-- ✅ Add Department Modal --}}
    <div class="modal fade" id="addDepartmentModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title">Add Department</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addDepartmentForm">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Name</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Code</label>
                            <input type="text" name="code" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select">
                                <option value="active" selected>Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                        <div class="text-end">
                            <button type="submit" class="btn btn-success">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            console.log("🧩 [HR] Departments page loaded");

            const form = $('#addDepartmentForm');
            const modalEl = document.getElementById('addDepartmentModal');
            const modal = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);

            form.on('submit', function(e) {
                e.preventDefault();
                $.ajax({
                    url: "{{ route('hr.departments.store') }}",
                    method: 'POST',
                    data: form.serialize(),
                    success: function() {
                        toastr.success('Department added successfully!');
                        modal.hide();
                        form[0].reset();

                        // Refresh table safely
                        if ($.fn.DataTable.isDataTable('#departmentsTable')) {
                            $('#departmentsTable').DataTable().ajax.reload(null, false);
                        }
                    },
                    error: function(xhr) {
                        toastr.error('Failed to add department');
                        console.error(xhr.responseText);
                    }
                });
            });
        });
    </script>
@endpush
