@php
    echo view('admin.users.index', [
        'users' => $users ?? null,
        'pendingCount' => $pendingCount ?? 0,
    ])->render();
@endphp

