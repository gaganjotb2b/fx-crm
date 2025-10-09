<?php

namespace App\Http\Controllers\IB;

use App\Http\Controllers\Controller;
use App\Models\admin\SystemConfig;
use App\Models\PopupImage;
use App\Services\api\FileApiService;
use App\Services\AllFunctionService;
use App\Services\balance\BalanceSheetService;
use App\Services\BalanceService;
use App\Services\GetMonthNameService;
use App\Services\PermissionService;
use App\Services\systems\VersionControllService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;

class IbDashboardController extends Controller
{
    public function __construct()
    {
        // controll ib for combined crm
        if (request()->is('ib/dashboard')) {
            // $this->middleware(PermissionService::is_combined());
            $this->middleware('is_ib');
        }
    }
    // view ib dashbaord
    public function index()
    {
        try {
            $copyright = SystemConfig::select('copyright')->first();
            $getmonths = new GetMonthNameService();
            $commission_amounts = $commission_month = $withdraw_amounts = [];
            $commission_calendar =  AllFunctionService::ib_com_12_month(null);
            $withdraw_calendar =  AllFunctionService::withdraw_12_month(null);
            $todays_ib_erning =  BalanceService::todays_ib_erning(null);
            foreach ($commission_calendar as $key => $value) {
                array_push($commission_amounts, $value->amount);
                array_push($commission_month, $value->Month . " " . $value->Year);
            }
            foreach ($withdraw_calendar as $key => $value) {
                array_push($withdraw_amounts, $value->withdraw);
            }
            $total_trader = AllFunctionService::total_trader(null);
            $total_sub_ib = AllFunctionService::total_sub_ib(null);
            $my_trader = AllFunctionService::my_trader_commission(null);
            // return $my_trader;
            $apx_lot = AllFunctionService::apx_lot(null);
            $apx_cent_lot = AllFunctionService::apx_cent_lot(null);
            $yesterday_ib_erning = BalanceService::yesterday_ib_erning(null);

            // ib commission reference
            $sub_ib_trader_com = AllFunctionService::sub_ib_commission(null);

            // commission by day chart
            $commission_day_chart = AllFunctionService::commission_day_chart(null);
            $commission_days = json_encode($commission_day_chart['days']);
            $commission_day_value = json_encode($commission_day_chart['value']);

            // instrument chart
            $insturment_with_com = AllFunctionService::instrument_with_commission(null);
            $commision_by_instrument = AllFunctionService::commission_by_instrument(null);
            // print_r($commision_by_instrument);
            // die;
            $instruments = array();
            $instruments_amount = array();
            $colors = ['var(--custom-primary)',  '#788aa9',  '#2182fe',  '#48c533',  '#f87e36',  '#FF0080', '#A8B8D8', '#21d4fd', '#98ec2d', '#ff667c'];
            $i = 0;
            $background_color = array();
            foreach ($commision_by_instrument as $key => $value) {
                array_push($instruments, $value->symbol);
                array_push($instruments_amount,  $value->sum);
                array_push($background_color,  rand_color());
                $i++;
            }
            // end instrument chart
            $commission_month = $getmonths->get_month_name($commission_month);
            // return $withdraw_months;
            $commission_months = json_encode($commission_month);
            $commission_amounts = json_encode($commission_amounts);
            $withdraw_amounts = json_encode($withdraw_amounts);
            // check crm version

            $popup = PopupImage::where('status', 1)->first();
            $popup_data = [];
            if ($popup) {
                $popup_file = FileApiService::contabo_file_path(isset($popup->image) ? $popup->image : '');
                $popup_data = [
                    'popup_id' => $popup->id,
                    'file_url' => $popup_file['dataUrl'],
                ];
            }

            return view((VersionControllService::check_version() === 'lite') ? 'ibs.dashboard-lite' : 'ibs.dashboard',
                [
                    'commission_months' => $commission_months,
                    'commission_amounts' => $commission_amounts,
                    'withdraw_amounts' => $withdraw_amounts,
                    'ib_referral' => AllFunctionService::ib_referel_link(null),
                    'trader_referral' => AllFunctionService::trader_referel_link(null),
                    'ib_balance' => BalanceSheetService::ib_wallet_balance(auth()->user()->id),
                    'total_trader' => $total_trader,
                    'total_sub_ib' => $total_sub_ib ?? 0,
                    'my_trader_commission' => $my_trader,
                    'instruments' => json_encode($instruments),
                    'instruments_amount' => json_encode($instruments_amount),
                    'instrument_backround' => json_encode($background_color),
                    'all_instrument_percent' => $insturment_with_com,
                    'colors' => $colors,
                    'apx_lot' => $apx_lot,
                    'apx_cent_lot' => $apx_cent_lot,
                    'todays_ib_erning' => $todays_ib_erning,
                    'yesterday_ib_erning' => $yesterday_ib_erning,
                    'commission_day_chart_days' => $commission_days,
                    'commission_day_chart_value' => $commission_day_value,
                    'sub_ib_trader_com' => $sub_ib_trader_com,
                    'client_deposit_balance' => BalanceService::client_deposit_balance(null),
                    'client_withdraw_balance' => BalanceService::client_withdraw_balance(null),
                    'total_commission' => BalanceService::total_commission(null),
                    'copyright' => $copyright,
                    'popup_data' => $popup_data,
                    'popup_visibility' => auth()->user()->popup_id == "" ? "visible" : "invisible"
                ]
            );
        } catch (\Throwable $th) {
            throw $th;
            return 'Got a seraver error';
        }
    }
    // user language change 
    public function ib_language_change(Request $request)
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
