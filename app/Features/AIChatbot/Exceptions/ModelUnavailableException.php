<?php

declare(strict_types=1);

namespace App\Features\AIChatbot\Exceptions;

class ModelUnavailableException extends AIChatbotException
{
    public function __construct(
        string $message = 'AI model is currently unavailable',
        ?\Exception $previous = null,
        array $context = []
    ) {
        parent::__construct($message, 503, $previous, $context);
    }
}
