<?php


namespace App\Services\systems;

class LanguageService
{
    public static function language()
    {
        if (session()->get('locale') == 'fr') {
            return (object) [
                'lang' => __('language.french'),
                'flag' => 'fr',
            ];
        } elseif (session()->get('locale') == 'de') {
            return (object)[
                'lang' => __('language.german'),
                'flag' => 'de',
            ];
        } elseif (session()->get('locale') == 'pt') {
            return (object)[
                'lang' => __('language.portuguese'),
                'flag' => 'pt',
            ];
        } elseif (session()->get('locale') == 'zh') {
            return (object)[
                'lang' => __('language.chinese'),
                'flag' => 'cn',
            ];
        } else {
            return (object)[
                'lang' => __('language.english'),
                'flag' => 'us',
            ];
        }
    }
}
