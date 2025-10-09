<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\admin\SystemConfig;
use App\Models\RequiredField;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class SignupSettingsController extends Controller
{
    public function get_settings(Request $request)
    {
        try {
            $systemconfig = SystemConfig::select('address_section as address_tab', 'social_account as social_tab', 'create_meta_acc as account_tab')->first()->toArray();
            $required_fields = RequiredField::select('password as password_tab')->first()->toArray('password_tab');
            $final_data = array_merge($systemconfig, $required_fields);
            return response()->json([
                'status' => true,
                'data' => $final_data,
            ]);
        } catch (\Throwable $th) {
            // throw $th;
            return response()->json([
                'status' => false,
                'data' => [
                    'address_tab' => 0,
                    'social_tab' => 0,
                    'account_tab' => 0,
                    'password_tab' => 0,
                ]
            ]);
        }
    }
    // get required field
    public function get_required_field(Request $request)
    {
        try {
            $required_fields = RequiredField::select(
                'phone',
                'gender',
                'password',
                'country',
                'state',
                'city',
                'zip_code',
                'address',

            )->first()->toArray();
            return response()->json([
                'status' => true,
                'data' => $required_fields
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return Response::json([
                'status' => false,
                'data' => [
                    'phone' => 1,
                    'gender' => 1,
                    'password' => 1,
                    'country' => 1,
                    'state' => 1,
                    'city' => 1,
                    'zip_code' => 1,
                    'address' => 1,
                ]
            ]);
        }
    }
}
