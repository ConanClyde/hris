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
use App\Features\Training\Models\Training;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Inertia\Inertia;
use Inertia\Response;
use Symfony\Component\Process\Process;

class PdsController extends Controller
{
    private function getEmployeeId()
    {
        return Auth::user()?->employee?->id;
    }

    public function index(): Response
    {
        $employeeId = $this->getEmployeeId();

        if (! $employeeId) {
            abort(403, 'User is not linked to an employee record.');
        }

        $existingPdsId = Pds::where('employee_id', $employeeId)->value('id');
        if ($existingPdsId === null) {
            $pdsModel = Pds::create([
                'employee_id' => $employeeId,
                'status' => 'draft',
            ]);
            $pdsId = (int) $pdsModel->id;
        } else {
            $pdsId = (int) $existingPdsId;
        }

        DB::transaction(function () use ($employeeId, $pdsId): void {
            $trainings = Training::query()
                ->forEmployeePk($employeeId)
                ->where('status', 'approved')
                ->orderByDesc('date_from')
                ->orderByDesc('id')
                ->get();

            PdsTraining::where('pds_id', $pdsId)->delete();

            foreach ($trainings as $i => $t) {
                PdsTraining::create([
                    'pds_id' => $pdsId,
                    'title' => $t->title,
                    'training_from' => $t->date_from,
                    'training_to' => $t->date_to,
                    'number_of_hours' => $t->hours,
                    'training_type' => $t->type,
                    'sponsor' => $t->provider,
                    'sort_order' => $i,
                ]);
            }
        });

        $pds = Pds::query()->with([
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

        $pendingRevision = null;
        if (Schema::hasTable('pds_revision_requests')) {
            $pendingRevision = \App\Features\Pds\Models\PdsRevisionRequest::where('employee_id', $employeeId)
                ->whereIn('status', ['draft', 'pending'])
                ->first();
        }

        return Inertia::render('Employee/PDS/Index', [
            'pds' => $pds,
            'pendingRevision' => $pendingRevision,
        ]);
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
            'data.personal.height' => 'nullable|numeric|min:0',
            'data.personal.weight' => 'nullable|numeric|min:0',
            'data.family' => 'sometimes|array',
            'data.children' => 'sometimes|array',
            'data.education' => 'sometimes|array',
            'data.csc_eligibility' => 'sometimes|array',
            'data.work_experience' => 'sometimes|array',
            'data.work_experience.*.salary' => 'nullable|numeric|min:0',
            'data.voluntary_work' => 'sometimes|array',
            'data.voluntary_work.*.number_of_hours' => 'nullable|numeric|min:0',
            'data.training' => 'sometimes|array',
            'data.training.*.number_of_hours' => 'nullable|numeric|min:0',
            'data.other_info' => 'sometimes|array',
            'data.references' => 'sometimes|array',
            'data.background' => 'sometimes|array',
        ]);

        $data = $validated['data'];
        $status = $data['status'] ?? 'draft';

        $oldStatus = (string) (Pds::where('employee_id', $employeeId)->value('status') ?? '');

        if ($oldStatus === 'approved') {
            $existingRevision = \App\Features\Pds\Models\PdsRevisionRequest::where('employee_id', $employeeId)
                ->whereIn('status', ['draft', 'pending'])
                ->first();

            $revisionStatus = $status === 'submitted' ? 'pending' : 'draft';

            if ($existingRevision) {
                $existingRevision->update([
                    'status' => $revisionStatus,
                    'changes' => $data,
                ]);
            } else {
                \App\Features\Pds\Models\PdsRevisionRequest::create([
                    'employee_id' => $employeeId,
                    'pds_id' => Pds::where('employee_id', $employeeId)->value('id'),
                    'status' => $revisionStatus,
                    'changes' => $data, // Casts as JSON automatically
                ]);
            }

            return redirect()->route('employee.pds.index')->with('success', $status === 'submitted' ? 'Revision request submitted successfully. Waiting for HR approval.' : 'Revision request saved as draft.');
        }

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

    public function parsePdf(Request $request)
    {
        $employeeId = $this->getEmployeeId();

        if (! $employeeId) {
            return response()->json(['error' => 'User is not linked to an employee record.'], 403);
        }

        $request->validate([
            'pds_file' => 'required|file|mimes:pdf|max:10240', // 10MB Max
        ]);

        $file = $request->file('pds_file');

        // Temporarily store the uploaded PDF
        $tempPath = $file->storeAs('temp', 'pds_upload_'.uniqid().'.pdf', 'local');
        $absolutePath = storage_path('app/'.$tempPath);

        try {
            // 1. Extract Text using Node.js script
            $scriptPath = base_path('scripts/extract_pdf_text.cjs');
            $process = new Process(['node', $scriptPath, $absolutePath]);
            $process->run();

            if (! $process->isSuccessful()) {
                Log::error('PDF Extraction Failed', ['error' => $process->getErrorOutput()]);

                return response()->json(['error' => 'Failed to extract text from the PDF.'], 500);
            }

            $output = json_decode($process->getOutput(), true);

            if (! isset($output['success']) || ! $output['success'] || empty($output['text'])) {
                Log::error('PDF Extraction Error Output', ['output' => $output]);

                return response()->json(['error' => 'Could not extract valid text from the PDF.'], 500);
            }

            $extractedText = $output['text'];

            // 2. Send to Gemini API for processing
            $apiKey = env('GEMINI_API_KEY');

            if (empty($apiKey)) {
                return response()->json(['error' => 'AI Parsing is not configured. Missing GEMINI_API_KEY.'], 500);
            }

            $prompt = <<<EOT
You are an expert HR data entry assistant. Your task is to extract information from the following raw text of a Philippine Civil Service Form 212 (Personal Data Sheet) and map it accurately into a strict JSON structure.

Analyze the text carefully. It may be messy because it was extracted via OCR or basic PDF parsing.

IMPORTANT INSTRUCTIONS:
1. Output ONLY valid JSON. Do not include markdown formatting like ```json or any conversational text.
2. If a field is empty, missing, or explicitly marked "N/A" in the text, return null or an empty string/array as appropriate.
3. Use the exact keys provided in the schema below.

EXPECTED JSON SCHEMA:
{
  "personal": {
    "surname": "string",
    "first_name": "string",
    "middle_name": "string",
    "name_extension": "string (e.g., JR., SR., III)",
    "date_of_birth": "YYYY-MM-DD",
    "place_of_birth": "string",
    "sex": "string (Male or Female)",
    "civil_status": "string (Single, Married, Widowed, Separated, Other)",
    "height": "float (in meters)",
    "weight": "float (in kg)",
    "blood_type": "string",
    "gsis_id_no": "string",
    "pagibig_id_no": "string",
    "philhealth_no": "string",
    "sss_no": "string",
    "tin_no": "string",
    "agency_employee_no": "string",
    "citizenship": "string (Filipino or Dual Citizenship)",
    "citizenship_type": "string (by birth or by naturalization)",
    "country": "string",
    "residential_house_block_no": "string",
    "residential_street": "string",
    "residential_subdivision_village": "string",
    "residential_barangay": "string",
    "residential_city_municipality": "string",
    "residential_province": "string",
    "residential_zipcode": "string",
    "permanent_house_block_no": "string",
    "permanent_street": "string",
    "permanent_subdivision_village": "string",
    "permanent_barangay": "string",
    "permanent_city_municipality": "string",
    "permanent_province": "string",
    "permanent_zipcode": "string",
    "telephone_no": "string",
    "mobile_no": "string",
    "email_address": "string"
  },
  "family": {
    "spouse_surname": "string",
    "spouse_first_name": "string",
    "spouse_middle_name": "string",
    "spouse_name_extension": "string",
    "spouse_occupation": "string",
    "spouse_employer_business_name": "string",
    "spouse_business_address": "string",
    "spouse_telephone_no": "string",
    "father_surname": "string",
    "father_first_name": "string",
    "father_middle_name": "string",
    "father_name_extension": "string",
    "mother_maiden_surname": "string",
    "mother_first_name": "string",
    "mother_middle_name": "string"
  },
  "children": [
    { "name": "string", "date_of_birth": "YYYY-MM-DD" }
  ],
  "education": [
    {
      "level": "string (Elementary, Secondary, Vocational, College, Graduate Studies)",
      "school_name": "string",
      "basic_education_degree_course": "string",
      "period_from": "YYYY",
      "period_to": "YYYY",
      "highest_level_units_earned": "string",
      "year_graduated": "YYYY",
      "scholarship_academic_honors": "string"
    }
  ],
  "csc_eligibility": [
    {
      "license_name": "string",
      "rating": "string",
      "date_of_examination": "YYYY-MM-DD",
      "place_of_examination": "string",
      "license_number": "string",
      "license_date_of_validity": "YYYY-MM-DD"
    }
  ],
  "work_experience": [
    {
      "employed_from": "YYYY-MM-DD",
      "employed_to": "YYYY-MM-DD (or 'Present')",
      "position_title": "string",
      "department": "string",
      "salary": "float",
      "salary_grade": "string",
      "status_of_appointment": "string",
      "is_government": "boolean"
    }
  ],
  "voluntary_work": [
    {
      "org_name_address": "string",
      "volunteer_from": "YYYY-MM-DD",
      "volunteer_to": "YYYY-MM-DD",
      "number_of_hours": "float",
      "nature_of_work": "string"
    }
  ]
}

RAW EXTRACTED TEXT:
---
{$extractedText}
---
EOT;

            $response = Http::timeout(60)->post('https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key='.$apiKey, [
                'contents' => [
                    ['parts' => [['text' => $prompt]]],
                ],
                'generationConfig' => [
                    'temperature' => 0.1,
                    'topK' => 1,
                    'topP' => 0.1,
                    'responseMimeType' => 'application/json',
                ],
            ]);

            if ($response->failed()) {
                Log::error('Gemini API Error', ['response' => $response->body()]);

                return response()->json(['error' => 'Failed to process document via AI.'], 500);
            }

            $aiData = $response->json();
            $generatedText = $aiData['candidates'][0]['content']['parts'][0]['text'] ?? '{}';

            // Clean up any potential markdown formatting in case the model ignored instructions
            $generatedText = str_replace(['```json', '```'], '', $generatedText);

            $parsedData = json_decode(trim($generatedText), true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                Log::error('JSON Parse Error from AI', ['text' => $generatedText]);

                return response()->json(['error' => 'AI returned invalid data format.'], 500);
            }

            return response()->json([
                'success' => true,
                'message' => 'PDS data extracted successfully.',
                'data' => $parsedData,
            ]);

        } catch (\Exception $e) {
            Log::error('PDS Parsing Exception', ['message' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);

            return response()->json(['error' => 'An unexpected error occurred during processing.'], 500);
        } finally {
            // Clean up temp file
            if (isset($absolutePath) && file_exists($absolutePath)) {
                unlink($absolutePath);
            }
        }
    }
}
