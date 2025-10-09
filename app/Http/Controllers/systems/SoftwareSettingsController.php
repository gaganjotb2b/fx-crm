<?php

namespace App\Http\Controllers\systems;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SoftwareSettingsController extends Controller
{
    public function index(Request $request)
    {
        return view('systems.choose-mail');
    }
}
