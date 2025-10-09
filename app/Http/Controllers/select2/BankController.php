<?php

namespace App\Http\Controllers\select2;

use App\Http\Controllers\Controller;
use App\Models\OnlineBank;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class BankController extends Controller
{
    public function get_bank(Request $request)
    {
        $fetchData  = OnlineBank::select()->where('country', $request->country)->get();;
        if (isset($request->searchTerm)) {
            $search = $request->searchTerm;
            $fetchData = OnlineBank::where('bank_name', 'like', '%' . $search . '%')->get();
        }

        $data = array();
        foreach ($fetchData as $key => $value) {
            $data[] = array(
                'id' => $value->currency,
                'text' => $value->bank_name,
                'code' => $value->bank_code,
            );
        }
        return Response::json($data);
    }
}
