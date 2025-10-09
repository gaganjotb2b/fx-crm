<?php

namespace App\Services\systems;

use Exception;

class AdminLogService
{
    public  static function admin_log()
    {
        try {
            $ipAddress = request()->ip();
            $userAgent = request()->header('User-Agent');
            $operatingSystems = [
                'Windows\sNT\s(\d+\.\d+)' => 'Windows',
                'Macintosh|Mac OS X\s(\d+\.\d+)' => 'macOS',
                'iOS\s(\d+\.\d+)' => 'iOS',
                'Android\s(\d+\.\d+)' => 'Android',
                'Windows\sPhone\sOS\s(\d+\.\d+)' => 'Windows Phone',
                'BlackBerry\s(\d+\.\d+)' => 'BlackBerry',
                'Linux\s(.+)' => 'Linux',
                'FreeBSD\s(\d+\.\d+)' => 'FreeBSD',
                'OpenBSD\s(\d+\.\d+)' => 'OpenBSD',
                'NetBSD\s(\d+\.\d+)' => 'NetBSD',
            ];

            $operatingSystem = 'Unknown';
            foreach ($operatingSystems as $pattern => $name) {
                if (preg_match('/' . $pattern . '/', $userAgent, $matches)) {
                    $operatingSystem = $name . ' ' . $matches[1];
                    break;
                }
            }
            $jsonData = [
                'email' => auth()->user()->email,
                'ip' => $ipAddress,
                'wname' => $operatingSystem
            ];
            return json_encode($jsonData);
        } catch (\Throwable $th) {
            //throw $th;
            return json_encode(['agent not found']);
        }
    }
}
