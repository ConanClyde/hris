<?php

namespace App\Features\Training\Http\Controllers\HR;

use App\Features\ActivityLogs\Services\ActivityLogger;
use App\Features\Employees\Models\Employee;
use App\Features\Training\Events\TrainingStatusUpdated;
use App\Features\Training\Models\Training;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TrainingController extends Controller
{
    public function __construct(protected ActivityLogger $logger) {}

    public function index(Request $request)
    {

        $query = Training::query();

        if ($request->filled('search')) {
            $search = strtolower((string) $request->input('search'));
            $query->where(function ($q) use ($search) {
                $q->whereRaw('LOWER(title) like ?', ["%{$search}%"])
                    ->orWhereRaw('LOWER(employee_name) like ?', ["%{$search}%"])
                    ->orWhereRaw('LOWER(employee_id) like ?', ["%{$search}%"])
                    ->orWhereRaw('LOWER(provider) like ?', ["%{$search}%"]);
            });
        }

        if ($request->filled('type')) {
            $query->where('type', (string) $request->input('type'));
        }

        if ($request->filled('category')) {
            $query->where('category', (string) $request->input('category'));
        }

        $trainings = $query
            ->orderByDesc('date_from')
            ->orderByDesc('created_at')
            ->paginate(10)
            ->appends($request->query());

        $types = ['Technical', 'Managerial', 'Supervisory', 'Foundational'];
        $categories = ['Internal', 'External'];

        return view('features.training.hr.index', [
            'trainings' => $trainings,
            'types' => $types,
            'categories' => $categories,
            'employees' => $this->employees(),
        ]);
    }

    public function store(Request $request)
    {

        $validated = $request->validate([
            'employee_id' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'category' => 'nullable|string|max:255',
            'title' => 'required|string|max:255',
            'provider' => 'nullable|string|max:255',
            'date_from' => 'required|date',
            'date_to' => 'required|date|after_or_equal:date_from',
            'time_from' => 'nullable|date_format:H:i',
            'time_to' => 'nullable|date_format:H:i',
            'hours' => 'nullable|numeric|min:0',
            'fee' => 'nullable|numeric|min:0',
            'participants' => 'nullable|string',
            'status' => 'nullable|in:pending,approved,rejected',
        ]);

        $training = Training::create([
            'employee_id' => $validated['employee_id'],
            'title' => $validated['title'],
            'date_from' => $validated['date_from'],
            'date_to' => $validated['date_to'],
            'time_from' => $validated['time_from'] ?? null,
            'time_to' => $validated['time_to'] ?? null,
            'hours' => (float) ($validated['hours'] ?? 0),
            'type' => $validated['type'],
            'provider' => $validated['provider'] ?? null,
            'category' => $validated['category'] ?? null,
            'fee' => (float) ($validated['fee'] ?? 0),
            'status' => $validated['status'] ?? 'pending',
            'participants' => $validated['participants'] ?? null,
        ]);

        event(new TrainingStatusUpdated(
            id: (int) $training->id,
            employeeId: (string) ($training->employee_id ?? ''),
            employeeName: null,
            status: (string) $training->status,
            title: (string) $training->title,
            dateFrom: (string) $training->date_from->toDateString(),
            hours: (float) $training->hours,
        ));

        $this->logger->logCreate('training', $training->id, [
            'employee_id' => $validated['employee_id'],
            'title' => $validated['title'],
            'date_from' => $validated['date_from'],
            'hours' => $validated['hours'] ?? 0,
        ]);

        return redirect()->route('hr.training.index')->with('success', 'Training record created successfully.');
    }

    public function update(Request $request, $id)
    {

        $validated = $request->validate([
            'employee_id' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'category' => 'nullable|string|max:255',
            'title' => 'required|string|max:255',
            'provider' => 'nullable|string|max:255',
            'date_from' => 'required|date',
            'date_to' => 'required|date|after_or_equal:date_from',
            'time_from' => 'nullable|date_format:H:i',
            'time_to' => 'nullable|date_format:H:i',
            'hours' => 'nullable|numeric|min:0',
            'fee' => 'nullable|numeric|min:0',
            'participants' => 'nullable|string',
            'status' => 'nullable|in:pending,approved,rejected',
        ]);

        $training = Training::findOrFail((int) $id);
        $training->update([
            'employee_id' => $validated['employee_id'],
            'title' => $validated['title'],
            'date_from' => $validated['date_from'],
            'date_to' => $validated['date_to'],
            'time_from' => $validated['time_from'] ?? null,
            'time_to' => $validated['time_to'] ?? null,
            'hours' => (float) ($validated['hours'] ?? $training->hours ?? 0),
            'type' => $validated['type'],
            'provider' => $validated['provider'] ?? null,
            'category' => $validated['category'] ?? null,
            'fee' => (float) ($validated['fee'] ?? $training->fee ?? 0),
            'status' => $validated['status'] ?? $training->status,
            'participants' => $validated['participants'] ?? null,
        ]);

        event(new TrainingStatusUpdated(
            id: (int) $training->id,
            employeeId: (string) ($training->employee_id ?? ''),
            employeeName: null,
            status: (string) $training->status,
            title: (string) $training->title,
            dateFrom: (string) $training->date_from->toDateString(),
            hours: (float) $training->hours,
        ));

        $this->logger->logStatusChange('training', $training->id, 'pending', $validated['status'] ?? 'approved');

        return redirect()->route('hr.training.index')->with('success', 'Training record updated successfully.');
    }

    public function destroy($id)
    {

        $training = Training::findOrFail((int) $id);
        $training->delete();

        $this->logger->logDelete('training', $training->id);

        return redirect()->route('hr.training.index')->with('success', 'Training record deleted successfully.');
    }

    private function employees()
    {
        return Employee::where('status', 'active')
            ->select('id', 'first_name', 'last_name', 'middle_name')
            ->get()
            ->map(fn ($e) => (object) [
                'id' => $e->id,
                'name' => trim($e->first_name.' '.($e->middle_name ? $e->middle_name.' ' : '').$e->last_name),
            ]);
    }
}
