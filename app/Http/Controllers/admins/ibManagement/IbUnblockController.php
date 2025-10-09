<?php

namespace App\Http\Controllers\admins\ibManagement;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class IbUnblockController extends Controller
{
    public function ib_unblock(Request $request)
    {
        try {
            $user = User::where('id', $request->input('id'))->first();
            $user->active_status = 1;
            $update = $user->save();
            if ($update) {
                // insert activity
                activity('User unblocked')
                    ->causedBy(auth()->user()->id)
                    ->withProperties($user)
                    ->event('user unblocked')
                    ->performedOn($user)
                    ->log("The IP address " . request()->ip() . " has been unblocked");
                return Response::json([
                    'status' => true,
                    'message' => 'User successfully unblocked'
                ]);
            }
            return Response::json([
                'status' => false,
                'message' => 'Something went wrong, please try again later',
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return Response::json([
                'status' => false,
                'message' => 'Got a server error, please contact for support',
            ]);
        }
    }
}
