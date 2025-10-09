<?php

namespace App\Http\Controllers\systems\commission;

use App\Http\Controllers\Controller;
use App\Models\CustomCommission;
use App\Models\IbCommissionStructure;
use App\Models\RemainingComSetup;
use App\Services\commission\CommissionStructureService;
use App\Services\IbService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class RemainingCommissionController extends Controller
{
    //
    public function setup_store(Request $request)
    {
        try {
            $result = RemainingComSetup::first();
            $create = RemainingComSetup::updateOrCreate(
                [
                    'id' => isset($result->id) ? $result->id : '',
                ],
                [
                    'remaining' => ($request->option == 'on') ? 'true' : 'false',
                    'first_level' => 'all',
                    'ip_address' => $request->ip(),
                ]
            );
            if ($create) {
                CommissionStructureService::reset_custom_commission();
                return Response::json([
                    'status' => true,
                    'message' => ($request->option == 'on') ? 'Remaining commission setup successfully on.' : 'Remaining commission setup successfully off.',
                ]);
            }
            return Response::json([
                'status' => false,
                'message' => 'Something went wrong, please try again later!'
            ]);
        } catch (\Throwable $th) {
            throw $th;
            return Response::json([
                'status' => false,
                'message' => 'We got a server error!'
            ]);
        }
    }
    // reset specific structure for specific 
}
