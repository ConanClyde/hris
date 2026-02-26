<?php

namespace App\Features\Training\Http\Controllers\HR;

use App\Events\TrainingAssigned;
use App\Events\TrainingCompleted;
use App\Features\Employees\Models\Employee;
use App\Features\Training\Models\Training;
use App\Features\Users\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Mail\TrainingAssignedMail;
use App\Mail\TrainingCompletedMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;

class TrainingController extends Controller
{
    public function index(Request $request)
    {
        $query = Training::query();

        // HR users cannot see training for admin accounts
        if (Auth::user()?->isHr()) {
            $query->whereHas('employee.user', function ($q) {
                $q->where('role', '!=', UserRole::Admin->value);
            });
        }

        if ($request->filled('search')) {
            $search = strtolower((string) $request->input('search'));
            $query->where(function ($q) use ($search) {
                $q->whereRaw('LOWER(title) like ?', ["%{$search}%"])
                    ->orWhereRaw('LOWER(provider) like ?', ["%{$search}%"]);
            });
        }

        if ($request->filled('status')) {
            $query->where('status', (string) $request->input('status'));
        }
        if ($request->filled('type')) {
            $query->where('type', (string) $request->input('type'));
        }
        if ($request->filled('category')) {
            $query->where('category', (string) $request->input('category'));
        }

        $appendQuery = collect($request->query())->reject(fn ($v) => $v === 'all')->all();
        $trainings = $query->orderByDesc('created_at')
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

        return Inertia::render('HR/Training/Index', [
            'trainings' => $trainings,
            'employees' => $employees,
            'statusOptions' => ['pending' => 'Pending', 'approved' => 'Approved', 'rejected' => 'Rejected'],
            'filters' => $request->only(['search', 'status', 'type', 'category']),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|string',
            'title' => 'required|string|max:255',
            'provider' => 'nullable|string|max:255',
            'date_from' => 'required|date',
            'date_to' => 'nullable|date|after_or_equal:date_from',
            'hours' => 'nullable|numeric|min:0',
            'type' => 'nullable|string|max:255',
            'category' => 'nullable|string|max:255',
            'status' => 'in:pending,approved,rejected',
        ]);

        // Find the employee to get the user ID for broadcasting
        $employee = Employee::with('user')->find($validated['employee_id']);

        // Prevent HR from assigning training to Admins
        if (Auth::user()?->isHr() && $employee?->user?->isAdmin()) {
            return redirect()->back()->with('error', 'HR users cannot manage training for admin accounts.');
        }

        $training = Training::create($validated);

        if ($employee && $employee->user_id) {
            broadcast(new TrainingAssigned($training, $employee->user_id))->toOthers();

            $user = $employee->user;
            if ($user && $user->email) {
                $assignedBy = Auth::user()?->full_name ?? Auth::user()?->name ?? 'HR';
                Mail::to($user->email)->queue(
                    new TrainingAssignedMail($training, $employee->full_name, $assignedBy)
                );
            }
        }

        return redirect()->route('hr.training.index')->with('success', 'Training record created.');
    }

    public function update(Request $request, $id)
    {
        $training = Training::findOrFail((int) $id);

        $validated = $request->validate([
            'employee_id' => 'required|string',
            'title' => 'required|string|max:255',
            'provider' => 'nullable|string|max:255',
            'date_from' => 'required|date',
            'date_to' => 'nullable|date|after_or_equal:date_from',
            'time_from' => 'nullable|string|max:20',
            'time_to' => 'nullable|string|max:20',
            'hours' => 'nullable|numeric|min:0',
            'type' => 'nullable|string|max:255',
            'category' => 'nullable|string|max:255',
            'fee' => 'nullable|numeric|min:0',
            'participants' => 'nullable|string|max:255',
            'status' => 'in:pending,approved,rejected',
        ]);

        // Find the employee to get the user ID for broadcasting
        $employee = Employee::with('user')->find($validated['employee_id']);

        // Prevent HR from updating training for Admins
        if (Auth::user()?->isHr() && $employee?->user?->isAdmin()) {
            return redirect()->back()->with('error', 'HR users cannot manage training for admin accounts.');
        }

        $oldStatus = $training->status;
        $training->update($validated);

        // Broadcast training completed event if status was changed to approved
        if ($oldStatus !== 'approved' && $validated['status'] === 'approved') {
            if ($employee && $employee->user_id) {
                broadcast(new TrainingCompleted($training, $employee->user_id))->toOthers();

                $user = $employee->user;
                if ($user && $user->email) {
                    Mail::to($user->email)->queue(
                        new TrainingCompletedMail($training, $employee->full_name)
                    );
                }
            }
        }

        return redirect()->route('hr.training.index')->with('success', 'Training record updated.');
    }

    public function destroy($id)
    {
        $training = Training::with('employee.user')->findOrFail((int) $id);

        // Prevent HR from deleting training for Admins
        if (Auth::user()?->isHr() && $training->employee?->user?->isAdmin()) {
            return redirect()->back()->with('error', 'HR users cannot delete training for admin accounts.');
        }

        $training->delete();

        return redirect()->route('hr.training.index')->with('success', 'Training record deleted.');
    }
}
