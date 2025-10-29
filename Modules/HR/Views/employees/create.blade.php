@extends('layouts.user_type.auth')

@section('content')
    <div class="container mt-4">
        <div class="card p-4 shadow">
            <h4 class="mb-4">➕ Add New Employee</h4>

            <form id="employeeForm" enctype="multipart/form-data">
                @csrf

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label>Employee Code *</label>
                        <input type="text" name="employee_code" id="employee_code"
                               value="{{ $employeeCode ?? '' }}" class="form-control" readonly>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label>First Name *</label>
                        <input type="text" name="first_name" class="form-control" required>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label>Last Name *</label>
                        <input type="text" name="last_name" class="form-control" required>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label>Phone</label>
                        <input type="text" name="phone" class="form-control">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label>Department</label>
                        <input type="text" name="department" class="form-control">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label>Position</label>
                        <input type="text" name="position" class="form-control">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label>Salary</label>
                        <input type="number" name="salary" class="form-control">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label>Photo</label>
                        <input type="file" name="photo" class="form-control">
                    </div>
                </div>

                {{-- الحقول المخصصة --}}
                @if(isset($customFields) && count($customFields))
                    <hr>
                    <h6>🧩 Custom Fields</h6>
                    <div class="row">
                        @foreach($customFields as $field)
                            <div class="col-md-4 mb-3">
                                <label>{{ $field->label }}</label>
                                <input type="text" name="custom[{{ $field->id }}]" class="form-control">
                            </div>
                        @endforeach
                    </div>
                @endif

                <button type="submit" class="btn btn-success mt-3">💾 Save Employee</button>
            </form>

            <div id="responseMessage" class="mt-4"></div>
        </div>
    </div>

    <script>
        document.getElementById('employeeForm').addEventListener('submit', function(e) {
            e.preventDefault();

            let form = e.target;
            let formData = new FormData(form);

            fetch("{{ route('hr.employees.store') }}", {
                method: "POST",
                headers: { "X-CSRF-TOKEN": formData.get('_token') },
                body: formData
            })
                .then(res => res.json())
                .then(data => {
                    let msgDiv = document.getElementById('responseMessage');
                    if (data.status === 'success') {
                        msgDiv.innerHTML = `<div class="alert alert-success">${data.message}</div>`;
                        form.reset();
                        document.getElementById('employee_code').value = data.next_code;
                    } else {
                        msgDiv.innerHTML = `<div class="alert alert-danger">❌ Error saving employee</div>`;
                    }
                })
                .catch(err => {
                    console.error(err);
                    document.getElementById('responseMessage').innerHTML =
                        `<div class="alert alert-danger">⚠️ Unexpected error occurred</div>`;
                });
        });
    </script>
@endsection
