<?php

namespace App\Http\Controllers\traders\contest;

use App\Http\Controllers\Controller;
use App\Models\Contest;
use App\Models\ContestJoin;
use App\Models\TradingAccount;
use App\Services\AllFunctionService;
use App\Services\client_groups\ClientGroupService;
use App\Services\contest\ContestService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class ContestController extends Controller
{
    public function __construct()
    {
        if (request()->is('user/contest/participate-contest')) {
            $this->middleware(AllFunctionService::access('participate_contest', 'trader'));
            $this->middleware(AllFunctionService::access('contest', 'trader'));
        }
        if (request()->is('user/contest/contest-list')) {
            $this->middleware(AllFunctionService::access('contest_list', 'trader'));
            $this->middleware(AllFunctionService::access('contest', 'trader'));
        }
        if (request()->is('user/contest/contest-status')) {
            $this->middleware(AllFunctionService::access('contest_status', 'trader'));
            $this->middleware(AllFunctionService::access('contest', 'trader'));
        }
    }
    public function get_contest(Request $request)
    {
        try {
            if ($request->contest_id == "") {
                $contest = ContestService::get_contest(auth()->user()->id);
            } else {
                $contest = Contest::where('id', $request->contest_id)->first();
            }


            return Response::json([
                'title' => $contest->contest_name,
                'contest_on' => ucwords(str_replace('_', ' ', $contest->contest_type)),
                'start_date' => date('d M Y h:i:s A', strtotime($contest->start_date)),
                'end_date' => date('d M Y h:i:s A', strtotime($contest->end_date)),
                'prices' => json_decode($contest->contest_prices),
                'id' => $contest->id,
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return Response::json([
                'title' => '',
                'contest_on' => '',
                'start_date' => '',
                'end_date' => '',
                'prices' => '',
            ]);
        }
    }
    // joining to contest
    public static function join_contest(Request $request)
    {
        try {
            $user = auth()->user();
            
            $validation_rules = [
                'account' => 'required',
                'contest_id' => 'required'
            ];
            $validator = Validator::make($request->all(), $validation_rules);
            if ($validator->fails()) {
                return Response::json([
                    'status' => false,
                    'message' => 'Joining faild, Need an account!',
                    'errors' => $validator->errors(),
                ]);
            }
            
            // Check if trading account is already being used in another contest
            $accountUsedInOtherContest = ContestJoin::where('account_number', $request->account)
                ->where('contest_id', '!=', $request->contest_id)
                ->first();
                
            if ($accountUsedInOtherContest) {
                return Response::json([
                    'status' => false,
                    'message' => 'This trading account is already participating in another contest. Please create a fresh account to join this contest.',
                    'show_create_account_popup' => true
                ]);
            }
            
            $create = ContestJoin::updateOrCreate(
                [
                    'contest_id' => $request->contest_id,
                    'user_id' => $user->id,
                ],
                [
                    'account_number' => $request->account,
                ]
            );
            if ($create) {
                return Response::json([
                    'status' => true,
                    'message' => 'Successfully joined the contest',
                ]);
            }
            return Response::json([
                'status' => false,
                'message' => 'Contest joining failed, please try again later!',
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return Response::json([
                'status' => false,
                'message' => 'Got a server error, please try again later!',
            ]);
        }
    }
    // get all contest
    public function participate_contest(Request $request)
    {

        try {
            $contest = ContestService::get_all_active_contest(auth()->user()->id);
            $accounts = TradingAccount::where('user_id', auth()->user()->id)->get();
            return view('traders.contest.contest-list',
                [
                    'contest' => $contest,
                    'accounts' => $accounts,
                ]
            );
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    //Contest List
    public function contest_list(Request $request)
    {
        $op = $request->input('op');

        if ($op == "data_table") {
            return $this->ContentListReport($request);
        }
        return view('traders.contest.contest-list');
    }

    public function ContentListReport(Request $request)
    {
        try {
            $user_id = auth()->id();
            $columns = ['contest_name', 'credit_type', 'created_at', 'contest_start_on', 'contest_end_on', 'status'];
            $orderby = $columns[$request->order[0]['column']];

            $contestIds = ContestJoin::where('user_id', $user_id)->pluck('contest_id');
            $result = Contest::select()->whereIn('id', $contestIds);
            // filter by status
            if ($request->status != "") {
                $result = $result->where('status', $request->status);
            }
            // filter by client type
            if ($request->client_type != "") {
                $result = $result->where('allowed_for', $request->client_type);
            }
            // filter by contest_name
            if ($request->contest_name != "") {
                $result = $result->where('contest_name', 'like', '%' . $request->contest_name . '%');
            }
            // filter by contest type
            if ($request->contest_type != "") {
                $result = $result->where('contest_type', $request->contest_type);
            }
            // filter by date from
            if ($request->date_from != "") {
                $result = $result->whereDate('created_at', '>=', $request->date_from);
            }
            if ($request->date_to != "") {
                $result = $result->whereDate('created_at', '<=', $request->date_to);
            }
            $count = $result->count();
            $result = $result->orderby($orderby, $request->order[0]['dir'])->skip($request->start)->take($request->length)->get();
            $data = array();
            foreach ($result as $value) {
                // status 
                $status = '';
                if ($value->status === 'active') {
                    $status = '<span class="badge badge-success bg-success">' . ucwords($value->status) . '</span>';
                } elseif ($value->status === 'disable') {
                    $status = '<span class="badge badge-danger bg-danger">' . ucwords($value->status) . '</span>';
                } else {
                    $status = '<span class="badge badge-warning bg-danger text-white">' . ucwords($value->status) . '</span>';
                }
                $data[] = [
                    'contest_name' => ucwords($value->contest_name),
                    'total_join' => ContestService::count_total_participant($value->id),
                    'date_range' => 'Start: ' . date('d M Y', strtotime($value->start_date)) . '<br/>End: ' . date('d M Y', strtotime($value->end_date)),
                    'create_date' => date('d M y', strtotime($value->created_at)),
                    'status' => $status
                ];
            }
            return Response::json([
                'draw' => $request->draw,
                'recordsTotal' => $count,
                'recordsFiltered' => $count,
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

    public function ContestListDescription(Request $request)
    {
        $id = $request->id;
        $contest = Contest::find($id);
        $client_group = ($contest->client_group != '') ? ClientGroupService::group_name($contest->client_group) : '--';
        // prize table
        $prizes = json_decode($contest->contest_prices);
        $prizes_table = '<table>';
        foreach ($prizes as $prize) {
            foreach ($prize as $key => $value) {
                $prizes_table .= '<tr>
                                <th>' . $key . '</th>
                                <th>:</th>
                                <td> $ ' . $value . '</td></tr>';
            }
        }
        $prizes_table .= '</table>';
        // kyc required or not
        $kyc = ($contest->kyc == 0) ? 'Not Required' : 'Required';
        // country
        $country = '';
        if ($contest->is_global == 1) {
            $country = 'All Countries';
        } else {
            $country = '<button type="button" class="btn btn-sm btn-success">Countryies <span class="badge badge-white bg-white">10</span></button>';
        }
        $description = '<tr class="description" style="display:none;">
        <td colspan="8">
            <div class="details-section-dark border-start-3 border-start-primary p-2 bg-light-secondary">
                <div class="row">
                    <div>
                        <div class="rounded-0 w-70">
                            <div class="card-body">    
                                <table class="table table-responsive tbl-balance">
                                    <tr>
                                        <th colspan="2" class="border-bottom-3 border-bottom-success"><h3>Contest Description :</h3></th>
                                    </tr>
                                    <tr>
                                        <th class="w-50 border-bottom-0 border-start-3 border-start-success">Contest Type</th>
                                        <td class="border-end-0  w-50">' . ucwords(str_replace('_', ' ', $contest->contest_type)) . '</td>
                                    </tr>
                                    <tr>
                                        <th class="w-50 border-bottom-0 border-start-3 border-start-success">Client Type</th>
                                        <td class="border-end-0  w-50">' . ucwords(str_replace('_', ' ', $contest->allowed_for)) . '</td>
                                    </tr>
                                    <tr>
                                        <th class="w-50 border-bottom-0 border-start-3 border-start-success">Client Group</th>
                                        <td class="border-end-0  w-50">' . $client_group . '</td>
                                    </tr>
                                    <tr>
                                        <th class="w-50 border-bottom-0 border-start-3 border-start-success">KYC</th>
                                        <td class="border-end-0  w-50">' . $kyc . '</td>
                                    </tr>
                                    <tr>
                                        <th class="w-50 border-bottom-0 border-start-3 border-start-success">Country</th>
                                        <td class="border-end-0  w-50">' . $country . '</td>
                                    </tr>
                                    <tr>
                                        <th class="w-50 border-bottom-0 border-start-3 border-start-success">Minimum participant</th>
                                        <td class="border-end-0  w-50">' . $contest->min_join . '</td>
                                    </tr>
                                    <tr>
                                        <th class="w-50 border-bottom-0 border-start-3 border-start-success">Maximum participant</th>
                                        <td class="border-end-0  w-50">' . $contest->max_contest . '</td>
                                    </tr>
                                    <tr>
                                        <th class="w-50 border-bottom-0 border-start-3 border-start-success">Contest Prize</th>
                                        <td class="border-end-0  w-50">' . $prizes_table . '</td>
                                    </tr>
                                </table>
                                <div class="d-flex justify-content-end mt-3">
                                    <button class="btn btn-success me-2 btn-view-popup" data-popup="' . asset('Uploads/contest/' . $contest->popup_image) . '">View popup image</button>
                                    <button class="btn btn-danger btn-close-contest" data-contest_id="' . $contest->id . '">Close contest</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    
                </div>
            </div>';
        $data = [
            'status' => true,
            'description' => $description
        ];
        return Response::json($data);
    }
}
