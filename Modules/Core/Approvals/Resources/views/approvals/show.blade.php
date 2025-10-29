@extends('layouts.user_type.auth')
@section('title', 'Approval Details')

@section('content')
    <div class="container-fluid py-4">
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6>Approval #{{ $approval->id }}</h6>
                <a href="{{ route('approvals.index') }}" class="btn btn-sm btn-secondary">← Back</a>
            </div>

            <div class="card-body">
                <h6 class="mb-3">General Information</h6>
                <ul class="list-group mb-4">
                    <li class="list-group-item">Module: <strong>{{ $approval->module }}</strong></li>
                    <li class="list-group-item">Record ID: <strong>{{ $approval->record_id }}</strong></li>
                    <li class="list-group-item">Status:
                        @if($approval->status === 'approved')
                            <span class="badge bg-success">Approved</span>
                        @elseif($approval->status === 'rejected')
                            <span class="badge bg-danger">Rejected</span>
                        @elseif($approval->status === 'in_progress')
                            <span class="badge bg-warning text-dark">In Progress</span>
                        @else
                            <span class="badge bg-secondary">Pending</span>
                        @endif
                    </li>
                </ul>

                <h6 class="mb-3">Steps</h6>
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>User</th>
                        <th>Status</th>
                        <th>Notes</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($approval->steps as $step)
                        <tr>
                            <td>{{ $step->step_order }}</td>
                            <td>{{ $step->user->name ?? 'N/A' }}</td>
                            <td>
                                @if($step->status === 'approved')
                                    <span class="badge bg-success">Approved</span>
                                @elseif($step->status === 'rejected')
                                    <span class="badge bg-danger">Rejected</span>
                                @else
                                    <span class="badge bg-secondary">Pending</span>
                                @endif
                            </td>
                            <td>{{ $step->notes ?? '-' }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>

                <h6 class="mt-4 mb-3">Logs</h6>
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th>User</th>
                        <th>Action</th>
                        <th>Comment</th>
                        <th>Date</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse ($approval->logs as $log)
                        <tr>
                            <td>{{ $log->user->name ?? 'N/A' }}</td>
                            <td>{{ ucfirst($log->action) }}</td>
                            <td>{{ $log->comment ?? '-' }}</td>
                            <td>{{ $log->created_at->format('Y-m-d H:i') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-center text-muted">No logs yet.</td></tr>
                    @endforelse
                    </tbody>
                </table>
                @if($approval->status === 'pending' || $approval->status === 'in_progress')
                    <div class="mt-4">
                        <form action="{{ route('approvals.action', $approval->id) }}" method="POST" class="d-flex gap-2">
                            @csrf
                            <input type="text" name="comment" class="form-control w-50" placeholder="Comment (optional)">
                            <button type="submit" name="action" value="approve" class="btn btn-success">Approve</button>
                            <button type="submit" name="action" value="reject" class="btn btn-danger">Reject</button>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
