<?php

namespace App\Http\Controllers\admins\tournaments;

use App\Services\CopyApiService;
use App\Http\Controllers\Controller;
use App\Models\tournaments\TourSetting;
use App\Models\ClientGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class TournamentSettingController extends Controller
{
    public function tournamentSettingView(Request $request)
    {
        $tourSetting = TourSetting::select()->first();
        $clientGroup = ClientGroup::where('visibility', 'visible')->get();
        return view('admins.tournaments.tournament-settings', [
            'tourSetting' => $tourSetting,
            'clientGroup' => $clientGroup,
        ]);
    }
    public function tournamentSettingAction(Request $request)
    {
        $validation_rules = [
            'tournament_name' => 'required',
            'organization_name' => 'nullable',
            'min_deposit' => 'required',
            'group_trading_duration' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
            'first_prize' => 'required'
        ];
        $validator = Validator::make($request->all(), $validation_rules);
        if ($validator->fails()) {
            return Response::json([
                'status' => false,
                'message' => '<span style="color:red;">Fix the following error</span>',
                'errors' => $validator->errors()
            ]);
        } else {
            $settingChange = TourSetting::updateOrCreate(
                ['id' => 1],
                [
                    'tour_name' => $request->tournament_name,
                    'organization_name' => $request->organization_name,
                    'min_deposit' => $request->min_deposit,
                    'client_group_id' => $request->client_group,
                    'group_trading_duration' => $request->group_trading_duration,
                    'start_date' => $request->start_date,
                    'end_date' => $request->end_date,
                    'prize_1' => $request->first_prize,
                    'prize_2' => $request->second_prize ?? 0,
                    'prize_3' => $request->third_prize ?? 0,
                    'prize_4' => $request->fourth_prize ?? 0,
                ]
            );

            if ($settingChange) {
                $response['success'] = true;
                $response['message'] = 'Settings updated.';
            }else{
                $response['success'] = false;
                $response['message'] = 'Failed to update!';
            }
            return Response::json($response);
        }
    }
}
