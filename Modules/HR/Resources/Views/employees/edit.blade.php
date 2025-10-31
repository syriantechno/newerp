@extends('layouts.user_type.auth')

@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card mb-4 mx-4">
                    <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                        <h6>Edit Employee</h6>
                        <a href="{{ route('hr.employees.index') }}" class="btn btn-sm bg-gradient-secondary mb-0">
                            ← Back
                        </a>
                    </div>

                    <div class="card-body pt-3">
                        <form action="{{ route('hr.employees.update', $employee->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Name</label>
                                    <input type="text" name="name" value="{{ old('name', $employee->name) }}" class="form-control border px-2" required>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Email</label>
                                    <input type="email" name="email" value="{{ old('email', $employee->email) }}" class="form-control border px-2">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Employee Code</label>
                                    <input type="text" name="emp_code" value="{{ old('emp_code', $employee->emp_code) }}" class="form-control border px-2" required>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Company</label>
                                    <select name="company_id" class="form-select border px-2">
                                        <option value="">Select</option>
                                        @foreach($companies as $company)
                                            <option value="{{ $company->id }}" @selected($employee->company_id == $company->id)>{{ $company->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Department</label>
                                    <select name="department_id" class="form-select border px-2">
                                        <option value="">Select</option>
                                        @foreach($departments as $dept)
                                            <option value="{{ $dept->id }}" @selected($employee->department_id == $dept->id)>{{ $dept->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Designation</label>
                                    <select name="designation_id" class="form-select border px-2">
                                        <option value="">Select</option>
                                        @foreach($designations as $des)
                                            <option value="{{ $des->id }}" @selected($employee->designation_id == $des->id)>{{ $des->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Status</label>
                                    <select name="status" class="form-select border px-2" required>
                                        <option value="active" @selected($employee->status == 'active')>Active</option>
                                        <option value="inactive" @selected($employee->status == 'inactive')>Inactive</option>
                                    </select>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Join Date</label>
                                    <input type="date" name="join_date" value="{{ old('join_date', $employee->join_date) }}" class="form-control border px-2">
                                </div>

                                <div class="col-md-12 mb-3">
                                    <label class="form-label">Notes</label>
                                    <textarea name="notes" rows="3" class="form-control border px-2">{{ old('notes', $employee->notes) }}</textarea>
                                </div>

                                <div class="col-md-12 mb-3">
                                    <label class="form-label">Photo</label>
                                    <input type="file" name="photo" class="form-control border px-2" accept="image/*">
                                    @if(!empty($employee->photo))
                                        <div class="mt-2">
                                            <img src="{{ asset('storage/'.$employee->photo) }}" alt="Photo" class="avatar avatar-md">
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="mt-4">
                                <button type="submit" class="btn bg-gradient-primary">Update Employee</button>
                                <a href="{{ route('hr.employees.index') }}" class="btn bg-gradient-secondary ms-2">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
