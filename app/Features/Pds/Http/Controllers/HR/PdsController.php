<?php

namespace App\Features\Pds\Http\Controllers\HR;

use App\Features\Pds\Events\PdsStatusUpdated;
use App\Features\Pds\Models\Pds;
use App\Features\Users\Enums\UserRole;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class PdsController extends Controller
{
    public function index(Request $request): Response|RedirectResponse
    {
        $query = Pds::query()->with([
            'employee.user',
            'personal',
            'family',
            'children',
            'education',
            'cscEligibility',
            'workExperience',
            'voluntaryWork',
            'training',
            'otherInfo',
            'references',
            'backgroundInfo',
        ]);

        // HR users cannot see PDS for admin accounts
        if (Auth::user()?->isHr()) {
            $query->whereHas('employee.user', function ($q) {
                $q->where('role', '!=', UserRole::Admin->value);
            });
        }

        if ($request->filled('status')) {
            $query->where('status', (string) $request->input('status'));
        }

        $appendQuery = collect($request->query())->reject(fn ($v) => $v === 'all')->all();
        $pdsList = $query->orderByDesc('created_at')
            ->paginate(10)
            ->appends($appendQuery);

        $payload = [
            'pdsList' => $pdsList,
            'statusOptions' => ['draft' => 'Draft', 'submitted' => 'Submitted', 'approved' => 'Approved', 'rejected' => 'Rejected'],
            'filters' => $request->only(['status']),
        ];

        if ($request->filled('preview_id')) {
            $pdsDetail = Pds::with([
                'employee.user',
                'personal',
                'family',
                'children',
                'education',
                'cscEligibility',
                'workExperience',
                'voluntaryWork',
                'training',
                'otherInfo',
                'references',
                'backgroundInfo',
            ])->find($request->integer('preview_id'));

            // Check if HR is trying to preview an admin's PDS
            if (Auth::user()?->isHr() && $pdsDetail?->employee?->user?->isAdmin()) {
                return redirect()->route('hr.pds.index')->with('error', 'HR users cannot view PDS for admin accounts.');
            }

            $payload['pdsDetail'] = $pdsDetail;
        }

        return Inertia::render('HR/PDS/Index', $payload);
    }

    public function preview(Request $request): RedirectResponse
    {
        if (! $request->filled('pds_id')) {
            return redirect()->route('hr.pds.index');
        }

        return redirect()->route('hr.pds.index', ['preview_id' => $request->query('pds_id')]);
    }

    public function updateStatus(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'pds_id' => 'required|exists:pds,id',
            'status' => 'required|in:draft,submitted,approved,rejected',
        ]);

        $pds = Pds::with('employee.user')->findOrFail($validated['pds_id']);

        // Prevent HR from updating status of Admin's PDS
        if (Auth::user()?->isHr() && $pds->employee?->user?->isAdmin()) {
            return redirect()->back()->with('error', 'HR users cannot update PDS status for admin accounts.');
        }

        $oldStatus = (string) ($pds->status ?? '');

        $pds->update([
            'status' => $validated['status'],
            'reviewed_by_user_id' => Auth::id(),
            'reviewed_at' => now(),
        ]);

        if ($oldStatus !== (string) $validated['status']) {
            event(new PdsStatusUpdated(
                id: $pds->id,
                employeeId: (int) $pds->employee_id,
                employeeName: $pds->employee?->full_name,
                status: (string) $validated['status'],
            ));
        }

        return redirect()->back()->with('success', 'PDS status updated.');
    }
}
