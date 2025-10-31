<table class="table mb-0 align-items-center">
  <thead>
    <tr>
      <th>#</th><th>Code</th><th>Name</th><th>Email</th><th>Company</th><th>Dept</th><th>Title</th><th>Status</th><th class="text-end">Actions</th>
    </tr>
  </thead>
  <tbody>
  @forelse($employees as $r)
    <tr>
      <td>{{ $r->id }}</td>
      <td>{{ $r->emp_code }}</td>
      <td>{{ $r->name }}</td>
      <td>{{ $r->email }}</td>
      <td>{{ $r->company->name ?? '-' }}</td>
      <td>{{ $r->department->name ?? '-' }}</td>
      <td>{{ $r->designation->name ?? '-' }}</td>
      <td><span class="badge bg-{{ $r->status==='active'?'success':'secondary' }}">{{ $r->status }}</span></td>
      <td class="text-end">
        <button class="btn btn-sm btn-outline-primary" data-action="edit" data-id="{{ $r->id }}">Edit</button>
        <button class="btn btn-sm btn-outline-danger" data-action="del" data-id="{{ $r->id }}">Delete</button>
      </td>
    </tr>
  @empty
    <tr><td colspan="9" class="text-center p-4">No data</td></tr>
  @endforelse
  </tbody>
</table>
<div class="p-3">
  {{ $employees->withQueryString()->links() }}
</div>
