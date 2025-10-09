<?php

namespace App\Services;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Request;
use Illuminate\Notifications\Action;

class VertexApiCall
{

    private $type = "live";
    private $URL, $disconnect_session, $parent_id;
    private $parameter = '';
    private $backofice_login = [];
    private $response;

    public function __construct()
    {
        $this->URL = "https://web.betatrader9.com/bowcf/WebService.svc";
        $this->backofice_login = ['Username' => 'JALAL23', 'Password' => 'TEST']; //Demo
    }

    public function execute($action, $data = null)
    {
        $url = '';
        switch ($action) {
                // get login status
            case 'BackofficeLogin':
                $url = $this->URL . '/BackofficeLogin?username=' . $this->backofice_login['Username'] . '&password=' . $this->backofice_login['Password'];
                break;
            case 'DisconnectSession':
                $url = $this->URL . '/DisconnectSession?SessionName=' . $this->backofice_login['Username'] . '&SessionType=1';
                break;
                // get symboles
            case 'GetSymbols':
                $url = $this->URL . '/GetSymbols?username=' . $this->backofice_login['Username'] . '&password=' . $this->backofice_login['Password'];
                break;
            case 'CreateClient':
                $url = $this->URL . '/CreateClient?ParentID=' . $data['ParentID'] . '&FirstName=' . $data['FirstName'];
                $url .= '&LastName=' . $data['LastName'];
                $url .= '&Username=' . $data['Username'];
                $url .= '&Password=' . $data['Password'];
                $url .= '&Mobile=' . $data['Mobile'];
                $url .= '&POB=' . $data['POB'];
                $url .= '&Country=' . $data['Country'];
                $url .= '&Email=' . $data['Email'];
                break;
            case 'CreateAccount':
                $parentID = $data['ParentID'];
                $defineDate = date('d/m/Y H:i:s', strtotime(now()));
                $url = $this->URL . "/CreateAccount?ParentID=$parentID&AccountType=1&IsDemo=false&IsLocked=false&DontLiquidate=true&IsMargin=true&UserDefinedDate=$defineDate";
                break;
                // get symbols groups
            case 'GetSymbolsGroups':
                $url = $this->URL . '/GetSymbolsGroups';

                break;
            case 'GetAccountSummary':
                // get account summary/balance-equity
                $account_id = $data['AccountId'];
                $url = $this->URL . "/GetAccountSummary?AccountId=$account_id";
                break;
            default:
                # code...
                break;
        }
        // get response from api

        // try {
        //     $client = new Client();
        //     $guzzleResponse = $client->get($url);
        //     // return ($guzzleResponse->getStatusCode());
        //     if ($guzzleResponse->getStatusCode() == 200) {
        //         $response = json_decode($guzzleResponse->getBody(), true);
        //         return $response;
        //     }
        //     return ($guzzleResponse->getStatusCode());
        // } catch (RequestException $e) {
        //     // you can catch here 400 response errors and 500 response errors
        //     // see this https://stackoverflow.com/questions/25040436/guzzle-handle-400-bad-request/25040600
        //     // return $e;
        // } catch (Exception $e) {
        //     //other errors 
        //     // return $e;
        // }
        $client = new Client();
        $headers = [
            'Cookie' => 'ASP.NET_SessionId=mviriln1zp3wcprbtk2ftnoe'
        ];
        $request = new Request('GET', $url, $headers);
        $res = $client->sendAsync($request)->wait();
        $statusCode = $res->getStatusCode();
        $response_data = json_decode($res->getBody());
        $bodyContents  = json_decode($response_data->d);

        return ([
            'success' => ($statusCode == 200) ? true : false,
            'data' => $bodyContents
        ]);
        // $client = new \GuzzleHttp\Client(self::getHttpHeaders());
        // $response = $client->get($url, ['verify' => false]);

        // $resp['statusCode'] = $response->getStatusCode();
        // $body_content = json_decode($response->getBody()->getContents());
        // $resp['bodyContents'] = json_decode($body_content->d);

        // return $resp;
    }
    public static function getHttpHeaders()
    {

        $bearerToken = 'your-bearer-token';
        $headers    =   [
            'headers' => [
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $bearerToken,
            ],
            'http_errors' => false,
        ];
        return $headers;
    }
}
