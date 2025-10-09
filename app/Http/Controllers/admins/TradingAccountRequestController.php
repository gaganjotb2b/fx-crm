<?php

namespace App\Http\Controllers\admins;

use App\Http\Controllers\Controller;
use App\Mail\ApproveTradingAccount;
use App\Mail\ApproveWithdrawRequest;
use App\Models\admin\InternalTransfer;
use App\Models\admin\SystemConfig;
use App\Models\BonusUser;
use App\Models\Country;
use App\Models\Deposit;
use App\Models\IB;
use App\Models\ManagerUser;
use App\Models\TradingAccount;
use App\Models\User;
use App\Models\UserDescription;
use App\Models\Withdraw;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Response;

class TradingAccountRequestController extends Controller
{
    public function account_request(Request $request)
    {
        if ($request->ajax()) {
            if ($request->op === 'description') {
                $id = $request->id;
                $user_id = TradingAccount::select('user_id', 'approve_status')->where('id', $id)->first();
                $check = $user_id;
                $user_id = $user_id->user_id;

                $user_info = User::select()->where('id', $user_id)->first();

                $user_descriptions = UserDescription::where('user_id', $user_info->id)->first(); //<---user description
                if (isset($user_descriptions->gender)) {
                    $avatar = ($user_descriptions->gender === 'Male') ? 'avater-men.png' : 'avater-lady.png'; //<----avatar url
                } else {
                    $avatar = 'avater-men.png'; //<----avatar url
                }

                $email_verify = $user_info->email_verified_at;
                if ($email_verify == "") {
                    $verify_status = "Unverified";
                } elseif ($email_verify !== "") {
                    $verify_status = "Verified";
                }
                if (UserDescription::select('country_id')->where('user_id', $user_id)->exists()) {
                    $country_id = UserDescription::select('country_id')->where('user_id', $user_id)->first();
                    $country_name = Country::select('name')->where('id', '=', $country_id->country_id)->first();
                    $country = $country_name->name;
                } else {
                    $country = "";
                }



                $total_trading_account = TradingAccount::select('user_id')->where('user_id', $user_info->id)->count();

                $total_deposit = Deposit::where('transaction_type', '!=', 'atw')->where('user_id', $user_info->id)->sum('amount');

                $total_withdraw = Withdraw::where('transaction_type', '!=', 'wta')->where('user_id', $user_info->id)->sum('amount');

                $total_deposit_current = Deposit::where('user_id', $user_info->id)->sum('amount');
                $total_withdraw_current = Withdraw::where('user_id', $user_info->id)->sum('amount');

                if ($total_deposit_current >= $total_withdraw_current) {
                    $current_balance = ($total_deposit_current - $total_withdraw_current);
                } else {
                    $current_balance = 0;
                }

                //check bonus-user
                if (BonusUser::where('bonus_users.user_id', $user_info->id)->exists()) {
                    $latest_bonus = BonusUser::where('bonus_users.user_id', $user_info->id)->select()
                        ->join('bonus_packages', 'bonus_users.bonus_package', '=', 'bonus_packages.id')
                        ->join('deposits', 'bonus_users.deposit_id', '=', 'deposits.id')
                        ->first();
                    $bonus_amount = 0;
                    if ($latest_bonus->bonus_amount == 0) {
                        $deposit_amount = $latest_bonus->amount;
                        $bonus_amount = $deposit_amount * ($latest_bonus->bonus_percent / 100);
                    } else {
                        $bonus_amount = $latest_bonus->bonus_amount;
                    }
                } else {
                    $bonus_amount = 0;
                }
                //button added

                $buttons = "";
                if ($check->approve_status === 0) {
                    if (auth()->user()->hasDirectPermission('edit withdraw request')) {
                        $buttons = '<div class="details-text w-100">
                    <div class="btn-container p-0 m-0" style="float:right;">
                        <button data-type="button" class="btn btn-primary waves-effect waves-float waves-light"  data-loading="processing..."  data-id="' . $id . '" data-user_id="' . $user_id . '"  onclick="approve_request(this)">Approve</button>
                        <button type="button" class="btn btn-danger withdraw-decline-request-btn waves-effect waves-float waves-light"  data-loading="processing..."   data-id="' . $id . '" data-user_id="' . $user_id . '"  onclick="decline_request(this)">Decline</button>
                    </div>
                </div>';
                    } else {
                        $buttons = '';
                    }
                }
                $description = '<tr class="description" style="display:none">
                                    <td colspan="6">
                                        <div class="details-section-dark dt-details border-start-3 border-start-primary p-2">
                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <div class="rounded-0 w-75">
                                                    <table class="table table-responsive tbl-trader-details">
                                                        <tr>
                                                            <th>Current Balance</th>
                                                            <td>&dollar; ' . $current_balance . '</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Total Withdraw</th>
                                                            <td>&dollar; ' . $total_withdraw . '</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Total Deposit</th>
                                                            <td>&dollar; ' . $total_deposit . '</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Latest Bonus Receive</th>
                                                            <td>&dollar; ' . $bonus_amount . '</td>
                                                        </tr>
                                                    </table>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 d-flex justfy-content-between">    
                                                    <div class="rounded-0 w-100">
                                                        <table class="table table-responsive tbl-trader-details">
                                                            <tr>
                                                                <th>Verification Status</th>
                                                                <td>' . $verify_status . '</td>
                                                            </tr>
                                                            <tr>
                                                                <th>Country</th>
                                                                <td>' . $country . '</td>
                                                            </tr>
                                                            <tr>
                                                                <th>Phone Number</th>
                                                                <td>' . $user_info->phone . '</td>
                                                            </tr>
                                                            <tr>
                                                                <th>Total Trading Account</th>
                                                                <td>' . $total_trading_account . '</td>
                                                            </tr>
                                                        </table>
                                                    </div> 
                                                    
                                                    <div class="card ms-1 dt-trader-img">
                                                        <div class="card-body bg-light-secondary p-0 m-0" style="height: 152px;">
                                                        <img class="img img-fluid" src="' . asset("admin-assets/app-assets/images/avatars/$avatar") . ' "alt="avatar">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mt-3">
                                                <br>
                                                ' . $buttons . '
                                            </div> 
                                        </div>
                                    </td>
                                    <td class="d-none">&nbsp;</td>
                                    <td class="d-none">&nbsp;</td>
                                    <td class="d-none">&nbsp;</td>
                                    <td class="d-none">&nbsp;</td>
                                </tr>';
                $data = [
                    'status' => true,
                    'description' => $description
                ];

                return Response::json($data);
            }
            $draw = $request->input('draw');
            $start = $request->input('start');
            $length = $request->input('length');
            $order = $_GET['order'][0]["column"];
            $orderDir = $_GET["order"][0]["dir"];

            $columns = ['email', 'account_number', 'platform', 'approved_status', 'client_type', 'created_at'];
            $orderby = $columns[$order];

            $result = TradingAccount::select(
                'trading_accounts.id as id',
                'trading_accounts.approve_status',
                'users.email',
                'trading_accounts.platform',
                'trading_accounts.client_type',
                'trading_accounts.created_at',
                'trading_accounts.user_id',
                'trading_accounts.account_number'
            )
                ->join('users', 'trading_accounts.user_id', '=', 'users.id');
            //filter by trading account
            if ($request->trading_acc != "") {
                $result = $result->where('account_number', $request->trading_acc);
            }
            // filter by platform
            if ($request->platform != "") {

                $result = $result->where('trading_accounts.platform', $request->platform);
            }
            // Filter by approve status
            if ($request->approve_status != "") {
                $result = $result->where('trading_accounts.approve_status', $request->approve_status);
            }
            // filter by leverage
            if ($request->leverage != "") {
                $result = $result->where('trading_accounts.leverage', $request->leverage);
            }
            //Filter by info like name,email,phone
            if ($request->info != "") {
                $trader_info = $request->info;
                $result = $result->where(function ($q) use ($trader_info) {
                    $q->where('users.name', $trader_info)
                        ->orWhere('users.email', $trader_info)
                        ->orWhere('users.phone', $trader_info);
                });
            }

            //filter by IB Info
            if ($request->ib_info != "") {
                $ib_info = $request->ib_info;
                $ib = User::where('type', 4)->where(function ($q) use ($ib_info) {
                    $q->where('users.name', 'like', '%' . $ib_info . '%')
                        ->orWhere('users.email', $ib_info)
                        ->orWhere('users.phone', $ib_info);
                })->select('id')->first();
                // return $ib;
                if ($ib) {
                    $trader_id = IB::where('ib_id', $ib)->where('users.type', 0)
                        ->join('users', 'ib.reference_id', '=', 'users.id')
                        ->select('reference_id')->get();
                } else {
                    $trader_id = [];
                }

                $result = $result->whereIn('trading_accounts.user_id', $trader_id);
            }

            $count = $result->count();
            $result = $result->orderby($orderby, $orderDir)->skip($start)->take($length)->get();


            $data = array();
            $i = 0;

            foreach ($result as $user) {
                if ($user->approve_status == 0) {
                    $status = 'Pending';
                } elseif ($user->approve_status == 1) {
                    $status = 'Approved';
                } elseif ($user->approve_status == 2) {
                    $status = 'Declined';
                }

                $data[$i]['email'] = '<a href="#" data-id=' . $user->id . ' data-user_id=' . $user->user_id . ' class="dt-description justify-content-start d-flex"><span class="w me-3"> <i class="plus-minus" data-feather="plus"></i> </span> <span>' .  $user->email . '</span></a>';;
                $data[$i]['account_number'] = $user->account_number;
                $data[$i]['server'] = $user->platform;
                $data[$i]['status'] = $status;
                $data[$i]['account_type'] = $user->client_type;
                $data[$i]['action'] = date('d M y, h:i A', strtotime($user->created_at));

                $i++;
            }
            $output = array(
                'draw' => $draw,
                'recordsTotal' => $count, 'recordsFiltered' => $count,
            );
            $output['data'] = $data;

            return Response::json($output);
        }
        return view('admins.reports.trading-account-request');
    }

    // account approve or decline request
    public function approve_decline(Request $request)
    {
        // sending mail
        if ($request->op === 'mail') {
            $user_id = $request->user_id;
            $user = User::select()->where('id', $user_id)->first();
            $support_email = SystemConfig::select('support_email')->first();
            $support_email = ($support_email) ? $support_email->support_email : default_support_email();
            $email_data = [
                'name'              => ($user) ? $user->name : config('app.name') . ' User',
                'account_email'     => ($user) ? $user->email : '',
                'admin'             => auth()->user()->name,
                'login_url'         => route('login'),
                'support_email'     => $support_email,

            ];
            // mail message for approve request
            if ($request->mail_for === 'approve') {
                $email_data['custom_message'] = 'Your trading account request approved!';
            }
            // mail message for decline request
            else {
                $email_data['custom_message'] = 'Your trading account request Declined!';
            }

            if (Mail::to($user->email)->send(new ApproveTradingAccount($email_data))) {
                return Response::json(['status' => true, 'message' => 'Mail successfully sent for Approved request', 'success_title' => 'Approve request']);
            } else {
                return Response::json(['status' => false, 'message' => 'Mail sending failed, Please try again later!', 'success_title' => 'Approve request']);
            }
        }
        // for approve request
        if ($request->request_for === 'approve') {
            $update = TradingAccount::where('id', $request->id)->update([
                'approve_status' => 1,
                'approve_date' => date('y-m-d h:i:s', strtotime('NOW')),
                'approved_by' => auth()->user()->id
            ]);
            if ($update) {
                return Response::json([
                    'status' => true,
                    'message' => 'Account Successfully approved!'
                ]);
            }
            return Response::json([
                'status' => false,
                'message' => 'Account approval failed!'
            ]);
        }
        // for decline request
        else {
            $update = TradingAccount::where('id', $request->id)->update([
                'approve_status' => 2,
                'approve_date' => date('y-m-d h:i:s', strtotime('NOW')),
                'approved_by' => auth()->user()->id
            ]);
            if ($update) {
                return Response::json([
                    'status' => true,
                    'message' => 'Account Successfully approved!'
                ]);
            }
            return Response::json([
                'status' => false,
                'message' => 'Account approval failed!'
            ]);
        }
    }
}
