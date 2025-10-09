<?php

namespace App\Http\Controllers\IB\Reports;

use App\Http\Controllers\Controller;
use App\Models\BankAccount;
use App\Models\Country;
use App\Models\WalletUpDown;
use App\Models\Withdraw;
use App\Services\AllFunctionService;
use App\Services\DataTableService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

class IbWithdrawReportController extends Controller
{
    public function __construct()
    {
        $this->middleware(AllFunctionService::access('withdraw_report', 'ib'));
        $this->middleware(AllFunctionService::access('reports', 'ib'));
        $this->middleware('is_ib');
    }
    public function withdrawReports(Request $request)
    {
        if ($request->action == 'table') {
            return $this->withdrawReportDT($request);
        }
        return view('ibs.reports.withdraw_report');
    }

    public function withdrawReportDT(Request $request)
    {
        try {
            $columns = ['withdraws.id', 'users.name', 'users.email', 'amount', 'transaction_type', 'withdraws.approved_status', 'withdraws.created_at'];
            $result_1 = Withdraw::select(
                'withdraws.id as withdraw_id',
                'withdraws.approved_date',
                'withdraws.amount',
                'withdraws.transaction_type',
                'withdraws.approved_status',
                'withdraws.created_at',
                'users.name',
                'users.email'
            )
                ->join('users', 'withdraws.user_id', '=', 'users.id')
                ->where('wallet_type', 'ib')
                ->where('user_id', Auth::id());

            // // custom filtering
            // if ($request->status !== '') {
            //     $result_1 = $result_1->where('withdraws.approved_status', $request->status);
            // }
            // if ($request->femail !== '') {
            //     $result_1 = $result_1->where('users.email', $request->femail);
            // }
            // if ($request->fname !== '') {
            //     $result_1 = $result_1->where('users.name', $request->fname);
            // }
            // if ($request->min !== "") {
            //     $result_1 = $result_1->where("withdraws.amount", '>=', $request->min);
            // }
            // if ($request->max !== "") {
            //     $result_1 = $result_1->where("withdraws.amount", '<=', $request->max);
            // }
            // if ($request->from !== "") {
            //     $result_1 = $result_1->whereDate('withdraws.created_at', '>=', Carbon::parse($request->from)->format('Y-m-d'));
            // }
            // if ($request->to !== "") {
            //     $result_1 = $result_1->whereDate('withdraws.created_at', '<=', Carbon::parse($request->to)->format('Y-m-d'));
            // }

            $result = $result_1;

            // Search if columns field has search data
            if (isset($request->search['value'])) {
                $search_value = $request->search['value'];
                $result = $result->where(function ($query) use ($search_value) {
                    $query->where('amount', $search_value)
                        ->orWhere('transaction_type', $search_value)
                        ->orWhere('status', $search_value);
                });
            }

            $count = $result->count();
            $result = $result->orderBy($columns[$request->order[0]['column']], $request->order[0]['dir'])->skip($request->start)->take($request->length)->get();

            $data = [];
            foreach ($result as $row) {
                $status = $row->approved_status;
                if ($status == 'A') {
                    $status = '<span class="bg-light-success badge badge-success">Approved</span>';
                } else if ($status == 'P') {
                    $status = '<span class="bg-light-warning badge badge-warning">Pending</span>';
                } else {
                    $status = '<span class="bg-light-danger badge badge-danger">Declined</span>';
                }

                $data[] = [
                    "id" => $row->withdraw_id,
                    "name" => $row->name,
                    "email" => $row->email,
                    "amount" => '$' . $row->amount,
                    "transaction_type" => ucwords($row->transaction_type),
                    "approved_status" => $status,
                    "created_at" => Carbon::parse($row->created_at)->format('d-m-Y'),
                    "extra" => $this->withdrawReportExtra($row->withdraw_id),
                ];
            }

            return response()->json([
                'draw' => $request->input('draw'),
                'recordsTotal' => $count,
                'recordsFiltered' => $count,
                'data' => $data
            ]);
        } catch (\Throwable $th) {
            throw $th;
            return response()->json([
                'draw' => $request->input('draw'),
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => []
            ]);
        }
    }

    private function withdrawReportExtra($id)
    {
        $withdraw = Withdraw::with(['bankAccount', 'otherTransaction'])->find($id);
        $button = "";
        if ($withdraw->approved_status == 'P') {
            $button .= '<button type="button" class="btn btn-sm btn-danger btn-decline" data-id="'.$id.'" style="padding: 4px 14px; font-size: 12px;">Decline</button>';
        }
        $innerTH = "";
        $innerTD = "";
        $withdraw_amount = '$' . $withdraw->amount;
        if (strtolower($withdraw->transaction_type) === 'bank') {

            $swift_code = $withdraw->bankAccount && $withdraw->bankAccount['bank_swift_code'] !== '' ? $withdraw->bankAccount['bank_swift_code'] : '---';
            $iban = ($withdraw->bankAccount['bank_iban'] !== '') ? $withdraw->bankAccount['bank_iban'] : '----';
            $country = Country::select('name')->where('id', $withdraw->bankAccount['bank_country'])->first();
            $innerTH .= '
                <th>Amount Request</th>
                <th>Bank Name</th>
                <th>Bank AC Name</th>
                <th>Bank AC No</th>
                <th>Bank Swift Code</th>
                <th>Bank IBAN</th>
                <th>Bank Country</th>';
            $innerTD .= '
                <td>' . $withdraw_amount . '</td>
                <td>' . ($withdraw->bankAccount['bank_name'] ? $withdraw->bankAccount['bank_name'] : '') . '</td>
                <td>' . ($withdraw->bankAccount['bank_ac_name'] ? $withdraw->bankAccount['bank_ac_name'] : '') . '</td>
                <td>' . ($withdraw->bankAccount['bank_ac_number'] ? $withdraw->bankAccount['bank_ac_number'] : '') . '</td>
                <td>' . $swift_code . '</td>
                <td>' . $iban . '</td>
                <td>' . $country->name . '</td>';
        } else if (strtolower($withdraw->transaction_type) === 'crypto') {
            $innerTH .= '
                <th>Amount Request</th>
                <th>Crypto Type</th>
                <th>Address</th>
                <th>Crypto Amount</th>';
            $innerTD .= '
                <td>' . $withdraw_amount . '</td>
                <td>' . $withdraw->otherTransaction['crypto_type'] . '</td>
                <td>' . $withdraw->otherTransaction['crypto_address'] . '</td>
                <td>' . $withdraw->otherTransaction['crypto_amount'] . '</td>';
        } else {
            $innerTH .= '
                <th>Amount Request</th>
                <th>Account Name</th>
                <th>Account Email</th>';
            if (isset($withdraw->otherTransaction['account_name'])) {
                $account_name = $withdraw->otherTransaction['account_name'];
            } else {
                $account_name = "";
            }

            if (isset($withdraw->otherTransaction['account_email'])) {
                $account_email = $withdraw->otherTransaction['account_email'];
            } else {
                $account_email = "";
            }

            $innerTD .= '
                <th>' . $withdraw->otherTransaction['amount'] . '</th>
                <th>' . $account_name . '</th>
                <th>' . $account_email . '</th>';
        }
        $transaction_type = ucwords($withdraw->transaction_type);
        $description = '
            <div class="details-section-dark border-start-3 border-start-primary p-2">
                <h4>
                    ' . $transaction_type . ' Details:
                </h4>
                
                <table class="table table-striped text-center">
                    <thead>
                        <tr class="table-secondary">
                            ' . $innerTH . '
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            ' . $innerTD . '
                        </tr>
                    </tbody>
                </table>
                ' . $button . '
            </div>';

        return $description;
    }
}
