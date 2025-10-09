<?php

namespace App\Http\Controllers\systems;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class B2bSettingsController extends Controller
{
    public function index()
    {
        return view('systems.paymets.b2b');
    }
}
