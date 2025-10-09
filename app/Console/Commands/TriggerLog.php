<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\TriggerFlug;
use App\Services\MailNotificationService;

class TriggerLog extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'trigger:log';

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
        try {
            $triiger_log = TriggerFlug::select()->first();
            if ($triiger_log) {
                if ($triiger_log->admin_bank && $triiger_log->admin_bank_id == "") {
                    MailNotificationService::admin_notification([
                        'type' => 'bank delete trigger',
                        'client_type' => 'trigger'
                    ]);
                    TriggerFlug::where('id', 1)->update([
                        'admin_bank' => 0,
                        'admin_bank_id' => null,
                    ]);
                }
                if ($triiger_log->admin_bank && $triiger_log->admin_bank_id != "") {
                    MailNotificationService::admin_notification([
                        'type' => 'bank update trigger',
                        'client_type' => 'trigger'
                    ]);
                    TriggerFlug::where('id', 1)->update([
                        'admin_bank' => 0,
                        'admin_bank_id' => null,
                    ]);
                }
                if ($triiger_log->deposit && $triiger_log->deposit_id != "") {
                    MailNotificationService::admin_notification([
                        'type' => 'deposit update trigger',
                        'client_type' => 'trigger'
                    ]);
                    TriggerFlug::where('id', 1)->update([
                        'deposit' => 0,
                        'deposit_id' => null,
                    ]);
                }
                if ($triiger_log->withdraw && $triiger_log->withdraw_id != "") {
                    MailNotificationService::admin_notification([
                        'type' => 'withdraw update trigger',
                        'client_type' => 'trigger'
                    ]);
                    TriggerFlug::where('id', 1)->update([
                        'withdraw' => 0,
                        'withdraw_id' => null,
                    ]);
                }
                if ($triiger_log->other_transaction && $triiger_log->other_transaction_id != "") {
                    MailNotificationService::admin_notification([
                        'type' => 'crypto address update',
                        'client_type' => 'trigger'
                    ]);
                    TriggerFlug::where('id', 1)->update([
                        'withdraw' => 0,
                        'withdraw_id' => null,
                    ]);
                }
            }
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
}
