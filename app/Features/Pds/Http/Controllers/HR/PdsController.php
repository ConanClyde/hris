<?php

namespace App\Features\Pds\Http\Controllers\HR;

use App\Features\Pds\Models\Pds;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PdsController extends Controller
{
    public function index(Request $request)
    {
        $query = Pds::query()->with('employee');

        if ($request->filled('status')) {
            $query->where('status', (string) $request->input('status'));
        }

        $pdsList = $query->orderByDesc('created_at')
            ->paginate(10)
            ->appends($request->query());

        $payload = [
            'pdsList' => $pdsList,
            'statusOptions' => ['draft' => 'Draft', 'submitted' => 'Submitted', 'approved' => 'Approved', 'rejected' => 'Rejected'],
            'filters' => $request->only(['status']),
        ];

        if ($request->filled('preview_id')) {
            $pdsDetail = Pds::with(['employee', 'personal'])->find($request->integer('preview_id'));
            $payload['pdsDetail'] = $pdsDetail;
        }

        return Inertia::render('HR/PDS/Index', $payload);
    }

    public function preview(Request $request)
    {
        if (! $request->filled('pds_id')) {
            return redirect()->route('hr.pds.index');
        }

        return redirect()->route('hr.pds.index', ['preview_id' => $request->query('pds_id')]);
    }

    public function updateStatus(Request $request)
    {
        $validated = $request->validate([
            'pds_id' => 'required|exists:pds,id',
            'status' => 'required|in:draft,submitted,approved,rejected',
        ]);

        $pds = Pds::findOrFail($validated['pds_id']);
        $pds->update(['status' => $validated['status']]);

        return redirect()->back()->with('success', 'PDS status updated.');
    }
}
