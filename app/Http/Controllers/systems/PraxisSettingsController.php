<?php

namespace App\Http\Controllers\systems;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PraxisSettingsController extends Controller
{
    public function index()
    {
        return view('systems.paymets.praxis');
    }
}
