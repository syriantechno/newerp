@extends('layouts.user_type.auth')

@section('content')
    <div class="container-fluid py-4">

        <div class="card mb-4">
            <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                <h6>Employees</h6>
                <a href="{{ route('hr.employees.create') }}" class="btn bg-gradient-primary btn-sm">+ Add Employee</a>
            </div>

            <div class="card-body px-4 pt-3 pb-2">
                {{-- ===== Filters Header (Soft-UI style) ===== --}}
                <form id="employee-filters" class="row g-2 align-items-end mb-3">
                    <div class="col-md-3">
                        <label class="form-label mb-0">Company</label>
                        <select name="company_id" class="form-select">
                            <option value="">All Companies</option>
                            @foreach($companies as $c)
                                <option value="{{ $c->id }}">{{ $c->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label mb-0">Department</label>
                        <select name="department_id" class="form-select">
                            <option value="">All Departments</option>
                            @foreach($departments as $d)
                                <option value="{{ $d->id }}">{{ $d->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label mb-0">Designation</label>
                        <select name="designation_id" class="form-select">
                            <option value="">All Designations</option>
                            @foreach($designations as $g)
                                <option value="{{ $g->id }}">{{ $g->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label class="form-label mb-0">Status</label>
                        <select name="status" class="form-select">
                            <option value="">All</option>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>

                    <div class="col-md-4 mt-2">
                        <input type="text" name="search" class="form-control" placeholder="Search by name, email, or code...">
                    </div>

                    <div class="col-md-8 mt-2 d-flex gap-2">
                        <button type="button" id="apply-filters" class="btn bg-gradient-primary btn-sm px-3">
                            Apply Filters
                        </button>
                        <button type="button" id="reset-filters" class="btn btn-outline-secondary btn-sm px-3">
                            Reset
                        </button>
                        <div class="ms-auto text-sm opacity-8 d-flex align-items-center">
                            <span id="results-count" class="me-2">{{ $employees->total() }}</span> results
                        </div>
                    </div>
                </form>

                {{-- Active filters summary --}}
                <div id="filter-summary" class="alert alert-secondary py-2 px-3 mb-3">
                    @include('hr::employees.partials.summary', ['count' => $employees->total(), 'filters' => []])
                </div>

                {{-- ===== Employees Table ===== --}}
                <div class="table-responsive p-0">
                    <table class="table align-items-center mb-0">
                        <thead>
                        <tr>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">#</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Name</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Email</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Company</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Department</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Designation</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">Status</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">Actions</th>
                        </tr>
                        </thead>
                        <tbody id="employees-table">
                        @include('hr::employees.partials.table', ['employees' => $employees])
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div id="employees-pagination" class="mt-3">
                    @include('hr::employees.partials.pagination', ['employees' => $employees])
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            (function() {
                const KEY = 'hr_employees_filters_v1';

                function loadFiltersFromStorage() {
                    try {
                        const saved = JSON.parse(localStorage.getItem(KEY) || '{}');
                        Object.keys(saved).forEach(k => {
                            const el = document.querySelector(`#employee-filters [name="${k}"]`);
                            if (el) el.value = saved[k];
                        });
                    } catch (e) { console.warn('[DEBUG] loadFiltersFromStorage error', e); }
                }

                function saveFiltersToStorage() {
                    const data = Object.fromEntries(new FormData(document.getElementById('employee-filters')).entries());
                    localStorage.setItem(KEY, JSON.stringify(data));
                }

                function applyFilters(url = null) {
                    const $btn = $('#apply-filters');
                    $btn.prop('disabled', true).text('Loading...');

                    saveFiltersToStorage();

                    const qs = $('#employee-filters').serialize();
                    const targetUrl = url || '{{ route('hr.employees.filter') }}';

                    $.ajax({
                        url: targetUrl,
                        data: qs,
                        method: 'GET',
                        dataType: 'text', // 👈 مهم: استقبل كنص وليس JSON
                        success: function (raw) {
                            let clean = raw.toString().trim();
                            // نحذف أي أسطر Debug في البداية
                            if (clean.startsWith('[DEBUG]')) {
                                clean = clean.replace(/^\[DEBUG\][\s\S]*?\{/, '{');
                            }

                            let res;
                            try {
                                res = JSON.parse(clean);
                            } catch (e) {
                                console.warn('[Filter] JSON parse failed, raw:', clean);
                                return;
                            }

                            $('#employees-table').html(res.tbody || '');
                            $('#employees-pagination').html(res.pagination || '');
                            $('#filter-summary').html(res.summary || '');
                            $('#results-count').text(res.total ?? 0);
                        },
                        error: function (xhr, status, err) {
                            console.error('[Filter] AJAX error handler triggered:', status, err);
                            console.warn('Raw response:', xhr.responseText);
                        },
                        complete: function () {
                            $btn.prop('disabled', false).text('Apply Filters');
                        }
                    });
                }



                function resetFilters() {
                    $('#employee-filters')[0].reset();
                    localStorage.removeItem(KEY);
                    applyFilters(); // fetch default
                }

                // Initial
                $(document).ready(function() {
                    loadFiltersFromStorage();
                    // Optional: Apply immediately on load to restore remembered filters
                    applyFilters();

                    $('#apply-filters').on('click', function() {
                        applyFilters();
                    });

                    $('#reset-filters').on('click', function() {
                        resetFilters();
                    });

                    // AJAX pagination: delegate clicks
                    $(document).on('click', '#employees-pagination a.page-link', function(e) {
                        e.preventDefault();
                        const href = $(this).attr('href');
                        if (!href) return;
                        applyFilters(href);
                    });
                });
            })();
        </script>
    @endpush
@endsection
