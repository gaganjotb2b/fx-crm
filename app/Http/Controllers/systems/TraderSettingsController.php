<?php

namespace App\Http\Controllers\systems;

use App\Http\Controllers\Controller;
use App\Models\TraderSetting;
use Illuminate\Http\Request;

class TraderSettingsController extends Controller
{
    public function trader_settings(Request $request)
    {
        $trader_settings = TraderSetting::all();
        return view('systems.configurations.trader-settings', ['trader_settings' => $trader_settings]);
    }
}
