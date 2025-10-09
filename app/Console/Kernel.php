<?php

namespace App\Console;

use App\Services\CurrencyUpdateService;
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
    protected $commands = [
        \App\Console\Commands\TriggerLog::class,
    ];
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();

        $schedule->call(function () {
            // update currency rate 
            $currency_update_service = new CurrencyUpdateService();
            $currency_update_service->updateCurrency();
        });
        $schedule->command('ibcommission:count')->everyFifteenMinutes();
        $schedule->command('contestposition:update')->everyFifteenMinutes();
        $schedule->command('mt5trade:count')->everyFiveMinutes();
        $schedule->command('TriggerLog:trigger_log')->everyFiveMinutes();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
