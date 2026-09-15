<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\PropertyLead;
use App\Models\PropertyVisit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VisitController extends Controller
{
    public function store(Request $request, Property $property)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please login to schedule a visit.');
        }

        $request->validate([
            'visit_date' => ['required', 'date', 'after_or_equal:today'],
            'time_slot' => ['required', 'string', 'max:100'],
            'user_notes' => ['nullable', 'string', 'max:500'],
        ]);

        if (Auth::id() === $property->user_id) {
            return back()->with('error', 'You cannot schedule a visit for your own property.');
        }

        // 1. Automatically create a Lead first
        $lead = PropertyLead::create([
            'property_id' => $property->id,
            'owner_id' => $property->user_id,
            'user_id' => Auth::id(),
            'source' => 'schedule_visit',
            'contact_type' => 'in_app',
            'status' => 'visit_scheduled',
            'message' => "Requested visit on {$request->visit_date} at {$request->time_slot}. Notes: " . ($request->user_notes ?? 'No notes provided.'),
        ]);

        // 2. Create the Visit Request
        PropertyVisit::create([
            'property_id' => $property->id,
            'lead_id' => $lead->id,
            'user_id' => Auth::id(),
            'visit_date' => $request->visit_date,
            'time_slot' => $request->time_slot,
            'status' => 'pending',
            'user_notes' => $request->user_notes,
        ]);

        return back()->with('status', 'Your visit request has been sent to the owner. They will review and confirm it.');
    }

    public function updateStatus(Request $request, PropertyVisit $visit)
    {
        $this->authorize('update', $visit);

        $request->validate([
            'status' => ['required', 'in:pending,confirmed,rescheduled,completed,cancelled,rejected'],
            'owner_notes' => ['nullable', 'string', 'max:500'],
            'visit_date' => ['nullable', 'required_if:status,rescheduled', 'date', 'after_or_equal:today'],
            'time_slot' => ['nullable', 'required_if:status,rescheduled', 'string', 'max:100'],
        ]);

        $data = ['status' => $request->status];

        if ($request->filled('owner_notes')) {
            $data['owner_notes'] = $request->owner_notes;
        }

        if ($request->status === 'rescheduled') {
            $data['visit_date'] = $request->visit_date;
            $data['time_slot'] = $request->time_slot;
            
            // If rescheduled, update the lead message too
            if ($visit->lead) {
                $visit->lead->update([
                    'message' => $visit->lead->message . "\n[Rescheduled to {$request->visit_date} at {$request->time_slot}]"
                ]);
            }
        }

        $visit->update($data);

        // Synchronize lead status if relevant
        if ($visit->lead) {
            if ($request->status === 'confirmed') {
                $visit->lead->update(['status' => 'visit_scheduled']);
            } elseif ($request->status === 'completed') {
                $visit->lead->update(['status' => 'interested']);
            }
        }

        return back()->with('status', 'Visit status updated successfully.');
    }
}
