<?php

namespace App\Http\Controllers\admins\Contest;

use App\Http\Controllers\Controller;
use App\Models\ClientGroup;
use App\Models\Contest;
use App\Models\Country;
use App\Models\IbGroup;
use App\Services\AllFunctionService;
use App\Services\api\FileApiService;
use App\Services\client_groups\ClientGroupService;
use App\Services\contest\ContestService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class ContentListController extends Controller
{
    public function __construct()
    {
        // $this->middleware(["role:support"]);
        // $this->middleware(["role:client ticket"]);
        // system module control
        $this->middleware(AllFunctionService::access('contest', 'admin'));
        $this->middleware(AllFunctionService::access('contest_list', 'admin'));
    }
    public function ContestList(Request $request)
    {
        $op = $request->input('op');

        if ($op == "data_table") {
            return $this->ContentListReport($request);
        }
        $countries = Country::select()->get();
        $group = ClientGroup::where('visibility', 'visible')->get();
        $ib_group = IbGroup::get();
        return view(
            'admins.contest.contest-test',
            [
                'countries' => $countries,
                'groups' => $group,
                'ib_group' => $ib_group,
            ]
        );
    }

    public function ContentListReport(Request $request)
    {
        try {
            $columns = ['contest_name', 'user_type', 'contest_type', 'contest_amount', 'status', 'created_at', 'created_at', 'created_at'];
            $orderby = $columns[$request->order[0]['column']];

            $result = Contest::select();
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
                    $status = '<span class="badge badge-warning bg-warning">' . ucwords($value->status) . '</span>';
                }
                $data[] = [
                    'contest_name' => '<a href="#" data-id=' . $value->id . ' class="dt-description  justify-content-between"><span class="w"> <i class="plus-minus" data-feather="plus"></i> </span> <span>' .  ucwords($value->contest_name) . '</span></a>',
                    'client_type' => ($value->user_type === 'trader') ? ucwords($value->user_type) : strtoupper($value->user_type),
                    'total_join' => ContestService::count_total_participant($value->id),
                    'date_range' => 'Start: ' . date('d M Y', strtotime($value->start_date)) . '<br/>End: ' . date('d M Y', strtotime($value->end_date)),
                    'create_date' => date('d M y', strtotime($value->created_at)),
                    'status' => $status,
                    'action' => '<a href="#" class="btn btn-sm btn-success btn-edit-contest"  data-contest_id="' . $value->id . '" ><i data-feather="edit" ></i></a> <a href="#" class="btn btn-sm btn-danger btn-delete-contest"  data-contest_id="' . $value->id . '" ><i data-feather="delete" ></i></a>',
                ];
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

    public function ContestListDescription(Request $request,)
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
                                    <button class="btn btn-success me-2 btn-view-popup" data-id="' . $contest->id . '">View popup image</button>
                                    <button class="btn btn-danger me-2 btn-close-contest" data-contest_id="' . $contest->id . '">Close contest</button>
                                    <button class="btn btn-warning btn-announce-result" data-contest_id="' . $contest->id . '">Announce Result</button>
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
    // get popup image from contabo file
    public function popup_image(Request $request)
    {
        try {
            $contest = Contest::find($request->id);
            $pop_up_image = FileApiService::contabo_file_path(isset($contest->popup_image) ? $contest->popup_image : '');
            return Response::json([
                'status'=>true,
                'file_url' => $pop_up_image['dataUrl'],
                'file_type' => $pop_up_image['file_type'],
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return Response::json([
                'status'=>false,
                'file_url' => $pop_up_image['dataUrl'],
                'file_type' => $pop_up_image['file_type'],
            ]);
        }
    }

    /**
     * Check contest status
     */
    public function checkContestStatus(Request $request)
    {
        try {
            $contest = Contest::find($request->contest_id);
            
            if (!$contest) {
                return Response::json([
                    'status' => false,
                    'message' => 'Contest not found'
                ]);
            }
            
            return Response::json([
                'status' => true,
                'contest_status' => $contest->status
            ]);
            
        } catch (\Exception $e) {
            return Response::json([
                'status' => false,
                'message' => 'Error checking contest status'
            ]);
        }
    }

    /**
     * Get contest result data
     */
    public function getContestResultData(Request $request)
    {
        try {
            $contest = Contest::find($request->contest_id);
            
            if (!$contest) {
                return Response::json([
                    'status' => false,
                    'message' => 'Contest not found'
                ]);
            }
            
            // Get contest prizes
            $prizes = json_decode($contest->contest_prices, true);
            if (!$prizes) {
                return Response::json([
                    'status' => false,
                    'message' => 'No prizes found for this contest'
                ]);
            }
            $prizeCount = count($prizes);
            
            // Get top participants based on total profit (since starting_balance column doesn't exist)
            $participants = \App\Models\ContestJoin::where('contest_id', $request->contest_id)
                ->orderBy('total_profit', 'desc')
                ->limit($prizeCount)
                ->get();
            
            if ($participants->isEmpty()) {
                return Response::json([
                    'status' => false,
                    'message' => 'No participants found for this contest'
                ]);
            }
            
            $winners = [];
            foreach ($participants as $index => $participant) {
                $prizeAmount = 0;
                if (isset($prizes[$index])) {
                    $prizeKey = array_keys($prizes[$index])[0];
                    $prizeAmount = $prizes[$index][$prizeKey];
                }
                
                // Get user name safely
                $userName = 'Unknown';
                try {
                    if ($participant->user_id) {
                        $user = \App\Models\User::find($participant->user_id);
                        $userName = $user ? $user->name : 'Unknown';
                    }
                } catch (\Exception $e) {
                    $userName = 'Unknown';
                }
                
                // For closed contests, use total_profit as equity (based on closed trades)
                $equity = $participant->total_profit ?? 0;
                
                $winners[] = [
                    'user_name' => $userName,
                    'account_number' => $participant->account_number ?? 'N/A',
                    'equity' => number_format($equity, 2),
                    'profit' => number_format($participant->total_profit ?? 0, 2),
                    'prize_amount' => number_format($prizeAmount, 2)
                ];
            }
            
            return Response::json([
                'status' => true,
                'data' => [
                    'contest_id' => $contest->id,
                    'contest_name' => $contest->contest_name,
                    'winners' => $winners,
                    'prize_count' => $prizeCount
                ]
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error in getContestResultData: ' . $e->getMessage());
            return Response::json([
                'status' => false,
                'message' => 'Error getting contest result data: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Announce contest result
     */
    public function announceResult(Request $request)
    {
        try {
            $contest = Contest::find($request->contest_id);
            
            if (!$contest) {
                return Response::json([
                    'status' => false,
                    'message' => 'Contest not found'
                ]);
            }
            
            if ($contest->status !== 'closed') {
                return Response::json([
                    'status' => false,
                    'message' => 'Contest must be closed before announcing results'
                ]);
            }
            
            // Get contest prizes
            $prizes = json_decode($contest->contest_prices, true);
            if (!$prizes) {
                return Response::json([
                    'status' => false,
                    'message' => 'No prizes found for this contest'
                ]);
            }
            $prizeCount = count($prizes);
            
            // Get top participants based on frozen equity (for closed contests) or total profit
            $participants = \App\Models\ContestJoin::where('contest_id', $request->contest_id)
                ->orderByRaw('CASE WHEN frozen_equity IS NOT NULL AND frozen_equity > 0 THEN frozen_equity ELSE total_profit END DESC')
                ->limit($prizeCount)
                ->get();
            
            if ($participants->isEmpty()) {
                return Response::json([
                    'status' => false,
                    'message' => 'No participants found for this contest'
                ]);
            }
            
            $winners = [];
            foreach ($participants as $index => $participant) {
                $rank = $index + 1;
                
                // Get prize amount based on rank
                $prizeAmount = 0;
                $prizeIndex = $index; // Use direct index for correct prize assignment
                if (isset($prizes[$prizeIndex])) {
                    $prizeKey = array_keys($prizes[$prizeIndex])[0];
                    $prizeAmount = $prizes[$prizeIndex][$prizeKey];
                }
                
                // Get user name safely
                $userName = 'Unknown';
                try {
                    if ($participant->user_id) {
                        $user = \App\Models\User::find($participant->user_id);
                        $userName = $user ? $user->name : 'Unknown';
                    }
                } catch (\Exception $e) {
                    $userName = 'Unknown';
                }
                
                // For closed contests, use frozen equity if available, otherwise use total_profit
                $equity = 0;
                if ($participant->frozen_equity !== null && $participant->frozen_equity > 0) {
                    $equity = $participant->frozen_equity;
                } else {
                    $equity = $participant->total_profit ?? 0;
                }
                
                $winners[] = [
                    'rank' => $rank,
                    'user_name' => $userName,
                    'account_number' => $participant->account_number ?? 'N/A',
                    'equity' => number_format($equity, 2),
                    'profit' => number_format($participant->total_profit ?? 0, 2),
                    'prize_amount' => number_format($prizeAmount, 2)
                ];
            }
            
            // Update contest to mark results as announced
            $contest->update([
                'results_announced' => true
            ]);
            
            return Response::json([
                'status' => true,
                'message' => 'Results announced successfully',
                'data' => [
                    'contest_id' => $contest->id,
                    'contest_name' => $contest->contest_name,
                    'winners' => $winners,
                    'prize_count' => $prizeCount
                ]
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error announcing results: ' . $e->getMessage());
            return Response::json([
                'status' => false,
                'message' => 'Error announcing results: ' . $e->getMessage()
            ]);
        }
    }
}
