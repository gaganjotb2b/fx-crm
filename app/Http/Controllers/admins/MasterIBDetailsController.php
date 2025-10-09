<?php

namespace App\Http\Controllers\admins;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\IB;
use App\Models\User;
use App\Models\UserDescription;
use App\Services\AllFunctionService;
use App\Services\CombinedService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class MasterIBDetailsController extends Controller
{
    public function masterIBDetails(Request $request)
    {
        $user_descriptions = UserDescription::where('user_id', auth()->user()->id)
            ->join('users', 'users.id', '=', 'user_descriptions.user_id')
            ->first();
        if (isset($user_descriptions->gender)) {
            $avatar = ($user_descriptions->gender === 'Male') ? 'avater-men.png' : 'avater-lady.png'; //<----avatar url
        } else {
            $avatar = 'avater-men.png';
        }
        $ib_id = $request->id;
        $user_info = User::select('users.name', 'users.email', 'users.phone', 'user_descriptions.country_id')->where('users.id', $ib_id)
            ->join('user_descriptions', 'users.id', '=', 'user_descriptions.user_id')->get();
        foreach ($user_info as $info) {
            $country_name = Country::select('name')->where('id', $info->country_id)->first();
        }

        $result = IB::where('ib.ib_id', '=', $ib_id)
            ->where('users.type', '=', CombinedService::type())
            ->select('ib.reference_id')
            ->join('users', 'ib.reference_id', '=', 'users.id');
        if (CombinedService::is_combined()) {
            $result = $result->where('combine_access', 1);
        }
        // total sub ibs 
        $total_ib = $result->count();

        // total sub ib 
        $total_sub_ib = $result->get();
        // count total affiliate traders of this master ib
        $total_affiliate_traders = 0;
        foreach ($total_sub_ib as $sub_id) {
            $count_trader = IB::where('ib.ib_id', '=', $sub_id->reference_id)
                ->where('users.type', '=', 0)
                ->select('users.name')
                ->join('users', 'ib.reference_id', '=', 'users.id')->count();

            $total_affiliate_traders += $count_trader;
        }

        return view('admins.masterIb-details', [
            'avatar' => $avatar,
            'user_info' => $user_info,
            'country_name' => $country_name,
            'total_ib' => AllFunctionService::total_sub_ib($ib_id),
            'total_total_affiliate_traders' => AllFunctionService::total_trader($ib_id),
            'ib_link' => AllFunctionService::ib_referel_link(null),
            'trader_link' => AllFunctionService::trader_referel_link(null)
        ]);
    }
}
