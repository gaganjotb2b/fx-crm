<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SoftwareSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class SuspendController extends Controller
{
    public function mobile_app_suspend(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'api_key' => 'required|exists:software_settings,app_key'
            ]);
            if ($validator->fails()) {
                return Response::json([
                    'status' => false,
                    'message' => $validator->errors()->first('api_key'),
                ]);
            }
            $software_settings = SoftwareSetting::first();
            $software_settings->app_status = 'block';
            $update = $software_settings->save();
            if ($update) {
                return Response::json([
                    'status' => true,
                    'message' => 'Mobile app successfully suspended',
                ]);
            }
            return Response::json([
                'status' => false,
                'message' => 'Something went wrong, please try again later'
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return Response::json([
                'status' => false,
                'message' => 'Got a server error, ' . $th->getMessage(),
            ]);
        }
    }
    public function mobile_app_unsuspend(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'api_key' => 'required|exists:software_settings,app_key'
            ]);
            if ($validator->fails()) {
                return Response::json([
                    'status' => false,
                    'message' => $validator->errors()->first('api_key'),
                ]);
            }
            $software_settings = SoftwareSetting::first();
            $software_settings->app_status = 'active';
            $update = $software_settings->save();
            if ($update) {
                return Response::json([
                    'status' => true,
                    'message' => 'Mobile app successfully un-suspended',
                ]);
            }
            return Response::json([
                'status' => false,
                'message' => 'Something went wrong, please try again later'
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return Response::json([
                'status' => false,
                'message' => 'Got a server error, ' . $th->getMessage(),
            ]);
        }
    }
    // crm suspention
    public function crm_suspend(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'api_key' => 'required|exists:software_settings,app_key'
            ]);
            if ($validator->fails()) {
                return Response::json([
                    'status' => false,
                    'message' => $validator->errors()->first('api_key'),
                ]);
            }
            $software_settings = SoftwareSetting::first();
            $software_settings->crm_status = 'block';
            $update = $software_settings->save();
            if ($update) {
                return Response::json([
                    'status' => true,
                    'message' => 'CRM successfully suspended',
                ]);
            }
            return Response::json([
                'status' => false,
                'message' => 'Something went wrong, please try again later'
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return Response::json([
                'status' => false,
                'message' => 'Got a server error, ' . $th->getMessage(),
            ]);
        }
    }


    public function crm_unsuspend(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'api_key' => 'required|exists:software_settings,app_key'
            ]);
            if ($validator->fails()) {
                return Response::json([
                    'status' => false,
                    'message' => $validator->errors()->first('api_key'),
                ]);
            }
            $software_settings = SoftwareSetting::first();
            $software_settings->crm_status = 'active';
            $update = $software_settings->save();
            if ($update) {
                return Response::json([
                    'status' => true,
                    'message' => 'CRM successfully un-suspended',
                ]);
            }
            return Response::json([
                'status' => false,
                'message' => 'Something went wrong, please try again later'
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return Response::json([
                'status' => false,
                'message' => 'Got a server error, ' . $th->getMessage(),
            ]);
        }
    }
}
