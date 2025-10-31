@php
    $active = [];
    if (!empty($filters['company_id'] ?? null))     $active[] = 'Company: '.$filters['company_id'];
    if (!empty($filters['department_id'] ?? null))  $active[] = 'Department: '.$filters['department_id'];
    if (!empty($filters['designation_id'] ?? null)) $active[] = 'Designation: '.$filters['designation_id'];
    if (!empty($filters['status'] ?? null))         $active[] = 'Status: '.ucfirst($filters['status']);
    if (!empty($filters['search'] ?? null))         $active[] = 'Search: "'.$filters['search'].'"';
@endphp

<strong>{{ $count }}</strong> employees
@if(count($active))
    <span class="ms-2"> ( {{ implode(', ', $active) }} )</span>
@else
    <span class="ms-2 text-muted"> (no filters applied) </span>
@endif
