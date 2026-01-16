<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Headline;
use App\Models\Section;
use App\Models\Widget;

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

        // Load widgets for the home section
        $widgets = Widget::where('section_id', Section::where('section_name', 'home')->value('id'))
            ->ordered()
            ->get();

        // Generate dynamic CSS for widget widths
        $dynamic_css = '';
        foreach ($widgets as $widget) {
            if ($widget->widget_type === 'card' && $widget->width_percentage) {
                $dynamic_css .= ".card-{$widget->id} { flex: 0 0 {$widget->width_percentage}% !important; max-width: {$widget->width_percentage}% !important; }\n";
            }
        }

        return view('layouts.frontend.pages.home', compact('headline', 'widgets', 'dynamic_css'));
    }
}
