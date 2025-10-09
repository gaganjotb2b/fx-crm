<?php

namespace App\Http\Controllers\admins;

use App\Http\Controllers\Controller;
use App\Mail\ApproveRequest;
use App\Mail\DeclineRequest;
use App\Mail\depositAmountUpdate;
use App\Mail\UserKycUpdate;
use App\Models\admin\InternalTransfer;
use App\Models\admin\SystemConfig;
use App\Models\admin\TraderDeposit;
use App\Models\AdminBank;
use App\Models\Country;
use App\Models\Deposit;
use App\Models\IB;
use App\Models\KycVerification;
use App\Models\Manager;
use App\Models\ManagerUser;
use App\Models\OtherTransaction;
use App\Models\SystemNotification;
use App\Models\TradingAccount;
use App\Models\User;
use App\Services\AllFunctionService;
use App\Services\api\FileApiService;
use App\Services\balance\BalanceSheetService;
use App\Services\BankService;
use App\Services\CombinedService;
use App\Services\EmailService;
use App\Services\IbService;
use App\Services\MailNotificationService;
use App\Services\MT4API;
use App\Services\Mt5WebApi;
use App\Services\systems\AdminLogService;
use Carbon\Carbon;
use GeoIp2\Record\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use InternalIterator;
use App\Services\systems\VersionControllService;

class DepositRequestController extends Controller
{
    public function __construct()
    {
        $this->middleware(["role:deposit request report"]);
        $this->middleware(["role:manage request"]);
        // system module control
        $this->middleware(AllFunctionService::access('manage_request', 'admin'));
        $this->middleware(AllFunctionService::access('deposit_request', 'admin'));
    }
    public function depositRequest(Request $request)
    {
        $op = $request->input('op');
        // update system notification table
        if (isset($request->not)) {
            $update = SystemNotification::where('id', $request->not)
                ->update([
                    'status' => 'read',
                    'admin_log' => AdminLogService::admin_log(),
                ]);
        }
        if ($op == "data_table") {
            return $this->depositRequestReport($request);
        }
        $crmVarsion = VersionControllService::check_version();
        $countries = Country::all();
        $deposit = Deposit::select('transaction_type')->distinct()->get();
        return view('admins.reports.deposit-request-report', ['deposit' => $deposit, 'countries' => $countries, 'varsion' => $crmVarsion]);
    }

    public function depositRequestReport($request)
    {
        try {

            $columns = ['name', 'email', 'transaction_type', 'type', 'approved_status', 'deposits.created_by', 'created_at', 'amount',];
            $orderby = $columns[$request->order[0]['column']];

            $result = TraderDeposit::select(
                'deposits.user_id as u_id',
                'deposits.id as deposit_id',
                'deposits.transaction_type',
                'deposits.user_id',
                'deposits.other_transaction_id',
                'deposits.approved_status',
                'deposits.wallet_type',
                'deposits.created_at',
                'deposits.amount',
                'deposits.created_by',
                'users.name',
                'users.email',
                'users.type',
                'users.email_verified_at'
                // 'kyc_verifications.status as kyc_status'
            )
                ->join('users', 'deposits.user_id', '=', 'users.id')
                ->whereIn('users.type', [0, CombinedService::type()]);
            // check crm is combined

            /*<-------filter search script start here------------->*/
            //    filter by account/desk manager
            if (strtolower(auth()->user()->type) === 'manager') {
                $manager_user = ManagerUser::where('manager_id', auth()->user()->id)->select('user_id')->get()->pluck('user_id');
                $result = $result->whereIn('user_id', $manager_user);
            }
            // check if have ID from
            // if this page redirect from notification
            if ($request->table_id != "") {
                $result = $result->where('deposits.id', $request->table_id);
            }
            // filter by transaction type
            if ($request->transaction_type != "") {
                $result = $result->where('transaction_type', '=', $request->transaction_type);
            }
            // filter by kyc verification status
            if ($request->verification_status != "") {
                $result = $result->where('users.kyc_status', $request->verification_status);
            }

            // filter by approved status
            if ($request->status != "") {
                $result = $result->where('approved_status', $request->status);
            }
            // filter by client type
            if ($request->client_type != "") {
                $result = $result->where('deposits.wallet_type', $request->client_type);
            }
            //Filter By Trader Name / Email /Phone
            if ($request->trader_info != "") {
                $trader_info = $request->trader_info;
                $user_id = User::select('id')->where(function ($query) use ($trader_info) {
                    $query->where('name', 'LIKE', '%' . $trader_info . '%')
                        ->orWhere('email', 'LIKE', '%' . $trader_info . '%')
                        ->orWhere('phone', 'LIKE', '%' . $trader_info . '%');
                })->get()->pluck('id');
                $result = $result->whereIn('deposits.user_id', $user_id);
            }
            //Filter By IB Name / Email /Phone
            if ($request->ib_info != "") {
                $ib = $request->ib_info;
                $user_id = User::select('id')->where('type', 4)->where(function ($query) use ($ib) {
                    $query->where('name', 'LIKE', '%' . $ib . '%')
                        ->orWhere('email', 'LIKE', '%' . $ib . '%')
                        ->orWhere('phone', $ib);
                })->get()->pluck('id');
                // get ib id
                $reference_id = IB::whereIn('ib_id', $user_id)->select('reference_id')->get()->pluck('reference_id');
                $result = $result->whereIn('deposits.user_id', $reference_id);
            }

            //Filter By Country
            if ($request->country != "") {
                $user_country = $request->country;
                $user_id = User::select('countries.name')->where(function ($query) use ($user_country) {
                    $query->where('countries.name', 'LIKE', '%' . $user_country . '%');
                })->leftJoin('user_descriptions', 'users.id', '=', 'user_descriptions.user_id')
                    ->leftJoin('countries', 'user_descriptions.country_id', '=', 'countries.id')
                    ->select('users.id as user_id')->get()->pluck('user_id');
                $result = $result->whereIn('users.id', $user_id);
            }



            // filter by manager info
            if ($request->manager_info) {
                $maanger_info = $request->manager_info;
                $managers = User::where(function ($q) use ($maanger_info) {
                    $q->where('users.email', 'like', '%' . $maanger_info . '%')
                        ->orWhere('users.name', 'like', '%' . $maanger_info . '%')
                        ->orWhere('users.phone', 'like', '%' . $maanger_info . '%');
                })->select('id')->get()->pluck('id');
                $manager_user = ManagerUser::whereIn('manager_id', $managers)->select('user_id')->get()->pluck('user_id');
                $result = $result->where(function ($q) use ($manager_user) {
                    $q->whereIn('user_id', $manager_user);
                });
            }
            // filter by trading account
            if ($request->trading_account != "") {
                $trading_account = TradingAccount::where('account_number', $request->trading_account)->select('user_id')->first();
                $user_id = $trading_account->user_id;
                $result = $result->where(function ($query) use ($user_id) {
                    $query->where('user_id', $user_id);
                });
            }
            // filter by min/max/amount
            // filter by min amount
            if ($request->min != "") {
                $result = $result->where("amount", '>=', $request->min);
            }
            // filter by max amount
            if ($request->max != "") {
                $result = $result->where("amount", '<=', $request->max);
            }
            // filter by date range
            // filter by date from
            if ($request->from != "") {
                $result = $result->whereDate("deposits.created_at", '>=', $request->from);
            }
            // filter by date to
            if ($request->to != "") {
                $result = $result->whereDate("deposits.created_at", '<=', $request->to);
            }
            // filter by client type
            if ($request->client_type != "") {
                $result = $result->where('users.type', '=', $request->client_type);
            }

            /*<-------filter search script End here------------->*/
            $total_amount = $result->sum('amount');
            $count = $result->count();
            $result = $result->orderby($orderby, $request->order[0]['dir'])->skip($request->start)->take($request->length)->get();
            $data = array();
            $i = 0;

            foreach ($result as $user) {
                if ($user->approved_status == 'P') {
                    $status = '<span class="bg-light-warning badge badge-light-warning">Pending</span>';
                } elseif ($user->approved_status == 'A') {
                    $status = '<span class="bg-light-success badge badge-light-success">Approved</span>';
                } elseif ($user->approved_status == 'D') {
                    $status = '<span class="bg-light-danger badge badge-light-danger">Declined</span>';
                }
                // created by badges
                if (strtolower($user->created_by) === 'system') {
                    $created_by = '<span class="bg-success badge badge-success">' . ucwords(str_replace('_', ' ', $user->created_by)) . '</span>';
                } elseif (strtolower($user->created_by) === 'admin') {
                    $created_by = '<span class="bg-warning badge badge-warning">' . ucwords(str_replace('_', ' ', $user->created_by)) . '</span>';
                } elseif (strtolower($user->created_by) === 'manager') {
                    $created_by = '<span class="bg-seconday badge badge-seconday">' . ucwords(str_replace('_', ' ', $user->created_by)) . '</span>';
                } else {
                    $created_by = '<span class="bg-danger badge badge-danger">' . ucwords(str_replace('_', ' ', $user->created_by)) . '</span>';
                }
                // datatable data formating
                $data[] = [
                    'name' => '<a href="#" data-id="' . $user->deposit_id . '" class="dt-description d-flex justify-content-between">
                                        <span class="w"> 
                                            <i class="plus-minus" data-feather="plus"></i> 
                                        </span> 
                                        <span>' .  ucfirst($user->name) . '</span>
                                    </a>',
                    'email' => $user->email,
                    'method' => ucwords($user->transaction_type),
                    "client_type" => $user->wallet_type,
                    'status' => $status,
                    'created_by' => $created_by,

                    'request_date' => date('d M y, h:i A', strtotime($user->created_at)),
                    'amount' => '$' . $user->amount,
                ];
            }

            return Response::json([
                'draw' => $request->draw,
                'recordsTotal' => $count,
                'recordsFiltered' => $count,
                'total_amount' => round($total_amount, 2),
                'data' => $data
            ]);
        } catch (\Throwable $th) {
            // throw $th;
            return Response::json([
                'draw' => $request->draw,
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'total_amount' => 0,
                'data' => []
            ]);
        }
    }

    // deposit request description
    public function depositRequestDescription(Request $request, $id)
    {
        $deposit = Deposit::where('deposits.id', $id)
            ->select(
                'deposits.transaction_type',
                'deposits.bank_id',
                'deposits.local_currency',
                'deposits.currency',
                'deposits.amount',
                'deposits.deposit_option',
                'deposits.account',
                'deposits.internal_transfer',
                'deposits.invoice_id',
                'deposits.transaction_id',
                'deposits.approved_by',
                'deposits.approved_date',
                'deposits.admin_log',
                'deposits.approved_status',
                'other_transactions.block_chain',
                'other_transactions.crypto_type',
                'other_transactions.crypto_amount'
            )
            ->leftJoin('other_transactions', 'deposits.other_transaction_id', '=', 'other_transactions.id')
            ->first();
        $buttons = '';
        if ($deposit->approved_status === 'P' && $deposit->transaction_type != 'b2bin') {
            $auth_user = User::find(auth()->user()->id);
            if ($auth_user->hasDirectPermission('edit deposit request')) {
                $buttons .= '<p class="details-text" style="float:right;">
                <button   data-type="button"  class="btn btn-secondary edit-amount-button waves-effect waves-float waves-light"  data-loading="processing..." data-bs-toggle="modal" data-bs-target="#amount_edit"  data-id="' . $id . '"  onclick="view_amount(this)">Edit</button>
                <button   data-type="button"  class="btn btn-primary waves-effect waves-float waves-light btn-transaction-approve"  data-loading="processing..."   data-id="' . $id . '" >Approve</button>
                <button   type="button"  class="btn btn-danger btn-transaction-declined waves-effect waves-float waves-light"  data-loading="processing..."   data-id="' . $id . '"  >Decline</button>
                <button   data-type="button"  class="btn btn-success waves-effect waves-float waves-light identify"  data-loading="processing..." data-bs-toggle="modal" data-bs-target="#editUser" data-modal_name="' . $deposit->transaction_type . '" data-id="' . $id . '">Identify</button>

            </p>';
            }
        } else {
            if ($deposit->transaction_type != 'b2bin') {
                $buttons .= '<p class="details-text" style="float:right;">
                        <button   data-type="button"  class="btn btn-success waves-effect waves-float waves-light identify"  data-loading="processing..." data-bs-toggle="modal" data-bs-target="#editUser"   data-modal_name="' . $deposit->transaction_type . '" data-id="' . $id . '" ">Identify</button>
                    </p>';
            }
        }



        $deposit_details_th = "";
        $deposit_details_td = "";
        if (strtolower($deposit->transaction_type) === 'bank' && $deposit->created_by == "system") {
            $bank_info = AdminBank::select()->where('id', $deposit->bank_id)->first();
            $multi_cur_visibility = BankService::is_multicurrency('all') ? "" : "d-none";
            $deposit_details_th .= '
                <th>Amount in USD</th>
                <th class="' . $multi_cur_visibility . '">Amount in ' . $deposit->currency . '</th>
                <th>Bank Name</th>
                <th>Bank AC Name</th>
                <th>Bank AC No</th>
                <th>Bank Swift Code</th>
                <th>Bank IBAN</th>
                <th>Bank Country</th>';
            $deposit_details_td .= '
                <td> $' . $deposit->amount . '</td>
                <td class="' . $multi_cur_visibility . '">' . $deposit->local_currency . '</td>
                <td>' . ($bank_info->bank_name ? $bank_info->bank_name : "---") . '</td>
                <td>' . ($bank_info->account_name ? $bank_info->account_name : "---") . '</td>
                <td>' . ($bank_info->account_number ? $bank_info->account_number : "---") . '</td>
                <td>' . ($bank_info->swift_code ? $bank_info->swift_code : "---") . '</td>
                <td>' . ($bank_info->ifsc_code ? $bank_info->ifsc_code : "---") . '</td>
                <td>' . ($bank_info->bank_country ? $bank_info->bank_country : "---") . '</td>';
        }
        // crypto transactions
        else if (strtolower($deposit->transaction_type) === 'crypto') {
            $hash_type = ($deposit->block_chain) ? $deposit->block_chain : '';
            if ($hash_type === "ERC20"); {
                $url = "https://etherscan.io/tx/" . $deposit->transaction_id;
            }
            if ($hash_type === "TRC20") {
                $url = "https://tronscan.org/#/searcherror/" . $deposit->transaction_id;
            }

            $deposit_details_th .= '
                <th>Amount Request</th>
                <th>Crypto Type</th>
                <th>Transaction Hash</th>
                <th>Crypto Amount</th>';
            $deposit_details_td .= '
                <th> $' . $deposit->amount . '</th>
                <th>' . $deposit->crypto_type . '</th>
                <th><a href="' . $url . '" target="_blank">' . $deposit->transaction_id . '</a></th>
                <th>' . $deposit->crypto_amount . '</th>';
        }
        // perfect money deposit
        else if ($deposit->transaction_type === 'Perfect Money') {
            $deposit_details_th .= '
                <th>Amount Request</th>
                <th>Transaction ID</th>
                <th>Order ID</th>';
            $deposit_details_td .= '
                <th> $' . $deposit->amount . '</th>
                <th>' . $deposit->transaction_id . '</th>
                <th>' . $deposit->order_id . '</th>';
        }
        // help2pay deposit
        else if (strtolower($deposit->transaction_type) === 'help2pay') {
            $deposit_details_th .= '
                <th>Amount Request</th>
                <th>Help2Pay ID</th>
                <th>IDR Amount</th>';
            $deposit_details_td .= '
                <th> $' . $deposit->amount . '</th>
                <th>' . $deposit->order_id . '</th>
                <th>' . $deposit->local_currency . '(' . $deposit->currency . ')' . '</th>';
        }
        // paypal deposit
        else if (($deposit->transaction_type) === 'PayPal') {
            $deposit_details_th .= '
                <th>Amount Request</th>
                <th>Transaction ID</th>
                <th>Invoice ID</th>';
            $deposit_details_td .= '
                <th> $' . $deposit->amount . '</th>
                <th>' . $deposit->transaction_id . '</th>
                <th>' . $deposit->invoice_id . '</th>';
        }
        // other deposit all
        else {
            $approved_by = User::find($deposit->approved_by);
            if (isset($approved_by)) {
                $name = ($approved_by->name) ? $approved_by->name : '---';
            } else {
                $name = '---';
            }
        
            $deposit_details_th .= '
                <th>Amount Request</th>
                <th>Charge Amount</th>
                <th>Approved By</th>
                <th>Note</th>';
                
            $deposit_details_td .= '
                <th> $' . $deposit->amount . '</th>
                <th> $' . ($deposit->charge ? $deposit->charge : 0) . '</th>
                <th>' . $name . '</th>
                <th>' . (($deposit->note) ? $deposit->note : '---') . '</th>';
        }

        //===========================Admin Information condition=================================////
        $tbl_admin_data = $tbl_admin = '';
        $approved_by = "";
        if ($deposit->approved_status === 'A' || $deposit->approved_status === 'D') {
            $approved_by = ($deposit->approved_status) == 'A' ? "Approved By" : "Declined By";
            $admin_info = User::select('name', 'email')->where('id', $deposit->approved_by)->first();
            $admin_json_data = json_decode($deposit->admin_log);
            $ip = isset($admin_json_data->ip) ? $admin_json_data->ip : $deposit->ip_address;
            $wname = isset($admin_json_data->wname) ? $admin_json_data->wname : $deposit->device_name;

            // admin table data
            $tbl_admin_data .= '
                <td>' . (isset($admin_info->name) ? $admin_info->name : '---') . '</td>
                <td>' . (isset($admin_info->email) ? $admin_info->email : '---') . '</td>
                <td>' . $ip . '</td>
                <td>' . $wname . '</td>
                <td>' . (isset($deposit->approved_date) ? date('d M Y, h:i A', strtotime($deposit->approved_date)) : '---') . '</td>';
            // admin table
            $tbl_admin = '<table id="deposit-admin-details' . $id . '" class="deposit-details table dt-inner-table-dark">
                <thead>
                    <tr>
                        <th>ADMIN Name</th>
                        <th>Admin Email</th>
                        <th>IP</th>
                        <th>Device</th>
                        <th>Action Date</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        ' . $tbl_admin_data . ' 
                    </tr>
                </tbody>
            </table>';
        }

        //===========================Admin Information condition End=================================////
        // ***************************************************
        // check deposit is direct account or wallet
        // **************************************************
        $tbl_account_details = '';
        if (strtolower($deposit->deposit_option) === 'account') {
            $trading_account = TradingAccount::where('trading_accounts.id', $deposit->account)
                ->select(
                    'trading_accounts.account_number',
                    'trading_accounts.platform',
                    'client_groups.group_name',
                    'trading_accounts.client_type',
                    'trading_accounts.leverage'
                )
                ->join('client_groups', 'trading_accounts.group_id', '=', 'client_groups.id')->first();
            $tbl_account_details .= '<span class="details-text">
                                            Account  Details
                                    </span>';
            $tbl_account_details .= '<table id="deposit-account-details' . $id . '" class="deposit-details table dt-inner-table-dark">
                                        <thead>
                                            <tr>
                                                <th>Account Number</th>
                                                <th>Platform</th>
                                                <th>Group</th>
                                                <th>Leverage</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                               <td>' . $trading_account->account_number . '</td>
                                               <td>' . $trading_account->platform . '</td>
                                               <td>' . $trading_account->group_name . '</td>
                                               <td>' . $trading_account->leverage . '</td>
                                            </tr>
                                        </tbody>
                                    </table>';
        }
        // start all inner table and descriptins
        $description = '
        <tr class="description" style="display:none">
            <td colspan="8">
                <div class="details-section-dark border-start-3 border-start-primary p-2 " style="display: flow-root;">
                    ' . $tbl_account_details . '
                    <span class="details-text">
                          ' . ucfirst($deposit->transaction_type) . '  Details
                    </span>
                    <table id="deposit-details' . $id . '" class="deposit-details table dt-inner-table-dark">
                        <thead>
                            <tr>
                                ' . $deposit_details_th . '
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                ' . $deposit_details_td . ' 
                            </tr>
                        </tbody>
                    </table>
                    <span class="details-text">
                          ' . $approved_by . '
                    </span>
                    ' . $tbl_admin . '
                    <br>
                    ' . $buttons . '
                </div>
            </td>
        </tr>';

        return Response::json([
            'status' => true,
            'description' => $description,
        ]);
    }


    public function showIdentifyModal(Request $request)
    {
        $data = [];
        if (Deposit::select()->where('deposits.id', $request->id)->exists()) {
            $bank_proof = Deposit::select('deposits.*')->where('deposits.id', $request->id)
                ->leftJoin('other_transactions', 'deposits.other_transaction_id', '=', 'other_transactions.id')
                ->first();
            if ($bank_proof->transaction_type == "bank") {
                $image_path = (isset($bank_proof->bank_proof)) ? $bank_proof->bank_proof : '';
                // $image_path = FileApiService::view_file($image_path);
                $contabo_files = FileApiService::contabo_file_path($image_path);
                $kyc_document = $bank_proof->bank_proof;
                $data = [
                    'image_path' => $image_path,
                    'kyc_document' => $kyc_document,
                    'status' => true,
                    'bank' => true,
                    'modal_name' => 'Bank',
                    'file_type' => $contabo_files['file_type'],
                    'file_url' => $contabo_files['dataUrl']
                ];
            }
            if ($bank_proof->transaction_type == "crypto") {
                $invoice = $bank_proof->crypto_address;
                $transaction = $bank_proof->crypto_address;
                $data = [
                    'invoice' => $invoice,
                    'transaction' => ($transaction) ? $transaction : '-------------',
                    'status' => true,
                    'crypto' => true,
                    'modal_name' => 'Crypto'
                ];
            }
            // b2bin pay crypto deposit
            if ($bank_proof->transaction_type == "b2bin") {
                $invoice = $bank_proof->invoice_id;
                $transaction = $bank_proof->crypto_address;
                $data = [
                    'invoice' => $invoice,
                    'transaction' => ($transaction) ? $transaction : '-------------',
                    'status' => true,
                    'crypto' => true,
                    'modal_name' => 'Crypto'
                ];
            }
            if ($bank_proof->transaction_type == "cash") {
                $invoice = $bank_proof->invoice_id;
                $transaction = $bank_proof->transaction_id;
                $data = [
                    'invoice' => ($invoice) ? $invoice : '---------------',
                    'transaction_type' => ($transaction) ? $transaction : '---------------',
                    'status' => true,
                    'cash' => true,
                    'modal_name' => 'Cash'
                ];
            }
            if ($bank_proof->transaction_type == "voucher") {
                $invoice = $bank_proof->invoice_id;
                $transaction = $bank_proof->transaction_id;
                $data = [
                    'invoice' => ($invoice) ? $invoice : '---------------',
                    'transaction_id' => ($transaction) ? $transaction : '---------------',
                    'status' => true,
                    'voucher' => true,
                    'modal_name' => 'Voucher'
                ];
            }

            if ($bank_proof->transaction_type == "help2pay") {
                $invoice = $bank_proof->invoice_id;
                $transaction = $bank_proof->transaction_id;
                $data = [
                    'invoice' => ($invoice) ? $invoice : '---------------',
                    'transaction_id' => ($bank_proof->transaction_id) ? $bank_proof->transaction_id : '---------------',
                    'status' => true,
                    'help2pay' => true,
                    'modal_name' => 'Help2pay'
                ];
            }

            if ($bank_proof->transaction_type == "PayPal") {
                $invoice = $bank_proof->invoice_id;
                $transaction = $bank_proof->transaction_id;
                $data = [
                    'invoice' => ($invoice) ? $invoice : '---------------',
                    'transaction_id' => ($bank_proof->transaction_id) ? $bank_proof->transaction_id : '---------------',
                    'status' => true,
                    'PayPal' => true,
                    'modal_name' => 'PayPal'
                ];
            }
            if ($bank_proof->transaction_type == "") {
                $data = [
                    'invoice' => '---------',
                    'transaction_id' => '--------------',
                    'status' => false,
                    'modal_name' => 'No Data Found For'
                ];
            }
        } else {
            $data = [
                'status' => false,
                'modal_name' => 'No Data Found For'
            ];
        }
        return Response::json($data);
    }

    //<---------Script for Approved Request----------->
    public function approveRequest(Request $request)
    {
        try {

            $deposit = Deposit::find($request->id);
            // check deposit for account or wallet
            $update = false;
            if (strtolower($deposit->deposit_option) === 'account') {
                // get trading account
                $trading_account = TradingAccount::where('trading_accounts.id', $deposit->account)->first();
                if (!$trading_account) {
                    return Response::json([
                        'status' => false,
                        'message' => 'Trading account not found! Please declined this make a request for wallet deposit',
                    ]);
                }
                // check platform is mt5 or mt4
                // mt4 platform deposit
                $result['success'] = false;
                if (strtolower($trading_account->platform) === 'mt4') {
                    $mt4_api = new MT4API();
                    $result = $mt4_api->execute([
                        'command' => 'deposit_funds',
                        'data' => [
                            'account_id' => $trading_account->account_number,
                            'amount' => (float)$deposit->amount,
                            'comment' => "Wallet Deposit from direct deposit approved #" . request()->ip()
                        ]
                    ], 'live');
                }
                // mt5 deposit
                if (strtolower($trading_account->platform) === 'mt5') {
                    $mt5_api = new Mt5WebApi();
                    $result = $mt5_api->execute('BalanceUpdate', [
                        "Login" => (int)$trading_account->account_number,
                        "Balance" => (float)$deposit->amount,
                        "Comment" => "Wallet Deposit from direct deposit approved #" . request()->ip(),
                    ]);
                }
                // uppdate internal deposit and wallet deposit
                if (isset($result['success']) && $result['success']) {
                    // update internal transfer
                    InternalTransfer::where('id', $deposit->internal_transfer)->update([
                        'status' => 'A',
                        'admin_log' => AdminLogService::admin_log(),
                        'approved_by' => auth()->user()->id,
                        'approved_date' => date('Y-m-d h:i:s', strtotime(now())),
                    ]);
                    // update deposit table
                    $update = Deposit::where('id', $request->id)->update([
                        'approved_status' => 'A',
                        'approved_date' => date('Y-m-d h:i:s', strtotime(now())),
                        'approved_by' => auth()->user()->id,
                        'admin_log' => AdminLogService::admin_log()
                    ]);
                } else {
                    return Response::json([
                        'status' => false,
                        'message' => $result['message'],
                    ]);
                }
            }
            // only wllet deposit
            else {
                // update deposit table
                $update = Deposit::where('id', $request->id)->update([
                    'approved_status' => 'A',
                    'approved_date' => date('Y-m-d h:i:s', strtotime(now())),
                    'approved_by' => auth()->user()->id,
                    'admin_log' => AdminLogService::admin_log()
                ]);
            }
            // after updated successfully
            if ($update) {
                // insert activity-----------------
                $user = User::find($deposit->user_id); //<---client email as user id
                $user->is_lead = true;
                $user->save();
                activity($deposit->wallet_type . " deposit approved")
                    ->causedBy(auth()->user()->id)
                    ->withProperties($deposit)
                    ->event($deposit->wallet_type . " deposit approved")
                    ->performedOn($user)
                    ->log("The IP address " . request()->ip() . " has been approved deposit request");
                // end activity log-----------------
                // sending mail to client
                $mail_status = EmailService::send_email('deposit-request-approve', [
                    'user_id' => $deposit->user_id,
                    'deposit_method' => ($deposit) ? ucwords($deposit->transaction_type) : '',
                    'deposit_date' => ($deposit) ? ucwords($deposit->created_at) : '',
                    'previous_balance' => BalanceSheetService::trader_wallet_balance($deposit->user_id) - ($deposit->amount),
                    'approved_amount' => $deposit->amount,
                    'total_balance' => BalanceSheetService::trader_wallet_balance($deposit->user_id)
                ]);
                // sending notification to all admin/account manager
                MailNotificationService::admin_notification([
                    'amount' => $deposit->amount,
                    'name' => $user->name,
                    'email' => $user->email,
                    'type' => 'deposit approve',
                    'client_type' => 'trader'
                ]);
                if ($mail_status) {
                    return Response::json([
                        'status' => true,
                        'message' => 'Mail successfully sent for Approved request',
                    ]);
                }
                return Response::json([
                    'status' => true,
                    'message' => 'Mail sending failed, Deposit approved!',
                ]);
            }
            return Response::json([
                'status' => false,
                'message' => 'Something went wrong, Please try again later!',
            ]);
        } catch (\Throwable $th) {
            throw $th;
            return Response::json([
                'status' => false,
                'message' => 'Got a server error!'
            ]);
        }
    }

    //<---------------Script for Decline Request------------------>
    public function declineRequest(Request $request)
    {

        // udpate deposit table

        $update = TraderDeposit::where('id', $request->id)->update([
            'approved_status' => 'D',
            'note' => $request->note,
            'approved_by' => auth()->user()->id,
            'approved_date' => date('Y-m-d h:i:s', strtotime(now())),
            'admin_log' => AdminLogService::admin_log()
        ]);
        // sending mail
        $deposits = Deposit::where('id', $request->id)->first();
        $mail_status = EmailService::send_email('decline-deposit-request', [
            'user_id' => $deposits->user_id,
            'deposit_method' => ucwords($deposits->transaction_type),
            'deposit_date' => ($deposits) ? ucwords($deposits->created_at) : '',
            'previous_balance' => AllFunctionService::trader_total_balance($deposits->user_id),
            'declined_amount' => $deposits->amount,
            'total_balance' => AllFunctionService::trader_total_balance($deposits->user_id)
        ]);
        if ($update) {
            // insert activity-----------------
            $user = User::find($deposits->user_id); //<---client email as user id
            activity($deposits->wallet_type . " deposit declined")
                ->causedBy(auth()->user()->id)
                ->withProperties($deposits)
                ->event($deposits->wallet_type . " deposit declined")
                ->performedOn($user)
                ->log("The IP address " . request()->ip() . " has been declined deposit request");
            // end activity log-----------------
            MailNotificationService::admin_notification([
                'amount' => $deposits->amount,
                'name' => $user->name,
                'email' => $user->email,
                'type' => 'deposit decline',
                'client_type' => 'trader'
            ]);
            if ($mail_status) {
                return Response::json([
                    'status' => true,
                    'message' => 'Mail successfully sent for Declined request',
                ]);
            }
            return Response::json([
                'status' => true,
                'message' => 'Mail sending failed, Please try again later!',
            ]);
        }
        return Response::json([
            'status' => false,
            'message' => 'Something went wrong, Please try again later!',
        ]);
    }

    //request  amount view
    public function viewAmount(Request $request, $id)
    {
        $amount = Deposit::select('amount')->where('id', $id)->first();
        $data = $amount->amount;
        return response()->json($data);
    }
    //update request amount
    public function amountUpdate(Request $request)
    {
        $table_id = $request->amount_id;
        $request_amount = $request->request_amount;
        $update = Deposit::where('id', $table_id)
            ->update([
                'amount' => $request_amount,
                'admin_log' => AdminLogService::admin_log(),
            ]);
        $deposit = Deposit::where('id', $request->amount_id)->first();

        if ($update) {
            // insert activity-----------------
            $user = User::find($deposit->user_id); //<---client email as user id
            activity($deposit->wallet_type . " deposit amount update")
                ->causedBy(auth()->user()->id)
                ->withProperties($deposit)
                ->event($deposit->wallet_type . " amount update")
                ->performedOn($user)
                ->log("The IP address " . request()->ip() . " has been update amount");
            // end activity log-----------------
            EmailService::send_email('update-deposit-amount', [
                'user_id' => $deposit->user_id,
                'admin' => auth()->user()->name,
                'amount' => $request_amount,
            ]);
            return Response::json([
                'status' => true,
                'message' => 'Amount Successful Updated',
            ]);
        } else {
            return Response::json([
                'status' => false,
                'message' => 'Amount Update Failed, Please try again later!',
            ]);
        }
    }
}
