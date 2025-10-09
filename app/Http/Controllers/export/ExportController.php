<?php

namespace App\Http\Controllers\export;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class ExportController extends Controller
{
    function export_done(Request $request)
    {
        $url = $request->done;
        $fileName = basename($url);

        if (file_exists(public_path('/export/' . $fileName))) {
            // return $fileName;
            if (unlink(public_path('/export/' . $fileName))) {
                return Response::json([
                    'status' => true,
                    'message' => 'Export successfully done'
                ]);
            }
        }
    }
}
