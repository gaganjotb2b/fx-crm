<?php

namespace App\Http\Controllers\admins;

use App\Http\Controllers\Controller;
use App\Models\FinanceOp;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class TraderAdminSecurityController extends Controller
{
    public function change_kyc_status(Request $request)
    {
        $validation_rules = [
            'id' => 'required',
            'request_for' => 'required'
        ];
        $validator = Validator::make($request->all(), $validation_rules);
        if ($validator->fails()) {
            return Response::json([
                'status' => false,
                'errors' => $validator->errors(),
                'message' => 'Something went wrong please try again later'
            ]);
        } else {
            $create_or_update = User::where('id',$request->id)->update(
                ['kyc_status' => ($request->request_for === 'enable') ? 1 : 0]
            );

            if ($request->request_for === 'enable') {
                $update_message = "KYC Status successfully updated";
                $success_title = 'KYC Status verified';
            } else {
                $update_message = "KYC Status successfully updated";
                $success_title = 'KYC Status unverified';
            }
            if ($create_or_update) {
                // insert activity log----------------
                $users = User::where('id',$request->id)->first();
                $ip_address = request()->ip();
                $description = "The IP address $ip_address has been " . $request->request_for;
                activity('IB to Trader')
                    ->causedBy(auth()->user()->id)
                    ->withProperties($users)
                    ->event($request->request_for)
                    ->performedOn($users)
                    ->log($description);
                // end activity log-----------------
                return Response::json([
                    'status' => true,
                    'message' => $update_message,
                    'success_title' => $success_title
                ]);
            }
            return Response::json([
                'status' => false,
                'message' => 'Somthing went wrong! please try again later.',
                'success_title' => $success_title
            ]);
        }
    }
}
