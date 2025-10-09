<?php

namespace App\Services\bonus;

use App\Models\admin\InternalTransfer;
use App\Models\BonusFor;
use App\Models\BonusPackage;
use App\Models\BonusUser;
use App\Models\TradingAccount;
use App\Services\MT4API;
use Carbon\Carbon;

class BonusCreditService
{
    // deposit bonus credit
    public static function deposit_bonus_credit($client_id, $account, $deposit_amount, $deposit_id)
    {
        try {
            if (BonusService::has_deoposit_bonus($client_id)) {
                $result = BonusService::deposit_bonus_details($client_id);
                if ($result->bonus_type === 'first_deposit') {
                    self::first_deposit_bonus($result->id, $deposit_amount, $client_id, $account, $deposit_id);
                } elseif ($result->bonus_type === 'specific_deposit') {
                    self::specific_deposit_bonus($result->id, $account, $client_id, $deposit_amount, $deposit_id);
                }
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    // credit speicific deposit bonus
    public static function specific_deposit_bonus($bonus_id, $account, $client_id, $deposit_amount, $deposit_id)
    {
        try {
            $mt4_api = new MT4API();
            $status = false;
            $fill_condition = false;
            $result = BonusPackage::find($bonus_id);
            $expire_after = $result->expire_after;
            $expire_type = $result->expire_type;
            // create fill condition
            if ($deposit_amount >= $result->min_deposit && $deposit_amount <= $result->max_deposit) {
                $fill_condition = true;
            } else {
                $fill_condition = false;
            }
            // check fill condition
            if ($fill_condition) {
                if ($result->credit_type === 'percent') {
                    $bonus_amount = self::get_bonus_amount($deposit_amount, $result->bonus_amount);
                } else {
                    $bonus_amount = $result->bonus_amount;
                }

                if (strtolower(get_platform()) === 'mt4') {
                    $data = array(
                        'command' => 'credit_funds',
                        'data' => array(
                            'account_id' => $account,
                            'amount' => (float) $bonus_amount,
                            "comment" => "Bonus for " . $result->bonus_type,
                            "expiration" => strtotime("+$expire_after $expire_type")
                        ),
                    );
                    $mt4_api->execute($data, 'live');
                    $status = true;
                }
                if ($status == true) {
                    BonusUser::create([
                        'user_id' => $client_id,
                        'bonus_package' => $result->id,
                        'fill_condition' => 1,
                        'internal_transfer_id' => $deposit_id,
                        'account_number' => $account,
                        'credit_expire' => date('Y-m-d h:i:s', strtotime("+$expire_after $expire_type")),
                        'amount' => $bonus_amount,
                    ]);
                }
            }
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
    // credit first deposit bonus
    public static function first_deposit_bonus($bonus_id, $deposit_amount, $client_id, $account, $deposit_id)
    {
        try {
            $mt4_api = new MT4API();
            $status = false;
            $fill_condition = false;
            $result = BonusPackage::find($bonus_id);
            $expire_after = $result->expire_after;
            $expire_type = $result->expire_type;
            // create fill condition
            if ($deposit_amount >= $result->min_deposit && $deposit_amount <= $result->max_deposit) {
                $fill_condition = true;
            } else {
                $fill_condition = false;
            }
            // check condition for first deposit
            $count_deposit = InternalTransfer::where('user_id', $client_id)->whereDate('created_at', '>=', Carbon::now())->count();
            if ($count_deposit > 0 && $count_deposit < 2) {
                $fill_condition = true;
            } else {
                $fill_condition = false;
            }
            // check fill condition
            if ($fill_condition) {
                if ($result->credit_type === 'percent') {
                    $bonus_amount = self::get_bonus_amount($deposit_amount, $result->bonus_amount);
                } else {
                    $bonus_amount = $result->bonus_amount;
                }
                if (strtolower(get_platform()) === 'mt4') {
                    $data = array(
                        'command' => 'credit_funds',
                        'data' => array(
                            'account_id' => $account,
                            'amount' => (float) $bonus_amount,
                            "comment" => "Bonus for " . $result->bonus_type,
                            "expiration" => strtotime("+$expire_after $expire_type")
                        ),
                    );
                    $mt4_api->execute($data, 'live');
                    $status = true;
                }
                if ($status == true) {
                    BonusUser::create([
                        'user_id' => $client_id,
                        'bonus_package' => $result->id,
                        'fill_condition' => 1,
                        'internal_transfer_id' => $deposit_id,
                        'account_number' => $account,
                        'credit_expire' => date('Y-m-d h:i:s', strtotime("+$expire_after $expire_type")),
                        'amount' => $bonus_amount,
                    ]);
                }
            }
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
    // new acccount bonus credit
    public static function account_bonus_credit($client_id, $account, $deposit_amount = null, $deposit_id = null)
    {
        try {
            if (BonusService::has_new_account_bonus($client_id)) {
                $result = BonusService::new_account_bonus_details($client_id);
                if ($deposit_amount == null) {
                    self::no_deposit_bonus($result->id, $client_id, $account);
                } else {
                    // check account created or not
                    $trading_account = TradingAccount::where('user_id', $client_id)->whereDate('created_at', '<=', $result->start_date);
                    $fill_condition = true;
                    $new_account_count = $trading_account->count();
                    // bonus count
                    $bonus_count = BonusUser::where('bonus_package', $result->id)->count();
                    if ($bonus_count >= $new_account_count) {
                        $fill_condition = false;
                    }
                    if ($trading_account->exists() && $fill_condition == true) {
                        if ($result->bonus_type === 'first_deposit') {
                            self::first_deposit_bonus($result->id, $deposit_amount, $client_id, $account, $deposit_id);
                        } elseif ($result->bonus_type === 'specific_deposit') {
                            self::specific_deposit_bonus($result->id, $account, $client_id, $deposit_amount, $deposit_id);
                        }
                    }
                }
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    // now deposit bonus credit
    public static function no_deposit_bonus($bonus_id, $client_id, $account)
    {
        try {
            $mt4_api = new MT4API();
            $status = false;
            $fill_condition = true;
            $result = BonusPackage::find($bonus_id);
            $expire_after = $result->expire_after;
            $expire_type = $result->expire_type;
            // check fill condition
            if ($fill_condition) {
                $bonus_amount = $result->bonus_amount;
                if (strtolower(get_platform()) === 'mt4') {
                    $data = array(
                        'command' => 'credit_funds',
                        'data' => array(
                            'account_id' => $account,
                            'amount' => (float) $bonus_amount,
                            "comment" => "Bonus for " . $result->bonus_on,
                            "expiration" => strtotime("+$expire_after $expire_type")
                        ),
                    );
                    $mt4_api->execute($data, 'live');
                    $status = true;
                }
                if ($status == true) {
                    BonusUser::create([
                        'user_id' => $client_id,
                        'bonus_package' => $result->id,
                        'fill_condition' => 1,
                        'account_number' => $account,
                        'amount' => $bonus_amount,
                        'credit_expire' => date('Y-m-d h:i:s', strtotime("+$expire_after $expire_type")),
                        
                    ]);
                }
            }
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
    // get bonus amount
    public static function get_bonus_amount($deposit_amount, $bonus_percent)
    {
        try {
            $bonus = ($deposit_amount * $bonus_percent) / 100;
            return $bonus;
        } catch (\Throwable $th) {
            throw $th;
            return 0;
        }
    }
}
