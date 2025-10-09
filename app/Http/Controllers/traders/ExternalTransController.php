<?php

namespace App\Http\Controllers\Traders;

use App\Http\Controllers\Controller;
use App\Models\ExternalFundTransfers;
use App\Models\User;
use App\Services\AllFunctionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class ExternalTransController extends Controller
{
    public function __construct()
    {
        $this->middleware(AllFunctionService::access('external_transfer_report', 'trader'));
        $this->middleware(AllFunctionService::access('reports', 'trader'));
    }
    public function externalReport(Request $request)
    {
        $op = $request->input('op');
        if ($op == "data_table") {
            return $this->externalReportDT($request);
        }
        return view('traders.reports.user_external_report');
    }

    public function externalReportDT($request)
    {
        try {
            $draw = $request->input('draw');
            $start = $request->input('start');
            $length = $request->input('length');
            $order = $_GET['order'][0]["column"];
            $orderDir = $_GET["order"][0]["dir"];

            $columns = ['status', 'status', 'type', 'status', 'charge', 'created_at', 'amount'];
            $orderby = $columns[$order];
            $result = ExternalFundTransfers::select(
                'external_fund_transfers.sender_id',
                'external_fund_transfers.receiver_id',
                'external_fund_transfers.status',
                'external_fund_transfers.charge',
                'external_fund_transfers.type',
                'external_fund_transfers.created_at',
                'external_fund_transfers.amount',
                'external_fund_transfers.id as transaction_id'
            )
            ->with(['sender', 'receiver'])
            ->where(function ($query) {
                $query->where('receiver_id', auth()->user()->id)
                      ->orWhere('sender_id', auth()->user()->id);
            })
            ->where(function ($query) {
                $query->where(function ($q) {
                    $q->where('sender_id', auth()->user()->id)
                      ->where('sender_wallet_type', 'trader')
                      ->orWhere('sender_wallet_type', 'ib');
                })
                ->orWhere(function ($q) {
                    $q->where('receiver_id', auth()->user()->id)
                      ->where('receiver_wallet_type', 'trader');
                });
            });


            $total_amount = $result->sum('amount');

            /*<-------filter search script start here------------->*/

            if ($request->type != "") {
                if ($request->type == 'send') {
                    $sender_id = ExternalFundTransfers::select('sender_id')->where('sender_id', auth()->user()->id)->get();
                    $filter_sender_id = [];
                    foreach ($sender_id as $id) {
                        array_push($filter_sender_id, $id->sender_id);
                    }

                    $result = $result->whereIn('external_fund_transfers.sender_id', $filter_sender_id);
                    $total_amount = $result->where('external_fund_transfers.sender_id', '=', $filter_sender_id)->sum('amount');
                } else if ($request->type == 'receive') {

                    $sender_id = ExternalFundTransfers::select('receiver_id')->where('receiver_id', auth()->user()->id)->get();
                    $filter_receiver_id = [];
                    foreach ($sender_id as $id) {
                        array_push($filter_receiver_id, $id->receiver_id);
                    }

                    $result = $result->whereIn('external_fund_transfers.receiver_id', $filter_receiver_id);
                    $total_amount = $result->where('external_fund_transfers.receiver_id', '=', $filter_receiver_id)->sum('amount');
                }
            }

            if ($request->approved_status != "") {
                $result = $result->where('external_fund_transfers.status', $request->approved_status);
            }

            if ($request->txnID != "") {
                $result = $result->where('txnid', '=', $request->txnID);
            }

            if ($request->min != "") {
                $result = $result->where('external_fund_transfers.amount', '>=', $request->min);
            }

            if ($request->max != "") {
                $result = $result->where('external_fund_transfers.amount', '<=', $request->max);
            }

            if ($request->from != "") {
                $result = $result->whereDate('external_fund_transfers.created_at', '>=', $request->from);
            }

            if ($request->to != "") {
                $result = $result->whereDate('external_fund_transfers.created_at', '<=', $request->to);
            }
            //name email filter
            if ($request->info != "") {
                $sender_id = User::select('id')->where('name', $request->info)->orWhere('email', $request->info)->first();
                if (isset($sender_id)) {
                    $sender_id_external = ExternalFundTransfers::select('sender_id')->where('sender_id', $sender_id->id)->get();
                    $filter_id = [];
                    foreach ($sender_id_external as $ex_id) {
                        array_push($filter_id, $ex_id->sender_id);
                    };
                    $result = $result->whereIn('sender_id', $filter_id);
                } else {
                    $result = $result->where('external_fund_transfers.sender_id', null);
                }
            }
            $count = $result->count();
            $result = $result->orderby($orderby, $orderDir)->skip($start)->take($length)->get();
            $data = array();
            $i = 0;
            $amount = 0;
            foreach ($result as $user) {

                if ($user->status == 'P') {
                    $status = '<span class="bg-light-warning badge badge-warning">Pending</span>';
                } elseif ($user->status == 'A') {
                    $status = '<span class="bg-light-success badge badge-success">Approved</span>';
                } elseif ($user->status == 'D') {
                    $status = '<span class="bg-light-danger badge badge-danger">Declined</span>';
                }
                // sender or receiver
                $sender = isset($user->sender->email) ? $user->sender->email : '---';
                if (strtolower(auth()->user()->email) === strtolower($sender)) {
                    $sender = '<i class="fas fa-circle text-info" style="font-size: 10px;margin-right: 4px;"></i>' . $sender;
                }
                $receiver = isset($user->receiver->email) ? $user->receiver->email : '---';
                if (strtolower(auth()->user()->email) === strtolower($receiver)) {
                    $receiver = '<i class="fas fa-circle text-info" style="font-size: 10px;margin-right: 4px;"></i>' . $receiver;
                }
                $type = (auth()->user()->id == $user->sender_id) ? 'Send' : 'Receive';
                $data[$i]['sender_email'] = $sender;
                $data[$i]['receiver_email'] = $receiver;
                $data[$i]['type'] = $type;
                $data[$i]['date'] = date('d F y, h:i A', strtotime($user->created_at));
                $data[$i]['status'] = $status;
                $data[$i]['charge'] = '$' . $user->charge;
                $data[$i]['amount'] = '$' . $user->amount;
                $amount += $user->amount;
                $i++;
            }
            return Response::json([
                'draw' => $draw,
                'recordsTotal' => $count,
                'recordsFiltered' => $count,
                'data' => $data,
                'total' => ['$'.round($amount, 2)],
            ]);
        } catch (\Throwable $th) {
            // throw $th;
            return Response::json([
                'draw' => $request->draw,
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => [],
                'total' => [0],
            ]);
        }
    }
}
