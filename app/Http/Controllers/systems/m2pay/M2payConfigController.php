<?php

namespace App\Http\Controllers\systems\m2pay;

use App\Http\Controllers\Controller;
use App\Models\M2Pay_Config;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class M2payConfigController extends Controller
{
    public function index()
    {
        $result  = M2Pay_Config::select()->first();
        return view('systems.configurations.m2pay-config',['result' => $result]);
    }

    //--------------------------M2Pay Config Store----------------------
    public function store(Request $request)
    {
        $data = $request->all();
        try {
            $validation_rules = [
                'api_url'    => 'required',
                'api_token'  => 'required',
                'api_secret' => 'required'
            ];            
            $validator = Validator::make($data,$validation_rules);
            if($validator->fails()){
                return Response::json([
                    'status'    => false,
                    'message'   => 'Please fix the following error!',
                    'errors'    => $validator->errors(),
                ]);
            }
            $result  = M2Pay_Config::select()->first();
            $created = M2Pay_Config::updateOrCreate([
                'id' => ($result) ? $request->id : 1
            ],[
                'api_url'    => $request->api_url,
                'api_token'  => $request->api_token,
                'api_secret' => $request->api_secret,
                'ip_address' => $request->ip(),
                'created_by' => Auth::id(),
            ]);

            if($created){
                return Response::json([
                    'status'    => true,
                    'message'   => 'M2Pay Config Successfully Created.'
                ]);
            }else{
                return Response::json([
                    'status'    => false,
                    'message'   => 'Something went wrong, Please try again later!'
                ]);
            }
        } catch (\Throwable $th) {
            throw $th;
            return Response::json([
                'status'    => false,
                'message'   => 'Got a server error'
            ]);
        }
    }
}
