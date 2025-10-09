<?php

namespace App\Http\Controllers\admins\Vouchers;

use App\Http\Controllers\Controller;
use App\Mail\Voucher as MailVoucher;
use App\Mail\VoucherGenerate;
use App\Models\admin\SystemConfig;
use App\Models\User;
use App\Models\Voucher;
use App\Services\AllFunctionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class VoucherController extends Controller
{
    public function __construct()
    {
        $this->middleware(["role:voucher generate"]);
        $this->middleware(["role:offers"]);
        // system module control
        $this->middleware(AllFunctionService::access('offers', 'admin'));
        $this->middleware(AllFunctionService::access('voucher_generate', 'admin'));
    }
    public function voucherShow()
    {
        return view('admins.vouchers.voucher');
    }
    public function traderEmail(Request $request)
    {
        $trader_email = User::select('id', 'email')->where('type', 0)->get();
        $trader_options = '';
        foreach ($trader_email as $value) :
            $trader_options .= '<option value="' . $value->id . '">' . $value->email . '</option>';
        endforeach;
        $data = [
            'status' => true,
            'options' => $trader_options
        ];

        return Response::json($data);
    }
    public function createVoucher(Request $request)
    {
        $validation_rules = [
            'amount' => 'required',
            'expire_date' => 'required',
            'user_classifie' => 'required'
        ];

        $validator = Validator::make($request->all(), $validation_rules);
        if ($validator->fails()) {
            if ($request->ajax()) {
                return Response::json(['success' => false, 'errors' => $validator->errors()]);
            }
        } else {
            $trader_id = $request->input('trader');
            $expire_date = $request->input('expire_date');
            $sent_to = $request->input('user_mail');
            $amount = $request->input('amount');
            $user_classifie = $request->input('user_classifie');
            $user_type = $request->input('user_type');
            $admin = Auth::user()->id;
            $security = Str::random(5);
            $length = 15;
            $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $charactersLength = strlen($characters);
            $token = '';
            for ($i = 0; $i < $length; $i++) {
                $token .= $characters[rand(0, $charactersLength - 1)];
            }
            $todayDate = date('Y-m-d');
            if ($expire_date < $todayDate) {
                return Response::json(['success' => false, 'message' => 'faild', 'date_error' => 'Expire date should be grater then or equal to Today Date']);
            }
            //------------voucher create-------
            if ($user_classifie == 'general') {
                $user_classifie = Voucher::create([
                    'amount' => $amount,
                    'expire_date' => $expire_date,
                    'token' => $token,
                    'security' => $security,
                    'send_to' => $sent_to,
                    'created_by' => $admin,
                    'user_id' => $trader_id,
                    'user_type' => "",
                ]);
            } else if ($user_classifie == 'classic') {

                $user_classifie = Voucher::create([
                    'amount' => $amount,
                    'expire_date' => $expire_date,
                    'token' => $token,
                    'security' => $security,
                    'send_to' => $sent_to,
                    'created_by' => $admin,
                    'user_id' => $trader_id,
                    'user_type' => $user_type,
                ]);
            }

            if ($user_classifie) {
                if (isset($sent_to)) {
                    $user = User::find($trader_id);
                    $support_email = SystemConfig::select('support_email')->first();
                    $support_email = ($support_email) ? $support_email->support_email : default_support_email();
                    $email_data = [
                        'name'              => ($user) ? $user->name : config('app.name') . ' User',
                        'token_number'      =>  $token,
                        'amount'            => $amount,
                        'support_email'     => $support_email,

                    ];
                    Mail::to($sent_to)->send(new VoucherGenerate($email_data));
                }
                return Response::json(['success' => true, 'message' => 'Voucher Generate Successfully', 'success_title' => 'Voucher Create']);
            } else {
                return Response::json(['success' => false, 'message' => 'Voucher Generate Failed, Please try again later!', 'success_title' => 'Failed To Create!']);
            }
        }
    }
}
