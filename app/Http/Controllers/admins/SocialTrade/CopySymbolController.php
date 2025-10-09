<?php

namespace App\Http\Controllers\admins\SocialTrade;

use App\Http\Controllers\Controller;
use App\Models\ClientGroup;
use App\Models\CopySymbol;
use App\Services\AllFunctionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class CopySymbolController extends Controller
{
    public function __construct()
    {
        // $this->middleware(["role:support"]);
        // $this->middleware(["role:client ticket"]);
        // system module control
        $this->middleware(AllFunctionService::access('settings', 'admin'));
        $this->middleware(AllFunctionService::access('copy_symbols', 'admin'));
    }
    // 
    public function copy_symbol(Request $request)
    {

        $client_groups = ClientGroup::select('group_name', 'id')
            ->whereNot('visibility', 'deleted')->get();
        return view(
            'admins.socialTrade.copy-symbol',
            ['groups' => $client_groups]
        );
    }


    public function SymbolTable(Request $request)
    {
        try {
            $columns = ['symbol', 'ib_rebate', 'group_name', 'added_by', 'visible', 'created_at'];
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
                                              <a data-id="' . $row->id . '" class="dropdown-item d-none" data-bs-toggle="modal" data-bs-target="#currency-pair-edit-form" id="currency-pair-edit-modal-button">
                                                <i data-feather="edit-2" class="me-50"></i>
                                                <span>Edit</span>
                                                </a>
                                                <a data-id="' . $row->id . '" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#currency-pair-delete-form" id="currency-pair-delete-button">
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
            throw $th;
            return Response::json([
                'draw' => $request->draw,
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => []
            ]);
        }
    }
}
