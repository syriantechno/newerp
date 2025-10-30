@extends('layouts.user_type.auth')

@section('content')
    <div class="container-fluid py-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">🔔 Notifications</h5>
                <div>
                    <form action="{{ route('notifications.read') }}" method="POST" class="d-inline">
                        @csrf
                        <button class="btn btn-sm btn-success">Mark all as read</button>
                    </form>
                    <form action="{{ route('notifications.delete') }}" method="POST" class="d-inline ms-2">
                        @csrf
                        <button class="btn btn-sm btn-danger">Delete all</button>
                    </form>
                </div>
            </div>

            <div class="card-body px-0 pt-0 pb-2">
                @if($notifications->count())
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                            <tr>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-4">Title</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Message</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">Date</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">Status</th>
                                <th class="text-secondary opacity-7"></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($notifications as $n)
                                <tr>
                                    <td class="ps-4">
                                        <a href="{{ $n->url ?? '#' }}" class="text-dark text-sm font-weight-bold">
                                            {{ $n->title }}
                                        </a>
                                    </td>
                                    <td>
                                        <p class="text-xs text-secondary mb-0">{{ $n->message ?? '-' }}</p>
                                    </td>
                                    <td class="align-middle text-center">
                                        <span class="text-secondary text-xs">{{ \Carbon\Carbon::parse($n->created_at)->diffForHumans() }}</span>
                                    </td>
                                    <td class="align-middle text-center">
                                        @if($n->is_read)
                                            <span class="badge bg-gradient-secondary">Read</span>
                                        @else
                                            <span class="badge bg-gradient-info">New</span>
                                        @endif
                                    </td>
                                    <td class="align-middle text-center">
                                        <a href="{{ $n->url ?? '#' }}" class="text-primary text-xs">View</a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-4">
                        <p class="text-secondary mb-0">No notifications available.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
