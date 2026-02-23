<?php

namespace App\Features\Leave\Http\Controllers\Employee;

use App\Features\Leave\Models\LeaveApplication;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class LeaveController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $employeeId = $user?->employee?->employee_id ?? null;

        $query = LeaveApplication::query();
        if ($employeeId) {
            $query->where('employee_id', $employeeId);
        }

        $applications = $query->orderByDesc('created_at')
            ->paginate(10)
            ->appends($request->query());

        return Inertia::render('Employee/Leave/Index', [
            'applications' => $applications,
        ]);
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $employeeId = $user?->employee?->employee_id ?? null;

        $validated = $request->validate([
            'type' => 'required|string',
            'date_from' => 'required|date',
            'total_days' => 'required|numeric|min:0.5',
            'reason' => 'nullable|string',
        ]);

        LeaveApplication::create([
            'employee_id' => $employeeId,
            'type' => $validated['type'],
            'date_from' => $validated['date_from'],
            'total_days' => $validated['total_days'],
            'reason' => $validated['reason'],
            'status' => 'pending',
        ]);

        return redirect()->route('employee.leave-applications.index')->with('success', 'Leave application submitted.');
    }

    public function update(Request $request, $id)
    {
        $leave = LeaveApplication::findOrFail((int) $id);

        $validated = $request->validate([
            'type' => 'required|string',
            'date_from' => 'required|date',
            'total_days' => 'required|numeric|min:0.5',
            'reason' => 'nullable|string',
        ]);

        $leave->update($validated);

        return redirect()->route('employee.leave-applications.index')->with('success', 'Leave application updated.');
    }

    public function destroy($id)
    {
        LeaveApplication::findOrFail((int) $id)->delete();

        return redirect()->route('employee.leave-applications.index')->with('success', 'Leave application deleted.');
    }

    public function export(Request $request)
    {
        $user = Auth::user();
        $employeeId = $user?->employee?->employee_id ?? null;

        $rows = LeaveApplication::where('employee_id', $employeeId)->orderByDesc('created_at')->get();

        return response()->streamDownload(function () use ($rows) {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['Type', 'Start Date', 'Total Days', 'Status', 'Submitted']);
            foreach ($rows as $leave) {
                fputcsv($out, [
                    $leave->type ?? '',
                    optional($leave->date_from)?->toDateString() ?? '',
                    $leave->total_days ?? '',
                    $leave->status ?? '',
                    optional($leave->created_at)?->toDateString() ?? '',
                ]);
            }
            fclose($out);
        }, 'my-leave-applications.csv', ['Content-Type' => 'text/csv']);
    }

    public function destroyAttachment($id)
    {
        return response()->json(['success' => true, 'message' => 'Attachment deleted']);
    }
}
