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

class IbNoCommissionController extends Controller
{
    public function __construct() {
        // system module control
        $this->middleware(AllFunctionService::access('ib_management', 'admin'));
        $this->middleware(AllFunctionService::access('no_commission_list', 'admin'));
    }
    public function noCommissionList()
    {
        return view('admins.ib-management.no_commission_list');
    }
    public function getNoCommissionList(Request $request)
    {
        $draw = $request->input('draw');
        $start = $request->input('start');
        $length = $request->input('length');

        // filter request start
        $status   = $request->status;
        $ticket   = $request->ticket;
        $login  = $request->login;
        $symbol  = $request->symbol;
        $volume  = $request->volume;
        $open_time  = $request->open_time;
        $close_time  = $request->close_time;
        //  find no commission details 
        $result = ComTrade::select('com_trades.*')->where('status', '!=', 'pending')->where('status', '!=', 'credited');
        // filter start
        if ($status != "") {
            $result = $result->where('status', 'LIKE', '%' . $status . '%');
        }
        if ($ticket != "") {
            $result = $result->where('ticket', 'LIKE', '%' . $ticket . '%');
        }
        if ($login != "") {
            $result = $result->where('trading_account', 'LIKE', '%' . $login . '%');
        }
        if ($symbol != "") {
            $result = $result->where('symbol', 'LIKE', '%' . $symbol . '%');
        }
        if ($volume != "") {
            $result = $result->where('volume', 'LIKE', '%' . $volume . '%');
        }
        if ($open_time != "") {
            $result = $result->whereDate('open_time', '>=', $open_time);
        }
        if ($close_time != "") {
            $result = $result->whereDate('open_time', '<=', $close_time);
        }
        // filter end
        $count = $result->count();
        $recordsTotal = $count;
        $recordsFiltered = $count;
        $result = $result->orderBy('id', 'DESC')->skip($start)->take($length)->get();


        $data = array();
        $i = 0;
        $reason = "";
        // $result = ComTrade::select('com_trades.*')->orderBy('id', 'DESC')->skip($start)->take($length)->first();
        foreach ($result as $item) {
            if($item->status == 'comNotFound'){
                $reason = 'Commission Not Found';
            }else if($item->status == 'groupIgnore'){
                $reason = 'For Group Ignore';
            }else if($item->status == 'timeIgnore'){
                $reason = 'For Time Ignore';
            }else if($item->status == 'single'){
                $reason = 'Single Trades';
            }
            $data[$i]["ticket"]   = $item->ticket;
            $data[$i]["trading_account"]   = $item->trading_account;
            $data[$i]["symbol"] = $item->symbol;
            $data[$i]["volume"] = $item->volume;
            $data[$i]["open_time"]  = date('d M, Y H:i:s A', strtotime($item->open_time));
            $data[$i]["close_time"]  = date('d M, Y H:i:s A', strtotime($item->close_time));
            $data[$i]["profit"] = $item->profit;
            $data[$i]["comment"] = $item->comment;
            $data[$i]["reason"] = $reason;
            // $data[$i]["action"] = '<button type="button" class="btn btn-primary">View</button>';
            $i++;
        }
       
        $output = array('draw' => $draw, 'recordsTotal' => $recordsTotal, 'recordsFiltered' => $recordsFiltered);
        $output['data'] = $data;
        return Response::json($output);
    }
}
