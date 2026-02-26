@php
    echo view('admin.activity-logs.index', [
        'logs' => $logs ?? null,
        'actions' => $actions ?? [],
    ])->render();
@endphp

