<?php

namespace App\Http\Controllers\traders;

use App\Http\Controllers\Controller;
use App\Mail\common\Notification;
use App\Models\admin\SystemConfig;
use App\Models\BankAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Country;
use App\Models\CurrencySetup;
use App\Models\SoftwareSetting;
use App\Models\Withdraw;
use App\Services\AllFunctionService;
use App\Services\DataTableService;
use Illuminate\Support\Facades\Auth;
use App\Services\BankService;
use App\Services\MailNotificationService;
use App\Services\systems\AdminLogService;
use App\Services\systems\NotificationService;

class UserBankingController extends Controller
{
    public function __construct()
    {
        $this->middleware(AllFunctionService::access('banking', 'trader'));
        $this->middleware(AllFunctionService::access('my_admin', 'trader'));
    }
    public function userBanking()
    {
        $countries = Country::all();
        return view('traders.my-admin.user-banking', ['countries' => $countries]);
    }

    //user bank account add
    public function bankAccountAdd(Request $request)
    {
        try {
            $validation_rules = [
                // 'bank_name'      => 'required',
                'account_name'   => 'required',
                'account_number' => 'required'
            ];

            // $software_settings = SoftwareSetting::select('is_multicurrency')->first();
            // if ($software_settings) {
            //     if ($software_settings->is_multicurrency == 1) {
            //         $validation_rules += [
            //             'currency'  => 'required'
            //         ];
            //     }
            // }

            $validator = Validator::make($request->all(), $validation_rules);
            if ($validator->fails()) {
                if ($request->ajax()) {
                    return Response::json([
                        'status' => false,
                        'message' => 'Fix The Following Error!',
                        'errors' => $validator->errors()
                    ]);
                } else {
                    return Redirect()->back()->with([
                        'status' => false,
                        'message' => 'Fix The Following Error!',
                        'errors' => $validator->errors()
                    ]);
                }
            } else {
                // $swift_code = (isset($request->code) ? $request->code : "");
                $bank_address = (isset($request->bank_address) ? $request->bank_address : "");
                $country = (isset($request->country) ? $request->country : 1);

                $unique_bank_account = BankAccount::where('bank_ac_number', $request->account_number)->where('user_id', auth()->user()->id)->first();

                if (empty($unique_bank_account)) {
                    $data = [
                        'user_id'           => Auth()->user()->id,
                        'bank_name'         => $request->bank_name,
                        'bank_ac_number'    => $request->account_number,
                        'bank_ac_name'      => $request->account_name,
                        'bank_swift_code'   => $request->code ?? "",
                        'bank_iban'         => $request->account_number,
                        'bank_address'      => $bank_address,
                        'currency_id'       => $request->currency,
                        'bank_country'      => $country
                    ];
                    $add_bank = BankAccount::create($data);
                    if ($add_bank) {
                        // insert activity-----------------
                        $user = User::find(auth()->user()->id);
                        activity("Trader bank add")
                            ->causedBy(auth()->user()->id)
                            ->withProperties($request->all())
                            ->event('bank add')
                            ->performedOn($user)
                            ->log("The IP address " . request()->ip() . " has been added new bank");
                        // end activity log-----------------
                        // send software notification
                        NotificationService::system_notification([
                            'type' => 'client_bank_add',
                            'user_type' => 'trader',
                            'user_id' => auth()->user()->id,
                            'table_id' => $add_bank->id,
                            'category' => 'client'
                        ]);
                        // send mail notification to admin / manager
                        $notification_status = MailNotificationService::admin_notification([
                            'name' => auth()->user()->name,
                            'email' => auth()->user()->email,
                            'type' => 'bank add',
                            'client_type' => 'trader'
                        ]);

                        if (!$notification_status) {
                            return Response::json([
                                'success' => true,
                                'message' => 'Bank successfully added, mail sending failed!'
                            ]);
                        }

                        return Response::json([
                            'success' => true,
                            'message' => 'Successfully Added.'
                        ]);
                    }
                    return Response::json([
                        'success' => false,
                        'message' => 'Failed To Add!'
                    ]);
                }
                return Response::json([
                    'success' => false,
                    'message' => 'Account Number already take for you!'
                ]);
            }
        } catch (\Throwable $th) {
            throw $th;
            return Response::json([
                'success' => false,
                'message' => 'Got a server error!'
            ]);
        }
    }

    // user bank account fetch data 
    public function userBankingFetchData(Request $request)
    {
        $dts = new DataTableService($request);
        $columns = $dts->get_columns();

        $result = BankAccount::where('user_id', auth()->user()->id)->whereNot('status', 2);

        //Search if columns field has search data
        $result = $result->where(function ($q) use ($dts, $columns) {
            if ($dts->search) {
                foreach ($columns as $col) {
                    if ($col['data'] != 'id' && !empty($col['data'])) {
                        $tf = $col['data'] == 'created_at' ? 'created_at' : $col['data'];
                        $st = $dts->search;
                        $q->orWhere($tf, 'LIKE', '%' . $st . '%');
                    }
                }
            }
        });
        $count = $result->count();
        $result = $result->orderBy($dts->orderBy(), $dts->orderDir)->skip($dts->start)->take($dts->length)->get();
        $data = array();
        $i = 0;
        foreach ($result as $value) {
            $country = Country::where('id', $value->bank_country)->first();

            $editOrDeleteBtn = "";
            $find_bank_account_transaction = Withdraw::where('bank_account_id', $value->id)->first();
            $editOrDeleteBtn = '<button data-id="' . encrypt($value->id) . '" type="button" class="btn btn-primary float-end" data-bs-toggle="modal" data-bs-target="#bank-account-list-edit-modal" id="bank-account-list-edit-button">
                                        <span class="align-middle">Edit</span>
                                    </button>
                                    <button data-id="' . encrypt($value->id) . '" type="button" class="btn bg-gradient-secondary float-end btn-delete-bank me-3" id="bank-account-list-delete-button">
                                        <span class="align-middle">Delete</span>
                                    </button>';
            $currency_setup = "";
            $software_settings = SoftwareSetting::select('is_multicurrency')->first();
            if ($software_settings) {
                if ($software_settings->is_multicurrency == 1) {
                    $currency_setup = CurrencySetup::where('id', $value->currency_id)->first();
                }
            }
            $multi_cur_visibility = BankService::is_multicurrency('all') ? "" : "d-none";


            $details = '<table class="w-100">
                <tr class="description bg-gray-100">
                    <td colspan="8" style="border-left: 3px solid #f7fafc !important;">
                        <div class="details-section-dark border-start-3 border-start-primary p-2 bg-light-secondary">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="card rounded-0 w-100">
                                        <div class="card-body p-0">    
                                            <table class="table table-responsive tbl-balance">
                                                <tr>
                                                    <th class="td-font td-border-bottom td-border-right w-50">Bank Name</th>
                                                    <td class="td-font td-border-bottom">' . (isset($value->bank_name) ? $value->bank_name : "") . '</td>
                                                </tr>
                                                <tr>
                                                    <th class="td-font td-border-bottom td-border-right w-50">Account Name</th>
                                                    <td class="td-font td-border-bottom">' . (isset($value->bank_ac_name) ? $value->bank_ac_name : "") . '</td>
                                                </tr>
                                                <tr>
                                                    <th class="td-font td-border-bottom td-border-right w-50">Account Number</th>
                                                    <td class="td-font td-border-bottom">' . (isset($value->bank_ac_number) ? $value->bank_ac_number : "") . '</td>
                                                </tr>
                                                <tr>
                                                    <th class="td-font td-border-bottom td-border-right w-50">' . BankService::swift_code_label(isset($country->name) ? $country->name : "") . '</th>
                                                    <td class="td-font td-border-bottom">' . (isset($value->bank_swift_code) ? $value->bank_swift_code : "") . '</td>
                                                </tr>
                                                <tr>
                                                    <th class="td-font td-border-bottom td-border-right w-50">Address</th>
                                                    <td class="td-font td-border-bottom">' . (isset($value->bank_address) ? $value->bank_address : "") . '</td>
                                                </tr>
                                                <tr>
                                                    <th class="td-font td-border-bottom td-border-right w-50">Country</th>
                                                    <td class="td-font td-border-bottom td-border-right">' . (isset($country->name) ? $country->name : "") . '</td>
                                                </tr>
                                                <tr class="' . $multi_cur_visibility . '">
                                                    <th class="td-font td-border-bottom td-border-right w-50">Currency</th>
                                                    <td class="td-font td-border-bottom td-border-right">' . ($currency_setup->currency ?? "---") . '</td>
                                                </tr>
                                            </table>
                                            <div class="col-lg-12 p-2">
                                                ' . $editOrDeleteBtn . '
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </td>
                    <td class="d-none">&nbsp;</td>
                    <td class="d-none">&nbsp;</td>
                    <td class="d-none">&nbsp;</td>
                    <td class="d-none">&nbsp;</td>
                </tr>
            </table>';
            $app_status = '';
            $approve_status = (($value->approve_status == 'a') ? '<span class="badge badge-success text-dark td-font text-capitalize">approved</span>' : (($value->approve_status == 'p') ? '<span class="badge badge-warning text-dark td-font text-capitalize">pending</span>' : (($value->approve_status == 'd') ? '<span class="badge badge-info text-dark td-font text-capitalize">declined</span>' : '')));
            $data[$i]["bank_name"] = $value->bank_name;
            $data[$i]["bank_ac_number"] = $value->bank_ac_number;
            $data[$i]["status"] = $approve_status;
            $data[$i]["created_at"]  = date('d M, Y H:i:s A', strtotime($value->created_at));
            $data[$i]["extra"]  = $details;
            $i++;
        }
        $res['draw'] = $dts->draw;
        $res['recordsTotal'] = $count;
        $res['recordsFiltered'] = $count;
        $res['data'] = $data;
        return json_encode($res);
    }

    // bank account delete
    public function bankAccountListDelete(Request $request)
    {
        $delete_bank_account = BankAccount::where('id', decrypt($request->id))->update([
            'status' => 2,
            'client_log' => AdminLogService::admin_log(),
        ]);

        if ($delete_bank_account) {
            // Send system notification for bank account deletion
            NotificationService::system_notification([
                'type' => 'bank_account_delete',
                'user_type' => 'trader',
                'user_id' => auth()->user()->id,
                'table_id' => decrypt($request->id),
                'category' => 'client',
                'message' => 'Bank account deleted by ' . auth()->user()->name
            ]);
            
            MailNotificationService::admin_notification([
                'name' => auth()->user()->name,
                'email' => auth()->user()->email,
                'type' => 'bank delete',
                'client_type' => 'trader'
            ]);
            $user = User::find(auth()->user()->id);
            activity("Trader bank delete")
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


    // get data bank account edit modal
    public function userBankingEditFetchData(Request $request, $id)
    {
        $bank_account = BankAccount::where('id', decrypt($id))->first();
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
                    'update_id' => $id,
                    'bank_name'     => $bank_account->bank_name,
                    'account_name' => $bank_account->bank_ac_name,
                    'account_number' => $bank_account->bank_ac_number,
                    'swift_code' => $bank_account->bank_swift_code,
                    'bank_iban' => $bank_account->bank_iban,
                    'bank_address' => $bank_account->bank_address,
                    'currency' => $bank_account->currency_id,
                    'country' => $options
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
    public function bankAccountEdit(Request $request)
    {
        $validation_rules = [
            'bank_name'      => 'required',
            'account_name'   => 'required',
            'account_number' => 'required'
        ];
        // $software_settings = SoftwareSetting::select('is_multicurrency')->first();
        // if ($software_settings) {
        //     if ($software_settings->is_multicurrency == 1) {
        //         $validation_rules += [
        //             'modal_currency'  => 'required'
        //         ];
        //     }
        // }
        $validator = Validator::make($request->all(), $validation_rules);
        if ($validator->fails()) {
            if ($request->ajax()) {
                return Response::json(['status' => false, 'errors' => $validator->errors()]);
            } else {
                return Redirect()->back()->with(['status' => false, 'errors' => $validator->errors()]);
            }
        } else {
            $swift_code = (isset($request->code) ? $request->code : "");
            $bank_iban = (isset($request->account_number) ? $request->account_number : "");
            $bank_address = (isset($request->bank_address) ? $request->bank_address : "");
            $country = (isset($request->country) ? $request->country : 1);

            $update_bank_account = BankAccount::where('id', decrypt($request->update_id))->update([
                'user_id'           => Auth()->user()->id,
                'bank_name'         => $request->bank_name,
                'bank_ac_number'    => $request->account_number,
                'bank_ac_name'      => $request->account_name,
                'bank_swift_code'   => $swift_code,
                'bank_iban'         => $bank_iban,
                'bank_address'      => $bank_address,
                'currency_id'       => $request->modal_currency,
                'bank_country'      => $country,
            ]);
            if ($update_bank_account) {
                $user = User::find(auth()->user()->id);
                // start activity log----------------------
                activity("Trader bank update")
                    ->causedBy(auth()->user()->id)
                    ->withProperties($request->all())
                    ->event('bank update')
                    ->performedOn($user)
                    ->log("The IP address " . request()->ip() . " has been updated bank");
                // end activity log-----------------
                
                // Send system notification for bank account update
                NotificationService::system_notification([
                    'type' => 'bank_account_update',
                    'user_type' => 'trader',
                    'user_id' => auth()->user()->id,
                    'table_id' => decrypt($request->update_id),
                    'category' => 'client',
                    'message' => 'Bank account updated by ' . auth()->user()->name
                ]);
                
                MailNotificationService::admin_notification([
                    'name' => auth()->user()->name,
                    'email' => auth()->user()->email,
                    'type' => 'bank update',
                    'client_type' => 'trader'
                ]);

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
}
