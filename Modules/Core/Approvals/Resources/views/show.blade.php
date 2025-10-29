@extends('layouts.user_type.auth')

@section('content')
    <div class="container-fluid py-4">
        <div class="row mb-3">
            <div class="col">
                <h4 class="text-gradient text-primary">Approval #{{ $approval->id }}</h4>
                <p class="text-muted">Module: {{ strtoupper($approval->module) }}</p>
            </div>
        </div>

        <div class="card p-4 shadow">
            <h6 class="text-dark mb-3">Approval Steps</h6>

            <ul class="list-group">
                @foreach($approval->steps as $step)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <strong>Step {{ $step->step_number }}:</strong>
                            Approver #{{ $step->approver_id }}
                            <br>
                            <small class="text-muted">
                                {{ $step->comment ?? 'No comments yet' }}
                            </small>
                        </div>

                        @if($step->status == 'pending')
                            <span class="badge bg-gradient-warning">Pending</span>
                        @elseif($step->status == 'approved')
                            <span class="badge bg-gradient-success">Approved</span>
                        @elseif($step->status == 'rejected')
                            <span class="badge bg-gradient-danger">Rejected</span>
                        @else
                            <span class="badge bg-secondary">Locked</span>
                        @endif
                    </li>
                @endforeach
            </ul>

            <hr>

            @if($approval->status == 'pending' && $approval->currentStep?->approver_id == Auth::id())
                <form id="approvalActionForm" class="mt-3">
                    @csrf
                    <input type="hidden" id="step_id" value="{{ $approval->currentStep->id }}">
                    <div class="form-group mb-3">
                        <label>Comment (optional)</label>
                        <textarea id="comment" class="form-control" rows="3"></textarea>
                    </div>
                    <button type="button" id="approveBtn" class="btn btn-success">✅ Approve</button>
                    <button type="button" id="rejectBtn" class="btn btn-danger">❌ Reject</button>
                </form>
            @endif

            <div id="responseMessage" class="mt-3"></div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const approveBtn = document.getElementById('approveBtn');
            const rejectBtn = document.getElementById('rejectBtn');

            function sendAction(action) {
                let stepId = document.getElementById('step_id').value;
                let comment = document.getElementById('comment').value;

                fetch(`/approvals/${stepId}/${action}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ comment })
                })
                    .then(res => res.json())
                    .then(data => {
                        if (data.status === 'success') {
                            document.getElementById('responseMessage').innerHTML =
                                `<div class='alert alert-success'>${action === 'approveStep' ? 'Approved' : 'Rejected'} successfully ✅</div>`;
                            setTimeout(() => location.reload(), 1500);
                        } else {
                            document.getElementById('responseMessage').innerHTML =
                                `<div class='alert alert-danger'>Error performing action ❌</div>`;
                        }
                    });
            }

            if (approveBtn) approveBtn.addEventListener('click', () => sendAction('approveStep'));
            if (rejectBtn) rejectBtn.addEventListener('click', () => sendAction('rejectStep'));
        });
    </script>
@endsection
