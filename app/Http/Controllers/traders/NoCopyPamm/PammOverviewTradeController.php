<?php

namespace App\Http\Controllers\traders\NoCopyPamm;

use App\Http\Controllers\Controller;
use App\Models\PammTrade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class PammOverviewTradeController extends Controller
{
    public function open_trade(Request $request)
    {
        try {
            $columns = ['order', 'login', 'open_time', 'close_time', 'symbol', 'volume', 'open_price', 'profit'];

            $order = $columns[$request->order[0]['column']];
            $result = PammTrade::where(function ($query) {
                $query->where('close_time', '1970-01-01 00:00:00')
                    ->orWhereNull('close_time');
            })
                ->where('login', $request->input('account'));

            // Start search
            if (isset($request->search['value'])) {
                $search =  $request->search['value'];
                $result->where(function ($q) use ($search) {
                    $q->where('order', $search)
                        ->orWhere('login', $search)
                        ->orWhere('open_time', 'LIKE', $search . '%')
                        ->orWhere('symbol', 'LIKE', '%' . $search . '%')
                        ->orWhere('volume', (float)$search)
                        ->orWhere('open_price', 'LIKE', '%' . $search . '%');
                });
            }

            $count = $result->count();
            $result = $result->orderBy($order, $request->order[0]['dir'])->skip($request->start)->take($request->length)->get();

            $data = [];
            foreach ($result as $value) {
                $data[] = [
                    'ticket' => $value->order,
                    'account' => $value->login,
                    'open_time' => $value->open_time,
                    'symbol' => $value->symbol,
                    'volume' => $value->volume,
                    'open_price' => $value->open_price,
                    'status' => 'Trade running....'
                ];
            }

            return Response::json([
                'draw' => $request->input('draw'),
                'recordsTotal' => $count,
                'recordsFiltered' => $count,
                'data' => $data,
            ]);
        } catch (\Throwable $th) {
            // throw $th;
            return Response::json([
                'draw' => $request->draw,
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => [],
            ]);
        }
    }

    // close trades
    public function close_trade(Request $request)
    {
        try {
            $columns = ['order', 'login', 'open_time', 'close_time', 'symbol', 'volume', 'open_price', 'profit'];
            $order = $columns[$request->order[0]['column']];
            $result = PammTrade::where(function ($query) {
                $query->whereNot('close_time', '1970-01-01 00:00:00')
                    ->orWhereNotNull('close_time');
            })
                ->where('cmd', '!=', 9)
                ->where('login', $request->account);
            // Start search
            if (isset($request->search['value'])) {
                $search =  $request->search['value'];
                $result->where(function ($q) use ($search) {
                    $q->where('order', $search)
                        ->orWhere('login', $search)
                        ->orWhere('open_time', 'LIKE', '%' . $search . '%')
                        ->orWhere('symbol', 'LIKE', '%' . $search . '%')
                        ->orWhere('volume', 'LIKE', '%' . (float)$search . '%')
                        ->orWhere('open_price', 'LIKE', '%' . (float)$search . '%');
                });
            }

            $count_result = clone $result;
            $result = $result->orderBy($order, $request->order[0]['dir'])->skip($request->start)->take($request->length)->get();

            $count = $count_result->count();
            $data = [];
            foreach ($result as $value) {
                // loss|profit arrow
                $ticket = '';
                $profit = '';
                if ($value->profit > 0) {
                    $arrow = asset('trader-assets/assets/img/pamm/logo/arro-circle-up.png');
                    $ticket = '<span class="d-flex justify-content-between">
                                <span><img class="" src="' . $arrow . '"></span>
                                <span style="color:#0f7a0b">' . $value->order . '</span>
                            </span>';
                    $profit = '<span style="color:#0f7a0b">' . $value->profit . '</span>';
                } else {
                    $arrow = asset('trader-assets/assets/img/pamm/logo/arro-circle-down.png');
                    $ticket = '<span class="d-flex justify-content-between">
                                <span><img class="" src="' . $arrow . '"></span>
                                <span style="color:#ff8e31">' . $value->order . '</span>
                            </span>';
                    $profit = '<span style="color:#ff8e31">' . $value->profit . '</span>';
                }
                $data[] = [
                    'ticket' => $ticket,
                    'account' => $value->login,
                    'open_time' => $value->open_time,
                    'close_time' => $value->close_time,
                    'symbol' => $value->symbol,
                    'volume' => $value->volume,
                    'open_price' => $value->open_price,
                    'profit' => $profit
                ];
            }

            return Response::json([
                'draw' => $request->draw,
                'recordsTotal' => $count,
                'recordsFiltered' => $count,
                'data' => $data,
            ]);
        } catch (\Throwable $th) {
            throw $th;
            return Response::json([
                'draw' => $request->draw,
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => [],
            ]);
        }
    }
}
