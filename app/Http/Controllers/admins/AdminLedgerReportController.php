<?php

namespace App\Http\Controllers\admins;

use App\Http\Controllers\Controller;
use App\Models\admin\InternalTransfer;
use App\Models\admin\TraderDeposit;
use App\Models\Category;
use App\Models\Deposit;
use App\Models\ExternalFundTransfers;
use App\Models\IB;
use App\Models\ManagerUser;
use App\Models\TradingAccount;
use App\Models\User;
use App\Models\Withdraw;
use App\Services\AllFunctionService;
use App\Services\CombinedService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class AdminLedgerReportController extends Controller
{
    public function __construct()
    {
        if (request()->is('admin/report/individual-ledger-report')) {
            // system module control
            $this->middleware(AllFunctionService::access('reports', 'admin'));
            $this->middleware(AllFunctionService::access('individual_ledger_report', 'admin'));
        } elseif (request()->is('admin/report/ledger-report')) {
            // system module control
            $this->middleware(AllFunctionService::access('reports', 'admin'));
            $this->middleware(AllFunctionService::access('ledger_report', 'admin'));
        }
    }
    // START LEDGER REPORT  
    public function view(Request $request)
    {
        $op = $request->input('op');

        if ($op == "data_table") {
            return $this->ledgerTableReport($request);
        }
        // get last 12 month for option 
        $lastMontsOfyear = array();
        for ($i = 11; $i >= 0; $i--) {
            $month = Carbon::today()->startOfMonth()->subMonth($i)->format('m');
            $year = Carbon::today()->startOfMonth()->subMonth($i)->format('Y');
            $MfullName =  Carbon::today()->startOfMonth()->subMonth($i)->format('F');
            array_push($lastMontsOfyear, array(
                'title'  => $MfullName . '-' . $year,
                'value' => $year . '-' . $month,
            ));
        }
        return view('admins.reports.ledger-report', [
            'lastMontsOfyear' => array_reverse($lastMontsOfyear)
        ]);
    }
    public function ledgerTableReport($request)
    {
        try {

            $columns = ['name', 'email','client_type', 'date', 'ledger', 'status', 'amount', 'amount', 'remark'];
            $orderby = $columns[$request->order[0]['column']];
            // get deposit table data 
            $deposit = Deposit::select(
                DB::raw("'straight' as data_type"),
                DB::raw("'deposits' as table_name"),
                'deposits.id as tbid',
                'users.name as name',
                'users.email as email',
                'deposits.created_at as date',
                'deposits.transaction_type as ledger',
                'deposits.approved_status as status',
                'deposits.amount as amount',
                'deposits.account as remark',
                'deposits.wallet_type as client_type',
                'deposits.user_id as client_id'
            )->join('users', 'deposits.user_id', '=', 'users.id');
            // check if login manager
            if (auth()->user()->type === 'manager') {
                $users_id = ManagerUser::where('manager_id', auth()->user()->id)->select('user_id')->get()->pluck('user_id');
                $deposit = $deposit->whereIn('users.id', $users_id);
            }
            // filter by client type
            if ($request->client_type != "") {
                $deposit = $deposit->where('wallet_type', $request->client_type);
            }
            // get withdraw table data 
            $withdraw = Withdraw::select(
                DB::raw("'straight' as data_type"),
                DB::raw("'withdraws' as table_name"),
                'withdraws.id as tbid',
                'users.name as name',
                'users.email as email',
                'withdraws.created_at as date',
                'withdraws.transaction_type as ledger',
                'withdraws.approved_status as status',
                'withdraws.amount as amount',
                'bank_accounts.bank_name as remark',
                'withdraws.wallet_type as client_type',
                'withdraws.user_id as client_id'
            )->join('users', 'withdraws.user_id', '=', 'users.id')
                ->join('bank_accounts', 'withdraws.bank_account_id', '=', 'bank_accounts.id');
            // check if login is manager
            if (auth()->user()->type === 'manager') {
                $users_id = ManagerUser::where('manager_id', auth()->user()->id)->select('user_id')->get()->pluck('user_id');
                $withdraw = $withdraw->whereIn('users.id', $users_id);
            }
            // filter by client type
            if ($request->client_type != "") {
                $withdraw = $withdraw->where('wallet_type', $request->client_type);
            }
            // get internal_transfers table  data 
            $internal_transfers  = InternalTransfer::select(
                DB::raw("'straight' as data_type"),
                DB::raw("'internal_transfers' as table_name"),
                'internal_transfers.id as tbid',
                'users.name as name',
                'users.email as email',
                'internal_transfers.created_at as date',
                'internal_transfers.type as ledger',
                'internal_transfers.status as status',
                'internal_transfers.amount as amount',
                'internal_transfers.invoice_code as remark',

                DB::raw("'trader' as client_type"),
                'internal_transfers.user_id as client_id',

            )->join('users', 'internal_transfers.user_id', '=', 'users.id');
            // check if login is manager
            if (auth()->user()->type === 'manager') {
                $users_id = ManagerUser::where('manager_id', auth()->user()->id)->select('user_id')->get()->pluck('user_id');
                $internal_transfers = $internal_transfers->whereIn('users.id', $users_id);
            }
            // filter by client type
            if ($request->client_type != "" && $request->client_type === 'ib') {
                $internal_transfers = $internal_transfers->where('users.id', 0);
            }
            // get external_fund_transfers data 
            $external_fund_transfers = ExternalFundTransfers::select(
                DB::raw("'straight' as data_type"),
                DB::raw("'external_fund_transfers' as table_name"),
                'external_fund_transfers.id as tbid',
                'sender_info.name as name',
                'sender_info.email as email',
                'external_fund_transfers.created_at as date',
                'external_fund_transfers.type as ledger',
                'external_fund_transfers.status as status',
                'external_fund_transfers.amount as amount',
                'receiver_info.email as remark',
                'sender_wallet_type as client_type',
                'sender_id as client_id'
            )->join('users as sender_info', 'external_fund_transfers.sender_id', '=', 'sender_info.id')
                ->join('users as receiver_info', 'external_fund_transfers.receiver_id', '=', 'receiver_info.id');
            // check if login is manager
            if (auth()->user()->type === 'manager') {
                $users_id = ManagerUser::where('manager_id', auth()->user()->id)->select('user_id')->get();
                $external_fund_transfers = $external_fund_transfers->whereIn('sender_info.id', $users_id);
            }
            // filter by client type
            if ($request->client_type != "") {
                $external_fund_transfers = $external_fund_transfers->where('sender_wallet_type', $request->client_type);
            }
            // Reverse get external_fund_transfers data 
            $Revexternal_fund_transfers = ExternalFundTransfers::select(
                DB::raw("'reverse' as data_type"),
                DB::raw("'external_fund_transfers' as table_name"),
                'external_fund_transfers.id as tbid',
                'receiver_info.name as name',
                'receiver_info.email as email',
                'external_fund_transfers.created_at as date',
                'external_fund_transfers.type as ledger',
                'external_fund_transfers.status as status',
                'external_fund_transfers.amount as amount',
                'sender_info.email as remark',
                'receiver_wallet_type as client_type',
                'receiver_id as client_id',
            )->join('users as sender_info', 'external_fund_transfers.sender_id', '=', 'sender_info.id')
                ->join('users as receiver_info', 'external_fund_transfers.receiver_id', '=', 'receiver_info.id');
            // check if manager login
            if (auth()->user()->type === 'manager') {
                $users_id = ManagerUser::where('manager_id', auth()->user()->id)->select('user_id')->get();
                $Revexternal_fund_transfers = $Revexternal_fund_transfers->whereIn('sender_info.id', $users_id);
            }

            // filter by client type
            if ($request->client_type != "") {
                $Revexternal_fund_transfers = $Revexternal_fund_transfers->where('receiver_wallet_type', $request->client_type);
            }

            // data table code 
            $from = $request->input('from');
            $to = $request->input('to');
            $filter_weak = $request->input('filter_weak');
            $filter_month = $request->input('filter_month');
            // START custom filter 
            // filter  by date 
            if ($from != "") {
                $deposit = $deposit->whereDate('deposits.created_at', '>=', $from);
                $withdraw = $withdraw->whereDate('withdraws.created_at', '>=', $from);
                $internal_transfers = $internal_transfers->whereDate('internal_transfers.created_at', '>=', $from);
                $external_fund_transfers = $external_fund_transfers->whereDate('external_fund_transfers.created_at', '>=', $from);
                $Revexternal_fund_transfers = $Revexternal_fund_transfers->whereDate('external_fund_transfers.created_at', '>=', $from);
            }
            if ($to != "") {
                $deposit = $deposit->whereDate('deposits.created_at', '<=', $to);
                $withdraw = $withdraw->whereDate('withdraws.created_at', '<=', $to);


                $internal_transfers = $internal_transfers->whereDate('internal_transfers.created_at', '<=', $to);
                $external_fund_transfers = $external_fund_transfers->whereDate('external_fund_transfers.created_at', '<=', $to);
                $Revexternal_fund_transfers = $Revexternal_fund_transfers->whereDate('external_fund_transfers.created_at', '<=', $to);
            }
            // filter by weak 
            if ($filter_weak) {
                if ($filter_weak == 'thisWeak') { //this weak
                    $thisWeak = [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()];
                    $weak = $thisWeak;
                } else if ($filter_weak == 'lastWeak') { // last weak
                    $lastWeak = [Carbon::now()->subWeek()->startOfWeek(), Carbon::now()->subWeek()->endOfWeek()];
                    $weak = $lastWeak;
                } else if ($filter_weak == 'last2Weak') { // last 2 weak
                    $last2weak = [Carbon::now()->subWeek()->startOfWeek(), Carbon::now()->endOfWeek()];
                    $weak = $last2weak;
                }
                if ($weak) {
                    $deposit = $deposit->whereBetween('deposits.created_at', $weak);
                    $withdraw = $withdraw->whereBetween('withdraws.created_at', $weak);

                    $internal_transfers = $internal_transfers->whereBetween('internal_transfers.created_at', $weak);
                    $external_fund_transfers = $external_fund_transfers->whereBetween('external_fund_transfers.created_at', $weak);
                    $Revexternal_fund_transfers = $Revexternal_fund_transfers->whereBetween('external_fund_transfers.created_at', $weak);
                }
            }
            // filter by month  
            if ($filter_month != "") {
                $split = explode("-", $filter_month);
                $years = $split[0];
                $monts = $split[1];
                $deposit = $deposit->whereMonth('deposits.created_at', '=', $monts)->whereYear('deposits.created_at', '=', $years);
                $withdraw = $withdraw->whereMonth('withdraws.created_at', '=', $monts)->whereYear('withdraws.created_at', '=', $years);


                $internal_transfers = $internal_transfers->whereMonth('internal_transfers.created_at', '=', $monts)->whereYear('internal_transfers.created_at', '=', $years);
                $external_fund_transfers = $external_fund_transfers->whereMonth('external_fund_transfers.created_at', '=', $monts)->whereYear('external_fund_transfers.created_at', '=', $years);
                $Revexternal_fund_transfers = $Revexternal_fund_transfers->whereMonth('external_fund_transfers.created_at', '=', $monts)->whereYear('external_fund_transfers.created_at', '=', $years);
            }

            // filter by trader name / name / phone / country
            if ($request->info != "") {
                $trader_info = $request->info;
                $trader_id = User::where('type', 0)->where(function ($query) use ($trader_info) {
                    $query->where("users.name", 'LIKE', '%' . $trader_info . '%')
                        ->orWhere('users.email', 'LIKE', '%' . $trader_info . '%')
                        ->orWhere('users.phone', 'LIKE', '%' . $trader_info . '%')
                        ->orWhere('countries.name', 'LIKE', '%' . $trader_info . '%');
                })->leftJoin('user_descriptions', 'users.id', '=', 'user_descriptions.user_id')
                    ->leftJoin('countries', 'user_descriptions.country_id', '=', 'countries.id')
                    ->select('users.id as client_id')->get()->pluck('client_id');
                // start all filter by this client id
                $deposit = $deposit->whereIn('deposits.user_id', ($trader_id) ? $trader_id : []);
                $withdraw = $withdraw->whereIn('withdraws.user_id', ($trader_id) ? $trader_id : []);
                $internal_transfers = $internal_transfers->whereIn('internal_transfers.user_id', ($trader_id) ? $trader_id : []);
                $external_fund_transfers = $external_fund_transfers->whereIn('sender_id', ($trader_id) ? $trader_id : []);
                $Revexternal_fund_transfers = $Revexternal_fund_transfers->whereIn('receiver_id', ($trader_id) ? $trader_id : []);
            }
            // filter by ib info
            if ($request->ib_info != "") {
                $ib_info = $request->ib_info;
                $ib_id = User::where('type', CombinedService::type())->where(function ($query) use ($ib_info) {
                    $query->where("users.name", 'LIKE', '%' . $ib_info . '%')
                        ->orWhere('users.email', 'LIKE', '%' . $ib_info . '%')
                        ->orWhere('users.phone', 'LIKE', '%' . $ib_info . '%')
                        ->orWhere('countries.name', 'LIKE', '%' . $ib_info . '%');
                })->leftJoin('user_descriptions', 'users.id', '=', 'user_descriptions.user_id')
                    ->leftJoin('countries', 'user_descriptions.country_id', '=', 'countries.id')
                    ->select('users.id as client_id')->get()->pluck('client_id');
                // start all filter by this client id
                $deposit = $deposit->whereIn('deposits.user_id', ($ib_id) ? $ib_id : []);
                $withdraw = $withdraw->whereIn('withdraws.user_id', ($ib_id) ? $ib_id : []);
                $internal_transfers = $internal_transfers->whereIn('internal_transfers.user_id', ($ib_id) ? $ib_id : []);
                $external_fund_transfers = $external_fund_transfers->whereIn('sender_id', ($ib_id) ? $ib_id : []);
                $Revexternal_fund_transfers = $Revexternal_fund_transfers->whereIn('receiver_id', ($ib_id) ? $ib_id : []);
            }
            // filter by manager info
            if ($request->manager_info != "") {
                $manager_info = $request->manager_info;
                $manager_id = User::where('type', 5)->where(function ($query) use ($manager_info) {
                    $query->where("users.name", 'LIKE', '%' . $manager_info . '%')
                        ->orWhere('users.email', 'LIKE', '%' . $manager_info . '%')
                        ->orWhere('users.phone', 'LIKE', '%' . $manager_info . '%')
                        ->orWhere('countries.name', 'LIKE', '%' . $manager_info . '%');
                })->leftJoin('user_descriptions', 'users.id', '=', 'user_descriptions.user_id')
                    ->leftJoin('countries', 'user_descriptions.country_id', '=', 'countries.id')
                    ->select('users.id as client_id')->get()->pluck('client_id');
                // get manager user
                $manager_user = ManagerUser::whereIn('manager_id', ($manager_id) ? $manager_id : [])->select('user_id')->get()->pluck('user_id');
                // start all filter by this client id
                $deposit = $deposit->whereIn('deposits.user_id', ($manager_user) ? $manager_user : []);
                $withdraw = $withdraw->whereIn('withdraws.user_id', ($manager_user) ? $manager_user : []);
                $internal_transfers = $internal_transfers->whereIn('internal_transfers.user_id', ($manager_user) ? $manager_user : []);
                $external_fund_transfers = $external_fund_transfers->whereIn('sender_id', ($manager_user) ? $manager_user : []);
                $Revexternal_fund_transfers = $Revexternal_fund_transfers->whereIn('receiver_id', ($manager_user) ? $manager_user : []);
            }
            // filter by trading account
            if ($request->trading_account != "") {
                $trader = TradingAccount::where('account_number', $request->trading_account)->select('user_id')->first();
                $deposit = $deposit->whereIn('deposits.user_id', ($trader) ? $trader->user_id : []);
                $withdraw = $withdraw->whereIn('withdraws.user_id', ($trader) ? $trader->user_id : []);
                $internal_transfers = $internal_transfers->whereIn('internal_transfers.user_id', ($trader) ? $trader->user_id : []);
                $external_fund_transfers = $external_fund_transfers->whereIn('sender_id', ($trader) ? $trader->user_id : []);
                $Revexternal_fund_transfers = $Revexternal_fund_transfers->whereIn('receiver_id', ($trader) ? $trader->user_id : []);
            }
             //Filter By Amount
             if ($request->min != "") {
                $deposit = $deposit->where('deposits.amount','>=', $request->min);
                $withdraw = $withdraw->where('withdraws.amount','>=', $request->min);
                $internal_transfers = $internal_transfers->where('internal_transfers.amount','>=', $request->min);
                $external_fund_transfers = $external_fund_transfers->where('amount', '>=',$request->min);
                $Revexternal_fund_transfers = $Revexternal_fund_transfers->where('amount', '>=',$request->min);
            }
            if ($request->max != "") {
                $deposit = $deposit->where('deposits.amount','<=', $request->min);
                $withdraw = $withdraw->where('withdraws.amount','<=', $request->max);
                $internal_transfers = $internal_transfers->where('internal_transfers.amount','<=', $request->max);
                $external_fund_transfers = $external_fund_transfers->where('amount', '<=',$request->max);
                $Revexternal_fund_transfers = $Revexternal_fund_transfers->where('amount', '<=',$request->max);
            }
            $result = $deposit->unionAll($withdraw)->unionAll($internal_transfers)->unionAll($external_fund_transfers)->unionAll($Revexternal_fund_transfers);
            $count = $result->count();
            $result = $result->orderby($orderby, $request->order[0]['dir'])->skip($request->start)->take($request->length)->get();
            $data = array();
            $i = 0;
            foreach ($result as $value) {
                if ($value->name) {
                    $name = $value->name;
                } else {
                    $name = '-';
                }
                if ($value->email) {
                    $email = $value->email;
                } else {
                    $email = '-';
                }
                if ($value->date) {
                    $date = date('d M y', strtotime($value->date));
                } else {
                    $date = '-';
                }
                if ($value->ledger) {
                    $ledger = $value->ledger;
                } else {
                    $ledger  = '-';
                }
                if ($value->status) {
                    $status = $value->status;
                } else {
                    $status = '-';
                }
                if ($value->remark) {
                    $remark = $value->remark;
                } else {
                    $remark  = '-';
                }
                // change word from  shortcut
                if ($status == 'P') {
                    $status = '<span class="bg-light-warning badge badge-warning">Pending</span>';
                } elseif ($status == 'A') {
                    $status = '<span class="bg-light-success badge badge-success">Approved</span>';
                } elseif ($status == 'D') {
                    $status = '<span class="bg-light-danger badge badge-danger">Diclined</span>';
                }
                if ($ledger == 'atw') {
                    $ledger = 'Account to Wallet';
                } else if ($ledger == 'wta') {
                    $ledger = 'Wallet to account';
                } else if ($ledger == 'wtw') {
                    $ledger = 'Wallet to Wallet';
                } else if ($ledger == 'ata') {
                    $ledger = 'Account to Account';
                }

                //others change by table name 
                $debit_amount = '';
                $credit_amount = '';
                if ($value->table_name == 'deposits') {
                    $ledger .= ' (Deposit)';
                    $credit_amount  = $value->amount;
                }
                if ($value->table_name == 'withdraws') {
                    $ledger .= " (Withdraw)";
                    $debit_amount = $value->amount;
                }
                if ($value->table_name == 'balance_transfers') {
                    $debit_amount = $value->amount;
                }
                if ($value->table_name == 'internal_transfers') {
                    $ledger .= " (Internal Transfers)";
                    $debit_amount = $value->amount;
                }
                if ($value->table_name == 'external_fund_transfers') {
                    $ledger .= " (External Transfers)";
                    $debit_amount = $value->amount;
                }
                if ($value->table_name ==  'ib_transfers') {
                    $debit_amount = $value->amount;
                }
                if ($value->table_name == 'staff_transactions') {
                    if ($ledger == 'add') {
                        $credit_amount  = $value->amount;
                    } else if ($ledger == 'deduct') {
                        $debit_amount = $value->amount;
                    }
                    $ledger .= " (Staff Transactions)";
                }
                if ($value->table_name == 'wallet_up_downs') {
                    if ($ledger == 'add') {
                        $credit_amount  = $value->amount;
                    } else if ($ledger == 'deduct') {
                        $debit_amount = $value->amount;
                    }
                    $ledger .= " (Wallet Up Downs)";
                }

                //amount null 
                if ($debit_amount) {
                    $debit_amount = $debit_amount;
                } else {
                    $debit_amount = '-';
                }
                if ($credit_amount) {
                    $credit_amount = $credit_amount;
                } else {
                    $credit_amount = '-';
                }
                //reverse conditon  
                if ($value->data_type == 'reverse') {
                    $Temp_debit_amount = $debit_amount;
                    $Temp_credit_amount = $credit_amount;
                    $credit_amount = $Temp_debit_amount;
                    $debit_amount = $Temp_credit_amount;
                }

                $data[$i]['name'] = $name;
                $data[$i]['email'] = $email;
                $data[$i]['client_type'] = str_replace('Ib', 'IB', ucwords($value->client_type));
                $data[$i]['date'] =  $date;
                $data[$i]['ledger'] = ucwords(str_replace('ib', 'IB', str_replace('_', ' ', $ledger)));
                $data[$i]['status'] = $status;
                $data[$i]['credit_amount'] = $credit_amount;
                $data[$i]['debit_amount'] = $debit_amount;
                $data[$i]['remark'] = $remark;
                $i++;
            }

            return Response::json([
                'draw' => $request->draw,
                'recordsTotal' => $count,
                'recordsFiltered' => $count,
                'data' => $data
            ]);
        } catch (\Throwable $th) {
            // throw $th;
            return Response::json([
                'draw' => $request->draw,
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => []
            ]);
        }
    }
    // END LEDGER REPORT 
    // START INDIVIDUAL LEDGER REPORT  
    // END INDIVIDUAL LEDGER REPORT 
    public function individual_ledger_view(Request $request)
    {
        $op = $request->input('op');

        if ($op == "data_table") {
            return $this->individualReportDT($request);
        }
        $category = Category::where('status', 1)->select('name', 'id')->get();
        return view(
            'admins.reports.ledger-report-individual',
            ['category' => $category]
        );
    }
    public function individualReportDT($request)
    {
        try {

            $columns = ['users.name', 'users.email', 'users.phone', 'users.type', 'users.active_status'];
            $orderby = $columns[$request->order[0]['column']];
            $result = User::select(
                'users.id',
                'users.name',
                'users.email',
                'users.phone',
                'users.type',
                'users.active_status'
            )
                ->leftJoin('user_descriptions', 'users.id', '=', 'user_descriptions.user_id')
                ->leftJoin('countries', 'user_descriptions.country_id', '=', 'countries.id')
                ->whereIn('type', [0, CombinedService::type()]);
            // check if login is manager
            if (auth()->user()->type === 'manager') {
                $users_id = ManagerUser::where('manager_id', auth()->user()->id)->select('user_id')->get()->pluck('user_id');
                $result = $result->whereIn('users.id', $users_id);
            }
            // filter by kyc verification status
            if ($request->kyc_status != "") {
                $result = $result->where('users.kyc_status', $request->kyc_status);
            }
            // filter by active status
            if ($request->active_status != "") {
                $result = $result->where('users.active_status', $request->active_status);
            }
            // filter by client type

            if ($request->client_type != "") {
                if (strtolower($request->client_type) === 'ib') {
                    if (CombinedService::is_combined()) {
                        $result = $result->where('combine_access', 1);
                    } else {
                        $result = $result->where('type', 4);
                    }
                } else {

                    $result = $result->where('type', 0);
                }
            }
            // filter by category
            if ($request->category != "") {
                $result = $result->where('users.category_id', $request->category);
            }
            //Filter By Trader Name / Email
            if ($request->trader_info != "") {
                $trader_info = $request->trader_info;
                $result = $result->where(function ($query) use ($trader_info) {
                    $query->where("users.name", 'LIKE', '%' . $trader_info . '%')
                        ->orWhere('users.email', 'LIKE', '%' . $trader_info . '%')
                        ->orWhere('users.phone', 'LIKE', '%' . $trader_info . '%')
                        ->orWhere('countries.name', 'LIKE', '%' . $trader_info . '%');
                });
            }
            // filter by trading account
            if ($request->trading_account != "") {
                $trader = TradingAccount::where('account_number', $request->trading_account)->select('user_id')->first();
                $result = $result->where('users.id', ($trader) ? $trader->user_id : '');
            }
            // filter by IB info
            if ($request->ib_info != "") {
                $ib_info = $request->ib_info;
                $result = $result->where(function ($query) use ($ib_info) {
                    $query->where("users.name", 'LIKE', '%' . $ib_info . '%')
                        ->orWhere('users.email', 'LIKE', '%' . $ib_info . '%')
                        ->orWhere('users.phone', 'LIKE', '%' . $ib_info . '%')
                        ->orWhere('countries.name', 'LIKE', '%' . $ib_info . '%');
                });
                if (CombinedService::is_combined()) {
                    $result = $result->where('combine_access', 1);
                } else {
                    $result = $result->where('type', 4);
                }
            }
            //Filter By Manager Name / Email
            if ($request->manager_info != "") {
                $manager_info = $request->manager_info;
                $manager_id = User::select('users.id as manager_id')
                    ->where('type', 5)
                    ->where(function ($query) use ($manager_info) {
                        $query->where("users.name", 'LIKE', '%' . $manager_info . '%')
                            ->orWhere('users.email', 'LIKE', '%' . $manager_info . '%')
                            ->orWhere('users.phone', 'LIKE', '%' . $manager_info . '%');
                    })
                    ->get()->pluck('manager_id');
                $user_id = ManagerUser::whereIn('manager_id', ($manager_id) ? $manager_id : [])->get()->pluck('user_id');
                $result = $result->whereIn('users.id', ($user_id) ? $user_id : []);
            }
            // filter by joining date
            // filter by date from
            if ($request->from != "") {
                $result = $result->whereDate('users.created_at', '>=', date('Y-m-d', strtotime($request->from)));
            }
            // filter by date to
            if ($request->to != "") {
                $result = $result->whereDate('users.created_at', '<=', date('Y-m-d', strtotime($request->to)));
            }

            // filter search script end
            $count = $result->count();
            $result = $result->orderby($orderby, $request->order[0]['dir'])->skip($request->start)->take($request->length)->get();
            $data = array();
            $i = 0;
            foreach ($result as $m_user) {
                $status = '';
                $status_color = '';
                if ($m_user->active_status == 1) {
                    $status = '<span class="bg-light-success badge badge-success">Active</span>';
                    $status_color = 'text-success';
                } else {

                    $status = '<span class="bg-light-danger badge badge-danger">Block</span>';
                    $status_color = 'text-danger';
                }
                // client type

                $data[$i]['name'] = '<a href="#" data-user_name="' . $m_user->name . '" data-user_id="' . $m_user->id . '"class="dt-description  justify-content-start"><span class="w"> <i class="plus-minus" data-feather="plus"></i> </span> <span class="' . $status_color . '">' . $m_user->name . '</span></a>';
                $data[$i]['email'] = $m_user->email;
                $data[$i]['phone'] = $m_user->phone;
                $data[$i]['user_type'] = ($m_user->type == 'trader') ? '<span class="bg-success badge badge-success">Trader</span>' : '<span class="bg-warning badge badge-warning">IB</span>';
                $data[$i]['status'] = $status;
                $i++;
            }

            return Response::json([
                "draw" => $request->draw,
                "recordsTotal" => $count,
                "recordsFiltered" => $count,
                "data" => $data,
            ]);
        } catch (\Throwable $th) {
            // throw $th;
            return Response::json([
                "draw" => $request->draw,
                "recordsTotal" => 0,
                "recordsFiltered" => 0,
                "data" => [],
            ]);
        }
    }

    public function individual_description()
    {
        $description = '<tr class="description" style="display:none">
            <td colspan="7">
                <div class="details-section-dark border-start-3 border-start-primary p-2" >
                <div class="d-flex justify-content-between align-items-center">
                    <div class="details-text">
                       Ledger Details
                    </div>
                    <div class="btn-exports" style="width:200px">
                        <select data-placeholder="Select a state..." class="single-export select2-icons form-select"
                            >
                            <option value="download" data-icon="download" selected>Export</option>
                            <option value="csv" data-icon="file">CSV</option>   
                            <option value="excel" data-icon="file">Excel</option>
                        </select>
                    </div>
                    </div>


                    <table id="deposit-details" class="deposit-details table dt-inner-table-dark">
                        <thead>
                            <tr>
                                <th class="text-truncate">date</th>
                                <th class="text-truncate">ledger</th>
                                <th class="text-truncate">transactin status</th>
                                <th class="text-truncate">wallet type</th>
                                <th class="text-truncate">credit amount</th>
                                <th class="text-truncate">debit amount</th>
                                <th class="text-truncate">remark</th>
                            </tr>
                        </thead>
                        
                    </table>
                </div>
            </td>
            <td class="d-none">&nbsp;</td>
            <td class="d-none">&nbsp;</td>
            <td class="d-none">&nbsp;</td>
            <td class="d-none">&nbsp;</td>
        </tr>';
        $data = [
            'status' => true,
            'description' => $description
        ];
        return Response::json($data);
    }


    public function individual_inner_description(Request $request, $user_id)
    {

        try {
            $columns = ['date', 'ledger', 'client_type','status', 'amount', 'amount', 'remark'];
            $orderby = $columns[$request->order[0]['column']];
            // get deposit table data 
            $deposit = Deposit::select(
                DB::raw("'straight' as data_type"),
                DB::raw("'deposits' as table_name"),
                'deposits.id as tbid',
                'deposits.created_at as date',
                'deposits.transaction_type as ledger',
                'deposits.approved_status as status',
                'deposits.amount as amount',
                'deposits.account as remark',
                'deposits.wallet_type as client_type'
            )->where('deposits.user_id', $user_id);
            // get withdraw table data 
            $withdraw = Withdraw::where('withdraws.user_id', $user_id)->select(
                DB::raw("'straight' as data_type"),
                DB::raw("'withdraws' as table_name"),
                'withdraws.id as tbid',
                'withdraws.created_at as date',
                'withdraws.transaction_type as ledger',
                'withdraws.approved_status as status',
                'withdraws.amount as amount',
                'bank_accounts.bank_name as remark',
                'withdraws.wallet_type as client_type'
            )->join('bank_accounts', 'withdraws.bank_account_id', '=', 'bank_accounts.id');

            // get internal_transfers table  data 
            $internal_transfers  = InternalTransfer::where('internal_transfers.user_id', $user_id)->select(
                DB::raw("'straight' as data_type"),
                DB::raw("'internal_transfers' as table_name"),
                'internal_transfers.id as tbid',
                'internal_transfers.created_at as date',
                'internal_transfers.type as ledger',
                'internal_transfers.status as status',
                'internal_transfers.amount as amount',
                'internal_transfers.invoice_code as remark',
                DB::raw("'trader' as client_type")
            );
            // get external_fund_transfers data 
            $external_fund_transfers = ExternalFundTransfers::where('external_fund_transfers.sender_id', $user_id)->select(
                DB::raw("'straight' as data_type"),
                DB::raw("'external_fund_transfers' as table_name"),
                'external_fund_transfers.id as tbid',
                'external_fund_transfers.created_at as date',
                'external_fund_transfers.type as ledger',
                'external_fund_transfers.status as status',
                'external_fund_transfers.amount as amount',
                'receiver_info.email as remark',
                'sender_wallet_type as client_type'
            )->join('users as receiver_info', 'external_fund_transfers.receiver_id', '=', 'receiver_info.id');
            // get Reverse external_fund_transfers data 
            $Revexternal_fund_transfers = ExternalFundTransfers::where('external_fund_transfers.receiver_id', $user_id)->select(
                DB::raw("'reverse' as data_type"),
                DB::raw("'external_fund_transfers' as table_name"),
                'external_fund_transfers.id as tbid',
                'external_fund_transfers.created_at as date',
                'external_fund_transfers.type as ledger',
                'external_fund_transfers.status as status',
                'external_fund_transfers.amount as amount',
                'sender_info.email as remark',
                'receiver_wallet_type as client_type'
            )->join('users as sender_info', 'external_fund_transfers.sender_id', '=', 'sender_info.id');

            //get wallet_up_downs data 

            $result = $deposit->unionAll($withdraw)->unionAll($internal_transfers)
                ->unionAll($external_fund_transfers)->unionAll($Revexternal_fund_transfers);
            $count = $result->count();
            $result = $result->orderby($orderby, $request->order[0]['dir'])->skip($request->start)->take($request->length)->get();
            $data = array();
            $i = 0;
            foreach ($result as $single) {
                if ($single->date) {
                    $date = '<span class="text-truncate">' . date('d M y h:i:s', strtotime($single->date)) . '</span>';
                } else {
                    $date = '-';
                }
                if ($single->ledger) {
                    $ledger = $single->ledger;
                } else {
                    $ledger  = '-';
                }
                if ($single->status) {
                    $status = $single->status;
                } else {
                    $status = '-';
                }
                if ($single->remark) {
                    $remark = $single->remark;
                } else {
                    $remark  = '-';
                }

                // change word from  shortcut
                if ($status == 'P') {
                    $status = '<span class="bg-light-warning badge badge-warning">Pending</span>';
                } elseif ($status == 'A') {
                    $status = '<span class="bg-light-success badge badge-success">Approved</span>';
                } elseif ($status == 'D') {
                    $status = '<span class="bg-light-danger badge badge-danger">Declined</span>';
                }
                if ($ledger == 'atw') {
                    $ledger = 'Account to Wallet';
                } else if ($ledger == 'wta') {
                    $ledger = 'Wallet to account';
                } else if ($ledger == 'wtw') {
                    $ledger = 'Wallet to Wallet';
                } else if ($ledger == 'ata') {
                    $ledger = 'Account to Account';
                }

                //others change by table name 
                $debit_amount = '';
                $credit_amount = '';
                if ($single->table_name == 'deposits') {
                    $ledger .= ' (Deposit)';
                    $credit_amount  = $single->amount;
                }
                if ($single->table_name == 'withdraws') {
                    $ledger .= " (Withdraw)";
                    $debit_amount = $single->amount;
                }
                if ($single->table_name == 'balance_transfers') {
                    $debit_amount = $single->amount;
                }
                if ($single->table_name == 'internal_transfers') {
                    $ledger .= " (Internal Transfers)";
                    $debit_amount = $single->amount;
                }
                if ($single->table_name == 'external_fund_transfers') {
                    $ledger .= " (External Transfers)";
                    $debit_amount = $single->amount;
                }
                if ($single->table_name ==  'ib_transfers') {
                    $debit_amount = $single->amount;
                }
                if ($single->table_name == 'staff_transactions') {
                    if ($ledger == 'add') {
                        $credit_amount  = $single->amount;
                    } else if ($ledger == 'deduct') {
                        $debit_amount = $single->amount;
                    }
                    $ledger .= " (Staff Transactions)";
                }
                if ($single->table_name == 'wallet_up_downs') {
                    if ($ledger == 'add') {
                        $credit_amount  = $single->amount;
                    } else if ($ledger == 'deduct') {
                        $debit_amount = $single->amount;
                    }
                    $ledger .= " (Wallet Up Downs)";
                }

                //amount null 
                if ($debit_amount) {
                    $debit_amount = $debit_amount;
                } else {
                    $debit_amount = '-';
                }
                if ($credit_amount) {
                    $credit_amount = $credit_amount;
                } else {
                    $credit_amount = '-';
                }

                //reverse conditon  
                if ($single->data_type == 'reverse') {
                    $Temp_debit_amount = $debit_amount;
                    $Temp_credit_amount = $credit_amount;
                    $credit_amount = $Temp_debit_amount;
                    $debit_amount = $Temp_credit_amount;
                }

                $data[$i]["date"]               = $date;
                $data[$i]["ledger"]             = ucwords(str_replace('ib', 'IB', str_replace('_', ' ', $ledger)));
                $data[$i]["transactin_status"]  = $status;
                $data[$i]["wallet_type"]  = str_replace('Ib', 'IB', ucwords($single->client_type));;
                $data[$i]["credit_amount"]      = $credit_amount;
                $data[$i]["debit_amount"]       = $debit_amount;
                $data[$i]["remark"]             = $remark;
                $i++;
            }

            return Response::json([
                'draw' => $request->draw,
                'recordsTotal' => $count,
                'recordsFiltered' => $count,
                "data" => $data
            ]);
        } catch (\Throwable $th) {
            throw $th;

            return Response::json([
                'draw' => $request->draw,
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                "data" => []
            ]);
        }
    }
}
