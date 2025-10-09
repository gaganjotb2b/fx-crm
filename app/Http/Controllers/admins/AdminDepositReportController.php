<?php

namespace App\Http\Controllers\admins;

use App\Http\Controllers\Controller;
use App\Models\admin\TraderDeposit;
use App\Models\ManagerUser;
use App\Models\User;
use App\Models\Deposit;
use App\Models\AdminBank;
use App\Models\TradingAccount;
use App\Services\AllFunctionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class AdminDepositReportController extends Controller
{
    public function __construct()
    {
        // system module control
        $this->middleware(AllFunctionService::access('admin_deposit', 'admin'));
        $this->middleware(AllFunctionService::access('finance', 'admin'));
    }
    public function deposit_report(Request $request)
    {
        return view('admins.finance.admin-deposit-report');
    }

    public function deposit_report_dt(Request $request)
    {
        try {

            $columns = ['name', 'email', 'wallet_type', 'transaction_type', 'approved_date', 'amount', 'created_at', 'approved_status'];
            $orderby = $columns[$request->order[0]['column']];
            $result = TraderDeposit::whereNot('deposits.created_by', 'system')->select(
                'deposits.id',
                'deposits.user_id',
                'users.name',
                'users.email',
                'users.kyc_status',
                'deposits.transaction_type',
                'deposits.approved_status',
                'deposits.approved_by',
                'deposits.wallet_type',
                'deposits.created_at',
                'deposits.approved_date',
                'users.email_verified_at',
                'deposits.amount'
            )->join('users', 'deposits.user_id', '=', 'users.id');

            // check login is manager
            if (auth()->user()->type === 'manager') {
                $users_id = ManagerUser::where('manager_id', auth()->user()->id)->select('user_id')->get();
                $result = $result->whereIn('users.id', $users_id);
            }

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
                $result = $result->whereIn('deposits.user_id', $user_id);
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
                $result = $result->whereIn('deposits.user_id', $user_id);
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
                $result = $result->whereIn('deposits.approved_by', $admin_id);
            }
            //Filter by trading account number
            if ($request->trading_acc != "") {
                $users_id = TradingAccount::select('user_id')
                    ->where('account_number', $request->trading_acc)->first();
                $result = $result->whereIn('deposits.user_id', $users_id);
            }
            //Filter By Amount
            if ($request->min != "") {
                $result = $result->where("deposits.amount", '>=', $request->min);
            }
            if ($request->max != "") {
                $result = $result->where("deposits.amount", '<=', $request->max);
            }
            //Filter By Request Date
            if ($request->from != "") {
                $result = $result->whereDate("deposits.created_at", '>=', $request->from);
            }
            if ($request->to != "") {
                $result = $result->whereDate("deposits.created_at", '<=', $request->to);
            }
            $total_amount = $result->sum('amount');
            // filter search script end
            $count = $result->count();
            $result = $result->orderby($orderby, $request->order[0]['dir'])->skip($request->start)->take($request->length)->get();
            $data = array();
            $total_amount = $total_amount;
            $i = 0;
            $amount = 0;
            foreach ($result as $value) {
                if ($value->approved_status == 'P') {
                    $status = '<span class="bg-light-warning badge badge-warning">Pending</span>';
                    $status_color = 'text-warning';
                } elseif ($value->approved_status == 'A') {
                    $status = '<span class="bg-light-success badge badge-success">Approved</span>';
                    $status_color = 'text-success';
                } elseif ($value->approved_status == 'D') {
                    $status = '<span class="bg-light-danger badge badge-danger">Declined</span>';
                    $status_color = 'text-danger';
                }

                if ($value->approved_date == '') {
                    $date = '---';
                } else {
                    $date = date('d M y', strtotime($value->approved_date));
                }

                if ($value->wallet_type === 'ib') {
                    $client_type = '<span class="bg-warning badge badge-warning">IB</span>';
                } else {
                    $client_type = '<span class="bg-success badge badge-success">Trader</span>';
                }

                if ($value->approved_by == '') {
                    $admin_name = '--';
                    $admin_email = '--';
                } else {
                    $admin_id = $value->approved_by;
                    $admin_data = User::where('id', $admin_id)->select('name', 'email')->first();

                    if ($admin_data) {
                        $admin_name = $admin_data->name;
                        $admin_email = $admin_data->email;
                    } else {
                        $admin_name = '--';
                        $admin_email = '--';
                    }
                }

                $data[$i]['name'] = '<a href="#" data-id=' . $value->id . ' class="dt-description  justify-content-start"><span class="w"> <i class="plus-minus" data-feather="plus"></i> </span> <span class="' . $status_color . '">' . ucfirst($value->name) . '</span></a>';
                $data[$i]['email'] = $value->email;
                $data[$i]['client_type'] = $client_type;
                $data[$i]['admin_name'] = $admin_name;
                $data[$i]['admin_email'] = $admin_email;
                $data[$i]['amount'] = '$' . $value->amount;
                $data[$i]['status'] = $status;
                $data[$i]['request_at'] = date('d M y', strtotime($value->created_at));
                $amount += $value->amount;

                $i++;
            }
            return Response::json([
                "draw" => $request->draw,
                "recordsTotal" => $count,
                "recordsFiltered" => $count,
                "data" => $data,
                'total_amount' => round($amount, 2),
            ]);
        } catch (\Throwable $th) {
            // throw $th;

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
            ->leftJoin('other_transactions', 'deposits.other_transaction_id', '=', 'other_transactions.id')->first();

        $innerTH = "";
        $innerTD = "";
        if (strtolower($deposit->transaction_type) === 'bank') {
            $bank_info = AdminBank::select()->where('id', $deposit->bank_id)->first();
            $mult_currency = ($deposit->local_currency) ? $deposit->local_currency : '----';
            $mult_currency_type = $deposit->currency;
            $amount = '$' . $deposit->amount;

            $innerTH .= '
                <th>Amount in USD</th>
                <th>Amount in ' . $mult_currency_type . '</th>
                <th>Bank Name</th>
                <th>Bank AC Name</th>
                <th>Bank AC No</th>
                <th>Bank Swift Code</th>
                <th>Bank IBAN</th>
                <th>Bank Country</th>';
            $innerTD .= '
                <td>' . $amount . '</td>
                <td>' . $mult_currency . '</td>
                <td>' . $bank_info->bank_name . '</td>
                <td>' . $bank_info->account_name . '</td>
                <td>' . $bank_info->account_number . '</td>
                <td>' . $bank_info->swift_code . '</td>
                <td>' . $bank_info->ifsc_code . '</td>
                <td>' . $bank_info->bank_country . '</td>
                ';
        } else if (strtolower($deposit->transaction_type) === 'crypto') {
            $amount = '$' . $deposit->amount;
            $hash_type = $deposit->otherTransaction->block_chain;
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
                <th>' . $amount . '</th>
                <th>' . $deposit->otherTransaction->crypto_type . '</th>
                <th><a href="$url" target="_blank">' . $deposit->transaction_id . '</a></th>
                <th>' . $deposit->otherTransaction->crypto_amount . '</th>';
        } else if ($deposit->transaction_type === 'Perfect Money') {

            $amount = '$' . $deposit->amount;
            $innerTH .= '
                <th>Amount Request</th>
                <th>Transaction ID</th>
                <th>Order ID</th>';
            $innerTD .= '
                <th>' . $amount . '</th>
                <th>' . $deposit->transaction_id . '</th>
                <th>' . $deposit->order_id . '</th>';
        } else if (strtolower($deposit->transaction_type) === 'help2pay') {
            $amount = '$' . $deposit->amount;
            $currency = $deposit->local_currency . '(' . $deposit->currency . ')';
            $innerTH .= '
                <th>Amount Request</th>
                <th>Help2Pay ID</th>
                <th>IDR Amount</th>';
            $innerTD .= '
                <th>' . $amount . '</th>
                <th>' . $deposit->order_id . '</th>
                <th>' . $currency . '</th>';
        } else if (($deposit->transaction_type) === 'PayPal') {
            $amount = '$' . $deposit->amount;
            $currency = $deposit->local_currency . '(' . $deposit->currency . ')';
            $innerTH .= '
                <th>Amount Request</th>
                <th>Transaction ID</th>
                <th>Invoice ID</th>';
            $innerTD .= '
                <th>' . $amount . '</th>
                <th>' . $deposit->transaction_id . '</th>
                <th>' . $deposit->invoice_id . '</th>';
        } else {

            $note = ($deposit->note) ? $deposit->note : '---';
            $approved_by = User::find($deposit->approved_by);
            if (isset($approved_by)) {
                $name = ($approved_by->name) ? $approved_by->name : '---';
            } else {
                $name = '---';
            }

            $amount = '$' . $deposit->amount;
            $charge_amount = '$' . $deposit->charge;
            $innerTH .= '
                <th>Amount Request</th>
                <th>Charge Amount</th>
                <th>Approved By</th>
                <th>Note</th>';
            $innerTD .= '
                <th>' . $amount . '</th>
                <th>' . $charge_amount . '</th>
                <th>' . $name . '</th>
                <th>' . $note . '</th>';
        }
        $transaction_type = ucfirst($deposit->transaction_type);
        $description = '
        <tr class="description" style="display:none">
            <td colspan="8">
                <div class="details-section-dark border-start-3 border-start-primary p-2 " style="display: flow-root;">
                    <span class="details-text">
                          ' . $transaction_type . '  Details:
                    </span>
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
