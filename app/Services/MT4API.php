<?php

namespace App\Services;

use App\Models\ApiConfig;
use Illuminate\Database\Capsule\Manager as DB;

/**
 * MT4API
 */
class MT4API
{

    //public $URL = "";
    public $URL = '';
    // public $URL = "";
    public $API_KEY_LIVE = '';
    public $API_KEY_DEMO = '';
    public function __construct()
    {
        // live api config
        $this->URL = $this->live_api()['api_url'];
        $this->API_KEY_LIVE = $this->live_api()['api_key'];
        // demo api config
        $this->API_KEY_DEMO = $this->demo_api()['api_key'];
    }

    public function execute($data, $type = 'live')
    {
        try {
            $data['api'] = 'mt4_manager_api';
            $data['api_key'] = ($type == 'demo') ? $this->API_KEY_DEMO : $this->API_KEY_LIVE;
            $ch = curl_init($this->URL);
            $post_data = 'json_request=' . json_encode($data);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);

            $output = curl_exec($ch);

            curl_close($ch);

            $response_data = json_decode($output, true);
            if ($response_data == NULL) {
                $response_data = $output;
            }

            return $response_data;
        } catch (\Throwable $th) {
            //throw $th;
            return [
                'success'=>false,
                'message'=>'MT4 Api Connection Failed!'
            ];
        }
    }
    // get live api config from database
    public function live_api()
    {
        try {
            $result = ApiConfig::where('server_type', 'live')->where('platform_type', 'mt4')->first();
            return [
                'api_key' => $result->live_api_key,
                'api_url' => $result->api_url,
            ];
        } catch (\Throwable $th) {
            //throw $th;
            return [
                'api_key' => '',
                'api_url' => '',
            ];
        }
    }
    // get demo api config from database
    public function demo_api()
    {
        try {
            $result = ApiConfig::where('server_type', 'demo')->where('platform_type', 'mt4')->first();
            return [
                'api_key' => $result->demo_api_key,
                'api_url' => $result->api_url,
            ];
        } catch (\Throwable $th) {
            //throw $th;
            return [
                'api_key' => '',
                'api_url' => '',
            ];
        }
    }
}
