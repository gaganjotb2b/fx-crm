<?php

namespace App\Console\Commands;

use App\Services\CurrencyUpdateService;
use Illuminate\Console\Command;

class UpdateCurrency extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'updateCurrency:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Currency Successfully Updated';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $currency_update_service = new CurrencyUpdateService();
        return $currency_update_service->updateCurrency();
        // return Command::SUCCESS;
    }
}
