<?php

namespace App\Http\Controllers\Api\country;

use App\Http\Controllers\Controller;
use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class CountryController extends Controller
{
    //get all acountry
    public function get_country(Request $request)
    {
        try {
            $countries = Country::all();
            if ($countries) {
                return Response::json([
                    'status' => true,
                    'countries' => $countries
                ], 200);
            }
            return Response::json([
                'status' => false,
                'message' => "The request recource was not found",
                'errors' => 'Resource not found',
            ], 404);
        } catch (\Throwable $th) {
            //throw $th;
            return Response::json([
                'status' => false,
                "error" => "Internal Server Error",
                "message" => "An unexpected error occurred while processing your request."
            ], 500);
        }
    }
}
