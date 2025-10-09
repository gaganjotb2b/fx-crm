<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use App\Models\Task;
use App\Models\Deposit;
use App\Models\Withdrawal;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    /**
     * Show the dashboard.
     */
    public function index(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        // Base queries
        $depositsQuery = Deposit::query();
        $withdrawalsQuery = Withdrawal::query();
        $tasksQuery = Task::query();

        // Apply date filters if provided
        if ($startDate && $endDate) {
            $startDate = Carbon::parse($startDate)->startOfDay();
            $endDate = Carbon::parse($endDate)->endOfDay();

            $depositsQuery->whereBetween('date', [$startDate, $endDate]);
            $withdrawalsQuery->whereBetween('date', [$startDate, $endDate]);
        }

        // Calculate statistics
        $totalDepositsUSDT = $depositsQuery->sum('amount_in_usdt');
        $totalWithdrawalsUSDT = $withdrawalsQuery->sum('amount_in_usdt');
        $completedTasks = $tasksQuery->where('status', 'completed')->count();
        $pendingTasks = $tasksQuery->where('status', 'pending')->count();

        // Get user statistics (for super admin)
        $totalUsers = User::count();
        $activeUsers = User::where('is_active', true)->count();
        $totalRoles = Role::count();
        $recentUsers = User::with('role')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        return view('dashboard.index', compact(
            'totalUsers',
            'activeUsers',
            'totalRoles',
            'recentUsers',
            'totalDepositsUSDT',
            'totalWithdrawalsUSDT',
            'completedTasks',
            'pendingTasks',
            'startDate',
            'endDate'
        ));
    }
} 