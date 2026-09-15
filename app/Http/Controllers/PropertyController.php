<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\Amenity;
use App\Models\Property;
use App\Models\PropertyCategory;
use App\Models\PropertyType;
use App\Models\PropertyMedia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PropertyController extends Controller
{
    public function index(Request $request)
    {
        $query = Property::query()->where('status', 'published');

        // Search text
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('address', 'like', "%{$search}%");
            });
        }

        // Purpose (buy/rent)
        if ($request->filled('purpose')) {
            $query->where('purpose', $request->input('purpose'));
        }

        // Category (residential/commercial)
        if ($request->filled('category')) {
            $categorySlug = $request->input('category');
            $query->whereHas('category', function ($q) use ($categorySlug) {
                $q->where('slug', $categorySlug);
            });
        }

        // Locality / Area
        if ($request->filled('area_id')) {
            $query->where('area_id', $request->input('area_id'));
        }

        // Property Type
        if ($request->filled('type_id')) {
            $query->where('property_type_id', $request->input('type_id'));
        }

        // BHK (Bedrooms)
        if ($request->filled('bhk')) {
            $bhk = $request->input('bhk');
            if ($bhk === '4+') {
                $query->where('bedrooms', '>=', 4);
            } else {
                $query->where('bedrooms', $bhk);
            }
        }

        // Furnishing
        if ($request->filled('furnishing')) {
            $query->where('furnishing', $request->input('furnishing'));
        }

        // Budget / Price
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->input('min_price'));
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->input('max_price'));
        }

        // Sorting
        $sort = $request->input('sort', 'newest');
        switch ($sort) {
            case 'price_low':
                $query->orderBy('price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('price', 'desc');
                break;
            case 'popular':
                $query->orderBy('views_count', 'desc');
                break;
            case 'newest':
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        $properties = $query->with(['area', 'category', 'type', 'primaryImage'])->paginate(9)->withQueryString();

        // Get filter options for UI
        $areas = Area::where('is_active', true)->orderBy('name')->get();
        $categories = PropertyCategory::where('is_active', true)->with('types')->get();

        return view('properties.index', compact('properties', 'areas', 'categories'));
    }

    public function show($slug)
    {
        $property = Property::where('slug', $slug)
            ->with(['user', 'category', 'type', 'area.city', 'amenities', 'media'])
            ->firstOrFail();

        // Check view permission if not published
        if ($property->status !== 'published') {
            if (!Auth::check() || (!Auth::user()->isAdmin() && Auth::id() !== $property->user_id)) {
                abort(403, 'Unauthorized access to unpublished property.');
            }
        }

        // Increment views count
        $property->increment('views_count');

        // Similar properties in same area or same type
        $similarProperties = Property::where('status', 'published')
            ->where('id', '!=', $property->id)
            ->where(function ($q) use ($property) {
                $q->where('area_id', $property->area_id)
                  ->orWhere('property_type_id', $property->property_type_id);
            })
            ->with(['area', 'category', 'type', 'primaryImage'])
            ->limit(3)
            ->get();

        return view('properties.show', compact('property', 'similarProperties'));
    }

    public function create()
    {
        $this->authorize('create', Property::class);

        $areas = Area::where('is_active', true)->orderBy('name')->get();
        $categories = PropertyCategory::where('is_active', true)->with('types')->get();
        $amenities = Amenity::all();

        return view('properties.create', compact('areas', 'categories', 'amenities'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', Property::class);

        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'purpose' => ['required', 'in:sell,rent'],
            'category_id' => ['required', 'exists:property_categories,id'],
            'property_type_id' => ['required', 'exists:property_types,id'],
            'area_id' => ['required', 'exists:areas,id'],
            'price' => ['required', 'numeric', 'min:0'],
            'price_type' => ['required', 'in:fixed,negotiable,contact_for_price'],
            'property_area' => ['required', 'numeric', 'min:0'],
            'area_unit' => ['required', 'in:sq_ft,sq_yd,marla,acre'],
            'bedrooms' => ['nullable', 'integer', 'min:0'],
            'bathrooms' => ['nullable', 'integer', 'min:0'],
            'balconies' => ['nullable', 'integer', 'min:0'],
            'floor_number' => ['nullable', 'integer'],
            'total_floors' => ['nullable', 'integer', 'min:0'],
            'parking' => ['required', 'in:none,car,bike,both'],
            'facing' => ['nullable', 'string'],
            'furnishing' => ['required', 'in:unfurnished,semi-furnished,fully-furnished'],
            'property_age' => ['nullable', 'integer', 'min:0'],
            'address' => ['required', 'string'],
            'pincode' => ['nullable', 'string', 'max:10'],
            'google_map_location' => ['nullable', 'string'],
            'amenities' => ['nullable', 'array'],
            'amenities.*' => ['exists:amenities,id'],
            'primary_image' => ['nullable', 'image', 'max:2048'],
            'images' => ['nullable', 'array', 'max:5'],
            'images.*' => ['image', 'max:2048'],
        ]);

        $data = $request->except(['amenities', 'primary_image', 'images']);
        $data['user_id'] = Auth::id();
        
        // Listings require admin review by default
        $data['status'] = Auth::user()->isAdmin() ? 'published' : 'pending_review';

        $property = Property::create($data);

        // Sync amenities
        if ($request->has('amenities')) {
            $property->amenities()->sync($request->amenities);
        }

        // Handle Primary Image Upload
        if ($request->hasFile('primary_image')) {
            $path = $request->file('primary_image')->store('properties', 'public');
            $property->media()->create([
                'file_path' => $path,
                'file_type' => 'image',
                'is_primary' => true,
                'sort_order' => 0,
            ]);
        }

        // Handle Multiple Images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $imageFile) {
                $path = $imageFile->store('properties', 'public');
                $property->media()->create([
                    'file_path' => $path,
                    'file_type' => 'image',
                    'is_primary' => false,
                    'sort_order' => $index + 1,
                ]);
            }
        }

        return redirect()->route('dashboard')
            ->with('status', 'Property listing created successfully. ' . (Auth::user()->isAdmin() ? 'It is published.' : 'It will appear on the site once approved by admin.'));
    }

    public function edit(Property $property)
    {
        $this->authorize('update', $property);

        $areas = Area::where('is_active', true)->orderBy('name')->get();
        $categories = PropertyCategory::where('is_active', true)->with('types')->get();
        $amenities = Amenity::all();
        $propertyAmenities = $property->amenities->pluck('id')->toArray();

        return view('properties.edit', compact('property', 'areas', 'categories', 'amenities', 'propertyAmenities'));
    }

    public function update(Request $request, Property $property)
    {
        $this->authorize('update', $property);

        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'purpose' => ['required', 'in:sell,rent'],
            'category_id' => ['required', 'exists:property_categories,id'],
            'property_type_id' => ['required', 'exists:property_types,id'],
            'area_id' => ['required', 'exists:areas,id'],
            'price' => ['required', 'numeric', 'min:0'],
            'price_type' => ['required', 'in:fixed,negotiable,contact_for_price'],
            'property_area' => ['required', 'numeric', 'min:0'],
            'area_unit' => ['required', 'in:sq_ft,sq_yd,marla,acre'],
            'bedrooms' => ['nullable', 'integer', 'min:0'],
            'bathrooms' => ['nullable', 'integer', 'min:0'],
            'balconies' => ['nullable', 'integer', 'min:0'],
            'floor_number' => ['nullable', 'integer'],
            'total_floors' => ['nullable', 'integer', 'min:0'],
            'parking' => ['required', 'in:none,car,bike,both'],
            'facing' => ['nullable', 'string'],
            'furnishing' => ['required', 'in:unfurnished,semi-furnished,fully-furnished'],
            'property_age' => ['nullable', 'integer', 'min:0'],
            'address' => ['required', 'string'],
            'pincode' => ['nullable', 'string', 'max:10'],
            'google_map_location' => ['nullable', 'string'],
            'amenities' => ['nullable', 'array'],
            'amenities.*' => ['exists:amenities,id'],
            'primary_image' => ['nullable', 'image', 'max:2048'],
            'images' => ['nullable', 'array', 'max:5'],
            'images.*' => ['image', 'max:2048'],
        ]);

        $data = $request->except(['amenities', 'primary_image', 'images']);
        
        // Reset status to review if owner/agent edits it
        if (!Auth::user()->isAdmin()) {
            $data['status'] = 'pending_review';
        }

        $property->update($data);

        // Sync amenities
        if ($request->has('amenities')) {
            $property->amenities()->sync($request->amenities);
        } else {
            $property->amenities()->detach();
        }

        // Handle Primary Image Update
        if ($request->hasFile('primary_image')) {
            // Delete old primary
            $oldPrimary = $property->media()->where('is_primary', true)->first();
            if ($oldPrimary) {
                Storage::disk('public')->delete($oldPrimary->file_path);
                $oldPrimary->delete();
            }

            $path = $request->file('primary_image')->store('properties', 'public');
            $property->media()->create([
                'file_path' => $path,
                'file_type' => 'image',
                'is_primary' => true,
                'sort_order' => 0,
            ]);
        }

        // Handle Multiple Images addition
        if ($request->hasFile('images')) {
            $maxSort = $property->media()->max('sort_order') ?? 0;
            foreach ($request->file('images') as $index => $imageFile) {
                $path = $imageFile->store('properties', 'public');
                $property->media()->create([
                    'file_path' => $path,
                    'file_type' => 'image',
                    'is_primary' => false,
                    'sort_order' => $maxSort + $index + 1,
                ]);
            }
        }

        return redirect()->route('dashboard')
            ->with('status', 'Property updated successfully. ' . (Auth::user()->isAdmin() ? '' : 'Pending approval again.'));
    }

    public function destroy(Property $property)
    {
        $this->authorize('delete', $property);

        // Delete associated files
        foreach ($property->media as $media) {
            Storage::disk('public')->delete($media->file_path);
        }

        $property->delete();

        return redirect()->route('dashboard')
            ->with('status', 'Property listing deleted successfully.');
    }

    public function toggleSaved(Request $request, Property $property)
    {
        if (!Auth::check()) {
            return response()->json(['success' => false, 'message' => 'Please login to save properties.'], 401);
        }

        $user = Auth::user();
        $saved = $user->savedProperties()->where('property_id', $property->id)->first();

        if ($saved) {
            $saved->delete();
            $status = 'removed';
            $msg = 'Property removed from wishlist.';
        } else {
            $user->savedProperties()->create(['property_id' => $property->id]);
            $status = 'saved';
            $msg = 'Property saved to wishlist.';
        }

        if ($request->ajax()) {
            return response()->json(['success' => true, 'status' => $status, 'message' => $msg]);
        }

        return back()->with('status', $msg);
    }
}
