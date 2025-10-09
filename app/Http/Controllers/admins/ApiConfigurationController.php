<?php

namespace App\Http\Controllers\admins;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\admin\SystemConfig;
use App\Models\TransactionSetting;
use App\Services\AllFunctionService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Redirect;
use PhpParser\Builder\Trait_;

class ApiConfigurationController extends Controller
{
    public function __construct()
    {
        $this->middleware(["role:api configuration"]);
        $this->middleware(["role:settings"]);
        // system module control
        $this->middleware(AllFunctionService::access('settings', 'admin'));
        $this->middleware(AllFunctionService::access('api_configuration', 'admin'));
    }
    public function apiConfiguration()
    {
        $platform_download_link = [];
        $server_type            = [];
        $server_ip              = [];
        $manager_login          = [];
        $manager_password       = [];
        $api_password           = [];
        $demo_api_key           = [];
        $api_url                = [];
        $live_api_key           = [];
        $configs = SystemConfig::select()->first();
        if (isset($configs->platform_download_link)) {
            $platform_download_link = json_decode($configs->platform_download_link);
        }
        if (isset($configs->server_type)) {
            $server_type = json_decode($configs->server_type);
        }
        if (isset($configs->server_ip)) {
            $server_ip = json_decode($configs->server_ip);
        }
        if (isset($configs->manager_login)) {
            $manager_login = json_decode($configs->manager_login);
        }
        if (isset($configs->manager_password)) {
            $manager_password = json_decode($configs->manager_password);
        }
        if (isset($configs->api_password)) {
            $api_password = json_decode($configs->api_password);
        }
        if (isset($configs->demo_api_key)) {
            $demo_api_key = json_decode($configs->demo_api_key);
        }
        if (isset($configs->api_url)) {
            $api_url = json_decode($configs->api_url);
        }
        if (isset($configs->live_api_key)) {
            $live_api_key = json_decode($configs->live_api_key);
        }
        return view('admins.settings.api_configuration', [
            'configs'                   => $configs,
            'platform_download_link'    => $platform_download_link,
            'server_type'               => $server_type,
            'server_ip'                 => $server_ip,
            'manager_login'             => $manager_login,
            'manager_password'          => $manager_password,
            'api_password'              => $api_password,
            'demo_api_key'              => $demo_api_key,
            'api_url'                   => $api_url,
            'live_api_key'              => $live_api_key,
        ]);
    }

    // api configuration
    public function apiConfigurationAdd(Request $request)
    {
        $has_records = SystemConfig::select()->count();
        $validation_rules = [
            'platform_type' => 'required',
        ];

        if (strtolower($request->platform_type) === "mt4") {
            $validation_rules += [
                'mt4_server_type'       => 'required',
                'mt4_download_link'     => 'required',
            ];
            if (strtolower($request->mt4_server_type) === "manager api") {
                $validation_rules += [
                    'mt4_server_ip'         => 'required',
                    'mt4_manager_login'     => 'required',
                    'mt4_manager_password'  => 'required',
                ];
            }

            if (strtolower($request->mt4_server_type) == "web app") {
                $validation_rules += [
                    'demo_api_key'      => 'required',
                    'api_url'           => 'required',
                    'live_api_key'      => 'required',
                ];
            }
        }
        if (strtolower($request->platform_type) === "mt5") {
            $validation_rules += [
                'mt5_server_type'       => 'required',
                'mt5_download_link'     => 'required',
                'mt5_server_ip'         => 'required',
                'mt5_manager_login'     => 'required',
                'mt5_manager_password'  => 'required',
                'mt5_api_password'      => 'required',
            ];
        }
        if (strtolower($request->platform_type) === "both") {
            $validation_rules += [
                'mt4_server_type'       => 'required',
                'mt5_server_type'       => 'required',
                'mt4_download_link'     => 'required',
                'mt5_download_link'     => 'required',
            ];
            if (strtolower($request->mt4_server_type) === "manager api") {
                $validation_rules += [
                    'mt4_server_ip'         => 'required',
                    'mt4_manager_login'     => 'required',
                    'mt4_manager_password'  => 'required',
                ];
            }

            if (strtolower($request->mt4_server_type) == "web app") {
                $validation_rules += [
                    'demo_api_key'      => 'required',
                    'api_url'           => 'required',
                    'live_api_key'      => 'required',
                ];
            }
            if (strtolower($request->mt5_server_type) == "demo" || strtolower($request->mt5_server_type == "live")) {
                $validation_rules += [
                    'mt5_server_ip'         => 'required',
                    'mt5_manager_login'     => 'required',
                    'mt5_manager_password'  => 'required',
                    'mt5_api_password'      => 'required',
                ];
            }
        }


        $validator = Validator::make($request->all(), $validation_rules);
        if ($validator->fails()) {
            if ($request->ajax()) {
                return Response::json(['status' => false, 'errors' => $validator->errors()]);
            } else {
                return Redirect()->back()->with(['status' => false, 'errors' => $validator->errors()]);
            }
        } else {
            // START: Platform download link
            // ----------------------------------------------------
            $platform_download_link = [
                'mt4_download_link' => (strtolower($request->platform_type) !== "mt5") ? ((isset($request->mt4_download_link)) ? strtolower($request->mt4_download_link) : '') : '',
                'mt5_download_link' => (strtolower($request->platform_type) !== "mt4") ? ((isset($request->mt5_download_link)) ? strtolower($request->mt5_download_link) : '') : '',
            ];
            $platform_download_link = json_encode($platform_download_link);
            // END: Download link

            // START: server type
            // -----------------------------------------------------------
            $server_type = [
                'mt4_server_type' => (strtolower($request->platform_type) !== "mt5") ? ((isset($request->mt4_server_type)) ? strtolower($request->mt4_server_type) : '') : '',
                'mt5_server_type' => (strtolower($request->platform_type) !== "mt4") ? ((isset($request->mt5_server_type)) ? strtolower($request->mt5_server_type) : '') : '',
            ];
            $server_type = json_encode($server_type);
            // END: server type

            // START: server ip
            // -----------------------------------------------------------
            $server_ip = [
                'mt4_server_ip' => (strtolower($request->mt4_server_type) !== "web app") ? ((strtolower($request->platform_type) !== "mt5") ? ((isset($request->mt4_server_ip)) ? $request->mt4_server_ip : '') : '') : '',
                'mt5_server_ip' => (strtolower($request->platform_type) !== "mt4") ? ((isset($request->mt5_server_ip)) ? $request->mt5_server_ip : '') : '',
            ];
            $server_ip = json_encode($server_ip);
            // END: server ip

            // START: manager login
            // -----------------------------------------------------------
            $manager_login = [
                'mt4_manager_login' => (strtolower($request->mt4_server_type) !== "web app") ? ((strtolower($request->platform_type) !== "mt5") ? ((isset($request->mt4_manager_login)) ? $request->mt4_manager_login : '') : '') : '',
                'mt5_manager_login' => (strtolower($request->platform_type) !== "mt4") ? ((isset($request->mt5_manager_login)) ? strtolower($request->mt5_manager_login) : '') : '',
            ];
            $manager_login = json_encode($manager_login);
            // END: manager login

            // START: manager password
            // -----------------------------------------------------------
            $manager_password = [
                'mt4_manager_password' => (strtolower($request->mt4_server_type) !== "web app") ? ((strtolower($request->platform_type) !== "mt5") ? ((isset($request->mt4_manager_password)) ? $request->mt4_manager_password : '') : '') : '',
                'mt5_manager_password' => (strtolower($request->platform_type) !== "mt4") ? ((isset($request->mt5_manager_password)) ? $request->mt5_manager_password : '') : '',
            ];
            $manager_password = json_encode($manager_password);
            // END: manager password

            // START: api password
            // -----------------------------------------------------------
            $api_password = [
                'mt5_api_password' => (strtolower($request->platform_type) !== "mt4") ? ((isset($request->mt5_api_password)) ? $request->mt5_api_password : '') : '',
            ];
            $api_password = json_encode($api_password);
            // END: api password

            // START: demo api key
            // -----------------------------------------------------------

            $demo_api_key = [
                'demo_api_key' => (strtolower($request->mt4_server_type) === "web app") ? ((isset($request->demo_api_key)) ? $request->demo_api_key : '') : '',
            ];
            $demo_api_key = json_encode($demo_api_key);
            // END: demo api key

            // START: api url
            // -----------------------------------------------------------
            $api_url = [
                'api_url' => (strtolower($request->mt4_server_type) === "web app") ? ((isset($request->api_url)) ? $request->api_url : '') : '',
            ];
            $api_url = json_encode($api_url);
            // END: api url

            // START: live api key
            // -----------------------------------------------------------
            $live_api_key = [
                'live_api_key' => (strtolower($request->mt4_server_type) === "web app") ? ((isset($request->live_api_key)) ? $request->live_api_key : '') : '',
            ];
            $live_api_key = json_encode($live_api_key);
            // END: live api key
            $data = [
                'platform_type'          => strtolower($request->platform_type),
                'server_type'            => $server_type,
                'platform_download_link' => $platform_download_link,
                'server_ip'              => $server_ip,
                'manager_login'          => $manager_login,
                'manager_password'       => $manager_password,
                'api_password'           => $api_password,
                'demo_api_key'           => $demo_api_key,
                'api_url'                => $api_url,
                'live_api_key'           => $live_api_key,
            ];


            if ($has_records == false) {
                $data['created_at'] = Carbon::now();
                if (SystemConfig::insert($data)) {
                    if ($request->ajax()) {
                        return Response::json(['status' => true, 'message' => 'Successfully Updated.']);
                    } else {
                        return Redirect()->back()->with(['status' => true, 'message' => 'Successfully Updated.']);
                    }
                } else {
                    if ($request->ajax()) {
                        return Response::json(['status' => false, 'message' => 'Failed To Update!']);
                    } else {
                        return Redirect()->back()->with(['status' => false, 'message' => 'Failed To Update!']);
                    }
                }
            } else {
                $data['updated_at'] = Carbon::now();
                if (SystemConfig::where('id', "=", $request->config_id)->update($data)) {
                    if ($request->ajax()) {
                        return Response::json(['status' => true, 'message' => 'Configuration Updated']);
                    } else {
                        return Redirect()->back()->with(['status' => true, 'message' => 'Configuration Updated']);
                    }
                } else {
                    if ($request->ajax()) {
                        return Response::json(['status' => false, 'message' => 'Configuration update failed']);
                    } else {
                        return Redirect()->back()->with(['status' => false, 'message' => 'Configuration update failed!']);
                    }
                }
            }
        }
    }
}
