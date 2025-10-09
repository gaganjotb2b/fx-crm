<?php

namespace App\Http\Controllers\admins;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\ExternalFundTransfers;
use App\Models\ManagerUser;
use App\Models\TradingAccount;
use App\Models\User;
use App\Services\AllFunctionService;
use App\Services\CombinedService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

use function PHPSTORM_META\type;

class ExternalFundTransferController extends Controller
{
    public function __construct()
    {
        $this->middleware(["role:external fund transfer"]);
        $this->middleware(["role:fund transfer"]);
        // system module control
        $this->middleware(AllFunctionService::access('fund_transfer', 'admin'));
        $this->middleware(AllFunctionService::access('external_fund_transfer', 'admin'));
    }
    public function externalFundTransfer(Request $request)
    {
        $op = $request->input('op');

        if ($op == "data_table") {
            return $this->externalFundReport($request);
        }
        return view('admins.reports.external-fund-transfer');
    }

    public function externalFundReport($request)
    {
        try {
             $columns = ['email', 'email', 'external_fund_transfers.receiver_wallet_type', 'external_fund_transfers.type','external_fund_transfers.created_at', 'external_fund_transfers.status','external_fund_transfers.charge', 'external_fund_transfers.amount'];
            $orderby = $columns[$request->order[0]['column']];
            $result = ExternalFundTransfers::select(
                'external_fund_transfers.sender_id',
                'external_fund_transfers.receiver_id',
                'external_fund_transfers.status',
                'external_fund_transfers.charge',
                'external_fund_transfers.type',
                'external_fund_transfers.created_at',
                'external_fund_transfers.amount',
                'external_fund_transfers.sender_wallet_type',
                'external_fund_transfers.receiver_wallet_type',
                'users.id',
                'users.name',
                'users.email'
            )
                ->join('users', 'external_fund_transfers.sender_id', '=', 'users.id');

            $total_amount = $result->sum('amount');

            /*<-------filter search script start here------------->*/
            // filter by login manager
            if (strtolower(auth()->user()->type) === 'manager') {
                $manager_user = ManagerUser::where('manager_id', auth()->user()->id)->select('user_id')->get()->pluck('user_id');
                $result = $result->whereIn('sender_id', $manager_user)->orWhereIn('receiver_id', $manager_user);
            }

            // approved status
            if ($request->approved_status != "") {
                $result = $result->where('status', $request->approved_status);
            }
            // filter by verification status
            if ($request->kyc_verification_status != "") {
                $result = $result->where('users.kyc_status', $request->kyc_verification_status);
            }
            //Filter By  receiver Client Type
            if ($request->receiver_client_type != "") {
                $result = $result->where('receiver_wallet_type', $request->receiver_client_type);
            }
            //Filter By  sender Client Type
            if ($request->sender_client_type != "") {
                $result = $result->where('sender_wallet_type', $request->sender_client_type);
            }
            // filter by trader info
            if ($request->trader_info != "") {
                // get trader id from info
                $trader_info = $request->trader_info;
                $country = Country::where(function ($q) use ($trader_info) {
                    $q->where('name', 'like', '%' . $trader_info . '%');
                })->get()->pluck('id');
                $traders = User::where(function ($query) use ($trader_info, $country) {
                    $query->where('email', 'like', '%' . $trader_info . '%')
                        ->orWhere('name', 'like', '%' . $trader_info . '%')
                        ->orWhereIn('country_id', $country)
                        ->orWhere('phone', 'like', '%' . $trader_info . '%');
                })
                    ->leftJoin('user_descriptions', 'users.id', '=', 'user_descriptions.user_id')
                    ->select('users.id as client_id')
                    ->get()->pluck('client_id');
                // get data from externalfund transfer
                $result = $result->where(function ($q) use ($traders) {
                    $q->whereIn('sender_id', $traders)
                        ->orWhereIn('receiver_id', $traders);
                });
            }

            // filter by ib name email phone
            if ($request->ib_info != "") {
                // get ib id from info
                $ib_info = $request->ib_info;
                $country = Country::where(function ($q) use ($ib_info) {
                    $q->where('name', 'like', '%' . $ib_info . '%');
                })->get()->pluck('id');
                $traders = User::where(function ($query) use ($ib_info, $country) {
                    $query->where('email', 'like', '%' . $ib_info . '%')
                        ->orWhere('name', 'like', '%' . $ib_info . '%')
                        ->orWhereIn('country_id', $country)
                        ->orWhere('phone', 'like', '%' . $ib_info . '%');
                })
                    ->leftJoin('user_descriptions', 'users.id', '=', 'user_descriptions.user_id')
                    ->select('users.id as client_id')
                    ->get()->pluck('client_id');
                // get data from externalfund transfer
                $result = $result->where(function ($q) use ($traders) {
                    $q->whereIn('sender_id', $traders)
                        ->orWhereIn('receiver_id', $traders);
                });
            }
            // filter by sender info
            if ($request->sender_info != "") {
                // get sender id from info
                $sender_info = $request->sender_info;
                $senders = User::where(function ($query) use ($sender_info) {
                    $query->where('email', 'like', '%' . $sender_info . '%')
                        ->orWhere('name', 'like', '%' . $sender_info . '%')
                        ->orWhere('phone', 'like', '%' . $sender_info . '%');
                })
                    ->leftJoin('user_descriptions', 'users.id', '=', 'user_descriptions.user_id')
                    ->select('users.id as client_id')
                    ->get()->pluck('client_id');
                // get data from externalfund transfer
                $result = $result->where(function ($q) use ($senders) {
                    $q->whereIn('sender_id', $senders);
                });
            }

            // filter by receiver info
            if ($request->receiver_info != "") {
                // get sender id from info
                $receiver_info = $request->receiver_info;
                $receiver = User::where(function ($query) use ($receiver_info) {
                    $query->where('email', 'like', '%' . $receiver_info . '%')
                        ->orWhere('name', 'like', '%' . $receiver_info . '%')
                        ->orWhere('phone', 'like', '%' . $receiver_info . '%');
                })
                    ->leftJoin('user_descriptions', 'users.id', '=', 'user_descriptions.user_id')
                    ->select('users.id as client_id')
                    ->get()->pluck('client_id');
                // get data from externalfund transfer
                $result = $result->where(function ($q) use ($receiver) {
                    $q->whereIn('receiver_id', $receiver);
                });
            }
            // filter by manager info
            if ($request->manager_info) {
                $maanger_info = $request->manager_info;
                $managers = User::where(function ($q) use ($maanger_info) {
                    $q->where('users.email', 'like', '%' . $maanger_info . '%')
                        ->orWhere('users.name', 'like', '%' . $maanger_info . '%')
                        ->orWhere('users.phone', 'like', '%' . $maanger_info . '%');
                })->select('id')->get()->pluck('id');
                $manager_user = ManagerUser::whereIn('manager_id', $managers)->select('user_id')->get()->pluck('user_id');
                $result = $result->where(function ($q) use ($manager_user) {
                    $q->whereIn('sender_id', $manager_user)
                        ->orWhereIn('receiver_id', $manager_user);
                });
            }
            // filter by trading account
            if ($request->trading_account != "") {
                $trading_account = TradingAccount::where('account_number', $request->trading_account)->select('user_id')->first();
                $user_id = $trading_account->user_id;
                $result = $result->where(function ($query) use ($user_id) {
                    $query->where('sender_id', $user_id)
                        ->orWhere('receiver_id', $user_id);
                });
            }
            // Amount
            // filter by min amount
            if ($request->min != "") {
                $result = $result->where('external_fund_transfers.amount', '>=', $request->min);
            }
            // filter by max amount
            if ($request->max != "") {
                $result = $result->where('external_fund_transfers.amount', '<=', $request->max);
            }

            // Transaction Date
            // filter by date from
            if ($request->from != "") {
                $result = $result->whereDate('external_fund_transfers.created_at', '>=', $request->from);
            }
            // filter by date to
            if ($request->to != "") {
                $result = $result->whereDate('external_fund_transfers.created_at', '<=', $request->to);
            }

            $count = $result->count();
            $result = $result->orderby($orderby, $request->order[0]['dir'])->skip($request->start)->take($request->length)->get();
            $data = [];

            foreach ($result as $value) {

                $receiver_mail = User::find($value->receiver_id);

                // approved status
                if ($value->status == 'P') {
                    $status = '<span class="bg-light-warning badge badge-warning">Pending</span>';
                } elseif ($value->status == 'A') {
                    $status = '<span class="bg-light-success badge badge-success">Approved</span>';
                } elseif ($value->status == 'D') {
                    $status = '<span class="bg-light-danger badge badge-danger">Declined</span>';
                }
                // client type as reciever type
                if ($value->receiver_wallet_type === 'ib') {
                    $cliend_type = '<span class="bg-warning badge badge-warning">IB</span>';
                } else {
                    $cliend_type = '<span class="bg-success badge badge-success">Trader</span>';
                }

                $data[] = [
                    'sender_email' => $value->email,
                    'receiver_email' => isset($receiver_mail->email) ? $receiver_mail->email : '',
                    'receiver_type' => $cliend_type,
                    'type' => str_replace('Ib', 'IB', ucwords(str_replace('_', ' ', $value->type))),
                    'date' => date('d M y h:i:s', strtotime($value->created_at)),
                    'status' => $status,
                    'charge' => $value->charge,
                    'amount' => '$' . $value->amount,
                ];
            }
            return Response::json([
                'draw' => $request->draw,
                'recordsTotal' => $count,
                'recordsFiltered' => $count,
                'total_amount' => round($total_amount,2),
                'data' => $data
            ]);
        } catch (\Throwable $th) {
            // throw $th;
            return Response::json([
                'draw' => $request->draw,
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'total_amount' => 0,
                'data' => []
            ]);
        }
    }
}
