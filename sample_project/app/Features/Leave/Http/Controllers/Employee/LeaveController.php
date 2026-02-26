<?php

namespace App\Features\Leave\Http\Controllers\Employee;

use App\Features\Employees\Models\Employee;
use App\Features\Leave\Enums\LeaveStatus;
use App\Features\Leave\Enums\LeaveType;
use App\Features\Leave\Http\Requests\StoreLeaveRequest;
use App\Features\Leave\Http\Requests\UpdateLeaveRequest;
use App\Features\Leave\Models\LeaveApplication;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;

class LeaveController extends Controller
{
    public function index(Request $request)
    {
        $employee = $this->getEmployeeForAuthUser();

        if (! $employee) {
            return redirect()->back()->with('error', 'Employee record not found.');
        }

        $query = LeaveApplication::forEmployee($employee->id);

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

        $page = (int) $request->input('page', 1);
        $perPage = 10;
        $paginator = $query
            ->orderByDesc('created_at')
            ->paginate($perPage, ['*'], 'page', $page)
            ->appends($request->query());

        $applications = collect($paginator->items())->map(function (LeaveApplication $leave) {
            return (object) [
                'id' => $leave->id,
                'type' => $leave->type,
                'date_from' => optional($leave->date_from)->toDateString(),
                'date_to' => optional($leave->date_to)->toDateString(),
                'total_days' => $leave->total_days,
                'reason' => $leave->reason,
                'status' => $leave->status,
                'created_at' => optional($leave->created_at)->toDateString(),
                'attachments' => $leave->attachments ?? [],
            ];
        });

        $paginatedApplications = new LengthAwarePaginator(
            $applications,
            $paginator->total(),
            $paginator->perPage(),
            $paginator->currentPage(),
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return view('features.leave.employee.index', [
            'paginatedApplications' => $paginatedApplications,
            'types' => LeaveType::labels(),
            'statusOptions' => LeaveStatus::options(),
            'leaveCredits' => $employee->leaveCredits()->get(),
        ]);
    }

    public function store(StoreLeaveRequest $request)
    {
        $employee = $this->getEmployeeForAuthUser();

        if (! $employee) {
            return redirect()->back()->with('error', 'Employee record not found.');
        }

        $validated = $request->validated();

        $attachments = [];
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('leave-attachments', 'public');
                $attachments[] = [
                    'name' => $file->getClientOriginalName(),
                    'path' => $path,
                    'size' => $file->getSize(),
                ];
            }
        }

        // Check for sufficient credits
        $credit = $employee->leaveCredits()->where('leave_type', $validated['leave_type'])->first();
        if ($credit && $credit->balance < $validated['total_days']) {
            return redirect()->back()->withErrors(['leave_type' => "Insufficient leave credits. Current balance: {$credit->balance}"])->withInput();
        }

        LeaveApplication::create([
            'employee_id' => $employee->id,
            'type' => $validated['leave_type'],
            'date_from' => $validated['date_from'],
            'date_to' => $validated['date_to'] ?? null,
            'total_days' => $validated['total_days'],
            'reason' => $validated['reason'],
            'status' => LeaveStatus::Pending->value,
            'attachments' => $attachments,
        ]);

        return redirect()->route('employee.leave-applications.index')->with('success', 'Leave application submitted successfully.');
    }

    public function update(UpdateLeaveRequest $request, $id)
    {
        $validated = $request->validated();

        $leave = LeaveApplication::findOrFail((int) $id);
        $leave->update([
            'type' => $validated['leave_type'],
            'date_from' => $validated['date_from'],
            'date_to' => $validated['date_to'] ?? null,
            'total_days' => $validated['total_days'],
            'reason' => $validated['reason'],
        ]);

        return redirect()->route('employee.leave-applications.index')->with('success', 'Leave application updated successfully.');
    }

    public function destroy($id)
    {
        $leave = LeaveApplication::findOrFail((int) $id);
        $leave->delete();

        return redirect()->route('employee.leave-applications.index')->with('success', 'Leave application cancelled/deleted successfully.');
    }

    public function export()
    {
        return redirect()->route('employee.leave-applications.index')->with('success', 'Leave applications exported to CSV.');
    }

    public function destroyAttachment($id)
    {
        return response()->json(['success' => true, 'message' => 'Attachment deleted']);
    }

    private function getEmployeeForAuthUser(): ?Employee
    {
        $user = Auth::user();
        if (! $user) {
            return null;
        }

        return Employee::where('user_id', $user->id)->first();
    }
}
