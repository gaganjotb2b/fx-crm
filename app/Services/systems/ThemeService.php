<?php

namespace App\Services\systems;

use App\Models\ThemeSetup;

class ThemeService
{
    public static function selected_theme($portal = 'admin', $name = 'vieuxy')
    {
        switch ($portal) {
            case 'client':
                $client_theme = ThemeSetup::where('use_for', 'client')->first();
                if ($client_theme && $client_theme->theme_name === $name) {
                    return "checked";
                }
                return '';
                break;

            default:
                $client_theme = ThemeSetup::where('use_for', 'admin')->first();
                if ($client_theme && $client_theme->theme_name === $name) {
                    return "checked";
                }
                return '';
                break;
        }
    }
}
