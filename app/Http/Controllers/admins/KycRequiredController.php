<?php

namespace App\Http\Controllers\admins;

use App\Http\Controllers\Controller;
use App\Models\KycRequired;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class KycRequiredController extends Controller
{
    // basic view
    public function setup_remquired(Request $request)
    {
        //  check kyc exist or not
        $check = KycRequired::select()->first();
        $col = $request->type;
        if ($check) {
            $create = KycRequired::where('id', $check->id)->update([
                "$col" => $request->status,
            ]);
        } else {
            $create = KycRequired::create([
                "$col" => $request->status
            ]);
        }
        if ($create) {
            return Response::json([
                'status' => true,
                'message' => 'KYC Setup for ' . str_replace('_', ' ', $col) . 'successfully done'
            ]);
        }
        return Response::json([
            'status' => false,
            'message' => "KYC Setup failed, Please try again later"
        ]);
    }
}
