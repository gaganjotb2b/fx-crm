<?php

namespace App\Http\Controllers\admins;

use App\Http\Controllers\Controller;
use App\Models\IbGroup;
use App\Services\DataTableService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class IbGroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $op = $request->op;
        if ($op == 'data-table') return $this->ibGroupDT($request);
        return view('admins.group_settings.ib_groups');
    }

    private function ibGroupDT($request)
    {
        try {
            $dts = new DataTableService($request);
            $columns = $dts->get_columns();

            $result = IbGroup::select();
            $count = $result->count();

            //Search if columns field has search data
            $result = $result->where(function ($q) use ($dts, $columns) {
                foreach ($columns as $col) {
                    if ($col['search']['value']) {
                        $tf = $col['data'];
                        $st = $col['search']['value'];
                        $q->orWhere("ib_groups.$tf", 'LIKE', '%' . $st . '%');
                    }
                }

                //Add search if search have value
                if ($dts->search) {
                    if (is_numeric($dts->search)) {
                        $q->orWhere("ib_groups.id", 'LIKE', '%' . ($dts->search - 100) . '%');
                    }
                    $q->orWhere("ib_groups.group_name", 'LIKE', '%' . $dts->search . '%');
                    $q->orWhere("ib_groups.status", 'LIKE', '%' . $dts->search . '%');
                    $q->orWhere("ib_groups.created_at", 'LIKE', '%' . $dts->search . '%');
                }
            });

            $result = $result->orderBy($dts->orderBy(), $dts->orderDir)->skip($dts->start)->take($dts->length)->get();
            // dd($count);

            $data = array();
            $i = 0;
            foreach ($result as $row) {
                $data[$i]['responsive_id'] = null;
                $data[$i]['id'] = $row->id;
                $data[$i]["group_name"] = $row->group_name;
                $data[$i]["status"] = $row->status ? 'Active' : 'Deactive';
                $data[$i]["created_at"] = date('d F y, h:i A', strtotime($row->created_at));
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

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            'group_name' => 'required'
        ];
        $request->validate($rules);
        $newIbGroup = IbGroup::create($request->all());
        if ($newIbGroup) {
            return [
                'status' => 'success',
                'msg' => 'Ib Group Created Successfully'
            ];
        } else {
            return [
                'status' => 'failed',
                'msg' => 'Unable To Create New Ib Group'
            ];
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\IbGroup  $ibGroup
     * @return \Illuminate\Http\Response
     */
    public function show(IbGroup $ibGroup)
    {
        return json_encode($ibGroup);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\IbGroup  $ibGroup
     * @return \Illuminate\Http\Response
     */
    public function edit(IbGroup $ibGroup)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\IbGroup  $ibGroup
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, IbGroup $ibGroup)
    {
        $rules = [
            'group_name' => 'required'
        ];
        $request->validate($rules);
        $update = $ibGroup->update($request->all());
        if ($update) {
            return [
                'status' => 'success',
                'msg' => 'Ib Group Updated Successfully'
            ];
        } else {
            return [
                'status' => 'failed',
                'msg' => 'Unable To Update Ib Group'
            ];
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\IbGroup  $ibGroup
     * @return \Illuminate\Http\Response
     */
    public function destroy(IbGroup $ibGroup)
    {
        $result = $ibGroup->delete();
        if ($result) {
            return [
                'status' => 'success',
                'msg' => 'Ib Group Deleted Successfully'
            ];
        } else {
            return [
                'status' => 'failed',
                'msg' => 'Unable To Delete Ib Group'
            ];
        }
    }
}
