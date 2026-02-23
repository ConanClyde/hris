<?php

namespace App\Features\Pds\Http\Controllers\Employee;

use App\Features\Pds\Models\Pds;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class PdsController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $pds = Pds::where('employee_id', $user?->employee?->id)->first();

        return Inertia::render('Employee/PDS/Index', ['pds' => $pds]);
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $employeeId = $user?->employee?->id;

        $validated = $request->validate([
            'data' => 'required|array',
        ]);

        Pds::updateOrCreate(
            ['employee_id' => $employeeId],
            array_merge($validated['data'], ['status' => 'draft'])
        );

        return redirect()->route('employee.pds.index')->with('success', 'PDS saved.');
    }

    public function preview()
    {
        $user = Auth::user();
        $pds = Pds::where('employee_id', $user?->employee?->id)->firstOrFail();

        return Inertia::render('Employee/PDS/Preview', ['pds' => $pds]);
    }
}
