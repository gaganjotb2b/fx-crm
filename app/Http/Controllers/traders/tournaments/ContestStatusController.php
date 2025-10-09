<?php

namespace App\Http\Controllers\traders\contest;

use App\Http\Controllers\Controller;
use App\Models\ContestJoin;
use App\Services\AllFunctionService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ContestStatusController extends Controller
{
    public function __construct()
    {
        
        if (request()->is('user/contest/contest-status')) {
            $this->middleware(AllFunctionService::access('contest_status', 'trader'));
            $this->middleware(AllFunctionService::access('contest', 'trader'));
        }
    }
    public  static function index(Request $request)
    {
        // $contest = ContestJoin::where('user_id', auth()->user()->id)
        //     ->join('contests', 'contest_joins.contest_id', '=', 'contests.id')
        //     ->whereDate('end_date', '>=', Carbon::now())->get();
        $contest = ContestJoin::where('user_id', auth()->user()->id)
            ->join('contests', 'contest_joins.contest_id', '=', 'contests.id')
            ->whereDate('contests.contest_end_on', '>=', Carbon::now())->get();
        return view('traders.contest.contest-status', [
            'contest' => $contest
        ]);
    }
}
