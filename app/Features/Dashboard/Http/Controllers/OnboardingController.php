<?php

namespace App\Features\Dashboard\Http\Controllers;

use App\Features\Employees\Models\Employee;
use App\Http\Controllers\Controller;
use App\Models\OffboardingClearance;
use App\Models\OnboardingChecklist;
use Illuminate\Http\Request;
use Inertia\Inertia;

class OnboardingController extends Controller
{
    /**
     * Default onboarding checklist items for new hires.
     */
    private function defaultItems(): array
    {
        return [
            ['title' => 'Submit Government IDs (SSS, PhilHealth, Pag-IBIG, TIN)', 'category' => 'documents', 'sort_order' => 1],
            ['title' => 'Complete Personal Data Sheet (PDS)', 'category' => 'documents', 'sort_order' => 2],
            ['title' => 'Submit 2x2 ID Photo', 'category' => 'documents', 'sort_order' => 3],
            ['title' => 'Attend HR orientation/briefing', 'category' => 'hr', 'sort_order' => 4],
            ['title' => 'Review company policies & employee handbook', 'category' => 'hr', 'sort_order' => 5],
            ['title' => 'Setup workstation & email account', 'category' => 'it', 'sort_order' => 6],
            ['title' => 'HRIS system account activation', 'category' => 'it', 'sort_order' => 7],
            ['title' => 'Meet with department supervisor', 'category' => 'general', 'sort_order' => 8],
            ['title' => 'Office tour & introductions', 'category' => 'general', 'sort_order' => 9],
        ];
    }

    public function index()
    {
        $employees = Employee::with('user')
            ->whereNotNull('date_hired')
            ->orderByDesc('date_hired')
            ->get()
            ->map(function ($emp) {
                $checklists = OnboardingChecklist::where('employee_id', $emp->id)->orderBy('sort_order')->get();
                $total = $checklists->count();
                $completed = $checklists->where('is_completed', true)->count();

                return [
                    'id' => $emp->id,
                    'name' => $emp->full_name,
                    'date_hired' => optional($emp->date_hired)->format('M d, Y'),
                    'total' => $total,
                    'completed' => $completed,
                    'progress' => $total > 0 ? round(($completed / $total) * 100) : 0,
                    'checklist' => $checklists->map(fn ($c) => [
                        'id' => $c->id,
                        'title' => $c->title,
                        'category' => $c->category,
                        'is_completed' => $c->is_completed,
                        'completed_at' => optional($c->completed_at)?->diffForHumans(),
                    ]),
                ];
            });

        return Inertia::render('HR/Onboarding/Index', [
            'employees' => $employees,
        ]);
    }

    public function initChecklist(Request $request, int $employeeId)
    {
        $employee = Employee::findOrFail($employeeId);

        // Don't re-initialize if already has items
        if (OnboardingChecklist::where('employee_id', $employee->id)->exists()) {
            return back()->with('info', 'Checklist already initialized.');
        }

        foreach ($this->defaultItems() as $item) {
            OnboardingChecklist::create(array_merge($item, ['employee_id' => $employee->id]));
        }

        return back()->with('success', 'Onboarding checklist initialized.');
    }

    public function toggleItem(Request $request, int $id)
    {
        $item = OnboardingChecklist::findOrFail($id);
        $item->is_completed = ! $item->is_completed;
        $item->completed_at = $item->is_completed ? now() : null;
        $item->save();

        return back();
    }

    // ========== Offboarding ==========

    /**
     * Default clearance departments.
     */
    private function defaultClearanceDepts(): array
    {
        return [
            ['department' => 'HR', 'title' => 'HR clearance (final pay, benefits, exit interview)'],
            ['department' => 'IT', 'title' => 'IT clearance (return laptop, revoke system access)'],
            ['department' => 'Finance', 'title' => 'Finance clearance (accountabilities, cash advances)'],
            ['department' => 'Admin', 'title' => 'Admin clearance (return ID, keys, equipment)'],
            ['department' => 'Supervisor', 'title' => 'Immediate supervisor clearance (turnover of duties)'],
        ];
    }

    public function offboardingIndex()
    {
        $employees = Employee::with('user')
            ->orderByDesc('id')
            ->get()
            ->map(function ($emp) {
                $clearances = OffboardingClearance::where('employee_id', $emp->id)->get();
                if ($clearances->isEmpty()) {
                    return null;
                }

                $total = $clearances->count();
                $cleared = $clearances->where('status', 'cleared')->count();

                return [
                    'id' => $emp->id,
                    'name' => $emp->full_name,
                    'total' => $total,
                    'cleared' => $cleared,
                    'progress' => $total > 0 ? round(($cleared / $total) * 100) : 0,
                    'clearances' => $clearances->map(fn ($c) => [
                        'id' => $c->id,
                        'department' => $c->department,
                        'title' => $c->title,
                        'status' => $c->status,
                        'remarks' => $c->remarks,
                        'cleared_at' => optional($c->cleared_at)?->diffForHumans(),
                    ]),
                ];
            })
            ->filter()
            ->values();

        return Inertia::render('HR/Offboarding/Index', [
            'employees' => $employees,
        ]);
    }

    public function initClearance(Request $request, int $employeeId)
    {
        $employee = Employee::findOrFail($employeeId);

        if (OffboardingClearance::where('employee_id', $employee->id)->exists()) {
            return back()->with('info', 'Clearance already initialized.');
        }

        foreach ($this->defaultClearanceDepts() as $item) {
            OffboardingClearance::create(array_merge($item, ['employee_id' => $employee->id]));
        }

        return back()->with('success', 'Offboarding clearance initialized.');
    }

    public function updateClearance(Request $request, int $id)
    {
        $request->validate([
            'status' => 'required|in:pending,cleared,flagged',
            'remarks' => 'nullable|string|max:500',
        ]);

        $clearance = OffboardingClearance::findOrFail($id);
        $clearance->status = $request->input('status');
        $clearance->remarks = $request->input('remarks');
        $clearance->cleared_at = $request->input('status') === 'cleared' ? now() : null;
        $clearance->save();

        return back();
    }
}
