<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Section;
use App\Models\Headline;
use Illuminate\Http\Request;

class SectionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sections = Section::with('headline')->get();
        return view('admin.sections.index', compact('sections'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.sections.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'section_name' => 'required|string|max:255|unique:sections',
        ]);

        $section = Section::create($data);

        // Erstelle automatisch Headline
        Headline::create([
            'section_id' => $section->id,
            'headline_text' => '',
            'subline_text' => '',
        ]);

        return redirect()->route('admin.sections.index')->with('success', 'Section erstellt.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Section $section)
    {
        return view('admin.sections.show', compact('section'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Section $section)
    {
        return view('admin.sections.edit', compact('section'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Section $section)
    {
        $data = $request->validate([
            'section_name' => 'required|string|max:255|unique:sections,section_name,' . $section->id,
        ]);

        $section->update($data);

        return redirect()->route('admin.sections.index')->with('success', 'Section aktualisiert.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Section $section)
    {
        if ($section->section_name === 'home') {
            return redirect()->route('admin.sections.index')->with('error', 'Die Home-Section kann nicht gelöscht werden.');
        }

        $section->delete(); // Cascade löscht Headline

        return redirect()->route('admin.sections.index')->with('success', 'Section gelöscht.');
    }
}
