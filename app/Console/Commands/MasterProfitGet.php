<?php

namespace App\Console\Commands;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Services\social_trades\MasterProfitService;

class MasterProfitGet extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'masterProfit:get';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'master profit share get';
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
        $masterProfit = new MasterProfitService();
        $masterProfit->getMasterProfit();
        
        $masterProfit->releaseMasterProfit();
    }
}
