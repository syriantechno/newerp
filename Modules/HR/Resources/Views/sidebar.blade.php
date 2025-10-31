<!-- HR Module Sidebar -->
<li class="nav-item">
    <a data-bs-toggle="collapse" href="#hrMenu" class="nav-link" aria-controls="hrMenu" role="button" aria-expanded="false">
        <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
            <i class="ni ni-single-02 text-dark text-sm opacity-10"></i>
        </div>
        <span class="nav-link-text ms-1">HR Management</span>
    </a>

    <div class="collapse" id="hrMenu">
        <ul class="nav ms-4">
            <li class="nav-item"><a class="nav-link" href="{{ route('hr.employees.index') }}"> Employees </a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('hr.attendance.index') }}"> Attendance </a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('hr.leaves.index') }}"> Leaves </a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('hr.penalties.index') }}"> Penalties </a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('hr.evaluations.index') }}"> Evaluations </a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('hr.departments.index') }}"> Departments </a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('hr.designations.index') }}"> Designations </a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('hr.companies.index') }}"> Companies Management </a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('hr.shifts.index') }}"> Shifts & Holidays </a></li>
        </ul>
    </div>
</li>
