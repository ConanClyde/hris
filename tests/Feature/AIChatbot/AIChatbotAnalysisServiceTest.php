<?php

use App\Features\AIChatbot\Services\AIChatbotAnalysisService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;

uses(RefreshDatabase::class);

beforeEach(function () {
    config(['services.ollama.base_url' => 'http://127.0.0.1:11434']);
    config(['services.ollama.model' => 'llama3.2:3b']);
});

it('returns parsed analysis when LLM returns valid JSON', function () {
    $json = json_encode([
        'requires_google_calendar' => true,
        'requires_database' => ['leave_applications'],
        'requires_markdown_prompts' => ['csc_leave_policies'],
        'min_required_role' => 'hr',
        'topic_summary' => 'leave balance inquiry',
    ]);

    Http::fake([
        '*/api/chat' => Http::response([
            'message' => ['content' => $json],
        ], 200),
    ]);

    $service = app(AIChatbotAnalysisService::class);
    $result = $service->analyzePrompt('What is my leave balance?', 'employee');

    expect($result)->not->toBeNull()
        ->and($result['requires_google_calendar'])->toBeTrue()
        ->and($result['requires_database'])->toBe(['leave_applications'])
        ->and($result['requires_markdown_prompts'])->toBe(['csc_leave_policies'])
        ->and($result['min_required_role'])->toBe('hr')
        ->and($result['topic_summary'])->toBe('leave balance inquiry');
});

it('returns null when HTTP request fails', function () {
    Http::fake([
        '*/api/chat' => Http::response(null, 500),
    ]);

    $service = app(AIChatbotAnalysisService::class);
    $result = $service->analyzePrompt('What holidays are coming?', 'admin');

    expect($result)->toBeNull();
});

it('returns null when response body is empty', function () {
    Http::fake([
        '*/api/chat' => Http::response([
            'message' => ['content' => ''],
        ], 200),
    ]);

    $service = app(AIChatbotAnalysisService::class);
    $result = $service->analyzePrompt('Hello', 'employee');

    expect($result)->toBeNull();
});

it('returns null when response is invalid JSON', function () {
    Http::fake([
        '*/api/chat' => Http::response([
            'message' => ['content' => 'This is not JSON at all'],
        ], 200),
    ]);

    $service = app(AIChatbotAnalysisService::class);
    $result = $service->analyzePrompt('Random question', 'employee');

    expect($result)->toBeNull();
});

it('parses JSON wrapped in markdown code blocks', function () {
    $json = '{"requires_google_calendar":false,"requires_database":[],"requires_markdown_prompts":["dtr_policies"],"min_required_role":"employee","topic_summary":"dtr policy"}';
    $wrapped = "```json\n{$json}\n```";

    Http::fake([
        '*/api/chat' => Http::response([
            'message' => ['content' => $wrapped],
        ], 200),
    ]);

    $service = app(AIChatbotAnalysisService::class);
    $result = $service->analyzePrompt('DTR policy?', 'employee');

    expect($result)->not->toBeNull()
        ->and($result['requires_markdown_prompts'])->toBe(['dtr_policies'])
        ->and($result['min_required_role'])->toBe('employee');
});

it('userHasRequiredRole returns true when user role meets or exceeds required', function () {
    $service = app(AIChatbotAnalysisService::class);

    expect($service->userHasRequiredRole('admin', 'employee'))->toBeTrue()
        ->and($service->userHasRequiredRole('admin', 'admin'))->toBeTrue()
        ->and($service->userHasRequiredRole('hr', 'employee'))->toBeTrue()
        ->and($service->userHasRequiredRole('hr', 'hr'))->toBeTrue()
        ->and($service->userHasRequiredRole('employee', 'employee'))->toBeTrue();
});

it('userHasRequiredRole returns false when user role is lower than required', function () {
    $service = app(AIChatbotAnalysisService::class);

    expect($service->userHasRequiredRole('employee', 'hr'))->toBeFalse()
        ->and($service->userHasRequiredRole('employee', 'admin'))->toBeFalse()
        ->and($service->userHasRequiredRole('hr', 'admin'))->toBeFalse();
});
