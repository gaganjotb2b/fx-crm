<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CryptoAddress;
use App\Models\CurrencySetup;
use App\Models\SoftwareSetting;
use Database\Seeders\SoftwareSetings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class CurrencySettupController extends Controller
{
    public function get_currency(Request $request)
    {
        try {
            $currency = CurrencySetup::select('id', 'currency', 'rate', 'transaction_type')->get();
            return response()->json([
                'status' => true,
                'data' => $currency,
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'status' => true,
                'data' => [],
            ]);
        }
    }
    public function multi_currency(Request $request)
    {
        try {
            $software_settings = SoftwareSetting::select('is_multicurrency as multicurrency')->first();
            return Response::json([
                'status' => true,
                'data' => $software_settings,
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return Response::json([
                'status' => false,
                'data' => []
            ]);
        }
    }
    // get crypto address
    public function get_crypto_address(Request $request)
    {
        try {
            $crypto_address = CryptoAddress::where(function ($query) {
                $query->where('verify_1', 1)
                    ->where('verify_2', 1)
                    ->where('status', 1);
            })
                ->where('name', $request->input('currency'))
                ->where('block_chain', $request->input('block_chain'))
                ->first();
            return Response::json([
                'status' => true,
                'data' => $crypto_address
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return Response::json([
                'status' => false,
                'message' => 'Got a server error, please contact for support',
                'errors' => $th->getMessage(),
                'data' => []
            ]);
        }
    }
    // get cuurency crypto
    public function get_crypto_currency(Request $request)
    {
        try {
            $block_chain = CryptoAddress::where(function ($query) {
                $query->where('verify_1', 1)
                    ->where('verify_2', 1)
                    ->where('status', 1);
            })->select('block_chain')->distinct('block_chain')->get();
            $block_chain_array = [];
            foreach ($block_chain as $key => $value) {
                $currency = CryptoAddress::where(function ($query) {
                    $query->where('verify_1', 1)
                        ->where('verify_2', 1)
                        ->where('status', 1);
                })->where('block_chain', $value->block_chain)->get()->pluck('name')->toArray();
                $block_chain_array[] = [
                    'block_chain' => 'USDT',
                    'currency' => $currency
                ];
            }
            return Response::json([
                'status' => true,
                'data' => $block_chain_array,
            ]);
        } catch (\Throwable $th) {
            // throw $th;
            return Response::json([
                'status' => false,
                'message' => 'Got a server error, please contact for support',
                'errors' => $th->getMessage(),
                'data' => []
            ]);
        }
    }
}
