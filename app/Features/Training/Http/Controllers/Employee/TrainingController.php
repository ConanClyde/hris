<?php

namespace App\Features\Training\Http\Controllers\Employee;

use App\Events\TrainingAssigned;
use App\Features\Training\Models\Training;
use App\Http\Controllers\Controller;
use App\Mail\TrainingAssignedMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;

class TrainingController extends Controller
{
    private function getEmployeeId()
    {
        return Auth::user()?->employee?->id;
    }

    public function index(Request $request)
    {
        $employeeId = $this->getEmployeeId();

        if (! $employeeId) {
            return Inertia::render('Employee/Training/Index', [
                'trainings' => [
                    'data' => [],
                    'current_page' => 1,
                    'last_page' => 1,
                    'links' => [],
                ],
                'statusOptions' => [
                    'pending' => 'Pending',
                    'approved' => 'Approved',
                    'rejected' => 'Rejected',
                ],
                'filters' => $request->only(['type', 'status']),
            ]);
        }

        $query = Training::query()
            ->where('employee_fk', $employeeId);

        if ($request->filled('type')) {
            $query->where('type', $request->input('type'));
        }
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        $appendQuery = collect($request->query())->reject(fn ($v) => $v === 'all')->all();
        $trainings = $query->orderByDesc('created_at')
            ->paginate(10)
            ->appends($appendQuery);

        $statusOptions = [
            'pending' => 'Pending',
            'approved' => 'Approved',
            'rejected' => 'Rejected',
        ];

        return Inertia::render('Employee/Training/Index', [
            'trainings' => $trainings,
            'statusOptions' => $statusOptions,
            'filters' => $request->only(['type', 'status']),
        ]);
    }

    public function store(Request $request)
    {
        $employeeId = $this->getEmployeeId();
        if (! $employeeId) {
            abort(403, 'User is not linked to an employee record.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'provider' => 'nullable|string|max:255',
            'date_from' => 'required|date',
            'date_to' => 'nullable|date|after_or_equal:date_from',
            'hours' => 'nullable|numeric|min:0',
            'type' => 'nullable|string|max:255',
            'category' => 'nullable|string|max:255',
        ]);

        $training = Training::create(array_merge($validated, [
            'employee_id' => $employeeId,
            'employee_fk' => $employeeId,
            'employee_name' => Auth::user()->name,
            'status' => 'pending',
        ]));

        // Notify HR (if applicable, though usually broadcasting is for employees)
        // For now, consistent with HR controller's broadcasting pattern
        broadcast(new TrainingAssigned($training, Auth::id()))->toOthers();

        // Email the employee about their self-submitted training record
        $user = Auth::user();
        if ($user && $user->email) {
            $assignedBy = $user->name;
            Mail::to($user->email)->queue(
                new TrainingAssignedMail($training, $training->employee_name, $assignedBy)
            );
        }

        return redirect()->route('employee.training.index')->with('success', 'Training record submitted.');
    }

    public function update(Request $request, $id)
    {
        $employeeId = $this->getEmployeeId();
        $training = Training::where('id', $id)->where('employee_fk', $employeeId)->firstOrFail();

        // Check if training record can be updated (e.g. only if pending)
        if ($training->status !== 'pending') {
            return back()->with('error', 'Cannot edit training record that has been processed.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'provider' => 'nullable|string|max:255',
            'date_from' => 'required|date',
            'date_to' => 'nullable|date|after_or_equal:date_from',
            'hours' => 'nullable|numeric|min:0',
            'type' => 'nullable|string|max:255',
            'category' => 'nullable|string|max:255',
        ]);

        $training->update($validated);

        return redirect()->route('employee.training.index')->with('success', 'Training record updated.');
    }

    public function destroy($id)
    {
        $employeeId = $this->getEmployeeId();
        $training = Training::where('id', $id)->where('employee_fk', $employeeId)->firstOrFail();

        if ($training->status !== 'pending') {
            return back()->with('error', 'Cannot delete training record that has been processed.');
        }

        $training->delete();

        return redirect()->route('employee.training.index')->with('success', 'Training record deleted.');
    }

    public function export(Request $request)
    {
        $employeeId = $this->getEmployeeId();

        $query = Training::where('employee_fk', $employeeId)->orderByDesc('created_at');

        return response()->streamDownload(function () use ($query) {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['Title', 'Provider', 'Start Date', 'End Date', 'Hours', 'Status']);
            $query->chunkById(500, function ($rows) use ($out): void {
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
            });
            fclose($out);
        }, 'my-trainings.csv', ['Content-Type' => 'text/csv']);
    }

    public function deleteAttachment($id)
    {
        $employeeId = Auth::user()?->employee?->id;
        $training = Training::where('id', $id)->where('employee_fk', $employeeId)->first();

        if (! $training) {
            return response()->json(['success' => false, 'message' => 'Training not found or unauthorized'], 404);
        }

        return response()->json(['success' => true, 'message' => 'Attachment deleted']);
    }
}
