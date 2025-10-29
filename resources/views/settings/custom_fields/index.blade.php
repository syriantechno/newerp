@extends('layouts.user_type.auth')

@section('content')
    <div class="card mt-4 p-4">
        <h4 class="mb-4">Custom Fields Manager</h4>
        <a href="{{ route('settings.custom-fields.create') }}" class="btn btn-primary mb-3">Add New Field</a>

        @foreach($fields as $module => $moduleFields)
            <h5 class="text-secondary mt-3">{{ strtoupper($module) }}</h5>
            <table class="table table-striped align-items-center mb-4">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Label</th>
                    <th>Type</th>
                    <th>Required</th>
                    <th>Active</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @foreach($moduleFields as $f)
                    <tr>
                        <td>{{ $f->name }}</td>
                        <td>{{ $f->label }}</td>
                        <td>{{ $f->type }}</td>
                        <td>{{ $f->is_required ? 'Yes' : 'No' }}</td>
                        <td>{{ $f->is_active ? 'Yes' : 'No' }}</td>
                        <td>
                            <form action="{{ route('settings.custom-fields.destroy', $f->id) }}" method="POST" onsubmit="return confirm('Delete this field?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endforeach
    </div>
@endsection
