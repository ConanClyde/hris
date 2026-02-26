<?php

use App\Features\Auth\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

// Authentication
Route::view('/login', 'auth.login')->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.store')->middleware('throttle:5,1');

Route::match(['get', 'post'], '/logout', [AuthController::class, 'logout'])->name('logout');

// Registration restricted to authenticated admins
Route::middleware(['session.auth', 'role:admin'])->group(function () {
    Route::view('/register', 'auth.register')->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.store');
});

// Forgot password flow
Route::view('/forgot-password', 'auth.forgot-password')->name('password.request');
Route::view('/forgot-password/verify', 'auth.forgot-password-verify')->name('password.verify');
Route::view('/forgot-password/reset', 'auth.forgot-password-reset')->name('password.reset.form');
Route::view('/forgot-password/done', 'auth.forgot-password-done')->name('password.reset.done');
