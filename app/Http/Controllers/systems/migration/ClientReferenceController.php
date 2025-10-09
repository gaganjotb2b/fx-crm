<?php

namespace App\Http\Controllers\systems\migration;

use App\Http\Controllers\Controller;
use App\Models\IB;
use App\Models\User;
use App\Services\CombinedService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class ClientReferenceController extends Controller
{
    public function index(Request $request)
    {
        return view('systems.migrations.ib-to-clients');
    }
    public function store(Request $request)
    {
        $validation_rules = [
            'csv_file' => 'required|mimes:csv',
        ];
        $validator = Validator::make($request->all(), $validation_rules);
        if ($validator->fails()) {
            return Response::json([
                'status' => false,
                'errors' => $validator->errors(),
                'message' => 'Fix the following Errors'
            ]);
        }
        $path = $request->file('csv_file')->getRealPath();

        $records = array_map('str_getcsv', file($path));
        $created_user = [];
        if (!count($records) > 0) {
            return Response::json([
                'status' => false,
                'message' => 'This file is broken'
            ]);
        }
        // Get field names from header column
        $fields = array_map('strtolower', $records[0]);
        $fields = str_replace(' ', '_', $fields);
        // // chacking header 
        // return $fields;
        // Remove the header column
        // return $records;
        array_shift($records);

        for ($i = 0; $i < count($records); $i++) {
            for ($j = 0; $j < count($records[$i]); $j++) {
                // check mail exist in file
                if ($records[$i][array_search('ib', $fields)] != "" && $records[$i][array_search('client', $fields)] != "") {
                    $check_ib = User::where('users.email', 'like', '%' . trim($records[$i][array_search('ib', $fields)]) . '%')
                        ->where('type', CombinedService::type());
                    if (CombinedService::is_combined()) {
                        $check_ib = $check_ib->where('users.combine_access', 1);
                    }
                    $client = User::where('users.email', 'like', '%' . trim($records[$i][array_search('client', $fields)]) . '%')->where('type', 0);
                    if ($check_ib->exists() && $client->exists()) {
                        $parent_ib = $check_ib->select('id')->first();
                        $client = $client->select('id')->first();
                        $check_tbl_ib = IB::where('ib_id', $parent_ib->id)->where('reference_id', $client->id)->exists();
                        if (!$check_tbl_ib) {
                            IB::updateOrCreate([
                                'ib_id' => $parent_ib->id,
                                'reference_id' => $client->id,
                            ]);
                        }
                    }
                } //end mail check in file
            }
        }

        return Response::json([
            'status' => true,
            'message' => 'Import success',
        ]);
    }
}
