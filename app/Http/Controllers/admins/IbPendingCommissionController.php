<?php

namespace App\Http\Controllers\Admins;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserDescription;
use App\Models\ConfuseTrade;
use App\Models\ComTrade;
use App\Services\AllFunctionService;
use Illuminate\Support\Facades\Response;

class IbPendingCommissionController extends Controller
{
    public function __construct()
    {
        // system module control
        $this->middleware(AllFunctionService::access('ib_management', 'admin'));
        $this->middleware(AllFunctionService::access('pending_commission_list', 'admin'));
    }
    public function pendingCommissionList()
    {
        return view('admins.ib-management.pending_commission_list');
    }
    public function getPendingCommissionList(Request $request)
    {
        try {
            // find confuse trades
            $confuse_trades = ConfuseTrade::all();
            $count = $confuse_trades->count();
            // filter request start
            $status   = $request->status;
            $ticket   = $request->ticket;
            $login  = $request->login;
            $symbol  = $request->symbol;
            $volume  = $request->volume;
            $open_time  = $request->open_time;
            $close_time  = $request->close_time;

            $data = array();
            $i = 0;
            foreach ($confuse_trades as $confuse_trade) {
                $result = ComTrade::select('com_trades.*')
                    ->where('ticket', $confuse_trade->ticket);
                // ----------------------------------------------------------------------
                //  filter start
                // ---------------------------------------------------------------------
                //Filter By Status
                if ($status != "") {
                    $result = $result->where('status', 'LIKE', '%' . $status . '%');
                }
                //Filter By Ticket
                if ($ticket != "") {
                    $result = $result->where('ticket', 'LIKE', '%' . $ticket . '%');
                }
                
                //Filter By Symbol
                if ($symbol != "") {
                    $result = $result->where('symbol', 'LIKE', '%' . $symbol . '%');
                }
                //Filter By Volume
                if ($volume != "") {
                    $result = $result->where('volume', 'LIKE', '%' . $volume . '%');
                }
                //Filter By Date
                if ($open_time != "") {
                    $result = $result->whereDate('open_time', '>=', $open_time);
                }
                if ($close_time != "") {
                    $result = $result->whereDate('open_time', '<=', $close_time);
                }
                //
                if ($login != "") {
                    $result = $result->where('trading_account', 'LIKE', '%' . $login . '%');
                }
                // filter end
                $result = $result->orderBy('id', 'DESC')->skip($request->start)->take($request->length)->get();
                foreach ($result as $result) {
                    $data[$i]["ticket"]   = $result->ticket;
                    $data[$i]["trading_account"]   = $result->trading_account;
                    $data[$i]["symbol"] = $result->symbol;
                    $data[$i]["volume"] = $result->volume;
                    $data[$i]["open_time"]  = date('d M, Y H:i:s A', strtotime($result->open_time));
                    $data[$i]["close_time"]  = date('d M, Y H:i:s A', strtotime($result->close_time));
                    $data[$i]["profit"] = $result->profit;
                    $data[$i]["comment"] = $result->comment;
                    $i++;
                }
            }
            return Response::json([
                'draw' => $request->draw, 
                'recordsTotal' => $count, 
                'recordsFiltered' => $count,
                'data' => $data
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return Response::json([
                'draw' => $request->draw, 
                'recordsTotal' => 0, 
                'recordsFiltered' => 0,
                'data' => []
            ]);
        }
    }
}
