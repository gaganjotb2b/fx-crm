<?php

namespace App\Services\api;

class Mt5ManagerApi
{
    public static function run_manager_api($command, $data, $server, $login, $password, $url)
    {
        $response['success'] = false;
        $login = (int) $login;
        $data = [
            'command' => $command,
            'config' => [
                'server' => $server,
                'login' => $login,
                'password' => $password,
            ],
            'data' => $data
        ];
        // return $data;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "$url");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/x-www-form-urlencoded',
            "Auth: kjhgfgsvgfds"
        ));
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        $result = json_decode($result);
        curl_close($ch);
        if (isset($result->status) || isset($result->success)) {
            $response['success'] = ((isset($result->status)) ? $result->status : $result->success);
            if (isset($result->status) || isset($result->success)) {
                if (isset($result->message)) {
                    $response['message'] = $result->message;
                }
                if (isset($result->data)) {
                    $response['data'] = (array) $result->data;
                }
            } else {
                $response['error']['Code'] =  $result->info->code;
                $response['error']['Description'] =  $result->info->error;
            }
        } else {
            $response['message'] = "API not response!";
        }
        return $response;
    }
}
