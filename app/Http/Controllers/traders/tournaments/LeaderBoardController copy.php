<?php

namespace App\Http\Controllers\traders\contest;

use App\Http\Controllers\Controller;
use App\Models\Contest;
use App\Models\ContestJoin;
use App\Models\ContestParticipant;
use App\Models\Deposit;
use App\Models\InternalTransfer;
use App\Models\TradingAccount;
use App\Services\Mt5WebApi;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ContestLeaderBoardController extends Controller
{
    public function index(Request $request)
    {
        try {
            // get the latest active contest
            $contest = Contest::with(['contestPrize', 'contestRule'])
                ->where('contest_for', 'contest for trader')
                ->where('status', 'active')
                ->latest()->first();
            $start = Carbon::parse($contest?->contest_start_on);
            $end = Carbon::parse($contest?->contest_end_on);

            $duration = $start->diffForHumans($end, [
                'parts' => 3,  // limit to 3 parts like "1 month 2 weeks 3 days"
                'join' => true,
                'syntax' => Carbon::DIFF_ABSOLUTE,
            ]);
            $account = TradingAccount::where('user_id', auth()->id())->select('account_number', 'client_type', 'user_id', 'id')->get();

            return view('traders.contest.contest-leader-board', [
                'contest' => $contest,
                'duration' => $duration,
                'accounts' => $account,
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    public function join_contest(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'contest' => 'required|integer',
                'account' => 'required|integer'
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => $validator->errors()->first(),
                    'errors' => $validator->errors(),
                ]);
            }
            // check the contest exists
            $contest = Contest::with(['contestRule'])->findOrFail($request->input('contest'));
            if (!$contest) {
                return response()->json([
                    'status' => false,
                    'message' => 'Contest not found, please reload the page.'
                ]);
            }
            $account = TradingAccount::where('user_id', auth()->id())->where('id', $request->input('account'))->first();
            if (!$account) {
                return response()->json([
                    'status' => false,
                    'message' => 'Account not found, please choose a valid account',
                ]);
            }
            $appliedRule = $contest->contestRule;
            if ($appliedRule) {
                // check rule for deposit type
                $depositType = $appliedRule?->firstWhere('rule_name', 'account_deposit');
                // check for account deposit
                if ($depositType && $depositType?->rule_value === 'account') {
                    // check rule for min deposit
                    $minDeposit = $appliedRule?->firstWhere('rule_name', 'min_deposit');
                    if ($minDeposit && $minDeposit?->rule_value != 0) {
                        // check minimum deposited or not
                        $minDepositAmount = $minDeposit->rule_value;
                        $accountDeposit = InternalTransfer::where('user_id', auth()->id())->where('account_id', $account->id)->sum('amount');
                        if ($accountDeposit < $minDeposit->rule_value) {
                            return response()->json([
                                'status' => false,
                                'message' => "Minimum account deposit required - $$minDepositAmount",
                            ]);
                        }
                    }
                }
                // check for wallet deposit
                if ($depositType && $depositType?->rule_value === 'wallet') {
                    // check rule for min deposit
                    $minDeposit = $appliedRule?->firstWhere('rule_name', 'min_deposit');
                    if ($minDeposit && $minDeposit?->rule_value != 0) {
                        // check minimum deposited or not
                        $minDepositAmount = $minDeposit->rule_value;
                        $accountDeposit = Deposit::where('user_id', auth()->id())->where('approve_status', 'A')->sum('amount');
                        if ($accountDeposit < $minDeposit->rule_value) {
                            return response()->json([
                                'status' => false,
                                'message' => "Minimum wallet deposit required - $$minDepositAmount",
                            ]);
                        }
                    }
                }
                // check account type
                $accountType = $appliedRule?->firstWhere('rule_name', 'accounts__is_demo');
                if ($accountType && $accountType?->rule_value === 'no') {
                    // check account is demo
                    if ($account->client_type === 'demo') {
                        return response()->json([
                            'status' => false,
                            'message' => 'Demo account not allowed, this contest for live account'
                        ]);
                    }
                } elseif ($accountType && $accountType?->rule_value === 'yes') {
                    // check account is live
                    if ($account->client_type === 'live') {
                        return response()->json([
                            'status' => false,
                            'message' => 'Live account is not allowed, this contest for demo account',
                        ]);
                    }
                }
            }

            // check already participate
            $participate = ContestJoin::where('contest_id', $contest->id)
                ->where('user_id', auth()->id())->first();
            if ($participate) {
                return response()->json([
                    'status' => false,
                    'message' => 'You already participated, please ignore this.'
                ]);
            }
            
            // Check if trading account is already being used in another contest
            $accountUsedInOtherContest = ContestJoin::where('account_number', $account->account_number)
                ->where('contest_id', '!=', $contest->id)
                ->first();
                
            if ($accountUsedInOtherContest) {
                return response()->json([
                    'status' => false,
                    'message' => 'This trading account is already participating in another contest. Please create a fresh account to join this contest.',
                    'show_create_account_popup' => true
                ]);
            }
            
            // now create the participate
            $create = ContestJoin::create([
                'contest_id' => $contest->id,
                'user_id' => auth()->id(),
                'account_number' => $account->account_number,
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
            //throw $th;
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
    // get leaders datatable
    public function contest_leaders(Request $request)
    {
        try {
            $result = ContestJoin::with(['user', 'user.description', 'user.description.country', 'account', 'account.accountDeposit'])
                ->where('contest_id', $request->input('contest'));

            $count = $result->count();
            $result = $result->orderBy('position', 'DESC')->skip($request->input('start'))->take($request->input('length'))->get();
            // return $result;
            $data = [];
            foreach ($result as $value) {
                $totalDeposit = $value?->account?->accountDeposit->sum('amount') ?? 0;
                $balance = $value?->total_profit + $totalDeposit;
                if ($totalDeposit != 0) {
                    $roi = (($balance - $totalDeposit) / $totalDeposit) * 100;
                } else {
                    $roi = 0;
                }

                $data[] = [
                    'rank' => $value->position,
                    'contestant' => [
                        'name' => $value?->user?->name ?? '---',
                        'country' => $value?->user?->description?->country?->name ?? '---',
                    ],
                    'trades' => 0,
                    'profit' => "$" . $value->total_profit,
                    'gain' => "$roi %"
                ];
            }
            return response()->json([
                'draw' => $request->draw,
                'recordsTotal' => $count,
                'recordsFiltered' => $count,
                'data' => $data,
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'draw' => $request->draw,
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => [],
                'message_error' => $th->getMessage(),
            ]);
        }
    }
}
