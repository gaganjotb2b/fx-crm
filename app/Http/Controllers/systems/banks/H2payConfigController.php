<?php

namespace App\Http\Controllers\systems\banks;

use App\Http\Controllers\Controller;
use App\Models\H2pay;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class H2payConfigController extends Controller
{
    public function index()
    {
        $result  = H2pay::select()->first();
        return view('systems.bnaks.h2pay-config',['result' => $result]);
    }

    public function store(Request $request)
    {
        try {
            $validation_rules = [
                'code'          => 'required',
                'user_name'     => 'required',
                'password'      => 'required|min:6|max:16',
                'security_code' => 'required',
                'api_url'       => 'required',
            ];
            $validator = Validator::make($request->all(),$validation_rules);

            if($validator->fails())
            {
                return Response::json([
                    'status'  => false,
                    'message' => 'Fix the following error',
                    'errors'  => $validator->errors(),
                ]);
            }

            $result  = H2pay::select()->first();
            $created = H2pay::updateOrCreate([
                'id' => ($result) ? $request->id : 1
            ],[
                'merchent_code' => $request->code,
                'user_name'     => $request->user_name,
                'password'      => $request->password,
                'security_code' => $request->security_code,
                'api_url'       => $request->api_url,
                'created_by'    => Auth::id(),
                'ip_address'    => $request->ip(),
            ]); 

            if($created){
                return Response::json([
                    'status'  => true,
                    'message' => 'H2Pay Config Created Successfully Done.',
                ]);
            }else{
                return Response::json([
                    'status'    => false,
                    'message'   => 'Something went wrong, Please try again later!',
                ]);
            }
        } catch (\Throwable $th) {
            throw $th;
            return Response::json([
                'status'    => false,
                'message'   => 'Got a server error!'
            ]);
        }
    }
}
