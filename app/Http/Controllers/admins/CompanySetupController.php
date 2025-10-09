<?php

namespace App\Http\Controllers\Admins;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\admin\SystemConfig;
use App\Models\TransactionSetting;
use App\Services\AllFunctionService;
use Carbon\Carbon;
use PhpParser\Builder\Trait_;

class CompanySetupController extends Controller
{
    public function __construct()
    {
        $this->middleware(["role:company setup"]);
        $this->middleware(["role:settings"]);
        // system module control
        $this->middleware(AllFunctionService::access('settings', 'admin'));
        $this->middleware(AllFunctionService::access('company_setup', 'admin'));
    }
    public function companySetup()
    {
        $com_email       = [];
        $com_phone       = [];
        $com_social_info = [];
        $configs = SystemConfig::select()->first();
        if (isset($configs->com_email)) {
            $com_email = json_decode($configs->com_email);
        }
        if (isset($configs->com_phone)) {
            $com_phone = json_decode($configs->com_phone);
        }
        if (isset($configs->com_social_info)) {
            $com_social_info = json_decode($configs->com_social_info);
        }
        // }
        return view('admins.settings.company_setup', [
            'configs'         => $configs,
            'com_email'       => $com_email,
            'com_phone'       => $com_phone,
            'com_social_info' => $com_social_info,
        ]);
    }
    // add company setup
    public function companySetupAdd(Request $request)
    {
        $validation_rules = [
            'com_name'      => 'required',
            'com_license'   => 'required',
            'com_email_1'   => 'required|email',
            'com_phone_1'   => 'required',
            'copyright'     => 'required',
            'support_email' => 'required|email',
            'auto_email'    => 'required|email',
            'whatsapp'    => 'nullable|numeric',
        ];
        $validator = Validator::make($request->all(), $validation_rules);
        if ($validator->fails()) {
            return Response::json(
                [
                    'status' => false,
                    'errors' => $validator->errors(),
                    'message'=>'Please fix the following errors'
                ]);
        } else {
            //company email store
            $com_email = [
                'com_email_1' => (isset($request->com_email_1)) ? $request->com_email_1 : '',
                'com_email_2' => (isset($request->com_email_2)) ? $request->com_email_2 : '',
            ];
            $com_email = json_encode($com_email);
            //company phone store
            $com_phone = [
                'com_phone_1' => (isset($request->com_phone_1)) ? $request->com_phone_1 : '',
                'com_phone_2' => (isset($request->com_phone_2)) ? $request->com_phone_2 : '',
            ];
            $com_phone = json_encode($com_phone);
            //start company social media
            $com_social_info = [
                'facebook'  => (isset($request->facebook)) ? $request->facebook : '',
                'twitter'   => (isset($request->twitter)) ? $request->twitter : '',
                'skype'     => (isset($request->skype)) ? $request->skype : '',
                'youtube'   => (isset($request->youtube)) ? $request->youtube : '',
                'telegram'  => (isset($request->telegram)) ? $request->telegram : '',
                'instagram'  => (isset($request->instagram)) ? $request->instagram : '',
                'line'  => (isset($request->line)) ? $request->line : '',
                'whatsapp'  => (isset($request->whatsapp)) ? $request->whatsapp : '', 
            ];
            $com_social_info = json_encode($com_social_info);
            //end company social media
            $data = [
                'com_name'          => $request->com_name,
                'com_email'         => $com_email,
                'com_phone'         => $com_phone,
                'com_license'       => $request->com_license,
                'com_website'       => $request->com_website,
                'com_address'       => $request->com_address,
                'com_authority'     => $request->com_authority,
                'com_social_info'   => $com_social_info,
                'copyright'         => $request->copyright,
                'privacy_statement' => $request->privacy_statement,
                'support_email'     => $request->support_email,
                'auto_email'        => $request->auto_email,
            ];
            if (SystemConfig::where('id', $request->config_id)->update($data)) {
                return Response::json(['status' => true, 'message' => 'Successfully Updated.']);
            } else {
                return Response::json(['status' => false, 'message' => 'Failed To Update!']);
            }
        }
    }
}
