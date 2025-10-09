<?php

namespace App\Services\manager;

use App\Models\Manager;
use App\Models\ManagerUser;

class ManagerService
{
    public static function manager_refer_link($manager_id, $user_type)
    {
        $manager_id  = ($manager_id != null) ? $manager_id : auth()->user()->id;
        $refer_code = base64_encode('{"rKey" : "' . $manager_id . '"}');
        if ($user_type === 'ib') {
            $link = route('ib.registration') . "?manager=" . $refer_code;
        }
        if ($user_type === 'trader') {
            $link = route('trader.registration') . "?manager=" . $refer_code;
        }
        return $link;
    }
    // get total manager under desk manager
    public static function total_manager($desk_manager_id)
    {
        try {
            $total = ManagerUser::where('manager_id', $desk_manager_id)->where('users.type', 5)
                ->join('users', 'manager_users.user_id', '=', 'users.id')
                ->count();
            return $total;
        } catch (\Throwable $th) {
            //throw $th;
            return 0;
        }
    }
    // get manager type 
    // manager is a desk manger or account manager
    public  static function manager_type($manager_id)
    {
        try {
            $managers = Manager::where('user_id', $manager_id)
                ->join('manager_groups', 'managers.group_id', '=', 'manager_groups.id')->select('group_type')->first();
            return $managers->group_type;
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
    // get account manager only
    public  static function acc_under_desk($desk_manager_id)
    {
        try {
            $managers = ManagerUser::where('manager_id', $desk_manager_id)
                ->join('users', 'manager_users.user_id', '=', 'users.id')
                ->where('users.type', 5)->select('user_id')->get()->pluck('user_id');
            return $managers;
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
    // find desk manger from account manager
    public  static function find_desk_manager($manager_id)
    {
        try {
            // account manager id as user_id
            $manager = ManagerUser::where('user_id', $manager_id)
                ->join('users', 'manager_users.manager_id', '=', 'users.id')
                ->where('users.type', 5)->select('manager_id')->get()->pluck('manager_id');
            // return $manager->manager_id;
            return $manager;
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
}
