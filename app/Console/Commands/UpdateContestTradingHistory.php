<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\contest\ContestTradingHistoryService;
use App\Models\Contest;
use App\Models\ContestJoin;

class UpdateContestTradingHistory extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'contest:trading-history 
                            {--contest-id= : Specific contest ID to update}
                            {--all : Update all active contests}
                            {--account= : Specific account number to update}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update contest trading history for participants';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting contest trading history update...');

        $contestId = $this->option('contest-id');
        $updateAll = $this->option('all');
        $specificAccount = $this->option('account');

        if ($contestId) {
            // Update specific contest
            $this->updateSpecificContest($contestId, $specificAccount);
        } elseif ($updateAll) {
            // Update all active contests
            $this->updateAllContests($specificAccount);
        } else {
            // Update active contests
            $this->updateActiveContests($specificAccount);
        }

        $this->info('Contest trading history update completed!');
    }

    /**
     * Update specific contest
     */
    private function updateSpecificContest($contestId, $specificAccount = null)
    {
        $this->info("Updating contest ID: $contestId");

        if ($specificAccount) {
            $this->updateSpecificAccount($contestId, $specificAccount);
        } else {
            $success = ContestTradingHistoryService::updateContestTradingHistory($contestId);
            
            if ($success) {
                $this->info("✅ Contest $contestId updated successfully");
            } else {
                $this->error("❌ Failed to update contest $contestId");
            }
        }
    }

    /**
     * Update all contests
     */
    private function updateAllContests($specificAccount = null)
    {
        $contests = Contest::all();
        $this->info("Found " . $contests->count() . " contests to update");

        $bar = $this->output->createProgressBar($contests->count());
        $bar->start();

        foreach ($contests as $contest) {
            if ($specificAccount) {
                $this->updateSpecificAccount($contest->id, $specificAccount);
            } else {
                ContestTradingHistoryService::updateContestTradingHistory($contest->id);
            }
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
    }

    /**
     * Update active contests only
     */
    private function updateActiveContests($specificAccount = null)
    {
        $contests = Contest::where('status', 'active')->get();
        $this->info("Found " . $contests->count() . " active contests to update");

        $bar = $this->output->createProgressBar($contests->count());
        $bar->start();

        foreach ($contests as $contest) {
            if ($specificAccount) {
                $this->updateSpecificAccount($contest->id, $specificAccount);
            } else {
                ContestTradingHistoryService::updateContestTradingHistory($contest->id);
            }
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
    }

    /**
     * Update specific account in a contest
     */
    private function updateSpecificAccount($contestId, $accountNumber)
    {
        $this->info("Updating account $accountNumber in contest $contestId");

        $participant = ContestJoin::where('contest_id', $contestId)
            ->where('account_number', $accountNumber)
            ->first();

        if (!$participant) {
            $this->error("❌ Participant not found for account $accountNumber in contest $contestId");
            return;
        }

        $contest = Contest::find($contestId);
        if (!$contest) {
            $this->error("❌ Contest $contestId not found");
            return;
        }

        ContestTradingHistoryService::updateParticipantTradingHistory($participant, $contest);
        $this->info("✅ Account $accountNumber updated successfully");
    }
}
