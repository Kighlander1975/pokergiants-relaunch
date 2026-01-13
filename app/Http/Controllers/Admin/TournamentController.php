<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Location;
use App\Models\Tournament;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;

class TournamentController extends Controller
{
    public function index()
    {
        $tournaments = Tournament::with('location')->latest()->paginate(20);
        return view('admin.tournaments.index', compact('tournaments'));
    }

    public function create()
    {
        $locations = Location::active()->orderBy('name')->get();
        return view('admin.tournaments.create', compact('locations'));
    }

    public function store(Request $request)
    {
        $data = $this->validateTournament($request);
        Tournament::create($data);

        return redirect()->route('admin.tournaments.index')->with('success', 'Turnier gespeichert.');
    }

    public function edit(Tournament $tournament)
    {
        $locations = Location::active()->orderBy('name')->get();

        return view('admin.tournaments.edit', compact('tournament', 'locations'));
    }

    public function update(Request $request, Tournament $tournament)
    {
        $data = $this->validateTournament($request);
        $tournament->update($data);

        return redirect()->route('admin.tournaments.index')->with('success', 'Turnier aktualisiert.');
    }

    public function destroy(Tournament $tournament)
    {
        $tournament->delete();

        return redirect()->route('admin.tournaments.index')->with('success', 'Turnier archiviert.');
    }

    private function validateTournament(Request $request): array
    {
        $attributes = $request->validate([
            'name' => 'required|string|max:255',
            'location_id' => 'required|exists:locations,id',
            'starts_at' => 'required|date',
            'registration_info' => 'nullable|string',
            'description' => 'required|string',
            'is_ranglistenturnier' => 'sometimes|boolean',
            'is_published' => 'sometimes|boolean',
            'is_registration_open' => 'sometimes|boolean',
        ]);

        $attributes['is_ranglistenturnier'] = $request->boolean('is_ranglistenturnier', true);
        $attributes['is_published'] = $request->boolean('is_published');
        $attributes['is_registration_open'] = $request->boolean('is_registration_open');
        $attributes['prices'] = $this->compilePrices($request->input('prices', []));

        return $attributes;
    }

    private function compilePrices(array $prices): array
    {
        $compiled = [];
        $gapFound = false;

        foreach ($prices as $value) {
            $trimmed = trim((string) $value);

            if ($trimmed === '') {
                $gapFound = true;
                continue;
            }

            if ($gapFound) {
                throw ValidationException::withMessages(['prices' => 'Bitte füllen Sie die Preisplatzierungen ohne Lücken auf.']);
            }

            $compiled[] = $trimmed;
        }

        if (empty($compiled)) {
            throw ValidationException::withMessages(['prices' => 'Mindestens ein Preis muss angegeben werden.']);
        }

        return $compiled;
    }
}
