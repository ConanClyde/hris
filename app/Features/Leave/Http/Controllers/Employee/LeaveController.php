<?php

namespace App\Features\Leave\Http\Controllers\Employee;

use App\Events\LeaveCancelled;
use App\Events\LeaveSubmitted;
use App\Features\Employees\Models\Employee;
use App\Features\Leave\Enums\LeaveStatus;
use App\Features\Leave\Enums\LeaveType;
use App\Features\Leave\Models\LeaveApplication;
use App\Features\Leave\Models\LeaveCredit;
use App\Http\Controllers\Controller;
use App\Mail\LeaveCancelledMail;
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
                'leaveCredits' => [],
                'types' => LeaveType::labels(),
                'statusOptions' => LeaveStatus::options(),
                'filters' => $request->only(['type', 'status']),
            ]);
        }

        $query = LeaveApplication::query()
            ->where('employee_fk', $employeeId);

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

        $leaveCredits = LeaveCredit::query()
            ->where('employee_id', $employeeId)
            ->orderBy('leave_type')
            ->get()
            ->map(fn (LeaveCredit $credit) => [
                'leave_type' => $credit->leave_type,
                'balance' => $credit->balance,
            ]);

        return Inertia::render('Employee/Leave/Index', [
            'applications' => $applications,
            'leaveCredits' => $leaveCredits,
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
            'type' => ['required', 'string', Rule::in(LeaveType::labels())],
            'date_from' => 'required|date',
            'total_days' => 'required|numeric|min:0.5',
            'reason' => 'nullable|string|max:1000',
            'attachments' => 'nullable|array|max:5',
            'attachments.*' => 'file|mimes:pdf,jpg,jpeg,png|max:51200',
        ]);

        $employee = Employee::find($employeeId);
        $employeeName = $employee?->full_name ?? Auth::user()->name;

        $leave = LeaveApplication::create([
            'employee_id' => (string) $employeeId,
            'employee_fk' => $employeeId,
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
            ->whereIn('role', ['admin', 'hr'])
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
        $leave = LeaveApplication::where('id', $id)->where('employee_fk', $employeeId)->firstOrFail();

        // If trying to cancel
        if ($request->input('status') === 'cancelled') {
            if ($leave->status === 'approved' || $leave->status === 'rejected') {
                return back()->with('error', 'Cannot cancel processed leave application.');
            }
            $leave->update(['status' => 'cancelled']);

            // Broadcast leave cancelled event
            broadcast(new LeaveCancelled($leave))->toOthers();

            $actor = Auth::user();
            $actorPayload = $actor
                ? [
                    'id' => $actor->id,
                    'name' => $actor->full_name,
                    'avatar' => $actor->avatar,
                ]
                : null;

            User::query()
                ->whereIn('role', ['admin', 'hr'])
                ->where('is_active', true)
                ->each(function (User $recipient) use ($leave, $actorPayload): void {
                    $recipient->notify(new SystemNotification(
                        type: 'warning',
                        title: 'Leave Cancelled',
                        message: "{$leave->employee_name} cancelled a leave request.",
                        data: [
                            'redirect_url' => '/hr/leave-applications',
                            'leave_id' => $leave->id,
                        ],
                        actor: $actorPayload,
                    ));
                });

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
            'type' => ['required', 'string', Rule::in(LeaveType::labels())],
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
        $leave = LeaveApplication::where('id', $id)->where('employee_fk', $employeeId)->firstOrFail();

        if ($leave->status !== 'pending') {
            return back()->with('error', 'Cannot delete leave application that is no longer pending.');
        }

        $leave->delete();

        return redirect()->route('employee.leave-applications.index')->with('success', 'Leave application deleted.');
    }

    public function export(Request $request)
    {
        $employeeId = $this->getEmployeeId();

        $query = LeaveApplication::where('employee_fk', $employeeId)->orderByDesc('created_at');

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

    public function destroyAttachment(Request $request, $id)
    {
        $employeeId = $this->getEmployeeId();
        $leave = LeaveApplication::where('id', $id)->where('employee_fk', $employeeId)->first();

        if (! $leave) {
            return response()->json(['success' => false, 'message' => 'Attachment not found or unauthorized'], 404);
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
