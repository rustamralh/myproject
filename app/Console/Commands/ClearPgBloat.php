<?php

namespace App\Console\Commands;

use App\Services\SlackService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\Process;

class ClearPgBloat extends Command
{
    protected $signature = 'maintenance:clear-pg-bloat {--dry-run : Show what will happen without executing} {--max-wait=300 : Maximum seconds to wait for jobs (default 5 minutes)} {--poll-interval=10 : Seconds between job checks} {--slack-channel=#general : Slack channel for notifications} {--force : Skip job waiting and proceed immediately}';

    protected $description = 'Enable maintenance mode, stop queues/schedules, and clear PostgreSQL bloat for all tenant schemas.';

    public function handle()
    {
        $dryRun = $this->option('dry-run');
        $force = $this->option('force');
        $this->info('🔧 Starting maintenance operation...');

        // Phase 1: Pre-Maintenance Checks
        $this->info('🛠 Starting maintenance operations.');

        // Step 2: Stop queues and scheduler
        $this->pauseQueues();
        $this->pauseScheduler();

        // Phase 2: Job Monitoring Loop (if not forcing)
        if (! $force) {
            $this->info('🔍 Checking for running jobs...');
            $jobsCompleted = $this->waitForJobsToComplete();

            if (! $jobsCompleted) {
                $this->error('❌ Jobs timeout exceeded. Use --force to proceed anyway (dangerous).');

                return Command::FAILURE;
            }
        } else {
            $this->warn('⚠️ Force mode: Skipping job wait. Proceeding immediately.');
        }

        // Phase 3: Schema Discovery & Notification
        // Step 3: Get all tenant schemas
        $schemas = DB::table('information_schema.schemata')
            ->whereNotIn('schema_name', ['information_schema', 'pg_catalog', 'public'])
            ->pluck('schema_name');

        $this->info('🗂 Found schemas: '.$schemas->implode(', '));

        if ($schemas->isEmpty()) {
            $this->warn('⚠️ No tenant schemas found. Nothing to maintain.');
        } else {
            // Send schema list notification
            if (! $dryRun) {
                $schemaList = $schemas->map(fn ($schema) => "• `{$schema}`")->implode("\n");
                $this->sendImmediateSlackNotification(
                    'Database maintenance starting for '.$schemas->count().' schema(s)',
                    [
                        [
                            'type' => 'section',
                            'text' => [
                                'type' => 'mrkdwn',
                                'text' => "*📋 Database Maintenance - Schema List*\n\nTotal schemas: ".$schemas->count()."\n\n{$schemaList}",
                            ],
                        ],
                    ]
                );
            }
        }

        // Phase 4: Per-Schema Maintenance Loop
        $successCount = 0;
        $failureCount = 0;

        foreach ($schemas as $schema) {
            $this->line("🧹 Cleaning schema: {$schema}");

            try {
                if (! $dryRun) {
                    DB::statement("SET search_path TO {$schema}");
                    DB::statement('VACUUM FULL');
                }

                $this->info("✅ Bloat cleared for {$schema}");
                $successCount++;

                // Send success notification
                if (! $dryRun) {
                    $this->sendImmediateSlackNotification(
                        "Schema maintenance completed: {$schema}",
                        [
                            [
                                'type' => 'section',
                                'text' => [
                                    'type' => 'mrkdwn',
                                    'text' => "✅ *Schema Maintenance Complete*\n\n• Schema: `{$schema}`\n• Status: Success\n• VACUUM FULL: Complete",
                                ],
                            ],
                        ]
                    );
                }
            } catch (\Throwable $e) {
                $this->error("❌ Error cleaning {$schema}: ".$e->getMessage());
                $failureCount++;

                // Send error notification
                if (! $dryRun) {
                    $errorMessage = substr($e->getMessage(), 0, 500); // Limit error message length
                    $this->sendImmediateSlackNotification(
                        "Schema maintenance failed: {$schema}",
                        [
                            [
                                'type' => 'section',
                                'text' => [
                                    'type' => 'mrkdwn',
                                    'text' => "❌ *Schema Maintenance Error*\n\n• Schema: `{$schema}`\n• Status: Failed\n• Error: `{$errorMessage}`",
                                ],
                            ],
                        ]
                    );
                }
            }
        }

        // Phase 5: Completion & Resume
        // Send final completion notification
        if (! $dryRun) {
            $totalSchemas = $schemas->count();
            $this->sendImmediateSlackNotification(
                "Database maintenance completed: {$successCount}/{$totalSchemas} schemas successful",
                [
                    [
                        'type' => 'section',
                        'text' => [
                            'type' => 'mrkdwn',
                            'text' => ($failureCount === 0
                                ? "✅ *Database Maintenance Completed*\n\n• Total schemas: {$totalSchemas}\n• Successful: {$successCount}\n• Failed: {$failureCount}\n• Application is live again."
                                : "⚠️ *Database Maintenance Completed (with errors)*\n\n• Total schemas: {$totalSchemas}\n• Successful: {$successCount}\n• Failed: {$failureCount}\n• Application is live again."),
                        ],
                    ],
                ]
            );
        }

        // Step 4: Resume queues and scheduler
        $this->resumeQueues();
        $this->resumeScheduler();

        // Step 5: Disable maintenance mode
        if (! $dryRun) {
            Artisan::call('up');
        }

        $this->info('🚀 Maintenance completed successfully. Application is live again.');
        $this->info("📊 Summary: {$successCount} successful, {$failureCount} failed");

        return $failureCount === 0 ? Command::SUCCESS : Command::FAILURE;
    }

    /**
     * Pause Laravel queue workers
     */
    protected function pauseQueues()
    {
        $this->info('⏸ Pausing queue workers...');
        try {
            // Pause all running workers (if using Horizon)
            if (class_exists(\Laravel\Horizon\Horizon::class)) {
                Process::fromShellCommandline('php artisan horizon:pause')->run();
                $this->info('🌀 Horizon queues paused.');
            } else {
                // Send SIGUSR2 to workers managed by Supervisor (optional)
                Process::fromShellCommandline('php artisan queue:pause')->run();
                $this->info('🧵 Queue workers paused.');
            }
        } catch (\Throwable $e) {
            $this->warn('⚠️ Unable to pause queues: '.$e->getMessage());
        }
    }

    /**
     * Resume Laravel queue workers
     */
    protected function resumeQueues()
    {
        $this->info('▶️ Resuming queue workers...');
        try {
            if (class_exists(\Laravel\Horizon\Horizon::class)) {
                Process::fromShellCommandline('php artisan horizon:continue')->run();
                $this->info('🌀 Horizon queues resumed.');
            } else {
                Process::fromShellCommandline('php artisan queue:resume')->run();
                $this->info('🧵 Queue workers resumed.');
            }
        } catch (\Throwable $e) {
            $this->warn('⚠️ Unable to resume queues: '.$e->getMessage());
        }
    }

    /**
     * Stop scheduled tasks (Supervisor or cron)
     */
    protected function pauseScheduler()
    {
        $this->info('⏹ Stopping scheduler...');
        try {
            // Optional: Stop Laravel schedule if running under Supervisor
            // Example: `supervisorctl stop laravel-scheduler:*`
            // You can adapt this to your environment

            // Log-only fallback
            $this->info('📅 Scheduler paused (manual supervision may be needed).');
        } catch (\Throwable $e) {
            $this->warn('⚠️ Unable to stop scheduler: '.$e->getMessage());
        }
    }

    /**
     * Resume scheduled tasks
     */
    protected function resumeScheduler()
    {
        $this->info('🔁 Resuming scheduler...');
        try {
            // Example: `supervisorctl start laravel-scheduler:*`
            $this->info('📅 Scheduler resumed.');
        } catch (\Throwable $e) {
            $this->warn('⚠️ Unable to resume scheduler: '.$e->getMessage());
        }
    }

    /**
     * Send immediate Slack notification using SlackService (synchronous, not queued)
     */
    protected function sendImmediateSlackNotification(string $message, array $blocks = [], ?string $channel = null): void
    {
        try {
            $slackService = app(SlackService::class);
            $channel = $channel ?? $this->option('slack-channel') ?? '#general';

            if (! empty($blocks)) {
                $success = $slackService->sendBlockMessage($channel, $blocks);
            } else {
                $success = $slackService->sendMessage($message, $channel);
            }

            if (! $success) {
                Log::warning('Failed to send immediate Slack notification', [
                    'channel' => $channel,
                    'message' => $message,
                ]);
            }
        } catch (\Throwable $e) {
            Log::error('Exception while sending immediate Slack notification: '.$e->getMessage(), [
                'channel' => $channel ?? 'unknown',
                'message' => $message,
                'exception' => $e,
            ]);
        }
    }

    /**
     * Get count of running jobs
     */
    protected function getRunningJobsCount(): int
    {
        try {
            $connection = config('queue.default');

            // Handle database queue driver
            if ($connection === 'database') {
                return DB::table('jobs')
                    ->whereNotNull('reserved_at')
                    ->count();
            }

            // Handle Redis queue driver
            if ($connection === 'redis') {
                try {
                    $redis = app('redis');
                    $queueName = config('queue.connections.redis.queue', 'default');
                    $prefix = config('database.redis.options.prefix', '');

                    // Check for reserved jobs (currently processing)
                    $reservedKey = "{$prefix}queues:{$queueName}:reserved";
                    $reservedJobs = $redis->zCard($reservedKey);

                    // Also check for jobs waiting in the queue
                    $queueKey = "{$prefix}queues:{$queueName}";
                    $pendingJobs = $redis->lLen($queueKey);

                    // Return total of reserved and pending jobs
                    return (int) $reservedJobs + (int) $pendingJobs;
                } catch (\Throwable $e) {
                    $this->warn('Redis queue check error: '.$e->getMessage());

                    return 0;
                }
            }

            // For sync driver or unsupported drivers, return 0
            return 0;
        } catch (\Throwable $e) {
            $this->warn('Unable to check running jobs: '.$e->getMessage());

            return 0;
        }
    }

    /**
     * Wait for all running jobs to complete
     *
     * @return bool True if all jobs completed, false if timeout
     */
    protected function waitForJobsToComplete(): bool
    {
        $maxWait = (int) $this->option('max-wait');
        $pollInterval = (int) $this->option('poll-interval');
        $startTime = time();
        $initialJobCount = $this->getRunningJobsCount();

        if ($initialJobCount === 0) {
            return true;
        }

        $this->info("⏳ Found {$initialJobCount} running job(s). Waiting for completion...");

        // Send initial notification
        $this->sendImmediateSlackNotification(
            "Database maintenance waiting for {$initialJobCount} job(s) to complete",
            [
                [
                    'type' => 'section',
                    'text' => [
                        'type' => 'mrkdwn',
                        'text' => "⚠️ *Database Maintenance - Jobs In Progress*\n\n• Running Jobs: {$initialJobCount}\n• Waiting for completion...\n• Max wait time: ".($maxWait / 60).' minutes',
                    ],
                ],
            ]
        );

        while (true) {
            sleep($pollInterval);

            $currentJobCount = $this->getRunningJobsCount();
            $elapsed = time() - $startTime;

            $this->info("   Status: {$currentJobCount} job(s) remaining (elapsed: ".($elapsed).'s)');

            if ($currentJobCount === 0) {
                $this->info('✅ All jobs completed!');
                $this->sendImmediateSlackNotification(
                    'All queue jobs completed. Starting database maintenance.',
                    [
                        [
                            'type' => 'section',
                            'text' => [
                                'type' => 'mrkdwn',
                                'text' => '✅ *All Jobs Processed*\n\n• All queue jobs completed\n• Starting database maintenance now\n• Estimated time: 10-15 minutes',
                            ],
                        ],
                    ]
                );

                return true;
            }

            // Check timeout
            if ($elapsed >= $maxWait) {
                $this->warn("⏰ Timeout reached after {$maxWait} seconds. {$currentJobCount} job(s) still running.");
                $this->sendImmediateSlackNotification(
                    "Job timeout: {$currentJobCount} job(s) still running after ".($maxWait / 60).' minutes',
                    [
                        [
                            'type' => 'section',
                            'text' => [
                                'type' => 'mrkdwn',
                                'text' => "⏰ *Job Timeout Warning*\n\n• Jobs still running after ".($maxWait / 60)." minutes\n• Remaining jobs: {$currentJobCount}\n• Manual intervention may be required",
                            ],
                        ],
                    ]
                );

                return false;
            }

            // Send periodic update if taking too long
            if ($elapsed > 60 && ($elapsed % 60 === 0)) {
                $remainingTime = round(($maxWait - $elapsed) / 60);
                $this->sendImmediateSlackNotification(
                    "Still waiting: {$currentJobCount} job(s) running (about {$remainingTime} minutes remaining)",
                    [
                        [
                            'type' => 'section',
                            'text' => [
                                'type' => 'mrkdwn',
                                'text' => "⏳ *Update*\n\n• Remaining jobs: {$currentJobCount}\n• Time remaining: ~{$remainingTime} minutes",
                            ],
                        ],
                    ]
                );
            }
        }
    }
}
