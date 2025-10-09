<?php

namespace App\Console\Commands;

use App\Services\commission\IbCommissionVersionTwo;
use Illuminate\Console\Command;

use Illuminate\Support\Facades\DB;

use App\Services\IBCommissionCountService;
use App\Services\trades\Mt5Trades;

class IBCommissionCount extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ibcommission:count';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This is ib commission';
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
        return IbCommissionVersionTwo::create_commission();
    }
}
