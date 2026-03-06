<?php

namespace App\Features\AIChatbot\Services;

class SourceLabelService
{
    private const LABELS = [
        'labor_code_leaves.txt' => 'Labor Code (Leaves)',
        'csc_leave_policies.txt' => 'CSC Leave Policies',
        'csc_leave_rules.txt' => 'CSC Leave Rules',
        'pds_policies.txt' => 'PDS Policies',
        'code_of_conduct.txt' => 'Code of Conduct',
        'ssl_vi_policies.txt' => 'SSL VI Policies',
        'gsis_policies.txt' => 'GSIS Policies',
        'spms_policies.txt' => 'SPMS Policies',
        'paternity_leave_policies.txt' => 'Paternity Leave Policies',
        'solo_parent_leave_policies.txt' => 'Solo Parent Leave Policies',
        'special_leave_women_policies.txt' => 'Special Leave for Women Policies',
        'year_end_bonus_policies.txt' => 'Year-End Bonus Policies',
        'mid_year_bonus_policies.txt' => 'Mid-Year Bonus Policies',
        'pbb_policies.txt' => 'PBB Policies',
        'policy_access.txt' => 'Policy Access',
        'calendar' => 'Calendar',
    ];

    public function getDisplayName(string $source): string
    {
        return self::LABELS[$source] ?? $this->humanizeFilename($source);
    }

    private function humanizeFilename(string $source): string
    {
        $base = pathinfo($source, PATHINFO_FILENAME);

        return str_replace(['_', '-'], ' ', ucwords($base, '_-'));
    }

    /**
     * Add display_name to each snippet. Modifies in place.
     *
     * @param  array<int, array{source: string, excerpt?: string, confidence?: float, url?: string}>  $snippets
     * @return array<int, array{source: string, display_name: string, excerpt?: string, confidence?: float, url?: string}>
     */
    public function addDisplayNames(array $snippets): array
    {
        foreach ($snippets as $i => $snippet) {
            $source = (string) ($snippet['source'] ?? '');
            $snippets[$i]['display_name'] = $this->getDisplayName($source);
        }

        return $snippets;
    }

    /**
     * Get policy group for deduplication (e.g. csc_* -> csc).
     */
    public function getPolicyGroup(string $source): string
    {
        $base = pathinfo($source, PATHINFO_FILENAME);
        $parts = explode('_', $base);

        return $parts[0] ?? $base;
    }
}
