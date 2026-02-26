@php
    echo view('admin.notices.edit', [
        'notice' => $notice ?? null,
    ])->render();
@endphp

