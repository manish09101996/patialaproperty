@extends('layouts.public')

@section('title', 'Buyer Dashboard | Patiala Property')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="border-b pb-6 mb-8 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-3xl font-black text-gray-900">Buyer Dashboard</h1>
            <p class="text-sm text-gray-400 mt-1">Manage your saved listings, scheduled visits, and sent inquiries.</p>
        </div>
        <div class="bg-white p-4 rounded-xl border flex items-center space-x-3">
            <div class="h-10 w-10 bg-red-600 text-white rounded-full flex items-center justify-center font-bold text-lg">
                {{ substr(Auth::user()->name, 0, 1) }}
            </div>
            <div>
                <span class="font-bold block text-gray-800">{{ Auth::user()->name }}</span>
                <span class="text-xs text-gray-400 capitalize">Role: <strong>Buyer / Tenant</strong></span>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Saved / Wishlist Properties -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
                <h3 class="text-lg font-bold text-gray-900 border-b pb-3 mb-4 flex items-center justify-between">
                    <span>Saved Properties (Wishlist)</span>
                    <span class="bg-gray-100 text-gray-600 text-xs px-2 py-0.5 rounded-full font-semibold">{{ $savedProperties->count() }}</span>
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @forelse($savedProperties as $saved)
                        @php $prop = $saved->property; @endphp
                        <div class="border rounded-xl p-3 flex space-x-3 hover:shadow-sm transition-shadow relative">
                            <div class="h-16 w-20 bg-gray-100 rounded-lg overflow-hidden flex-shrink-0">
                                @if($prop->primaryImage)
                                    <img src="{{ asset('storage/' . $prop->primaryImage->file_path) }}" class="h-full w-full object-cover">
                                @else
                                    <div class="w-full h-full bg-gray-200"></div>
                                @endif
                            </div>
                            <div class="flex-grow min-w-0">
                                <h4 class="text-sm font-bold text-gray-900 line-clamp-1"><a href="{{ route('properties.show', $prop->slug) }}" class="hover:underline">{{ $prop->title }}</a></h4>
                                <p class="text-xs text-gray-400 mt-0.5">{{ $prop->area->name }}, Patiala</p>
                                <span class="text-sm font-black text-gray-900 block mt-1">₹{{ number_format($prop->price) }}</span>
                            </div>
                            
                            <form action="{{ route('properties.save', $prop->id) }}" method="POST" class="absolute top-2 right-2">
                                @csrf
                                <button type="submit" class="text-red-500 hover:text-gray-400">
                                    <svg class="h-4 w-4 fill-current" viewBox="0 0 20 20"><path d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z"/></svg>
                                </button>
                            </form>
                        </div>
                    @empty
                        <div class="col-span-2 text-center text-gray-500 py-8 text-xs">You haven't saved any property listings yet.</div>
                    @endforelse
                </div>
            </div>

            <!-- Inquiries / Leads Sent -->
            <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
                <h3 class="text-lg font-bold text-gray-900 border-b pb-3 mb-4">Sent Inquiries</h3>
                
                <div class="space-y-4">
                    @forelse($myLeads as $lead)
                        <div class="border rounded-xl p-4 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 text-xs">
                            <div>
                                <span class="bg-red-50 text-red-700 text-[10px] px-2 py-0.5 rounded font-bold uppercase">{{ $lead->source }}</span>
                                <h4 class="text-sm font-bold text-gray-900 mt-1">Inquiry for: <a href="{{ route('properties.show', $lead->property->slug) }}" class="hover:underline text-blue-600">{{ $lead->property->title }}</a></h4>
                                <p class="text-gray-400 mt-1">Owner: <strong>{{ $lead->owner->name }}</strong> ({{ $lead->owner->mobile }})</p>
                                <p class="text-gray-600 mt-2 bg-gray-50 p-2.5 rounded-lg border border-dashed">"{{ $lead->message }}"</p>
                            </div>
                            <div class="text-right flex-shrink-0">
                                <span class="bg-amber-100 text-amber-800 text-[10px] px-2.5 py-1 rounded-full font-bold uppercase tracking-wider">{{ $lead->status }}</span>
                                <span class="text-gray-400 block mt-1.5">{{ $lead->created_at->format('d M, Y') }}</span>
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-gray-500 py-8 text-xs">You haven't sent any contact inquiries yet.</div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Right: Scheduled Site Visits -->
        <div class="space-y-6">
            <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
                <h3 class="text-lg font-bold text-gray-900 border-b pb-3 mb-4">Scheduled Property Visits</h3>
                
                <div class="space-y-4">
                    @forelse($myVisits as $visit)
                        <div class="border rounded-xl p-4 space-y-3 text-xs">
                            <div class="flex justify-between items-start gap-2">
                                <div>
                                    <h4 class="font-bold text-gray-900"><a href="{{ route('properties.show', $visit->property->slug) }}" class="hover:underline text-blue-600">{{ $visit->property->title }}</a></h4>
                                    <p class="text-gray-400 mt-0.5">{{ $visit->property->area->name }}, Patiala</p>
                                </div>
                                <span class="bg-amber-100 text-amber-800 text-[10px] px-2 py-0.5 rounded font-bold uppercase tracking-wider">{{ $visit->status }}</span>
                            </div>

                            <div class="bg-gray-50 p-2.5 rounded-lg space-y-1">
                                <div class="flex justify-between">
                                    <span class="text-gray-400">Date:</span>
                                    <span class="font-bold text-gray-800">{{ $visit->visit_date->format('d M, Y') }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-400">Slot:</span>
                                    <span class="font-bold text-gray-800">{{ $visit->time_slot }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-400">Owner Mobile:</span>
                                    <span class="font-bold text-gray-800">{{ $visit->property->user->mobile }}</span>
                                </div>
                            </div>

                            @if($visit->owner_notes)
                                <div class="text-[10px] text-gray-600 bg-red-50 p-2 rounded">
                                    <strong>Owner Notes:</strong> {{ $visit->owner_notes }}
                                </div>
                            @endif

                            @if($visit->status === 'pending')
                                <form action="{{ route('visits.status', $visit->id) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="status" value="cancelled">
                                    <button type="submit" class="w-full bg-red-50 hover:bg-red-100 text-red-700 py-1.5 rounded font-bold text-xs transition-colors">Cancel Visit Request</button>
                                </form>
                            @endif
                        </div>
                    @empty
                        <div class="text-center text-gray-500 py-8 text-xs">No site visits scheduled yet.</div>
                    @endforelse
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
