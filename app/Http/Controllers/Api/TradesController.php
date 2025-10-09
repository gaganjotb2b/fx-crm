<?php

namespace App\Http\Controllers\Api;

ini_set('serialize_precision', 14);

use App\Http\Controllers\Controller;
use App\Models\Mt5Trade;
use App\Services\trades\TradesReportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class TradesController extends Controller
{
    public function get_trades_report(Request $request)
    {

        try {
            $validator = Validator::make($request->all(), [
                'per_page' => 'nullable|numeric|min:1',
                'account_number' => 'nullable|numeric|exists:trading_accounts,account_number',
                'ticket_number' => 'nullable|numeric',
                'symbol' => 'nullable|string|max:10',
                'min_volume' => 'nullable|string|max:10',
            ]);
            if ($validator->fails()) {
                return Response::json([
                    'status' => false,
                    'message' => 'Validation error, please fix the following errors',
                    'errors' => $validator->errors(),
                ]);
            }
            $result = Mt5Trade::whereHas('account', function ($query) {
                $query->where('user_id', auth()->guard('api')->user()->id);
            });
            // filter by account number
            if ($request->input('account_number')) {
                $result = $result->where('LOGIN', $request->account_number);
            }
            // filter by ticket
            if ($request->input('ticket_number')) {
                $result = $result->where('TICKET', $request->input('ticket_number'));
            }
            // filter by symbol
            if ($request->input('symbol')) {
                $result = $result->where('SYMBOL', $request->input('symbol'));
            }
            // filter by min volume
            if ($request->input('min_volume')) {
                $result = $result->where('VOLUME', '>=', $request->input('min_volume'));
            }
            // filter by max volume
            if ($request->input('max_volume')) {
                $result = $result->where('VOLUME', '<=', $request->input('max_volume'));
            }
            $result = $result->paginate($request->input('per_page', 5));
            if ($result) {
                return Response::json([
                    'status' => true,
                    'data' => $result,
                ]);
            }
            return Response::json([
                'status' => false,
                'message' => 'No data found',
                'data' => []
            ]);
        } catch (\Throwable $th) {
            // throw $th;
            return Response::json([
                'status' => false,
                'message' => 'No data found',
                'data' => []
            ]);
        }
    }
}
