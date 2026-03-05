<?php

use App\Features\AIChatbot\Services\AIChatbotSuggestionService;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('returns rich answer text for suggestions with explicit answers', function (): void {
    $user = new User([
        'role' => 'employee',
    ]);

    $service = app(AIChatbotSuggestionService::class);

    $question = 'RA 6713 norms of conduct';
    $answer = $service->answerForUser($user, 'emp_code_of_conduct', $question);

    expect($answer)->not()->toBe('');
    expect($answer)->not()->toBe('What are the eight norms of conduct under RA 6713?');
    expect(mb_strtolower(trim($answer)))->not()->toBe(mb_strtolower(trim($question)));
});
