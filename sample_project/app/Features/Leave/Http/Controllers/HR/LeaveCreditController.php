<?php

namespace App\Features\Leave\Http\Controllers\HR;

use App\Features\Employees\Models\Employee;
use App\Http\Controllers\Controller;

class LeaveCreditController extends Controller
{
    public function index()
    {
        $employees = Employee::with('leaveCredits')->paginate(15);

        return view('features.leave.hr.credits.index', compact('employees'));
    }

    public function show($id)
    {
        $employee = Employee::with(['leaveCredits.adjustments.creator'])->findOrFail($id);

        return view('features.leave.hr.credits.show', compact('employee'));
    }
}
