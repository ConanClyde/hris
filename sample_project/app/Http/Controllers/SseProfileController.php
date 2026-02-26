<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SseProfileController extends Controller
{
    public function stream(Request $request): Response
    {
        abort(404);
    }
}
