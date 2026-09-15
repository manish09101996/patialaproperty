<!-- Property Card Component -->
<div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden hover:shadow-xl hover:-translate-y-1 transition-all relative group">
    
    <!-- Image -->
    <div class="h-52 bg-slate-200 overflow-hidden relative">
        @if($prop->is_featured)
            <span class="absolute top-4 left-4 bg-amber-500 text-white text-[10px] px-2.5 py-1 rounded-full font-black uppercase tracking-wider z-10 shadow-sm">Featured</span>
        @endif
        <span class="absolute top-4 right-4 bg-blue-600 text-white text-[10px] px-2.5 py-1 rounded-full font-bold uppercase tracking-wider z-10 shadow-sm">{{ ucfirst($prop->purpose === 'sell' ? 'For Sale' : 'For Rent') }}</span>
        
        @if($prop->primaryImage)
            <img src="{{ asset('storage/' . $prop->primaryImage->file_path) }}" alt="{{ $prop->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
        @else
            <div class="w-full h-full flex items-center justify-center bg-slate-350 text-slate-500">
                <span class="text-xs font-bold font-mono">No Image</span>
            </div>
        @endif

        <!-- Favorite button -->
        <form action="{{ route('properties.save', $prop->id) }}" method="POST" class="absolute bottom-4 right-4 z-10">
            @csrf
            <button type="submit" class="h-9 w-9 bg-white/90 backdrop-blur-sm rounded-full flex items-center justify-center shadow-md hover:bg-white text-slate-400 hover:text-red-500 transition-colors">
                @if(Auth::check() && Auth::user()->savedProperties()->where('property_id', $prop->id)->exists())
                    <svg class="h-5 w-5 text-red-500 fill-current" viewBox="0 0 20 20"><path d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z"/></svg>
                @else
                    <svg class="h-5 w-5 text-slate-400 fill-none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                @endif
            </button>
        </form>
    </div>

    <!-- Details -->
    <div class="p-6 space-y-3.5">
        <div class="flex items-center space-x-1.5 text-[10px] font-black text-blue-600 uppercase tracking-widest">
            <span>{{ $prop->type->name }}</span>
            <span>•</span>
            <span class="text-green-600">✓ Verified</span>
        </div>

        <h3 class="text-base font-black text-slate-900 line-clamp-1"><a href="{{ route('properties.show', $prop->slug) }}" class="hover:underline">{{ $prop->title }}</a></h3>
        
        <p class="text-xs text-slate-400 flex items-center">
            <svg class="h-4 w-4 mr-1 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            {{ $prop->area->name }}, Patiala
        </p>

        <!-- Price block -->
        <div class="border-t border-slate-100 pt-4 flex justify-between items-baseline">
            <span class="text-lg font-black text-slate-900">
                @if($prop->price_type === 'contact_for_price')
                    Contact Owner
                @else
                    ₹{{ number_format($prop->price) }}
                    @if($prop->purpose === 'rent')
                        <span class="text-xs text-slate-400 font-normal">/ mo</span>
                    @endif
                @endif
            </span>
            <span class="text-[11px] text-slate-400 font-medium">{{ $prop->property_area }} {{ str_replace('_', ' ', $prop->area_unit) }}</span>
        </div>

        <!-- Bedroom & specs grid -->
        @if($prop->bedrooms || $prop->bathrooms)
            <div class="flex space-x-4 text-[11px] text-slate-500 bg-slate-50 p-2.5 rounded-xl justify-around font-semibold">
                @if($prop->bedrooms)
                    <span class="flex items-center space-x-1">
                        <span>🛏</span>
                        <span>{{ $prop->bedrooms }} Beds</span>
                    </span>
                @endif
                @if($prop->bathrooms)
                    <span class="flex items-center space-x-1">
                        <span>🛁</span>
                        <span>{{ $prop->bathrooms }} Baths</span>
                    </span>
                @endif
                @if($prop->furnishing)
                    <span class="capitalize">{{ str_replace('-', ' ', $prop->furnishing) }}</span>
                @endif
            </div>
        @endif
    </div>
</div>
