<?php

namespace App\Http\Controllers;

use App\Models\admin\SystemConfig;
use App\Models\ClientGroup;
use App\Models\Country;
use App\Models\RequiredField;
use App\Models\TradingAccount;
use App\Models\User;
use App\Services\OpenLiveTradingAccountService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class TraderActivationController extends Controller
{
    public function trader_activation(Request $request)
    {
        try {
            $system_config = SystemConfig::select(
                'create_meta_acc',
                'social_account',
                'acc_limit',
                'platform_type'
            )->first();
            $create_meta_account = isset($system_config->create_meta_acc) ? $system_config->create_meta_acc : 0;
            $user = User::find($request->user_id);
            if ($create_meta_account == 1 && $request->op === 'meta-account') {
                $trading_account = TradingAccount::where('user_id', $user->id)->first();
                $client_group = ClientGroup::find($trading_account->group_id);
                // check if account already created
                if ($user->email_verified_at != null) {
                    return Response::json([
                        'status' => 2,
                        'message' => 'Your profile already activated, Please try to login.'
                    ]);
                }
                // open trading account by service
                $response = OpenLiveTradingAccountService::open_live_account([
                    'user_id' => $request->user_id,
                    'platform' => strtoupper($trading_account->platform),
                    'leverage' => $trading_account->leverage,
                    'account_type' => $client_group->id,
                ], true);
                // if respons success
                if ($response['status'] == true) {
                    return Response::json([
                        'status' => true,
                        'message' => 'Activation success, You can login now.'
                    ]);
                }
                return Response::json([
                    'status' => false,
                    'message' => 'Activation failed, Please reopen your mail click to activation.'
                ]);
            } else if ($create_meta_account == 0 && $request->op === 'meta-account') {
                $user->email_verified_at = date('Y-m-d h:i:s', strtotime('now'));
                $update = $user->save();
                if ($update) {
                    return Response::json([
                        'status' => true,
                        'message' => 'Activation success, You can login now.'
                    ]);
                }
                return Response::json([
                    'status' => false,
                    'message' => 'Activation failed, Please reopen your mail click to activation.'
                ]);
            } else {
                $user->email_verified_at = date('Y-m-d h:i:s', strtotime('now'));
                $update = $user->save();
                if ($update) {
                    return Response::json([
                        'status' => true,
                        'message' => 'Activation success, You can login now.'
                    ]);
                }
                return Response::json([
                    'status' => false,
                    'message' => 'Activation failed, Please reopen your mail click to activation.'
                ]);
            }
        } catch (\Throwable $th) {
            //throw $th;
            return Response::json([
                'status'=>false,
                'message'=>'Got a server error, please contact for support',
            ]);
        }
    }
}
