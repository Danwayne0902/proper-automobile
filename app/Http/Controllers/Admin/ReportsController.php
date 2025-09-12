<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Automobile;
use App\Models\Booking;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ReportsController extends Controller
{
    /**
     * Display the reports dashboard.
     */
    public function index(Request $request)
    {
        $period = $request->input('period', 30); // Default to last 30 days
        $startDate = Carbon::now()->subDays($period);

        $analytics = [
            'revenue' => $this->getRevenueData($startDate),
            'bookings' => $this->getBookingsData($startDate),
            'automobiles' => $this->getAutomobilesData($startDate),
            'users' => $this->getUsersData($startDate),
            'topPerformers' => $this->getTopPerformers($startDate),
            'salesTrends' => $this->getSalesTrends($startDate)
        ];

        return Inertia::render('Admin/Reports/Index', [
            'analytics' => $analytics,
            'period' => $period,
            'periods' => [
                7 => 'Last 7 days',
                30 => 'Last 30 days',
                90 => 'Last 90 days',
                365 => 'Last year'
            ]
        ]);
    }

    /**
     * Generate detailed revenue report.
     */
    public function show(Request $request, $type)
    {
        $period = $request->input('period', 30);
        $startDate = Carbon::now()->subDays($period);

        switch ($type) {
            case 'revenue':
                $data = $this->getDetailedRevenueReport($startDate);
                break;
            case 'bookings':
                $data = $this->getDetailedBookingsReport($startDate);
                break;
            case 'automobiles':
                $data = $this->getDetailedAutomobilesReport($startDate);
                break;
            case 'users':
                $data = $this->getDetailedUsersReport($startDate);
                break;
            default:
                abort(404);
        }

        return Inertia::render('Admin/Reports/Show', [
            'reportType' => $type,
            'data' => $data,
            'period' => $period
        ]);
    }

    private function getRevenueData($startDate)
    {
        $total = Transaction::where('status', 'completed')
                           ->where('created_at', '>=', $startDate)
                           ->sum('amount');

        $previous = Transaction::where('status', 'completed')
                              ->where('created_at', '>=', $startDate->copy()->subDays($startDate->diffInDays(Carbon::now())))
                              ->where('created_at', '<', $startDate)
                              ->sum('amount');

        $growth = $previous > 0 ? (($total - $previous) / $previous) * 100 : 0;

        return [
            'total' => $total,
            'growth' => round($growth, 2),
            'dailyAverage' => $total / max($startDate->diffInDays(Carbon::now()), 1)
        ];
    }

    private function getBookingsData($startDate)
    {
        $total = Booking::where('created_at', '>=', $startDate)->count();
        $confirmed = Booking::where('created_at', '>=', $startDate)
                           ->where('status', 'confirmed')
                           ->count();
        $pending = Booking::where('created_at', '>=', $startDate)
                         ->where('status', 'pending')
                         ->count();

        return [
            'total' => $total,
            'confirmed' => $confirmed,
            'pending' => $pending,
            'conversionRate' => $total > 0 ? round(($confirmed / $total) * 100, 2) : 0
        ];
    }

    private function getAutomobilesData($startDate)
    {
        $total = Automobile::count();
        $newlyAdded = Automobile::where('created_at', '>=', $startDate)->count();
        $sold = Automobile::where('status', 'sold')
                         ->where('updated_at', '>=', $startDate)
                         ->count();

        return [
            'total' => $total,
            'newlyAdded' => $newlyAdded,
            'sold' => $sold,
            'available' => Automobile::where('status', 'available')->count()
        ];
    }

    private function getUsersData($startDate)
    {
        $newUsers = User::where('created_at', '>=', $startDate)->count();
        $totalUsers = User::count();

        return [
            'new' => $newUsers,
            'total' => $totalUsers,
            'dealers' => User::where('role', 'dealer')->count(),
            'customers' => User::where('role', 'customer')->count()
        ];
    }

    private function getTopPerformers($startDate)
    {
        $topDealers = User::where('role', 'dealer')
                         ->withCount(['automobiles' => function($query) use ($startDate) {
                             $query->whereHas('transactions', function($q) use ($startDate) {
                                 $q->where('status', 'completed')
                                   ->where('created_at', '>=', $startDate);
                             });
                         }])
                         ->orderBy('automobiles_count', 'desc')
                         ->limit(5)
                         ->get();

        $topAutomobiles = Automobile::withSum(['transactions' => function($query) use ($startDate) {
                                    $query->where('status', 'completed')
                                          ->where('created_at', '>=', $startDate);
                                }], 'amount')
                                ->with('dealer')
                                ->orderBy('transactions_sum_amount', 'desc')
                                ->limit(5)
                                ->get();

        return [
            'dealers' => $topDealers,
            'automobiles' => $topAutomobiles
        ];
    }

    private function getSalesTrends($startDate)
    {
        $dailySales = Transaction::where('status', 'completed')
                                ->where('created_at', '>=', $startDate)
                                ->select([
                                    DB::raw('DATE(created_at) as date'),
                                    DB::raw('SUM(amount) as total'),
                                    DB::raw('COUNT(*) as count')
                                ])
                                ->groupBy('date')
                                ->orderBy('date')
                                ->get();

        return $dailySales->map(function ($sale) {
            return [
                'date' => $sale->date,
                'revenue' => $sale->total,
                'transactions' => $sale->count
            ];
        });
    }

    private function getDetailedRevenueReport($startDate)
    {
        return Transaction::with(['user', 'automobile.dealer'])
                         ->where('status', 'completed')
                         ->where('created_at', '>=', $startDate)
                         ->orderBy('created_at', 'desc')
                         ->paginate(20);
    }

    private function getDetailedBookingsReport($startDate)
    {
        return Booking::with(['user', 'automobile.dealer'])
                     ->where('created_at', '>=', $startDate)
                     ->orderBy('created_at', 'desc')
                     ->paginate(20);
    }

    private function getDetailedAutomobilesReport($startDate)
    {
        return Automobile::with(['dealer', 'images'])
                        ->where('created_at', '>=', $startDate)
                        ->orderBy('created_at', 'desc')
                        ->paginate(20);
    }

    private function getDetailedUsersReport($startDate)
    {
        return User::withCount(['automobiles', 'bookings', 'transactions'])
                  ->where('created_at', '>=', $startDate)
                  ->orderBy('created_at', 'desc')
                  ->paginate(20);
    }

    /**
     * Get pending payments count for dashboard
     */
    private function getPendingPaymentsCount()
    {
        $user = Auth::user();

        if ($user->isAdmin()) {
            return Transaction::where('status', 'pending')->count();
        } elseif ($user->isDealer()) {
            return Transaction::where('status', 'pending')
                             ->whereHas('automobile', function ($query) use ($user) {
                                 $query->where('dealer_id', $user->id);
                             })
                             ->count();
        }

        return 0;
    }

    /**
     * Display pending payments for confirmation
     */
    public function payments(Request $request)
    {
        $user = Auth::user();

        // Get pending transactions that this user can confirm
        $query = Transaction::with(['user', 'automobile.dealer', 'bankAccount'])
            ->where('status', 'pending');

        // If user is a dealer, only show their own transactions
        // If user is admin, show all pending transactions
        if ($user->isDealer()) {
            $query->whereHas('automobile', function ($query) use ($user) {
                $query->where('dealer_id', $user->id);
            });
        }

        $query->when($request->search, function ($query, $search) {
                $query->where(function ($query) use ($search) {
                    $query->where('transaction_number', 'like', "%{$search}%")
                          ->orWhereHas('user', function ($query) use ($search) {
                              $query->where('name', 'like', "%{$search}%")
                                    ->orWhere('email', 'like', "%{$search}%");
                          })
                          ->orWhereHas('automobile', function ($query) use ($search) {
                              $query->where('make', 'like', "%{$search}%")
                                    ->orWhere('model', 'like', "%{$search}%");
                          });
                });
            })
            ->when($request->type, function ($query, $type) {
                $query->where('type', $type);
            });

        $transactions = $query->orderBy('created_at', 'desc')->paginate(15);

        return Inertia::render('Admin/Reports/Payments', [
            'transactions' => $transactions
        ]);
    }

    /**
     * Confirm a payment
     */
    public function confirmPayment(Request $request, Transaction $transaction)
    {
        $user = Auth::user();

        // Check if user is authorized to confirm this payment
        if ($user->isDealer() && $transaction->automobile->dealer_id !== $user->id) {
            return redirect()->back()->with('error', 'You are not authorized to confirm this payment.');
        }

        // Validate the status update
        $validated = $request->validate([
            'status' => 'required|in:completed,failed',
            'notes' => 'nullable|string|max:500'
        ]);

        // Update transaction status
        $transaction->update([
            'status' => $validated['status'],
            'processed_at' => now(),
            'metadata' => array_merge($transaction->metadata ?? [], [
                'confirmation_notes' => $validated['notes'] ?? null,
                'confirmed_by' => $user->id,
                'confirmed_at' => now()
            ])
        ]);

        // Handle status changes
        if ($validated['status'] === 'completed') {
            if ($transaction->type === 'payment') {
                // Mark automobile as sold
                $transaction->automobile->update(['status' => 'sold']);
            } elseif ($transaction->type === 'deposit') {
                // Mark automobile as reserved
                $transaction->automobile->update(['status' => 'reserved']);
            }

            return redirect()->back()->with('success', 'Payment confirmed successfully. Transaction completed.');
        } else {
            // Reset automobile status to available if payment failed
            if (in_array($transaction->type, ['payment', 'deposit'])) {
                $transaction->automobile->update(['status' => 'available']);
            }

            return redirect()->back()->with('success', 'Payment marked as failed. Automobile status updated.');
        }
    }
}
