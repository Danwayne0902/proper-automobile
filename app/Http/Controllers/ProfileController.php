<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): Response
    {
        $user = $request->user();

        // For customers, show dashboard with stats
        if ($user->isCustomer()) {
            $bookingsCount = $user->bookings()->count();
            $transactionsCount = $user->transactions()->count();
            $pendingCount = $user->bookings()->where('status', 'pending')->count() +
                           $user->transactions()->where('status', 'pending')->count();

            $recentBookings = $user->bookings()
                ->with('automobile')
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();

            $recentTransactions = $user->transactions()
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();

            return Inertia::render('Profile/Dashboard', [
                'user' => $user,
                'bookingsCount' => $bookingsCount,
                'transactionsCount' => $transactionsCount,
                'pendingCount' => $pendingCount,
                'recentBookings' => $recentBookings,
                'recentTransactions' => $recentTransactions,
                'mustVerifyEmail' => $user instanceof MustVerifyEmail,
                'status' => session('status'),
            ]);
        }

        // For admins and dealers, show the regular profile edit form
        // Pass user data explicitly to ensure it's available
        return Inertia::render('Profile/Edit', [
            'user' => $user,
            'mustVerifyEmail' => $user instanceof MustVerifyEmail,
            'status' => session('status'),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
