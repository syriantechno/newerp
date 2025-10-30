@extends('layouts.user_type.auth')

@section('content')
    <div class="container-fluid py-4">
        <div class="card">
            <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                <h5 class="mb-0">🗂 Messages Manager</h5>
                <form method="POST" action="{{ route('settings.messages.save') }}">
                    @csrf
                    <button type="submit" class="btn btn-primary btn-sm">💾 Save Changes</button>
            </div>
            <div class="card-body pt-3 pb-4">
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <table class="table align-items-center mb-0">
                    <thead>
                    <tr>
                        <th style="width: 25%">Key</th>
                        <th>Message</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($messages as $key => $text)
                        <tr>
                            <td>
                                <input type="text" class="form-control form-control-sm" name="messages[{{ $key }}][key]" value="{{ $key }}" readonly>
                            </td>
                            <td>
                                <input type="text" class="form-control form-control-sm" name="messages[{{ $key }}]" value="{{ $text }}">
                            </td>
                        </tr>
                    @endforeach
                    <tr>
                        <td colspan="2" class="text-center text-muted pt-3">+ Add New Message</td>
                    </tr>
                    <tr>
                        <td><input type="text" class="form-control form-control-sm" name="messages[new.key]" placeholder="new.key"></td>
                        <td><input type="text" class="form-control form-control-sm" name="messages[new.value]" placeholder="Message text..."></td>
                    </tr>
                    </tbody>
                </table>
            </div>
            </form>
        </div>
    </div>
@endsection
