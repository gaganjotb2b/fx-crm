<?php

namespace App\Console\Commands;

use App\Services\trades\Mt5Trades;
use Illuminate\Console\Command;

class TradeCounts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mt5trade:count';

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
        // return Command::SUCCESS;?
        $trades = new Mt5Trades();
        // for ($i=0; $i < 3; $i++) { 
        return $trades->margeTrades();
        // }
    }
}
