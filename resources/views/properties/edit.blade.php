@extends('layouts.public')

@section('title', 'Edit Property | Patiala Property')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
        <h1 class="text-2xl font-black text-gray-900 border-b pb-4 mb-6">Edit Property Listing</h1>

        <form action="{{ route('properties.update', $property->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Step 1: Purpose & Classification -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Purpose</label>
                    <select name="purpose" class="w-full rounded-lg border-gray-200" required>
                        <option value="sell" {{ $property->purpose === 'sell' ? 'selected' : '' }}>Sell Property</option>
                        <option value="rent" {{ $property->purpose === 'rent' ? 'selected' : '' }}>Rent Property</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Category</label>
                    <select name="category_id" id="category_id" class="w-full rounded-lg border-gray-200" required onchange="toggleFields()">
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" data-slug="{{ $cat->slug }}" {{ $property->category_id == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Property Type</label>
                    <select name="property_type_id" id="property_type_id" class="w-full rounded-lg border-gray-200" required onchange="toggleFields()">
                        @foreach($categories as $cat)
                            @foreach($cat->types as $type)
                                <option value="{{ $type->id }}" data-category-id="{{ $cat->id }}" data-slug="{{ $type->slug }}" {{ $property->property_type_id == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                            @endforeach
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Step 2: Basic Info -->
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Property Title</label>
                    <input type="text" name="title" value="{{ old('title', $property->title) }}" placeholder="e.g. Luxurious 3 BHK House in Urban Estate" class="w-full rounded-lg border-gray-200" required>
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Description</label>
                    <textarea name="description" rows="4" placeholder="Detail the specs, location highlights..." class="w-full rounded-lg border-gray-200" required>{{ old('description', $property->description) }}</textarea>
                </div>
            </div>

            <!-- Step 3: Location Details -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Locality / Area in Patiala</label>
                    <select name="area_id" class="w-full rounded-lg border-gray-200" required>
                        <option value="">Select Locality</option>
                        @foreach($areas as $area)
                            <option value="{{ $area->id }}" {{ $property->area_id == $area->id ? 'selected' : '' }}>{{ $area->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-bold text-gray-700 mb-2">Address</label>
                    <input type="text" name="address" value="{{ old('address', $property->address) }}" class="w-full rounded-lg border-gray-200" required>
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Pincode</label>
                    <input type="text" name="pincode" value="{{ old('pincode', $property->pincode) }}" placeholder="14700X" class="w-full rounded-lg border-gray-200">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Google Map Location URL (Optional)</label>
                    <input type="text" name="google_map_location" value="{{ old('google_map_location', $property->google_map_location) }}" class="w-full rounded-lg border-gray-200">
                </div>
            </div>

            <!-- Step 4: Pricing & Area Size -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Price (₹)</label>
                    <input type="number" name="price" value="{{ old('price', (int)$property->price) }}" class="w-full rounded-lg border-gray-200" required>
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Price Type</label>
                    <select name="price_type" class="w-full rounded-lg border-gray-200" required>
                        <option value="fixed" {{ $property->price_type === 'fixed' ? 'selected' : '' }}>Fixed Price</option>
                        <option value="negotiable" {{ $property->price_type === 'negotiable' ? 'selected' : '' }}>Negotiable</option>
                        <option value="contact_for_price" {{ $property->price_type === 'contact_for_price' ? 'selected' : '' }}>Contact for Price</option>
                    </select>
                </div>
                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Area Size</label>
                        <input type="number" step="0.01" name="property_area" value="{{ old('property_area', $property->property_area) }}" class="w-full rounded-lg border-gray-200" required>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Unit</label>
                        <select name="area_unit" class="w-full rounded-lg border-gray-200" required>
                            <option value="sq_ft" {{ $property->area_unit === 'sq_ft' ? 'selected' : '' }}>Sq. Ft.</option>
                            <option value="sq_yd" {{ $property->area_unit === 'sq_yd' ? 'selected' : '' }}>Sq. Yd.</option>
                            <option value="marla" {{ $property->area_unit === 'marla' ? 'selected' : '' }}>Marla</option>
                            <option value="acre" {{ $property->area_unit === 'acre' ? 'selected' : '' }}>Acre</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Step 5: Rooms & Details -->
            <div id="rooms-details-section" class="border-t pt-6 space-y-6">
                <h3 class="text-base font-bold text-gray-900 mb-2">Rooms & Specs</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Bedrooms (BHK)</label>
                        <input type="number" name="bedrooms" value="{{ old('bedrooms', $property->bedrooms) }}" class="w-full rounded-lg border-gray-200">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Bathrooms</label>
                        <input type="number" name="bathrooms" value="{{ old('bathrooms', $property->bathrooms) }}" class="w-full rounded-lg border-gray-200">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Balconies</label>
                        <input type="number" name="balconies" value="{{ old('balconies', $property->balconies) }}" class="w-full rounded-lg border-gray-200">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Furnishing</label>
                        <select name="furnishing" class="w-full rounded-lg border-gray-200">
                            <option value="unfurnished" {{ $property->furnishing === 'unfurnished' ? 'selected' : '' }}>Unfurnished</option>
                            <option value="semi-furnished" {{ $property->furnishing === 'semi-furnished' ? 'selected' : '' }}>Semi-Furnished</option>
                            <option value="fully-furnished" {{ $property->furnishing === 'fully-furnished' ? 'selected' : '' }}>Fully Furnished</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Floor Number</label>
                        <input type="number" name="floor_number" value="{{ old('floor_number', $property->floor_number) }}" class="w-full rounded-lg border-gray-200">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Total Floors</label>
                        <input type="number" name="total_floors" value="{{ old('total_floors', $property->total_floors) }}" class="w-full rounded-lg border-gray-200">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Parking</label>
                        <select name="parking" class="w-full rounded-lg border-gray-200">
                            <option value="none" {{ $property->parking === 'none' ? 'selected' : '' }}>None</option>
                            <option value="car" {{ $property->parking === 'car' ? 'selected' : '' }}>Car Only</option>
                            <option value="bike" {{ $property->parking === 'bike' ? 'selected' : '' }}>Bike Only</option>
                            <option value="both" {{ $property->parking === 'both' ? 'selected' : '' }}>Both Car & Bike</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Facing Direction</label>
                        <input type="text" name="facing" value="{{ old('facing', $property->facing) }}" class="w-full rounded-lg border-gray-200">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Property Age (Years)</label>
                        <input type="number" name="property_age" value="{{ old('property_age', $property->property_age) }}" class="w-full rounded-lg border-gray-200">
                    </div>
                </div>
            </div>

            <!-- Step 6: Amenities Checkboxes -->
            <div class="border-t pt-6">
                <label class="block text-sm font-bold text-gray-700 mb-4">Amenities Available</label>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                    @foreach($amenities as $amenity)
                        <label class="flex items-center space-x-2 text-sm text-gray-700 cursor-pointer">
                            <input type="checkbox" name="amenities[]" value="{{ $amenity->id }}" {{ in_array($amenity->id, $propertyAmenities) ? 'checked' : '' }} class="rounded text-red-600 focus:ring-red-500 border-gray-300">
                            <span>{{ $amenity->name }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <!-- Step 7: Media Uploads -->
            <div class="border-t pt-6 space-y-6">
                <h3 class="text-base font-bold text-gray-900">Upload Media</h3>
                
                @if($property->media->count() > 0)
                    <div class="bg-gray-50 p-4 rounded-xl border mb-4">
                        <span class="text-xs text-gray-400 font-bold block mb-2">Current Media Files</span>
                        <div class="flex flex-wrap gap-4">
                            @foreach($property->media as $media)
                                <div class="relative h-20 w-28 border rounded-lg overflow-hidden">
                                    <img src="{{ asset('storage/' . $media->file_path) }}" class="h-full w-full object-cover">
                                    @if($media->is_primary)
                                        <span class="absolute bottom-0 inset-x-0 bg-red-600/90 text-[10px] text-white font-bold text-center py-0.5">Cover</span>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Replace Cover / Primary Image</label>
                        <input type="file" name="primary_image" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-red-50 file:text-red-700 hover:file:bg-red-100" accept="image/*">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Add More Images</label>
                        <input type="file" name="images[]" multiple class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-red-50 file:text-red-700 hover:file:bg-red-100" accept="image/*">
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="pt-6 border-t flex justify-end space-x-4">
                <a href="{{ route('dashboard') }}" class="px-6 py-2.5 border rounded-lg text-sm font-bold text-gray-700 hover:bg-gray-50 transition-colors">Cancel</a>
                <button type="submit" class="px-6 py-2.5 bg-red-600 hover:bg-red-700 text-white rounded-lg text-sm font-bold shadow-md transition-colors">Update Property</button>
            </div>
        </form>
    </div>
</div>

<script>
    function toggleFields() {
        const categorySelect = document.getElementById('category_id');
        const selectedCategoryOption = categorySelect.options[categorySelect.selectedIndex];
        const categorySlug = selectedCategoryOption.getAttribute('data-slug');

        const typeSelect = document.getElementById('property_type_id');
        const selectedTypeOption = typeSelect.options[typeSelect.selectedIndex];
        const typeSlug = selectedTypeOption ? selectedTypeOption.getAttribute('data-slug') : '';

        const roomsSection = document.getElementById('rooms-details-section');

        if (categorySlug === 'commercial' && (typeSlug === 'commercial-plot' || typeSlug === 'industrial-shed' || typeSlug === 'factory')) {
            roomsSection.style.display = 'none';
        } else if (typeSlug === 'residential-plot' || typeSlug === 'farm-house') {
            roomsSection.style.display = 'none';
        } else {
            roomsSection.style.display = 'block';
        }

        const categoryId = categorySelect.value;
        for (let i = 0; i < typeSelect.options.length; i++) {
            const option = typeSelect.options[i];
            const optionCatId = option.getAttribute('data-category-id');
            if (optionCatId === categoryId) {
                option.style.display = 'block';
            } else {
                option.style.display = 'none';
            }
        }
    }

    document.addEventListener("DOMContentLoaded", function() {
        toggleFields();
    });
</script>
@endsection
