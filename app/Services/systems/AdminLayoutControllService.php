<?php

namespace App\Services\systems;

class AdminLayoutControllService
{
    public static function admin_layout()
    {
        switch (strtolower(auth()->user()->type)) {
            case 'manager':
                return ('layouts.manager-layout');
                break;

            default:
                return ('layouts.admin-layout');
                break;
        }
    }
}
