<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\PropertyLead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LeadController extends Controller
{
    public function store(Request $request, Property $property)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please login to contact the owner.');
        }

        $request->validate([
            'source' => ['required', 'in:contact_owner,call,whatsapp,chat,schedule_visit'],
            'contact_type' => ['required', 'in:phone,email,whatsapp,in_app'],
            'message' => ['nullable', 'string', 'max:500'],
        ]);

        // Owners shouldn't generate leads for their own properties
        if (Auth::id() === $property->user_id) {
            return back()->with('error', 'You cannot submit an inquiry for your own property.');
        }

        // Create the lead
        $lead = PropertyLead::create([
            'property_id' => $property->id,
            'owner_id' => $property->user_id,
            'user_id' => Auth::id(),
            'source' => $request->source,
            'contact_type' => $request->contact_type,
            'status' => 'new',
            'message' => $request->message ?? "Hi, I am interested in your property '{$property->title}'. Please contact me.",
        ]);

        return back()->with('status', 'Your inquiry has been sent to the owner. They will contact you shortly.');
    }

    public function updateStatus(Request $request, PropertyLead $lead)
    {
        $this->authorize('update', $lead);

        $request->validate([
            'status' => ['required', 'in:new,contacted,interested,visit_scheduled,negotiation,closed,lost'],
        ]);

        $lead->update([
            'status' => $request->status,
        ]);

        return back()->with('status', "Lead status updated to: " . ucfirst($request->status));
    }
}
