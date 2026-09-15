@extends('layouts.public')

@section('title', 'Patiala Property | Premium Property & Home Services Portal')

@section('content')
<!-- Hero Section -->
<div class="relative bg-slate-900 h-[620px] flex items-center justify-center overflow-hidden">
    <!-- Visual Background -->
    <div class="absolute inset-0 bg-cover bg-center opacity-45 scale-105 transition-transform duration-[10s]" style="background-image: url('https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=1600&q=80');"></div>
    <div class="absolute inset-0 bg-gradient-to-b from-slate-950/80 via-slate-900/60 to-slate-50 z-10"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 z-20 text-center text-white mt-12 w-full">
        <!-- Main Heading -->
        <span class="bg-blue-500/10 text-blue-400 border border-blue-400/20 text-xs px-3.5 py-1 rounded-full font-bold uppercase tracking-widest inline-block mb-4">Patiala's Rental & Property Hub</span>
        <h1 class="text-4xl md:text-6xl font-black tracking-tight leading-tight mb-4">Find a <span class="bg-gradient-to-r from-amber-400 to-orange-400 bg-clip-text text-transparent">Rental Place</span> You'll Love in Patiala</h1>
        <p class="text-base md:text-lg text-slate-350 mb-10 max-w-2xl mx-auto font-medium">Explore verified rental homes, flats, commercial spaces and more across Patiala.</p>

        <!-- Floating Glassmorphism Search Card -->
        <div class="bg-white/95 backdrop-blur-md p-6 rounded-3xl shadow-2xl max-w-4xl mx-auto w-full text-slate-800 border border-white/20">
            <!-- Rent | Buy | Sell | Projects Selector tabs -->
            <div class="flex space-x-2 border-b border-slate-100 pb-4 mb-4 justify-start overflow-x-auto">
                <button type="button" onclick="setSearchPurpose('rent', this)" class="search-purpose-btn px-5 py-1.5 rounded-full text-xs font-black transition-all bg-blue-600 text-white shadow-md shadow-blue-500/20 flex items-center space-x-1.5">
                    <span class="h-1.5 w-1.5 rounded-full bg-amber-400 animate-pulse"></span>
                    <span>Rent</span>
                </button>
                <button type="button" onclick="setSearchPurpose('sell', this)" class="search-purpose-btn px-5 py-1.5 rounded-full text-xs font-black transition-all bg-slate-100 text-slate-600 hover:bg-slate-200">
                    <span>Buy</span>
                </button>
                <button type="button" onclick="handleSearchSell(this)" class="search-purpose-btn px-5 py-1.5 rounded-full text-xs font-black transition-all bg-slate-100 text-slate-600 hover:bg-slate-200">
                    <span>Sell</span>
                </button>
                <button type="button" onclick="handleSearchProjects(this)" class="search-purpose-btn px-5 py-1.5 rounded-full text-xs font-black transition-all bg-slate-100 text-slate-600 hover:bg-slate-200">
                    <span>Projects</span>
                </button>
            </div>

            <form action="{{ route('properties.index') }}" method="GET" id="hero-search-form" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end text-left">
                <input type="hidden" name="purpose" id="search-purpose" value="rent">
                
                <!-- Search Locality -->
                <div class="space-y-1.5">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest">Select Locality</label>
                    <select name="area_id" class="w-full rounded-xl border-slate-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 text-xs py-3 font-semibold">
                        <option value="">All Localities in Patiala</option>
                        @foreach($areas as $area)
                            <option value="{{ $area->id }}">{{ $area->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Property Type -->
                <div class="space-y-1.5">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest">Property Type</label>
                    <select name="type_id" class="w-full rounded-xl border-slate-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 text-xs py-3 font-semibold">
                        <option value="">Residential & Commercial</option>
                        @foreach($categories as $cat)
                            <optgroup label="{{ $cat->name }}">
                                @foreach($cat->types as $type)
                                    <option value="{{ $type->id }}">{{ $type->name }}</option>
                                @endforeach
                            </optgroup>
                        @endforeach
                    </select>
                </div>

                <!-- Budget (Default Rental Budgets) -->
                <div class="space-y-1.5">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest">Max Budget</label>
                    <select name="max_price" id="budget-select" class="w-full rounded-xl border-slate-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 text-xs py-3 font-semibold">
                        <option value="">No Max Limit</option>
                        <option value="10000">Up to ₹10,000 / month</option>
                        <option value="15000">Up to ₹15,000 / month</option>
                        <option value="25000">Up to ₹25,000 / month</option>
                        <option value="35000">Up to ₹35,000 / month</option>
                        <option value="50000">Up to ₹50,000 / month</option>
                        <option value="100000">Above ₹50,000 / month</option>
                    </select>
                </div>

                <!-- Submit Button -->
                <div>
                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-xl shadow-lg shadow-blue-500/20 transition-all flex items-center justify-center space-x-2 text-xs">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        <span id="search-btn-label">Search Rental Properties</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- Quick Links -->
        <div class="mt-6 text-xs font-semibold text-slate-300 space-x-2 flex items-center justify-center flex-wrap gap-y-2">
            <span class="opacity-60">Popular Search Areas:</span>
            <a href="{{ route('properties.index', ['search' => 'Model Town', 'purpose' => 'rent']) }}" class="bg-white/10 hover:bg-white/20 px-3 py-1 rounded-full transition-all">Model Town</a>
            <a href="{{ route('properties.index', ['search' => 'Urban Estate', 'purpose' => 'rent']) }}" class="bg-white/10 hover:bg-white/20 px-3 py-1 rounded-full transition-all">Urban Estate</a>
            <a href="{{ route('properties.index', ['search' => 'Leela Bhawan', 'purpose' => 'rent']) }}" class="bg-white/10 hover:bg-white/20 px-3 py-1 rounded-full transition-all">Leela Bhawan</a>
            <a href="{{ route('properties.index', ['search' => 'Rajpura Road', 'purpose' => 'rent']) }}" class="bg-white/10 hover:bg-white/20 px-3 py-1 rounded-full transition-all">Rajpura Road</a>
        </div>
    </div>
</div>

<!-- Quick Action Cards Section -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative -mt-16 z-30">
    <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
        
        <!-- Action 1: Rent Property (First & Highlighted) -->
        <a href="{{ route('properties.index', ['purpose' => 'rent']) }}" class="bg-white p-6 rounded-2xl border-2 border-blue-600 shadow-xl hover:shadow-2xl hover:-translate-y-1 transition-all group flex flex-col justify-between h-44 relative overflow-hidden">
            <span class="absolute top-2.5 right-2.5 bg-blue-600 text-white text-[9px] font-black uppercase tracking-wider px-2 py-0.5 rounded-full">Top Choice</span>
            <div class="h-10 w-10 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            </div>
            <div>
                <h4 class="font-black text-slate-900 text-sm flex items-center space-x-1">
                    <span>Rent Property</span>
                    <svg class="h-3 w-3 text-blue-600 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                </h4>
                <p class="text-[11px] text-slate-500 mt-1">Explore verified flats, kothis & commercial rentals.</p>
            </div>
        </a>

        <!-- Action 2: Buy Property -->
        <a href="{{ route('properties.index', ['purpose' => 'sell']) }}" class="bg-white p-6 rounded-2xl border border-slate-100 shadow-xl hover:shadow-2xl hover:-translate-y-1 transition-all group flex flex-col justify-between h-44">
            <div class="h-10 w-10 bg-amber-50 text-amber-600 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
            </div>
            <div>
                <h4 class="font-black text-slate-800 text-sm flex items-center space-x-1">
                    <span>Buy Property</span>
                    <svg class="h-3 w-3 text-slate-400 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                </h4>
                <p class="text-[11px] text-slate-400 mt-1">Discover houses, villas & apartments in Patiala.</p>
            </div>
        </a>

        <!-- Action 3: Sell Property -->
        <a href="{{ route('properties.create') }}" class="bg-white p-6 rounded-2xl border border-slate-100 shadow-xl hover:shadow-2xl hover:-translate-y-1 transition-all group flex flex-col justify-between h-44">
            <div class="h-10 w-10 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <h4 class="font-black text-slate-800 text-sm flex items-center space-x-1">
                    <span>Sell Property</span>
                    <svg class="h-3 w-3 text-slate-400 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                </h4>
                <p class="text-[11px] text-slate-400 mt-1">List your flats, shops, or land listings for free.</p>
            </div>
        </a>

        <!-- Action 4: Property & Professional Services -->
        <a href="#services-section" class="bg-white p-6 rounded-2xl border border-slate-100 shadow-xl hover:shadow-2xl hover:-translate-y-1 transition-all group flex flex-col justify-between h-44">
            <div class="h-10 w-10 bg-purple-50 text-purple-600 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            </div>
            <div>
                <h4 class="font-black text-slate-800 text-sm flex items-center space-x-1">
                    <span>Professional Services</span>
                    <svg class="h-3 w-3 text-slate-400 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                </h4>
                <p class="text-[11px] text-slate-400 mt-1">NOC, CLU, PUDA, Architectural Planning & 3D.</p>
            </div>
        </a>
    </div>
</div>

<!-- Tabbed Property Listings -->
<div class="py-24 bg-slate-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header & Tab controls -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-end mb-12 gap-6">
            <div>
                <span class="text-blue-600 text-xs font-bold uppercase tracking-widest">Handpicked Listings</span>
                <h2 class="text-3xl font-black text-slate-900 mt-1">Properties in Patiala</h2>
                <p class="text-sm text-slate-400 mt-1">Discover verified, highly rated rental and sale properties you'll love.</p>
            </div>
            
            <!-- Controls (For Rent First & Default Active) -->
            <div class="flex bg-slate-200/60 p-1.5 rounded-xl text-xs font-bold text-slate-500 space-x-1">
                <button onclick="switchTab('rent', this)" class="tab-control-btn px-4 py-2 rounded-lg bg-white text-slate-900 shadow-sm transition-all flex items-center space-x-1.5">
                    <span class="h-1.5 w-1.5 rounded-full bg-blue-600"></span>
                    <span>For Rent</span>
                </button>
                <button onclick="switchTab('featured', this)" class="tab-control-btn px-4 py-2 rounded-lg hover:bg-white hover:text-slate-900 transition-all">Featured</button>
                <button onclick="switchTab('latest', this)" class="tab-control-btn px-4 py-2 rounded-lg hover:bg-white hover:text-slate-900 transition-all">Latest</button>
                <button onclick="switchTab('sale', this)" class="tab-control-btn px-4 py-2 rounded-lg hover:bg-white hover:text-slate-900 transition-all">For Sale</button>
            </div>
        </div>

        <!-- Tab content grids -->
        <div id="tabs-container">
            
            <!-- Tab: Rent (Default Visible) -->
            <div id="tab-rent" class="tab-grid grid grid-cols-1 md:grid-cols-3 gap-8">
                @each('components.property-card', $rentProperties, 'prop', 'components.empty-listings')
            </div>

            <!-- Tab: Featured -->
            <div id="tab-featured" class="tab-grid grid grid-cols-1 md:grid-cols-3 gap-8 hidden">
                @each('components.property-card', $featuredProperties, 'prop', 'components.empty-listings')
            </div>

            <!-- Tab: Latest -->
            <div id="tab-latest" class="tab-grid grid grid-cols-1 md:grid-cols-3 gap-8 hidden">
                @each('components.property-card', $latestProperties, 'prop', 'components.empty-listings')
            </div>

            <!-- Tab: Sale -->
            <div id="tab-sale" class="tab-grid grid grid-cols-1 md:grid-cols-3 gap-8 hidden">
                @each('components.property-card', $saleProperties, 'prop', 'components.empty-listings')
            </div>

        </div>

        <div class="text-center mt-12">
            <a href="{{ route('properties.index') }}" class="inline-block border-2 border-slate-200 hover:border-slate-800 text-slate-800 hover:text-slate-900 font-bold px-8 py-3 rounded-xl transition-all text-xs">View All Properties &rarr;</a>
        </div>
    </div>
</div>

<!-- Browse by Property Type -->
<div class="py-24 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-lg mx-auto mb-16">
            <span class="text-blue-600 text-xs font-bold uppercase tracking-widest">Browse Categories</span>
            <h2 class="text-3xl font-black text-slate-900 mt-2">Explore Properties Your Way</h2>
            <p class="text-sm text-slate-400 mt-2">Find exactly what matches your lifestyle or business requirement.</p>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-6 gap-6">
            @php 
                $typeImages = [
                    'apartment' => 'https://images.unsplash.com/photo-1545324418-cc1a3fa10c00?auto=format&fit=crop&w=300&q=80',
                    'flat' => 'https://images.unsplash.com/photo-1493809842364-78817add7ffb?auto=format&fit=crop&w=300&q=80',
                    'villa' => 'https://images.unsplash.com/photo-1613977257363-707ba9348227?auto=format&fit=crop&w=300&q=80',
                    'independent-house' => 'https://images.unsplash.com/photo-1600585154526-990dced4db0d?auto=format&fit=crop&w=300&q=80',
                    'office' => 'https://images.unsplash.com/photo-1497366216548-37526070297c?auto=format&fit=crop&w=300&q=80',
                    'shop' => 'https://images.unsplash.com/photo-1555529669-e69e7aa0ba9a?auto=format&fit=crop&w=300&q=80',
                ];
            @endphp

            @foreach($propertyTypes as $type)
                <a href="{{ route('properties.index', ['type_id' => $type->id]) }}" class="relative h-48 rounded-2xl overflow-hidden group shadow-sm border border-slate-100 flex flex-col justify-end p-4">
                    <!-- Image zoom effect -->
                    <div class="absolute inset-0 bg-cover bg-center group-hover:scale-110 transition-transform duration-300" style="background-image: url('{{ $typeImages[$type->slug] ?? 'https://images.unsplash.com/photo-1564013799919-ab600027ffc6?auto=format&fit=crop&w=300&q=80' }}')"></div>
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-900/90 via-slate-900/40 to-transparent z-10"></div>
                    
                    <!-- Content -->
                    <div class="relative z-20 text-white">
                        <span class="block font-bold text-sm leading-tight">{{ $type->name }}</span>
                        <span class="block text-[10px] text-slate-300 mt-0.5 font-medium">{{ $type->properties_count }} Active Listings</span>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</div>

<!-- Explore Popular Patiala Areas -->
<div class="py-24 bg-slate-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-end mb-16 gap-4">
            <div>
                <span class="text-blue-600 text-xs font-bold uppercase tracking-widest">Local Insights</span>
                <h2 class="text-3xl font-black text-slate-900 mt-2">Explore Popular Areas in Patiala</h2>
                <p class="text-sm text-slate-400 mt-2">Check availability across the highly demanded localities in Patiala.</p>
            </div>
            <a href="{{ route('properties.index') }}" class="text-blue-600 font-bold hover:underline text-sm">&rarr; See All Localities</a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @php
                $areaImages = [
                    'Model Town' => 'https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?auto=format&fit=crop&w=400&q=80',
                    'Urban Estate' => 'https://images.unsplash.com/photo-1600566753190-17f0baa2a6c3?auto=format&fit=crop&w=400&q=80',
                    'Leela Bhawan' => 'https://images.unsplash.com/photo-1600607687920-4e2a09cf159d?auto=format&fit=crop&w=400&q=80',
                    'Tripuri' => 'https://images.unsplash.com/photo-1605276374104-dee2a0ed3cd6?auto=format&fit=crop&w=400&q=80',
                    'Nabha Road' => 'https://images.unsplash.com/photo-1583608205776-bfd35f0d9f83?auto=format&fit=crop&w=400&q=80',
                    'Rajpura Road' => 'https://images.unsplash.com/photo-1592595896551-12b371d546d5?auto=format&fit=crop&w=400&q=80',
                ];
            @endphp

            @foreach($areas as $area)
                <a href="{{ route('properties.index', ['area_id' => $area->id]) }}" class="relative h-60 rounded-3xl overflow-hidden group shadow-lg border border-slate-100 flex flex-col justify-end p-6">
                    <div class="absolute inset-0 bg-cover bg-center group-hover:scale-105 transition-transform duration-[4s]" style="background-image: url('{{ $areaImages[$area->name] ?? 'https://images.unsplash.com/photo-1512917774080-9991f1c4c750?auto=format&fit=crop&w=400&q=80' }}')"></div>
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-900/30 to-transparent z-10"></div>
                    
                    <div class="relative z-20 text-white flex justify-between items-end">
                        <div>
                            <h4 class="text-lg font-black">{{ $area->name }}</h4>
                            <p class="text-xs text-slate-300 mt-1">Covering Pincode {{ $area->pincode ?? '147001' }}</p>
                        </div>
                        <span class="bg-white/15 backdrop-blur-md text-white text-[10px] font-bold px-3 py-1.5 rounded-full uppercase tracking-wider group-hover:bg-amber-500 group-hover:text-white transition-all">{{ $area->properties_count }}+ Homes</span>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</div>

<!-- Why Choose Us -->
<div class="py-24 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-lg mx-auto mb-16">
            <span class="text-blue-600 text-xs font-bold uppercase tracking-widest">Why Choose Us</span>
            <h2 class="text-3xl font-black text-slate-900 mt-2">Simplify Your Search</h2>
            <p class="text-sm text-slate-400 mt-2">What makes Patiala Property the preferred portal in Patiala.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Reason 1 -->
            <div class="p-6 bg-slate-50 rounded-2xl border border-slate-100 hover:shadow-md transition-shadow">
                <div class="h-10 w-10 bg-green-100 text-green-700 rounded-xl flex items-center justify-center font-bold mb-4">
                    ✓
                </div>
                <h4 class="text-base font-bold text-slate-900">Verified Properties Only</h4>
                <p class="text-xs text-slate-500 mt-2 leading-relaxed">Every property listed goes through a verification check by our admin team before publication.</p>
            </div>
            
            <!-- Reason 2 -->
            <div class="p-6 bg-slate-50 rounded-2xl border border-slate-100 hover:shadow-md transition-shadow">
                <div class="h-10 w-10 bg-blue-100 text-blue-700 rounded-xl flex items-center justify-center font-bold mb-4">
                    ✓
                </div>
                <h4 class="text-base font-bold text-slate-900">Easy Property Visits</h4>
                <p class="text-xs text-slate-500 mt-2 leading-relaxed">Book a property site visit directly from the details page, choosing your preferred date and slot.</p>
            </div>

            <!-- Reason 3 -->
            <div class="p-6 bg-slate-50 rounded-2xl border border-slate-100 hover:shadow-md transition-shadow">
                <div class="h-10 w-10 bg-amber-100 text-amber-700 rounded-xl flex items-center justify-center font-bold mb-4">
                    ✓
                </div>
                <h4 class="text-base font-bold text-slate-900">100% Focused on Patiala</h4>
                <p class="text-xs text-slate-500 mt-2 leading-relaxed">No cluttering from other states. We cover neighborhoods exclusively in Patiala, Punjab.</p>
            </div>
        </div>
    </div>
</div>

<!-- Property & Professional Services Section -->
<div id="services-section" class="py-24 bg-slate-950 text-white relative overflow-hidden">
    <!-- Ambient glow decoration -->
    <div class="absolute top-0 right-1/4 w-96 h-96 bg-blue-600/10 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute bottom-0 left-1/4 w-96 h-96 bg-amber-500/10 rounded-full blur-3xl pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-end mb-16 gap-4">
            <div class="max-w-3xl">
                <span class="text-amber-500 text-xs font-bold uppercase tracking-widest flex items-center space-x-1.5 mb-2">
                    <span class="h-1.5 w-1.5 rounded-full bg-amber-400 animate-pulse"></span>
                    <span>One-Stop Solutions</span>
                </span>
                <h2 class="text-3xl md:text-4xl font-black text-white">Property & Professional Services</h2>
                <p class="text-sm md:text-base text-slate-400 mt-2 font-medium leading-relaxed">Property buying, renting aur approval se lekar architectural planning aur 3D design tak — all property services under one roof.</p>
            </div>
            <div>
                <a href="#hero-search-form" class="inline-flex items-center space-x-2 text-xs font-bold text-slate-300 hover:text-white bg-slate-900 border border-slate-800 px-4 py-2.5 rounded-xl transition-all hover:border-slate-700">
                    <span>Explore Properties</span>
                    <span>&rarr;</span>
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            
            <!-- Service 1: NOC Services -->
            <div class="bg-slate-900/90 border border-slate-800 p-6 rounded-2xl space-y-4 hover:border-blue-500/50 hover:bg-slate-900 transition-all group flex flex-col justify-between shadow-lg">
                <div class="space-y-4">
                    <div class="flex justify-between items-start">
                        <div class="h-12 w-12 bg-blue-500/10 text-blue-400 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        </div>
                        <span class="text-[10px] bg-blue-500/10 text-blue-400 border border-blue-400/20 px-2.5 py-0.5 rounded-full uppercase font-black tracking-wider">Documentation</span>
                    </div>
                    <div>
                        <h4 class="font-black text-base text-white group-hover:text-blue-400 transition-colors">NOC Services</h4>
                        <p class="text-xs text-slate-400 mt-2 leading-relaxed">Complete assistance for NOC applications and documentation.</p>
                    </div>
                </div>
                <div class="pt-4 border-t border-slate-800/80 flex items-center justify-between text-xs">
                    <span class="text-slate-500 text-[11px]">Patiala Jurisdiction</span>
                    <a href="https://wa.me/919876543210?text=Hi,%20I%20am%20interested%20in%20NOC%20Services%20in%20Patiala" target="_blank" class="text-blue-400 font-bold hover:text-blue-300 flex items-center space-x-1">
                        <span>Get Assistance</span>
                        <span>&rarr;</span>
                    </a>
                </div>
            </div>

            <!-- Service 2: CLU Services -->
            <div class="bg-slate-900/90 border border-slate-800 p-6 rounded-2xl space-y-4 hover:border-amber-500/50 hover:bg-slate-900 transition-all group flex flex-col justify-between shadow-lg">
                <div class="space-y-4">
                    <div class="flex justify-between items-start">
                        <div class="h-12 w-12 bg-amber-500/10 text-amber-400 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
                        </div>
                        <span class="text-[10px] bg-amber-500/10 text-amber-400 border border-amber-400/20 px-2.5 py-0.5 rounded-full uppercase font-black tracking-wider">Land Use</span>
                    </div>
                    <div>
                        <h4 class="font-black text-base text-white group-hover:text-amber-400 transition-colors">CLU Services</h4>
                        <p class="text-xs text-slate-400 mt-2 leading-relaxed">Land use conversion and CLU-related assistance.</p>
                    </div>
                </div>
                <div class="pt-4 border-t border-slate-800/80 flex items-center justify-between text-xs">
                    <span class="text-slate-500 text-[11px]">Commercial & Residential</span>
                    <a href="https://wa.me/919876543210?text=Hi,%20I%20need%20CLU%20Services%20in%20Patiala" target="_blank" class="text-amber-400 font-bold hover:text-amber-300 flex items-center space-x-1">
                        <span>Get Assistance</span>
                        <span>&rarr;</span>
                    </a>
                </div>
            </div>

            <!-- Service 3: CRO Services -->
            <div class="bg-slate-900/90 border border-slate-800 p-6 rounded-2xl space-y-4 hover:border-purple-500/50 hover:bg-slate-900 transition-all group flex flex-col justify-between shadow-lg">
                <div class="space-y-4">
                    <div class="flex justify-between items-start">
                        <div class="h-12 w-12 bg-purple-500/10 text-purple-400 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        </div>
                        <span class="text-[10px] bg-purple-500/10 text-purple-400 border border-purple-400/20 px-2.5 py-0.5 rounded-full uppercase font-black tracking-wider">Clearances</span>
                    </div>
                    <div>
                        <h4 class="font-black text-base text-white group-hover:text-purple-400 transition-colors">CRO Services</h4>
                        <p class="text-xs text-slate-400 mt-2 leading-relaxed">Professional property and approval-related services.</p>
                    </div>
                </div>
                <div class="pt-4 border-t border-slate-800/80 flex items-center justify-between text-xs">
                    <span class="text-slate-500 text-[11px]">Legal & Registry</span>
                    <a href="https://wa.me/919876543210?text=Hi,%20I%20am%20inquiring%20about%20CRO%20Services" target="_blank" class="text-purple-400 font-bold hover:text-purple-300 flex items-center space-x-1">
                        <span>Get Assistance</span>
                        <span>&rarr;</span>
                    </a>
                </div>
            </div>

            <!-- Service 4: PUDA Approval -->
            <div class="bg-slate-900/90 border border-slate-800 p-6 rounded-2xl space-y-4 hover:border-emerald-500/50 hover:bg-slate-900 transition-all group flex flex-col justify-between shadow-lg">
                <div class="space-y-4">
                    <div class="flex justify-between items-start">
                        <div class="h-12 w-12 bg-emerald-500/10 text-emerald-400 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        </div>
                        <span class="text-[10px] bg-emerald-500/10 text-emerald-400 border border-emerald-400/20 px-2.5 py-0.5 rounded-full uppercase font-black tracking-wider">Authority</span>
                    </div>
                    <div>
                        <h4 class="font-black text-base text-white group-hover:text-emerald-400 transition-colors">PUDA Approval</h4>
                        <p class="text-xs text-slate-400 mt-2 leading-relaxed">Guidance and assistance for PUDA approvals.</p>
                    </div>
                </div>
                <div class="pt-4 border-t border-slate-800/80 flex items-center justify-between text-xs">
                    <span class="text-slate-500 text-[11px]">Punjab Urban Dev Authority</span>
                    <a href="https://wa.me/919876543210?text=Hi,%20I%20need%20PUDA%20Approval%20guidance%20for%20my%20property" target="_blank" class="text-emerald-400 font-bold hover:text-emerald-300 flex items-center space-x-1">
                        <span>Get Guidance</span>
                        <span>&rarr;</span>
                    </a>
                </div>
            </div>

            <!-- Service 5: Architectural Planning -->
            <div class="bg-slate-900/90 border border-slate-800 p-6 rounded-2xl space-y-4 hover:border-cyan-500/50 hover:bg-slate-900 transition-all group flex flex-col justify-between shadow-lg">
                <div class="space-y-4">
                    <div class="flex justify-between items-start">
                        <div class="h-12 w-12 bg-cyan-500/10 text-cyan-400 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 4a2 2 0 114 0v1a1 1 0 001 1h3a1 1 0 011 1v3a1 1 0 01-1 1h-1a2 2 0 100 4h1a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-1a2 2 0 10-4 0v1a1 1 0 01-1 1H7a1 1 0 01-1-1v-3a1 1 0 00-1-1H4a2 2 0 110-4h1a1 1 0 001-1V7a1 1 0 011-1h3a1 1 0 001-1V4z"/></svg>
                        </div>
                        <span class="text-[10px] bg-cyan-500/10 text-cyan-400 border border-cyan-400/20 px-2.5 py-0.5 rounded-full uppercase font-black tracking-wider">Architecture</span>
                    </div>
                    <div>
                        <h4 class="font-black text-base text-white group-hover:text-cyan-400 transition-colors">Architectural Planning</h4>
                        <p class="text-xs text-slate-400 mt-2 leading-relaxed">Professional planning, layouts and architectural solutions.</p>
                    </div>
                </div>
                <div class="pt-4 border-t border-slate-800/80 flex items-center justify-between text-xs">
                    <span class="text-slate-500 text-[11px]">House & Commercial Layouts</span>
                    <a href="https://wa.me/919876543210?text=Hi,%20I%20want%20Architectural%20Planning%20services%20in%20Patiala" target="_blank" class="text-cyan-400 font-bold hover:text-cyan-300 flex items-center space-x-1">
                        <span>Get Layout Plans</span>
                        <span>&rarr;</span>
                    </a>
                </div>
            </div>

            <!-- Service 6: 3D Design & Visualization -->
            <div class="bg-slate-900/90 border border-slate-800 p-6 rounded-2xl space-y-4 hover:border-pink-500/50 hover:bg-slate-900 transition-all group flex flex-col justify-between shadow-lg">
                <div class="space-y-4">
                    <div class="flex justify-between items-start">
                        <div class="h-12 w-12 bg-pink-500/10 text-pink-400 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10l-2 1m0 0l-2-1m2 1v2.5M20 7l-2 1m2-1l-2-1m2 1v2.5M14 4l-2-1-2 1M4 7l2-1M4 7l2 1M4 7v2.5M12 21l-2-1m2 1l2-1m-2 1v-2.5M6 18l-2-1v-2.5M18 18l2-1v-2.5"/></svg>
                        </div>
                        <span class="text-[10px] bg-pink-500/10 text-pink-400 border border-pink-400/20 px-2.5 py-0.5 rounded-full uppercase font-black tracking-wider">3D Elevation</span>
                    </div>
                    <div>
                        <h4 class="font-black text-base text-white group-hover:text-pink-400 transition-colors">3D Design & Visualization</h4>
                        <p class="text-xs text-slate-400 mt-2 leading-relaxed">Realistic 3D views and visualization for your property/project.</p>
                    </div>
                </div>
                <div class="pt-4 border-t border-slate-800/80 flex items-center justify-between text-xs">
                    <span class="text-slate-500 text-[11px]">Interior & Front Elevations</span>
                    <a href="https://wa.me/919876543210?text=Hi,%20I%20am%20looking%20for%203D%20Design%20and%20Elevation%20work" target="_blank" class="text-pink-400 font-bold hover:text-pink-300 flex items-center space-x-1">
                        <span>View 3D Renders</span>
                        <span>&rarr;</span>
                    </a>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- New Projects Section -->
<div id="new-projects" class="py-24 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-lg mx-auto mb-16">
            <span class="text-blue-600 text-xs font-bold uppercase tracking-widest">New Construction</span>
            <h2 class="text-3xl font-black text-slate-900 mt-2">Discover New Projects</h2>
            <p class="text-sm text-slate-400 mt-2">Explore upcoming residential colonies and commercial high-rises in Patiala.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @foreach($newProjects as $proj)
                <div class="bg-white rounded-2xl border border-slate-100 overflow-hidden shadow-sm hover:shadow-md transition-shadow relative">
                    <span class="absolute top-4 left-4 bg-blue-600 text-white text-[9px] font-black uppercase tracking-wider px-2 py-0.5 rounded-full z-10 shadow-sm">{{ $proj['status'] }}</span>
                    
                    <!-- Cover -->
                    <div class="h-48 bg-slate-200 overflow-hidden">
                        <img src="{{ $proj['image'] }}" class="w-full h-full object-cover">
                    </div>
                    
                    <!-- Details -->
                    <div class="p-6 space-y-3">
                        <span class="text-[10px] text-blue-600 font-bold uppercase">{{ $proj['builder'] }}</span>
                        <h4 class="font-black text-base text-slate-900"><a href="#" class="hover:underline opacity-70 cursor-not-allowed">{{ $proj['title'] }}</a></h4>
                        <p class="text-xs text-slate-400 flex items-center">
                            <svg class="h-4 w-4 mr-1 text-slate-450" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                            {{ $proj['area'] }}, Patiala
                        </p>
                        
                        <div class="border-t pt-3 flex justify-between items-center text-xs">
                            <span class="text-slate-400">Starting Price</span>
                            <span class="font-black text-slate-800">{{ $proj['price_range'] }}</span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

<!-- How It Works Section -->
<div class="py-24 bg-slate-50 border-t border-b">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-lg mx-auto mb-16">
            <h2 class="text-3xl font-black text-slate-900">How It Works</h2>
            <p class="text-sm text-slate-400 mt-2">Get started with our 3-step simple process.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-12 relative">
            
            <!-- Step 1 -->
            <div class="text-center space-y-4">
                <div class="h-12 w-12 bg-blue-600 text-white rounded-full flex items-center justify-center font-black text-lg mx-auto shadow-lg shadow-blue-500/25">
                    1
                </div>
                <h4 class="font-black text-sm text-slate-900">Search Properties</h4>
                <p class="text-xs text-slate-500 leading-relaxed max-w-xs mx-auto">Browse verified flats, plots, and offices using our locality area and budget filters.</p>
            </div>

            <!-- Step 2 -->
            <div class="text-center space-y-4">
                <div class="h-12 w-12 bg-blue-600 text-white rounded-full flex items-center justify-center font-black text-lg mx-auto shadow-lg shadow-blue-500/25">
                    2
                </div>
                <h4 class="font-black text-sm text-slate-900">Schedule Visits / Connect</h4>
                <p class="text-xs text-slate-500 leading-relaxed max-w-xs mx-auto">Submit site visit booking requests or contact property owners directly via phone/WhatsApp.</p>
            </div>

            <!-- Step 3 -->
            <div class="text-center space-y-4">
                <div class="h-12 w-12 bg-blue-600 text-white rounded-full flex items-center justify-center font-black text-lg mx-auto shadow-lg shadow-blue-500/25">
                    3
                </div>
                <h4 class="font-black text-sm text-slate-900">Move In!</h4>
                <p class="text-xs text-slate-500 leading-relaxed max-w-xs mx-auto">Finalize deals directly with owners/agents and settle comfortably into your new place.</p>
            </div>

        </div>
    </div>
</div>

<!-- Testimonials and Stats -->
<div class="py-24 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-12 items-center">
            
            <!-- Counter stats column -->
            <div class="space-y-8">
                <span class="text-blue-600 text-xs font-bold uppercase tracking-widest">Platform Stats</span>
                <h3 class="text-3xl font-black text-slate-900">Trust Figures of Patiala Property</h3>
                
                <div class="grid grid-cols-2 gap-6">
                    <div class="bg-slate-50 p-5 rounded-2xl border">
                        <span class="text-3xl font-black text-blue-600 block">10,000+</span>
                        <span class="text-xs text-slate-400 mt-1 block">Active Users</span>
                    </div>
                    <div class="bg-slate-50 p-5 rounded-2xl border">
                        <span class="text-3xl font-black text-amber-500 block">500+</span>
                        <span class="text-xs text-slate-400 mt-1 block">Verified Listings</span>
                    </div>
                    <div class="bg-slate-50 p-5 rounded-2xl border">
                        <span class="text-3xl font-black text-purple-600 block">50+</span>
                        <span class="text-xs text-slate-400 mt-1 block">Home Services</span>
                    </div>
                    <div class="bg-slate-50 p-5 rounded-2xl border">
                        <span class="text-3xl font-black text-green-600 block">100%</span>
                        <span class="text-xs text-slate-400 mt-1 block">Patiala Focus</span>
                    </div>
                </div>
            </div>

            <!-- Testimonial cards deck -->
            <div class="lg:col-span-2 bg-slate-50 p-8 rounded-3xl border border-slate-100 shadow-sm relative overflow-hidden">
                <span class="absolute top-4 right-6 text-6xl text-slate-200 font-serif leading-none">“</span>
                
                <div class="space-y-6 relative z-10">
                    <p class="text-slate-600 text-sm leading-relaxed italic">"Finding a rental property in Patiala was incredibly easy. The property visit scheduler feature saved me a lot of time. In one afternoon, I scheduled slots for three houses in Urban Estate and finalized the best deal."</p>
                    
                    <div class="flex items-center space-x-3">
                        <div class="h-10 w-10 bg-blue-600 text-white rounded-full flex items-center justify-center font-bold">AJ</div>
                        <div>
                            <h4 class="text-sm font-bold text-slate-900">Arjun Jaggi</h4>
                            <span class="text-xs text-slate-400">Tenant, Model Town, Patiala</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- Property Insights / Blog Section -->
<div id="insights-section" class="py-24 bg-slate-50 border-t">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-end mb-16 gap-4">
            <div>
                <span class="text-blue-600 text-xs font-bold uppercase tracking-widest flex items-center space-x-1.5">
                    <span class="h-1.5 w-1.5 rounded-full bg-blue-600"></span>
                    <span>Property Insights</span>
                </span>
                <h2 class="text-3xl font-black text-slate-900 mt-2">Patiala Property & Legal Guides</h2>
                <p class="text-sm text-slate-400 mt-2">Tips, market trends and documentation guides by real estate experts in Punjab.</p>
            </div>
            <a href="#" class="text-blue-600 font-bold hover:underline text-sm opacity-60">View All Insights &rarr;</a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @foreach($latestBlogs as $blog)
                <div class="bg-white rounded-2xl border border-slate-100 overflow-hidden shadow-sm hover:shadow-md transition-shadow">
                    <!-- Image -->
                    <div class="h-44 bg-slate-200 overflow-hidden">
                        <img src="{{ $blog['image'] }}" class="w-full h-full object-cover">
                    </div>
                    <!-- Details -->
                    <div class="p-6 space-y-3 text-xs">
                        <div class="flex justify-between items-center text-[10px] text-blue-600 font-bold uppercase">
                            <span>{{ $blog['category'] }}</span>
                            <span class="text-slate-400 font-normal">{{ $blog['date'] }}</span>
                        </div>
                        <h4 class="font-black text-sm text-slate-900 leading-snug"><a href="#" class="hover:underline opacity-70 cursor-not-allowed">{{ $blog['title'] }}</a></h4>
                        <a href="#" class="text-[10px] font-bold text-blue-600 hover:underline flex items-center space-x-1 opacity-60">
                            <span>Read Article</span>
                            <span>&rarr;</span>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

<!-- Final CTA Section -->
<div class="py-20 bg-gradient-to-r from-slate-950 to-slate-900 text-white text-center relative overflow-hidden">
    <div class="absolute -top-24 -left-24 h-48 w-48 rounded-full bg-blue-500/10 blur-3xl z-10"></div>
    <div class="absolute -bottom-24 -right-24 h-48 w-48 rounded-full bg-amber-500/10 blur-3xl z-10"></div>

    <div class="relative z-20 max-w-4xl mx-auto px-4 space-y-6">
        <h2 class="text-3xl md:text-5xl font-black tracking-tight leading-tight">Ready to Find Your Perfect Place?</h2>
        <p class="text-slate-400 text-sm md:text-base max-w-lg mx-auto">Whether you're looking for verified rentals, buying property, or need professional PUDA & NOC services, we've got Patiala covered.</p>
        
        <div class="flex justify-center space-x-4 pt-4">
            <a href="{{ route('properties.index', ['purpose' => 'rent']) }}" class="bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold px-6 py-3 rounded-xl shadow-lg transition-all flex items-center space-x-2">
                <span>Explore Rentals</span>
                <span class="text-amber-400">★</span>
            </a>
            <a href="{{ route('properties.create') }}" class="bg-amber-500 hover:bg-amber-600 text-slate-950 text-xs font-bold px-6 py-3 rounded-xl shadow-lg transition-all">List Your Property</a>
        </div>
    </div>
</div>

<!-- Javascript to set search purpose inside form -->
<script>
    function updateSearchButtonActive(element) {
        const buttons = document.querySelectorAll('.search-purpose-btn');
        buttons.forEach(btn => {
            btn.classList.remove('bg-blue-600', 'text-white', 'shadow-md', 'shadow-blue-500/20');
            btn.classList.add('bg-slate-100', 'text-slate-600', 'hover:bg-slate-200');
        });
        element.classList.add('bg-blue-600', 'text-white', 'shadow-md', 'shadow-blue-500/20');
        element.classList.remove('bg-slate-100', 'text-slate-600', 'hover:bg-slate-200');
    }

    function setSearchPurpose(purpose, element) {
        document.getElementById('search-purpose').value = purpose;
        updateSearchButtonActive(element);
        
        const budgetSelect = document.getElementById('budget-select');
        const btnLabel = document.getElementById('search-btn-label');

        if (purpose === 'rent') {
            btnLabel.innerText = 'Search Rental Properties';
            budgetSelect.innerHTML = `
                <option value="">No Max Limit</option>
                <option value="10000">Up to ₹10,000 / month</option>
                <option value="15000">Up to ₹15,000 / month</option>
                <option value="25000">Up to ₹25,000 / month</option>
                <option value="35000">Up to ₹35,000 / month</option>
                <option value="50000">Up to ₹50,000 / month</option>
                <option value="100000">Above ₹50,000 / month</option>
            `;
        } else {
            btnLabel.innerText = 'Search Properties for Sale';
            budgetSelect.innerHTML = `
                <option value="">No Max Limit</option>
                <option value="2500000">Up to ₹25 Lakh</option>
                <option value="5000000">Up to ₹50 Lakh</option>
                <option value="7500000">Up to ₹75 Lakh</option>
                <option value="10000000">Up to ₹1 Crore</option>
                <option value="20000000">Above ₹1 Crore</option>
            `;
        }
    }

    function handleSearchSell(element) {
        updateSearchButtonActive(element);
        window.location.href = "{{ route('properties.create') }}";
    }

    function handleSearchProjects(element) {
        updateSearchButtonActive(element);
        const projectsSec = document.getElementById('new-projects');
        if (projectsSec) {
            projectsSec.scrollIntoView({ behavior: 'smooth' });
        }
    }

    // Dynamic Javascript Tabs for Featured/Latest/Rent/Sale properties
    function switchTab(tabId, element) {
        // Hide all grids
        const grids = document.querySelectorAll('.tab-grid');
        grids.forEach(grid => grid.classList.add('hidden'));

        // Show selected grid
        document.getElementById('tab-' + tabId).classList.remove('hidden');

        // Remove active styles from other tab buttons
        const buttons = document.querySelectorAll('.tab-control-btn');
        buttons.forEach(btn => {
            btn.classList.remove('bg-white', 'text-slate-900', 'shadow-sm');
            btn.classList.add('hover:bg-white', 'hover:text-slate-900');
        });

        // Add active styles to clicked button
        element.classList.add('bg-white', 'text-slate-900', 'shadow-sm');
        element.classList.remove('hover:bg-white', 'hover:text-slate-900');
    }
</script>
@endsection
