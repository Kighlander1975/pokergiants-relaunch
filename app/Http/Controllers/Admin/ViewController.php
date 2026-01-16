<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Headline;
use App\Models\Section;

class ViewController extends Controller
{
    public function index()
    {
        $sections = Section::with('headline')->get();
        return view('admin.views.index', compact('sections'));
    }

    public function home()
    {
        $headline = Headline::where('section_id', Section::where('section_name', 'home')->value('id'))->first();
        return view('admin.views.home', compact('headline'));
    }

    public function news()
    {
        $headline = Headline::where('section_id', Section::where('section_name', 'news')->value('id'))->first();
        return view('admin.views.news', compact('headline'));
    }

    public function tournaments()
    {
        $headline = Headline::where('section_id', Section::where('section_name', 'tournaments')->value('id'))->first();
        return view('admin.views.tournaments', compact('headline'));
    }
}