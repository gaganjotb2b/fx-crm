<?php

namespace App\Http\Controllers\systems;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\admin\SystemConfig;
use App\Models\ApiConfig;
use App\Models\TransactionSetting;
use Carbon\Carbon;
use Symfony\Component\HttpKernel\Event\ResponseEvent;

// use PhpParser\Builder\Trait_;

class SystemApiConfigurationController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware(["role:api configuration"]);
    //     $this->middleware(["role:settings"]);
    // }
    public function apiConfiguration()
    {
        $server_type            = [];
        $server_ip              = [];
        $manager_login          = [];
        $server_port            = [];
        $web_password           = [];
        $manager_password       = [];
        $api_key                = [];
        $api_url                = [];

        $system_configs = SystemConfig::select()->first();
        $api_configs = ApiConfig::select()->first();
        $demo_api = ApiConfig::select()->where('server_type', 'demo')->first();
        $manager_api = ApiConfig::select()->where('server_type', 'manager-api')->first();
        $web_api = ApiConfig::select()->where('server_type', 'web-api')->first();
        //  return $manager_api;

        if (isset($system_configs->server_type)) {
            $server_type = json_decode($system_configs->server_type);
        }
        if (isset($api_configs->server_ip)) {
            $server_ip = json_decode($api_configs->server_ip);
        }
        if (isset($api_configs->manager_login)) {
            $manager_login = json_decode($api_configs->manager_login);
        }
        if (isset($api_configs->server_port)) {
            $server_port = json_decode($api_configs->server_port);
        }
        if (isset($api_configs->web_password)) {
            $web_password = json_decode($api_configs->web_password);
        }
        if (isset($api_configs->manager_password)) {
            $manager_password = json_decode($api_configs->manager_password);
        }
        if (isset($api_configs->api_key)) {
            $api_key = json_decode($api_configs->api_key);
        }
        if (isset($api_configs->api_url)) {
            $api_url = json_decode($api_configs->api_url);
        }

        return view('systems.configurations.api_configuration', [
            'api_configs'               => $api_configs,
            'demo_api'                  => $demo_api,
            'manager_api'               => $manager_api,
            'web_api'                   => $web_api,
            'server_type'               => $server_type,
            'server_ip'                 => $server_ip,
            'manager_login'             => $manager_login,
            'server_port'               => $server_port,
            'web_password'              => $web_password,
            'manager_password'          => $manager_password,
            'api_key'                   => $api_key,
            'api_url'                   => $api_url,

        ]);
    }

    // api configuration
    public function apiConfigurationAdd(Request $request)
    {
        $has_records_system_config = SystemConfig::select()->count();
        $has_records_api_config = ApiConfig::select()->count();
        $validation_rules = [
            'platform_type'         => 'required',
            'mt5_server_type'       => 'required',
            'mt5_server_ip'         => 'required',
            'mt5_manager_login'     => 'required',
            'mt5_server_port'       => 'required',
            'mt5_manager_password'  => 'required',
            'mt5_web_password'      => 'required',
            'mt5_api_key'           => 'required',
            'mt5_api_url'           => 'required',
        ];

        // if (strtolower($request->platform_type) === "mt5") {
        //     $validation_rules += [

        //     ];
        // }


        $validator = Validator::make($request->all(), $validation_rules);
        if ($validator->fails()) {
            if ($request->ajax()) {
                return Response::json(['status' => false, 'errors' => $validator->errors()]);
            } else {
                return Redirect()->back()->with(['status' => false, 'errors' => $validator->errors()]);
            }
        } else {

            // START: server type
            // -----------------------------------------------------------
            $server_type = [
                'mt5_server_type' => (strtolower($request->platform_type) !== "mt4") ? ((isset($request->mt5_server_type)) ? strtolower($request->mt5_server_type) : '') : '',
            ];
            $server_type = json_encode($server_type);
            // END: server type

            // START: server ip
            // -----------------------------------------------------------
            $server_ip = [
                'mt5_server_ip' => (strtolower($request->platform_type) !== "mt4") ? ((isset($request->mt5_server_ip)) ? $request->mt5_server_ip : '') : '',
            ];
            $server_ip = json_encode($server_ip);
            // END: server ip

            // START: manager login
            // -----------------------------------------------------------
            $manager_login = [
                'mt5_manager_login' => (strtolower($request->platform_type) !== "mt4") ? ((isset($request->mt5_manager_login)) ? strtolower($request->mt5_manager_login) : '') : '',
            ];
            $manager_login = json_encode($manager_login);
            // END: manager login

            // START: server port
            // -----------------------------------------------------------
            $server_port = [
                'mt5_server_port' => (strtolower($request->platform_type) !== "mt4") ? ((isset($request->mt5_server_port)) ? strtolower($request->mt5_server_port) : '') : '',
            ];
            $server_port = json_encode($server_port);
            // END: server port

            // START: web password
            // -----------------------------------------------------------
            $web_password = [
                'mt5_web_password' => (strtolower($request->platform_type) !== "mt4") ? ((isset($request->mt5_web_password)) ? $request->mt5_web_password : '') : '',
            ];
            $web_password = json_encode($web_password);
            // END: web password
            // START: manager password
            // -----------------------------------------------------------
            $manager_password = [
                'mt5_manager_password' => (strtolower($request->platform_type) !== "mt4") ? ((isset($request->mt5_manager_password)) ? $request->mt5_manager_password : '') : '',
            ];
            $manager_password = json_encode($manager_password);
            // END: manager password

            // START: api key
            // -----------------------------------------------------------
            $api_key = [
                'mt5_api_key' => (strtolower($request->platform_type) !== "mt4") ? ((isset($request->mt5_api_key)) ? $request->mt5_api_key : '') : '',
            ];
            $api_key = json_encode($api_key);
            // END: api key

            // START: api url
            // -----------------------------------------------------------
            $api_url = [
                'mt5_api_url' => (strtolower($request->platform_type) !== "mt4") ? ((isset($request->mt5_api_url)) ? $request->mt5_api_url : '') : '',
            ];
            $api_url = json_encode($api_url);
            // END: api url

            $system_data = [
                'platform_type'    => strtolower($request->platform_type),
                'server_type'      => $server_type,
            ];

            $api_data = [
                'server_ip'        => $server_ip,
                'manager_login'    => $manager_login,
                'server_port'      => $server_port,
                'web_password'     => $web_password,
                'manager_password' => $manager_password,
                'api_key'          => $api_key,
                'api_url'          => $api_url,
            ];

            $status = false;
            if ($has_records_system_config == false) {
                $data['created_at'] = Carbon::now();
                if (SystemConfig::insert($system_data)) {
                    $status = true;
                } else {
                    $status = false;
                }
            } else {
                $data['updated_at'] = Carbon::now();
                if (SystemConfig::where('id', "=", 1)->update($system_data)) {
                    $status = true;
                } else {
                    $status = false;
                }
            }

            if ($has_records_api_config == false) {
                $data['created_at'] = Carbon::now();
                if (ApiConfig::insert($api_data)) {
                    $status = true;
                } else {
                    $status = false;
                }
            } else {
                $data['updated_at'] = Carbon::now();
                if (ApiConfig::where('id', "=", 1)->update($api_data)) {
                    $status = true;
                } else {
                    $status = false;
                }
            }
            if ($status === true) {
                return Response::json(['status' => true, 'message' => 'Successfully Updated.']);
            } else {
                return Response::json(['status' => false, 'message' => 'Failed To Update!']);
            }
        }
    }
    // mt4 live api configuration
    public function mt4_live_api_config(Request $request)
    {
        try {
            $validation_rules = [
                'mt4_api_url' => 'required',
                'mt4_api_key' => 'required',
            ];

            $validator = Validator::make($request->all(), $validation_rules);
            if ($validator->fails()) {
                return Response::json([
                    'status' => false,
                    'message' => 'please fix the following   errors!',
                    'errors' => $validator->errors(),
                ]);
            }

            $create = ApiConfig::updateOrCreate(
                [
                    'platform_type' => $request->platform_type,
                    'server_type' => $request->server_type,
                ],
                [
                    'api_url' => $request->mt4_api_url,
                    'live_api_key' => $request->mt4_api_key,
                ]
            );
            if ($create) {
                return Response::json([
                    'status' => true,
                    'message' => 'API Configuration successfully done!',
                ]);
            }
            return Response::json([
                'status' => false,
                'message' => 'Somthing went wrong, please try again later!',
            ]);
        } catch (\Throwable $th) {
            throw $th;
            return Response::json([
                'status' => false,
                'message' => 'Got a server error!',
            ]);
        }
    }

    //MT4 demo api configuration
    public function mt4_demo_api_config(Request $request)
    {
        try {
            $validation_rules = [
                'mt4_api_url' => 'required',
                'mt4_api_key' => 'required',
            ];
            $validator = Validator::make($request->all(), $validation_rules);
            if ($validator->fails()) {
                return Response::json([
                    'status' => false,
                    'message' => 'Please fix the following errors!',
                    'errors' => $validator->errors(),
                ]);
            }
            $create = ApiConfig::updateOrCreate(
                [
                    'platform_type' => $request->platform_type,
                    'server_type' => $request->server_type,
                ],
                [
                    'platform_type' => $request->platform_type,
                    'server_type' => $request->server_type,
                    'api_url' => $request->mt4_api_url,
                    'demo_api_key' => $request->mt4_api_key,
                ]
            );
            if ($create) {
                return Response::json([
                    'status' => true,
                    'message' => "Api Configuration Successfully Done!",
                ]);
            }
            return Response::json([
                'status' => false,
                'message' => 'Somthing went wrong, please try again later!'
            ]);
        } catch (\Throwable $th) {
            throw $th;
            return Response::json([
                'status' => false,
                'message' => 'Got a server error!'
            ]);
        }
    }

    //Manager API Configuration 
    public function manager_api_config(Request $request)
    {
        try {
            $validation_rules = [
                'mt5_server_ip' => 'required',
                'mt5_server_port' => 'required',
                'mt5_api_url' => 'required',
                'mt5_manager_login' => 'required',
                'mt5_manager_password' => 'required|min:6',
            ];
            $validator = Validator::make($request->all(), $validation_rules);
            if ($validator->fails()) {
                return Response::json([
                    'status' => false,
                    'message' => "Please fix the following errors!",
                    'errors' => $validator->errors(),
                ]);
            }

            $create = ApiConfig::updateOrCreate(
                [
                    'platform_type' => $request->platform_type,
                    'server_type' => $request->server_type,
                ],
                [
                    'platform_type' => $request->platform_type,
                    'server_type' => $request->server_type,
                    'server_ip' => $request->mt5_server_ip,
                    'server_port' => $request->mt5_server_port,
                    'api_url' => $request->mt5_api_url,
                    'manager_login' => $request->mt5_manager_login,
                    'manager_password' => $request->mt5_manager_password,
                    'status' => isset($request->status) ? $request->status : 0,
                ]
            );

            if ($create) {
                return Response::json([
                    'status' => true,
                    'message' => 'API Configuration Successfully Dine!'
                ]);
            }
            return Response::json(
                [
                    'status' => false,
                    'message' => 'Something went wrong, Please try again later!'
                ]
            );
        } catch (\Throwable $th) {
            throw $th;
            return Response::json([
                'stats' => false,
                'message' => 'Got a server error!'
            ]);
        }
    }
    public function web_api_config(Request $request)
    {
        try {
            $validation_rules = [
                'mt5_server_ip' => 'required',
                'mt5_server_port' => 'required',
                'mt5_api_url' => 'required',
                'mt5_web_password' => 'required|min:6',
                'mt5_manager_login' => 'required',
                'mt5_manager_password' => 'required|min:6',
            ];
            $validator = Validator::make($request->all(), $validation_rules);
            if ($validator->fails()) {
                return Response::json([
                    'status' => false,
                    'message' => 'Please fix the following errors!',
                    'errors' => $validator->errors(),
                ]);
            }
            $create = ApiConfig::updateOrCreate(
                [
                    'platform_type' => $request->platform_type,
                    'server_type' => $request->server_type,
                ],
                [
                    'server_ip' => $request->mt5_server_ip,
                    'server_port' =>  $request->mt5_server_port,
                    'api_url' => $request->mt5_api_url,
                    'web_password' => $request->mt5_web_password,
                    'manager_login' => $request->mt5_manager_login,
                    'manager_password' => $request->mt5_manager_password,
                    'status' => isset($request->status) ? $request->status : 0,
                ]
            );
            if ($create) {
                return Response::json([
                    'status' => true,
                    'message' => 'Web app api configuration successfully done!'
                ]);
            } else {
                return Response::json([
                    'status' => false,
                    'message' => 'Something went wrong, please try again later!'
                ]);
            }
        } catch (\Throwable $th) {
            throw $th;
            return Response::json([
                'status' => false,
                'message' => 'Got a server error',
            ]);
        }
    }
}
