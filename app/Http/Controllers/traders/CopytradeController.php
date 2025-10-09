<?php

namespace App\Http\Controllers\traders;

use App\Http\Controllers\Controller; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class CopytradeController extends Controller
{
    function CopyTradeDashboardView(Request $request){ 
        return view('traders.copy-trade.copy-trade-dashboard'); 
    }
    function CopyTradeOverview(Request $request){ 
        return view('traders.copy-trade.copy-trade-overview'); 
    }
}
