<?php

namespace App\Http\Controllers\systems;

use App\Http\Controllers\Controller;
use App\Models\ClientGroup;
use App\Models\CopySymbol;
use App\Models\Traders\PammSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class SystemPammController extends Controller
{
    public function pammSetting()
    {
        $client_groups = ClientGroup::select('group_name', 'id')
            ->whereNot('visibility', 'deleted')->get();
        return view(
            'systems.configurations.pamm-setting',
            ['groups' => $client_groups]
        );
    }
    public function pammSettingProcess(Request $request)
    {


        $response['status'] = false;
        $response['message'] = 'Please fix the errors';

        $create = PammSetting::updateOrCreate([
            'profit_share_commission_value' => 1,
            'maximum_profit_share_value' => 1,
            'minimum_profit_share_value' => 1,
            'minimum_account_balance' => 1,
            'minimum_wallet_balance' => 1,
            'minimum_deposit' => 1,
            'pamm_account_limit' => 1,
            'slave_limit' => 1,
            'master_limit' => 1,
            'pamm_global_deposit' => 1,
            'profit_share_value' => 1,
            'pamm_requirement' => 1,
            'profit_share_commission_status' => 1,
            'pamm_requirement_status' => 1,
            'manual_approve_pamm_reg' => 1,
            'profit_share_commission' => 1,
            'flexible_profit_share_status' => 1,
            'profit_share_status' => 1,
            'global_pamm_status' => 1,
            'profit_share_margin_value' => 1,
        ]);

        if ($create) {
            $response['status'] = true;
            $response['message'] = 'PAMM Activated';
        }
        return $response;
    }

    public function addSymbol(Request $request)
    {

        try {
            $validation_rules = [
                'symbol_name' => 'required',
                'symbol_org' => 'required',
                'title' => 'required',
                'ib_rebate' => 'required',
                'client_group' => 'required',
                'visible' => 'required',
            ];
            $validator = Validator::make($request->all(), $validation_rules);
            // dd($validator);
            if ($validator->fails()) {
                return Response::json([
                    'status' => false,
                    'message' => 'Please fix the following errors!',
                    'error' => $validator->errors(),
                ]);
            }
            $group = ClientGroup::where('id', $request->client_group)->select('group_name', 'id')->first();
            $result = CopySymbol::updateOrCreate([
                'symbol' => $request->symbol_name,
                'symbol_org' => $request->symbol_org,
                'title' => $request->title,
                'comm' => '0.00',
                'ib_rebate' => $request->ib_rebate,
                'group_name' => $group->group_name,
                'group_id' => $group->id,
                'added_by' => auth()->user()->id,
                'visible' => $request->visible,
            ]);

            if ($result) {
                return Response::json([
                    'status' => true,
                    'message' => 'Symbol successfully updated'
                ]);
            }
            return Response::json([
                'status' => false,
                'message' => 'Got a server error, Try again later!',
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return Response::json([
                'status' => false,
                'message' => 'Got a server error, Try again later!',
            ]);
        }
    }


    public function SymbolTable(Request $request)
    {
        try {
            $columns = ['symbol', 'ib_rebate', 'group_name', 'added_by', 'visible', 'created_at', 'id'];
            $orderby = $columns[$request->order[0]['column']];

            $result = CopySymbol::select();
            /*<-------filter search script End here------------->*/
            $count = $result->count();
            $result = $result->orderby($orderby, $request->order[0]['dir'])->skip($request->start)->take($request->length)->get();
            $data = array();
            $i = 0;

            foreach ($result as $user) {
                   
                $data[$i]['symbol'] = $user->symbol;
                $data[$i]['ib_rebate'] = $user->ib_rebate;
                $data[$i]['group_name'] = $user->group_name;
                $data[$i]['added_by'] = $user->added_by;
                $data[$i]['visible'] = $user->visible;
                $data[$i]['date'] = date('d M y', strtotime($user->created_at));
                $data[$i]['action'] = '<td>
                                        <div class="dropdown">
                                            <button type="button" class="btn btn-sm dropdown-toggle hide-arrow py-0" data-bs-toggle="dropdown">
                                                <i data-feather="more-vertical"></i>
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-end">
                                              <a data-id="' . $user->id . '" class="dropdown-item d-none" data-bs-toggle="modal" data-bs-target="#currency-pair-edit-form" id="currency-pair-edit-modal-button">
                                                <i data-feather="edit-2" class="me-50"></i>
                                                <span>Edit</span>
                                                </a>
                                                <a data-id="' . $user->id . '" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#currency-pair-delete-form" id="currency-pair-delete-button">
                                                    <i data-feather="trash" class="me-50"></i>
                                                    <span>Delete</span>
                                                </a>

                                            </div>
                                        </div>
                                    </td>';

                $i++;
            }

            return Response::json([
                'draw' => $request->draw,
                'recordsTotal' => $count,
                'recordsFiltered' => $count,
                'data' => $data
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return Response::json([
                'draw' => $request->draw,
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => []
            ]);
        }
    }
}
