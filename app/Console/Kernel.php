<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Check for overdue invoices every day at midnight
        $schedule->command('invoices:check-overdue')->dailyAt('00:00');
        
        // Send reminders for invoices that are 7 days overdue, every Monday at 9 AM
        $schedule->command('invoices:send-reminders')->weekly()->mondays()->at('09:00');
        
        // Send reminders for invoices that are 14 days overdue, every Monday at 9:15 AM
        $schedule->command('invoices:send-reminders --days=14')->weekly()->mondays()->at('09:15');
        
        // Send reminders for invoices that are 30 days overdue, every Monday at 9:30 AM
        $schedule->command('invoices:send-reminders --days=30')->weekly()->mondays()->at('09:30');
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
} 