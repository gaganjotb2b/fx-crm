<?php

namespace App\Http\Controllers\admins;

use App\Http\Controllers\Controller;
use App\Models\TradingAccount;
use App\Models\User;
use App\Models\Withdraw;
use App\Services\AllFunctionService;
use App\Services\BankService as ServicesBankService;
use App\Services\deposit\BankService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class AdminWithdrawReportController extends Controller
{
    public function __construct()
    {
        // system module control
        $this->middleware(AllFunctionService::access('admin_withdraw', 'admin'));
        $this->middleware(AllFunctionService::access('finance', 'admin'));
    }
    public function withdraw_report(Request $request)
    {
        return view('admins.finance.admin-withdraw-report');
    }

    public function withdraw_report_dt(Request $request)
    {
        try {
            $columns = ['name', 'email', 'wallet_type', 'transaction_type', 'approved_date', 'withdraws.amount', 'created_at', 'approved_status'];
            $orderby = $columns[$request->order[0]['column']];
            $result = Withdraw::whereNot('withdraws.created_by', 'system')->select(
                'withdraws.id',
                'withdraws.user_id',
                'users.name',
                'users.email',
                'withdraws.transaction_type',
                'withdraws.approved_status',
                'withdraws.approved_by',
                'withdraws.wallet_type',
                'withdraws.created_at',
                'withdraws.amount',
                'withdraws.approved_date',
                'users.email_verified_at',
                'users.kyc_status'
            )->join('users', 'users.id', '=', 'withdraws.user_id');
            // -----------------------------------------Filter Start---------------------------------->
            // Filter By Approved Status
            if ($request->approved_status != "") {
                $result = $result->where("approved_status", $request->approved_status);
            }
            // Filter By Client Type
            if ($request->client_type != "") {
                $result = $result->where("wallet_type", $request->client_type);
            }
            // Filter By KYC Verification Status
            if ($request->verify_status != "") {
                $result = $result->where("users.kyc_status", $request->verify_status);
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
                    ->select('users.id as client_id')->get()->pluck('client_id');
                $result = $result->whereIn('withdraws.user_id', $user_id);
            }
            //Filter By IB Name / Email /Phone /Country
            if ($request->ib_info != "") {
                $ib = $request->ib_info;
                $user_id = User::select('countries.name')->where('users.type', 4)->where(function ($query) use ($ib) {
                    $query->where('users.name', 'LIKE', '%' . $ib . '%')
                        ->orWhere('users.email', 'LIKE', '%' . $ib . '%')
                        ->orWhere('users.phone', 'LIKE', '%' . $ib . '%')
                        ->orWhere('countries.name', 'LIKE', '%' . $ib . '%');
                })->leftJoin('user_descriptions', 'users.id', '=', 'user_descriptions.user_id')
                    ->leftJoin('countries', 'user_descriptions.country_id', '=', 'countries.id')
                    ->select('users.id as client_id')->get()->pluck('client_id');
                $result = $result->whereIn('withdraws.user_id', $user_id);
            }
            //Filter by Admin Name /Email /Phone
            if ($request->admin_info != "") {
                $admin = $request->admin_info;
                $admin_id = User::where('users.type', 1)
                    ->where(function ($query) use ($admin) {
                        $query->where('users.name', 'LIKE', '%' . $admin . '%')
                            ->orWhere('users.email', 'LIKE', '%' . $admin . '%')
                            ->orWhere('users.phone', 'LIKE', '%' . $admin . '%');
                    })->select('users.id as client_id')->get()->pluck('client_id');
                // return $admin_id;
                $result = $result->whereIn('withdraws.approved_by', $admin_id);
            }
            //Filter by trading account number
            if ($request->trading_acc != "") {
                $users_id = TradingAccount::select('user_id')
                    ->where('account_number', $request->trading_acc)->first();
                $result = $result->whereIn('withdraws.user_id', $users_id);
            }
            //Filter By Amount
            if ($request->min != "") {
                $result = $result->where("withdraws.amount", '>=', $request->min);
            }
            if ($request->max != "") {
                $result = $result->where("withdraws.amount", '<=', $request->max);
            }
            //Filter By Request Date
            if ($request->from != "") {
                $result = $result->whereDate("withdraws.created_at", '>=', $request->from);
            }
            if ($request->to != "") {
                $result = $result->whereDate("withdraws.created_at", '<=', $request->to);
            }
            // -----------------------------------------Filter End---------------------------------->
            $total_amount = $result->sum('withdraws.amount');
            $count_row = $result->count();
            $results = $result->orderBy($orderby, $request->order[0]['dir'])->skip($request->current)->take($request->length)->get();
            $data = [];
            foreach ($results as $result) {
                if ($result->approved_status == 'P') {
                    $status = '<span class="bg-light-warning badge badge-warning">Pending</span>';
                } elseif ($result->approved_status == 'A') {
                    $status = '<span class="bg-light-success badge badge-success">Approved</span>';
                } elseif ($result->approved_status == 'D') {
                    $status = '<span class="bg-light-danger badge badge-danger">Declined</span>';
                }

                if ($result->wallet_type === 'ib') {
                    $client_type = '<span class="bg-warning badge badge-warning">IB</span>';
                } else {
                    $client_type = '<span class="bg-success badge badge-success">Trader</span>';
                }

                if ($result->approved_by == '') {
                    $admin_name = '--';
                    $admin_email = '--';
                } else {
                    $admin_id = $result->approved_by;
                    $admin_data = User::where('id', $admin_id)->select('name', 'email')->first();

                    if ($admin_data) {
                        $admin_name = $admin_data->name;
                        $admin_email = $admin_data->email;
                    } else {
                        $admin_name = '--';
                        $admin_email = '--';
                    }
                }
                $data[] = [
                    'name'   => '<a href="#" data-id="' . $result->id . '" class="dt-description d-flex justify-content-between"><span class="w"> <i class="plus-minus" data-feather="plus"></i> </span>' . (isset($check_uncheck) ? '<span class="text-success">' . $result->name . '</span>' : '<span class="text-danger">' . $result->name . '</span>') . '</a>',
                    'email'  => $result->email,
                    'client_type' => $client_type,
                    'method' => ucwords($result->transaction_type),
                    'admin_name' => $admin_name,
                    'admin_email' => $admin_email,
                    'amount' => '$' . $result->amount,
                    'status' => $status,
                    'request_at'   => date('d M y, h:i A', strtotime($result->created_at)),
                ];
            }

            return Response::json([
                'draw' => $request->draw,
                'recordsTotal' => $count_row,
                'recordsFiltered' => $count_row,
                'total_amount' => round($total_amount, 2),
                'data' => $data,
            ]);
        } catch (\Throwable $th) {
            throw $th;
            return Response::json([
                'draw' => $request->draw,
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'total_amount' => 0,
                'data' => [],
            ]);
        }
    }
    public function withdraw_description(Request $request, $id)
    {

        $withdraw = Withdraw::select(
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
            'other_transactions.account_email'
        )
            ->leftJoin('bank_accounts', 'withdraws.bank_account_id', '=', 'bank_accounts.id')
            ->leftJoin('other_transactions', 'withdraws.other_transaction_id', '=', 'other_transactions.id')
            ->leftJoin('countries', 'bank_accounts.bank_country', '=', 'countries.id')
            ->where('withdraws.id', $id)
            ->first();
        $innerTH = "";
        $innerTD = "";
        if (isset($withdraw->transaction_type) && strtolower($withdraw->transaction_type) === 'bank') {
            $withdraw_amount = '$' . $withdraw->amount;
            $mult_currency = ($withdraw->local_currency) ? $withdraw->local_currency : '---';
            $mult_currency_type = $withdraw->currency;
            $multi_cur_visibility = ServicesBankService::is_multicurrency('all') ? "" : "d-none";
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
