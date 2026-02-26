@php
    echo view('dashboard.admin', [
        'totalUsers' => $totalUsers ?? 0,
        'pendingCount' => $pendingCount ?? 0,
        'usersByRole' => $usersByRole ?? [],
        'usersByStatus' => $usersByStatus ?? [],
    ])->render();
@endphp

