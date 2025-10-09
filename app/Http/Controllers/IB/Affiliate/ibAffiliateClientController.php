<?php

namespace App\Http\Controllers\IB\Affiliate;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\Deposit;
use App\Models\IB;
use App\Models\IbCommissionStructure;
use App\Models\IbSetup;
use App\Models\TradingAccount;
use App\Models\User;
use App\Models\Withdraw;
use App\Services\AllFunctionService;
use App\Services\balance\BalanceSheetService;
use App\Services\IbService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

class ibAffiliateClientController extends Controller
{
    public function __construct()
    {
        $this->middleware(AllFunctionService::access('my_clients', 'ib'));
        $this->middleware(AllFunctionService::access('affiliate', 'ib'));
        $this->middleware('is_ib'); // check the combined user is an IB
    }
    public function myClients(Request $request)
    {
        $op = $request->input('op');
        if ($request->ajax()) {
            return $this->myClientsDT($request);
        }
        return view('ibs.affiliate.myclients');
    }
    public function myClientsDT($request)
    {
        try {
            $columns = ['name', 'email', 'country_id', 'created_at', 'created_at', 'created_at', 'created_at', 'created_at'];
            $orderby = $columns[$request->order[0]['column']];

            // return $all_client_id;
            $result = User::select(
                'users.id',
                'users.name',
                'users.email',
                'users.created_at',
                'users.phone',
                'users.kyc_status',
                'user_descriptions.user_id',
                'user_descriptions.country_id'
            )
                ->leftJoin('user_descriptions', 'users.id', '=', 'user_descriptions.user_id');
            // filter by team
            if ($request->fiGroup == 'my_direct') { // my direct 
                $result = $result->whereIn('users.id', AllFunctionService::my_direct_client_id(auth()->user()->id));
            } else if ($request->fiGroup == 'my_team') { // my team
                $result = $result->whereIn('users.id', AllFunctionService::sub_ib_traders_id(auth()->user()->id));
            } else {
                $result = $result->whereIn('users.id', AllFunctionService::sub_ib_traders_id(auth()->user()->id, 'all'));
            }
            // /*<-------filter search script start here------------->*/f      
            // filter by trader name or email
            if ($request->trader_name_email != "") {
                $result = $result->where('name', 'LIKE', '%' . $request->trader_name_email . '%')
                    ->orWhere('email', 'LIKE', '%' . $request->trader_name_email . '%');
            }
            //filter by trading account
            if ($request->account_number != "") {
                $account = TradingAccount::where('account_number', $request->account_number)->select('user_id')->first();
                if (isset($account)) {
                    $user_id = IB::where('reference_id', $account->user_id)->get()->pluck('ib_id');
                    // $user_id = Deposit::where('user_id', $account->user_id)->get()->pluck('user_id');
                    $result = $result->whereIn('users.id', $user_id);
                } else {
                    $result = $result->where('users.id', null);
                }
            }

            //Filter By Level
            if ($request->level != "") {
                $user_ids = $result->pluck('id')->toArray();
                
                if ($user_ids) {
                    $levels = array();
                    for ($i = 0; $i < count($user_ids); $i++) {
                        $user_id = $user_ids[$i];
                        $level = AllFunctionService::get_node_level($user_id);
                        $levels[$user_id] = $level;
                    }
                    
                    $user_ids_with_level = Array();
                    foreach ($levels as $user_id => $level) {
                        if ($level == $request->level) {
                            // return $level;
                            array_push($user_ids_with_level,$user_id);
                        }
                    } 
                    $result = $result->whereIn('users.id',$user_ids_with_level);
                }
            }

            // filter by sub ib
            if ($request->sub_ib != "") {
                $filter_client = [];
                $users = User::where(function ($query) use ($request) {
                    $query->where('email', 'LIKE', '%' . $request->sub_ib . '%')
                        ->orWhere('name', 'LIKE', '%' . $request->sub_ib . '%')
                        ->orWhere('phone', 'LIKE', '%' . $request->sub_ib . '%');
                })->first();
                $ref_id = IB::where('ib_id', $users->id)
                    ->where('users.type', 0)->select('reference_id')
                    ->join('users', 'ib.reference_id', '=', 'users.id')->get();
                foreach ($ref_id as $key => $value) {
                    array_push($filter_client, $value->reference_id);
                }
                $result = $result->whereIn('users.id', $filter_client);
            }

            //Filter By Deposit Type
            if ($request->deposit == "1") {
                $deposit_id = Deposit::select('user_id')->pluck('user_id');
                $result = $result->whereIn('users.id', $deposit_id);
            }
            if ($request->deposit == "0") {
                $deposit_id = Deposit::select('user_id')->pluck('user_id');
                $result = $result->whereNotIn('users.id', $deposit_id);
            }

            //Filter By Status
            if ($request->status != "") {
                $result = $result->where('users.active_status', $request->status);
            }
            //Filter By KYC Verified
            if ($request->kyc != "") {
                $result = $result->where('users.kyc_status', $request->kyc);
            }

            //date filter
            if ($request->from != "") {
                $result = $result->whereDate("users.created_at", '>=', $request->from);
            }
            if ($request->to != "") {
                $result = $result->whereDate("users.created_at", '<=', $request->to);
            }

            // /*<-------filter search script end here------------->*/  

            $count = $result->count();
            $result = $result->orderby($orderby, $request->order[0]['dir'])->skip($request->start)->take($request->length)->get();
            $data = array();
            $i = 0;

            foreach ($result as $value) {

                $country = Country::select('name')->where('id', $value->country_id)->get();
                foreach ($country as $name) {
                    $country_name = $name->name;
                }
                $total_balance = BalanceSheetService::trader_wallet_balance($value->id);
                $total_withdraw = AllFunctionService::trader_total_withdraw($value->id);
                $total_deposit = AllFunctionService::trader_total_deposit($value->id);

                $data[$i]['name'] = $value->name;
                $data[$i]['email'] = $value->email;
                $data[$i]['country'] = $country_name;
                $data[$i]['reg_date'] = date('d F y, h:i:sa', strtotime($value->created_at));
                $data[$i]['affiliate_by'] = AllFunctionService::user_email(IbService::instant_parent($value->id));
                $data[$i]['current_balance'] = '$ ' . $total_balance;
                $data[$i]['total_deposit'] = '$ ' . $total_deposit;
                $data[$i]['total_withdraw'] = '$ ' . $total_withdraw;
                $i++;
            }
            return Response::json([
                'draw' => $request->draw,
                'recordsTotal' => $count,
                'recordsFiltered' => $count,
                'data' => $data
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return Response::json([
                'draw' => $request->draw,
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => []
            ]);
        }
    }
}
