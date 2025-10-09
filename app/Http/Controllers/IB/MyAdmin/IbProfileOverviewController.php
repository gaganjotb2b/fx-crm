<?php

namespace App\Http\Controllers\IB\MyAdmin;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\Traders\SocialLink;
use App\Models\Traders\Trade;
use App\Models\TradingAccount;
use App\Models\UserDescription;
use App\Services\AllFunctionService;
use App\Services\PermissionService;
use Illuminate\Http\Request;

class IbProfileOverviewController extends Controller
{
    public function __construct()
    {
        $this->middleware(AllFunctionService::access('profile_overview', 'ib'));
        $this->middleware(AllFunctionService::access('my_admin', 'ib'));
        $this->middleware('is_ib');
        // if (request()->is('ib/ib-admin/profile-overview')) {
        //     $this->middleware(PermissionService::is_combined());
        // }
    }
    public function profile_overview()
    {
        $user_descriptions = UserDescription::where('user_id', auth()->user()->id)
            ->leftjoin('users', 'user_descriptions.user_id', '=', 'users.id')
            ->leftJoin('countries', 'user_descriptions.country_id', '=', 'countries.id')
            ->select('users.*','user_descriptions.*','countries.name as countryName')
            ->first();

            if (isset($user_descriptions->date_of_birth)) {
                $time = strtotime($user_descriptions->date_of_birth);
                $month = date("F", $time);
                $year = date("Y", $time);
                $date = date('d', $time);
            }

        $sub_ib_with_level = AllFunctionService::sub_ib_with_level(null);

        if (isset($user_descriptions->gender)) {
            $avatar = ($user_descriptions->gender === 'Male') ? 'avater-men.png' : 'avater-lady.png'; //<----avatar url
        } else {
            $avatar = 'avater-men.png';
        }
        $countries = Country::all();
        $social_link = SocialLink::where('user_id', auth()->user()->id)->first();
        $trading_account = TradingAccount::where('user_id', auth()->user()->id)->paginate(5);
        return view('ibs.ib-admins.profile-overview', [
            'avatar' => $avatar,
            'user_description' => (isset($user_descriptions) ? $user_descriptions : ''),
            'countries' => (isset($countries) ? $countries : ''),
            'social_link' => (isset($social_link) ? $social_link : ''),
            'trading_account' => (isset($trading_account) ? $trading_account : ''),
            'month' => $month,
            'date' => $date,
            'year' => $year,
            'all_sub_id' => $sub_ib_with_level,
        ]);
    }
    public function userSocialLinkIb(){
        $social_link = SocialLink::where('user_id', auth()->user()->id)->first();
        return response()->json(['social_link' => $social_link]);
    }
}
