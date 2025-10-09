<?php

namespace App\Http\Controllers;

use App\Models\AdminBank;
use App\Models\ClientGroup;
use App\Models\Country;
use App\Models\CryptoAddress;
use App\Models\CurrencySetup;
use App\Models\TradingAccount;
use App\Models\User;
use App\Services\CombinedService;
use App\Services\IbService;
use App\Services\MT4API;
use App\Services\Mt5WebApi;
use App\Services\PriceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class CommonController extends Controller
{
    // get admin bank details
    public function getBankDetails($bank_id)
    {
        $admin_bank = AdminBank::where('id', $bank_id)->first();
        if ($bank_id == 99999) {
            return Response::json([
                'bank_name' => "---",
                'account_name' => "---",
                'account_number' => "---",
                'swift_code' => "---",
                'routing' => "---",
                'bank_country' => "---",
                'bank_address' => "---",
                'minimum_deposit' => "---",
                'note' => "---",
                'ifsc_code' => "---",
                'loc_currency' => "---",
            ]);
        } else {
            $local_currency = CurrencySetup::find($admin_bank->currency_id);
            return Response::json([
                'bank_name' => $admin_bank->bank_name ?? "---",
                'account_name' => $admin_bank->account_name ?? "---",
                'account_number' => $admin_bank->account_number ?? "---",
                'swift_code' => $admin_bank->swift_code ?? "---",
                'routing' => $admin_bank->routing ?? "---",
                'bank_country' => $admin_bank->bank_country ?? "---",
                'bank_address' => $admin_bank->bank_address ?? "---",
                'minimum_deposit' => $admin_bank->minimum_deposit ?? "---",
                'note' => $admin_bank->note ?? "---",
                'ifsc_code' => $admin_bank->ifsc_code ?? "---",
                'loc_currency' => $local_currency->currency ?? "---"
            ]);
        }
    }
    //calculate / convert crypto to usd amount
    public function convert_amount(Request $request)
    {
        $bitcoin_data = new PriceService();
        $type = $request->crypto_type;
        $all_crypto_value = $bitcoin_data->prices();
        $get_arr = get_object_vars($all_crypto_value);
        if ($request->crypto_type == "BITCOIN") {
            if ($request->convart_from == "usd") {
                $crypto_amount = $request->usd_amount / $get_arr['BTC']['USD'];
            } else {
                $crypto_amount = $get_arr['BTC']['USD'] * $request->usd_amount;
            }
        }
        if ($request->crypto_type == "BTC") {
            if ($request->convart_from == "usd") {
                $crypto_amount = $request->usd_amount / $get_arr['BTC']['USD'];
            } else {
                $crypto_amount = $get_arr['BTC']['USD'] * $request->usd_amount;
            }
        }
        if ($request->crypto_type == "USDT") {
            $crypto_amount = $request->usd_amount;
            // if ($request->convart_from == "usd") {
            //     $crypto_amount = $request->usd_amount / $get_arr['USDT']['USD'];
            // } else {
            //     $crypto_amount = $get_arr['USDT']['USD'] * $request->usd_amount;
            // }
        }
        if ($request->crypto_type == "ETHEREUM") {
            if ($request->convart_from == "usd") {
                $crypto_amount = $request->usd_amount / $get_arr['ETH']['USD'];
            } else {
                $crypto_amount = $get_arr['ETH']['USD'] * $request->usd_amount;
            }
        }
        if ($request->crypto_type == "ETH") {
            if ($request->convart_from == "usd") {
                $crypto_amount = $request->usd_amount / $get_arr['ETH']['USD'];
            } else {
                $crypto_amount = $get_arr['ETH']['USD'] * $request->usd_amount;
            }
        }
        if ($request->crypto_type == "TRX") {
            if ($request->convart_from == "usd") {
                $crypto_amount = $request->usd_amount / $get_arr['TRX']['USD'];
            } else {
                $crypto_amount = $get_arr['TRX']['USD'] * $request->usd_amount;
            }
        }
        if ($request->crypto_type == "TRON") {
            if ($request->convart_from == "usd") {
                $crypto_amount = $request->usd_amount / $get_arr['TRX']['USD'];
            } else {
                $crypto_amount = $get_arr['TRX']['USD'] * $request->usd_amount;
            }
        }
        if ($request->crypto_type == "BCH") {
            if ($request->convart_from == "usd") {
                $crypto_amount = $request->usd_amount / $get_arr['BCH']['USD'];
            } else {
                $crypto_amount = $get_arr['BCH']['USD'] * $request->usd_amount;
            }
        }
        if ($request->crypto_type == "LTC") {
            if ($request->convart_from == "usd") {
                $crypto_amount = $request->usd_amount / $get_arr['LTC']['USD'];
            } else {
                $crypto_amount = $get_arr['LTC']['USD'] * $request->usd_amount;
            }
        }
        if ($request->crypto_type == "BNB") {
            if ($request->convart_from == "usd") {
                $crypto_amount = $request->usd_amount / $get_arr['BNB']['USD'];
            } else {
                $crypto_amount = $get_arr['BNB']['USD'] * $request->usd_amount;
            }
        }
        return Response::json($crypto_amount);
    }

    // get crypto instrument
    public function instrument(Request $request)
    {
        $data = [
            'instrument' => '',
            'crypto_address' => ''
        ];
        $crypto_address = CryptoAddress::where(function ($query) {
            $query->where('verify_1', 1)
                ->where('verify_2', 1)
                ->where('status', 1);
        });
        if ($request->op === 'instrument') {
            $instrument_options = '';
            $instruments = $crypto_address->where('block_chain', $request->request_data)->select('name', 'address')->get();
            foreach ($instruments as $key => $value) {
                $instrument_options .= '<option value="' . $value->name . '">' . $value->name . '</option>';
            }
            $data['instrument'] = $instrument_options;
            $data['crypto_address'] = ($instruments) ? $instruments[0]->address : '';
        }
        if ($request->op === 'address') {
            $crypto_address = $crypto_address->where('name', $request->request_data)->first();
            $data['crypto_address'] = ($crypto_address) ? $crypto_address->address : '';
        }
        return Response::json($data);
    }
    // start: get client type for trader registrations--------------------------------
    public function get_client_type(Request $request, $server)
    {
        $account_category = ClientGroup::where('server', $server)
            ->where('visibility', 'visible')
            ->select('account_category')->get();
        $options = '<option value="">choose a client type</option>';
        foreach ($account_category as $key => $value) {
            $options .= '<option value="' . $value->account_category . '">' . $value->account_category . '</option>';
        }
        return Response::json($options);
    }

    // end: client category/type for trader registration------------------
    public function get_account_type(Request $request, $server)
    {
        if ($request->op === 'demo') {
            $account_category = ClientGroup::where('server', $server)
                ->where('visibility', 'visible')
                ->where('account_category', 'demo')
                ->where('active_status', 1)
                ->select('group_id', 'id')->get();
        } else {
            $account_category = ClientGroup::where('server', $server)
                ->where('visibility', 'visible')
                ->where('account_category', 'live')
                ->where('active_status', 1)
                ->select('group_id', 'id')->get();
        }

        $options = '<option value="">choose a Account type</option>';
        foreach ($account_category as $key => $value) {
            $options .= '<option value="' . $value->id . '">' . $value->group_id . '</option>';
        }
        return Response::json($options);
    }
    // get leverage for regitration
    public function get_leverage(Request $request, $group_id)
    {
        $group = ClientGroup::where('id', $group_id)->first();
        $leverage_options = '<option value="">choose a Account type</option>';
        $leverage = json_decode($group->leverage);
        for ($i = 0; $i < count($leverage); $i++) {
            $leverage_options .= '<option value="' . $leverage[$i] . '">' . $leverage[$i] . '</option>';
        }
        return Response::json($leverage_options);
    }
    // // start: get client group for trader registrations-------------------------------------
    // public function get_client_groups(Request $request, $server, $client_type)
    // {
    //     // Get the authenticated user
    //     $authUser = auth()->user();

    //     // Decode the groups field (convert from string to array)
    //     $userGroupIds = json_decode($authUser->groups, true);

    //     // Ensure the groups field is an array
    //     if (!is_array($userGroupIds)) {
    //         $userGroupIds = [];
    //     }

    //     // return $client_type;
    //     $client_groups = ClientGroup::where('server', $server)
    //         ->where('visibility', 'visible')
    //         ->where('account_category', $client_type)
    //         ->where('active_status', 1)
    //         ->whereIn('id', $userGroupIds) 
    //         ->get();

    //     $client_group_option = '<option value="">choose a account type</option> ';
    //     foreach ($client_groups as $key => $value) {
    //         $client_group_option .= '<option value="' . $value->id . '">' . $value->group_id . '</option>';
    //     }

    //     $leverage_options = '';
    //     $leverage = json_decode($client_groups[0]->leverage);
    //     for ($i = 0; $i < count($leverage); $i++) {
    //         $leverage_options .= '<option value="' . $leverage[$i] . '">' . $leverage[$i] . '</option>';
    //     }
    //     return Response::json([
    //         'client_groups' => $client_group_option,
    //         'leverage' => $leverage_options,
    //     ]);
    // }
    public function get_client_groups(Request $request, $server, $client_type)
    {
        // return $client_type;
        $client_groups = ClientGroup::where('server', $server)
            ->where('visibility', 'visible')
            ->where('account_category', $client_type)
            ->where('active_status', 1)
            ->get();

        $client_group_option = '<option value="">choose a account type</option> ';
        foreach ($client_groups as $key => $value) {
            $client_group_option .= '<option value="' . $value->id . '">' . $value->group_id . '</option>';
        }

        $leverage_options = '';
        $leverage = json_decode($client_groups[0]->leverage);
        for ($i = 0; $i < count($leverage); $i++) {
            $leverage_options .= '<option value="' . $leverage[$i] . '">' . $leverage[$i] . '</option>';
        }
        return Response::json([
            'client_groups' => $client_group_option,
            'leverage' => $leverage_options,
        ]);
    }
    // end: get client group gor trader registrations-------------------------
    // check balance and equity
    public function balance_equity(Request $request, $account_number = null, $platform = null)
    {
        $trading_account = TradingAccount::where('account_number', $account_number)->first();
        $response['success'] = false;
        if (!isset($trading_account)) {
            return Response::json([
                'success' => false,
                'message' => "Failed to check wallet balance!"
            ]);
        }
        if ($platform == 'mt4') {
            $mt4api = new MT4API();
            $data = array(
                'command' => 'user_data_get',
                'data' => array('account_id' => $account_number),
            );

            $result = $mt4api->execute($data, $trading_account->client_type);

            if ($result["success"]) {
                $result1 = $result['data'];
                $response['success'] = true;
                $response['credit'] = 0;
                $response['equity'] = $result1['equity'];
                $response['balance'] = $result1['balance'];
                $response['free_margin'] = 0;
                $response['amount']  = ($request->search === 'balance') ? $result1['balance'] : $result1['equity'];
                return Response::json($response);
            } else {
                return Response::json([
                    'success' => false,
                    'message' => $result['info']['message']
                ]);
            }
        }
        // for mt5 api-------------------
        else {
            $mt5_api = new Mt5WebApi();
            $action = 'AccountGetMargin';

            $data = array(
                "Login" => $trading_account->account_number
            );
            $result = $mt5_api->execute($action, $data);
            $mt5_api->Disconnect();

            if (isset($result['success'])) {
                // return $result;
                if ($result['success']) {
                    $response['success'] = true;
                    $response['credit'] = $result['data']['Credit'];
                    $response['equity'] = $result['data']['Equity'];
                    $response['balance'] = $result['data']['Balance'];
                    $response['free_margin'] = isset($result['data']['MarginFree']) ? $result['data']['MarginFree'] : 0;
                    $response['amount']  = ($request->search === 'balance') ? $result['data']['Balance'] : $result['data']['Equity'];
                    return Response::json($response);
                } else if (isset($result['error'])) {
                    $response['message'] = $result['error']['Description'];
                } else {
                    $response = [
                        'success' => false,
                        'message' => $result['message']
                    ];
                }
            }
            return Response::json($response);
        }
    }

    // end: get client group gor trader registrations-------------------------
    // get data for select2 users
    public function ib_user_select2(Request $request)
    {
        if (!isset($request->searchTerm)) {
            $fetchData = User::whereIn('type', [0, 4])->limit(5)->get();
        } else {
            $search = $request->searchTerm;
            $fetchData = User::whereIn('type', [0, 4])->where('email', 'like', '%' . $search . '%')->limit(5)->get();
        }

        $data = array();
        foreach ($fetchData as $key => $value) {
            if ($value->type === 'ib') {
                $title = 'IB';
            } else {
                $title = ucwords($value->type);
            }
            $data[] = array(
                'id' => $value->id,
                'text' => $value->email,
                'title' => 'Type: ' . $title,
                'parent' => (IbService::has_parent($value->id)) ? 'Yes' : 'No'
            );
        }
        return Response::json($data);
    }

    // removed trading account and user details
    public function removedTradingAccountDetatils(Request $request)
    {
        return "works";
        if (!isset($request->searchTerm)) {
            $fetchData = User::whereIn('type', [0, 4])->limit(5)->get();
        } else {
            $search = $request->searchTerm;
            $fetchData = User::whereIn('type', [0, 4])->where('email', 'like', '%' . $search . '%')->limit(5)->get();
        }

        $data = array();
        foreach ($fetchData as $key => $value) {
            if ($value->type === 'ib') {
                $title = 'IB';
            } else {
                $title = ucwords($value->type);
            }
            $data[] = array(
                'id' => $value->id,
                'text' => $value->email,
                'title' => 'Type: ' . $title,
                'parent' => (IbService::has_parent($value->id)) ? 'Yes' : 'No'
            );
        }
        return Response::json($data);
    }

    // search ib from select 2
    public function get_ib(Request $request)
    {
        $fetchData = [];
        if (isset($request->searchTerm)) {
            $search = $request->searchTerm;
            $fetchData = User::where('type', 0)->where('combine_access', 1);
            // check crm is combine
            if (CombinedService::is_combined()) {
                $fetchData = $fetchData->where('users.combine_access', 1);
            }
            $fetchData = $fetchData->where('email', 'like', '%' . $search . '%')->limit(5)->get();
        }

        $data = array();
        foreach ($fetchData as $key => $value) {
            if ($value->type === 'ib') {
                $title = 'IB';
            } else {
                if ($value->combine_access == 1) {
                    $title = 'IB';
                } else {
                    $title = ucwords($value->type);
                }
            }
            $data[] = array(
                'id' => $value->id,
                'text' => $value->email,
                'title' => '<strong>Type: </strong>' . $title,
                'name' => ucwords($value->name)
            );
        }
        return Response::json($data);
    }
    // search trader from select 2
    public function get_trader(Request $request)
    {
        $fetchData = [];
        if (isset($request->searchTerm)) {
            $search = $request->searchTerm;
            $fetchData = User::where('type', 0)->whereNot('id', auth()->user()->id);
            // check crm is combine
            // if (CombinedService::is_combined()) {
            //     $fetchData = $fetchData->where('users.combine_access', 0);
            // }
            $fetchData = $fetchData->where('email', 'like', '%' . $search . '%')->limit(5)->get();
        }

        $data = array();
        foreach ($fetchData as $key => $value) {
            if ($value->type === 'ib') {
                $title = 'IB';
            } else {
                // if ($value->combine_access == 1) {
                //     $title = 'IB';
                // } else {
                //     $title = ucwords($value->type);
                // }
                $title = ucwords($value->type);
            }
            $data[] = array(
                'id' => $value->id,
                'text' => $value->email,
                'title' => '<strong>Type: </strong>' . $title,
                'name' => ucwords($value->name)
            );
        }
        if (empty($data)) {
            $data[] = array(
                'id' => "",
                'text' => "",
                'title' => '<strong>Type: </strong>' . 0,
                'name' => ""
            );
            return Response::json($data);
        }
        return Response::json($data);
    }
    // client for finance balance management
    // *********************************************************************
   public function finanace_blance_client(Request $request)
{
    $fetchData = User::whereNot('id', auth()->user()->id); // Exclude the authenticated user

    // Check if there is a search term in the request
    if (isset($request->searchTerm)) {
        $search = $request->searchTerm;

        // If client_type is 'IB', check for combine_access 1, else 0 for 'trader'
        if ($request->client_type == 'IB') {
            $fetchData = $fetchData->where(function ($query) {
                $query->where('combine_access', 1) // Allow users with combine_access = 1 for IB
                      ->orWhere('client_type', 'IB'); // Allow IB users even if combine_access is 0
            });
        } elseif ($request->client_type == 'trader') {
            $fetchData = $fetchData->where(function ($query) {
                $query->where('combine_access', 1) // Allow combine_access = 1 users for both IB and trader
                      ->orWhere('combine_access', 0) // Allow combine_access = 0 users only for trader
                      ->orWhere('client_type', 'trader'); // Allow trader users irrespective of combine_access
            });
        }

        // If CombinedService is combined, force combine_access = 1 for IB
        if (CombinedService::is_combined() && $request->client_type === 'IB') {
            $fetchData = $fetchData->where('combine_access', 1);
        }

        // Apply search term filtering on email
        $fetchData = $fetchData->where('email', 'like', '%' . $search . '%')
                               ->limit(5)
                               ->get();
    } else {
        // If no search term, return empty data
        $fetchData = collect(); // Empty collection
    }

    // Prepare data for response
    $data = [];
    foreach ($fetchData as $value) {
        // Define title based on client_type
        if ($request->client_type === 'IB') {
            $title = 'IB';
        } else {
            $title = ucwords('Trader');
        }

        // Add user data to the response array
        $data[] = [
            'id'    => $value->id,
            'text'  => $value->email,
            'title' => '<strong>Type: </strong>' . $title,
            'name'  => ucwords($value->name)
        ];
    }

    // If no results were found, return a placeholder empty entry
    if (empty($data)) {
        $data[] = [
            'id'    => "",
            'text'  => "",
            'title' => '<strong>Type: </strong>' . ($request->client_type == 'IB' ? '4' : '0'),
            'name'  => ""
        ];
    }

    return Response::json($data);
}

    public function get_trader_ib(Request $request)
    {

        if (isset($request->searchTerm)) {
            $search = $request->searchTerm;
            $fetchData = User::whereIn('type', [0, CombinedService::type()])->whereNot('id', auth()->user()->id);
            // check crm is combine
            // if (CombinedService::is_combined()) {
            //     $fetchData = $fetchData->where('users.combine_access', 0);
            // }
            $fetchData = $fetchData->where('email', 'like', '%' . $search . '%')->limit(5)->get();
        }

        $data = array();
        foreach ($fetchData as $key => $value) {
            if ($value->type === 'ib') {
                $title = 'IB';
            } else {
                if ($value->combine_access == 1) {
                    $title = 'IB';
                } else {
                    $title = ucwords($value->type);
                }
            }
            $data[] = array(
                'id' => $value->id,
                'text' => $value->email,
                'title' => '<strong>Type: </strong>' . $title,
                'name' => ucwords($value->name)
            );
        }
        return Response::json($data);
    }
    // get ib for remove sub ib
    // references subIB
    public function references_user(Request $request)
    {

        if (!isset($request->searchTerm)) {
            $fetchData = User::whereIn('type', [0, 4])
                ->where('ib_id', $request->ib_id)
                ->join('ib', 'users.id', '=', 'ib.reference_id')
                ->select('users.*')
                ->limit(5)->get();
        } else {
            $search = $request->searchTerm;
            $fetchData = User::whereIn('type', [0, 4])
                ->where('ib_id', $request->ib_id)
                ->join('ib', 'users.id', '=', 'ib.reference_id')
                ->select('users.*')
                ->where('email', 'like', '%' . $search . '%')->limit(5)->get();
        }

        $data = array();
        foreach ($fetchData as $key => $value) {
            if ($value->type === 'ib') {
                $title = 'IB';
            } else {
                $title = ucwords($value->type);
            }
            $data[] = array(
                'id' => $value->id,
                'text' => $value->email,
                'title' => 'Type: ' . $title,
                'parent' => (IbService::has_parent($value->id)) ? 'Yes' : 'No'
            );
        }
        return Response::json($data);
    }

    public function sub_ib_user_select2(Request $request)
    {
        $fetchData  = [];
        if (isset($request->searchTerm)) {
            $search = $request->searchTerm;
            $fetchData = User::where('type', 4)->where('email', 'like', '%' . $search . '%')->get();
        }

        $data = array();
        foreach ($fetchData as $key => $value) {
            if ($value->type === 'ib') {
                $title = 'IB';
            } else {
                $title = ucwords($value->type);
            }
            $data[] = array(
                'id' => $value->id,
                'text' => $value->email,
                'title' => 'Type: ' . $title,
                'parent' => (IbService::has_parent($value->id)) ? 'Yes' : 'No'
            );
        }
        return Response::json($data);
    }

    public function trader_user_select2(Request $request)
    {

        $fetchData  = [];
        if (isset($request->searchTerm)) {
            $search = $request->searchTerm;
            $fetchData = User::where('type', 0)->where('email', 'like', '%' . $search . '%')->get();
        }

        $data = array();
        foreach ($fetchData as $key => $value) {
            if ($value->type === 'ib') {
                $title = 'IB';
            } else {
                $title = ucwords($value->type);
            }
            $data[] = array(
                'id' => $value->id,
                'text' => $value->email,
                'title' => 'Type: ' . $title,
                'parent' => (IbService::has_parent($value->id)) ? 'Yes' : 'No'
            );
        }
        return Response::json($data);
    }
    // filter client select2
    public function get_filter_client(Request $request)
    {
        $clientType = $request->clientType;
        if (CombinedService::is_combined()) {
            $clientType = 0;
        }
        $fetchData  = [];
        if (isset($request->searchTerm)) {
            $fetchData = User::where('type', $clientType)
                ->where('email', 'like', '%' . $request->searchTerm . '%');
            if (CombinedService::is_combined() && $request->clientType == 4) {
                $fetchData = $fetchData->where('combine_access', 1);
            }
            $fetchData = $fetchData->get();
        }

        $data = array();
        foreach ($fetchData as $key => $value) {
            if (CombinedService::is_combined()) {
                if ($request->clientType == 4 && $value->combine_access == 1) {
                    $title = 'IB';
                } else {
                    $title = ucwords($value->type);
                }
            } else {
                if ($value->type === 'ib') {
                    $title = 'IB';
                } else {
                    $title = ucwords($value->type);
                }
            }
            $data[] = array(
                'id' => $value->id,
                'text' => $value->email,
                'title' => 'Type: ' . $title,
                'name' => $value->name,
            );
        }
        return Response::json($data);
    }
    // removed trading account details
    public function removed_trading_account_details(Request $request)
    {
        $fetchData  = [];
        if (isset($request->searchTerm)) {
            $search = $request->searchTerm;
            $fetchData = TradingAccount::where('account_status', 0)->where('account_number', 'like', '%' . $search . '%')
                ->join('users', 'trading_accounts.user_id', 'users.id')
                ->get();
        }

        $data = array();
        foreach ($fetchData as $value) {
            $data[] = array(
                'id' => $value->account_number,
                'text' => $value->account_number,
                'name' => 'Old User: ' . $value->name,
                'account' => 'Account: ' . $value->account_number
            );
        }
        return Response::json($data);
    }
    // get country using select2
    public function get_country_select2(Request $request)
    {

        $fetchData  = [];
        if (isset($request->searchTerm)) {
            $search = $request->searchTerm;
            $fetchData = Country::where('name', 'like', '%' . $search . '%')->get();
        }

        $data = array();
        foreach ($fetchData as $key => $value) {

            $data[] = array(
                'id' => $value->id,
                'text' => $value->name,
            );
        }
        return Response::json($data);
    }
    public function get_trader_for_fund(Request $request)
    {

        if (isset($request->searchTerm)) {
            $search = $request->searchTerm;
            $fetchData = User::where('type', 0)->whereNot('id', auth()->user()->id);

            $fetchData = $fetchData->where('email', 'like', '%' . $search . '%')->limit(5)->get();
        }

        $data = array();
        foreach ($fetchData as $key => $value) {
            if ($value->type === 'ib') {
                $title = 'IB';
            } else {
                $title = ucwords($value->type);
            }
            $data[] = array(
                'id' => $value->id,
                'text' => $value->email,
                'title' => '<strong>Type: </strong>' . $title,
                'name' => ucwords($value->name)
            );
        }

        return Response::json($data);
    }
}
