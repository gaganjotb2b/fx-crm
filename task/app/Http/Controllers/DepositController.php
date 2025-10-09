<?php

namespace App\Http\Controllers;

use App\Models\Deposit;
use App\Models\Country;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DepositController extends Controller
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
        $query = Deposit::with(['country', 'creator']);

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

        $deposits = $query->latest()->paginate(10)->withQueryString();
        
        return view('deposits.index', compact('deposits'));
    }

    public function create()
    {
        $managers = User::where('is_active', true)->get();
        $countries = Country::where('is_active', true)->get();
        
        return view('deposits.create', compact('managers', 'countries'));
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

        Deposit::create($validated);

        return redirect()->route('deposits.index')
            ->with('success', 'Deposit created successfully.');
    }

    public function show(Deposit $deposit)
    {
        $deposit->load(['manager', 'country', 'creator']);
        return view('deposits.show', compact('deposit'));
    }

    public function edit(Deposit $deposit)
    {
        $managers = User::where('is_active', true)->get();
        $countries = Country::where('is_active', true)->get();
        
        return view('deposits.edit', compact('deposit', 'managers', 'countries'));
    }

    public function update(Request $request, Deposit $deposit)
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

        $deposit->update($validated);

        return redirect()->route('deposits.index')
            ->with('success', 'Deposit updated successfully.');
    }

    public function destroy(Deposit $deposit)
    {
        $deposit->delete();

        return redirect()->route('deposits.index')
            ->with('success', 'Deposit deleted successfully.');
    }
} 