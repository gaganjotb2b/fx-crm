<?php

namespace App\Services;

use App\Models\UserDescription;

class GetPhotosService
{
    public function __call($name, $data)
    {
        if ($name == 'avatar') {
            return $this->get_avatar();
        }
    }
    public static function __callStatic($name, $data)
    {
        if ($name == 'avatar') {
            return (new self)->get_avatar();
        }
    }
    private function get_avatar($user_id = null)
    {
        $avatar = '';
        $id = ($user_id != null) ? $user_id : auth()->user()->id;
        $user_descriptions = UserDescription::where('user_id', $id)->first();
        if (isset($user_descriptions->gender)) {
            $avatar = ($user_descriptions->gender === 'Male') ? 'avater-men.png' : 'avater-lady.png'; //<----avatar url
        } else {
            $avatar = 'avater-men.png';
        }
        return $avatar;
    }


    public function timeis($ti, $type = '')
    {
        //use timeis(datetime,'moment')
        //use timeis(datetime)
        if ($type == 'moment') {
            $time = time() - strtotime($ti);
            $time = ($time < 1) ? 1 : $time;
            $tokens = array(
                31536000 => 'year',
                2592000 => 'month',
                604800 => 'week',
                86400 => 'day',
                3600 => 'hour',
                60 => 'minute',
                1 => 'second'
            );
            foreach ($tokens as $unit => $text) {
                if ($time < $unit) continue;
                $numberOfUnits = floor($time / $unit);
                return $numberOfUnits . ' ' . $text . (($numberOfUnits > 1) ? 's ago' : ' ago');
            }
        } else {
            // return date_format(date_create($ti), "d F Y H:i:s A");

            $redate = date_format(date_create($ti), "d F Y ");
            $reTime = date_format(date_create($ti), "H:i:s A");
            return json_encode([
                'date'  => $redate,
                'time' =>  $reTime
            ]);
        }
    }
}
