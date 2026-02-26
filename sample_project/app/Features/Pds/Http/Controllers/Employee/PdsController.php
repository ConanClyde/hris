<?php

namespace App\Features\Pds\Http\Controllers\Employee;

use App\Features\Employees\Models\Employee;
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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PdsController extends Controller
{
    public function index()
    {
        $employee = Employee::where('user_id', Auth::id())->first();

        if (! $employee) {
            return view('employee.pds.index', [
                'pds' => null,
                'personal' => null,
                'family' => null,
                'children' => collect(),
                'education' => collect(),
                'csc_eligibility' => collect(),
                'work_experience' => collect(),
                'voluntary_work' => collect(),
                'training_records' => collect(),
                'reference_records' => collect(),
            ]);
        }

        $pds = Pds::with(['personal', 'family', 'children', 'education'])
            ->forEmployee($employee->id)
            ->first();

        if (! $pds) {
            $pds = Pds::create([
                'employee_id' => $employee->id,
                'status' => 'draft',
            ]);
        }

        // Map education by level for easier access in view
        $education = $pds->education->mapWithKeys(function ($item) {
            $key = strtolower(str_replace(' ', '_', $item->level));

            // Handle specific view keys mapping if standard levels differ
            return [$key => $item];
        });

        // Ensure standard levels exist in collection if not present
        $levels = ['elementary', 'secondary', 'vocational', 'college', 'graduate_studies'];
        foreach ($levels as $lvl) {
            if (! $education->has($lvl)) {
                $education->put($lvl, null);
            }
        }

        return view('employee.pds.index', [
            'pds' => $pds,
            'personal' => $pds->personal,
            'family' => $pds->family,
            'children' => $pds->children,
            'education' => $education,
            'csc_eligibility' => collect(), // TODO: Implement if missing
            'work_experience' => collect(), // TODO: Implement if missing
            'voluntary_work' => collect(), // TODO: Implement if missing
            'training_records' => collect(), // TODO: Implement if missing
            'reference_records' => collect(), // TODO: Implement if missing
        ]);
    }

    public function store(Request $request)
    {
        $employee = Employee::where('user_id', Auth::id())->first();

        if (! $employee) {
            return redirect()->back()->with('error', 'Employee record not found.');
        }

        $pds = Pds::firstOrCreate(
            ['employee_id' => $employee->id],
            ['status' => 'draft']
        );

        $section = $request->input('section');

        DB::transaction(function () use ($pds, $request, $section) {
            switch ($section) {
                case 'c1_personal':
                case 'c1_ids':
                case 'c1_address':
                    $this->savePersonalSection($pds, $request);
                    break;
                case 'c1_family':
                    $this->saveFamilySection($pds, $request);
                    break;
                case 'c1_education':
                    $this->saveEducationSection($pds, $request);
                    break;
                case 'csc':
                    $this->saveCscSection($pds, $request);
                    break;
                case 'work':
                    $this->saveWorkSection($pds, $request);
                    break;
                case 'voluntary':
                    $this->saveVoluntarySection($pds, $request);
                    break;
                case 'training':
                    $this->saveTrainingSection($pds, $request);
                    break;
                case 'other':
                    $this->saveOtherSection($pds, $request);
                    break;
                case 'references':
                    $this->saveReferencesSection($pds, $request);
                    break;
                case 'background':
                    $this->saveBackgroundSection($pds, $request);
                    break;
                case 'govid':
                    $this->saveGovIdSection($pds, $request);
                    break;
            }

        });

        return redirect()->back()->with('success', 'PDS section saved successfully.');
    }

    public function preview()
    {
        $employee = Employee::where('user_id', Auth::id())->first();
        $pds = $employee ? Pds::with(['personal', 'family', 'children', 'education'])->forEmployee($employee->id)->first() : null;

        return view('employee.pds.preview', [
            'pds' => $pds,
            'employee' => $employee,
        ]);
    }

    private function savePersonalSection(Pds $pds, Request $request): void
    {
        $data = $request->all();

        $personalData = [
            'surname' => $data['surname'] ?? null,
            'first_name' => $data['first_name'] ?? null,
            'middle_name' => $data['middle_name'] ?? null,
            'name_extension' => $data['name_extension'] ?? null,
            'dob' => $data['dob'] ?? null,
            'place_of_birth' => $data['place_of_birth'] ?? null,
            'sex' => $data['sex'] ?? null,
            'civil_status' => $data['civil_status'] ?? null,
            'height' => $data['height'] ?? null,
            'weight' => $data['weight'] ?? null,
            'blood_type' => $data['blood_type'] ?? null,
            'citizenship_type' => $data['citizenship_type'] ?? null,
            'citizenship_nature' => $data['citizenship_nature'] ?? null,
            'citizenship_country' => $data['citizenship_country'] ?? null,
            'phone' => $data['phone'] ?? null,
            'mobile' => $data['mobile'] ?? null,
            'email' => $data['email'] ?? null,
            'cs_id' => $data['cs_id'] ?? null,
            'agency_employee_no' => $data['agency_employee_no'] ?? null,
            'gsis' => $data['gsis'] ?? null,
            'pag_ibig' => $data['pag_ibig'] ?? null,
            'philhealth' => $data['philhealth'] ?? null,
            'sss' => $data['sss'] ?? null,
            'tin' => $data['tin'] ?? null,
        ];

        if (isset($data['residential_house_block_lot'])) {
            $personalData['residential_address'] = [
                'house_block_lot' => $data['residential_house_block_lot'] ?? null,
                'street' => $data['residential_street'] ?? null,
                'subdivision' => $data['residential_subdivision'] ?? null,
                'barangay' => $data['residential_barangay'] ?? null,
                'city_municipality' => $data['residential_city_municipality'] ?? null,
                'province' => $data['residential_province'] ?? null,
                'zip_code' => $data['residential_zip'] ?? null, // Fixed key name from residential_zip_code to residential_zip to match view name/migration usage if applicable. Actually view uses 'residential_zip'. Migration usually uses zip_code but PdsPersonal model casts it? Let's check model.
            ];
        }

        if (isset($data['permanent_house_block_lot'])) {
            $personalData['permanent_address'] = [
                'house_block_lot' => $data['permanent_house_block_lot'] ?? null,
                'street' => $data['permanent_street'] ?? null,
                'subdivision' => $data['permanent_subdivision'] ?? null,
                'barangay' => $data['permanent_barangay'] ?? null,
                'city_municipality' => $data['permanent_city_municipality'] ?? null,
                'province' => $data['permanent_province'] ?? null,
                'zip_code' => $data['permanent_zip'] ?? null, // Fixed key name
            ];
        }

        PdsPersonal::updateOrCreate(
            ['pds_id' => $pds->id],
            $personalData
        );
    }

    private function saveFamilySection(Pds $pds, Request $request): void
    {
        $data = $request->all();

        PdsFamily::updateOrCreate(
            ['pds_id' => $pds->id],
            [
                // Mapping view 'spouse_name' to 'spouse_surname' for now as view uses single field
                'spouse_surname' => $data['spouse_name'] ?? null,
                'spouse_occupation' => $data['spouse_occupation'] ?? null,
                'spouse_employer' => $data['spouse_employer'] ?? null,
                'spouse_business_address' => $data['spouse_business_address'] ?? null,
                'spouse_telephone' => $data['spouse_telephone'] ?? null,
                'father_surname' => $data['father_surname'] ?? null,
                'father_first_name' => $data['father_first_name'] ?? null,
                'father_middle_name' => $data['father_middle_name'] ?? null,
                'father_name_extension' => $data['father_name_extension'] ?? null,
                'mother_maiden_surname' => $data['mother_maiden_surname'] ?? null,
                'mother_maiden_first_name' => $data['mother_maiden_first_name'] ?? null,
                'mother_maiden_middle_name' => $data['mother_maiden_middle_name'] ?? null,
            ]
        );

        // Children
        PdsChild::where('pds_id', $pds->id)->delete();
        if (! empty($data['children']) && is_array($data['children'])) {
            foreach ($data['children'] as $child) {
                if (! empty($child['name'])) { // Only save if name exists
                    PdsChild::create([
                        'pds_id' => $pds->id,
                        'name' => $child['name'],
                        'dob' => $child['dob'] ?? null,
                    ]);
                }
            }
        }
    }

    private function saveEducationSection(Pds $pds, Request $request): void
    {
        $data = $request->except(['_token', 'section']);

        // Education data comes as 'education' array: education[elementary][school]
        if (! empty($data['education']) && is_array($data['education'])) {
            $levelsMap = [
                'elementary' => 'Elementary',
                'secondary' => 'Secondary',
                'vocational' => 'Vocational',
                'college' => 'College',
                'graduate_studies' => 'Graduate Studies',
            ];

            foreach ($data['education'] as $key => $eduData) {
                $levelLabel = $levelsMap[$key] ?? ucwords(str_replace('_', ' ', $key));

                // Use updateOrCreate with pds_id AND level
                PdsEducation::updateOrCreate(
                    [
                        'pds_id' => $pds->id,
                        'level' => $levelLabel,
                    ],
                    [
                        'school_name' => $eduData['school'] ?? null,
                        'degree_course' => $eduData['degree_course'] ?? null,
                        'period_from' => $eduData['year_from'] ?? null,
                        'period_to' => $eduData['year_to'] ?? null,
                        'highest_level' => $eduData['units'] ?? null, // View uses 'units'
                        'year_graduated' => $eduData['year_graduated'] ?? null,
                        'scholarship_honors' => $eduData['awards'] ?? null, // View uses 'awards'
                    ]
                );
            }
        }
    }

    private function saveCscSection(Pds $pds, Request $request)
    {
        $data = $request->all();
        PdsCscEligibility::where('pds_id', $pds->id)->delete();

        if (! empty($data['csc']) && is_array($data['csc'])) {
            foreach ($data['csc'] as $item) {
                if (! empty($item['license_name'])) {
                    PdsCscEligibility::create([
                        'pds_id' => $pds->id,
                        'license_name' => $item['license_name'],
                        'rating' => $item['rating'] ?? null,
                        'date_of_examination' => $item['date_of_examination'] ?? null,
                        'place_of_examination' => $item['place_of_examination'] ?? null,
                        'license_no' => $item['license_no'] ?? null,
                        'date_of_validity' => $item['date_of_validity'] ?? null,
                    ]);
                }
            }
        }
    }

    private function saveWorkSection(Pds $pds, Request $request)
    {
        $data = $request->all();
        PdsWorkExperience::where('pds_id', $pds->id)->delete();

        if (! empty($data['work']) && is_array($data['work'])) {
            foreach ($data['work'] as $item) {
                if (! empty($item['position_title'])) {
                    PdsWorkExperience::create([
                        'pds_id' => $pds->id,
                        'employed_from' => $item['employed_from'] ?? null,
                        'employed_to' => $item['employed_to'] ?? null,
                        'position_title' => $item['position_title'],
                        'department' => $item['department'] ?? null,
                        'salary' => $item['salary'] ?? null,
                        'salary_grade' => $item['salary_grade'] ?? null,
                        'appointment_status' => $item['appointment_status'] ?? null,
                        'is_government' => isset($item['is_government']) && $item['is_government'] === 'Y',
                    ]);
                }
            }
        }
    }

    private function saveVoluntarySection(Pds $pds, Request $request)
    {
        $data = $request->all();
        PdsVoluntaryWork::where('pds_id', $pds->id)->delete();

        if (! empty($data['voluntary']) && is_array($data['voluntary'])) {
            foreach ($data['voluntary'] as $item) {
                if (! empty($item['org_name_address'])) {
                    PdsVoluntaryWork::create([
                        'pds_id' => $pds->id,
                        'org_name_address' => $item['org_name_address'],
                        'volunteer_from' => $item['volunteer_from'] ?? null,
                        'volunteer_to' => $item['volunteer_to'] ?? null,
                        'number_of_hours' => $item['number_of_hours'] ?? 0,
                        'nature_of_work' => $item['nature_of_work'] ?? null,
                    ]);
                }
            }
        }
    }

    private function saveTrainingSection(Pds $pds, Request $request)
    {
        $data = $request->all();
        PdsTraining::where('pds_id', $pds->id)->delete();

        if (! empty($data['training']) && is_array($data['training'])) {
            foreach ($data['training'] as $item) {
                if (! empty($item['title'])) {
                    PdsTraining::create([
                        'pds_id' => $pds->id,
                        'title' => $item['title'],
                        'training_from' => $item['training_from'] ?? null,
                        'training_to' => $item['training_to'] ?? null,
                        'number_of_hours' => $item['number_of_hours'] ?? 0,
                        'training_type' => $item['training_type'] ?? null,
                        'sponsor' => $item['sponsor'] ?? null,
                    ]);
                }
            }
        }
    }

    private function saveOtherSection(Pds $pds, Request $request)
    {
        $data = $request->all();
        PdsOtherInfo::where('pds_id', $pds->id)->delete();

        if (! empty($data['other']) && is_array($data['other'])) {
            foreach ($data['other'] as $item) {
                if (! empty($item['skills']) || ! empty($item['academic_distinctions']) || ! empty($item['memberships'])) {
                    PdsOtherInfo::create([
                        'pds_id' => $pds->id,
                        'skills' => $item['skills'] ?? null,
                        'recognition' => $item['academic_distinctions'] ?? null,
                        'membership' => $item['memberships'] ?? null,
                    ]);
                }
            }
        }
    }

    private function saveReferencesSection(Pds $pds, Request $request)
    {
        $data = $request->all();
        PdsReference::where('pds_id', $pds->id)->delete();

        if (! empty($data['references']) && is_array($data['references'])) {
            foreach ($data['references'] as $item) {
                if (! empty($item['reference_name'])) {
                    PdsReference::create([
                        'pds_id' => $pds->id,
                        'reference_name' => $item['reference_name'],
                        'reference_address' => $item['reference_address'] ?? null,
                        'reference_telno' => $item['reference_telno'] ?? null,
                    ]);
                }
            }
        }
    }

    private function saveBackgroundSection(Pds $pds, Request $request)
    {
        $data = $request->all();
        $answers = [];
        foreach ($data as $key => $value) {
            if (str_starts_with($key, 'q')) {
                if (! str_contains($key, 'details') && ! str_contains($key, 'specify') && ! str_contains($key, 'idno')) {
                    $answers[$key] = $value;
                }
            }
        }

        // Handle q35 split
        $details35 = ($data['q35a_details'] ?? '')."\n".($data['q35b_details'] ?? '');

        PdsBackgroundInfo::updateOrCreate(
            ['pds_id' => $pds->id],
            [
                'answers' => $answers,
                'details_34' => $data['q34_details'] ?? null,
                'details_35' => trim($details35),
                'details_36' => $data['q36_details'] ?? null,
                'details_37' => $data['q37_details'] ?? null,
                'details_38' => trim(($data['q38a_details'] ?? '').' '.($data['q38b_details'] ?? '')),
                'details_39' => $data['q39_details'] ?? null,
                'details_40' => trim(($data['q40a_specify'] ?? '').' '.($data['q40b_idno'] ?? '').' '.($data['q40c_idno'] ?? '')),
            ]
        );
    }

    private function saveGovIdSection(Pds $pds, Request $request)
    {
        $data = $request->all();
        PdsPersonal::updateOrCreate(
            ['pds_id' => $pds->id],
            [
                'gov_id_type' => $data['govid_name'] ?? null,
                'gov_id_no' => $data['govid_no'] ?? null,
                'gov_id_issuance' => $data['govid_dateplace'] ?? null,
            ]
        );
    }
}
