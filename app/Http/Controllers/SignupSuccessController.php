<?php

namespace App\Http\Controllers;

use App\Models\admin\SystemConfig;
use App\Models\TradingAccount;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class SignupSuccessController extends Controller
{
    public function signupSuccess(Request $request)
    {
        $user_id = request()->hash;
        $user_id = decrypt($user_id);
        $downloadLink = null;
        $platform = null;

        $TradingAccount = TradingAccount::select('platform')->where('user_id', $user_id)->first();
        if (isset($TradingAccount->platform)) {
            $platform = $TradingAccount->platform;
            $system_data = SystemConfig::select('platform_download_link')->first();
            $downloadLinkDe = json_decode($system_data->platform_download_link);
            if ($TradingAccount->platform == 'mt5') {
                $downloadLink = $downloadLinkDe->mt5_download_link;
            } else if ($TradingAccount->platform == 'mt4') {
                $downloadLink = $downloadLinkDe->mt5_download_link;
            }
        }

        return view('auth.signup_success', [
            'platform' => $platform,
            'downloadLink' => $downloadLink
        ]);
    }

    public function resendActiveionLink(Request $request)
    {


        return view('auth.resend_activation_link');
    }

    public function activeion(Request $request, $hash)
    {
        try {
            // Decrypt the user ID
            $user_id = decrypt($hash);
            
            // Find the user
            $user = User::find($user_id);
            
            if (!$user) {
                return redirect('https://my.coreprimemarkets.com/')->with('error', 'Invalid activation link');
            }
            
            // Update user status if needed
            if ($user->email_verified_at === null) {
                $user->email_verified_at = now();
                $user->save();
            }
            
            // Log the activation
            \Log::info('User account activated', [
                'user_id' => $user_id,
                'email' => $user->email,
                'ip' => request()->ip()
            ]);
            
            // Redirect to main site
            return redirect('https://my.coreprimemarkets.com/')->with('success', 'Account activated successfully!');
            
        } catch (\Exception $e) {
            \Log::error('Activation error', [
                'hash' => $hash,
                'error' => $e->getMessage()
            ]);
            
            return redirect('https://my.coreprimemarkets.com/')->with('error', 'Invalid activation link');
        }
    }
    public function demo_activeion(Request $request)
    {
        return view('auth.demo-activation');
    }
}
