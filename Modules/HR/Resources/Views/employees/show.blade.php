@extends('layouts.user_type.auth')

@section('content')
    <div class="container-fluid py-4">
        <div class="card">
            <div class="card-header pb-0">
                <h6>Employee Details</h6>
            </div>
            <div class="card-body">
                <p><strong>Name:</strong> {{ $employee->name }}</p>
                <p><strong>Email:</strong> {{ $employee->email }}</p>
                <p><strong>Status:</strong> {{ ucfirst($employee->status) }}</p>
                <p><strong>Join Date:</strong> {{ $employee->join_date }}</p>
            </div>
        </div>
    </div>
@endsection
