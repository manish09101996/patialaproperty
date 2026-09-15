@extends('layouts.public')

@section('title', 'Properties in Patiala | Buy & Rent Listings')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="flex flex-col lg:flex-row gap-8">
        
        <!-- Filter Sidebar (Desktop) -->
        <aside class="w-full lg:w-1/4 bg-white p-6 rounded-2xl border border-gray-100 shadow-sm h-fit">
            <h2 class="text-xl font-bold text-gray-900 mb-6">Filter Properties</h2>
            
            <form action="{{ route('properties.index') }}" method="GET" class="space-y-6">
                <!-- Search Keyword -->
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Search Keyword</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="e.g. 3 BHK flat, Model Town" class="w-full rounded-lg border-gray-200 focus:border-red-500 focus:ring focus:ring-red-200 text-sm py-2 px-3">
                </div>

                <!-- Purpose -->
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Purpose</label>
                    <div class="grid grid-cols-2 gap-2">
                        <label class="border rounded-lg p-2.5 text-center cursor-pointer text-sm font-semibold flex items-center justify-center space-x-1.5 {{ request('purpose', 'sell') === 'sell' ? 'bg-red-50 border-red-500 text-red-700' : 'border-gray-200 hover:bg-gray-50' }}">
                            <input type="radio" name="purpose" value="sell" class="sr-only" {{ request('purpose', 'sell') === 'sell' ? 'checked' : '' }} onchange="this.form.submit()">
                            <span>Buy</span>
                        </label>
                        <label class="border rounded-lg p-2.5 text-center cursor-pointer text-sm font-semibold flex items-center justify-center space-x-1.5 {{ request('purpose') === 'rent' ? 'bg-red-50 border-red-500 text-red-700' : 'border-gray-200 hover:bg-gray-50' }}">
                            <input type="radio" name="purpose" value="rent" class="sr-only" {{ request('purpose') === 'rent' ? 'checked' : '' }} onchange="this.form.submit()">
                            <span>Rent</span>
                        </label>
                    </div>
                </div>

                <!-- Locality -->
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Patiala Locality</label>
                    <select name="area_id" class="w-full rounded-lg border-gray-200 focus:border-red-500 focus:ring focus:ring-red-200 text-sm" onchange="this.form.submit()">
                        <option value="">All Areas</option>
                        @foreach($areas as $area)
                            <option value="{{ $area->id }}" {{ request('area_id') == $area->id ? 'selected' : '' }}>{{ $area->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Category -->
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Category</label>
                    <select name="category" class="w-full rounded-lg border-gray-200 focus:border-red-500 focus:ring focus:ring-red-200 text-sm" onchange="this.form.submit()">
                        <option value="">Residential & Commercial</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->slug }}" {{ request('category') === $cat->slug ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- BHK Rooms -->
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">BHK (Bedrooms)</label>
                    <div class="grid grid-cols-4 gap-1">
                        @foreach(['1', '2', '3', '4+'] as $bhk)
                            <label class="border rounded-lg py-2 text-center text-xs font-semibold cursor-pointer {{ request('bhk') === $bhk ? 'bg-red-50 border-red-500 text-red-700' : 'border-gray-200 hover:bg-gray-50' }}">
                                <input type="radio" name="bhk" value="{{ $bhk }}" class="sr-only" {{ request('bhk') === $bhk ? 'checked' : '' }} onchange="this.form.submit()">
                                {{ $bhk }}
                            </label>
                        @endforeach
                    </div>
                </div>

                <!-- Price range -->
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Price Budget (₹)</label>
                    <div class="grid grid-cols-2 gap-2">
                        <input type="number" name="min_price" value="{{ request('min_price') }}" placeholder="Min" class="w-full rounded-lg border-gray-200 text-xs p-2">
                        <input type="number" name="max_price" value="{{ request('max_price') }}" placeholder="Max" class="w-full rounded-lg border-gray-200 text-xs p-2">
                    </div>
                </div>

                <!-- Furnishing -->
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Furnishing</label>
                    <select name="furnishing" class="w-full rounded-lg border-gray-200 text-sm" onchange="this.form.submit()">
                        <option value="">Any</option>
                        <option value="unfurnished" {{ request('furnishing') === 'unfurnished' ? 'selected' : '' }}>Unfurnished</option>
                        <option value="semi-furnished" {{ request('furnishing') === 'semi-furnished' ? 'selected' : '' }}>Semi-Furnished</option>
                        <option value="fully-furnished" {{ request('furnishing') === 'fully-furnished' ? 'selected' : '' }}>Fully Furnished</option>
                    </select>
                </div>

                <div class="pt-4 border-t flex space-x-2">
                    <a href="{{ route('properties.index') }}" class="w-1/2 text-center border border-gray-200 text-gray-700 py-2 rounded-lg text-sm font-semibold hover:bg-gray-50 transition-colors">Clear All</a>
                    <button type="submit" class="w-1/2 bg-red-600 hover:bg-red-700 text-white py-2 rounded-lg text-sm font-semibold transition-colors">Apply</button>
                </div>
            </form>
        </aside>

        <!-- Listings Content Area -->
        <main class="w-full lg:w-3/4">
            <!-- Header bar -->
            <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm mb-6 flex flex-col sm:flex-row justify-between items-center gap-4">
                <div>
                    <h1 class="text-2xl font-black text-gray-900">Properties in Patiala</h1>
                    <p class="text-sm text-gray-400">Showing {{ $properties->firstItem() ?? 0 }}-{{ $properties->lastItem() ?? 0 }} of {{ $properties->total() }} results</p>
                </div>
                
                <form action="{{ route('properties.index') }}" method="GET" class="flex items-center space-x-2">
                    <!-- Preserve existing inputs -->
                    @foreach(request()->except(['sort', 'page']) as $k => $v)
                        <input type="hidden" name="{{ $k }}" value="{{ $v }}">
                    @endforeach
                    
                    <span class="text-sm text-gray-500 whitespace-nowrap">Sort By</span>
                    <select name="sort" class="rounded-lg border-gray-200 text-sm" onchange="this.form.submit()">
                        <option value="newest" {{ request('sort') === 'newest' ? 'selected' : '' }}>Newest Listings</option>
                        <option value="price_low" {{ request('sort') === 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                        <option value="price_high" {{ request('sort') === 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                        <option value="popular" {{ request('sort') === 'popular' ? 'selected' : '' }}>Most Popular</option>
                    </select>
                </form>
            </div>

            <!-- Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($properties as $prop)
                    <!-- Card -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-shadow relative">
                        <span class="absolute top-4 right-4 bg-red-600 text-white text-xs px-2.5 py-1 rounded-full font-semibold uppercase z-10">{{ ucfirst($prop->purpose) }}</span>
                        
                        <!-- Image -->
                        <div class="h-44 bg-gray-200 overflow-hidden relative">
                            @if($prop->primaryImage)
                                <img src="{{ asset('storage/' . $prop->primaryImage->file_path) }}" alt="{{ $prop->title }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center bg-gray-300 text-gray-500">
                                    <span class="font-semibold">No Image</span>
                                </div>
                            @endif
                        </div>

                        <!-- Info -->
                        <div class="p-5">
                            <span class="text-xs font-bold text-red-600 uppercase tracking-widest">{{ $prop->type->name }}</span>
                            <h3 class="text-lg font-bold text-gray-900 line-clamp-1 mt-1"><a href="{{ route('properties.show', $prop->slug) }}" class="hover:underline">{{ $prop->title }}</a></h3>
                            <p class="text-sm text-gray-400 mt-1 flex items-center">
                                <svg class="h-4 w-4 mr-1 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                {{ $prop->area->name }}, Patiala
                            </p>

                            <!-- Price and Area -->
                            <div class="mt-4 flex items-baseline justify-between border-t border-gray-100 pt-4">
                                <span class="text-lg font-black text-gray-900">
                                    @if($prop->price_type === 'contact_for_price')
                                        Contact for Price
                                    @else
                                        ₹{{ number_format($prop->price) }}
                                        @if($prop->purpose === 'rent')
                                            <span class="text-xs text-gray-400 font-normal">/ mo</span>
                                        @endif
                                    @endif
                                </span>
                                <span class="text-xs text-gray-400 font-medium">{{ $prop->property_area }} {{ str_replace('_', ' ', $prop->area_unit) }}</span>
                            </div>

                            @if($prop->bedrooms || $prop->bathrooms)
                            <div class="mt-3 flex space-x-3 text-xs text-gray-500 bg-gray-50 p-2 rounded-lg justify-around">
                                @if($prop->bedrooms)
                                    <span><strong>{{ $prop->bedrooms }}</strong> BHK</span>
                                @endif
                                @if($prop->bathrooms)
                                    <span><strong>{{ $prop->bathrooms }}</strong> Bath</span>
                                @endif
                                @if($prop->furnishing)
                                    <span class="capitalize">{{ str_replace('-', ' ', $prop->furnishing) }}</span>
                                @endif
                            </div>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="col-span-3 text-center text-gray-500 py-20 bg-white rounded-2xl border">
                        <svg class="h-12 w-12 text-gray-300 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <h4 class="text-lg font-bold text-gray-700">No properties match your filters.</h4>
                        <p class="text-xs text-gray-400 mt-1">Try resetting the localities, budgets, or keywords.</p>
                        <a href="{{ route('properties.index') }}" class="inline-block mt-4 bg-red-600 text-white text-xs font-bold px-4 py-2 rounded-lg">Reset All Filters</a>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                {{ $properties->links() }}
            </div>
        </main>

    </div>
</div>
@endsection
