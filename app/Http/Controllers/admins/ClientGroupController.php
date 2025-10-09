<?php

namespace App\Http\Controllers\admins;

use App\Http\Controllers\Controller;
use App\Models\ClientGroup;
use App\Models\Leverage;
use App\Services\AllFunctionService;
use App\Services\DataTableService;
use App\Services\MT4API;
use App\Services\Mt5WebApi;
use Illuminate\Console\View\Components\Alert;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Request as FacadesRequest;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class ClientGroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        $this->middleware(["role:group manager"]);
        $this->middleware(["role:group list"]);
        $this->middleware(["role:group settings"]);
        if (request()->is('admin/client-groups/create')) {
            // system module control
            $this->middleware(AllFunctionService::access('group_settings', 'admin'));
            $this->middleware(AllFunctionService::access('group_manager', 'admin'));
        } elseif (request()->is('admin/client-groups')) {
            // system module control
            $this->middleware(AllFunctionService::access('group_settings', 'admin'));
            $this->middleware(AllFunctionService::access('group_list', 'admin'));
        } elseif (request()->is('admin/ib-groups')) {
            // system module control
            $this->middleware(AllFunctionService::access('group_settings', 'admin'));
            $this->middleware(AllFunctionService::access('manage_ib_group', 'admin'));
        } elseif (request()->is('/admin/client-groups/*')) {
            // system module control
            $this->middleware(AllFunctionService::access('group_settings', 'admin'));
            $this->middleware(AllFunctionService::access('group_list', 'admin'));
        }
    }
    public function index(Request $request)
    {
        $op = $request->op;
        if ($op == 'data-table') return $this->clientGroupDT($request);
        $leverages = Leverage::select()->orderby("leverage", "ASC")->get();
        return view('admins.group_settings.client_groups', ['leverages' => $leverages]);
    }

    private function clientGroupDT($request)
    {
        try {
            $dts = new DataTableService($request);
            $columns = $dts->get_columns();

            $result = ClientGroup::select()->where('visibility', '<>', 'deleted');
            $count = $result->count();

            //Search if columns field has search data
            $result = $result->where(function ($q) use ($dts, $columns) {
                if ($dts->search) {
                    foreach ($columns as $col) {
                        if ($col['data'] != 'responsive_id' && !empty($col['data'])) {
                            $tf = $col['data'];
                            $st = $dts->search;
                            $q->orWhere("client_groups.$tf", 'LIKE', '%' . $st . '%');
                        }
                    }
                }
            });

            $result = $result->orderBy($dts->orderBy(), $dts->orderDir)->skip($dts->start)->take($dts->length)->get();

            $data = array();
            foreach ($result as $row) {
                // $data[$i]['responsive_id'] = null;
                $visibility = '';
                if (strtolower($row->visibility) === 'visible') {
                    $visibility = '<span class="badge badge-success bg-success">Visible</span>';
                } else {
                    $visibility = '<span class="badge badge-danger bg-danger">' . ucwords($row->visibility) . '</span>';
                }
                // badge a book / b book
                $book = '';
                if (strtolower($row->book) === 'a book') {
                    $book = '<span class="badge badge-light-success bg--light-success">' . ucwords($row->book) . '</span>';
                } else {
                    $book = '<span class="badge badge-light-danger bg--light-danger">' . ucwords($row->book) . '</span>';
                }
                $data[] = [
                    'id' => $row->id,
                    "server" => strtoupper($row->server),
                    "book" => $book,
                    "group_id" => $row->group_id,
                    "group_name" => $row->group_name,
                    "max_leverage" => $row->max_leverage,
                    "min_deposit" => $row->min_deposit,
                    "visibility" => $visibility,
                ];
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

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $leverages = Leverage::select()->orderby("leverage", "ASC")->get();
        return view('admins.group_settings.create_client_group', compact('leverages'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request)
    {
        // check for validation. If validation fails it will return errors
        $rules = [
            'group_name' => 'required',
            'group_id' => 'required',
            'platform' => 'required',
            'account_category' => 'required',
            'leverage' => 'required',
            'book' => 'required',
            'min_deposit' => 'required',
            'deposit_type' => 'required',
        ];
        
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Response::json([
                'status'    => false,
                'errors'    => $validator->errors(),
                'message'   => 'Something went wrong, Please fix the following error!',
            ]);
        } else {
            $leverage = $request->leverage;
            $max_leverage = max($leverage);

            ClientGroup::create([
                'group_name'        => $request->group_name,
                'group_id'          => $request->group_id,
                'server'            => $request->platform,
                'account_category'  => $request->account_category,
                'book'              => $request->book,
                'min_deposit'       => $request->min_deposit,
                'deposit_type'      => $request->deposit_type,
                'leverage'          => json_encode($leverage),
                'max_leverage'      => $max_leverage,
                'created_by'        => auth()->user()->id,
                'visibility' => $request->visibility
            ]);
            return Response::json([
                'status'    => true,
                'message'   => 'New Client Group Successfully Created.'
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ClientGroup  $clientGroup
     * @return \Illuminate\Http\Response
     */
    public function show(ClientGroup $clientGroup)
    {
        //
    }
    // ------------------------------------------------------------------------------------
    //                              Edit Client Group
    // ------------------------------------------------------------------------------------
    public function get_edit_data(Request $request)
    {
        try {
            $result = ClientGroup::where('id', $request->group_id)->first();
            return Response::json([
                'status'            => true,
                'message'           => 'Client Successfully Updated.',
                'id'                => $result->id,
                'server'            => $result->server,
                'group_name'        => $result->group_name,
                'group_id'          => $result->group_id,
                'book'              => $result->book,
                'leverage'          => $result->leverage,
                'account_category'  => $result->account_category,
                'min_deposit'       => $result->min_deposit,
                'deposit_type'      => $result->deposit_type,
                'visibility'        => $result->visibility,

            ]);
        } catch (\Throwable $th) {
            // throw $th;
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ClientGroup  $clientGroup
     * @return \Illuminate\Http\Response
     */
    public function edit(ClientGroup $clientGroup)
    {
        $leverages = Leverage::select()->orderby("leverage", "ASC")->get();
        return view('admins.group_settings.edit_client_group', compact('clientGroup', 'leverages'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ClientGroup  $clientGroup
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        try {

            if ($request->ajax()) {
                $validator = Validator::make(
                    $request->all(),
                    [
                        'platform' => 'required',
                        'book' => 'required',
                        'group_name' => 'required',
                        'group_id' => 'required',
                        'account_category' => 'required',
                        'leverage' => 'required',
                        'min_deposit' => 'required',
                        'deposit_type' => 'required',
                    ],
                );


                if ($validator->fails()) {
                    return Response::json([
                        'status'    => false,
                        'message'   => 'Fix  the following error!',
                        'errors'    => $validator->errors(),
                    ]);
                }
                $update = ClientGroup::where('id', $request->id)->update([

                    'server' => $request->platform,
                    'book' => $request->book,
                    'group_name' => $request->group_name,
                    'group_id' => $request->group_id,
                    'account_category' => $request->account_category,
                    'leverage' => $request->leverage,
                    'min_deposit' => $request->min_deposit,
                    'deposit_type' => $request->deposit_type,
                    'visibility'   => $request->visibility,

                ]);

                if ($update) {
                    return Response::json([
                        'status'  => true,
                        'message' => 'Client group successfully update.',
                    ]);
                }
                return Response::json([
                    'status'  => false,
                    'message' => 'Something went wrong, Please try again later!',
                ]);
            }
        } catch (\Throwable $th) {
            throw $th;
            return Response::json([
                'status'  => false,
                'message' => 'Got a server error!',
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ClientGroup  $clientGroup
     * @return \Illuminate\Http\Response
     */
    public function destroy(ClientGroup $clientGroup)
    {
        $result = ClientGroup::where('id', $clientGroup->id)->update([
            'visibility' => 'deleted'
        ]);
        if ($result) {
            return [
                'status' => 'success',
                'msg' => 'Client Group Deleted Successfully'
            ];
        } else {
            return [
                'status' => 'failed',
                'msg' => 'Unable To Delete Client Group'
            ];
        }
    }

    public function getClientGroup(Request $request)
    {

        // common client group data
        $account_category = "live";
        $leverage = [1, 10, 20, 25, 50, 75, 100, 125, 150, 175, 200, 300, 400, 500];
        $leverage = json_encode($leverage);
        $max_leverage = 500;
        $book = "A Book";
        $min_deposit = 10;
        $deposit_type = "every_time";
        // get mt5 groups start:
        $mt5_api = new Mt5WebApi();
        $action = 'GroupGet';
        for ($i = 0; $i < 10; $i++) {
            $data = array(
                'Position' => 1,
            );
            $mt5_groups = $mt5_api->execute($action, $data);
            if ($mt5_groups['success']) {
                if (isset($mt5_groups['data']['Group'])) {
                    $get_groups = ClientGroup::where('group_name', $mt5_groups['data']['Group'])->first();
                    if (empty($get_groups)) {
                        $created_id = ClientGroup::create([
                            'group_name'        => $mt5_groups['data']['Group'],
                            'group_id'          => '',
                            'server'            => 'mt5',
                            'account_category'  => 'live',
                            'leverage'          => $leverage,
                            'max_leverage'      => $max_leverage,
                            'book'              => $book,
                            'min_deposit'       => $min_deposit,
                            'deposit_type'      => $deposit_type,
                        ])->id;
                    } else {
                        $i = 10;
                    }
                }
            } else {
                $i = 10;
            }
        }
        // get mt5 groups end:
        // dd($result);


        // get mt5 groups start:
        $mt4api = new MT4API();
        $data = array(
            'command' => 'groups_get',
        );
        $mt4_groups = $mt4api->execute($data, 'live');
        if (isset($mt4_groups['success'])) {
            $total_mt4_groups = count($mt4_groups['data']);
            $created_id = "";
            for ($i = 0; $i < $total_mt4_groups; $i++) {
                // echo $mt4_groups['data'][$i];
                $get_groups = ClientGroup::where('group_name', $mt4_groups['data'][$i])->first();
                if (empty($get_groups)) {
                    $created_id = ClientGroup::create([
                        'group_name'        => $mt4_groups['data'][$i],
                        'group_id'          => '',
                        'server'            => 'mt4',
                        'account_category'  => 'live',
                        'leverage'          => $leverage,
                        'max_leverage'      => $max_leverage,
                        'book'              => $book,
                        'min_deposit'       => $min_deposit,
                        'deposit_type'      => $deposit_type,
                    ])->id;
                }
            }
            // get mt5 groups end:

            if ($created_id) {
                return [
                    'status' => 'success',
                    'msg' => 'Client Group Updated Successfully.'
                ];
            } else {
                return [
                    'status' => 'success',
                    'msg' => 'Already Up To Date!'
                ];
            }
        } else {
            return [
                'status' => 'success',
                'msg' => 'Already Up To Date!'
            ];
        }
    }
}
