<?php

namespace App\Features\Leave\Http\Controllers\HR;

use App\Events\LeaveApproved;
use App\Events\LeaveRejected;
use App\Events\LeaveSubmitted;
use App\Features\Employees\Models\Employee;
use App\Features\Leave\Enums\LeaveStatus;
use App\Features\Leave\Enums\LeaveType;
use App\Features\Leave\Models\LeaveApplication;
use App\Features\Leave\Models\LeaveCredit;
use App\Features\Users\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Mail\LeaveApprovedMail;
use App\Mail\LeaveRejectedMail;
use App\Mail\LeaveSubmittedMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;

class LeaveController extends Controller
{
    public function index(Request $request)
    {
        $query = LeaveApplication::query()
            ->leftJoin('employees', 'leave_applications.employee_id', '=', 'employees.id')
            ->leftJoin('users', 'employees.user_id', '=', 'users.id')
            ->select([
                'leave_applications.*',
                'users.email as user_email',
                'users.name as user_name',
                'users.role as user_role',
                'employees.first_name as employee_first_name',
                'employees.last_name as employee_last_name',
            ]);

        // HR users cannot see leave applications for admin accounts
        if (Auth::user()?->isHr()) {
            $query->where('users.role', '!=', UserRole::Admin->value);
        }

        $this->applyFilters($query, $request);

        $appendQuery = collect($request->query())->reject(fn ($v) => $v === 'all')->all();
        $applications = $query->orderByDesc('created_at')
            ->paginate(10)
            ->appends($appendQuery);

        $employeesQuery = Employee::query()->with(['user']);

        // HR users cannot manage admin accounts
        if (Auth::user()?->isHr()) {
            $employeesQuery->whereHas('user', function ($q) {
                $q->where('role', '!=', UserRole::Admin->value);
            });
        }

        $employees = $employeesQuery->orderBy('last_name')
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

        $employee = Employee::with('user')->find($validated['employee_id']);

        // Prevent HR from creating leave for Admins
        if (Auth::user()?->isHr() && $employee?->user?->isAdmin()) {
            return redirect()->back()->with('error', 'HR users cannot manage leave applications for admin accounts.');
        }

        $employeeName = $employee ? $employee->full_name : $validated['employee_id'];

        $leave = LeaveApplication::create([
            'employee_id' => $validated['employee_id'],
            'employee_name' => $employeeName,
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
            ->whereIn('role', [UserRole::Admin->value, UserRole::Hr->value])
            ->where('is_active', true)
            ->pluck('email')
            ->all();

        if ($hrRecipients !== []) {
            Mail::to($hrRecipients)->queue(new LeaveSubmittedMail($leave));
        }

        return redirect()->route('hr.leave-applications.index')->with('success', 'Leave application submitted successfully.');
    }

    public function update(Request $request, $id)
    {
        $leave = LeaveApplication::with('employee.user')->findOrFail((int) $id);

        // Prevent HR from managing Admin leave applications
        if (Auth::user()?->isHr() && $leave->employee?->user?->isAdmin()) {
            return redirect()->back()->with('error', 'HR users cannot manage leave applications for admin accounts.');
        }

        $validated = $request->validate([
            'employee_id' => 'required|string',
            'status' => 'required|in:pending,approved,rejected',
            'type' => 'required|string',
            'date_from' => 'required|date',
            'total_days' => 'required|numeric|min:0.5',
            'reason' => 'nullable|string',
        ]);

        // Store original status to check if it's being changed to approved
        $originalStatus = $leave->status;

        // Update the leave application first (always)
        $leave->update($validated);

        // If status was changed to approved, handle credit deduction separately
        if ($originalStatus !== 'approved' && $validated['status'] === 'approved') {
            // Find the corresponding leave credit
            $leaveCredit = LeaveCredit::where('employee_id', $leave->employee_id)
                ->where('leave_type', $leave->type)
                ->first();

            if ($leaveCredit) {
                // Deduct the leave days from the credit
                $leaveCredit->adjust(
                    amount: -$leave->total_days, // Negative to deduct
                    reason: "Leave application #{$leave->id} approved",
                    userId: Auth::id()
                );
            }
            // Even if no credit exists, we still want to approve the leave
            // (the business logic might allow this, or require credit setup beforehand)

            // Broadcast leave approved event
            $approverName = Auth::user()?->full_name ?? 'HR';
            broadcast(new LeaveApproved($leave, $approverName))->toOthers();

            // Email employee about the approved leave
            $employeeUser = $leave->employee?->user;
            $email = $employeeUser?->email ?? null;

            if ($email !== null) {
                Mail::to($email)->queue(new LeaveApprovedMail($leave, $approverName));
            }
        }

        // If status was changed to rejected
        if ($originalStatus !== 'rejected' && $validated['status'] === 'rejected') {
            $approverName = Auth::user()?->full_name ?? 'HR';

            broadcast(new LeaveRejected($leave, $approverName))->toOthers();

            // Email employee about the rejected leave
            $employeeUser = $leave->employee?->user;
            $email = $employeeUser?->email ?? null;

            if ($email !== null) {
                $reason = $validated['reason'] ?? null;
                Mail::to($email)->queue(new LeaveRejectedMail($leave, $approverName, $reason));
            }
        }

        return redirect()->route('hr.leave-applications.index')->with('success', 'Leave application updated successfully.');
    }

    public function destroy($id)
    {
        $leave = LeaveApplication::with('employee.user')->findOrFail((int) $id);

        // Prevent HR from deleting Admin leave applications
        if (Auth::user()?->isHr() && $leave->employee?->user?->isAdmin()) {
            return redirect()->back()->with('error', 'HR users cannot delete leave applications for admin accounts.');
        }

        $leave->delete();

        return redirect()->route('hr.leave-applications.index')->with('success', 'Leave application deleted successfully.');
    }

    public function export(Request $request)
    {
        $query = LeaveApplication::query();
        $this->applyFilters($query, $request);

        return response()->streamDownload(function () use ($query) {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['Employee ID', 'Type', 'Start Date', 'Total Days', 'Status', 'Submitted']);
            $query->orderByDesc('created_at')->chunkById(500, function ($rows) use ($out): void {
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
            });

            fclose($out);
        }, 'leave-applications.csv', ['Content-Type' => 'text/csv']);
    }

    public function destroyAttachment($id)
    {
        $leave = LeaveApplication::find($id);

        if (! $leave) {
            return response()->json(['success' => false, 'message' => 'Attachment not found'], 404);
        }

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
