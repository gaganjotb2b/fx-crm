<?php

namespace App\Console\Commands;

use App\Services\contest\ContestService;
use Illuminate\Console\Command;

class ContestPosition extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'contestposition:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update active contest contester position';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // return Command::SUCCESS;
        return ContestService::contest_position();
    }
}
