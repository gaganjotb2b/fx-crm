<?php

namespace App\Services;

use App\Models\IB;
use App\Models\IbSetup;
use App\Models\User;

class IbService
{
    private $parents = [];
    // overrite private functions
    public static function __callStatic($name, $data)
    {
        if ($name === "has_parent") {
            return (new self)->has_parent_node($data[0], (array_key_exists(1, $data)) ? $data[1] : null);
        }
        // get instant parent
        if ($name === "instant_parent") {
            return (new self)->get_instant_parent($data[0]);
        }
        // get system ib commission level
        if ($name === "system_ibCommission_level") {
            return (new self)->get_system_ibCommission_level();
        }
    }
    // all of private function is here
    private function has_parent_node($user_id, $parent_id = false)
    {
        $ibs = IB::where('reference_id', $user_id);
        switch ($parent_id) {
            case true:
                // if need parent id
                /****************/ // have a bug***************
                // dont use without fix this bock
                if ($ibs->exists()) {
                    $ibs = $ibs->first();
                    $parents = $this->get_instant_parent($user_id);
                    return $parents->ib_id;
                }
                return false;
                break;

            default:
                if ($ibs->exists()) {
                    return true;
                }
                return false;
                break;
        }
    }
    // get instant parent
    private function get_instant_parent($referenc_id)
    {
        $ibs = IB::where('reference_id', $referenc_id)->first();
        if ($ibs) {
            return ($ibs->ib_id);
        }
        return false;
    }
    public static function all_parents($user_id)
    {
        try {
            $parent_id = [];
            $result = IB::where('reference_id', $user_id)->select('ib_id')->first();
            if ($result) {
                array_push($parent_id, $result->ib_id);
            }
            if ((new self)->has_parent_node($result->ib_id)) {
                $parent_id = array_merge($parent_id, self::all_parents($result->ib_id));
            }
            return $parent_id;
        } catch (\Throwable $th) {
            // throw $th;
            return [];
        }
    }
    public static function all_parents_with_master($user_id)
    {
        try {
            $parent_id = [];
            $result = IB::where('reference_id', $user_id)->with(['ibDetails'])->select('ib_id')->first();
            $has_parent = (new self)->has_parent_node($result->ib_id);
            $individual_commission = $result->ibDetails->individual_commission ?? 'disabled';
            $individual_total = $result->ibDetails->total_commission ?? 0;
            $is_master = true;
            if ($has_parent) {
                $is_master = false;
            }
            if ($result) {
                array_push($parent_id, [
                    'id' => $result->ib_id,
                    'is_master' => $is_master,
                    'individual_commission' => $individual_commission,
                    'individual_total' => $individual_total,
                ]);
            }
            if ($has_parent) {
                $parent_id = array_merge($parent_id, self::all_parents_with_master($result->ib_id));
            }
            return $parent_id;
        } catch (\Throwable $th) {
            // throw $th;
            return [];
        }
    }
    // get ib commission level from system
    private static function get_system_ibCommission_level()
    {
        $ib_setup = IbSetup::latest()->first();
        if ($ib_setup) {
            return (($ib_setup->ib_level) ? $ib_setup->ib_level : 3);
        } else {
            return (3);
        }
    }
    // get traders id by ib email
    // instant child
    public static function instant_traders_child($ib_email)
    {
        $filter_client = [];
        $users = User::where('email', $ib_email)->first();

        $ref_id = IB::where('ib_id', $users->id)
            ->where('users.type', 0)->select('reference_id')
            ->join('users', 'ib.reference_id', '=', 'users.id')->get();
        foreach ($ref_id as $key => $value) {
            array_push($filter_client, $value->reference_id);
        }
        return $filter_client;
    }
    // function get instant ib email
    // 
    public static function instant_ib_email($reference_id)
    {
        try {
            $ib_email = AllFunctionService::user_email((new self)->get_instant_parent($reference_id));
            if ($ib_email !== "") {
                return $ib_email;
            }
            return 'N/A';
        } catch (\Throwable $th) {
            //throw $th;
            return 'N/A';
        }
    }
    // get all traders under an IB
    //@return id
    //@return type array
    public static function get_all_trader($ib_id)
    {
        try {
            $result = IB::whereHas('user', function ($query) {
                $query->where('type', 0);
            })
                ->where('ib_id', $ib_id)
                ->pluck('reference_id');

            // The $result variable will contain the reference_ids
            return $result;
        } catch (\Throwable $th) {
            //throw $th;
            return [];
        }
    }
}
