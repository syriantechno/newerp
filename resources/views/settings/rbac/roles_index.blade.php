@extends('layouts.user_type.auth')

@section('content')
    <div class="container py-3">
        <h4 class="mb-3">Roles Management</h4>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @error('slug')
        <div class="alert alert-danger">{{ $message }}</div>
        @enderror

        <div class="card mb-4">
            <div class="card-body">
                <form method="POST" action="{{ route('settings.roles.store') }}" class="row g-2">
                    @csrf
                    <div class="col-md-3"><input name="name" class="form-control" placeholder="Role name" required></div>
                    <div class="col-md-3"><input name="slug" class="form-control" placeholder="role slug" required></div>
                    <div class="col-md-4"><input name="description" class="form-control" placeholder="Description"></div>
                    <div class="col-md-2"><button class="btn btn-primary w-100">Add</button></div>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="table-responsive">
                <table class="table align-items-center mb-0">
                    <thead><tr><th>Role</th><th>Slug</th><th>System</th><th>Description</th><th class="text-end">Actions</th></tr></thead>
                    <tbody>
                    @foreach($roles as $r)
                        <tr>
                            <td>{{ $r->name }}</td>
                            <td><code>{{ $r->slug }}</code></td>
                            <td>{{ $r->is_system ? 'Yes' : 'No' }}</td>
                            <td>{{ $r->description }}</td>
                            <td class="text-end">
                                <form method="POST" action="{{ route('settings.roles.update',$r) }}" class="d-inline-flex gap-2">
                                    @csrf @method('PUT')
                                    <input name="name" class="form-control form-control-sm" value="{{ $r->name }}">
                                    <input name="description" class="form-control form-control-sm" value="{{ $r->description }}">
                                    <button class="btn btn-sm btn-success" @if($r->is_system) disabled @endif>Save</button>
                                </form>
                                <form method="POST" action="{{ route('settings.roles.destroy',$r) }}" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-danger" @if($r->is_system) disabled @endif>Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
