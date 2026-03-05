<?php

namespace App\Features\Pds\Http\Controllers;

use App\Features\Pds\Models\Pds;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class PdsExportController
{
    private function getPdsWithRelations(int|string $pdsId): Pds
    {
        return Pds::with([
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
        ])->findOrFail($pdsId);
    }

    public function exportEmployee(Request $request)
    {
        $user = $request->user();
        if (! $user || ! $user->employee) {
            abort(403, 'Unauthorized. Employee profile required.');
        }

        // Only export approved PDS
        $pds = Pds::where('employee_id', $user->employee->id)
            ->where('status', 'approved')
            ->firstOrFail();

        $pds = $this->getPdsWithRelations($pds->id);

        $pdf = Pdf::loadView('pdf.pds', compact('pds'))
            ->setPaper('legal', 'portrait');

        return $pdf->download("CS_Form_212_{$user->employee->first_name}_{$user->employee->last_name}.pdf");
    }

    public function exportHr(Request $request, $id)
    {
        // Add basic authorization check for HR roles, handled by routes usually

        $pds = $this->getPdsWithRelations($id);

        $pdf = Pdf::loadView('pdf.pds', compact('pds'))
            ->setPaper('legal', 'portrait');

        return $pdf->download("CS_Form_212_{$pds->employee->first_name}_{$pds->employee->last_name}.pdf");
    }
}
