@php
    echo view('hr.leave.index', [
        'paginatedApplications' => $paginatedApplications ?? null,
        'employees' => $employees ?? null,
        'types' => $types ?? [],
        'statusOptions' => $statusOptions ?? [],
    ])->render();
@endphp

