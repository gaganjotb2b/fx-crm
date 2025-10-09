<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ClientGroup;
use App\Models\TradingAccount;
use App\Models\User;
use App\Services\accounts\AccountService;
use App\Services\api\CrmApiService;
use App\Services\Mt5WebApi;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class AccountController extends Controller
{
    // get all account
    public function get_account(Request $request)
    {
        switch ($request->single_account) {
            case true:
                $user = User::find($request->user_id);
                if ($user) {
                    $account = TradingAccount::where('user_id', $request->user_id)->where('account_number', $request->account_number)
                        ->select('trading_accounts.*', 'client_groups.group_id as group_display_name')
                        ->join('client_groups', 'trading_accounts.group_id', '=', 'client_groups.id')
                        ->first();
                    if ($account) {
                        return ([
                            'status' => true,
                            'account' => $account,
                        ]);
                    }
                    return ([
                        'status' => false,
                        'message' => 'No account found for this user'
                    ]);
                }
                return ([
                    'status' => false,
                    'message' => 'No datat found for this user!'
                ]);
                break;

            default:
                $user = User::find($request->user_id);
                if ($user) {
                    $accounts = TradingAccount::where('user_id', $request->user_id)
                        ->select('trading_accounts.*', 'client_groups.group_id as group_display_name')
                        ->join('client_groups', 'trading_accounts.group_id', '=', 'client_groups.id')
                        ->get();
                    if ($accounts) {
                        return ([
                            'status' => true,
                            'accounts' => $accounts,
                        ]);
                    }
                    return ([
                        'status' => false,
                        'message' => 'No account found for this user'
                    ]);
                }
                return ([
                    'status' => false,
                    'message' => 'No datat found for this user!'
                ]);
                break;
        }
    }
    public function all_accounts(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'per_page' => 'nullable|min:1|numeric',
                'account_number' => 'nullable|numeric|exists:trading_accounts,account_number',
            ]);
            if ($validator->fails()) {
                return Response::json([
                    'status' => false,
                    'message' => 'Validation error, please fix the following errors',
                    'errors' => $validator->errors(),
                    'accounts' => []
                ]);
            }

            $accounts = TradingAccount::where('user_id', auth()->guard('api')->user()->id);
            // filter by account nubmer
            if ($request->input('account_number')) {
                $accounts = $accounts->where('account_number', $request->input('account_number'));
            }
            // filter by date from 
            if ($request->input('date_to')) {
                $to  = Carbon::parse($request->input('date_to'));
                $accounts = $accounts->whereDate('created_at', '<=', $to);
            }
            // filter by date from
            if ($request->input('date_from')) {
                $date_from  = Carbon::parse($request->input('date_from'));
                $accounts = $accounts->whereDate('created_at', '>=', $date_from);
            }
            $accounts = $accounts->with(['group' => function ($query) {
                $query->select('id', 'group_name', 'leverage');
            }])
                ->paginate($request->input('per_page', 5));
            if ($accounts) {
                return ([
                    'status' => true,
                    'accounts' => $accounts,
                ]);
            }
            return ([
                'status' => false,
                'message' => 'No account found for this user'
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    // check balance equity
    public function balance_equity(Request $request)
    {
        try {

            $validation_rules = [
                'account_number' => 'required|numeric|exists:trading_accounts,account_number'
            ];
            $custom_message = [
                'exists' => 'The request account was not found'
            ];
            $validator = Validator::make($request->all(), $validation_rules, $custom_message);
            if ($validator->fails()) {
                return Response::json([
                    'status' => false,
                    'error' => 'Validation Error',
                    'message' => "The request data is invalid.",
                    'errors' => $validator->errors(),
                ], 400);
            }
            $accounts = TradingAccount::where('account_number', $request->account_number)
                ->first();
            if ($accounts) {
                return Response::json([
                    'status' => true,
                    'check' => AccountService::check_balance_equity($request->account_number)
                ], 200);
            }
            return Response::json([
                'status' => false,
                'error' => "Account not found",
                'message' => "The requested account was not found."
            ], 404);
        } catch (\Throwable $th) {
            throw $th;
            return Response::json([
                'status' => false,
                "error" => "Internal Server Error",
                "message" => "An unexpected error occurred while processing your request."
            ], 500);
        }
    }
    // chage password
    public function change_password(Request $request)
    {
        try {
            $validation_rules = [
                'account_number' => 'required|numeric|exists:trading_accounts,account_number',
                'new_password' => 'required|min:8|max:32',
            ];
            $custom_message = [
                'exists' => 'The requested account number was not found',
            ];
            $validator = Validator::make($request->all(), $validation_rules, $custom_message);
            if ($validator->fails()) {
                return Response::json([
                    'status' => false,
                    'error' => 'Validation Error',
                    'message' => "The request data is invalid.",
                    'errors' => $validator->errors(),
                ], 400);
            }
            $account = TradingAccount::where('account_number', $request->account_number)
                ->where('user_id', auth()->user()->id)->first();
            if ($account) {
                $response = AccountService::change_password([
                    'account_number' => $request->account_number,
                    'password' => $request->new_password,
                    'type' => 'password'
                ]);
                return Response::json($response, 200);
            }
            return Response::json([
                'status' => false,
                'error' => "Account not found",
                'message' => "The requested account was not found."
            ], 404);
        } catch (\Throwable $th) {
            // throw $th;
            return Response::json([
                'status' => false,
                "error" => "Internal Server Error",
                "message" => "An unexpected error occurred while processing your request."
            ], 500);
        }
    }
    // chage password
    public function change_investor_password(Request $request)
    {
        try {
            $validation_rules = [
                'account_number' => 'required|numeric|exists:trading_accounts,account_number',
                'new_password' => 'required|min:8|max:32',
            ];
            $custom_message = [
                'exists' => 'The requested account number was not found',
            ];
            $validator = Validator::make($request->all(), $validation_rules, $custom_message);
            if ($validator->fails()) {
                return Response::json([
                    'status' => false,
                    'error' => 'Validation Error',
                    'message' => "The request data is invalid.",
                    'errors' => $validator->errors(),
                ], 400);
            }
            $account = TradingAccount::where('account_number', $request->account_number)
                ->where('user_id', auth()->user()->id)->first();
            if ($account) {
                $response = AccountService::change_password([
                    'account_number' => $request->account_number,
                    'password' => $request->new_password,
                    'type' => 'investor-password'
                ]);
                return Response::json($response, 200);
            }
            return Response::json([
                'status' => false,
                'error' => "Account not found",
                'message' => "The requested account was not found."
            ], 404);
        } catch (\Throwable $th) {
            throw $th;
            return Response::json([
                'status' => false,
                "error" => "Internal Server Error",
                "message" => "An unexpected error occurred while processing your request."
            ], 500);
        }
    }
    // get account group leverage
    public function get_account_leverage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'account_number' => 'required|numeric|exists:trading_accounts,account_number',
        ]);
        if ($validator->fails()) {
            return Response::json([
                'status' => false,
                'message' => 'Validation error, please fix the following error',
                'errors' => $validator->errors(),
            ]);
        }
        // this account is authenticated or not
        $account = TradingAccount::where('account_number', $request->input('account_number'))
            ->where('user_id', auth()->guard('api')->user()->id)
            ->select('account_number', 'group_id', 'leverage')
            ->with(['group' => function ($query) {
                $query->select('id', 'group_name', 'leverage');
            }])
            ->first();

        if ($account) {
            return ([
                'status' => true,
                'data' => $account
            ]);
        }
        return ([
            'status' => false,
            'message' => 'Invalid request',
            'data' => []
        ]);
    }
    // change leverage
    public function change_leverage(Request $request)
    {
        try {
            // validation 
            $validation_rules = [
                'account_number' => 'required|numeric|exists:trading_accounts,account_number',
                'leverage' => 'required|numeric|min:10'
            ];
            $custom_message = [
                'exists' => 'The requested account number is not found'
            ];
            $validator = Validator::make($request->all(), $validation_rules, $custom_message);
            if ($validator->fails()) {
                return Response::json([
                    'status' => false,
                    'error' => "Validation Error",
                    'message' => "The request data is invalid.",
                    'errors' => $validator->errors(),
                ], 400);
            }
            $account = TradingAccount::where('user_id', auth()->user()->id)
                ->where('account_number', $request->account_number)->first();
            if (!$account) {
                return Response::json([
                    'status' => false,
                    'error' => "Account not found",
                    'message' => "The requested account was not found."
                ], 404);
            }
            $response = AccountService::change_leverage([
                'account_number' => $request->account_number,
                'leverage' => $request->leverage,
                'platform' => $account->platform
            ]);
            return Response::json($response, 200);
        } catch (\Throwable $th) {
            // throw $th;
            return Response::json([
                'status' => false,
                "error" => "Internal Server Error",
                "message" => "An unexpected error occurred while processing your request."
            ], 500);
        }
    }
    // get all traders
    public function get_all_clients(Request $request)
    {
        // in client end point need this key
        if ($request->header('api_key') === CrmApiService::api_key()) {
            if ($request->user_id != "") {
                $user = User::where('type', 0)->whereNot('id', $request->user_id);
                $count = $user->count();
                $user = $user->get();
                return ([
                    'status' => true,
                    'clients' => $user,
                    'total' => $count,
                ]);
            } else {
                $user = User::where('type', 0)->select();
                $count = $user->count();
                $user = $user->get();
                return ([
                    'status' => true,
                    'clients' => $user,
                    'total' => $count,
                ]);
            }
        }
        // invalid request found
        // when api key missmatch
        return ([
            'status' => false,
            'message' => 'Unauthorize request found!'
        ]);
    }
}
