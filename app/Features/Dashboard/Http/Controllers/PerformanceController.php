<?php

namespace App\Features\Dashboard\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PerformanceController extends Controller
{
    public function index(Request $request): \Inertia\Response
    {
        $memoryBytes = memory_get_peak_usage(true);
        $memoryMb = round($memoryBytes / 1024 / 1024, 2);

        return Inertia::render('Admin/Performance/Index', [
            'metrics' => [
                'memory_peak_mb' => $memoryMb,
                'php_version' => PHP_VERSION,
                'laravel_version' => \Illuminate\Foundation\Application::VERSION,
            ],
        ]);
    }
}
