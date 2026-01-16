<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Section;
use App\Models\Widget;

class WidgetController extends Controller
{
    /**
     * Display a listing of widgets for a section.
     */
    public function index($section)
    {
        $sectionModel = Section::where('section_name', $section)->first();
        if (!$sectionModel) {
            abort(404, 'Section not found');
        }

        $widgets = $sectionModel->widgets()->ordered()->get();

        return view('admin.widgets.index', compact('sectionModel', 'widgets'));
    }

    /**
     * Show the form for creating a new widget.
     */
    public function create($section)
    {
        $sectionModel = Section::where('section_name', $section)->first();
        if (!$sectionModel) {
            abort(404, 'Section not found');
        }

        return view('admin.widgets.create', compact('sectionModel'));
    }

    /**
     * Store a newly created widget in storage.
     */
    public function store(Request $request, $section)
    {
        $sectionModel = Section::where('section_name', $section)->first();
        if (!$sectionModel) {
            abort(404, 'Section not found');
        }

        $validated = $request->validate([
            'widget_type' => 'required|in:one-card,card',
            'width_percentage' => 'nullable|in:10,25,33,50,66,75,100',
            'center_on_small' => 'boolean',
            'content_html' => 'nullable|string|max:65535',
            'content_plain' => 'nullable|string|max:65535',
            'content_type' => 'required|in:html,plain,news,events,gallery,stats,custom',
            'internal_name' => 'required|string|max:255|unique:widgets,internal_name,NULL,id,section_id,' . $sectionModel->id,
        ]);

        // Get the next sort order
        $nextSortOrder = $sectionModel->widgets()->max('sort_order') + 1;

        $widget = new Widget($validated);
        $widget->section_id = $sectionModel->id;
        $widget->sort_order = $nextSortOrder;
        $widget->save();

        return redirect()->route('admin.widgets.index', $section)
            ->with('success', 'Widget wurde erfolgreich erstellt.');
    }

    /**
     * Display the specified widget.
     */
    public function show($section, Widget $widget)
    {
        // Ensure widget belongs to the correct section
        if ($widget->section->section_name !== $section) {
            abort(404);
        }

        return view('admin.widgets.show', compact('widget'));
    }

    /**
     * Show the form for editing the specified widget.
     */
    public function edit($section, Widget $widget)
    {
        // Ensure widget belongs to the correct section
        if ($widget->section->section_name !== $section) {
            abort(404);
        }

        return view('admin.widgets.edit', compact('widget'));
    }

    /**
     * Update the specified widget in storage.
     */
    public function update(Request $request, $section, Widget $widget)
    {
        // Ensure widget belongs to the correct section
        if ($widget->section->section_name !== $section) {
            abort(404);
        }

        $validated = $request->validate([
            'widget_type' => 'required|in:one-card,card',
            'width_percentage' => 'nullable|in:10,25,33,50,66,75,100',
            'center_on_small' => 'boolean',
            'content_html' => 'nullable|string|max:65535',
            'content_plain' => 'nullable|string|max:65535',
            'content_type' => 'required|in:html,plain,news,events,gallery,stats,custom',
            'internal_name' => 'required|string|max:255|unique:widgets,internal_name,' . $widget->id . ',id,section_id,' . $widget->section_id,
        ]);

        $widget->update($validated);

        return redirect()->route('admin.widgets.index', $section)
            ->with('success', 'Widget wurde erfolgreich aktualisiert.');
    }

    /**
     * Remove the specified widget from storage.
     */
    public function destroy($section, Widget $widget)
    {
        // Ensure widget belongs to the correct section
        if ($widget->section->section_name !== $section) {
            abort(404);
        }

        $widget->delete();

        return redirect()->route('admin.widgets.index', $section)
            ->with('success', 'Widget wurde erfolgreich gelöscht.');
    }

    /**
     * Render widgets HTML for a section (Debug endpoint).
     */
    public function render($section)
    {
        $sectionModel = Section::where('section_name', $section)->first();
        if (!$sectionModel) {
            abort(404, 'Section not found');
        }

        $widgets = $sectionModel->widgets()->ordered()->get();

        $html = '';
        foreach ($widgets as $widget) {
            $html .= '<div class="' . $widget->css_classes . '">' . "\n";
            $html .= $widget->content_html . "\n";
            $html .= '</div>' . "\n";
        }

        return response($html)->header('Content-Type', 'text/plain');
    }

    /**
     * Move widget up in sort order.
     */
    public function moveUp($section, Widget $widget)
    {
        $sectionModel = Section::where('section_name', $section)->first();
        if (!$sectionModel) {
            abort(404, 'Section not found');
        }

        // Find the previous widget in sort order
        $previousWidget = $sectionModel->widgets()
            ->where('sort_order', '<', $widget->sort_order)
            ->orderBy('sort_order', 'desc')
            ->first();

        if ($previousWidget) {
            // Swap sort orders
            $tempOrder = $widget->sort_order;
            $widget->update(['sort_order' => $previousWidget->sort_order]);
            $previousWidget->update(['sort_order' => $tempOrder]);
        }

        return redirect()->back()->with('success', 'Widget wurde nach oben verschoben.');
    }

    /**
     * Move widget down in sort order.
     */
    public function moveDown($section, Widget $widget)
    {
        $sectionModel = Section::where('section_name', $section)->first();
        if (!$sectionModel) {
            abort(404, 'Section not found');
        }

        // Find the next widget in sort order
        $nextWidget = $sectionModel->widgets()
            ->where('sort_order', '>', $widget->sort_order)
            ->orderBy('sort_order', 'asc')
            ->first();

        if ($nextWidget) {
            // Swap sort orders
            $tempOrder = $widget->sort_order;
            $widget->update(['sort_order' => $nextWidget->sort_order]);
            $nextWidget->update(['sort_order' => $tempOrder]);
        }

        return redirect()->back()->with('success', 'Widget wurde nach unten verschoben.');
    }

    /**
     * Reorder widgets via drag & drop.
     */
    public function reorder(Request $request, $section)
    {
        $sectionModel = Section::where('section_name', $section)->first();
        if (!$sectionModel) {
            abort(404, 'Section not found');
        }

        $validated = $request->validate([
            'widget_ids' => 'required|array',
            'widget_ids.*' => 'integer|exists:widgets,id',
        ]);

        // Update sort_order for each widget
        foreach ($validated['widget_ids'] as $index => $widgetId) {
            Widget::where('id', $widgetId)
                ->where('section_id', $sectionModel->id)
                ->update(['sort_order' => $index + 1]);
        }

        return response()->json(['success' => true]);
    }
}
