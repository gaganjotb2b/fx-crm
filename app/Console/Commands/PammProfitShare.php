<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Services\pamm\ProfitShareService;

class PammProfitShare extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pamm_profit:share';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Pamm Profit Share';
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }
    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        return ProfitShareService::profit_share();
    }
}
