<?php

declare(strict_types=1);

namespace App\Features\AIChatbot\Exceptions;

use Exception;

class AIChatbotException extends Exception
{
    /**
     * Additional context for logging/monitoring.
     */
    protected array $context = [];

    public function __construct(
        string $message = 'AI Chatbot error',
        int $code = 500,
        ?Exception $previous = null,
        array $context = []
    ) {
        parent::__construct($message, $code, $previous);
        $this->context = $context;
    }

    /**
     * Get exception context.
     */
    public function getContext(): array
    {
        return $this->context;
    }

    /**
     * Set additional context.
     */
    public function setContext(array $context): self
    {
        $this->context = array_merge($this->context, $context);

        return $this;
    }
}
