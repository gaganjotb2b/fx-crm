<?php

namespace App\Http\Controllers;

use App\Http\Controllers\traders\praxis\PraxisPaymentController;
use App\Models\admin\InternalTransfer;
use App\Models\BalanceSheet;
use App\Models\Deposit;
use App\Models\ExternalFundTransfers;
use App\Models\Log;
use App\Models\User;
use App\Models\IbIncome;
use App\Models\Withdraw;
use App\Models\CommissionStatus;
use App\Services\AllFunctionService;
use App\Services\balance\BalanceSheetService;
use App\Services\BalanceService;
use App\Services\BankService;
use app\Services\bonus\BonusCreditService;
use App\Services\bonus\BonusService;
use App\Services\CombinedService;
use App\Services\commission\CommissionStructureService;
use App\Services\commission\IbCommissionService;
use App\Services\commission\IbCommissionVersionTwo;
use App\Services\commission\IbCommssionVersionTwo;
use App\Services\commission\LevelService;
use App\Services\commission\TreeService;
use App\Services\contest\ContestService;
use App\Services\CopyApiService;
use App\Services\currency\GoogleCurrencyService;
use App\Services\CurrencyUpdateService;
use App\Services\deposit\B2bDepositService;
use App\Services\EmailService;
use App\Services\IBManagementService;
use App\Services\IbService;
use App\Services\manager\ManagerService;
use App\Services\MT4API;
use App\Services\Mt5WebApi;
use App\Services\OpenLiveTradingAccountService;
use App\Services\PermissionService;
use App\Services\PriceService;
use App\Services\systems\PlatformService;
use App\Services\systems\TransactionSettings;
use App\Services\systems\VersionControllService;
use App\Services\Trader\TraderAffiliatService;
use App\Services\Trader\WelcomeMailService;
use App\Services\trades\Mt5Trades;
use App\Services\social_trades\MasterProfitService;
use App\Services\social_trades\MasterProfitServiceTest;
use App\Models\Mt5Trade;
use App\Models\PammProfitShare;
use App\Models\IbCommissionStructure;
use App\Services\trades\ProfitService;
use App\Services\VertexApiCall;
use App\Services\VertexFnService;
use App\Services\pamm\ProfitShareService;
use App\Services\tournaments\GroupTradeCalculationService;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;


use App\Models\tournaments\TourParticipant;
use App\Models\tournaments\TourGroup;
use App\Models\tournaments\TourSetting;

class TestController extends Controller
{
    public function test(Request $request){
        $mt5trade = new Mt5Trades();
        return $mt5trade->margeTrades();
    }
    
    public function test22(Request $request)
    {
        // echo date('Y-m-d H:i:s');
        // die;
        // $obj = new GroupTradeCalculationService();
        // return $obj->groupTradeCalculation();
        // die;
        
        // $tourSetting = TourSetting::select()->first();

        // $tourGroups = TourGroup::with('participants')
        //     ->whereNotNull('start_trading')
        //     ->where('status', 'enabled')
        //     ->has('participants')
        //     ->first();
        
        // if (!$tourGroups) return "No active tour group found.";
        
        // // Shift start time 6 hours earlier
        // $startTrading = Carbon::parse($tourGroups->start_trading);
        // $tradingEnd = $startTrading->copy()->addDays($tourSetting->group_trading_duration);
        
        // // Check if trading is still ongoing
        // if ($tradingEnd->greaterThanOrEqualTo(Carbon::now())) {
        //     foreach ($tourGroups->participants as $row) {
        //         // Use open time for filtering trades within the range
        //         $result = Mt5Trade::where('LOGIN', $row->account_num)
        //             ->whereBetween('OPEN_TIME', [$startTrading, $tradingEnd]);
        
        //         // Calculate total profit and volume
        //         $total_profit = $result->sum('PROFIT');
        //         $total_volume = $result->sum('VOLUME');
        
        //         // Update the participant record
        //         TourParticipant::where('group_id', $row->group_id)
        //             ->where('account_num', $row->account_num)
        //             ->update([
        //                 'group1_profit' => $total_profit,
        //                 'group1_volume' => $total_volume/100,
        //                 'status'        => 'disable',
        //             ]);
        //     }
        
        //     return 'Profit/Volume updated for all participants.';
        // } else {
        //     return "false"; // Still within trading period
        // }

        // die;
        
            
        // return $expiredParticipants = TourParticipant::select([
        //     'tour_participants.user_id',
        //     'tour_participants.account_id',
        //     'tour_participants.account_num',
        //     'tour_groups.id as group_id',
        //     'tour_groups.start_trading'
        // ])
        // ->join('tour_groups', 'tour_participants.group_id', 'tour_groups.id')
        // ->join('tour_settings', 'tour_groups.tournament_id', 'tour_settings.id')
        // ->where('tour_settings.group_trading_duration', 2)
        // // ->whereRaw('NOW() >= DATE_ADD(tour_groups.start_trading, INTERVAL 2 DAY)')
        // ->get();
        
        // $masterProfit = new MasterProfitServiceTest();
        // return $masterProfit->releaseMasterProfit();
        die;
        // // return "sdfsd";
        //   $mt5_api = new Mt5WebApi();
        // $data = array(
        //     "Login" => (int)2100604
        // );
        // $result = $mt5_api->execute('AccountGetMargin', $data);
        //  return $result;
        
        // $trade = Mt5Trade::where([
        //         'TICKET' => (int)2425927,
        //         'LOGIN' => (int)2104414,
        //     ])->with('account', 'account.user', 'account.user.parentIb')->first();
        // return $trade->account->commission_status;
        
        // $masterProfit = new MasterProfitService();
        // $masterProfit->releaseMasterProfit();
        
        // return $masterProfit->getMasterProfit();
        
        // return ProfitShareService::profit_share();
        die;
        // try {
        //     $trade = Mt5Trade::where([
        //         'TICKET' => (int)1109491,
        //         'LOGIN' => (int)2101758,
        //     ])->with('account', 'account.user', 'account.user.parentIb')->first();
        //     if ($trade) {
        //         // $trade->DEAL = $request->Deal;
        //         // $trade->PROFIT = $request->Profit;
        //         // $trade->CLOSE_TIME = date('Y-m-d H:i:s', $request->Time);
        //         // $trade->CLOSE_PRICE = $request->Price;
        //         // // $trade->all_data = json_encode($request->all());
        //         // $update = $trade->save();
        //     }
        //     // LogData::create(['log' => json_encode($request->all()), 'type' => 'make commission']);
        //     // return $trade;
        //     // make commission
        //     // ------------------------------------
        //     $account = $trade->account;
        //     if ($account != "") {

        //         $trader = $trade->account->user;
        //         $instant_ib = $trade->account->user->parentIb ?? "";
        //         // 
        //         // check has instant IB
        //         if ($instant_ib == "") {
        //             // LogData::create(['log' => json_encode($instant_ib), 'type' => 'no ib']);
        //             // CommissionStatus::create([
        //             //     'ticket' => $trade->TICKET,
        //             //     'trader' => $trader->id,
        //             //     'login' => $trade->LOGIN,
        //             //     'status' => 'NO IB',
        //             //     'open_time' => date('Y-m-d H:i:s', strtotime($trade->OPEN_TIME)),
        //             //     'close_time' => date('Y-m-d H:i:s', strtotime($trade->CLOSE_TIME)),
        //             //     'tradeable_type' => 'App\Models\Mt5Trade'
        //             // ]);
        //             return Response::json([
        //                 'status' => true,
        //                 'message' => 'NO IB'
        //             ]);
        //         }
        //         // check trade is deleted
        //         if ($trade->COMMENT == 'deleted [no money]') {
        //             // CommissionStatus::create([
        //             //     'ticket' => $trade->TICKET,
        //             //     'trader' => $trader->id,
        //             //     'login' => $trade->LOGIN,
        //             //     'status' => 'CANCELED',
        //             //     'open_time' => date('Y-m-d H:i:s', strtotime($trade->OPEN_TIME)),
        //             //     'close_time' => date('Y-m-d H:i:s', strtotime($trade->CLOSE_TIME)),
        //             //     'tradeable_type' => 'App\Models\Mt5Trade'
        //             // ]);
        //             return Response::json([
        //                 'status' => true,
        //                 'message' => 'CANCELED'
        //             ]);
        //         }
        //         //FIND Related IB
        //         //Commissions IBs
        //         $all_parent_ib_with_master = IbService::all_parents_with_master($instant_ib->id);
        //         $master = array_filter($all_parent_ib_with_master, fn ($item) => $item['is_master'] === true);
        //         if (count($all_parent_ib_with_master)) {
        //             $master_id = !empty($master) ? reset($master)['id'] : null;
        //             $masterCommissionStatus  = !empty($master) ? reset($master)['individual_commission'] : null;
        //         } else {
        //             $master_id = $instant_ib->id;
        //             $masterCommissionStatus = $instant_ib->individual_commission ?? null;
        //         }

        //         $all_parent_ib = array_map(fn ($item) => $item['id'], $all_parent_ib_with_master);
        //         // LogData::create(['log' => json_encode($all_parent_ib_with_master), 'type' => 'make commission master status']);


        //         array_push($all_parent_ib, $instant_ib->id);
        //         $total_ib = count($all_parent_ib);
        //         // return $all_parent_ib;
        //         // return $total_ib;
        //         $structure = [];
        //         foreach ($all_parent_ib as $value) {
        //             $ib_level = LevelService::get_level($value, $trader->id);
        //             $ib_group_id = $this->get_ib_group_id($value);
        //             $ib_commission_structure = IbCommissionStructure::where([
        //                 ['symbol', $trade->SYMBOL],
        //                 ['client_group_id', $account->group_id],
        //                 // ['ib_group_id', $ib_group_id],
        //             ])->with('customCommission')->whereNot('status', 2)->first();
        //             // $admin_structure = IbCommissionStructure::where([
        //             //     ['symbol', $trade->SYMBOL],
        //             //     ['client_group_id', $account->group_id],
        //             //     ['ib_group_id', $ib_group_id],
        //             // ])->with('customCommission')->whereNot('status', 2)->first();
        //             if (!$ib_commission_structure) {
        //                 echo "com not found";
        //                 // CommissionStatus::create([
        //                 //     'ticket' => $trade->TICKET,
        //                 //     'ib' => $value,
        //                 //     'trader' => $trader->id,
        //                 //     'login' => $trade->LOGIN,
        //                 //     'status' => 'COMMISSION NOT FOUND',
        //                 //     'open_time' => date('Y-m-d H:i:s', strtotime($trade->OPEN_TIME)),
        //                 //     'close_time' => date('Y-m-d H:i:s', strtotime($trade->CLOSE_TIME)),
        //                 //     'tradeable_type' => 'App\Models\Mt5Trade'
        //                 // ]);
        //                 continue;
        //             }
        //             // check 
        //             if ($ib_commission_structure) {
        //                 // finding the level commission
        //                 // -----------------------------------------
        //                 $commissions = json_decode($ib_commission_structure->commission);
        //                 $level_com = 0;
        //                 if ($total_ib >= count($commissions)) {
        //                     $desiredSubarray = $commissions;
        //                 } else {
        //                     // -------------------------------
        //                     // custom commission
        //                     // -------------------------------
        //                     $custom_com = $ib_commission_structure->customCommission;
        //                     $custom_com_array = [];
        //                     foreach ($custom_com as $com_value) {
        //                         $sub_array = json_decode($com_value->custom_commission);
        //                         $sub_array = array_filter($sub_array, 'is_numeric');
        //                         $custom_com_array[] = $sub_array;
        //                     }
        //                     $custom_com = array_filter($custom_com_array, function ($subArray) use ($total_ib) {
        //                         return count($subArray) === $total_ib;
        //                     });
        //                     $desiredSubarray = null;

        //                     foreach ($custom_com as $subArray) {
        //                         if (count($subArray) == $total_ib) {
        //                             $desiredSubarray = $subArray;
        //                             break;
        //                         }
        //                     }
        //                 }

        //                 if (isset($desiredSubarray) && is_array($desiredSubarray) && isset($desiredSubarray[$ib_level - 1])) {
        //                     $level_com = $desiredSubarray[$ib_level - 1]; // resulted commission of level
        //                 }

        //                 if (!$this->checkTradeTime($ib_commission_structure->timing, $trade->OPEN_TIME, $trade->CLOSE_TIME)) {
        //                     return "time ignore";
        //                     // CommissionStatus::create([
        //                     //     'ticket' => $trade->TICKET,
        //                     //     'ib' => $value,
        //                     //     'trader' => $trader->id,
        //                     //     'login' => $trade->LOGIN,
        //                     //     'status' => 'TIME IGNORE',
        //                     //     'open_time' => date('Y-m-d H:i:s', strtotime($trade->OPEN_TIME)),
        //                     //     'close_time' => date('Y-m-d H:i:s', strtotime($trade->CLOSE_TIME)),
        //                     // ]);
        //                     continue;
        //                 }
        //                 // ending the level commission
        //                 // ----------------------------------------------

        //                 if ($level_com <= 0) {
        //                     echo "zero com";
        //                     // CommissionStatus::create([
        //                     //     'ticket' => $trade->TICKET,
        //                     //     'ib' => $value,
        //                     //     'trader' => $trader->id,
        //                     //     'login' => $trade->LOGIN,
        //                     //     'status' => 'ZERO COMMISSION',
        //                     //     'open_time' => date('Y-m-d H:i:s', strtotime($trade->OPEN_TIME)),
        //                     //     'close_time' => date('Y-m-d H:i:s', strtotime($trade->CLOSE_TIME)),
        //                     //     'tradeable_type' => 'App\Models\Mt5Trade'
        //                     // ]);
        //                     continue;
        //                 } else {
        //                     $amount = ($trade->VOLUME / 100) * $level_com;
        //                     echo $amount . " ";
        //                     // IbIncome::updateOrCreate(
        //                     //     [
        //                     //         'ib_id' => $value,
        //                     //         'trader_id' => $trader->id,
        //                     //         'order_num' => $trade->TICKET,
        //                     //     ],
        //                     //     [
        //                     //         'trading_account' => $trade->LOGIN,
        //                     //         'symbol' => $trade->SYMBOL,
        //                     //         'cmd' => $trade->CMD,
        //                     //         'volume' => $trade->VOLUME,
        //                     //         'profit' => $trade->PROFIT,
        //                     //         'open_time' => $trade->OPEN_TIME,
        //                     //         'close_time' => $trade->CLOSE_TIME,
        //                     //         'comment' => $trade->COMMENT,
        //                     //         'amount' => $amount,
        //                     //         'com_level' => $ib_level,
        //                     //         'level_com' => $level_com,
        //                     //         'total_ibs' => $total_ib,
        //                     //         'account_group' => $account->group_id,
        //                     //         'ip' => request()->ip(),
        //                     //         'ib_group' => $ib_group_id
        //                     //     ]
        //                     // );
        //                     // CommissionStatus::create([
        //                     //     'ticket' => $trade->TICKET,
        //                     //     'ib' => $value,
        //                     //     'trader' => $trader->id,
        //                     //     'login' => $trade->LOGIN,
        //                     //     'status' => 'CREDITED',
        //                     //     'open_time' => date('Y-m-d H:i:s', strtotime($trade->OPEN_TIME)),
        //                     //     'close_time' => date('Y-m-d H:i:s', strtotime($trade->CLOSE_TIME)),
        //                     //     'tradeable_type' => 'App\Models\Mt5Trade'
        //                     // ]);
        //                     continue;
        //                 }
        //             } else {
        //                 echo "not found";
        //                 // CommissionStatus::create([
        //                 //     'ticket' => $trade->TICKET,
        //                 //     'ib' => $value,
        //                 //     'trader' => $trader->id,
        //                 //     'login' => $trade->LOGIN,
        //                 //     'status' => 'COMMISSION NOT FOUND',
        //                 //     'open_time' => date('Y-m-d H:i:s', strtotime($trade->OPEN_TIME)),
        //                 //     'close_time' => date('Y-m-d H:i:s', strtotime($trade->CLOSE_TIME)),
        //                 //     'tradeable_type' => 'App\Models\Mt5Trade'
        //                 // ]);
        //                 continue;
        //             }
        //         }
        //     }
            
            
            
        //     // // outgoing balance
        //     // //**********************************************************************
        //     // $ib_id = 28;
        //     // $total_ib_withdraw = Withdraw::where(function ($query) {
        //     //     $query->where('approved_status', 'A')
        //     //         ->orWhere('approved_status', 'P');
        //     // })->where('user_id', $ib_id)
        //     //     ->where('wallet_type', 'ib')->sum('amount');
        //     // // fexternal fund send
        //     // $external_fund_send = ExternalFundTransfers::where('sender_id', $ib_id)
        //     // ->where('sender_wallet_type', 'ib')    
        //     // ->where(function ($query) {
        //     //         $query->where('type', 'ib_to_trader')
        //     //             ->orWhere('type', 'ib_to_ib');
        //     //     })->where(function ($query) {
        //     //         $query->where('status', 'A')
        //     //             ->orWhere('status', 'P');
        //     //     })->sum('amount');
        //     // //********************************************************************
        //     // $deposit = Deposit::where('approved_status', 'A')
        //     //     ->where('wallet_type', 'ib')->where('user_id', $ib_id)->sum('amount');
        //     // // external fund receive
        //     // $external_fund_rec = ExternalFundTransfers::where('receiver_id', $ib_id)
        //     //     ->where(function ($query) {
        //     //         $query->where('type', 'ib_to_ib')
        //     //             ->orWhere('type', 'trader_to_ib');
        //     //     })
        //     //     ->where('status', 'A')
        //     //     ->where('receiver_wallet_type', 'ib')
        //     //     ->sum('amount');
        //     // $ib_income = IbIncome::where('ib_id', $ib_id)->sum('amount');
        //     // $balance = ($deposit + $external_fund_rec + $ib_income) - ($total_ib_withdraw + $external_fund_send);
        //     // return round($balance, 2);
            
        //     // trader balance
        //     // $user_id = 260;
        //     // $total_withdraw = Withdraw::where('user_id', $user_id)
        //     //     ->where(function ($query) {
        //     //         $query->where('approved_status', 'A')
        //     //             ->orWhere('approved_status', 'P');
        //     //     })->where('wallet_type', 'trader');
        //     // $withdraw_charg = $total_withdraw->sum('charge');
        //     // $total_withdraw = $total_withdraw->sum('amount'); // get data from withdraw table

        //     // $total_deposit = Deposit::where('user_id', $user_id)
        //     //     ->where('wallet_type', 'trader')
        //     //     ->where('approved_status', 'A');
        //     // $deposit_charge = $total_deposit->sum('charge');
        //     // $total_deposit = $total_deposit->sum('amount'); //get data from deposit table

        //     // $external_fund_send = ExternalFundTransfers::where('sender_id', $user_id)
        //     //     ->where('sender_wallet_type', 'trader')
        //     //     ->where(function ($query) {
        //     //         $query->where('status', 'A')
        //     //             ->orWhere('status', 'P');
        //     //     });
        //     // $external_charge = $external_fund_send->sum('charge');
        //     // $external_fund_send = $external_fund_send->sum('amount'); //get data from external fund table

        //     // $external_fund_rec = ExternalFundTransfers::where('receiver_id', $user_id)
        //     //     ->where('status', 'A')
        //     //     ->where('receiver_wallet_type', 'trader');
        //     // $ex_fund_rec_charge = $external_fund_rec->sum('charge');
        //     // $external_fund_rec = $external_fund_rec->sum('amount'); // get data from external fund receive

        //     // $atw_internal = InternalTransfer::where('user_id', $user_id)->where('type', 'atw')
        //     //     ->where(function ($query) {
        //     //         $query->where('status', 'A')
        //     //             ->orWhere('status', 'P');
        //     //     });
        //     // $atw_internal_charge = $atw_internal->sum('charge');
        //     // $atw_internal = $atw_internal->sum('amount'); // get data from account to wallet

        //     // $wta_internal = InternalTransfer::where('user_id', $user_id)
        //     //     ->where('type', 'wta')->where('status', 'A');
        //     // $wta_internal_charge = $wta_internal->sum('charge');
        //     // $wta_internal = $wta_internal->sum('amount'); //get data from internal fund table
        //     // // return round(($total_withdraw + $wta_internal + $external_fund_send), 2);
        //     // $balance = round(($total_deposit + $atw_internal + $external_fund_rec), 2) - round(($total_withdraw + $wta_internal + $external_fund_send), 2);
        //     // $charge = ($deposit_charge + $withdraw_charg + $external_charge + $ex_fund_rec_charge + $atw_internal_charge + $wta_internal_charge);
        //     // $balance = round(($balance - $charge), 2);
        //     // return ($balance);
            
        //     // $mt5trade = new Mt5Trades();
        //     // return $mt5trade->margeTrades();
        //     // return IbCommissionVersionTwo::create_commission();
        // } catch (\Throwable $th) {
        //     throw $th;
        // }
    }
    public function testv2(Request $request)
    {
        // Artisan::call('optimize:clear');
        // die;

        // $mt5_api = new Mt5WebApi();
        // $data = array(
        //     "Login" => (int)1003
        // );
        // $result = $mt5_api->execute('AccountGetMargin', $data);
        //  return $result;

        //   $data = array(
        //     'Position' => 1
        // );
        // $result = $mt5_api->execute('GroupGet', $data);
        // return $result;

        // return TransactionSettings::is_account_withdraw();

        // return ContestService::contest_popup_file(1);
        $mt5api = new Mt5WebApi();
        // $data = array(
        //     'Login' => 97900009,
        // );
        // $result = $mt5api->execute('AccountGetMargin', $data);
        // $data = array(
        //     'Login' => 1003,
        // );
        // $result = $mt5api->execute('UserDataGet', $data);
        // return $result;
        // $data = array(
        //     'Position' => 0
        // );
        // $data = array(
        //     "Email" => "Shaheeen reza",
        //     "Login" => null,
        //     "Group" => "IB_2010_02",
        //     "Leverage" => (int) 500,
        //     "Comment" => "na",
        //     "Phone" =>  "na",
        //     "Name" => "na",
        //     "Country" => "na",
        //     "City" => "na",
        //     "State" => "na",
        //     "ZipCode" => "na",
        //     "Address" => "na",
        //     'Password' => "dkkdksl1232132",
        //     'InvestPassword' => "dkkdksl1232132"
        // );
        // // print_r();
        // return $mt5api->execute('AccountUpdate', $data);
        // $result = $mt5api->execute('GroupGet', $data);
        // return $result;
        // -------------------------------------------------\
        // $mt5trade = new Mt5Trades();
        // return $mt5trade->margeTrades();
        return IbCommssionVersionTwo::create_comssion();
    }

    public function custom_email($user_id)
    {
        // try {
        //     return EmailService::send_email('custom-mail', [
        //         'user_id' => $user_id,
        //         'message_header' => 'We hope this email finds you well. We are writing to inform you about two important updates that may impact our services:',
        //         'sessage_body' => '
        //             1. Data Shifting to CRM:
        //                 We are thrilled to announce the successful shift of our data management system to a more efficient and advanced Customer Relationship Management (CRM) platform. This upgrade enables us to better serve you and provide more personalised services tailored to your specific needs. We are confident that this transition will result in improved communication, streamlined processes, and an enhanced experience for all our valued clients.

        //             2. Copier Issues through Bot and Trading Disruption:
        //                 Regrettably, during the integration of our new CRM system, we encountered unexpected issues with the copier bot, which have led to temporary disruptions in our trading activities. We apologise for any inconvenience this may cause you. Please rest assured that our team is working tirelessly to resolve these problems promptly. Our technical experts are actively investigating the bot-related issues and collaborating with the copier manufacturer to implement the necessary fixes.

        //             We understand the importance of seamless service and are committed to minimising any impact on your trading experience. Our support team is available 24/7 to address any urgent trading-related inquiries you may have during this period.

        //             Your trust and partnership are of utmost importance to us, and we sincerely appreciate your understanding and patience as we work to rectify these challenges. We will keep you updated on the progress and notify you once normal trading operations are restored.

        //             If you have any questions or concerns, please do not hesitate to reach out to us. Thank you for your continued support.
        //         ',
        //     ]);
        // } catch (\Throwable $th) {
        //     //throw $th;
        //     return 0;
        // }

        return B2bDepositService::get_wallet();
    }
    // 
    
    
    
    
    public function checkTradeTime($COM_TIME, $OPEN_TIME, $CLOSE_TIME)
    {
        $time_input = $COM_TIME;
        $timing = explode(":", $time_input);
        $in_hou = $timing[0] * 60;
        $in_min = $timing[1] * 60;
        $in_sec = $timing[1];

        $sec = $in_hou + $in_min + $in_sec;

        $open_time_ob = new DateTime($OPEN_TIME);
        $close_time_ob = new DateTime($CLOSE_TIME);
        $diff_in_sencond = $close_time_ob->getTimestamp() - $open_time_ob->getTimestamp();

        if ($diff_in_sencond >= $sec) {
            return true;
        }

        return false;
    }
    public function get_ib_group_id($ib_id)
    {
        try {
            $user = User::where('id', $ib_id)->select('ib_group_id')->first();
            $ib_group = $user->ib_group_id;
            return $ib_group;
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function remaining_setup()
    {
        try {
            $result = RemainingComSetup::first();
            if ($result->remaining === 'true') {
                return true;
            } elseif ($result->remaining === 'false') {
                return false;
            }
            return false;
        } catch (\Throwable $th) {
            //throw $th;
            return false;
        }
    }
    public function client_remaining_com($ib_id)
    {
        try {
            if ($this->remaining_setup()) {
                $result = User::where('id', $ib_id)->select('remaining_com')->first();
                if ($result->remaining_com == 1) {
                    return true;
                }
                return false;
            }
            return false;
        } catch (\Throwable $th) {
            throw $th;
        }
    }


    public function get_level($ib_id, $reference_id, $level = 0)
    {
        try {
            if ($ib_id == $reference_id) {
                $ib = IB::where('ib_id', $ib_id)->with('ibDetails')->first();
                return [
                    'level' => $level,
                    'ib_id' => $ib_id
                ];
            }
            $ib = IB::where('ib_id', $ib_id)->with('ibDetails')->get();
            if ($ib) {
                foreach ($ib as $value) {
                    $result = $this->get_level($value->reference_id, $reference_id, $level + 1);
                    if ($result !== false) {
                        return $result;
                    }
                }
            }
            return false;
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
