<?php

namespace App\Http\Controllers\traders;

use App\Http\Controllers\Controller;
use App\Models\Deposit;
use App\Models\User;
use App\Services\AllFunctionService;
use App\Services\MailNotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use PayPal\Api\Amount;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Rest\ApiContext;

class PayPalController extends Controller
{
    public function __construct()
    {
        $this->middleware(AllFunctionService::access('paypal_deposit', 'trader'));
        $this->middleware(AllFunctionService::access('deposit', 'trader'));
    }
    public function index(Request $request)
    {
        return view('traders.deposit.paypal-deposit');
    }
    // create paypal orders
    public function store_payment(Request $request)
    {
        if (strtolower($request->data['status']) == 'completed') {
            $create = Deposit::create([
                'user_id' => auth()->user()->id,
                'invoice_id' => $request->data['id'], //as invoice id,
                'transaction_type' => 'PayPal',
                'transaction_id' => $request->data['id'], //as transaction id,
                'amount' => $request->amount,
            ]);
            $user = User::find(auth()->user()->id);
            activity("paypal deposit")
                ->causedBy(auth()->user()->id)
                ->withProperties($request->all())
                ->event("paypal deposit")
                ->performedOn($user)
                ->log("The IP address " . request()->ip() . " has been " .  "request a paypal deposit");
            MailNotificationService::admin_notification([
                'amount' => $request->amount,
                'name' => auth()->user()->name,
                'email' => auth()->user()->email,
                'type' => 'deposit',
                'client_type' => 'trader'
            ]);
            return Response::json([
                'status' => true,
                'res_status' => $request->status,
                'message' => 'Deposit Successfully done, You may check it on deposit reports'
            ]);
        }
        return Response::json([
            'status' => false,
            'res_status' => $request->status,
            'message' => 'Deposit Successfully done, You may check it on deposit reports'
        ]);
    }
    // -----------------------------
    // paypal api call to submit request
    public function call_api(Request $request)
    {
        // Set up the API context
        try {
            // api configuration
            $apiContext = new ApiContext(
                new OAuthTokenCredential(config('paypal.client_id'), config('paypal.client_secret'))
            );
            $apiContext->setConfig(
                array(
                    'mode' => 'sandbox'
                )
            );
            // Set up transaction details
            $amount = new Amount();
            $amount->setTotal($request->amount); // Replace with the desired amount
            $amount->setCurrency('USD');

            $transaction = new Transaction();
            $transaction->setAmount($amount);

            // Set up payer and redirect URLs
            $payer = new Payer();
            $payer->setPaymentMethod('paypal');

            $redirectUrls = new RedirectUrls();
            $redirectUrls->setReturnUrl(route('paypal.paypal.success'));
            $redirectUrls->setCancelUrl(route('paypal.paypal.cancel'));

            $api_status = false;
            $approvalLink = '';
            try {
                // Create a new payment
                $payment = new Payment();
                $payment->setIntent('sale')
                    ->setPayer($payer)
                    ->setTransactions([$transaction])
                    ->setRedirectUrls($redirectUrls);

                // Create the payment and get the approval link
                $payment->create($apiContext);
                $approvalLink = $payment->getApprovalLink();
                //  return($approvalLink);
                $api_status = true;
            } catch (\Exception $ex) {
                return response()->json(['error' => $ex->getMessage()], 500);
                $api_status = false;
            }
            if ($api_status) {
                $references = $this->getOrderID();
                Session::put('reference_key', $references);
                return Response::json([
                    'status' => true,
                    'checkout_url' => $approvalLink,
                    'message' => 'Please wait while we redirecting your!',
                ]);
            }
        } catch (\Throwable $th) {
            throw $th;
            return Response::json([
                'status' => false,
                'message' => 'Got a server error',
            ]);
        }
    }
    private function getOrderID(): string
    {
        // return 'order_' . rand(100, 100000);
        $order_id = microtime(true) . mt_rand();
        $order_id = str_replace('.', '', $order_id);
        return auth()->user()->id . '-' . $order_id;
    }
    public function payment_success(Request $request)
    {
        return $request->all();
        // return 'Payment successful!';
        $status = $message = false;
        if (Session::has('reference_key') && !empty(Session::get('reference_key'))) {
            Session::forget('reference_key');
            // insert activity-----------------
            $user = User::find(auth()->user()->id);
            activity('paypal deposit request')
                ->causedBy(auth()->user()->id)
                ->withProperties($request->all())
                ->event('paypal deposit')
                ->performedOn($user)
                ->log('The IP address ' . request()->ip() . ' has been make paypal deposit request');
            // end activity log-----------------
            $create_data = [
                'user_id' => auth()->user()->id,
                'invoice_id' => $request->paymentId,
                'note' => $request->descriptor,
                'transaction_type' => 'card',
                'transaction_id' => $request->PayerID,
                'amount' => $request->bill_amt,
                'currency' => $request->bill_currency,

            ];
            if (strtolower($request->status) === 'approved') {
                $create_data['approved_status'] = 'A';
            } elseif (strtolower($request->status) === 'declined') {
                $create_data['approved_status'] = 'D';
            } elseif (strtolower($request->status) === 'pending') {
                $create_data['approved_status'] = 'P';
            } else {
                $create_data['approved_status'] = 'P';
            }
            $create = Deposit::create($create_data);
            $status = $request->status;
        }
    }

    public function payment_cancel(Request $request)
    {
        return $request->all();
        return 'Payment cancelled.';
    }
}
