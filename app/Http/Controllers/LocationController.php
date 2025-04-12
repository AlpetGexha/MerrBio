<?php

namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function index()
    {
        $locations = Location::where('user_id', auth()->id())->get();
        return response()->json($locations);
    }

    public function store(Request $request)
    {
        $request->validate([
            'address' => 'required|string',
            'latitude' => 'nullable|string',
            'longitude' => 'nullable|string',
            'city' => 'nullable|string',
            'country' => 'nullable|string',
        ]);

        $location = Location::create([
            'user_id' => auth()->id(),
            'address' => $request->address,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'city' => $request->city,
            'country' => $request->country,
            'status' => 'active',
        ]);

        return response()->json(['message' => 'Location created successfully', 'location' => $location]);
    }

    public function show($id)
    {
        $location = Location::where('user_id', auth()->id())->findOrFail($id);
        return response()->json($location);
    }

    public function update(Request $request, $id)
    {
        $location = Location::where('user_id', auth()->id())->findOrFail($id);

        $request->validate([
            'address' => 'required|string',
            'latitude' => 'nullable|string',
            'longitude' => 'nullable|string',
            'city' => 'nullable|string',
            'country' => 'nullable|string',
        ]);

        $location->update($request->all());

        return response()->json(['message' => 'Location updated successfully', 'location' => $location]);
    }

    public function destroy($id)
    {
        $location = Location::where('user_id', auth()->id())->findOrFail($id);
        $location->delete();

        return response()->json(['message' => 'Location deleted successfully']);
    }
}
