<?php

namespace App\Http\Controllers\admins\SocialTrade;

use App\Http\Controllers\Controller;
use App\Models\TradingAccount;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use App\Services\CopyApiService;

class AdminCopyTradeReportController extends Controller
{
    public function CopyTradeReport()
    {
        return view('admins.socialTrade.copy-trade-report');
    }


    public function CopyTradeReportProcess(Request $request)
    {
        $copy_mt5 = new CopyApiService();
        $result = $copy_mt5->apiCall('get/slaves_copy_trades', [
            'start' => $request->start,
            'length' => $request->length,
            'isnew' => $request->isnew,
            'order_by' => strtolower($request->order),
            'dir' => $request->dir,
        ]);
        if (is_string($result)) {
            $result = json_decode($result);
        }
        if (isset($request['export'])) {
            header("Content-Type: text/csv; charset=utf-8");
            header("Content-Disposition: attachment; filename=copy-trading-report-" . time() . ".csv");
            $output = fopen("php://output", "w");
            fputcsv($output, ['TRADE NUMBER', 'LOGIN', 'SYMBOL', 'VOLUME', 'OPEN TIME', 'CLOSE TIME', 'PROFIT', 'STATUS']);

            foreach ($result->data as $res) {
                array_shift($res);
                fputcsv($output, $res);
            }
            fclose($output);
        } else {
            return Response::json($result);
        }
    }



    public function CopyTradeReportProcessDetail(Request $request)
    {
        $copy_mt5 = new CopyApiService('mt5');

        $master = $request['master'];
        $slave = $request['slave'];
        $trade = $request['trade'];

        $sdata = [
            'command' => 'Custom',
            'data' => [
                'sql' => "SELECT `share_profit` FROM copy_users WHERE `account` = $master"
            ]
        ];

        $copy_users = json_decode($copy_mt5->apiCall($sdata));

        $tdata = [
            'command' => 'Custom',
            'data' => [
                'sql' => "SELECT `type` FROM copy_slaves WHERE `slave` = $slave"
            ]
        ];

        $copy_slaves = json_decode($copy_mt5->apiCall($tdata));


        $vdata = [
            'command' => 'Custom',
            'data' => [
                'sql' => "SELECT copy_trades.`Profit`, copy_master_profits.`broker_profit`, copy_master_profits.`amount`, copy_master_profits.`status` 
	            FROM copy_trades JOIN copy_master_profits ON copy_trades.`Order` = copy_master_profits.`slave_order`
	            WHERE `Order` = $trade"
            ]
        ];

        $copy_trades = json_decode($copy_mt5->apiCall($vdata));

        if (isset($copy_trades->data) && count($copy_trades->data) > 0) {
            // The data array exists and has at least one element
            $profit = $copy_trades->data[0]->Profit;
            $broker_comm = $copy_trades->data[0]->broker_profit;
            $comm_status = $copy_trades->data[0]->status;
        } else {
            // The data array is empty or does not exist
            $profit = null;
            $broker_comm = null;
            $comm_status = null;
            // or any other default value that you want to use
        }




        $type = '';

        if ($copy_slaves->data[0]->type == 'mam') {
            $type = "MAM ORDER";
        } else if ($copy_slaves->data[0]->type == 'pamm') {
            $type = "PAMM ORDER";
        }

        $commission_p = $copy_users->data[0]->share_profit ?? 0;


        if ($profit != 0 && $commission_p != 0 && $profit > 0) {

            $commission = $copy_trades->data[0]->amount;
        } else {
            $commission = 0;
        }

        $m_email = "----";
        $s_email = "----";
        if (TradingAccount::select('user_id')->where('account_number', $master)->exists()) {
            $master = TradingAccount::select('user_id')->where('account_number', $master)->first();
            $master_email = User::select('email')->where('id', $master->user_id)->first();
            $m_email = ($master_email->email) ? $master_email->email : '----';
        }

        if (TradingAccount::select('user_id')->where('account_number', $slave)->exists()) {
            $slave2 = TradingAccount::select('user_id')->where('account_number', $slave)->first();
            $slave_email = User::select('email')->where('id', $slave2->user_id)->first();
            $s_email = ($slave_email->email) ? $slave_email->email : '----';
        }


        $details =  '<div class="details-section-dark dt-details border-start-3 border-start-primary p-2">
                  <div class="row">
                    <div class="col-lg-6">
                        <div class="rounded-0 w-75">
                            <table class="table table-responsive tbl-balance">
                                <tr>
                                    <th>Master</th>
                                    <td>' . $m_email . '</td>
                                </tr>
                                <tr>
                                    <th>Slave</th>
                                    <td>' . $s_email . '</td>
                                </tr>
                                <tr>
                                    <th>Type</th>
                                    <td>' . $type . '</td>
                                </tr>
                                <tr>
                                    <th>Commission</th>
                                    <td>' . $commission_p . '</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="col-lg-6 d-flex justfy-content-between">    
                        <div class="rounded-0 w-100">
                            <table class="table table-responsive tbl-trader-details">
                                <tr>
                                    <th>Commission</th>
                                    <td>$' . number_format($commission, 2, '.', '') . '</td>
                                </tr>
                                <tr>
                                    <th>Broker Commission</th>
                                    <td>$' . number_format($broker_comm, 2, '.', '') . '</td>
                                </tr>
                                <tr>
                                    <th>Commission Status</th>
                                    <td>' . strtoupper($comm_status ?? 'Not Found') . '</td>
                                </tr>
                                
                            </table>
                        </div> 
                       
                    </div>
                </div>
                </div>';

        return Response::json($details);
    }
}
