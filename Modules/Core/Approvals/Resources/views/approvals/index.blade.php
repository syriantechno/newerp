@extends('layouts.user_type.module')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Approvals</h4>
                    <a href="{{ route('approvals.create') }}" class="btn btn-sm btn-primary">+ New Approval</a>
                </div>
                <div class="card-body px-4 pt-4 pb-2">
                    @if(isset($approvals) && count($approvals))
                        <div class="table-responsive">
                            <table class="table align-items-center mb-0">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Title</th>
                                    <th>Module</th>
                                    <th>Status</th>
                                    <th>Current Step</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($approvals as $ap)
                                    <tr>
                                        <td>{{ $ap->id }}</td>
                                        <td>{{ $ap->title }}</td>
                                        <td>{{ $ap->module }}</td>
                                        <td>
                      <span class="badge bg-gradient-{{ $ap->status == 'approved' ? 'success' : ($ap->status == 'rejected' ? 'danger' : 'info') }}">
                        {{ ucfirst($ap->status) }}
                      </span>
                                        </td>
                                        <td>{{ $ap->current_step }}</td>
                                        <td>
                                            <a href="{{ route('approvals.show', $ap->id) }}" class="btn btn-sm btn-outline-secondary">View</a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-3">
                            {{ $approvals->links() }}
                        </div>
                    @else
                        <div class="alert alert-info text-center">
                            No approvals found.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
