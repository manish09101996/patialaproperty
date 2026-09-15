<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Area;
use App\Models\City;
use Illuminate\Http\Request;

class LocalityManagementController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:100', 'unique:areas,name'],
            'pincode' => ['nullable', 'string', 'digits:6'],
        ]);

        $city = City::where('name', 'Patiala')->first();

        if (!$city) {
            return back()->with('error', 'Default city Patiala not seeded.');
        }

        Area::create([
            'city_id' => $city->id,
            'name' => $request->name,
            'pincode' => $request->pincode,
            'is_active' => true,
        ]);

        return back()->with('status', "Area '{$request->name}' added successfully.");
    }

    public function update(Request $request, Area $area)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:100', 'unique:areas,name,' . $area->id],
            'pincode' => ['nullable', 'string', 'digits:6'],
        ]);

        $area->update([
            'name' => $request->name,
            'pincode' => $request->pincode,
        ]);

        return back()->with('status', "Area updated successfully.");
    }

    public function toggle(Area $area)
    {
        $area->update([
            'is_active' => !$area->is_active
        ]);

        $status = $area->is_active ? 'enabled' : 'disabled';
        return back()->with('status', "Area has been {$status}.");
    }

    public function destroy(Area $area)
    {
        if ($area->properties()->exists()) {
            return back()->with('error', "Cannot delete area '{$area->name}' as there are active property listings associated with it.");
        }

        $area->delete();
        return back()->with('status', "Area deleted successfully.");
    }
}
