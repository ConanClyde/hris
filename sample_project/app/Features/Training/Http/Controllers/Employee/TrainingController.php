<?php

namespace App\Features\Training\Http\Controllers\Employee;

use App\Features\Training\Models\Training;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;

class TrainingController extends Controller
{
    public function index(Request $request)
    {
        $employeeMap = [
            'employee01' => 'EMP-001',
        ];

        $userId = (string) Auth::id();
        $employeeId = $employeeMap[$userId] ?? null;

        $query = Training::query();

        if ($employeeId) {
            $query->where('employee_id', $employeeId);
        }

        if ($request->filled('search')) {
            $search = strtolower((string) $request->input('search'));
            $query->where(function ($q) use ($search) {
                $q->whereRaw('LOWER(title) like ?', ["%{$search}%"])
                    ->orWhereRaw('LOWER(provider) like ?', ["%{$search}%"]);
            });
        }

        if ($request->filled('type')) {
            $query->where('type', (string) $request->input('type'));
        }

        if ($request->filled('category')) {
            $query->where('category', (string) $request->input('category'));
        }

        $page = (int) $request->input('page', 1);
        $perPage = 10;
        $paginator = $query
            ->orderByDesc('date_from')
            ->orderByDesc('created_at')
            ->paginate($perPage, ['*'], 'page', $page)
            ->appends($request->query());

        $items = collect($paginator->items())->map(function (Training $training) {
            return (object) [
                'id' => $training->id,
                'title' => $training->title,
                'date_from' => optional($training->date_from)->toDateString(),
                'date_to' => optional($training->date_to)->toDateString(),
                'time_from' => optional($training->time_from)->format('H:i'),
                'time_to' => optional($training->time_to)->format('H:i'),
                'hours' => $training->hours,
                'type' => $training->type,
                'provider' => $training->provider,
                'category' => $training->category,
                'fee' => $training->fee,
                'status' => $training->status,
                'participants' => $training->participants,
                'attachments' => [],
            ];
        });

        $paginatedTrainings = new LengthAwarePaginator(
            $items,
            $paginator->total(),
            $paginator->perPage(),
            $paginator->currentPage(),
            ['path' => $request->url(), 'query' => $request->query()]
        );

        $types = ['Technical', 'Managerial', 'Supervisory', 'Foundational'];

        return view('features.training.employee.index', compact('paginatedTrainings', 'types'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
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
        ]);

        $employeeMap = [
            'employee01' => 'EMP-001',
        ];

        $userId = (string) Auth::id();
        $employeeId = $employeeMap[$userId] ?? 'EMP-001';

        Training::create([
            'employee_id' => $employeeId,
            'employee_name' => null,
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
            'status' => 'pending',
            'participants' => $validated['participants'] ?? null,
        ]);

        return redirect()->route('employee.training.index')->with('success', 'Training record submitted successfully.');
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
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
        ]);

        $training = Training::findOrFail((int) $id);
        $training->update([
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
            'participants' => $validated['participants'] ?? null,
        ]);

        return redirect()->route('employee.training.index')->with('success', 'Training record updated successfully.');
    }

    public function destroy($id)
    {
        $training = Training::findOrFail((int) $id);
        $training->delete();

        return redirect()->route('employee.training.index')->with('success', 'Training record deleted successfully.');
    }

    public function deleteAttachment($id)
    {
        return response()->json(['success' => true, 'message' => 'Attachment deleted']);
    }

    public function export()
    {
        return redirect()->route('employee.training.index')->with('success', 'Training records exported to CSV.');
    }
}
