<?php

namespace App\Services;

use App\Models\MtSerial;
use App\Services\Mt5WebApi;
// use App\Services\MT4API;
// use Illuminate\Database\Capsule\Manager as DB;

/**
 * CrmApiService Api new
 */

class OpenAccountService
{


	private $mt5api;
	private $mt4api;
	public $server;
	public $type;
	public $response;


	function __construct($server = 'mt4', $type = 'live')
	{
		$this->response['success'] = false;
		$this->response['message'] = '';

		$this->mt5api = new Mt5WebApi();
		// $this->mt4api = new MT4API();
		$this->server = $server;
		$this->type = $type;
	}

	public function AccountCreate($data, $server = null, $type = 'live', $disconnect = true)
	{

		$data->MainPassword         = (!$data->MainPassword) ? ("M") . date('His') . rand(100, 9999) : $data->MainPassword;
		$data->InvestPassword       = (!$data->InvestPassword) ? ("I") . date('His') . rand(100, 9999) : $data->InvestPassword;
		$data->PhonePassword        = (!$data->PhonePassword) ? ("P") . date('His') . rand(100, 9999) : $data->PhonePassword;

		$server = ($server) ? $server : $this->server;
		$type = ($type) ? $type : $this->type;

		//Get Meta Serial setup
		$mts = $this->getMTSerialSetup($server);

		$enable_mts = false;
		$limit_execute = false;
		if ($mts) {
			$last_num = ($mts->last == 0) ? $mts->login_start : ($mts->last + 1);
			if ($mts->login_gen == 'custom' || $mts->login_gen == 'limit') {
				$data->Login = $last_num;
				$enable_mts = true;
			}

			if ($mts->login_gen == 'limit') {
				if ($last_num > $mts->login_end) {
					$this->response['message'] = 'Error: Does create account for limitation!';
					$limit_execute = true;
				}
			}
		}

		if ($limit_execute) {
			return $this->response;
		}

		if ($server == 'mt4') {
			$mtdata = array(
				'command' => 'user_create',
				'data' => array(
					'name'               	=> $data->Name,
					'login'               	=> $data->Login,
					'account_id'         	=> $data->ID,
					'address'            	=> $data->Address,
					'country'            	=> $data->Country,
					'city'               	=> $data->City,
					'email'              	=> $data->Email,
					'comment'            	=> $data->Comment,
					'group'              	=> $data->Group,
					'state'              	=> $data->State,
					'leverage'           	=> $data->Leverage,
					'zipcode'            	=> $data->ZipCode,
					'mqid'               	=> 1,
					'password_phone'     	=> $data->PhonePassword,
					'id_number'          	=> 1,
					'status'             	=> 'RE',
					'taxes'              	=> 10.0,
					'agent_account'      	=> $data->Agent,
					'password'           	=> $data->MainPassword,
					'password_investor'  	=> $data->InvestPassword,
					'enable_change_password' => true,
					'enable'             	=> true,
					'send_reports'       	=> true,
					'enable_read_only'   	=> false,
				)
			);

			$result = $this->mt4api->execute($mtdata,  $type);
		} else if ($server == 'mt5') {

			$action = 'AccountCreate';
			$mtdata = array(
				"Login" 			=> $data->Login,
				"Name" 				=> $data->Name,
				"Email" 			=> $data->Email,
				"Group" 			=> $data->Group,
				"Leverage" 			=> $data->Leverage,
				"Comment" 			=> $data->Comment,
				"Phone" 			=> $data->Phone,
				"Country" 			=> $data->Country,
				"City" 				=> $data->City,
				"State" 			=> $data->State,
				"ZipCode" 			=> $data->ZipCode,
				"Address" 			=> $data->Address,
				'Password' 			=> $data->MainPassword,
				'PhonePassword' 	=> $data->PhonePassword,
				'InvestPassword' 	=> $data->InvestPassword
			);

			// var_dump($data);
			// var_dump($mtdata);exit;

			$result = $this->mt5api->execute($action, $mtdata);
		}

		$this->response['message'] = 'Error: Network Problem!';

		if (isset($result['success'])) {
			$this->response['result'] = $result;
			if ($result['success']) {
				$this->response['success'] = true;
				$this->response['login'] = ($server == 'mt4') ? $result['data']['login'] : $result['data']['Login'];
				$this->response['MainPassword'] = $data->MainPassword;
				$this->response['InvestPassword'] = $data->InvestPassword;
				$this->response['PhonePassword'] = $data->PhonePassword;
				if ($enable_mts) {
					MtSerial::where('server', $server)->update(['last' => $data->Login]);
				}
			} else {
				$this->response['message'] = ($result['error']['Description']) ? $result['error']['Description'] : $result['message'];
			}
		}

		if ($disconnect) {
			$this->mt5api->Disconnect();
		}

		return $this->response;
	}

	public function UserCreate()
	{
		// return $this->mt5api->api->UserCreate();\
		$data = [];
		$data['Login'] = NULL;
		$data['Name'] = NULL;
		$data['Email'] = NULL;
		$data['Group'] = NULL;
		$data['Leverage'] = NULL;
		$data['Comment'] = NULL;
		$data['Phone'] = NULL;
		$data['City'] = NULL;
		$data['State'] = NULL;
		$data['ZipCode'] = NULL;
		$data['Address'] = NULL;
		$data['Country'] = NULL;
		$data['MainPassword'] = NULL;
		$data['InvestPassword'] = NULL;
		$data['PasswordPhone'] = NULL;
		$data['PhonePassword'] = NULL;

		return (object)$data;
	}

	public function getMTSerialSetup($server = null)
	{
		$server = ($server) ? $server : $this->server;
		$mt_serial = MtSerial::where('server', $server)->first();
		if ($mt_serial) {
			return $mt_serial;
		}
		return false;
	}
}
