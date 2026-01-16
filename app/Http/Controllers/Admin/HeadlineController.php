<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Headline;
use Illuminate\Http\Request;

class HeadlineController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $headlines = Headline::all();
        return view('admin.headlines.index', compact('headlines'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.headlines.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'section_id' => 'required|exists:sections,id',
            'headline_text' => 'required|string',
            'subline_text' => 'required|string',
        ]);

        Headline::create($data);

        return redirect()->route('admin.headlines.index')->with('success', 'Headline erstellt.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Headline $headline)
    {
        return view('admin.headlines.show', compact('headline'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Headline $headline)
    {
        return view('admin.headlines.edit', compact('headline'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Headline $headline)
    {
        $data = $request->validate([
            'headline_text' => 'required|string',
            'subline_text' => 'required|string',
        ]);

        $headline->update($data);

        return redirect()->route('admin.headlines.index')->with('success', 'Headline aktualisiert.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Headline $headline)
    {
        $headline->delete();

        return redirect()->route('admin.headlines.index')->with('success', 'Headline gelöscht.');
    }
}
