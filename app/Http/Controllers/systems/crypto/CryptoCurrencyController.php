<?php

namespace App\Http\Controllers\systems\crypto;

use App\Http\Controllers\Controller;
use App\Models\CryptoCurrency;
use App\Services\PriceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class CryptoCurrencyController extends Controller
{
    function index(Request $request)
    {
        try {
            return view('systems.crypto.crypto-currency');
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
    // store crypto currency
    public function store(Request $request)
    {
        try {
            $validation_rules = [
                'crypto_symbol' => 'required|string',
                'crypto_currency' => 'required|string',
                'payment_currency' => 'required|string'
            ];
            $validator = Validator::make($request->all(), $validation_rules);
            if ($validator->fails()) {
                return Response::json([
                    'status' => false,
                    'message' => 'Please fix the following errors',
                    'errors' => $validator->errors(),
                ]);
            }
            // create db row
            $create = CryptoCurrency::create([
                'symbol' => $request->crypto_symbol,
                'currency' => $request->crypto_currency,
                'payment_currency' => $request->payment_currency,
                'status' => 'active',
                'ip_address' => $request->ip(),
                'created_by' => auth()->user()->id,
            ]);
            if ($create) {
                return Response::json([
                    'status' => true,
                    'message' => 'Crypto currency successfully added',
                ]);
            }
            return Response::json([
                'status' => false,
                'message' => 'Crypto currency could not added, got a database error',
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return Response::json([
                'status' => false,
                'message' => 'Got a server error!'
            ]);
        }
    }
    // datatable
    public function datatable(Request $request)
    {
        try {
            $columns = ['symbol', 'currency', 'payment_currency', 'status', 'created_at'];
            $orderby = $columns[$request->order[0]['column']];
            // select type= 0 for trader 
            $result = CryptoCurrency::select();

            $count = $result->count(); // <------count total rows
            $result = $result->orderby($orderby, $request->order[0]['dir'])->skip($request->start)->take($request->length)->get();
            $data = array();
            foreach ($result as $key => $value) {
                $data[] = [
                    'crypto_symbol' => $value->symbol,
                    'crypto_currency' => $value->currency,
                    'payment_currency' => $value->payment_currency,
                    'status' => ucwords($value->status),
                    'date' => date('d M Y', strtotime($value->created_at)),
                    'action' => '<button class="btn btn-primary btn-sm btn-edit" data-id="' . $value->id . '"><i data-feather="edit"></i></button>'
                ];
            }
            return Response::json([
                'draw' => $_REQUEST['draw'],
                'recordsTotal' => $count,
                'recordsFiltered' => $count,
                'data' => $data,
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    // get currency
    public function currency(Request $request)
    {
        try {
            $result = CryptoCurrency::where('symbol', $request->symbol)->get();
            return $result;
        } catch (\Throwable $th) {
            //throw $th;
            return [];
        }
    }
    // convert currency value
    public function convert(Request $request)
    {
        try {
            $result = PriceService::crypto_price($request->fsymbol, $request->tsymbol);
            $result = $result * $request->amount;
            return $result;
        } catch (\Throwable $th) {
            // throw $th;
        }
    }
}
