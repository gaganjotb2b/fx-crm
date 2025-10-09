<?php

namespace App\Console\Commands;

use App\Services\currency\GoogleCurrencyService;
use Illuminate\Console\Command;

class CurrencyRate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'currency:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        GoogleCurrencyService::currency_rate();
        // return 0;
        // $test = date('d/m/Y',strtotime(now()));
    }
    
}
