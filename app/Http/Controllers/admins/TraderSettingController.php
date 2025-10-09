<?php

namespace App\Http\Controllers\Admins;

use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use App\Models\KycRequired;
use App\Models\TraderSetting;
use App\Models\User;
use App\Services\AllFunctionService;
use App\Services\PermissionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class TraderSettingController extends Controller
{
    public function __construct()
    {
        if (request()->is('admin/trader-settings')) {
            $this->middleware(["role:trader setting"]);
            $this->middleware(["role:settings"]);

            // system module control
            $this->middleware(AllFunctionService::access('settings', 'admin'));
            $this->middleware(AllFunctionService::access('trader_settings', 'admin'));
        }
    }

    // view trader setting
    public function traderSetting()
    {
        $trader_settings = TraderSetting::all();
        $kyc_setup = KycRequired::select()->first();
        return view('admins.settings.trader_setting', ['trader_settings' => $trader_settings, 'kyc_setup' => $kyc_setup]);
    }
    // create trader settings
    // create all permission
    public function create_all(Request $request)
    {
        switch ($request->op) {
            case 'update':
                return Response::json(PermissionService::update_permission('trader', $request->id));
                break;

            default:
                return Response::json(PermissionService::creae_permission('trader'));
                break;
        }
    }

    // get all trader settings using dt
    public function trader_settings_dt(Request $request)
    {
        
        $result = TraderSetting::whereNull('parent_id');
        if (auth()->user()->type !== 'system') {
            $result = $result->where('system_disable', 0);
        }

        $count = $result->count(); // <------count total rows
        $result = $result->orderby('id', 'ASC')->skip($request->start)->take($request->length)->get();
        $data = array();
        $i = 0;
        foreach ($result as $value) {
            $childs = TraderSetting::where('parent_id', $value->id);
            if (auth()->user()->type !== 'system') {
                $childs = $childs->where('system_disable', 0);
            }
            $childs = $childs->get();
            $child_rows = '';
            foreach ($childs as $ch) {
                // check child active status
                $check_child = '';
                $check_child = ($ch->status) ? 'checked' : '';
                $child_rows .= '<div><div class="form-check form-switch">
                                    <input type="checkbox" class="switch-trader-settings form-check-input form-check-success" id="customSwitch' . $ch->id . '" value="' . $ch->id . '" ' . $check_child . '/>
                                    <label class="form-check-label" for="customSwitch' . $ch->id . '">' . $ch->settings . '</label>
                                </div></di>';
            }
            $parent_check = ($value->status) ? 'checked' : '';
            $data[$i]["action"]       = '<div class="col-2 col-sm-2 custom-switch column1">
                                            <div class="d-flex flex-column">
                                                <div class="form-switch form-check-success">
                                                    <input name="trader" id="customSwitch' . $value->id . '" type="checkbox" class="form-check-input switch-trader-settings" data-plugin-ios-switch  value="' . $value->id . '" ' . $parent_check . '/>
                                                    <label class="form-check-label" for="customSwitch' . $value->id . '">
                                                        <span class="switch-icon-left"><i data-feather="check"></i></span>
                                                        <span class="switch-icon-right"><i data-feather="x"></i></span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>';
            $data[$i]["title"]      = '<div class="column2">
                                            <div class="mb-1">
                                                <span class="text-primary">' . $value->settings . '</span>
                                                <hr>
                                                ' . $child_rows . '
                                            </div>
                                        </div>';
            $i++;
        }
        $output = array('draw' => $_REQUEST['draw'], 'recordsTotal' => $count, 'recordsFiltered' => $count);
        $output['data'] = $data;

        return Response::json($output);
    }
}
