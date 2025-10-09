<?php

namespace App\Http\Controllers\systems\theme_setup;

use App\Http\Controllers\Controller;
use App\Models\admin\SystemConfig;
use App\Models\CrmLogo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class LogoUploadController extends Controller
{
    public function logo_upload(Request $request)
    {
        $validation_rules = [
            'dark_logo'     => 'image|mimes:jpeg,png,jpg|max:2048',
            'light_logo'    => 'image|mimes:jpeg,png,jpg|max:2048',
            'favicon_icon'    => 'image|mimes:jpeg,png,jpg|max:2048',
            'email_logo'    => 'image|mimes:jpeg,png,jpg|max:2048',
        ];
        $validator = Validator::make($request->all(), $validation_rules);
        if ($validator->fails()) {
            return Response::json([
                'status' => false,
                'errors' => $validator->errors(),
                'message' => 'Please fix the following errors'
            ]);
        }
        // get previous image
        $logos = CrmLogo::select()->first();
        $dark_logo = ($logos) ? $logos->dark_layout : '';
        $light_logo = ($logos) ? $logos->light_layout : '';
        $favicon = ($logos) ? $logos->favicon : '';
        $email_logo = ($logos) ? $logos->email_logo : '';
        // upload logo
        if (isset($request->dark_logo)) {
            $this->unlinkPrevImg('dark_layout');
            $dark_logo = join_app_name() . '_logo_dark_' . time() . '.' . $request->dark_logo->extension();
            $request->dark_logo->move(public_path('Uploads/logos'), $dark_logo);
            // return $dark_logo;
        }
        if (isset($request->light_logo)) {
            $this->unlinkPrevImg('light_layout');
            $light_logo = join_app_name() . '_logo_light_' . time() . '.' . $request->light_logo->extension();
            $request->light_logo->move(public_path('Uploads/logos'), $light_logo);
        }
        if (isset($request->favicon_icon)) {
            $this->unlinkPrevImg('fevicon');
            $favicon = join_app_name() . '_favicon_icon_' . time() . '.' . $request->favicon_icon->extension();
            $request->favicon_icon->move(public_path('Uploads/logos'), $favicon);
        }
        if (isset($request->email_logo)) {
            $this->unlinkPrevImg('email_logo');
            $email_logo = join_app_name() . '_email_logo_' . time() . '.' . $request->email_logo->extension();
            $request->email_logo->move(public_path('Uploads/logos'), $email_logo);
        }
        // update in crm_logos table
        $create = CrmLogo::updateOrCreate(
            [
                'id' => 1,
            ],
            [
                'dark_layout' => $dark_logo,
                'light_layout' => $light_logo,
                'email_logo' => $email_logo,
                'fevicon' => $favicon,
            ]
        );
        if ($create) {
            return Response::json([
                'status' => true,
                'message' => 'Logo Successfully Updated'
            ]);
        }
        return Response::json([
            'status' => false,
            'message' => 'Logo Upload failed, Please try again later'
        ]);
    }
    private function unlinkPrevImg($for)
    {
        $crm_logos = CrmLogo::select();
        if ($crm_logos->count()) {
            $prev_logos = $crm_logos->first();
            if (isset($prev_logos->{$for}) && $prev_logos->{$for} != "") {
                if (file_exists(public_path('Uploads/logos/') . $prev_logos->{$for})) {
                    unlink(public_path('Uploads/logos/') . $prev_logos->{$for});
                }
            }
        }
    }
}
