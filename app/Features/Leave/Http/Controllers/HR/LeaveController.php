<?php

namespace App\Features\Leave\Http\Controllers\HR;

use App\Features\Employees\Models\Employee;
use App\Features\Leave\Enums\LeaveStatus;
use App\Features\Leave\Enums\LeaveType;
use App\Features\Leave\Models\LeaveApplication;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class LeaveController extends Controller
{
    public function index(Request $request)
    {
        $query = LeaveApplication::query();
        $this->applyFilters($query, $request);

        $applications = $query->orderByDesc('created_at')
            ->paginate(10)
            ->appends($request->query());

        $employees = Employee::query()
            ->orderBy('last_name')
            ->get()
            ->map(fn (Employee $e) => [
                'id' => (string) $e->id,
                'name' => $e->full_name,
            ]);

        return Inertia::render('HR/Leave/Index', [
            'applications' => $applications,
            'employees' => $employees,
            'types' => LeaveType::labels(),
            'statusOptions' => LeaveStatus::options(),
            'filters' => $request->only(['search', 'type', 'status']),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|string',
            'type' => 'required|string',
            'date_from' => 'required|date',
            'total_days' => 'required|numeric|min:0.5',
            'reason' => 'nullable|string',
        ]);

        $employee = Employee::find($validated['employee_id']);
        $employeeName = $employee ? $employee->full_name : $validated['employee_id'];

        LeaveApplication::create([
            'employee_id' => $validated['employee_id'],
            'employee_name' => $employeeName,
            'type' => $validated['type'],
            'date_from' => $validated['date_from'],
            'total_days' => $validated['total_days'],
            'reason' => $validated['reason'],
            'status' => 'pending',
        ]);

        return redirect()->route('hr.leave-applications.index')->with('success', 'Leave application submitted successfully.');
    }

    public function update(Request $request, $id)
    {
        $leave = LeaveApplication::findOrFail((int) $id);

        $validated = $request->validate([
            'employee_id' => 'required|string',
            'status' => 'required|in:pending,approved,rejected',
            'type' => 'required|string',
            'date_from' => 'required|date',
            'total_days' => 'required|numeric|min:0.5',
            'reason' => 'nullable|string',
        ]);

        $leave->update($validated);

        return redirect()->route('hr.leave-applications.index')->with('success', 'Leave application updated successfully.');
    }

    public function destroy($id)
    {
        $leave = LeaveApplication::findOrFail((int) $id);
        $leave->delete();

        return redirect()->route('hr.leave-applications.index')->with('success', 'Leave application deleted successfully.');
    }

    public function export(Request $request)
    {
        $query = LeaveApplication::query();
        $this->applyFilters($query, $request);

        $rows = $query->orderByDesc('created_at')->get();

        return response()->streamDownload(function () use ($rows) {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['Employee ID', 'Type', 'Start Date', 'Total Days', 'Status', 'Submitted']);

            foreach ($rows as $leave) {
                fputcsv($out, [
                    $leave->employee_id ?? '',
                    $leave->type ?? '',
                    optional($leave->date_from)?->toDateString() ?? '',
                    $leave->total_days ?? '',
                    $leave->status ?? '',
                    optional($leave->created_at)?->toDateString() ?? '',
                ]);
            }

            fclose($out);
        }, 'leave-applications.csv', ['Content-Type' => 'text/csv']);
    }

    public function destroyAttachment($id)
    {
        return response()->json(['success' => true, 'message' => 'Attachment deleted']);
    }

    private function applyFilters($query, Request $request): void
    {
        if ($request->filled('search')) {
            $search = strtolower((string) $request->input('search'));
            $query->where(function ($q) use ($search) {
                $q->whereRaw('LOWER(type) like ?', ["%{$search}%"])
                    ->orWhereRaw('LOWER(reason) like ?', ["%{$search}%"])
                    ->orWhereRaw('LOWER(employee_name) like ?', ["%{$search}%"])
                    ->orWhereRaw('LOWER(employee_id) like ?', ["%{$search}%"]);
            });
        }

        if ($request->filled('type')) {
            $query->where('type', (string) $request->input('type'));
        }

        if ($request->filled('status')) {
            $query->where('status', (string) $request->input('status'));
        }
    }
}
