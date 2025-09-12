<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;

class UsersController extends Controller
{
    /**
     * Display a listing of users.
     */
    public function index(Request $request)
    {
        $query = User::query();

        // Search filter
        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        // Role filter
        if ($request->role) {
            $query->where('role', $request->role);
        }

        $users = $query->withCount(['automobiles', 'bookings', 'transactions'])
                      ->orderBy('created_at', 'desc')
                      ->paginate(10);

        return Inertia::render('Admin/Users/Index', [
            'users' => $users,
            'filters' => $request->only(['search', 'role']),
            'roles' => ['admin', 'dealer', 'customer']
        ]);
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        return Inertia::render('Admin/Users/Create', [
            'roles' => ['admin', 'dealer', 'customer']
        ]);
    }

    /**
     * Store a newly created user.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,dealer,customer',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500'
        ]);

        $validated['password'] = Hash::make($validated['password']);

        User::create($validated);

        return redirect()->route('admin.users.index')
                        ->with('success', 'User created successfully.');
    }

    /**
     * Display the specified user.
     */
    public function show(User $user)
    {
        $user->load(['automobiles', 'bookings.automobile', 'transactions.automobile']);

        return Inertia::render('Admin/Users/Show', [
            'user' => $user,
            'stats' => [
                'automobiles_count' => $user->automobiles_count ?? 0,
                'bookings_count' => $user->bookings_count ?? 0,
                'transactions_count' => $user->transactions_count ?? 0,
                'total_spent' => $user->transactions()->where('status', 'completed')->sum('amount'),
                'total_earned' => $user->automobiles()->whereHas('transactions', function($q) {
                    $q->where('status', 'completed');
                })->withSum('transactions', 'amount')->get()->sum('transactions_sum_amount')
            ]
        ]);
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        return Inertia::render('Admin/Users/Edit', [
            'user' => $user,
            'roles' => ['admin', 'dealer', 'customer']
        ]);
    }

    /**
     * Update the specified user.
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'role' => 'required|in:admin,dealer,customer',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500'
        ]);

        // Only update password if provided
        if ($request->password) {
            $request->validate([
                'password' => 'required|string|min:8|confirmed',
            ]);
            $validated['password'] = Hash::make($request->password);
        }

        $user->update($validated);

        return redirect()->route('admin.users.index')
                        ->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified user.
     */
    public function destroy(User $user)
    {
        // Prevent admin from deleting themselves
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
                        ->with('success', 'User deleted successfully.');
    }

    /**
     * Display dealers management page.
     */
    public function dealers(Request $request)
    {
        $query = User::where('role', 'dealer');

        // Search filter
        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        // Status filter
        if ($request->status) {
            if ($request->status === 'active') {
                $query->whereNotNull('email_verified_at');
            } elseif ($request->status === 'inactive') {
                $query->whereNull('email_verified_at');
            }
        }

        $dealers = $query->withCount(['automobiles', 'bookings', 'transactions'])
                        ->with('automobiles:id,dealer_id,price')
                        ->orderBy('created_at', 'desc')
                        ->paginate(10);

        // Calculate stats
        $stats = [
            'total_dealers' => User::where('role', 'dealer')->count(),
            'active_dealers' => User::where('role', 'dealer')->whereNotNull('email_verified_at')->count(),
            'total_automobiles' => \App\Models\Automobile::count(),
            'total_sales' => \App\Models\Transaction::where('status', 'completed')->sum('amount')
        ];

        return Inertia::render('Admin/Dealers/Index', [
            'dealers' => $dealers,
            'stats' => $stats,
            'filters' => $request->only(['search', 'status'])
        ]);
    }

    /**
     * Display customers management page.
     */
    public function customers(Request $request)
    {
        $query = User::where('role', 'customer');

        // Search filter
        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        // Activity filter
        if ($request->activity) {
            if ($request->activity === 'active') {
                $query->whereHas('bookings');
            } elseif ($request->activity === 'inactive') {
                $query->whereDoesntHave('bookings');
            } elseif ($request->activity === 'high_value') {
                $query->whereHas('transactions', function ($q) {
                    $q->where('status', 'completed')
                      ->groupBy('user_id')
                      ->havingRaw('SUM(amount) > 50000');
                });
            }
        }

        $customers = $query->withCount(['bookings', 'transactions'])
                          ->withSum(['transactions as total_spent' => function ($query) {
                              $query->where('status', 'completed');
                          }], 'amount')
                          ->orderBy('created_at', 'desc')
                          ->paginate(10);

        // Calculate average transaction amount for each customer
        $customers->getCollection()->transform(function ($customer) {
            $customer->avg_transaction = $customer->transactions_count > 0
                ? $customer->total_spent / $customer->transactions_count
                : 0;
            return $customer;
        });

        // Calculate stats
        $stats = [
            'total_customers' => User::where('role', 'customer')->count(),
            'active_customers' => User::where('role', 'customer')->whereHas('bookings')->count(),
            'total_bookings' => \App\Models\Booking::count(),
            'total_spent' => \App\Models\Transaction::where('status', 'completed')
                                                   ->whereHas('user', function($q) {
                                                       $q->where('role', 'customer');
                                                   })->sum('amount')
        ];

        return Inertia::render('Admin/Customers/Index', [
            'customers' => $customers,
            'stats' => $stats,
            'filters' => $request->only(['search', 'activity'])
        ]);
    }
}
