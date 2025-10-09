<?php

namespace App\Http\Controllers\admins\tournaments;

use App\Http\Controllers\Controller;
use App\Models\tournaments\TourGroup;
use App\Models\tournaments\TourParticipant;
use App\Models\tournaments\TourSetting;
use App\Mail\TournamentTradingStartMail;
use App\Models\User;
use App\Models\admin\SystemConfig;
use App\Models\TradingAccount;
use App\Services\CombinedService;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Carbon\Carbon;

class GroupListController extends Controller
{
    public function groupList(Request $request)
    {
        $rounds = TourGroup::select('round')->distinct()->get();
        $groups = TourGroup::select('group_name')->get();
        return view('admins.tournaments.group-list', [
            'rounds' => $rounds,
            'groups' => $groups
        ]);
    }

    public function groupListDatatable(Request $request)
    {
        try {
            $columns = ['tour_settings.tour_name', 'tour_groups.group_name', 'tour_groups.round', 'tour_groups.max_participants', 'tour_groups.start_trading', 'tour_settings.group_trading_duration', 'tour_groups.status'];
            $orderby = $columns[$request->order[0]['column']];
            $result = TourGroup::select(
                'tour_groups.id',
                'tour_settings.tour_name',
                'tour_settings.group_trading_duration',
                'tour_groups.group_name',
                'tour_groups.round',
                'tour_groups.max_participants', 
                'tour_groups.start_trading', 
                'tour_groups.duration', 
                'tour_groups.status'
            )->join('tour_settings', 'tour_groups.tournament_id', 'tour_settings.id');
            //-----------------------------------------------------------------------------------
            //Filter Start
            //-----------------------------------------------------------------------------------
            //Filter By Transaction Type
            if ($request->tour_name != "") {
                $result = $result->where("tour_settings.tour_name", $request->tour_name);
            }
            if ($request->group_name != "") {
                $result = $result->where("tour_groups.group_name", $request->group_name);
            }
            if ($request->round != "") {
                $result = $result->where("tour_groups.round", $request->round);
            }
            if ($request->status != "") {
                $result = $result->where("tour_groups.status", $request->status);
            }

            //Filter By participants
            if ($request->min != "") {
                $result = $result->where("tour_groups.max_participants", '>=', $request->min);
            }
            if ($request->max != "") {
                $result = $result->where("tour_groups.max_participants", '<=', $request->max);
            }

            //Filter By start date
            if ($request->from != "") {
                $result = $result->whereDate("tour_groups.start_trading", '>=', $request->from);
            }
            if ($request->to != "") {
                $result = $result->whereDate("tour_groups.start_trading", '<=', $request->to);
            }
            // filter search script end
            $count = $result->count();
            // $total_amount = $result->sum('amount');
            $result = $result->orderby($orderby, $request->order[0]['dir'])->skip($request->start)->take($request->length)->get();
            $data = array();
            foreach ($result as $value) {
                if ($value->status == 'enabled') {
                    $status = '<span class="bg-light-success badge badge-success">Enabled</span>';
                    $status_color = 'text-success';
                } else {
                    $status = '<span class="bg-light-danger badge badge-danger">Disabled</span>';
                    $status_color = 'text-danger';
                }

                if ($value->start_trading == '') {
                    $start_trading = '---';
                } else {
                    $start_trading = date('d M y', strtotime($value->start_trading));
                }

                $data[] = [
                    'tour_name' => '<a href="#" data-id="' . $value->id . '" class="dt-description justify-content-start"><span class="w"> <i class="plus-minus" data-feather="plus"></i> </span> <span class="' . $status_color . '">' . ucfirst($value->tour_name) . '</span></a>',
                    'group_name' => $value->group_name,
                    'round' => ucwords($value->round),
                    'max_participants' => $value->max_participants,
                    'start_trading' => $start_trading,
                    'duration' => $value->group_trading_duration . " Days",
                    'status' => $status,
                ];
            }
            return Response::json([
                "draw" => $request->draw,
                "recordsTotal" => $count,
                "recordsFiltered" => $count,
                "data" => $data,
                // 'total_amount' => round($total_amount, 2),
            ]);
        } catch (\Throwable $th) {
            throw $th;
            return Response::json([
                "draw" => $request->draw,
                "recordsTotal" => 0,
                "recordsFiltered" => 0,
                "data" => [],
                // 'total_amount' => 0,
            ]);
        }
    }

    public function groupListDescription(Request $request, $id)
    {
        $group_participants = TourGroup::with('participants.user')->where('tour_groups.id', $id)->first();
        $innerTH = '
            <th>Name</th>
            <th>Email</th>
            <th>Account Number</th>
            <th>Status</th>
            <th>Join Date</th>
            <th>Total Profit[Pool 1]</th>
            <th>Total Volume[Pool 1]</th>
            <th>Action</th>
        ';

        $innerTD = '';
        $group_start_button = '';
        if($group_participants->start_trading == null){
            $group_start_button = '<button class="btn btn-sm btn-success start-trading-btn" data-group_id="' . $id . '">
                Start Trading
            </button>';
        }
        $close_pool_button = '';
        if($group_participants->status == 'enabled'){
            $close_pool_button = '<button class="btn btn-sm btn-danger close-pool-btn" data-group_id="' . $id . '">
                Close Pool
            </button>';
        }

        foreach($group_participants?->participants as $row){
            $gained_color = "";
            if($row?->group1_status == 'gained'){
                $gained_color = "text-success";
            }elseif($row?->group1_status == 'failed'){
                $gained_color = "text-danger";
            }
            $user = User::find($row->user_id); // cleaner than where()->first()
            $innerTD .= '
                <tr>
                    <td>' . ucwords($user?->name) . '</td>
                    <td>' . $user?->email . '</td>
                    <td class="'.$gained_color.'">' . $row?->account_num . '</td>
                    <td>' . ucwords($row?->status) . '</td>
                    <td>' . date('d M Y, h:i A', strtotime($row?->created_at)) . '</td>
                    <td>' . ($row?->group1_profit?$row?->group1_profit:0) . '</td>
                    <td>' . ($row?->group1_volume?$row?->group1_volume:0) . '</td>
                    <td>
                        <button class="btn btn-sm btn-danger delete-btn" data-id="' . $row->id . '" data-user="' . $row->user_id . '">
                            Delete
                        </button>
                    </td>
                </tr>';
        }

        $description = '
        <tr class="description" style="display:none">
            <td colspan="8">
                <div class="details-section-dark border-start-3 border-start-primary p-2" style="display: flow-root;">
                    <span class="details-text mb-2">
                        Participant Details:
                    </span>
                    <span class="float-end mb-2">
                        ' . $group_start_button . ' ' . $close_pool_button . '
                    </span>
                    <table id="deposit-details' . $id . '" class="deposit-details table dt-inner-table-dark">
                        <thead>
                            <tr>' . $innerTH . '</tr>
                        </thead>
                        <tbody>
                            ' . $innerTD . '
                        </tbody>
                    </table>
                </div>
            </td>
        </tr>';

        return Response::json([
            'status' => true,
            'description' => $description,
        ]);
    }
    // delete participant
    public function groupListParticipantDelete(Request $request){
        $delete_participant = TourParticipant::where('id', $request->participant_id)->delete();
        if($delete_participant){
            return Response::json([
                'status' => true,
                'message' => 'Deleted successfully.'
            ]);
        }
        return Response::json([
            'status' => false,
            'message' => 'Failed to delete!'
        ]);
    }
    // delete participant
    public function groupTradingStart(Request $request){
        $tourSetting = TourSetting::select()->first();
        $group_participants = TourGroup::with('participants.user')->where('tour_groups.id', $request->group_id)->first();
        $now = Carbon::now();
        $endDate = $now->copy()->addDays($tourSetting?->group_trading_duration)->format('Y-m-d H:i:s');
        foreach($group_participants?->participants as $row){
            // $user = User::find(24);
            $user = User::find($row->user_id);
            //start: mail for participant
            $tournament_message = '<p> Your trading time is started from now.</p>
            <table style="text-align:left;border-collapse:collapse;margin-top:2rem; width:400px !important;">
                <tbody>
                    <tr>
                        <td style="text-align:center;border:solid 1px #cbcbb8;color:#5a1515;padding:15px">Group Round</td>
                        <td style="text-align:center;border:solid 1px #cbcbb8;color:#ffa442;padding:15px">'.ucwords($group_participants->round).'</td>
                    </tr>
                    <tr>
                        <td style="text-align:center;border:solid 1px #cbcbb8;color:#5a1515;padding:15px">Group Name</td>
                        <td style="text-align:center;border:solid 1px #cbcbb8;color:#ffa442;padding:15px">'.ucwords($group_participants->group_name).'</td>
                    </tr>
                    <tr>
                        <td style="text-align:center;border:solid 1px #cbcbb8;color:#5a1515;padding:15px">Account Number</td>
                        <td style="text-align:center;border:solid 1px #cbcbb8;color:#ffa442;padding:15px">'. $row?->account_num .'</td>
                    </tr>
                    <tr>
                        <td style="text-align:center;border:solid 1px #cbcbb8;color:#5a1515;padding:15px">Start From</td>
                        <td style="text-align:center;border:solid 1px #cbcbb8;color:#ffa442;padding:15px">'. $now .'</td>
                    </tr>
                    <tr>
                        <td style="text-align:center;border:solid 1px #cbcbb8;color:#5a1515;padding:15px">End To</td>
                        <td style="text-align:center;border:solid 1px #cbcbb8;color:#ffa442;padding:15px">'. $endDate .'</td>
                    </tr>
                </tbody>
            </table>';
            $to_participant = $user->email;
            $support_email = SystemConfig::select('support_email')->first();
            $support_email = ($support_email) ? $support_email->support_email : default_support_email();

            $participant_data = [
                'name'                  => $user->name,
                'master-admin'          => $to_participant,
                'tournament_message'    => $tournament_message,
                'support_email'         => $support_email,
            ];

            Mail::to($to_participant)->send(new TournamentTradingStartMail($participant_data));
            //end: mail for participant
        }
        $group_participants->start_trading = $now;
        $group_participants->save();
        if($group_participants){
            return Response::json([
                'status' => true,
                'message' => 'Trading is started successfully.'
            ]);
        }
    }
    // Close group trading
    public function groupTradingClose(Request $request)
    {
        // Load the group with participants and their user info
        $group = TourGroup::with('participants.user')->where('id', $request->group_id)->first();
    
        if (!$group || $group->participants->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => 'Group or participants not found.'
            ]);
        }
    
        // Filter participants who meet the minimum volume and profit requirement
        $qualifiedParticipants = $group->participants
            ->filter(function ($p) {
                return $p->group1_volume >= 0.25;
            })
            ->filter(function ($p) {
                return $p->group1_profit > 0;
            });
    
        // If no qualified participant, update all to failed and return
        if ($qualifiedParticipants->isEmpty()) {
            // Mark all participants as 'failed'
            TourParticipant::where('group_id', $group->id)
                ->update(['group1_status' => 'failed']);
    
            // Disable the group
            $group->status = 'disabled';
            $group->save();
    
            return response()->json([
                'status' => false,
                'message' => 'No participant met the criteria (volume ≥ 0.25 and profit > 0). All participants marked as failed.',
                'winner' => null
            ]);
        }
    
        // Sort qualified participants by profit (desc), then volume (desc)
        $winner = $qualifiedParticipants->sort(function ($a, $b) {
            $profitDiff = $b->group1_profit <=> $a->group1_profit;
            if ($profitDiff === 0) {
                return $b->group1_volume <=> $a->group1_volume;
            }
            return $profitDiff;
        })->first();
    
        // Disable the group
        $group->status = 'disabled';
        $group->save();
    
        // Update winner's status to 'gained'
        $winnerParticipant = TourParticipant::find($winner->id);
        $winnerParticipant->group1_status = 'gained';
        $winnerParticipant->save();
    
        // Update all other participants in the same group to 'failed'
        TourParticipant::where('group_id', $group->id)
            ->where('id', '!=', $winner->id)
            ->update(['group1_status' => 'failed']);
    
        // Find a group in the second round with less than 4 participants
        $tour_group = TourGroup::withCount('participants')
            ->where('round', 'second')
            ->having('participants_count', '<', 4)
            ->orderBy('id')
            ->first();
    
        // Create the winner's participation in the next round
        TourParticipant::create([
            'tournament_id' => $winner->tournament_id,
            'user_id' => $winner->user_id,
            'account_id' => $winner->account_id,
            'account_num' => $winner->account_num,
            'group_id' => $tour_group->id,
        ]);
    
        // Return response with winner info
        return response()->json([
            'status' => true,
            'message' => 'Winner found successfully.',
            'winner' => [
                'participant_id' => $winner->id,
                'user_id' => $winner->user_id,
                'name' => $winner->user->name ?? null,
                'account_num' => $winner->account_num,
                'profit' => $winner->group1_profit,
                'volume' => $winner->group1_volume
            ]
        ]);
    }


    

    // public function groupTradingClose(Request $request){
    //     // $tourSetting = TourSetting::select()->first();
        
    //     // Load the group and its participants with users
    //     $group = TourGroup::with('participants.user')->where('id', $request->group_id)->first();
    
    //     if (!$group || $group->participants->isEmpty()) {
    //         return response()->json([
    //             'status' => false,
    //             'message' => 'Group or participants not found.'
    //         ]);
    //     }
    
    //     // Find the participant with highest profit (and tie-breaker with volume)
    //     $qualifiedParticipants = $group->participants->filter(function ($p) {
    //         return $p->group1_volume >= 0.25;
    //     });
    
    //     if ($qualifiedParticipants->isEmpty()) {
    //         return response()->json([
    //             'status' => false,
    //             'message' => 'No participant met the minimum volume to win.',
    //             'winner' => null
    //         ]);
    //     }
    
    //     $winner = $qualifiedParticipants->sort(function ($a, $b) {
    //         // First compare by profit descending
    //         $profitDiff = $b->group1_profit <=> $a->group1_profit;
    //         if ($profitDiff === 0) {
    //             // If profit is equal, compare by volume descending
    //             return $b->group1_volume <=> $a->group1_volume;
    //         }
    //         return $profitDiff;
    //     })->first();
    //     $group->status = 'disabled';
    //     $group->save();
        
    //     $change_participant_status = TourParticipant::find($winner->id);
    //     $change_participant_status->group1_status = 'gained';
    //     $change_participant_status->save();
        
    //     return response()->json([
    //         'status' => true,
    //         'message' => 'Winner found successfully.',
    //         'winner' => [
    //             'participant_id' => $winner->id,
    //             'user_id' => $winner->user_id,
    //             'name' => $winner->user->name ?? null,
    //             'account_num' => $winner->account_num,
    //             'profit' => $winner->group1_profit,
    //             'volume' => $winner->group1_volume
    //         ]
    //     ]);
    // }

}
