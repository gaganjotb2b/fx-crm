<?php

namespace App\Http\Controllers\traders;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\ManagerUser;
use App\Models\Traders\SocialLink;
use App\Models\Traders\Trade;
use App\Models\TradingAccount;
use App\Models\User;
use App\Models\UserDescription;
use App\Services\AllFunctionService;
use App\Services\api\FileApiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class ProfileOverviewController extends Controller
{
    public function __construct()
    {
        $this->middleware(AllFunctionService::access('profile_overview', 'trader'));
        $this->middleware(AllFunctionService::access('my_admin', 'trader'));
    }
    public function profile_overview(Request $request)
    {
        $user_descriptions = User::where('user_id', auth()->user()->id)
            ->leftJoin('user_descriptions', 'users.id', '=', 'user_descriptions.user_id')
            ->leftJoin('countries', 'user_descriptions.country_id', '=', 'countries.id')
            ->select('users.*','user_descriptions.*','countries.name as countryName')
            ->first();
        $accountManager = ManagerUser::where('user_id',$user_descriptions->user_id)->pluck('manager_id')->toArray();
            $manager = User::whereIn('id',$accountManager)->select('name','email','phone')->first();
        $time = $month = $year = $date = '';
        if (isset($user_descriptions->date_of_birth)) {
            $time = strtotime($user_descriptions->date_of_birth);
            $month = date("F", $time);
            $year = date("Y", $time);
            $date = date('d', $time);
        }

        if (isset($user_descriptions->gender)) {
            $avatar = ($user_descriptions->gender === 'Male') ? 'avater-men.png' : 'avater-lady.png'; //<----avatar url
        } else {
            $avatar = 'avater-men.png';
        }
        $countries = Country::all();
        $social_link = SocialLink::where('user_id', auth()->user()->id)->first();
        $trading_account = TradingAccount::where('user_id', auth()->user()->id)->paginate(5);
        return view('traders.my-admin.profile-overview', [
            'avatar' => $avatar,
            'user_description' => (isset($user_descriptions) ? $user_descriptions : ''),
            'countries' => (isset($countries) ? $countries : ''),
            'social_link' => (isset($social_link) ? $social_link : ''),
            'manager' => (isset($manager) ? $manager : ''),
            'trading_account' => (isset($trading_account) ? $trading_account : ''),
            'month' => $month,
            'date' => $date,
            'year' => $year
        ]);
    }
    
     // trading account
    public function trading_account(Request $request)
    {
        try {

            $trading_account = TradingAccount::where('user_id', auth()->user()->id);
            $count = $trading_account->count();
            $trading_account = $trading_account->orderBy('id', 'DESC')->skip($request->start)->take($request->length)->get();
            $data = [];
            foreach ($trading_account as $value) {
                $data[] = [
                    'account_number' => $value->account_number,
                    "id" => $value->id,
                    "platform" => $value->platform
                ];
            }
            return Response::json([
                'draw' => $request->draw,
                'recordsTotal' => $count,
                'recordsFiltered' => $count,
                'data' =>  $data,
            ]);
        } catch (\Throwable $th) {
            // return $th;
            return Response::json([
                'draw' => $request->draw,
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' =>  [],
            ]);
        }
    }
    public function userSocialLink(){
        $social_link = SocialLink::where('user_id', auth()->user()->id)->first();
        return response()->json(['social_link' => $social_link]);
    }
    public function updateBasicInfo(Request $request)
    {
        $validation_rules = [
            'full_name'     => 'required',
            // 'state'         => 'required',
            // 'city'          => 'required',
            // 'phone'         => 'required',
            'date_of_birth' => 'required',
            // 'zipcode'       => 'required|numeric',
            // 'address'       => 'required',
        ];

        $validator = Validator::make($request->all(), $validation_rules);
        if ($validator->fails()) {
            return Response::json([
                'status' => false,
                'errors' => $validator->errors(),
                'message' => 'Please fix the following errors!'
            ]);
        } else {
            $date_of_birth = date('Y-m-d H:i:s', strtotime($request->date_of_birth));

            $user = User::find(auth()->user()->id);
            $user->name = $request->full_name;
            $user->phone = $request->phone;
            $update = $user->save();

            $user_descriptions = UserDescription::where('user_id', auth()->user()->id)->first();
            $user_descriptions->gender = $request->gender;
            $user_descriptions->date_of_birth = $date_of_birth;
            $user_descriptions->country_id = $request->country;
            $user_descriptions->state = $request->state;
            $user_descriptions->city = $request->city;
            $user_descriptions->address = $request->address;
            $user_descriptions->zip_code = $request->zipcode;

            $update = $user_descriptions->save();
            if ($update) {
                return Response::json([
                    'status'        => true,
                    'message'       => 'User Updated Successfully',
                    'full_name'     => $request->full_name,
                    'gender'        => $request->gender,
                    'phone'         => $request->phone,
                    'date_of_birth' => $request->date_of_birth,
                    'state'         => $request->state,
                    'city'          => $request->city,
                    'zipcode'       => $request->zipcode,
                    'address'       => $request->address,
                ]);
            }
        }
    }
    public function profilePictureUpload(Request $request)
    {
        $validation_rules = [
            'file_front_part' => 'required|file|mimes:jpeg,png,gif,pdf,jpg|max:1024',
        ];
    
        $validator = Validator::make($request->all(), $validation_rules);
    
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
                'message' => 'Please fix the following errors!'
            ]);
        }
    
        $front = $request->file('file_front_part');
        $filename_front = time() . '_front.' . $front->getClientOriginalExtension();
        $destinationPath = public_path('Uploads/profile');
    
        // Get existing user profile
        $user = UserDescription::where('user_id', auth()->user()->id)->first();
    
        // Delete old profile picture if exists
        if ($user && $user->profile_avater) {
            $old_path = $destinationPath . '/' . $user->profile_avater;
            if (file_exists($old_path)) {
                @unlink($old_path); // suppress error if file is already missing
            }
        }
    
        // Ensure directory exists
        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0775, true);
        }
    
        // Move new file
        $front->move($destinationPath, $filename_front);
    
        // Update record
        $updated = UserDescription::where('user_id', auth()->user()->id)->update([
            'profile_avater' => $filename_front
        ]);
    
        if ($updated) {
            return response()->json([
                'status' => true,
                'message' => 'Profile picture updated successfully.'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Failed to update profile picture.'
            ]);
        }
    }

}
