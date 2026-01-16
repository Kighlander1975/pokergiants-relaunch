<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Section;
use App\Models\Headline;

class ViewController extends Controller
{
    public function index()
    {
        $sections = Section::with('headline')->get();
        return view('admin.views.index', compact('sections'));
    }

    // Temporarily disabled - will be replaced with widget system
    // public function home()
    // {
    //     $headline = Headline::where('section_id', Section::where('section_name', 'home')->value('id'))->first();
    //     return view('admin.views.home', compact('headline'));
    // }
}
