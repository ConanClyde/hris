<?php

namespace App\Features\Training\Http\Controllers\HR;

use App\Features\Employees\Models\Employee;
use App\Features\Training\Models\Training;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TrainingController extends Controller
{
    public function index(Request $request)
    {
        $query = Training::query();

        if ($request->filled('search')) {
            $search = strtolower((string) $request->input('search'));
            $query->where(function ($q) use ($search) {
                $q->whereRaw('LOWER(title) like ?', ["%{$search}%"])
                    ->orWhereRaw('LOWER(provider) like ?', ["%{$search}%"]);
            });
        }

        if ($request->filled('status')) {
            $query->where('status', (string) $request->input('status'));
        }

        $trainings = $query->orderByDesc('created_at')
            ->paginate(10)
            ->appends($request->query());

        $employees = Employee::query()
            ->orderBy('last_name')
            ->get()
            ->map(fn (Employee $e) => [
                'id' => (string) $e->id,
                'name' => $e->full_name,
            ]);

        return Inertia::render('HR/Training/Index', [
            'trainings' => $trainings,
            'employees' => $employees,
            'statusOptions' => ['pending' => 'Pending', 'approved' => 'Approved', 'rejected' => 'Rejected'],
            'filters' => $request->only(['search', 'status']),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|string',
            'title' => 'required|string|max:255',
            'provider' => 'nullable|string|max:255',
            'date_from' => 'required|date',
            'date_to' => 'nullable|date|after_or_equal:date_from',
            'hours' => 'nullable|numeric|min:0',
            'status' => 'in:pending,approved,rejected',
        ]);

        Training::create($validated);

        return redirect()->route('hr.training.index')->with('success', 'Training record created.');
    }

    public function update(Request $request, $id)
    {
        $training = Training::findOrFail((int) $id);

        $validated = $request->validate([
            'employee_id' => 'required|string',
            'title' => 'required|string|max:255',
            'provider' => 'nullable|string|max:255',
            'date_from' => 'required|date',
            'date_to' => 'nullable|date|after_or_equal:date_from',
            'hours' => 'nullable|numeric|min:0',
            'status' => 'in:pending,approved,rejected',
        ]);

        $training->update($validated);

        return redirect()->route('hr.training.index')->with('success', 'Training record updated.');
    }

    public function destroy($id)
    {
        Training::findOrFail((int) $id)->delete();

        return redirect()->route('hr.training.index')->with('success', 'Training record deleted.');
    }
}
