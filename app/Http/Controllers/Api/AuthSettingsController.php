<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\EmailService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class AuthSettingsController extends Controller
{
    public function google_2step(Request $request)
    {
        try {
            $user = User::find(auth()->user()->id);
            $trader_user = $user;
            if (strtolower($user->type) === 'ib') {
                $trader_user = $user->TraderAccount()->first();
            }
            $validator = Validator::make($request->all(), [
                'status' => 'required|integer|in:0,1'
            ]);
            if ($validator->fails()) {
                return Response::json([
                    'status' => false,
                    'message' => 'validation error, please fix the following errors',
                    'errors' => $validator->errors(),
                ]);
            }

            $trader_user->g_auth = $request->input('status');
            $update = $trader_user->save();
            if ($update) {
                // other authentication disable
                $status = 'disable';
                if ($request->input('status') == 1) {
                    $trader_user->email_auth = 0;
                    $trader_user->save();
                    $status = 'Enable';
                }
                EmailService::super_dynamic_mail('mail-email-2step', [
                    'email' => $trader_user->email,
                    'user_id' => $trader_user->id,
                    'subject' => "Google 2step $status",
                    'status' => $status
                ]);
                return Response::json([
                    'status' => true,
                    'message' => "Google 2steps authentication successfully $status",
                ]);
            }
            return Response::json([
                'status' => false,
                'message' => 'Something went wrong, please try again later'
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return Response::json([
                'status' => false,
                'message' => 'Got a server error, please contact for support',
                'error' => $th->getMessage()
            ]);
        }
    }
    // email 2step
    public function email_2step(Request $request)
    {
        try {
            $user = User::find(auth()->user()->id);
            $trader_user = $user;
            if (strtolower($user->type) === 'ib') {
                $trader_user = $user->TraderAccount()->first();
            }
            $validator = Validator::make($request->all(), [
                'status' => 'required|integer|in:0,1'
            ]);

            if ($validator->fails()) {
                return Response::json([
                    'status' => false,
                    'message' => 'validation error, please fix the following errors',
                    'errors' => $validator->errors(),
                ]);
            }

            $trader_user->email_auth = $request->input('status');
            $update = $trader_user->save();
            if ($update) {
                // other authentication disable
                $status = 'disable';
                if ($request->input('status') == 1) {
                    $trader_user->g_auth = 0;
                    $trader_user->save();
                    $status = 'Enable';
                }
                EmailService::super_dynamic_mail('mail-email-2step', [
                    'email' => $trader_user->email,
                    'user_id' => $trader_user->id,
                    'subject' => "Email 2step $status",
                    'status' => $status
                ]);
                return Response::json([
                    'status' => true,
                    'message' => "Email 2steps authentication successfully $status",
                ]);
            }
            return Response::json([
                'status' => false,
                'message' => 'Something went wrong, please try again later'
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return Response::json([
                'status' => false,
                'message' => 'Got a server error, please contact for support',
                'error' => $th->getMessage()
            ]);
        }
    }
    // disable all the 2steps settings
    public function disable_all(Request $request)
    {
        try {
            $user = User::find(auth()->user()->id);
            $trader_user = $user;
            if (strtolower($user->type) === 'ib') {
                $trader_user = $user->TraderAccount()->first();
            }
            $trader_user->g_auth = 0;
            $trader_user->email_auth = 0;
            $update = $trader_user->save();
            if ($update) {
                EmailService::super_dynamic_mail('mail-no-auth', [
                    'email' => $trader_user->email,
                    'user_id' => $trader_user->id,
                    'subject' => "Security settings",
                    'status' => 'Disable'
                ]);
                return Response::json([
                    'status' => true,
                    'message' => 'All the security 2step authetication successfully disable',
                ]);
            }
            return Response::json([
                'status' => false,
                'message' => 'Something went wrong, please try again later'
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return Response::json([
                'status' => false,
                'message' => 'Got a server error, please contact for support',
                'error' => $th->getMessage()
            ]);
        }
    }
}
