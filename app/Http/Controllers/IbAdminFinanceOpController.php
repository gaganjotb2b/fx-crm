<?php

namespace App\Http\Controllers;

use App\Models\FinanceOp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class IbAdminFinanceOpController extends Controller
{
    public function ib_to_ib(Request $request)
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
            $create_or_update = FinanceOp::updateOrCreate(
                ['user_id' => $request->id],
                ['ib_to_ib' => ($request->request_for === 'enable') ? 1 : 0]
            );

            if ($request->request_for === 'enable') {
                $update_message = $create_or_update->name . " " . "IB to IB Transfer Successfully Enabled";
                $success_title = 'IB to IB Transfer Enabled';
            } else {
                $update_message = $create_or_update->name . " " . "IB to IB Transfer successfully Disabled";
                $success_title = 'IB to IB Transfer Disabled';
            }
            if ($create_or_update) {
                // insert activity log----------------
                $ip_address = request()->ip();
                $description = "The IP address $ip_address has been " . $request->request_for;
                activity('IB to IB')
                    ->causedBy(auth()->user()->id)
                    ->withProperties($create_or_update)
                    ->event($request->request_for)
                    ->performedOn($create_or_update)
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
