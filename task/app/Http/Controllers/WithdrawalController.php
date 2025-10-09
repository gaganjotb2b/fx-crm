<?php

namespace App\Http\Controllers;

use App\Models\Withdrawal;
use App\Models\Country;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WithdrawalController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
        $this->middleware(function ($request, $next) {
            if (!auth()->user()->isSuperAdmin() && !auth()->user()->isAdmin()) {
                abort(403);
            }
            return $next($request);
        });
    }

    public function index(Request $request)
    {
        $query = Withdrawal::with(['country', 'creator']);

        // Date filter
        if ($request->filled('start_date')) {
            $query->whereDate('date', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('date', '<=', $request->end_date);
        }

        // Search filter
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('customer_name', 'like', "%{$searchTerm}%")
                  ->orWhere('customer_phone', 'like', "%{$searchTerm}%")
                  ->orWhere('customer_email', 'like', "%{$searchTerm}%")
                  ->orWhere('manager_id', 'like', "%{$searchTerm}%")
                  ->orWhere('account_number', 'like', "%{$searchTerm}%")
                  ->orWhere('description', 'like', "%{$searchTerm}%")
                  ->orWhereHas('country', function($q) use ($searchTerm) {
                      $q->where('name', 'like', "%{$searchTerm}%");
                  });
            });
        }

        $withdrawals = $query->latest()->paginate(10)->withQueryString();
        
        return view('withdrawals.index', compact('withdrawals'));
    }

    public function create()
    {
        $countries = Country::where('is_active', true)->get();
        return view('withdrawals.create', compact('countries'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:255',
            'customer_email' => 'nullable|email|max:255',
            'manager_id' => 'required|integer',
            'country_id' => 'required|exists:countries,id',
            'amount' => 'required|numeric|min:0',
            'usdt_rate' => 'required|numeric|min:0',
            'amount_in_usdt' => 'required|numeric|min:0',
            'account_number' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $validated['created_by'] = Auth::id();

        Withdrawal::create($validated);

        return redirect()->route('withdrawals.index')
            ->with('success', 'Withdrawal created successfully.');
    }

    public function show(Withdrawal $withdrawal)
    {
        return view('withdrawals.show', compact('withdrawal'));
    }

    public function edit(Withdrawal $withdrawal)
    {
        $countries = Country::where('is_active', true)->get();
        return view('withdrawals.edit', compact('withdrawal', 'countries'));
    }

    public function update(Request $request, Withdrawal $withdrawal)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:255',
            'customer_email' => 'nullable|email|max:255',
            'manager_id' => 'required|integer',
            'country_id' => 'required|exists:countries,id',
            'amount' => 'required|numeric|min:0',
            'usdt_rate' => 'required|numeric|min:0',
            'amount_in_usdt' => 'required|numeric|min:0',
            'account_number' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $withdrawal->update($validated);

        return redirect()->route('withdrawals.index')
            ->with('success', 'Withdrawal updated successfully.');
    }

    public function destroy(Withdrawal $withdrawal)
    {
        $withdrawal->delete();

        return redirect()->route('withdrawals.index')
            ->with('success', 'Withdrawal deleted successfully.');
    }
} 