<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class VertexFnService
{
    public static function vertex_username()
    {
        // $prev_id = DB::table('vertex_username_settings')->where('id', 1)->first();
        // $next_id = intval($prev_id->number) + 1;
        // $str = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        // $shuffled = substr(str_shuffle($str), 0, 1);
        // $user_name = $shuffled . $prev_id->number;
        // DB::table('vertex_username_settings')->where('id', 1)->update(['number' => $next_id]);
        // return $user_name;
        return(11225533);
    }

    public static function vertex_password()
    {
        return substr(str_shuffle("0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 5);
    }
}
