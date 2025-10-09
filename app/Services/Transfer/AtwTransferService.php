<?php

namespace App\Services\Transfer;

use App\Models\admin\InternalTransfer;
use App\Models\TradingAccount;
use App\Models\User;
use App\Services\accounts\AccountService;
use App\Services\AllFunctionService;
use App\Services\balance\BalanceSheetService;
use App\Services\EmailService;
use App\Services\MailNotificationService;
use App\Services\MT4API;
use App\Services\Mt5WebApi;
use App\Services\TransactionService;
use DateTime;
use Illuminate\Support\Facades\Response;

class AtwTransferService
{
    public static function balance_update($account_number, $amount, $is_cen_acc=false)
    {
        $trading_account = TradingAccount::where('account_number', $account_number)->first();
        $mt5_api = new Mt5WebApi();
        $mt4_api = new MT4API();
        $invoice = substr(hash('sha256', mt_rand() . microtime()), 0, 16);
        //charge applied here
        $charge = TransactionService::charge('a_to_w', $amount, null);
        $user = User::find($trading_account->user_id);
        // command for mt5
        if (strtolower($trading_account->platform) === 'mt5') {
            $action = 'BalanceUpdate';
            $data = array(
                "Login" => (int)$trading_account->account_number,
                "Balance" => $is_cen_acc? -(float)$amount * 100 : -(float)$amount,
                "Comment" => "account to wallet #" . $invoice
            );
            $result = $mt5_api->execute($action, $data);
        }
        // command for mt4
        if (strtolower($trading_account->platform) === 'mt4') {
            // check mt4 balance
            $mt4_balance = AccountService::get_mt4_balance($trading_account->account_number, 'live');
            if ($amount > $mt4_balance['equity']) {
                return Response::json([
                    'status' => false,
                    'submit_wait' => submit_wait('atw-transfer', 60),
                    'errors' => ['amount' => "You don't have available balance!"],
                    'message' => "You don't have available balance!"
                ]);
            }
            $data = array(
                'command' => 'deposit_funds',
                'data' => array(
                    'account_id' => $trading_account->account_number,
                    'amount' => $is_cen_acc? -(float)$amount * 100 : -(float)$amount,
                    'comment' => "account to wallet #" . $invoice
                ),
            );
            $result = $mt4_api->execute($data, 'live');
        }

        if (isset($result['success'])) {
            if ($result['success']) {
                $trans_data = [
                    'user_id' => $trading_account->user_id,
                    'platform' => $trading_account->platform,
                    'invoice_code' => $invoice,
                    'account_id' => $trading_account->id,
                    'charge' => $charge,
                    'amount' => $amount,
                    'type' => 'atw',
                    'order_id' => $result['data']['order'],
                    'status' => 'A'
                ];
                $internal_transfer = InternalTransfer::create($trans_data);

                if ($internal_transfer) {

                    //notification mail to admin
                    // MailNotificationService::notification('balance transfer', 'trader', 1, $user->name, $amount);
                    $last_transaction = InternalTransfer::where('user_id', auth()->user()->id)->where('type', 'atw')->latest()->first();
                    $client = User::find($trading_account->user_id);
                    MailNotificationService::admin_notification([
                        'amount' => $amount,
                        'name' => $client->name,
                        'email' => $client->email,
                        'type' => 'account to wallet transfer',
                        'client_type' => 'trader'
                    ]);
                    EmailService::send_email('atw-transfer', [
                        'user_id' => $trading_account->user_id,
                        'clientDepositAmount' => $amount,
                        'transfer_date' => ($last_transaction) ? ucwords($last_transaction->created_at) : '',
                        'previous_balance' => ((BalanceSheetService::trader_wallet_balance($user->id)) - ($last_transaction->amount)),
                        'transfer_amount' => $last_transaction->amount,
                        'total_balance' => BalanceSheetService::trader_wallet_balance($user->id)
                    ]);
                    // get last transaction
                    // insert activity-----------------
                    $user = User::find(auth()->user()->id);
                    //<---client email as user id
                    activity("account to wallet")
                        ->causedBy(auth()->user()->id)
                        ->withProperties($trans_data)
                        ->event("account to wallet")
                        ->performedOn($user)
                        ->log("The IP address " . request()->ip() . " has been Account to wallet transfer");
                    // end activity log----------------->>

                    return ([
                        'status' => true,
                        'last_transaction' => $last_transaction,
                        'submit_wait' => submit_wait('atw-transfer', 60),
                        'message' => 'Transaction successfully done!'
                    ]);
                }
                return ([
                    'status' => false,
                    'submit_wait' => submit_wait('atw-transfer', 60),
                    'message' => 'Somthing went wrong please try again later!'
                ]);
            }
            return ([
                'status' => false,
                'submit_wait' => submit_wait('atw-transfer', 60),
                'message' => (array_key_exists('data', $result)) ? $result['data']['message'] : $result['error']['Description'],
                'data' => $result,
            ]);
        }
    }
}
