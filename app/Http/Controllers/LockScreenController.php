<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserDescription;
use App\Services\CombinedService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class LockScreenController extends Controller
{
    // Trader Lock Screen 
    public function lockScreen(Request $request, $user_id, $current_page)
    {
        $user_id = base64_decode($user_id);
        $user = User::where('id', $user_id)->first();
        $user_name = $user->name;

        $user_descriptions = UserDescription::where('id', $user_id)->first();
        $user_profile_photo = asset('admin-assets/app-assets/images/avatars/');
        if (isset($user_descriptions->gender)) {
            $avatar = ($user_descriptions->gender === 'Male') ? '/avater-men.png' : '/avater-lady.png'; //<----avatar url
            $user_profile_photo .= $avatar;
        } else {
            $avatar = '/avater-men.png'; //<----avatar url
            $user_profile_photo .= $avatar;
        }


        $current_page = base64_decode($current_page);
        Auth::logout();
        return view('auth.lock-screen', compact('user_id', 'current_page', 'user_name', 'user_profile_photo'));
    }
    // trader login
    public function lockScreenLogin(Request $request)
    {
        $validation_rules = [
            'password' => 'required',
        ];
        $validator = Validator::make($request->all(), $validation_rules);
        if ($validator->fails()) {
            if ($request->ajax()) {
                return Response::json(['status' => false, 'message' => "Fix the following error", 'errors' => $validator->errors()]);
            } else {
                return Redirect()->back()->with(['status' => false, 'message' => "Fix the following error", 'errors' => $validator->errors()]);
            }
        } else {
            $user = User::where('id', $request->user_id)->first();

            if (Hash::check($request->password, $user->password)) {
                if (auth()->attempt(array(
                    'email' => $user->email,
                    'password' => $request->password,
                    'type' => 0
                ))) { //check login
                    $request->session()->regenerate();
                    return Response::json([
                        'status' => true,
                        'message' => 'You are successfully Unlock.',
                        'current_page' => $request->current_page,
                    ]);
                } else {
                    return Response::json([
                        'status' => false,
                        'message' => 'Password error!'
                    ]);
                }
            } else {
                return Response::json([
                    'status' => false,
                    'message' => 'Password Not Match!'
                ]);
            }
        }
    }

    // IB Lock Screen 
    public function IBlockScreen(Request $request, $user_id, $current_page)
    {
        $user_id = base64_decode($user_id);
        $user = User::where('id', $user_id)->first();
        $user_name = $user->name;

        $user_descriptions = UserDescription::where('id', $user_id)->first();
        $user_profile_photo = asset('admin-assets/app-assets/images/avatars/');
        if (isset($user_descriptions->gender)) {
            $avatar = ($user_descriptions->gender === 'Male') ? '/avater-men.png' : '/avater-lady.png'; //<----avatar url
            $user_profile_photo .= $avatar;
        } else {
            $avatar = '/avater-men.png'; //<----avatar url
            $user_profile_photo .= $avatar;
        }

        $current_page = base64_decode($current_page);
        Auth::logout();
        return view('auth.ibs.ib-lockscreen', compact('user_id', 'current_page', 'user_name', 'user_profile_photo'));
    }
    // IB lock screen login
    public function IBlockScreenLogin(Request $request)
    {
        $validation_rules = [
            'password' => 'required',
        ];
        $validator = Validator::make($request->all(), $validation_rules);
        if ($validator->fails()) {
            if ($request->ajax()) {
                return Response::json(['status' => false, 'message' => "Fix the following error", 'errors' => $validator->errors()]);
            } else {
                return Redirect()->back()->with(['status' => false, 'message' => "Fix the following error", 'errors' => $validator->errors()]);
            }
        } else {
            $user = User::where('id', $request->user_id)->first();

            if (Hash::check($request->password, $user->password)) {
                if (auth()->attempt(array(
                    'email' => $user->email,
                    'password' => $request->password,
                    'type' => CombinedService::type()
                ))) { //check login
                    $request->session()->regenerate();
                    return Response::json([
                        'status' => true,
                        'message' => 'You are successfully Unlock.',
                        'current_page' => $request->current_page,
                    ]);
                } else {
                    return Response::json([
                        'status' => false,
                        'message' => 'Email or password not matched!'
                    ]);
                }
            } else {
                return Response::json([
                    'status' => false,
                    'message' => 'Password Not Match!'
                ]);
            }
        }
    }

    // Admin Lock Screen 
    public function AdminlockScreen(Request $request, $user_id, $current_page)
    {
        $user_id = base64_decode($user_id);
        $user = User::where('id', $user_id)->first();
        $user_name = $user->name;

        $user_descriptions = UserDescription::where('id', $user_id)->first();
        $user_profile_photo = asset('admin-assets/app-assets/images/avatars/');
        if (isset($user_descriptions->gender)) {
            $avatar = ($user_descriptions->gender === 'Male') ? '/avater-men.png' : '/avater-lady.png'; //<----avatar url
            $user_profile_photo .= $avatar;
        } else {
            $avatar = '/avater-men.png'; //<----avatar url
            $user_profile_photo .= $avatar;
        }


        $current_page = base64_decode($current_page);
        Auth::logout();
        return view('auth.admins.admin-lockscreen', compact('user_id', 'current_page', 'user_name', 'user_profile_photo'));
    }
    // admin login/lock screen
    public function AdminlockScreenLogin(Request $request)
    {
        $validation_rules = [
            'password' => 'required',
        ];
        $validator = Validator::make($request->all(), $validation_rules);
        if ($validator->fails()) {
            if ($request->ajax()) {
                return Response::json(['status' => false, 'message' => "Fix the following error", 'errors' => $validator->errors()]);
            } else {
                return Redirect()->back()->with(['status' => false, 'message' => "Fix the following error", 'errors' => $validator->errors()]);
            }
        } else {
            $user = User::where('id', $request->user_id)->first();

            if (Hash::check($request->password, $user->password)) {
                if (auth()->attempt(array(
                    'email' => $user->email,
                    'password' => $request->password,
                    // 'type'
                ))) { //check login
                    $request->session()->regenerate();
                    return Response::json([
                        'status' => true,
                        'message' => 'You are successfully Unlock.',
                        'current_page' => $request->current_page,
                    ]);
                } else {
                    return Response::json([
                        'status' => false,
                        'message' => 'Password error!'
                    ]);
                }
            } else {
                return Response::json([
                    'status' => false,
                    'message' => 'Password Not Match!'
                ]);
            }
        }
    }
}
