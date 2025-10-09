<?php

namespace App\Http\Controllers\traders;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EconomicCalendarController extends Controller
{
    public function economicCalendarView()
    {
        return view('traders.economic-calendar');
    }
}
