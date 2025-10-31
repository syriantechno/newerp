@extends('layouts.user_type.auth')

@section('content')
    <div class="card mb-4">
        <div class="card-header pb-0">
            <h5 class="mb-0">Attendance</h5>
        </div>

        <div class="card-body py-3">
            {{-- Filters Bar --}}
            <form id="attendance-filters" class="row g-2 align-items-end">
                <div class="col-md-3">
                    <label class="form-label">Employee</label>
                    <select name="employee_id" class="form-select">
                        <option value="">All</option>
                        @foreach($employees as $emp)
                            <option value="{{ $emp->id }}">{{ $emp->name }}</option>
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
                        @foreach($designations as $d)
                            <option value="{{ $d->id }}">{{ $d->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-1">
                    <label class="form-label">Month</label>
                    <select name="month" class="form-select">
                        @for($m=1;$m<=12;$m++)
                            <option value="{{ $m }}" @selected($m==$month)>{{ \Carbon\Carbon::create(null,$m,1)->format('F') }}</option>
                        @endfor
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label">Year</label>
                    <select name="year" class="form-select">
                        @for($y=date('Y')-3;$y<=date('Y')+1;$y++)
                            <option value="{{ $y }}" @selected($y==$year)>{{ $y }}</option>
                        @endfor
                    </select>
                </div>

                <div class="col-12 d-flex gap-2 mt-2">
                    <button type="button" id="apply-filters" class="btn btn-sm bg-gradient-primary">Apply</button>
                    <button type="button" id="reset-filters" class="btn btn-sm btn-outline-secondary">Reset</button>

                    <div class="ms-auto d-flex gap-2">
                        <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#markModal">+ Mark Attendance</button>
                        <form action="{{ route('hr.attendance.import') }}" method="POST" enctype="multipart/form-data" class="d-inline">
                            @csrf
                            <label class="btn btn-sm btn-outline-dark mb-0">
                                Import <input type="file" name="file" class="d-none" onchange="this.form.submit()">
                            </label>
                        </form>
                        <a href="{{ route('hr.attendance.export') }}" class="btn btn-sm btn-outline-primary">Export</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Legend --}}
    <div class="alert alert-secondary py-2">
        <strong>Note:</strong>
        ⭐ Half Day &nbsp; | &nbsp; 📅 Holiday &nbsp; | &nbsp; 💤 Day Off &nbsp; | &nbsp; ✅ Present &nbsp; | &nbsp; 🕓 Late &nbsp; | &nbsp; ❌ Absent &nbsp; | &nbsp; 🚫 Not registered &nbsp; | &nbsp; ✈️ On Leave
    </div>

    {{-- Table --}}
    <div class="card">
        <div class="card-body" id="attendance-table-wrap">
            <div class="text-center text-muted py-5" id="table-loader">Loading...</div>
        </div>
    </div>

    {{-- Mark Attendance Modal --}}
    <div class="modal fade" id="markModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form method="POST" action="{{ route('hr.attendance.mark') }}" class="modal-content">
                @csrf
                <div class="modal-header">
                    <h6 class="modal-title">Mark Attendance</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-2">
                        <label class="form-label">Employee</label>
                        <select name="employee_id" class="form-select" required>
                            @foreach($employees as $emp)
                                <option value="{{ $emp->id }}">{{ $emp->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Date</label>
                        <input type="date" name="date" class="form-control" value="{{ now()->toDateString() }}" required>
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select" required>
                            <option value="present">Present</option>
                            <option value="late">Late</option>
                            <option value="half_day">Half Day</option>
                            <option value="absent">Absent</option>
                            <option value="holiday">Holiday</option>
                            <option value="day_off">Day Off</option>
                            <option value="leave">On Leave</option>
                            <option value="not_registered">Not registered</option>
                        </select>
                    </div>
                    <div class="row g-2">
                        <div class="col">
                            <label class="form-label">Check-in</label>
                            <input type="time" name="check_in" class="form-control">
                        </div>
                        <div class="col">
                            <label class="form-label">Check-out</label>
                            <input type="time" name="check_out" class="form-control">
                        </div>
                    </div>
                    <div class="mt-2">
                        <label class="form-label">Remarks</label>
                        <textarea name="remarks" class="form-control" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-sm btn-primary" type="submit">Save</button>
                    <button class="btn btn-sm btn-secondary" type="button" data-bs-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
        <script>
            (function(){
                function loadTable() {
                    const $btn = $('#apply-filters');
                    $btn.prop('disabled', true).text('Loading...');
                    $('#table-loader').show();

                    $.ajax({
                        url: "{{ route('hr.attendance.filter') }}",
                        data: $('#attendance-filters').serialize(),
                        method: 'GET',
                        dataType: 'text',
                        success: function(raw){
                            let clean = raw.toString().trim();
                            if (clean.startsWith('[DEBUG]')) {
                                clean = clean.replace(/^\[DEBUG\][\s\S]*?\{/, '{');
                            }
                            let res = null;
                            try { res = JSON.parse(clean); } catch(e){ console.warn('[Attendance] parse failed', e); return; }
                            if (!res || !res.ok) return;

                            $('#attendance-table-wrap').html(res.table);
                        },
                        complete: function(){
                            $btn.prop('disabled', false).text('Apply');
                            $('#table-loader').hide();
                        },
                        error: function(xhr){ console.error('[Attendance] AJAX', xhr.status, xhr.responseText); }
                    });
                }

                $('#apply-filters').on('click', loadTable);
                $('#reset-filters').on('click', function(){
                    $('#attendance-filters').get(0).reset();
                    loadTable();
                });

                // initial
                loadTable();
            })();
        </script>
    @endpush
@endsection
