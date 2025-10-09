<?php

namespace App\Http\Controllers\admins;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

use App\Models\AdminBank;
use App\Models\SoftwareSetting;
use App\Models\User;
use App\Services\AllFunctionService;
use App\Services\MailNotificationService;
use App\Services\systems\AdminLogService;

class BankAccountSetupController extends Controller
{
    public function __construct()
    {
        // system module control
        $this->middleware(AllFunctionService::access('settings', 'admin'));
        $this->middleware(AllFunctionService::access('bank_setting', 'admin'));
    }
    public function BankAccountSetup(Request $request)
    {
        $admin_bank = AdminBank::select('tab_selection')->where('status', 1)->get();
        return view('admins.bank_setup.bank_account_setup', ['admin_bank' => $admin_bank]);
    }


    public function AddBankAccountSetup(Request $request)
    {
        $process = $request->process;
        $bankAccountInfo = "";
        if ($process == 'add_bank_account') {
            $validation_rules = [
                'tab_selection'   => 'required',
                'account_name'    => 'required',
                'account_number'  => 'required',
                'minimum_deposit' => 'nullable|numeric'
            ];
            $validator = Validator::make($request->all(), $validation_rules);

            if ($validator->fails()) {
                if ($request->ajax()) {
                    return Response::json(['status' => false, 'message' => 'Fix the following error', 'errors' => $validator->errors()]);
                }
            } else {
                // Add or Edit
                $bankAccountInfo = AdminBank::updateOrCreate(
                    [
                        'id' => $request->id,
                    ],
                    [
                        'tab_selection'     => $request->tab_selection,
                        'tab_name'          => $request->tab_name,
                        'bank_name'         => $request->bank_name,
                        'account_name'      => $request->account_name,
                        'account_number'    => $request->account_number,
                        'swift_code'        => $request->code,
                        'ifsc_code'         => $request->code,
                        'routing'           => $request->routing ?? "",
                        'bank_country'      => $request->bank_country,
                        'bank_address'      => $request->bank_address,
                        'minimum_deposit'   => $request->minimum_deposit,
                        'currency_id'       => $request->currency,
                        'note'              => $request->note,
                        'status'            => 1,
                        'admin_log'         => AdminLogService::admin_log(),
                    ]
                );
            }

            $notifyMsg = null;
            if ($request->id == 0) {
                $notifyMsg = 'Bank Account Added Successfully';
            } else {
                $notifyMsg = 'Bank Account Updated Successfully';
            }

            $response['success'] = true;
            $response['banksetup'] = $bankAccountInfo;
            $response['message'] = $notifyMsg;
            // activity log
            if ($bankAccountInfo->wasRecentlyCreated) {
                // A new record was created
                $user = User::find(auth()->user()->id);
                activity("company bank added by admin")
                    ->causedBy(auth()->user()->id)
                    ->withProperties($request->all())
                    ->event('company bank added')
                    ->performedOn($user)
                    ->log("The IP address " . request()->ip() . " has been added a company bank");
                // end activity log-----------------
                MailNotificationService::admin_notification([
                    'name' => auth()->user()->name,
                    'email' => auth()->user()->email,
                    'type' => 'bank add',
                    'client_type' => 'admin'
                ]);
            } else {
                // An existing record was updated
                $user = User::find(auth()->user()->id);
                activity("company bank updated by admin")
                    ->causedBy(auth()->user()->id)
                    ->withProperties($request->all())
                    ->event('company bank updated')
                    ->performedOn($user)
                    ->log("The IP address " . request()->ip() . " has been updated a company bank");
                // end activity log-----------------
                MailNotificationService::admin_notification([
                    'name' => auth()->user()->name,
                    'email' => auth()->user()->email,
                    'type' => 'bank update',
                    'client_type' => 'admin'
                ]);
            }

            return Response::json($response);
        } else if ($process == 'tab_selection') {
            $tabSelectionValue = $_REQUEST['tab_value'];
            $tabExits = AdminBank::where('tab_selection', $tabSelectionValue)->where('status', 1)->first();
            if ($tabExits == null) {
                $tabExits['success'] = false;
                $tabExits['message'] = 'Tab data not existed';
            } else if ($tabExits != null) {
                $tabExits['success'] = true;
                $tabExits['message'] = 'Tab data exist';
            }
            return Response::json($tabExits);
        }
    }


    public function BankAccountSetupReport(Request $request)
    {
        $op = $request->input('op');
        if ($op == "data_table") {

            return $this->bankSetupTable($request);
        }
        return view('admins.bank_setup.bank_account_setup_report');
    }

    public function bankSetupTable(Request $request)
    {
        $condition = array();

        $search = !isset($_GET['export']) ? $_REQUEST["search"] : '';
        $order_a = !isset($_GET['export']) ? $_REQUEST['order'] : '';
        $order = !isset($_GET['export']) ? $order_a[0]['dir'] : 'asc';
        $oc = !isset($_GET['export']) ? $order_a[0]['column'] : 0;
        $ocd = !isset($_GET['export']) ? $_REQUEST['columns'][$oc]['data'] : 'id';
        $table = 'admin_banks';
        $start = 0;
        $take = 10;
        $result = AdminBank::select();

        if (!isset($_GET['export'])) {
            if ($_REQUEST["search"]["value"] != "") {
                $result = $result->where(function ($q) use ($condition, $table) {
                    $q->orWhere('admin_banks', 'LIKE', '%' . $condition . '%');
                });
            }
        }
        $count_row = $result->count();
        $recordsTotal = $result->count();
        $recordsFiltered = $count_row;
        $result = $result->orderBy('admin_banks.id', 'desc');
        $data = array();
        $i = 0;
        $result = $result->skip($start)->take($take)->get();

        foreach ($result as $user) {

            $details = '
            
            <table id="lead_report" class="datatables-ajax table table-responsive">
            <thead>
                <tr>
                    <th>Swift Code : </th>
                    <th>' . $user->swift_code . '</th>

                    <th>IFSC code : </th>
                    <th>' . $user->ifsc_code . '</th>
                    
                </tr>

                <tr>
                    <th>Routing :</th>
                    <th>' . $user->routing . '</th>

                    <th>Note : </th>
                    <th>' . $user->note . '</th>
                    
                </tr>
               
               
            </thead>
        
        </table> </br>';

            $data[$i]['tab_selection'] = $user->tab_selection;
            $data[$i]['tab_name'] = $user->tab_name;
            $data[$i]['bank_name'] = $user->bank_name;
            $data[$i]['account_name'] = $user->account_name;
            $data[$i]['account_number'] = $user->account_number;
            $data[$i]['country'] = $user->bank_country;
            $data[$i]['address'] = $user->bank_address;
            $data[$i]['currency'] = $user->currency_id;
            $data[$i]['deposit'] = $user->minimum_deposit;
            $data[$i]['action'] = '<div class="panel-footer" style="float: right;">
            <a  data-toggle="tooltip" type="button" onclick="clickEdit(this)" data-id =' . $user->id . '  data-tabselection =' . $user->tab_selection . ' id="editleadBtn"  data-bs-toggle="modal" data-bs-target="#updateBank"  class="text-warning pull-left"><i class="fa fa-pencil" aria-hidden="true"></i></a>
            <a  data-original-title="Edit this user" onclick="clickDeleteBtn(' . $user->id . ')"  data-toggle="tooltip" type="button"   id="deleteLeadBtn"  data-bs-toggle="modal" data-bs-target="#deleteBank" class="text-danger"><i class="fa fa-trash" aria-hidden="true"></i></a>
        </div>';

            $data[$i]["extra"]           = $details;
            $i++;
        }


        $output = array('draw' => $_REQUEST['draw'], 'recordsTotal' => $recordsTotal, 'recordsFiltered' => $recordsFiltered);
        $output['data'] = $data;
        return Response::json($output);
    }



    public function DeleteBankAccount(Request $request)
    {
        $id = $request->deletid;
        $deleteBank = AdminBank::where('id', $id)->update([
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
        if ($deleteBank) {
            $response['success'] = true;
            $response['message'] = 'Bank Delete successfully<br/>';
        }
        echo json_encode($response);
    }
    // add or remove admin bank tab
    public function addOrRemoveBankTab($action, $tab_selection)
    {
        $last_tab = AdminBank::latest()->first();
        if ($action == 'remove') {
            if ($tab_selection == "null") {
                $response['success'] = false;
                $response['message'] = 'Please Select A Tab To Remove.';
            } else {
                $removeBankTab = AdminBank::where('tab_selection', $tab_selection)->delete();
                if ($removeBankTab) {
                    $response['success'] = true;
                    $response['message'] = 'Tab Removed Successfully';
                }
            }
        } else {

            if(isset($last_tab->tab_selection) == null){
                $tab_selections = 0+1;
            }else{
                $tab_selections = $last_tab->tab_selection + 1;
            }
            $addBankTab = AdminBank::create([
                'tab_selection' => $tab_selections,
                'account_name' => "",
                'account_number' => "",
                'status' => 1,
            ]);
            if ($addBankTab) {
                $response['success'] = true;
                $response['message'] = 'Tab Added Successfully';
            }
        }
        echo json_encode($response);
    }
}
