<?php

namespace App\Http\Controllers\systems;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\admin\SystemConfig;
use App\Models\RequiredField;
use App\Models\TransactionSetting;
use Carbon\Carbon;
use PhpParser\Builder\Trait_;

class SystemSoftwareSettingController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware(["role:software settings"]);
    //     $this->middleware(["role:settings"]);
    // }
    public function softwareSetting()
    {
        $configs = SystemConfig::select()->first();
        $required_fields = RequiredField::select()->first();
        return view('systems.configurations.software_setting', [
            'configs' => $configs,
            'required_fields' => $required_fields
        ]);
    }
    // software setting
    public function softwareSettingAdd(Request $request)
    {
        
        $config = SystemConfig::select('id')->first();
        $update = SystemConfig::updateOrCreate(
            [
                'id' => ($config) ? $config->id : 1,
            ],
            [
                'crm_type'              => strtolower($request->crm_type),
                'create_meta_acc'       => ($request->create_meta_acc == "on") ? 1 : 0,
                'platform_book'         => (isset($request->platform_book)) ? strtolower($request->platform_book) : "",
                'social_account'        => ($request->social_account == "on") ? 1 : 0,
                'acc_limit'             => (isset($request->acc_limit)) ? $request->acc_limit : 0,
                'brute_force_attack'    => (isset($request->brute_force_attack)) ? $request->brute_force_attack : 0,
            ]
        );
        if ($update) {
            return Response::json([
                'status' => true,
                'message' => 'Successfully Updated.'
            ]);
        }
        return Response::json([
            'status' => false,
            'message' => 'Failed To Update!'
        ]);
    }
    // setup required field
    public function required_fields(Request $request)
    {
        $config = RequiredField::select('id')->first();
        $update = RequiredField::updateOrCreate(
            [
                'id' => ($config) ? $config->id : 1,
            ],
            [
                'phone'     => ($request->phone == "on") ? 1 : 0,
                'gender'    => ($request->gender == "on") ? 1 : 0,
                'password'  => ($request->password == "on") ? 1 : 0,
                'country'   => ($request->country == "on") ? 1 : 0,
                'state'     => ($request->state == "on") ? 1 : 0,
                'city'      => ($request->city == "on") ? 1 : 0,
                'zip_code'  => ($request->zip_code == "on") ? 1 : 0,
                'address'   => ($request->address == "on") ? 1 : 0,
            ]
        );
        if ($update) {
            return Response::json([
                'status'=>true,
                'message'=>'Field require setup successfully updated!'
            ]);
        }
        return Response::json([
            'status'=>false,
            'message'=>'Field require setup Failed!',
        ]);
    }
}
