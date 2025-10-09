<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\admin\InternalTransfer;
use App\Models\ExternalFundTransfers;
use App\Models\User;
use App\Models\Withdraw;
use App\Services\AllFunctionService;
use App\Services\BalanceService;
use App\Services\EmailService;
use Illuminate\Http\Request;

class EmailServiceController extends Controller
{
    public function sending_mail(Request $request)
    {
        switch ($request->mail_for) {
            case 'reset_password':
                return (EmailService::send_email('reset-password', [
                    'user_id' => $request->user_id,
                    'new_password' => $request->new_password,
                    'account_email' => $request->email,
                    'type' => 'trader'
                ]));
                break;
                // mail change password
            case 'change-password':
                return (EmailService::send_email('change-password', [
                    'user_id' => $request->user_id,
                    'clientPassword' => $request->clientPassword,
                    'account_email' => $request->email,
                    'type' => 'trader'
                ]));
                break;
                // mail reset transaction password
            case 'reset-transaction-password':
                return (EmailService::send_email('reset-transaction-password', [
                    'user_id' => $request->user_id,
                    'new_pin' => $request->new_pin,
                    'account_email' => $request->email,
                    'type' => 'trader'
                ]));
                break;
                // change transaction password
            case 'change-transaction-password':
                return (EmailService::send_email('change-transaction-password', [
                    'user_id' => $request->user_id,
                    'transaction_pin' => $request->transaction_pin,
                    'account_email' => $request->email,
                    'type' => 'trader'
                ]));
                break;
                // bank deposit request
            case 'bank-deposit-request':
                // if sender is trader/get trader self balance
                if (User::where('id', $request->user_id)->where('type', 0)->exists()) {
                    $self_balance = AllFunctionService::trader_total_balance($request->user_id);
                }
                // else sender is an IB/get IB self balance
                else {
                    $self_balance = BalanceService::ib_balance($request->user_id);
                }
                return (EmailService::send_email('bank-deposit-request', [
                    'clientWithdrawAmount'      => $request->amount,
                    'user_id' => $request->user_id,
                    'deposit_status' => 'Pending',
                    'previous_balance' => ($self_balance) + ($request->amount),
                    'request_amount' => $request->amount,
                    'deposit_method' => 'Bank'
                ]));
                break;
                // crypto deposit request
            case 'crypto-deposit-request':
                return (EmailService::send_email('crypto-deposit-request', [
                    'user_id'               => $request->user_id,
                    'clientWithdrawAmount'  => $request->usd_amount,
                ]));
                break;
                // bank withdraw
            case 'withdraw-request':
                $last_transaction = Withdraw::find($request->withdraw_id);
                return (EmailService::send_email('withdraw-request', [
                    'clientWithdrawAmount'      => $request->amount,
                    'user_id'                   => $request->user_id,
                    'deposit_method'            => ($last_transaction) ? ucwords($last_transaction->transaction_type) : '',
                    'deposit_date'              => ($last_transaction) ? ucwords($last_transaction->created_at) : '',
                    'previous_balance'          => ((AllFunctionService::trader_total_balance($request->user_id)) + ($last_transaction->amount)),
                    'approved_amount'           => $last_transaction->amount,
                    'total_balance'             => AllFunctionService::trader_total_balance($request->user_id)
                ]));
                break;
                // crypto withdraw
            case 'crypto-withdraw-request':
                return (EmailService::send_email('crypto-withdraw-request', [
                    'cryptoAddress' => $request->crypto_address,
                    'currency' => $request->block_chain,
                    'blockchain' => $request->instrument,
                    'amount' => $request->usd_amount,
                    'cryptoAmount' => $request->crypto_amount,
                    'status' => "Pending",
                    'user_id' => $request->user_id,
                ]));
                break;
                // skrill withdraw
            case 'skrill-withdraw':
                return (EmailService::send_email('withdraw-request', [
                    'clientWithdrawAmount'      => $request->amount,
                    'user_id' => $request->user_id,
                ]));
                break;
                // netteler withdraw
            case 'neteller-withdraw':
                return (EmailService::send_email('withdraw-request', [
                    'clientWithdrawAmount'      => $request->amount,
                    'user_id' => $request->user_id,
                ]));
                break;
                // trader to trader transfer
            case 'trader-to-trader':
                $last_transaction = ExternalFundTransfers::where('sender_id',)->where('type', 'wtw')->latest()->first();
                $receiver = User::where('id', $request->recipient)->select('id', 'email', 'name')->first();
                return (EmailService::send_email('trader-to-trader-transfer', [
                    'user_id' => $request->user_id,
                    'clientDepositAmount' => $request->amount,
                    'transfer_date' => ($last_transaction) ? ucwords($last_transaction->created_at) : '',
                    'previous_balance' => ((AllFunctionService::trader_total_balance($request->user_id)) + ($last_transaction->amount)),
                    'transfer_amount' => $last_transaction->amount,
                    'total_balance' => AllFunctionService::trader_total_balance($request->user_id),
                    'reciever_name' => ucwords($receiver->name),
                    'reciever_email' => $receiver->email,
                ]));
                break;
                // trader to ib transfer
            case 'trader-to-ib-transfer':
                $last_transaction = ExternalFundTransfers::where('sender_id', $request->user_id)->where('type', 'wtw')->latest()->first();
                $receiver = User::where('id', $request->recipient)->select('id', 'type', 'name', 'email', 'combine_access')->first();
                return (EmailService::send_email('trader-to-ib-transfer', [
                    'user_id' => $request->user_id,
                    'clientDepositAmount' => $request->amount,
                    'transfer_date' => ($last_transaction) ? ucwords($last_transaction->created_at) : '',
                    'previous_balance' => ((AllFunctionService::trader_total_balance($request->user_id)) + ($last_transaction->amount)),
                    'transfer_amount' => $last_transaction->amount,
                    'total_balance' => AllFunctionService::trader_total_balance($request->user_id),
                    'reciever_name' => ucwords($receiver->name),
                    'reciever_email' => $receiver->email,
                ]));
                break;
            case 'wallet-to-account':
                $last_transaction = InternalTransfer::where('user_id', $request->user_id)->where('type', 'wta')->latest()->first();
                return (EmailService::send_email('wta-transfer', [
                    'user_id' => $request->user_id,
                    'clientDepositAmount' => $request->amount,
                    'transfer_date' => ($last_transaction) ? ucwords($last_transaction->created_at) : '',
                    'previous_balance' => ((AllFunctionService::trader_total_balance($request->user_id)) + ($last_transaction->amount)),
                    'transfer_amount' => $last_transaction->amount,
                    'total_balance' => AllFunctionService::trader_total_balance($request->user_id)
                ]));
                break;
                // account to wallet
            case 'account-to-wallet':
                $last_transaction = InternalTransfer::where('user_id', $request->user_id)->where('type', 'atw')->latest()->first();
                return (EmailService::send_email('atw-transfer', [
                    'user_id' => $request->user_id,
                    'clientDepositAmount' => $request->amount,
                    'transfer_date' => ($last_transaction) ? ucwords($last_transaction->created_at) : '',
                    'previous_balance' => ((AllFunctionService::trader_total_balance($request->user_id)) - ($last_transaction->amount)),
                    'transfer_amount' => $last_transaction->amount,
                    'total_balance' => AllFunctionService::trader_total_balance($request->user_id)
                ]));
                break;
                // 
            default:
                # code...
                break;
        }
    }
}
