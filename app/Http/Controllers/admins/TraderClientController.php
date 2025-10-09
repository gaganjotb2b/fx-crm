<?php

namespace App\Http\Controllers\admins;

use App\Http\Controllers\Controller;
use App\Mail\UpdateProfile;
use App\Models\Admin\AdminUser;
use App\Models\admin\BalanceTransfer;
use App\Models\admin\SystemConfig;
use App\Models\Category;
use App\Models\Country;
use App\Models\Deposit;
use App\Models\IB;
use App\Models\IbTransfer;
use App\Models\KycVerification;
use App\Models\Log;
use App\Models\Manager;
use App\Models\Traders\SocialLink;
use App\Models\User;
use App\Models\UserDescription;
use App\Models\WalletUpDown;
use App\Models\Withdraw;
use App\Services\AllFunctionService;
use App\Services\KycService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class TraderClientController extends Controller
{
    public function __construct()
    {
        // $this->middleware(["role:trader client"]);
        // $this->middleware(["role:manage client"]);
    }
    // datable finance reports
    // --------------------------------------------------------------
    public function finance_report(Request $request)
    {
        $draw = $request->input('draw');
        $start = $request->input('start');
        $length = $request->input('length');
        $order = $_GET['order'][0]["column"];
        $orderDir = $_GET["order"][0]["dir"];
        $columns = ['active_status', 'name', 'email', 'phone', 'created_at'];
        $orderby = $columns[$order];
        // select type= 0 for trader
        $result = User::where('type', 0);
        $count = $result->count(); // <------count total rows
        $result = $result->orderby($orderby, $orderDir)->skip($start)->take($length)->get();
        $data = array();
        $i = 0;
    }
    // get user info--------------------------------------------------------
    public function get_user_info(Request $request, $id)
    {
        $user = User::where('users.id', $id)
            ->leftJoin('user_descriptions', 'users.id', '=', 'user_descriptions.user_id')
            ->leftJoin('countries', 'user_descriptions.country_id', '=', 'countries.id')
            ->select(
                'users.id as user_id',
                'users.name',
                'users.email',
                'users.phone',
                'countries.name as country_name',
                'countries.id as country_id',
                'app_investment',
                'user_descriptions.city',
                'user_descriptions.state',
                'user_descriptions.zip_code',
                'user_descriptions.address',
                'users.password',
                'users.transaction_password',
                'users.trading_ac_limit',
                'users.live_status',
                'users.kyc_status',
            )
            ->first();
        $user_passwords = Log::where('user_id', $id)->first();
        $password = decrypt($user_passwords->password);
        $transaction_password = decrypt($user_passwords->transaction_password);
        // social links
        $social_link = SocialLink::where('user_id', $id)->first();
        if ($social_link) {
            $social_link = $social_link;
        } else {
            $social_link = [
                'facebook' => '',
                'twitter' => '',
                'whatsapp' => '',
                'telegram' => '',
                'linkedin' => '',
                'skype' => '',
            ];
        }
        return Response::json([
            'user' => $user,
            'password' => $password,
            'transaction_password' => $transaction_password,
            'social' => $social_link,
        ]);
    }
    // END: user info-----------------------------------------

    // update profile-----------------------------------------------
    public function update_profile(Request $request)
    {
        $validation_rules = [
            'name' => 'required',
            'email' => 'nullable|email',
            'phone' => 'required|max:32',
            'country' => 'required',
            'app_investment' => 'required|numeric',
            'city' => 'required|max:100',
            'state' => 'required|max:100',
            'zip_code' => 'required|max:32',
            'address' => 'required',
            'password' => 'nullable|max:32',
            'transaction_pin' => 'nullable|max:32',
            'facebook' => 'nullable|max:191',
            'twitter' => 'nullable|max:191',
            'whatsapp' => 'nullable|max:191',
            'linkedin' => 'nullable|max:191',
            'telegram' => 'nullable|max:191',
            'skype' => 'nullable|max:191',
            'client_type' => 'required',
        ];
        // kyc id proof validation
        if (KycService::has_kyc($request->user_id, 'all') == false) {
            if ($request->perpose === 'id proof') {
                $validation_rules = [
                    'document_type' => 'required',
                    'issue_date' => 'required',
                    'expire_date' => 'required',
                    'file_front_part' => 'required',
                    'file_back_part' => 'required',
                    'kyc_status' => 'required',
                ];
            }
            // kyc address proof validation
            if ($request->perpose === 'address proof') {
                $validation_rules = [
                    'document_type' => 'required',
                    'issue_date' => 'required',
                    'expire_date' => 'required',
                    'file_document' => 'required',
                    'kyc_status' => 'required',
                ];
            }
        }

        $validator = Validator::make($request->all(), $validation_rules);
        if ($validator->fails()) {
            if ($request->ajax()) {
                return Response::json([
                    'status' => false,
                    'errors' => $validator->errors(),
                    'message' => 'You have an error, please check manually!'
                ]);
            }
        } else {
            // update users table
            $user = User::find($request->user_id);
            $user->name = $request->name;
            $user->phone = $request->phone;
            $user->app_investment = $request->app_investment;
            $user->password = ($request->password != "") ? $request->password : $user->password;
            $user->transaction_password = ($request->transaction_pin != "") ? $request->transaction_pin : $user->transaction_password;
            $user->trading_ac_limit = $request->trading_ac_limit;
            $update = $user->Save();

            // update user description table
            $user_description = UserDescription::where('user_id', $request->user_id)->first();
            $user_description->country_id = $request->country;
            $user_description->state = $request->state;
            $user_description->city = $request->city;
            $user_description->zip_code = $request->zip_code;
            $user_description->address = $request->address;
            $des_update = $user_description->save();

            // update or kyc table
            if (KycService::has_kyc($user->id, 'all') == false) {


                if (isset($request->perpose)) {
                    // upload kyc address proof
                    // address proof
                    if ($request->perpose === 'address proof') {
                        $file_document = $request->file('file_document')[0];
                        if (substr($file_document->getMimeType(), 0, 5) != 'image') {
                            return Response::json([
                                'status' => false,
                                'errors' => [
                                    'file_document' => 'The file is not an image/pdf'
                                ],
                                'message' => 'Please fix the following errors!'
                            ]);
                        }


                        $check_exist = KycVerification::where('user_id', auth()->user()->id)->where('status', '!=', 2)->where('perpose', 'address proof')->exists();
                        if ($check_exist) {
                            return Response::json([
                                'status' => false,
                                'message' => 'KYC already exist for address proof, Please contact your manger'
                            ]);
                        } else {

                            $address_document = $request->file('file_document')[0];
                            $filename_address = time() . '_address_document_' . $address_document->getClientOriginalName();
                            $address_document->move(public_path('/Uploads/kyc'), $filename_address);
                            $created = KycVerification::create([
                                'user_id' => $user->id,
                                'issue_date' => $request->issue_date,
                                'exp_date' => $request->expire_date,
                                'doc_type' => $request->document_type,
                                'perpose' => $request->perpose,
                                'note' => $request->decline_reason,
                                'status' => ($request->kyc_status) ? 1 : 0,
                                'approved_by' => auth()->user()->id,
                                'approved_date' => date('Y-m-d h:i:s', strtotime(now())),
                                'document_name' => json_encode(['front_part' => $filename_address, 'back_part' => ''])
                            ])->id;
                        }
                    }
                    // upload kyc id proof
                    if ($request->perpose === 'id proof') {
                        $file_front_part = $request->file('file_front_part')[0];
                        if (substr($file_front_part->getMimeType(), 0, 5) != 'image') {
                            return Response::json([
                                'status' => false,
                                'errors' => [
                                    'file_front_part' => 'The file is not an image/pdf'
                                ],
                                'message' => 'Please fix the following errors!'
                            ]);
                        }
                        $file_back_part = $request->file('file_back_part')[0];
                        if (substr($file_back_part->getMimeType(), 0, 5) != 'image') {
                            return Response::json([
                                'status' => false,
                                'errors' => [
                                    'file_back_part' => 'The file is not an image/pdf'
                                ],
                                'message' => 'Please fix the following errors!'
                            ]);
                        }


                        $check_exist = KycVerification::where('user_id', $user->id)->where('status', '!=', 2)->where('perpose', 'id proof')->exists();
                        if ($check_exist) {
                            return Response::json([
                                'status' => false,
                                'message' => 'KYC already exist for ID proof, Please contact your manger'
                            ]);
                        } else {

                            $front_part = $request->file('file_front_part')[0];
                            $filename_front_part = time() . '_id_front_part_' . $front_part->getClientOriginalName();
                            $front_part->move(public_path('/Uploads/kyc'), $filename_front_part);

                            $back_part = $request->file('file_back_part')[0];
                            $filename_back_part = time() . '_id_back_part_' . $back_part->getClientOriginalName();
                            $back_part->move(public_path('/Uploads/kyc'), $filename_back_part);

                            $document_name = [
                                'front_part' => $filename_front_part,
                                'back_part' => $filename_back_part
                            ];
                            $created = KycVerification::create([
                                'user_id' => $user->id,
                                'issue_date' => $request->issue_date,
                                'exp_date' => $request->expire_date,
                                'doc_type' => $request->document_type,
                                'perpose' => $request->perpose,
                                'note' => $request->decline_reason,
                                'status'  => ($request->kyc_status) ? 1 : 0,
                                'approved_by' => auth()->user()->id,
                                'approved_date' => date('Y-m-d h:i:s', strtotime(now())),
                                'document_name' => json_encode($document_name)
                            ])->id;
                        }
                    }
                }
            } else {
                switch ($request->kyc_status) {
                    case 'on':
                        KycService::kyc_approve($request->user_id, $request->up_document_type, 1);
                        break;

                    default:
                        KycService::kyc_approve($request->user_id, $request->up_document_type, 0);
                        break;
                }
            }
            // update social link table
            if (SocialLink::where('user_id', $request->user_id)->exists()) {
                $social_link = SocialLink::where('user_id', $request->user_id)->update([
                    'facebook' => $request->facebook,
                    'twitter' => $request->twitter,
                    'whatsapp' => $request->whatsapp,
                    'linkedin' => $request->linkedin,
                    'telegram' => $request->telegram,
                    'skype' => $request->skype,
                ]);
            } else {
                $social_link = SocialLink::create([
                    'user_id' => $request->user_id,
                    'facebook' => $request->facebook,
                    'twitter' => $request->twitter,
                    'whatsapp' => $request->whatsapp,
                    'linkedin' => $request->linkedin,
                    'telegram' => $request->telegram,
                    'skype' => $request->skype
                ]);
            }
            // sending email
            if ($update && $des_update) {
                if (isset($request->send_email)) {
                    $system_config = SystemConfig::select()->first();
                    $support_email = (isset($system_config->support_email)) ? $system_config->support_email : '';

                    $email_data = [
                        'emailSupport' => $support_email,
                        'clientName' => $user->name,
                        'customMessage' => (isset($request->note)) ? $request->note : '',
                        'phone1' => (isset($user->phone)) ? $user->phone : '',
                        'companyName' => (isset($system_config->com_name)) ? $system_config->com_name : '',
                        'emailCommon' => $request->email,
                        'loginUrl' => route('login'),
                        'website' => (isset($system_config->com_website)) ? $system_config->com_website : '',
                        'copy_right' => (isset($system_config->copyright)) ? $system_config->copyright : '',
                        'authority' => (isset($system_config->com_authority)) ? $system_config->com_authority : '',
                        'license' => (isset($system_config->com_license)) ? $system_config->com_license : ''
                    ];
                    if (Mail::to($user->email)->send(new UpdateProfile($email_data))) {
                        return Response::json(['status' => true, 'message' => 'Mail successfully sent for profile update']);
                    } else {
                        return Response::json(['status' => 'false_true', 'message' => 'Profile Updated But Mail sending failed, Please try again later!']);
                    }
                }
                return Response::json(['status' => true, 'message' => 'profile successfully updated']);
            } else {
                return Response::json(['status' => false, 'message' => 'Somthing went wrong please try again later']);
            }
        }
    }
    // END: update profile------------------------------------------

}
