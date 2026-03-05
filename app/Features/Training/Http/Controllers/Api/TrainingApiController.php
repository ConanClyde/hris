<?php

namespace App\Features\Training\Http\Controllers\Api;

use App\Features\Training\Events\TrainingStatusUpdated;
use App\Features\Training\Http\Requests\UpdateTrainingStatusRequest;
use App\Features\Training\Models\Training;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TrainingApiController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Training::query();

        if ($employeeFk = $request->query('employee_fk')) {
            $query->where('employee_fk', (int) $employeeFk);
        } elseif ($employeeId = (string) $request->query('employee_id', '')) {
            $query->where('employee_id', $employeeId);
        }

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

    public function updateStatus(UpdateTrainingStatusRequest $request, int $id): JsonResponse
    {
        $training = Training::findOrFail($id);
        $training->status = $request->validated()['status'];
        $training->save();

        event(new TrainingStatusUpdated(
            id: $training->id,
            employeeId: (string) ($training->employee_fk ?? $training->employee_id ?? ''),
            employeeName: $training->employee_name,
            status: $training->status,
            title: $training->title,
            dateFrom: $training->date_from->toDateString(),
            hours: (float) $training->hours,
        ));

        return response()->json($training);
    }
}
