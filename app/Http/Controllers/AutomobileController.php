<?php

namespace App\Http\Controllers;

use App\Models\Automobile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Illuminate\Validation\Rule;

class AutomobileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Automobile::with('dealer')
            ->when($request->search, function ($query, $search) {
                $query->where(function ($query) use ($search) {
                    $query->where('make', 'like', "%{$search}%")
                          ->orWhere('model', 'like', "%{$search}%")
                          ->orWhere('year', 'like', "%{$search}%")
                          ->orWhere('vin', 'like', "%{$search}%")
                          ->orWhere('color', 'like', "%{$search}%")
                          ->orWhere('description', 'like', "%{$search}%");
                });
            })
            ->when($request->status, function ($query, $status) {
                $query->where('status', $status);
            })
            ->when($request->make, function ($query, $make) {
                $query->where('make', $make);
            })
            ->when($request->body_type, function ($query, $bodyType) {
                $query->where('body_type', $bodyType);
            })
            ->when($request->fuel_type, function ($query, $fuelType) {
                $query->where('fuel_type', $fuelType);
            })
            ->when($request->transmission, function ($query, $transmission) {
                $query->where('transmission', $transmission);
            })
            ->when($request->color, function ($query, $color) {
                $query->where('color', $color);
            })
            ->when($request->min_price, function ($query, $minPrice) {
                $query->where('price', '>=', $minPrice);
            })
            ->when($request->max_price, function ($query, $maxPrice) {
                $query->where('price', '<=', $maxPrice);
            })
            ->when($request->min_year, function ($query, $minYear) {
                $query->where('year', '>=', $minYear);
            })
            ->when($request->max_year, function ($query, $maxYear) {
                $query->where('year', '<=', $maxYear);
            })
            ->when($request->min_mileage, function ($query, $minMileage) {
                $query->where('mileage', '>=', $minMileage);
            })
            ->when($request->max_mileage, function ($query, $maxMileage) {
                $query->where('mileage', '<=', $maxMileage);
            });

        // If user is a dealer, only show their automobiles
        if (Auth::user()->isDealer()) {
            $query->where('dealer_id', Auth::id());
            // Debug: Log which dealer is viewing and what they should see
            \Log::info('Dealer viewing automobiles', [
                'dealer_id' => Auth::id(),
                'dealer_name' => Auth::user()->name,
                'expected_automobiles' => Automobile::where('dealer_id', Auth::id())->pluck('id')->toArray()
            ]);
        }

        // Handle sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortDirection = $request->get('sort_direction', 'desc');

        $validSorts = ['created_at', 'price', 'year', 'mileage', 'make', 'model'];
        if (in_array($sortBy, $validSorts)) {
            $query->orderBy($sortBy, in_array($sortDirection, ['asc', 'desc']) ? $sortDirection : 'desc');
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $automobiles = $query->paginate(12)->withQueryString();

        return Inertia::render('Automobiles/Index', [
            'automobiles' => $automobiles,
            'filters' => $request->only([
                'search', 'status', 'make', 'body_type', 'fuel_type', 'transmission', 'color',
                'min_price', 'max_price', 'min_year', 'max_year', 'min_mileage', 'max_mileage',
                'sort_by', 'sort_direction'
            ]),
            'filterOptions' => [
                'makes' => Automobile::distinct()->pluck('make')->filter()->sort()->values(),
                'body_types' => Automobile::distinct()->pluck('body_type')->filter()->sort()->values(),
                'fuel_types' => Automobile::distinct()->pluck('fuel_type')->filter()->sort()->values(),
                'transmissions' => Automobile::distinct()->pluck('transmission')->filter()->sort()->values(),
                'colors' => Automobile::distinct()->pluck('color')->filter()->sort()->values(),
                'statuses' => ['available', 'sold', 'reserved', 'maintenance'],
                'year_range' => [
                    'min' => Automobile::min('year') ?: date('Y') - 20,
                    'max' => Automobile::max('year') ?: date('Y')
                ],
                'price_range' => [
                    'min' => Automobile::min('price') ?: 0,
                    'max' => Automobile::max('price') ?: 100000
                ],
                'mileage_range' => [
                    'min' => Automobile::min('mileage') ?: 0,
                    'max' => Automobile::max('mileage') ?: 200000
                ]
            ],
            'totalResults' => $automobiles->total()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Only dealers and admins can create automobiles
        if (!Auth::user()->isDealer() && !Auth::user()->isAdmin()) {
            return redirect()->route('automobiles.index')
                           ->with('error', 'You do not have permission to create automobiles.');
        }

        return Inertia::render('Automobiles/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Only dealers and admins can create automobiles
        if (!Auth::user()->isDealer() && !Auth::user()->isAdmin()) {
            return redirect()->route('automobiles.index')
                           ->with('error', 'You do not have permission to create automobiles.');
        }

        $validated = $request->validate([
            'make' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'year' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'vin' => 'required|string|max:17|unique:automobiles,vin',
            'price' => 'required|numeric|min:0',
            'mileage' => 'required|integer|min:0',
            'color' => 'required|string|max:50',
            'body_type' => 'required|string|max:50',
            'fuel_type' => 'required|string|max:50',
            'transmission' => 'required|string|max:50',
            'engine_size' => 'nullable|string|max:50',
            'description' => 'nullable|string',
            'status' => ['required', Rule::in(['available', 'sold', 'reserved', 'maintenance'])],
            'images' => 'nullable|array|max:10',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        // Handle image uploads
        $imagePaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('automobiles', 'public');
                $imagePaths[] = $path;
            }
        }

        $validated['images'] = $imagePaths;
        $validated['dealer_id'] = Auth::user()->isAdmin() && $request->dealer_id ? $request->dealer_id : Auth::id();

        $automobile = Automobile::create($validated);

        return redirect()->route('automobiles.show', $automobile)
                         ->with('success', 'Automobile created successfully.')
                         ->with('showAlert', true);
    }

    /**
     * Display the specified resource for public viewing (unauthenticated users).
     */
    public function publicShow(Automobile $automobile)
    {
        // Show available and sold automobiles to unauthenticated users
        // Sold automobiles will show as sold but still display details
        if (!in_array($automobile->status, ['available', 'sold'])) {
            abort(404);
        }

        $automobile->load('dealer');

        return Inertia::render('Automobiles/PublicShow', [
            'automobile' => $automobile
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Automobile $automobile)
    {
        $automobile->load('dealer', 'bookings.user', 'transactions');

        return Inertia::render('Automobiles/Show', [
            'automobile' => $automobile,
            'canEdit' => Auth::user()->isAdmin() ||
                        (Auth::user()->isDealer() && $automobile->dealer_id === Auth::id())
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Automobile $automobile)
    {
        // Check permissions
        if (!Auth::user()->isAdmin() &&
            (!Auth::user()->isDealer() || $automobile->dealer_id !== Auth::id())) {
            return redirect()->route('automobiles.show', $automobile)
                           ->with('error', 'You do not have permission to edit this automobile.');
        }

        return Inertia::render('Automobiles/Edit', [
            'automobile' => $automobile
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Automobile $automobile)
    {
        // Check permissions
        if (!Auth::user()->isAdmin() &&
            (!Auth::user()->isDealer() || $automobile->dealer_id !== Auth::id())) {
            return redirect()->route('automobiles.show', $automobile)
                           ->with('error', 'You do not have permission to edit this automobile.');
        }

        $validated = $request->validate([
            'make' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'year' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'vin' => 'required|string|max:17|unique:automobiles,vin,' . $automobile->id,
            'price' => 'required|numeric|min:0',
            'mileage' => 'required|integer|min:0',
            'color' => 'required|string|max:50',
            'body_type' => 'required|string|max:50',
            'fuel_type' => 'required|string|max:50',
            'transmission' => 'required|string|max:50',
            'engine_size' => 'nullable|string|max:50',
            'description' => 'nullable|string',
            'status' => ['required', Rule::in(['available', 'sold', 'reserved', 'maintenance'])],
            'images' => 'nullable|array|max:10',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'remove_images' => 'nullable|array',
            'remove_images.*' => 'string'
        ]);

        // Handle image removal
        $currentImages = $automobile->images ?? [];
        if ($request->has('remove_images')) {
            foreach ($request->remove_images as $imageToRemove) {
                if (($key = array_search($imageToRemove, $currentImages)) !== false) {
                    Storage::disk('public')->delete($imageToRemove);
                    unset($currentImages[$key]);
                }
            }
            $currentImages = array_values($currentImages);
        }

        // Handle new image uploads
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('automobiles', 'public');
                $currentImages[] = $path;
            }
        }

        $validated['images'] = $currentImages;

        // Only admins can change dealer
        if (Auth::user()->isAdmin() && $request->dealer_id) {
            $validated['dealer_id'] = $request->dealer_id;
        }

        $automobile->update($validated);

        return redirect()->route('automobiles.show', $automobile)
                         ->with('success', 'Automobile updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Automobile $automobile)
    {
        // Check permissions
        if (!Auth::user()->isAdmin() &&
            (!Auth::user()->isDealer() || $automobile->dealer_id !== Auth::id())) {
            return redirect()->route('automobiles.index')
                           ->with('error', 'You do not have permission to delete this automobile.');
        }

        // Delete associated images
        if ($automobile->images) {
            foreach ($automobile->images as $image) {
                Storage::disk('public')->delete($image);
            }
        }

        $automobile->delete();

        return redirect()->route('automobiles.index')
                         ->with('success', 'Automobile deleted successfully.');
    }
}
