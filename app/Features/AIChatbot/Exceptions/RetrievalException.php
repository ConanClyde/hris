<?php

declare(strict_types=1);

namespace App\Features\AIChatbot\Exceptions;

class RetrievalException extends AIChatbotException
{
    public function __construct(
        string $message = 'Failed to retrieve relevant information',
        ?\Exception $previous = null,
        array $context = []
    ) {
        parent::__construct($message, 500, $previous, $context);
    }
}
