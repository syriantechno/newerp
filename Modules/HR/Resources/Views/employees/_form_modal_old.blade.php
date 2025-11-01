<div class="modal fade" id="empModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header"><h6 class="modal-title">Employee</h6>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
      <form id="emp-form">
        @csrf
        <input type="hidden" name="emp_id">
        <div class="modal-body">
          <div class="row g-3">
            <div class="col-md-3">
              <label class="form-label">Company</label>
              <select name="company_id" id="company_id" class="form-select">
                @foreach(\Modules\HR\Models\Company::orderBy('name')->get() as $c)
                  <option value="{{ $c->id }}">{{ $c->name }}</option>
                @endforeach
              </select>
            </div>
            <div class="col-md-3">
              <label class="form-label">Department</label>
              <select name="department_id" id="department_id" class="form-select">
                @foreach(\Modules\HR\Models\Department::orderBy('name')->get() as $d)
                  <option value="{{ $d->id }}">{{ $d->name }}</option>
                @endforeach
              </select>
            </div>
            <div class="col-md-3">
              <label class="form-label">Designation</label>
              <select name="designation_id" id="designation_id" class="form-select">
                @foreach(\Modules\HR\Models\Designation::orderBy('name')->get() as $g)
                  <option value="{{ $g->id }}">{{ $g->name }}</option>
                @endforeach
              </select>
            </div>
            <div class="col-md-3">
              <label class="form-label">Status</label>
              <select name="status" class="form-select">
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
              </select>
            </div>

            <div class="col-md-3">
              <label class="form-label">Emp Code</label>
              <input name="emp_code" class="form-control" required>
            </div>
            <div class="col-md-3">
              <label class="form-label">Name</label>
              <input name="name" class="form-control" required>
            </div>
            <div class="col-md-3">
              <label class="form-label">Email</label>
              <input name="email" type="email" class="form-control">
            </div>
            <div class="col-md-3">
              <label class="form-label">Phone</label>
              <input name="phone" class="form-control">
            </div>
            <div class="col-md-3">
              <label class="form-label">Join Date</label>
              <input name="join_date" type="date" class="form-control">
            </div>

            <div class="col-12">
              <label class="form-label">Extra (JSON)</label>
              <textarea name="extra" class="form-control" placeholder='{"nationality":"..."}'></textarea>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button class="btn btn-primary" type="submit">Save</button>
          <button class="btn btn-light" data-bs-dismiss="modal" type="button">Cancel</button>
        </div>
      </form>
    </div>
  </div>
</div>
