<?php

namespace App\Http\Controllers\Admins;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserDescription;
use App\Models\BankAccount;
use App\Models\ManagerUser;
use App\Models\Country;
use App\Models\CurrencySetup;
use App\Models\TradingAccount;
use App\Models\Withdraw;
use App\Services\AllFunctionService;
use Illuminate\Support\Facades\Response;
use App\Services\BankService;
use App\Services\MailNotificationService;
use App\Services\systems\AdminLogService;

class BankAccountListController extends Controller
{
    public function __construct()
    {
        $this->middleware(["role:bank account list"]);
        // $this->middleware(["role:ib management"]);
        // system module control
        $this->middleware(AllFunctionService::access('manage_banks', 'admin'));
        $this->middleware(AllFunctionService::access('bank_account_list', 'admin'));
    }
    public function bankAccountList()
    {
        $countries = Country::all();
        return view('admins.manage_banks.bank_account_list', ['countries' => $countries]);
    }
    // get bank account list 
    public function getBankAccountList(Request $request)
    {
        try {
            $columns = ['bank_ac_name','email','bank_ac_number','type','approve_status','bank_name','created_at','created_at'];
            $orderby = $columns[$request->order[0]['column']];
            // find all ib from ib table
            $columns = ['bank_ac_name','email','bank_ac_number','type','approve_status','bank_name','created_at','created_at'];
            $orderby = $columns[$request->order[0]['column']];
            $result = BankAccount::select(
                'bank_accounts.user_id',
                'bank_accounts.bank_name',
                'users.email',
                'users.type',
                'bank_accounts.bank_ac_name',
                'bank_accounts.bank_ac_number',
                'bank_accounts.bank_swift_code',
                'bank_accounts.approve_status',
                'bank_accounts.created_at',
                'bank_accounts.id as bank_id'

            )
                ->whereNot('status', 2)
                ->join('users', 'bank_accounts.user_id', '=', 'users.id');
            // ->join('trading_accounts','trading_accounts.user_id','=','bank_accounts.user_id');
            if (auth()->user()->type === 'manager') {
                $users_id = ManagerUser::where('manager_id', auth()->user()->id)->select('user_id')->get();
                $result = $result->whereIn('users.id', $users_id);
            }
            // Filter By Approved Status
            if ($request->approved_status != "") {
                $result = $result->where('approve_status', '=', $request->approved_status);
            }
            // Filter By Status (for notification links)
            if ($request->status != "") {
                if ($request->status == 'pending') {
                    $result = $result->where('approve_status', '=', 'p');
                } elseif ($request->status == 'approved') {
                    $result = $result->where('approve_status', '=', 'a');
                } elseif ($request->status == 'declined') {
                    $result = $result->where('approve_status', '=', 'd');
                }
            }
            //Filter By Trading Account Number
            if ($request->account_number != "") {
                $trader_account_number = TradingAccount::where('account_number', $request->account_number)->pluck('user_id')->first();
                $result = $result->where('bank_accounts.user_id', '=', $trader_account_number);
            }
            //Filter By Trader Name / Email /Phone /Country
            if ($request->trader_info != "") {
                $trader_info = $request->trader_info;
                $user_id = User::select('countries.name')->where('users.type', 4)->where(function ($query) use ($trader_info) {
                    $query->where('users.name', 'LIKE', '%' . $trader_info . '%')
                        ->orWhere('users.email', 'LIKE', '%' . $trader_info . '%')
                        ->orWhere('phone', 'LIKE', '%' . $trader_info . '%')
                        ->orWhere('countries.name', 'LIKE', '%' . $trader_info . '%');
                })->leftJoin('user_descriptions', 'users.id', '=', 'user_descriptions.user_id')
                    ->leftJoin('countries', 'user_descriptions.country_id', '=', 'countries.id')
                    ->select('users.id as client_id')->get()->pluck('client_id');
                $result = $result->whereIn('bank_accounts.user_id', $user_id);
            }
            //Filter By IB Name / Email /Phone /Country
            if ($request->ib_info != "") {
                $ib = $request->ib_info;
                $user_id = User::select('countries.name')->where('users.type', 4)->where(function ($query) use ($ib) {
                    $query->where('users.name', 'LIKE', '%' . $ib . '%')
                        ->orWhere('users.email', 'LIKE', '%' . $ib . '%')
                        ->orWhere('users.phone', 'LIKE', '%' . $ib . '%')
                        ->orWhere('countries.name', 'LIKE', '%' . $ib . '%');
                })->leftJoin('user_descriptions', 'users.id', '=', 'user_descriptions.user_id')
                    ->leftJoin('countries', 'user_descriptions.country_id', '=', 'countries.id')
                    ->select('users.id as client_id')->get()->pluck('client_id');
                $result = $result->whereIn('bank_accounts.user_id', $user_id);
            }
            //Filter By Bank Name or Account Number
            if ($request->bank_name_account != "") {
                $result = $result->where(function ($query) use ($request) {
                    $query->where('bank_accounts.bank_name', $request->bank_name_account)
                        ->orWhere('bank_accounts.bank_ac_number', $request->bank_name_account);
                });
            }
            //Filter By Request Date
            if ($request->from != "") {
                $result = $result->whereDate("bank_accounts.created_at", '>=', $request->from);
            }
            if ($request->to != "") {
                $result = $result->whereDate("bank_accounts.created_at", '<=', $request->to);
            }

            if ($request->account_name != "") {
                $result = $result->where('bank_ac_name', '=', $request->account_name);
            }
            //Filter By User Type
            if ($request->client_type != "") {
                $result = $result->where('users.type', '=', $request->client_type);
            }


            // filter end
            $count = $result->count();
            $recordsTotal = $count;
            $recordsFiltered = $count;

            $result = $result->orderby($orderby, $request->order[0]['dir'])->skip($request->start)->take($request->length)->get();

            $data = array();
            $i = 0;
            foreach ($result as $value) {
                if ($value->approve_status == 'p') {
                    $status = '<span class="bg-warning badge badge-warning">Pending</span>';
                }
                if ($value->approve_status == 'a') {
                    $status = '<span class="bg-success badge badge-success">Approved</span>';
                }
                if ($value->approve_status == 'd') {
                    $status = '<span class="bg-danger badge badge-danger">Declined</span>';
                }

                if ($value->type == 0) {
                    $type = '<span class="bg-light-success badge badge-light-success">Trader</span>';
                } else {
                    $type = '<span class="bg-light-warning badge badge-light-warning">IB</span>';
                }

                $data[] = [
                    "plus" => '<a href="#" data-id="' . $value->bank_id . '" data-bank_ac_number="' . $value->bank_ac_number . '" class="dt-description d-flex justify-content-between"><span class="w"> <i class="plus-minus" data-feather="plus"></i> </span></a>',
                    "account_name" => $value->bank_ac_name,
                    "email" => $value->email,
                    "account_number" => $value->bank_ac_number,
                    "client_type" => $type,
                    "status" => $status,
                    "bank_name" => $value->bank_name,
                    "date" => date('d M y, h:i A', strtotime($value->created_at)),
                ];
            }
            return Response::json([
                'draw' => $request->draw,
                'recordsTotal' => $recordsTotal,
                'recordsFiltered' => $recordsFiltered,
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


    // get bank account list description
    public function bankAccountListDescription(Request $request, $id)
    {
        // find bank user bank details
        $bank = BankAccount::select('*')->where('id', $id)->first();
        if (isset($bank->currency_id)) {
            $currency = CurrencySetup::find($bank->currency_id);
            $curr = $currency->currency;
        } else {
            $curr = "";
        }


        $multi_cur_visibility = BankService::is_multicurrency('all') ? "" : "d-none";

        if ($country = Country::where('id', $bank->bank_country)->exists()) {
            $country = Country::where('id', $bank->bank_country)->first();
            $country_name = $country->name;
        } else {
            $country_name = '';
        }
        $auth_user = User::find(auth()->user()->id);
        if ($auth_user->hasDirectPermission('edit bank account list')) {
            $editOrDeleteBtn = "";
            $approveOrDeclineBtn = "";
            $find_bank_account_transaction = Withdraw::where('bank_account_id', $bank->id)->first();
            // if account number has no transaction recored then it can be edited or deleted
            // edit or delete button 
            $editOrDeleteBtn = ' <button data-id="' . $bank->id . '" type="button" class="btn btn-primary waves-effect waves-float waves-light" data-bs-toggle="modal" data-bs-target="#bank-account-edit-modal" id="bank-account-edit-button">
                        <span class="align-middle">Edit</span>
                    </button>
                    <button data-id="' . $bank->id . '" type="button" class="btn btn-danger waves-effect waves-float waves-light" data-bs-toggle="modal" data-bs-target="#bank-account-delete-modal" id="bank-account-delete-button">
                        <span class="align-middle">Delete</span>
                    </button>';

            // approve or decline button
            if ($bank->approve_status === "p") {
                $approveOrDeclineBtn = '<span style="margin-left:5px;"><span><button data-id="' . $bank->id . '" data-status="a" type="button" class="btn btn-primary waves-effect waves-float waves-light" data-bs-toggle="modal" data-bs-target="#bank-account-approve-status-modal" id="bank-account-approve-status-button">
                                            <span class="align-middle">Approve</span>
                                        </button>
                                        <button data-id="' . $bank->id . '" data-status="d" type="button" class="btn btn-danger waves-effect waves-float waves-light" data-bs-toggle="modal" data-bs-target="#bank-account-approve-status-modal" id="bank-account-approve-status-button">
                                            <span class="align-middle">Decline</span>
                                        </button>';
            } else {
                $approveOrDeclineBtn = '';
            }
        } else {
            $editOrDeleteBtn = "";
            $approveOrDeclineBtn = "";
        }


        $description = '<tr class="description" style="display:none;">
            <td colspan="8">
                <div class="details-section-dark border-start-3 border-start-primary p-2 bg-light-secondary">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="rounded-0 w-100">
                                <div class="p-0">    
                                    <table class="table tbl-balanc tbl-bank-list mb-3">
                                        <tr>
                                            <th class="border-end-2 border-bottom-3">Bank Name</th>
                                            <td class="border-end-0 border-bottom-3">' . $bank->bank_name . '</td>
                                        </tr>
                                        <tr>
                                            <th class="border-end-2 border-bottom-3">Account Name</th>
                                            <td class="border-end-0 border-bottom-3">' . $bank->bank_ac_name . '</td>
                                        </tr>
                                        <tr>
                                            <th class="border-end-2 border-bottom-3">Account Number</th>
                                            <td class="border-end-0 border-bottom-3">' . $bank->bank_ac_number . '</td>
                                        </tr>
                                        <tr>
                                            <th class="border-end-2 border-bottom-3">' . BankService::swift_code_label($country_name) . '</th>
                                            <td class="border-end-0 border-bottom-3">' . $bank->bank_swift_code . '</td>
                                        </tr>
                                        
                                        <tr>
                                            <th class="border-end-2 border-bottom-3">Address</th>
                                            <td class="border-end-0 border-bottom-3">' . $bank->bank_address . '</td>
                                        </tr>
                                        <tr>
                                            <th class="border-end-2 border-bottom-3">Country</th>
                                            <td class="border-end-0 border-bottom-3">' . $country_name . '</td>
                                        </tr>
                                        <tr class="' . $multi_cur_visibility . '">
                                            <th class="border-end-2 border-bottom-3">Currency</th>
                                            <td class="border-end-0 border-bottom-3">' . $curr . '</td>
                                        </tr>
                                      
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="float-end">
                                ' . $editOrDeleteBtn . $approveOrDeclineBtn . '
                            </div>
                        </div>
                    </div>
                </div>
            </td>
            <td class="d-none">&nbsp;</td>
            <td class="d-none">&nbsp;</td>
            <td class="d-none">&nbsp;</td>
            <td class="d-none">&nbsp;</td>
        </tr>';
        $data = [
            'status' => true,
            'description' => $description,
        ];
        return Response::json($data);
    }

    // bank account delete
    public function bankAccountListDelete(Request $request, $id)
    {
        $delete_bank_account = BankAccount::where('id', $id)->update([
            'status' => 2
        ]);
        $banks = BankAccount::where('bank_accounts.id', $id)
            ->select('bank_accounts.*', 'users.type', 'users.name as client_name', 'users.email')
            ->join('users', 'bank_accounts.user_id', '=', 'users.id')
            ->first();
        $client_type = strtolower(($banks->type == 4) ? 'ib' : 'trader');
        if ($delete_bank_account) {
            MailNotificationService::admin_notification([
                'name' => $banks->client_name,
                'email' => $banks->email,
                'type' => 'bank delete',
                'client_type' => $client_type
            ]);
            $user = User::find($banks->user_id);
            activity("$client_type bank delete by admin")
                ->causedBy(auth()->user()->id)
                ->withProperties($request->all())
                ->event('bank delete')
                ->performedOn($user)
                ->log("The IP address " . request()->ip() . " has been deleted a bank");
            // end activity log-----------------
            return Response::json([
                'status' => true,
                'message' => 'Successfully Deleted.'
            ]);
        } else {
            return Response::json([
                'status' => false,
                'message' => 'Failed To Delete!'
            ]);
        }
    }
    // bank account approved / decline /
    public function bankAccountRequest(Request $request)
    {
        $update_bank_account = BankAccount::where('id', $request->id)->update([
            'approve_status' => $request->status,
            'admin_log'=>AdminLogService::admin_log()
        ]);
        if ($update_bank_account) {
            $status = '';
            if ($request->status === 'a') {
                $status = 'Approved';
            } elseif ($request->status === 'd') {
                $status = 'Declined';
            }
            $banks = BankAccount::where('bank_accounts.id', $request->id)
                ->select('bank_accounts.*', 'users.type', 'users.name as client_name', 'users.email')
                ->join('users', 'bank_accounts.user_id', '=', 'users.id')
                ->first();
            $client_type = strtolower(($banks->type == 4) ? 'ib' : 'trader');
            MailNotificationService::admin_notification([
                'name' => $banks->client_name,
                'email' => $banks->email,
                'type' => 'bank ' . strtolower($status),
                'client_type' => $client_type
            ]);
            $user = User::find($banks->user_id);
            activity("$client_type bank $status by admin")
                ->causedBy(auth()->user()->id)
                ->withProperties($request->all())
                ->event("bank $status")
                ->performedOn($user)
                ->log("The IP address " . request()->ip() . " has been $status a bank");
            // end activity log-----------------
            return Response::json([
                'success' => true,
                'message' => 'Successfully Updated.'
            ]);
        } else {
            return Response::json([
                'success' => false,
                'message' => 'Failed To Update!'
            ]);
        }
    }


    // get data bank account edit modal
    public function bankAccountEditModalFetchData(Request $request, $id)
    {
        $bank_account = BankAccount::where('id', $id)->first();

        $country = Country::all();
        $options = '';
        foreach ($country as $value) {
            $selected = ($value->id == $bank_account->bank_country) ? 'selected' : '';
            $options .= '<option value="' . $value->id . '" ' . $selected . '>' . $value->name . '</option>';
        }
        if ($bank_account) {
            if ($request->ajax()) {
                return Response::json([
                    'status' => true,
                    'bank_name'     => $bank_account->bank_name,
                    'bank_ac_name' => $bank_account->bank_ac_name,
                    'bank_ac_number' => $bank_account->bank_ac_number,
                    'bank_swift_code' => $bank_account->bank_swift_code,
                    'bank_iban' => $bank_account->bank_iban,
                    'bank_address' => $bank_account->bank_address,
                    'bank_country' => $options
                ]);
            }
        } else {
            if ($request->ajax()) {
                return Response::json(['status' => false, 'message' => 'Failed To Get Data!']);
            } else {
                return Redirect()->back()->with(['status' => false, 'message' => 'Failed To Get Data!']);
            }
        }
    }

    // bank account update modal action
    public function bankAccountEditModalUpdate(Request $request)
    {
        $id = $request->id;
        $bank_name = (isset($request->user_bank_name)) ? strtolower($request->user_bank_name) : '';
        $bank_ac_name = (isset($request->bank_ac_name)) ? strtolower($request->bank_ac_name) : '';
        $bank_ac_number = (isset($request->bank_ac_number)) ? strtolower($request->bank_ac_number) : '';
        $bank_swift_code = (isset($request->bank_swift_code)) ? strtolower($request->bank_swift_code) : '';
        $bank_iban = (isset($request->bank_iban)) ? strtolower($request->bank_iban) : '';
        $bank_address = (isset($request->bank_address)) ? strtolower($request->bank_address) : '';
        $bank_country = (isset($request->bank_country)) ? strtolower($request->bank_country) : '';

        $update_bank_account = BankAccount::where('id', $id)->update([
            'bank_name'         => $bank_name,
            'bank_ac_name'      => $bank_ac_name,
            'bank_ac_number'    => $bank_ac_number,
            'bank_swift_code'   => $bank_swift_code,
            'bank_iban'         => $bank_iban,
            'bank_address'      => $bank_address,
            'bank_country'      => $bank_country,
        ]);
        if ($update_bank_account) {
            $banks = BankAccount::where('bank_accounts.id', $id)
                ->select('bank_accounts.*', 'users.type', 'users.name as client_name', 'users.email')
                ->join('users', 'bank_accounts.user_id', '=', 'users.id')
                ->first();
            $client_type = strtolower(($banks->type == 4) ? 'ib' : 'trader');
            MailNotificationService::admin_notification([
                'name' => $banks->client_name,
                'email' => $banks->email,
                'type' => 'bank update',
                'client_type' => $client_type
            ]);
            $user = User::find($banks->user_id);
            activity("$client_type bank update by admin")
                ->causedBy(auth()->user()->id)
                ->withProperties($request->all())
                ->event('bank update')
                ->performedOn($user)
                ->log("The IP address " . request()->ip() . " has been update a bank");
            // end activity log-----------------
            return Response::json([
                'success' => true,
                'message' => 'Successfully Updated.'
            ]);
        } else {
            return Response::json([
                'success' => false,
                'message' => 'Failed To Update!'
            ]);
        }
    }
}
