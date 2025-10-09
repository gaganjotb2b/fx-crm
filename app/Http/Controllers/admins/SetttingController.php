<?php

namespace App\Http\Controllers\Admins;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Auth;
use App\Models\admin\SystemConfig;
use App\Models\TransactionSetting;
use App\Models\Symbol;
use App\Models\CopySymbol;
use App\Services\AllFunctionService;
use Carbon\Carbon;
use PhpParser\Builder\Trait_;

class SetttingController extends Controller
{
    public function __construct()
    {
        $this->middleware(["role:currency pair"]);
        $this->middleware(["role:settings"]);
        // system module control
        $this->middleware(AllFunctionService::access('settings', 'admin'));
        $this->middleware(AllFunctionService::access('currency_pair', 'admin'));
    }
    public function currencyPair()
    {
        return view('admins.settings.currency-pair');
    }
    // currency pair datatable
    public function currencyPairGetData(Request $request)
    {
        try {
            $symbol = $request->symbol;
            $title = $request->title;
            $ib_rebate = $request->ib_rebate;
            $active_status = $request->active_status;

            $count_row = Symbol::select()->count();

            $recordsTotal = $count_row;
            $recordsFiltered = $count_row;

            $symbols = Symbol::select();
            if ($symbol != "") {
                $symbols = $symbols->where('symbol', 'LIKE', '%' . $symbol . '%');
            }
            if ($title != "") {
                $symbols = $symbols->where('title', 'LIKE', '%' . $title . '%');
            }
            if ($ib_rebate != "") {
                $symbols = $symbols->where('ib_rebate', '=', $ib_rebate);
            }
            if ($active_status != "") {
                $symbols = $symbols->where('active_status', 'LIKE', '%' . $active_status . '%');
            }
            $count_row = $symbols->count();
            $recordsTotal = $count_row;
            $recordsFiltered = $count_row;
            $symbols = $symbols->orderBy('id', 'DESC')->get();
            $data = array();
            $serial = 1;
            $i = 0;
            foreach ($symbols as $row) {

                if (auth()->user()->hasDirectPermission('edit currency pair')) {
                    $custom_button = '  <a data-id="' . $row->id . '" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#currency-pair-edit-form" id="currency-pair-edit-modal-button">
                                    <i data-feather="edit-2" class="me-50"></i>
                                    <span>Edit</span>
                                    </a>
                                    <a data-id="' . $row->id . '" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#currency-pair-delete-form" id="currency-pair-delete-button">
                                        <i data-feather="trash" class="me-50"></i>
                                        <span>Delete</span>
                                    </a>';
                } else {
                    $custom_button = "<span class='text-danger'> No permission to access </span>";
                }

                $count_row = Symbol::select()->where('id', $row->id)->count();
                $data[$i]['serial'] = $serial++;
                $data[$i]['symbol'] = $row->symbol;
                $data[$i]['title'] = $row->title;
                $data[$i]['ib_rebate'] = strtoupper($row->ib_rebate);
                $data[$i]['active_status'] = ($row->active_status == 1) ? "Enable" : "Disable";
                $data[$i]['action'] = '<td>
                                        <div class="dropdown">
                                            <button type="button" class="btn btn-sm dropdown-toggle hide-arrow py-0" data-bs-toggle="dropdown">
                                                <i data-feather="more-vertical"></i>
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-end">
                                              ' . $custom_button . '
                                            </div>
                                        </div>
                                    </td>';
                $i++;
            }
            return Response::json([
                'draw' => $request->draw, 
                'recordsTotal' => $recordsTotal, 
                'recordsFiltered' => $recordsFiltered,
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
    // currency pair add
    public function currencyPairAdd(Request $request)
    {
        $validation_rules = [
            'symbol'    => 'required|unique:symbols',
            'title'     => 'required',
            'ib_rebate' => 'required',
        ];
        $validator = Validator::make($request->all(), $validation_rules);
        if ($validator->fails()) {
            if ($request->ajax()) {
                return Response::json(['status' => false, 'errors' => $validator->errors()]);
            } else {
                return Redirect()->back()->with(['status' => false, 'errors' => $validator->errors()]);
            }
        } else {
            $created_by = Auth::user()->id;
            $add_currency_pair = Symbol::create([
                'symbol'        => $request->symbol,
                'title'         => $request->title,
                'ib_rebate'     => $request->ib_rebate,
                'active_status' => $request->status,
                'created_by'    => $created_by,
            ]);
            if ($add_currency_pair) {
                if ($request->ajax()) {
                    return Response::json(['status' => true, 'message' => 'Successfully Added.']);
                } else {
                    return Redirect()->back()->with(['status' => true, 'message' => 'Successfully Added.']);
                }
            } else {
                if ($request->ajax()) {
                    return Response::json(['status' => false, 'message' => 'Failed To Add!']);
                } else {
                    return Redirect()->back()->with(['status' => false, 'message' => 'Failed To Add!']);
                }
            }
        }
    }
    // currency pair delete
    public function currencyPairDelete(Request $request, $id)
    {
        // Find the record
        $copySymbol = CopySymbol::find($id);
    
        // Check if record exists
        if (!$copySymbol) {
            return $request->ajax()
                ? Response::json(['status' => false, 'message' => 'Record not found!'])
                : Redirect()->back()->with(['status' => false, 'message' => 'Record not found!']);
        }
    
        // Attempt to delete the record
        try {
            $copySymbol->delete();
    
            return $request->ajax()
                ? Response::json(['status' => true, 'message' => 'Successfully Deleted.'])
                : Redirect()->back()->with(['status' => true, 'message' => 'Successfully Deleted.']);
        } catch (\Exception $e) {
            return $request->ajax()
                ? Response::json(['status' => false, 'message' => 'Failed to delete! ' . $e->getMessage()])
                : Redirect()->back()->with(['status' => false, 'message' => 'Failed to delete!']);
        }
    }

    // currency pair update modal fetch data
    public function currencyPairEditModalFetchData(Request $request, $id)
    {
        $symbol = Symbol::where('id', $id)->first();
        if ($symbol) {
            if ($request->ajax()) {
                return Response::json([
                    'status' => true,
                    'symbol' => $symbol->symbol,
                    'title' => $symbol->title,
                    'ib_rebate' => $symbol->ib_rebate,
                    'active_status' => $symbol->active_status,
                ]);
            }
        } else {
            if ($request->ajax()) {
                return Response::json(['status' => false, 'message' => 'Failed To Update!']);
            } else {
                return Redirect()->back()->with(['status' => false, 'message' => 'Failed To Update!']);
            }
        }
    }

    // currency pair update
    public function currencyPairEdit(Request $request)
    {
        $update_currency_pair = Symbol::where('id', $request->currency_pair_id)->update([
            'symbol'        => $request->symbol,
            'title'         => $request->title,
            'ib_rebate'     => $request->ib_rebate,
            'active_status' => $request->status,
        ]);
        if ($update_currency_pair) {
            if ($request->ajax()) {
                return Response::json(['success' => true, 'message' => 'Successfully Updated.']);
            } else {
                return Redirect()->back()->with(['success' => true, 'message' => 'Successfully Updated.']);
            }
        } else {
            if ($request->ajax()) {
                return Response::json(['success' => false, 'message' => 'Failed To Update!']);
            } else {
                return Redirect()->back()->with(['success' => false, 'message' => 'Failed To Update!']);
            }
        }
    }
}
