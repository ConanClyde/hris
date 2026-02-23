<?php

namespace App\Features\Leave\Http\Controllers\HR;

use App\Features\Leave\Models\LeaveCredit;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class LeaveCreditController extends Controller
{
    public function index(Request $request)
    {
        $credits = LeaveCredit::with('employee')
            ->orderBy('employee_id')
            ->paginate(20)
            ->appends($request->query());

        $payload = ['credits' => $credits];

        if ($request->filled('view')) {
            $creditDetail = LeaveCredit::with(['employee', 'adjustments'])
                ->find($request->integer('view'));
            $payload['creditDetail'] = $creditDetail;
        }

        return Inertia::render('HR/LeaveCredits/Index', $payload);
    }

    public function show($id)
    {
        return redirect()->route('hr.leaveCredits.index', ['view' => $id]);
    }
}
