<?php

namespace App\Http\Controllers;

use App\Models\Automobile;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Illuminate\Validation\Rule;

class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Booking::with(['user', 'automobile.dealer'])
            ->when($request->status, function ($query, $status) {
                $query->where('status', $status);
            })
            ->when($request->type, function ($query, $type) {
                $query->where('type', $type);
            })
            ->when($request->search, function ($query, $search) {
                $query->where(function ($query) use ($search) {
                    $query->whereHas('user', function ($query) use ($search) {
                        $query->where('name', 'like', "%{$search}%")
                              ->orWhere('email', 'like', "%{$search}%");
                    })->orWhereHas('automobile', function ($query) use ($search) {
                        $query->where('make', 'like', "%{$search}%")
                              ->orWhere('model', 'like', "%{$search}%")
                              ->orWhere('year', 'like', "%{$search}%");
                    })->orWhere('booking_number', 'like', "%{$search}%");
                });
            });

        // Role-based filtering
        $user = Auth::user();
        if ($user->isCustomer()) {
            // Customers can only see their own bookings
            $query->where('user_id', $user->id);
        } elseif ($user->isDealer()) {
            // Dealers can only see bookings for their automobiles
            $query->whereHas('automobile', function ($query) use ($user) {
                $query->where('dealer_id', $user->id);
            });
        }
        // Admins can see all bookings

        $bookings = $query->orderBy('scheduled_at', 'desc')
                          ->paginate(15);

        return Inertia::render('Bookings/Index', [
            'bookings' => $bookings,
            'filters' => $request->only(['search', 'status', 'type']),
            'statuses' => ['pending', 'confirmed', 'completed', 'cancelled'],
            'types' => ['test_drive', 'reservation', 'inspection']
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        // Only customers can create bookings
        if (!Auth::user()->isCustomer()) {
            return redirect()->route('bookings.index')
                           ->with('error', 'Only customers can create bookings.');
        }

        $automobile = null;
        if ($request->automobile_id) {
            $automobile = Automobile::with('dealer')
                                   ->where('id', $request->automobile_id)
                                   ->where('status', 'available')
                                   ->first();

            if (!$automobile) {
                return redirect()->route('automobiles.index')
                               ->with('error', 'Selected automobile is not available for booking.');
            }
        }

        // Get available automobiles for booking
        $availableAutomobiles = Automobile::with('dealer')
            ->where('status', 'available')
            ->orderBy('make')
            ->orderBy('model')
            ->get(['id', 'make', 'model', 'year', 'dealer_id']);

        return Inertia::render('Bookings/Create', [
            'automobile' => $automobile,
            'availableAutomobiles' => $availableAutomobiles
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Only customers can create bookings
        if (!Auth::user()->isCustomer()) {
            return redirect()->route('bookings.index')
                           ->with('error', 'Only customers can create bookings.');
        }

        $validated = $request->validate([
            'automobile_id' => 'required|exists:automobiles,id',
            'type' => ['required', Rule::in(['test_drive', 'reservation', 'inspection'])],
            'scheduled_at' => 'required|date|after:now',
            'preferred_time' => 'required|string',
            'notes' => 'nullable|string|max:1000'
        ]);

        // Check if automobile is available
        $automobile = Automobile::where('id', $validated['automobile_id'])
                                ->where('status', 'available')
                                ->first();

        if (!$automobile) {
            return back()->withErrors([
                'automobile_id' => 'Selected automobile is not available for booking.'
            ]);
        }

        // Check for conflicting bookings
        $conflictingBooking = Booking::where('automobile_id', $validated['automobile_id'])
            ->where('scheduled_at', $validated['scheduled_at'])
            ->whereIn('status', ['pending', 'confirmed'])
            ->exists();

        if ($conflictingBooking) {
            return back()->withErrors([
                'scheduled_at' => 'Another booking exists for this automobile on the selected date.'
            ]);
        }

        // Generate unique booking number
        do {
            $bookingNumber = 'BK-' . strtoupper(Str::random(8));
        } while (Booking::where('booking_number', $bookingNumber)->exists());

        $booking = Booking::create([
            'user_id' => Auth::id(),
            'automobile_id' => $validated['automobile_id'],
            'booking_number' => $bookingNumber,
            'type' => $validated['type'],
            'scheduled_at' => $validated['scheduled_at'],
            'preferred_time' => $validated['preferred_time'],
            'status' => 'pending',
            'notes' => $validated['notes']
        ]);

        return redirect()->route('bookings.show', $booking)
                         ->with('success', 'Booking created successfully. Booking number: ' . $bookingNumber)
                         ->with('showAlert', true);
    }

    /**
     * Display the specified resource.
     */
    public function show(Booking $booking)
    {
        // Check permissions
        $user = Auth::user();
        if ($user->isCustomer() && $booking->user_id !== $user->id) {
            return redirect()->route('bookings.index')
                           ->with('error', 'You do not have permission to view this booking.');
        }

        if ($user->isDealer() && $booking->automobile->dealer_id !== $user->id) {
            return redirect()->route('bookings.index')
                           ->with('error', 'You do not have permission to view this booking.');
        }

        $booking->load(['user', 'automobile.dealer']);

        $canManage = $user->isAdmin() ||
                    ($user->isDealer() && $booking->automobile->dealer_id === $user->id);

        return Inertia::render('Bookings/Show', [
            'booking' => $booking,
            'canManage' => $canManage
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Booking $booking)
    {
        // Check permissions - only customers can edit their own bookings
        if (!Auth::user()->isCustomer() || $booking->user_id !== Auth::id()) {
            return redirect()->route('bookings.show', $booking)
                           ->with('error', 'You do not have permission to edit this booking.');
        }

        // Can only edit pending bookings
        if ($booking->status !== 'pending') {
            return redirect()->route('bookings.show', $booking)
                           ->with('error', 'Only pending bookings can be edited.');
        }

        $booking->load('automobile');

        // Get available automobiles for rebooking
        $availableAutomobiles = Automobile::with('dealer')
            ->where('status', 'available')
            ->orderBy('make')
            ->orderBy('model')
            ->get(['id', 'make', 'model', 'year', 'dealer_id']);

        return Inertia::render('Bookings/Edit', [
            'booking' => $booking,
            'availableAutomobiles' => $availableAutomobiles
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Booking $booking)
    {
        // Check permissions - only customers can edit their own bookings
        if (!Auth::user()->isCustomer() || $booking->user_id !== Auth::id()) {
            return redirect()->route('bookings.show', $booking)
                           ->with('error', 'You do not have permission to edit this booking.');
        }

        // Can only edit pending bookings
        if ($booking->status !== 'pending') {
            return redirect()->route('bookings.show', $booking)
                           ->with('error', 'Only pending bookings can be edited.');
        }

        $validated = $request->validate([
            'automobile_id' => 'required|exists:automobiles,id',
            'type' => ['required', Rule::in(['test_drive', 'reservation', 'inspection'])],
            'scheduled_at' => 'required|date|after:now',
            'preferred_time' => 'required|string',
            'notes' => 'nullable|string|max:1000'
        ]);

        // Check if automobile is available
        $automobile = Automobile::where('id', $validated['automobile_id'])
                                ->where('status', 'available')
                                ->first();

        if (!$automobile) {
            return back()->withErrors([
                'automobile_id' => 'Selected automobile is not available for booking.'
            ]);
        }

        // Check for conflicting bookings (excluding current booking)
        $conflictingBooking = Booking::where('automobile_id', $validated['automobile_id'])
            ->where('scheduled_at', $validated['scheduled_at'])
            ->where('id', '!=', $booking->id)
            ->whereIn('status', ['pending', 'confirmed'])
            ->exists();

        if ($conflictingBooking) {
            return back()->withErrors([
                'scheduled_at' => 'Another booking exists for this automobile on the selected date.'
            ]);
        }

        $booking->update($validated);

        return redirect()->route('bookings.show', $booking)
                         ->with('success', 'Booking updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Booking $booking)
    {
        // Check permissions
        $user = Auth::user();
        $canDelete = $user->isAdmin() ||
                    ($user->isCustomer() && $booking->user_id === $user->id) ||
                    ($user->isDealer() && $booking->automobile->dealer_id === $user->id);

        if (!$canDelete) {
            return redirect()->route('bookings.index')
                           ->with('error', 'You do not have permission to delete this booking.');
        }

        // Can only delete pending or cancelled bookings
        if (!in_array($booking->status, ['pending', 'cancelled'])) {
            return redirect()->route('bookings.show', $booking)
                           ->with('error', 'Only pending or cancelled bookings can be deleted.');
        }

        $booking->delete();

        return redirect()->route('bookings.index')
                         ->with('success', 'Booking deleted successfully.');
    }

    /**
     * Update booking status (for dealers and admins)
     */
    public function updateStatus(Request $request, Booking $booking)
    {
        $user = Auth::user();

        // Check permissions - only dealers (for their cars) and admins can update status
        if (!$user->isAdmin() &&
            (!$user->isDealer() || $booking->automobile->dealer_id !== $user->id)) {
            return redirect()->route('bookings.show', $booking)
                           ->with('error', 'You do not have permission to update this booking status.');
        }

        $validated = $request->validate([
            'status' => ['required', Rule::in(['pending', 'confirmed', 'completed', 'cancelled'])],
            'notes' => 'nullable|string|max:1000'
        ]);

        $booking->update([
            'status' => $validated['status'],
            'notes' => $validated['notes'] ?? $booking->notes
        ]);

        return redirect()->route('bookings.show', $booking)
                         ->with('success', 'Booking status updated successfully.');
    }
}
