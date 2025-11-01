@extends('layouts.user_type.auth')

@section('content')
    <div class="container-fluid py-4">

        {{-- ✅ Header --}}
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0">Employees</h5>
            <a href="{{ route('hr.employees.create') }}" class="btn btn-primary btn-sm">+ Add Employee</a>
        </div>

        {{-- ✅ Filters Bar --}}
        <div class="card mb-4 shadow-sm">
            <div class="card-body py-3">
                <form id="employeeFilters" class="row g-2 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label">Company</label>
                        <select name="company_id" class="form-select">
                            <option value="">All</option>
                            @foreach($companies as $c)
                                <option value="{{ $c->id }}">{{ $c->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Department</label>
                        <select name="department_id" class="form-select">
                            <option value="">All</option>
                            @foreach($departments as $d)
                                <option value="{{ $d->id }}">{{ $d->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Designation</label>
                        <select name="designation_id" class="form-select">
                            <option value="">All</option>
                            @foreach($designations as $g)
                                <option value="{{ $g->id }}">{{ $g->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="">All</option>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>

                    <div class="col-md-3 mt-2">
                        <input type="text" name="search" class="form-control" placeholder="Search name or email">
                    </div>

                    <div class="col-md-9 mt-2 d-flex gap-2">
                        <button type="button" id="applyFilters" class="btn bg-gradient-primary btn-sm">Apply</button>
                        <button type="button" id="resetFilters" class="btn btn-outline-secondary btn-sm">Reset</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- ✅ Unified DataTable --}}
        <x-datatable
            id="employeesTable"
            route="{{ route('hr.employees.table') }}"
            :columns="['ID', 'Name', 'Email', 'Company', 'Department', 'Designation', 'Status', 'Actions']"
        />

    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            console.log("🧩 [HR] Employees Filters Ready");
            let reloadTimer = null;

            function reloadTable() {
                clearTimeout(reloadTimer);
                reloadTimer = setTimeout(() => {
                    const dt = $('#employeesTable').DataTable();
                    if (dt && dt.ajax) {
                        console.log("🔄 Reloading employees table with filters...");
                        dt.ajax.reload(null, false);
                    } else {
                        console.warn("⚠️ Table not ready yet — reload skipped.");
                    }
                }, 400);
            }

            // ✅ Apply Filters
            $('#applyFilters').on('click', function (e) {
                e.preventDefault();
                reloadTable();
            });

            // ✅ Reset Filters (clear + reload default)
            $('#resetFilters').on('click', function (e) {
                e.preventDefault();
                const form = document.getElementById('employeeFilters');
                if (form) {
                    form.reset();
                }
                console.log("🧹 Filters reset — reloading table...");
                reloadTable();
            });
        });
    </script>
@endpush



