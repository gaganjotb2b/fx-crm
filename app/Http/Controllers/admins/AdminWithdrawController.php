<?php

namespace App\Http\Controllers\admins;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\IB;
use App\Models\ManagerUser;
use App\Models\TradingAccount;
use App\Models\User;
use App\Models\Withdraw;
use App\Services\AllFunctionService;
use App\Services\CombinedService;
use App\Services\BankService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

class AdminWithdrawController extends Controller
{
    public function __construct()
    {
        $this->middleware(["role:ib withdraw"]);
        $this->middleware(["role:reports"]);
        if (request()->is('/admin/report/withdraw/ib')) {
            // system module control
            $this->middleware(AllFunctionService::access('reports', 'admin'));
            $this->middleware(AllFunctionService::access('ib_withdraw', 'admin'));
        } elseif (request()->is('/admin/report/withdraw/trader')) {
            // system module control
            $this->middleware(AllFunctionService::access('reports', 'admin'));
            $this->middleware(AllFunctionService::access('trader_withdraw', 'admin'));
        }
    }
    public function withdraw_report(Request $request)
    {
        $withdraw = Withdraw::select('transaction_type')->distinct()->get();
        return view('admins.reports.trader-withdraw-report', ['withdraw' => $withdraw]);
    }

    public function withdrawReportDT(Request $request)
    {
        try {
            $approved_status = $request->approved_status;
            // check crm is combned
            $columns = ['id', 'name', 'email', 'transaction_type', 'approved_status', 'created_at', 'approved_date', 'amount'];
            $orderby = $columns[$request->order[0]['column']];
            $result = Withdraw::select(
                'withdraws.id',
                'withdraws.user_id',
                'users.name',
                'users.email',
                'users.type',
                'withdraws.transaction_type',
                'withdraws.approved_status',
                'withdraws.created_at',
                'withdraws.created_by',
                'withdraws.approved_date',
                'withdraws.amount'
            )->where('wallet_type', 'trader')->join('users', 'withdraws.user_id', '=', 'users.id');

            // filter by auth manager
            if (auth()->user()->type === 'manager') {
                $users_id = ManagerUser::where('manager_id', auth()->user()->id)->select('user_id')->get()->pluck('user_id');
                $result = $result->whereIn('users.id', $users_id);
            }
            // filter by transaction method
            if ($request->transaction_type != "") {
                $result = $result->where('transaction_type', $request->transaction_type);
            }
            // filter by kyc verifications
            if ($request->verify_status != "") {
                $result = $result->where('users.kyc_status', $request->verify_status);
            }
            // filter by approved status
            if ($approved_status != "") {
                $result = $result->where('approved_status', $approved_status);
            }
            // filter by created by
            if ($request->created_by != "") {
                $result = $result->where('created_by', $request->created_by);
            }
            //Filter By Trader Name / Email /Phone /Country
            if ($request->trader_info != "") {
                $trader_info = $request->trader_info;
                $user_id = User::select('id')->where(function ($query) use ($trader_info) {
                    $query->where('name', 'LIKE', '%' . $trader_info . '%')
                        ->orWhere('email', 'LIKE', '%' . $trader_info . '%')
                        ->orWhere('phone', 'LIKE', '%' . $trader_info . '%');
                })->get()->pluck('id');
                $result = $result->whereIn('withdraws.user_id', $user_id);
            }
            //Filter By IB Name / Email /Phone /Country
            if ($request->ib_info != "") {
                $ib = $request->ib_info;
                $user_id = User::select('id')->where('type', 4)->where(function ($query) use ($ib) {
                    $query->where('name', 'LIKE', '%' . $ib . '%')
                        ->orWhere('email', 'LIKE', '%' . $ib . '%')
                        ->orWhere('phone', $ib);
                })->get()->pluck('id');
                // get ib id
                $reference_id = IB::whereIn('ib_id', $user_id)->select('reference_id')->get()->pluck('reference_id');
                $result = $result->whereIn('withdraws.user_id', $reference_id);
            }
            //Filter By Account Number
            if ($request->trading_account != "") {
                $user_id = TradingAccount::select('user_id')->where('account_number', $request->trading_account)->first();
                $result = $result->whereIn('users.id', $user_id);
            }
            //Filter By Manager Name / Email
            if ($request->manager_info != "") {
                $manager_info = $request->manager_info;
                $manager_id = User::select('id')
                    ->where(function ($query) use ($manager_info) {
                        $query->where('name', 'LIKE', '%' . $manager_info . '%')
                            ->orWhere('email', 'LIKE', '%' . $manager_info . '%')
                            ->orWhere('phone', 'like', '%' . $manager_info . '%');
                    })
                    ->get()->pluck('id');
                $user_id = ManagerUser::whereIn('manager_id', $manager_id)->selct('user_id')->get()->pluck('user_id');
                $result = $result->whereIn('users.id', $user_id);
            }
            // filter by date from
            if ($request->from != "") {
                $result = $result->whereDate("withdraws.created_at", '>=', $request->from);
            }
            // filter by date
            if ($request->to != "") {
                $result = $result->whereDate("withdraws.created_at", '<=', $request->to);
            }
            // filter by amount min
            if ($request->min != "") {
                $result = $result->where("amount", '>=', $request->min);
            }
            // filter by amount max
            if ($request->max != "") {
                $result = $result->where("amount", '<=', $request->max);
            }

            // filter search script end

            if (isset($request->search['value']) && $request->search['value'] != "") {
                $search = $request->search['value'];
                $result = $result->where(function ($query) use ($search) {
                    $query->where('transaction_type', 'LIKE', '%' . $search . '%')
                        ->orWhere('approved_status', 'LIKE', '%' . $search . '%')
                        ->orWhere('name', 'LIKE', '%' . $search . '%')
                        ->orWhere('email', 'LIKE', '%' . $search . '%')
                        ->orWhere('withdraws.created_at', 'LIKE', '%' . $search . '%')
                        ->orWhere('amount', 'LIKE', '%' . $search . '%');
                });
            }

            $count = $result->count();
            $total_amount = $result->sum('amount');
            $result = $result->orderby($orderby, $request->order[0]['dir'])->skip($request->start)->take($request->length)->get();
            $data = array();

            foreach ($result as $value) {
                if ($value->approved_date == '') {
                    $date = '----';
                } else {
                    $date = date('d F y, h:i A', strtotime($value->approved_date));
                }
                // $extra = 'Name: '.$withdraw->user.'<br/>Method: '.$withdraw->method.'<br/>Transaction ID: '.$withdraw->txn_hash;
                if (strtolower($value->created_by) === 'system') {
                    $created_by = '<span class="bg-success badge badge-success">' . ucwords(str_replace('_', ' ', $value->created_by)) . '</span>';
                } elseif (strtolower($value->created_by) === 'admin') {
                    $created_by = '<span class="bg-warning badge badge-warning">' . ucwords(str_replace('_', ' ', $value->created_by)) . '</span>';
                } elseif (strtolower($value->created_by) === 'manager') {
                    $created_by = '<span class="bg-seconday badge badge-seconday">' . ucwords(str_replace('_', ' ', $value->created_by)) . '</span>';
                } else {
                    $created_by = '<span class="bg-danger badge badge-danger">' . ucwords(str_replace('_', ' ', $value->created_by)) . '</span>';
                }
                $data[] = [
                    'DT_RowId' => "row_" . $value->id,
                    'name' => '<a href="#" data-withdrawid=' . $value->id . ' class="dt-description  justify-content-start"><span class="w"> <i class="plus-minus" data-feather="plus"></i> </span> <span>' . $value->name . '</span></a>',
                    'email' => $value->email,
                    'transaction_type' => ucwords($value->transaction_type),
                    'created_by' => $created_by,
                    'approved_status' => $value->approved_status == "A" ? '<span class="bg-light-success badge badge-success">Approved</span>' : '<span class="bg-light-warning badge badge-warning">Pending</span>',
                    'created_at' => date('d F y, h:i A', strtotime($value->created_at)),
                    'approved_date' => $date,
                    'amount' => '$ ' . $value->amount,
                ];
            }

            return Response::json([
                "draw" => $request->draw,
                'recordsTotal' => $count,
                'recordsFiltered' => $count,
                'total_amount' => round($total_amount, 2),
                'data' => $data,
            ]);
        } catch (\Throwable $th) {
            // throw $th;
            return Response::json([
                "draw" => $request->draw,
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'total_amount' => 0,
                'data' => [],
            ]);
        }
    }

    public function withdraw_description(Request $request)
    {
        $id = $request->id;
        $withdraw = Withdraw::where('withdraws.id', $id)
            ->leftJoin('bank_accounts', 'withdraws.bank_account_id', '=', 'bank_accounts.id')
            ->leftJoin('other_transactions', 'withdraws.other_transaction_id', '=', 'other_transactions.id')
            ->leftJoin('countries', 'bank_accounts.bank_country', '=', 'countries.id')
            ->select(
                'withdraws.*',
                'countries.name as country',
                'bank_accounts.bank_ac_name',
                'bank_accounts.bank_name',
                'bank_accounts.bank_ac_number',
                'bank_accounts.bank_swift_code',
                'bank_accounts.bank_iban',
                'other_transactions.crypto_type',
                'other_transactions.crypto_address',
                'other_transactions.crypto_amount',
                'other_transactions.account_name',
                'other_transactions.account_email',
            )
            ->first();
        $innerTH = "";
        $innerTD = "";
        if (isset($withdraw->transaction_type) && strtolower($withdraw->transaction_type) === 'bank') {
            $withdraw_amount = '$' . $withdraw->amount;
            $mult_currency = ($withdraw->local_currency) ? $withdraw->local_currency : '---';
            $mult_currency_type = $withdraw->currency;
            $multi_cur_visibility = BankService::is_multicurrency('all') ? "" : "d-none";
            $innerTH .= '
                <th>Amount In USD</th>
                <th class="' . $multi_cur_visibility . '">Amount In ' . $mult_currency_type . '</th>
                <th>Bank Name</th>
                <th>Bank AC Name</th>
                <th>Bank AC No</th>
                <th>Bank Swift Code</th>
                <th>Bank IBAN</th>
                <th>Bank Country</th>';
            $innerTD .= '
                <td>' . $withdraw_amount . '</td>
                <td class="' . $multi_cur_visibility . '">' . $mult_currency . '</td>
                <td>' . ucwords($withdraw->bank_name) . '</td>
                <td>' . $withdraw->bank_ac_name . '</td>
                <td>' . $withdraw->bank_ac_number . '</td>
                <td>' . $withdraw->bank_swift_code . '</td>
                <td>' . $withdraw->bank_iban . '</td>
                <td>' . ucwords($withdraw->country) . '</td>';
        } else if (strtolower($withdraw->transaction_type) === 'crypto') {
            $withdraw_amount = '$' . $withdraw->amount;
            $innerTH .= '
                <th>Amount Request</th>
                <th>Crypto Type</th>
                <th>Address</th>
                <th>Crypto Amount</th>';
            $innerTD .= '
                <th>' . $withdraw_amount . '</th>
                <th>' . $withdraw->crypto_type . '</th>
                <th>' . $withdraw->crypto_address . '</th>
                <th>' . $withdraw->crypto_amount . '</th>';
        } else {
            $withdraw_amount = '$' . $withdraw->amount;
            $innerTH .= '
                <th>Amount Request</th>
                <th>Account Name</th>
                <th>Account Email</th>';
            $innerTD .= '
                <th>' . $withdraw_amount . '</th>
                <th>' . $withdraw->account_name . '</th>
                <th>' . $withdraw->account_email . '</th>';
        }
        $transaction_type = strtoupper($withdraw->transaction_type);
        $innerTH1 = "";
        $innerTD1 = "";
        $approved_by = "";
        if ($withdraw->approved_status === 'A' || $withdraw->approved_status === 'D') {
            $approved_by = ($withdraw->approved_status) == 'A' ? 'Approved By' : 'Declined By';
            $admin_info = User::select('name', 'email')->where('id', $withdraw->approved_by)->first();
            $admin_name = isset($admin_info->name) ? $admin_info->name : '---';
            $admin_email = isset($admin_info->email) ? $admin_info->email : '---';
            $admin_json_data = json_decode($withdraw->admin_log);
            $ip = isset($admin_json_data->ip) ? $admin_json_data->ip : $withdraw->ip_address;
            $winName = isset($admin_json_data->wname) ? $admin_json_data->wname : $withdraw->device_name;
            $action_date = isset($withdraw->approved_date) ? date('d M Y,h:i A', strtotime($withdraw->approved_date)) : '---';

            $innerTH1 .= '
                <th>Admin Name</th>
                <th>Admin Email</th>
                <th>IP</th>
                <th>Device</th>
                <th>Action Date</th>
            ';
            $innerTD1 .= '
                <td>' . $admin_name . '</td>
                <td>' . $admin_email . '</td>
                <td>' . $ip . '</td>
                <td>' . $winName . '</td>
                <td>' . $action_date . '</td>
            ';
        }
        $description = '
            <tr class="description" style="display:none">
                <td colspan="8">
                    <div class="details-section-dark border-start-3 border-start-primary p-2">
                    <span class="details-text">
                        <?php echo $transaction_type; ?> DETAILS
                    </span>                 
                    <table id="deposit-details" class="datatable-inner table dt-inner-table-dark">
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
                    <table id="deposit-details' . $id . '" class="deposit-details table dt-inner-table-dark">
                        <thead>
                            <tr>
                             ' . $innerTH1 . ' 
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                ' . $innerTD1 . '
                            </tr>
                        </tbody>
                    </table>
                    <br>
                    </div>
                </td>
            </tr>';

        $data = [
            'status' => true,
            'description' => $description
        ];
        return Response::json($data);
    }
}
