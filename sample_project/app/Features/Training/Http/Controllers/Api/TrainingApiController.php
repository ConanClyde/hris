<?php

namespace App\Features\Training\Http\Controllers\Api;

use App\Features\Training\Events\TrainingStatusUpdated;
use App\Features\Training\Models\Training;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TrainingApiController extends Controller
{
    public function index(Request $request)
    {
        $query = Training::query();

        if ($search = (string) $request->query('search', '')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('employee_name', 'like', "%{$search}%")
                    ->orWhere('employee_id', 'like', "%{$search}%");
            });
        }

        if ($type = (string) $request->query('type', '')) {
            $query->where('type', $type);
        }

        if ($category = (string) $request->query('category', '')) {
            $query->where('category', $category);
        }

        if ($status = (string) $request->query('status', '')) {
            $query->where('status', $status);
        }

        $perPage = (int) $request->query('per_page', 10);

        return response()->json(
            $query->orderByDesc('date_from')->paginate($perPage)
        );
    }

    public function updateStatus(Request $request, int $id)
    {
        $data = $request->validate([
            'status' => 'required|string|in:pending,approved,rejected',
        ]);

        $training = Training::findOrFail($id);
        $training->status = $data['status'];
        $training->save();

        event(new TrainingStatusUpdated(
            id: $training->id,
            employeeId: $training->employee_id ?? '',
            employeeName: $training->employee_name,
            status: $training->status,
            title: $training->title,
            dateFrom: $training->date_from->toDateString(),
            hours: (float) $training->hours,
        ));

        return response()->json($training);
    }
}
