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
use App\Services\CombinedService;

class IbBalanceAddController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware(["role:trader deposit report"]);
    //     $this->middleware(["role:reports"]);

    //     // system module control
    //     $this->middleware(AllFunctionService::access('reports', 'admin'));
    //     $this->middleware(AllFunctionService::access('trader_deposit', 'admin'));
    // }
    public function ibBalanceReport(Request $request)
    {
        $deposit = Deposit::select('transaction_type')->distinct()->get();
        return view('admins.reports.ib-add-balance-report', ['deposit' => $deposit]);
    }

    public function ibBalanceDescription(Request $request)
    {
        try {

            $columns = ['name', 'email', 'transaction_type', 'created_by', 'approved_status', 'approved_date', 'created_at', 'amount'];
            $orderby = $columns[$request->order[0]['column']];
            $result = TraderDeposit::select(
                'deposits.id',
                'deposits.user_id',
                'users.name',
                'users.email',
                'users.kyc_status',
                'deposits.transaction_type',
                'deposits.approved_status',
                'deposits.created_by',
                'deposits.created_at',
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
                ->where('wallet_type', 'ib');
            $approved_status = $request->approved_status;

            //-----------------------------------------------------------------------------------
            //Filter Start
            //-----------------------------------------------------------------------------------

            //Filter By Transaction Type
            if ($request->transaction_type != "") {
                $result = $result->where("transaction_type", $request->transaction_type);
            }
            //Filter By Approved Status
            if ($approved_status != "") {
                $result = $result->where("approved_status", $approved_status);
            }

            //Filter By KYC Verification Status
            if ($request->verification_status != "") {
                $result = $result->where('users.kyc_status', $request->verification_status);
            }
            // filter by created by
            if ($request->created_by != "") {
                $result = $result->where('deposits.created_by', $request->created_by);
            }
            //Filter by account manager desk manager
            if ($request->manager_info != "") {
                $manager = $request->manager_info;
                $manager_id = User::select('id')
                    ->where(function ($query) use ($manager) {
                        $query->where('users.name', 'LIKE', '%' . $manager . '%')
                            ->orWhere('users.email', 'LIKE', '%' . $manager . '%')
                            ->orWhere('users.phone', 'LIKE', '%' . $manager . '%');
                    })->get()->pluck('id');
                $users_id = ManagerUser::select('user_id')->whereIn('manager_id', $manager_id)->get()->pluck('user_id');
                $result = $result->whereIn('deposits.user_id', $users_id);
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
            //Filter by trading account number
            if ($request->trading_acc != "") {
                $users_id = TradingAccount::select('user_id')
                    ->where('account_number', $request->trading_acc)->first();
                $trader_id = IB::whereIn('ib.reference_id', $users_id)->pluck('ib_id');
                $result = $result->whereIn('deposits.user_id', $trader_id);
            }
            //Filter By Amount
            if ($request->min != "") {
                $result = $result->where("deposits.amount", '>=', $request->min);
            }
            if ($request->max != "") {
                $result = $result->where("deposits.amount", '<=', $request->max);
            }

            //Filter By Reques Date
            if ($request->from != "") {
                $result = $result->whereDate("deposits.created_at", '>=', $request->from);
            }
            if ($request->to != "") {
                $result = $result->whereDate("deposits.created_at", '<=', $request->to);
            }

            // filter search script end
            $count = $result->count();
            $total_amount = $result->sum('amount');
            $result = $result->orderby($orderby, $request->order[0]['dir'])->skip($request->start)->take($request->length)->get();
            $data = array();
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

                $data[] = [
                    'name' => '<a href="#" data-id="' . $value->id . '" class="dt-description  justify-content-start"><span class="w"> <i class="plus-minus" data-feather="plus"></i> </span> <span class="' . $status_color . '">' . ucfirst($value->name) . '</span></a>',
                    'email' => $value->email,
                    'transaction_type' => ucwords($value->transaction_type),
                    'created_by' => ucwords(str_replace('_', ' ', $value->created_by)),
                    'approved_status' => $status,
                    'request_at' => date('d M y', strtotime($value->created_at)),
                    'approved_at' => $date,
                    'amount' => '$' . $value->amount,
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

    public function ib_inner_description(Request $request, $id)
    {

        $deposit = Deposit::where('deposits.id', $id)
            ->leftJoin('other_transactions', 'deposits.other_transaction_id', '=', 'other_transactions.id')
            ->select(
                'other_transactions.block_chain',
                'other_transactions.crypto_type',
                'other_transactions.crypto_amount',
                'deposits.*'
            )
            ->first();

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
                <td>' . $bank_info->bank_country . '</td>';
        } else if (strtolower($deposit->transaction_type) === 'crypto') {
            $amount = '$' . $deposit->amount;
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
                <th>' . $amount . '</th>
                <th>' . $deposit->crypto_type . '</th>
                <th><a href="$url" target="_blank">' . $deposit->transaction_id . '</a></th>
                <th>' . $deposit->crypto_amount . '</th>';
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
        $innerTH1 = "";
        $innerTD1 = "";
        $approved_by = "";
        if ($deposit->approved_status === 'A' || $deposit->approved_status === 'D') {
            $approved_by = ($deposit->approved_status) == 'A' ? 'Approved By' : 'Declined By';
            $admin_info = User::select('name', 'email')->where('id', $deposit->approved_by)->first();
            $admin_name = isset($admin_info->name) ? $admin_info->name : '---';
            $admin_email = isset($admin_info->email) ? $admin_info->email : '---';
            $admin_json_data = json_decode($deposit->admin_log);
            $ip = isset($admin_json_data->ip) ? $admin_json_data->ip : $deposit->ip_address;
            $winName = isset($admin_json_data->wname) ? $admin_json_data->wname : $deposit->device_name;
            $action_date = isset($deposit->approved_date) ? date('d M Y,h:i A', strtotime($deposit->approved_date)) : '---';

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
            'description' => $description,
        ];
        return Response::json($data);
    }
}
