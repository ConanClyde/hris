<?php

namespace App\Console\Commands;

use App\Features\Users\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MigrateUserNames extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:migrate-names 
                            {--dry-run : Preview changes without applying}
                            {--batch=100 : Number of users to process per batch}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate existing user name data into separated components';

    /**
     * Common name extensions to detect.
     *
     * @var array<string>
     */
    protected array $nameExtensions = [
        'Jr.', 'Sr.', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X',
        'Junior', 'Senior', 'Jr', 'Sr',
    ];

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $dryRun = $this->option('dry-run');
        $batchSize = (int) $this->option('batch');

        if ($dryRun) {
            $this->warn('DRY RUN MODE: No changes will be applied');
        }

        $this->info('Starting user name migration...');

        $totalUsers = User::count();
        $this->info("Total users to process: {$totalUsers}");

        $processed = 0;
        $updated = 0;
        $failed = 0;
        $skipped = 0;

        User::chunkById($batchSize, function ($users) use ($dryRun, &$processed, &$updated, &$failed, &$skipped) {
            foreach ($users as $user) {
                $processed++;

                // Skip if already migrated (new fields have values)
                if ($user->first_name || $user->last_name) {
                    $skipped++;
                    $this->line("  Skipping ID {$user->id}: Already migrated", 'comment');

                    continue;
                }

                // Skip if name is empty
                if (empty($user->name)) {
                    $skipped++;
                    $this->line("  Skipping ID {$user->id}: Empty name", 'comment');

                    continue;
                }

                try {
                    $components = $this->parseName($user->name);

                    if ($dryRun) {
                        $this->info("  ID {$user->id}: '{$user->name}'");
                        $this->line("    → first: '{$components['first_name']}'");
                        $this->line("    → middle: '{$components['middle_name']}'");
                        $this->line("    → last: '{$components['last_name']}'");
                        $this->line("    → ext: '{$components['name_extension']}'");
                    } else {
                        DB::table('users')
                            ->where('id', $user->id)
                            ->update([
                                'first_name' => $components['first_name'],
                                'middle_name' => $components['middle_name'],
                                'last_name' => $components['last_name'],
                                'name_extension' => $components['name_extension'],
                                'updated_at' => now(),
                            ]);

                        $updated++;

                        if ($updated % 50 === 0) {
                            $this->info("  Progress: {$updated} users updated...");
                        }
                    }
                } catch (\Exception $e) {
                    $failed++;
                    $this->error("  Failed ID {$user->id}: {$e->getMessage()}");
                    Log::error("Name migration failed for user {$user->id}", [
                        'name' => $user->name,
                        'error' => $e->getMessage(),
                    ]);
                }
            }
        });

        $this->newLine();
        $this->info('Migration Summary:');
        $this->table(
            ['Metric', 'Count'],
            [
                ['Total Processed', $processed],
                ['Successfully Updated', $updated],
                ['Skipped', $skipped],
                ['Failed', $failed],
            ]
        );

        if ($dryRun) {
            $this->warn('This was a dry run. No changes were applied.');
            $this->info('Run without --dry-run to apply changes.');
        } else {
            $this->info('Migration completed successfully!');
        }

        return $failed > 0 ? 1 : 0;
    }

    /**
     * Parse a full name into components.
     *
     * Algorithm:
     * 1. Detect and extract name extension (Jr., Sr., II, etc.)
     * 2. Split remaining name by spaces
     * 3. First token = first_name
     * 4. Last token = last_name
     * 5. Middle tokens = middle_name (if any)
     *
     * Handles edge cases:
     * - Compound first names (e.g., "Ana Marie")
     * - Compound surnames (e.g., "Dela Cruz", "De Leon")
     * - Multiple middle names
     * - Name extensions
     */
    protected function parseName(string $name): array
    {
        $name = trim($name);
        $extension = null;

        // Step 1: Detect and extract name extension
        foreach ($this->nameExtensions as $ext) {
            // Check for extension at end with space or comma
            $pattern = '/[,\s]+'.preg_quote($ext, '/').'$/i';
            if (preg_match($pattern, $name, $matches)) {
                $extension = $ext;
                $name = preg_replace($pattern, '', $name);
                $name = trim($name);
                break;
            }
        }

        // Step 2: Split remaining name
        $parts = preg_split('/\s+/', $name);
        $parts = array_filter($parts, fn ($p) => ! empty($p));
        $parts = array_values($parts);

        $count = count($parts);

        if ($count === 0) {
            return [
                'first_name' => null,
                'middle_name' => null,
                'last_name' => null,
                'name_extension' => $extension,
            ];
        }

        if ($count === 1) {
            // Single name - treat as first name
            return [
                'first_name' => $parts[0],
                'middle_name' => null,
                'last_name' => null,
                'name_extension' => $extension,
            ];
        }

        if ($count === 2) {
            // First + Last only
            return [
                'first_name' => $parts[0],
                'middle_name' => null,
                'last_name' => $parts[1],
                'name_extension' => $extension,
            ];
        }

        // 3+ parts: First, optional Middle(s), Last
        $firstName = $parts[0];
        $lastName = $parts[$count - 1];
        $middleName = null;

        // Handle compound surnames (Philippine/Spanish pattern)
        // Common prefixes that indicate compound surnames
        $surnamePrefixes = ['de', 'del', 'dela', 'de los', 'de las', 'san', 'santa', 'von', 'van'];

        if ($count >= 3) {
            $secondToLast = strtolower($parts[$count - 2]);

            if (in_array($secondToLast, $surnamePrefixes)) {
                // Compound surname detected
                $lastName = $parts[$count - 2].' '.$parts[$count - 1];

                // Everything between first and compound surname is middle name
                if ($count > 3) {
                    $middleParts = array_slice($parts, 1, $count - 3);
                    $middleName = implode(' ', $middleParts);
                }
            } else {
                // Regular multi-part name
                $middleParts = array_slice($parts, 1, $count - 2);
                $middleName = implode(' ', $middleParts);
            }
        }

        return [
            'first_name' => $firstName,
            'middle_name' => $middleName ?: null,
            'last_name' => $lastName,
            'name_extension' => $extension,
        ];
    }
}
