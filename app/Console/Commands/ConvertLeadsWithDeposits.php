<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Deposit;

class ConvertLeadsWithDeposits extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'leads:convert-with-deposits';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Convert all users with deposits from lead (is_lead=0) to trader (is_lead=1)';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Starting conversion of leads with deposits to traders...');
        
        $updatedCount = Deposit::convertLeadsWithDeposits();
        
        $this->info("Successfully converted {$updatedCount} users from lead to trader status.");
        
        return Command::SUCCESS;
    }
}
