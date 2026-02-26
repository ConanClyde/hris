<?php

namespace App\Features\Pds\Http\Controllers\Employee;

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
use App\Features\Pds\Models\PdsTraining;
use App\Features\Pds\Models\PdsVoluntaryWork;
use App\Features\Pds\Models\PdsWorkExperience;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class PdsController extends Controller
{
    private function getEmployeeId()
    {
        return Auth::user()?->employee?->id;
    }

    public function index(): Response
    {
        $employeeId = $this->getEmployeeId();
        $pds = Pds::with([
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
        ])->where('employee_id', $employeeId)->first();

        return Inertia::render('Employee/PDS/Index', ['pds' => $pds]);
    }

    public function store(Request $request)
    {
        $employeeId = $this->getEmployeeId();

        if (! $employeeId) {
            abort(403, 'User is not linked to an employee record.');
        }

        $validated = $request->validate([
            'data' => 'required|array',
            'data.personal' => 'sometimes|array',
            'data.family' => 'sometimes|array',
            'data.children' => 'sometimes|array',
            'data.education' => 'sometimes|array',
            'data.csc_eligibility' => 'sometimes|array',
            'data.work_experience' => 'sometimes|array',
            'data.voluntary_work' => 'sometimes|array',
            'data.training' => 'sometimes|array',
            'data.other_info' => 'sometimes|array',
            'data.references' => 'sometimes|array',
            'data.background' => 'sometimes|array',
        ]);

        $data = $validated['data'];
        $status = $data['status'] ?? 'draft';

        $oldStatus = (string) (Pds::where('employee_id', $employeeId)->value('status') ?? '');

        DB::transaction(function () use ($employeeId, $data, $status, &$pds) {
            $pdsAttrs = ['status' => $status];
            if ($status === 'submitted') {
                $pdsAttrs['submitted_at'] = now();
            }

            $pds = Pds::updateOrCreate(
                ['employee_id' => $employeeId],
                $pdsAttrs
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
        });

        if (isset($pds) && $oldStatus !== (string) ($pds->status ?? '')) {
            event(new PdsStatusUpdated(
                id: (int) $pds->id,
                employeeId: (int) $employeeId,
                employeeName: Auth::user()?->employee?->full_name,
                status: (string) ($pds->status ?? ''),
            ));
        }

        return redirect()->route('employee.pds.index')->with('success', 'PDS saved successfully');
    }

    public function preview(): Response|RedirectResponse
    {
        $employeeId = $this->getEmployeeId();

        if (! $employeeId) {
            return redirect()->route('employee.pds.index')->with('error', 'You must have an employee record to preview PDS.');
        }

        $pds = Pds::with([
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
        ])->where('employee_id', $employeeId)->first();

        if (! $pds) {
            return redirect()->route('employee.pds.index')->with('error', 'No PDS found. Please complete your PDS first.');
        }

        return Inertia::render('Employee/PDS/Preview', ['pds' => $pds]);
    }
}
