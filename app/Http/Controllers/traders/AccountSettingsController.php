<?php

namespace App\Http\Controllers\Traders;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AccountSettingsController extends Controller
{
    public function settings(Request $request){
        return view('traders.my-admin.account-settings');
    }
}
