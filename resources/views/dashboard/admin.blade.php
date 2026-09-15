@extends('layouts.public')

@section('title', 'Admin Dashboard | Patiala Property')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="border-b pb-6 mb-8">
        <h1 class="text-3xl font-black text-gray-900">Admin Console</h1>
        <p class="text-sm text-gray-400 mt-1">Review property submissions, configure Patiala localities, and oversee user activity.</p>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-2 md:grid-cols-5 gap-6 mb-8">
        <div class="bg-white p-5 rounded-2xl border shadow-sm text-center">
            <span class="text-xs text-gray-400 font-bold uppercase tracking-wider block">Registered Users</span>
            <span class="text-2xl font-black text-gray-800 block mt-2">{{ $stats['total_users'] }}</span>
        </div>
        <div class="bg-white p-5 rounded-2xl border shadow-sm text-center">
            <span class="text-xs text-gray-400 font-bold uppercase tracking-wider block">Total Properties</span>
            <span class="text-2xl font-black text-gray-800 block mt-2">{{ $stats['total_properties'] }}</span>
        </div>
        <div class="bg-white p-5 rounded-2xl border shadow-sm text-center">
            <span class="text-xs text-gray-400 font-bold uppercase tracking-wider block">Pending Approvals</span>
            <span class="text-2xl font-black text-amber-500 block mt-2">{{ $stats['pending_approvals'] }}</span>
        </div>
        <div class="bg-white p-5 rounded-2xl border shadow-sm text-center">
            <span class="text-xs text-gray-400 font-bold uppercase tracking-wider block">Total Leads</span>
            <span class="text-2xl font-black text-purple-600 block mt-2">{{ $stats['total_leads'] }}</span>
        </div>
        <div class="bg-white p-5 rounded-2xl border shadow-sm text-center">
            <span class="text-xs text-gray-400 font-bold uppercase tracking-wider block">Visits Scheduled</span>
            <span class="text-2xl font-black text-red-600 block mt-2">{{ $stats['total_visits'] }}</span>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Left 2 Cols: Approvals & Master Listings -->
        <div class="lg:col-span-2 space-y-8">
            
            <!-- Property Approval Section -->
            <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
                <h3 class="text-lg font-bold text-gray-900 border-b pb-3 mb-6 flex items-center justify-between">
                    <span>Pending Property Approvals</span>
                    <span class="bg-amber-100 text-amber-800 text-xs px-2.5 py-0.5 rounded-full font-bold">{{ $pendingProperties->count() }}</span>
                </h3>

                <div class="space-y-6">
                    @forelse($pendingProperties as $prop)
                        <div class="border border-amber-200 rounded-xl p-5 bg-amber-50/20 space-y-4">
                            <div class="flex justify-between items-start gap-4">
                                <div>
                                    <span class="bg-amber-100 text-amber-800 text-[10px] px-2 py-0.5 rounded font-bold uppercase">{{ $prop->type->name }}</span>
                                    <h4 class="text-sm font-bold text-gray-900 mt-1"><a href="{{ route('properties.show', $prop->slug) }}" class="text-blue-600 hover:underline" target="_blank">{{ $prop->title }}</a></h4>
                                    <p class="text-xs text-gray-400 mt-0.5">{{ $prop->area->name }}, Patiala | Owner: <strong>{{ $prop->user->name }}</strong> ({{ $prop->user->mobile }})</p>
                                </div>
                                <div class="text-right">
                                    <span class="text-sm font-black text-gray-900 block">₹{{ number_format($prop->price) }}</span>
                                    <span class="text-[10px] text-gray-400">{{ $prop->created_at->diffForHumans() }}</span>
                                </div>
                            </div>
                            
                            <!-- Action Form -->
                            <form action="{{ route('admin.properties.approve', $prop->id) }}" method="POST" class="border-t pt-3 flex flex-col md:flex-row items-end gap-3 text-xs">
                                @csrf
                                <div class="w-full md:w-1/3">
                                    <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Approval Action</label>
                                    <select name="action" class="w-full rounded border-gray-300 text-xs py-1" required>
                                        <option value="approve">Approve & Publish</option>
                                        <option value="request_changes">Request Changes</option>
                                        <option value="reject">Reject</option>
                                    </select>
                                </div>
                                <div class="w-full md:w-2/3">
                                    <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Feedback Notes</label>
                                    <input type="text" name="admin_notes" placeholder="Reason for change request or rejection..." class="w-full rounded border-gray-300 text-xs py-1">
                                </div>
                                <button type="submit" class="bg-amber-500 hover:bg-amber-600 text-white font-bold px-4 py-1.5 rounded text-xs transition-colors whitespace-nowrap">Submit Decision</button>
                            </form>
                        </div>
                    @empty
                        <div class="text-center text-gray-500 py-6 text-xs bg-gray-50 rounded-xl">No property listings awaiting review. Nice job!</div>
                    @endforelse
                </div>
            </div>

            <!-- Master Properties Table -->
            <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <h3 class="text-lg font-bold text-gray-900 border-b pb-3 mb-6">All Property Listings</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-xs">
                        <thead>
                            <tr class="text-left font-bold text-gray-400 uppercase">
                                <th class="pb-3">Title</th>
                                <th class="pb-3">Locality</th>
                                <th class="pb-3">Owner</th>
                                <th class="pb-3">Price</th>
                                <th class="pb-3">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($properties as $prop)
                                <tr class="hover:bg-gray-50">
                                    <td class="py-3 font-bold text-gray-900"><a href="{{ route('properties.show', $prop->slug) }}" class="hover:underline" target="_blank">{{ Str::limit($prop->title, 35) }}</a></td>
                                    <td class="py-3 text-gray-500">{{ $prop->area->name }}</td>
                                    <td class="py-3 text-gray-500">{{ $prop->user->name }}</td>
                                    <td class="py-3 font-bold">₹{{ number_format($prop->price) }}</td>
                                    <td class="py-3">
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold capitalize {{ $prop->status === 'published' ? 'bg-green-100 text-green-800' : ($prop->status === 'pending_review' ? 'bg-amber-100 text-amber-800' : 'bg-red-100 text-red-800') }}">
                                            {{ $prop->status }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    {{ $properties->links() }}
                </div>
            </div>

        </div>

        <!-- Right Col: Locality Area Configuration Console -->
        <div class="space-y-6">
            
            <!-- Locality Manager -->
            <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
                <h3 class="text-lg font-bold text-gray-900 border-b pb-3 mb-6">Patiala Area Localities</h3>
                
                <!-- Add locality form -->
                <form action="{{ route('admin.areas.store') }}" method="POST" class="space-y-3 mb-6 bg-gray-50 p-4 rounded-xl border">
                    @csrf
                    <span class="text-xs font-bold text-gray-700 block">Add New Area / Locality</span>
                    <div>
                        <input type="text" name="name" placeholder="Locality Name (e.g. Lower Mall)" class="w-full rounded border-gray-300 text-xs py-1.5" required>
                    </div>
                    <div>
                        <input type="text" name="pincode" placeholder="Pincode (6 digits)" class="w-full rounded border-gray-300 text-xs py-1.5" maxlength="6">
                    </div>
                    <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-1.5 rounded text-xs transition-colors shadow">Add Locality</button>
                </form>

                <!-- List of areas -->
                <div class="space-y-3 max-h-96 overflow-y-auto">
                    @foreach($areas as $area)
                        <div class="flex items-center justify-between border rounded-lg p-3 text-xs bg-white">
                            <div>
                                <span class="font-bold text-gray-900" id="area-name-{{ $area->id }}">{{ $area->name }}</span>
                                <span class="text-gray-400 block" id="area-pin-{{ $area->id }}">Pincode: {{ $area->pincode ?? 'N/A' }}</span>
                            </div>
                            
                            <div class="flex space-x-1 items-center">
                                <form action="{{ route('admin.areas.toggle', $area->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="border py-1 px-2 rounded hover:bg-gray-50 {{ $area->is_active ? 'text-green-600 bg-green-50 border-green-200' : 'text-gray-400 bg-gray-50' }}">
                                        {{ $area->is_active ? 'Active' : 'Disabled' }}
                                    </button>
                                </form>

                                <form action="{{ route('admin.areas.destroy', $area->id) }}" method="POST" onsubmit="return confirm('Delete this area?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="border border-red-200 text-red-600 py-1 px-2 rounded hover:bg-red-50">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Master Users list -->
            <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
                <h3 class="text-lg font-bold text-gray-900 border-b pb-3 mb-6">User Accounts</h3>
                <div class="space-y-3">
                    @foreach($users as $user)
                        <div class="flex items-center space-x-3 text-xs border rounded-lg p-2.5">
                            <div class="h-8 w-8 bg-gray-200 rounded-full flex items-center justify-center font-bold capitalize">
                                {{ substr($user->name, 0, 1) }}
                            </div>
                            <div>
                                <span class="font-bold block text-gray-900">{{ $user->name }}</span>
                                <span class="text-gray-400 block capitalize">Role: {{ $user->role }} | {{ $user->mobile ?? $user->email }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="mt-4">
                    {{ $users->links() }}
                </div>
            </div>

        </div>

    </div>
</div>
@endsection
