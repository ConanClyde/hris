@php
    echo view('admin.notices.index', [
        'notices' => $notices ?? null,
    ])->render();
@endphp

