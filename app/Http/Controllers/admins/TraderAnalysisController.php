<?php

namespace App\Http\Controllers\admins;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\User;
use App\Models\ClientGroup;
use App\Models\UserDescription;
use App\Services\AllFunctionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;


class TraderAnalysisController extends Controller
{
    public function index(Request $request)
    {
        return view('admins.trader-analysis');
    }
        public function special_customer()
    {
        $groups = ClientGroup::all();
        return view('admins.special-customer', compact('groups'));
    }
    public function make_special_customer(Request $request){

        $validator = Validator::make($request->all(), [
            'email'=>'required|email|exists:users,email',
        ]);
        
  
        $trader = User::where('email', $request->email)->first();

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $trader->client_groups = '['.$request->groups.']';
        $trader->save();


        return redirect()->back()->with('success', 'Successfully assign groups to user.');

    }
    // get trader analysis data
    public function get_data(Request $request)
    {
        try {
            if ($request->search_email) {
                $date = null;
                if ($request->start_date != "") {
                    $date = [
                        'from' => $request->start_date,
                        'to' => $request->end_date
                    ];
                }
                $trade_volume = AllFunctionService::total_volume($request->search_email, $date); // search_email as trader ID
                $total_withdraw = AllFunctionService::total_withdraw_with_date($request->search_email, $date); // search_email as trader ID
                $total_deposit = AllFunctionService::total_deposit_with_date($request->search_email, $date); // search_email as trader ID
                $total_balance = AllFunctionService::trader_total_balance($request->search_email, $date); // search_email as trader ID
                $total_trades = AllFunctionService::total_trades($request->search_email, $date); // search_email as trader ID
                $total_accounts = AllFunctionService::total_trading_accounts($request->search_email, $date); // search_email as trader ID
                $total_wta = AllFunctionService::total_wta_transfer($request->search_email, $date); // search_email as trader ID
                $total_atw = AllFunctionService::total_atw_transfer($request->search_email, $date); // search_email as trader ID
                $total_bonus = AllFunctionService::total_bonus($request->search_email, $date); // search_email as trader ID
                $total_trader_send = AllFunctionService::total_trd_to_trd_send($request->search_email, $date); // search_email as trader ID
                $total_trader_recive = AllFunctionService::total_trd_to_trd_recive($request->search_email, $date); // search_email as trader ID
                $total_ib_from_recive = AllFunctionService::total_receive_from_ib($request->search_email, $date); // search_email as trader ID
                $total_trader_to_ib_send = AllFunctionService::total_trd_to_ib_send($request->search_email, $date); // search_email as trader ID
                $user_info = User::find($request->search_email);
                $user_description = UserDescription::where('user_id', $request->search_email)->first();
                $country_name = '';
                if ($user_description && ($user_description->country_id != "")) {
                    $country = Country::find($user_description->country_id);
                    $country_name = $country->name;
                }
                return Response::json([
                    'status' => true,
                    'message' => 'Trader analysis successfully created',
                    'total_volume' => $trade_volume,
                    'total_withdraw' => $total_withdraw,
                    'total_deposit' => $total_deposit,
                    'total_balance' => $total_balance,
                    'total_trades' => $total_trades,
                    'total_accounts' => $total_accounts,
                    'total_wta' => $total_wta,
                    'total_atw' => $total_atw,
                    'total_bonus' => $total_bonus,
                    'total_trader_send' => $total_trader_send,
                    'total_trader_recive' => $total_trader_recive,
                    'total_ib_from_recive' => $total_ib_from_recive,
                    'total_trader_to_ib_send' => $total_trader_to_ib_send,
                    'user_info' => $user_info,
                    'country_name' => $country_name
                ]);
            }
            return Response::json([
                'status' => false,
                'message' => 'Please choose an user email'
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return Response::json([
                'status' => true,
                'message' => 'Trader analysis successfully created',
                'total_volume' => 0,
                'total_withdraw' => 0,
                'total_deposit' => 0,
                'total_balance' => 0,
                'total_trades' => 0,
                'total_accounts' => 0,
                'total_wta' => 0,
                'total_atw' => 0,
                'total_bonus' => 0,
                'total_trader_send' =>0,
                'total_trader_recive' => 0,
                'total_ib_from_recive' => 0,
                'total_trader_to_ib_send' => 0,
                'user_info' => 0,
                'country_name' => 0
            ]);
        }
    }
}
