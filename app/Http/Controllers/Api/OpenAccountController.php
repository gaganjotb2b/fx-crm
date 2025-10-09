<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ClientGroup;
use App\Services\AllFunctionService;
use App\Services\OpenLiveTradingAccountService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class OpenAccountController extends Controller
{
    public function __construct()
    {
        $this->middleware(AllFunctionService::access('open_live_account', 'trader'));
        $this->middleware(AllFunctionService::access('trading_accounts', 'trader'));
    }
    // client get groups
    public function get_groups(Request $request)
    {
        // return $request->all();
        try {
            $groups = ClientGroup::where('visibility', 'visible')->where('server', $request->platform);
            if ($request->category) {
                $groups = $groups->where('account_category', $request->category);
            }

            $groups = $groups->get();
            if ($groups) {
                $all_groups = [];
                foreach ($groups as $value) {
                    $all_groups[] = [
                        'id' => encrypt($value->id),
                        'group_name' => $value->group_id,
                        'platform' => $value->server,
                        'category' => $value->account_category,
                        'leverage' => json_decode($value->leverage),
                        'max_leverage' => $value->max_leverage,
                        'min_deposit' => $value->min_deposit,
                        'deposit_type' => $value->deposit_type,
                    ];
                }
                return Response::json([
                    'status' => true,
                    'groups' => $all_groups,
                ], 200);
            }
            return Response::json([
                'status' => false,
                'message' => 'Groups not found'
            ], 500);
        } catch (\Throwable $th) {
            throw $th;
            return Response::json([
                'status' => false,
                'message' => 'Got a server error'
            ], 500);
        }
    }
    // open live trading accounts
    public function open_account(Request $request)
    {
        try {
            $validation_ruls = [
                'platform' => 'required|max:100|string|in:mt5,mt4,edgeTrader',
                'leverage' => 'required|numeric',
                'group_id' => 'required|integer|exists:client_groups,id'
            ];
            $vlaidator = Validator::make($request->all(), $validation_ruls);
            if ($vlaidator->fails()) {
                return Response::json([
                    'status' => false,
                    'message' => 'Validation error, please fix the following errors',
                    'errors' => $vlaidator->errors(),
                ], 400);
            }
            $response = (OpenLiveTradingAccountService::open_live_account([
                'user_id' => auth()->user()->id,
                'platform' => $request->platform,
                'leverage' => $request->leverage,
                'account_type' => $request->group_id, //group id
            ]));
            return Response::json($response, 200);
        } catch (\Throwable $th) {
            throw $th;
            return Response::json([
                'status' => false,
                'message' => "An unexpected error occurred while processing your request.",
                'error' => $th->getMessage(),
            ], 500);
        }
    }
}
