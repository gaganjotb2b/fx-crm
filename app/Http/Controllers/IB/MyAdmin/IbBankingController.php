<?php

namespace App\Http\Controllers\IB\MyAdmin;

use App\Http\Controllers\Controller;
use App\Models\admin\SystemConfig;
use App\Models\BankAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Country;
use App\Models\CurrencySetup;
use App\Models\SoftwareSetting;
use App\Services\AllFunctionService;
use App\Services\DataTableService;
use Illuminate\Support\Facades\Auth;
use App\Services\BankService;
use App\Services\CombinedService;
use App\Services\MailNotificationService;
use App\Services\PermissionService;
use App\Services\systems\AdminLogService;

class IbBankingController extends Controller
{
    protected $user;
    public function __construct()
    {
        $this->middleware(AllFunctionService::access('banking', 'ib'));
        $this->middleware(AllFunctionService::access('my_admin', 'ib'));
        $this->middleware('is_ib');
    }
    public function ibBanking()
    {
        // return $this->user;
        $copyright = SystemConfig::select('copyright')->first();
        $countries = Country::all();
        return view('ibs.ib-admins.ib-banking', ['countries' => $countries, 'copyright' => $copyright]);
    }

    //ib bank account add
    public function bankAccountAdd(Request $request)
    {
        try {
            $validation_rules = [
                'bank_name'      => 'required',
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
                return Response::json([
                    'success' => false,
                    'errors' => $validator->errors(),
                    'message' => 'Please fix the following errors!'
                ]);
            } else {

                // create bank account 
                $create = BankAccount::create([
                    'user_id'           => Auth()->user()->id,
                    'bank_name'         => $request->bank_name,
                    'bank_ac_number'    => $request->account_number,
                    'bank_ac_name'      => $request->account_name,
                    'bank_swift_code'   => (isset($request->code) ? $request->code : ""),
                    'bank_iban'         => (isset($request->account_number) ? $request->account_number : ""),
                    'bank_address'      => (isset($request->bank_address) ? $request->bank_address : ""),
                    'currency_id'       => $request->currency,
                    'bank_country'      => (isset($request->country) ? $request->country : 1),
                    'client_log' => AdminLogService::admin_log(),
                ]);
                if ($create) {
                    // insert activity-----------------
                    $user = User::find(auth()->user()->id);
                    activity("IB bank add")
                        ->causedBy(auth()->user()->id)
                        ->withProperties($request->all())
                        ->event('bank add')
                        ->performedOn($user)
                        ->log("The IP address " . request()->ip() . " has been added new bank");
                    // end activity log-----------------
                    MailNotificationService::admin_notification([
                        'name' => auth()->user()->name,
                        'email' => auth()->user()->email,
                        'type' => 'bank add',
                        'client_type' => 'ib'
                    ]);
                    return Response::json([
                        'success' => true,
                        'message' => 'Successfully Added.'
                    ]);
                } else {
                    return Response::json([
                        'success' => false,
                        'message' => 'Failed To Add!'
                    ]);
                }
            }
        } catch (\Throwable $th) {
            //throw $th;
            return Response::json([
                'success' => false,
                'message' => 'Got a server error!'
            ]);
        }
    }

    // ib bank account fetch data 
    public function ibBankingFetchData(Request $request)
    {
        try {
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
                $currency_setup = "";
                $software_settings = SoftwareSetting::select('is_multicurrency')->first();
                if ($software_settings) {
                    if ($software_settings->is_multicurrency == 1) {
                        $currency_setup = CurrencySetup::where('id', $value->currency_id)->first();
                    }
                }
                $multi_cur_visibility = BankService::is_multicurrency('all') ? "" : "d-none";


                $details = '
                        <div class="details-section-dark border-start-3 border-start-primary p-2">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="rounded-0 w-100">
                                        <div class="card-body p-0">    
                                            <table class="table table-striped text-center">
                                                <tr class="">
                                                    <th class="td-font td-border-bottom td-border-right">Bank Name</th>
                                                    <td class="td-font td-border-bottom">' . (isset($value->bank_name) ? $value->bank_name : "") . '</td>
                                                </tr>
                                                <tr>
                                                    <th class="td-font td-border-bottom td-border-right">Account Name</th>
                                                    <td class="td-font td-border-bottom">' . (isset($value->bank_ac_name) ? $value->bank_ac_name : "") . '</td>
                                                </tr>
                                                <tr>
                                                    <th class="td-font td-border-bottom td-border-right">Account Number</th>
                                                    <td class="td-font td-border-bottom">' . (isset($value->bank_ac_number) ? $value->bank_ac_number : "") . '</td>
                                                </tr>
                                                <tr>
                                                    <th class="td-font td-border-bottom td-border-right">Swift Code</th>
                                                    <td class="td-font td-border-bottom">' . (isset($value->bank_swift_code) ? $value->bank_swift_code : "") . '</td>
                                                </tr>
                                                <tr>
                                                    <th class="td-font td-border-bottom td-border-right">Address</th>
                                                    <td class="td-font td-border-bottom">' . (isset($value->bank_address) ? $value->bank_address : "") . '</td>
                                                </tr>
                                                <tr>
                                                    <th class="td-font td-border-bottom td-border-right">Country</th>
                                                    <td class="td-font td-border-bottom td-border-right">' . (isset($country->name) ? $country->name : "") . '</td>
                                                </tr>
                                                <tr class="' . $multi_cur_visibility . '">
                                                    <th class="td-font td-border-bottom td-border-right w-50">Currency</th>
                                                    <td class="td-font td-border-bottom td-border-right">' . ($currency_setup->currency ?? "") . '</td>
                                                </tr>
                                            </table>
                                            <div class="col-lg-12 p-2">
                                                <button data-id="' . encrypt($value->id) . '" type="button" class="btn bg-gradient-primary float-end" data-bs-toggle="modal" data-bs-target="#bank-account-list-edit-modal" id="bank-account-list-edit-button">
                                                    <span class="align-middle">Edit</span>
                                                </button>
                                                <button data-id="' . encrypt($value->id) . '" type="button" class="btn bg-gradient-secondary float-end btn-bank-delete" id="bank-account-list-delete-button">
                                                    <span class="align-middle">Delete</span>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    ';

                $approve_status = (($value->approve_status == 'a') ? '<span class="badge badge-success text-dark td-font text-capitalize">approved</span>' : (($value->approve_status == 'p') ? '<span class="badge badge-warning text-dark td-font text-capitalize">pending</span>' : (($value->approve_status == 'd') ? '<span class="badge badge-info text-dark td-font text-capitalize">declined</span>' : '')));
                $data[$i]["bank_name"] = $value->bank_name;
                $data[$i]["bank_ac_number"] = $value->bank_ac_number;
                $data[$i]["status"] = $approve_status;
                $data[$i]["created_at"]  = date('d M, Y H:i:s A', strtotime($value->created_at));
                $data[$i]["extra"]  = $details;
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

    // bank account delete
    public function bankAccountListDelete(Request $request)
    {
        $delete_bank_account = BankAccount::where('id', decrypt($request->id))->update([
            'status' => 2,
            'client_log' => AdminLogService::admin_log(),
        ]);
        if ($delete_bank_account) {
            // send notification to admin
            MailNotificationService::admin_notification([
                'name' => auth()->user()->name,
                'email' => auth()->user()->email,
                'type' => 'bank delete',
                'client_type' => 'ib'
            ]);
            $user = User::find(auth()->user()->id);
            activity("IB bank delete")
                ->causedBy(auth()->user()->id)
                ->withProperties($request->all())
                ->event('bank delete')
                ->performedOn($user)
                ->log("The IP address " . request()->ip() . " has been deleted bank");
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
    public function ibBankingEditFetchData(Request $request, $id)
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
        try {
            $validation_rules = [
                'bank_name'      => 'required',
                'account_name'   => 'required',
                'account_number' => 'required',
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
                return Response::json([
                    'success' => false,
                    'errors' => $validator->errors(),
                    'message' => 'Please fix the following errors!'
                ]);
            }
            // update bank table
            $update_bank_account = BankAccount::where('id', decrypt($request->update_id))->update([
                'user_id'           => Auth()->user()->id,
                'bank_name'         => $request->bank_name,
                'bank_ac_number'    => $request->account_number,
                'bank_ac_name'      => $request->account_name,
                'bank_swift_code'   => (isset($request->code) ? $request->code : ""),
                'bank_iban'         => (isset($request->account_number) ? $request->account_number : ""),
                'bank_address'      => (isset($request->bank_address) ? $request->bank_address : ""),
                'currency_id'       => $request->modal_currency,
                'bank_country'      => (isset($request->country) ? $request->country : 1),
                'client_log' => AdminLogService::admin_log(),
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
            }
            return Response::json([
                'success' => false,
                'message' => 'Failed To Update!'
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return Response::json([
                'success' => false,
                'message' => 'Got a server error'
            ]);
        }
    }
}
