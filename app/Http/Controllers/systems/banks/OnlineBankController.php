<?php

namespace App\Http\Controllers\systems\banks;

use App\Http\Controllers\Controller;
use App\Models\OnlineBank;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class OnlineBankController extends Controller
{
    public function index(Request $request)
    {
        return view('systems.bnaks.online-bank');
    }
    public function add_bank(Request $request)
    {
        try {
            $validation_rule = [
                'bank_country' => 'required',
                'bank_name' => 'required|unique:online_banks,bank_name',
                'bank_code' => 'required',
                'currency' => 'required',
            ];
            $validator = Validator::make($request->all(), $validation_rule);
            if ($validator->fails()) {
                return Response::json([
                    'status' => false,
                    'message' => 'Validation error found, please fix',
                    'errors' => $validator->errors(),
                ]);
            }
            $create = OnlineBank::create([
                'status' => 'active',
                'country' => $request->bank_country,
                'bank_name' => $request->bank_name,
                'bank_code' => $request->bank_code,
                'currency' => $request->currency,
                'ip_address' => $request->ip(),
            ]);
            if ($create) {
                return Response::json([
                    'status' => true,
                    'message' => 'Bank successfully added!',
                ]);
            }
            return Response::json([
                'status' => false,
                'message' => 'Something went wrong, please try again later!',
            ]);
        } catch (\Throwable $th) {
            // throw $th;
            return Response::json([
                'status' => false,
                'message' => 'Server error, please try again later!',
            ]);
        }
    }
    // create datatable
    public function get_bank(Request $request)
    {
        try {

            $columns = ['bank_name', 'bank_country', 'bank_code', 'currency', 'status', 'created_at'];
            $orderby = $columns[$request->order[0]['column']];
            // select type= 0 for trader 
            $result = OnlineBank::select();

            $count = $result->count(); // <------count total rows
            $result = $result->orderby($orderby, $request->order[0]['dir'])->skip($request->start)->take($request->length)->get();
            $data = array();
            foreach ($result as $key => $value) {
                $data[] = [
                    'bank_name' => $value->bank_name,
                    'bank_country' => $value->country,
                    'bank_code' => $value->bank_code,
                    'currency' => $value->currency,
                    'status' => $value->status,
                    'action' => '<button class="btn btn-primary btn-sm btn-edit" data-id="' . $value->id . '"><i data-feather="edit"></i></button>'
                ];
            }
            return Response::json([
                'draw' => $_REQUEST['draw'],
                'recordsTotal' => $count,
                'recordsFiltered' => $count,
                'data' => $data,
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return Response::json([
                'draw' => $_REQUEST['draw'],
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => [],
            ]);
        }
    }
    public function get_edit_data(Request $request)
    {
        try {
            $result = OnlineBank::find($request->id);
            return Response::json([
                'bank_country' => $result->country,
                'bank_name' => $result->bank_name,
                'bank_code' => $result->bank_code,
                'currency' => $result->currency,
                'id' => $result->id,
            ]);
        } catch (\Throwable $th) {
            throw $th;
            // return [];
        }
    }
    public function edit_bank(Request $request)
    {
        try {
            $validation_rule = [
                'bank_country' => 'required',
                'bank_name' => 'required',
                'bank_code' => 'required',
                'currency' => 'required',
            ];
            $validator = Validator::make($request->all(), $validation_rule);
            if ($validator->fails()) {
                return Response::json([
                    'status' => false,
                    'message' => 'Validation error found, please fix',
                    'errors' => $validator->errors(),
                ]);
            }
            $create = OnlineBank::where('id', $request->id)->update([
                'status' => 'active',
                'country' => $request->bank_country,
                'bank_name' => $request->bank_name,
                'bank_code' => $request->bank_code,
                'currency' => $request->currency,
                'ip_address' => $request->ip(),
            ]);
            if ($create) {
                return Response::json([
                    'status' => true,
                    'message' => 'Bank successfully updated!',
                ]);
            }
            return Response::json([
                'status' => false,
                'message' => 'Something went wrong, please try again later!',
            ]);
        } catch (\Throwable $th) {
            // throw $th;
            return Response::json([
                'status' => false,
                'message' => 'Server error, please try again later!',
            ]);
        }
    }
}
