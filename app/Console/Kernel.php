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
        $schedule->command('emails:send')->everyMinute();
        $schedule->command('emails:send2')->everyMinute();
    //     // Ejecutar emails:send una vez y luego cada semana a la misma hora
    // $schedule->command('emails:send')->weekly()->onOneServer()->at('08:00'); 

    // // Ejecutar emails:send2 una vez y luego cada semana a la misma hora
    // $schedule->command('emails:send2')->weekly()->onOneServer()->at('08:00');
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
