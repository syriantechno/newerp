@extends('layouts.user_type.auth')

@section('content')
    <div class="card mt-4 p-4">
        <h3 class="mb-4 text-primary">Employees List</h3>
        <a href="{{ route('hr.employees.create') }}" class="btn btn-sm btn-primary mb-3">Add Employee</a>

        <table class="table align-items-center mb-0">
            <thead>
            <tr>
                <th>ID</th>
                <th>Code</th>
                <th>Name</th>
                <th>Department</th>
                <th>Position</th>
                <th>Salary</th>
            </tr>
            </thead>
            <tbody>
            @forelse($employees as $emp)
                <tr>
                    <td>{{ $emp->id }}</td>
                    <td>{{ $emp->employee_code }}</td>
                    <td>{{ $emp->first_name }} {{ $emp->last_name }}</td>
                    <td>{{ $emp->department }}</td>
                    <td>{{ $emp->position }}</td>
                    <td>{{ $emp->salary }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">No employees found.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
@endsection
