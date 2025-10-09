<?php

namespace App\Services;

use App\Models\IbSetup;
use App\Models\User;
use App\Models\Withdraw;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class IBManagementService
{

    /*
     * if true -> kyc is not required or kyc is verified -> withdraw possible
     * if false -> kyc is required but not verified -> withdraw is not possible
     */
    public static function withdrawStatus(): bool
    {
        return (bool) IbSetup::first()->withdraw_kyc ? self::isKycVerified(Auth::user()) : true;
    }

    /*
     * if true -> kyc is not required or kyc is verified -> you can show R.Link
     * if false -> kyc is required but not verified -> You can not show R.Link
     */
    public static function referralLinkStatus(): bool
    {
        return (bool) IbSetup::first()->refer_kyc ? self::isKycVerified(Auth::user()) : true;
    }

    /*
     * if true -> kyc is not required or kyc is verified
     * if false -> kyc is required but not verified
     */
    public static function ibCommissionStatus(): bool
    {
        return (bool) IbSetup::first()->ib_commission_kyc ? self::isKycVerified(Auth::user()) : true;
    }

    /**
     * Return true if KYC is verified
     * Return false if KYC is not verified
     *
     * @param User $user
     * @return boolean
     */
    public static function isKycVerified(User $user): bool
    {
        return $user->kyc_status === 1 ? true : false;
    }

    /**
     * Checking withdrawal Limit as set in ib_setups table
     *
     * @return array
     */
    // update code
    public static function checkWithdrawLimit()
    {
        $ib_setup = IbSetup::select()->first();
        if ($ib_setup) {
            $response = [
                'status' => true,
                'message' => ''
            ];
            switch (strtolower($ib_setup->withdraw_period)) {
                case 'weekly':
                    $date = date('Y-m-d h:i:s', strtotime($ib_setup->period_days));
                    $timeDiffInHour = Carbon::parse($date)->diffInHours(Carbon::now());
                    if (date('Y-m-d', strtotime($ib_setup->period_days)) !== date('Y-m-d')) {
                        $future = strtotime($date); //Future date.
                        $timefromdb = strtotime(date('Y-m-d h:i:s')); //source time
                        $timeleft = $future - $timefromdb;
                        $daysleft = round((($timeleft / 24) / 60) / 60);
                        $response['status'] = false;
                        $response['message'] = 'You can not withdraw before ' . $daysleft . ' days';
                    }
                    break;
                case 'by-weekly':
                    if (date('d', strtotime(now())) > $ib_setup->byweekly_period_date) {
                        $day = date('d') - ($ib_setup->byweekly_period_date);
                        $date = date('Y-m-d h:i:s', strtotime($ib_setup->byweekly_period_date . "-" . date('m') . "-" . date('Y')));
                        $date = Carbon::now()->addDays((date('t') / 2) - $day)->format('Y-m-d');
                    } else {
                        $date = date('Y-m-d h:i:s', strtotime($ib_setup->byweekly_period_date . "-" . date('m') . "-" . date('Y')));
                    }
                    $timeDiffInHour = Carbon::parse($date)->diffInHours(Carbon::now());
                    if (date('Y-m-d', strtotime($date)) !== date('Y-m-d')) {
                        $future = strtotime($date); //Future date.
                        $timefromdb = strtotime(date('Y-m-d h:i:s')); //source time
                        $timeleft = $future - $timefromdb;
                        $daysleft = round((($timeleft / 24) / 60) / 60);

                        $response['status'] = false;
                        $response['message'] = 'You can not withdraw before ' . $daysleft . ' days';
                    }
                    break;
                case 'monthly':
                    if (date('d', strtotime(now())) > $ib_setup->period_date) {
                        $day = date('d') - ($ib_setup->period_date);
                        $date = date('Y-m-d h:i:s', strtotime($ib_setup->period_date . "-" . date('m') . "-" . date('Y')));
                        $date = Carbon::now()->addDays(date('t') - $day)->format('Y-m-d');
                    } else {
                        $date = date('Y-m-d h:i:s', strtotime($ib_setup->period_date . "-" . date('m') . "-" . date('Y')));
                    }
                    $timeDiffInHour = Carbon::parse($date)->diffInHours(Carbon::now());
                    if (date('Y-m-d', strtotime($date)) !== date('Y-m-d')) {
                        $future = strtotime($date); //Future date.
                        $timefromdb = strtotime(date('Y-m-d h:i:s')); //source time
                        $timeleft = $future - $timefromdb;
                        $daysleft = round((($timeleft / 24) / 60) / 60);

                        $response['status'] = false;
                        $response['message'] = 'You can not withdraw before ' . $daysleft . ' days';
                    }
                    break;
                default:
                    return ([
                        'status' => true,
                        'message' => ''
                    ]);
                    break;
            }
            return $response;
        }
        return ([
            'status' => true,
            'message' => ''
        ]);
    }
    // ib setup selected day/date/
    public static function get_period($type)
    {
        $ib_setup = IbSetup::select()->first();
        switch ($type) {
            case 'weekly':
                return ($ib_setup) ? $ib_setup->period_days : '';
                break;
            case 'by-weekly':
                return ($ib_setup) ? $ib_setup->byweekly_period_date : '';
                break;
            case 'monthly':
                return ($ib_setup) ? $ib_setup->period_date : '';
                break;
            default:
                return ('');
                break;
        }
    }
    // update code end
}
