<?php

declare(strict_types=1);

namespace App\Features\AIChatbot\Exceptions;

class RateLimitExceededException extends AIChatbotException
{
    public function __construct(
        string $message = 'Rate limit exceeded. Please try again later.',
        ?\Exception $previous = null,
        array $context = []
    ) {
        parent::__construct($message, 429, $previous, $context);
    }
}
