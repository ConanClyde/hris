@php
    echo view('employee.leave.index', [
        'paginatedApplications' => $paginatedApplications ?? null,
        'types' => $types ?? [],
        'statusOptions' => $statusOptions ?? [],
        'leaveCredits' => $leaveCredits ?? null,
    ])->render();
@endphp

