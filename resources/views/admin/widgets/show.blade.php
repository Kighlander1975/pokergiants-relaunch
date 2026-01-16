@extends('layouts.backend.main-layout-container.app')

@section('title', 'Widget anzeigen: ' . ($widget->internal_name ?: 'Widget #' . $widget->id))

@section('content-title')
@endsection

@section('content-body')
<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 space-y-6">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-medium text-gray-900">
                        Widget anzeigen: {{ $widget->internal_name ?: 'Widget #' . $widget->id }}
                    </h3>
                    <div class="flex space-x-3">
                        <a href="{{ route('admin.widgets.edit', [$widget->section->section_name, $widget]) }}" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                            Bearbeiten
                        </a>
                        <a href="{{ route('admin.widgets.index', $widget->section->section_name) }}" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400">
                            Zurück zur Übersicht
                        </a>
                    </div>
                </div>

                <!-- Internal Name -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Interner Name
                    </label>
                    <p class="text-sm text-gray-900 bg-gray-50 px-3 py-2 rounded-md">
                        {{ $widget->internal_name ?: 'Kein interner Name' }}
                    </p>
                </div>

                <!-- Widget Type -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Widget-Typ
                    </label>
                    <p class="text-sm text-gray-900 bg-gray-50 px-3 py-2 rounded-md">
                        {{ $widget->widget_type === 'one-card' ? 'Eine Karte' : 'Karte' }}
                    </p>
                </div>

                <!-- Width Percentage -->
                @if($widget->width_percentage)
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Breite
                    </label>
                    <p class="text-sm text-gray-900 bg-gray-50 px-3 py-2 rounded-md">
                        {{ $widget->width_percentage }}%
                    </p>
                </div>
                @endif

                <!-- Center on Small -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Zentrieren auf kleinen Bildschirmen
                    </label>
                    <p class="text-sm text-gray-900 bg-gray-50 px-3 py-2 rounded-md">
                        {{ $widget->center_on_small ? 'Ja' : 'Nein' }}
                    </p>
                </div>

                <!-- Content Type -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Inhaltstyp
                    </label>
                    <p class="text-sm text-gray-900 bg-gray-50 px-3 py-2 rounded-md">
                        {{ ucfirst($widget->content_type) }}
                    </p>
                </div>

                <!-- Content HTML -->
                @if($widget->content_type === 'html' && $widget->content_html)
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        HTML-Inhalt
                    </label>
                    <div class="bg-gray-50 border border-gray-200 rounded-md p-4">
                        <pre class="text-sm text-gray-900 whitespace-pre-wrap font-mono">{{ $widget->content_html }}</pre>
                    </div>
                    <div class="mt-2">
                        <button type="button" onclick="previewHTML()" class="text-blue-600 hover:text-blue-800 underline text-sm">
                            Vorschau anzeigen
                        </button>
                    </div>
                </div>
                @endif

                <!-- Sort Order -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Sortierreihenfolge
                    </label>
                    <p class="text-sm text-gray-900 bg-gray-50 px-3 py-2 rounded-md">
                        {{ $widget->sort_order }}
                    </p>
                </div>

                <!-- Timestamps -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Erstellt am
                        </label>
                        <p class="text-sm text-gray-900 bg-gray-50 px-3 py-2 rounded-md">
                            {{ $widget->created_at->format('d.m.Y H:i') }}
                        </p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Zuletzt aktualisiert
                        </label>
                        <p class="text-sm text-gray-900 bg-gray-50 px-3 py-2 rounded-md">
                            {{ $widget->updated_at->format('d.m.Y H:i') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- HTML Preview Modal (hidden by default) -->
@if($widget->content_type === 'html' && $widget->content_html)
<div id="html-preview-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 max-w-4xl shadow-lg rounded-md bg-white">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-medium text-gray-900">HTML-Vorschau</h3>
            <button type="button" id="close-preview" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <div id="html-preview-content" class="border border-gray-200 rounded-md p-4 bg-gray-50 max-h-96 overflow-y-auto">
            {!! $widget->content_html !!}
        </div>
    </div>
</div>
@endif

<script>
@if($widget->content_type === 'html' && $widget->content_html)
function previewHTML() {
    const modal = document.getElementById('html-preview-modal');
    const closeBtn = document.getElementById('close-preview');

    modal.classList.remove('hidden');

    closeBtn.addEventListener('click', function() {
        modal.classList.add('hidden');
    });

    // Close modal when clicking outside
    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            modal.classList.add('hidden');
        }
    });
}
@endif
</script>
@endsection