<?php

namespace App\Http\Controllers\admins;

use App\Http\Controllers\Controller;
use App\Models\Admin\AdminUser;
use App\Models\admin\TraderDeposit;
use App\Models\AdminBank;
use App\Models\Deposit;
use App\Models\IB;
use App\Models\User;
use App\Models\KycVerification;
use App\Models\ManagerUser;
use App\Models\TradingAccount;
use App\Services\AllFunctionService;
use App\Services\IbService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\DB;

use function PHPUnit\Framework\isNull;

class TraderDepositController extends Controller
{
    public function __construct()
    {
        $this->middleware(["role:trader deposit report"]);
        $this->middleware(["role:reports"]);

        // system module control
        $this->middleware(AllFunctionService::access('reports', 'admin'));
        $this->middleware(AllFunctionService::access('trader_deposit', 'admin'));
    }
    public function trader_report(Request $request)
    {
        $op = $request->input('op');

        if ($op == "data_table") {
            return $this->traderReportDT($request);
        }
        $deposit = Deposit::select('transaction_type')->distinct()->get();
        return view('admins.admin-trader-deposit-report', ['deposit' => $deposit]);
    }


    public function traderReportDT($request)
    {
        try {

            $columns = ['name', 'email', 'transaction_type', 'created_by', 'approved_status', 'created_at', 'approved_date', 'amount'];
            $orderby = $columns[$request->order[0]['column']];
            $result = TraderDeposit::select(
                'deposits.id',
                'deposits.user_id',
                'users.name',
                'users.email',
                'deposits.transaction_type',
                'deposits.approved_status',
                'deposits.created_at',
                'deposits.created_by',
                'deposits.approved_date',
                'users.email_verified_at',
                'deposits.amount'
            );
            // check login is manager
            if (auth()->user()->type === 'manager') {
                $users_id = ManagerUser::where('manager_id', auth()->user()->id)->select('user_id')->get();
                $result = $result->whereIn('users.id', $users_id);
            }

            $result = $result->join('users', 'deposits.user_id', '=', 'users.id')
                ->where('wallet_type', 'trader');
            // filter by manager info
            if ($request->manager_info != "") {
                $manager_info = $request->manager_info;
                $manager_id = User::select('id')->where(function ($query) use ($manager_info) {
                    $query->where('users.name', 'like', '%' . $manager_info . '%')
                        ->orWhere('users.email', 'like', '%' . $manager_info . '%')
                        ->orWhere('users.phone', 'like', '%' . $manager_info . '%');
                })->get()->pluck('id');
                $user_id = ManagerUser::select('user_id')->whereIn('manager_id', $manager_id)->get()->pluck('manager_id');
                $result = $result->whereIn('deposits.user_id', $user_id);
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
            // filter by kyc verification status
            if ($request->kyc_status != "") {
                $result = $result->where('users.kyc_status', $request->kyc_status);
            }
            //Filter By Created by
            if ($request->created_by != "") {
                $result = $result->where('created_by', $request->created_by);
            }


            //Filter by account number
            if ($request->account_number != "") {
                $user_id = TradingAccount::select('user_id')->where('account_number', $request->account_number)->first();
                $result = $result->where('deposits.user_id', $user_id->user_id);
            }
            // filter by transaction tuype
            if ($request->transaction_type != "") {
                $result = $result->where("transaction_type", $request->transaction_type);
            }
            // filter by approved status
            if ($request->approved_status != "") {
                $result = $result->where("approved_status", $request->approved_status);
            }
            // filter by date from
            if ($request->from != "") {
                $result = $result->whereDate("deposits.created_at", '>=', $request->from);
            }
            // filter by date to
            if ($request->to != "") {
                $result = $result->whereDate("deposits.created_at", '<=', $request->to);
            }
            // filter by min / max
            if ($request->min != "") {
                $result = $result->where("deposits.amount", '>=', $request->min);
            }
            if ($request->max != "") {
                $result = $result->where("deposits.amount", '<=', $request->max);
            }

            // filter search script end
            $count = $result->count();
            $total_amount = $result->sum('amount');
            $result = $result->orderby($orderby, $request->order[0]['dir'])->skip($request->start)->take($request->length)->get();
            $data = array();
            $amount = 0;
            foreach ($result as $m_user) {
                if ($m_user->approved_status == 'P') {
                    $status = '<span class="bg-light-warning badge badge-light-warning">Pending</span>';
                    $status_color = 'text-warning';
                } elseif ($m_user->approved_status == 'A') {
                    $status = '<span class="bg-light-success badge badge-light-success">Approved</span>';
                    $status_color = 'text-success';
                } elseif ($m_user->approved_status == 'D') {
                    $status = '<span class="bg-light-danger badge badge-light-danger">Declined</span>';
                    $status_color = 'text-danger';
                }

                if ($m_user->approved_date == '') {
                    $date = '---';
                } else {
                    $date = date('d M y', strtotime($m_user->approved_date));
                }
                // created by badges
                if (strtolower($m_user->created_by) === 'system') {
                    $created_by = '<span class="bg-success badge badge-success">' . ucwords(str_replace('_', ' ', $m_user->created_by)) . '</span>';
                } elseif (strtolower($m_user->created_by) === 'admin') {
                    $created_by = '<span class="bg-warning badge badge-warning">' . ucwords(str_replace('_', ' ', $m_user->created_by)) . '</span>';
                } elseif (strtolower($m_user->created_by) === 'manager') {
                    $created_by = '<span class="bg-seconday badge badge-seconday">' . ucwords(str_replace('_', ' ', $m_user->created_by)) . '</span>';
                } else {
                    $created_by = '<span class="bg-danger badge badge-danger">' . ucwords(str_replace('_', ' ', $m_user->created_by)) . '</span>';
                }
                $data[] = [
                    'name' => '<a href="#" data-id=' . $m_user->id . ' data-user_id=' . $m_user->user_id . ' class="dt-description  justify-content-start"><span class="w"> <i class="plus-minus" data-feather="plus"></i> </span> <span class="' . $status_color . '">' . ucfirst($m_user->name) . '</span></a>',
                    'email' => $m_user->email,
                    'transaction_type' => ucwords($m_user->transaction_type),
                    'created_by' => $created_by,
                    'approved_status' => $status,
                    'request_at' => date('d M y h:i:s', strtotime($m_user->created_at)),
                    'approved_at' => $date,
                    'amount' => '$' . $m_user->amount,
                ];
            }

            return Response::json([
                "draw" => $request->draw,
                "recordsTotal" => $count,
                "recordsFiltered" => $count,
                "data" => $data,
                'total_amount' => round($total_amount, 2),
            ]);
        } catch (\Throwable $th) {
            throw $th;

            return Response::json([
                "draw" => $request->draw,
                "recordsTotal" => 0,
                "recordsFiltered" => 0,
                "data" => [],
                'total_amount' => 0,
            ]);
        }
    }

    public function trader_deposit_description(Request $request, $id)
    {

        $deposit = Deposit::where('deposits.id', $id)
            ->select(
                'deposits.transaction_type',
                'deposits.approved_status',
                'deposits.local_currency',
                'deposits.bank_id',
                'deposits.amount',
                'deposits.currency',
                'deposits.transaction_id',
                'deposits.order_id',
                'deposits.invoice_id',
                'deposits.admin_log',
                'deposits.approved_by',
                'deposits.approved_date',
                'deposits.account',
                'deposits.internal_transfer',
                'deposits.charge',
                'deposits.deposit_option',
                'other_transactions.block_chain',
                'other_transactions.crypto_type',
                'other_transactions.crypto_amount',
            )
            ->leftJoin('other_transactions', 'deposits.other_transaction_id', '=', 'other_transactions.id')->first();

        $innerTH = "";
        $innerTD = "";
        // bank deposit
        if (strtolower($deposit->transaction_type) === 'bank') {
            $bank_info = AdminBank::select()->where('id', $deposit->bank_id)->first();
            $innerTH .= '
                <th>Amount in USD</th>
                <th class="' . (isNull($deposit->currency) ? "d-none" : "") . '">Amount in ' . (isset($deposit->currency) ? $deposit->currency : '----') . '</th>
                <th>Bank Name</th>
                <th>Bank AC Name</th>
                <th>Bank AC No</th>
                <th class="' . (isNull($bank_info->swift_code) ? "d-none" : "") . '">Bank Swift Code</th>
                <th class="' . (isNull($bank_info->ifsc_code) ? "d-none" : "") . '">Bank IBAN</th>
                <th>Bank Country</th>';
            $innerTD .= '
                <td>' . '$' . $deposit->amount . '</td>
                <td class="' . (isNull($deposit->currency) ? "d-none" : "") . '">' . $deposit->local_currency . '</td>
                <td>' . ucwords($bank_info->bank_name) . '</td>
                <td>' . ucwords($bank_info->account_name) . '</td>
                <td>' . $bank_info->account_number . '</td>
                <td class="' . (isNull($bank_info->swift_code) ? "d-none" : "") . '">' . $bank_info->swift_code . '</td>
                <td class="' . (isNull($bank_info->ifsc_code) ? "d-none" : "") . '">' . $bank_info->ifsc_code . '</td>
                <td>' . $bank_info->bank_country . '</td>
                ';
        }
        // crypto deposit
        else if (strtolower($deposit->transaction_type) === 'crypto') {

            $hash_type = $deposit->block_chain;
            if ($hash_type === "ERC20"); {
                $url = "https://etherscan.io/tx/" . $deposit->transaction_id;
            }
            if ($hash_type === "TRC20") {
                $url = "https://tronscan.org/#/searcherror/" . $deposit->transaction_id;
            }
            $innerTH .= '
                <th>Amount Request</th>
                <th>Crypto Type</th>
                <th>Transaction Hash</th>
                <th>Crypto Amount</th>';
            $innerTD .= '
                <th>' . '$' . $deposit->amount . '</th>
                <th>' . $deposit->crypto_type . '</th>
                <th><a href="$url" target="_blank">' . $deposit->transaction_id . '</a></th>
                <th>' . $deposit->crypto_amount . '</th>';
        }
        // perfect money deposit
        else if ($deposit->transaction_type === 'Perfect Money') {
            $innerTH .= '
                <th>Amount Request</th>
                <th>Transaction ID</th>
                <th>Order ID</th>';
            $innerTD .= '
                <th>' . '$' . $deposit->amount . '</th>
                <th>' . $deposit->transaction_id . '</th>
                <th>' . $deposit->order_id . '</th>';
        }
        // help2pay deposit
        else if (strtolower($deposit->transaction_type) === 'help2pay') {
            $amount = '$' . $deposit->amount;
            $innerTH .= '
                <th>Amount Request</th>
                <th>Help2Pay ID</th>
                <th>IDR Amount</th>';
            $innerTD .= '
                <th>' . $amount . '</th>
                <th>' . $deposit->order_id . '</th>
                <th>' . $deposit->local_currency . '(' . $deposit->currency . ')' . '</th>';
        }
        // paypal deposit
        else if (($deposit->transaction_type) === 'PayPal') {
            $innerTH .= '
                <th>Amount Request</th>
                <th>Transaction ID</th>
                <th>Invoice ID</th>';
            $innerTD .= '
                <th>' . '$' . $deposit->amount . '</th>
                <th>' . $deposit->transaction_id . '</th>
                <th>' . $deposit->invoice_id . '</th>';
        }
        // else all other deposit
        else {
            $approved_by = User::find($deposit->approved_by);
            if (isset($approved_by)) {
                $name = ($approved_by->name) ? $approved_by->name : '---';
            } else {
                $name = '---';
            }
            $innerTH .= '
                <th>Amount Request</th>
                <th>Charge Amount</th>
                <th>Approved By</th>
                <th>Note</th>';
            $innerTD .= '
                <th>' . '$' . $deposit->amount . '</th>
                <th>' . '$' . $deposit->charge . '</th>
                <th>' . $name . '</th>
                <th>' . (($deposit->note) ? $deposit->note : '---') . '</th>';
        }
        // account details
        $tbl_account_details = '';
        if (strtolower($deposit->deposit_option) === 'account') {
            $trading_account = TradingAccount::where('trading_accounts.id', $deposit->account)
                ->select(
                    'trading_accounts.account_number',
                    'trading_accounts.platform',
                    'client_groups.group_name',
                    'trading_accounts.client_type',
                    'trading_accounts.leverage',
                )
                ->join('client_groups', 'trading_accounts.group_id', '=', 'client_groups.id')->first();
            $tbl_account_details .= '<span class="details-text">Account  Details</span>';
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
        //===========================Admin Information condition=================================////
        $tbl_admin = "";
        $approved_by = "";
        if ($deposit->approved_status === 'A' || $deposit->approved_status === 'D') {
            $approved_by = ($deposit->approved_status) == 'A' ? "Approved By" : "Declined By";
            $admin_info = User::select('name', 'email')->where('id', $deposit->approved_by)->first();
            $admin_json_data = json_decode($deposit->admin_log);

            $tbl_admin .= '<table id="deposit-admin-details' . $id . '" class="deposit-details table dt-inner-table-dark">
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
                                        <td>' . (isset($admin_info->name) ? $admin_info->name : '---') . '</td>
                                        <td>' . (isset($admin_info->email) ? $admin_info->email : '---') . '</td>
                                        <td>' . (isset($admin_json_data->ip) ? $admin_json_data->ip : '---') . '</td>
                                        <td>' . (isset($admin_json_data->wname) ? $admin_json_data->wname : '---') . '</td>
                                        <td>' . (isset($deposit->approved_date) ? date('d M Y, h:i A', strtotime($deposit->approved_date)) : '---') . '</td>
                                    </tr>
                                </tbody>
                            </table>';
        }
        //===========================Admin Information condition End=================================////
        $description = '
        <tr class="description" style="display:none">
            <td colspan="8">
                <div class="details-section-dark border-start-3 border-start-primary p-2 " style="display: flow-root;">
                ' . $tbl_account_details . '
                    <span class="details-text">' . ucfirst($deposit->transaction_type) . '  Details:</span>
                    <table id="deposit-details' . $id . '" class="deposit-details table dt-inner-table-dark">
                        <thead>
                            <tr>
                             ' . $innerTH . '
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                ' . $innerTD . '
                            </tr>
                        </tbody>
                    </table>
                    <span class="details-text">
                          ' . $approved_by . '
                    </span>
                    ' . $tbl_admin . '
                    <br>                    
                </div>
            </td>
        </tr>';
        return Response::json(
            [
                'status' => true,
                'description' => $description,
            ]
        );
    }
}
