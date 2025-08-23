<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class ClearCache implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $logFile = storage_path('logs/laravel.log');
        if (File::exists($logFile)) {
            try {
                File::put($logFile, '');
            } catch (\Throwable $e) {
            }
        }
    }
}
