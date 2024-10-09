<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use \App\Console\Commands\CleanUpTokens ; 

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected $commands = [ \App\Console\Commands\CleanUpTokens::class, ];

    
    protected function schedule(Schedule $schedule): void
    {
        $schedule->command('tokens:cleanup')->daily();    }
    
   

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
