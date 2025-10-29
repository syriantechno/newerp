@extends('layouts.user_type.auth')

@section('content')
    <div class="container-fluid py-4">
        <div class="row mb-3">
            <div class="col">
                <h4 class="text-gradient text-primary">🧾 Approvals Management</h4>
                <p class="text-muted">Track and manage all approval requests across modules</p>
            </div>
        </div>

        <div class="card p-3 shadow">
            <div class="table-responsive">
                <table class="table align-items-center mb-0" id="approvalsTable">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Module</th>
                        <th>Record ID</th>
                        <th>Status</th>
                        <th>Created By</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($approvals as $approval)
                        <tr>
                            <td>{{ $approval->id }}</td>
                            <td>{{ strtoupper($approval->module) }}</td>
                            <td>{{ $approval->record_id }}</td>
                            <td>
                                @if($approval->status == 'approved')
                                    <span class="badge bg-gradient-success">Approved</span>
                                @elseif($approval->status == 'rejected')
                                    <span class="badge bg-gradient-danger">Rejected</span>
                                @else
                                    <span class="badge bg-gradient-warning">Pending</span>
                                @endif
                            </td>
                            <td>{{ $approval->created_by }}</td>
                            <td>{{ $approval->created_at->format('Y-m-d H:i') }}</td>
                            <td>
                                <a href="{{ route('approvals.show', $approval->id) }}"
                                   class="btn btn-sm btn-outline-primary">View</a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
