@php
    echo view('admin.notifications.index', [
        'notices' => $notices ?? null,
    ])->render();
@endphp

