<?php

declare(strict_types=1);

namespace App\Features\Employees\Http\Controllers\HR;

use App\Features\Employees\Services\EmployeeBulkImportService;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class EmployeeBulkController extends Controller
{
    public function __construct(
        private EmployeeBulkImportService $importService
    ) {}

    /**
     * Download CSV template for employee import.
     */
    public function downloadTemplate(): Response
    {
        $headers = $this->importService->getImportTemplateHeaders();

        $content = implode(',', $headers)."\n";
        $content .= implode(',', array_fill(0, count($headers), ''))."\n";

        return response($content, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="employee_import_template.csv"',
        ]);
    }

    /**
     * Import employees from uploaded CSV.
     */
    public function import(Request $request): JsonResponse
    {
        if (! Auth::user()?->isAdmin() && ! Auth::user()?->isHr()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->validate([
            'file' => 'required|file|mimes:csv,txt|max:10240', // 10MB max
        ]);

        $file = $request->file('file');
        $handle = fopen($file->getPathname(), 'r');

        if ($handle === false) {
            return response()->json(['error' => 'Could not read file'], 400);
        }

        // Read headers
        $headers = fgetcsv($handle);
        if ($headers === false) {
            return response()->json(['error' => 'Empty or invalid CSV file'], 400);
        }

        $rows = [];
        while (($data = fgetcsv($handle)) !== false) {
            if (count($data) === count($headers)) {
                $rows[] = array_combine($headers, $data);
            }
        }
        fclose($handle);

        $results = $this->importService->import($rows);

        return response()->json([
            'message' => 'Import completed',
            'imported' => $results['success'],
            'failed' => $results['failed'],
            'errors' => $results['errors'],
        ], $results['failed'] > 0 ? 207 : 200);
    }

    /**
     * Export employees to CSV.
     */
    public function export(Request $request): Response
    {
        if (! Auth::user()?->isAdmin() && ! Auth::user()?->isHr()) {
            abort(403, 'Unauthorized');
        }

        $employeeIds = $request->input('employee_ids');
        $data = $this->importService->export($employeeIds);

        $output = fopen('php://temp', 'r+');
        fputcsv($output, $data['headers']);

        foreach ($data['rows'] as $row) {
            fputcsv($output, $row);
        }

        rewind($output);
        $content = stream_get_contents($output);
        fclose($output);

        return response($content, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="employees_export_'.now()->format('Y-m-d').'.csv"',
        ]);
    }
}
