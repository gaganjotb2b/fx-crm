<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ErrorController extends Controller
{
    public function custom_forbidden(Request $request)
    {
        // return "this is error page";
        return view('errors.not-authorize');
    }
}
