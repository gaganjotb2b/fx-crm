<?php

namespace App\Http\Controllers\admins;

use App\Http\Controllers\Controller;
use App\Models\CustomCommission;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use App\Models\IbSetup;
use App\Services\AllFunctionService;
use App\Services\commission\CommissionStructureService;
use App\Services\IbService;

class IBsetupController extends Controller
{
    public function __construct()
    {
        $this->middleware(["role:ib setup"]);
        $this->middleware(["role:ib management"]);

        // system module control
        $this->middleware(AllFunctionService::access('ib_management', 'admin'));
        $this->middleware(AllFunctionService::access('ib_setup', 'admin'));
    }
    //IB setup view
    // -----------------------------------------------------------------------------------
    public function index(Request $request)
    {
        $ib_setup = IbSetup::select()->first();
        // seelct option select
        $withdraw_period = [
            'monthly' => '',
            'by-weekly' => '',
            'weekly' => '',
            'daily' => '',
        ];
        switch (($ib_setup) ? $ib_setup->withdraw_period : 'daily') {
            case 'monthly':
                $withdraw_period['monthly'] = 'selected';
                break;
            case 'by-weekly':
                $withdraw_period['by-weekly'] = 'selected';
                break;
            case 'weekly':
                $withdraw_period['weekly'] = 'selected';
                break;

            default:
                $withdraw_period['daily'] = 'daily';
                break;
        }
        $data = [
            'ib_level' => ($ib_setup) ? $ib_setup->ib_level : 3,
            'sub_ib_req' => ($ib_setup) ? $ib_setup->require_sub_ib : 0,
            'min_withdraw' => ($ib_setup) ? $ib_setup->min_withdraw : 0,
            'max_withdraw' => ($ib_setup) ? $ib_setup->max_withdraw : 0,
            'max_withdraw' => ($ib_setup) ? $ib_setup->max_withdraw : 0,
            'withdraw_period' => $withdraw_period,
            'withdraw_kyc' => (($ib_setup) ? $ib_setup->withdraw_kyc : 0) ? 'checked' : '',
            'refer_kyc' => (($ib_setup) ? $ib_setup->refer_kyc : 0) ? 'checked' : '',
            'ib_commission_kyc' => (($ib_setup) ? $ib_setup->ib_commission_kyc : 0) ? 'checked' : '',
        ];
        return view('admins.ib-management.ib-setup', $data);
    }

    // save configuration 
    // --------------------------------------------------------------------------
    public function store(Request $request)
    {
        $validation_rules = [
            'ib_level' => 'required|numeric',
            'require_sub_ib' => 'required|numeric',
            'min_withdraw' => 'required|numeric',
            'max_withdraw' => 'required|numeric',
            'withdraw_period' => 'required|max:191',
        ];
        $validator = Validator::make($request->all(), $validation_rules);
        if ($validator->fails()) {
            return Response::json([
                'status' => false,
                'errors' => $validator->errors(),
                'message' => 'Please check atleast one user'
            ]);
        }
        // check the withdraw period days
        // if weekly 
        if (strtolower($request->withdraw_period) === 'weekly' && $request->period_days == "") {
            return Response::json([
                'status' => false,
                'errors' => ['period_days' => 'The weekly days field is required!'],
                'message' => 'The weekly days field is required!'
            ]);
        }
        // check the withdraw period date
        // if monthly
        if (strtolower($request->withdraw_period) === 'monthly' && $request->period_date == "") {
            return Response::json([
                'status' => false,
                'errors' => ['period_date' => 'The monthly date field is required!'],
                'message' => 'The monthly date field is required!'
            ]);
        }
        // check the by weekly withdraw period date
        // if monthly
        if (strtolower($request->withdraw_period) === 'by-weekly' && $request->by_weekly_period_date == "") {
            return Response::json([
                'status' => false,
                'errors' => ['period_date' => 'The by weekly period date field is required!'],
                'message' => 'The by weekly period date field is required!'
            ]);
        }
        // check if exist
        $check = IbSetup::select()->first();
        if ($check) {
            // update for already exist
            $check->ib_level = $request->ib_level;
            $check->require_sub_ib = $request->require_sub_ib;
            $check->min_withdraw = $request->min_withdraw;
            $check->max_withdraw = $request->max_withdraw;
            $check->withdraw_period = $request->withdraw_period;
            $check->period_days = $request->period_days;
            $check->period_date = $request->period_date;
            $check->byweekly_period_date = $request->by_weekly_period_date;
            $check->withdraw_kyc = (isset($request->withdraw_kyc)) ? $request->withdraw_kyc : 0;
            $check->refer_kyc = (isset($request->refer_kyc)) ? $request->refer_kyc : 0;
            $check->ib_commission_kyc = (isset($request->ib_commission_kyc)) ? $request->ib_commission_kyc : 0;
            $create = $check->save();
            // *****************************************************************
        } else {
            $create = IbSetup::create([
                'ib_level' => $request->ib_level,
                'require_sub_ib' => $request->require_sub_ib,
                'min_withdraw' => $request->min_withdraw,
                'max_withdraw' => $request->max_withdraw,
                'withdraw_period' => $request->withdraw_period,
                'period_days' => $request->period_days,
                'period_date' => $request->period_date,
                'byweekly_period_date' => $request->by_weekly_period_date,
                'withdraw_kyc' => (isset($request->withdraw_kyc)) ? $request->withdraw_kyc : 0,
                'refer_kyc' => (isset($request->refer_kyc)) ? $request->refer_kyc : 0,
                'ib_commission_kyc' => (isset($request->ib_commission_kyc)) ? $request->ib_commission_kyc : 0
            ]);
        }
        if ($create) {
            // reset custom commission
            // $commission_level = IbService::system_ibCommission_level();
            // $previous_custom_com = CustomCommission::first();
            // if (isset($previous_custom_com->custom_commission) && $commission_level < count(json_decode($previous_custom_com->custom_commission))) {
            //     CommissionStructureService::reset_custom_commission();
            // }
            // CommissionStructureService::reset_custom_commission();
            // insert activity-----------------
            activity("IB Setup")
                ->causedBy(auth()->user()->id)
                ->withProperties($request->all())
                ->event("IB Setup")
                ->log("The IP address " . request()->ip() . " has been complete IB setup");
            // end activity log-----------------
            return Response::json([
                'status' => true,
                'message' => 'Setup Saved successfully'
            ]);
        } else {
            return Response::json([
                'status' => false,
                'message' => 'Somthing went wrong! Please try again later'
            ]);
        }
    }
}
