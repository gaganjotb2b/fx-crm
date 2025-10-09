<?php

namespace App\Http\Controllers\select2;

use App\Http\Controllers\Controller;
use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class CountryController extends Controller
{
    public function country_value_name(Request $request)
    {
        $fetchData  = Country::select()->limit(5)->get();;
        if (isset($request->searchTerm)) {
            $search = $request->searchTerm;
            $fetchData = Country::where('name', 'like', '%' . $search . '%')->get();
        }

        $data = array();
        foreach ($fetchData as $key => $value) {
            $data[] = array(
                'id' => $value->name,
                'text' => $value->name,
            );
        }
        return Response::json($data);
    }
}
