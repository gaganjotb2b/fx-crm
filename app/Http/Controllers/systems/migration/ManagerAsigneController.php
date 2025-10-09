<?php

namespace App\Http\Controllers\systems\migration;

use App\Http\Controllers\Controller;
use App\Models\Log;
use App\Models\ManagerUser;
use App\Models\User;
use App\Services\CombinedService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class ManagerAsigneController extends Controller
{
    public function index(Request $request)
    {
        return view('systems.migrations.manager-asigne');
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
        
        if (!count($records) > 0) {
            return Response::json([
                'status' => false,
                'message' => 'This file is broken or empty!'
            ]);
        }
        // Get field names from header column
        $fields = array_map('strtolower', $records[0]);
        $fields = str_replace(' ', '_', $fields);
        array_shift($records); // remove header columns | first row
        // check the required field is exists in the csv
        // check name field is available
        if (!in_array('email', $fields)) {
            return Response::json([
                'status' => false,
                'message' => 'The name field is required in this csv file',
            ]);
        }
        // check email field is available
        if (!in_array('manager', $fields)) {
            return Response::json([
                'status' => false,
                'message' => 'The manager(value as email) field is required in this csv file',
            ]);
        }
        $count_created = 0;
        for ($i = 0; $i < count($records); $i++) {
            for ($j = 0; $j < count($records[$i]); $j++) {
                // check mail exist in file
                if (trim($records[$i][array_search('email', $fields)]) != "" && trim($records[$i][array_search('manager', $fields)]) != "") {
                    $check_client = User::where('email', 'like', '%' . trim($records[$i][array_search('email', $fields)]) . '%');
                    $check_manager = User::where('email', 'like', '%' . trim($records[$i][array_search('manager', $fields)]) . '%');
                    // client and manager exist in database
                    if ($check_client->exists() && $check_manager->exists()) {
                        $client = $check_client->first();
                        $manager = $check_manager->first();
                        $asigne_manager = ManagerUser::updateOrCreate(
                            [
                                'manager_id' => $manager->id,
                                'user_id' => $client->id,
                            ]
                        );
                        if ($asigne_manager) {
                            $count_created++;
                        }
                    }
                } //end mail check in file
            }
        }
        return Response::json([
            'status' => true,
            'message' => 'Import success, Total ' . $count_created . ' user asigned to manager',
        ]);
    }
}
