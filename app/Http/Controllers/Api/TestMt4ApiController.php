<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Account;
use Illuminate\Http\Request;

class TestMt4ApiController extends Controller
{
    public function open_account(Request $request)
    {
        // $data = (object) json_decode($request->data);
        // return $request->all();
        $login = ($request->account_id != null) ? $request->account_id : mt_rand(000000,9999999);
        $create = Account::create([
            'name' => $request->name,
            'account_id' => $login,
            'address' => $request->address,
            'country' => $request->country,
            'city' => $request->city,
            'email' => $request->email,
            'comment' => $request->comment,
            'group' => $request->group,
            'state' => $request->state,
            'leverage' => $request->leverage,
            'zipcode' => $request->zipcode,
            'mqid' => $request->mqid,
            'password_phone' => $request->password_phone,
            'id_number' => $request->id_number,
            'status' => $request->status,
            'taxes' => $request->taxes,
            'agent_account' => $request->agent_account,
            'phone' => $request->phone,
            'password' => $request->password,
            'password_investor' => $request->password_investor,
            // 'enable_change_password' => $request->change_password,
            'enable' => $request->enable,
            'send_reports' => $request->send_reports,
            // 'enable_read_only' => $request->read_only,
            'account_type' => $request->account_type,
            'balance' => $request->balance,
            'mergin' => $request->mergin,
        ]);
        if ($create) {
            return ([
                'status' => true,
                'success'=>true,
                'message' => 'account successfully created',
                'data'=>[
                    'Login'=>$login,
                    'success'=>true,
                ]
            ]);
        }
        return ([
            'status' => false,
            'message' => 'Account creation failed'
        ]);
    }
}
