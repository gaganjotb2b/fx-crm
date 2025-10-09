<?php

namespace App\Http\Controllers\systems;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SystemDashboardController extends Controller
{
    public function dashboard()
    {
        return view('systems.dashboard');
    }
}
