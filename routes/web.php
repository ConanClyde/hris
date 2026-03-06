<?php

use App\Features\Auth\Http\Controllers\ForceChangePasswordController;
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use Laravel\Fortify\Http\Controllers\NewPasswordController;
use Laravel\Fortify\Http\Controllers\PasswordResetLinkController;

Route::get('/', function () {
    return redirect()->route('login');
})->name('home');

// Generic /dashboard redirects to role-appropriate dashboard
Route::get('dashboard', function () {
    if (! Auth::check()) {
        return redirect()->route('login');
    }

    $user = Auth::user();
    if ($user->isAdmin()) {
        return redirect()->route('admin.dashboard');
    }
    if ($user->isHr()) {
        return redirect()->route('hr.dashboard');
    }

    return redirect()->route('employee.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::get('/force-change-password', [ForceChangePasswordController::class, 'show'])
        ->name('force-password.show');
    Route::post('/force-change-password', [ForceChangePasswordController::class, 'update'])
        ->name('force-password.update');
});

// Shared AI Chatbot
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/ai-chatbot', function () {
        return Inertia\Inertia::render('Shared/AIChatbot/Index');
    })->name('ai-chatbot');
    Route::post('/ai-chatbot/chat', [\App\Features\AIChatbot\Http\Controllers\AIChatbotController::class, 'chat'])
        ->middleware(\App\Http\Middleware\AIChatbotRateLimit::class.':chat')
        ->name('ai-chatbot.chat');
    Route::post('/ai-chatbot/chat/stream', [\App\Features\AIChatbot\Http\Controllers\AIChatbotController::class, 'chatStream'])
        ->middleware(\App\Http\Middleware\AIChatbotRateLimit::class.':stream')
        ->name('ai-chatbot.chat.stream');
    Route::get('/ai-chatbot/context', [\App\Features\AIChatbot\Http\Controllers\AIChatbotController::class, 'context'])
        ->middleware('throttle:chatbot')
        ->name('ai-chatbot.context');
    Route::get('/api/ai/context', [\App\Features\AIChatbot\Http\Controllers\AIChatbotDataController::class, 'context'])
        ->middleware('throttle:chatbot')
        ->name('api.ai.context');
    Route::get('/api/ai/users', [\App\Features\AIChatbot\Http\Controllers\AIChatbotDataController::class, 'users'])
        ->middleware('throttle:chatbot')
        ->name('api.ai.users');
    Route::get('/api/ai/leave-applications', [\App\Features\AIChatbot\Http\Controllers\AIChatbotDataController::class, 'leaveApplications'])
        ->middleware('throttle:chatbot')
        ->name('api.ai.leave-applications');
    Route::get('/api/ai/trainings', [\App\Features\AIChatbot\Http\Controllers\AIChatbotDataController::class, 'trainings'])
        ->middleware('throttle:chatbot')
        ->name('api.ai.trainings');
    Route::get('/api/ai/notices', [\App\Features\AIChatbot\Http\Controllers\AIChatbotDataController::class, 'notices'])
        ->middleware('throttle:chatbot')
        ->name('api.ai.notices');
    Route::get('/api/ai/health', [\App\Features\AIChatbot\Http\Controllers\AIChatbotDataController::class, 'health'])
        ->middleware('throttle:chatbot')
        ->name('api.ai.health');
    Route::get('/api/ai/suggestions', [\App\Features\AIChatbot\Http\Controllers\AIChatbotDataController::class, 'suggestions'])
        ->middleware('throttle:chatbot')
        ->name('api.ai.suggestions');
    Route::post('/api/ai/suggestions/answer', [\App\Features\AIChatbot\Http\Controllers\AIChatbotDataController::class, 'suggestionAnswer'])
        ->middleware('throttle:chatbot')
        ->name('api.ai.suggestions.answer');
    Route::post('/api/ai/feedback', [\App\Features\AIChatbot\Http\Controllers\AIChatbotDataController::class, 'feedback'])
        ->middleware('throttle:chatbot')
        ->name('api.ai.feedback');

    // Personal Insights endpoints (accessible to all authenticated users)
    Route::get('/api/ai/insights/personal', [\App\Features\AIChatbot\Http\Controllers\AIChatbotDataController::class, 'personalInsights'])
        ->middleware('throttle:chatbot')
        ->name('api.ai.insights.personal');
    Route::get('/api/ai/insights/feedback', [\App\Features\AIChatbot\Http\Controllers\AIChatbotDataController::class, 'personalFeedback'])
        ->middleware('throttle:chatbot')
        ->name('api.ai.insights.feedback');

    Route::get('/ai-chatbot/policy/{filename}', [\App\Features\AIChatbot\Http\Controllers\AIChatbotDataController::class, 'policy'])
        ->middleware('throttle:chatbot')
        ->name('ai-chatbot.policy');
    Route::middleware(['throttle:chatbot', 'role:admin'])->group(function () {
        Route::get('/api/ai/activity-logs', [\App\Features\AIChatbot\Http\Controllers\AIChatbotDataController::class, 'activityLogs'])
            ->name('api.ai.activity-logs');
        Route::post('/api/ai/ingest', [\App\Features\AIChatbot\Http\Controllers\AIChatbotDataController::class, 'ingest'])
            ->name('api.ai.ingest');
        Route::get('/api/ai/ingest-logs', [\App\Features\AIChatbot\Http\Controllers\AIChatbotDataController::class, 'ingestLogs'])
            ->name('api.ai.ingest-logs');
        Route::get('/api/ai/feedback/export', [\App\Features\AIChatbot\Http\Controllers\AIChatbotDataController::class, 'exportFeedback'])
            ->name('api.ai.feedback.export');
        Route::get('/api/ai/feedback/summary', [\App\Features\AIChatbot\Http\Controllers\AIChatbotDataController::class, 'feedbackSummary'])
            ->name('api.ai.feedback.summary');
        Route::get('/api/ai/analytics', [\App\Features\AIChatbot\Http\Controllers\AIChatbotDataController::class, 'analytics'])
            ->name('api.ai.analytics');
        Route::get('/api/ai/metrics/export', [\App\Features\AIChatbot\Http\Controllers\AIChatbotDataController::class, 'exportMetrics'])
            ->name('api.ai.metrics.export');
        Route::get('/api/ai/policy-coverage', [\App\Features\AIChatbot\Http\Controllers\AIChatbotDataController::class, 'policyCoverage'])
            ->name('api.ai.policy-coverage');
        Route::get('/api/ai/enhancement-framework', [\App\Features\AIChatbot\Http\Controllers\AIChatbotDataController::class, 'enhancementFramework'])
            ->name('api.ai.enhancement-framework');
    });

    // Conversation/Chat History Routes
    Route::get('/api/ai/conversations', [\App\Features\AIChatbot\Http\Controllers\AIChatbotConversationController::class, 'index'])
        ->middleware('throttle:chatbot')
        ->name('api.ai.conversations.index');
    Route::post('/api/ai/conversations', [\App\Features\AIChatbot\Http\Controllers\AIChatbotConversationController::class, 'store'])
        ->middleware('throttle:chatbot')
        ->name('api.ai.conversations.store');
    Route::get('/api/ai/conversations/{id}', [\App\Features\AIChatbot\Http\Controllers\AIChatbotConversationController::class, 'show'])
        ->middleware('throttle:chatbot')
        ->name('api.ai.conversations.show');
    Route::put('/api/ai/conversations/{id}', [\App\Features\AIChatbot\Http\Controllers\AIChatbotConversationController::class, 'update'])
        ->middleware('throttle:chatbot')
        ->name('api.ai.conversations.update');
    Route::delete('/api/ai/conversations/{id}', [\App\Features\AIChatbot\Http\Controllers\AIChatbotConversationController::class, 'destroy'])
        ->middleware('throttle:chatbot')
        ->name('api.ai.conversations.destroy');
    Route::post('/api/ai/conversations/{id}/archive', [\App\Features\AIChatbot\Http\Controllers\AIChatbotConversationController::class, 'archive'])
        ->middleware('throttle:chatbot')
        ->name('api.ai.conversations.archive');
    Route::post('/api/ai/conversations/{id}/restore', [\App\Features\AIChatbot\Http\Controllers\AIChatbotConversationController::class, 'restore'])
        ->middleware('throttle:chatbot')
        ->name('api.ai.conversations.restore');
    Route::post('/api/ai/conversations/{id}/messages', [\App\Features\AIChatbot\Http\Controllers\AIChatbotConversationController::class, 'storeMessage'])
        ->middleware('throttle:chatbot')
        ->name('api.ai.conversations.messages.store');
    Route::get('/api/ai/conversations/{id}/messages/recent', [\App\Features\AIChatbot\Http\Controllers\AIChatbotConversationController::class, 'recentMessages'])
        ->middleware('throttle:chatbot')
        ->name('api.ai.conversations.messages.recent');

    // Employee Bulk Import/Export
    Route::get('/hr/employees/template', [\App\Features\Employees\Http\Controllers\HR\EmployeeBulkController::class, 'downloadTemplate'])
        ->middleware('throttle:bulk')
        ->name('hr.employees.template');
    Route::post('/hr/employees/import', [\App\Features\Employees\Http\Controllers\HR\EmployeeBulkController::class, 'import'])
        ->middleware('throttle:bulk')
        ->name('hr.employees.import');
    Route::get('/hr/employees/export', [\App\Features\Employees\Http\Controllers\HR\EmployeeBulkController::class, 'export'])
        ->middleware('throttle:export')
        ->name('hr.employees.export');
});

if (Features::enabled(Features::registration())) {
    Route::get('/register', [RegisterController::class, 'create'])
        ->middleware(['guest'])
        ->name('register');
    Route::post('/register', [RegisterController::class, 'store'])
        ->middleware(['guest'])
        ->name('register.store');
}

Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])
    ->middleware(['guest', 'throttle:password-reset'])
    ->name('password.email');
Route::post('/reset-password', [NewPasswordController::class, 'store'])
    ->middleware(['guest', 'throttle:password-reset'])
    ->name('password.update');

require __DIR__.'/settings.php';
require __DIR__.'/web/dashboard.php';
require __DIR__.'/web/admin.php';
require __DIR__.'/web/hr.php';

Route::middleware(['auth', 'verified', 'role:employee'])->prefix('employee')->name('employee.')->group(function () {
    Route::post('/pds/parse', [\App\Features\Pds\Http\Controllers\Employee\PdsController::class, 'parsePdf'])->name('pds.parse');
});

require __DIR__.'/web/employee.php';
