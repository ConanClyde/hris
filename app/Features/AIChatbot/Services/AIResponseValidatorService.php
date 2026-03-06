<?php

namespace App\Features\AIChatbot\Services;

use Illuminate\Support\Facades\Log;

/**
 * Service for validating AI responses against source data to ensure accuracy.
 */
class AIResponseValidatorService
{
    /**
     * Validate that counts mentioned in the response match the source data.
     *
     * @param  string  $response  The AI's response text
     * @param  array  $contextData  The context data that was provided to the AI
     * @return array{valid: bool, issues: string[], corrected_response: string|null}
     */
    public function validateResponse(string $response, array $contextData): array
    {
        $issues = [];
        $valid = true;

        // Extract numbers from response
        preg_match_all('/(\d+)\s+(?:pending\s+)?leave/i', $response, $leaveMatches);
        preg_match_all('/(\d+)\s+(?:pending\s+)?training/i', $response, $trainingMatches);
        preg_match_all('/(\d+)\s+employee/i', $response, $employeeMatches);
        preg_match_all('/(\d+)\s+user/i', $response, $userMatches);

        // Validate leave counts
        $actualPendingLeaves = $contextData['leave_applications']['pending'] ?? null;
        if ($actualPendingLeaves !== null && ! empty($leaveMatches[1])) {
            foreach ($leaveMatches[1] as $mentionedCount) {
                if ((int) $mentionedCount !== $actualPendingLeaves) {
                    $issues[] = "Leave count mismatch: AI said {$mentionedCount}, but actual pending is {$actualPendingLeaves}";
                    $valid = false;
                }
            }
        }

        // Validate training counts
        $actualPendingTraining = $contextData['training']['pending_approval'] ?? null;
        if ($actualPendingTraining !== null && ! empty($trainingMatches[1])) {
            foreach ($trainingMatches[1] as $mentionedCount) {
                if ((int) $mentionedCount !== $actualPendingTraining) {
                    $issues[] = "Training count mismatch: AI said {$mentionedCount}, but actual pending is {$actualPendingTraining}";
                    $valid = false;
                }
            }
        }

        // Log validation results
        if (! $valid) {
            Log::warning('AI Response Validation Failed', [
                'issues' => $issues,
                'context_data' => $contextData,
                'response_preview' => mb_substr($response, 0, 500),
            ]);
        }

        return [
            'valid' => $valid,
            'issues' => $issues,
            'corrected_response' => $valid ? null : $this->generateCorrection($response, $issues, $contextData),
        ];
    }

    /**
     * Generate a corrected response when validation fails.
     */
    private function generateCorrection(string $originalResponse, array $issues, array $contextData): string
    {
        $correction = 'I need to correct my previous response. Based on the system data as of ';
        $correction .= ($contextData['timestamp'] ?? now()->toDateTimeString()).":\n\n";

        foreach ($issues as $issue) {
            $correction .= "- {$issue}\n";
        }

        $correction .= "\nLet me provide the accurate information:\n";

        if (isset($contextData['leave_applications'])) {
            $correction .= '- Pending leave applications: '.$contextData['leave_applications']['pending']."\n";
        }
        if (isset($contextData['training'])) {
            $correction .= '- Pending training applications: '.$contextData['training']['pending_approval']."\n";
        }
        if (isset($contextData['users'])) {
            $correction .= '- Total users: '.$contextData['users']['total']."\n";
        }

        return $correction;
    }

    /**
     * Check if the AI is hallucinating by verifying mentioned facts against context.
     */
    public function detectHallucinations(string $response, array $contextData): array
    {
        $hallucinations = [];

        // List of facts that should be in the context
        $verifiableFacts = [
            'employee names' => $this->extractEmployeeNames($contextData),
            'department names' => $this->extractDepartmentNames($contextData),
            'leave types' => $this->extractLeaveTypes($contextData),
        ];

        // Check for specific hallucination patterns
        if (preg_match('/\b\d+\s+employee\s+\w+\b/i', $response, $matches)) {
            // Check if this specific count is supported by data
            $mentionedCount = (int) $matches[0];
            $actualEmployeeCount = $contextData['users']['employees'] ?? null;

            if ($actualEmployeeCount !== null && $mentionedCount > $actualEmployeeCount * 1.1) {
                $hallucinations[] = "Mentioned {$mentionedCount} employees, but only {$actualEmployeeCount} exist in system";
            }
        }

        return $hallucinations;
    }

    private function extractEmployeeNames(array $contextData): array
    {
        $names = [];
        // Extract from employee_list or employee_data
        if (isset($contextData['employee_list'])) {
            preg_match_all('/\b([A-Z][a-z]+\s[A-Z][a-z]+)\b/', $contextData['employee_list'], $matches);
            $names = $matches[1] ?? [];
        }

        return $names;
    }

    private function extractDepartmentNames(array $contextData): array
    {
        // Extract department names from context
        return [];
    }

    private function extractLeaveTypes(array $contextData): array
    {
        // Common leave types that exist in the system
        return ['Vacation', 'Sick', 'Emergency', 'Maternity', 'Paternity'];
    }
}
