<?php

namespace App\Http\Controllers\admins\Contest;

use App\Http\Controllers\Controller;
use App\Models\Contest;
use App\Models\ContestJoin;
use App\Models\Credit;
use App\Models\TradingAccount;
use App\Models\User;
use App\Services\AllFunctionService;
use App\Services\contest\ContestService;
use App\Services\MT4API;
use App\Services\Mt5WebApi;
use App\Services\systems\DateService;
use App\Services\systems\OrdinalNumberService;
use App\Services\systems\TransactionIDService;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class ContestParticipantController extends Controller
{
    public function __construct()
    {
        // $this->middleware(["role:support"]);
        // $this->middleware(["role:client ticket"]);
        // system module control
        $this->middleware(AllFunctionService::access('contest', 'admin'));
        $this->middleware(AllFunctionService::access('contest_participant', 'admin'));
    }
    public function ContestParticipant(Request $request)
    {
        $contest = Contest::select()->get();
        $count = Contest::count();
        return view('admins.contest.contest-participant', ['contest' => $contest, 'count' => $count]);
    }
    public function ContentParticipantReport(Request $request)
    {
        try {
            if ($request->op === 'description') {
                return $this->dt_description($request);
            }
            $columns = ['name', 'email', 'email', 'account', 'account', 'account', 'created_at', 'created_at', 'created_at'];
            $orderby = $columns[$request->order[0]['column']];

            $result = ContestJoin::select(
                'users.email',
                'users.name',
                'contest_joins.*',
                'contests.contest_name',
                'contests.status',
                'contests.max_contest',
                'contests.min_join',
                'contest_joins.total_profit',
                'contest_joins.total_lot',
                'contest_joins.position',
            )->join('users', 'contest_joins.user_id', '=', 'users.id')
                ->leftJoin('contests', 'contest_joins.contest_id', '=', 'contests.id');

            $count = $result->count();
            $result = $result->orderby($orderby, $request->order[0]['dir'])->skip($request->start)->take($request->length)->get();
            $data = array();

            foreach ($result as $value) {
                // contest status
                $contest_name = '';
                if ($value->status === 'active') {
                    $contest_name = '<span class="text-success">' . $value->contest_name . '</span>';
                } else {
                    $contest_name = '<span class="text-warning">' . $value->contest_name . '</span>';
                }
                $data[] = [
                    'name' => '<a href="#" data-id=' . $value->id . ' class="dt-description  justify-content-between"><span class="w"> <i class="plus-minus" data-feather="plus"></i> </span> <span>' .  ucwords($value->name) . '</span></a>',
                    'email' => $value->email,
                    'rank' => OrdinalNumberService::getOrdinal($value->position),
                    'account' => $value->account_number,
                    'total_joined' => ContestService::count_total_participant($value->contest_id),
                    'loss_profit' => $value->min_join,
                    'total_deposit' => $value->max_contest,
                    'contest' => $contest_name,
                    'join_contest' => date('d M Y', strtotime($value->created_at)),
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
    // content participant description
    public function dt_description(Request $request)
    {
        $contest_join = ContestJoin::where('contest_joins.id', $request->id)
            ->leftJoin('contests', 'contest_joins.contest_id', '=', 'contests.id')
            ->select(
                'contest_joins.*',
                'contests.contest_type',
                'contests.allowed_for',
                'contests.client_group',
                'contests.kyc',
                'contests.contest_prices',
            )->first();
        // prize table
        $prizes = json_decode($contest_join->contest_prices);
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
        // count total price
        $total_prize = count($prizes);
        $my_posistion = $contest_join->position;
        $credit_button = '';
        if ($my_posistion <= $total_prize && $my_posistion > 0) {
            $credit_button = '<button class="btn btn-danger btn-close-contest btn-credit-contest" data-contest_id="' . $contest_join->contest_id . '" data-user_id="' . $contest_join->user_id . '">Credit</button>';
        }
        // kyc required or not
        $kyc = ($contest_join->kyc == 0) ? 'Not Required' : 'Required';
        try {
            $description = '<tr class="description" style="display:none;">
                                <td colspan="9">
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
                                                                <td class="border-end-0  w-50">' . ucwords(str_replace('_', ' ', $contest_join->contest_type)) . '</td>
                                                            </tr>
                                                            <tr>
                                                                <th class="w-50 border-bottom-0 border-start-3 border-start-success">Client Type</th>
                                                                <td class="border-end-0  w-50">' . ucwords(str_replace('_', ' ', $contest_join->allowed_for)) . '</td>
                                                            </tr>
                                                            <tr>
                                                                <th class="w-50 border-bottom-0 border-start-3 border-start-success">KYC</th>
                                                                <td class="border-end-0  w-50">' . $kyc . '</td>
                                                            </tr>
                                                            <tr>
                                                                <th class="w-50 border-bottom-0 border-start-3 border-start-success">Client KYC Status</th>
                                                                <td class="border-end-0  w-50">Verified</td>
                                                            </tr>
                                                            <tr>
                                                                <th class="w-50 border-bottom-0 border-start-3 border-start-success">Contest Prize</th>
                                                                <td class="border-end-0  w-50">' . $prizes_table . '</td>
                                                            </tr>
                                                        </table>
                                                        <div class="d-flex justify-content-end mt-3">
                                                            <button class="btn btn-success me-2 btn-view-popup" data-popup="' . asset('Uploads/contest/' . $contest_join->popup_image) . '">View popup image</button>
                                                            ' . $credit_button . '
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            
                                        </div>
                                    </div>
                                </td>
                            </tr>';
            $data = [
                'status' => true,
                'description' => $description
            ];
            return Response::json($data);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
    // contest credit
    public function contest_credit(Request $request)
    {
        try {
            $validation_rules = [
                'user_id' => 'required',
                'contest_id' => 'required',
            ];
            $validator = Validator::make($request->all(), $validation_rules);
            if ($validator->fails()) {
                return Response::json([
                    'status' => false,
                    'message' => 'Got a enternal server error! please try again later'
                ]);
            }
            // check contest is colosed or not
            // and alredy credited or not in this account 
            $contest_id = $request->contest_id;
            $user_id = $request->user_id;
            $contest_join = ContestJoin::where(function ($query) use ($contest_id, $user_id) {
                $query->where('user_id', $user_id)
                    ->where('contest_id', $contest_id);
            })
                ->join('contests', 'contest_joins.contest_id', '=', 'contests.id')
                ->select(
                    'contests.status',
                    'contests.contest_prices',
                    'contests.expire_after',
                    'contests.expire_type',
                    'contest_joins.credited',
                    'contest_joins.account_number',
                    'contest_joins.position',
                )->first();

            if ($contest_join->status !== 'closed') {
                return Response::json([
                    'status' => false,
                    'message' => 'You could not credit for this contest, becaouse the contest still running. first close contest manualy or wait for ending date. '
                ]);
            } elseif ($contest_join->credited !== 'no credit') {
                return Response::json([
                    'status' => false,
                    'message' => 'You could not credit on this account, becaouse credit amount already credted to this winner account'
                ]);
            }
            // check the user is winner or not

            $prizes = json_decode($contest_join->contest_prices);
            $total_prize = count($prizes);
            $my_posistion = $contest_join->position;
            if ($my_posistion > $total_prize || $my_posistion <= 0) {
                return Response::json([
                    'status' => false,
                    'message' => 'You could not credit on this account, becaouse this user not a winner'
                ]);
            }
            // now start credit operation
            // meta account credit start here
            // ****************************************************
            // write the credit operation code here
            // get expire date
            $expire_date = '';
            if ($contest_join->expire_type === 'days') {
                $expire_date = DateService::addDurationToDate($contest_join->expire_after);
            } elseif ($contest_join->expire_type === 'months') {
                $expire_date = DateService::addDurationToDate(0, $contest_join->expire_after);
            } else {
                $expire_date = DateService::addDurationToDate(0, 0, $contest_join->expire_after);
            }
            // randome transaction id
            $transaction_id = TransactionIDService::generateRandomTransactionID();
            // get trading account
            $trading_account = TradingAccount::where('account_number', $contest_join->account_number)
                ->select(
                    'id',
                    'account_number',
                    'platform',
                )->first();
            if (strtolower($trading_account->platform) === 'mt5') {
                $mt5_api = new Mt5WebApi();
                $data = array(
                    'Login' => (int)$trading_account->account_number,
                    'Comment' => 'Credit for contest' . $request->ip() . ' ' . $transaction_id,
                    'Balance' => (float)$request->amount,
                    'Expiration' => $expire_date,
                );

                $result = $mt5_api->execute('CreditUpdate', $data);
                if ($result['success'] == true) {
                    $response['success'] = true;
                }
            } else if (strtolower($trading_account->platform) == 'mt4') {
                $mt4api = new MT4API();
                $data = array(
                    'command' => 'credit_funds',
                    'data' => array(
                        'account_id' => $trading_account->account_number,
                        'amount' => $request->amount,
                        'comment' => "Credit-In",
                        'expiration' => strtotime($expire_date),
                    ),
                );

                $result = $mt4api->execute($data);
                if (isset($result['success'])) {
                    if ($result['success']) {
                        $response['success'] = true;
                    }
                }
            }
            // $result = ['success' => 1];
            // ***************************************************
            // find price amount 
            $my_prize_amount = (array) $prizes[$my_posistion];
            $my_prize_amount = array_values($my_prize_amount);
            $amount = $my_prize_amount[0];


            // crm credit operation
            if ($result['success']) {
                // update contest winner credit status
                $contest_winner_status = ContestJoin::where('contest_id', $request->contest_id)
                    ->where('user_id', $request->user_id)
                    ->update([
                        'credited' => 'credited'
                    ]);
                // store data to credit table
                $credit = Credit::create([
                    'trading_account' => $trading_account->id,
                    'amount' => $amount,
                    'type' => 'add',
                    'expire_date' => $expire_date,
                    'transaction_id' => $transaction_id,
                    'note' => 'The credit amount for contest winner',
                    'credited_by' => auth()->user()->id,
                    'ip' => $request->ip(),
                    'status' => 1,
                    'credit_for' => 'contest_credit',

                ]);
                // activity log
                // insert activity-----------------
                $user = User::find(auth()->user()->id); //<---client email as user id
                activity("contest credit")
                    ->causedBy(auth()->user()->id)
                    ->withProperties($request->all())
                    ->event(" contest credited")
                    ->performedOn($user)
                    ->log("The IP address " . request()->ip() . " has been credited contest winner");
                // end activity log-----------------
                if ($credit) {
                    return Response::json([
                        'status' => true,
                        'message' => 'Successfully credited for contest winner',
                    ]);
                }
                return Response::json([
                    'status' => false,
                    'message' => 'Account credited but database storage failed'
                ]);
            }
            return Response::json([
                'status' => false,
                'message' => 'API response failed, please try again later'
            ]);
        } catch (\Throwable $th) {
            // throw $th;
            return Response::json([
                'status' => false,
                'message' => 'Got a server error, please try again later!'
            ]);
        }
    }
}
