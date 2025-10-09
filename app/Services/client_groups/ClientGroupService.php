<?php

namespace App\Services\client_groups;

use App\Models\ClientGroup;

class ClientGroupService
{
    // function get client group
    public static function get_client_group()
    {
        $client_groups = ClientGroup::where('visibility', 'visible')->where('active_status', 1)->get();
        return ($client_groups);
    }
    // function for get group name
    public static function group_name($group_id)
    {
        try {
            $result = ClientGroup::where('id', $group_id)->select('group_id')->first();
            return $result;
        } catch (\Throwable $th) {
            //throw $th;
            return false;
        }
    }
}
