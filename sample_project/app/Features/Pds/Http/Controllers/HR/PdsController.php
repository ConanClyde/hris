<?php

namespace App\Features\Pds\Http\Controllers\HR;

use App\Features\Employees\Models\Employee;
use App\Features\Pds\Models\Pds;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PdsController extends Controller
{
    public function index(Request $request)
    {
        $query = Pds::with(['employee', 'personal', 'reviewer']);

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('search')) {
            $search = strtolower($request->input('search'));
            $query->whereHas('employee', function ($q) use ($search) {
                $q->whereRaw('LOWER(first_name) like ?', ["%{$search}%"])
                    ->orWhereRaw('LOWER(last_name) like ?', ["%{$search}%"]);
            });
        }

        $pdsList = $query->orderByDesc('updated_at')->paginate(15)->appends($request->query());

        return view('hr.pds.index', [
            'pdsList' => $pdsList,
            'statusOptions' => ['draft' => 'Draft', 'submitted' => 'Submitted', 'under_review' => 'Under Review', 'approved' => 'Approved', 'rejected' => 'Rejected'],
        ]);
    }

    public function preview(Request $request)
    {
        $pdsId = $request->query('pds_id');
        $employeeId = $request->query('employee_id');

        if ($pdsId) {
            $pds = Pds::with(['employee', 'personal'])->findOrFail($pdsId);
        } elseif ($employeeId) {
            $employee = Employee::findOrFail($employeeId);
            $pds = Pds::with(['employee', 'personal'])
                ->forEmployee($employee->id)
                ->firstOrFail();
        } else {
            abort(404);
        }

        return view('hr.pds.preview', [
            'pds' => $pds,
            'employee' => $pds->employee,
        ]);
    }

    public function updateStatus(Request $request)
    {
        $validated = $request->validate([
            'pds_id' => 'required|integer|exists:pds,id',
            'status' => 'required|in:draft,submitted,under_review,approved,rejected',
        ]);

        $pds = Pds::findOrFail($validated['pds_id']);
        $status = $validated['status'];

        $updateData = ['status' => $status];

        if (in_array($status, ['approved', 'rejected'])) {
            $updateData['reviewed_by_user_id'] = Auth::id();
            $updateData['reviewed_at'] = now();
        }

        if ($status === 'submitted' && $pds->status === 'draft') {
            $updateData['submitted_at'] = now();
        }

        $pds->update($updateData);

        return redirect()->back()->with('success', 'PDS status updated to '.ucfirst(str_replace('_', ' ', $status)).'.');
    }
}
