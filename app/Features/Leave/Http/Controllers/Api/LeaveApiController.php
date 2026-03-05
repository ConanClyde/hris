<?php

namespace App\Features\Leave\Http\Controllers\Api;

use App\Features\Employees\Models\Employee;
use App\Features\Leave\Events\LeaveStatusUpdated;
use App\Features\Leave\Http\Requests\StoreLeaveRequest;
use App\Features\Leave\Http\Requests\UpdateLeaveStatusRequest;
use App\Features\Leave\Models\LeaveApplication;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LeaveApiController extends Controller
{
    public function index(Request $request): JsonResponse
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

    public function show(int $id): JsonResponse
    {
        $leave = LeaveApplication::findOrFail($id);

        return response()->json($leave);
    }

    public function store(StoreLeaveRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $employee = is_numeric($validated['employee_id'] ?? null)
            ? Employee::find((int) $validated['employee_id'])
            : null;

        $leave = LeaveApplication::create(array_merge(
            $validated,
            [
                'status' => 'pending',
                'employee_fk' => $employee?->id,
                'employee_name' => $validated['employee_name'] ?? $employee?->full_name,
            ]
        ));

        event(new LeaveStatusUpdated(
            id: $leave->id,
            employeeId: $leave->employee_id,
            employeeName: $leave->employee_name,
            status: $leave->status ?? 'pending',
            type: $leave->type,
            dateFrom: $leave->date_from->toDateString(),
            totalDays: (float) $leave->total_days,
        ));

        return response()->json($leave, 201);
    }

    public function updateStatus(UpdateLeaveStatusRequest $request, int $id): JsonResponse
    {
        $leave = LeaveApplication::findOrFail($id);
        $leave->status = $request->validated()['status'];
        $leave->save();

        event(new LeaveStatusUpdated(
            id: $leave->id,
            employeeId: $leave->employee_id,
            employeeName: $leave->employee_name,
            status: $leave->status ?? 'pending',
            type: $leave->type,
            dateFrom: $leave->date_from->toDateString(),
            totalDays: (float) $leave->total_days,
        ));

        return response()->json($leave);
    }
}
