<?php

namespace App\Http\Controllers\Admins;

use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use App\Models\admin\SystemConfig;
use App\Models\PasswordSettings;
use Illuminate\Support\Facades\Response;

use Illuminate\Http\Request;

class PasswordSettingsController extends Controller
{
    public function passwordSettings(Request $request, $name, $check)
    {
        $result = PasswordSettings::where('admin_id', auth()->user()->id)->first();
        if (!empty($result)) {
            if ($name === 'all_password' and $check == 1) {
                $created = PasswordSettings::where('admin_id', auth()->user()->id)->update([
                    'master_password' => 1,
                    'investor_password' => 1,
                    'leverage' => 1,
                    'admin_id' => auth()->user()->id
                ]);
                if ($created) {
                    return response()->json([
                        'status' => true,
                        'message' => 'All Password and Leverage Activate'
                    ]);
                } else {
                    return response()->json([
                        'status' => false,
                        'message' => 'something went to wrong'
                    ]);
                }
            }
            if ($name === 'all_password' and $check == 0) {
                $created = PasswordSettings::where('admin_id', auth()->user()->id)->update([
                    'master_password' => 0,
                    'investor_password' => 0,
                    'leverage' => 0,
                    'admin_id' => auth()->user()->id
                ]);
                if ($created) {
                    return response()->json([
                        'status' => true,
                        'message' => 'All Password and Leverage Deactivate'
                    ]);
                } else {
                    return response()->json([
                        'status' => false,
                        'message' => 'something went to wrong'
                    ]);
                }
            }
            if ($name === 'master' and $check == 1) {
                $created = PasswordSettings::where('admin_id', auth()->user()->id)->update([
                    'master_password' => 1,
                    'admin_id' => auth()->user()->id
                ]);
                if ($created) {
                    return response()->json([
                        'status' => true,
                        'message' => 'Master Password Activate'
                    ]);
                } else {
                    return response()->json([
                        'status' => false,
                        'message' => 'something went to wrong'
                    ]);
                }
            }
            if ($name === 'master' and $check == 0) {
                $created = PasswordSettings::where('admin_id', auth()->user()->id)->update([
                    'master_password' => 0,
                    'admin_id' => auth()->user()->id
                ]);
                if ($created) {
                    return response()->json([
                        'status' => true,
                        'message' => 'Master Password Deactivate'
                    ]);
                } else {
                    return response()->json([
                        'status' => false,
                        'message' => 'something went to wrong'
                    ]);
                }
            }
            if ($name === 'investor' and $check == 1) {
                $created = PasswordSettings::where('admin_id', auth()->user()->id)->update([
                    'investor_password' => 1,
                    'admin_id' => auth()->user()->id
                ]);
                if ($created) {
                    return response()->json([
                        'status' => true,
                        'message' => 'Investor Password Activate'
                    ]);
                } else {
                    return response()->json([
                        'status' => false,
                        'message' => 'something went to wrong'
                    ]);
                }
            }
            if ($name === 'investor' and $check == 0) {
                $created = PasswordSettings::where('admin_id', auth()->user()->id)->update([
                    'investor_password' => 0,
                    'admin_id' => auth()->user()->id
                ]);
                if ($created) {
                    return response()->json([
                        'status' => true,
                        'message' => 'Investor Password Deactivate'
                    ]);
                } else {
                    return response()->json([
                        'status' => false,
                        'message' => 'something went to wrong'
                    ]);
                }
            }
            if ($name === 'leverage' and $check == 1) {
                $created = PasswordSettings::where('admin_id', auth()->user()->id)->update([
                    'leverage' => 1,
                    'admin_id' => auth()->user()->id
                ]);
                if ($created) {
                    return response()->json([
                        'status' => true,
                        'message' => 'Investor Password Activate'
                    ]);
                } else {
                    return response()->json([
                        'status' => false,
                        'message' => 'something went to wrong'
                    ]);
                }
            }
            if ($name === 'leverage' and $check == 0) {
                $created = PasswordSettings::where('admin_id', auth()->user()->id)->update([
                    'leverage' => 0,
                    'admin_id' => auth()->user()->id
                ]);
                if ($created) {
                    return response()->json([
                        'status' => true,
                        'message' => 'Investor Password Deactivate'
                    ]);
                } else {
                    return response()->json([
                        'status' => false,
                        'message' => 'something went to wrong'
                    ]);
                }
            }
        } else {
            if ($name === 'all_password' and $check == 1) {
                $created = PasswordSettings::create([
                    'master_password' => 1,
                    'investor_password' => 1,
                    'leverage' => 1,
                    'admin_id' => auth()->user()->id
                ]);
                if ($created) {
                    return response()->json([
                        'status' => true,
                        'message' => 'All Password and Leverage Activate'
                    ]);
                } else {
                    return response()->json([
                        'status' => false,
                        'message' => 'something went to wrong'
                    ]);
                }
            }
            if ($name === 'master' and $check == 1) {
                $created = PasswordSettings::create([
                    'master_password' => 1,
                    'admin_id' => auth()->user()->id
                ]);
                if ($created) {
                    return response()->json([
                        'status' => true,
                        'message' => 'Master Password Activate'
                    ]);
                } else {
                    return response()->json([
                        'status' => false,
                        'message' => 'something went to wrong'
                    ]);
                }
            }
            if ($name === 'investor' and $check == 1) {
                $created = PasswordSettings::create([
                    'investor_password' => 1,
                    'admin_id' => auth()->user()->id
                ]);
                if ($created) {
                    return response()->json([
                        'status' => true,
                        'message' => 'Investor Password Activate'
                    ]);
                } else {
                    return response()->json([
                        'status' => false,
                        'message' => 'something went to wrong'
                    ]);
                }
            }
            if ($name === 'leverage' and $check == 1) {
                $created = PasswordSettings::create([
                    'leverage' => 1,
                    'admin_id' => auth()->user()->id
                ]);
                if ($created) {
                    return response()->json([
                        'status' => true,
                        'message' => 'Investor Password Activate'
                    ]);
                } else {
                    return response()->json([
                        'status' => false,
                        'message' => 'something went to wrong'
                    ]);
                }
            }
        }
    }
}
