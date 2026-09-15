<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\Property;
use App\Models\PropertyLead;
use App\Models\PropertyVisit;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->isAdmin()) {
            return $this->adminDashboard();
        }

        if (in_array($user->role, ['owner', 'agent'])) {
            return $this->ownerDashboard();
        }

        // Default Buyer/Tenant Dashboard
        return $this->buyerDashboard();
    }

    protected function adminDashboard()
    {
        $stats = [
            'total_users' => User::count(),
            'total_properties' => Property::count(),
            'pending_approvals' => Property::where('status', 'pending_review')->count(),
            'total_leads' => PropertyLead::count(),
            'total_visits' => PropertyVisit::count(),
        ];

        // Fetch properties requiring approval
        $pendingProperties = Property::where('status', 'pending_review')
            ->with(['user', 'area', 'category', 'type'])
            ->latest()
            ->get();

        // Fetch all properties paginated
        $properties = Property::with(['user', 'area', 'category', 'type'])
            ->latest()
            ->paginate(10, ['*'], 'properties_page');

        // Fetch all users
        $users = User::latest()->paginate(10, ['*'], 'users_page');

        // Fetch all areas for locality management
        $areas = Area::with('city')->orderBy('name')->get();

        return view('dashboard.admin', compact('stats', 'pendingProperties', 'properties', 'users', 'areas'));
    }

    protected function ownerDashboard()
    {
        $user = Auth::user();

        // Calculations for owner metrics
        $propertiesQuery = Property::where('user_id', $user->id);
        $myPropertyIds = $propertiesQuery->pluck('id');

        $stats = [
            'total_properties' => $propertiesQuery->count(),
            'active_listings' => Property::where('user_id', $user->id)->where('status', 'published')->count(),
            'pending_review' => Property::where('user_id', $user->id)->where('status', 'pending_review')->count(),
            'total_views' => $propertiesQuery->sum('views_count'),
            'total_leads' => PropertyLead::whereIn('property_id', $myPropertyIds)->count(),
            'scheduled_visits' => PropertyVisit::whereIn('property_id', $myPropertyIds)->count(),
        ];

        // Fetch list of owner's properties
        $myProperties = Property::where('user_id', $user->id)
            ->with(['area', 'category', 'type', 'primaryImage'])
            ->latest()
            ->paginate(5, ['*'], 'properties_page');

        // Fetch leads received on owner's properties
        $leads = PropertyLead::whereIn('property_id', $myPropertyIds)
            ->with(['property', 'buyer'])
            ->latest()
            ->paginate(5, ['*'], 'leads_page');

        // Fetch visits scheduled on owner's properties
        $visits = PropertyVisit::whereIn('property_id', $myPropertyIds)
            ->with(['property', 'buyer', 'lead'])
            ->latest()
            ->paginate(5, ['*'], 'visits_page');

        return view('dashboard.owner', compact('stats', 'myProperties', 'leads', 'visits'));
    }

    protected function buyerDashboard()
    {
        $user = Auth::user();

        // Fetch saved properties
        $savedProperties = $user->savedProperties()
            ->with(['property.area', 'property.category', 'property.type', 'property.primaryImage'])
            ->latest()
            ->get();

        // Fetch leads sent by buyer
        $myLeads = $user->leadsAsBuyer()
            ->with(['property', 'owner'])
            ->latest()
            ->get();

        // Fetch visits scheduled by buyer
        $myVisits = $user->visits()
            ->with(['property.area', 'property.user'])
            ->latest()
            ->get();

        return view('dashboard.buyer', compact('savedProperties', 'myLeads', 'myVisits'));
    }

    /**
     * Admin workflow to approve, reject or request changes on properties
     */
    public function approveProperty(Request $request, Property $property)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }

        $request->validate([
            'action' => ['required', 'in:approve,reject,request_changes'],
            'admin_notes' => ['nullable', 'string', 'max:500'],
        ]);

        $status = 'pending_review';
        $msg = 'Property status updated.';

        if ($request->action === 'approve') {
            $status = 'published';
            $msg = 'Property has been approved and published.';
        } elseif ($request->action === 'reject') {
            $status = 'rejected';
            $msg = 'Property has been rejected.';
        } elseif ($request->action === 'request_changes') {
            $status = 'changes_requested';
            $msg = 'Changes requested from the owner.';
        }

        $property->update([
            'status' => $status,
            'admin_notes' => $request->admin_notes,
        ]);

        return back()->with('status', $msg);
    }
}
