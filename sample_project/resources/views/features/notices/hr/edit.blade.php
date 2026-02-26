@php
    echo view('hr.notices.edit', [
        'notice' => $notice ?? null,
    ])->render();
@endphp

