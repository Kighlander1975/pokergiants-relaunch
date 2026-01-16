<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Headline;
use App\Models\Section;

class HomeController extends Controller
{
    public function index()
    {
        $headline = Headline::where('section_id', Section::where('section_name', 'home')->value('id'))->first();

        // Parse BB-Code to HTML if headline exists
        if ($headline) {
            $headline->headline_text = convertBBToHtml($headline->headline_text);
            $headline->subline_text = convertBBToHtml($headline->subline_text);
        }

        return view('layouts.frontend.pages.home', compact('headline'));
    }
}
