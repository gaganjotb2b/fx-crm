<?php

namespace App\Services;

class CustomValidationService
{
    public static function __callStatic($name, $data)
    {
        if ($name === 'html_encode') {
            return (new self)->custom_html_encode($data[0]);
        }
    }
    // check string is html element
    private function is_html($string)
    {
        if ($string != strip_tags($string)) {
            // is HTML
            return true;
        } else {
            // not HTML
            return false;
        }
    }
    private function custom_html_encode($string)
    {
        if ($this->is_html($string)) {
            return (htmlspecialchars($string));
        }
        return $string;
    }
}
