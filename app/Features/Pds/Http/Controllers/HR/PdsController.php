<?php

namespace App\Features\Pds\Http\Controllers\HR;

use App\Features\Pds\Events\PdsStatusUpdated;
use App\Features\Pds\Models\Pds;
use App\Features\Pds\Models\PdsBackgroundInfo;
use App\Features\Pds\Models\PdsChild;
use App\Features\Pds\Models\PdsCscEligibility;
use App\Features\Pds\Models\PdsEducation;
use App\Features\Pds\Models\PdsFamily;
use App\Features\Pds\Models\PdsOtherInfo;
use App\Features\Pds\Models\PdsPersonal;
use App\Features\Pds\Models\PdsReference;
use App\Features\Pds\Models\PdsRevisionRequest;
use App\Features\Pds\Models\PdsTraining;
use App\Features\Pds\Models\PdsVoluntaryWork;
use App\Features\Pds\Models\PdsWorkExperience;
use App\Features\Users\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Notifications\SystemNotification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Inertia\Inertia;
use Inertia\Response;

class PdsController extends Controller
{
    public function index(Request $request): Response|RedirectResponse
    {
        $query = Pds::query()->with([
            'employee.user',
            'personal',
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
            ->appends($appendQuery)
            ->through(fn ($pds) => [
                'id' => $pds->id,
                'employee_id' => $pds->employee_id,
                'user_id' => $pds->employee?->user?->id,
                'avatar' => $pds->employee?->user?->avatar
                    ? asset('storage/'.$pds->employee->user->avatar).'?v='.$pds->employee->user->updated_at?->timestamp
                    : null,
                'employee_name' => $pds->employee?->full_name ?? $pds->employee_id,
                'status' => $pds->status,
                'submitted_at' => $pds->submitted_at,
                'reviewed_at' => $pds->reviewed_at,
                'created_at' => $pds->created_at,
                'personal' => $pds->personal,
            ]);

        if (Schema::hasTable('pds_revision_requests')) {
            $revisionQuery = PdsRevisionRequest::query()->with([
                'employee.user',
                'pds',
            ])->where('status', 'pending');

            if (Auth::user()?->isHr()) {
                $revisionQuery->whereHas('employee.user', function ($q) {
                    $q->where('role', '!=', UserRole::Admin->value);
                });
            }

            $revisionList = $revisionQuery->orderByDesc('created_at')
                ->paginate(10, ['*'], 'revisions_page')
                ->through(fn ($rev) => [
                    'id' => $rev->id,
                    'employee_id' => $rev->employee_id,
                    'user_id' => $rev->employee?->user?->id,
                    'avatar' => $rev->employee?->user?->avatar
                        ? asset('storage/'.$rev->employee->user->avatar).'?v='.$rev->employee->user->updated_at?->timestamp
                        : null,
                    'employee_name' => $rev->employee?->full_name ?? $rev->employee_id,
                    'status' => $rev->status,
                    'changes' => $rev->changes,
                    'created_at' => $rev->created_at,
                ]);
        } else {
            $revisionList = new \Illuminate\Pagination\LengthAwarePaginator(
                [],
                0,
                10,
                (int) $request->input('revisions_page', 1),
                [
                    'path' => $request->url(),
                    'pageName' => 'revisions_page',
                ]
            );
        }

        $payload = [
            'pdsList' => $pdsList,
            'revisionList' => $revisionList,
            'statusOptions' => ['draft' => 'Draft', 'submitted' => 'Submitted', 'under_review' => 'Under Review', 'approved' => 'Approved', 'rejected' => 'Rejected'],
            'filters' => $request->only(['status']),
        ];

        return Inertia::render('HR/PDS/Index', $payload);
    }

    public function previewJson(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'pds_id' => 'required|exists:pds,id',
        ]);

        $pdsDetail = Pds::query()
            ->select([
                'id',
                'employee_id',
                'status',
                'submitted_at',
                'reviewed_at',
                'reviewed_by_user_id',
                'created_at',
                'updated_at',
            ])
            ->with([
                'employee' => function ($q) {
                    $q->select(['id', 'first_name', 'last_name']);
                },
                'employee.user' => function ($q) {
                    $q->select(['id', 'employee_id', 'avatar', 'updated_at', 'role']);
                },
                'personal' => function ($q) {
                    $q->select([
                        'id',
                        'pds_id',
                        'first_name',
                        'middle_name',
                        'surname',
                        'name_extension',
                        'dob',
                        'place_of_birth',
                        'sex',
                        'civil_status',
                        'email',
                        'phone',
                        'mobile',
                    ]);
                },
            ])
            ->findOrFail($validated['pds_id']);

        if (Auth::user()?->isHr() && $pdsDetail->employee?->user?->isAdmin()) {
            return response()->json(['message' => 'HR users cannot view PDS for admin accounts.'], 403);
        }

        return response()->json([
            'pdsDetail' => [
                'id' => $pdsDetail->id,
                'employee_id' => $pdsDetail->employee_id,
                'status' => (string) $pdsDetail->status,
                'submitted_at' => $pdsDetail->submitted_at,
                'reviewed_at' => $pdsDetail->reviewed_at,
                'created_at' => $pdsDetail->created_at,
                'employee' => $pdsDetail->employee,
                'personal' => $pdsDetail->personal,
            ],
        ]);
    }

    public function preview(Request $request): Response|RedirectResponse
    {
        $validated = $request->validate([
            'pds_id' => 'required|exists:pds,id',
        ]);

        $pds = Pds::with([
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
        ])->findOrFail((int) $validated['pds_id']);

        if (Auth::user()?->isHr() && $pds->employee?->user?->isAdmin()) {
            return redirect()->route('hr.pds.index')->with('error', 'HR users cannot view PDS for admin accounts.');
        }

        return Inertia::render('HR/PDS/Preview', [
            'pds' => $pds,
        ]);
    }

    public function updateStatus(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'pds_id' => 'required|exists:pds,id',
            'status' => 'required|in:draft,submitted,under_review,approved,rejected',
        ]);

        $pds = Pds::with('employee.user')->findOrFail($validated['pds_id']);

        // Prevent HR from updating status of Admin's PDS
        if (Auth::user()?->isHr() && $pds->employee?->user?->isAdmin()) {
            return redirect()->back()->with('error', 'HR users cannot update PDS status for admin accounts.');
        }

        $oldStatus = (string) ($pds->status ?? '');

        $nextStatus = (string) $validated['status'];
        $isFinalDecision = in_array($nextStatus, ['approved', 'rejected'], true);

        $update = [
            'status' => $nextStatus,
            'reviewed_by_user_id' => Auth::id(),
        ];

        if ($isFinalDecision) {
            $update['reviewed_at'] = now();
        }

        $pds->update($update);

        if ($oldStatus !== (string) $validated['status']) {
            event(new PdsStatusUpdated(
                id: $pds->id,
                employeeId: (int) $pds->employee_id,
                employeeName: $pds->employee?->full_name,
                status: (string) $validated['status'],
            ));
        }

        $employeeUser = $pds->employee?->user;
        if ($employeeUser) {
            $status = (string) $validated['status'];
            $type = $status === 'approved' ? 'success' : ($status === 'rejected' ? 'error' : 'info');
            $employeeUser->notify(new SystemNotification(
                type: $type,
                title: 'PDS Status Updated',
                message: "Your PDS status is now: {$status}.",
                data: [
                    'redirect_url' => '/employee/pds',
                    'pds_id' => $pds->id,
                ],
                actor: Auth::user()
                    ? ['id' => Auth::id(), 'name' => Auth::user()?->full_name ?? 'HR', 'avatar' => Auth::user()?->avatar]
                    : null,
            ));
        }

        return redirect()->back()->with('success', 'PDS status updated.');
    }

    public function approveRevision(Request $request, int $id): RedirectResponse
    {
        $revision = PdsRevisionRequest::with('employee.user')->findOrFail($id);

        if ($revision->status !== 'pending') {
            return redirect()->back()->with('error', 'This revision is no longer pending.');
        }

        if (Auth::user()?->isHr() && $revision->employee?->user?->isAdmin()) {
            return redirect()->back()->with('error', 'HR users cannot approve revisions for admin accounts.');
        }

        $data = $revision->changes;
        $employeeId = $revision->employee_id;

        DB::transaction(function () use ($employeeId, $data, $revision) {
            $pds = Pds::updateOrCreate(
                ['employee_id' => $employeeId],
                ['status' => 'approved', 'reviewed_by_user_id' => Auth::id(), 'reviewed_at' => now()]
            );

            if (! empty($data['personal']) && is_array($data['personal'])) {
                $personalAttrs = array_intersect_key(
                    $data['personal'],
                    array_flip((new PdsPersonal)->getFillable())
                );
                unset($personalAttrs['pds_id']);
                PdsPersonal::updateOrCreate(
                    ['pds_id' => $pds->id],
                    $personalAttrs
                );
            }

            if (! empty($data['family']) && is_array($data['family'])) {
                $familyAttrs = array_intersect_key(
                    $data['family'],
                    array_flip((new PdsFamily)->getFillable())
                );
                unset($familyAttrs['pds_id']);
                PdsFamily::updateOrCreate(
                    ['pds_id' => $pds->id],
                    $familyAttrs
                );
            }

            if (array_key_exists('children', $data) && is_array($data['children'])) {
                PdsChild::where('pds_id', $pds->id)->delete();
                foreach (array_values($data['children']) as $child) {
                    if (! is_array($child)) {
                        continue;
                    }
                    $attrs = array_intersect_key($child, array_flip((new PdsChild)->getFillable()));
                    unset($attrs['pds_id']);
                    if (! empty($attrs['name'])) {
                        $attrs['pds_id'] = $pds->id;
                        PdsChild::create($attrs);
                    }
                }
            }

            if (array_key_exists('education', $data) && is_array($data['education'])) {
                PdsEducation::where('pds_id', $pds->id)->delete();
                foreach (array_values($data['education']) as $row) {
                    if (! is_array($row)) {
                        continue;
                    }
                    $attrs = array_intersect_key($row, array_flip((new PdsEducation)->getFillable()));
                    unset($attrs['pds_id']);
                    if (! empty($attrs['level'])) {
                        $attrs['pds_id'] = $pds->id;
                        PdsEducation::create($attrs);
                    }
                }
            }

            if (array_key_exists('csc_eligibility', $data) && is_array($data['csc_eligibility'])) {
                PdsCscEligibility::where('pds_id', $pds->id)->delete();
                foreach (array_values($data['csc_eligibility']) as $i => $row) {
                    if (! is_array($row)) {
                        continue;
                    }
                    $attrs = array_intersect_key($row, array_flip((new PdsCscEligibility)->getFillable()));
                    unset($attrs['pds_id']);
                    if (! empty($attrs['license_name']) && ! empty($attrs['date_of_examination']) && ! empty($attrs['place_of_examination'])) {
                        $attrs['pds_id'] = $pds->id;
                        $attrs['sort_order'] = (int) ($attrs['sort_order'] ?? $i);
                        PdsCscEligibility::create($attrs);
                    }
                }
            }

            if (array_key_exists('work_experience', $data) && is_array($data['work_experience'])) {
                PdsWorkExperience::where('pds_id', $pds->id)->delete();
                foreach (array_values($data['work_experience']) as $i => $row) {
                    if (! is_array($row)) {
                        continue;
                    }
                    $attrs = array_intersect_key($row, array_flip((new PdsWorkExperience)->getFillable()));
                    unset($attrs['pds_id']);
                    if (! empty($attrs['employed_from']) && ! empty($attrs['position_title']) && ! empty($attrs['department'])) {
                        $attrs['pds_id'] = $pds->id;
                        $attrs['sort_order'] = (int) ($attrs['sort_order'] ?? $i);
                        $attrs['is_government'] = (bool) ($attrs['is_government'] ?? false);
                        PdsWorkExperience::create($attrs);
                    }
                }
            }

            if (array_key_exists('voluntary_work', $data) && is_array($data['voluntary_work'])) {
                PdsVoluntaryWork::where('pds_id', $pds->id)->delete();
                foreach (array_values($data['voluntary_work']) as $i => $row) {
                    if (! is_array($row)) {
                        continue;
                    }
                    $attrs = array_intersect_key($row, array_flip((new PdsVoluntaryWork)->getFillable()));
                    unset($attrs['pds_id']);
                    if (! empty($attrs['org_name_address']) && ! empty($attrs['volunteer_from']) && ! empty($attrs['nature_of_work'])) {
                        $attrs['pds_id'] = $pds->id;
                        $attrs['sort_order'] = (int) ($attrs['sort_order'] ?? $i);
                        PdsVoluntaryWork::create($attrs);
                    }
                }
            }

            if (array_key_exists('training', $data) && is_array($data['training'])) {
                PdsTraining::where('pds_id', $pds->id)->delete();
                foreach (array_values($data['training']) as $i => $row) {
                    if (! is_array($row)) {
                        continue;
                    }
                    $attrs = array_intersect_key($row, array_flip((new PdsTraining)->getFillable()));
                    unset($attrs['pds_id']);
                    if (! empty($attrs['title']) && ! empty($attrs['training_from'])) {
                        $attrs['pds_id'] = $pds->id;
                        $attrs['sort_order'] = (int) ($attrs['sort_order'] ?? $i);
                        PdsTraining::create($attrs);
                    }
                }
            }

            if (array_key_exists('other_info', $data) && is_array($data['other_info'])) {
                PdsOtherInfo::where('pds_id', $pds->id)->delete();
                foreach (array_values($data['other_info']) as $i => $row) {
                    if (! is_array($row)) {
                        continue;
                    }
                    $attrs = array_intersect_key($row, array_flip((new PdsOtherInfo)->getFillable()));
                    unset($attrs['pds_id']);
                    if (! empty($attrs['skills']) || ! empty($attrs['recognition']) || ! empty($attrs['membership'])) {
                        $attrs['pds_id'] = $pds->id;
                        $attrs['sort_order'] = (int) ($attrs['sort_order'] ?? $i);
                        PdsOtherInfo::create($attrs);
                    }
                }
            }

            if (array_key_exists('references', $data) && is_array($data['references'])) {
                PdsReference::where('pds_id', $pds->id)->delete();
                foreach (array_values($data['references']) as $i => $row) {
                    if (! is_array($row)) {
                        continue;
                    }
                    $attrs = array_intersect_key($row, array_flip((new PdsReference)->getFillable()));
                    unset($attrs['pds_id']);
                    if (! empty($attrs['reference_name']) && ! empty($attrs['reference_address'])) {
                        $attrs['pds_id'] = $pds->id;
                        $attrs['sort_order'] = (int) ($attrs['sort_order'] ?? $i);
                        PdsReference::create($attrs);
                    }
                }
            }

            if (! empty($data['background']) && is_array($data['background'])) {
                $bgAttrs = array_intersect_key(
                    $data['background'],
                    array_flip((new PdsBackgroundInfo)->getFillable())
                );
                unset($bgAttrs['pds_id']);
                PdsBackgroundInfo::updateOrCreate(
                    ['pds_id' => $pds->id],
                    $bgAttrs
                );
            }

            $revision->update([
                'status' => 'approved',
                'reviewed_by_user_id' => Auth::id(),
                'reviewed_at' => now(),
            ]);
        });

        $employeeUser = $revision->employee?->user;
        if ($employeeUser) {
            $employeeUser->notify(new SystemNotification(
                type: 'success',
                title: 'PDS Revision Approved',
                message: 'Your PDS revision request has been approved and applied.',
                data: ['redirect_url' => '/employee/pds'],
                actor: Auth::user()
                    ? ['id' => Auth::id(), 'name' => Auth::user()?->full_name ?? 'HR', 'avatar' => Auth::user()?->avatar]
                    : null,
            ));
        }

        return redirect()->back()->with('success', 'PDS Revision Request approved and successfully merged.');
    }

    public function rejectRevision(Request $request, int $id): RedirectResponse
    {
        $validated = $request->validate([
            'remarks' => 'nullable|string|max:1000',
        ]);

        $revision = PdsRevisionRequest::with('employee.user')->findOrFail($id);

        if ($revision->status !== 'pending') {
            return redirect()->back()->with('error', 'This revision is no longer pending.');
        }

        if (Auth::user()?->isHr() && $revision->employee?->user?->isAdmin()) {
            return redirect()->back()->with('error', 'HR users cannot reject revisions for admin accounts.');
        }

        $revision->update([
            'status' => 'rejected',
            'remarks' => $validated['remarks'] ?? null,
            'reviewed_by_user_id' => Auth::id(),
            'reviewed_at' => now(),
        ]);

        $employeeUser = $revision->employee?->user;
        if ($employeeUser) {
            $employeeUser->notify(new SystemNotification(
                type: 'error',
                title: 'PDS Revision Rejected',
                message: 'Your PDS revision request was rejected.',
                data: ['redirect_url' => '/employee/pds'],
                actor: Auth::user()
                    ? ['id' => Auth::id(), 'name' => Auth::user()?->full_name ?? 'HR', 'avatar' => Auth::user()?->avatar]
                    : null,
            ));
        }

        return redirect()->back()->with('success', 'PDS Revision Request rejected.');
    }
}
