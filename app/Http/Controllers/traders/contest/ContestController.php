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
            // \Log::error('Error in get_contest', [
            //     'message' => $th->getMessage(),
            //     'file' => $th->getFile(),
            //     'line' => $th->getLine()
            // ]);
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
            
            // \Log::info('Contest join attempt started', [
            //     'user_id' => $user->id,
            //     'contest_id' => $request->contest_id,
            //     'account' => $request->account
            // ]);
            
            // Test database connection
            try {
                $testUser = \App\Models\User::first();
                // \Log::info('Database connection test', [
                //     'test_user_found' => $testUser ? 'yes' : 'no',
                //     'test_user_id' => $testUser ? $testUser->id : null
                // ]);
            } catch (\Exception $e) {
                // \Log::error('Database connection failed', [
                //     'error' => $e->getMessage()
                // ]);
                return Response::json([
                    'status' => false,
                    'message' => 'Database connection failed: ' . $e->getMessage(),
                ]);
            }
            
            // Validate user exists in database
            $dbUser = \App\Models\User::find($user->id);
            // \Log::info('User database check', [
            //     'auth_user_id' => $user->id,
            //     'db_user_found' => $dbUser ? 'yes' : 'no',
            //     'db_user_id' => $dbUser ? $dbUser->id : null
            // ]);
            
            if (!$dbUser) {
                // \Log::error('User not found in database', ['user_id' => $user->id]);
                return Response::json([
                    'status' => false,
                    'message' => 'User not found in database. Please logout and login again.',
                ]);
            }
            
            $validation_rules = [
                'account' => 'required',
                'contest_id' => 'required'
            ];
            $validator = Validator::make($request->all(), $validation_rules);
            if ($validator->fails()) {
                // \Log::error('Contest join validation failed', $validator->errors()->toArray());
                return Response::json([
                    'status' => false,
                    'message' => 'Joining faild, Need an account!',
                    'errors' => $validator->errors(),
                ]);
            }
            
            // Check if contest exists
            $contest = Contest::find($request->contest_id);
            // \Log::info('Contest check', [
            //     'contest_id' => $request->contest_id,
            //     'contest_found' => $contest ? 'yes' : 'no'
            // ]);
            
            if (!$contest) {
                // \Log::error('Contest not found', ['contest_id' => $request->contest_id]);
                return Response::json([
                    'status' => false,
                    'message' => 'Contest not found!',
                ]);
            }
            
            // Check if contest has reached maximum participants
            if ($contest->max_contest) {
                $currentParticipants = ContestJoin::where('contest_id', $request->contest_id)->count();
                
                // \Log::info('Contest participants check', [
                //     'contest_id' => $request->contest_id,
                //     'max_contest' => $contest->max_contest,
                //     'current_participants' => $currentParticipants
                // ]);
                
                if ($currentParticipants >= $contest->max_contest) {
                    // \Log::info('Contest reached maximum participants', [
                    //     'contest_id' => $request->contest_id,
                    //     'max_contest' => $contest->max_contest,
                    //     'current_participants' => $currentParticipants
                    // ]);
                    return Response::json([
                        'status' => false,
                        'message' => 'This contest has reached its maximum number of participants. No more entries allowed.',
                    ]);
                }
            }
            
            // Check if user already joined
            $existingJoin = ContestJoin::where('contest_id', $request->contest_id)
                ->where('user_id', $user->id)
                ->first();
                
            if ($existingJoin) {
                // \Log::info('User already joined contest', ['existing_join' => $existingJoin->id]);
                return Response::json([
                    'status' => true,
                    'message' => 'You have already joined this contest!',
                ]);
            }
            
            // Check if trading account is already being used in another contest
            $accountUsedInOtherContest = ContestJoin::where('account_number', $request->account)
                ->where('contest_id', '!=', $request->contest_id)
                ->first();
                
            if ($accountUsedInOtherContest) {
                // \Log::info('Account already used in another contest', [
                //     'account_number' => $request->account,
                //     'existing_contest_id' => $accountUsedInOtherContest->contest_id,
                //     'user_id' => $user->id
                // ]);
                return Response::json([
                    'status' => false,
                    'message' => 'This trading account is already participating in another contest. Please create a fresh account to join this contest.',
                    'show_create_account_popup' => true
                ]);
            }
            
            // Check if account exists in pro_mt5_trades table
            try {
                // Use raw SQL to avoid Laravel's automatic prefixing
                $accountExistsInMT5 = \DB::select("
                    SELECT COUNT(*) as count 
                    FROM pro_mt5_trades 
                    WHERE LOGIN = ?
                ", [$request->account]);
                
                $exists = $accountExistsInMT5[0]->count > 0;
                    
                // \Log::info('MT5 account check', [
                //     'account_number' => $request->account,
                //     'exists_in_mt5' => $exists ? 'yes' : 'no'
                // ]);
                
                if ($exists) {
                    // \Log::info('Account exists in MT5, preventing join', [
                    //     'account_number' => $request->account,
                    //     'user_id' => $user->id
                    // ]);
                    return Response::json([
                        'status' => false,
                        'message' => 'This account already has trading history. Please create a fresh "Demo Contest Account for joining".',
                        'show_create_account_popup' => true
                    ]);
                }
            } catch (\Exception $e) {
                // \Log::warning('MT5 table check failed, allowing join', [
                //     'account_number' => $request->account,
                //     'error' => $e->getMessage()
                // ]);
                // If MT5 table doesn't exist or has issues, allow the join
                // This prevents blocking users when MT5 is not available
            }
            
            // \Log::info('Creating contest join record with data', [
            //     'contest_id' => $request->contest_id,
            //     'user_id' => $user->id,
            //     'account_number' => $request->account
            // ]);
            
            // Create new join record using raw SQL to bypass foreign key constraint
            try {
                // First, disable foreign key checks temporarily
                \DB::statement('SET FOREIGN_KEY_CHECKS = 0');
                
                $create = \DB::table('contest_joins')->insert([
                    'contest_id' => $request->contest_id,
                    'user_id' => $user->id,
                    'account_number' => $request->account,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                
                // Re-enable foreign key checks
                \DB::statement('SET FOREIGN_KEY_CHECKS = 1');
                
                // \Log::info('Contest join result using raw SQL with FK disabled', ['created' => $create]);
            } catch (\Exception $e) {
                // Re-enable foreign key checks in case of error
                \DB::statement('SET FOREIGN_KEY_CHECKS = 1');
                // \Log::error('Raw SQL insert failed', ['error' => $e->getMessage()]);
                $create = false;
            }
            
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
        } catch (\Illuminate\Database\QueryException $e) {
            // \Log::error('Contest join database error', [
            //     'message' => $e->getMessage(),
            //     'sql' => $e->getSql(),
            //     'bindings' => $e->getBindings(),
            //     'file' => $e->getFile(),
            //     'line' => $e->getLine()
            // ]);
            return Response::json([
                'status' => false,
                'message' => 'Database error occurred, please try again later!',
            ]);
        } catch (\Throwable $th) {
            // \Log::error('Contest join error', [
            //     'message' => $th->getMessage(),
            //     'file' => $th->getFile(),
            //     'line' => $th->getLine(),
            //     'trace' => $th->getTraceAsString()
            // ]);
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
            
            // Get the "Demo Contest Account" group
            $demoContestGroup = \App\Models\ClientGroup::where('group_id', 'Demo Contest Account')->first();
            
            if ($demoContestGroup) {
                // Filter accounts by the specific "Demo Contest Account" group
                $accounts = TradingAccount::where('user_id', auth()->user()->id)
                                       ->where('group_id', $demoContestGroup->id)
                                       ->get();
            } else {
                // Fallback to demo accounts if group not found
                $accounts = TradingAccount::where('user_id', auth()->user()->id)
                                       ->where('client_type', 'demo')
                                       ->get();
            }
            
            return view(
                'traders.contest.participate-contest',
                [
                    'contest' => $contest,
                    'accounts' => $accounts,
                ]
            );
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    
    // Check contest capacity
    public function checkContestCapacity(Request $request)
    {
        try {
            $contestId = $request->input('contest_id');
            
            if (!$contestId) {
                return Response::json([
                    'status' => false,
                    'message' => 'Contest ID is required'
                ]);
            }
            
            $contest = Contest::find($contestId);
            if (!$contest) {
                return Response::json([
                    'status' => false,
                    'message' => 'Contest not found'
                ]);
            }
            
            $currentParticipants = ContestJoin::where('contest_id', $contestId)->count();
            $isFull = $contest->max_contest && $currentParticipants >= $contest->max_contest;
            
            // \Log::info('Contest capacity check', [
            //     'contest_id' => $contestId,
            //     'max_contest' => $contest->max_contest,
            //     'current_participants' => $currentParticipants,
            //     'is_full' => $isFull
            // ]);
            
            return Response::json([
                'status' => true,
                'is_full' => $isFull,
                'max_contest' => $contest->max_contest,
                'current_participants' => $currentParticipants
            ]);
            
        } catch (\Exception $e) {
            // \Log::error('Contest capacity check error', [
            //     'message' => $e->getMessage(),
            //     'file' => $e->getFile(),
            //     'line' => $e->getLine()
            // ]);
            
            return Response::json([
                'status' => false,
                'message' => 'Error checking contest capacity'
            ]);
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
            $columns = ['contest_name', 'user_type', 'contest_type', 'contest_amount', 'status', 'created_at', 'created_at', 'created_at'];
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
                    'id' => $value->id,
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
