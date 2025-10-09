<?php

namespace App\Http\Controllers\traders\tournaments;

use App\Http\Controllers\Controller;
use App\Models\admin\SystemConfig;
use App\Models\ComTrade;
use App\Models\Deposit;
use App\Models\PopupImage;
use App\Models\TradingAccount;
use App\Models\User;
use App\Models\Withdraw;
use App\Models\tournaments\TourSetting;
use App\Models\tournaments\TourParticipant;
use App\Models\tournaments\TourGroup;
use App\Services\AllFunctionService;
use App\Services\api\FileApiService;
use App\Services\balance\BalanceSheetService;
use App\Services\BalanceService;
use App\Services\bonus\BonusService;
use App\Services\contest\ContestService;
use App\Services\GetMonthNameService;
use App\Services\systems\VersionControllService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;


class TournamentDashboardController extends Controller
{
    public function tournamentDashboard()
    {
        $tourSetting = TourSetting::select()->first();
        $round1_groups = TourGroup::with('participants.user.description')->where('round', 'first')->get();
        $round2_groups = TourGroup::with('participants.user.description')->where('round', 'second')->get();
        $round3_groups = TourGroup::with('participants.user.description')->where('round', 'third')->get();
        $round4_groups = TourGroup::with('participants.user.description')->where('round', 'fourth')->get();
        return view('traders.tournaments.tournament-dashboard',[
            'round1_groups' => $round1_groups,
            'round2_groups' => $round2_groups,
            'round3_groups' => $round3_groups,
            'round4_groups' => $round4_groups,
            'tourSetting' => $tourSetting,
        ]);
    }
    
    public function deleteTournamentParticipant(Request $request){
        $find_trading_account = TradingAccount::where('account_number', $request->account_num)->where('user_id', auth()->user()->id)->first();
        if (!$find_trading_account) {
            return response()->json([
                'status' => false,
                'message' => 'This is not your trading account!',
            ]);
        }
        $delete_participant = TourParticipant::where('account_num', $request->account_num)->delete();
        if ($delete_participant) {
            return response()->json([
                'status' => true,
                'message' => 'Deleted successfully.',
            ]);
        }else{
            return response()->json([
                'status' => false,
                'message' => 'Failed to delete participant!',
            ]);
        }
    }
}
