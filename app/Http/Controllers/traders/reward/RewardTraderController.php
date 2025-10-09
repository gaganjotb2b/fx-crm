<?php

namespace App\Http\Controllers\traders\reward;

use App\Http\Controllers\admins\BalanceTransferController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Reward;
use App\Models\RewardCountry;
use App\Models\Deposit;
use App\Models\TraderReward;
use App\Models\IB;
use App\Models\RewardDependency;
use App\Models\IbIncome;
use App\Models\User;
use App\Models\ExternalFundTransfers;





use App\Services\BalanceService;
use App\Services\CombinedService;
use App\Services\EmailService;
use App\Services\TransactionService;
use App\Services\MailNotificationService;

use App\Utils\RewardAssignStatus;
use App\Utils\RewardType;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Response;



class RewardTraderController extends Controller
{
    public function rewardTraderView(){
        return view('traders.reward.list');
    }


    public function rewardTraderQuery($id = null)
    {
        $user = auth()->user();
        $today = now();
        
        // Determine the parent user ID (if exists)
        $parentUserId = IB::where('reference_id', $user->id)->value('ib_id');
    
        // Decode clients_group into an array
        $clientGroups = json_decode($user->clients_group, true);
        if (!is_array($clientGroups)) {
            $clientGroups = []; // Ensure it's always an array
        }
    
        // Fetch rewards based on conditions
        $query = Reward::select('rewards.*')
            ->join('trader_reward', 'trader_reward.reward_id', '=', 'rewards.id')
            ->leftJoin('reward_groups', 'rewards.id', '=', 'reward_groups.reward_id')
            ->leftJoin('reward_country', 'rewards.id', '=', 'reward_country.reward_id')
            ->leftJoin('user_descriptions', function ($join) use ($user) {
                $join->on('user_descriptions.user_id', '=', DB::raw($user->id));
            })
            ->where(function ($query) use ($user, $parentUserId, $today, $clientGroups) {
                $query->where('rewards.is_global', 1) // Include global rewards
                    ->orWhere(function ($q) use ($user, $parentUserId, $clientGroups) {
                        $q->where('rewards.is_global', 0)
                            ->where(function ($subQuery) use ($user, $parentUserId, $clientGroups) {
                                $subQuery->where('rewards.user_id', $parentUserId) // Parent user's rewards
                                        ->orWhereColumn('reward_country.country_id', 'user_descriptions.country_id'); // Country match
    
                                // Client group match
                                if (!empty($clientGroups)) {
                                    $subQuery->orWhereIn('reward_groups.group_id', $clientGroups);
                                }
                            });
                    });
            })
            ->where('rewards.end_date', '>=', $today)
            ->where('rewards.is_active', true)
            ->whereNotExists(function ($sub) use ($user) {
                $sub->select(DB::raw(1))
                    ->from('trader_reward')
                    ->whereColumn('trader_reward.reward_id', 'rewards.id')
                    ->where('trader_reward.user_id', $user->id)
                    ->where('trader_reward.status', RewardAssignStatus::$SUSSPEND);
            });
            
    
        if ($id) {
            // Check if a specific reward ID is passed in and filter by that
            $query->where('rewards.id', $id);
        }
    
        return $query->distinct();
    }
    

    public function rewardList(Request $request)
    {
        try {
            

            $result = $this->rewardTraderQuery();
            $user = auth()->user();

            $assign_reward = TraderReward::where('user_id', $user->id)->where('status', RewardAssignStatus::$OPEN)->first();

            // Apply filters
            if ($request->contest_name != "") {
                $result->where('rewards.name', 'like', '%' . $request->contest_name . '%');
            }
            if ($request->date_from != "") {
                $result->whereDate('rewards.created_at', '>=', $request->date_from);
            }
            if ($request->date_to != "") {
                $result->whereDate('rewards.created_at', '<=', $request->date_to);
            }

            // Get total count
            $count = $result->count();

            // Apply ordering and pagination
            $columns = ['name', 'amount', 'start_date', 'end_date', 'created_at'];
            $orderby = $columns[$request->order[0]['column']];
            $result = $result->orderBy($orderby, $request->order[0]['dir'])
                ->skip($request->start)
                ->take($request->length)
                ->get();

            $data = [];

            foreach ($result as $value) {
                
                $action = '';
                if($value->is_active != 0 && !$assign_reward){
                    
                    # check if the reward already assign to the current user
                    $count = TraderReward::where('user_id', $user->id)
                    ->where('reward_id', $value->id)
                    ->count();
                    
                    if ($count == 0)
                        $action = '<span class="btn btn-sm btn-success btn-assign-reward"  data-id='.$value->id.'>Join Reward</span>';
                    else
                        $action = '<span class="btn btn-sm btn-secondary disabled">Already Joined</span>';

                }

                $deposit = 0;
                $reffer_user = 0;
                $lot = 0;

                $dependencies = RewardDependency::where("reward_id", $value->id)->get();

                foreach($dependencies as $dependency){
                    
                    if(RewardType::$options[$dependency->type] == 'Deposit'){
                        $deposit = $dependency->value;
                    }else if(RewardType::$options[$dependency->type] == 'User'){
                        $reffer_user = $dependency->value;
                    }else if(RewardType::$options[$dependency->type] == 'Lot'){
                        $lot = $dependency->value;
                    }
                }

                $terms_condition = '
                   <div style="display: flex; flex-direction: column; gap: 10px; padding: 15px; border-radius: 8px; width: fit-content;">
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <i class="fas fa-user" style="color: #ff8e5c; font-size: 18px;"></i> 
                        <span style="font-weight: bold;">Reffer User:</span> '.$reffer_user.'
                    </div>
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <i class="fas fa-money-bill-wave" style="color: #ff8e5c; font-size: 18px;"></i> 
                        <span style="font-weight: bold;">Deposit:</span> '.$deposit.'
                    </div>
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <i class="fas fa-cubes" style="color: #ff8e5c; font-size: 18px;"></i> 
                        <span style="font-weight: bold;">Lot Required:</span> '.$lot.'
                    </div>
                </div>
                ';

                $data[] = [
                    'reward_name' => '<a href="#" data-id=' . $value->id . ' class="dt-description  justify-content-between"><span class="w"> <i class="plus-minus" data-feather="plus"></i> </span> <span>' .  ucwords($value->name) . '</span></a>',
                    'reward_amount' => $value->amount,
                    'date_range' => 'Start: ' . date('d M Y', strtotime($value->start_date)) . '<br/>End: ' . date('d M Y', strtotime($value->end_date)),
                    'create_date' => date('d M y', strtotime($value->created_at)),
                    'action' => $action,
                    'terms_conditions' => $terms_condition
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
            // dd($th);
            return Response::json([
                'draw' => $request->draw,
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => [],
                'error' => $th
            ]);
        }


    }

    public function assignReward($id)
    {
        try {
            $query = $this->rewardTraderQuery();
            $reward = $query->where('rewards.id', $id)->first();

            if ($reward) {
                $user = auth()->user();

                // Check if the user already assigned this reward
                $existingAssignment = TraderReward::where('reward_id', $reward->id)
                    ->where('user_id', $user->id)
                    ->exists();

                if ($existingAssignment) {
                    return Response::json([
                        'success' => false,
                        'message' => 'You have already assigned this reward.',
                    ], 400);
                }

                // Assign the reward
                TraderReward::create([
                    'reward_id' => $reward->id,
                    'user_id' => $user->id,
                    'status' => 1, // Assuming 1 means "assigned"
                ]);

                return Response::json([
                    'success' => true,
                    'message' => 'Reward assigned successfully.',
                ], 200);
            } else {
                return Response::json([
                    'success' => false,
                    'message' => 'Reward not found or not eligible for join this reward.',
                ], 404);
            }
        } catch (\Throwable $th) {
            return Response::json([
                'success' => false,
                'message' => 'An error occurred while assigning the reward.',
                'error' => $th->getMessage(),
            ], 500);
        }
    }

    public function fetchOpenRewardAndItsDependency(){
        $user = auth()->user();
        $assign_reward = TraderReward::where('user_id', $user->id)->where('status', RewardAssignStatus::$OPEN)->first();

        dd($assign_reward);
        if(!$assign_reward){
            return [
                'depositSum' => 0,
                'ibUserCount' => 0,
                'totalDepositAmount' => 0,
                'totalUserCount' => 0,
                'reward' => null,
                'isComplete' => false,
                'totalLot' => 0,
                'acheiveLot' => 0
            ];
        }
        $reward = Reward::where('id', $assign_reward->reward_id)->first();
        $reward_dependencies = RewardDependency::where('reward_id', $reward->id)->get();
        $depositSum  = 0;
        $ibUserCount = 0;
        $totalDepositAmount = 0;
        $totalUserCount = 0;
        $totalLot = 0;
        $completeLot = 0;
        $isComplete = true;
        foreach($reward_dependencies as $dependency){
            if(RewardType::$options[$dependency->type] == 'Deposit'){
                $depositSum = Deposit::where('user_id', $user->id)
                ->where('created_at', '>=', $reward->created_at)
                ->where('created_at', '<=', $reward->end_date)
                ->sum('amount');
                $totalDepositAmount = $dependency->value;
                if ($totalDepositAmount > $depositSum){
                    $isComplete = false;
                }
            }else if(RewardType::$options[$dependency->type] == 'User'){
                $ibUserCount = IB::where('reference_id', $user->id)
                ->where('created_at', '>=', $reward->start_date)
                ->where('created_at', '<=', $reward->end_date)
                ->count();
                $totalUserCount = $dependency->value;
                if($totalUserCount > $ibUserCount){
                    $isComplete = false;
                }
            }else if(RewardType::$options[$dependency->type] == 'Lot'){
                $total_lot = IbIncome::where('ib_id', $user->id)
                ->where('created_at', '>=', $reward->start_date)
                ->where('created_at', '<=', $reward->end_date)
                ->sum('volume');
                $completeLot = round(($total_lot / 100), 2);

                $totalLot = $dependency->value;
                if($totalLot > $completeLot){
                    $isComplete = false;
                }
            }

        }

        return [
            'depositSum' => $depositSum,
            'ibUserCount' => $ibUserCount,
            'totalDepositAmount' => $totalDepositAmount,
            'totalUserCount' => $totalUserCount,
            'reward' => $reward,
            'isComplete' => $isComplete,
            'assign_reward' => $assign_reward,
            'totalLot' => $totalLot,
            'acheiveLot' => $completeLot
        ];
    }

    public function fetchOpenRewardWithDependency(){
        
        $reward_data = $this->fetchOpenRewardAndItsDependency();

        // Count users from IB table where reference_id matches user_id
        
        $status = $reward_data['reward'] != null ? 200 : 404;
        return Response::json([
            'success' => $reward_data['reward'] != null,
            'data' => [
                'deposit_sum' => $reward_data['depositSum'],
                'user_count' => $reward_data['ibUserCount'],
                'total_deposit_amount' => $reward_data['totalDepositAmount'],
                'total_user_count' => $reward_data['totalUserCount'],
                'reward' => $reward_data['reward'],
                'is_complete' => $reward_data['isComplete'],
                'total_lot' => $reward_data['totalLot'],
                'acheive_lot' => $reward_data['acheiveLot']
            ],
        ], $status);

    }


    public function cancelReward(){
        $reward_data = $this->fetchOpenRewardAndItsDependency();
        
        if($reward_data['reward'] == null){
            response()->json([
                'success' => false,
                'message' => 'No assigned reward found'
            ], 404);
        }
        
        $assign_reward = $reward_data['assign_reward'];
        $assign_reward = $reward_data['assign_reward'];

        $assign_reward->status = RewardAssignStatus::$CANCELD;
        $assign_reward->save();

        return response()->json([
            'success' => true,
            'message' => 'Reward association canceled.'
        ], 200);
       

    }

    public function claimReward(){
        $reward_data = $this->fetchOpenRewardAndItsDependency();
        
        if ($reward_data['reward'] == null) {
            return response()->json([
                'success' => false,
                'message' => 'No assigned reward found'
            ], 404);
        }

        if($reward_data['is_complete'] == false){
            return response()->json([
                'success' => false,
                'message' => 'You have to fulfill the requirement first to receive the reward.'
            ], 403);
        }
    
        try {
            $assign_reward = $reward_data['assign_reward'];
            $assign_reward->status = RewardAssignStatus::$CLAIME;
            $assign_reward->save();
    
            return response()->json([
                'success' => true,
                'message' => "Your request has been sent to the reward creator."
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while assigning the reward.',
                'error' => $th->getMessage(),
            ], 500);
        }
    }




    public function claimRewardListReport(Request $request)
    {
        try {
            $columns = ['name', 'amount', 'start_date', 'end_date', 'created_at'];
            $orderby = $columns[$request->order[0]['column']];
            $user = auth()->user();
            $result = Reward::select('rewards.*', 'trader_reward.id as reward_trader_id', 'users.name as trader_name')
            ->join('trader_reward', 'rewards.id', '=', 'trader_reward.reward_id')
            ->join('users', 'trader_reward.user_id', '=', 'users.id')
            ->where('trader_reward.status', 3)->where('rewards.user_id', $user->id);
            // filter by status
            // if ($request->status != "") {
            //     $result = $result->where('status', $request->status);
            // }
            // filter by client type
            // if ($request->client_type != "") {
            //     $result = $result->where('allowed_for', $request->client_type);
            // }
            // filter by contest_name
            if ($request->contest_name != "") {
                $result = $result->where('rewards.name', 'like', '%' . $request->contest_name . '%');
            }
            // filter by contest type
            // if ($request->contest_type != "") {
            //     $result = $result->where('contest_type', $request->contest_type);
            // }
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
                // $status = '';
                // if ($value->status === 'active') {
                //     $status = '<span class="badge badge-success bg-success">' . ucwords($value->status) . '</span>';
                // } elseif ($value->status === 'disable') {
                //     $status = '<span class="badge badge-danger bg-danger">' . ucwords($value->status) . '</span>';
                // } else {
                //     $status = '<span class="badge badge-warning bg-warning">' . ucwords($value->status) . '</span>';
                // }

                
                $dependencies = RewardDependency::where("reward_id", $value->id)->get();
                $deposit  = 0;
                $reffer_user = 0;
                $lot = 0; 
                foreach($dependencies as $dependency){
                    
                    if(RewardType::$options[$dependency->type] == 'Deposit'){
                        $deposit = $dependency->value;
                    }else if(RewardType::$options[$dependency->type] == 'User'){
                        $reffer_user = $dependency->value;
                    }else if(RewardType::$options[$dependency->type] == 'Lot'){
                        $lot = $dependency->value;
                    }
                }

                $terms_condition = '
                   <div style="display: flex; flex-direction: column; gap: 10px; padding: 15px; border-radius: 8px; width: fit-content;">
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <i class="fas fa-user" style="color: #ff8e5c; font-size: 18px;"></i> 
                        <span style="font-weight: bold;">Reffer User:</span> '.$reffer_user.'
                    </div>
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <i class="fas fa-money-bill-wave" style="color: #ff8e5c; font-size: 18px;"></i> 
                        <span style="font-weight: bold;">Deposit:</span> '.$deposit.'
                    </div>
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <i class="fas fa-cubes" style="color: #ff8e5c; font-size: 18px;"></i> 
                        <span style="font-weight: bold;">Lot Required:</span> '.$lot.'
                    </div>
                </div>
                ';


               $action = '<button class="btn btn-sm btn-primary btn-approve-reward" data-reward_trader_id="'.$value->reward_trader_id.'">
                                    Approve
                            </button>';
                $data[] = [
                    'trader_name' => '<span class="justify-content-between"><span>' .  ucwords($value->trader_name) . '</span></span>',
                    'reward_name' => '<span class="justify-content-between"><span>' .  ucwords($value->name) . '</span></span>',
                    
                    // 'reward_name' => '<span class="dt-description  justify-content-between"><span>' .  ucwords($value->name) . '</span></span>',
                    // 'reward_amount' => ($value->user_type === 'trader') ? ucwords($value->user_type) : strtoupper($value->user_type),
                    'reward_amount' => $value->amount,
                    // 'total_join' => ContestService::count_total_participant($value->id),
                    'date_range' => 'Start: ' . date('d M Y', strtotime($value->start_date)) . '<br/>End: ' . date('d M Y', strtotime($value->end_date)),
                    'create_date' => date('d M y', strtotime($value->created_at)),
                    // 'status' => $status,
                    'action' => $action,
                    'terms_condition' => $terms_condition
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
                'data' => [],
                "error" => $th
            ]);
        }
    }


    public function rewardClaimView(){
        return view('traders.reward.reward-claim');
    }


    public function rewardTransfer($reward_trader_id, $user=null){
        $reward_trader = TraderReward::where('id', $reward_trader_id)->first();
        $reward = Reward::where('id', $reward_trader->reward_id)->first();
        // dd($reward);
        if ($user == null)
            $user = auth()->user();
        
        if (!$reward){
            return response()->json([
                "success"=> false,
                "message" => "No reward found"
            ]);
        }

        $recipient = User::where('id', $reward_trader->user_id)->first();

        if($user->type != 'admin_user' && $reward->user_id != $user->id){
            return response()->json([
                "success"=> false,
                "message" => "No reward found"
            ], 404);
        }


        if (!$recipient){
            return response()->json([
                "success"=> false,
                "message" => "No recepient found"
            ], 404);
        }

        if ($user->type != 'admin_user'){

            $balance = BalanceService::get_ib_balance_v2($user->id);
            // return $balance;
            // check available balance---------
            if ($balance <= 0 || $reward->amount > $balance) {
                return response()->json([
                    'valid_status' => false,
                    'errors' => ['amount' => "You don't have available balance!"],
                    'message' => "You don't have sufficient balance!"
                ]);
            }

        }
        
        $charge = TransactionService::charge('w_to_w', $reward->amount, null);
        $data = [
            'recipient' => $recipient->id,
            'amount' => $reward->amount,
            'charge' => $charge,
            'name' => $user->name,
            'request_all' => [],
            'user' => $user,
        ];
        $response = $this->make_transaction($data);
        // dd("ok");
        
        $reward_trader->status = RewardAssignStatus::$APPROVED;
        $reward_trader->save();


        return response()->json($response);

    }


    private function make_transaction($data)
    {
        $user = $data['user'];
        $balance = BalanceService::get_ib_balance_v2($user->id);
        $invoice = substr(hash('sha256', mt_rand() . microtime()), 0, 16);
        
        $receiver = User::where('id', $data['recipient'])->select('id', 'type', 'name', 'email', 'combine_access')->first();
        // check receiver type
        // check crm type
        if ($receiver->type !== 'trader' && CombinedService::is_combined() == false) {
            return ([
                'status'    => false,
                'message'   => 'The Receiver is not a Trader!'
            ]);
        }
        
        $created = ExternalFundTransfers::create([
            'sender_id' => $user->id,
            'receiver_id' => $receiver->id,
            'amount' => $data['amount'],
            'charge' => $data['charge'],
            'type' => 'ib_to_trader',
            'status' => 'P',
            'txnid' => $invoice,
            'sender_wallet_type' => 'ib',
            'receiver_wallet_type' => 'trader',
            'ip_address' => request()->ip,

        ]);

        //mail script
        if ($created) {
            //notification mail to admin
            // MailNotificationService::notification('balance transfer', 'ib', 1, $data['name'], $data['amount']);
            MailNotificationService::admin_notification([
                'amount'=>$data['amount'],
                'name' => $user->name,
                'email' => $user->email,
                'type' => 'balance transfer',
                'client_type' => 'ib'
            ]);

            
            // get last transaction----------------
            $last_transaction = TransactionService::last_transaction(null, 'ib_to_trader');
            // send mail to client
            // dd($last_transaction);
            // $transaction_amount = $last_transaction != null ? $last_transaction->amount : 0;
            // EmailService::send_email('ib-to-trader-transfer', [
            //     'clientWithdrawAmount' => $data['amount'],
            //     'user_id' => $user->id,
            //     'transfer_date' => ($last_transaction) ? ucwords($last_transaction->created_at) : '',
            //     'previous_balance' => (($balance) + ($transaction_amount)),
            //     'transfer_amount' => $transaction_amount,
            //     'total_balance' => $balance,
            //     'reciever_name' => ucwords($receiver->name),
            //     'reciever_email' => $receiver->email,
            // ]);
            // dd($data['amount']);
            // insert activity-----------------
            //<---client email as user id
            activity("IB to trader transfer")
                ->causedBy(auth()->user()->id)
                ->withProperties($data['request_all'])
                ->event("IB to trader transfer")
                ->performedOn($data['user'])
                ->log("The IP address " . request()->ip() . " has been " .  "IB to trader transfer");
            // end activity log----------------->>
            request()->session()->forget('trader-transfer-otp');
            request()->session()->forget('otp_set_time');
            return ([
                'success' => true,
                'message' => 'Transaction successfully done!',
                'last_transaction' => $last_transaction,
                'external_fund_transfer_id' => $created->id
            ]);
        }
        return ([
            'success' => false,
            'message' => 'Somthing went wrong, please try again later!',
        ]);
    }



    public function adminRewardTransfer($reward_trader_id){
        $user = User::where('type', 6)->first();
        $response = $this->rewardTransfer($reward_trader_id, $user);
        $data = $response->getData(true);
        if($data['success'] == true){
            $externalFundTransferId = $data['external_fund_transfer_id'];
            $balanceTransferController = new BalanceTransferController();
            $request = new Request();
            $balanceTransferController->approveBalanceRequest($request,$externalFundTransferId);
        }
        return $response;
    }
}