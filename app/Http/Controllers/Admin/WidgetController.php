<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Section;

class WidgetController extends Controller
{
    public function create($section)
    {
        // Validate that section exists
        $sectionModel = Section::where('section_name', $section)->first();
        if (!$sectionModel) {
            abort(404, 'Section not found');
        }

        return view('admin.widgets.create', compact('sectionModel'));
    }
}
