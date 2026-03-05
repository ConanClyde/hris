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
use App\Notifications\SystemNotification;
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
        $trainings = $query->with(['employee.user'])
            ->orderByDesc('created_at')
            ->paginate(10)
            ->appends($appendQuery)
            ->through(fn ($training) => [
                'id' => $training->id,
                'employee_id' => $training->employee_id,
                'user_id' => $training->employee?->user?->id,
                'avatar' => $training->employee?->user?->avatar
                    ? asset('storage/'.$training->employee->user->avatar).'?v='.$training->employee->user->updated_at?->timestamp
                    : null,
                'employee_name' => $training->employee_name,
                'title' => $training->title,
                'date_from' => $training->date_from,
                'date_to' => $training->date_to,
                'time_from' => $training->time_from,
                'time_to' => $training->time_to,
                'hours' => $training->hours,
                'type' => $training->type,
                'provider' => $training->provider,
                'category' => $training->category,
                'fee' => $training->fee,
                'participants' => $training->participants,
                'status' => $training->status,
                'created_at' => $training->created_at,
            ]);

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

        // Prevent HR from assigning training to Admins
        if (Auth::user()?->isHr() && $employee?->user?->isAdmin()) {
            return redirect()->back()->with('error', 'HR users cannot manage training for admin accounts.');
        }

        $training = Training::create(array_merge($validated, [
            'employee_name' => $employee?->full_name ?? ($validated['employee_name'] ?? null),
        ]));

        if ($employee) {
            $training->employee_fk = $employee->id;
            $training->save();
        }

        if ($employee && $employee->user_id) {
            broadcast(new TrainingAssigned($training, $employee->user_id))->toOthers();

            $user = $employee->user;
            if ($user) {
                $assignedBy = Auth::user()?->full_name ?? Auth::user()?->name ?? 'HR';
                $user->notify(new SystemNotification(
                    type: 'info',
                    title: 'Training Assigned',
                    message: "You have been assigned: {$training->title}.",
                    data: [
                        'redirect_url' => '/employee/training',
                        'training_id' => $training->id,
                    ],
                    actor: Auth::user()
                        ? ['id' => Auth::id(), 'name' => $assignedBy, 'avatar' => Auth::user()?->avatar]
                        : null,
                ));
            }
            if ($user && $user->email) {
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
        $training->update(array_merge($validated, [
            'employee_fk' => is_numeric($validated['employee_id'] ?? null) ? (int) $validated['employee_id'] : null,
        ]));

        // Broadcast training completed event if status was changed to approved
        if ($oldStatus !== 'approved' && $validated['status'] === 'approved') {
            if ($employee && $employee->user_id) {
                broadcast(new TrainingCompleted($training, $employee->user_id))->toOthers();

                $user = $employee->user;
                if ($user) {
                    $user->notify(new SystemNotification(
                        type: 'success',
                        title: 'Training Completed',
                        message: "Your training \"{$training->title}\" has been marked as completed.",
                        data: [
                            'redirect_url' => '/employee/training',
                            'training_id' => $training->id,
                        ],
                        actor: Auth::user()
                            ? ['id' => Auth::id(), 'name' => Auth::user()?->full_name ?? 'HR', 'avatar' => Auth::user()?->avatar]
                            : null,
                    ));
                }
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
