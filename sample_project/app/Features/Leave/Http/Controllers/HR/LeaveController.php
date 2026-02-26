<?php

namespace App\Features\Leave\Http\Controllers\HR;

use App\Features\ActivityLogs\Services\ActivityLogger;
use App\Features\Employees\Models\Employee;
use App\Features\Leave\Enums\LeaveStatus;
use App\Features\Leave\Enums\LeaveType;
use App\Features\Leave\Events\LeaveStatusUpdated;
use App\Features\Leave\Http\Requests\StoreLeaveRequest;
use App\Features\Leave\Http\Requests\UpdateLeaveRequest;
use App\Features\Leave\Models\LeaveApplication;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LeaveController extends Controller
{
    public function __construct(protected ActivityLogger $logger) {}

    public function index(Request $request)
    {
        $employees = $this->employees();

        $query = LeaveApplication::with('employee');

        $this->applyFilters($query, $request);

        $paginatedApplications = $query
            ->orderByDesc('created_at')
            ->paginate(10)
            ->appends($request->query());

        return view('features.leave.hr.index', [
            'paginatedApplications' => $paginatedApplications,
            'employees' => $employees,
            'types' => LeaveType::labels(),
            'statusOptions' => LeaveStatus::options(),
        ]);
    }

    public function store(StoreLeaveRequest $request)
    {
        $validated = $request->validated();

        $leave = LeaveApplication::create([
            'employee_id' => $validated['employee_id'],
            'type' => $validated['leave_type'],
            'date_from' => $validated['date_from'],
            'total_days' => $validated['total_days'],
            'reason' => $validated['reason'],
            'status' => LeaveStatus::Pending->value,
        ]);

        $this->logger->logCreate('leave_application', $leave->id, [
            'employee_id' => $validated['employee_id'],
            'type' => $validated['leave_type'],
            'date_from' => $validated['date_from'],
            'total_days' => $validated['total_days'],
        ]);

        return redirect()->route('hr.leave-applications.index')->with('success', 'Leave application submitted successfully.');
    }

    public function update(UpdateLeaveRequest $request, $id)
    {
        $validated = $request->validated();

        $leave = LeaveApplication::findOrFail((int) $id);

        $employees = $this->employees();
        $employee = $employees->firstWhere('id', $validated['employee_id']);

        DB::transaction(function () use ($leave, $validated) {
            $originalStatus = $leave->status;

            $leave->fill([
                'employee_id' => $validated['employee_id'],
                'status' => $validated['status'],
                'type' => $validated['leave_type'],
                'date_from' => $validated['date_from'],
                'total_days' => $validated['total_days'],
                'reason' => $validated['reason'],
            ]);

            // Deduct credits if status changes to Approved
            if ($leave->isDirty('status') && $leave->status === LeaveStatus::Approved->value && $originalStatus !== LeaveStatus::Approved->value) {
                // Fetch fresh employee to get credits
                $empModel = Employee::find($validated['employee_id']);
                $credit = $empModel->leaveCredits()->where('leave_type', $leave->type)->lockForUpdate()->first();

                if ($credit) {
                    if ($credit->balance < $leave->total_days) {
                        throw \Illuminate\Validation\ValidationException::withMessages([
                            'status' => "Insufficient leave credits for {$empModel->full_name}. Balance: {$credit->balance}, Required: {$leave->total_days}.",
                        ]);
                    }
                    $credit->adjust(-$leave->total_days, "Approved Leave Application #{$leave->id}", auth()->id());
                }
            }

            $leave->save();
        });

        $this->logger->logStatusChange('leave_application', $leave->id, 'pending', $validated['status']);

        event(new LeaveStatusUpdated(
            id: (int) $leave->id,
            employeeId: (string) $leave->employee_id,
            employeeName: $employee->name ?? null,
            status: (string) $leave->status,
            type: (string) $leave->type,
            dateFrom: (string) $leave->date_from->toDateString(),
            totalDays: (float) $leave->total_days,
        ));

        return redirect()->route('hr.leave-applications.index')->with('success', 'Leave application updated successfully.');
    }

    public function destroy($id)
    {
        $leave = LeaveApplication::findOrFail((int) $id);
        $leave->delete();

        $this->logger->logDelete('leave_application', $leave->id);

        return redirect()->route('hr.leave-applications.index')->with('success', 'Leave application deleted successfully.');
    }

    public function export(Request $request)
    {
        $query = LeaveApplication::query();
        $this->applyFilters($query, $request);

        $rows = $query->orderByDesc('created_at')->get();

        $this->logger->logExport('leave_application');

        return response()->streamDownload(function () use ($rows) {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['Employee ID', 'Type', 'Start Date', 'Total Days', 'Status', 'Submitted']);

            foreach ($rows as $leave) {
                fputcsv($out, [
                    $leave->employee_id ?? '',
                    $leave->type ?? '',
                    optional($leave->date_from)->toDateString() ?? '',
                    $leave->total_days ?? '',
                    $leave->status ?? '',
                    optional($leave->created_at)->toDateString() ?? '',
                ]);
            }

            fclose($out);
        }, 'leave-applications.csv', [
            'Content-Type' => 'text/csv',
        ]);
    }

    public function destroyAttachment($id)
    {
        return response()->json(['success' => true, 'message' => 'Attachment deleted']);
    }

    // ── Private Helpers ────────────────────────────

    /**
     * Apply search/type/status filters to query (DRY — shared between index & export).
     */
    private function applyFilters($query, Request $request): void
    {
        if ($request->filled('search')) {
            $search = strtolower((string) $request->input('search'));
            $query->where(function ($q) use ($search) {
                $q->whereRaw('LOWER(type) like ?', ["%{$search}%"])
                    ->orWhereRaw('LOWER(reason) like ?', ["%{$search}%"]);
            });
        }

        if ($request->filled('type')) {
            $query->where('type', (string) $request->input('type'));
        }

        if ($request->filled('status')) {
            $query->where('status', (string) $request->input('status'));
        }
    }

    private function employees()
    {
        return Employee::where('status', 'active')
            ->select('id', 'first_name', 'last_name', 'middle_name')
            ->get()
            ->map(fn ($e) => (object) [
                'id' => $e->id,
                'name' => trim($e->first_name.' '.($e->middle_name ? $e->middle_name.' ' : '').$e->last_name),
            ]);
    }
}
