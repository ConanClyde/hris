<?php

namespace App\Features\Leave\Http\Controllers\Api;

use App\Features\Leave\Events\LeaveStatusUpdated;
use App\Features\Leave\Models\LeaveApplication;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LeaveApiController extends Controller
{
    public function index(Request $request)
    {
        $query = LeaveApplication::query();

        if ($search = (string) $request->query('search', '')) {
            $query->where(function ($q) use ($search) {
                $q->where('employee_name', 'like', "%{$search}%")
                    ->orWhere('employee_id', 'like', "%{$search}%")
                    ->orWhere('type', 'like', "%{$search}%")
                    ->orWhere('reason', 'like', "%{$search}%");
            });
        }

        if ($type = (string) $request->query('type', '')) {
            $query->where('type', $type);
        }

        if ($status = (string) $request->query('status', '')) {
            $query->where('status', $status);
        }

        $perPage = (int) $request->query('per_page', 10);

        return response()->json(
            $query->orderByDesc('created_at')->paginate($perPage)
        );
    }

    public function show(int $id)
    {
        $leave = LeaveApplication::findOrFail($id);

        return response()->json($leave);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'employee_id' => 'required|string|max:255',
            'employee_name' => 'nullable|string|max:255',
            'type' => 'required|string|max:255',
            'date_from' => 'required|date',
            'date_to' => 'nullable|date|after_or_equal:date_from',
            'total_days' => 'required|numeric|min:0.5',
            'reason' => 'nullable|string',
        ]);

        $leave = LeaveApplication::create($data);

        event(new LeaveStatusUpdated(
            id: $leave->id,
            employeeId: $leave->employee_id,
            employeeName: $leave->employee_name,
            status: $leave->status,
            type: $leave->type,
            dateFrom: $leave->date_from->toDateString(),
            totalDays: (float) $leave->total_days,
        ));

        return response()->json($leave, 201);
    }

    public function updateStatus(Request $request, int $id)
    {
        $data = $request->validate([
            'status' => 'required|string|in:pending,approved,rejected',
        ]);

        $leave = LeaveApplication::findOrFail($id);
        $leave->status = $data['status'];
        $leave->save();

        event(new LeaveStatusUpdated(
            id: $leave->id,
            employeeId: $leave->employee_id,
            employeeName: $leave->employee_name,
            status: $leave->status,
            type: $leave->type,
            dateFrom: $leave->date_from->toDateString(),
            totalDays: (float) $leave->total_days,
        ));

        return response()->json($leave);
    }
}
