<?php

namespace App\Http\Controllers;

use App\Models\BankAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class BankAccountController extends Controller
{
    // Available payment methods for global usage
    private const PAYMENT_METHODS = [
        'bank_transfer' => 'Bank Transfer',
        'paypal' => 'PayPal',
        'stripe' => 'Stripe',
        'wire_transfer' => 'Wire Transfer',
        'ach' => 'ACH Transfer',
        'sepa' => 'SEPA Transfer',
        'swift' => 'SWIFT Transfer'
    ];

    // Supported currencies
    private const CURRENCIES = [
        'USD' => 'US Dollar',
        'EUR' => 'Euro',
        'GBP' => 'British Pound',
        'JPY' => 'Japanese Yen',
        'CAD' => 'Canadian Dollar',
        'AUD' => 'Australian Dollar',
        'CHF' => 'Swiss Franc',
        'CNY' => 'Chinese Yuan',
        'INR' => 'Indian Rupee',
        'BRL' => 'Brazilian Real',
        'MXN' => 'Mexican Peso',
        'NGN' => 'Nigerian Naira',
        'RUB' => 'Russian Ruble',
        'ZAR' => 'South African Rand',
        'TRY' => 'Turkish Lira',
        'THB' => 'Thai Baht',
        'AED' => 'United Arab Emirates Dirham',
        'SGD' => 'Singapore Dollar',
        'HKD' => 'Hong Kong Dollar',
        'NOK' => 'Norwegian Krone',
        'SEK' => 'Swedish Krona',
        'DKK' => 'Danish Krone',
        'PLN' => 'Polish Zloty',
        'ILS' => 'Israeli Shekel',
        'ARS' => 'Argentine Peso',
        'COP' => 'Colombian Peso',
        'CLP' =>'Chilean Peso',
        'PEN' => 'Peruvian Nuevo Sol',
        'KES' => 'Kenyan Shilling',
        'UGX' => 'Ugandan Shilling',
        'GHS' => 'Ghanaian Cedi',
        'ZMW' => 'Zambian Kwacha',
        'TZS' => 'Tanzanian Shilling',

    ];

    /**
     * Display a listing of the bank accounts.
     */
    public function index()
    {
        $bankAccounts = Auth::user()->bankAccounts()->get();

        return Inertia::render('BankAccounts/Index', [
            'bankAccounts' => $bankAccounts,
        ])->with('paymentMethods', self::PAYMENT_METHODS)
          ->with('currencies', self::CURRENCIES);
    }

    /**
     * Show the form for creating a new bank account.
     */
    public function create()
    {
        return Inertia::render('BankAccounts/Create')
            ->with('paymentMethods', self::PAYMENT_METHODS)
            ->with('currencies', self::CURRENCIES);
    }

    /**
     * Store a newly created bank account in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'account_name' => 'required|string|max:255',
            'account_number' => 'required|string|max:255',
            'bank_name' => 'required|string|max:255',
            'routing_number' => 'nullable|string|max:50',
            'account_type' => 'required|in:checking,savings',
            'payment_method' => 'required|in:' . implode(',', array_keys(self::PAYMENT_METHODS)),
            'currency' => 'required|in:' . implode(',', array_keys(self::CURRENCIES)),
            'country_code' => 'required|string|size:2',
            'payment_details' => 'nullable|array'
        ]);

        Auth::user()->bankAccounts()->create($validated);

        return redirect()->route('bank-accounts.index')->with('success', 'Payment account added successfully.');
    }

    /**
     * Show the form for editing the specified bank account.
     */
    public function edit(BankAccount $bankAccount)
    {
        // Ensure the user owns this bank account
        if ($bankAccount->user_id !== Auth::id()) {
            abort(403);
        }

        return Inertia::render('BankAccounts/Edit', [
            'bankAccount' => $bankAccount,
        ])->with('paymentMethods', self::PAYMENT_METHODS)
          ->with('currencies', self::CURRENCIES);
    }

    /**
     * Update the specified bank account in storage.
     */
    public function update(Request $request, BankAccount $bankAccount)
    {
        // Ensure the user owns this bank account
        if ($bankAccount->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'account_name' => 'required|string|max:255',
            'account_number' => 'required|string|max:255',
            'bank_name' => 'required|string|max:255',
            'routing_number' => 'nullable|string|max:50',
            'account_type' => 'required|in:checking,savings',
            'payment_method' => 'required|in:' . implode(',', array_keys(self::PAYMENT_METHODS)),
            'currency' => 'required|in:' . implode(',', array_keys(self::CURRENCIES)),
            'country_code' => 'required|string|size:2',
            'payment_details' => 'nullable|array',
            'is_active' => 'boolean',
        ]);

        $bankAccount->update($validated);

        return redirect()->route('bank-accounts.index')->with('success', 'Payment account updated successfully.');
    }

    /**
     * Remove the specified bank account from storage.
     */
    public function destroy(BankAccount $bankAccount)
    {
        // Ensure the user owns this bank account
        if ($bankAccount->user_id !== Auth::id()) {
            abort(403);
        }

        $bankAccount->delete();

        return redirect()->route('bank-accounts.index')->with('success', 'Payment account deleted successfully.');
    }
}
