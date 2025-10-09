<?php

namespace App\Http\Controllers\admins;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ClientGroup;
use App\Models\CustomCommission;
use App\Models\IbSetup;
use App\Models\IbCommissionStructure;
use App\Models\IbGroup;
use App\Services\AllFunctionService;
use App\Services\commission\CommissionStructureService;
use App\Services\commission\IbCommissionService;
use App\Services\CustomValidationService;
use App\Services\IbService;
use App\Services\systems\AdminLogService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\Finder\Iterator\CustomFilterIterator;

class IBcommisionStructureController extends Controller
{
    private $prefix;
    public function __construct()
    {
        $this->middleware(["role:ib commission structure"]);
        $this->middleware(["role:ib management"]);
        // system module control
        $this->middleware(AllFunctionService::access('ib_management', 'admin'));
        $this->middleware(AllFunctionService::access('ib_commission_structure', 'admin'));
        $this->prefix = DB::getTablePrefix();
    }
    //ib commision structure view
    // ---------------------------------------------------------------------------------
    public function index(Request $request)
    {
        $groups = ClientGroup::whereNot('visibility', 'deleted')->where('active_status', 1)->get();
        $IbGroup = IbGroup::where('status', 1)->get();
        $ib_setup = IbSetup::first();
        $ib_level = (isset($ib_setup->ib_level)) ? $ib_setup->ib_level : 0;
        return view(
            'admins.ib-management.ib-commission-structure',
            [
                'groups' => $groups,
                'IbGroup' => $IbGroup,
                'ib_level' => $ib_level
            ]
        );
    }
    // START: ib commission structure store
    // -----------------------------------------------------------------------------
    // public function store(Request $request)
    // {
    //     try {
    //         $ib_setup = IbSetup::select('ib_level')->first();

    //         $validation_rules = [
    //             'client_group' => 'required',
    //             'symbol' => 'required|string|min:2|max:64',
    //             'timing' => 'required',
    //             'total' => 'required|numeric',
    //         ];
    //         if ($ib_setup) {
    //             for ($i = 1; $i <= $ib_setup->ib_level; $i++) {
    //                 $validation_rules['commission' . $i] = 'required';
    //             }
    //         }
    //         // return Response::json($request->all());
    //         $validator = Validator::make($request->all(), $validation_rules);
    //         if ($validator->fails()) {
    //             return Response::json([
    //                 'status' => false,
    //                 'errors' => $validator->errors(),
    //                 'message' => 'Please fix the following errors!',
    //                 'message_title' => 'IB Commission Structure'
    //             ]);
    //         } else {
    //             $commission_level = [];
    //             for ($i = 1; $i <= $ib_setup->ib_level; $i++) {
    //                 $com = 'commission' . $i;
    //                 array_push($commission_level, $request->{$com});
    //             }
    //             // check total
    //             if ($request->total < array_sum($commission_level)) {
    //                 return Response::json([
    //                     'status' => false,
    //                     'errors' => ['total' => 'Total must greater than / equeal to sum of commissions'],
    //                     'message' => 'Total must be greater than or equal to sum of commissions',
    //                     'message_title' => 'IB Commission Structure'
    //                 ]);
    //             }
    //             if ($request->op === 'add') {
    //                 if (!is_numeric($request->client_group)) {
    //                     return Response::json(['status' => false, 'errors' => [
    //                         'client_group' => 'Select a Group'
    //                     ]]);
    //                 }
    //                 $create = IbCommissionStructure::create([
    //                     'client_group_id' => $request->client_group,
    //                     'ib_group_id' => $request->customOptionsCheckableRadios,
    //                     'symbol' => $request->symbol,
    //                     'timing' => $request->timing,
    //                     'total' => $request->total,
    //                     'commission' => json_encode($commission_level),
    //                     'created_by' => auth()->user()->id,
    //                     'admin_log' => AdminLogService::admin_log(),
    //                 ]);
    //                 if ($create) {
    //                     // create custom commission
    //                     if (IbCommissionService::remaining_setup()) {
    //                         CommissionStructureService::create_custom_commission($create->id);
    //                     }
    //                     // insert activity-----------------
    //                     activity("IB commission structure added")
    //                         ->causedBy(auth()->user()->id)
    //                         ->withProperties($request->all())
    //                         ->event("added")
    //                         ->log("The IP address " . request()->ip() . " has been added IB commission structure");
    //                     // end activity log-----------------
    //                     return Response::json([
    //                         'status' => true,
    //                         'message' => 'IB Commission Structure successfully added',
    //                         'message_title' => 'IB Commission Structure add'
    //                     ]);
    //                 } else {
    //                     return Response::json([
    //                         'status' => false,
    //                         'message' => 'Somthing went wrong please try again later!',
    //                         'message_title' => 'IB Commission Structure add'
    //                     ]);
    //                 }
    //             }
    //             if ($request->op === 'edit') {
    //                 $commission = IbCommissionStructure::find($request->id);
    //                 $commission->client_group_id = $request->client_group;
    //                 $commission->ib_group_id = $request->customOptionsCheckableRadios;
    //                 $commission->symbol = $request->symbol;
    //                 $commission->timing = $request->timing;
    //                 $commission->total = $request->total;
    //                 $commission->commission = json_encode($commission_level);
    //                 $update = $commission->save();
    //             }
    //             if ($update) {
    //                 // insert activity-----------------
    //                 activity("IB commission structure updated")
    //                     ->causedBy(auth()->user()->id)
    //                     ->withProperties($request->all())
    //                     ->event("updated")
    //                     ->log("The IP address " . request()->ip() . " has been updated IB commission structure");
    //                 // end activity log-----------------
    //                 return Response::json([
    //                     'status' => true,
    //                     'message' => 'IB Commission Structure successfully updated',
    //                     'message_title' => 'IB Commission Structure update'
    //                 ]);
    //             } else {
    //                 return Response::json([
    //                     'status' => false,
    //                     'message' => 'Somthing went wrong please try again later!',
    //                     'message_title' => 'IB Commission Structure update'
    //                 ]);
    //             }
    //         }
    //     } catch (\Throwable $th) {
    //         //throw $th;
    //         return Response::json([
    //             'status' => false,
    //             'message' => 'Got a server error!'
    //         ]);
    //     }
    // }
    
    public function store(Request $request)
    {
        try {
            $ib_setup = IbSetup::select('ib_level')->first();

            $validation_rules = [
                'client_group' => 'required',
                'symbol' => 'required|string|min:2|max:64',
                'timing' => 'required',
                'total' => 'required|numeric',
            ];
            if ($ib_setup) {
                for ($i = 1; $i <= $ib_setup->ib_level; $i++) {
                    $validation_rules['commission' . $i] = 'required';
                }
            }
            // return Response::json($request->all());
            $validator = Validator::make($request->all(), $validation_rules);
            if ($validator->fails()) {
                return Response::json([
                    'status' => false,
                    'errors' => $validator->errors(),
                    'message' => 'Please fix the following errors!',
                    'message_title' => 'IB Commission Structure'
                ]);
            } else {
                $commission_level = [];
                for ($i = 1; $i <= $ib_setup->ib_level; $i++) {
                    $com = 'commission' . $i;
                    array_push($commission_level, $request->{$com});
                }
                // check total
                if ($request->total < array_sum($commission_level)) {
                    return Response::json([
                        'status' => false,
                        'errors' => ['total' => 'Total must greater than / equeal to sum of commissions'],
                        'message' => 'Total must be greater than or equal to sum of commissions',
                        'message_title' => 'IB Commission Structure'
                    ]);
                }
                if ($request->op === 'add') {
                    if (!is_numeric($request->client_group)) {
                        return Response::json(['status' => false, 'errors' => [
                            'client_group' => 'Select a Group'
                        ]]);
                    }
                    $create = IbCommissionStructure::create([
                        'client_group_id' => $request->client_group,
                        'ib_group_id' => $request->customOptionsCheckableRadios,
                        'symbol' => $request->symbol,
                        'timing' => $request->timing,
                        'total' => $request->total,
                        'commission' => json_encode($commission_level),
                        'created_by' => auth()->user()->id,
                        'admin_log' => AdminLogService::admin_log(),
                    ]);
                    if ($create) {
                        // create custom commission
                        if (IbCommissionService::remaining_setup()) {
                            CommissionStructureService::create_custom_commission($create->id);
                        }
                        // insert activity-----------------
                        activity("IB commission structure added")
                            ->causedBy(auth()->user()->id)
                            ->withProperties($request->all())
                            ->event("added")
                            ->log("The IP address " . request()->ip() . " has been added IB commission structure");
                        // end activity log-----------------
                        return Response::json([
                            'status' => true,
                            'message' => 'IB Commission Structure successfully added',
                            'message_title' => 'IB Commission Structure add'
                        ]);
                    } else {
                        return Response::json([
                            'status' => false,
                            'message' => 'Somthing went wrong please try again later!',
                            'message_title' => 'IB Commission Structure add'
                        ]);
                    }
                }
                if ($request->op === 'edit') {
                    $commission = IbCommissionStructure::find($request->id);
                    $commission->client_group_id = $request->client_group;
                    $commission->ib_group_id = $request->customOptionsCheckableRadios;
                    $commission->symbol = $request->symbol;
                    $commission->timing = $request->timing;
                    $commission->total = $request->total;
                    $commission->commission = json_encode($commission_level);
                    $update = $commission->save();

                    
                    // create custom commission
                    if (IbCommissionService::remaining_setup()) {
                        CommissionStructureService::create_custom_commission($request->id);
                    }
                }
                if ($update) {
                    // insert activity-----------------
                    activity("IB commission structure updated")
                        ->causedBy(auth()->user()->id)
                        ->withProperties($request->all())
                        ->event("updated")
                        ->log("The IP address " . request()->ip() . " has been updated IB commission structure");
                    // end activity log-----------------
                    return Response::json([
                        'status' => true,
                        'message' => 'IB Commission Structure successfully updated',
                        'message_title' => 'IB Commission Structure update'
                    ]);
                } else {
                    return Response::json([
                        'status' => false,
                        'message' => 'Somthing went wrong please try again later!',
                        'message_title' => 'IB Commission Structure update'
                    ]);
                }
            }
        } catch (\Throwable $th) {
            throw $th;
            return Response::json([
                'status' => false,
                'message' => 'Got a server error!'
            ]);
        }
    }

    // fetch datatable data
    // ------------------------------------------------------------------------------------------
    public function datatable_data(Request $request)
    {
        try {

            // column initialize by ib_level
            // ----------------------------------------------------------------------------
            $ib_setup = IbSetup::first();
            $ib_level = (isset($ib_setup->ib_level)) ? $ib_setup->ib_level : 0;

            // Start datatable operation
            // ----------------------------------------------------------------------------
            $result = IbCommissionStructure::select()->whereNot('status', 2);
            if (isset($request->trader_group)) {
                if (is_numeric($request->trader_group)) {
                    $result = $result->where('client_group_id', $request->trader_group);
                }
            }
            if (isset($request->ib_group)) {
                if (is_numeric($request->ib_group)) {
                    $result = $result->where('ib_group_id', $request->ib_group);
                }
            }
            // search result
            if (isset($request->search['value']) && $request->search['value']) {
                $search_item = $request->search['value'];
                $result = $result->where(function ($query) use ($search_item) {
                    $query->where('symbol', 'like', '%' . $search_item . '%')
                        ->orWhere('total', 'like', '%' . $search_item . '%')
                        ->orWhere('timing', 'like', '%' . $search_item . '%');
                });
            }

            $count = $result->count(); // <------count total rows
            $result = $result->orderby('created_at', 'DESC')->skip($request->start)->take($request->length)->get();
            $data = array();
            $i = 0;

            foreach ($result as $key => $value) {
                // checkr remaining setup
                $currency = '';
                if (IbCommissionService::remaining_setup()) {
                    $currency = '<a data-id="' . $value->id . '" href="#" class="dt-description justify-content-start text-truncate"><span class="w"> <i class="plus-minus text-dark" data-feather="plus"></i></span> <span class="ms-2">' . $value->symbol . '</span></a>';
                } else {
                    $currency = $value->symbol;
                }
                $data[$i]["currency"]   = $currency;
                $data[$i]["timing"]     = $value->timing;
                $data[$i]["total"]      = $value->total;
                for ($j = 1; $j <= $ib_level; $j++) {
                    $commissions = json_decode($value->commission);
                    $data[$i]['level_' . $j]  = (array_key_exists(($j - 1), $commissions)) ? $commissions[$j - 1] : 0;
                }
                if ($value->status == 0) {
                    $request_for = 'enable';
                } elseif ($value->status == 1) {
                    $request_for = 'disable';
                }

                $data[$i]["actions"]      = '<div class="d-flex justify-content-between">
                                            <a href="#" class="more-actions dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i data-feather="more-vertical"></i>
                                            </a> 
                                            <a data-id="' . $value->id . '" data-ib_level="' . $ib_level . '" data-commission="' . $value->commission . '" data-symbol="' . $value->symbol . '" href="javascript:void(0);" data-timing="' . $value->timing . '" class="edit-ib-commission-structure">
                                                <i data-feather="edit"></i>
                                            </a> 
                                            <div class="dropdown-menu dropdown-menu-end">
                                                <span class="dropdown-item btn-enable-disable" data-id="' . $value->id . '" data-request_for="' . $request_for . '">
                                                    <i data-feather="user-x"></i>
                                                    ' . ucwords($request_for) . '
                                                </span>
                                            </div>
                                        </div>';
                $i++;
            }

            return Response::json([
                'draw' => $_REQUEST['draw'],
                'recordsTotal' => $count,
                'recordsFiltered' => $count,
                'data' => $data,
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return Response::json([
                'draw' => $request->draw,
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => [],
            ]);
        }
    }

    // delete ib commission structure----------------------------
    public function ib_commission_structure_delete(Request $request)
    {
        try {
            $commission = IbCommissionStructure::find($request->id);
            $delete = IbCommissionStructure::where('id', $request->id)->update([
                'status' => 2,
                'admin_log' => AdminLogService::admin_log(),
            ]);
            if ($delete) {
                // insert activity-----------------
                activity("IB commission structure deleted")
                    ->causedBy(auth()->user()->id)
                    ->withProperties($commission)
                    ->event("deleted")
                    ->log("The IP address " . request()->ip() . " has been deleted IB commission structure");
                // end activity log-----------------
                return Response::json([
                    'status' => true,
                    'message' => 'IB Commission structure successfully deleted!'
                ]);
            } else {
                return Response::json([
                    'status' => false,
                    'message' => 'Somthing went wrong please try again later!'
                ]);
            }
        } catch (\Throwable $th) {
            //throw $th;
            return Response::json([
                'status' => false,
                'message' => 'Got a server error!'
            ]);
        }
    }
    // enable disable ib commission structure---------------------------
    public function ib_commission_enable_disable(Request $request)
    {
        try {
            $commission = IbCommissionStructure::find($request->id);
            $commission->status = ($request->request_for === 'enable') ? 1 : 0;
            $update = $commission->save();
            if ($update) {
                // insert activity-----------------
                activity("IB commission structure " . $request->request_for)
                    ->causedBy(auth()->user()->id)
                    ->withProperties($request->all())
                    ->event($request->request_for)
                    ->log("The IP address " . request()->ip() . " has been " . $request->request_for . " IB commission structure");
                // end activity log-----------------
                return Response::json([
                    'status' => true,
                    'message' => 'Commission structure successfully ' . $request->request_for,
                ]);
            }
            return Response::json([
                'status' => false,
                'message' => 'Somthing went wrong please try again later',
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return Response::json([
                'status' => false,
                'message' => 'Got a server error!'
            ]);
        }
    }

    private $rows = [];
    // import CSV 
    public function importCsv(Request $request)
    {
        try {
            $system_level = IbService::system_ibCommission_level();
            $validation_rules = [
                'csv_file' => 'required|mimes:csv',
            ];

            $validator = Validator::make($request->all(), $validation_rules);
            if ($validator->fails()) {
                return Response::json([
                    'status' => false,
                    'errors' => $validator->errors(),
                    'message' => 'Fix the following Errors'
                ]);
            }

            $records = array_map('str_getcsv', file($request->file('csv_file')->getRealPath()));
            if (empty($records)) {
                return Response::json(['status' => false, 'message' => 'This file is broken']);
            }

            // Get field names from header column
            $fields = array_map('strtolower', $records[0]);
            $fields = array_map(function ($field) {
                return str_replace(' ', '_', $field);
            }, $fields);

            // prepare two array for missing column / commission column
            $required_fields = ['currency', 'timing', 'total'];
            $commission_fields = [];
            for ($i = 0; $i < $system_level; $i++) {
                $level = $i + 1;
                $levelFieldName = 'level_' . $level;

                if (!in_array('commission', $fields)) {
                    $required_fields[] = $levelFieldName;
                    $commission_fields[] = $levelFieldName;
                }
            }

            // Check required field availability in CSV file
            $missing_fields = array_diff($required_fields, $fields);
            if (!empty($missing_fields)) {
                $missingFieldsMessage = implode(", ", array_map('ucwords', str_replace('_', ' ', $missing_fields)));
                return Response::json([
                    'status' => false,
                    'fromError' => true,
                    'message' => 'Column(s) missing in your CSV file: (' . $missingFieldsMessage . ')'
                ]);
            }
            // Remove the header column
            array_shift($records);
            // data prepare for insert into database
            $recordsToInsert = $currencys = [];
            $recoresToInsertCustom = [];
            foreach ($records as &$record) {
                $commission = [];
                foreach ($commission_fields as $field) {
                    $commission[] = $record[array_search($field, $fields)];
                }
                $currency = CustomValidationService::html_encode($record[array_search('currency', $fields)]);
                $currencys[] = $currency; // store all currency to a variable
                $recordsToInsert[] = [
                    'client_group_id' => $request->client_group,
                    'ib_group_id' => $request->ib_group,
                    'symbol' => $currency,
                    'timing' => CustomValidationService::html_encode($record[array_search('timing', $fields)]),
                    'total' => $record[array_search('total', $fields)],
                    'commission' => json_encode($commission),
                    'created_by' => auth()->user()->id,
                    'admin_log' => AdminLogService::admin_log(),
                    'created_at' => date('Y-m-d h:i:s', strtotime(now())),
                    'updated_at' => date('Y-m-d h:i:s', strtotime(now())),
                    'status' => '1',
                ];
            }
            // check currency already exists or not
            $currency_check = IbCommissionStructure::whereIn('symbol', $currencys)->whereNot('status', 2)
                ->where('ib_group_id', $request->ib_group)->where('client_group_id', $request->client_group)->first();
            if ($currency_check) {
                return Response::json([
                    'status' => false,
                    'fromError' => true,
                    'message' => 'Currency already exists in selected groups: (' . $currency_check->symbol . '), Please remove symbols CSV file that already exists'
                ]);
            }
            // insert ib commission structure and 
            $custom_commissions = [];
            if (!empty($recordsToInsert)) {
                // get current last ID
                $current_last_id = IbCommissionStructure::select('id')->orderBy('id', 'desc')->first();
                $create = IbCommissionStructure::insert($recordsToInsert);
                if ($current_last_id) {
                    $insertedIds = IbCommissionStructure::where('id', '>', $current_last_id->id)->pluck('id');
                } else {
                    $insertedIds = IbCommissionStructure::select('id')->pluck('id');
                }

                // check custom commission enabled
                if (IbCommissionService::remaining_setup()) {
                    // prepare custom commission
                    for ($i = 1; $i < $system_level; $i++) {
                        $custom_com_row = $request->{'level_commission_' . $i};
                        $new_cust_com_row = [];
                        for ($j = 0; $j < $system_level; $j++) {
                            $new_cust_com_row[] = (array_key_exists($j, $custom_com_row)) ? $custom_com_row[$j] : '--';
                        }
                        array_push($custom_commissions, $new_cust_com_row);
                    }

                    foreach ($custom_commissions as $row_inside) {
                        foreach ($insertedIds as $value) {
                            $recoresToInsertCustom[] = [
                                'commission_id' => $value,
                                'custom_commission' => json_encode($row_inside),
                                'created_at' => date('Y-m-d h:i:s', strtotime(now())),
                                'updated_at' => date('Y-m-d h:i:s', strtotime(now())),
                            ];
                        }
                    }
                    // insert custom commissions
                    $createCustom = CustomCommission::insert($recoresToInsertCustom);
                }

                // Log the import activity
                activity("IB commission structure import by csv")
                    ->causedBy(auth()->user()->id)
                    ->withProperties(['records_count' => count($recordsToInsert), 'records' => $recordsToInsert])
                    ->event('Import commission structure')
                    ->log("The IP address " . request()->ip() . " has imported IB commission structure");
                // end activity log
            }
            if ($create) {
                return Response::json(
                    [
                        'status' => true,
                        'message' => 'IB Commission structure successfully imported'
                    ]
                );
            }
            return Response::json([
                'status' => false,
                'message' => 'Something went wrong, please try again later'
            ]);
        } catch (\Throwable $th) {
            // throw $th;
            return Response::json([
                'status' => false,
                'message' => 'Got a server error'
            ]);
        }
    }

    private function clear_encoding_str($value)
    {
        if (is_array($value)) {
            $clean = [];
            foreach ($value as $key => $val) {
                $clean[$key] = mb_convert_encoding($val, 'UTF-8', 'UTF-8');
            }
            return $clean;
        }
        return mb_convert_encoding($value, 'UTF-8', 'UTF-8');
    }

    // ib commission structure description
    public function description(Request $request)
    {
        try {
            $system_level = IbService::system_ibCommission_level();
            $row = '';
            $heading_row = '';
            $commission_structure = IbCommissionStructure::find($request->id);
            $commission = json_decode($commission_structure->commission);
            $table = [];
            for ($i = $system_level; $i >= 1; $i--) {
                $heading_row .= '<th>Level ' . ($system_level - $i) + 1 . '</th>';
                for ($j = 1; $j <= $system_level; $j++) {
                    $level_com = 0;
                    if ($i < $j) {
                        $level_com = 0;
                    } else {
                        $level_com = array_key_exists(($j - 1), $commission) ? $commission[$j - 1] : 0;
                    }
                    $table[($system_level - $i) + 1][$j - 1] = $level_com;
                }
            }
            $heading_row .= '<th>Action</th>';
            // MODIFY THE TABLE ARRAY
            for ($i = 1; $i <= count($table); $i++) {
                for ($j = 0; $j < count($table[$i]); $j++) {
                    if ($table[$i][$j] == 0) {
                        $table[$i][0] += (float)$table[1][$j];
                        $table[$i][$j] = 0;
                    }
                }
            }
            for ($i = 2; $i <= count($table); $i++) {
                for ($j = count($table) - 1; $j > count($table) - $i; $j--) {
                    $table[$i][$j] = '--';
                }
            }

            // get custom commission 
            $custom_commissions = CustomCommission::where('commission_id', $request->id)->get();
            // return $custom_commissions;
            $total = count($custom_commissions);
            $j = 1;
            foreach ($custom_commissions as $key => $value) {
                $row .= '<tr>';
                $columns = json_decode($value->custom_commission);
                for ($i = 0; $i < count($columns); $i++) {
                    $row .= '<td>
                                    ' . $columns[$i] . '
                                </td>';
                }

                $row .= '<td><button class="btn btn-sm btn-warning btn-edit-custom" data-total="' . $total + 1 . '" data-row="' . $j . '" data-id="' . $request->id . '" type="button"><i data-feather="edit"></i></button>';
                $row .= '<button class="btn btn-sm btn-success btn-save-custom d-none" data-total="' . $total + 1 . '" data-row="' . $j . '" data-commission_id="' . $request->id . '" data-id="' . $value->id . '" type="button"><i data-feather="save"></i></button></td>';
                $row .= '<tr>';
                $j++;
            }
            $colspan = ($system_level + 4);
            $description = '<tr class="description" style="display:none">
            <td colspan="' . $colspan . '">
                <div class="details-section-dark dt-details border-start-3 border-start-primary p-2">
                    <table class="table table-responsive tbl-balance">
                        <tr>
                            ' . $heading_row . '
                        </tr>
                        <tbody>
                        ' . $row . '
                        </tbody>
                    </table>
                </div>
            </td>
            <td class="d-none">&nbsp;</td>
            <td class="d-none">&nbsp;</td>
            <td class="d-none">&nbsp;</td>
            <td class="d-none">&nbsp;</td>
            <td class="d-none">&nbsp;</td>
        </tr>';
            return Response::json([
                'status' => true,
                'description' => $description,
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    // delete all currency from selected groups
    public function delete_group_wise(Request $request)
    {
        try {
            // action to soft delete
            $delete = IbCommissionStructure::where('client_group_id', $request->client_group)
                ->where('ib_group_id', $request->ib_group)
                ->whereNot('status', '2')
                ->update([
                    'status' => '2',
                    'admin_log' => AdminLogService::admin_log(),
                    'created_by' => auth()->user()->id,
                ]);

            if ($delete) {
                // insert activity-----------------
                activity("IB commission structure delete")
                    ->causedBy(auth()->user()->id)
                    ->withProperties($request->all())
                    ->event('Delete IB Commission Structure')
                    ->log("The IP address " . request()->ip() . " has been delete IB commission structure");
                // end activity log-----------------
                return Response::json([
                    'status' => true,
                    'message' => 'Currency deleted from selected groups'
                ]);
            }
            return Response::json([
                'status' => false,
                'message' => 'Delete operation failed, Please try again later'
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return Response::json([
                'status' => false,
                'message' => 'Got a server error!'
            ]);
        }
    }
}
