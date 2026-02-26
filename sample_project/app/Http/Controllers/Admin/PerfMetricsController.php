<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PerfMetric;
use Illuminate\Http\Request;

class PerfMetricsController extends Controller
{
    public function index(Request $request)
    {
        $query = PerfMetric::query()->orderByDesc('created_at');

        if ($request->filled('route')) {
            $query->where('route', 'like', '%'.$request->input('route').'%');
        }

        if ($request->filled('budget_exceeded')) {
            $query->where('budget_exceeded', (bool) $request->boolean('budget_exceeded'));
        }

        if ($request->filled('role')) {
            $query->where('role', $request->input('role'));
        }

        $metrics = $query
            ->orderByDesc('budget_exceeded')
            ->paginate(25)
            ->appends($request->query());

        return view('admin.performance.index', [
            'metrics' => $metrics,
        ]);
    }
}
