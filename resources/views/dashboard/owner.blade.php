@extends('layouts.public')

@section('title', 'Owner Dashboard | Patiala Property')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="border-b pb-6 mb-8 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-3xl font-black text-gray-900">Owner / Agent Dashboard</h1>
            <p class="text-sm text-gray-400 mt-1">Manage your properties, track views, convert leads, and approve site visits in Patiala.</p>
        </div>
        <a href="{{ route('properties.create') }}" class="bg-red-600 hover:bg-red-700 text-white font-bold px-5 py-2.5 rounded-lg shadow-md transition-colors text-sm flex items-center space-x-2">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
            <span>Post New Property</span>
        </a>
    </div>

    <!-- Stat Cards -->
    <div class="grid grid-cols-2 md:grid-cols-6 gap-4 mb-8">
        <div class="bg-white p-5 rounded-2xl border shadow-sm text-center">
            <span class="text-xs text-gray-400 font-bold uppercase tracking-wider block">Properties</span>
            <span class="text-2xl font-black text-gray-800 block mt-2">{{ $stats['total_properties'] }}</span>
        </div>
        <div class="bg-white p-5 rounded-2xl border shadow-sm text-center">
            <span class="text-xs text-gray-400 font-bold uppercase tracking-wider block">Active</span>
            <span class="text-2xl font-black text-green-600 block mt-2">{{ $stats['active_listings'] }}</span>
        </div>
        <div class="bg-white p-5 rounded-2xl border shadow-sm text-center">
            <span class="text-xs text-gray-400 font-bold uppercase tracking-wider block">Pending Review</span>
            <span class="text-2xl font-black text-amber-500 block mt-2">{{ $stats['pending_review'] }}</span>
        </div>
        <div class="bg-white p-5 rounded-2xl border shadow-sm text-center">
            <span class="text-xs text-gray-400 font-bold uppercase tracking-wider block">Total Views</span>
            <span class="text-2xl font-black text-blue-600 block mt-2">{{ $stats['total_views'] }}</span>
        </div>
        <div class="bg-white p-5 rounded-2xl border shadow-sm text-center">
            <span class="text-xs text-gray-400 font-bold uppercase tracking-wider block">Total Leads</span>
            <span class="text-2xl font-black text-purple-600 block mt-2">{{ $stats['total_leads'] }}</span>
        </div>
        <div class="bg-white p-5 rounded-2xl border shadow-sm text-center">
            <span class="text-xs text-gray-400 font-bold uppercase tracking-wider block">Scheduled Visits</span>
            <span class="text-2xl font-black text-red-600 block mt-2">{{ $stats['scheduled_visits'] }}</span>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Left: Properties List -->
        <div class="lg:col-span-2 space-y-8">
            
            <!-- My Properties -->
            <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
                <h3 class="text-lg font-bold text-gray-900 border-b pb-3 mb-6">My Listed Properties</h3>
                
                <div class="space-y-4">
                    @forelse($myProperties as $prop)
                        <div class="border rounded-xl p-4 flex flex-col md:flex-row gap-4 items-start hover:shadow-sm transition-shadow">
                            <!-- Image -->
                            <div class="h-20 w-28 bg-gray-100 rounded-lg overflow-hidden flex-shrink-0">
                                @if($prop->primaryImage)
                                    <img src="{{ asset('storage/' . $prop->primaryImage->file_path) }}" class="h-full w-full object-cover">
                                @else
                                    <div class="w-full h-full bg-gray-200"></div>
                                @endif
                            </div>
                            
                            <!-- Content -->
                            <div class="flex-grow min-w-0">
                                <div class="flex items-center space-x-2">
                                    <span class="text-xs font-bold text-red-600 uppercase">{{ $prop->type->name }}</span>
                                    <span class="text-xs bg-gray-100 text-gray-700 px-2 py-0.5 rounded capitalize">{{ $prop->purpose }}</span>
                                </div>
                                <h4 class="text-sm font-bold text-gray-900 mt-1 line-clamp-1"><a href="{{ route('properties.show', $prop->slug) }}" class="hover:underline">{{ $prop->title }}</a></h4>
                                <p class="text-xs text-gray-400 mt-0.5">{{ $prop->area->name }}, Patiala</p>
                                <span class="text-sm font-black text-gray-800 mt-1.5 block">₹{{ number_format($prop->price) }}</span>
                            </div>

                            <!-- Actions -->
                            <div class="flex flex-row md:flex-col items-stretch justify-center gap-2 flex-shrink-0 w-full md:w-fit border-t md:border-t-0 pt-3 md:pt-0">
                                <span class="text-center text-[10px] font-bold uppercase py-1 px-2.5 rounded-full inline-block {{ $prop->status === 'published' ? 'bg-green-100 text-green-800' : ($prop->status === 'pending_review' ? 'bg-amber-100 text-amber-800' : 'bg-gray-100 text-gray-600') }}">
                                    {{ str_replace('_', ' ', $prop->status) }}
                                </span>
                                
                                <div class="flex space-x-2 mt-1">
                                    <a href="{{ route('properties.edit', $prop->id) }}" class="border text-center text-xs hover:bg-gray-50 text-gray-700 py-1.5 px-3 rounded-lg font-semibold flex-1">Edit</a>
                                    
                                    <form action="{{ route('properties.destroy', $prop->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this listing?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="border border-red-200 text-center text-xs hover:bg-red-50 text-red-600 py-1.5 px-3 rounded-lg font-semibold">Delete</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-gray-500 py-12 text-xs">You haven't listed any properties yet.</div>
                    @endforelse
                </div>

                <div class="mt-6">
                    {{ $myProperties->links() }}
                </div>
            </div>

            <!-- Leads Received -->
            <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
                <h3 class="text-lg font-bold text-gray-900 border-b pb-3 mb-6">Property Leads Received</h3>
                
                <div class="space-y-6">
                    @forelse($leads as $lead)
                        <div class="border rounded-xl p-4 space-y-4 hover:shadow-sm transition-shadow">
                            <div class="flex flex-wrap justify-between items-start gap-2 text-xs">
                                <div>
                                    <span class="bg-red-50 text-red-700 text-[10px] px-2 py-0.5 rounded font-bold uppercase">{{ $lead->source }}</span>
                                    <h4 class="text-sm font-bold text-gray-900 mt-1">Lead on: <a href="{{ route('properties.show', $lead->property->slug) }}" class="text-blue-600 hover:underline">{{ $lead->property->title }}</a></h4>
                                    <p class="text-gray-400 mt-1">Buyer: <strong>{{ $lead->buyer->name }}</strong> (Mobile: {{ $lead->buyer->mobile ?? 'N/A' }}, Email: {{ $lead->buyer->email ?? 'N/A' }})</p>
                                </div>
                                <div class="text-right">
                                    <span class="text-gray-400">{{ $lead->created_at->format('d M, Y') }}</span>
                                </div>
                            </div>

                            <p class="text-xs text-gray-600 bg-gray-50 p-2.5 rounded-lg border border-dashed">"{{ $lead->message }}"</p>

                            <!-- Lead Status Form -->
                            <form action="{{ route('leads.status', $lead->id) }}" method="POST" class="flex items-center space-x-2 pt-2 border-t text-xs">
                                @csrf
                                <span class="text-gray-400">Update Lead Status:</span>
                                <select name="status" class="rounded-lg border-gray-200 text-xs py-1" onchange="this.form.submit()">
                                    <option value="new" {{ $lead->status === 'new' ? 'selected' : '' }}>New</option>
                                    <option value="contacted" {{ $lead->status === 'contacted' ? 'selected' : '' }}>Contacted</option>
                                    <option value="interested" {{ $lead->status === 'interested' ? 'selected' : '' }}>Interested</option>
                                    <option value="visit_scheduled" {{ $lead->status === 'visit_scheduled' ? 'selected' : '' }}>Visit Scheduled</option>
                                    <option value="negotiation" {{ $lead->status === 'negotiation' ? 'selected' : '' }}>Negotiation</option>
                                    <option value="closed" {{ $lead->status === 'closed' ? 'selected' : '' }}>Closed (Sold/Rented)</option>
                                    <option value="lost" {{ $lead->status === 'lost' ? 'selected' : '' }}>Lost</option>
                                </select>
                            </form>
                        </div>
                    @empty
                        <div class="text-center text-gray-500 py-12 text-xs">No leads received yet. Make sure your listings are active and published.</div>
                    @endforelse
                </div>

                <div class="mt-6">
                    {{ $leads->links() }}
                </div>
            </div>

        </div>

        <!-- Right: Visit Approvals -->
        <div class="space-y-6">
            <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
                <h3 class="text-lg font-bold text-gray-900 border-b pb-3 mb-6">Site Visit Requests</h3>
                
                <div class="space-y-4">
                    @forelse($visits as $visit)
                        <div class="border rounded-xl p-4 space-y-3 text-xs">
                            <div class="flex justify-between items-start gap-2">
                                <div>
                                    <h4 class="font-bold text-gray-900"><a href="{{ route('properties.show', $visit->property->slug) }}" class="text-blue-600 hover:underline">{{ $visit->property->title }}</a></h4>
                                    <p class="text-gray-400 mt-0.5">Buyer: <strong>{{ $visit->buyer->name }}</strong> ({{ $visit->buyer->mobile }})</p>
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
                            </div>

                            @if($visit->user_notes)
                                <div class="bg-gray-100 p-2 rounded text-[10px] text-gray-600">
                                    <strong>Buyer Notes:</strong> "{{ $visit->user_notes }}"
                                </div>
                            @endif

                            <!-- Action buttons -->
                            @if($visit->status === 'pending')
                                <div class="flex space-x-2 pt-2">
                                    <form action="{{ route('visits.status', $visit->id) }}" method="POST" class="w-1/2">
                                        @csrf
                                        <input type="hidden" name="status" value="confirmed">
                                        <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-1.5 rounded transition-colors">Accept</button>
                                    </form>
                                    <form action="{{ route('visits.status', $visit->id) }}" method="POST" class="w-1/2">
                                        @csrf
                                        <input type="hidden" name="status" value="rejected">
                                        <button type="submit" class="w-full bg-red-50 hover:bg-red-100 text-red-700 border border-red-200 py-1.5 rounded transition-colors">Reject</button>
                                    </form>
                                </div>

                                <!-- Reschedule Form -->
                                <details class="mt-2 text-xs border rounded-lg p-2 bg-gray-50 cursor-pointer">
                                    <summary class="font-bold text-gray-700">Reschedule instead</summary>
                                    <form action="{{ route('visits.status', $visit->id) }}" method="POST" class="space-y-2 mt-2">
                                        @csrf
                                        <input type="hidden" name="status" value="rescheduled">
                                        <div>
                                            <label class="block text-[10px] font-bold text-gray-500 uppercase">New Date</label>
                                            <input type="date" name="visit_date" min="{{ date('Y-m-d') }}" class="w-full rounded border-gray-300 text-xs py-1" required>
                                        </div>
                                        <div>
                                            <label class="block text-[10px] font-bold text-gray-500 uppercase">New Time Slot</label>
                                            <input type="text" name="time_slot" placeholder="e.g. 11:00 AM - 01:00 PM" class="w-full rounded border-gray-300 text-xs py-1" required>
                                        </div>
                                        <div>
                                            <label class="block text-[10px] font-bold text-gray-500 uppercase">Reason / Notes</label>
                                            <input type="text" name="owner_notes" placeholder="Reason for rescheduling..." class="w-full rounded border-gray-300 text-xs py-1">
                                        </div>
                                        <button type="submit" class="w-full bg-amber-500 text-white py-1 rounded font-bold">Reschedule</button>
                                    </form>
                                </details>
                            @endif
                        </div>
                    @empty
                        <div class="text-center text-gray-500 py-8 text-xs">No visit requests received yet.</div>
                    @endforelse
                </div>

                <div class="mt-6">
                    {{ $visits->links() }}
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
