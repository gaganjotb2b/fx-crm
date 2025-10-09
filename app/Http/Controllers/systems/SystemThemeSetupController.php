<?php

namespace App\Http\Controllers\systems;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\admin\SystemConfig;
use App\Models\theme_setup;
use App\Models\ThemeColor;
use App\Models\ThemeSetup;
use App\Models\TransactionSetting;
use Carbon\Carbon;
use Faker\Core\File;
use PhpParser\Builder\Trait_;

class SystemThemeSetupController extends Controller
{
    // view configuration 
    public function configuration()
    {
        return view('systems.configurations.theme_setup');
    }
    // theme setup
    public function client_theme_setup(Request $request)
    {

        $themes = ThemeSetup::where('use_for', 'client')->first();
        $create = ThemeSetup::updateOrCreate(
            [
                'id' => ($themes) ? $themes->id : ''
            ],
            [
                'theme_name' => trim($request->theme_name),
                'theme_version' => trim($request->user_theme),
                'use_for' => 'client',
            ]
        );
        if ($create) {
            return Response::json([
                'status' => true,
                'message' => 'Client portal theme successfully update!'
            ]);
        }
        return Response::json([
            'status' => false,
            'message' => 'Theme update failed, please try again later!'
        ]);
    }

    // admin theme setup
    public function admin_theme_setup(Request $request)
    {
        $themes = ThemeSetup::where('use_for', 'admin')->first();
        $create = ThemeSetup::updateOrCreate(
            [
                'id' => ($themes) ? $themes->id : ''
            ],
            [
                'theme_name' => trim($request->theme_name),
                'theme_version' => trim($request->admin_theme),
                'use_for' => 'admin',
            ]
        );
        if ($create) {
            return Response::json([
                'status' => true,
                'message' => 'Admin portal theme successfully update!'
            ]);
        }
        return Response::json([
            'status' => false,
            'message' => 'Theme update failed, please try again later!'
        ]);
    }
    // theme colors setup
    public function theme_colors(Request $request)
    {
        $validation_rules = [
            'user_body_color' => 'nullable|max:30',
            'user_secondary_color' => 'nullable|max:30',
            'user_primary_color' => 'nullable|max:30',
            'user_form_color' => 'nullable|max:30',
            // admin colors
            'ad_body_color' => 'nullable|max:30',
            'admin_secondary_color' => 'nullable|max:30',
            'ad_primary_color' => 'nullable|max:30',
            'ad_form_color' => 'nullable|max:30',
        ];
        $validator = Validator::make($request->all(), $validation_rules);
        if ($validator->fails()) {
            return Response::json([
                'status' => false,
                'errors' => $validator->errors(),
                'message' => 'Please fix the following errors'
            ]);
        }
        // create admin colors
        $admin_colors = ThemeColor::where('use_for', 'admin_theme')->first();
        $update = ThemeColor::updateOrCreate(
            [
                'id' => ($admin_colors) ? $admin_colors->id : ''
            ],
            [
                'primary_color' => $request->ad_primary_color,
                'body_color' => $request->ad_body_color,
                'secondary_color' => $request->admin_secondary_color,
                'form_color' => $request->ad_form_color,
                'use_for' => 'admin_theme'
            ]
        );
        // create clients color
        $client_colors = ThemeColor::where('use_for', 'user_theme')->first();
        $update = ThemeColor::updateOrCreate(
            [
                'id' => ($client_colors) ? $client_colors->id : ''
            ],
            [
                'primary_color' => $request->user_primary_color,
                'body_color' => $request->user_body_color,
                'secondary_color' => $request->user_secondary_color,
                'form_color' => $request->ad_form_color,
                'use_for' => 'user_theme'
            ]
        );
        if ($update) {
            return Response::json([
                'status' => true,
                'message' => 'Theme Colors Successfully Update'
            ]);
        }
        return Response::json([
            'status' => false,
            'message' => 'Theme Colors Update Failed, Please try agian later'
        ]);
    }
}
