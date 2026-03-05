<?php

namespace App\Features\Notices\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class NoticeApiController extends Controller
{
    public function active(): JsonResponse
    {
        return response()->json([]);
    }
}
