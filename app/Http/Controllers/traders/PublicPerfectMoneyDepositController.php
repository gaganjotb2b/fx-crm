<?php

namespace App\Http\Controllers\traders;

use App\Http\Controllers\Controller;
use App\Models\Deposit;
use App\Models\DepositLog;
use App\Models\Log;
use App\Models\OnlinePaymentMethod;
use App\Models\PaymentStatus;
use App\Models\User;
use App\Services\Mt5WebApi;
use App\Models\admin\InternalTransfer;
use App\Models\TradingAccount;
use App\Services\AllFunctionService;
use Illuminate\Http\Request;

class PublicPerfectMoneyDepositController extends Controller
{
    public function __construct()
    {
        $this->middleware(AllFunctionService::access('perfect_money_deposit', 'trader'));
        $this->middleware(AllFunctionService::access('deposit', 'trader'));
    }
    // deposit perfect money
    public function perfectMoneyDepositProcess(Request $request)
    {

        // perfect money
        $perfectMoney = OnlinePaymentMethod::where('name', 'IT Corner')->select('info')->first();
        $perfectMoneytInformation = "";
        if ($perfectMoney) {
            $perfectMoneytInformation = json_decode($perfectMoney->info);
        }

        $PAYEE_ACCOUNT = isset($perfectMoney->info) ? $perfectMoneytInformation->PAYEE_ACCOUNT : null;

        // $PAYEE_ACCOUNT = "U44203570";
        // $marcentUserId = 2;


        /*
        This script demonstrates getting and validating SCI
        payment confirmation data from Perfect Money server

        !!! WARNING !!!
        This sample PHP-script is provided AS IS and you should
        use it at your own risk.
        The only purpose of this script is to demonstrate main
        principles of SCI-payment validation process.
        You MUST modify it before using with your particular
        Perfect Money account.

        */


        /* Constant below contains md5-hashed alternate passphrase in upper case.
        You can generate it like this:
        strtoupper(md5('your_passphrase'));
        Where `your_passphrase' is Alternate Passphrase you entered
        in your Perfect Money account.

        !!! WARNING !!!
        We strongly recommend NOT to include plain Alternate Passphrase in
        this script and use its pre-generated hashed version instead (just
        like we did in this script below).
        This is the best way to keep it secure. */
        define('ALTERNATE_PHRASE_HASH',  'Bangladesh123');

        // // Path to directory to save logs. Make sure it has write permissions.
        define('PATH_TO_LOG',  '/home/blockhash/public_html/member/');


        $string =
            $_GET['PAYMENT_ID'] . ':' . $_GET['PAYEE_ACCOUNT'] . ':' .
            $_GET['PAYMENT_AMOUNT'] . ':' . $_GET['PAYMENT_UNITS'] . ':' .
            $_GET['PAYMENT_BATCH_NUM'] . ':' .
            $_GET['PAYER_ACCOUNT'] . ':' . ALTERNATE_PHRASE_HASH . ':' .
            $_GET['TIMESTAMPGMT'] . ':' . $_GET['USER_ID'];

        // demo data start 
        // $hash = "B4C0537C0369232E3404B03A9137FCF0";
        $hash = strtoupper(md5($string));


        // demo data end
        // $hash = strtoupper(md5($string));

        // perfect money response to log start

        // $properties = [
        //     'user_id'           => (isset($_GET['USER_ID'])) ? $_GET['USER_ID'] : "",
        //     'payment_id'        => (isset($_GET['PAYMENT_ID'])) ? $_GET['PAYMENT_ID'] : "",
        //     'invoice_id'        => "",
        //     'account'           => (isset($_GET['ACCOUNT_NUMBER'])) ? $_GET['ACCOUNT_NUMBER'] : "",
        //     'transaction_type'  => 'Perfect Money',
        //     'amount'            => (isset($_GET['PAYMENT_AMOUNT'])) ? $_GET['PAYMENT_AMOUNT'] : "",
        //     'transaction_id'    => (isset($_GET['PAYMENT_BATCH_NUM'])) ? $_GET['PAYMENT_BATCH_NUM'] : "",
        //     'order_id'          => (isset($_GET['ORDER_NUM'])) ? $_GET['ORDER_NUM'] : "",
        // ];
        $properties = json_encode($_GET);



        $log = DepositLog::create([
            'properties' => $properties,
        ]);
        // perfect money response to log end


        // if ($hash == $_GET['V2_HASH']) { // processing payment if only hash is valid

        //     /* In section below you must implement comparing of data you received
        //     with data you sent. This means to check if $_GET['PAYMENT_AMOUNT'] is
        //     particular amount you billed to client and so on. */
        //     if ($_GET['PAYEE_ACCOUNT'] == $PAYEE_ACCOUNT && $_GET['PAYMENT_UNITS'] == 'USD') {


        //         /* ...insert some code to process valid payments here... */

        //         // $user = User::where('email', $_GET['PAYMENT_ID'])->first();
        //         $user_id       = $_GET['USER_ID'];
        //         $txid          = $_GET['PAYMENT_BATCH_NUM'];
        //         $amount        = $_GET['PAYMENT_AMOUNT'];
        //         $currency      = $_GET['PAYMENT_UNITS'];
        //         $account_number  = (isset($_GET['ACCOUNT_NUMBER'])) ? $_GET['ACCOUNT_NUMBER'] : "";
        //         $to_address    = $_GET['PAYEE_ACCOUNT'];


        //         $order_number    = (isset($_GET['ORDER_NUM'])) ? $_GET['ORDER_NUM'] : "";
        //         // check transaction id exist or not 
        //         $unique_deposit = Deposit::where('transaction_id', $txid)->first();

        //         $rec_date      = date('Y-m-d h:i:s');
        //         $charge        = ($amount / 100) * 1.5;
        //         // check transaction id is empty or not && check unique deposit
        //         if ($_GET['PAYMENT_BATCH_NUM'] != 0 && !isset($unique_deposit)) {
        //             // uncomment code below if you want to log successfull payments
        //             $payment_status = PaymentStatus::create([
        //                 'payment_id' => $_GET['PAYMENT_ID'],
        //                 'txid' => $txid,
        //                 'amount' => $amount,
        //                 'status' => 1,
        //             ]);

        //             $pm_deposit = Deposit::create([
        //                 'user_id'           => $user_id,
        //                 'invoice_id'        => "",
        //                 'account'           => $account_number,
        //                 'transaction_type'  => 'Perfect Money',
        //                 'amount'            => $amount,
        //                 'charge'            => 0,
        //                 'transaction_id'    => $txid,
        //                 'order_id'          => $order_number,
        //                 'ip_address'        => request()->ip(),
        //                 'approved_status'   => 'A',
        //             ]);

        //             echo "success";
        //         }
        //     }
        // }

    }

    public function cancelPMD(Request $request)
    {
        $is_success = false;

        if ($request->input('PAYMENT_BATCH_NUM') > 0) {

            /* ...insert some code to process valid payments here... */

            // $user = User::where('email', $_GET['PAYMENT_ID'])->first();
            $user_id       = $_GET['USER_ID'];
            $txid          = $_GET['PAYMENT_BATCH_NUM'];
            $amount        = $_GET['PAYMENT_AMOUNT'];
            $currency      = $_GET['PAYMENT_UNITS'];
            $account_number  = (isset($_GET['ACCOUNT_NUMBER'])) ? $_GET['ACCOUNT_NUMBER'] : "";
            $to_address    = $_GET['PAYEE_ACCOUNT'];

            $order_number    = (isset($_GET['ORDER_NUM'])) ? $_GET['ORDER_NUM'] : "";
            // check transaction id exist or not 
            $unique_deposit = Deposit::where('transaction_id', $txid)->first();

            $rec_date      = date('Y-m-d h:i:s');
            $charge        = ($amount / 100) * 1.5;
            // check transaction id is empty or not && check unique deposit
            if ($_GET['PAYMENT_BATCH_NUM'] != 0 && !isset($unique_deposit)) {

                $trading_account = TradingAccount::find($_GET['ACCOUNT_NUMBER']);

                // uncomment code below if you want to log successfull payments
                $payment_status = PaymentStatus::create([
                    'payment_id' => $_GET['PAYMENT_ID'],
                    'txid' => $txid,
                    'amount' => $amount,
                    'status' => 1,
                ]);

                $pm_deposit = Deposit::create([
                    'user_id'           => $user_id,
                    'invoice_id'        => "",
                    'account'           => $account_number,
                    'transaction_type'  => 'Perfect Money',
                    'amount'            => $amount,
                    'charge'            => 0,
                    'transaction_id'    => $txid,
                    'order_id'          => $order_number,
                    'ip_address'        => request()->ip(),
                    'approved_status'   => 'A',
                ]);

                //Meta Account Deposit HEre.

                if (isset($_GET['ACCOUNT_NUMBER'])) {

                    if ($trading_account) {

                        $mt5_api = new Mt5WebApi();
                        $action = 'BalanceUpdate';
                        $data = array(
                            "Login" => (int) $trading_account->account_number,
                            "Balance" => (float) $amount,
                            "Comment" => "Perfect Money Deposit #" . $txid
                        );
                        $result = $mt5_api->execute($action, $data);


                        //dd($result);

                        if (isset($result['success'])) {
                            if ($result['success']) {
                                $internal_transfer = InternalTransfer::create([
                                    'user_id' => $user_id,
                                    'invoice_code' => $txid,
                                    'platform' => 'mt5',
                                    'account_id' => $trading_account->account_number,
                                    'charge' => 0,
                                    'amount' => $amount,
                                    'type' => 'wta',
                                    'order_id' => $result['data']['order'],
                                    'status' => 'A'
                                ]);
                            }
                        }
                    }
                }

                $is_success = true;
            }
        }

        $data['is_success'] = $is_success;
        $data['pm_deposit_status'] = 1;
        $data['api'] = $request->all();

        return view('traders.deposit.perfect-money-deposit', $data);
    }
}
