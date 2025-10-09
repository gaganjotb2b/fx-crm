<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\PriceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class CurrencyConvertController extends Controller
{
    public function __invoke(Request $request)
    {
        $from = strtoupper($request->input('currency_from'));
        $to = strtoupper($request->input('currency_to'));
        $amount = $request->input('amount');
        try {
            $validator = Validator::make($request->all(),[
                'currency_from'=>'required',
                'currency_to'=>'required',
                'amount'=>'required|numeric'
            ]);
            if ($validator->fails()) {
                return Response::json([
                    'status'=>false,
                    'message'=>'Validation error, please fix the following error',
                    'errors'=>$validator->errors(),
                ]);
            }

            $result = (float)PriceService::crypto_price($from, $to);
            $converted_amount = $result;
            if (strtolower($from) === 'usd') {
                $converted_amount = $amount * $result;
            } else {
                $converted_amount = $amount / $result;
            }
            return Response::json([
                'status' => true,
                'data' => [
                    "$from" => (float)$amount,
                    "$to" => $converted_amount
                ],
            ]);
        } catch (\Throwable $th) {
            // throw $th;
            return Response::json([
                'status' => false,
                'data' => [
                    "$from" => (float)$amount,
                    "$to" => 0
                ],
            ]);
        }
    }
    // convert from sicentific notation
    private function convart($amount)
    {
        if ($this->isScientificNotation($amount)) {
            $scientificNotation = $amount;
            // Split the scientific notation string into base and exponent
            list($base, $exponent) = explode('e', $scientificNotation);
            // Convert base to float
            $base = (float) $base;
            // Convert exponent to int
            $exponent = (int) $exponent;
            // Calculate the original decimal number
            $decimalNumber = $base * pow(10, $exponent);
            return $decimalNumber;
        }
        return $amount;
    }
    private function isScientificNotation($str)
    {
        // Regular expression to match scientific notation
        $pattern = '/^[+-]?\d*\.?\d+([eE][+-]?\d+)?$/';
        // Use preg_match to test if the string matches the pattern
        return preg_match($pattern, $str);
    }
}
