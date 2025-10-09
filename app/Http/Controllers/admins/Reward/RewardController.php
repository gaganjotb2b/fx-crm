<?php

namespace App\Http\Controllers\admins\Reward;

use App\Http\Controllers\Controller;
use App\Models\ClientGroup;
use App\Models\Country;
use App\Models\IbGroup;
use App\Models\Reward;
use App\Models\RewardCountry;
use App\Models\RewardDependency;
use App\Models\RewardGroups;
use App\Models\TraderReward;
use App\Models\User;
use App\Models\Deposit;
use App\Models\IbIncome;
use App\Models\IB;
use App\Utils\RewardAssignStatus;


use Illuminate\Http\Request;
use App\Utils\RewardType;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;
use App\Services\api\FileApiService;

class RewardController extends Controller
{
    //

    public function createRewardView()
    {
        $countries = Country::select()->get();
        $group = ClientGroup::whereNot('visibility', 'deleted')->where('account_category', 'live')->get();
        $ib_group = IbGroup::get();
       
        
        return view(
            'admins.reward.create-reward',
            [
                'countries' => $countries,
                'groups' => $group,
                'ib_group' => $ib_group,
                'options' => RewardType::$options,
                
            ]
        );
   
    }

    public function createReward(Request $request){
       
        try{

            $validation_rules = [
                'reward_name' => 'required|max:255',
                'to' => 'required',
                'from' => 'required',
                'reward_amount' => 'required|numeric',
                'popup_image' => 'max:2048|image|dimensions:6/2',
                'kyc' => 'nullable',
                'is_global' => 'nullable',
                'countries' => 'nullable',
                'client_groups' => 'required'

            ];
            $validator = Validator::make($request->all(), $validation_rules);
            if ($validator->fails()) {
                return Response::json([
                    'status' => false,
                    'message' => 'Please fix the following errors',
                    'errors' => $validator->errors(),
                ]);
            }

            $filename = '';
            if ($request->hasFile('popup_image')) {
                $uploaded_file = $request->file('popup_image');
                $filename = time() . '_popup_' . $uploaded_file->getClientOriginalName();
                // contabo file upload
                $client = FileApiService::s3_clients();
                $client->putObject([
                    'Bucket' => FileApiService::contabo_bucket_name(),
                    'Key' => $filename,
                    'Body' => file_get_contents($uploaded_file)
                ]);
            }

            // dd($request);
            $reward = Reward::create([
                'name' => $request->reward_name,
                'amount' => $request->reward_amount,
                'start_date' => $request->from,
                'end_date' => $request->to,
                'is_kyc' => isset($request->kyc) ? $request->kyc : false,
                'is_global' => isset($request->is_global) ? $request->is_global : false,
                'popup_img' => $filename,
                'is_admin' => isset($request->is_admin) ? true : false,
                'user_id' => $request->client_id
            ]);

            if ($request->has('countries')) {
                
                foreach ($request->countries as $country) {
                    RewardCountry::create([
                        "reward_id" => $reward->id,
                        "country_id" => (int) $country
                    ]);
                }

            }

            if(isset($request->dependencies)){
                $objects = json_decode($request->dependencies);
                foreach ($objects as $dependency) {
                    RewardDependency::create([
                        "reward_id" => $reward->id,
                        "type" => $dependency->option,
                        "value" => $dependency->value
                    ]);
                }

            }


            if(isset($request->client_groups)){
                
                $client_groups = explode(",", $request->client_groups);

                foreach ($client_groups as $client_group) {

                    RewardGroups::create([

                        "reward_id" => $reward->id,
                        "group_id" => (int)$client_group 

                    ]);

                }
            }

            return Response::json([
                'status' => true,
                'message' => 'Reward successfully created',
            ]);
        
        }catch (\Throwable $th) {
            throw $th;
            return Response::json([
                'status' => false,
                'message' => 'Got a server error!'
            ]);
        }


    }


    public function rewardList()
    {
        
        $countries = Country::select()->get();
        $group = ClientGroup::where('visibility', 'visible')->get();
        $ib_group = IbGroup::get();
        return view('admins.reward.list-reward',
            [
                'countries' => $countries,
                'groups' => $group,
                'ib_group' => $ib_group,
            ]
        );
    }

    public function rewardListReport(Request $request)
    {
        try {
            $columns = ['name', 'amount', 'start_date', 'end_date', 'created_at'];
            $orderby = $columns[$request->order[0]['column']];

            $result = Reward::select();
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
                $result = $result->where('name', 'like', '%' . $request->contest_name . '%');
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

                // $total_participants = TraderReward::where("reward_id", $value->id)->count(); 
                
               

                if ($value->is_active){
                    $action = '<a href="'.route('admin.reward.update.view', ['id' => $value->id]).'" class="btn btn-sm btn-success btn-edit-reward" ><i data-feather="edit" ></i></a>
                                    <button class="btn btn-sm btn-danger suspend-btn" data-id="' . $value->id . '">
                                        Suspend
                                    </button>';
                }else{
                    $action = '<button class="btn btn-sm btn-secondary disabled" >
                                        Suspend
                                    </button>';
                }

                $data[] = [
                    'reward_name' => '<a href="#" data-id=' . $value->id . ' class="justify-content-between"> <span>' .  ucwords($value->name) . '</span></a>',
                    // 'reward_amount' => ($value->user_type === 'trader') ? ucwords($value->user_type) : strtoupper($value->user_type),
                    'reward_amount' => $value->amount,
                    // 'total_join' => $total_participants,
                    'date_range' => 'Start: ' . date('d M Y', strtotime($value->start_date)) . '<br/>End: ' . date('d M Y', strtotime($value->end_date)),
                    'create_date' => date('d M y', strtotime($value->created_at)),
                    // 'status' => $status,
                    'action' => $action,
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


    public function updateRewardView($id){

        $reward = Reward::where('id', $id)->first();
        $rewardCountries = RewardCountry::where('reward_id', $id)->pluck('country_id')->toArray();
        $rewardGroupIds = RewardGroups::where('reward_id', $id)->pluck('group_id')->toArray();
        $rewardDependencies = RewardDependency::where('reward_id', $id)->select('type', 'value')->get()->toArray();
        $storedGroups = ClientGroup::whereIn('id', $rewardGroupIds)->select('id', 'group_id')->get();
        
        $client = null;
        
        if($reward->user_id != null)
            $client = User::where('id', $reward->user_id)->first();
        
            $selectedGroups = [];

        foreach($storedGroups as $group){
            $selectedGroups[$group->id] = $group->group_id;
        }

        $countries = Country::select()->get();
        $group = ClientGroup::whereNot('visibility', 'deleted')->where('account_category', 'live')->get();
        $ib_group = IbGroup::get();

        return view(
            'admins.reward.update-reward',
            [
                'countries' => $countries,
                'groups' => $group,
                'ib_group' => $ib_group,
                'options' => RewardType::$options,
                'reward' => $reward,
                'selectedGroups' => $selectedGroups,
                'selectedGroupIds' => $rewardGroupIds,
                'selectedCountries' => $rewardCountries,
                'storedGroups' =>  implode(',', $rewardGroupIds),
                'rewardDependencies' => $rewardDependencies,
                'client' => $client
            ]
        );

    }



    public function updateReward(Request $request, $id){
       
        try{

            $validation_rules = [
                'reward_name' => 'required|max:255',
                'to' => 'required',
                'from' => 'required',
                'reward_amount' => 'required|numeric',
                'popup_image' => 'max:2048|image|dimensions:6/2',
                'kyc' => 'nullable',
                'is_global' => 'nullable',
                'countries' => 'nullable',
                'client_groups' => 'required'

            ];
            $validator = Validator::make($request->all(), $validation_rules);
            if ($validator->fails()) {
                return Response::json([
                    'status' => false,
                    'message' => 'Please fix the following errors',
                    'errors' => $validator->errors(),
                ]);
            }

            $filename = '';
            if ($request->hasFile('popup_image')) {
                $uploaded_file = $request->file('popup_image');
                $filename = time() . '_popup_' . $uploaded_file->getClientOriginalName();
                // contabo file upload
                $client = FileApiService::s3_clients();
                $client->putObject([
                    'Bucket' => FileApiService::contabo_bucket_name(),
                    'Key' => $filename,
                    'Body' => file_get_contents($uploaded_file)
                ]);
            }

            $reward = Reward::where('id', $id)->first();
            
            $reward->name = $request->reward_name;
            $reward->amount = $request->reward_amount;
            $reward->start_date = $request->from;
            $reward->end_date = $request->to;
            $reward->is_kyc = isset($request->kyc) ? $request->kyc : false;
            $reward->is_global = isset($request->is_global) ? $request->is_global : false;
            $reward->popup_img = $filename;
            $reward->is_admin = isset($request->is_admin) ? true : false;
            $reward->user_id = isset($request->client_id) ? $request->client_id : null;
            $reward->save();
            RewardCountry::where('reward_id', $id)->delete();
            if ($request->has('countries')) {
                
                foreach ($request->countries as $country) {
                    RewardCountry::create([
                        "reward_id" => $reward->id,
                        "country_id" => (int) $country
                    ]);
                }

            }

            // dd($request->dependencies);
            RewardDependency::where('reward_id', $id)->delete();
            if(isset($request->dependencies)){
                $objects = json_decode($request->dependencies);
                foreach ($objects as $dependency) {
                    RewardDependency::create([
                        "reward_id" => $reward->id,
                        "type" => $dependency->type,
                        "value" => $dependency->value
                    ]);
                }

            }

            RewardGroups::where('reward_id', $id)->delete();
            if(isset($request->client_groups)){
                
                $client_groups = explode(",", $request->client_groups);

                foreach ($client_groups as $client_group) {

                    RewardGroups::create([

                        "reward_id" => $reward->id,
                        "group_id" => (int)$client_group 

                    ]);

                }
            }

            return Response::json([
                'status' => true,
                'message' => 'Reward successfully created',
            ]);
        
        }catch (\Throwable $th) {
            throw $th;
            return Response::json([
                'status' => false,
                'message' => 'Got a server error!'
            ]);
        }


    }



    public function rewardClaimList()
    {
        
        $countries = Country::select()->get();
        $group = ClientGroup::where('visibility', 'visible')->get();
        $ib_group = IbGroup::get();
        return view(
            'admins.reward.list-claim-reward',
            [
                'countries' => $countries,
                'groups' => $group,
                'ib_group' => $ib_group,
            ]
        );
    }


    public function claimRewardListReport(Request $request)
    {
        try {
            $columns = ['name', 'amount', 'start_date', 'end_date', 'created_at'];
            $orderby = $columns[$request->order[0]['column']];

            $result = Reward::select()->join('trader_reward', 'rewards.id', '=', 'trader_reward.reward_id')
            ->where('trader_reward.status', 3)->where('rewards.is_admin', 1);
            
            $result = Reward::select('rewards.*', 'trader_reward.id as reward_trader_id', 'users.name as trader_name')
            ->join('trader_reward', 'rewards.id', '=', 'trader_reward.reward_id')
            ->join('users', 'trader_reward.user_id', '=', 'users.id')
            ->where('trader_reward.status', 3)->where('rewards.is_admin', 1);
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
                $result = $result->where('name', 'like', '%' . $request->contest_name . '%');
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
            // dd($result);
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
                $action = '<button class="btn btn-sm btn-primary btn-approve-reward" data-reward_trader_id="'.$value->reward_trader_id.'">
                                Approve
                        </button>';

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




                $data[] = [
                    'user_name' => '<span  data-id=' . $value->id . ' class="justify-content-between"><span>' .  ucwords($value->trader_name) . '</span></span>',

                    'reward_name' => '<span  data-id=' . $value->id . ' class="justify-content-between"><span>' .  ucwords($value->name) . '</span></span>',
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
            // dd($th);
            return Response::json([
                'draw' => $request->draw,
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => []
            ]);
        }
    }


    public function toggleRewardStatus(Request $request)
    {
        $reward = Reward::find($request->id);
        if (!$reward) {
            return response()->json(['success' => false, 'message' => 'Reward not found!']);
        }

        // Toggle the status
        $reward->is_active = !$reward->is_active;
        $reward->save();

        TraderReward::where('reward_id', $request->id)
        ->update(['status' => RewardAssignStatus::$SUSSPEND]);

        return response()->json([
            'success' => true,
            'message' => 'Reward status updated successfully!',
            'status' => $reward->is_active
        ]);
    }


    public function rewardParticipantView(){
        return view('admins.reward.reward-participants');
    }





    public function fetchOpenRewardAndItsDependency($user){
        $assign_reward = TraderReward::where('user_id', $user->id)->where('status', RewardAssignStatus::$OPEN)->first();
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

    public function rewardParticipantReport(Request $request)
    {
        try {
            // if ($request->op === 'description') {
            //     return $this->dt_description($request);
            // }
            $columns = ['users.name', 'users.email', 'rewards.name', 'trader_reward.created_at'];;
            $orderby = $columns[$request->order[0]['column']];

            $result = User::join('trader_reward', 'users.id', '=', 'trader_reward.user_id')
                    ->join('rewards', 'trader_reward.reward_id', '=', 'rewards.id')
                    ->select(
                        'users.name as user_name',
                        'users.email as user_email',
                        'rewards.name as reward_name',
                        'rewards.is_active as is_active',
                        'rewards.id as reward_id',
                        'rewards.created_at as created_at',
                        'trader_reward.created_at as join_date',
                        'trader_reward.id as trader_reward_id',
                        'trader_reward.status as status'
                    )->where('trader_reward.status', '!=', RewardAssignStatus::$SUSSPEND);

            $count = $result->count();
            $result = $result->orderby($orderby, $request->order[0]['dir'])->skip($request->start)->take($request->length)->get();
            $data = array();
            $total_join_arr = [];
            foreach ($result as $value) {
                
                $reward_name = '';
                if ($value->is_active) {
                    $reward_name = '<span class="text-success">' . $value->reward_name . '</span>';
                } else {
                    $reward_name = '<span class="text-warning">' . $value->reward_name . '</span>';
                }
                // $total_join = 0;
                // if(!isset($total_join_arr[$value->reward_id])){
                //     $total_join = TraderReward::where("reward_id", $value->reward_id)->count();
                //     $total_join_arr [] = [$value->reward_id => $total_join];
                // }else{
                //     $total_join = $total_join_arr[$value->reward_id];
                // }
                $total_join = TraderReward::where("reward_id", $value->reward_id)->count();

                $progress_data = $this->fetchOpenRewardAndItsDependency($value);

                $terms_condition = '
                   <div style="display: flex; flex-direction: column; gap: 10px; padding: 15px; border-radius: 8px; width: fit-content;">
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <i class="fas fa-user" style="color: #ff8e5c; font-size: 18px;"></i> 
                        <span style="font-weight: bold;">Reffer User:</span> '.$progress_data['totalUserCount'].'
                    </div>
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <i class="fas fa-money-bill-wave" style="color: #ff8e5c; font-size: 18px;"></i> 
                        <span style="font-weight: bold;">Deposit:</span> '.$progress_data['depositSum'].'
                    </div>
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <i class="fas fa-cubes" style="color: #ff8e5c; font-size: 18px;"></i> 
                        <span style="font-weight: bold;">Lot:</span> '.$progress_data['totalLot'].'
                    </div>
                </div>
                ';



                $data[] = [
                    'user_name' => '<span>' .  ucwords($value->user_name) . '</span>',
                    // 'name' => '<span>' .  ucwords($value->name) . '</span>',
                    'user_email' => $value->user_email,
                    'reward_name' => $reward_name,
                    'join_date' => date('d M Y', strtotime($value->join_date)),
                    'total_join' => $total_join,
                    'progress' => $terms_condition,
                    // 'action' => '<button class="btn btn-danger suspend-btn" data-id=" '.$value->trader_reward_id.' ">
                    //                     Suspend
                    //                 </button>' 
                    'action' => '<button class="btn btn-danger suspend-btn" data-id=" '.$value->trader_reward_id.' ">
                                        Suspend
                                    </button>' 
                ];
            }

            return Response::json([
                'draw' => $request->draw,
                'recordsTotal' => $count,
                'recordsFiltered' => $count,
                'data' => $data,
            ]);
        } catch (\Throwable $th) {
            throw $th;
            return Response::json([
                'draw' => $request->draw,
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => [],
            ]);
        }
    }

    public function suspendTraderReward($id)
    {
        $trader_reward = TraderReward::find($id);
    
        if (!$trader_reward) {
            return response()->json(['status' => false, 'message' => 'Reward not found.'], 404);
        }
    
        $trader_reward->status = RewardAssignStatus::$SUSSPEND;
        $trader_reward->save();
    
        return response()->json(['status' => true, 'message' => 'Reward suspended successfully.']);
    }
    

}
