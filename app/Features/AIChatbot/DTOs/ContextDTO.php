<?php

declare(strict_types=1);

namespace App\Features\AIChatbot\DTOs;

readonly class ContextDTO
{
    public function __construct(
        public string $role,
        public array $stats,
        public string $employeeList,
        public array $employeeData,
        public array $policySnippets,
        public array $retrievalMeta,
        public string $generatedAt
    ) {}

    /**
     * Create from array.
     */
    public static function fromArray(array $data): self
    {
        return new self(
            role: (string) ($data['role'] ?? 'employee'),
            stats: $data['stats'] ?? [],
            employeeList: (string) ($data['employee_list'] ?? ''),
            employeeData: $data['employee_data'] ?? [],
            policySnippets: $data['policy_snippets'] ?? [],
            retrievalMeta: $data['retrieval_meta'] ?? [],
            generatedAt: (string) ($data['generated_at'] ?? now()->toDateTimeString())
        );
    }

    /**
     * Convert to array.
     */
    public function toArray(): array
    {
        return [
            'role' => $this->role,
            'stats' => $this->stats,
            'employee_list' => $this->employeeList,
            'employee_data' => $this->employeeData,
            'policy_snippets' => $this->policySnippets,
            'retrieval_meta' => $this->retrievalMeta,
            'generated_at' => $this->generatedAt,
        ];
    }

    /**
     * Get max confidence from retrieval meta.
     */
    public function getMaxConfidence(): float
    {
        return $this->retrievalMeta['max_confidence'] ?? 0.0;
    }

    /**
     * Check if context has policy snippets.
     */
    public function hasPolicySnippets(): bool
    {
        return $this->policySnippets !== [];
    }

    /**
     * Create default context for error cases.
     */
    public static function default(string $role = 'employee'): self
    {
        return new self(
            role: $role,
            stats: [],
            employeeList: '',
            employeeData: [],
            policySnippets: [],
            retrievalMeta: [
                'max_confidence' => 0.0,
                'retrieval' => 'none',
            ],
            generatedAt: now()->toDateTimeString()
        );
    }
}
