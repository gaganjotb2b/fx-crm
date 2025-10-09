<?php

namespace App\Http\Controllers;

use App\Models\SoftwareSetting;
use App\Models\User;
use App\Services\systems\AdminLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class TransactionSettingsController extends Controller
{
    // deposit settings
    public function deposit_settings(Request $request)
    {
        try {
            $sotsettings = SoftwareSetting::select()->first();
            $update = SoftwareSetting::updateOrCreate(
                [
                    'id' => $sotsettings->id,
                ],
                [
                    'direct_deposit' => $request->status,
                    'admin_log' => AdminLogService::admin_log(),
                ]
            );
            if ($update) {
                // activity log
                $user = User::find(auth()->user()->id);
                activity('Deposit settings')
                    ->causedBy($user->id)
                    ->withProperties($update)
                    ->event('deposit settngs')
                    ->performedOn($user)
                    ->log('The IP address ' . request()->ip() . ' has been update deposit settings');
                // end activity log
                return Response::json([
                    'status' => true,
                    'message' => ($request->status === 'wallet') ? 'successfully enable wallet deposit' : 'Successfully enable account deposit',
                ]);
            }
            return Response::json([
                'status' => false,
                'message' => 'Somthing went wrong, please try again later!',
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return Response::json([
                'status' => false,
                'message' => 'Got a server error!',
            ]);
        }
    }
    // deposit settings
    public function crypto_deposit_setting(Request $request)
    {
        try {
            $softsettings = SoftwareSetting::select()->first();
            $update = SoftwareSetting::updateOrCreate(
                [
                    'id' => $softsettings->id,
                ],
                [
                    'crypto_deposit' => $request->crypto_deposit,
                    'admin_log' => AdminLogService::admin_log(),
                ]
            );
            if ($update) {
                // activity log
                $user = User::find(auth()->user()->id);
                activity('CryptoDeposit settings')
                    ->causedBy($user->id)
                    ->withProperties($update)
                    ->event('deposit settngs')
                    ->performedOn($user)
                    ->log('The IP address ' . request()->ip() . ' has been update deposit settings');
                // end activity log
                return Response::json([
                    'status' => true,
                    'message' => ($request->crypto_deposit === 'manual') ? 'Manual crypto deposit is successfully enabled.' : 'Auto crypto deposit is successfully enabled.',
                ]);
            }
            return Response::json([
                'status' => false,
                'message' => 'Somthing went wrong, please try again later!',
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return Response::json([
                'status' => false,
                'message' => 'Got a server error!',
            ]);
        }
    }

    //Withdraw Settings
    public function withdraw_settinngs(Request $request)
    {
        try {
            $sotsettings = SoftwareSetting::select()->first();
            $update = SoftwareSetting::updateOrCreate(
                [
                    'id' => $sotsettings->id,
                ],
                [
                    'direct_withdraw' => $request->status,
                    'admin_log' => AdminLogService::admin_log(),
                ]
            );
            if ($update) {
                // activity log
                $user = User::find(auth()->user()->id);
                activity('Withdraw settings')
                    ->causedBy($user->id)
                    ->withProperties($update)
                    ->event('withdraw settngs')
                    ->performedOn($user)
                    ->log('The IP address ' . request()->ip() . ' has been update withdraw settings');
                // end activity log
                return Response::json([
                    'status' => true,
                    'message' => ($request->status === 'wallet') ? 'successfully enable wallet withdraw' : 'Successfully enable account withdraw',
                ]);
            }
            return Response::json([
                'status' => false,
                'message' => 'Somthing went wrong, please try again later!',
            ]);
        } catch (\Throwable $th) {
            // throw $th;
            return Response::json([
                'status' => false,
                'message' => 'Got a server error!',
            ]);
        }
    }
}
