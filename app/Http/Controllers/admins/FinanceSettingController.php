<?php

namespace App\Http\Controllers\Admins;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\admin\SystemConfig;
use App\Models\DepositSetting;
use App\Models\TransactionSetting;
use App\Models\WithdrawSetting;
use App\Services\AllFunctionService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use PhpParser\Builder\Trait_;

class FinanceSettingController extends Controller
{
    public function __construct()
    {
        $this->middleware(["role:finance settings"]);
        $this->middleware(["role:settings"]);
        // system module control
        $this->middleware(AllFunctionService::access('settings', 'admin'));
        $this->middleware(AllFunctionService::access('finance_settings', 'admin'));
    }
    public function financeSetting()
    {
        $depositSettings = DepositSetting::select()->get();
        $withdrawSettings = WithdrawSetting::select()->get();
        return view('admins.settings.finance_setting', compact('depositSettings','withdrawSettings'));
    }
    // finance setting
    public function financeSettingFetchData(Request $request)
    {
        try {
            $result = TransactionSetting::select();
            // Filter by finance
            $count = $result->count(); // <------count total rows
            $result = $result->orderby('id', 'DESC')->skip($request->start)->take($request->length)->get();
            $data = array();
            $i = 0;

            // $serial = 1;
            foreach ($result as $row) {
                if (Auth::user()->hasDirectPermission('edit finance settings')) {
                    if ($row->active_status === 1) {
                        $buttons = '<a data-id="' . $row->id . '" class="dropdown-item" data-value="0" data-bs-toggle="modal" id="finance-setting-active-status-button">
                                <i data-feather="shield-off"></i>
                                <span>Deactive</span>
                            </a>
                            <a type="button" data-id="' . $row->id . '" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#finance-setting-delete-modal" id="finance-setting-delete-button">
                                <i data-feather="trash" class="me-50"></i>
                                <span>Delete</span>
                            </a>';
                    } else {
                        $buttons = '<a data-id="' . $row->id . '" class="dropdown-item" data-value="1" data-bs-toggle="modal" id="finance-setting-active-status-button">
                                <i data-feather="shield"></i>
                                <span>Active</span>
                            </a>
                            <a type="button" data-id="' . $row->id . '" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#finance-setting-delete-modal" id="finance-setting-delete-button">
                                <i data-feather="trash" class="me-50"></i>
                                <span>Delete</span>
                            </a>';
                    }
                } else {
                    $buttons = '<span class=text-danger>No Access To Write</span>';
                }
                $data[$i]['transaction_type']   = (($row->transaction_type == "deposit") ? "Deposit" : (($row->transaction_type == "withdraw") ? "Withdraw" : (($row->transaction_type == "a_to_w") ? "Account To Wallet" : (($row->transaction_type == "w_to_a") ? "Wallet To Account" : (($row->transaction_type == "a_to_a") ? "Account To Account" : (($row->transaction_type == "w_to_w") ? "Wallet To Wallet" : ""))))));
                $data[$i]['transaction_limit']  = ($row->min_transaction != 0 && $row->max_transaction != 0) ? ($row->min_transaction . "&nbsp;  &nbsp;To&nbsp;  &nbsp;" . $row->max_transaction . "") : "NA";
                $data[$i]['charge_type']        = $row->charge_type;
                $data[$i]['charge_limit']       = ($row->limit_start != 0 && $row->limit_end != 0) ? ($row->limit_start . " To " . $row->limit_end . "") : "NA";
                $data[$i]['kyc']                = ($row->kyc == 1) ? "Required" : "NA";
                $data[$i]['amount']             = $row->amount;
                $data[$i]['status']             = $row->permission;
                $data[$i]['active_status']      = ($row->active_status == 1) ? "<span class='badge badge-light-success' style='font-size:1rem;'>Active</span>" : "<span class='badge badge-light-danger' style='font-size:1rem;'>Disable</span>";
                $data[$i]['action']             = '<td>
                                                <div class="dropdown">
                                                    <button type="button" class="btn btn-sm dropdown-toggle hide-arrow py-0" data-bs-toggle="dropdown">
                                                        <i data-feather="more-vertical"></i>
                                                    </button>
                                                    <div class="dropdown-menu dropdown-menu-end">
                                                       ' . $buttons . '
                                                    </div>
                                                </div>
                                            </td>';
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
    // add finance settings
    public function financeSettingAdd(Request $request)
    {
        $validation_rules = [
            'amount' => 'required',
        ];
        // set transaction charge type 
        $charge_type = "";
        $fixed = $request->fixed;
        $percentage = $request->percentage;
        if ($fixed == "on") {
            $charge_type = "fixed";
        } else if ($percentage == "on") {
            $charge_type = "percentage";
        } else {
            $charge_type = "";
        }
        if ($charge_type == "") {
            if ($request->ajax()) {
                return Response::json(['status' => false, 'charge_type' => 'Charge type field is required!']);
            } else {
                return Redirect()->back()->with(['status' => false, 'charge_type' => 'Charge type field is required!']);
            }
        }

        $validator = Validator::make($request->all(), $validation_rules);
        if ($validator->fails()) {
            if ($request->ajax()) {
                return Response::json(['status' => false, 'amount' => 'The amount field is required!', 'errors' => $validator->errors()]);
            } else {
                return Redirect()->back()->with(['status' => false, 'errors' => $validator->errors()]);
            }
        } else {
            $min_transaction = (isset($request->min_transaction) ? $request->min_transaction : 0);
            $max_transaction = (isset($request->max_transaction) ? $request->max_transaction : 0);

            $limit_start = (isset($request->limit_start) ? $request->limit_start : 0);
            $limit_end   = (isset($request->limit_end) ? $request->limit_end : 0);
            $kyc = ($request->kyc == "on") ? 1 : 0;


            $error = 0;
            $set_all_trnx_limit = 0;
            // check transaction limit and charge limit exist or not
            $transaction_settings = TransactionSetting::where('transaction_type', strtolower($request->transaction_type))->get();

            if (isset($transaction_settings[0]['id'])) {
                foreach ($transaction_settings as $row) {

                    // if transaction limits are null
                    if (empty($min_transaction) || empty($max_transaction)) {
                        if (empty($limit_start) || empty($limit_end)) {
                            $set_all_trnx_limit = 1;
                        }
                        if (($limit_start >= $row->limit_start && $limit_start <= $row->limit_end) || ($limit_end >= $row->limit_start && $limit_end <= $row->limit_end)) {
                            $error = 1;
                            if ($request->ajax()) {
                                return Response::json(['status' => false, 'message' => 'Charge Limit Already Exists!']);
                            } else {
                                return Redirect()->back()->with(['status' => false, 'message' => 'Charge Limit Already Exists!']);
                            }
                        }
                    }
                    if (($min_transaction >= $row->min_transaction && $min_transaction <= $row->max_transaction) || ($max_transaction >= $row->min_transaction && $max_transaction <= $row->max_transaction)) {
                        // echo "2";
                        // exit;
                        if (($limit_start >= $row->limit_start && $limit_start <= $row->limit_end) || ($limit_end >= $row->limit_start && $limit_end <= $row->limit_end)) {
                            $error = 1;
                            if ($request->ajax()) {
                                return Response::json(['status' => false, 'message' => 'Charge Limit Already Exists!']);
                            } else {
                                return Redirect()->back()->with(['status' => false, 'message' => 'Charge Limit Already Exists!']);
                            }
                        }
                        if (!($limit_start >= $row->limit_start && $limit_start <= $row->limit_end) || ($limit_end >= $row->limit_start && $limit_end <= $row->limit_end)) {
                            $error = 0;
                        }
                    }
                    if (($limit_start < $min_transaction && $limit_start > $max_transaction) && ($limit_end < $min_transaction && $limit_end > $max_transaction)) {
                        // echo "3";
                        // exit;
                        $error = 1;
                        if ($request->ajax()) {
                            return Response::json(['status' => false, 'message' => 'Minimum or maximum charge limit should not greater/less than the transaction limit!']);
                        } else {
                            return Redirect()->back()->with(['status' => false, 'message' => 'Minimum or maximum charge limit should not greater/less than the transaction limit!']);
                        }
                    }
                    if (($limit_start < $min_transaction || $limit_start > $max_transaction) || ($limit_end < $min_transaction || $limit_end > $max_transaction)) {
                        // echo "4";
                        // exit;
                        if (empty($limit_start) || empty($limit_end)) {
                            $error = 1;
                            if ($request->ajax()) {
                                return Response::json(['status' => false, 'message' => 'Transaction Limit Already Exists!']);
                            } else {
                                return Redirect()->back()->with(['status' => false, 'message' => 'Transaction Limit Already Exists!']);
                            }
                        } else {
                            $error = 1;
                            if ($request->ajax()) {
                                return Response::json(['status' => false, 'message' => 'Minimum or maximum charge limit should not greater/less than the transaction limit!']);
                            } else {
                                return Redirect()->back()->with(['status' => false, 'message' => 'Minimum or maximum charge limit should not greater/less than the transaction limit!']);
                            }
                        }
                    }
                }
            } else {
                // echo "5";
                // exit;
                if (($limit_start >= $min_transaction && $limit_start <= $max_transaction) && ($limit_end >= $min_transaction && $limit_end <= $max_transaction)) {
                    $error = 0;
                } else {
                    $error = 1;
                    if ($request->ajax()) {
                        return Response::json(['status' => false, 'message' => 'Minimum or maximum charge limit should not greater/less than the transaction limit!']);
                    } else {
                        return Redirect()->back()->with(['status' => false, 'message' => 'Minimum or maximum charge limit should not greater/less than the transaction limit!']);
                    }
                }
            }
            if ($error === 1) {
                if ($request->ajax()) {
                    return Response::json(['status' => false, 'message' => 'Failed To Insert!']);
                } else {
                    return Redirect()->back()->with(['status' => false, 'message' => 'Failed To Insert!']);
                }
            } else {
                $add_finance = TransactionSetting::create([
                    'transaction_type'  => strtolower($request->transaction_type),
                    'min_transaction'   => $min_transaction,
                    'max_transaction'   => $max_transaction,
                    'charge_type'       => $charge_type,
                    'limit_start'       => $limit_start,
                    'limit_end'         => $limit_end,
                    'kyc'               => $kyc,
                    'amount'            => $request->amount,
                    'permission'        => strtolower($request->permission),
                    'active_status'     => $request->active_status,
                ]);
                if ($add_finance) {
                    if ($request->ajax()) {
                        return Response::json(['status' => true, 'message' => 'Successfully Inserted.']);
                    } else {
                        return Redirect()->back()->with(['status' => true, 'message' => 'Successfully Inserted.']);
                    }
                } else {
                    if ($request->ajax()) {
                        return Response::json(['status' => false, 'message' => 'Failed To Insert!']);
                    } else {
                        return Redirect()->back()->with(['status' => false, 'message' => 'Failed To Insert!']);
                    }
                }
            }

            // $add_finance = TransactionSetting::create([
            //     'transaction_type'  => strtolower($request->transaction_type),
            //     'min_transaction'   => $min_transaction,
            //     'max_transaction'   => $max_transaction,
            //     'charge_type'       => $charge_type,
            //     'limit_start'       => $limit_start,
            //     'limit_end'         => $limit_end,
            //     'kyc'               => $kyc,
            //     'amount'            => $request->amount,
            //     'permission'        => strtolower($request->permission),
            //     'active_status'     => $request->active_status,
            // ]);
            // if ($add_finance) {
            //     if ($request->ajax()) {
            //         return Response::json(['status' => true, 'message' => 'Successfully Updated.']);
            //     } else {
            //         return Redirect()->back()->with(['status' => true, 'message' => 'Successfully Updated.']);
            //     }
            // } else {
            //     if ($request->ajax()) {
            //         return Response::json(['status' => false, 'message' => 'Failed To Update!']);
            //     } else {
            //         return Redirect()->back()->with(['status' => false, 'message' => 'Failed To Update!']);
            //     }
            // }
        }
    }
    // finance settings delete
    public function financeSettingDelete(Request $request, $id)
    {
        $delete_finance = TransactionSetting::find($id)->delete();
        if ($delete_finance) {
            if ($request->ajax()) {
                return Response::json(['status' => true, 'message' => 'Successfully Deleted.']);
            } else {
                return Redirect()->back()->with(['status' => true, 'message' => 'Successfully Deleted.']);
            }
        } else {
            if ($request->ajax()) {
                return Response::json(['status' => false, 'message' => 'Failed To Delete!']);
            } else {
                return Redirect()->back()->with(['status' => false, 'message' => 'Failed To Delete!']);
            }
        }
    }

    // finance settings active status change
    public function financeSettingChangeActiveStatus(Request $request, $id, $value)
    {
        $update_active_status = TransactionSetting::where('id', $id)->update([
            'active_status' => $value,
        ]);
        if ($update_active_status) {
            if ($request->ajax()) {
                return Response::json(['status' => true, 'message' => 'Successfully Updated.']);
            } else {
                return Redirect()->back()->with(['status' => true, 'message' => 'Successfully Updated.']);
            }
        } else {
            if ($request->ajax()) {
                return Response::json(['status' => false, 'message' => 'Failed To Update!']);
            } else {
                return Redirect()->back()->with(['status' => false, 'message' => 'Failed To Update!']);
            }
        }
    }

    // // finance settings edit
    // public function financeSettingEdit(Request $request)
    // {
    //     $validation_rules = [
    //         'amount' => 'required',
    //     ];
    //     // set transaction charge type 
    //     $charge_type = "";
    //     $fixed = $request->fixed;
    //     $percentage = $request->percentage;
    //     if ($fixed == "on") {
    //         $charge_type = "fixed";
    //     } else if ($percentage == "on") {
    //         $charge_type = "percentage";
    //     } else {
    //         $charge_type = "";
    //     }
    //     if ($charge_type == "") {
    //         if ($request->ajax()) {
    //             return Response::json(['status' => false, 'charge_type' => 'Charge type field is required!']);
    //         } else {
    //             return Redirect()->back()->with(['status' => false, 'charge_type' => 'Charge type field is required!']);
    //         }
    //     }

    //     $validator = Validator::make($request->all(), $validation_rules);
    //     if ($validator->fails()) {
    //         if ($request->ajax()) {
    //             return Response::json(['status' => false, 'amount' => 'The amount field is required!', 'errors' => $validator->errors()]);
    //         } else {
    //             return Redirect()->back()->with(['status' => false, 'errors' => $validator->errors()]);
    //         }
    //     } else {
    //         $min_transaction = (isset($request->min_transaction) ? $request->min_transaction : 0);
    //         $max_transaction = (isset($request->max_transaction) ? $request->max_transaction : 0);

    //         $limit_start = (isset($request->limit_start) ? $request->limit_start : 0);
    //         $limit_end   = (isset($request->limit_end) ? $request->limit_end : 0);
    //         $kyc = ($request->kyc == "on") ? 1 : 0;


    //         $error = 0;
    //         $set_all_trnx_limit = 0;
    //         // check transaction limit and charge limit exist or not
    //         $transaction_settings = TransactionSetting::where('transaction_type', strtolower($request->transaction_type))->get();

    //         if (isset($transaction_settings[0]['id'])) {
    //             foreach ($transaction_settings as $row) {

    //                 // if transaction limits are null
    //                 if (empty($min_transaction) || empty($max_transaction)) {
    //                     // echo "1";
    //                     // exit;
    //                     if (empty($limit_start) || empty($limit_end)) {
    //                         $set_all_trnx_limit = 1;
    //                     }
    //                     if (($limit_start >= $row->limit_start && $limit_start <= $row->limit_end) || ($limit_end >= $row->limit_start && $limit_end <= $row->limit_end)) {
    //                         $error = 1;
    //                         if ($request->ajax()) {
    //                             return Response::json(['status' => false, 'message' => 'Charge Limit Already Exists!']);
    //                         } else {
    //                             return Redirect()->back()->with(['status' => false, 'message' => 'Charge Limit Already Exists!']);
    //                         }
    //                     }
    //                 }
    //                 if (($min_transaction >= $row->min_transaction && $min_transaction <= $row->max_transaction) || ($max_transaction >= $row->min_transaction && $max_transaction <= $row->max_transaction)) {
    //                     // echo "2";
    //                     // exit;
    //                     if (($limit_start >= $row->limit_start && $limit_start <= $row->limit_end) || ($limit_end >= $row->limit_start && $limit_end <= $row->limit_end)) {
    //                         $error = 1;
    //                         if ($request->ajax()) {
    //                             return Response::json(['status' => false, 'message' => 'Charge Limit Already Exists!']);
    //                         } else {
    //                             return Redirect()->back()->with(['status' => false, 'message' => 'Charge Limit Already Exists!']);
    //                         }
    //                     }
    //                     if (!($limit_start >= $row->limit_start && $limit_start <= $row->limit_end) || ($limit_end >= $row->limit_start && $limit_end <= $row->limit_end)) {
    //                         $error = 0;
    //                     }
    //                 }
    //                 if (($limit_start < $min_transaction && $limit_start > $max_transaction) && ($limit_end < $min_transaction && $limit_end > $max_transaction)) {
    //                     // echo "3";
    //                     // exit;
    //                     $error = 1;
    //                     if ($request->ajax()) {
    //                         return Response::json(['status' => false, 'message' => 'Minimum or maximum charge limit should not greater/less than the transaction limit!']);
    //                     } else {
    //                         return Redirect()->back()->with(['status' => false, 'message' => 'Minimum or maximum charge limit should not greater/less than the transaction limit!']);
    //                     }
    //                 }
    //                 if (($limit_start < $min_transaction || $limit_start > $max_transaction) || ($limit_end < $min_transaction || $limit_end > $max_transaction)) {
    //                     // echo "4";
    //                     // exit;
    //                     if (empty($limit_start) || empty($limit_end)) {
    //                         $error = 1;
    //                         if ($request->ajax()) {
    //                             return Response::json(['status' => false, 'message' => 'Transaction Limit Already Exists!']);
    //                         } else {
    //                             return Redirect()->back()->with(['status' => false, 'message' => 'Transaction Limit Already Exists!']);
    //                         }
    //                     } else {
    //                         $error = 1;
    //                         if ($request->ajax()) {
    //                             return Response::json(['status' => false, 'message' => 'Minimum or maximum charge limit should not greater/less than the transaction limit!']);
    //                         } else {
    //                             return Redirect()->back()->with(['status' => false, 'message' => 'Minimum or maximum charge limit should not greater/less than the transaction limit!']);
    //                         }
    //                     }
    //                 }
    //             }
    //         } else {
    //             // echo "5";
    //             // exit;
    //             if (($limit_start >= $min_transaction && $limit_start <= $max_transaction) && ($limit_end >= $min_transaction && $limit_end <= $max_transaction)) {
    //                 $error = 0;
    //             } else {
    //                 $error = 1;
    //                 if ($request->ajax()) {
    //                     return Response::json(['status' => false, 'message' => 'Minimum or maximum charge limit should not greater/less than the transaction limit!']);
    //                 } else {
    //                     return Redirect()->back()->with(['status' => false, 'message' => 'Minimum or maximum charge limit should not greater/less than the transaction limit!']);
    //                 }
    //             }
    //         }
    //         if ($error === 1) {
    //             if ($request->ajax()) {
    //                 return Response::json(['status' => false, 'message' => 'Failed To Insert!']);
    //             } else {
    //                 return Redirect()->back()->with(['status' => false, 'message' => 'Failed To Insert!']);
    //             }
    //         } else {
    //             $id = $request->transaction_setting_id;
    //             $add_finance = TransactionSetting::where('id', $id)->update([
    //                 'transaction_type'  => strtolower($request->transaction_type),
    //                 'min_transaction'   => $min_transaction,
    //                 'max_transaction'   => $max_transaction,
    //                 'charge_type'       => $charge_type,
    //                 'limit_start'       => $limit_start,
    //                 'limit_end'         => $limit_end,
    //                 'kyc'               => $kyc,
    //                 'amount'            => $request->amount,
    //                 'permission'        => strtolower($request->permission),
    //                 'active_status'     => $request->active_status,
    //             ]);
    //             if ($add_finance) {
    //                 if ($request->ajax()) {
    //                     return Response::json(['status' => true, 'message' => 'Successfully Inserted.']);
    //                 } else {
    //                     return Redirect()->back()->with(['status' => true, 'message' => 'Successfully Inserted.']);
    //                 }
    //             } else {
    //                 if ($request->ajax()) {
    //                     return Response::json(['status' => false, 'message' => 'Failed To Insert!']);
    //                 } else {
    //                     return Redirect()->back()->with(['status' => false, 'message' => 'Failed To Insert!']);
    //                 }
    //             }
    //         }
    //     }
    // }

    // // finance settings edit
    // public function transactionSettingEditModalFetchData(Request $request, $id)
    // {
    //     $transaction_settings = TransactionSetting::where('id', $id)->first();
    //     if ($transaction_settings) {
    //         if ($request->ajax()) {
    //             return Response::json([
    //                 'status' => true,
    //                 'transaction_type' => $transaction_settings->transaction_type,
    //                 'min_transaction' => $transaction_settings->min_transaction,
    //                 'max_transaction' => $transaction_settings->max_transaction,
    //                 'charge_type' => $transaction_settings->charge_type,
    //                 'limit_start' => $transaction_settings->limit_start,
    //                 'limit_end' => $transaction_settings->limit_end,
    //                 'kyc' => $transaction_settings->kyc,
    //                 'amount' => $transaction_settings->amount,
    //                 'permission' => $transaction_settings->permission,
    //                 'active_status' => $transaction_settings->active_status,
    //             ]);
    //         } else {
    //             return Redirect()->back()->with(['status' => true, 'message' => 'Successfully Updated.']);
    //         }
    //     } else {
    //         if ($request->ajax()) {
    //             return Response::json(['status' => false, 'message' => 'Failed To Update!']);
    //         } else {
    //             return Redirect()->back()->with(['status' => false, 'message' => 'Failed To Update!']);
    //         }
    //     }
    // }
}
