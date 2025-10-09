<?php

namespace App\Services\deposit;

use App\Models\Deposit;
use App\Models\User;
use App\Services\AllFunctionService;
use App\Services\BalanceService;
use App\Services\EmailService;
use App\Services\MailNotificationService;

class BankDepositService
{
    // make bank deposit
    public static function bank_deposit($data)
    {
        // create deposit
        $invoice = substr(hash('sha256', mt_rand() . microtime()), 0, 16);
        $created = Deposit::create([
            'user_id' => $data['user_id'],
            'invoice_id' => $invoice,
            'transaction_type' => 'bank',
            'amount' => $data['amount'],
            'charge' =>  $data['charge'],
            'approved_status' => 'P',
            'ip_address' => $data['ip_address'],
            'bank_proof' => $data['file_name'],
            'bank_id' => $data['bank_id'],
            'currency' => $data['currency'] ?? "",
            'local_currency' => $data['local_amount'] ?? 0
        ])->id;

        $user = User::find($data['user_id']);
        // if sender is trader/get trader self balance
        if (User::where('id', $data['user_id'])->where('type', 0)->exists()) {
            $self_balance = AllFunctionService::trader_total_balance($data['user_id']);
        }
        // else sender is an IB/get IB self balance
        else {
            $self_balance = BalanceService::ib_balance($data['user_id']);
        }
        EmailService::send_email('bank-deposit-request', [
            'clientWithdrawAmount'      => $data['amount'],
            'user_id' => $user->id,
            'deposit_status' => 'Pending',
            'previous_balance' => ($self_balance) + ($data['amount']),
            'request_amount' => $data['amount'],
            'deposit_method' => 'Bank',
            'mail_subject'=>'Bank deposit'
        ]);
        if ($created) {
            //notification mail to admin
            MailNotificationService::admin_notification([
                'amount' => $data['amount'],
                'name' => auth()->user()->name,
                'email' => auth()->user()->email,
                'type' => 'deposit',
                'client_type' => 'trader'
            ]);
            $last_transaction = Deposit::find($created);
            return ([
                'status' => true,
                'last_transaction' => $last_transaction,
                'message' => 'Deposit Request successfully submited.'
            ]);
        }
        return ([
            'status' => false,
            'message' => 'Somthing went wrong, please try agian later!.'
        ]);
    }
}
