<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\MotorLog;
use Carbon\Carbon;

class FixMotorLogTimestamps extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'motorlogs:fix-timestamps';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix motor log timestamps that are in seconds instead of milliseconds';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking for motor logs with incorrect timestamps...');

        // Find logs with timestamps that are incorrect:
        // 1. Negative timestamps
        // 2. Timestamps less than 10 characters (likely corrupted)
        $incorrectLogs = MotorLog::where(function($query) {
            $query->where('timestamp', 'like', '-%')
                  ->orWhereRaw('LENGTH(timestamp) < 10');
        })->get();

        if ($incorrectLogs->isEmpty()) {
            $this->info('No logs found with incorrect timestamps.');
            return 0;
        }

        $this->warn("Found {$incorrectLogs->count()} logs with incorrect timestamps.");
        
        $this->table(
            ['ID', 'Current Timestamp', 'Phone Number', 'Created At'],
            $incorrectLogs->take(5)->map(fn($log) => [
                $log->id,
                $log->timestamp,
                $log->phone_number,
                $log->created_at
            ])
        );
        
        if (!$this->confirm('Do you want to fix these timestamps?')) {
            $this->info('Operation cancelled.');
            return 0;
        }

        $bar = $this->output->createProgressBar($incorrectLogs->count());
        $bar->start();

        $fixed = 0;
        foreach ($incorrectLogs as $log) {
            if (str_starts_with($log->timestamp, '-')) {
                // Negative timestamp - use created_at as fallback
                $log->timestamp = (string)($log->created_at->timestamp * 1000);
                $this->newLine();
                $this->warn("Fixed negative timestamp for log #{$log->id} using created_at");
            } else {
                // Too short timestamp - use created_at as fallback
                $log->timestamp = (string)($log->created_at->timestamp * 1000);
                $this->newLine();
                $this->warn("Fixed short timestamp for log #{$log->id} using created_at");
            }
            $log->save();
            $fixed++;
            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);
        $this->info("Successfully fixed {$fixed} motor log timestamps!");
        $this->info('All timestamps are now in milliseconds format.');

        return 0;
    }
}

