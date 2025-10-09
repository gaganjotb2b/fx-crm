<?php

namespace App\Http\Controllers\admins;

use App\Http\Controllers\Controller;
use App\Models\ClientGroup;
use App\Services\DataTableService;
use Illuminate\Http\Request;

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
    }
    public function index(Request $request)
    {
        $op = $request->op;
        if ($op == 'data-table') return $this->clientGroupDT($request);
        return view('admins.group_settings.client_groups');
    }

    private function clientGroupDT($request)
    {
        $dts = new DataTableService($request);
        $columns = $dts->get_columns();

        $result = ClientGroup::select();
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
        $i = 0;
        foreach ($result as $row) {
            $data[$i]['responsive_id'] = null;
            $data[$i]['id'] = $row->id;
            $data[$i]["server"] = $row->server;
            $data[$i]["book"] = $row->book;
            $data[$i]["group_id"] = $row->group_id;
            $data[$i]["group_name"] = $row->group_name;
            $data[$i]["account_category"] = $row->account_category;
            $data[$i]["max_leverage"] = $row->max_leverage;
            $data[$i]["min_deposit"] = $row->min_deposit;
            $i++;
        }
        $res['draw'] = $dts->draw;
        $res['recordsTotal'] = $count;
        $res['recordsFiltered'] = $count;
        $res['data'] = $data;
        return json_encode($res);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admins.group_settings.create_client_group');
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
            'server' => 'required',
            'account_category' => 'required',
            'max_leverage' => 'required',
            'book' => 'required',
            'min_deposit' => 'required',
            'deposit_type' => 'required',
        ];
        $request->validate($rules);
        // store new client group
        $newClientGroup = ClientGroup::create($request->all());
        if ($newClientGroup) {
            // add leverage to the new data
            $leverage = [1];
            $i = 25;
            while ($i <= $request->max_leverage) {
                if ($i == 400) $i = 500;
                array_push($leverage, $i);
                $i *= 2;
            }
            $newClientGroup->update([
                'leverage' => json_encode($leverage)
            ]);
            return [
                'status' => 'success',
                'msg' => 'Client Group Created Successfully'
            ];
        } else {
            return [
                'status' => 'failed',
                'msg' => 'Unable To Create New Client Group'
            ];
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

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ClientGroup  $clientGroup
     * @return \Illuminate\Http\Response
     */
    public function edit(ClientGroup $clientGroup)
    {
        return view('admins.group_settings.edit_client_group', compact('clientGroup'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ClientGroup  $clientGroup
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ClientGroup $clientGroup)
    {
        // check for validation. If validation fails it will return errors
        $rules = [
            'group_name' => 'required',
            'group_id' => 'required',
            'server' => 'required',
            'account_category' => 'required',
            'max_leverage' => 'required',
            'book' => 'required',
            'min_deposit' => 'required',
            'deposit_type' => 'required',
        ];
        $request->validate($rules);
        // update client group
        $update = $clientGroup->update($request->all());
        if ($update) {
            // add leverage to the new data
            $leverage = [1, 10, 20, 25];
            $i = 50;
            while ($i <= $request->max_leverage) {
                array_push($leverage, $i);
                $i += 25;
            }
            $clientGroup->update([
                'leverage' => json_encode($leverage)
            ]);
            return [
                'status' => 'success',
                'msg' => 'Client Group Updated Successfully'
            ];
        } else {
            return [
                'status' => 'failed',
                'msg' => 'Unable To Update Client Group'
            ];
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
        $result = $clientGroup->delete();
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
}
