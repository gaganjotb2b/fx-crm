<?php

namespace App\Http\Controllers\admins\ibManagement;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class IbBlockController extends Controller
{
    public function ib_block(Request $request)
    {
        try {
            $user = User::where('id', $request->input('id'))->first();
            $user->active_status = 2;
            $update = $user->save();
            if ($update) {
                // insert activity
                activity('User blocked')
                    ->causedBy(auth()->user()->id)
                    ->withProperties($user)
                    ->event('user blocked')
                    ->performedOn($user)
                    ->log("The IP address " . request()->ip() . " has been blocked");
                return Response::json([
                    'status' => true,
                    'message' => 'User successfully blocked'
                ]);
            }
            return Response::json([
                'status'=>false,
                'message'=>'Something went wrong, please try again later',
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
