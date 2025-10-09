<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Services\tournaments\GroupTradeCalculationService;

class TournamentGroupTradeCal extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tour_group:calculation';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Tournament Group Calculation';
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
        $obj = new GroupTradeCalculationService();
        return $obj->groupTradeCalculation();
    }
}
