<?php

namespace App\Http\Controllers\systems\mobile;

use App\Http\Controllers\Controller;
use App\Models\MobileAppSetting;
use App\Models\SoftwareSetting;
use App\Services\EmailService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class ApplicationController extends Controller
{
    public function logo_controll(Request $request)
    {
        $software_settings = SoftwareSetting::value('app_key');
        return view('systems.mobile.application-controll', ['app_key' => $software_settings]);
    }
    public function app_key_setup(Request $request)
    {

        try {
            $validator = Validator::make($request->all(), [
                'app_key' => 'required|max:64'
            ]);

            if ($validator->fails()) {
                return Response::json([
                    'status' => false,
                    'errors' => $validator->errors(),
                    'message' => $validator->errors()->first('app_key'),
                ]);
            }
            $software_settings = SoftwareSetting::first('app_key');
            $software_settings->app_key = $request->input('app_key');
            $update = $software_settings->save();
            if ($update) {
                EmailService::super_dynamic_mail('mail-app-key-update', [
                    'to_mail' => 'fxcrm03@gmail.com',
                    'subject' => 'Mobile app key updated in CRM',
                    'crm' => config('app.name'),
                    'url' => url('/'),
                    'ip' => request()->ip(),
                ]);
                return Response::json([
                    'status' => true,
                    'message' => 'App key successfully updated'
                ]);
            }
            return Response::json([
                'status' => false,
                'message' => 'Something went wrong, Please try again later'
            ]);
        } catch (\Throwable $th) {
            throw $th;
            return Response::json([
                'status' => false,
                'message' => 'Server error ' . $th->getMessage(),
            ]);
        }
    }
    // upload loader logo
    public function upload_loader(Request $request)
    {
        $validation_rul = [
            'logo_loader' => 'required|mimes:png,jpg,jpeg|max:2048'
        ];
        $validator = Validator::make($request->all(), $validation_rul);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return Response::json([
                'status' => false,
                'errors' => $validator->errors(),
                'message' => $errors['logo_loader'],
            ]);
        }
        $logo_loader = $request->file('logo_loader');
        $file_logo = time() . '_logo_loader_' . $logo_loader->getClientOriginalName();
        $logo_loader->move(public_path('/Uploads/mobile'), $file_logo);
        // delete exist file
        $this->delete_exists('logo_loader');
        // update app settings table
        $create = MobileAppSetting::updateOrCreate(
            [
                'id' => 1
            ],
            [
                'logo_loader' => $file_logo,
            ]
        );
        if ($create) {
            return Response::json([
                'status' => true,
                'message' => 'File successfully uploaded'
            ]);
        }
        return Response::json([
            'status' => false,
            'message' => 'File upload filed, Please try again later'
        ]);
    }
    // check file exist
    private function delete_exists($file)
    {
        $file_exist = MobileAppSetting::select("$file")->first();
        if (isset($file_exist->{$file})) {
            if (file_exists(public_path('Uploads/mobile/') . $file_exist->{$file})) {
                unlink(public_path('Uploads/mobile/') . $file_exist->{$file});
            }
        }
    }
}
