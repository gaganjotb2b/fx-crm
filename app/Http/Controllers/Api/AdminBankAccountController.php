<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\deposit\BankService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class AdminBankAccountController extends Controller
{
    // get admin all active bank
    public function get_admin_active_bank(Request $request)
    {
        try {
            $response = BankService::get_admin_banks(
                ['status' => 1]
            );
            return Response::json($response, 200);
        } catch (\Throwable $th) {
            //throw $th;
            return Response::json([
                'status' => false,
                "error" => "Internal Server Error",
                "message" => "An unexpected error occurred while processing your request."
            ], 500);
        }
    }
}
