@php
    echo view('dashboard.hr', [
        'totalUsers' => $totalUsers ?? 0,
        'pendingLeaveCount' => $pendingLeaveCount ?? 0,
        'pendingTrainingCount' => $pendingTrainingCount ?? 0,
        'pdsPendingCount' => $pdsPendingCount ?? 0,
    ])->render();
@endphp

