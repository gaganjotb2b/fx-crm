<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BankAccount;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class IBBankGetController extends Controller
{
    public function get_bank(Request $request)
    {
        try {
            $user = User::find(auth()->user()->id);
            $ib_user = $user;
            if (strtolower($user->type) === 'trader') {
                $ib_user = $user->IbAccount()->first();
            }
            $banks = BankAccount::where('status', '1')->where('user_id', $ib_user->id)->get();
            if ($banks) {
                return Response::json([
                    'status'=>true,
                    'message'=>$banks,
                ]);
            }
            return Response::json([
                'status'=>false,
                'message'=>'Bank account not found'
            ]);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
}
