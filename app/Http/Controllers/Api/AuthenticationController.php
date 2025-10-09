<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\OtpCode;
use App\Models\User;
use App\Services\EmailService;
use Carbon\Carbon;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class AuthenticationController extends Controller
{
    //
    public function trader_login(Request $request)
    {
        try {
            $validation_ruls = [
                'email' => 'required|email|exists:users,email',
                'password' => 'required',
            ];
            $validator = Validator::make($request->all(), $validation_ruls);
            if ($validator->fails()) {
                return Response::json([
                    'status' => false,
                    'otp' => false,
                    'message' => 'Validation error',
                    'errors' => $validator->errors(),
                ]);
            }
            // check user password
            $requested_user = User::where('email', $request->email)
                ->where(function ($query) {
                    $query->where('type', 0)
                        ->orWhere('type', 4);
                });
            if (!$requested_user->exists()) {
                return Response::json([
                    'status' => false,
                    'otp' => false,
                    'message' => 'User not register with this email',
                    'errors' => ['email' => 'User not registered with this email']
                ]);
            }
            if (!Hash::check($request->password, $requested_user->first()->password)) {
                return Response::json([
                    'status' => false,
                    'otp' => false,
                    'message' => 'Incorrect password, please try another',
                    'errors' => ['password' => 'Incorrect password, please try another']
                ]);
            }
            if ($requested_user->first() == null) { //if the user is a manager but he has not verify his account?
                return Response::json([
                    'status' => false,
                    'otp' => false,
                    'message' => 'Your account is not verified. Please <a class="text-danger" href="#">verify your account</a>.'
                ]);
            }

            // check has OTP settings ON
            $user = $requested_user->first();
            if ($user->email_auth) {
                $user->otpCode()->delete();
                $data = [
                    'code' => mt_rand(100000, 999999),
                    'user_id' => $user->id,
                    'email' => $request->input('email'),
                    'type' => 'login',
                    'properties' => json_encode(['email' => $request->input('email'), 'password' => $request->input('password')])
                ];
                OtpCode::create($data);
                EmailService::send_email('otp-verification', [
                    'account_email' => $user->email,
                    'otp' => $data['code'],
                    'user_id' => $user->id,
                    'name' => $user->name,
                ]);
                return Response::json([
                    'status' => true,
                    'otp' => true,
                    'message' => 'We sending the OTP code to your email, please check your mail',
                ]);
            }

            // check login user is IB or Trader for non combined
            if (strtolower($requested_user->first()->type) === 'trader') {
                $client = $requested_user->select(
                    'id',
                    'name',
                    'phone',
                    'email',
                    'type',
                    'live_status',
                    'transaction_password as pin',
                    'active_status',
                    'login_status',
                    'email_verified_at',
                    'trading_ac_limit as trading_account_limit',
                    'kyc_status',
                    'created_at',
                )->with([
                    'user_description' => function ($query) {
                        $query->select('user_id', 'state', 'city', 'country_id', 'address', 'zip_code', 'gender', 'date_of_birth');
                    },
                    'user_description.country' => function ($query) {
                        $query->select('id', 'name');
                    },
                    'otpOptions' => function ($query) {
                        $query->select('user_id', 'account_create', 'deposit', 'withdraw', 'transfer');
                    },
                    'financeOptions' => function ($query) {
                        $query->select('user_id', 'deposit_operation', 'withdraw_operation', 'internal_transfer', 'wta_transfer', 'trader_to_trader', 'trader_to_ib', 'ib_to_ib', 'ib_to_trader', 'kyc_verify');
                    },
                    'IbAccount' => function ($query) {
                        $query->select('id', 'name', 'email', 'phone', 'type', 'live_status', 'transaction_password as pin', 'active_status', 'email_verified_at', 'kyc_status', 'created_at');
                    },
                    'IbAccount.otpOptions' => function ($query) {
                        $query->select('user_id', 'account_create', 'deposit', 'withdraw', 'transfer');
                    },
                    'IbAccount.financeOptions' => function ($query) {
                        $query->select('user_id', 'deposit_operation', 'withdraw_operation', 'internal_transfer', 'wta_transfer', 'trader_to_trader', 'trader_to_ib', 'ib_to_ib', 'ib_to_trader', 'kyc_verify');
                    },
                ])->first();
            } else {
                $client = $requested_user->select('id', 'name', 'email', 'phone', 'type', 'live_status', 'transaction_password as pin', 'active_status', 'login_status', 'email_verified_at', 'kyc_status', 'created_at')
                    ->with([
                        'user_description' => function ($query) {
                            $query->select('user_id', 'state', 'city', 'country_id', 'address', 'zip_code', 'gender', 'date_of_birth');
                        },
                        'user_description.country' => function ($query) {
                            $query->select('id', 'name');
                        },
                        'otpOptions' => function ($query) {
                            $query->select('user_id', 'account_create', 'deposit', 'withdraw', 'transfer');
                        },
                        'financeOptions' => function ($query) {
                            $query->select('user_id', 'deposit_operation', 'withdraw_operation', 'internal_transfer', 'wta_transfer', 'trader_to_trader', 'trader_to_ib', 'ib_to_ib', 'ib_to_trader', 'kyc_verify');
                        },
                        'TraderAccount' => function ($query) {
                            $query->select('id', 'name', 'email', 'phone', 'type', 'live_status', 'trading_ac_limit as trading_account_limit', 'transaction_password as pin', 'active_status', 'email_verified_at', 'kyc_status', 'created_at');
                        },
                        'TraderAccount.otpOptions' => function ($query) {
                            $query->select('user_id', 'account_create', 'deposit', 'withdraw', 'transfer');
                        },
                        'TraderAccount.financeOptions' => function ($query) {
                            $query->select('user_id', 'deposit_operation', 'withdraw_operation', 'internal_transfer', 'wta_transfer', 'trader_to_trader', 'trader_to_ib', 'ib_to_ib', 'ib_to_trader', 'kyc_verify');
                        },
                    ])->first();
            }

            // check user blocked or not
            if ($client->active_status != 1) {
                return Response::json([
                    'status' => false,
                    'otp' => false,
                    'message' => 'This account is temporarily blocked!, please contact for support',
                ]);
            }

            // check user activated or not
            if (empty($client->email_verified_at)) {
                return Response::json([
                    'status' => false,
                    'otp' => false,
                    'email_verified_at' => false,
                    'message' => 'Your account is not activated. Please activate your account.',
                ]);
            }
            return Response::json([
                'status' => true,
                'otp' => false,
                'message' => ($client->type === 'trader' ? 'Trader' : 'IB') . " logged in successfully",
                'type' => $client->type,
                'token' => $client->createToken('trader-login', ['*'])->plainTextToken,
                'refresh_token' => $client->createToken('trader-refresh', ['server:update'], now()->addMonth())->plainTextToken,
                // "data" => $client
            ], 200);
        } catch (\Throwable $th) {
            // throw $th;
            return Response::json([
                'status' => false,
                'otp' => false,
                'message' => 'Got a server error, contact for support',
            ]);
        }
    }
    // trader registration
    // trader registration
    public function refresh_token(Request $request)
    {
        try {
            $user = User::where('id', auth()->user()->id)->first();
            // return $user;
            if ($user) {
                $token = $user->createToken('trader-login', ['*'], now()->addDays(7))->plainTextToken;
                return Response::json([
                    'status' => true,
                    'message' => 'Login data successfully reloaded',
                    'token' => $token,
                ]);
            }
            return Response::json([
                'status' => false,
                'message' => 'Refresh failed, please try again later'
            ]);
        } catch (\Throwable $th) {
            // throw $th;
            return Response::json([
                'status' => false,
                'message' => 'Got a server error, please try again later'
            ]);
        }
    }
    // otp check for login
    public function login_otp(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'code' => 'required|exists:otp_codes,code',
                'email' => 'required|exists:users,email'
            ]);
            if ($validator->fails()) {
                // count wrong input
                $code = OtpCode::where('type', 'login')
                    ->where('email', $request->input('email'))->first();
                if ($code) {
                    $code->action_count = $code->action_count + 1;
                    $code->save();
                }

                return Response::json([
                    'status' => false,
                    'message' => 'Validation error, please fix the following errors',
                    'errors' => $validator->errors(),
                ]);
            }

            $code = OtpCode::where(function ($query) use ($request) {
                $query->where('code', $request->input('code'))
                    ->where('type', 'login');
            })->with(['user'])->first();
            if (!$code) {
                return Response::json([
                    'status' => false,
                    'message' => 'You enter invalid code, please try with valid code',
                    'errors' => ['code' => 'The code is invalid'],
                ]);
            }
            // check wrong input
            if ($code->action_count > 3) {
                $code->delete();
                return Response::json([
                    'status' => false,
                    'message' => 'You try more than three times, the OTP is not work now'
                ]);
            }
            // check otp code is expired or not
            // check if it does not expired: the time is one hour
            if ($code->created_at->addMinutes(1) < Carbon::now()) {
                $code->delete();
                return Response::json(
                    [
                        'status' => false,
                        'message' => 'Code is expired'
                    ]
                );
            }
            $client = $code->user;
            if ($client) {
                $code->delete();
            }
            return Response::json([
                'status' => true,
                'otp' => false,
                'message' => $client->type === 'trader' ? 'Trader' : 'IB' . ' logged in successfully',
                'token' => $client->createToken('trader-login', ['*'], now()->addDays(7))->plainTextToken,
                'refresh_token' => $client->createToken('trader-refresh', ['server:update'], now()->addMonth())->plainTextToken,
            ]);
        } catch (\Throwable $th) {
            // throw $th;
            return Response::json([
                'status' => false,
                'message' => 'Got a server error, please try again later'
            ]);
        }
    }
}
