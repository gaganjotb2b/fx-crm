<?php

namespace App\Http\Controllers\Admins;

use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\IbSetting;
use App\Models\TraderSetting;
use App\Services\AllFunctionService;
use App\Services\PermissionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class IbSettingController extends Controller
{
    public function __construct()
    {
        $this->middleware(["role:ib setting"]);
        $this->middleware(["role:settings"]);
        // system module control
        $this->middleware(AllFunctionService::access('settings', 'admin'));
        $this->middleware(AllFunctionService::access('ib_settings', 'admin'));
    }
    // update ib setting
    public function ibSetting()
    {
        $ib_settings = IbSetting::all();
        return view('admins.settings.ib_setting', ['ib_settings' => $ib_settings]);
    }

    // create trader settings
    // create all permission
    public function create_all(Request $request)
    {
        switch ($request->op) {
            case 'update':
                return Response::json(PermissionService::update_permission('ib', $request->id));
                break;

            default:
                return Response::json(PermissionService::creae_permission('ib'));
                break;
        }
    }
    // get all trader settings using dt
    public function ib_settings_dt(Request $request)
    {
        $draw = $request->input('draw');
        $start = $request->input('start');
        $length = $request->input('length');
        $order = $_GET['order'][0]["column"];
        $orderDir = $_GET["order"][0]["dir"];
        $columns = ['settings', 'created_at'];
        $orderby = $columns[$order];
        $result = ibSetting::whereNull('parent_id');
        if (auth()->user()->type !== 'system') {
            $result = $result->where('system_disable', 0);
        }

        $count = $result->count(); // <------count total rows
        $result = $result->orderby($orderby, $orderDir)->skip($start)->take($length)->get();
        $data = array();
        $i = 0;
        foreach ($result as $value) {
            $childs = ibSetting::where('parent_id', $value->id);
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
