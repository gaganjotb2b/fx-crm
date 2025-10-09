<?php

namespace App\Http\Controllers\traders;

use App\Http\Controllers\Controller;
use App\Models\KycIdType;
use App\Models\KycVerification;
use App\Models\User;
use App\Models\UserDescription;
use App\Services\AllFunctionService;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;

class AccountVerificationController extends Controller
{
    public function __construct()
    {
        $this->middleware(AllFunctionService::access('verification', 'trader'));
        $this->middleware(AllFunctionService::access('my_admin', 'trader'));
    }
    //basic view----------------
    public function verification(Request $request)
    {
        $kyc_id_type = KycIdType::where('group', 'id proof')->get();

        $kyc_address_type = KycIdType::where('group', 'address proof')->get();
        $avatar = avatar();
        // get all details of this user---------
        $user = User::where('users.id', auth()->user()->id)
            ->leftJoin('user_descriptions', 'users.id', '=', 'user_descriptions.user_id')
            ->leftJoin('countries', 'user_descriptions.country_id', '=', 'countries.id')
            ->select(
                'users.id',
                'users.name',
                'users.email',
                'users.phone',
                'users.created_at',
                'countries.name as country',
                'users.email_verified_at'
            )
            ->first();


        // kyc documents status----------------
        $kyc_status = KycVerification::where('user_id', auth()->user()->id);
        $kyc_documents = $kyc_status->get();
        // kyc verification status----------
        $kyc_status = $kyc_status->where('status', '!=', 2);
        $kyc_status = $kyc_status->first();
        if ($kyc_status == null) {
            $kyc_verifiction_status = 'Unverified';
        } elseif ($kyc_status->status == 0) {
            $kyc_verifiction_status = 'Pending';
        } elseif ($kyc_status->status == 1) {
            $kyc_verifiction_status = 'Verified';
        }
        // email verification status---------------
        if (isset($user->email_verified_at) && $user->email_verified_at != "") {
            $email_verification_status = 'Verified';
        } else {
            $email_verification_status = 'Unverified';
        }

        return view(
            'traders.my-admin.account-verification',
            [
                'avatar' => $avatar,
                'id_type' => $kyc_id_type,
                'address_type' => $kyc_address_type,
                'user' => $user,
                'kyc_status' => $kyc_verifiction_status,
                'email_verification_status' => $email_verification_status,
                'kyc_documents' => $kyc_documents,
                'check_kyc_status' => $kyc_status,
            ]
        );
    }
}
