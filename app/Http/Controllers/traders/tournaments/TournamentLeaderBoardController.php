<?php

namespace App\Http\Controllers\traders\tournaments;

use App\Http\Controllers\Controller;
use App\Models\tournaments\TourSetting;
use App\Models\ContestJoin;
use App\Models\tournaments\TourParticipant;
use App\Models\tournaments\TourGroup;
use App\Models\Deposit;
use App\Models\InternalTransfer;
use App\Models\TradingAccount;
use App\Services\Mt5WebApi;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TournamentLeaderBoardController extends Controller
{
    public function leaderBoardView(Request $request)
    {
        try {
            // get the latest active tournament
            $tournament = TourSetting::select()->latest()->first();
            $start = Carbon::parse($tournament?->start_date);
            $end = Carbon::parse($tournament?->end_date);

            $duration = $start->diffForHumans($end, [
                'parts' => 3,  // limit to 3 parts like "1 month 2 weeks 3 days"
                'join' => true,
                'syntax' => Carbon::DIFF_ABSOLUTE,
            ]);
            $account = TradingAccount::where('user_id', auth()->id())
                ->select('trading_accounts.account_number', 'trading_accounts.client_type', 'trading_accounts.user_id', 'trading_accounts.id', 'client_groups.group_id')
                ->join('client_groups', 'trading_accounts.group_id', 'client_groups.id')
                ->where('trading_accounts.group_id', $tournament->client_group_id)
                ->get();

            return view('traders.tournaments.leader-board', [
                'tournament' => $tournament,
                'duration' => $duration,
                'accounts' => $account,
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    public function joinTournament(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'tournament' => 'required|integer',
                'account' => 'required|integer'
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => $validator->errors()->first(),
                    'errors' => $validator->errors(),
                ]);
            }
            // check the tournament exists
            $tournament = TourSetting::first();
            // return $tournament = TourSetting::with(['$tournamentRule'])->findOrFail($request->input('$tournament'));
            $trading_account = TradingAccount::where('user_id', auth()->id())->where('id', $request->input('account'))->first();
            if (!$trading_account) {
                return response()->json([
                    'status' => false,
                    'message' => 'Account not found, please choose a valid account',
                ]);
            }

            // // check for wallet deposit
            // $accountDeposit = Deposit::where('user_id', auth()->id())->where('approved_status', 'A')->sum('amount');
            // if ($accountDeposit < $tournament->min_deposit) {
            //     return response()->json([
            //         'status' => false,
            //         'message' => "Minimum wallet deposit required - $$tournament->min_deposit",
            //     ]);
            // }
            
            // check for wallet deposit
            // $accountDeposit = Deposit::where('user_id', auth()->id())->where('approved_status', 'A')->sum('amount');
            $mt5_api = new Mt5WebApi();
            $action = 'AccountGetMargin';

            $data = array(
                "Login" => (int)$trading_account->account_number
            );
            $result = $mt5_api->execute($action, $data);
            $mt5_api->Disconnect();
            if ($result['data']['Equity'] < $tournament->min_deposit) {
                return response()->json([
                    'status' => false,
                    'message' => "Minimum account balance required - $$tournament->min_deposit",
                ]);
            }

            // check trading account is live or not
            if ($trading_account->client_type !== 'live') {
                return response()->json([
                    'status' => false,
                    'message' => 'Demo account is not allowed, this contest is for live account.',
                ]);
            }

            // check already participate
            $participate = TourParticipant::where('tournament_id', $tournament->id)
                ->where('user_id', auth()->id())
                ->where('account_num', $trading_account->account_number)
                ->first();
            if ($participate) {
                return response()->json([
                    'status' => false,
                    'message' => 'You already participated by the account, please ignore this.'
                ]);
            }

            // tour group find
            $tour_group = TourGroup::withCount('participants')
                ->where('round', 'first')
                ->having('participants_count', '<', 4)
                ->orderBy('id')
                ->first();

            // now create the participate
            $create = TourParticipant::create([
                'tournament_id' => $tournament->id,
                'user_id' => auth()->id(),
                'account_id' => $trading_account->id,
                'account_num' => $trading_account->account_number,
                'group_id' => $tour_group->id,
            ]);
            if ($create) {
                return response()->json([
                    'status' => true,
                    'message' => 'Congratulations! you successfully joined the contest',
                ]);
            }
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong, please try again later.'
            ]);
        } catch (\Throwable $th) {
            // throw $th;
            return response()->json([
                'status' => false,
                'message' => 'Got a server error, please contact for support',
                'error' => $th->getMessage(),
            ]);
        }
    }
    // balance equity
    public function balance_equity(Request $request)
    {
        try {
            $trading_account = TradingAccount::where('user_id', auth()->id())->where('id', $request->input('account_id'))->first();
            $mt5_api = new Mt5WebApi();
            $result = $mt5_api->execute('AccountGetMargin', [
                "Login" => $trading_account->account_number
            ]);
            if (isset($result['success']) &&  $result['success']) {
                return response()->json([
                    'status' => true,
                    'balance' => $result['data']['Balance'],
                    'equity' => $result['data']['Equity'],
                    'type' => $trading_account->client_type,
                ]);
            }
            return response()->json([
                'status' => false,
                'balance' => 0.00,
                'equity' => 0.00,
                'type' => $trading_account->client_type,
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'status' => false,
                'balance' => 0.00,
                'equity' => 0.00,
                'type' => '---',
                'error' => $th->getMessage(),
            ]);
        }
    }
    public function tourLeaderBoard(Request $request)
    {
        try {
            // Get all gained participants with required user info
            $all = TourParticipant::with([
                    'user',
                    'user.description',
                    'user.description.country',
                ])
                ->where('group1_status', 'gained')
                ->orderByDesc('group1_profit')
                ->orderByDesc('group1_volume')
                ->get();
    
            // Total count before pagination
            $count = $all->count();
    
            // Assign rank
            $ranked = $all->values()->map(function ($item, $index) {
                $item->rank = $index + 1;
                return $item;
            });
    
            // Paginate manually
            $start = $request->input('start', 0);
            $length = $request->input('length', 10);
            $paged = $ranked->slice($start, $length);
    
            // Format data
            $data = [];
            foreach ($paged as $value) {
                $data[] = [
                    'rank' => $value->rank,
                    'tournament' => [
                        'name' => $value?->user?->name ?? '---',
                        'country' => $value?->user?->description?->country?->name ?? '---',
                    ],
                    'account_num' => $value?->account_num, // Update if needed
                    'profit' => "$" . number_format($value->group1_profit, 2),
                    'volume' => number_format($value->group1_volume, 2),
                ];
            }
    
            return response()->json([
                'draw' => intval($request->draw),
                'recordsTotal' => $count,
                'recordsFiltered' => $count,
                'data' => $data,
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'draw' => intval($request->draw),
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => [],
                'message_error' => $th->getMessage(),
            ]);
        }
    }
}
