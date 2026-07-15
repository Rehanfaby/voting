<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\ReminderCron::class,
        Commands\ReconcileVotes::class,
        Commands\ProcessScheduledAnnouncements::class,
        Commands\ReconcilePendingPawaPayDeposits::class,
        Commands\PawaPayCheckCommand::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // Safety net: reconcile any debited-but-uncounted votes against Campay.
        // Scheduled first and in the background so it is unaffected by other
        // scheduled commands that terminate their own process.
        $schedule->command('votes:reconcile')
            ->everyMinute()
            ->runInBackground()
            ->withoutOverlapping();

        $schedule->command('reminder:cron')
            ->everyMinute();

        $schedule->command('announcements:process-scheduled')
            ->everyMinute()
            ->runInBackground()
            ->withoutOverlapping();

        $schedule->command('payments:reconcile-pawapay')
            ->everyFiveMinutes()
            ->runInBackground()
            ->withoutOverlapping();
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
