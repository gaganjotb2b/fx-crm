<?php

namespace App\Http\Controllers\admins\SocialTrade;

use App\Services\CopyApiService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class AdminSocialTradesController extends Controller
{
	public function SocialTrade(Request $request)
	{
		return view('admins.socialTrade.social-trade-report');
	}
	public function SocialTradeProcess(Request $request)
{
    try {
        $copy_mt5 = new CopyApiService();
        $conditions = [];

        // Adding filters dynamically
        if (!empty($request->type)) {
            $conditions[] = "type = '" . addslashes($request->type) . "'";
        }
        if (!empty($request->master_account)) {
            $conditions[] = "master = '" . addslashes($request->master_account) . "'";
        }
        if (!empty($request->slave_account)) {
            $conditions[] = "slave = '" . addslashes($request->slave_account) . "'";
        }
        if (!empty($request->status)) {
            $conditions[] = "action = '" . addslashes($request->status) . "'";
        }
        if (!empty($request->date_from)) {
            $conditions[] = "created_at >= '" . addslashes($request->date_from) . "'";
        }
        if (!empty($request->date_to)) {
            $conditions[] = "created_at <= '" . addslashes($request->date_to) . "'";
        }

        // Construct final SQL query
        $sql = "SELECT * FROM copy_activities";
        if (!empty($conditions)) {
            $sql .= " WHERE " . implode(" AND ", $conditions);
        }

        // Get total records count before pagination
        $count_sql = str_replace("SELECT *", "SELECT COUNT(*) as total", $sql);
        $req_data = ['command' => 'Custom', 'data' => ["sql" => $count_sql]];
        $count_res = json_decode($copy_mt5->apiCall($req_data));

        $recordsTotal = isset($count_res->data[0]->total) ? (int) $count_res->data[0]->total : 0;
        $recordsFiltered = $recordsTotal;

        // Apply ordering
        $orderColumnIndex = $request->order[0]['column'] ?? 4; // Default to created_at
        $orderDirection = $request->order[0]['dir'] ?? 'desc';

        $columns = ['master', 'slave', 'action', 'type', 'created_at'];
        $orderColumn = $columns[$orderColumnIndex] ?? 'created_at';

        // Apply pagination
        $start = (int) ($request->start ?? 0);
        $length = (int) ($request->length ?? 10);

        $sql .= " ORDER BY $orderColumn $orderDirection LIMIT $start, $length";

        // Make API request
        $req_data = ['command' => 'Custom', 'data' => ["sql" => $sql]];
        $result = json_decode($copy_mt5->apiCall($req_data));

        if (!$result || !isset($result->data)) {
            return response()->json([
                'draw' => $request->draw,
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => []
            ]);
        }

        // Prepare response data
        $data = [];
        foreach ($result->data as $row) {
            $data[] = [
                $row->master,
                $row->slave,
                $row->action,
                "Social Trade",
                // $row->type,
                $row->created_at
            ];
        }

        return response()->json([
            'draw' => $request->draw,
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data
        ]);
    } catch (\Throwable $th) {
        return response()->json([
            'draw' => $request->draw ?? 0,
            'recordsTotal' => 0,
            'recordsFiltered' => 0,
            'data' => [],
            'error' => $th->getMessage()
        ]);
    }
}

}
