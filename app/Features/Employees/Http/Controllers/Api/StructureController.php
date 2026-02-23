<?php

namespace App\Features\Employees\Http\Controllers\Api;

use App\Features\Employees\Models\Division;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class StructureController extends Controller
{
    public function index(): JsonResponse
    {
        $structure = Division::with(['subdivisions.sections', 'sections'])
            ->get()
            ->map(function ($division) {
                return [
                    'id' => $division->id,
                    'name' => $division->name,
                    'subdivisions' => $division->subdivisions->map(function ($sub) {
                        return [
                            'id' => $sub->id,
                            'name' => $sub->name,
                            'sections' => $sub->sections->map(function ($sec) {
                                return [
                                    'id' => $sec->id,
                                    'name' => $sec->name,
                                ];
                            }),
                        ];
                    }),
                    'sections' => $division->sections->map(function ($sec) {
                        return [
                            'id' => $sec->id,
                            'name' => $sec->name,
                        ];
                    }),
                ];
            });

        return response()->json($structure);
    }
}
