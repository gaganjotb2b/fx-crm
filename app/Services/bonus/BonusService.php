<?php

namespace App\Services\bonus;

use App\Models\BonusPackage;
use App\Models\User;
use App\Services\Trader\DepositService;
use App\Services\Trader\InternalTransferService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;

class BonusService
{
    // all client bonus
    public static function all_client_bonus($user_id = null)
    {
        try {
            $user_id = ($user_id != null) ? $user_id : auth()->user()->id;
            // get all clients bonus
            $all_client_bonus = BonusPackage::where('bonus_for', 'all')
                // ->whereDate('start_date', '<=', now())
                // ->whereDate('end_date', '>=', now())
                ->get();
            $total_bonus = 0;
            foreach ($all_client_bonus as $key => $value) {
                if ($value->credit_type === 'fixed') {
                    $total_bonus += $value->bonus_amount;
                    // bonus for free
                } elseif ($value->credit_type === 'percent' && $value->bonus_type != 'free') {
                    // bonus for deposit
                    if ($value->bonus_type === 'on_deposit') {
                        $total_deposit = DepositService::total_approved_deposit($user_id);
                        $total_bonus += (($value->bonus_amount * $total_deposit) / 100);
                    } elseif ($value->bonus_type === 'first_deposit') {
                        $deposit = DepositService::get_first_deposit($user_id);
                        $total_bonus += (($value->bonus_amount * $deposit) / 100);
                    } elseif ($value->credit_type === 'specific_deposit') {
                        $total_deposit = DepositService::get_range_deposit($user_id, $value->min_deposit, $value->max_deposit, [
                            'start_date' => $value->start_date,
                            'end_date' => $value->end_date,
                        ]);
                        $total_bonus += (($value->bonus_amount * $total_deposit) / 100);
                    }
                }
            }
            return $total_bonus;
        } catch (\Throwable $th) {
            //throw $th;
            return (0);
        }
    }
    // get new registration bonus
    public static function get_new_registration_bonus($user_id = null)
    {
        try {
            $user_id = ($user_id != null) ? $user_id : auth()->user()->id;
            $bonus = BonusPackage::where('bonus_for', 'new_registration')
                ->get();
            $total_bonus = 0;
            foreach ($bonus as $key => $value) {
                // check user registation date
                $user = User::where('id', $user_id)->select('created_at')->first();
                $register_date = ($user) ? $user->created_at : '';
                if ($value->credit_type === 'fixed' && $value->bonus_type === 'on_deposit' && $register_date >= $value->created_at) {
                    $total_deposit = DepositService::total_approved_deposit($user_id, [
                        'offer_date' => $value->created_at,
                    ]);
                    $total_bonus += $value->bonus_amunt;
                } elseif ($value->credit_type === 'fixed' && $value->bonus_type === 'first_deposit'  && $register_date >= $value->created_at) {
                    $deposit = DepositService::get_first_deposit($user_id, [
                        'offer_date' => $value->created_at,
                    ]);
                    if ($deposit != 0) {
                        $total_bonus += $value->bonus_amunt;
                    }
                } elseif ($value->credit_type === 'fixed' && $value->bonus_type === 'specific_deposit'  && $register_date >= $value->created_at) {
                    $total_deposit = DepositService::get_range_deposit($user_id, $value->min_deposit, $value->max_deposit, [
                        'start_date' => $value->start_date,
                        'end_date' => $value->end_date,
                        'offer_date' => $value->created_at,
                    ]);
                    if ($total_deposit != 0) {
                        $total_bonus += $value->bonus_amount;
                    }
                }
                // start credit type percents
                elseif ($value->credit_type === 'percent' && $value->bonus_type === 'on_deposit'  && $register_date >= $value->created_at) {
                    $total_deposit = DepositService::total_approved_deposit($user_id, [
                        'offer_date' => $value->created_at,
                    ]);
                    $total_bonus += (($value->bonus_amount * $total_deposit) / 100);
                } elseif ($value->credit_type === 'percent' && $value->bonus_type === 'first_deposit'  && $register_date >= $value->created_at) {
                    $deposit = DepositService::get_first_deposit($user_id, [
                        'offer_date' => $value->created_at,
                    ]);
                    $total_bonus += (($value->bonus_amount * $deposit) / 100);
                } elseif ($value->credit_type === 'percent' && $value->credit_type === 'specific_deposit'  && $register_date >= $value->created_at) {
                    $total_deposit = DepositService::get_range_deposit($user_id, $value->min_deposit, $value->max_deposit, [
                        'start_date' => $value->start_date,
                        'end_date' => $value->end_date,
                        'offer_date' => $value->created_at,
                    ]);
                    $total_bonus += (($value->bonus_amount * $total_deposit) / 100);
                } elseif ($value->credit_type == 'fixed' && $value->credit_type === 'free'  && $register_date >= $value->created_at) {
                    $total_bonus += $value->bonus_amount;
                }
            }
            return $total_bonus;
        } catch (\Throwable $th) {
            //throw $th;
            return (0);
        }
    }
    // new trading account bonus
    public static function new_account_bonus($user_id = null)
    {
        try {
            $user_id = ($user_id != null) ? $user_id : auth()->user()->id;
            $bonus = BonusPackage::where('bonus_for', 'new_account')
                ->get();
            $total_bonus = 0;
            foreach ($bonus as $key => $value) {
                // check user registation date
                $user = User::where('id', $user_id)->select('created_at')->first();
                $register_date = ($user) ? $user->created_at : '';
                if ($value->credit_type === 'fixed' && $value->bonus_type === 'on_deposit' && $register_date >= $value->created_at) {
                    $total_deposit = InternalTransferService::wta_deposit_approved($user_id, [
                        'offer_date' => $value->created_at,
                    ]);
                    $total_bonus += $value->bonus_amunt;
                } elseif ($value->credit_type === 'fixed' && $value->bonus_type === 'first_deposit'  && $register_date >= $value->created_at) {
                    $deposit = InternalTransferService::wta_first_deposit($user_id, [
                        'offer_date' => $value->created_at,
                    ]);
                    if ($deposit != 0) {
                        $total_bonus += $value->bonus_amunt;
                    }
                } elseif ($value->credit_type === 'fixed' && $value->bonus_type === 'specific_deposit'  && $register_date >= $value->created_at) {
                    $total_deposit = InternalTransferService::wta_range_deposit($user_id, $value->min_deposit, $value->max_deposit, [
                        'start_date' => $value->start_date,
                        'end_date' => $value->end_date,
                        'offer_date' => $value->created_at,
                    ]);
                    if ($total_deposit != 0) {
                        $total_bonus += $value->bonus_amount;
                    }
                }
                // start credit type percents
                elseif ($value->credit_type === 'percent' && $value->bonus_type === 'on_deposit'  && $register_date >= $value->created_at) {
                    $total_deposit = InternalTransferService::wta_deposit_approved($user_id, [
                        'offer_date' => $value->created_at,
                    ]);
                    $total_bonus += (($value->bonus_amount * $total_deposit) / 100);
                } elseif ($value->credit_type === 'percent' && $value->bonus_type === 'first_deposit'  && $register_date >= $value->created_at) {
                    $deposit = InternalTransferService::wta_first_deposit($user_id, [
                        'offer_date' => $value->created_at,
                    ]);
                    $total_bonus += (($value->bonus_amount * $deposit) / 100);
                } elseif ($value->credit_type === 'percent' && $value->credit_type === 'specific_deposit'  && $register_date >= $value->created_at) {
                    $total_deposit = InternalTransferService::wta_range_deposit($user_id, $value->min_deposit, $value->max_deposit, [
                        'start_date' => $value->start_date,
                        'end_date' => $value->end_date,
                        'offer_date' => $value->created_at,
                    ]);
                    $total_bonus += (($value->bonus_amount * $total_deposit) / 100);
                } elseif ($value->credit_type == 'fixed' && $value->credit_type === 'free'  && $register_date >= $value->created_at) {
                    $total_bonus += $value->bonus_amount;
                }
            }
            return $total_bonus;
        } catch (\Throwable $th) {
            //throw $th;
            return (0);
        }
    }
    // get sum of bonus
    public static function total_bonus($user_id = null)
    {
        try {
            $all_client_bonus = self::all_client_bonus($user_id);
            $new_register_bonus = self::get_new_registration_bonus($user_id);
            $new_account_bonus = self::new_account_bonus($user_id);
            return round(($all_client_bonus + $new_account_bonus + $new_register_bonus), 3);
        } catch (\Throwable $th) {
            //throw $th;
            return (0);
        }
    }
    // count active bonus / for clients
    public static function count_active_bonus($user_id = null)
    {
        try {
            $total_bonus = (self::count_all_cient_bonus($user_id) +
                self::count_specific_bonus($user_id) +
                self::count_new_register_bonus($user_id) +
                self::count_new_account($user_id)
            );
            return $total_bonus;
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    // count all client bonus
    public static function count_all_cient_bonus()
    {
        try {
            $count = BonusPackage::where('bonus_for', 'all')
                ->where('active_status', 1)
                ->whereDate('end_date', '>=', Carbon::now())->count();
            return $count;
        } catch (\Throwable $th) {
            throw $th;
            return (0);
        }
    }
    // count specific client bonus
    public static function count_specific_bonus($user_id = null)
    {
        try {
            $user_id = ($user_id != null) ? $user_id : auth()->user()->id;
            $count = BonusPackage::where('active_status', 1)
                ->whereDate('end_date', '>=', Carbon::now())
                ->where('bonus_for.user_id', $user_id)
                ->join('bonus_for', 'bonus_packages.id', '=', 'bonus_for.bonus_package')
                ->count();
            return $count;
        } catch (\Throwable $th) {
            throw $th;
            return (0);
        }
    }
    // count bonus for new registration
    public static function count_new_register_bonus($user_id = null)
    {
        try {
            $user = User::where('id', $user_id)->select('created_at')->first();
            $count = BonusPackage::whereDate('created_at', '<=', $user->created_at)
                ->whereDate('end_date', '>=', Carbon::now())->count();
            return $count;
        } catch (\Throwable $th) {
            // throw $th;
            return 0;
        }
    }
    // count new account bonus
    public static function count_new_account($user_id = null)
    {
        try {
            $count = BonusPackage::where('bonus_on', 'new_account')
                // ->where('bonus_for',)
                ->whereDate('end_date', '>=', Carbon::now())->count();
            return $count;
        } catch (\Throwable $th) {
            // throw $th;
            return 0;
        }
    }
    // get all active bonus
    public static function get_active_bonus($user_id = null)
    {
        try {
            $bonus = BonusPackage::where('active_status', 1)->where(function ($query) use ($user_id) {
                $query->whereIn('bonus_for', ['all', 'new_account'])
                    ->orWhere('user_id', $user_id);
            })
                ->leftJoin('bonus_for', 'bonus_packages.id', '=', 'bonus_for.bonus_package')
                ->select('bonus_packages.*')
                ->get();
            return $bonus;
        } catch (\Throwable $th) {
            //throw $th;
            return ([]);
        }
    }
    // has deposit bonus
    public static function has_deoposit_bonus($user_id)
    {
        try {
            // count for all
            $count_all_client_bonus = BonusPackage::where('bonus_on', 'deposit')
                ->where('bonus_for', 'all')
                ->where('is_global', 1)
                ->whereDate('end_date', '>=', Carbon::now())->count();
            // count for specific client
            $specific_client = BonusPackage::where('bonus_on', 'deposit')
                ->where('bonus_for', 'specific_client')
                ->whereDate('end_date', '>=', Carbon::now())
                ->where('user_id', $user_id)
                ->join('bonus_for', 'bonus_packages.id', '=', 'bonus_for.bonus_package')->count();
            return ($count_all_client_bonus + $specific_client);
        } catch (\Throwable $th) {
            //throw $th;
            return 0;
        }
    }
    // has new trading account bonus
    public static function has_new_account_bonus($user_id)
    {
        try {
            // bonus count for all client
            $all_client_bonus = BonusPackage::where('bonus_on', 'new_account')
                ->where('bonus_for', 'all')
                ->whereDate('end_date', '>=', Carbon::now())
                ->where('is_global', 1)->count();
            // count for specific client
            $specific_client = BonusPackage::where('bonus_on', 'new_account')
                ->where('bonus_for', 'specific_client')
                ->whereDate('end_date', '>=', Carbon::now())
                ->where('user_id', $user_id)
                ->join('bonus_for', 'bonus_packages.id', '=', 'bonus_for.bonus_package')->count();
            return ($all_client_bonus + $specific_client);
        } catch (\Throwable $th) {
            //throw $th;
            return 0;
        }
    }
    // active deposit bonus
    public static function active_deposit_bonus($user_id)
    {
        try {
            if (self::has_deoposit_bonus($user_id)) {
                // count for all
                $all_client_bonus = BonusPackage::where('bonus_on', 'deposit')
                    ->where('bonus_for', 'all')
                    ->where('is_global', 1)
                    ->whereDate('end_date', '>=', Carbon::now())->first();
                if ($all_client_bonus) {
                    return $all_client_bonus->pkg_name;
                }
                // count for specific client
                $specific_client = BonusPackage::where('bonus_on', 'deposit')
                    ->where('bonus_for', 'specific_client')
                    ->whereDate('end_date', '>=', Carbon::now())
                    ->where('user_id', $user_id)
                    ->join('bonus_for', 'bonus_packages.id', '=', 'bonus_for.bonus_package')->first();
                return $specific_client->pkg_name;
            }
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
    public static function deposit_bonus_details($user_id)
    {
        try {
            if (self::has_deoposit_bonus($user_id)) {
                // count for all
                $all_client_bonus = BonusPackage::where('bonus_on', 'deposit')
                    ->where('bonus_for', 'all')
                    ->where('is_global', 1)
                    ->whereDate('end_date', '>=', Carbon::now())->first();
                if ($all_client_bonus) {
                    return $all_client_bonus;
                }
                // count for specific client
                $specific_client = BonusPackage::where('bonus_on', 'deposit')
                    ->where('bonus_for', 'specific_client')
                    ->whereDate('end_date', '>=', Carbon::now())
                    ->where('user_id', $user_id)
                    ->join('bonus_for', 'bonus_packages.id', '=', 'bonus_for.bonus_package')
                    ->select('bonus_packages.*')
                    ->first();
                return $specific_client;
            }
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
    // active new account bonus
    public static function active_new_account_bonus($user_id)
    {
        try {
            if (self::has_new_account_bonus($user_id)) {
                // bonus count for all client
                $all_client_bonus = BonusPackage::where('bonus_on', 'new_account')
                    ->where('bonus_for', 'all')
                    ->whereDate('end_date', '>=', Carbon::now())
                    ->where('is_global', 1)->first();
                if ($all_client_bonus) {
                    return $all_client_bonus->pkg_name;
                }
                // count for specific client
                $specific_client = BonusPackage::where('bonus_on', 'new_account')
                    ->where('bonus_for', 'specific_client')
                    ->whereDate('end_date', '>=', Carbon::now())
                    ->where('user_id', $user_id)
                    ->join('bonus_for', 'bonus_packages.id', '=', 'bonus_for.bonus_package')->first();
                return $specific_client->pkg_name;
            }
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
    // new account bonus details
    public static function new_account_bonus_details($user_id)
    {
        try {
            if (self::has_new_account_bonus($user_id)) {
                // bonus count for all client
                $all_client_bonus = BonusPackage::where('bonus_on', 'new_account')
                    ->where('bonus_for', 'all')
                    ->whereDate('end_date', '>=', Carbon::now())
                    ->where('is_global', 1)->first();
                if ($all_client_bonus) {
                    return $all_client_bonus;
                }
                // count for specific client
                $specific_client = BonusPackage::where('bonus_on', 'new_account')
                    ->where('bonus_for', 'specific_client')
                    ->whereDate('end_date', '>=', Carbon::now())
                    ->where('user_id', $user_id)
                    ->join('bonus_for', 'bonus_packages.id', '=', 'bonus_for.bonus_package')
                    ->select('bonus_packages.*')
                    ->first();
                return $specific_client;
            }
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
}
