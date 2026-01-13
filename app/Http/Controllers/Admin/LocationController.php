<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Location;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function index()
    {
        $locations = Location::latest()->paginate(20);
        return view('admin.locations.index', compact('locations'));
    }

    public function create()
    {
        return view('admin.locations.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'street' => 'required|string|max:255',
            'postal_city' => 'required|string|max:255',
            'notes' => 'required|string',
            'is_active' => 'boolean',
        ]);

        $attributes = $request->only(['name', 'street', 'postal_city', 'notes']);
        $attributes['is_active'] = $request->boolean('is_active');

        Location::create($attributes);

        return redirect()->route('admin.locations.index')->with('success', 'Spielstätte gespeichert.');
    }

    public function edit(Location $location)
    {
        return view('admin.locations.edit', compact('location'));
    }

    public function update(Request $request, Location $location)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'street' => 'required|string|max:255',
            'postal_city' => 'required|string|max:255',
            'notes' => 'required|string',
            'is_active' => 'boolean',
        ]);

        $attributes = $request->only(['name', 'street', 'postal_city', 'notes']);
        $attributes['is_active'] = $request->boolean('is_active');

        $location->update($attributes);

        return redirect()->route('admin.locations.index')->with('success', 'Spielstätte aktualisiert.');
    }

    public function destroy(Location $location)
    {
        $location->delete();

        return redirect()->route('admin.locations.index')->with('success', 'Spielstätte archiviert.');
    }
}
