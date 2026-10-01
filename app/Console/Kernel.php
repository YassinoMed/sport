<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Console\Commands\SendPaymentReminders;
use App\Console\Commands\SendEventPaymentReminders;
use App\Console\Commands\Task;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected $commands = [Task::class, SendPaymentReminders::class, SendEventPaymentReminders::class];

    protected function schedule(Schedule $schedule)
    {

        $schedule->command('notify:users-not-logged-in-for-month')->everyMinute();

        $schedule->command('payments:remind --days=3')->dailyAt('09:00');
        
        $schedule->command('events:remind-payments --days=2')->dailyAt('10:00');

    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
