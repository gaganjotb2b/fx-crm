<?php

namespace App\Http\Controllers\systems;

use App\Http\Controllers\Controller;
use App\Models\SoftwareSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class VersionController extends Controller
{
    public function virsion_upgrate(Request $request)
    {
        $check = SoftwareSetting::first();
        if ($check) {
            // update existing row
            $update = SoftwareSetting::where('id', $check->id)->update([
                'version' => $request->version,
            ]);
            if ($update) {
                return Response::json([
                    'status' => true,
                    'message' => 'CRM Version ' . $request->version . ' Activated',
                ]);
            }
            return Response::json([
                'status' => false,
                'message' => 'Somthing went wrong, Please try again later'
            ]);
        } else {
            // create new row
            $update = SoftwareSetting::create([
                'version' => $request->version,
            ]);
            if ($update) {
                return Response::json([
                    'status' => true,
                    'message' => 'CRM Version ' . $request->version . ' Activated',
                ]);
            }
            return Response::json([
                'status' => false,
                'message' => 'Somthing went wrong, Please try again later'
            ]);
        }
    }
}
