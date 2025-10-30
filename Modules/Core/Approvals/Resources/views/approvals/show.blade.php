@extends('layouts.user_type.auth')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Approval #{{ $approval->id }}</h4>
                    <span
                        class="badge bg-gradient-{{ $approval->status == 'approved' ? 'success' : ($approval->status == 'rejected' ? 'danger' : 'info') }}">
          {{ ucfirst($approval->status) }}
        </span>
                </div>

                <div class="card-body px-4 pt-4 pb-2">
                    <div class="mb-3">
                        <strong>Title:</strong> {{ $approval->title }}<br>
                        <strong>Module:</strong> {{ $approval->module }}<br>
                        <strong>Record ID:</strong> {{ $approval->record_id }}
                    </div>

                    <h6>Approval Steps</h6>
                    <div class="table-responsive">
                        <table class="table align-items-center mb-0">
                            <thead>
                            <tr>
                                <th>Order</th>
                                <th>User</th>
                                <th>Status</th>
                                <th>Active</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($approval->steps as $s)
                                <tr class="{{ $s->step_order == $approval->current_step && $s->status == 'pending' ? 'table-warning' : '' }}">
                                    <td>{{ $s->step_order }}</td>
                                    <td>{{ $s->user?->name ?? 'Any User' }} @if($s->user_id)
                                            (#{{ $s->user_id }})
                                        @endif</td>
                                    <td>
                  <span
                      class="badge bg-gradient-{{ $s->status == 'approved' ? 'success' : ($s->status == 'rejected' ? 'danger' : 'secondary') }}">
                    {{ ucfirst($s->status) }}
                  </span>
                                    </td>
                                    <td>{{ $s->step_order == $approval->current_step && $s->status == 'pending' ? 'Yes' : 'No' }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>

                    @if(in_array($approval->status, ['pending','in_progress']))
                        <form action="{{ route('approvals.action', $approval->id) }}" method="POST"
                              class="d-flex gap-2 mt-4">
                            @csrf
                            <input type="text" name="comment" class="form-control w-50"
                                   placeholder="Comment (optional)">
                            <button type="submit" name="action" value="approve" class="btn btn-success">Approve</button>
                            <button type="submit" name="action" value="reject" class="btn btn-danger">Reject</button>
                        </form>
                    @endif
                </div>
            </div>

            <div class="card">
                <div class="card-header pb-0">
                    <h6>Logs</h6>
                </div>
                <div class="card-body">
                    <ul class="list-group">
                        @forelse($approval->logs as $log)
                            <li class="list-group-item">
                                <strong>{{ strtoupper($log->action) }}</strong>
                                — by {{ $log->user?->name ?? 'System' }}
                                — at {{ $log->created_at->format('Y-m-d H:i') }}
                                @if($log->comment)
                                    <br><em>{{ $log->comment }}</em>
                                @endif
                            </li>
                        @empty
                            <li class="list-group-item text-muted">No logs recorded yet.</li>
                        @endforelse
                    </ul>
                </div>
            </div>

            <div class="mt-3">
                <a href="{{ route('approvals.index') }}" class="btn btn-secondary">Back</a>
            </div>
        </div>
    </div>
@endsection
