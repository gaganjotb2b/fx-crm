<?php

namespace App\Console\Commands;

use App\Services\ComTradeService;
use Illuminate\Console\Command;

class CreateComTrade extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'createComTrade:insert';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Com Trade Created Successfully';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $com_trade_service = new ComTradeService();
        $com_trade_service->insertComTradeMT4();
        // return Command::SUCCESS;
    }
}
