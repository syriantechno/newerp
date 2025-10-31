@extends('layouts.user_type.auth')

@section('content')
<div class="container-fluid py-3">
  <div class="card shadow-sm">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h6 class="mb-0">Employees</h6>
      <div class="d-flex gap-2">
        <input id="emp-search" type="text" class="form-control form-control-sm" placeholder="Search...">
        <select id="emp-status" class="form-select form-select-sm">
          <option value="">All</option>
          <option value="active">Active</option>
          <option value="inactive">Inactive</option>
        </select>
        <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#empModal">Add</button>
        <a href="{{ route('hr.employees.export','csv') }}" class="btn btn-sm btn-outline-secondary">Export CSV</a>
      </div>
    </div>
    <div class="card-body p-0" id="emp-table-wrap"></div>
  </div>
</div>

@include('hr::employees._form_modal')

@endsection

@push('scripts')
<script>
const tableWrap = document.getElementById('emp-table-wrap');
const statusSel = document.getElementById('emp-status');
const searchInp = document.getElementById('emp-search');
let tableUrl = "{{ route('hr.employees.table') }}";

function loadTable(params={}) {
  const u = new URL(tableUrl, window.location.origin);
  Object.entries(params).forEach(([k,v]) => { if(v!=='' && v!=null) u.searchParams.set(k, v) });
  fetch(u, {headers:{'X-Requested-With':'XMLHttpRequest'}})
    .then(r=>r.text()).then(html=>{ tableWrap.innerHTML = html; bindTableEvents(); });
}
function bindTableEvents(){
  document.querySelectorAll('[data-action="edit"]').forEach(btn=>{
    btn.addEventListener('click', async ()=>{
      const id = btn.dataset.id;
      const res = await fetch(`{{ url('hr/employees') }}/${id}`, {headers:{'X-Requested-With':'XMLHttpRequest'}});
      const data = await res.json();
      fillForm(data); new bootstrap.Modal('#empModal').show();
    });
  });
  document.querySelectorAll('[data-action="del"]').forEach(btn=>{
    btn.addEventListener('click', async ()=>{
      if(!confirm('Delete this employee?')) return;
      const id = btn.dataset.id;
      const res = await fetch(`{{ url('hr/employees') }}/${id}`, {method:'DELETE', headers:{
        'X-CSRF-TOKEN':'{{ csrf_token() }}','X-Requested-With':'XMLHttpRequest'
      }});
      if(res.ok) loadTable(getFilters());
    });
  });
  document.querySelectorAll('#emp-table-wrap .pagination a').forEach(a=>{
    a.addEventListener('click', (e)=>{ e.preventDefault(); fetch(a.href, {headers:{'X-Requested-With':'XMLHttpRequest'}})
      .then(r=>r.text()).then(html=>{ tableWrap.innerHTML = html; bindTableEvents(); }); });
  });
}
function getFilters(){ return {status: statusSel.value, search: searchInp.value}; }
statusSel.addEventListener('change', ()=> loadTable(getFilters()));
searchInp.addEventListener('input', ()=> loadTable(getFilters()));

const form = document.getElementById('emp-form');
form.addEventListener('submit', async (e)=>{
  e.preventDefault();
  const id = form.emp_id.value;
  const method = id ? 'PUT' : 'POST';
  const url = id ? `{{ url('hr/employees') }}/${id}` : `{{ route('hr.employees.store') }}`;
  const fd = new FormData(form);
  const res = await fetch(url, {method, body:fd, headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}'}});
  if(res.ok){ bootstrap.Modal.getInstance(document.getElementById('empModal')).hide(); form.reset(); loadTable(getFilters()); }
  else { alert('Validation error'); }
});
function fillForm(d){
  form.reset();
  form.emp_id.value = d.id ?? '';
  form.company_id.value = d.company_id ?? '';
  form.department_id.value = d.department_id ?? '';
  form.designation_id.value = d.designation_id ?? '';
  form.emp_code.value = d.emp_code ?? '';
  form.name.value = d.name ?? '';
  form.email.value = d.email ?? '';
  form.phone.value = d.phone ?? '';
  form.join_date.value = d.join_date ?? '';
  form.status.value = d.status ?? 'active';
}
document.addEventListener('DOMContentLoaded', ()=> loadTable());
</script>
@endpush
