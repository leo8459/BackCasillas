<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
{
    $schedule->command('emails:send')->dailyAt('7:00');
    $schedule->command('emails:send2')->dailyAt('7:00');
    $schedule->command('emails:send3')->dailyAt('7:00');
    $schedule->command('alquileres:updateVencidos')->dailyAt('7:00');
    $schedule->command('alquileres:updateAllToOcupadas')->dailyAt('7:00');
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
