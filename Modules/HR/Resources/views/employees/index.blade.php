@extends('layouts.user_type.auth')

@section('content')
    <div class="container-fluid py-4">
        <div class="container-fluid">
            <div class="page-header min-height-200 border-radius-xl mt-4" style="background-image: url('../assets/img/curved-images/curved14.jpg'); background-position-y: 72%;">
                <span class="bg-gradient-secondary" ></span>
            </div>
            <div class="card card-body blur shadow-blur mx-4 mt-n6">
                <div class="row gx-4">
                    <div class="col-auto">
                        <div class="avatar avatar-xl position-relative">
                                <img src="../assets/img/emp.png" alt="..." class="w-100 border-radius-lg shadow-sm">

                        </div>
                    </div>
                    <div class="col-auto my-auto">
                            <h5 class="mb-1">
                                Employees
                            </h5>
                        </div>
                    <div class="col-lg-4 col-md-6 my-sm-auto ms-sm-auto me-sm-0 mx-auto mt-3 d-flex justify-content-end">

                        <a href="{{ route('hr.employees.create') }}"
                           data-bs-toggle="tooltip"
                           data-bs-placement="top"
                           title="Print"
                           class=" d-flex align-items-center justify-content-center"
                           style="width:40px; height:40px; border-radius:8px; padding:0; margin-right: 1em;">
                            <img src="../assets/img/print.png"
                                 alt="Export"
                                 style="width:45px; height:45px;">
                        </a>
                        <a href="{{ route('hr.employees.create') }}"
                           data-bs-toggle="tooltip"
                           data-bs-placement="top"
                           title="Export to Excel"
                           class=" d-flex align-items-center justify-content-center"
                           style="width:40px; height:40px; border-radius:8px; padding:0; margin-right: 1em;">
                            <img src="../assets/img/export-excel.png"
                                 alt="Export"
                                 style="width:40px; height:40px;">
                        </a>
                        <a href="{{ route('hr.employees.create') }}"
                           data-bs-toggle="tooltip"
                           data-bs-placement="top"
                           title="Export to PDF"
                           class=" d-flex align-items-center justify-content-center"
                           style="width:40px; height:40px; border-radius:8px; padding:0; margin-right: 1em;">
                            <img src="../assets/img/export-pdf.png"
                                 alt="Export"
                                 style="width:40px; height:40px;">
                        </a>

                        <a href="{{ route('hr.employees.create') }}" class="btn bg-gradient-info btn-sm d-flex align-items-center">
                            <i class="fas fa-plus me-1"></i> Add Employee
                        </a>
                    </div>

                     </div>
                </div>
            </div>
        </div>
        {{-- ✅ Filters Bar --}}
        <!-- Put this CSS near the page (scoped) -->
        <style>
            /* Normalize heights and vertical alignment */
            .filters-row .filter-col {
                display: flex;
                align-items: center;
                gap: .5rem;
                margin-top: 0 !important; /* neutralize any mt-2 */
            }
            .filters-row .form-control,
            .filters-row .form-select,
            .filters-row .btn {
                height: 42px;
                line-height: 42px; /* keep text centered vertically */
                padding-top: 0;
                padding-bottom: 0;
            }
            #employeeFilters .btn {
                height: 42px !important;
                line-height: 42px !important;
                padding-top: 0 !important;
                padding-bottom: 0 !important;
                display: flex;
                align-items: center;
            }
            .btn {
                margin-bottom: 0;
            }
            .filters-row .btn { display: inline-flex; align-items: center; }
        </style>

        <div class="card-body py-4">
            <form id="employeeFilters" class="row g-2 filters-row align-items-center">

                <div class="col-md-2 filter-col">
                    <input type="text" name="search" class="form-control" placeholder="Search name or email">
                </div>

                <div class="col-md-2 filter-col">
                    <select name="company_id" class="form-select">
                        <option value="">Company</option>
                        @foreach($companies as $c)
                            <option value="{{ $c->id }}">{{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2 filter-col">
                    <select name="department_id" class="form-select">
                        <option value="">Department</option>
                        @foreach($departments as $d)
                            <option value="{{ $d->id }}">{{ $d->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2 filter-col">
                    <select name="designation_id" class="form-select">
                        <option value="">Designation</option>
                        @foreach($designations as $g)
                            <option value="{{ $g->id }}">{{ $g->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2 filter-col">
                    <select name="status" class="form-select">
                        <option value="">Status</option>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>

                <div class="col-md-2 filter-col">
                    <button type="button" id="applyFilters" class="btn bg-gradient-primary btn-sm">Apply</button>
                    <button type="button" id="resetFilters" class="btn btn-outline-secondary btn-sm">Reset</button>
                </div>
            </form>
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
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })
        })
    </script>
@endpush



