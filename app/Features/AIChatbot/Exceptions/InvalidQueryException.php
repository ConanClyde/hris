<?php

declare(strict_types=1);

namespace App\Features\AIChatbot\Exceptions;

class InvalidQueryException extends AIChatbotException
{
    public function __construct(
        string $message = 'Invalid query provided',
        ?\Exception $previous = null,
        array $context = []
    ) {
        parent::__construct($message, 400, $previous, $context);
    }
}
