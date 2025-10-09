<?php

namespace App\Services\Transfer;

use App\Models\admin\InternalTransfer;
use App\Models\TradingAccount;
use App\Models\User;
use App\Services\AllFunctionService;
use App\Services\balance\BalanceSheetService;
use App\Services\bonus\BonusCreditService;
use App\Services\EmailService;
use App\Services\MailNotificationService;
use App\Services\MT4API;
use App\Services\Mt5WebApi;
use App\Services\TransactionService;
use DateTime;
use Illuminate\Support\Facades\Response;

class WtaTransferService
{
    public static function balance_update($account_number, $amount, $is_cen_acc=false)
    {
        $trading_account = TradingAccount::where('account_number', $account_number)->first();
        $mt5_api = new Mt5WebApi();
        $mt4_api = new MT4API();
        $invoice = substr(hash('sha256', mt_rand() . microtime()), 0, 16);

        //charge applied here
        $charge = TransactionService::charge('w_to_a', $amount, null);
        // command for mt5
        if (strtolower($trading_account->platform) === 'mt5') {
            $action = 'BalanceUpdate';
            $data = array(
                "Login" => (int)$trading_account->account_number,
                "Balance" => $is_cen_acc? (float)$amount * 100 : (float)$amount,
                "Comment" => "Wallet Deposit #" . $invoice
            );
            $result = $mt5_api->execute($action, $data);
        } elseif (strtolower($trading_account->platform) === 'mt4') {
            $data = array(
                'command' => 'deposit_funds',
                'data' => array(
                    'account_id' => $trading_account->account_number,
                    'amount' =>  $is_cen_acc? (float)$amount * 100 : (float)$amount,
                    'comment' => "Wallet Deposit #" . $invoice
                ),
            );
            $result = $mt4_api->execute($data, 'live');
        }

        if (isset($result['success'])) {
            if ($result['success']) {
                $trans_data = [
                    'user_id' => $trading_account->user_id,
                    'invoice_code' => $invoice,
                    'platform' => $trading_account->platform,
                    'account_id' => $trading_account->id,
                    'charge' => $charge,
                    'amount' => $amount,
                    'type' => 'wta',
                    'order_id' => $result['data']['order'],
                    'status' => 'A'
                ];
                $internal_transfer = InternalTransfer::create($trans_data);
                //mailer script
                if ($internal_transfer) {
                    
                    // get last transaction
                    $last_transaction = InternalTransfer::where('user_id', $trading_account->user_id)->where('type', 'wta')->latest()->first();
                    // giv bonus
                    BonusCreditService::deposit_bonus_credit($trading_account->user_id, $trading_account->account_number, $amount, $internal_transfer->id);
                    BonusCreditService::account_bonus_credit($trading_account->user_id, $trading_account->account_number, $amount, $internal_transfer->id);
                    // admin notification
                    $client = User::find($trading_account->user_id);
                    MailNotificationService::admin_notification([
                        'amount'=>$amount,
                        'name'=>$client->name,
                        'email'=>$client->email,
                        'type'=>'wallet to account transfer',
                        'client_type'=>'trader'
                    ]);
                    // sending mail
                    EmailService::send_email('wta-transfer', [
                        'user_id' => $trading_account->user_id,
                        'clientDepositAmount' => $amount,
                        'transfer_date' => ($last_transaction) ? ucwords($last_transaction->created_at) : '',
                        'previous_balance' => ((BalanceSheetService::trader_wallet_balance($trading_account->user_id)) + ($last_transaction->amount)),
                        'transfer_amount' => $last_transaction->amount,
                        'total_balance' => BalanceSheetService::trader_wallet_balance($trading_account->user_id)
                    ]);
                    // insert activity-----------------
                    $user = User::find(auth()->user()->id);
                    //<---client email as user id
                    activity("wallet to account")
                        ->causedBy(auth()->user()->id)
                        ->withProperties($trans_data)
                        ->event("wallet to account")
                        ->performedOn($user)
                        ->log("The IP address " . request()->ip() . " has been wallet to account transfer");
                    // end activity log----------------->>
                    return ([
                        'status' => true,
                        'last_transaction' => $last_transaction,
                        'submit_wait' => submit_wait('wta-transfer', 60),
                        'message' => 'Transaction successfully done!'
                    ]);
                } else {
                    return ([
                        'status' => false,
                        'submit_wait' => submit_wait('wta-transfer', 60),
                        'message' => 'Somthing went wrong please try again later!'
                    ]);
                }
            } else {
                return ([
                    'status' => false,
                    'submit_wait' => submit_wait('wta-transfer', 60),
                    'message' => (array_key_exists('data', $result)) ? $result['data']['message'] : $result['error']['Description'],

                ]);
            }
        }
        return ([
            'status' => false,
            'submit_wait' => submit_wait('wta-transfer', 60),
            'message' => (array_key_exists('data', $result)) ? $result['data']['message'] : $result['error']['Description'],

        ]);
    }
}
