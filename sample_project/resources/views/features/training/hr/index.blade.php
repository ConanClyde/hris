@php
    echo view('hr.training.index', [
        'trainings' => $trainings ?? null,
        'types' => $types ?? [],
        'categories' => $categories ?? [],
        'employees' => $employees ?? null,
    ])->render();
@endphp

