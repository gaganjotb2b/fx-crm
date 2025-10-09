<?php

namespace App\Http\Controllers\admins;

use App\Http\Controllers\Controller;
use App\Models\FinanceOp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class TraderadminFinanceOpController extends Controller
{
    // deposit operation-----------------------------------------
    public function deposit_operation(Request $request)
    {
        $validation_rules = [
            'id' => 'required',
            'request_for' => 'required'
        ];
        $validator = Validator::make($request->all(), $validation_rules);
        if ($validator->fails()) {
            return Response::json(['status' => false, 'errors' => $validator->errors()]);
        } else {
            $create_or_update = FinanceOp::updateOrCreate(
                ['user_id' => $request->id],
                ['deposit_operation' => ($request->request_for === 'enable') ? 1 : 0]
            );

            if ($request->request_for === 'enable') {
                $update_message = $create_or_update->name . " " . "Deposit operation Successfully Enabled";
                $success_title = 'Deposit Operation Enabled';
            } else {
                $update_message = $create_or_update->name . " " . "Deposit Operation successfully Disabled";
                $success_title = 'Deposit Operation Disabled';
            }
            if ($create_or_update) {
                $ip_address = request()->ip();
                $description = "The IP address $ip_address has been " . $request->request_for;
                // insert activity-----------------
                activity('deposit operation')
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
                'message' => 'Something went wrong! please try again later.',
                'success_title' => $success_title
            ]);
        }
        return Response::json($request->trader_id);
    }
    // withdraw operation-------------------------------------
    public function withdraw_operation(Request $request)
    {
        $validation_rules = [
            'id' => 'required',
            'request_for' => 'required'
        ];
        $validator = Validator::make($request->all(), $validation_rules);
        if ($validator->fails()) {
            return Response::json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
        $create_or_update = FinanceOp::updateOrCreate(
            ['user_id' => $request->id],
            ['withdraw_operation' => ($request->request_for === 'enable') ? 1 : 0]
        );

        if ($request->request_for === 'enable') {
            $update_message = $create_or_update->name . " " . "Withdraw operation Successfully Enabled";
            $success_title = 'Withdraw Operation Enabled';
        } else {
            $update_message = $create_or_update->name . " " . "Withdraw Operation successfully Disabled";
            $success_title = 'Withdraw Operation Disabled';
        }
        if ($create_or_update) {
            $ip_address = request()->ip();
            $description = "The IP address $ip_address has been " . $request->request_for;
            // insert activity-----------------
            activity('withdraw operation')
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

    // Internal transfer 
    // account to wallet
    public function internal_transfer(Request $request)
    {
        $validation_rules = [
            'id' => 'required',
            'request_for' => 'required'
        ];
        $validator = Validator::make($request->all(), $validation_rules);
        if ($validator->fails()) {
            return Response::json(['status' => false, 'errors' => $validator->errors()]);
        } else {
            $create_or_update = FinanceOp::updateOrCreate(
                ['user_id' => $request->id],
                ['internal_transfer' => ($request->request_for === 'enable') ? 1 : 0]
            );

            if ($request->request_for === 'enable') {
                $update_message = $create_or_update->name . " " . "Account to Wallet Transfer Successfully Enabled";
                $success_title = 'Account to Wallet Transfer Enabled';
            } else {
                $update_message = $create_or_update->name . " " . "Account to Wallet Transfer successfully Disabled";
                $success_title = 'Account to Wallet Transfer Disabled';
            }
            if ($create_or_update) {
                // insert activity log----------------
                $ip_address = request()->ip();
                $description = "The IP address $ip_address has been " . $request->request_for;
                activity('internal transer')
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
    // wallet to account transfer
    public function wta_finance_op(Request $request)
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
                ['wta_transfer' => ($request->request_for === 'enable') ? 1 : 0]
            );

            if ($request->request_for === 'enable') {
                $update_message = $create_or_update->name . " " . "Wallet to Account Transfer Successfully Enabled";
                $success_title = 'Wallet to Account Transfer Enabled';
            } else {
                $update_message = $create_or_update->name . " " . "Wallet to Account Transfer successfully Disabled";
                $success_title = 'Wallet to Account Transfer Disabled';
            }
            if ($create_or_update) {
                // insert activity log----------------
                $ip_address = request()->ip();
                $description = "The IP address $ip_address has been " . $request->request_for;
                activity('wallet to account')
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
    // trader to trader transfer
    public function trader_to_trader(Request $request)
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
                ['trader_to_trader' => ($request->request_for === 'enable') ? 1 : 0]
            );

            if ($request->request_for === 'enable') {
                $update_message = $create_or_update->name . " " . "Trader to Trader Transfer Successfully Enabled";
                $success_title = 'Trader to Trader Transfer Enabled';
            } else {
                $update_message = $create_or_update->name . " " . "Trader to Trader Transfer successfully Disabled";
                $success_title = 'Trader to Trader Transfer Disabled';
            }
            if ($create_or_update) {
                // insert activity log----------------
                $ip_address = request()->ip();
                $description = "The IP address $ip_address has been " . $request->request_for;
                activity('Trader to Trader')
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
    // trader to ib transfer
    public function trader_to_ib(Request $request)
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
                ['trader_to_ib' => ($request->request_for === 'enable') ? 1 : 0]
            );

            if ($request->request_for === 'enable') {
                $update_message = $create_or_update->name . " " . "Trader to IB Transfer Successfully Enabled";
                $success_title = 'Trader to Trader Transfer Enabled';
            } else {
                $update_message = $create_or_update->name . " " . "Trader to IB Transfer successfully Disabled";
                $success_title = 'Trader to IB Transfer Disabled';
            }
            if ($create_or_update) {
                // insert activity log----------------
                $ip_address = request()->ip();
                $description = "The IP address $ip_address has been " . $request->request_for;
                activity('Trader to Trader')
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
    // ib to trader transfer
    public function ib_to_trader(Request $request)
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
                ['ib_to_trader' => ($request->request_for === 'enable') ? 1 : 0]
            );

            if ($request->request_for === 'enable') {
                $update_message = $create_or_update->name . " " . "IB to Trader Transfer Successfully Enabled";
                $success_title = 'IB to Trader Transfer Enabled';
            } else {
                $update_message = $create_or_update->name . " " . "IB to Trader Transfer successfully Disabled";
                $success_title = 'IB to Trader Transfer Disabled';
            }
            if ($create_or_update) {
                // insert activity log----------------
                $ip_address = request()->ip();
                $description = "The IP address $ip_address has been " . $request->request_for;
                activity('IB to Trader')
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
