<?php

namespace App\Features\Leave\Http\Controllers\HR;

use App\Features\Leave\Models\LeaveCredit;
use App\Features\Users\Enums\UserRole;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class LeaveCreditController extends Controller
{
    public function index(Request $request)
    {
        $query = LeaveCredit::with(['employee.user']);

        // HR users cannot see leave credits for admin accounts
        if (auth()->user()?->isHr()) {
            $query->whereHas('employee.user', function ($q) {
                $q->where('role', '!=', UserRole::Admin->value);
            });
        }

        $appendQuery = collect($request->query())->reject(fn ($v) => $v === 'all')->all();
        $credits = $query->orderBy('employee_id')
            ->paginate(10)
            ->appends($appendQuery);

        $payload = ['credits' => $credits];

        if ($request->filled('view')) {
            $creditDetail = LeaveCredit::with(['employee.user', 'adjustments'])
                ->find($request->integer('view'));

            // Check if HR is trying to view an admin's leave credits
            if (auth()->user()?->isHr() && $creditDetail?->employee?->user?->isAdmin()) {
                return redirect()->route('hr.leave-credits.index')->with('error', 'HR users cannot view leave credits for admin accounts.');
            }

            $payload['creditDetail'] = $creditDetail;
        }

        return Inertia::render('HR/LeaveCredits/Index', $payload);
    }

    public function show($id)
    {
        return redirect()->route('hr.leaveCredits.index', ['view' => $id]);
    }
}
