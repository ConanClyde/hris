@php
    echo view('employee.training.index', [
        'paginatedTrainings' => $paginatedTrainings ?? null,
        'types' => $types ?? [],
    ])->render();
@endphp

