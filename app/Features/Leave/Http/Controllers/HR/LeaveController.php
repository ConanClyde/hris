<?php

namespace App\Features\Leave\Http\Controllers\HR;

use App\Events\LeaveApproved;
use App\Events\LeaveRejected;
use App\Events\LeaveSubmitted;
use App\Features\Employees\Models\Employee;
use App\Features\Leave\Enums\LeaveStatus;
use App\Features\Leave\Enums\LeaveType;
use App\Features\Leave\Models\LeaveApplication;
use App\Features\Leave\Services\LeaveCreditEngine;
use App\Features\Users\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Mail\LeaveApprovedMail;
use App\Mail\LeaveRejectedMail;
use App\Mail\LeaveSubmittedMail;
use App\Models\User;
use App\Notifications\SystemNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class LeaveController extends Controller
{
    public function index(Request $request)
    {
        $query = LeaveApplication::query()
            ->leftJoin('employees', 'leave_applications.employee_fk', '=', 'employees.id')
            ->leftJoin('users', 'employees.user_id', '=', 'users.id')
            ->select([
                'leave_applications.*',
                'users.id as user_id',
                'users.email as user_email',
                'users.name as user_name',
                'users.avatar as user_avatar',
                'users.updated_at as user_updated_at',
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

        $applications->getCollection()->transform(function ($app) {
            $avatarPath = $app->user_avatar ?? null;
            $updatedAt = $app->user_updated_at ?? null;

            $app->avatar = $avatarPath
                ? asset('storage/'.$avatarPath).'?v='.(optional($updatedAt)->timestamp)
                : null;

            return $app;
        });

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
            'employee_id' => ['required', 'integer', Rule::exists('employees', 'id')],
            'type' => ['required', 'string', Rule::in(LeaveType::labels())],
            'date_from' => 'required|date',
            'total_days' => 'required|numeric|min:0.5',
            'reason' => 'nullable|string|max:1000',
            'attachments' => 'nullable|array|max:5',
            'attachments.*' => 'file|mimes:pdf,jpg,jpeg,png|max:51200',
        ]);

        $employee = Employee::with('user')->find((int) $validated['employee_id']);

        // Prevent HR from creating leave for Admins
        if (Auth::user()?->isHr() && $employee?->user?->isAdmin()) {
            return redirect()->back()->with('error', 'HR users cannot manage leave applications for admin accounts.');
        }

        $employeeName = $employee ? $employee->full_name : $validated['employee_id'];

        $leave = LeaveApplication::create([
            'employee_id' => (string) $validated['employee_id'],
            'employee_fk' => $employee?->id,
            'employee_name' => $employeeName,
            'type' => $validated['type'],
            'date_from' => $validated['date_from'],
            'total_days' => $validated['total_days'],
            'reason' => $validated['reason'],
            'status' => 'pending',
        ]);

        // Broadcast leave submitted event
        broadcast(new LeaveSubmitted($leave))->toOthers();

        $actor = Auth::user();
        $actorPayload = $actor
            ? [
                'id' => $actor->id,
                'name' => $actor->full_name,
                'avatar' => $actor->avatar,
            ]
            : null;

        User::query()
            ->whereIn('role', [UserRole::Admin->value, UserRole::Hr->value])
            ->where('is_active', true)
            ->each(function (User $recipient) use ($leave, $actorPayload): void {
                $recipient->notify(new SystemNotification(
                    type: 'info',
                    title: 'Leave Submitted',
                    message: "{$leave->employee_name} submitted a leave request.",
                    data: [
                        'redirect_url' => '/hr/leave-applications?status=pending',
                        'leave_id' => $leave->id,
                    ],
                    actor: $actorPayload,
                ));
            });

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

        $creditEngine = new LeaveCreditEngine;

        // Prevent HR from managing Admin leave applications
        if (Auth::user()?->isHr() && $leave->employee?->user?->isAdmin()) {
            return redirect()->back()->with('error', 'HR users cannot manage leave applications for admin accounts.');
        }

        $validated = $request->validate([
            'employee_id' => 'required|string',
            'status' => ['required', Rule::in(array_column(LeaveStatus::cases(), 'value'))],
            'type' => ['required', 'string', Rule::in(LeaveType::labels())],
            'date_from' => 'required|date',
            'total_days' => 'required|numeric|min:0.5',
            'reason' => 'nullable|string',
        ]);

        // Store original status to check if it's being changed to approved
        $originalStatus = $leave->status;

        // Update the leave application first (always)
        $employee = is_numeric($validated['employee_id'])
            ? Employee::with('user')->find((int) $validated['employee_id'])
            : null;

        $leave->update(array_merge($validated, [
            'employee_id' => (string) $validated['employee_id'],
            'employee_fk' => $employee?->id,
            'employee_name' => $employee?->full_name ?? $leave->employee_name,
        ]));

        $leave->refresh();

        // If status was changed to approved, handle credit deduction separately
        if ($originalStatus !== 'approved' && $validated['status'] === 'approved') {
            $errors = $creditEngine->validateForApproval($leave);
            if ($errors !== []) {
                $leave->update(['status' => $originalStatus]);

                return redirect()->back()->with('error', implode(' ', $errors));
            }

            $creditEngine->applyApprovalDeduction($leave, Auth::id());

            // Broadcast leave approved event
            $approverName = Auth::user()?->full_name ?? 'HR';
            broadcast(new LeaveApproved($leave, $approverName))->toOthers();

            $employeeUser = $leave->employee?->user;
            if ($employeeUser) {
                $employeeUser->notify(new SystemNotification(
                    type: 'success',
                    title: 'Leave Approved',
                    message: "Your leave request has been approved by {$approverName}.",
                    data: [
                        'redirect_url' => '/employee/leave-applications?status=approved',
                        'leave_id' => $leave->id,
                    ],
                    actor: Auth::user()
                        ? ['id' => Auth::id(), 'name' => $approverName, 'avatar' => Auth::user()?->avatar]
                        : null,
                ));
            }

            // Email employee about the approved leave
            $email = $employeeUser?->email ?? null;

            if ($email !== null) {
                Mail::to($email)->queue(new LeaveApprovedMail($leave, $approverName));
            }
        }

        if ($originalStatus !== 'rejected' && $validated['status'] === 'rejected') {
            // status already persisted via $leave->update($validated) above
        }

        // If status was changed to rejected
        if ($originalStatus !== 'rejected' && $validated['status'] === 'rejected') {
            $approverName = Auth::user()?->full_name ?? 'HR';

            broadcast(new LeaveRejected($leave, $approverName))->toOthers();

            $employeeUser = $leave->employee?->user;
            if ($employeeUser) {
                $employeeUser->notify(new SystemNotification(
                    type: 'error',
                    title: 'Leave Rejected',
                    message: "Your leave request has been rejected by {$approverName}.",
                    data: [
                        'redirect_url' => '/employee/leave-applications?status=rejected',
                        'leave_id' => $leave->id,
                    ],
                    actor: Auth::user()
                        ? ['id' => Auth::id(), 'name' => $approverName, 'avatar' => Auth::user()?->avatar]
                        : null,
                ));
            }

            // Email employee about the rejected leave
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

    public function destroyAttachment(Request $request, $id)
    {
        $leave = LeaveApplication::find($id);

        if (! $leave) {
            return response()->json(['success' => false, 'message' => 'Attachment not found'], 404);
        }

        if ($leave->status !== 'pending') {
            return response()->json(['success' => false, 'message' => 'Cannot delete attachment from a non-pending leave application'], 403);
        }

        $attachments = $leave->attachments;
        if (! is_array($attachments) || $attachments === []) {
            return response()->json(['success' => true, 'message' => 'No attachments to delete', 'attachments' => []]);
        }

        $target = $request->input('path');
        [$remaining, $removed] = $this->removeAttachments($attachments, $target);

        $leave->attachments = $remaining;
        $leave->save();

        return response()->json([
            'success' => true,
            'message' => 'Attachment deleted',
            'removed' => count($removed),
            'attachments' => $remaining,
        ]);
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
            $query->where('leave_applications.status', (string) $request->input('status'));
        }
    }

    private function removeAttachments(array $attachments, ?string $target): array
    {
        $remaining = [];
        $removed = [];
        $normalizedTarget = $target ? $this->normalizeAttachmentPath($target) : null;

        foreach ($attachments as $attachment) {
            $meta = $this->attachmentMeta($attachment);
            $path = $meta['path'];
            $disk = $meta['disk'];
            $matches = $normalizedTarget === null;

            if ($normalizedTarget !== null) {
                $matches = $path !== null && ($path === $normalizedTarget || $path === ltrim((string) $target, '/'));
                if (! $matches && is_string($attachment)) {
                    $matches = $attachment === $target;
                }
                if (! $matches && is_array($attachment)) {
                    $matches = ($attachment['path'] ?? null) === $target || ($attachment['url'] ?? null) === $target;
                }
            }

            if ($matches) {
                if ($path) {
                    Storage::disk($disk)->delete($path);
                }
                $removed[] = $path;

                continue;
            }

            $remaining[] = $attachment;
        }

        return [$remaining, $removed];
    }

    private function attachmentMeta(mixed $attachment): array
    {
        $disk = 'public';
        $path = null;

        if (is_string($attachment)) {
            $path = $attachment;
        } elseif (is_array($attachment)) {
            $disk = $attachment['disk'] ?? $disk;
            $path = $attachment['path'] ?? $attachment['file_path'] ?? $attachment['storage_path'] ?? $attachment['url'] ?? null;
        }

        if (! is_string($path) || $path === '') {
            return ['path' => null, 'disk' => $disk];
        }

        return ['path' => $this->normalizeAttachmentPath($path), 'disk' => $disk];
    }

    private function normalizeAttachmentPath(string $path): string
    {
        if (str_contains($path, '/storage/')) {
            $path = substr($path, strpos($path, '/storage/') + 9);
        }

        if (str_starts_with($path, 'storage/')) {
            $path = substr($path, 8);
        }

        $path = ltrim($path, '/');

        if (str_starts_with($path, 'public/')) {
            $path = substr($path, 7);
        }

        return $path;
    }
}
