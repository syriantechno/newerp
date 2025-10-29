@extends('layouts.user_type.auth')

@section('content')
    <div class="card mt-4 p-4">
        <h4 class="mb-4">Add New Custom Field</h4>

        <form method="POST" action="{{ route('settings.custom-fields.store') }}">
            @csrf

            <div class="row mb-3">
                <div class="col-md-6">
                    <label>Module</label>
                    <input type="text" name="module" class="form-control" placeholder="e.g., HR">
                </div>
                <div class="col-md-6">
                    <label>Internal Name</label>
                    <input type="text" name="name" class="form-control" placeholder="e.g., residence_expiry">
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label>Label</label>
                    <input type="text" name="label" class="form-control" placeholder="e.g., Residence Expiry Date">
                </div>
                <div class="col-md-6">
                    <label>Type</label>
                    <select name="type" class="form-control">
                        <option value="text">Text</option>
                        <option value="number">Number</option>
                        <option value="date">Date</option>
                        <option value="boolean">Boolean</option>
                        <option value="select">Select</option>
                        <option value="textarea">Textarea</option>
                        <option value="file">File</option>
                    </select>
                </div>
            </div>

            <div class="mb-3">
                <label>Options (for select type, comma separated)</label>
                <input type="text" name="options" class="form-control" placeholder="e.g., Active,Inactive,On Hold">
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label>Required?</label>
                    <input type="checkbox" name="is_required" value="1">
                </div>
                <div class="col-md-6">
                    <label>Validation Rule</label>
                    <input type="text" name="validation" class="form-control" placeholder="e.g., date|after:today">
                </div>
            </div>

            <button class="btn btn-success">Save Field</button>
        </form>
    </div>
@endsection
