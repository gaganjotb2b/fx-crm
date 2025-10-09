<?php

namespace App\Http\Controllers\select2;

use App\Http\Controllers\Controller;
use App\Models\ClientGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class ClientGroupController extends Controller
{
    //get all client group that visible
    public function get_client_group(Request $request)
    {
        $fetchData  = ClientGroup::select()->where('visibility', 'visible')->get();
        if (isset($request->searchTerm)) {
            $search = $request->searchTerm;
            $fetchData = ClientGroup::where('group_name', 'like', '%' . $search . '%')->get();
        }

        $data = array();
        foreach ($fetchData as $key => $value) {
            $data[] = array(
                'id' => $value->id,
                'text' => $value->group_id,
            );
        }
        return Response::json($data);
    }
}
