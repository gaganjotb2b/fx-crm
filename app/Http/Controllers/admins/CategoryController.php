<?php

namespace App\Http\Controllers\admins;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Services\DataTableService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware(["role:category manager"]);
    }
    public function index(Request $request)
    {
        $op = $request->op;
        if ($op == 'data-table') return $this->categoryReportDT($request);
        return view('admins.categories.index');
    }

    private function categoryReportDT($request)
    {
        try {
            $dts = new DataTableService($request);
            $columns = $dts->get_columns();

            $result = Category::select();
            $count = $result->count();

            //Search if columns field has search data
            $result = $result->where(function ($q) use ($dts, $columns) {
                foreach ($columns as $col) {
                    if ($col['search']['value']) {
                        $tf = $col['data'];
                        $st = $col['search']['value'];
                        $q->orWhere("categories.$tf", 'LIKE', '%' . $st . '%');
                    }
                }

                //Add search if search have value
                if ($dts->search) {
                    if (is_numeric($dts->search)) {
                        $q->orWhere("categories.id", 'LIKE', '%' . ($dts->search - 100) . '%');
                    }

                    $q->orWhere("categories.client_type", 'LIKE', '%' . $dts->search . '%');
                    $q->orWhere("categories.name", 'LIKE', '%' . $dts->search . '%');
                    $q->orWhere("categories.priority", 'LIKE', '%' . $dts->search . '%');
                    $q->orWhere("categories.created_at", 'LIKE', '%' . $dts->search . '%');
                }
            });
            $result = $result->orderBy($dts->orderBy(), $dts->orderDir)->skip($dts->start)->take($dts->length)->get();

            $data = array();
            $i = 0;
            foreach ($result as $row) {
                $data[$i]['responsive_id'] = null;
                $data[$i]['id'] = $row->id;
                $data[$i]["client_type"] = ucwords($row->client_type);
                $data[$i]["name"] = ucwords($row->name);
                $data[$i]["priority"] = ($row->priority == 1) ? 'Normal' : (($row->priority == 2) ? 'Important' : 'Very Important');
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
     * @param  \App\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            'name' => 'required',
            'client_type' => 'required',
            'priority' => 'required'
        ];
        $request->validate($rules);
        $newCategory = Category::create($request->all());
        if ($newCategory) {
            // insert activity-----------------
            activity("add new category")
                ->causedBy(auth()->user()->id)
                ->withProperties($request->all())
                ->event("category add")
                ->log("The IP address " . request()->ip() . " has been add new category");
            // end activity log-----------------
            return [
                'status' => 'success',
                'msg' => 'Category Created Successfully'
            ];
        } else {
            return [
                'status' => 'failed',
                'msg' => 'Unable to create new category'
            ];
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Category $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        return json_encode($category);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Category $category
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Request $request
     * @param  \App\Models\Category $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        $rules = [
            'name' => 'required',
            'client_type' => 'required',
            'priority' => 'required'
        ];
        $request->validate($rules);
        $update = $category->update($request->all());
        if ($update) {
            // insert activity-----------------
            activity("category updated")
                ->causedBy(auth()->user()->id)
                ->withProperties($request->all())
                ->event("category update")
                ->log("The IP address " . request()->ip() . " has been update category");
            // end activity log-----------------
            return [
                'status' => 'success',
                'msg' => 'Category Updated Successfully'
            ];
        } else {
            return [
                'status' => 'failed',
                'msg' => 'Unable To Update Category'
            ];
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        $result = $category->delete();
        if ($result) {
            return [
                'status' => 'success',
                'msg' => 'Category Deleted Successfully'
            ];
        } else {
            return [
                'status' => 'failed',
                'msg' => 'Unable To Delete Category'
            ];
        }
    }
}
