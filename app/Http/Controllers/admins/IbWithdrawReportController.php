<?php

namespace App\Http\Controllers\admins;

use App\Http\Controllers\Controller;
use App\Models\IB;
use App\Models\ManagerUser;
use App\Models\TradingAccount;
use App\Models\User;
use App\Models\Withdraw;
use App\Services\AllFunctionService;
use App\Services\BankService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class IbWithdrawReportController extends Controller
{
    public function __construct()
    {
        $this->middleware(["role:ib withdraw"]);
        $this->middleware(["role:reports"]);
        $this->middleware(AllFunctionService::access('reports', 'admin'));
        $this->middleware(AllFunctionService::access('ib_withdraw', 'admin'));
    }
    public function index(Request $request)
    {
        $withdraw = Withdraw::select('transaction_type')->distinct()->get();
        return view('admins/reports/ib-withdraw-report', ['withdraw' => $withdraw]);
    }
    public function datatable_ib_withdraw(Request $request)
    {
        try {
            // check crm is combned
            $columns = ['users.name', 'users.email', 'transaction_type', 'created_by', 'approved_status', 'created_at', 'approved_date', 'amount'];
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
            )
                ->join('users', 'withdraws.user_id', '=', 'users.id')
                ->where('wallet_type', 'ib');
            // filter by manager auth
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
            if ($request->approved_status != "") {
                $result = $result->where('approved_status', $request->approved_status);
            }
            //Filter By Created by
            if ($request->created_by != "") {
                $result = $result->where('created_by', $request->created_by);
            }
            //Filter By Trader Name / Email /Phone /Country
            if ($request->trader_info != "") {
                $trader_info = $request->trader_info;
                $user_id = User::select('countries.name')->where(function ($query) use ($trader_info) {
                    $query->where('users.name', 'LIKE', '%' . $trader_info . '%')
                        ->orWhere('users.email', 'LIKE', '%' . $trader_info . '%')
                        ->orWhere('phone', 'LIKE', '%' . $trader_info . '%')
                        ->orWhere('countries.name', 'LIKE', '%' . $trader_info . '%');
                })->leftJoin('user_descriptions', 'users.id', '=', 'user_descriptions.user_id')
                    ->leftJoin('countries', 'user_descriptions.country_id', '=', 'countries.id')
                    ->select('users.id as user_id')->get()->pluck('user_id');
                // get trader id from ib table
                $ib_ids = IB::whereIn('reference_id', $user_id)->select('ib_id')->get()->pluck('ib_id');
                $result = $result->whereIn('withdraws.user_id', $ib_ids);
            }
            //Filter By IB Name / Email /Phone /Country
            if ($request->ib_info != "") {
                $ib_info = $request->ib_info;
                // $user_id = User::select('countries.name')->where('users.type', 4)->where(function ($query) use ($ib) {
                //     $query->where('name', 'LIKE', '%' . $ib . '%')
                //         ->orWhere('email', 'LIKE', '%' . $ib . '%')
                //         ->orWhere('phone', $ib)
                //         ->orWhere('countries.name', $ib);
                // })->leftJoin('user_descriptions', 'users.id', '=', 'user_descriptions.user_id')
                //     ->leftJoin('countries', 'user_descriptions.country_id', '=', 'countries.id')
                //     ->select('users.id as user_id')->get()->pluck('user_id');
                $result = $result->where(function ($query) use ($ib_info) {
                    $query->orWhere('users.name', 'LIKE', '%' . $ib_info . '%')
                        ->orWhere('users.email', 'LIKE', '%' . $ib_info . '%')
                        ->orWhere('users.phone', 'LIKE', '%' . $ib_info . '%');
                });
            }
            //Filter By Account Number
            if ($request->trading_account != "") {
                $user_id = TradingAccount::select('reference_id')
                    ->leftJoin('ib', 'trading_accounts.user_id', '=', 'ib.reference_id')
                    ->where('account_number', $request->trading_account)
                    ->first();

                $result = $result->where('users.id', $user_id->reference_id);
            }
            //Filter By Manager Name / Email
            if ($request->manager_info != "") {
                $manager_id = User::select('id')->where('email', $request->manager_info)
                    ->orWhere('name', $request->manager_info)
                    ->first();
                $user_id = ManagerUser::where('manager_id', $manager_id->id)->get()->pluck('user_id');
                $result = $result->whereIn('users.id', $user_id);
            }
            // filter by date range
            // date from 
            if ($request->from != "") {
                $result = $result->whereDate("withdraws.created_at", '>=', $request->from);
            }
            // date to
            if ($request->to != "") {
                $result = $result->whereDate("withdraws.created_at", '<=', $request->to);
            }
            // filter by min / max
            // filter by minimum amount
            if ($request->min != "") {
                $result = $result->where("amount", '>=', $request->min);
            }
            // filter by maximum amount
            if ($request->max != "") {
                $result = $result->where("amount", '<=', $request->max);
            }

            // filter search script
            if (isset($request->search['value']) && $request->search['value'] != "") {
                $search = $request->search['value'];
                $result = $result->where(function ($query) use ($search) {
                    $query->where('transaction_type', 'LIKE', '%' . $search . '%')
                        // ->orWhere('approved_status', 'LIKE', '%' . $search . '%')
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
                    $date = date('d F y, h:i:s', strtotime($value->approved_date));
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
                // approved status
                $status = '';
                if ($value->approved_status === "A") {
                    $status = '<span class="bg-light-success badge badge-success">Approved</span>';
                } elseif ($value->approved_status == "P") {
                    $status = '<span class="bg-light-warning badge badge-warning">Pending</span>';
                } else {
                    $status = '<span class="bg-light-danger badge badge-danger">Declined</span>';
                }
                $data[] = [
                    'DT_RowId'      => "row_" . $value->id,
                    'name'          => '<a href="#" data-withdrawid=' . $value->id . ' class="dt-description  justify-content-start"><span class="w"> <i class="plus-minus" data-feather="plus"></i> </span> <span>' . $value->name . '</span></a>',
                    'email'         => $value->email,

                    'transaction_type' => ucwords($value->transaction_type),
                    'created_by'    => $created_by,
                    'approved_status' => $status,
                    'created_at'    => date('d F y, h:i:s', strtotime($value->created_at)),
                    'approved_date' => $date,
                    'amount'        => '$' . $value->amount,
                ];
            }

            return Response::json([
                "draw" => $request->draw,
                'recordsTotal' => $count,
                'recordsFiltered' => $count,
                'total_amount' => $total_amount,
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
    // datatable description
    public function dt_description(Request $request)
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
