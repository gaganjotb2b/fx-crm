<?php

namespace App\Http\Controllers\admins\SocialTrade;

use App\Http\Controllers\Controller;
use App\Services\CopyApiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class PammProfileController extends Controller
{
    private $copy_api;
    public function __construct()
    {
        $this->copy_api = new CopyApiService('mt4');
    }
    //get pamm profile data
    public function get_pamm_profile(Request $request)
    {
        $result = json_decode($this->copy_api->apiCall([
            'command' => 'Custom',
            'data' => [
                'sql' => "SELECT * FROM copy_users WHERE account=" . $request->account,
            ]
        ]));
        // dd($result->data);
        return Response::json([
            'status' => true,
            'account_number' => isset($result->data[0]->account) ? $result->data[0]->account : '',
            'user_name' => isset($result->data[0]->username) ? $result->data[0]->username : '',
            'min_deposit' => isset($result->data[0]->min_deposit) ? $result->data[0]->min_deposit : '',
            'max_deposit' => isset($result->data[0]->max_deposit) ? $result->data[0]->max_deposit : '',
            'share_profit' => isset($result->data[0]->share_profit) ? $result->data[0]->share_profit : '',
        ]);
    }
    // update pamm profile
    public function update_pamm_profile(Request $request)
    {
        if ($request->ajax()) {
            $copy_mt5 = new CopyApiService();

            $result = $copy_mt5->apiCall('get/slaves_copy_trades', [
                'start' => $request->start ?? 1,
                'length' => $request->length ?? 10,
                'isnew' => $request->isnew ?? 1,
                'order_by' => $request->order ?? 'order',
                'dir' => $request->dir ?? 'desc',
            ]);

            return DataTables::of($result->data)
                ->addColumn('status', function ($row) {
                    return $row->status ? 'Active' : 'Closed';
                })
                ->rawColumns(['status'])
                ->make(true);
        }

        return view('copy-trade-report');
    }

    public function exportCsv(Request $request)
    {
        $copy_mt5 = new CopyApiService();

        $result = $copy_mt5->apiCall('get/slaves_copy_trades', [
            'start' => 1,
            'length' => 1000,
            'isnew' => 1,
            'order_by' => 'order',
            'dir' => 'desc',
        ]);

        header("Content-Type: text/csv; charset=utf-8");
        header("Content-Disposition: attachment; filename=copy-trading-report-" . time() . ".csv");

        $output = fopen("php://output", "w");
        fputcsv($output, ['MASTER', 'SLAVE', 'TRADE NUMBER', 'SYMBOL', 'VOLUME', 'OPEN TIME', 'CLOSE TIME', 'PROFIT', 'STATUS']);

        foreach ($result->data as $res) {
            fputcsv($output, (array) $res);
        }

        fclose($output);
    }
}
