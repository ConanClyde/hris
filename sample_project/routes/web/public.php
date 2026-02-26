<?php

use Illuminate\Support\Facades\Route;

// Public landing page
Route::get('/', function () {
    return view('landing.index');
});
