@extends('layouts.backend.main-layout-container.app')

@section('title', 'Widgets - ' . ucfirst($sectionModel->section_name))

@section('content-title')
@endsection

@section('content-body')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900">Widgets für Section: {{ ucfirst($sectionModel->section_name) }}</h3>
                        <p class="mt-1 text-sm text-gray-600">{{ $widgets->count() }} Widgets</p>
                    </div>
                    <div class="flex space-x-3">
                        <a href="{{ route('admin.widgets.create', $sectionModel->section_name) }}" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                            Neues Widget
                        </a>
                        <a href="{{ route('admin.views.index') }}" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400">
                            Zurück zu Views
                        </a>
                    </div>
                </div>

                @if($widgets->isEmpty())
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">Keine Widgets</h3>
                        <p class="mt-1 text-sm text-gray-500">Erstellen Sie Ihr erstes Widget für diese Section.</p>
                        <div class="mt-6">
                            <a href="{{ route('admin.widgets.create', $sectionModel->section_name) }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                <svg class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                                Neues Widget erstellen
                            </a>
                        </div>
                    </div>
                @else
                    <div id="widgets-list" class="space-y-4">
                        @foreach($widgets as $widget)
                            <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 widget-item relative" data-widget-id="{{ $widget->id }}">
                                <div class="absolute top-2 left-2 cursor-move text-gray-400 hover:text-gray-600 z-10">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16" />
                                    </svg>
                                </div>
                                <div class="flex items-center justify-between pl-8">
                                            <h4 class="text-sm font-medium text-gray-900">
                                                {{ $widget->internal_name ?: 'Widget #' . $widget->id }}
                                            </h4>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                @if($widget->widget_type === 'one-card') bg-purple-100 text-purple-800
                                                @else bg-blue-100 text-blue-800 @endif">
                                                {{ $widget->widget_type === 'one-card' ? 'One-Card' : 'Card' }}
                                            </span>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                {{ $widget->width_percentage }}%
                                            </span>
                                            @if($widget->center_on_small)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    Zentriert
                                                </span>
                                            @endif
                                        </div>
                                        <div class="mt-2 text-sm text-gray-600">
                                            <div class="line-clamp-2" style="max-width: 600px;">
                                                {!! Str::limit(strip_tags($widget->content_html), 150) !!}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <!-- Sort Up Button -->
                                        <form method="POST" action="{{ route('admin.widgets.move-up', [$sectionModel->section_name, $widget]) }}" class="inline">
                                            @csrf
                                            <button type="submit" class="text-gray-400 hover:text-blue-600" title="Nach oben verschieben">
                                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                                                </svg>
                                            </button>
                                        </form>

                                        <!-- Sort Down Button -->
                                        <form method="POST" action="{{ route('admin.widgets.move-down', [$sectionModel->section_name, $widget]) }}" class="inline">
                                            @csrf
                                            <button type="submit" class="text-gray-400 hover:text-blue-600" title="Nach unten verschieben">
                                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                                </svg>
                                            </button>
                                        </form>

                                        <a href="{{ route('admin.widgets.show', [$sectionModel->section_name, $widget]) }}" class="text-gray-400 hover:text-gray-600">
                                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </a>
                                        <a href="{{ route('admin.widgets.edit', [$sectionModel->section_name, $widget]) }}" class="text-gray-400 hover:text-gray-600">
                                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </a>
                                        <form method="POST" action="{{ route('admin.widgets.destroy', [$sectionModel->section_name, $widget]) }}" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-400 hover:text-red-600" onclick="return confirm('Sind Sie sicher, dass Sie dieses Widget löschen möchten?')">
                                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- Debug Panel --}}
@if($widgets->isNotEmpty())
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900">🔍 Debug: Generierter Code aller Widgets</h3>
                        <p class="mt-1 text-sm text-gray-600">Vollständiger zusammenhängender HTML-Code aller Widgets</p>
                    </div>
                    <button onclick="toggleDebugPanel()" class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600 text-sm">
                        Debug Panel umschalten
                    </button>
                </div>

                <div id="debug-panel" class="hidden">
                    <div class="bg-gray-50 p-4 rounded border">
                        <h5 class="text-sm font-medium text-gray-700 mb-3">Generierter HTML-Code aller Widgets:</h5>
                        <pre class="text-xs text-gray-900 whitespace-pre-wrap font-mono bg-white p-4 rounded border overflow-x-auto"><code>@foreach($widgets as $widget)
&lt;div class="{{ $widget->css_classes }}"&gt;
@php
    $indentedContent = str_replace("\n", "\n    ", htmlspecialchars($widget->content_html));
    if (!str_starts_with($indentedContent, '    ')) {
        $indentedContent = '    ' . $indentedContent;
    }
@endphp
    {!! $indentedContent !!}
&lt;/div&gt;
@endforeach</code></pre>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

<script>
function toggleDebugPanel() {
    const panel = document.getElementById('debug-panel');
    panel.classList.toggle('hidden');
}
</script>

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const widgetsList = document.getElementById('widgets-list');
    if (!widgetsList) return;

    let draggedElement = null;

    // Add drag event listeners to each widget item
    const widgetItems = widgetsList.querySelectorAll('.widget-item');
    widgetItems.forEach(item => {
        item.draggable = true;
        item.addEventListener('dragstart', handleDragStart);
        item.addEventListener('dragover', handleDragOver);
        item.addEventListener('drop', handleDrop);
        item.addEventListener('dragend', handleDragEnd);
    });

    function handleDragStart(e) {
        draggedElement = this;
        this.classList.add('opacity-50');
        e.dataTransfer.effectAllowed = 'move';
        e.dataTransfer.setData('text/html', this.outerHTML);
    }

    function handleDragOver(e) {
        e.preventDefault();
        e.dataTransfer.dropEffect = 'move';
        return false;
    }

    function handleDrop(e) {
        e.preventDefault();
        e.stopPropagation();

        if (draggedElement !== this) {
            // Reorder the DOM elements
            const allItems = Array.from(widgetsList.children);
            const draggedIndex = allItems.indexOf(draggedElement);
            const targetIndex = allItems.indexOf(this);

            if (draggedIndex < targetIndex) {
                this.parentNode.insertBefore(draggedElement, this.nextSibling);
            } else {
                this.parentNode.insertBefore(draggedElement, this);
            }

            // Send reorder request
            const widgetItems = widgetsList.querySelectorAll('.widget-item');
            const widgetIds = Array.from(widgetItems).map(item => parseInt(item.dataset.widgetId));

            fetch('{{ route("admin.widgets.reorder", $sectionModel->section_name) }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    widget_ids: widgetIds
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    console.log('Widgets reordered successfully');
                }
            })
            .catch(error => {
                console.error('Error reordering widgets:', error);
                location.reload();
            });
        }

        return false;
    }

    function handleDragEnd(e) {
        this.classList.remove('opacity-50');
        draggedElement = null;
    }
});
</script>
@endsection