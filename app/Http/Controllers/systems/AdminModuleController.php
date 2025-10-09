<?php

namespace App\Http\Controllers\systems;

use App\Http\Controllers\Controller;
use App\Models\SystemModule;
use App\Services\PermissionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class AdminModuleController extends Controller
{
    public function index(Request $request)
    {
        return view('systems.configurations.system-module');
    }
    // create all permission
    public function create_all(Request $request)
    {
        switch ($request->op) {
            case 'update':
                return Response::json(PermissionService::update_permission('admin', $request->id));
                break;

            default:
                return Response::json(PermissionService::creae_permission('admin'));
                break;
        }
    }
    public function module_dts(Request $request)
    {

        $start = $request->input('start');
        $length = $request->input('length');
        $result = SystemModule::whereNull('parent_id');
        if (auth()->user()->type !== 'system') {
            $result = $result->where('system_disable', 0);
        }

        $count = $result->count(); // <------count total rows
        $result = $result->orderby('id', 'ASC')->skip($start)->take($length)->get();
        $data = array();
        $i = 0;
        foreach ($result as $value) {
            $childs = SystemModule::where('parent_id', $value->id);
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
                                    <label class="form-check-label" for="customSwitch' . $ch->id . '">' . $ch->module . '</label>
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
                                                <span class="text-primary">' . $value->module . '</span>
                                                <hr>
                                                ' . $child_rows . '
                                            </div>
                                        </div>';
            $i++;
        }

        return Response::json(
            [
                'draw' => $_REQUEST['draw'],
                'recordsTotal' => $count,
                'recordsFiltered' => $count,
                'data' => $data,
            ]
        );
    }
}
