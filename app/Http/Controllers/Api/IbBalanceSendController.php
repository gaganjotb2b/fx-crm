<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ExternalFundTransfers;
use App\Models\User;
use App\Services\Transfer\ExternalTransfer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class IbBalanceSendController extends Controller
{
    public function __invoke(Request $request)
    {
        try {
            $validation_rules = [
                'status' => 'nullable|in:approved,pending,declined',
                'min_amount' => 'nullable|min:0|numeric',
                'max_amount' => 'nullable|min:0|numeric',
                'date_to' => 'nullable|date',
                'date_from' => 'nullable|date'
            ];
            $validator = Validator::make($request->all(), $validation_rules);
            if ($validator->fails()) {
                return Response::json([
                    'status' => false,
                    'error' => "Validation Error",
                    'message' => "The request data is invalid.",
                    'errors' => $validator->errors(),
                ], 400);
            }
            $user = User::find(auth()->user()->id);
            $ib_user = $user;
            if (strtolower($user->type) === 'trader') {
                $ib_user = $user->IbAccount()->first();
            }
            $result = ExternalFundTransfers::where('sender_id', $ib_user->id)->where('sender_wallet_type', 'ib');
            // filter by status
            if ($request->input('status')) {
                $status = '';
                if (strtolower($request->input('status')) === 'approved') {
                    $status = 'A';
                } elseif (strtolower($request->input('status')) === 'pending') {
                    $status = 'P';
                } elseif (strtolower($request->input('status')) === 'declined') {
                    $status = 'D';
                }
                $result = $result->where('status', $status);
            }
            // filter by min amount
            if ($request->input('min_amount')) {
                $result = $result->where('amount', '>=', $request->input('min_amount'));
            }
            if ($request->input('max_amount')) {
                $result = $result->where('amount', '<=', $request->input('max_amount'));
            }
            // filter by method
            if ($request->input('method')) {
                $result = $result->where('transaction_type', strtolower($request->input('method')));
            }
            // filter by date to
            if ($request->input('date_to')) {
                $to  = Carbon::parse($request->input('date_to'));
                $result = $result->whereDate('created_at', '<=', $to);
            }
            // filter by date from
            if ($request->input('date_from')) {
                $date_from  = Carbon::parse($request->input('date_from'));
                $result = $result->whereDate('created_at', '>=', $date_from);
            }
            $total_amount = $result->sum('amount');
            $result =  $result->with(['receiver' => function ($query) {
                $query->select('id', 'name', 'email');
            }])->paginate($request->input('per_page', 5) ?? 5);
            return Response::json([
                'status' => true,
                'total_amount' => $total_amount,
                'data' => $result
            ]);
        } catch (\Throwable $th) {
            throw $th;
            return Response::json([
                'status' => false,
                'total_amount' => 0,
                'data' => [],
                'message' => 'Got a server error, please contact for suporrort'
            ]);
        }
    }
}
