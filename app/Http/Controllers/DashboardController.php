<?php

namespace App\Http\Controllers;

use App\Models\Automobile;
use App\Models\Booking;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Display the dashboard.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        if ($user->isAdmin()) {
            return $this->adminDashboard($request);
        } elseif ($user->isDealer()) {
            return $this->dealerDashboard($request);
        } else {
            return $this->customerDashboard($request);
        }
    }

    /**
     * Admin dashboard with comprehensive analytics
     */
    private function adminDashboard(Request $request)
    {
        $period = $request->get('period', '30'); // Default to 30 days
        $startDate = Carbon::now()->subDays($period);

        // Overview Statistics
        $stats = [
            'total_automobiles' => Automobile::count(),
            'available_automobiles' => Automobile::where('status', 'available')->count(),
            'total_bookings' => Booking::count(),
            'pending_bookings' => Booking::where('status', 'pending')->count(),
            'total_transactions' => Transaction::count(),
            'total_revenue' => Transaction::where('status', 'completed')->sum('amount'),
            'total_users' => User::count(),
            'total_dealers' => User::where('role', 'dealer')->count(),
        ];

        // Sales Analytics (last 30 days)
        $salesData = Transaction::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(amount) as total'),
                DB::raw('COUNT(*) as count')
            )
            ->where('status', 'completed')
            ->where('created_at', '>=', $startDate)
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Top Performing Automobiles
        $topAutomobiles = Automobile::select('automobiles.*', DB::raw('COUNT(transactions.id) as transaction_count'), DB::raw('SUM(transactions.amount) as total_revenue'))
            ->leftJoin('transactions', 'automobiles.id', '=', 'transactions.automobile_id')
            ->where('transactions.status', 'completed')
            ->groupBy('automobiles.id')
            ->orderBy('total_revenue', 'desc')
            ->limit(5)
            ->get();

        // Booking Status Distribution
        $bookingStats = Booking::select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->get()
            ->pluck('count', 'status');

        // Transaction Type Distribution
        $transactionStats = Transaction::select('type', DB::raw('SUM(amount) as total'))
            ->where('status', 'completed')
            ->groupBy('type')
            ->get()
            ->pluck('total', 'type');

        // Recent Activities
        $recentBookings = Booking::with(['user', 'automobile'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $recentTransactions = Transaction::with(['user', 'automobile'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Monthly Revenue Trend (last 12 months)
        $monthlyRevenue = Transaction::select(
                DB::raw('YEAR(created_at) as year'),
                DB::raw('MONTH(created_at) as month'),
                DB::raw('SUM(amount) as total')
            )
            ->where('status', 'completed')
            ->where('created_at', '>=', Carbon::now()->subYear())
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        return Inertia::render('Dashboard/Admin', [
            'stats' => $stats,
            'salesData' => $salesData,
            'topAutomobiles' => $topAutomobiles,
            'bookingStats' => $bookingStats,
            'transactionStats' => $transactionStats,
            'recentBookings' => $recentBookings,
            'recentTransactions' => $recentTransactions,
            'monthlyRevenue' => $monthlyRevenue,
            'period' => $period
        ]);
    }

    /**
     * Dealer dashboard with dealer-specific metrics
     */
    private function dealerDashboard(Request $request)
    {
        $dealerId = Auth::id();
        $period = $request->get('period', '30');
        $startDate = Carbon::now()->subDays($period);

        // Dealer Statistics
        $stats = [
            'my_automobiles' => Automobile::where('dealer_id', $dealerId)->count(),
            'available_automobiles' => Automobile::where('dealer_id', $dealerId)->where('status', 'available')->count(),
            'my_bookings' => Booking::whereHas('automobile', function($q) use ($dealerId) {
                $q->where('dealer_id', $dealerId);
            })->count(),
            'pending_bookings' => Booking::whereHas('automobile', function($q) use ($dealerId) {
                $q->where('dealer_id', $dealerId);
            })->where('status', 'pending')->count(),
            'my_transactions' => Transaction::whereHas('automobile', function($q) use ($dealerId) {
                $q->where('dealer_id', $dealerId);
            })->count(),
            'my_revenue' => Transaction::whereHas('automobile', function($q) use ($dealerId) {
                $q->where('dealer_id', $dealerId);
            })->where('status', 'completed')->sum('amount'),
            'pending_payments' => Transaction::whereHas('automobile', function($q) use ($dealerId) {
                $q->where('dealer_id', $dealerId);
            })->where('status', 'pending')->count(),
        ];

        // My Sales Data
        $salesData = Transaction::select(
                DB::raw('DATE(transactions.created_at) as date'),
                DB::raw('SUM(transactions.amount) as total'),
                DB::raw('COUNT(*) as count')
            )
            ->join('automobiles', 'transactions.automobile_id', '=', 'automobiles.id')
            ->where('automobiles.dealer_id', $dealerId)
            ->where('transactions.status', 'completed')
            ->where('transactions.created_at', '>=', $startDate)
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // My Top Automobiles
        $topAutomobiles = Automobile::select('automobiles.*', DB::raw('COUNT(transactions.id) as transaction_count'), DB::raw('SUM(transactions.amount) as total_revenue'))
            ->leftJoin('transactions', 'automobiles.id', '=', 'transactions.automobile_id')
            ->where('automobiles.dealer_id', $dealerId)
            ->where(function($q) {
                $q->whereNull('transactions.id')
                  ->orWhere('transactions.status', 'completed');
            })
            ->groupBy('automobiles.id')
            ->orderBy('total_revenue', 'desc')
            ->limit(5)
            ->get();

        // Recent Activities
        $recentBookings = Booking::with(['user', 'automobile'])
            ->whereHas('automobile', function($q) use ($dealerId) {
                $q->where('dealer_id', $dealerId);
            })
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $recentTransactions = Transaction::with(['user', 'automobile'])
            ->whereHas('automobile', function($q) use ($dealerId) {
                $q->where('dealer_id', $dealerId);
            })
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return Inertia::render('Dashboard/Dealer', [
            'stats' => $stats,
            'salesData' => $salesData,
            'topAutomobiles' => $topAutomobiles,
            'recentBookings' => $recentBookings,
            'recentTransactions' => $recentTransactions,
            'period' => $period
        ]);
    }

    /**
     * Customer dashboard with customer-specific information
     */
    private function customerDashboard(Request $request)
    {
        $customerId = Auth::id();

        // Customer Statistics
        $stats = [
            'my_bookings' => Booking::where('user_id', $customerId)->count(),
            'pending_bookings' => Booking::where('user_id', $customerId)->where('status', 'pending')->count(),
            'confirmed_bookings' => Booking::where('user_id', $customerId)->where('status', 'confirmed')->count(),
            'my_transactions' => Transaction::where('user_id', $customerId)->count(),
            'total_spent' => Transaction::where('user_id', $customerId)->where('status', 'completed')->sum('amount'),
            'pending_payments' => Transaction::where('user_id', $customerId)->where('status', 'pending')->sum('amount'),
        ];

        // Recent Activities
        $recentBookings = Booking::with(['automobile.dealer'])
            ->where('user_id', $customerId)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $recentTransactions = Transaction::with(['automobile.dealer'])
            ->where('user_id', $customerId)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Check if customer is new (no bookings or transactions)
        $isNewCustomer = $stats['my_bookings'] == 0 && $stats['my_transactions'] == 0;

        // Recommended Automobiles (based on user's previous interests or new customer recommendations)
        $recommendedAutomobiles = Automobile::with('dealer')
            ->where('status', 'available')
            ->when($isNewCustomer, function ($query) {
                // For new customers, show featured automobiles
                return $query->orderBy('created_at', 'desc');
            }, function ($query) use ($customerId) {
                // For existing customers, we could implement more personalized recommendations
                return $query->orderBy('created_at', 'desc');
            })
            ->limit(6)
            ->get();

        return Inertia::render('Dashboard/Customer', [
            'stats' => $stats,
            'recentBookings' => $recentBookings,
            'recentTransactions' => $recentTransactions,
            'recommendedAutomobiles' => $recommendedAutomobiles,
            'isNewCustomer' => $isNewCustomer
        ]);
    }
}
