<?php

namespace App\Services;

use App\Models\ApiConfig;
use App\Services\api\Mt5ManagerApi;
use App\Services\MT5\MTCommand;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;

define("PATH_TO_SCRIPTS", "MT5/");

/**
 * MT5WEBAPI Api new
 * This live environment api
 * not for testing
 */

class Mt5WebApi
{
    // live api
    private $MT5_SERVER_IP = ""; //MT5 Server IP here
    private $MT5_SERVER_PORT = 443; //MT5 Server port here
    private $MT5_SERVER_WEB_LOGIN = 0; //MT5 Server login here
    private $MT5_SERVER_WEB_PASSWORD = ""; //MT5 Api password here
    private $MT5_SERVER_MANAGER_PASSWORD = ""; //MT5 Api password here

    // demo api
    private $MT5_SERVER_IP_DEMO = ''; //MT5 Server DEMO IP here
    private $MT5_SERVER_PORT_DEMO = ''; //MT5 Server DEMO port here
    private $MT5_SERVER_WEB_LOGIN_DEMO = ''; //MT5 Server DEMO login here
    private $MT5_SERVER_MANAGER_PASSWORD_DEMO = ''; //MT5 DEMO Api password here

    public $api_key = "kjhgfgsvgfds";
    public $type;
    public $req_data = [];
    public $api_url = "http://198.38.93.116:1188/post-req";
    public $api_type = 'manager'; //web,manager

    private $response;

    function __construct($config = null, $type = 'live')
    {
        $this->response['success'] = false;
        if ($type == 'live') {
            // live api
            $this->MT5_SERVER_IP                = $this->api_config_live()->server_ip;
            $this->MT5_SERVER_PORT              = $this->api_config_live()->server_port;
            $this->MT5_SERVER_WEB_LOGIN         = $this->api_config_live()->manager_login;
            $this->MT5_SERVER_WEB_PASSWORD  = $this->api_config_live()->web_password;
            $this->MT5_SERVER_MANAGER_PASSWORD  = $this->api_config_live()->manager_password;
            // set up the api url
            $this->api_url = $this->api_config_live()->api_url;
            $this->api_type = $this->api_config_live()->api_type;
            if (
                empty($this->MT5_SERVER_IP) ||
                empty($this->MT5_SERVER_PORT) ||
                empty($this->MT5_SERVER_WEB_LOGIN) ||
                empty($this->MT5_SERVER_MANAGER_PASSWORD)
            ) {
                $this->response['message'] = 'Config information must not be empty!';
            } else {
                $this->req_data['config']['server'] = $this->MT5_SERVER_IP . ":" . $this->MT5_SERVER_PORT;
                $this->req_data['config']['login'] = $this->MT5_SERVER_WEB_LOGIN;
                $this->req_data['config']['password'] = ($this->api_type === 'manager') ? $this->MT5_SERVER_MANAGER_PASSWORD : $this->MT5_SERVER_WEB_PASSWORD;
            }
        } else if ($type == 'demo') {
            // demo api
            $this->MT5_SERVER_IP_DEMO               = $this->api_config_demo()->server_ip;
            $this->MT5_SERVER_PORT_DEMO             = $this->api_config_demo()->server_port;
            $this->MT5_SERVER_WEB_LOGIN_DEMO        = $this->api_config_demo()->manager_login;
            $this->MT5_SERVER_MANAGER_PASSWORD_DEMO = $this->api_config_demo()->manager_password;
            // set up the api url
            $this->api_url = $this->api_config_demo()->api_url;
            $this->api_type = $this->api_config_demo()->api_type;
            if (
                empty($this->MT5_SERVER_IP_DEMO) ||
                empty($this->MT5_SERVER_PORT_DEMO) ||
                empty($this->MT5_SERVER_WEB_LOGIN_DEMO) ||
                empty($this->MT5_SERVER_MANAGER_PASSWORD_DEMO)
            ) {
                $this->response['message'] = 'Config information must not be empty!';
            } else {
                $this->req_data['config']['server'] = $this->MT5_SERVER_IP_DEMO . ":" . $this->MT5_SERVER_PORT_DEMO;
                $this->req_data['config']['login'] = $this->MT5_SERVER_WEB_LOGIN_DEMO;
                $this->req_data['config']['password'] = $this->MT5_SERVER_MANAGER_PASSWORD_DEMO;
            }
        }
    }

    public function execute($command = null, $data = null)
    {
        $this->response = [];
        $this->response['success'] = false;
        if (MTCommand::hasCommand($command)) {
            switch ($this->api_type) {
                    // manager api
                case 'manager':
                    return Mt5ManagerApi::run_manager_api(
                        $command,
                        $data,
                        $this->MT5_SERVER_IP . ':' . $this->MT5_SERVER_PORT,
                        $this->MT5_SERVER_WEB_LOGIN,
                        $this->MT5_SERVER_MANAGER_PASSWORD,
                        $this->api_url
                    );
                    break;
                    // web api
                default:
                    $this->req_data['command'] = $command;
                    $this->req_data['data'] = $data;
                    $result = $this->apiCall();

                    if ($result) {
                        $result = json_decode($result);
                        // return $result;
                        if (isset($result->status) || isset($result->success)) {
                            $this->response['success'] = isset($result->status) ? $result->status : $result->success;

                            if (isset($result->status) || isset($result->success)) {
                                if (isset($result->message)) {
                                    $this->response['message'] = $result->message;
                                }
                                if (isset($result->data)) {
                                    $this->response['data'] = (array) $result->data;
                                }
                                
                                if(isset($result->error)){
                                    $this->response['error']['Code'] =  isset($result->error->Code) ? $result->error->Code : 0;
                                    $this->response['error']['Description'] =  isset($result->error->Description) ? $result->error->Description : "Unknown Error!";
                                }
                            } else {
                                $this->response['error']['Code'] =  -1;
                                $this->response['error']['Description'] =  "Error: Network issue!";
                            }
                        } else {
                            $this->response['message'] = "API not response!";
                        }
                    }
                    break;
            }
        } else {
            $this->response['message'] = 'Command does not exits!';
        }
        return $this->response;
    }
    // mt5 api call
    public function apiCall()
    {
        // return $this->req_data;
        try {
            $client = new Client(['verify' => false]);
            $headers = [
                'Content-Type' => 'application/json'
            ];
            $body = json_encode($this->req_data);
            $request = new Request('POST', $this->api_url . "/", $headers, $body);
            $res = $client->sendAsync($request)->wait();
            $result = $res->getBody();
            $result = json_decode($result, true);
            $result = (array) $result;
            return json_encode($result);
        } catch (\Throwable $th) {
            //throw $th;
            return json_encode(['success'=>false,'message'=>'MT5 API Not configured']);
        }
    }
    // get live api config
    public function api_config_live()
    {
        try {
            $result = ApiConfig::where('platform_type', 'mt5')
                ->where('server_type', 'live')
                ->where('status', 1)->first();
            return (object) [
                'server_ip' => $result->server_ip,
                'manager_login' => $result->manager_login,
                'server_port' => $result->server_port,
                'web_password' => $result->web_password,
                'manager_password' => $result->manager_password,
                'api_type' => $result->api_type,
                'api_url' => $result->api_url,
            ];
        } catch (\Throwable $th) {
            // throw $th;
            return (object) [
                'server_ip' => '',
                'manager_login' => '',
                'server_port' => '',
                'web_password' => '',
                'manager_password' => '',
                'api_type' => '',
                'api_url' => '',
            ];
        }
    }
    public function api_config_demo()
    {
        try {
            $result = ApiConfig::where('platform_type', 'mt5')
                ->where('server_type', 'demo')
                ->where('status', 1)->first();
            return (object) [
                'server_ip' => $result->server_ip,
                'manager_login' => $result->manager_login,
                'server_port' => $result->server_port,
                'web_password' => $result->web_password,
                'manager_password' => $result->manager_password,
                'api_type' => $result->api_type,
                'api_url' => $result->api_url,
            ];
        } catch (\Throwable $th) {
            //throw $th;
            return (object) [
                'server_ip' => '',
                'manager_login' => '',
                'server_port' => '',
                'web_password' => '',
                'manager_password' => '',
                'api_type' => '',
                'api_url' => '',
            ];
        }
    }

    public function Disconnect()
    {

        return true;
    }
}
