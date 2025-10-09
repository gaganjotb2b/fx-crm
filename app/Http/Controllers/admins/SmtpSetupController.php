<?php

namespace App\Http\Controllers\Admins;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\admin\SystemConfig;
use App\Models\SmtpSetup;
use App\Models\TransactionSetting;
use App\Services\AllFunctionService;
use Carbon\Carbon;
use PhpParser\Builder\Trait_;

class SmtpSetupController extends Controller
{
    public function __construct()
    {
        $this->middleware(["role:smtp setup"]);
        $this->middleware(["role:settings"]);
        // system module control
        $this->middleware(AllFunctionService::access('settings', 'admin'));
        $this->middleware(AllFunctionService::access('smtp_setup', 'admin'));
    }
    public function smtpSetup()
    {
        $configs = SmtpSetup::select()->first();
        return view('admins.settings.smtp_setup', [
            'configs' => $configs,
        ]);
    }
    public function smtpSetupAdd(Request $request)
    {

        $validation_rules = [
            'mail_driver'       => 'required',
            'host'              => 'required',
            'port'              => 'required',
            'mail_user'         => 'required',
            'mail_password'     => 'required',
            'mail_encryption'   => 'required',
        ];
        $validator = Validator::make($request->all(), $validation_rules);
        if ($validator->fails()) {
            return Response::json(
                [
                    'status' => false,
                    'errors' => $validator->errors(),
                    'message' => 'Please fix the following errors!'
                ]
            );
        } else {
            $smtp = SmtpSetup::select()->first();

            $create = SmtpSetup::updateOrCreate(
                [
                    'id' => ($smtp) ? $smtp->id : ''
                ],
                [
                    'mail_driver'     => $request->mail_driver,
                    'host'            => $request->host,
                    'port'            => $request->port,
                    'mail_user'       => $request->mail_user,
                    'mail_password'   => $request->mail_password,
                    'mail_encryption' => $request->mail_encryption,
                ]
            );
            if ($create) {
                return Response::json(
                    [
                        'status' => true,
                        'message' => 'SMTP Successfully Updated.'
                    ]
                );
            }
            return Response::json(
                [
                    'status' => false,
                    'message' => 'Failed To Update SMTP!'
                ]
            );
        }
    }
}
