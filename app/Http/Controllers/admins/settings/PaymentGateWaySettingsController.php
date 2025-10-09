<?php

namespace App\Http\Controllers\admins\settings;

use App\Http\Controllers\Controller;
use App\Models\CopyUser;
use App\Models\PaymentGatewayConfig;
use App\Services\systems\AdminLogService;
use Database\Seeders\PaymentGateway;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class PaymentGateWaySettingsController extends Controller
{
    //
    public function index(Request $request)
    {
        try {
            // help2pay configured
            $help2pay = PaymentGatewayConfig::where('gateway_name', 'help2pay')->first();
            // b2binpay configured
            $b2binpay = PaymentGatewayConfig::where('gateway_name', 'b2binpay')->first();
            $paypal = PaymentGatewayConfig::where('gateway_name', 'paypal')->first();
            $praxis = PaymentGatewayConfig::where('gateway_name', 'praxis')->first();
            $nowpay = PaymentGatewayConfig::where('gateway_name', 'nowpay')->first();
            return view(
                'admins.settings.payment-gateway',
                [
                    'help2pay' => $help2pay,
                    'b2binpay' => $b2binpay,
                    'paypal' => $paypal,
                    'praxis' => $praxis,
                    'nowpay'=>$nowpay
                ]
            );
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
    // configure help2pay
    public function help2pay(Request $request)
    {
        try {
            $validation_rules = [
                'merchant' => 'required|string|max:100',
                'security_code' => 'required|string|max:255',
            ];
            $validator = Validator::make($request->all(), $validation_rules);
            if ($validator->fails()) {
                return Response::json([
                    'status' => false,
                    'message' => "Please fix the following errors",
                    'errors' => $validator->errors(),
                ]);
            }

            // Find the record if it already exists
            $existingRecord = PaymentGatewayConfig::where('gateway_name', 'help2pay')->first();

            // Initialize an array to store the old data
            $oldData = [];

            if ($existingRecord) {
                // If the record exists, store its old data
                $oldData = $existingRecord->getOriginal();
            }
            $create = PaymentGatewayConfig::updateOrCreate(
                [
                    'gateway_name' => 'help2pay',
                ],
                [

                    'merchent_code' => $request->merchant,
                    'api_secret' => $request->security_code,
                    'admin_log' => AdminLogService::admin_log(
                        'update help2pay',
                        ['old' => $oldData, 'new' => $request->all()]
                    )
                ]
            );
            if ($create) {
                return Response::json([
                    'status' => true,
                    'message' => 'Help2pay Successfully configured'
                ]);
            }
            return Response::json([
                'status' => false,
                'message' => 'Something went wrong please try again later.'
            ]);
        } catch (\Throwable $th) {
            // throw $th;
            return Response::json([
                'status' => false,
                'message' => "An unexpected error occurred while processing your request."
            ]);
        }
    }
    // b2binpay configuration
    public function b2binpay(Request $request)
    {
        try {
            $validation_rules = [
                'wallet_id' => 'required|string|max:100',
                'login' => 'required|string|max:255',
                'password' => 'required|string|max:255',
            ];
            $validator = Validator::make($request->all(), $validation_rules);
            if ($validator->fails()) {
                return Response::json([
                    'status' => false,
                    'message' => "Please fix the following errors",
                    'errors' => $validator->errors(),
                ]);
            }

            // Find the record if it already exists
            $existingRecord = PaymentGatewayConfig::where('gateway_name', 'b2binpay')->first();

            // Initialize an array to store the old data
            $oldData = [];

            if ($existingRecord) {
                // If the record exists, store its old data
                $oldData = $existingRecord->getOriginal();
            }
            $create = PaymentGatewayConfig::updateOrCreate(
                [
                    'gateway_name' => 'b2binpay',
                ],
                [
                    'merchent_code' => $request->wallet_id,
                    'password' => $request->password,
                    'user_name' => $request->login,
                    'admin_log' => AdminLogService::admin_log(
                        'update b2binpay',
                        ['old' => $oldData, 'new' => $request->all()]
                    )
                ]
            );
            if ($create) {
                return Response::json([
                    'status' => true,
                    'message' => 'B2BinPay Successfully configured'
                ]);
            }
            return Response::json([
                'status' => false,
                'message' => 'Something went wrong please try again later.'
            ]);
        } catch (\Throwable $th) {
            // throw $th;
            return Response::json([
                'status' => false,
                'message' => "An unexpected error occurred while processing your request."
            ]);
        }
    }
    // paypal configuration
    public function paypal(Request $request)
    {
        try {
            $validation_rules = [
                'mode' => 'required',
                'client_id' => 'required|string|max:255',
                'client_secret' => 'required|string|max:255',
            ];
            $validator = Validator::make($request->all(), $validation_rules);
            if ($validator->fails()) {
                return Response::json([
                    'status' => false,
                    'message' => "Please fix the following errors",
                    'errors' => $validator->errors(),
                ]);
            }

            // Find the record if it already exists
            $existingRecord = PaymentGatewayConfig::where('gateway_name', 'paypal')->first();

            // Initialize an array to store the old data
            $oldData = [];

            if ($existingRecord) {
                // If the record exists, store its old data
                $oldData = $existingRecord->getOriginal();
            }
            $create = PaymentGatewayConfig::updateOrCreate(
                [
                    'gateway_name' => 'paypal',
                ],
                [
                    'api_token' => $request->client_id,
                    'api_secret' => $request->client_secret,
                    'mode' => $request->mode,
                    'admin_log' => AdminLogService::admin_log(
                        'update paypal',
                        ['old' => $oldData, 'new' => $request->all()]
                    )
                ]
            );
            if ($create) {
                return Response::json([
                    'status' => true,
                    'message' => 'paypal Successfully configured'
                ]);
            }
            return Response::json([
                'status' => false,
                'message' => 'Something went wrong please try again later.'
            ]);
        } catch (\Throwable $th) {
            // throw $th;
            return Response::json([
                'status' => false,
                'message' => "An unexpected error occurred while processing your request."
            ]);
        }
    }
    // praxis configuration
    public function praxis(Request $request)
    {
        try {
            $validation_rules = [
                'application_key' => 'required|string|max:255',
                'merchant_id' => 'required|string|max:255',
                'merchant_secret' => 'required|string|max:255',
            ];
            $validator = Validator::make($request->all(), $validation_rules);
            if ($validator->fails()) {
                return Response::json([
                    'status' => false,
                    'message' => "Please fix the following errors",
                    'errors' => $validator->errors(),
                ]);
            }

            // Find the record if it already exists
            $existingRecord = PaymentGatewayConfig::where('gateway_name', 'praxis')->first();

            // Initialize an array to store the old data
            $oldData = [];

            if ($existingRecord) {
                // If the record exists, store its old data
                $oldData = $existingRecord->getOriginal();
            }
            $create = PaymentGatewayConfig::updateOrCreate(
                [
                    'gateway_name' => 'praxis',
                ],
                [
                    'api_token' => $request->application_key,
                    'api_secret' => $request->merchant_secret,
                    'merchent_code' => $request->merchant_id,
                    'admin_log' => AdminLogService::admin_log(
                        'update praxis',
                        ['old' => $oldData, 'new' => $request->all()]
                    ),
                    'mode' => 'live'
                ]
            );
            if ($create) {
                return Response::json([
                    'status' => true,
                    'message' => 'praxis Successfully configured'
                ]);
            }
            return Response::json([
                'status' => false,
                'message' => 'Something went wrong please try again later.'
            ]);
        } catch (\Throwable $th) {
            // throw $th;
            return Response::json([
                'status' => false,
                'message' => "An unexpected error occurred while processing your request."
            ]);
        }
    }
    public function nowpay(Request $request)
    {
        try {
            $validation_rules = [
                'api_key' => 'required|string|max:255',
                'ipn_secret' => 'required|string|max:255',
            ];
            $validator = Validator::make($request->all(), $validation_rules);
            if ($validator->fails()) {
                return Response::json([
                    'status' => false,
                    'message' => "Please fix the following errors",
                    'errors' => $validator->errors(),
                ]);
            }

            // Find the record if it already exists
            $existingRecord = PaymentGatewayConfig::where('gateway_name', 'nowpay')->first();

            // Initialize an array to store the old data
            $oldData = [];

            if ($existingRecord) {
                // If the record exists, store its old data
                $oldData = $existingRecord->getOriginal();
            }
            $create = PaymentGatewayConfig::updateOrCreate(
                [
                    'gateway_name' => 'nowpay',
                ],
                [
                    'api_token' => $request->input('api_key'),
                    'api_secret' => $request->input('ipn_secret'),
                    'admin_log' => AdminLogService::admin_log(
                        'update nowpayment',
                        ['old' => $oldData, 'new' => $request->all()]
                    ),
                    'mode' => 'live'
                ]
            );
            if ($create) {
                return Response::json([
                    'status' => true,
                    'message' => 'NOWPayments Successfully configured'
                ]);
            }
            return Response::json([
                'status' => false,
                'message' => 'Something went wrong please try again later.'
            ]);
        } catch (\Throwable $th) {
            // throw $th;
            return Response::json([
                'status' => false,
                'message' => "An unexpected error occurred while processing your request."
            ]);
        }
    }
}
