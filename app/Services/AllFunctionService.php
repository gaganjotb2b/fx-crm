<?php

namespace App\Services;

use App\Models\admin\InternalTransfer;
use App\Models\admin\SystemConfig;
use App\Models\BonusUser;
use App\Models\Deposit;
use App\Models\ExternalFundTransfers;
use App\Models\IB;
use App\Models\IbCommissionStructure;
use App\Models\IbGroup;
use App\Models\IbIncome;
use App\Models\IbSetting;
use App\Models\IbTransfer;
use App\Models\KycRequired;
use App\Models\KycVerification;
use App\Models\Mt5Trade;
use App\Models\SystemModule;
use App\Models\Trade;
use App\Models\TraderSetting;
use App\Models\TradingAccount;
use App\Models\PammProfitShare;
use App\Models\User;
use App\Models\UserDescription;
use App\Models\WalletUpDown;
use App\Models\Withdraw;
use App\Services\bonus\BonusService;
use App\Services\CombinedService;
use App\Models\ManagerUser;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;

/**
 * CrmApiService Api new
 */

class AllFunctionService
{
    private $tree = [];
    private $parent_ib = [];
    private $permission;
    private $module;
    private $prefix;
    public function __construct()
    {
        $this->prefix = DB::getTablePrefix();
    }
    public function __call($name, $data)
    {
        if ($name === 'ib_com_12_month') {
            return $this->get_ib_com_12_month($data[0]);
        }
        if ($name === 'withdraw_12_month') {
            return $this->get_12_month_withdraw($data[0]);
        }
        if ($name === 'ib_referel_link') {
            return $this->create_referral($data[0], 'ib');
        }
        if ($name === 'trader_referel_link') {
            return $this->create_referral($data[0], 'trader');
        }
        // count total trader under an ib
        if ($name === 'total_sub_ib') {
            return $this->get_total_sub_ib($data[0]);
        }
        // count total trader under an ib
        if ($name === 'total_trader') {
            return $this->get_total_trader($data[0]);
        }
        // sum master ib trader commission
        if ($name === 'my_trader_commission') {
            return $this->master_ib_trader_commission($data[0]);
        }
        // ib commission by instrument
        if ($name === 'commission_by_instrument') {
            return $this->get_commission_by_instrument($data[0]);
        }
        // ib instrument with commission
        if ($name === 'instrument_with_commission') {
            return $this->get_instrument_with_percent($data[0]);
        }
        // get approximate lot of ib income
        if ($name === 'apx_lot') {
            return $this->get_approximate_lot($data[0]);
        }  
        // get approximate cent lot of ib income
        if ($name === 'apx_cent_lot') {
            return $this->get_approximate_cent_lot($data[0]);
        }
        // get sub ib
        if ($name === 'get_sub_ib') {
            return $this->get_sub_ib($data[0]);
        }
        // get sub ib with level
        if ($name === 'sub_ib_with_level') {
            return $this->get_sub_ib_with_level($data[0]);
        }
        if ($name === 'get_node_level') {
            return $this->get_level($data[0]);
        }
        // get kyc status
        if ($name === 'kyc_status') {
            return $this->check_kyc_status($data[0]);
        }
        if ($name === 'kyc_verified_unverified') {
            return $this->kyc_verified();
        }
        // get ib commission by level
        // ib commission from structure;
        if ($name === 'commission_level') {
            return $this->commission_by_level($data[0], $data[1]);
        }
    }
    // find 
    // call all function statically
    public static function  __callStatic($name, $data)
    {
        if ($name === 'ib_com_12_month') {
            return (new self)->get_ib_com_12_month($data[0]);
        }
        if ($name === 'withdraw_12_month') {
            return (new self)->get_12_month_withdraw($data[0]);
        }
        if ($name === 'ib_referel_link') {
            return (new self)->create_referral($data[0], 'ib');
        }
        if ($name === 'trader_referel_link') {
            return (new self)->create_referral($data[0], 'trader');
        }
        // count total trader under an ib
        if ($name === 'total_trader') {
            return (new self)->get_total_trader($data[0]);
        }
        // count total sub ib under an ib
        if ($name === 'total_sub_ib') {
            return (new self)->get_total_sub_ib($data[0]);
        }
        // sum master ib trader commission
        if ($name === 'my_trader_commission') {
            return (new self)->master_ib_trader_commission($data[0]);
        }
        // ib commission by instrument
        if ($name === 'commission_by_instrument') {
            return (new self)->get_commission_by_instrument($data[0]);
        }
        // ib instrument with commission
        if ($name === 'instrument_with_commission') {
            return (new self)->get_instrument_with_percent($data[0]);
        }
        // get approximate lot of ib income
        if ($name === 'apx_lot') {
            return (new self)->get_approximate_lot($data[0]);
        }
        // get approximate cent lot of ib income
        if ($name === 'apx_cent_lot') {
            return (new self)->get_approximate_cent_lot($data[0]);
        }
        // get sub ib
        if ($name === 'get_sub_ib') {
            return (new self)->get_sub_ib($data[0]);
        }
        // get sub ib with level
        if ($name === 'sub_ib_with_level') {
            return (new self)->get_sub_ib_with_level($data[0]);
        }
        // get ib level 
        if ($name === 'get_node_level') {
            return (new self)->get_level($data[0]);
        }
        // get ib chain 
        if ($name === 'ib_chain') {
            return (new self)->get_ib_chain($data[0], (array_key_exists(1, $data)) ? $data[1] : null);
        }
        // get ib day commission chart
        if ($name === 'commission_day_chart') {
            return (new self)->get_commission_daychart($data[0]);
        }
        // get ib commission by referenc
        if ($name === 'sub_ib_commission') {
            return (new self)->sub_ib_trader_commission($data[0]);
        }
        // get client under ib
        if ($name === 'trader_under_ib') {
            return (new self)->get_trader_under_ib($data[0]);
        }
        // get kyc status
        if ($name === 'kyc_status') {
            return (new self)->check_kyc_status($data[0]);
        }
        // get trader total balance
        if ($name === "trader_total_balance") {
            return (new self)->get_self_balance($data[0]);
        }
        // get trader total withdraw
        if ($name === "trader_total_withdraw") {
            return (new self)->get_total_withdraw($data[0], (array_key_exists(1, $data)) ? $data[1] : null, (array_key_exists(2, $data)) ? $data[$data[2]] : null);
        }
        // get trader total withdraw
        if ($name === "trader_total_deposit") {
            return (new self)->get_total_deposit($data[0], (array_key_exists(1, $data)) ? $data[1] : null, (array_key_exists(2, $data)) ? $data[$data[2]] : null);
        }
        // get trader total balance receive
        if ($name === "trader_balance_receive") {
            return (new self)->get_total_balance_receive($data[0]);
        }
        // find send or receive transaction
        if ($name === "sendOrReceive") {
            return (new self)->find_send_or_receive($data[0], $data[1]);
        }
        // get login browswer
        if ($name === "login_browser") {
            return (new self)->get_login_browser($data[0]);
        }
        // get login device
        if ($name === "login_device") {
            return (new self)->get_login_device($data[0]);
        }
        // kyc verified unverified
        if ($name === "kyc_verified_unverified") {
            return (new self)->kyc_verified();
        }
        // 12 month revenue
        if ($name === "get_revenue_report") {
            return (new self)->revenue_report();
        }
        // last 7 pending deposit for chart
        if ($name === "pending_deposit_chart") {
            return (new self)->last_pending_deposit();
        }
        // last 7 pending withdraw for chart
        if ($name === "pending_withdraw_chart") {
            return (new self)->last_pending_withdraw();
        }
        // permonth/days deposit for chart
        if ($name === "per_month_chart") {
            return (new self)->per_month_line_chart($data[0]);
        }
        // commission/deposit/withdraw chart
        if ($name === "commission_chart") {
            return (new self)->commission_chart_data();
        }
        // month_with name
        if ($name === "months_with_name") {
            return (new self)->months();
        }
        // per month deposit
        if ($name === "deposit_per_month") {
            return (new self)->total_deposit_per_month($data[0]);
        }
        // withdraw per month
        if ($name === "withdraw_per_month") {
            return (new self)->total_withdraw_per_month($data[0]);
        }
        // get login device icon
        if ($name === "device_icon") {
            return (new self)->login_device_icon($data[0]);
        }
        // get user email by id
        if ($name === "user_email") {
            return (new self)->get_user_email($data[0]);
        }
        // get user email by id
        if ($name === "user_name") {
            return (new self)->get_user_name($data[0]);
        }
        // get user profile photo by id
        if ($name === "user_profile") {
            return (new self)->get_user_profile($data[0]);
        }
        // get user type by id
        if ($name === "user_type") {
            return (new self)->get_user_type($data[0]);
        }
        // get internal transaction type by trans type
        if ($name === "internal_trans_type") {
            return (new self)->internal_transaction_type($data[0]);
        }
        // get all platform/server from system config
        if ($name === "all_platform") {
            return (new self)->get_all_platform();
        }
        // get all removed trading account 
        if ($name === "all_removed_trading_account") {
            return (new self)->get_all_removed_trading_account();
        }
        // meta account create autometically true or false
        if ($name === "create_meta_acc") {
            return (new self)->account_create_auto();
        }
        // social link required or not
        if ($name === "social_link_required") {
            return (new self)->social_link_reqired_reg();
        }
        // find ib groups name
        if ($name === "find_ib_group") {
            return (new self)->get_ib_group($data[0]);
        }
        // get access permission
        // trader settings/ ib settings
        if ($name === "access") {
            return (new self)->access_permission($data[0], $data[1]);
        }
        // get ib commission by level
        // ib commission from structure;
        if ($name === 'commission_level') {
            return (new self)->commission_by_level($data[0], $data[1]);
        }
        // get ib commission
        // sum of ib commission
        if ($name === 'sum_of_commission') {
            return (new self)->get_commission($data[0], $data[1]);
        }
        // get total trader / trade volume
        if ($name === 'total_volume') {
            return (new self)->get_total_volume($data[0], (array_key_exists(1, $data)) ? $data[1] : null);
        }
        // get total trades
        if ($name === 'total_trades') {
            return (new self)->get_total_trades($data[0], (array_key_exists(1, $data)) ? $data[1] : null);
        }
        // get total trading accounts
        if ($name === 'total_trading_accounts') {
            return (new self)->get_total_trading_accounts($data[0], (array_key_exists(1, $data)) ? $data[1] : null);
        }
        // get wallet to account transfer
        if ($name === 'total_wta_transfer') {
            return (new self)->get_total_wta_transfer($data[0], (array_key_exists(1, $data)) ? $data[1] : null);
        }
        // get account to wallet transfer
        if ($name === 'total_atw_transfer') {
            return (new self)->get_total_atw_transfer($data[0], (array_key_exists(1, $data)) ? $data[1] : null);
        }
        // get total bonus
        if ($name === 'total_bonus') {
            return (new self)->get_total_bonus($data[0], (array_key_exists(1, $data)) ? $data[1] : null);
        }
        // get total trader to trade send amount
        if ($name === 'total_trd_to_trd_send') {
            return (new self)->get_total_trd_to_trd_send($data[0], (array_key_exists(1, $data)) ? $data[1] : null);
        }
        // trader to trade recieve
        if ($name === 'total_trd_to_trd_recive') {
            return (new self)->get_total_trd_to_trd_recive($data[0], (array_key_exists(1, $data)) ? $data[1] : null);
        }
        // get total trader to ib send amount
        if ($name === 'total_trd_to_ib_send') {
            return (new self)->get_total_trd_to_ib_send($data[0], (array_key_exists(1, $data)) ? $data[1] : null);
        }
        // ib to trade recieve
        if ($name === 'total_receive_from_ib') {
            return (new self)->get_total_receive_from_ib($data[0], (array_key_exists(1, $data)) ? $data[1] : null);
        }
        // total withdraw with date/ trader
        if ($name === 'total_withdraw_with_date') {
            return (new self)->get_total_withdraw_with_date($data[0], (array_key_exists(1, $data)) ? $data[1] : null);
        }
        // total deposit with date/ trader
        if ($name === 'total_deposit_with_date') {
            return (new self)->get_total_deposit_with_date($data[0], (array_key_exists(1, $data)) ? $data[1] : null);
        }
    }
    // commission by level
    // commission from ib structure
    private function commission_by_level($ib_level, $staucture_id)
    {
        $ib_structure = IbCommissionStructure::find($staucture_id);
        $ib_com_level = $ib_structure->commission;
        $ib_com = json_decode($ib_com_level);
        $commission = 0;
        for ($i = 0; $i < count($ib_com); $i++) {
            if ($i == ($ib_level - 1)) {
                $commission = $ib_com[$i];
                break;
            }
        }
        return $commission;
    }
    // find send or receive
    private function find_send_or_receive($user_id = null, $transaction_id)
    {
        try {
            $data = [];
            if ($user_id == null) {
                $user_id = auth()->user()->id;
            }
            $status = '';
            if (ExternalFundTransfers::where('sender_id', $user_id)->where('external_fund_transfers.id', $transaction_id)->exists()) {
                $sender = ExternalFundTransfers::where('sender_id', $user_id)->where('external_fund_transfers.id', $transaction_id)
                    ->join('users', 'external_fund_transfers.receiver_id', '=', 'users.id')->first();
                $data['status'] = 'send';
                $data['name'] = $sender->name;
                $data['email'] = $sender->email;
            } else {
                $sender = ExternalFundTransfers::where('receiver_id', $user_id)->where('external_fund_transfers.id', $transaction_id)
                    ->join('users', 'external_fund_transfers.sender_id', '=', 'users.id')->first();
                $data['name'] = $sender->name;
                $data['email'] = $sender->email;
                $data['status'] = 'receive';
            }
            return $data;
        } catch (\Throwable $th) {
            //throw $th;
            $data['name'] = '';
            $data['email'] = '';
            $data['status'] = '';
            return $data;
        }
    }
    // get instrument with ib commission percents
    private function get_instrument_with_percent($ib_id = null)
    {
        if ($ib_id == null) {
            $ib_id = auth()->user()->id;
        }
        $tbl_ib_commission = DB::getTablePrefix() . 'ib_commission_structures';
        $tbl_ib_income = DB::getTablePrefix() . 'ib_incomes';
        $total_amount = IbIncome::where('ib_id', $ib_id)->sum('amount');
        $all_instrument = DB::select("SELECT IFNULL(sum(amount),0) as sum, $tbl_ib_commission.symbol from $tbl_ib_commission LEFT JOIN (SELECT amount, symbol FROM $tbl_ib_income as tn WHERE ib_id=$ib_id)$tbl_ib_income on $tbl_ib_income.symbol=$tbl_ib_commission.symbol GROUP BY symbol");
        $instrument = [];
        $amount_percent = [];
        $data = [
            'instruments' => [],
            'amount_percents' => []
        ];
        if ($all_instrument) {
            foreach ($all_instrument as $key => $value) {
                array_push($instrument, $value->symbol);
                $percent  = ($total_amount == 0) ? 0 : round((($value->sum * 100) / $total_amount), 2);;
                array_push($amount_percent, $percent);
            }
            $data = [
                'instruments' => $instrument,
                'amount_percents' => $amount_percent
            ];
        }
        return $data;
    }


    // get commission day chart
    private function get_commission_daychart($ib_id = null)
    {
        try {
            if ($ib_id = null) {
                $ib_id = auth()->user()->id;
            }
            // generate last 12 month
            $days = [];
            $day = time();
            for ($i = 1; $i <= 7; $i++) {
                $day = strtotime('last day', $day);
                $days[] = [
                    'days' => date("D", $day),
                    'value' => 0,
                ];
            }

            $commission_day_chart =  IbIncome::where('ib_id', $ib_id)->get()->sortBy(function ($item) {
                return -$item->created_at->day;
            })->groupBy(function ($item) {
                return $item->created_at->format("D");
            })->map->sum('amount');
            $count = 0;
            for ($i = 0; $i < count($days); $i++) {
                if (isset($commission_day_chart[$days[$i]['days']])) {
                    $count++;
                    $days[$i]['value'] = $commission_day_chart[$days[$i]['days']];
                }
            }
            $day_array = [];
            $value_array = [];
            for ($i = 0; $i < count($days); $i++) {
                $day_array[] = $days[$i]['days'];
                $value_array[] = $days[$i]['value'];
            }
            return ['days' => $day_array, 'value' => $value_array];
        } catch (\Throwable $th) {
            //throw $th;
            return [
                'days' => [],
                'value' => []
            ];
        }
    }
    // get trader under ib
    private function get_trader_under_ib($ib_id = null)
    {
        if ($ib_id == null) {
            $ib_id = auth()->user()->id;
        }
        $traders = IB::where('reference_id', $ib_id)->get();
        return $traders;
    }
    // get approximate lot 
    private function get_approximate_lot($ib_id = null)
    {
        if ($ib_id == null) {
            $ib_id = auth()->user()->id;
        }
        $total_lot = IbIncome::where('ib_id', $ib_id)
            ->whereNot('account_group', 10) // except cent account
            ->sum('volume');
        $appx_lot = round(($total_lot / 100), 2);
        return $appx_lot;
    }
    // get approximate lot 
    private function get_approximate_cent_lot($ib_id = null)
    {
        if ($ib_id == null) {
            $ib_id = auth()->user()->id;
        }
        $total_lot = IbIncome::where('ib_id', $ib_id)
            ->where('account_group', 10) // except cent account
            ->sum('volume');
        $appx_lot = round(($total_lot / 100), 2);
        return $appx_lot;
    }
    // check kyc status
    private function check_kyc_status($user_id = null)
    {
        if ($user_id == null) {
            $user_id = auth()->user()->id;
        }
        $kyc = KycVerification::where('user_id', $user_id)->select('status')->get();
        $status = 2;
        foreach ($kyc as $key => $value) {
            if ($value->status == 1) {
                $status = $value->status;
                break;
            } elseif ($value->status == 0) {
                $status = $value->status;
            }
        }
        return $status;
    }
    // generate IB chain from IB table
    private function get_ib_chain($ib_id = null, $request = null)
    {
        if ($ib_id == null) {
            $ib_id = auth()->user()->id;
        }
        $parent_ib = $this->get_level($ib_id, 'ib_chain');
        if ($parent_ib == null) {
            $parent_ib[] = [
                'parents' => $ib_id,
                'sub_ib' => $ib_id,
                'ib_level' => 1
            ];
        }
        $child_ib = $this->get_sub_ib($ib_id);
        $parent_child = array_merge($parent_ib, $child_ib);
        $ib_ids = [$ib_id];
        for ($i = 0; $i < count($parent_child); $i++) {
            $ib_ids[] = $parent_child[$i]['sub_ib'];
        }
        $all_connected_ib = User::whereIn('id', $ib_ids)->where('type', CombinedService::type());
        // check crm is combined
        if (CombinedService::is_combined()) {
            $all_connected_ib = $all_connected_ib->where('users.combine_access', 1);
        }
        $count = $all_connected_ib->count();
        if ($request != null) {
            $all_connected_ib = $all_connected_ib->skip($request->start)->take($request->length);
        }
        $all_connected_ib = $all_connected_ib->get();
        $ib_chain = [];
        foreach ($all_connected_ib as $key => $value) {
            if ($value->id == $ib_id) {
                $name = '<span class="badge bg-primary">' . $value->name . '</span>';
            } else {
                $name = $value->name;
            }
            // kyc status 
            $kyc_status = "";
            if ($value->kyc_status == 2) {
                $kyc_status = '<span class="badge badge-light-warning bg-light-warning">Pending</span>';
            } elseif ($value->kyc_status == 1) {
                $kyc_status = '<span class="badge badge-light-success bg-light-success">Verified</span>';
            } else {
                $kyc_status = '<span class="badge badge-light-danger bg-light-danger">Unverified</span>';
            }
            array_push($ib_chain, [
                'name' => $name,
                'email' => $value->email,
                'level' => $this->get_level($value->id),
                'commission_earned' => BalanceService::ib_balance($value->id),
                'commission_volume' => BalanceService::ib_commission_volume($value->id),
                'join_date' => date('Y M d', strtotime($value->created_at)),
                'kyc_status' => $kyc_status
            ]);
        }
        // return $ib_chain;
        return [
            'ib_chain' => $ib_chain,
            'count' => $count,
        ];
    }
    // get IB level for each IB
    private function get_level($ib_id = null, $op = null)
    {
        if ($ib_id == null) {
            $ib_id = auth()->user()->id;
        }
        $parents = IB::where('reference_id', $ib_id)->first();
        if (isset($parents->ib_id)) {
            $this->parent_ib[] = [
                'parents' => $parents->ib_id,
                'sub_ib' => $parents->ib_id,
                'ib_level' => 1
            ];
            if ($parents->parents != "") {
                $this->get_parent_nodes($parents->parents, 1);
            }
        }
        if (!empty($this->parent_ib)) {
            $level = $this->parent_ib[0]['ib_level'] + $this->parent_ib[count($this->parent_ib) - 1]['ib_level'];
        } else {
            $level = 1;
        }
        if ($op == null) {
            return $level;
        }
        $paren_ib = $this->parent_ib;
        $this->parent_ib = [];
        return $paren_ib;
    }
    // recursive call for find root node
    private function get_parent_nodes($data, $level)
    {
        $level++;
        $this->parent_ib[] = [
            'parents' => $data->ib_id,
            'sub_ib' => $data->ib_id,
            'ib_level' => $level
        ];
        if ($data->parents != "") {
            $this->get_parent_nodes($data->parents, $level);
        }
        return $this->parent_ib;
    }
    // get sub ib with level
    public function get_sub_ib_with_level($ib_id = null)
    {
        if ($ib_id == null) {
            $ib_id = auth()->user()->id;
        }
        $all_sub_ib = self::get_sub_ib($ib_id);
        $sub_ib_id = [];
        if (count($all_sub_ib) != 0) {
            for ($i = 0; $i < count($all_sub_ib); $i++) {
                array_push($sub_ib_id, $all_sub_ib[$i]['sub_ib']);
            }
        }
        if ($sub_ib_id != null) {
            $all_sub_ib_sql = User::whereIn('id', $sub_ib_id)->get();
        }
        $sub_ib_with_level = [];
        $j = 0;
        if (!empty($all_sub_ib_sql)) {
            foreach ($all_sub_ib_sql as $key => $value) {
                array_push($sub_ib_with_level, [
                    'name' => $value->name,
                    'email' => $value->email,
                    'ib_level' => $all_sub_ib[$j]['ib_level']
                ]);
                $j++;
            }
        }
        return $sub_ib_with_level;
    }
    // get sub IB
    private function get_sub_ib($ib_id = null)
    {
        if ($ib_id == null) {
            $ib_id = auth()->user()->id;
        }
        $ib_users = IB::where('ib.ib_id', $ib_id)->where('type', CombinedService::type())
            ->join('users', 'ib.reference_id', '=', 'users.id');
        // check crm is combine
        if (CombinedService::is_combined()) {
            $ib_users = $ib_users->where('users.combine_access', 1);
        }
        $ib_users = $ib_users->get();
        $i = 1;
        foreach ($ib_users as $value) :
            array_push($this->tree, [
                'ib_id' => $value->ib_id,
                'sub_ib' => $value->reference_id,
                'ib_level' => $i,

            ]);
            if (count($value->childs)) {
                $child =  $this->iterative_ib_child($value, $i);
            }
            $i++;
        endforeach;

        return $this->tree;
    }
    private function get_sub_ib_2($ib_id = null)
    {
        if ($ib_id == null) {
            $ib_id = auth()->user()->id;
        }
        $ib_users = IB::where('ib.ib_id', $ib_id)->where('type', CombinedService::type())
            ->join('users', 'ib.reference_id', '=', 'users.id');
        // check crm is combine
        if (CombinedService::is_combined()) {
            $ib_users = $ib_users->where('users.combine_access', 1);
        }
        $ib_users = $ib_users->get();
        $i = 1;
        foreach ($ib_users as $value) :
            array_push($this->tree, [
                'ib_id' => $value->ib_id,
                'sub_ib' => $value->reference_id,
                'ib_level' => $i,

            ]);
            if (count($value->childs)) {
                $child =  $this->iterative_ib_child($value, $i);
            }
            $i++;
        endforeach;
        $custom_array = [];
        $prev_tree = $this->tree;
        for ($i = 0; $i < count($prev_tree); $i++) {
            $custom_array[] = $prev_tree[$i]['sub_ib'];
        }
        return $prev_tree;
    }

    // itarative ib child
    private function iterative_ib_child($ib_users, $i)
    {
        $i++;
        $sub_ib_array = array();
        foreach ($ib_users->childs as $key => $value) {
            array_push($this->tree, [
                'ib_id' => $value->ib_id,
                'sub_ib' => $value->reference_id,
                'ib_level' => $i,

            ]);
            if (count($value->childs)) {
                $child = $this->iterative_ib_child($value, $i);
                array_push($sub_ib_array, array_merge($sub_ib_array, $child));
            }
        }
        return $sub_ib_array;
    }

    // get commission by instrument
    private function get_commission_by_instrument($ib_id)
    {
        if ($ib_id == null) {
            $ib_id = auth()->user()->id;
        }
        $total_commission = IbIncome::where('ib_id', $ib_id)
            ->groupBy('symbol')
            ->selectRaw('sum(amount) as sum, symbol')
            ->get('sum', 'symbol');
        return $total_commission;
    }
    // count my trader commission
    private function master_ib_trader_commission($ib_id = null)
    {
        if ($ib_id == null) {
            $ib_id = auth()->user()->id;
        }
        $total_commission = IbIncome::where('ib_id', $ib_id)
            ->sum('amount');
        return $total_commission;
    }
    // sum of sub ib trader commission
    private function sub_ib_trader_commission($ib_id = null)
    {
        if ($ib_id == null) {
            $ib_id = auth()->user()->id;
        }
        $sub_ib = array();
        $sub_ib = $this->get_sub_ib($ib_id);
        $sub_ib_ids = [];
        for ($i = 0; $i < count($sub_ib); $i++) {
            $sub_ib_ids[] = $sub_ib[$i]['sub_ib'];
        }
        $sub_ib_commission = IbIncome::whereIn('ib_id', $sub_ib_ids)->sum('amount');
        return $sub_ib_commission;
    }
    // get total sub IB under an IB
    private function get_total_sub_ib($ib_id = null)
    {
        if ($ib_id == null) {
            $ib_id = auth()->user()->id;
        }
        // $all_sub_ib = self::get_sub_ib($ib_id);
        // $all_sub_ib = count($all_sub_ib);
        // return $all_sub_ib;

        $user = User::with('ibReference', 'ibReference.referenceDetails')->find($ib_id);
        // return $user->ibReference;
        $result = $this->total_references($user->ibReference);
        return $result['ib'];
    }
    // get total trader under an ib
    private function get_total_trader($ib_id = null, $part = 'all')
    {
        if ($ib_id == null) {
            $ib_id = auth()->user()->id;
        }
        // $sub_ib_id = self::my_sub_ib_id($ib_id);
        // switch ($part) {
        //     case 'all':
        //         array_push($sub_ib_id, $ib_id);
        //         break;

        //     default:
        //         # code...
        //         break;
        // }
        // $total = IB::whereIn('ib_id', $sub_ib_id)
        //     ->where('users.type', 0)
        //     ->join('users', 'ib.reference_id', '=', 'users.id')->count('users.id');
        // return $total;
        $user = User::with('ibReference', 'ibReference.referenceDetails')->find($ib_id);
        // return $user->ibReference;
        $result = $this->total_references($user->ibReference);
        return $result['trader'];
    }
    function total_references($ibReferences)
    {
        try {
            $counts = [
                'ib' => 0,
                'trader' => 0,
            ];

            // Recursive function to flatten ib_reference arrays
            $flattenIbReferences = function ($references) use (&$counts, &$flattenIbReferences) {
                foreach ($references as $reference) {
                    if (isset($reference->referenceDetails->type)) {
                        $type = $reference->referenceDetails->type;
                        if ($type === 'ib') {
                            $counts['ib']++;
                        } elseif ($type === 'trader') {
                            $counts['trader']++;
                        }
                    }
                    if (!empty($reference['ibReference'])) {
                        $flattenIbReferences($reference['ibReference']);
                    }
                }
            };

            $flattenIbReferences($ibReferences);
            return $counts;
        } catch (\Throwable $th) {
            //throw $th;
            // return $th->getMessage();
            return [
                'ib' => 0,
                'trader' => 0,
            ];
        }
    }
    private function get_ib_com_12_month($user_id = null)
    {
        $z = date('m') - 12;
        $calendar = [];

        for ($z; $z < date('m') + 1; $z++) {

            if ($z !== 0) {
                $year = (int)date("Y");
                if ($z < 0) {
                    $month = 12 + ($z + 1);
                    $year = date("Y") - 1;
                } else {
                    $month = $z;
                }
                if ($user_id == null) {
                    $user_id = auth()->user()->id;
                }
                $totalIbCommision = IbIncome::where('ib_id', $user_id)->whereMonth('created_at', $month)->whereYear('created_at', '=', $year)->sum('amount');

                $std_array = (object) [
                    'Month'  => $month,
                    'amount'  => $totalIbCommision,
                    'Year'  => $year,
                ];

                array_push($calendar, $std_array);
            }
        }
        return $calendar;
    }
    // get last 12 month withdraw
    private function get_12_month_withdraw($user_id = null)
    {
        $z = date('m') - 12;
        $celender = [];

        for ($z; $z < date('m') + 1; $z++) {

            if ($z !== 0) {
                $year = (int)date("Y");
                if ($z < 0) {
                    $month = 12 + ($z + 1);
                    $year = date("Y") - 1;
                } else {
                    $month = $z;
                }

                $total_withdraw2 = Withdraw::where('user_id', $user_id)
                    ->whereMonth('created_at', $month)
                    ->whereYear('created_at', '=', $year)
                    ->where(function ($query) {
                        $query->where('approved_status', 'A')
                            ->orWhere('approved_status', 'P');
                    })->sum('amount');
                $wta_internal = InternalTransfer::where('user_id', $user_id)
                    ->where('type', 'wta')
                    ->whereYear('created_at', '=', $year)->whereMonth('created_at', $month)->where('status', 'A')->sum('amount');
                $external_fund_send = ExternalFundTransfers::where('sender_id', $user_id)
                    ->whereMonth('created_at', $month)
                    ->whereYear('created_at', '=', $year)
                    ->where(function ($query) {
                        $query->where('status', 'A')
                            ->orWhere('status', 'P');
                    })->sum('amount');


                $total = $total_withdraw2 + $wta_internal + $external_fund_send;

                $std_array = (object) [
                    'Month'  => $month,
                    'withdraw'  => $total,
                    'Year'  => $year,
                ];

                array_push($celender, $std_array);
            }
        }

        $z = date('m') - 12;
        $celender = [];

        for ($z; $z < date('m') + 1; $z++) {

            if ($z !== 0) {
                $year = (int)date("Y");
                if ($z < 0) {
                    $month = 12 + ($z + 1);
                    $year = date("Y") - 1;
                } else {
                    $month = $z;
                }

                $total_withdraw2 = Withdraw::where('user_id', $user_id)
                    ->whereMonth('created_at', $month)
                    ->whereYear('created_at', '=', $year)
                    ->where(function ($query) {
                        $query->where('approved_status', 'A')
                            ->orWhere('approved_status', 'P');
                    })->sum('amount');
                $wta_internal = InternalTransfer::where('user_id', $user_id)
                    ->where('type', 'wta')
                    ->whereYear('created_at', '=', $year)->whereMonth('created_at', $month)->where('status', 'A')->sum('amount');
                $external_fund_send = ExternalFundTransfers::where('sender_id', $user_id)
                    ->whereMonth('created_at', $month)
                    ->whereYear('created_at', '=', $year)
                    ->where(function ($query) {
                        $query->where('status', 'A')
                            ->orWhere('status', 'P');
                    })->sum('amount');

                $total = $total_withdraw2 + $wta_internal + $external_fund_send;

                $std_array = (object) [
                    'Month'  => $month,
                    'withdraw'  => $total,
                    'Year'  => $year,
                ];

                array_push($celender, $std_array);
            }
        }
        return $celender;
    }
    // generate referral link
    private function create_referral($user_id = null, $type)
    {
        if ($user_id == null) {
            $user_id = auth()->user()->id;
        }
        $user_id = base64_encode('{"rKey" : "' . $user_id . '"}');
        if ($type === 'ib') {
            $link = route('ib.registration') . "?refer=" . $user_id;
        }
        if ($type === 'trader') {
            $link = route('trader.registration') . "?refer=" . $user_id;
        }
        return $link;
    }
    public function get_self_balance($user_id)
    {
        try {
            $total_withdraw = Withdraw::where('user_id', $user_id)
                ->where(function ($query) {
                    $query->where('approved_status', 'A')
                        ->orWhere('approved_status', 'P');
                })->where('wallet_type', 'trader');
            $withdraw_charg = $total_withdraw->sum('charge');
            $total_withdraw = $total_withdraw->sum('amount'); // get data from withdraw table

            $total_deposit = Deposit::where('user_id', $user_id)
                ->where('wallet_type', 'trader')
                ->where('approved_status', 'A');
            $deposit_charge = $total_deposit->sum('charge');
            $total_deposit = $total_deposit->sum('amount'); //get data from deposit table

            $external_fund_send = ExternalFundTransfers::where('sender_id', $user_id)
                ->where('sender_wallet_type', 'trader')
                ->where(function ($query) {
                    $query->where('status', 'A')
                        ->orWhere('status', 'P');
                });
            $external_charge = $external_fund_send->sum('charge');
            $external_fund_send = $external_fund_send->sum('amount'); //get data from external fund table

            $external_fund_rec = ExternalFundTransfers::where('receiver_id', $user_id)
                ->where('status', 'A')
                ->where('receiver_wallet_type', 'trader');
            $ex_fund_rec_charge = $external_fund_rec->sum('charge');
            $external_fund_rec = $external_fund_rec->sum('amount'); // get data from external fund receive

            $atw_internal = InternalTransfer::where('user_id', $user_id)->where('type', 'atw')
                ->where(function ($query) {
                    $query->where('status', 'A')
                        ->orWhere('status', 'P');
                });
            $atw_internal_charge = $atw_internal->sum('charge');
            $atw_internal = $atw_internal->sum('amount'); // get data from account to wallet

            $wta_internal = InternalTransfer::where('user_id', $user_id)
                ->where('type', 'wta')->where('status', 'A');
            $wta_internal_charge = $wta_internal->sum('charge');
            $wta_internal = $wta_internal->sum('amount'); //get data from internal fund table


            $pamm_profit = PammProfitShare::where('pamm_id', $user_id)
                ->where('share_type', 'pamm');
            $pamm_profit_shares = (clone $pamm_profit)
                ->where('shared_amount', '>=', 0)
                ->sum('shared_amount');
            $pamm_loss_shares = (clone $pamm_profit)
                ->where('shared_amount', '<', 0)
                ->sum('shared_amount');

            $investor_profit = PammProfitShare::where('investor_id', $user_id)
                ->where('share_type', 'investor');
            $investor_profit_shares = (clone $investor_profit)
                ->where('shared_amount', '>=', 0)
                ->sum('shared_amount');
            $investor_loss_shares = (clone $investor_profit)
                ->where('shared_amount', '<', 0)
                ->sum('shared_amount');
            
            $balance = round(($total_deposit + $atw_internal + $external_fund_rec + $pamm_profit_shares + $investor_profit_shares), 2) - round(($total_withdraw + $wta_internal + $external_fund_send + $pamm_loss_shares + $investor_loss_shares), 2);
            $charge = ($deposit_charge + $withdraw_charg + $external_charge + $ex_fund_rec_charge + $atw_internal_charge + $wta_internal_charge);
            $balance = round(($balance - $charge), 2);
            return ($balance);
        } catch (\Throwable $th) {
            // throw $th;
            return (0);
        }
    }
    public function get_pending_balance($user_id)
    {
        try {
            $deposit_pending = Deposit::where('user_id', $user_id)->where('approved_status', 'P')->where('wallet_type', 'trader')->sum('amount');
            $external_pending = ExternalFundTransfers::where('sender_id', $user_id)->where('status', 'P')->sum('amount');
            $enternal_pending = InternalTransfer::where('user_id', $user_id)->where('type', 'atw')->where('status', 'P')->sum('amount');
            $total = ($deposit_pending + $external_pending + $enternal_pending);
            return round($total, 2);
        } catch (\Throwable $th) {
            //throw $th;
            return (0);
        }
    }
    public function get_total_deposit($user_id, $status = 'approved', $get = 'only-deposit')
    {
        try {
            switch ($status) {
                case 'pending':
                    $deposit = Deposit::where('user_id', $user_id)->where('approved_status', 'P')->where('wallet_type', 'trader')->sum('amount');
                    return round($deposit, 2);
                    break;

                default:
                    $deposit = Deposit::where('user_id', $user_id)->where('approved_status', 'A')->where('wallet_type', 'trader')->sum('amount');
                    // check get all or only deposit
                    if ($get === 'all') {
                        $external = ExternalFundTransfers::where('receiver_id', $user_id)->where('status', 'A')->sum('amount');
                        $atw_internal = InternalTransfer::where('user_id', $user_id)->where('type', 'atw')
                            ->where(function ($query) {
                                $query->where('status', 'A');
                            })->sum('amount');
                    } else {
                        $atw_internal = $external = 0;
                    }

                    $total = ($deposit + $external + $atw_internal);
                    return round($total, 2);
                    break;
            }
        } catch (\Throwable $th) {
            //throw $th;
            return 0;
        }
    }
    // get total withdraw
    public function get_total_withdraw($user_id, $status = 'approved', $get = 'only-withdraw')
    {
        switch ($status) {
                // get pending withdraw
            case 'pending':
                $withdraw = Withdraw::where('user_id', $user_id)->where('approved_status', 'P')->sum('amount');
                // check all or only withdraw
                if ($get === 'all') {
                    $internal_transfer = InternalTransfer::where('user_id', $user_id)->where('type', 'wta')->where('status', 'P')->sum('amount');
                    $external_fund_send = ExternalFundTransfers::where('sender_id', $user_id)
                        ->where(function ($query) {
                            $query->where('status', 'P');
                        })->sum('amount');
                } else {
                    $external_fund_send = $internal_transfer = 0;
                }
                $sql_bd_res = WalletUpDown::where('user_id', $user_id)->where('txn_type', 'deduct')
                    ->where(function ($query) {
                        $query->where('status', 'P');
                    })->sum('amount');
                $total = ($withdraw + $internal_transfer + $external_fund_send + $sql_bd_res);
                return round($total, 2);
                break;

            default:
                // get approved withdraw
                $withdraw = Withdraw::where('user_id', $user_id)->where('approved_status', 'A')->sum('amount');
                // check get all or withdraw only
                if ($get === 'all') {
                    $internal_transfer = InternalTransfer::where('user_id', $user_id)->where('type', 'wta')->where('status', 'A')->sum('amount');
                    $external_fund_send = ExternalFundTransfers::where('sender_id', $user_id)
                        ->where(function ($query) {
                            $query->where('status', 'A');
                        })->sum('amount');
                } else {
                    $external_fund_send = $internal_transfer = 0;
                }

                $total = ($withdraw + $internal_transfer + $external_fund_send);
                return round($total, 2);
                break;
        }
    }
    // get pending withdraw
    public function get_pending_withdraw($user_id)
    {
        $withdraw = Withdraw::where('user_id', $user_id)->where('approved_status', 'P')->sum('amount');
        $enternal_pending = InternalTransfer::where('user_id', $user_id)->where('type', 'wta')->where('status', 'P')->sum('amount');
        $external_pending = ExternalFundTransfers::where('sender_id', $user_id)->where('status', 'P')->sum('amount');
        $sql_bd_res = WalletUpDown::where('user_id', $user_id)->where('txn_type', 'deduct')
            ->where(function ($query) {
                $query->where('status', 'p');
            })->sum('amount');
        $total = ($withdraw + $enternal_pending + $external_pending + $sql_bd_res);
        return round($total, 2);
    }
    // get total volume
    public function get_total_volume($user_id, $date = null)
    {
        try {

            $platform = get_platform();
            switch (strtolower($platform)) {
                case 'mt4':
                    $trads = DB::connection('alternate')->table('MT4_TRADES')->where('user_id', $user_id)->select('VOLUME');
                    // date filter
                    if ($date != null) {
                        $from = Carbon::parse($date['from']);
                        $to = '';
                        if ($date['to'] != "") {
                            $to  = Carbon::parse($date['to']);
                        }
                        $trads = $trads->whereDate('MT4_TRADES.OPEN_TIME', '<=', $to)->whereDate('MT4_TRADES.OPEN_TIME', '>=', $from);
                    }
                    $trads = $trads->join($this->prefix . 'trading_accounts', 'MT4_TRADES.LOGIN', '=', $this->prefix . 'trading_accounts.account_number')
                        ->sum('VOLUME');
                    $trade_volume = 0;
                    if ($trads != 0) {
                        $trade_volume = round(($trads / 100), 2);
                    }
                    break;

                default:
                    $trads = Mt5Trade::where('user_id', $user_id)->select('VOLUME');
                    // date filter
                    if ($date != null) {
                        $from = Carbon::parse($date['from']);
                        $to = '';
                        if ($date['to'] != "") {
                            $to  = Carbon::parse($date['to']);
                        }
                        $trads = $trads->whereDate('mt5_trades.created_at', '<=', $to)->whereDate('mt5_trades.created_at', '>=', $from);
                    }
                    $trads = $trads->join('trading_accounts', 'mt5_trades.LOGIN', '=', 'trading_accounts.account_number')
                        ->where('trading_accounts.client_type', 'live')
                        ->sum('VOLUME');
                    $trade_volume = 0;
                    if ($trads != 0) {
                        $trade_volume = round(($trads / 100), 2);
                    }

                    break;
            }

            return $trade_volume;
        } catch (\Throwable $th) {
            //throw $th;
            return 0;
        }
    }
    // get todays volume
    public function get_today_volume($user_id)
    {
        $volume = Trade::where('user_id', $user_id)->whereDay('trades.created_at', date('d'))
            ->join('trading_accounts', 'trades.trading_account', '=', 'trading_accounts.id')
            ->sum('volume');
        return round($volume, 2);
    }
    // Admin side function
    public function all_trader()
    {
        if (auth()->user()->type == "manager") {
            $total_client = ManagerUser::where('manager_id', auth()->user()->id)
                ->join('users', 'manager_users.user_id', 'users.id')
                ->where('type', 0)
                // ->where('combine_access', 1)
                ->count();
            return $total_client;
        }
        $total_trader = User::select('id')->where('type', 0)->count('id');
        return $total_trader;
    }
    // // Admin side function
    // public function all_trader()
    // {
    //     $total_trader = User::select('id')->where('type', 0)->count('id');
    //     return $total_trader;
    // }
    // get total ib
    public function total_ib()
    {
        if (auth()->user()->type == "manager") {
            $total_ib = ManagerUser::where('manager_id', auth()->user()->id)
                ->join('users', 'manager_users.user_id', 'users.id')
                ->where('type', 0)
                ->where('combine_access', 1)
                ->count();
            return $total_ib;
        }
        $total_ib = User::select('id')->where('type', CombinedService::type());
        // check crm is combined
        if (CombinedService::is_combined()) {
            $total_ib = $total_ib->where('users.combine_access', 1);
        }
        $total_ib = $total_ib->count('id');
        return $total_ib;
    }
    // today deposit calculate
    public function today_deposit()
    {
        if (auth()->user()->type == "manager") {
            $clients = ManagerUser::where('manager_id', auth()->user()->id)
                ->join('users', 'manager_users.user_id', '=', 'users.id')
                ->where('type', 0)
                // ->where('combine_access', 1)
                ->select('users.id as user_id')
                ->get();
            
            $userIds = $clients->pluck('user_id');
            
            // $total_deposit = Deposit::where('approved_status', 'A')
            //     ->whereIn('user_id', $userIds)
            //     ->sum('amount');
            
            // return round($total_deposit, 2);
            $today = now();
            $today_deposit = Deposit::select('amount')->whereDate('created_at', $today)->where('approved_status', 'A')->whereIn('user_id', $userIds)->sum('amount');
            return round($today_deposit, 2);
        }
        $today = now();
        $today_deposit = Deposit::select('amount')->whereDate('created_at', $today)->where('approved_status', 'A')->sum('amount');
        return round($today_deposit, 2);
    }
    // total deposit calculation
    public function total_deposit()
    {
        if (auth()->user()->type == "manager") {
            $clients = ManagerUser::where('manager_id', auth()->user()->id)
                ->join('users', 'manager_users.user_id', '=', 'users.id')
                ->where('type', 0)
                // ->where('combine_access', 1)
                ->select('users.id as user_id')
                ->get();
            
            $userIds = $clients->pluck('user_id');
            
            $total_deposit = Deposit::where('approved_status', 'A')
                ->whereIn('user_id', $userIds)
                ->sum('amount');
            
            return round($total_deposit, 2);
        }
        $total_deposit = Deposit::select('amount')->where('approved_status', 'A')->sum('amount');
        return round($total_deposit, 2);
    }
    // pending deposit calculation
    public function pending_deposit()
    {
        $pending_deposit = Deposit::select('amount')->where('approved_status', 'P')->sum('amount');
        return round($pending_deposit, 2);
    }
    // total withdraw calculation
    public function total_withdraw()
    {
        if (auth()->user()->type == "manager") {
            $clients = ManagerUser::where('manager_id', auth()->user()->id)
                ->join('users', 'manager_users.user_id', '=', 'users.id')
                ->where('type', 0)
                // ->where('combine_access', 1)
                ->select('users.id as user_id')
                ->get();
            
            $userIds = $clients->pluck('user_id');
            
            $total_withdraw = Withdraw::select('amount')->where('approved_status', 'A')->whereIn('user_id', $userIds)->sum('amount');
            return round($total_withdraw, 2);
        }
        $total_withdraw = Withdraw::select('amount')->where('approved_status', 'A')->sum('amount');
        return round($total_withdraw, 2);
    }
    // pending withdraw calculation
    public function pending_withdraw()
    {
        if (auth()->user()->type == "manager") {
            $clients = ManagerUser::where('manager_id', auth()->user()->id)
                ->join('users', 'manager_users.user_id', '=', 'users.id')
                ->where('type', 0)
                // ->where('combine_access', 1)
                ->select('users.id as user_id')
                ->get();
            
            $userIds = $clients->pluck('user_id');
            
            $total_withdraw = Withdraw::select('amount')->where('approved_status', 'P')->whereIn('user_id', $userIds)->sum('amount');
            return round($total_withdraw, 2);
        }
        $pending_withdraw = Withdraw::select('amount')->where('approved_status', 'P')->sum('amount');
        $pending_withdraw  = (float) $pending_withdraw;
        return round($pending_withdraw, 2);
    }
    // bank deposit get
    public function bank_deposit()
    {
        if (auth()->user()->type == "manager") {
            $clients = ManagerUser::where('manager_id', auth()->user()->id)
                ->join('users', 'manager_users.user_id', '=', 'users.id')
                ->where('type', 0)
                // ->where('combine_access', 1)
                ->select('users.id as user_id')
                ->get();
            
            $userIds = $clients->pluck('user_id');
            $bank_deposit = Deposit::select('amount')->where('transaction_type', 'bank')->where('approved_status', 'A')->whereIn('user_id', $userIds)->sum('amount');
            return round($bank_deposit, 2);
        }
        $bank_deposit = Deposit::select('amount')->where('transaction_type', 'bank')->where('approved_status', 'A')->sum('amount');
        return round($bank_deposit, 2);
    }
    // get all bank withdraw
    public function bank_withdraw()
    {
        if (auth()->user()->type == "manager") {
            $clients = ManagerUser::where('manager_id', auth()->user()->id)
                ->join('users', 'manager_users.user_id', '=', 'users.id')
                ->where('type', 0)
                // ->where('combine_access', 1)
                ->select('users.id as user_id')
                ->get();
            
            $userIds = $clients->pluck('user_id');
            
            $bank_withdraw = Withdraw::select('amount')->where('transaction_type', 'bank')->where('approved_status', 'A')->whereIn('user_id', $userIds)->sum('amount');
            return round($bank_withdraw, 2);
        }
        $bank_withdraw = Withdraw::select('amount')->where('transaction_type', 'bank')->where('approved_status', 'A')->sum('amount');
        return round($bank_withdraw, 2);
    }
    // get all crypto deposit
    public function crypto_deposit()
    {
        if (auth()->user()->type == "manager") {
            $clients = ManagerUser::where('manager_id', auth()->user()->id)
                ->join('users', 'manager_users.user_id', '=', 'users.id')
                ->where('type', 0)
                // ->where('combine_access', 1)
                ->select('users.id as user_id')
                ->get();
            
            $userIds = $clients->pluck('user_id');
            
            $crypto_deposit = Deposit::select('amount')->where('transaction_type', 'crypto')->where('approved_status', 'A')->whereIn('user_id', $userIds)->sum('amount');
            return round($crypto_deposit, 3);
        }
        $crypto_deposit = Deposit::select('amount')->where('transaction_type', 'crypto')->where('approved_status', 'A')->sum('amount');
        return round($crypto_deposit, 3);
    }
    // get crypto withdraw
    public function crypto_withdraw()
    {
        if (auth()->user()->type == "manager") {
            $clients = ManagerUser::where('manager_id', auth()->user()->id)
                ->join('users', 'manager_users.user_id', '=', 'users.id')
                ->where('type', 0)
                // ->where('combine_access', 1)
                ->select('users.id as user_id')
                ->get();
            
            $userIds = $clients->pluck('user_id');
            
            $crypto_withdraw = Withdraw::select('amount')->where('transaction_type', 'crypto')->where('approved_status', 'A')->whereIn('user_id', $userIds)->sum('amount');
            return round($crypto_withdraw, 3);
        }
        $crypto_withdraw = Withdraw::select('amount')->where('transaction_type', 'crypto')->where('approved_status', 'A')->sum('amount');
        return round($crypto_withdraw, 3);
    }
    // get other deposit
    public function other_deposit()
    {
        if (auth()->user()->type == "manager") {
            $clients = ManagerUser::where('manager_id', auth()->user()->id)
                ->join('users', 'manager_users.user_id', '=', 'users.id')
                ->where('type', 0)
                // ->where('combine_access', 1)
                ->select('users.id as user_id')
                ->get();
            
            $userIds = $clients->pluck('user_id');
            
            $other_deposit = Deposit::select('amount')->where('transaction_type', '!=', 'crypto')
                ->where('transaction_type', '!=', 'bank')->where('approved_status', 'A')->whereIn('user_id', $userIds)->sum('amount');
            return round($other_deposit, 2);
        }
        $other_deposit = Deposit::select('amount')->where('transaction_type', '!=', 'crypto')
            ->where('transaction_type', '!=', 'bank')->where('approved_status', 'A')->sum('amount');
        return round($other_deposit, 2);
    }
    // get other withdraw
    public function other_withdraw()
    {
        if (auth()->user()->type == "manager") {
            $clients = ManagerUser::where('manager_id', auth()->user()->id)
                ->join('users', 'manager_users.user_id', '=', 'users.id')
                ->where('type', 0)
                // ->where('combine_access', 1)
                ->select('users.id as user_id')
                ->get();
            
            $userIds = $clients->pluck('user_id');
            
            $other_withdraw = Withdraw::select('amount')->where('transaction_type', '!=', 'crypto')
                ->where('transaction_type', '!=', 'bank')->where('approved_status', 'A')->whereIn('user_id', $userIds)->sum('amount');
            return round($other_withdraw, 2);
        }
        $other_withdraw = Withdraw::select('amount')->where('transaction_type', '!=', 'crypto')
            ->where('transaction_type', '!=', 'bank')->where('approved_status', 'A')->sum('amount');
        return round($other_withdraw, 2);
    }

    // get device logo
    private function get_login_browser($browser_name)
    {
        switch (strtolower($browser_name)) {
            case 'mozilla firefox':
                $browser_logo = 'mozila-firefox.png';
                break;
            case 'google chrome':
                $browser_logo = 'google-chrome.png';
                break;
            case 'apple safari':
                $browser_logo = 'apple-safari.png';
                break;
            case 'internet explorer':
                $browser_logo = 'internet-explorer.png';
                break;
            case 'opera':
                $browser_logo = 'opera.png';
                break;
            default:
                $browser_logo = 'google-chrome.png';
                break;
        }
        return $browser_logo;
    }
    // get login devices
    private function get_login_device($login_device)
    {
        switch (strtolower($login_device)) {
            case 'windows':
                $device = 'fa-desktop';
                break;
            case 'android':
                $device = 'fa-mobile';
                break;
            default:
                $device = 'fa-desktop';
                break;
        }
        return $login_device;
    }
    // check kyc verification status
    private function kyc_verified()
    {
        $kyc = KycVerification::select('id');
        $kyc_verified = $kyc->where('status', '=', 1)->count('id');
        $kyc_unverified = $kyc->where('status', '=', 0)->count('id');
        $all_user = $kyc->get();
        $kyc_user_id = [];
        foreach ($all_user as $key => $value) {
            $kyc_user_id[] = $value->user_id;
        }
        $unverified_user = User::whereNotIn('id', $kyc_user_id)->where(function ($query) {
            $query->where('type', 0)
                ->orWhere('type', 4);
        })->count();
        $verified_percent = (100 * ($kyc_verified / ($kyc_unverified + $unverified_user + $kyc_verified)));
        $data = [
            'verified' => $kyc_verified,
            'unverified' => ($kyc_unverified + $unverified_user),
            'percent' => round($verified_percent, 2)
        ];
        return $data;
    }
    // revinue 12 month
    private function revenue_report()
    {
        $months = $this->generateMonthsArray();

        $deposit = $this->calculateTotalByMonth(Deposit::all());
        $withdraw = $this->calculateTotalByMonth(Withdraw::all());

        $count = 0;
        foreach ($months as &$monthData) {
            if (isset($deposit[$monthData['months']])) {
                $count++;
                $monthData['deposit'] = $deposit[$monthData['months']];
            }
            if (isset($withdraw[$monthData['months']])) {
                $count++;
                $monthData['withdraw'] = $withdraw[$monthData['months']];
            }
        }

        $day_array = array_column($months, 'months');
        $deposit_array = array_column($months, 'deposit');
        $withdraw_array = array_map(function ($withdraw) {
            return -$withdraw;
        }, array_column($months, 'withdraw'));

        return [
            'months' => $day_array,
            'deposit' => $deposit_array,
            'withdraw' => $withdraw_array
        ];
    }

    private function generateMonthsArray()
    {
        $currentMonth = date('m');
        $months = [];

        for ($z = 1; $z <= $currentMonth; $z++) {
            $month = ($z - 1) % 12 + 1;
            $months[] = [
                'months' => date('M', strtotime('2022-' . $month . '-05')),
                'deposit' => 0,
                'withdraw' => 0,
            ];
        }

        return $months;
    }

    private function calculateTotalByMonth($transactions)
    {
        return $transactions->sortByDesc(function ($item) {
            return $item->created_at->day;
        })->groupBy(function ($item) {
            return $item->created_at->format("M");
        })->map->sum('amount');
    }

    // last 7 pending deposit
    // for admin dashboard chart
    private function last_pending_deposit()
    {
        $pending_deposit = Deposit::where('approved_status', 'P')->limit(7)->get();
        $pending_array = [0, 0, 0, 0, 0, 0, 0];
        $i = 0;
        foreach ($pending_deposit as $key => $value) {
            $pending_array[$i] = $value->amount;
            $i++;
        }
        return $pending_array;
    }
    // last pending withdraw
    private function last_pending_withdraw()
    {
        $pending_withdraw = Withdraw::where('approved_status', 'P')->limit(7)->get();
        $pending_array = [0, 0, 0, 0, 0, 0, 0];
        $i = 0;
        foreach ($pending_withdraw as $key => $value) {
            $pending_array[$i] = $value->amount;
            $i++;
        }
        return $pending_array;
    }
    // per month deposit withdraw chart
    private function per_month_line_chart($month)
    {
        // generate last 12 month
        $days = [];
        $day = $this->get_date_given_month($month);
        $d = date('d');
        for ($i = 1; $i <= $d; $i++) {

            $days[] = [
                'days' => $day[($i - 1)],
                'deposit' => 0,
                'withdraw' => 0,
            ];
        }

        $deposit =  Deposit::whereMonth('created_at', $month)->get()->sortBy(function ($item) {
            return -$item->created_at->day;
        })->groupBy(function ($item) {
            return $item->created_at->format("d");
        })->map->sum('amount');
        // return $deposit[01];
        for ($i = 0; $i < count($days); $i++) {
            if (isset($deposit[$days[$i]['days']])) {
                $days[$i]['deposit'] = $deposit[$days[$i]['days']];
            }
        }

        // withdraw
        $withdraw =  Withdraw::whereMonth('created_at', $month)->get()->sortBy(function ($item) {
            return -$item->created_at->day;
        })->groupBy(function ($item) {
            return $item->created_at->format("d");
        })->map->sum('amount');

        for ($i = 0; $i < count($days); $i++) {
            if (isset($withdraw[$days[$i]['days']])) {
                $days[$i]['withdraw'] = $withdraw[$days[$i]['days']];
            }
        }
        $day_array = [];
        $deposit_array = [];
        $withdraw_array = [];
        for ($i = 0; $i < count($days); $i++) {
            $day_array[] = $days[$i]['days'];
            $deposit_array[] = $days[$i]['deposit'];
            $withdraw_array[] = $days[$i]['withdraw'];
        }
        return [
            'days' => $day_array,
            'deposit' => $deposit_array,
            'withdraw' => $withdraw_array
        ];
    }
    // function get all date in a month

    private function get_date_given_month($month)
    {
        $month = $month;
        $year = date('Y');

        $start_date = "01-" . $month . "-" . $year;
        $start_time = strtotime($start_date);

        $end_time = strtotime("+1 month", $start_time);

        for ($i = $start_time; $i < $end_time; $i += 86400) {
            $list[] = date('d', $i);
        }

        return $list;
    }
    private function commission_chart_data()
    {
        if (auth()->user()->type == "manager") {
            $clients = ManagerUser::where('manager_id', auth()->user()->id)
                ->join('users', 'manager_users.user_id', '=', 'users.id')
                ->where('type', 0)
                // ->where('combine_access', 1)
                ->select('users.id as user_id')
                ->get();
            
            $userIds = $clients->pluck('user_id');
            
            // this month data
            $total_deposit = Deposit::whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))->whereIn('user_id', $userIds)->sum('amount');
            $total_withdraw = Withdraw::whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))->whereIn('user_id', $userIds)->sum('amount');
            $total_commission = IbIncome::whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))->whereIn('ib_id', $userIds)->sum('amount');
            // previous month data
            $previous_commission = IbIncome::whereMonth('close_time', (date('m') - 1))->whereYear('created_at', date('Y'))->whereIn('ib_id', $userIds)->sum('amount');
            // percent than previous month
            if ($previous_commission <= 0) {
                $percent = $total_commission;
            } else {
                $percent = round(((100 * $total_commission) / $previous_commission), 2);
            }
    
            // return (date('m')-1);
            return ([
                'chart_data' => json_encode([
                    round($total_deposit, 2),
                    round($total_withdraw, 2),
                    round($total_commission, 2),
                ]),
                'percent' => $percent
            ]);
        }
        // this month data
        $total_deposit = Deposit::whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))->sum('amount');
        $total_withdraw = Withdraw::whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))->sum('amount');
        $total_commission = IbIncome::whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))
            ->sum('amount');
        // previous month data
        $previous_commission = IbIncome::whereMonth('close_time', (date('m') - 1))->whereYear('created_at', date('Y'))
            ->sum('amount');
        // percent than previous month
        if ($previous_commission <= 0) {
            $percent = $total_commission;
        } else {
            $percent = round(((100 * $total_commission) / $previous_commission), 2);
        }

        // return (date('m')-1);
        return ([
            'chart_data' => json_encode([
                round($total_deposit, 2),
                round($total_withdraw, 2),
                round($total_commission, 2),
            ]),
            'percent' => $percent
        ]);
    }
    // get 12 month of the year
    private function months()
    {
        $current_month = date('m');
        $month = [];
        for ($i = 0; $i < (int)$current_month; $i++) {
            $month['name'][$i] = date('F', mktime(0, 0, 0, ($i + 1), 10));
            $month['month'][$i] = date('m', mktime(0, 0, 0, ($i + 1), 10));
        }
        return $month;
    }
    // month wise total deposit
    private function total_deposit_per_month($month)
    {
        \Log::info('total_deposit_per_month called with month: ' . $month . ' (type: ' . gettype($month) . ')');
        // Ensure month is an integer
        $month = (int) $month;
        
        if (auth()->user()->type == "manager") {
            $clients = ManagerUser::where('manager_id', auth()->user()->id)
                ->join('users', 'manager_users.user_id', '=', 'users.id')
                ->where('type', 0)
                // ->where('combine_access', 1)
                ->select('users.id as user_id')
                ->get();
            
            $userIds = $clients->pluck('user_id');
            $deposit = Deposit::whereMonth('created_at', $month)->where('approved_status', 'A')->whereIn('user_id', $userIds)->sum('amount');
            \Log::info('Manager deposit for month ' . $month . ': ' . $deposit);
            return round($deposit, 2);
        }
        $deposit = Deposit::whereMonth('created_at', $month)->where('approved_status', 'A')->sum('amount');
        \Log::info('Admin deposit for month ' . $month . ': ' . $deposit);
        
        // Debug: Check total deposits and approved deposits
        $totalDeposits = Deposit::whereMonth('created_at', $month)->count();
        $approvedDeposits = Deposit::whereMonth('created_at', $month)->where('approved_status', 'A')->count();
        \Log::info('Total deposits in month ' . $month . ': ' . $totalDeposits . ', Approved: ' . $approvedDeposits);
        
        return round($deposit, 2);
    }
    // month wise total withdraw
    private function total_withdraw_per_month($month)
    {
        \Log::info('total_withdraw_per_month called with month: ' . $month . ' (type: ' . gettype($month) . ')');
        // Ensure month is an integer
        $month = (int) $month;
        
        if (auth()->user()->type == "manager") {
            $clients = ManagerUser::where('manager_id', auth()->user()->id)
                ->join('users', 'manager_users.user_id', '=', 'users.id')
                ->where('type', 0)
                // ->where('combine_access', 1)
                ->select('users.id as user_id')
                ->get();
            
            $userIds = $clients->pluck('user_id');
            $withdraw = Withdraw::whereMonth('created_at', $month)->where('approved_status', 'A')->whereIn('user_id', $userIds)->sum('amount');
            \Log::info('Manager withdraw for month ' . $month . ': ' . $withdraw);
            return round($withdraw, 2);
        }
        $withdraw = Withdraw::whereMonth('created_at', $month)->where('approved_status', 'A')->sum('amount');
        \Log::info('Admin withdraw for month ' . $month . ': ' . $withdraw);
        return round($withdraw, 2);
    }
    private function login_device_icon($device)
    {
        $icon = asset('comon-icon/device-icon/');
        switch (strtolower($device)) {
            case 'windows':
                $icon .= '/windows.png';
                break;
            case 'mac':
                $icon .= '/mac.png';
                break;
            case 'linux':
                $icon .= '/linux.png';
                break;
            default:
                $icon .= '/android.png';
                break;
        }
        return $icon;
    }
    private function get_user_email($user_id)
    {
        try {
            $email = User::where('id', $user_id)->select('email')->first();
            if (isset($email->email) && $email->email != "") {
                $email = $email->email;
            } else {
                $email = '';
            }
            return $email;
        } catch (\Throwable $th) {
            //throw $th;
            return '';
        }
    }
    private function get_user_name($user_id)
    {
        $name = User::where('id', $user_id)->select('name')->first();
        if ($name) {
            if ($name->name != "") {
                $name = $name->name;
            } else {
                $name = '';
            }
        } else {
            $name = '';
        }
        return $name;
    }
    // get user profile
    public function get_user_profile($user_id)
    {
        $user = UserDescription::where('user_id', $user_id)->first();
    
        if ($user && $user->profile_avater && file_exists(public_path('Uploads/profile/' . $user->profile_avater))) {
            return asset('Uploads/profile/' . $user->profile_avater);
        }
    
        // fallback avatar based on gender
        if ($user && isset($user->gender) && strtolower($user->gender) === 'female') {
            return asset('admin-assets/app-assets/images/avatars/avater-lady.png');
        }
    
        return asset('admin-assets/app-assets/images/avatars/avater-men.png');
    }


    // get user type
    private function get_user_type($user_id)
    {
        $users = User::where('id', $user_id)->select('type', 'combine_access')->first();
        if (isset($users->type) && $users->type != "") {
            $type = $users->type;
            if ($type == 0) {
                // check crm type is combine
                if (CombinedService::is_combined()) {
                    if ($users->combine_access) {
                        return ('IB');
                    } else {
                        return ('Trader');
                    }
                }
                $type = 'Trader';
            } elseif ($type == 4) {
                $type = 'IB';
            }
        } else {
            $type = 'N/A';
        }
        return $type;
    }

    // get internal transaction type
    private function internal_transaction_type($transaction_type)
    {
        if (strtolower($transaction_type) === 'wta') {
            $transaction_type = 'Wallet to Account';
        } else {
            $transaction_type = 'Account to Wallet';
        }
        return $transaction_type;
    }

    // get all server/ platform from system config
    private function get_all_platform()
    {
        $platform_options = '';
        $platform = SystemConfig::select('platform_type')->first();
        if ($platform) {
            if (strtolower($platform->platform_type) === 'mt5') {
                $platform_options .= '<option value="' . $platform->platform_type . '">' . strtoupper($platform->platform_type) . '</option>';
            }
            if (strtolower($platform->platform_type) === 'mt4') {
                $platform_options .= '<option value="' . $platform->platform_type . '">' . strtoupper($platform->platform_type) . '</option>';
            }
            if (strtolower($platform->platform_type) === 'vertex') {
                $platform_options .= '<option value="' . $platform->platform_type . '">' . strtoupper($platform->platform_type) . '</option>';
            }
            if (strtolower($platform->platform_type) === 'both') {
                $platform_options .= '<option value="mt4">MT4</option>';
                $platform_options .= '<option value="mt5">MT5</option>';
            }
        } else {
            $platform_options .= '<option value="mt5">MT5</option>';
        }

        return $platform_options;
    }
    // get all removed trading account
    private function get_all_removed_trading_account()
    {
        $removed_accounts = '';
        $trading_accounts = TradingAccount::select('account_number')->where('account_status', 0)->get();
        if ($trading_accounts) {
            foreach ($trading_accounts as $account) {
                $removed_accounts .= '<option value="' . $account->account_number . '">' . $account->account_number . '</option>';
            }
        }

        return $removed_accounts;
    }

    // check trading account auto or not
    public function account_create_auto()
    {
        $system_config = SystemConfig::select('create_meta_acc')->first();
        if ($system_config) {
            $create_meta_acc = $system_config->create_meta_acc;
        } else {
            $create_meta_acc = 0;
        }
        return $create_meta_acc;
    }
    // check social link required or not
    public function social_link_reqired_reg()
    {
        $system_config = SystemConfig::select('social_account')->first();
        if ($system_config) {
            $social_account = $system_config->social_account;
        } else {
            $social_account = 0;
        }
        return $social_account;
    }
    // get ib groups
    private function get_ib_group($group_id)
    {

        $ib_groups = IbGroup::find($group_id);
        return ($ib_groups->group_name);
    }
    // function for get access of client

    private function access_permission($access_module, $type)

    {

        $this->permission = 'client_controll:notaccess';

        switch ($type) {

            case 'trader':
                switch ($access_module) {
                    case 'deposit':
                        // deposit 3
                        $this->module = TraderSetting::where('settings', ucwords('deposit'));
                        return $this->check_module($this->module);
                        break;

                    case 'withdraw':
                        // withdraw 4
                        $this->module = TraderSetting::where('settings', ucwords('withdraw'));
                        return $this->check_module($this->module);
                        break;
                    case 'transfer':
                        // transfer 5
                        $this->module = TraderSetting::where('settings', ucwords('transfer'));
                        return $this->check_module($this->module);
                        break;

                    case 'leverage_change':
                        // leverag
                        $this->module = TraderSetting::where('settings', ucwords('Trading Account Leverage Change'));
                        return $this->check_module($this->module);
                        break;

                    case 'internal_transfer':
                        // internal balance transfer
                        $this->module = TraderSetting::where('settings', ucwords('Internal Balance Transfer'));
                        return $this->check_module($this->module);
                        break;

                    case 'external_transfer':
                        // external transfer
                        $this->module = TraderSetting::where('settings', ucwords('External Balance Transfer'));
                        return $this->check_module($this->module);
                        break;

                    case 'support_ticket':
                        // support ticket
                        // parent 
                        $this->module = TraderSetting::where('settings', ucwords('Support Ticket'));
                        return $this->check_module($this->module);
                        break;

                    case 'verification_system':
                        // verification system
                        $this->module = TraderSetting::where('settings', ucwords('Verification System'));
                        return $this->check_module($this->module);
                        break;

                    case 'my_admin':
                        // myadmin /1
                        $this->module = TraderSetting::where('settings', ucwords('my admin'));
                        return $this->check_module($this->module);
                        break;

                    case 'contest_feature':
                        // contest feature
                        $this->module = TraderSetting::where('settings', ucwords('Contest Feature'));
                        return $this->check_module($this->module);
                        break;

                    case 'bonus_feature':
                        // bonus feature
                        $this->module = TraderSetting::where('settings', ucwords('Bonus Feature'));
                        return $this->check_module($this->module);
                        break;

                    case 'trading_tools':
                        // daily market analysis 
                        $this->module = TraderSetting::where('settings', ucwords('trading tools'));
                        return $this->check_module($this->module);
                        break;
                    case 'market_analysis':
                        // daily market analysis 
                        $this->module = TraderSetting::where('settings', ucwords('daily market analysis'));
                        return $this->check_module($this->module);
                        break;

                    case 'forex_signals':
                        // forex signals /trader
                        $this->module = TraderSetting::where('settings', ucwords('forex signals'));
                        return $this->check_module($this->module);
                        break;
                    case 'forex_education':
                        // forex education / trader
                        $this->module = TraderSetting::where('settings', ucwords('forex education'));
                        return $this->check_module($this->module);
                        break;

                    case 'economic_calendar':
                        // economic calender/ trader
                        $this->module = TraderSetting::where('settings', ucwords('echonomic calendar'));
                        return $this->check_module($this->module);
                        break;

                    case 'forex_calculators':
                        // forex calculators / trader
                        $this->module = TraderSetting::where('settings', ucwords('forex calculators'));
                        return $this->check_module($this->module);

                    case 'profile_overview':
                        // profile overview/ trader
                        $this->module = TraderSetting::where('settings', ucwords('Profile Overview'));
                        return $this->check_module($this->module);
                        break;

                    case 'settings':
                        // trader 
                        // parent /myadmin->1
                        $this->module = TraderSetting::where('settings', ucwords('settings'));
                        return $this->check_module($this->module);
                        break;

                    case 'verification':
                        // trader
                        // parent /myadmin->1
                        $this->module = TraderSetting::where('settings', ucwords('verification'));
                        return $this->check_module($this->module);
                        break;

                    case 'banking':
                        // trader
                        // parent /myadmin->1
                        $this->module = TraderSetting::where('settings', ucwords('banking'));
                        return $this->check_module($this->module);
                        break;

                    case 'bank_deposit':
                        // trader
                        // parent /deposit->3
                        $this->module = TraderSetting::where('settings', ucwords('bank deposit'));
                        return $this->check_module($this->module);
                        break;
                    case 'perfect_money_deposit':
                        // trader
                        // parent /deposit->3
                        $this->module = TraderSetting::where('settings', ucwords('perfect money deposit'));
                        return $this->check_module($this->module);
                        break;
                    case 'help2pay_deposit':
                        // trader
                        // parent /deposit->3
                        $this->module = TraderSetting::where('settings', ucwords('help2pay deposit'));
                        return $this->check_module($this->module);
                        break;
                    case 'match2pay_deposit':
                        // trader
                        // parent /deposit->3
                        $this->module = TraderSetting::where('settings', ucwords('match2pay deposit'));
                        return $this->check_module($this->module);
                        break;
                    case 'paypal_deposit':
                        // trader
                        // parent /deposit->3
                        $this->module = TraderSetting::where('settings', ucwords('paypal deposit'));
                        return $this->check_module($this->module);
                        break;
                    case 'b2binpay_deposit':
                        // trader
                        // parent /deposit->3
                        $this->module = TraderSetting::where('settings', ucwords('b2binpay deposit'));
                        return $this->check_module($this->module);
                        break;

                    case 'bank_withdraw':
                        // trader
                        // parent /withdraw->4
                        $this->module = TraderSetting::where('settings', ucwords('bank withdraw'));
                        return $this->check_module($this->module);
                        break;
                    case 'paypal_withdraw':
                        // trader
                        // parent /withdraw->4
                        $this->module = TraderSetting::where('settings', ucwords('paypal withdraw'));
                        return $this->check_module($this->module);
                        break;
                    case 'gcash_withdraw':
                        // trader
                        // parent /withdraw->4
                        $this->module = TraderSetting::where('settings', ucwords('gcash withdraw'));
                        return $this->check_module($this->module);
                        break;

                    case 'copy_trade_dashboard':
                        // trader
                        // not implemented
                        $this->module = TraderSetting::where('settings', ucwords('Copy Trade Dashboard'));
                        return $this->check_module($this->module);
                        break;

                    case 'copy_trade_overview':
                        // trader
                        // not implemented
                        $this->module = TraderSetting::where('settings', ucwords('Copy Trade Overview'));
                        return $this->check_module($this->module);
                        break;

                    case 'copy_trader_report':
                        // trader
                        // not implemented
                        $this->module = TraderSetting::where('settings', ucwords('Copy Trader report'));
                        return $this->check_module($this->module);
                        break;

                    case 'crypto_deposit':
                        // trader 
                        // parent /deposit->3
                        $this->module = TraderSetting::where('settings', ucwords('crypto deposit'));
                        return $this->check_module($this->module);
                        break;

                    case 'crypto_withdraw':
                        // trader 
                        // parent /withdraw->4
                        $this->module = TraderSetting::where('settings', ucwords('crypto withdraw'));
                        return $this->check_module($this->module);
                        break;
                    case 'skrill_withdraw':
                        // trader
                        // parent /withdraw->4
                        $this->module = TraderSetting::where('settings', ucwords('skrill withdraw'));
                        return $this->check_module($this->module);
                        break;
                    case 'neteller_withdraw':
                        // trader
                        // parent /withdraw->4
                        $this->module = TraderSetting::where('settings', ucwords('neteller withdraw'));
                        return $this->check_module($this->module);
                        break;

                    case 'external_report':
                        // trader
                        // parent /reports->6
                        $this->module = TraderSetting::where('settings', ucwords('exteranl transfer report'));
                        return $this->check_module($this->module);
                        break;
                        // trading accounts menue

                    case 'trading_accounts':
                        // trader /2
                        $this->module = TraderSetting::where('settings', ucwords('trading accounts'));
                        return $this->check_module($this->module);
                        break;

                    case 'open_demo_account':
                        // trader 
                        // parent /trading-account->2
                        $this->module = TraderSetting::where('settings', ucwords('open demo account'));
                        return $this->check_module($this->module);
                        break;

                    case 'open_live_account':
                        // trader
                        // parent /trading-account->2
                        $this->module = TraderSetting::where('settings', ucwords('open live account'));
                        return $this->check_module($this->module);
                        break;

                    case 'trading_account_settings':
                        // trader 
                        // parent trading account
                        $this->module = TraderSetting::where('settings', ucwords('trading account settings'));

                        return $this->check_module($this->module);

                        break;
                    case 'trader_to_ib':
                        // trader
                        // transfer/ parent
                        $this->module = TraderSetting::where('settings', ucwords('trader to IB'));

                        return $this->check_module($this->module);

                        break;
                    case 'trader_to_trader':
                        // trader
                        // parent / transfer
                        $this->module = TraderSetting::where('settings', ucwords('trader to trader'));

                        return $this->check_module($this->module);

                        break;
                    case 'account_to_wallet':
                        // trader /
                        // parent / transfer
                        $this->module = TraderSetting::where('settings', ucwords('account to wallet'));

                        return $this->check_module($this->module);

                        break;
                    case 'wallet_to_account':

                        $this->module = TraderSetting::where('settings', ucwords('wallet to account'));

                        return $this->check_module($this->module);

                        break;
                    case 'reports':

                        $this->module = TraderSetting::where('settings', ucwords('reports'));

                        return $this->check_module($this->module);

                        break;
                    case 'deposit_report':

                        $this->module = TraderSetting::where('settings', ucwords('deposit report'));

                        return $this->check_module($this->module);

                        break;
                    case 'withdraw_reports':
                        $this->module = TraderSetting::where('settings', ucwords('withdraw reports'));
                        return $this->check_module($this->module);
                        break;
                    case 'external_transfer_report':
                        $this->module = TraderSetting::where('settings', ucwords('external transfer report'));
                        return $this->check_module($this->module);
                        break;
                    case 'internal_transfer_report':
                        // internal transfer report
                        $this->module = TraderSetting::where('settings', ucwords('internal transfer report'));
                        return $this->check_module($this->module);
                        break;
                    case 'ib_transfer_report':
                        // ib transfer report
                        $this->module = TraderSetting::where('settings', ucwords('IB transfer report'));
                        return $this->check_module($this->module);
                        break;
                    case 'trading_report':
                        // trading report
                        $this->module = TraderSetting::where('settings', ucwords('trading report'));
                        return $this->check_module($this->module);
                        break;
                    case 'support':
                        // support trader
                        $this->module = TraderSetting::where('settings', ucwords('support'));
                        return $this->check_module($this->module);
                        break;
                    case 'support_ticket':
                        // support ticket trader
                        $this->module = TraderSetting::where('settings', ucwords('support_ticket'));
                        return $this->check_module($this->module);
                        break;
                    case 'social_activies_report':

                        $this->module = TraderSetting::where('settings', ucwords('social activities report'));

                        return $this->check_module($this->module);

                        break;
                    case 'social_traders_report':
                        $this->module = TraderSetting::where('settings', ucwords('social traders report'));
                        return $this->check_module($this->module);
                        break;
                    case 'social_activities_report':
                        $this->module = TraderSetting::where('settings', ucwords('social activities report'));
                        return $this->check_module($this->module);
                        break;
                    case 'copy_trading':

                        $this->module = TraderSetting::where('settings', ucwords('copy trading'));

                        return $this->check_module($this->module);

                        break;
                    case 'manage_slave_account':

                        $this->module = TraderSetting::where('settings', ucwords('manage slave account'));

                        return $this->check_module($this->module);

                        break;
                    case 'mamm':

                        $this->module = TraderSetting::where('settings', ucwords('MAMM'));

                        return $this->check_module($this->module);

                        break;
                    case 'pamm':

                        $this->module = TraderSetting::where('settings', ucwords('PAMM'));

                        return $this->check_module($this->module);

                        break;
                    case 'pamm_registration':

                        $this->module = TraderSetting::where('settings', ucwords('PAMM registration'));

                        return $this->check_module($this->module);

                        break;
                    case 'pamm_profile':
                        $this->module = TraderSetting::where('settings', ucwords('PAMM profile'));
                        return $this->check_module($this->module);

                        break;
                    case 'contest':
                        // trader 
                        // parent contest
                        $this->module = TraderSetting::where('settings', ucwords('contest'));
                        return $this->check_module($this->module);

                        break;
                    case 'participate_contest':
                        // trader 
                        // parent contest
                        $this->module = TraderSetting::where('settings', ucwords('participate contest'));
                        return $this->check_module($this->module);

                        break;
                    case 'contest_list':
                        // trader 
                        // parent contest
                        $this->module = TraderSetting::where('settings', ucwords('contest list'));
                        return $this->check_module($this->module);

                        break;
                    case 'contest_status':
                        // trader 
                        // parent contest
                        $this->module = TraderSetting::where('settings', ucwords('contest status'));
                        return $this->check_module($this->module);
                        break;
                    case 'become_a_partner':
                        // trader 
                        // parent self / become a partner
                        $this->module = TraderSetting::where('settings', ucwords('become a partner'));
                        return $this->check_module($this->module);
                        break;
                }
                break;
                // admin module
            case 'admin':

                switch ($access_module) {
                        // admin profile parent
                    case 'admin_profile':

                        $this->module = SystemModule::where('module', ucwords('admin profile'));

                        return $this->check_module($this->module);

                        break;
                        // change profile parent 1
                    case 'change_profile':

                        $this->module = SystemModule::where('module', ucwords('change profile'));

                        return $this->check_module($this->module);

                        break;
                        // admin notification parent 1
                    case 'notification':

                        $this->module = SystemModule::where('module', ucwords('notifications'));

                        return $this->check_module($this->module);

                        break;
                        // social trade/parent 19
                    case 'social_trade':
                        $this->module = SystemModule::where('module', ucwords('social trade'));
                        return $this->check_module($this->module);
                        break;
                        // parent 19->social dashbaord
                    case 'social_dashboard':
                        $this->module = SystemModule::where('module', ucwords('social dashboard'));
                        return $this->check_module($this->module);
                        break;
                        // parent 19->pamm settings
                    case 'pamm_settings':
                        $this->module = SystemModule::where('module', ucwords('pamm settings'));
                        return $this->check_module($this->module);
                        break;
                        // parent 19->social pamm manager
                    case 'pamm_manager':
                        $this->module = SystemModule::where('module', ucwords('pamm manager'));
                        return $this->check_module($this->module);
                        break;
                        // parent ->19 social copy trades report
                    case 'copy_trades_report':
                        $this->module = SystemModule::where('module', ucwords('copy trades report'));
                        return $this->check_module($this->module);
                        break;
                        // parent ->19 social trades activity reports
                    case 'social_trades_activity_reports':
                        $this->module = SystemModule::where('module', ucwords('social trades activity reports'));
                        return $this->check_module($this->module);
                        break;
                        // parent ->19 social manage mamm
                    case 'manage_mamm':
                        $this->module = SystemModule::where('module', ucwords('manage mamm'));
                        return $this->check_module($this->module);
                        break;
                        // parent 19 social trade
                        // pamm request
                    case 'pamm_request':
                        $this->module = SystemModule::where('module', ucwords('pamm request'));
                        return $this->check_module($this->module);
                        break;
                        // manage clients parent
                    case 'manage_client':
                        $this->module = SystemModule::where('module', ucwords('manage client'));
                        return $this->check_module($this->module);
                        break;
                        // parent 2 /trader admin
                    case 'trader_admin':
                        $this->module = SystemModule::where('module', ucwords('trader admin'));
                        return $this->check_module($this->module);
                        break;
                        // parent 2 / trader admin
                    case 'trader_analysis':
                        $this->module = SystemModule::where('module', ucwords('trader analysis'));
                        return $this->check_module($this->module);
                        break;
                        // manage trade parent
                    case 'manage_trade':
                        $this->module = SystemModule::where('module', ucwords('manage trade'));
                        return $this->check_module($this->module);
                        break;
                        // trading report
                    case 'trading_report':
                        $this->module = SystemModule::where('module', ucwords('trading report'));
                        return $this->check_module($this->module);
                        break;
                        // trade commission parent 3
                    case 'trade_commission':
                        $this->module = SystemModule::where('module', ucwords('trade commission'));
                        return $this->check_module($this->module);
                        break;
                        // manage admin // parent
                    case 'manage_admin':
                        $this->module = SystemModule::where('module', ucwords('manage admin'));
                        return $this->check_module($this->module);
                        break;
                        // parent 4 admin group
                    case 'admin_groups':

                        $this->module = SystemModule::where('module', ucwords('admin groups'));

                        return $this->check_module($this->module);

                        break;
                        // parent 4 admin registration
                    case 'admin_registration':

                        $this->module = SystemModule::where('module', ucwords('admin registration'));

                        return $this->check_module($this->module);

                        break;
                        // admin right management/ parent 4
                    case 'admin_right_management':
                        $this->module = SystemModule::where('module', ucwords('admin right management'));
                        return $this->check_module($this->module);
                        break;
                        // parent 5 manager settings
                    case 'manager_settings':

                        $this->module = SystemModule::where('module', ucwords('manager settings'));

                        return $this->check_module($this->module);

                        break;
                        // parent 5 -> manager group
                    case 'manager_groups':

                        $this->module = SystemModule::where('module', ucwords('manager groups'));

                        return $this->check_module($this->module);

                        break;
                        // parent 5 -> add manager
                    case 'add_manager':

                        $this->module = SystemModule::where('module', ucwords('add manager'));

                        return $this->check_module($this->module);

                        break;
                        // parent 5 ->manager list
                    case 'manager_list':
                        $this->module = SystemModule::where('module', ucwords('manager list'));
                        return $this->check_module($this->module);
                        // parent 5 -> manager right
                    case 'manager_right':
                        $this->module = SystemModule::where('module', ucwords('manager right'));
                        return $this->check_module($this->module);
                        break;
                        // parent 5->manager analysis
                    case 'manager_analysis':
                        $this->module = SystemModule::where('module', ucwords('manager analysis'));
                        return $this->check_module($this->module);
                        break;
                        // parent 6-> manage account
                    case 'manage_accounts':
                        $this->module = SystemModule::where('module', ucwords('manage accounts'));
                        return $this->check_module($this->module);
                        break;
                        // parent 6-> live trading account
                    case 'live_trading_account':

                        $this->module = SystemModule::where('module', ucwords('live trading account'));

                        return $this->check_module($this->module);

                        break;
                        // parent 6->demo trading account
                    case 'demo_trading_account':
                        $this->module = SystemModule::where('module', ucwords('demo trading account'));
                        return $this->check_module($this->module);
                        break;
                        // parent 7 / manage bank
                    case 'manage_banks':
                        $this->module = SystemModule::where('module', ucwords('manage banks'));
                        return $this->check_module($this->module);
                        break;
                        // parent 7-> bank account list
                    case 'bank_account_list':
                        $this->module = SystemModule::where('module', ucwords('bank account list'));
                        return $this->check_module($this->module);
                        break;
                        // parent 7 / bompany bank account list
                        // manange bank
                    case 'company_bank_list':
                        $this->module = SystemModule::where('module', ucwords('company bank list'));
                        return $this->check_module($this->module);
                        break;
                        // parent 8 / finance
                    case 'finance':
                        $this->module = SystemModule::where('module', ucwords('finance'));
                        return $this->check_module($this->module);
                        break;
                        // parent 8->balance management
                    case 'balance_management':
                        $this->module = SystemModule::where('module', ucwords('balance management'));
                        return $this->check_module($this->module);
                        break;
                        // parent 8-> credit management
                    case 'credit_management':
                        $this->module = SystemModule::where('module', ucwords('credit management'));
                        return $this->check_module($this->module);
                        break;
                        // fund management
                    case 'fund_management':
                        $this->module = SystemModule::where('module', ucwords('fund management'));
                        return $this->check_module($this->module);
                        break;
                        // parent 8-> finance reports
                    case 'finance_reports':
                        $this->module = SystemModule::where('module', ucwords('finance reports'));
                        return $this->check_module($this->module);
                        break;
                        // admin deposit report
                        // parent 8 / finance
                    case 'admin_deposit':
                        $this->module = SystemModule::where('module', ucwords('admin deposit'));
                        return $this->check_module($this->module);
                        break;
                        // admin withdraw report
                        // parent 8 / finance
                    case 'admin_withdraw':
                        $this->module = SystemModule::where('module', ucwords('admin withdraw'));
                        return $this->check_module($this->module);
                        break;
                        // parent 9 / supports
                    case 'support':
                        $this->module = SystemModule::where('module', ucwords('support'));
                        return $this->check_module($this->module);
                        break;
                        // parent 9->support tickets
                    case 'support_tickets':
                        $this->module = SystemModule::where('module', ucwords('support tickets'));
                        return $this->check_module($this->module);
                        break;
                        // parent 10 / category manager
                    case 'category_manager':
                        $this->module = SystemModule::where('module', ucwords('category manager'));
                        return $this->check_module($this->module);
                        break;
                    case 'lead_management':
                        $this->module = SystemModule::where('module', ucwords('lead management'));
                        return $this->check_module($this->module);
                        break;
                        // parent 10->trader category
                    case 'trader_category':
                        $this->module = SystemModule::where('module', ucwords('trader category'));
                        return $this->check_module($this->module);
                        break;
                        // parent 10->ib category

                    case 'ib_category':
                        $this->module = SystemModule::where('module', ucwords('IB category'));
                        return $this->check_module($this->module);
                        break;
                        // parent 11 / ib management
                    case 'ib_management':
                        $this->module = SystemModule::where('module', ucwords('IB management'));
                        return $this->check_module($this->module);
                        break;
                        // parent 11-> ib setup
                    case 'ib_setup':
                        $this->module = SystemModule::where('module', ucwords('IB setup'));
                        return $this->check_module($this->module);
                        break;
                        // parent 11-> ib commission structure
                    case 'ib_commission_structure':
                        $this->module = SystemModule::where('module', ucwords('IB commission structure'));
                        return $this->check_module($this->module);
                        break;
                        // parent 11-> ib tree
                    case 'ib_tree':
                        $this->module = SystemModule::where('module', ucwords('IB tree'));
                        return $this->check_module($this->module);
                        break;
                        // parent 11-> master IB
                    case 'master_ib':
                        $this->module = SystemModule::where('module', ucwords('master IB'));
                        return $this->check_module($this->module);
                        break;
                        // parent 11->pending commission list
                    case 'pending_commission_list':
                        $this->module = SystemModule::where('module', ucwords('pending commission list'));
                        return $this->check_module($this->module);
                        break;
                        // parent 11->no commission list
                    case 'mo_commission_list':
                        $this->module = SystemModule::where('module', ucwords('pending commission list'));
                        return $this->check_module($this->module);
                        break;
                        // parent 11->ib chain
                    case 'ib_chain':
                        $this->module = SystemModule::where('module', ucwords('IB chain'));
                        return $this->check_module($this->module);
                        break;
                        // parent 11->ib admin
                    case 'ib_admin':
                        $this->module = SystemModule::where('module', ucwords('IB admin'));
                        return $this->check_module($this->module);
                        break;
                        // parent 11->ib verification request
                    case 'ib_verification_request':
                        $this->module = SystemModule::where('module', ucwords('IB verification request'));
                        return $this->check_module($this->module);
                        break;
                        // parent 11->ib analysis
                    case 'ib_analysis':
                        $this->module = SystemModule::where('module', ucwords('IB analysis'));
                        return $this->check_module($this->module);
                        break;
                        // parent 11 / ib management
                        // ib registration request
                    case 'ib_registration_request':
                        $this->module = SystemModule::where('module', ucwords('IB registration request'));
                        return $this->check_module($this->module);
                        break;
                        // parent 12/ settings
                    case 'settings':
                        $this->module = SystemModule::where('module', ucwords('settings'));
                        return $this->check_module($this->module);
                        break;
                        // parent 12 -> add crypto address
                    case 'add_crypto_address':
                        $this->module = SystemModule::where('module', ucwords('add crypto address'));
                        return $this->check_module($this->module);
                        break;
                        // parent 12-> announcement
                    case 'announcement':
                        $this->module = SystemModule::where('module', ucwords('announcement'));
                        return $this->check_module($this->module);
                        break;
                        // parent 12-> api configuration
                    case 'api_configuration':
                        $this->module = SystemModule::where('module', ucwords('API configuration'));
                        return $this->check_module($this->module);
                        break;
                        // parent 12->bank setting
                    case 'bank_setting':
                        $this->module = SystemModule::where('module', ucwords('bank setting'));
                        return $this->check_module($this->module);
                        break;
                        // parent 12 / currency setup
                        // settings / parent
                    case 'currency_setup':
                        $this->module = SystemModule::where('module', ucwords('currency setup'));
                        return $this->check_module($this->module);
                        break;
                        // parent 12 / copy symbol
                        // settings / parent
                    case 'copy_symbols':
                        $this->module = SystemModule::where('module', ucwords('copy symbols'));
                        return $this->check_module($this->module);
                        break;
                        // parent 12->banner setup
                    case 'banner_setup':
                        $this->module = SystemModule::where('module', ucwords('banner setup'));
                        return $this->check_module($this->module);
                        break;
                        // parent 12->company setup
                    case 'company_setup':
                        $this->module = SystemModule::where('module', ucwords('company setup'));
                        return $this->check_module($this->module);
                        break;
                        // prent 12 -> currency pair
                    case 'currency_pair':
                        $this->module = SystemModule::where('module', ucwords('currency pair'));
                        return $this->check_module($this->module);
                        break;
                        // parent 12-> finance settings
                    case 'finance_settings':
                        $this->module = SystemModule::where('module', ucwords('finance settings'));
                        return $this->check_module($this->module);
                        break;
                        // parent 12->IB settings
                    case 'ib_settings':
                        $this->module = SystemModule::where('module', ucwords('IB settings'));
                        return $this->check_module($this->module);
                        break;
                        // parent 12->notification setting
                    case 'notification_settings':
                        $this->module = SystemModule::where('module', ucwords('notification settings'));
                        return $this->check_module($this->module);
                        break;
                        // parent 12->security setting
                    case 'security_settings':
                        $this->module = SystemModule::where('module', ucwords('security settings'));
                        return $this->check_module($this->module);
                        break;
                        // parent 12->smtp setup
                    case 'smtp_setup':
                        $this->module = SystemModule::where('module', ucwords('SMTP setup'));
                        return $this->check_module($this->module);
                        break;
                        // parent 12->software settings
                    case 'software_settings':
                        $this->module = SystemModule::where('module', ucwords('software settings'));
                        return $this->check_module($this->module);
                        break;
                        // parent 12->trader settings
                    case 'trader_settings':
                        $this->module = SystemModule::where('module', ucwords('trader settings'));
                        return $this->check_module($this->module);
                        break;
                    case 'payment_gateways':
                        $this->module = SystemModule::where('module', ucwords('payment gateways'));
                        return $this->check_module($this->module);
                        break;
                    case 'notification_template':
                        // settings parent / 12
                        // notification template
                        $this->module = SystemModule::where('module', ucwords('notification template'));
                        return $this->check_module($this->module);
                        break;
                        // parent 13 / kyc management
                    case 'kyc_management':
                        $this->module = SystemModule::where('module', ucwords('kyc management'));
                        return $this->check_module($this->module);
                        break;
                        // parent 13->kyc upload
                    case 'kyc_upload':
                        $this->module = SystemModule::where('module', ucwords('kyc upload'));
                        return $this->check_module($this->module);
                        break;
                        // parent 13->kyc reports
                    case 'kyc_reports':
                        $this->module = SystemModule::where('module', ucwords('kyc reports'));
                        return $this->check_module($this->module);
                        break;
                        // parent 13->kyc request
                    case 'kyc_request':
                        $this->module = SystemModule::where('module', ucwords('kyc request'));
                        return $this->check_module($this->module);
                        break;
                        // parent 14 manage request
                    case 'manage_request':
                        $this->module = SystemModule::where('module', ucwords('manage request'));
                        return $this->check_module($this->module);
                        break;
                        // parent 14->deposit request
                    case 'deposit_request':
                        $this->module = SystemModule::where('module', ucwords('deposit request'));
                        return $this->check_module($this->module);
                        break;
                        // parent 14->withdraw request
                    case 'withdraw_request':
                        $this->module = SystemModule::where('module', ucwords('withdraw request'));
                        return $this->check_module($this->module);
                        break;
                        // parent 14->acount request
                    case 'account_request':
                        $this->module = SystemModule::where('module', ucwords('account request'));
                        return $this->check_module($this->module);
                        break;
                        // parent 14->balance transfer request
                    case 'balance_transfer_request':
                        $this->module = SystemModule::where('module', ucwords('balance transfer request'));
                        return $this->check_module($this->module);
                        break;
                        // parent 14->ib transfer request
                    case 'ib_transfer_request':
                        $this->module = SystemModule::where('module', ucwords('IB transfer request'));
                        return $this->check_module($this->module);
                        break;
                        // parent 14->ib withdraw request
                    case 'ib_withdraw_request':
                        $this->module = SystemModule::where('module', ucwords('IB withdraw request'));
                        return $this->check_module($this->module);
                        break;
                        // parent 15 / fund transfer
                    case 'fund_transfer':
                        $this->module = SystemModule::where('module', ucwords('fund transfer'));
                        return $this->check_module($this->module);
                        break;
                        // parent 15->internal fund transfer
                    case 'internal_fund_transfer':
                        $this->module = SystemModule::where('module', ucwords('internal fund transfer'));
                        return $this->check_module($this->module);
                        break;
                        // parent 15->external fund transfer
                    case 'external_fund_transfer':
                        $this->module = SystemModule::where('module', ucwords('external fund transfer'));
                        return $this->check_module($this->module);
                        break;
                        // parent 16/ reports
                    case 'reports':
                        $this->module = SystemModule::where('module', ucwords('reports'));
                        return $this->check_module($this->module);
                        break;
                        // parent 16->ib withdraw
                    case 'ib_withdraw':
                        $this->module = SystemModule::where('module', ucwords('IB withdraw'));
                        return $this->check_module($this->module);
                        break;
                        // parent 16->ib commission
                    case 'ib_commission':
                        $this->module = SystemModule::where('module', ucwords('IB commission'));
                        return $this->check_module($this->module);
                        break;
                        // parent 16-> trader withdraw
                    case 'trader_withdraw':
                        $this->module = SystemModule::where('module', ucwords('trader withdraw'));
                        return $this->check_module($this->module);
                        break;
                        // parent 16 / reports
                    case 'blocked_users':
                        $this->module = SystemModule::where('module', ucwords('blocked users'));
                        return $this->check_module($this->module);
                        break;
                        // parent 16->deposit request
                    case 'deposit_request':
                        $this->module = SystemModule::where('module', ucwords('deposit request'));
                        return $this->check_module($this->module);
                        break;
                        // parent 16->activity log
                    case 'activity_log':
                        $this->module = SystemModule::where('module', ucwords('activity log'));
                        return $this->check_module($this->module);
                        break;
                        // parent 16->trader deposit
                    case 'trader_deposit':
                        $this->module = SystemModule::where('module', ucwords('trader deposit'));
                        return $this->check_module($this->module);
                        break;
                        // parent 16->bonus report
                    case 'bonus_report':
                        $this->module = SystemModule::where('module', ucwords('bonus report'));
                        return $this->check_module($this->module);
                        break;
                        // parent 16->ib fund transfer
                    case 'ib_fund_transfer':
                        $this->module = SystemModule::where('module', ucwords('IB fund transfer'));
                        return $this->check_module($this->module);
                        break;
                        // parent 16 / reports
                        // ib extenal fund transfer
                    case 'external_fund_transfer':
                        $this->module = SystemModule::where('module', ucwords('external fund transfer'));
                        return $this->check_module($this->module);
                        break;
                        // parent 16->balance upload and deduction

                    case 'balance_upload_and_deduction':
                        $this->module = SystemModule::where('module', ucwords('balance upload and deduction'));
                        return $this->check_module($this->module);
                        break;
                        // parent 16->ledger report
                    case 'ledger_report':
                        $this->module = SystemModule::where('module', ucwords('ledger report'));
                        return $this->check_module($this->module);
                        break;
                        // parent 16-> individual ledger reports
                    case 'individual_ledger_report':
                        $this->module = SystemModule::where('module', ucwords('individual ledger report'));
                        return $this->check_module($this->module);
                        break;
                        // parent 17/offer
                    case 'offers':
                        $this->module = SystemModule::where('module', ucwords('offers'));
                        return $this->check_module($this->module);
                        break;
                        // parent 17->voucher generate
                    case 'voucher_generate':
                        $this->module = SystemModule::where('module', ucwords('voucher generate'));
                        return $this->check_module($this->module);
                        break;
                        // parent 17 voucher report
                    case 'voucher_report':
                        $this->module = SystemModule::where('module', ucwords('voucher report'));
                        return $this->check_module($this->module);
                        break;
                        // parent 17 / offers
                        // create bonus
                    case 'create_bonus':
                        $this->module = SystemModule::where('module', ucwords('create bonus'));
                        return $this->check_module($this->module);
                        break;
                        // parent 17 / offers
                        // bonus list
                    case 'bonus_list':
                        $this->module = SystemModule::where('module', ucwords('bonus list'));
                        return $this->check_module($this->module);
                        break;
                        // parent 17 / offers
                        // bonus list
                    case 'offer_bonus_report':
                        $this->module = SystemModule::where('module', ucwords('bonus report(offer)'));
                        return $this->check_module($this->module);
                        break;
                        // parent 18/group settings
                    case 'group_settings':
                        $this->module = SystemModule::where('module', ucwords('group settings'));
                        return $this->check_module($this->module);
                        break;
                        // parent 18-> group manager
                    case 'group_manager':
                        $this->module = SystemModule::where('module', ucwords('group manager'));
                        return $this->check_module($this->module);
                        break;
                        // parent 18->group list
                    case 'group_list':
                        $this->module = SystemModule::where('module', ucwords('group list'));
                        return $this->check_module($this->module);
                        break;
                        // parent 18->manage ib group
                    case 'manage_ib_group':
                        $this->module = SystemModule::where('module', ucwords('manage ib group'));
                        return $this->check_module($this->module);
                        break;
                        // contest / 20
                    case 'contest':
                        $this->module = SystemModule::where('module', ucwords('contest'));
                        return $this->check_module($this->module);
                        break;
                        // parent 20 / contest
                        // create contest
                    case 'create_contest':
                        $this->module = SystemModule::where('module', ucwords('create contest'));
                        return $this->check_module($this->module);
                        break;
                        // parent 20 / contest
                        // contest list
                    case 'contest_list':
                        $this->module = SystemModule::where('module', ucwords('contest list'));
                        return $this->check_module($this->module);
                        break;
                        // parent 20 / contest
                        // contest participant
                    case 'contest_participant':
                        $this->module = SystemModule::where('module', ucwords('contest participant'));
                        return $this->check_module($this->module);
                        break;
                }
                break;
            default:
                switch ($access_module) {
                        // ib menues permission
                    case 'my_admin':

                        $this->module = IbSetting::where('settings', ucwords('my admin'));

                        return $this->check_module($this->module);

                        break;

                    case 'settings':

                        $this->module = IbSetting::where('settings', ucwords('settings'));

                        return $this->check_module($this->module);

                        break;
                    case 'profile_overview':

                        $this->module = IbSetting::where('settings', ucwords('profile overview'));

                        return $this->check_module($this->module);

                        break;
                    case 'verification':

                        $this->module = IbSetting::where('settings', ucwords('verification'));

                        return $this->check_module($this->module);

                        break;

                    case 'banking':

                        $this->module = IbSetting::where('settings', ucwords('banking'));

                        return $this->check_module($this->module);

                        break;
                        // affiliate
                    case 'affiliate':

                        $this->module = IbSetting::where('settings', ucwords('affiliate'));

                        return $this->check_module($this->module);

                        break;
                    case 'ib_tree':

                        $this->module = IbSetting::where('settings', ucwords('IB tree'));

                        return $this->check_module($this->module);

                        break;
                    case 'my_ib':

                        $this->module = IbSetting::where('settings', ucwords('my IB'));

                        return $this->check_module($this->module);

                        break;

                    case 'my_clients':

                        $this->module = IbSetting::where('settings', ucwords('my clients'));

                        return $this->check_module($this->module);

                        break;

                    case 'deposit_reports':

                        $this->module = IbSetting::where('settings', ucwords('deposit reports'));

                        return $this->check_module($this->module);

                        break;

                    case 'withdraw_reports':

                        $this->module = IbSetting::where('settings', ucwords('withdraw reports'));

                        return $this->check_module($this->module);

                        break;

                    case 'reports':
                        // reports
                        $this->module = IbSetting::where('settings', ucwords('reports'));

                        return $this->check_module($this->module);

                        break;

                    case 'trade_commission':

                        $this->module = IbSetting::where('settings', ucwords('trade commission'));

                        return $this->check_module($this->module);

                        break;

                    case 'withdraw_report':

                        $this->module = IbSetting::where('settings', ucwords('withdraw report'));

                        return $this->check_module($this->module);

                        break;

                        // IB Balance Send
                    case 'ib_balance_send':
                        $this->module = IbSetting::where('settings', ucwords('IB balance send'));
                        return $this->check_module($this->module);
                        break;

                        // IB Balance Receive
                    case 'ib_balance_receive':
                        $this->module = IbSetting::where('settings', ucwords('IB balance receive'));
                        return $this->check_module($this->module);
                        break;
                        // withdraw
                    case 'withdraw':

                        $this->module = IbSetting::where('settings', ucwords('withdraw'));

                        return $this->check_module($this->module);

                        break;

                    case 'bank_withdraw':

                        $this->module = IbSetting::where('settings', ucwords('bank withdraw'));

                        return $this->check_module($this->module);

                        break;

                    case 'crypto_withdraw':

                        $this->module = IbSetting::where('settings', ucwords('crypto withdraw'));

                        return $this->check_module($this->module);

                    case 'skrill_withdraw':

                        $this->module = IbSetting::where('settings', ucwords('skrill withdraw'));

                        return $this->check_module($this->module);

                        break;

                    case 'neteleer_withdraw':

                        $this->module = IbSetting::where('settings', ucwords('neteller withdraw'));

                        return $this->check_module($this->module);

                        break;
                    case 'gcash_withdraw':
                        // ib
                        // gcash withdraw
                        $this->module = IbSetting::where('settings', ucwords('gcash withdraw'));
                        return $this->check_module($this->module);
                        break;
                        // transfer

                    case 'transfer':

                        $this->module = IbSetting::where('settings', ucwords('transfer'));

                        return $this->check_module($this->module);

                        break;
                    case 'ib_to_trader_transfer':
                        // ib to trader transfer
                        $this->module = IbSetting::where('settings', ucwords('IB to trader transfer'));
                        return $this->check_module($this->module);
                        break;
                    case 'ib_to_ib_transfer':
                        // ib to ib transfer
                        $this->module = IbSetting::where('settings', ucwords('IB to IB transfer'));
                        return $this->check_module($this->module);
                        break;
                        // support
                    case 'support':

                        $this->module = IbSetting::where('settings', ucwords('support'));
                        return $this->check_module($this->module);
                        break;
                    case 'support_ticket':
                        $this->module = IbSetting::where('settings', ucwords('support ticket'));
                        return $this->check_module($this->module);
                        break;
                }
                break;
        }
        return $this->permission;
    }



    // check oudule

    private function check_module($query)

    {

        if ($query->exists()) {

            $module_status = $query->first('status');

            $permission = $module_status->status;

            if ($module_status->status) {

                $permission = 'client_controll:access';
            } else {

                $permission = 'client_controll:notaccess';
            }
        } else {

            $permission = 'client_controll:notaccess';
        }

        return $permission;
    }

    // get ib commission by order number
    // ib of ib commission by order nuber
    // if type = account?filter by trading_account
    // if type=order?filter by order_number
    public function get_commission($orderORTradingAccount, $type = null)
    {
        switch ($type) {
            case 'account':
                $commission = IbIncome::where('trading_account', $orderORTradingAccount)->sum('amount');
                return $commission;
                break;

            default:
                $commission = IbIncome::where('order_num', $orderORTradingAccount)->sum('amount');
                return $commission;
                break;
        }
        return (0);
    }
    // find my direct traders
    public static function my_direct_traders($ib_id)
    {
        $traders = IB::where('ib_id', $ib_id)->where('users.type', CombinedService::type())
            ->join('users', 'ib.reference_id', '=', 'users.id')->select('reference_id');
        return $traders;
    }

    // my sub ib id
    public static function my_sub_ib_id($ib_id)
    {
        $all_sub_ib = (new self)->get_sub_ib($ib_id);
        $sub_ib_id = [];
        if (count($all_sub_ib) != 0) {
            for ($i = 0; $i < count($all_sub_ib); $i++) {
                array_push($sub_ib_id, $all_sub_ib[$i]['sub_ib']);
            }
        }
        return $sub_ib_id;
    }
    // my direct client ID
    public static function my_direct_client_id($ib_id)
    {
        $clients = IB::where('ib_id', $ib_id)->where('users.type', 0)->select('reference_id')
            ->join('users', 'ib.reference_id', '=', 'users.id')->get();
        $client_id = [];
        foreach ($clients as $key => $value) {
            array_push($client_id, $value->reference_id);
        }
        return $client_id;
    }
    // get sub ib traders
    public static function sub_ib_traders($ib_id, $part = 'subib')
    {
        $sub_ib_id = self::my_sub_ib_id($ib_id);
        switch ($part) {
            case 'all':
                array_push($sub_ib_id, $ib_id);
                break;

            default:
                # code...
                break;
        }
        $sub_ib_traders = IB::whereIn('ib_id', $sub_ib_id)
            ->join('users', 'ib.reference_id', '=', 'users.id');
        // check if crm is combined
        $sub_ib_traders = $sub_ib_traders->where('users.type', 0)->get();
        if ($sub_ib_traders) {
            return $sub_ib_traders;
        } else {
            return $sub_ib_traders = [];
        }
    }
    // sub ib trader id
    public static function sub_ib_traders_id($ib_id, $part = 'subib')
    {
        $sub_ib_id = self::my_sub_ib_id($ib_id);
        switch ($part) {
            case 'all':
                array_push($sub_ib_id, $ib_id);
                break;

            default:
                # code...
                break;
        }
        $sub_ib_traders = IB::whereIn('ib_id', $sub_ib_id)
            ->join('users', 'ib.reference_id', '=', 'users.id');
        $sub_ib_traders = $sub_ib_traders->where('users.type', 0)->get();
        if ($sub_ib_traders) {
            $trader_id = [];
            foreach ($sub_ib_traders as $key => $value) {
                array_push($trader_id, $value->reference_id);
            }
            return $trader_id;
        } else {
            return $sub_ib_traders = [];
        }
    }
    // check kyc required
    // return true for kyc required
    // return false for not required
    public static function kyc_required($user_id, $type)
    {
        $user = User::find($user_id);
        $check = KycRequired::select()->first();
        if ($check) {
            switch ($type) {
                case 'deposit':
                    if ($check->deposit == true) {

                        if ($user) {
                            if ($user->kyc_status == 1) {
                                return (false);
                            } else {
                                return (true);
                            }
                        }
                        return true;
                    }
                    return false;
                    break;
                case 'withdraw':
                    if ($check->withdraw == true) {

                        if ($user) {
                            if ($user->kyc_status == 1) {
                                return (false);
                            } else {
                                return (true);
                            }
                        }
                        return true;
                    }
                    return false;
                    break;
                case 'open-account':
                    if ($check->open_account == true) {

                        if ($user) {
                            if ($user->kyc_status == 1) {
                                return (false);
                            } else {
                                return (true);
                            }
                        }
                        return true;
                    }
                    return false;
                    break;

                default:
                    # code...
                    break;
            }
        }
        return false;
    }
    // get total trades
    private function get_total_trades($user_id)
    {
        try {
            $platform = get_platform();
            switch (strtolower($platform)) {
                case 'mt4':
                    $trads = DB::connection('alternate')->table('MT4_TRADES')
                        ->where('trading_accounts.user_id', $user_id)
                        ->join($this->prefix . 'trading_accounts', 'MT4_TRADES.LOGIN', '=', $this->prefix . 'trading_accounts.account_number')
                        ->select('login')->count();
                    break;

                default:
                    $trads = Mt5Trade::where('user_id', $user_id)->select('LOGIN')
                        ->join('trading_accounts', 'mt5_trades.LOGIN', '=', 'trading_accounts.account_number')
                        ->count();
                    break;
            }
            return $trads;
        } catch (\Throwable $th) {
            //throw $th;
            return 0;
        }
    }
    // get total trading accounts
    private function get_total_trading_accounts($user_id, $date = null)
    {
        $trading_accounts = TradingAccount::where('user_id', $user_id);
        // date filter
        if ($date != null) {
            $from = Carbon::parse($date['from']);
            $to = '';
            if ($date['to'] != "") {
                $to  = Carbon::parse($date['to']);
            }
            $trading_accounts = $trading_accounts->whereDate('trading_accounts.created_at', '<=', $to)->whereDate('trading_accounts.created_at', '>=', $from);
        }

        $trading_accounts = $trading_accounts->select()->count();
        return $trading_accounts;
    }
    // get total wallet to account transfer
    private function get_total_wta_transfer($user_id, $date = null)
    {
        try {
            $wta_transfer = InternalTransfer::where('user_id', $user_id)->where('type', 'wta')->where('status', 'A');
            // date filter
            if ($date != null) {
                $from = Carbon::parse($date['from']);
                $to = '';
                if ($date['to'] != "") {
                    $to  = Carbon::parse($date['to']);
                }
                $wta_transfer = $wta_transfer->whereDate('internal_transfers.created_at', '<=', $to)->whereDate('internal_transfers.created_at', '>=', $from);
            }
            $wta_transfer = $wta_transfer->sum('amount');
            return (round($wta_transfer, 2));
        } catch (\Throwable $th) {
            //throw $th;
            return 0;
        }
    }
    // get total account to wallet transfer
    private function get_total_atw_transfer($user_id, $date = null)
    {
        try {
            $atw_transfer = InternalTransfer::where('user_id', $user_id)->where('type', 'atw')->where('status', 'A');
            // date filter
            if ($date != null) {
                $from = Carbon::parse($date['from']);
                $to = '';
                if ($date['to'] != "") {
                    $to  = Carbon::parse($date['to']);
                }
                $atw_transfer = $atw_transfer->whereDate('internal_transfers.created_at', '<=', $to)->whereDate('internal_transfers.created_at', '>=', $from);
            }
            $atw_transfer = $atw_transfer->sum('amount');
            return (round($atw_transfer, 2));
        } catch (\Throwable $th) {
            //throw $th;
            return 0;
        }
    }
    // get total wallet to account transfer
    public function get_wta_transfer($user_id, $date = null)
    {
        try {
            $wta_transfer = InternalTransfer::where('user_id', $user_id)->where('type', 'wta')->where('status', 'A');
            // date filter
            if ($date != null) {
                $from = Carbon::parse($date['from']);
                $to = '';
                if ($date['to'] != "") {
                    $to  = Carbon::parse($date['to']);
                }
                $wta_transfer = $wta_transfer->whereDate('internal_transfers.created_at', '<=', $to)->whereDate('internal_transfers.created_at', '>=', $from);
            }
            $wta_transfer = $wta_transfer->sum('amount');
            return (round($wta_transfer, 2));
        } catch (\Throwable $th) {
            //throw $th;
            return 0;
        }
    }
    // get total account to wallet transfer
    public function get_atw_transfer($user_id, $date = null)
    {
        try {
            $atw_transfer = InternalTransfer::where('user_id', $user_id)->where('type', 'atw')->where('status', 'A');
            // date filter
            if ($date != null) {
                $from = Carbon::parse($date['from']);
                $to = '';
                if ($date['to'] != "") {
                    $to  = Carbon::parse($date['to']);
                }
                $atw_transfer = $atw_transfer->whereDate('internal_transfers.created_at', '<=', $to)->whereDate('internal_transfers.created_at', '>=', $from);
            }
            $atw_transfer = $atw_transfer->sum('amount');
            return (round($atw_transfer, 2));
        } catch (\Throwable $th) {
            //throw $th;
            return 0;
        }
    }
    // get total wallet to account transfer pending
    public function get_wta_transfer_pending($user_id, $date = null)
    {
        try {
            $wta_transfer = InternalTransfer::where('user_id', $user_id)->where('type', 'wta')->where('status', 'P');
            // date filter
            if ($date != null) {
                $from = Carbon::parse($date['from']);
                $to = '';
                if ($date['to'] != "") {
                    $to  = Carbon::parse($date['to']);
                }
                $wta_transfer = $wta_transfer->whereDate('internal_transfers.created_at', '<=', $to)->whereDate('internal_transfers.created_at', '>=', $from);
            }
            $wta_transfer = $wta_transfer->sum('amount');
            return (round($wta_transfer, 2));
        } catch (\Throwable $th) {
            //throw $th;
            return 0;
        }
    }
    // get total account to wallet transfer pending
    public function get_atw_transfer_pending($user_id, $date = null)
    {
        try {
            $atw_transfer = InternalTransfer::where('user_id', $user_id)->where('type', 'atw')->where('status', 'P');
            // date filter
            if ($date != null) {
                $from = Carbon::parse($date['from']);
                $to = '';
                if ($date['to'] != "") {
                    $to  = Carbon::parse($date['to']);
                }
                $atw_transfer = $atw_transfer->whereDate('internal_transfers.created_at', '<=', $to)->whereDate('internal_transfers.created_at', '>=', $from);
            }
            $atw_transfer = $atw_transfer->sum('amount');
            return (round($atw_transfer, 2));
        } catch (\Throwable $th) {
            //throw $th;
            return 0;
        }
    }
    // get total bonus
    private function get_total_bonus($user_id, $date = null)
    {
        try {
            $total_bonus = BonusUser::where('bonus_users.user_id', $user_id)->join('deposits', 'bonus_users.deposit_id', '=', 'deposits.id');
            // date filter
            if ($date != null) {
                $from = Carbon::parse($date['from']);
                $to = '';
                if ($date['to'] != "") {
                    $to  = Carbon::parse($date['to']);
                }
                $total_bonus = $total_bonus->whereDate('bonus_users.created_at', '<=', $to)->whereDate('bonus_users.created_at', '>=', $from);
            }
            $total_bonus = $total_bonus->sum('amount');
            return (round($total_bonus, 2));
        } catch (\Throwable $th) {
            //throw $th;
            return 0;
        }
    }
    // get total trader to trader transfer send
    private function get_total_trd_to_trd_send($user_id, $date = null)
    {
        $total_transfer = ExternalFundTransfers::where('sender_id', $user_id)->where('status', 'A')
            ->where('users.type', 0);
        // date filter
        if ($date != null) {
            $from = Carbon::parse($date['from']);
            $to = '';
            if ($date['to'] != "") {
                $to  = Carbon::parse($date['to']);
            }
            $total_transfer = $total_transfer->whereDate('external_fund_transfers.created_at', '<=', $to)->whereDate('external_fund_transfers.created_at', '>=', $from);
        }
        $total_transfer = $total_transfer->join('users', 'external_fund_transfers.receiver_id', '=', 'users.id')
            ->sum('amount');
        return (round($total_transfer, 2));
    }
    // get total trader to trader transfer recive
    private function get_total_trd_to_trd_recive($user_id, $date = null)
    {
        $total_transfer = ExternalFundTransfers::where('receiver_id', $user_id)->where('status', 'A')
            ->where('users.type', 0);
        // date filter
        if ($date != null) {
            $from = Carbon::parse($date['from']);
            $to = '';
            if ($date['to'] != "") {
                $to  = Carbon::parse($date['to']);
            }
            $total_transfer = $total_transfer->whereDate('external_fund_transfers.created_at', '<=', $to)->whereDate('external_fund_transfers.created_at', '>=', $from);
        }
        $total_transfer = $total_transfer->join('users', 'external_fund_transfers.sender_id', '=', 'users.id')
            ->sum('amount');
        return (round($total_transfer, 2));
    }
    // get total trader to ib transfer send
    private function get_total_trd_to_ib_send($user_id, $date = null)
    {
        $total_transfer = ExternalFundTransfers::where('sender_id', $user_id)->where('status', 'A')
            ->where('users.type', 4); //need to change for combined
        // date filter
        if ($date != null) {
            $from = Carbon::parse($date['from']);
            $to = '';
            if ($date['to'] != "") {
                $to  = Carbon::parse($date['to']);
            }
            $total_transfer = $total_transfer->whereDate('external_fund_transfers.created_at', '<=', $to)->whereDate('external_fund_transfers.created_at', '>=', $from);
        }
        $total_transfer = $total_transfer->join('users', 'external_fund_transfers.receiver_id', '=', 'users.id')
            ->sum('amount');
        return (round($total_transfer, 2));
    }
    // get total trader to trader transfer recive
    private function get_total_receive_from_ib($user_id, $date = null)
    {
        $total_transfer = ExternalFundTransfers::where('receiver_id', $user_id)->where('status', 'A')
            ->where('users.type', 4); // need to change for combined
        // date filter
        if ($date != null) {
            $from = Carbon::parse($date['from']);
            $to = '';
            if ($date['to'] != "") {
                $to  = Carbon::parse($date['to']);
            }
            $total_transfer = $total_transfer->whereDate('external_fund_transfers.created_at', '<=', $to)->whereDate('external_fund_transfers.created_at', '>=', $from);
        }
        $total_transfer = $total_transfer->join('users', 'external_fund_transfers.sender_id', '=', 'users.id')
            ->sum('amount');
        return (round($total_transfer, 2));
    }
    // get total withdraw with date
    private function get_total_withdraw_with_date($user_id, $date = null)
    {
        $withdraw = Withdraw::where('user_id', $user_id)->where('approved_status', 'A');
        // date filter
        if ($date != null) {
            $from = Carbon::parse($date['from']);
            $to = '';
            if ($date['to'] != "") {
                $to  = Carbon::parse($date['to']);
            }
            $withdraw = $withdraw->whereDate('withdraws.created_at', '<=', $to)->whereDate('withdraws.created_at', '>=', $from);
        }
        $withdraw = $withdraw->sum('amount');
        return (round($withdraw, 2));
    }
    // get total deposit with date filter
    private function get_total_deposit_with_date($user_id, $date = null)
    {
        $deposit = Deposit::where('user_id', $user_id)->where('approved_status', 'A');
        // date filter
        if ($date != null) {
            $from = Carbon::parse($date['from']);
            $to = '';
            if ($date['to'] != "") {
                $to  = Carbon::parse($date['to']);
            }
            $deposit = $deposit->whereDate('deposits.created_at', '<=', $to)->whereDate('deposits.created_at', '>=', $from);
        }
        $deposit = $deposit->sum('amount');
        return (round($deposit, 2));
    }
    // pending deposit specific user
    public static function client_pending_deposit($user_id = null)
    {
        $user_id = ($user_id == null) ? auth()->user()->id : $user_id;
    }
}
