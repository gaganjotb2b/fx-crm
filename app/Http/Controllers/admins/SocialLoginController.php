<?php

namespace App\Http\Controllers\Admins;

use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use App\Models\admin\SystemConfig;
use App\Models\SocialLogin;
use Illuminate\Support\Facades\Response;

use Illuminate\Http\Request;

class SocialLoginController extends Controller
{
    public function socialLogin(Request $request, $name, $check)
    {
        try {
            $result = SocialLogin::where('admin_id', auth()->user()->id)->first();
            $SystemConfig = SystemConfig::first();
            $data = [
                'brute_force_attack' => $check,
            ];

            if (!empty($result)) {
                if ($name === 'all_social' and $check == 1) {
                    $created = SocialLogin::where('admin_id', auth()->user()->id)->update([
                        'facebook' => 1,
                        'google' => 1,
                        'mac' => 1,
                        'admin_id' => auth()->user()->id
                    ]);
                    if ($created) {
                        return response()->json([
                            'status' => true,
                            'message' => 'All Social Login Activated'
                        ]);
                    } else {
                        return response()->json([
                            'status' => false,
                            'message' => 'something went to wrong'
                        ]);
                    }
                }
                if ($name === 'all_social' and $check == 0) {
                    $created = SocialLogin::where('admin_id', auth()->user()->id)->update([
                        'facebook' => 0,
                        'google' => 0,
                        'mac' => 0,
                        'admin_id' => auth()->user()->id
                    ]);
                    if ($created) {
                        return response()->json([
                            'status' => true,
                            'message' => 'All Socail Login Deactivated'
                        ]);
                    } else {
                        return response()->json([
                            'status' => false,
                            'message' => 'something went to wrong'
                        ]);
                    }
                }
                if ($name === 'facebook_login' and $check == 1) {
                    $created = SocialLogin::where('admin_id', auth()->user()->id)->update([
                        'facebook' => 1,
                        'admin_id' => auth()->user()->id
                    ]);
                    if ($created) {
                        return response()->json([
                            'status' => true,
                            'message' => 'Facebook Socail Login Activated'
                        ]);
                    } else {
                        return response()->json([
                            'status' => false,
                            'message' => 'something went to wrong'
                        ]);
                    }
                }
                if ($name === 'facebook_login' and $check == 0) {
                    $created = SocialLogin::where('admin_id', auth()->user()->id)->update([
                        'facebook' => 0,
                        'admin_id' => auth()->user()->id
                    ]);
                    if ($created) {
                        return response()->json([
                            'status' => true,
                            'message' => 'Facebook Socail Login Deactivated'
                        ]);
                    } else {
                        return response()->json([
                            'status' => false,
                            'message' => 'something went to wrong'
                        ]);
                    }
                }
                if ($name === 'google_login' and $check == 1) {
                    $created = SocialLogin::where('admin_id', auth()->user()->id)->update([
                        'google' => 1,
                        'admin_id' => auth()->user()->id
                    ]);
                    if ($created) {
                        return response()->json([
                            'status' => true,
                            'message' => 'Google Socail Login Activated'
                        ]);
                    } else {
                        return response()->json([
                            'status' => false,
                            'message' => 'something went to wrong'
                        ]);
                    }
                }
                if ($name === 'google_login' and $check == 0) {
                    $created = SocialLogin::where('admin_id', auth()->user()->id)->update([
                        'google' => 0,
                        'admin_id' => auth()->user()->id
                    ]);
                    if ($created) {
                        return response()->json([
                            'status' => true,
                            'message' => 'Google Socail Login Deactivated'
                        ]);
                    } else {
                        return response()->json([
                            'status' => false,
                            'message' => 'something went to wrong'
                        ]);
                    }
                }
                if ($name === 'mac_login' and $check == 1) {
                    $created = SocialLogin::where('admin_id', auth()->user()->id)->update([
                        'mac' => 1,
                        'admin_id' => auth()->user()->id
                    ]);
                    if ($created) {
                        return response()->json([
                            'status' => true,
                            'message' => 'Mac Socail Login Activated'
                        ]);
                    } else {
                        return response()->json([
                            'status' => false,
                            'message' => 'something went to wrong'
                        ]);
                    }
                }
                if ($name === 'mac_login' and $check == 0) {
                    $created = SocialLogin::where('admin_id', auth()->user()->id)->update([
                        'mac' => 0,
                        'admin_id' => auth()->user()->id
                    ]);
                    if ($created) {
                        return response()->json([
                            'status' => true,
                            'message' => 'Mac Socail Login Deactivated'
                        ]);
                    } else {
                        return response()->json([
                            'status' => false,
                            'message' => 'something went to wrong'
                        ]);
                    }
                }
                if ($name === 'brute_force_attack' and $check == 1) {
                    $update = SystemConfig::where('id', $SystemConfig->id)->update($data);
                    if ($update) {
                        return response()->json([
                            'status' => true,
                            'message' => 'Brute Force Attack Activated'
                        ]);
                    } else {
                        return response()->json([
                            'status' => false,
                            'message' => 'something went to wrong'
                        ]);
                    }
                }
                if ($name === 'brute_force_attack' and $check == 0) {
                    $update = SystemConfig::where('id', $SystemConfig->id)->update($data);
                    if ($update) {
                        return response()->json([
                            'status' => true,
                            'message' => 'Brute Force Attack Deactivated'
                        ]);
                    } else {
                        return response()->json([
                            'status' => false,
                            'message' => 'something went to wrong'
                        ]);
                    }
                }
            } else {
                if ($name === 'all_social' and $check == 1) {
                    $created = SocialLogin::create([
                        'facebook' => 1,
                        'google' => 1,
                        'mac' => 1,
                        'admin_id' => auth()->user()->id
                    ]);
                    if ($created) {
                        return response()->json([
                            'status' => true,
                            'message' => 'All Social Login Activated'
                        ]);
                    } else {
                        return response()->json([
                            'status' => false,
                            'message' => 'something went to wrong'
                        ]);
                    }
                }
                if ($name === 'mac_login' and $check == 1) {
                    $created = SocialLogin::create([
                        'mac' => 1,
                        'admin_id' => auth()->user()->id
                    ]);
                    if ($created) {
                        return response()->json([
                            'status' => true,
                            'message' => 'Mac Socail Login Activated'
                        ]);
                    } else {
                        return response()->json([
                            'status' => false,
                            'message' => 'something went to wrong'
                        ]);
                    }
                }
                if ($name === 'facebook_login' and $check == 1) {
                    $created = SocialLogin::create([
                        'facebook_login' => 1,
                        'admin_id' => auth()->user()->id
                    ]);
                    if ($created) {
                        return response()->json([
                            'status' => true,
                            'message' => 'Facebook Socail Login Activated'
                        ]);
                    } else {
                        return response()->json([
                            'status' => false,
                            'message' => 'something went to wrong'
                        ]);
                    }
                }
                if ($name === 'google_login' and $check == 1) {
                    $created = SocialLogin::create([
                        'google' => 1,
                        'admin_id' => auth()->user()->id
                    ]);
                    if ($created) {
                        return response()->json([
                            'status' => true,
                            'message' => 'Google Socail Login Activated'
                        ]);
                    } else {
                        return response()->json([
                            'status' => false,
                            'message' => 'something went to wrong'
                        ]);
                    }
                }
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
