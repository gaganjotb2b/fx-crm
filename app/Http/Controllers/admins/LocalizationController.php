<?php

namespace App\Http\Controllers\admins;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;

class LocalizationController extends Controller
{
    // language change

    public function lang_change(Request $request)
    {
        App::setLocale($request->lang);
        if (array_key_exists($request->lang, Config::get('languages'))) {
            Session::put('locale', $request->lang);
        }
        if (Session::has('locale')) {
            return Response::json(['status'=>true]);
        } else {
            return Response::json(['status' => false]);
        }
    }
}
