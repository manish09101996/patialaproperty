@extends('layouts.public')

@section('title', $property->title . ' | Patiala Property')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <!-- Breadcrumbs -->
    <nav class="text-xs text-gray-500 mb-6 flex space-x-2">
        <a href="{{ route('home') }}" class="hover:underline">Home</a>
        <span>&rsaquo;</span>
        <a href="{{ route('properties.index') }}" class="hover:underline">Properties</a>
        <span>&rsaquo;</span>
        <span class="text-gray-800 font-medium">{{ $property->title }}</span>
    </nav>

    <div class="flex flex-col lg:flex-row gap-8">
        
        <!-- Left: Image Gallery & Details -->
        <div class="w-full lg:w-2/3 space-y-8">
            
            <!-- Gallery / Media Slider -->
            <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm">
                <div class="h-96 w-full bg-gray-100 rounded-xl overflow-hidden relative" id="gallery-container">
                    @php $primary = $property->media->where('is_primary', true)->first() ?? $property->media->first(); @endphp
                    @if($primary)
                        <img id="main-gallery-img" src="{{ asset('storage/' . $primary->file_path) }}" alt="{{ $property->title }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center bg-gray-300 text-gray-500">
                            <span class="text-lg font-bold">No Image Available</span>
                        </div>
                    @endif
                </div>

                <!-- Thumbnails -->
                @if($property->media->count() > 1)
                    <div class="flex space-x-2 overflow-x-auto mt-4 pb-2">
                        @foreach($property->media as $media)
                            <button onclick="document.getElementById('main-gallery-img').src='{{ asset('storage/' . $media->file_path) }}'" class="h-16 w-24 flex-shrink-0 border rounded-lg overflow-hidden focus:outline-none focus:ring-2 focus:ring-red-500">
                                <img src="{{ asset('storage/' . $media->file_path) }}" class="h-full w-full object-cover">
                            </button>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Property Basic Header -->
            <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm space-y-4">
                <div class="flex flex-wrap justify-between items-start gap-4">
                    <div>
                        <span class="bg-red-50 text-red-700 text-xs px-2.5 py-1 rounded-full font-bold uppercase tracking-wider">{{ $property->type->name }}</span>
                        <h1 class="text-2xl md:text-3xl font-black text-gray-900 mt-2">{{ $property->title }}</h1>
                        <p class="text-sm text-gray-500 flex items-center mt-2">
                            <svg class="h-5 w-5 mr-1.5 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            {{ $property->address }}, {{ $property->area->name }}, Patiala, Punjab
                        </p>
                    </div>
                    <div class="text-right">
                        <span class="text-3xl font-black text-gray-900 block">
                            @if($property->price_type === 'contact_for_price')
                                Contact Owner
                            @else
                                ₹{{ number_format($property->price) }}
                                @if($property->purpose === 'rent')
                                    <span class="text-sm font-normal text-gray-400">/ mo</span>
                                @endif
                            @endif
                        </span>
                        <span class="text-xs text-gray-400 capitalize">Price Type: <strong>{{ $property->price_type }}</strong></span>
                    </div>
                </div>

                <!-- Core Details Grid -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 border-t border-b py-4 text-sm">
                    <div>
                        <span class="text-gray-400 block">Purpose</span>
                        <span class="font-bold text-gray-800 capitalize">{{ $property->purpose === 'sell' ? 'For Sale' : 'For Rent' }}</span>
                    </div>
                    <div>
                        <span class="text-gray-400 block">Property Area</span>
                        <span class="font-bold text-gray-800">{{ $property->property_area }} {{ str_replace('_', ' ', $property->area_unit) }}</span>
                    </div>
                    @if($property->bedrooms)
                    <div>
                        <span class="text-gray-400 block">Bedrooms</span>
                        <span class="font-bold text-gray-800">{{ $property->bedrooms }} BHK</span>
                    </div>
                    @endif
                    @if($property->bathrooms)
                    <div>
                        <span class="text-gray-400 block">Bathrooms</span>
                        <span class="font-bold text-gray-800">{{ $property->bathrooms }}</span>
                    </div>
                    @endif
                    @if($property->furnishing)
                    <div>
                        <span class="text-gray-400 block">Furnishing</span>
                        <span class="font-bold text-gray-800 capitalize">{{ str_replace('-', ' ', $property->furnishing) }}</span>
                    </div>
                    @endif
                    @if($property->facing)
                    <div>
                        <span class="text-gray-400 block">Facing</span>
                        <span class="font-bold text-gray-800 capitalize">{{ $property->facing }}</span>
                    </div>
                    @endif
                    @if($property->parking !== 'none')
                    <div>
                        <span class="text-gray-400 block">Parking</span>
                        <span class="font-bold text-gray-800 capitalize">{{ $property->parking }}</span>
                    </div>
                    @endif
                    @if($property->property_age)
                    <div>
                        <span class="text-gray-400 block">Age of Property</span>
                        <span class="font-bold text-gray-800">{{ $property->property_age }} Years</span>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Description -->
            <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Property Description</h3>
                <p class="text-gray-600 text-sm leading-relaxed whitespace-pre-line">{{ $property->description }}</p>
            </div>

            <!-- Amenities -->
            @if($property->amenities->count() > 0)
                <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Amenities Available</h3>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                        @foreach($property->amenities as $amenity)
                            <div class="flex items-center space-x-2 text-sm text-gray-700">
                                <svg class="h-5 w-5 text-green-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                <span>{{ $amenity->name }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Map View (Simulated Google Maps) -->
            <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Location Map</h3>
                <div class="h-64 bg-gray-200 rounded-xl overflow-hidden relative shadow-inner">
                    <!-- Since Google Maps requires API, we load a standard iframe simulator with coordinates or a nice static preview -->
                    @if($property->latitude && $property->longitude)
                        <iframe width="100%" height="100%" frameborder="0" style="border:0;" allowfullscreen src="https://maps.google.com/maps?q={{ $property->latitude }},{{ $property->longitude }}&hl=es;z=14&output=embed"></iframe>
                    @else
                        <iframe width="100%" height="100%" frameborder="0" style="border:0;" allowfullscreen src="https://maps.google.com/maps?q=Patiala,%20Punjab,%20India&hl=es;z=14&output=embed"></iframe>
                    @endif
                </div>
            </div>

            <!-- Interactive EMI Calculator -->
            <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
                <h3 class="text-lg font-bold text-gray-900 mb-4">EMI Calculator</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-center">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs text-gray-400 font-bold uppercase mb-1">Loan Amount (₹)</label>
                            <input type="range" id="emi-amount" min="100000" max="10000000" step="50000" value="{{ min(max((int)$property->price, 100000), 10000000) }}" class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer accent-red-600" oninput="calculateEMI()">
                            <div class="flex justify-between text-xs text-gray-500 font-semibold mt-1">
                                <span>₹1 Lakh</span>
                                <span class="text-red-600 font-bold" id="lbl-amount">₹{{ number_format(min(max((int)$property->price, 100000), 10000000)) }}</span>
                                <span>₹1 Crore</span>
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs text-gray-400 font-bold uppercase mb-1">Interest Rate (%)</label>
                            <input type="range" id="emi-rate" min="5" max="15" step="0.1" value="8.5" class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer accent-red-600" oninput="calculateEMI()">
                            <div class="flex justify-between text-xs text-gray-500 font-semibold mt-1">
                                <span>5%</span>
                                <span class="text-red-600 font-bold" id="lbl-rate">8.5%</span>
                                <span>15%</span>
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs text-gray-400 font-bold uppercase mb-1">Loan Tenure (Years)</label>
                            <input type="range" id="emi-tenure" min="5" max="30" step="1" value="20" class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer accent-red-600" oninput="calculateEMI()">
                            <div class="flex justify-between text-xs text-gray-500 font-semibold mt-1">
                                <span>5 Yrs</span>
                                <span class="text-red-600 font-bold" id="lbl-tenure">20 Yrs</span>
                                <span>30 Yrs</span>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 p-6 rounded-2xl border text-center">
                        <span class="text-gray-400 text-xs font-bold uppercase tracking-wider block">Estimated Monthly Payment</span>
                        <span class="text-3xl font-black text-red-600 block mt-2" id="emi-result">₹0</span>
                        <p class="text-xs text-gray-400 mt-2">Principal + Interest calculation. Final bank loan rates may vary.</p>
                    </div>
                </div>
            </div>

            <!-- Javascript for EMI -->
            <script>
                function calculateEMI() {
                    const amount = parseFloat(document.getElementById('emi-amount').value);
                    const rate = parseFloat(document.getElementById('emi-rate').value) / 12 / 100;
                    const tenure = parseInt(document.getElementById('emi-tenure').value) * 12;

                    // Update Labels
                    document.getElementById('lbl-amount').innerText = '₹' + amount.toLocaleString('en-IN');
                    document.getElementById('lbl-rate').innerText = rate * 12 * 100 + '%';
                    document.getElementById('lbl-tenure').innerText = (tenure / 12) + ' Yrs';

                    // EMI Formula: P * r * (1 + r)^n / ((1 + r)^n - 1)
                    let emi = (amount * rate * Math.pow(1 + rate, tenure)) / (Math.pow(1 + rate, tenure) - 1);
                    
                    if (isNaN(emi) || !isFinite(emi)) {
                        emi = 0;
                    }

                    document.getElementById('emi-result').innerText = '₹' + Math.round(emi).toLocaleString('en-IN') + '/mo';
                }
                
                // Initial calculation
                window.onload = function() {
                    calculateEMI();
                };
            </script>
        </div>

        <!-- Right: Inquiry Form & Actions -->
        <div class="w-full lg:w-1/3 space-y-6">
            
            <!-- Quick Actions -->
            <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm space-y-4">
                <div class="flex justify-between items-center">
                    <span class="text-xs font-bold text-gray-400 uppercase">Save & Share</span>
                    
                    <div class="flex space-x-2">
                        <!-- Save Wishlist Button -->
                        <form action="{{ route('properties.save', $property->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="border p-2 rounded-lg hover:bg-gray-50 flex items-center justify-center group" title="Save Property">
                                @if(Auth::check() && Auth::user()->savedProperties()->where('property_id', $property->id)->exists())
                                    <svg class="h-5 w-5 text-red-600 fill-current" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                                @else
                                    <svg class="h-5 w-5 text-gray-400 group-hover:text-red-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                                @endif
                            </button>
                        </form>
                        
                        <!-- Share button -->
                        <button onclick="navigator.clipboard.writeText(window.location.href); alert('Link copied to clipboard!');" class="border p-2 rounded-lg hover:bg-gray-50 flex items-center justify-center text-gray-400 hover:text-blue-500" title="Copy Link">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 10.742l4.636-2.318m0 0a3 3 0 10-2.243-4.077L6.44 6.666m6.88 1.758a3 3 0 002.244-4.077L6.44 6.666m0 0a3 3 0 100 4.244l6.88 3.44m0 0a3 3 0 11-2.243-4.077l4.636 2.318m0 0a3 3 0 102.243 4.077L8.684 12.742"/></svg>
                        </button>
                    </div>
                </div>

                <!-- Call and WhatsApp Links -->
                <div class="grid grid-cols-2 gap-2 pt-2">
                    <a href="tel:{{ $property->user->mobile ?? '9999999999' }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 px-4 rounded-lg flex items-center justify-center space-x-2 text-sm shadow transition-colors">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.94.725l.548 2.2a1 1 0 01-.321.988l-1.305.98a10.582 10.582 0 004.872 4.872l.98-1.305a1 1 0 01.988-.321l2.2.548a1 1 0 01.725.94V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                        <span>Call Owner</span>
                    </a>
                    <a href="https://wa.me/91{{ $property->user->mobile ?? '9999999999' }}?text=Hi,%20I%20am%20interested%20in%20your%20property%20listed%20on%20PatialaProperty:%20{{ urlencode($property->title) }}" target="_blank" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2.5 px-4 rounded-lg flex items-center justify-center space-x-2 text-sm shadow transition-colors">
                        <span class="font-bold">WhatsApp</span>
                    </a>
                </div>
            </div>

            <!-- Contact/Lead Inquiry Form -->
            <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm space-y-4">
                <h4 class="text-base font-bold text-gray-900 border-b pb-2">Send Inquiry</h4>
                
                @auth
                    @if(Auth::id() === $property->user_id)
                        <p class="text-xs text-amber-600 bg-amber-50 p-3 rounded-lg">You are the owner of this property listing.</p>
                    @else
                        <form action="{{ route('leads.store', $property->id) }}" method="POST" class="space-y-4">
                            @csrf
                            <input type="hidden" name="source" value="contact_owner">
                            <input type="hidden" name="contact_type" value="in_app">
                            
                            <div>
                                <label class="block text-xs font-bold text-gray-400 uppercase mb-1">Your Message</label>
                                <textarea name="message" rows="3" class="w-full rounded-lg border-gray-200 text-sm" placeholder="Ask about price, possession date, or property coordinates..."></textarea>
                            </div>
                            <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-2.5 rounded-lg text-sm transition-colors">Send Message</button>
                        </form>
                    @endif
                @else
                    <p class="text-xs text-gray-500">Please <a href="{{ route('login') }}" class="text-red-600 font-bold hover:underline">login</a> or <a href="{{ route('otp.login.form') }}" class="text-red-600 font-bold hover:underline">OTP Register</a> to submit leads directly.</p>
                @endauth
            </div>

            <!-- Schedule Property Visit Form -->
            <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm space-y-4">
                <h4 class="text-base font-bold text-gray-900 border-b pb-2">Schedule Site Visit</h4>
                
                @auth
                    @if(Auth::id() === $property->user_id)
                        <p class="text-xs text-amber-600 bg-amber-50 p-3 rounded-lg">You are the owner of this property listing.</p>
                    @else
                        <form action="{{ route('visits.store', $property->id) }}" method="POST" class="space-y-4">
                            @csrf
                            <div>
                                <label class="block text-xs font-bold text-gray-400 uppercase mb-1">Preferred Date</label>
                                <input type="date" name="visit_date" min="{{ date('Y-m-d') }}" class="w-full rounded-lg border-gray-200 text-sm" required>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-400 uppercase mb-1">Preferred Time Slot</label>
                                <select name="time_slot" class="w-full rounded-lg border-gray-200 text-sm" required>
                                    <option value="10:00 AM - 12:00 PM">10:00 AM - 12:00 PM (Morning)</option>
                                    <option value="12:00 PM - 02:00 PM">12:00 PM - 02:00 PM (Noon)</option>
                                    <option value="02:00 PM - 04:00 PM">02:00 PM - 04:00 PM (Afternoon)</option>
                                    <option value="04:00 PM - 06:00 PM">04:00 PM - 06:00 PM (Evening)</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-400 uppercase mb-1">Inquiry / Notes</label>
                                <textarea name="user_notes" rows="2" class="w-full rounded-lg border-gray-200 text-sm" placeholder="Any specific requirements..."></textarea>
                            </div>
                            <button type="submit" class="w-full bg-amber-500 hover:bg-amber-600 text-white font-bold py-2.5 rounded-lg text-sm transition-colors">Book Visit Slot</button>
                        </form>
                    @endif
                @else
                    <p class="text-xs text-gray-500">Please <a href="{{ route('login') }}" class="text-red-600 font-bold hover:underline">login</a> or <a href="{{ route('otp.login.form') }}" class="text-red-600 font-bold hover:underline">OTP Register</a> to request scheduled property visits.</p>
                @endauth
            </div>
            
        </div>

    </div>
</div>
@endsection
