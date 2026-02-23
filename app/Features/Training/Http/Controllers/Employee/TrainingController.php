<?php

namespace App\Features\Training\Http\Controllers\Employee;

use App\Features\Training\Models\Training;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class TrainingController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $employeeId = $user?->employee?->employee_id ?? null;

        $query = Training::query();
        if ($employeeId) {
            $query->where('employee_id', $employeeId);
        }

        $trainings = $query->orderByDesc('created_at')
            ->paginate(10)
            ->appends($request->query());

        return Inertia::render('Employee/Training/Index', [
            'trainings' => $trainings,
        ]);
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $employeeId = $user?->employee?->employee_id ?? null;

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'provider' => 'nullable|string|max:255',
            'date_from' => 'required|date',
            'date_to' => 'nullable|date|after_or_equal:date_from',
            'hours' => 'nullable|numeric|min:0',
        ]);

        Training::create(array_merge($validated, [
            'employee_id' => $employeeId,
            'status' => 'pending',
        ]));

        return redirect()->route('employee.training.index')->with('success', 'Training record submitted.');
    }

    public function update(Request $request, $id)
    {
        $training = Training::findOrFail((int) $id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'provider' => 'nullable|string|max:255',
            'date_from' => 'required|date',
            'date_to' => 'nullable|date|after_or_equal:date_from',
            'hours' => 'nullable|numeric|min:0',
        ]);

        $training->update($validated);

        return redirect()->route('employee.training.index')->with('success', 'Training record updated.');
    }

    public function destroy($id)
    {
        Training::findOrFail((int) $id)->delete();

        return redirect()->route('employee.training.index')->with('success', 'Training record deleted.');
    }

    public function export(Request $request)
    {
        $user = Auth::user();
        $employeeId = $user?->employee?->employee_id ?? null;

        $rows = Training::where('employee_id', $employeeId)->orderByDesc('created_at')->get();

        return response()->streamDownload(function () use ($rows) {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['Title', 'Provider', 'Start Date', 'End Date', 'Hours', 'Status']);
            foreach ($rows as $t) {
                fputcsv($out, [
                    $t->title ?? '',
                    $t->provider ?? '',
                    optional($t->date_from)?->toDateString() ?? '',
                    optional($t->date_to)?->toDateString() ?? '',
                    $t->hours ?? '',
                    $t->status ?? '',
                ]);
            }
            fclose($out);
        }, 'my-trainings.csv', ['Content-Type' => 'text/csv']);
    }

    public function deleteAttachment($id)
    {
        return response()->json(['success' => true, 'message' => 'Attachment deleted']);
    }
}
