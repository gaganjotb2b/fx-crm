<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;

class MT4API
{
    private $api_url = "http://127.0.0.1:8080/api";
    private $api_key = "";
    public function execute($data, $type = 'live')
    {
        $client = new Client();
        switch ($data['command']) {
            case 'user_create':
                $value = $data['data'];
                $options = [
                    'multipart' => [
                        [
                            'name' => 'name',
                            'contents' => array_key_exists('name', $value) ? $value['name'] : null,
                        ],
                        [
                            'name' => 'account_id',
                            'contents' => array_key_exists('account_id', $value) ? $value['account_id'] : null,
                        ],
                        [
                            'name' => 'address',
                            'contents' => array_key_exists('address', $value) ? $value['address'] : null,
                        ],
                        [
                            'name' => 'country',
                            'contents' => array_key_exists('country', $value) ? $value['country'] : null,
                        ],
                        [
                            'name' => 'city',
                            'contents' => array_key_exists('city', $value) ? $value['city'] : null,
                        ],
                        [
                            'name' => 'email',
                            'contents' => array_key_exists('email', $value) ? $value['email'] : null,
                        ],
                        [
                            'name' => 'comment',
                            'contents' => array_key_exists('comment', $value) ? $value['comment'] : null,
                        ],
                        [
                            'name' => 'group',
                            'contents' => array_key_exists('group', $value) ? $value['group'] : null,
                        ],
                        [
                            'name' => 'state',
                            'contents' => array_key_exists('state', $value) ? $value['state'] : null,
                        ],
                        [
                            'name' => 'leverage',
                            'contents' => array_key_exists('leverage', $value) ? $value['leverage'] : null,
                        ],
                        [
                            'name' => 'zipcode',
                            'contents' => array_key_exists('zipcode', $value) ? $value['zipcode'] : null,
                        ],
                        [
                            'name' => 'mqid',
                            'contents' => array_key_exists('mqid', $value) ? $value['mqid'] : null,
                        ],
                        [
                            'name' => 'password_phone',
                            'contents' => array_key_exists('password_phone', $value) ? $value['password_phone'] : null,
                        ],
                        [
                            'name' => 'id_number',
                            'contents' => array_key_exists('id_number', $value) ? $value['id_number'] : null,
                        ],
                        [
                            'name' => 'status',
                            'contents' => array_key_exists('status', $value) ? $value['status'] : null,
                        ],
                        [
                            'name' => 'taxes',
                            'contents' => array_key_exists('taxes', $value) ? $value['taxes'] : null,
                        ],
                        [
                            'name' => 'agent_account',
                            'contents' => array_key_exists('agent_account', $value) ? $value['agent_account'] : null,
                        ],
                        [
                            'name' => 'phone',
                            'contents' => array_key_exists('phone', $value) ? $value['phone'] : null,
                        ],
                        [
                            'name' => 'password',
                            'contents' => array_key_exists('password', $value) ? $value['password'] : null,
                        ],
                        [
                            'name' => 'password_investor',
                            'contents' => array_key_exists('passwod_investor', $value) ? $value['passwod_investor'] : 'na',
                        ],
                        [
                            'name' => 'change_password',
                            'contents' => array_key_exists('enable_change_password', $value) ? $value['enable_change_password'] : 1,
                        ],
                        [
                            'name' => 'enable',
                            'contents' => array_key_exists('enable', $value) ? $value['enable'] : null,
                        ],
                        [
                            'name' => 'send_reports',
                            'contents' => array_key_exists('send_reports', $value) ? $value['send_reports'] : null,
                        ],
                        [
                            'name' => 'read_only',
                            'contents' => array_key_exists('enable_read_only', $value) ? $value['enable_read_only'] : false,
                        ],
                        [
                            'name' => 'account_type',
                            'contents' => $type
                        ],
                        [
                            'name' => 'balance',
                            'contents' => array_key_exists('balance', $value) ? $value['balance'] : 0,
                        ],
                        [
                            'name' => 'mergin',
                            'contents' => array_key_exists('nergin', $value) ? $value['nergin'] : 0,
                        ]
                    ]
                ];
                // return $options;
                $request = new Request('POST', $this->api_url . '/meta/account/open');
                $res = $client->sendAsync($request, $options)->wait();
                return (json_decode($res->getBody(),true));

                break;

            default:
                # code...
                break;
        }
    }
}
