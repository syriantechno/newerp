@foreach($employees as $emp)
    <tr>
        <td>{{ $emp->id }}</td>
        <td>{{ $emp->name }}</td>
        <td>{{ $emp->email }}</td>
        <td>{{ optional($emp->company)->name }}</td>
        <td>{{ optional($emp->department)->name }}</td>
        <td>{{ optional($emp->designation)->name }}</td>
        <td class="text-center">
        <span class="badge bg-{{ $emp->status === 'active' ? 'success' : 'secondary' }}">
            {{ ucfirst($emp->status) }}
        </span>
        </td>
        <td class="text-center">
            <a href="{{ route('hr.employees.edit', $emp->id) }}" class="mx-2 text-secondary">
                <i class="fas fa-user-edit"></i>
            </a>
            <form action="{{ route('hr.employees.destroy', $emp->id) }}" method="POST" class="d-inline">
                @csrf @method('DELETE')
                <button class="btn btn-link text-danger p-0 m-0" onclick="return confirm('Delete this employee?')">
                    <i class="fas fa-trash"></i>
                </button>
            </form>
        </td>
    </tr>
@endforeach

@if($employees->isEmpty())
    <tr>
        <td colspan="8" class="text-center text-muted py-3">No employees found.</td>
    </tr>
@endif
