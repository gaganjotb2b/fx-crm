<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\EmailService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class IbEmail2StepController extends Controller
{
    public function email_2step(Request $request)
    {
        try {
            $user = User::find(auth()->user()->id);
            $ib_user = $user;
            if (strtolower($user->type) === 'trader') {
                $ib_user = $user->IbAccount()->first();
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

            $ib_user->email_auth = $request->input('status');
            $update = $ib_user->save();
            if ($update) {
                // other authentication disable
                $status = 'disable';
                if ($request->input('status') == 1) {
                    $ib_user->g_auth = 0;
                    $ib_user->save();
                    $status = 'Enable';
                }
                EmailService::super_dynamic_mail('mail-email-2step', [
                    'email' => $ib_user->email,
                    'user_id' => $ib_user->id,
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

    public function disable_all(Request $request)
    {
        try {
            $user = User::find(auth()->user()->id);
            $ib_user = $user;
            if (strtolower($user->type) === 'trader') {
                $ib_user = $user->IbAccount()->first();
            }
            $ib_user->g_auth = 0;
            $ib_user->email_auth = 0;
            $update = $ib_user->save();
            if ($update) {
                EmailService::super_dynamic_mail('mail-no-auth', [
                    'email' => $ib_user->email,
                    'user_id' => $ib_user->id,
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
