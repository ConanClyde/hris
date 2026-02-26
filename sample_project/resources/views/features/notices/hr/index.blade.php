@php
    echo view('hr.notices.index', [
        'notices' => $notices ?? null,
    ])->render();
@endphp

