<?php

namespace App\Http\Controllers\Traders;

use App\Http\Controllers\Controller;
use App\Models\admin\SystemConfig;
use App\Models\ComTrade;
use App\Models\Deposit;
use App\Models\TradingAccount;
use App\Models\Withdraw;
use App\Services\AllFunctionService;
use App\Services\balance\BalanceSheetService;
use App\Services\BalanceService;
use App\Services\bonus\BonusService;
use App\Services\contest\ContestService;
use App\Services\GetMonthNameService;
use App\Services\systems\VersionControllService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;

class TraderDashboardController extends Controller
{
    // public function __construct() {
    //     // $this->middleware(AllFunctionService::access('dashboard','trader'));
    //     $this->middleware('client_controll:access');
    // }
    // view trader dashboard
    public function dashboard(Request $request)
    {

        // get crm platform--------------------
        $platform = SystemConfig::select('platform_type')->first();
        // trading account for data list
        if ($request->ajax()) {
            $trading_accounts = TradingAccount::where('user_id', auth()->user()->id)->whereNotNull('account_number')
                ->join('client_groups', 'trading_accounts.group_id', '=', 'client_groups.id')
                ->where('trading_accounts.account_status', 1)
                ->select(
                    'trading_accounts.leverage',
                    'trading_accounts.account_number',
                    'client_groups.group_id as display_group_name',
                    'client_groups.max_leverage',
                    'trading_accounts.platform',
                    'trading_accounts.id as account_id'
                );
            $total_record = $trading_accounts->count();
            $trading_accounts = $trading_accounts->skip($request->current)->take($request->limit)->get();
            $data = array();
            foreach ($trading_accounts as $key => $value) {
                $platform_logo = '';
                if (strtolower($value->platform) === 'mt5') {
                    $platform_logo = asset('trader-assets/assets/img/mt5_icon.png');
                } elseif (strtolower($value->platform) === 'mt4') {
                    $platform_logo = asset('trader-assets/assets/img/mt4_icon.png');
                } elseif (strtolower($value->platform) === 'vertex') {
                    $platform_logo = asset('trader-assets/assets/img/vetex-icon.jpg');
                }

                array_push(
                    $data,
                    '<div class="d-flex list-row border-bottom p-0 justify-content-around">
                <div class="list-col w-100">
                    <div class="d-flex align-items-center">
                        <div>
                            <img src="' . $platform_logo . '" alt="Country flag" width="25">
                        </div>
                        <div class="ms-4">
                            <p class="text-xs font-weight-bold mb-0">Account:</p>
                            <h6 class="text-sm mb-0">' . $value->account_number . '</h6>
                        </div>
                    </div>
                </div>
                <div class="list-col w-100 dashboard-leverage dashboard-small-size-leverage">
                    <div class="text-center">
                        <p class="text-xs font-weight-bold mb-0">Leverage:</p>
                        <h6 class="text-sm mb-0">1:' . $value->leverage . '</h6>
                    </div>
                </div>
                <div class="list-col w-100 dashboard-acc-type">
                    <div class="text-center">
                        <p class="text-xs font-weight-bold mb-0">Account Type:</p>
                        <h6 class="text-sm mb-0">' . $value->display_group_name . '</h6>
                    </div>
                </div>
                <div class="list-col w-100 dashboard-small-size">
                    <div class="col text-center">
                        <p class="text-xs font-weight-bold mb-0">Balance:</p>
                        <h6 class="text-sm mb-0 cursor-pointer btn-load-ac-balance" data-id="' . $value->account_id . '">
                            $ <span class="amount">
                                0
                            </span>
                            <i class="fas fa-redo-alt bg-gradient-faded-dark-vertical p-1 rounded-circle"></i>
                        </h6>
                    </div>
                </div>
                <div class="list-col w-100 dashboard-small-size">
                    <div class="col text-center">
                        <p class="text-xs font-weight-bold mb-0">Equity:</p>
                        <h6 class="text-sm mb-0 cursor-pointer btn-load-ac-balance" data-id="' . $value->account_id . '">
                            $ <span class="amount">
                                0
                            </span>
                            <i class="fas fa-redo-alt bg-gradient-faded-dark-vertical p-1 rounded-circle"></i>
                        </h6>
                    </div>
                </div>
                <div class="col text-center py-2 dashboard-sm-modal-btn">
                    <div class="col text-center">
                        <h6 class="text-sm mb-0 cursor-pointer btn-load-ac-balance" data-id="' . $value->account_id . '" data-bs-toggle="modal" data-bs-target="#exampleModalCenter">
                            <i class="fas fa-redo-alt bg-gradient-faded-dark-vertical p-1 rounded-circle"></i>
                        </h6>
                    </div>
                </div>
            </div>'
                );
            }
            return Response::json([
                'list' => $data,
                'totalRecord' => $total_record
            ]);
        }

        $getmonths = new GetMonthNameService();

        $withdraw_amounts = $withdraw_months = $deposit_amounts = [];
        $withdraw_calendar =  $getmonths->get_12_month_withdraw();
        $deposit_calendar =  $getmonths->get_12_month_deposit();
        foreach ($withdraw_calendar as $key => $value) {
            array_push($withdraw_amounts, $value->withdraw);
            array_push($withdraw_months, $value->Month . " " . $value->Year);
        }
        // return $deposit_calendar;
        foreach ($deposit_calendar as $key => $value) {
            array_push($deposit_amounts, $value->deposit);
        }
        $withdraw_months = ($getmonths) ? $getmonths->get_month_name($withdraw_months) : [];
        // return $withdraw_months;
        $withdraw_months = json_encode($withdraw_months);
        $withdraw_amounts = json_encode($withdraw_amounts);
        $deposit_amounts = json_encode($deposit_amounts);


        //------------Trades Count by every date for trade chart-------------------
        $dates = [];
        $date = \Carbon\Carbon::today()->subDays(7);


        $trades_date = ComTrade::select('created_at')->where('created_at', '>=', $date)->distinct()->orderBy('created_at', 'asc')->get();
        // $trades_date = ComTrade::select()->get();
        foreach ($trades_date as $date) {
            $day = $date->created_at->format('l');
            array_push($dates,  $day);
        }
        $dates = json_encode($dates);


        $date_count = $each_date = [];
        $datas = ComTrade::whereBetween('created_at', [now()->subDays(7), now()])
            ->select(DB::raw('date(created_at) as date'), DB::raw('count(*) as count'))
            ->groupBy('date')
            ->get();

        foreach ($datas as $data) {
            array_push($date_count, $data->count);
            array_push($each_date, $data->date);
        }
        $date_count = json_encode($date_count);
        $each_date = json_encode($each_date);
        //------------Trades Count by every date for trade chart end-------------------


        // trader total balance
        $total_balance = BalanceSheetService::trader_wallet_balance(auth()->user()->id);
        // $total_withdraw = AllFunctionService::trader_total_withdraw(auth()->user()->id);
        $total_withdraw = BalanceService::trader_total_withdraw(auth()->user()->id);
        $total_deposit = AllFunctionService::trader_total_deposit(auth()->user()->id);
        $trading_account_exists = TradingAccount::where('user_id', auth()->user()->id)->exists();
        // ***************************************************************************************************************************************
        // lite dashboard data
        /****************************************************************************************** */
        $getmonths = new GetMonthNameService();

        $withdraw_calendar =  $getmonths->get_12_month_withdraw();
        $deposit_calendar =  $getmonths->get_12_month_deposit();
        // get monthly total data 
        $month_count = [];
        $monthly_balance = [];
        $monthly = [0, 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

        $z = date('m') - 12;

        for ($z; $z < date('m') + 1; $z++) {
            if ($z !== 0) {
                if ($z < 0) {
                    $month = 12 + ($z + 1);
                    array_push($month_count, $monthly[$month]);
                    array_push($monthly_balance, round($getmonths->get_12_month_total(auth()->user()->id, $month), 2));
                } else {
                    $month = $z;
                    array_push($month_count, $monthly[$month]);
                    array_push($monthly_balance, round($getmonths->get_12_month_total(auth()->user()->id, $month), 2));
                }

                // echo $getmonths->get_12_month_total(auth()->user()->id, $month);
            }
        }

        // get total bonus
        $count_bonus = BonusService::count_active_bonus(auth()->user()->id);
        $active_bonus = BonusService::get_active_bonus(auth()->user()->id);
        // return view and data
        return view(

            (VersionControllService::check_version() === 'lite') ? 'traders.dashboard-lite' : 'traders.dashboard',
            [
                // 'trading_accounts' => $trading_accounts,
                'withdraw_months' => (VersionControllService::check_version() === 'lite') ? json_encode($withdraw_months) : $withdraw_months,
                'withdraw_amounts' => (VersionControllService::check_version() === 'lite') ? json_encode($withdraw_amounts) : $withdraw_amounts,
                'deposit_amounts' => (VersionControllService::check_version() === 'lite') ? json_encode($deposit_amounts) : $deposit_amounts,
                'monthly' => json_encode($month_count), //lite version data
                'monthly_balance' => json_encode($monthly_balance), //lite version data
                'platform' => $platform,
                'total_balance' => $total_balance,
                'total_withdraw' => $total_withdraw,
                'total_deposit' => $total_deposit,
                'trading_account_exists' => $trading_account_exists,
                'trade_count' => $date_count,
                'dates' => $dates,
                'bonus_count' => $count_bonus,
                'active_bonus' => $active_bonus,
                'non_participate_contest' => ContestService::non_participate_contest(auth()->user()->id),
            ]
        );
    }

    // user language change 
    public function user_language_change(Request $request)
    {
        // set request from user panel
        App::setLocale($request->lang);
        if (array_key_exists($request->lang, Config::get('languages'))) {
            Session::put('locale', $request->lang);
        }
        if (Session::has('locale')) {
            return Response::json(['status' => true]);
        } else {
            return Response::json(['status' => false]);
        }
    }
}
