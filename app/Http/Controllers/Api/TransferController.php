<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\admin\InternalTransfer;
use App\Models\ExternalFundTransfers;
use App\Models\TradingAccount;
use App\Models\User;
use App\Services\api\CrmApiService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use MongoDB\Operation\Find;

class TransferController extends Controller
{
    //get all external transfer
    public function get_external_transfer(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'transaction_id' => 'nullable|string|max:100',
            'status' => 'nullable|in:A,P,D',
            'transaction_type' => 'nullable|in:send,received'
        ]);
        $user_id = auth()->guard('api')->user()->id;
        $external_transfer = ExternalFundTransfers::where(function ($query) use ($user_id) {
            $query->where('sender_id', $user_id)
                ->orWhere('receiver_id', $user_id);
        });
        // filter by sender info
        if ($request->input('sender_info')) {
            $external_transfer = $external_transfer->whereHas('sender', function ($query) use ($request) {
                $query->where('email', 'LIKE', '%' . $request->input('sender_info') . '%')
                    ->orWhere('name', 'LIKE', '%' . $request->input('sender_info') . '%');
            });
        }

        // filter by receiver info
        if ($request->input('receiver_info')) {
            $external_transfer = $external_transfer->whereHas('receiver', function ($query) use ($request) {
                $query->where('email', 'LIKE', '%' . $request->input('receiver_info') . '%')
                    ->orWhere('name', 'LIKE', '%' . $request->input('receiver_info') . '%');
            });
        }

        // filter by transaction id
        if (isset($request->transaction_id) && $request->transaction_id != "") {
            $external_transfer = $external_transfer->where('txnid', $request->transaction_id);
        }
        // filter by status
        if (isset($request->status) && $request->status != '') {
            $external_transfer = $external_transfer->where('status', $request->status);
        }
        // filter by transaction type
        if (isset($request->transaction_type) && $request->transaction_type != "") {
            switch (strtolower($request->transaction_type)) {
                case 'send':
                    $external_transfer = $external_transfer->where('sender_id', $user_id);
                    break;
                default:
                    $external_transfer = $external_transfer->where('receiver_id', $user_id);
                    break;
            }
        }
        // filter by min amount
        if ($request->min_amount) {
            $external_transfer = $external_transfer->where('amount', '>=', $request->min_amount);
        }
        // filter by maximum
        if ($request->max_amount) {
            $external_transfer = $external_transfer->where('amount', '<=', $request->max_amount);
        }
         // filter by date to
         if ($request->input('date_to')) {
            $to  = Carbon::parse($request->input('date_to'));
            $external_transfer = $external_transfer->whereDate('created_at', '<=', $to);
        }
        // filter by date from
        if ($request->input('date_from')) {
            $date_from  = Carbon::parse($request->input('date_from'));
            $external_transfer = $external_transfer->whereDate('created_at', '>=', $date_from);
        }
        $count = $external_transfer->count();
        $total_amount = $external_transfer->sum('amount');
        $external_transfer = $external_transfer->with(['sender' => function ($query) {
            $query->select('id', 'name', 'email');
        }, 'receiver' => function ($query) {
            $query->select('id', 'name', 'email');
        }])->paginate($request->input('per_page', 5));
        if ($external_transfer) {
            return ([
                'status' => true,
                'external_transfer' => $external_transfer,
                'total_amount' => $total_amount,
                'count' => $count,
            ]);
        }
        return ([
            'status' => false,
            'message' => 'Data not found'
        ]);
    }
    // get internal transfer data
    public function get_internal_transfer(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'account_number' => 'nullable|numeric|exists:trading_account,account_number',
            'status' => 'nullable|in:approved,pending,declined',
            'transaction_type' => 'nullable|in:wta,atw',
            'min_amount' => 'nullable|min:0|numeric',
            'max_amount' => 'nullable|min:0|numeric',
            'per_page' => 'nullable|numeric|min:1'
        ]);

        $user = User::find(auth()->user()->id);
        $trader_user = $user;
        if (strtolower($user->type) === 'ib') {
            $trader_user = $user->TraderAccount()->first();
        }
        $user_id = $trader_user->id;
        $internal_transfer = InternalTransfer::where('internal_transfers.user_id', $user_id);
        // filter by account number
        if (isset($request->account_number) && $request->account_number != "") {
            $internal_transfer = $internal_transfer->whereHas('tradingAccount', function ($query) use ($request) {
                $query->where('account_number', $request->account_number);
            });
        }
        // filter by status
        if (isset($request->status) && $request->status != '') {
            $internal_transfer = $internal_transfer->where('status', $request->status);
        }
        // filter by transaction type
        if (isset($request->transaction_type) && $request->transaction_type != "") {
            $internal_transfer = $internal_transfer->where('type', $request->transaction_type);
        }
        // filter by min amount
        if ($request->min_amount) {
            $internal_transfer = $internal_transfer->where('amount', '>=', $request->min_amount);
        }
        // filter by maximum
        if ($request->max_amount) {
            $internal_transfer = $internal_transfer->where('amount', '<=', $request->max_amount);
        }
        // filter by date to
        if ($request->input('date_to')) {
            $to  = Carbon::parse($request->input('date_to'));
            $internal_transfer = $internal_transfer->whereDate('created_at', '<=', $to);
        }
        // filter by date from
        if ($request->input('date_from')) {
            $date_from  = Carbon::parse($request->input('date_from'));
            $internal_transfer = $internal_transfer->whereDate('created_at', '>=', $date_from);
        }
        $total_amount = $internal_transfer->sum('amount');
        $count = $internal_transfer->count();
        $internal_transfer = $internal_transfer->with(['tradingAccount' => function ($query) {
            $query->select('id', 'account_number', 'platform', 'created_at');
        }, 'user' => function ($query) {
            $query->select('id', 'name', 'email');
        }])
            ->paginate($request->input('per_page', 5));
        if ($internal_transfer) {
            return ([
                'status' => true,
                'internal_transfer' => $internal_transfer,
                'total_amount' => $total_amount,
                'count' => $count,
            ]);
        }
        return ([
            'status' => false,
            'message' => 'Data not found',
            'code' => '003'
        ]);
    }
    // trader to trader/ clients to clients transfer
    public static function client_to_client(Request $request)
    {
        if ($request->header('api_key') === CrmApiService::api_key()) {
            // validation check
            $validation_rules = [
                'recipient' => 'required',
                'user_id' => 'required',
                'amount' => 'required|min:1|numeric',
                'transaction_password' => 'required'
            ];
            $validator = Validator::make($request->all(), $validation_rules);
            if ($validator->fails()) {
                return ([
                    'status' => false,
                    'errors' => $validator->errors(),
                    'message' => 'Validation failed! Please fix the following errors',
                    'code' => '004',
                ]);
            }
            // check user has in datatabase
            $client = User::where('id', $request->user_id)->where('type', 0)->exists();
            $recipient = User::where('id', $request->recipient)->where('type', 0)->exists();
            if (!$client) {
                return ([
                    'status' => false,
                    'message' => 'client not found!',
                    'code' => '003'
                ]);
            }
            if (!$recipient) {
                return ([
                    'status' => false,
                    'message' => 'Recipient not found',
                    'code' => '003'
                ]);
            }
            // if everything good
            // pending work because need it otp

        }
        return ([
            'status' => false,
            'message' => 'Un-authorize request found!',
            'code' => '002'
        ]);
    }
}
