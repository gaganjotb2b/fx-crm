<?php

namespace App\Services;

class CopyApiService
{
    private $baseUrl;
    private $api_key;
    private $server;

    public function __construct($server = 'mt5', $api_key = null)
    {
        $this->api_key = $api_key ?? '12345'; // Default API key
        $this->server = $server;
        $this->baseUrl = 'http://136.243.103.53:8000/api/';
    }

    private function isSetup()
    {
        return !empty($this->api_key);
    }

    public function apiCall($endpoint, $data = [], $method = 'POST')
    {
        if (!$this->isSetup()) {
            return ['error' => 'API key must be set up!'];
        }

        // Handle cases where first argument is an array (Custom SQL queries)
        if (is_array($endpoint)) {
            $data = $endpoint;
            $endpoint = 'custom'; // Default endpoint for custom queries
        }

        $url = $this->baseUrl . $endpoint;
        $ch = curl_init();

        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_POSTFIELDS => is_array($data) ? http_build_query($data) : $data,
            CURLOPT_HTTPHEADER => [
                'Accept: application/json',
                "Auth: $this->api_key",
                'Content-Type: application/x-www-form-urlencoded'
            ],
        ]);

        $response = curl_exec($ch);
        curl_close($ch);

        return $response;
    }

}
