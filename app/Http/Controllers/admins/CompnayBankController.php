<?php

namespace App\Http\Controllers\admins;

use App\Http\Controllers\Controller;
use App\Models\AdminBank;
use App\Models\Country;
use App\Models\User;
use App\Services\AllFunctionService;
use App\Services\MailNotificationService;
use App\Services\systems\AdminLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class CompnayBankController extends Controller
{
    public function __construct()
    {

        // system module control
        $this->middleware(AllFunctionService::access('company_bank_list', 'admin'));
        $this->middleware(AllFunctionService::access('manage_banks', 'admin'));
    }
    public function index(Request $request)
    {
        $countries = Country::all();
        // call datatable
        if ($request->op == 'dt') {
            return Response::json($this->bank_list_dt($request));
        }
        return view('admins.manage_banks.company-bank-list', ['countries' => $countries]);
    }
    // make bank account list datatable
    public function bank_list_dt($request)
    {
        $draw = $request->input('draw');
        $start = $request->input('start');
        $length = $request->input('length');
        $order = $_GET['order'][0]["column"];
        $orderDir = $_GET["order"][0]["dir"];
        $columns = [
            'tab_selection',
            'tab_name',
            'bank_name',
            'account_name',
            'account_number',
            'bank_country',
            'minimum_deposit',
            'created_at'
        ];
        $orderby = $columns[$order];
        $result = AdminBank::select()->whereNot('status', 2)->whereNotNull('tab_name');
        // filter by status active
        if ($request->approved_status == '1') {
            $result = $result->where('status', $request->approved_status);
        }
        // filter by status disabled
        if ($request->approved_status == '0') {
            $result = $result->where('status', $request->approved_status);
        }
        // filter by account name
        if (trim($request->account_name) != "") {
            $result = $result->where('account_name', 'LIKE', '%' . $request->account_name . '%');
        }
        // filter by bank info
        if (trim($request->bank_info) != "") {
            $bank_info = $request->bank_info;
            $result = $result->where(function ($query) use ($bank_info) {
                $query->where('bank_name', 'LIKE', '%' . $bank_info . '%')
                    ->orwhere('account_number', 'LIKE', '%' . $bank_info, '%');
            });
        }
        // filter by min amount
        if ($request->min != "") {
            $result = $result->where('minimum_deposit', '>=', $request->min);
        }
        // filter by max amount
        if ($request->max != "") {
            $result = $result->where('minimum_deposit', '<=', $request->max);
        }
        // filter by swift code
        if (trim($request->swift_code) != "") {
            $result = $result->where('swift_code', $request->swift_code);
        }
        // filter by date from
        if ($request->from != "") {
            $result = $result->whereDate('created_at', '>=', date('Y-m-d', strtotime($request->from)));
        }
        // filter by date to
        if ($request->to != "") {
            $result = $result->whereDate('created_at', '<=', date('Y-m-d', strtotime($request->to)));
        }
        // count and get data
        $count = $result->count(); // <------count total rows
        $result = $result->orderby($orderby, $orderDir)->skip($start)->take($length)->get();
        $data = array();
        $i = 0;
        foreach ($result as $value) {
            $status_text = ($value->status == 1) ? 'text-success' : 'text-danger';
            if ($value->status == 1) {
                $btn_active = '<a class="dropdown-item btn-disable text-warning" href="#" data-id="' . $value->id . '">Disable</a>';
            } elseif ($value->status == 0) {
                $btn_active = '<a class="dropdown-item btn-active text-success" href="#" data-id="' . $value->id . '">Active</a>';
            }
            $data[$i]['tab'] = '<span class="' . $status_text . ' text-truncate">' . ucwords($value->tab_selection) . '</span>';
            $data[$i]['tab_name'] = ucwords($value->tab_name);
            $data[$i]['bank_name'] = ucwords($value->bank_name);
            $data[$i]['account_name'] = ucwords($value->account_name);
            $data[$i]['account_number'] = ucwords($value->account_number);
            $data[$i]['bank_country'] = ucwords($value->bank_country);
            $data[$i]['minimum_deposit'] = '$' . ucwords($value->minimum_deposit);
            $data[$i]["action"]    = '<div class="d-flex justify-content-between">
                                        <a href="#" class="more-actions dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i data-feather="more-vertical"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-end">
                                            <a class="dropdown-item text-danger btn-delete" href="#" data-id="' . $value->id . '">Delete</a>
                                            <a class="dropdown-item" href="' . route('admin.bank-account-setup') . '">Edit Tab</a>
                                            ' . $btn_active . '
                                        </div>
                                    </div>';
            $i++;
        }
        return ([
            'draw' => $draw,
            'recordsTotal' => $count,
            'recordsFiltered' => $count,
            'data' => $data
        ]);
    }
    // activate bank account
    public function active(Request $request)
    {
        $update = AdminBank::where('id', $request->id)->update([
            'status' => 1
        ]);
        if ($update) {
            return Response::json([
                'status' => true,
                'Bank account successfully activated'
            ]);
        }
        return Response::json([
            'status' => false,
            'Bank account activation failed'
        ]);
    }
    // disable bank account
    public function disable(Request $request)
    {
        $update = AdminBank::where('id', $request->id)->update([
            'status' => 0,
            'admin_log' => AdminLogService::admin_log(),
        ]);
        $user = User::find(auth()->user()->id);
        activity("company bank disable by admin")
            ->causedBy(auth()->user()->id)
            ->withProperties($request->all())
            ->event('company bank disable')
            ->performedOn($user)
            ->log("The IP address " . request()->ip() . " has been disable a company bank");
        // end activity log-----------------
        MailNotificationService::admin_notification([
            'name' => auth()->user()->name,
            'email' => auth()->user()->email,
            'type' => 'bank disable',
            'client_type' => 'admin'
        ]);
        if ($update) {
            return Response::json([
                'status' => true,
                'Bank account successfully disabled'
            ]);
        }
        return Response::json([
            'status' => false,
            'Bank account disable failed'
        ]);
    }
    // delete bank account
    public function delete(Request $request)
    {
        $update = AdminBank::where('id', $request->id)->update([
            'status' => 2,
            'admin_log' => AdminLogService::admin_log(),
        ]);
        $user = User::find(auth()->user()->id);
        activity("company bank delete by admin")
            ->causedBy(auth()->user()->id)
            ->withProperties($request->all())
            ->event('company bank delete')
            ->performedOn($user)
            ->log("The IP address " . request()->ip() . " has been delete a company bank");
        // end activity log-----------------
        MailNotificationService::admin_notification([
            'name' => auth()->user()->name,
            'email' => auth()->user()->email,
            'type' => 'bank delete',
            'client_type' => 'admin'
        ]);
        if ($update) {
            return Response::json([
                'status' => true,
                'Bank account successfully deleted'
            ]);
        }
        return Response::json([
            'status' => false,
            'Bank account delete failed'
        ]);
    }
}
