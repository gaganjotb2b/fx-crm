<?php

namespace App\Http\Controllers\systems;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\admin\SystemConfig;
use App\Models\TransactionSetting;
use Carbon\Carbon;
use PhpParser\Builder\Trait_;

class SystemConfigController extends Controller
{
    // view configuration 
    public function configuration()
    {
        $themes                 = [];
        $platform_download_link = [];
        $server_type            = [];
        $server_ip              = [];
        $manager_login          = [];
        $manager_password       = [];
        $api_password           = [];
        $demo_api_key           = [];
        $api_url                = [];
        $live_api_key           = [];
        $com_email              = [];
        $com_phone              = [];
        $com_social_info        = [];
        $transaction_charge     = [];
        $transaction_permission = [];
        $configs = SystemConfig::select()->first();
        if (isset($configs->theme)) {
            $themes = $configs->theme;
            $themes = json_decode($themes);
        }
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
        if (isset($configs->com_email)) {
            $com_email = json_decode($configs->com_email);
        }
        if (isset($configs->com_phone)) {
            $com_phone = json_decode($configs->com_phone);
        }
        if (isset($configs->com_social_info)) {
            $com_social_info = json_decode($configs->com_social_info);
        }
        if (isset($configs->transaction_charge)) {
            $transaction_charge = json_decode($configs->transaction_charge);
        }
        if (isset($configs->transaction_permission)) {
            $transaction_permission = json_decode($configs->transaction_permission);
        }
        return view('systems.config-form', [
            'configs'                   => $configs,
            'themes'                    => $themes,
            'platform_download_link'    => $platform_download_link,
            'server_type'               => $server_type,
            'server_ip'                 => $server_ip,
            'manager_login'             => $manager_login,
            'manager_password'          => $manager_password,
            'api_password'              => $api_password,
            'demo_api_key'              => $demo_api_key,
            'api_url'                   => $api_url,
            'live_api_key'              => $live_api_key,
            'com_email'                 => $com_email,
            'com_phone'                 => $com_phone,
            'com_social_info'           => $com_social_info,
            'transaction_charge'        => $transaction_charge,
            'transaction_permission'    => $transaction_permission,
        ]);
    }
    // theme setup
    public function themeSetup(Request $request)
    {
        $has_records = SystemConfig::select()->count();

        $validation_rules = [
            'user_theme'    => 'required',
            'admin_theme'   => 'required',
            'dark_logo'     => 'image|mimes:jpeg,png,jpg|max:2048',
            'light_logo'    => 'image|mimes:jpeg,png,jpg|max:2048',
        ];

        $validator = Validator::make($request->all(), $validation_rules);
        if ($validator->fails()) {
            if ($request->ajax()) {
                return Response::json(['status' => false, 'errors' => $validator->errors()]);
            } else {
                return Redirect()->back()->with(['status' => false, 'errors' => $validator->errors()]);
            }
        } else {
            $theme = [
                'user_theme'  => $request->user_theme,
                'admin_theme' => $request->admin_theme,
            ];
            // BEGIN: upload logos
            $logos  = [];
            $data   = [];
            if (isset($request->dark_logo)) {
                $dark_logo = config('app.name') . '_logo_dark_' . time() . '.' . $request->dark_logo->extension();
                $request->dark_logo->move(public_path('Uploads/logos'), $dark_logo);
                $logos['dark_logo'] = $dark_logo;
            }
            if (isset($request->light_logo)) {
                $light_logo = config('app.name') . '_logo_light_' . time() . '.' . $request->light_logo->extension();
                $request->light_logo->move(public_path('Uploads/logos'), $light_logo);
                $logos['light_logo'] = $light_logo;
            }
            $theme = json_encode($theme);

            $data = [
                'theme' => $theme
            ];

            if ($has_records == false) {
                $data['created_at'] = Carbon::now();
                $logos = json_encode($logos);
                $data['logo'] = $logos;
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
                $config_records = SystemConfig::select('id', 'logo')->first();
                if (!isset($request->dark_logo)) {
                    $db_logos = $config_records->logo;
                    $db_logos = json_decode($db_logos);
                    $db_dark_logo = isset($db_logos->dark_logo) ? $db_logos->dark_logo : '';
                    $logos['dark_logo'] = $db_dark_logo;
                }
                if (!isset($request->light_logo) && isset($db_logos->light_logo)) {
                    $db_logos = $config_records->logo;
                    $db_logos = json_decode($db_logos);
                    $db_light_logo = $db_logos->light_logo;
                    $logos['light_logo'] = $db_light_logo;
                }

                $logos = json_encode($logos);
                $data['logo'] = $logos;
                if (SystemConfig::where('id', "=", $config_records->id)->update($data)) {
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
    // api configuration
    public function apiConfiguration(Request $request)
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

    // smtp setup
    public function smtpSetup(Request $request)
    {
        $has_records = SystemConfig::select()->count();
        $validation_rules = [
            'mail_driver'       => 'required',
            'host'              => 'required',
            'port'              => 'required',
            'mail_user'         => 'required',
            'mail_password'     => 'required',
            'mail_encryption'   => 'required',
        ];
        $validator = Validator::make($request->all(), $validation_rules);
        if ($validator->fails()) {
            if ($request->ajax()) {
                return Response::json(['status' => false, 'errors' => $validator->errors()]);
            } else {
                return Redirect()->back()->with(['status' => false, 'errors' => $validator->errors()]);
            }
        } else {
            $data = [
                'mail_driver'     => $request->mail_driver,
                'host'            => $request->host,
                'port'            => $request->port,
                'mail_user'       => $request->mail_user,
                'mail_password'   => $request->mail_password,
                'mail_encryption' => $request->mail_encryption,
            ];
            if (SystemConfig::where('id', $request->config_id)->update($data)) {
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
        }
    }

    // company information setup
    public function companyInfoSetup(Request $request)
    {
        $validation_rules = [
            'com_name'      => 'required',
            'com_license'   => 'required',
            'com_email_1'   => 'required|email',
            'com_phone_1'   => 'required',
            'copyright'     => 'required',
            'support_email' => 'required|email',
            'auto_email'    => 'required|email',
        ];
        $validator = Validator::make($request->all(), $validation_rules);
        if ($validator->fails()) {
            if ($request->ajax()) {
                return Response::json(['status' => false, 'errors' => $validator->errors()]);
            } else {
                return Redirect()->back()->with(['status' => false, 'errors' => $validator->errors()]);
            }
        } else {
            //company email store
            $com_email = [
                'com_email_1' => (isset($request->com_email_1)) ? $request->com_email_1 : '',
                'com_email_2' => (isset($request->com_email_2)) ? $request->com_email_2 : '',
            ];
            $com_email = json_encode($com_email);
            //company phone store
            $com_phone = [
                'com_phone_1' => (isset($request->com_phone_1)) ? $request->com_phone_1 : '',
                'com_phone_2' => (isset($request->com_phone_2)) ? $request->com_phone_2 : '',
            ];
            $com_phone = json_encode($com_phone);
            //start company social media
            $com_social_info = [
                'facebook'  => (isset($request->facebook)) ? $request->facebook : '',
                'twitter'   => (isset($request->twitter)) ? $request->twitter : '',
                'skype'     => (isset($request->skype)) ? $request->skype : '',
                'youtube'   => (isset($request->youtube)) ? $request->youtube : '',
                'telegram'  => (isset($request->telegram)) ? $request->telegram : '',
                'linkedin'  => (isset($request->linkedin)) ? $request->linkedin : '',
                'livechat'  => (isset($request->livechat)) ? $request->livechat : '',
            ];
            $com_social_info = json_encode($com_social_info);
            //end company social media
            $data = [
                'com_name'          => $request->com_name,
                'com_email'         => $com_email,
                'com_phone'         => $com_phone,
                'com_license'       => $request->com_license,
                'com_website'       => $request->com_website,
                'com_address'       => $request->com_address,
                'com_authority'     => $request->com_authority,
                'com_social_info'   => $com_social_info,
                'copyright'         => $request->copyright,
                'privacy_statement' => $request->privacy_statement,
                'support_email'     => $request->support_email,
                'auto_email'        => $request->auto_email,
            ];
            if (SystemConfig::where('id', $request->config_id)->update($data)) {
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
        }
    }

    // finance setting
    public function financeSetting(Request $request)
    {
        $draw = $request->input('draw');
        $start = $request->input('start');
        $length = $request->input('length');
        $result = TransactionSetting::select();
        // Filter by finance
        $count = $result->count(); // <------count total rows
        $result = $result->orderby('id', 'DESC')->skip($start)->take($length)->get();
        $data = array();
        $i = 0;

        // $serial = 1;
        foreach ($result as $row) {
            $data[$i]['transaction_type']   = (($row->transaction_type == "deposit") ? "Deposit" : (($row->transaction_type == "withdraw") ? "Withdraw" : (($row->transaction_type == "w_to_a") ? "Wallet To Account" : (($row->transaction_type == "a_to_w") ? "Account To Wallet" : ""))));
            $data[$i]['transaction_limit']  = ($row->min_transaction != 0 && $row->max_transaction != 0) ? ($row->min_transaction . " To " . $row->max_transaction . "") : "NA";
            $data[$i]['charge_type']        = $row->charge_type;
            $data[$i]['charge_limit']       = ($row->limit_start != 0 && $row->limit_end != 0) ? ($row->limit_start . " To " . $row->limit_end . "") : "NA";
            $data[$i]['kyc']                = ($row->kyc == 1) ? "Required" : "NA";
            $data[$i]['amount']             = $row->amount;
            $data[$i]['status']             = $row->permission;
            $data[$i]['active_status']      = ($row->active_status == 1) ? "<span class='badge badge-light-success' style='font-size:1rem;'>Active</span>" : "<span class='badge badge-light-danger' style='font-size:1rem;'>Disable</span>";
            $data[$i]['action']             = '<td>
                                                    <div class="dropdown">
                                                        <button type="button" class="btn btn-sm dropdown-toggle hide-arrow py-0" data-bs-toggle="dropdown">
                                                            <i data-feather="more-vertical"></i>
                                                        </button>
                                                        <div class="dropdown-menu dropdown-menu-end">
                                                            <a data-id="' . $row->id . '" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#finance-setting-edit-form" id="finance-setting-edit-button">
                                                                <i data-feather="edit-2" class="me-50"></i>
                                                                <span>Edit</span>
                                                            </a>
                                                            <a type="button" data-id="' . $row->id . '" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#finance-setting-delete-modal" id="finance-setting-delete-button">
                                                                <i data-feather="trash" class="me-50"></i>
                                                                <span>Delete</span>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </td>';
            $i++;
        }
        $output = array('draw' => $draw, 'recordsTotal' => $count, 'recordsFiltered' => $count);
        $output['data'] = $data;
        return Response::json($output);
    }

    // finance settings edit
    public function transactionSettingAdd(Request $request)
    {
        $validation_rules = [
            'amount' => 'required',
        ];
        // set transaction charge type 
        $charge_type = "";
        $fixed = $request->fixed;
        $percentage = $request->percentage;
        if ($fixed == "on") {
            $charge_type = "fixed";
        } else if ($percentage == "on") {
            $charge_type = "percentage";
        } else {
            $charge_type = "";
        }
        if ($charge_type == "") {
            if ($request->ajax()) {
                return Response::json(['status' => false, 'charge_type' => 'Charge type field is required!']);
            } else {
                return Redirect()->back()->with(['status' => false, 'charge_type' => 'Charge type field is required!']);
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
            $min_transaction = (isset($request->min_transaction)) ? $request->min_transaction : 0;
            $max_transaction = (isset($request->max_transaction)) ? $request->max_transaction : 0;
            $limit_start = (isset($request->limit_start)) ? $request->limit_start : 0;
            $limit_end = (isset($request->limit_end)) ? $request->limit_end : 0;
            $kyc = (isset($request->kyc) ? (($request->kyc == "on") ? 1 : 0) : 0);
            $amount = (isset($request->amount)) ? $request->amount : '';

            $add_finance = TransactionSetting::create([
                'transaction_type'  => strtolower($request->transaction_type),
                'min_transaction'   => $min_transaction,
                'max_transaction'   => $max_transaction,
                'charge_type'       => $charge_type,
                'limit_start'       => $limit_start,
                'limit_end'         => $limit_end,
                'kyc'               => $kyc,
                'amount'            => $amount,
                'permission'        => strtolower($request->permission),
                'active_status'     => $request->active_status,
            ]);
            if ($add_finance) {
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
        }
    }

    // finance settings delete
    public function financeSettingDelete(Request $request, $id)
    {
        $delete_finance = TransactionSetting::find($id)->delete();
        if ($delete_finance) {
            if ($request->ajax()) {
                return Response::json(['status' => true, 'message' => 'Successfully Deleted.']);
            } else {
                return Redirect()->back()->with(['status' => true, 'message' => 'Successfully Deleted.']);
            }
        } else {
            if ($request->ajax()) {
                return Response::json(['status' => false, 'message' => 'Failed To Delete!']);
            } else {
                return Redirect()->back()->with(['status' => false, 'message' => 'Failed To Delete!']);
            }
        }
    }

    // finance settings edit
    public function transactionSettingEdit(Request $request)
    {
        $id = $request->transaction_setting_id;
        $transaction_type = (isset($request->transaction_type)) ? strtolower($request->transaction_type) : '';
        // set transaction charge type 
        $charge_type = "";
        $fixed = $request->fixed;
        $percentage = $request->percentage;
        if ($fixed == "on") {
            $charge_type = "fixed";
        } else if ($percentage == "on") {
            $charge_type = "percentage";
        } else {
            $charge_type = "";
        }
        $min_transaction = (isset($request->min_transaction)) ? $request->min_transaction : 0;
        $max_transaction = (isset($request->max_transaction)) ? $request->max_transaction : 0;
        $limit_start = (isset($request->limit_start)) ? $request->limit_start : 0;
        $limit_end = (isset($request->limit_end)) ? $request->limit_end : 0;
        $kyc = (isset($request->kyc) ? (($request->kyc == "on") ? 1 : 0) : 0);
        $amount = (isset($request->amount)) ? $request->amount : '';
        // $charge = $request->charge;
        $edit_finance = TransactionSetting::where('id', $id)->update([
            'transaction_type'  => $transaction_type,
            'min_transaction'   => $min_transaction,
            'max_transaction'   => $max_transaction,
            'charge_type'       => $charge_type,
            'limit_start'       => $limit_start,
            'limit_end'         => $limit_end,
            'kyc'               => $kyc,
            'amount'            => $amount,
            'permission'        => strtolower($request->permission),
            'active_status'     => $request->active_status,
        ]);
        if ($edit_finance) {
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
    }

    // finance settings edit
    public function transactionSettingGetData(Request $request, $id)
    {
        $transaction_settings = TransactionSetting::where('id', $id)->first();
        if ($transaction_settings) {
            if ($request->ajax()) {
                return Response::json([
                    'status' => true,
                    'transaction_type' => $transaction_settings->transaction_type,
                    'min_transaction' => $transaction_settings->min_transaction,
                    'max_transaction' => $transaction_settings->max_transaction,
                    'charge_type' => $transaction_settings->charge_type,
                    'limit_start' => $transaction_settings->limit_start,
                    'limit_end' => $transaction_settings->limit_end,
                    'kyc' => $transaction_settings->kyc,
                    'amount' => $transaction_settings->amount,
                    'permission' => $transaction_settings->permission,
                    'active_status' => $transaction_settings->active_status,
                ]);
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
    }
    // software setting
    public function softwareSetting(Request $request)
    {
        // end company social media
        $create_meta_acc = ($request->create_meta_acc == "on") ? 1 : 0;
        $platform_book = (isset($request->platform_book)) ? strtolower($request->platform_book) : "";
        $social_account = ($request->social_account == "on") ? 1 : 0;
        $acc_limit = (isset($request->acc_limit)) ? $request->acc_limit : 0;
        $brute_force_attack = (isset($request->brute_force_attack)) ? $request->brute_force_attack : 0;

        $data = [
            'crm_type'           => strtolower($request->crm_type),
            'create_meta_acc'    => $create_meta_acc,
            'platform_book'      => $platform_book,
            'social_account'     => $social_account,
            'acc_limit'          => $acc_limit,
            'brute_force_attack' => $brute_force_attack,

        ];
        if (SystemConfig::where('id', $request->config_id)->update($data)) {
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
    }
}
