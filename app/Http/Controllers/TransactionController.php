<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Automobile;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Illuminate\Validation\Rule;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Transaction::with(['user', 'automobile.dealer', 'booking'])
            ->when($request->search, function ($query, $search) {
                $query->where(function ($query) use ($search) {
                    $query->where('transaction_number', 'like', "%{$search}%")
                          ->orWhere('type', 'like', "%{$search}%")
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
            ->when($request->status, function ($query, $status) {
                $query->where('status', $status);
            })
            ->when($request->type, function ($query, $type) {
                $query->where('type', $type);
            })
            ->when($request->date_from, function ($query, $dateFrom) {
                $query->whereDate('created_at', '>=', $dateFrom);
            })
            ->when($request->date_to, function ($query, $dateTo) {
                $query->whereDate('created_at', '<=', $dateTo);
            });

        // Filter based on user role
        $user = Auth::user();
        if ($user->isCustomer()) {
            // Customers can only see their own transactions
            $query->where('user_id', $user->id);
        } elseif ($user->isDealer()) {
            // Dealers can see transactions for their automobiles
            $query->whereHas('automobile', function ($query) use ($user) {
                $query->where('dealer_id', $user->id);
            });
        }
        // Admins can see all transactions

        $transactions = $query->orderBy('created_at', 'desc')->paginate(15);

        return Inertia::render('Transactions/Index', [
            'transactions' => $transactions,
            'filters' => $request->only(['search', 'status', 'type', 'date_from', 'date_to']),
            'statuses' => ['pending', 'processing', 'completed', 'failed', 'refunded'],
            'types' => ['payment', 'deposit', 'refund', 'commission'],
            'canCreate' => $user->isCustomer()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        // Only customers and admins can create transactions
        if (!Auth::user()->isCustomer() && !Auth::user()->isAdmin()) {
            return redirect()->route('transactions.index')
                           ->with('error', 'You do not have permission to create transactions.');
        }

        // Get available automobiles and bookings for transaction creation
        $automobiles = [];
        $bookings = [];
        $selectedAutomobile = null;
        $selectedBooking = null;

        if ($request->automobile_id) {
            $selectedAutomobile = Automobile::with('dealer')
                ->where('id', $request->automobile_id)
                ->whereIn('status', ['available', 'reserved'])
                ->first();
        }

        if ($request->booking_id) {
            $selectedBooking = Booking::with(['automobile.dealer', 'user'])
                ->where('id', $request->booking_id)
                ->where('status', 'confirmed')
                ->first();

            if ($selectedBooking && !$selectedAutomobile) {
                $selectedAutomobile = $selectedBooking->automobile;
            }
        }

        // Get user's automobiles for customers or all available for admins
        if (Auth::user()->isCustomer()) {
            $automobiles = Automobile::with('dealer')
                ->whereIn('status', ['available', 'reserved'])
                ->get(['id', 'make', 'model', 'year', 'price', 'dealer_id']);

            $bookings = Booking::with(['automobile.dealer'])
                ->where('user_id', Auth::id())
                ->where('status', 'confirmed')
                ->whereDoesntHave('transactions', function ($query) {
                    $query->whereIn('type', ['payment', 'deposit'])
                          ->whereIn('status', ['completed', 'processing']);
                })
                ->get(['id', 'booking_number', 'automobile_id', 'type']);
        }

        return Inertia::render('Transactions/Create', [
            'automobile' => $selectedAutomobile,
            'booking' => $selectedBooking,
            'availableAutomobiles' => $automobiles,
            'availableBookings' => $bookings
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Only customers and admins can create transactions
        if (!Auth::user()->isCustomer() && !Auth::user()->isAdmin()) {
            return redirect()->route('transactions.index')
                           ->with('error', 'You do not have permission to create transactions.');
        }

        $validated = $request->validate([
            'automobile_id' => 'required|exists:automobiles,id',
            'booking_id' => 'nullable|exists:bookings,id',
            'type' => ['required', Rule::in(['payment', 'deposit', 'refund'])],
            'amount' => 'required|numeric|min:1',
            'description' => 'nullable|string|max:1000',
            'payment_method' => ['required', Rule::in(['credit_card', 'bank_transfer', 'cash', 'check'])]
        ]);

        // Validate automobile availability
        $automobile = Automobile::where('id', $validated['automobile_id'])
                                ->whereIn('status', ['available', 'reserved'])
                                ->first();

        if (!$automobile) {
            return back()->withErrors([
                'automobile_id' => 'Selected automobile is not available for transactions.'
            ]);
        }

        // Validate booking if provided
        if ($validated['booking_id']) {
            $booking = Booking::where('id', $validated['booking_id'])
                             ->where('automobile_id', $validated['automobile_id'])
                             ->where('status', 'confirmed')
                             ->first();

            if (!$booking) {
                return back()->withErrors([
                    'booking_id' => 'Selected booking is not valid for this automobile.'
                ]);
            }

            // Check if customer owns the booking
            if (Auth::user()->isCustomer() && $booking->user_id !== Auth::id()) {
                return back()->withErrors([
                    'booking_id' => 'You can only create transactions for your own bookings.'
                ]);
            }
        }

        // Validate amount based on type and automobile price
        if ($validated['type'] === 'payment' && $validated['amount'] > $automobile->price) {
            return back()->withErrors([
                'amount' => 'Payment amount cannot exceed automobile price.'
            ]);
        }

        if ($validated['type'] === 'deposit' && $validated['amount'] > $automobile->price * 0.5) {
            return back()->withErrors([
                'amount' => 'Deposit amount cannot exceed 50% of automobile price.'
            ]);
        }

        // Generate unique transaction number
        do {
            $transactionNumber = 'TXN-' . date('Y') . '-' . strtoupper(Str::random(6));
        } while (Transaction::where('transaction_number', $transactionNumber)->exists());

        // Get the dealer's active bank account
        $dealerBankAccount = null;
        if ($automobile->dealer) {
            $dealerBankAccount = $automobile->dealer->bankAccounts()
                ->where('is_active', true)
                ->first();
        }

        $transaction = Transaction::create([
            'user_id' => Auth::id(),
            'automobile_id' => $validated['automobile_id'],
            'booking_id' => $validated['booking_id'],
            'transaction_number' => $transactionNumber,
            'type' => $validated['type'],
            'amount' => $validated['amount'],
            'status' => 'pending',
            'description' => $validated['description'],
            'payment_method' => $validated['payment_method'],
            'bank_account_id' => $dealerBankAccount ? $dealerBankAccount->id : null
        ]);

        // Update automobile status if full payment
        if ($validated['type'] === 'payment' && $validated['amount'] >= $automobile->price) {
            $automobile->update(['status' => 'sold']);
        } elseif ($validated['type'] === 'deposit') {
            $automobile->update(['status' => 'reserved']);
        }

        return redirect()->route('transactions.show', $transaction)
                         ->with('success', 'Transaction created successfully. Transaction number: ' . $transactionNumber);
    }

    /**
     * Display the specified resource.
     */
    public function show(Transaction $transaction)
    {
        // Check permissions
        $user = Auth::user();
        if ($user->isCustomer() && $transaction->user_id !== $user->id) {
            return redirect()->route('transactions.index')
                           ->with('error', 'You do not have permission to view this transaction.');
        }

        if ($user->isDealer() && $transaction->automobile->dealer_id !== $user->id) {
            return redirect()->route('transactions.index')
                           ->with('error', 'You do not have permission to view this transaction.');
        }

        $transaction->load(['user', 'automobile.dealer', 'booking']);

        $canManage = $user->isAdmin() ||
                    ($user->isDealer() && $transaction->automobile->dealer_id === $user->id);

        return Inertia::render('Transactions/Show', [
            'transaction' => $transaction,
            'canManage' => $canManage
        ]);
    }

    /**
     * Update transaction status (for dealers and admins)
     */
    public function updateStatus(Request $request, Transaction $transaction)
    {
        $user = Auth::user();

        // Only dealers (for their automobiles) and admins can update status
        if (!$user->isAdmin() &&
            !($user->isDealer() && $transaction->automobile->dealer_id === $user->id)) {
            return redirect()->route('transactions.show', $transaction)
                           ->with('error', 'You do not have permission to update this transaction.');
        }

        $validated = $request->validate([
            'status' => ['required', Rule::in(['pending', 'processing', 'completed', 'failed', 'refunded'])],
            'notes' => 'nullable|string|max:500'
        ]);

        $transaction->update([
            'status' => $validated['status'],
            'processed_at' => in_array($validated['status'], ['completed', 'failed', 'refunded']) ? now() : null
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
        } elseif ($validated['status'] === 'failed' || $validated['status'] === 'refunded') {
            // Reset automobile status to available if payment failed or refunded
            if (in_array($transaction->type, ['payment', 'deposit'])) {
                $transaction->automobile->update(['status' => 'available']);
            }
        }

        return redirect()->route('transactions.show', $transaction)
                         ->with('success', 'Transaction status updated successfully.');
    }

    /**
     * Generate invoice for completed transactions
     */
    public function generateInvoice(Transaction $transaction)
    {
        // Check permissions
        $user = Auth::user();
        if ($user->isCustomer() && $transaction->user_id !== $user->id) {
            return redirect()->route('transactions.index')
                           ->with('error', 'You do not have permission to access this invoice.');
        }

        if ($user->isDealer() && $transaction->automobile->dealer_id !== $user->id) {
            return redirect()->route('transactions.index')
                           ->with('error', 'You do not have permission to access this invoice.');
        }

        // Only generate invoices for completed transactions
        if ($transaction->status !== 'completed') {
            return redirect()->route('transactions.show', $transaction)
                           ->with('error', 'Invoices can only be generated for completed transactions.');
        }

        $transaction->load(['user', 'automobile.dealer', 'booking']);

        return Inertia::render('Transactions/Invoice', [
            'transaction' => $transaction
        ]);
    }
}
