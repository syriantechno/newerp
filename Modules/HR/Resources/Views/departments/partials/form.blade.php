<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label">Name</label>
        <input type="text" name="name" class="form-control" placeholder="e.g. Production" required>
    </div>
    <div class="col-md-6">
        <label class="form-label">Code</label>
        <input type="text" name="code" class="form-control" placeholder="e.g. PRD">
    </div>
    <div class="col-md-12">
        <label class="form-label">Description</label>
        <textarea name="description" class="form-control" rows="2" placeholder="Short description (optional)"></textarea>
    </div>

    <div class="col-md-4">
        <label class="form-label">Company ID</label>
        <input type="number" name="company_id" class="form-control" placeholder="Optional">
    </div>

    <div class="col-md-4">
        <label class="form-label">Parent Department</label>
        <select name="parent_id" class="form-select">
            <option value="">None</option>
            @foreach($allDepartments as $d)
                <option value="{{ $d->id }}">{{ $d->name }}</option>
            @endforeach
        </select>
    </div>

    <div class="col-md-4">
        <label class="form-label">Manager</label>
        <select name="manager_id" class="form-select">
            <option value="">None</option>
            @foreach($managers as $m)
                <option value="{{ $m->id }}">{{ $m->name }}</option>
            @endforeach
        </select>
    </div>

    <div class="col-md-4">
        <label class="form-label">Status</label>
        <select name="status" class="form-select" required>
            <option value="active" selected>Active</option>
            <option value="inactive">Inactive</option>
        </select>
    </div>
</div>
