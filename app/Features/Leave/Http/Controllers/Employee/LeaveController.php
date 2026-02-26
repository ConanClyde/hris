<?php

namespace App\Features\Leave\Http\Controllers\Employee;

use App\Events\LeaveCancelled;
use App\Events\LeaveSubmitted;
use App\Features\Leave\Enums\LeaveStatus;
use App\Features\Leave\Enums\LeaveType;
use App\Features\Leave\Models\LeaveApplication;
use App\Http\Controllers\Controller;
use App\Mail\LeaveCancelledMail;
use App\Mail\LeaveSubmittedMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;

class LeaveController extends Controller
{
    private function getEmployeeId()
    {
        return Auth::user()?->employee?->id;
    }

    public function index(Request $request)
    {
        $employeeId = $this->getEmployeeId();

        if (! $employeeId) {
            return Inertia::render('Employee/Leave/Index', [
                'applications' => [
                    'data' => [],
                    'current_page' => 1,
                    'last_page' => 1,
                    'links' => [],
                ],
                'types' => LeaveType::labels(),
                'statusOptions' => LeaveStatus::options(),
                'filters' => $request->only(['type', 'status']),
            ]);
        }

        $query = LeaveApplication::query()
            ->where('employee_id', $employeeId);

        if ($request->filled('type')) {
            $query->where('type', $request->input('type'));
        }
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        $appendQuery = collect($request->query())->reject(fn ($v) => $v === 'all')->all();
        $applications = $query->orderByDesc('created_at')
            ->paginate(10)
            ->appends($appendQuery);

        return Inertia::render('Employee/Leave/Index', [
            'applications' => $applications,
            'types' => LeaveType::labels(),
            'statusOptions' => LeaveStatus::options(),
            'filters' => $request->only(['type', 'status']),
        ]);
    }

    public function store(Request $request)
    {
        $employeeId = $this->getEmployeeId();
        if (! $employeeId) {
            abort(403, 'User is not linked to an employee record.');
        }

        $validated = $request->validate([
            'type' => 'required|string',
            'date_from' => 'required|date',
            'total_days' => 'required|numeric|min:0.5',
            'reason' => 'nullable|string',
        ]);

        $leave = LeaveApplication::create([
            'employee_id' => $employeeId,
            'employee_name' => Auth::user()->name,
            'type' => $validated['type'],
            'date_from' => $validated['date_from'],
            'total_days' => $validated['total_days'],
            'reason' => $validated['reason'],
            'status' => 'pending',
        ]);

        // Broadcast leave submitted event
        broadcast(new LeaveSubmitted($leave))->toOthers();

        // Email HR / Admin users about the new leave application
        $hrRecipients = User::query()
            ->whereIn('role', ['admin', 'hr'])
            ->where('is_active', true)
            ->pluck('email')
            ->all();

        if ($hrRecipients !== []) {
            Mail::to($hrRecipients)->queue(new LeaveSubmittedMail($leave));
        }

        return redirect()->route('employee.leave-applications.index')->with('success', 'Leave application submitted.');
    }

    public function update(Request $request, $id)
    {
        $employeeId = $this->getEmployeeId();
        $leave = LeaveApplication::where('id', $id)->where('employee_id', $employeeId)->firstOrFail();

        // If trying to cancel
        if ($request->input('status') === 'cancelled') {
            if ($leave->status === 'approved' || $leave->status === 'rejected') {
                return back()->with('error', 'Cannot cancel processed leave application.');
            }
            $leave->update(['status' => 'cancelled']);

            // Broadcast leave cancelled event
            broadcast(new LeaveCancelled($leave))->toOthers();

            // Email employee about the cancelled leave
            $employeeUser = $leave->employee?->user;
            $email = $employeeUser?->email ?? null;

            if ($email !== null) {
                $cancelledAt = now()->toDayDateTimeString();
                Mail::to($email)->queue(new LeaveCancelledMail($leave, $cancelledAt));
            }

            return redirect()->route('employee.leave-applications.index')->with('success', 'Leave application cancelled.');
        }

        // Allow editing details only if pending
        if ($leave->status !== 'pending') {
            return back()->with('error', 'Cannot edit leave application that is no longer pending.');
        }

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
        $employeeId = $this->getEmployeeId();
        $leave = LeaveApplication::where('id', $id)->where('employee_id', $employeeId)->firstOrFail();

        if ($leave->status !== 'pending') {
            return back()->with('error', 'Cannot delete leave application that is no longer pending.');
        }

        $leave->delete();

        return redirect()->route('employee.leave-applications.index')->with('success', 'Leave application deleted.');
    }

    public function export(Request $request)
    {
        $employeeId = $this->getEmployeeId();

        $query = LeaveApplication::where('employee_id', $employeeId)->orderByDesc('created_at');

        return response()->streamDownload(function () use ($query) {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['Type', 'Start Date', 'Total Days', 'Status', 'Submitted']);
            $query->chunkById(500, function ($rows) use ($out): void {
                foreach ($rows as $leave) {
                    fputcsv($out, [
                        $leave->type ?? '',
                        optional($leave->date_from)?->toDateString() ?? '',
                        $leave->total_days ?? '',
                        $leave->status ?? '',
                        optional($leave->created_at)?->toDateString() ?? '',
                    ]);
                }
            });
            fclose($out);
        }, 'my-leave-applications.csv', ['Content-Type' => 'text/csv']);
    }

    public function destroyAttachment($id)
    {
        $employeeId = $this->getEmployeeId();
        $leave = LeaveApplication::where('id', $id)->where('employee_id', $employeeId)->first();

        if (! $leave) {
            return response()->json(['success' => false, 'message' => 'Attachment not found or unauthorized'], 404);
        }

        if ($leave->status !== 'pending') {
            return response()->json(['success' => false, 'message' => 'Cannot delete attachment from a non-pending leave application'], 403);
        }

        return response()->json(['success' => true, 'message' => 'Attachment deleted']);
    }
}
