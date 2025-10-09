<?php

namespace App\Http\Controllers\select2;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\CombinedService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class ClientController extends Controller
{
    //
    public function get_clients(Request $request)
    {
        try {
            $fetchData  = User::select()->limit(5)->where('type', CombinedService::type())->get();
            if (isset($request->searchTerm)) {
                $search = $request->searchTerm;
                $fetchData = User::where('email', 'like', '%' . $search . '%')->where('type', CombinedService::type())->limit(5)->get();
            }

            $data = array();
            foreach ($fetchData as $key => $value) {
                $data[] = array(
                    'id' => $value->id,
                    'text' => $value->email,
                );
            }
            return Response::json($data);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
}
