@extends('layouts.backend.main-layout-container.app')

@section('title', 'Widget bearbeiten: ' . ($widget->internal_name ?: 'Widget #' . $widget->id))

@push('styles')
@vite('resources/css/backend/news-editor.css')
@endpush

@php
$bbTagButtons = [
['tag' => 'b', 'icon' => 'bold', 'label' => 'Fett'],
['tag' => 'i', 'icon' => 'italic', 'label' => 'Kursiv'],
['tag' => 'u', 'icon' => 'underline', 'label' => 'Unterstrichen'],
['tag' => 'ul', 'icon' => 'list-ul', 'label' => 'Ungeordnete Liste'],
['tag' => 'ol', 'icon' => 'list-ol', 'label' => 'Geordnete Liste'],
['tag' => 'p', 'icon' => 'paragraph', 'label' => 'Absatz'],
['tag' => 'hr', 'icon' => 'minus', 'label' => 'Trennlinie'],
];

$bbHeadingButtons = [
['level' => 'h2', 'label' => 'H2'],
['level' => 'h3', 'label' => 'H3'],
['level' => 'h4', 'label' => 'H4'],
['level' => 'h5', 'label' => 'H5'],
];

$bbAlignButtons = [
['align' => 'left', 'icon' => 'align-left', 'label' => 'Linksbündig'],
['align' => 'center', 'icon' => 'align-center', 'label' => 'Zentriert'],
['align' => 'right', 'icon' => 'align-right', 'label' => 'Rechtsbündig'],
['align' => 'justify', 'icon' => 'align-justify', 'label' => 'Blocksatz'],
];

$bbSuitButtons = [
['type' => 'club', 'symbol' => '♣', 'label' => 'Kreuz'],
['type' => 'spade', 'symbol' => '♠', 'label' => 'Pik'],
['type' => 'heart', 'symbol' => '♥', 'label' => 'Herz'],
['type' => 'diamond', 'symbol' => '♦', 'label' => 'Karo'],
];
@endphp

@section('content-title')
@endsection

@section('content-body')
<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <form method="POST" action="{{ route('admin.widgets.update', [$widget->section->section_name, $widget]) }}" class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            @csrf
            @method('PATCH')

            <div class="p-6 space-y-6">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-medium text-gray-900">
                        Widget bearbeiten: {{ $widget->internal_name ?: 'Widget #' . $widget->id }}
                    </h3>
                    <div class="flex space-x-3">
                        <a href="{{ route('admin.widgets.index', $widget->section->section_name) }}" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400">
                            Zurück zur Übersicht
                        </a>
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                            Speichern
                        </button>
                    </div>
                </div>

                <!-- Internal Name -->
                <div>
                    <label for="internal_name" class="block text-sm font-medium text-gray-700 mb-2">
                        Interner Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="internal_name" id="internal_name"
                           value="{{ old('internal_name', $widget->internal_name) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="z.B. news, events, hero-banner"
                           required>
                    <p class="mt-1 text-sm text-gray-500">
                        Wird in der Navigation angezeigt. Verwenden Sie nur Buchstaben, Zahlen und Bindestriche.
                    </p>
                    @error('internal_name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Widget Type -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-3">
                        Widget-Typ <span class="text-red-500">*</span>
                    </label>
                    <div class="space-y-2">
                        <div class="flex items-center">
                            <input type="radio" name="widget_type" id="type_card" value="card"
                                   {{ old('widget_type', $widget->widget_type) === 'card' ? 'checked' : '' }}
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                            <label for="type_card" class="ml-2 block text-sm text-gray-900">
                                <strong>Card</strong> - Grid-Layout, teilt sich die Reihe mit anderen Cards
                            </label>
                        </div>
                        <div class="flex items-center">
                            <input type="radio" name="widget_type" id="type_one_card" value="one-card"
                                   {{ old('widget_type', $widget->widget_type) === 'one-card' ? 'checked' : '' }}
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                            <label for="type_one_card" class="ml-2 block text-sm text-gray-900">
                                <strong>One-Card</strong> - Nimmt die gesamte Reihe ein
                            </label>
                        </div>
                    </div>
                    @error('widget_type')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Width Percentage -->
                <div>
                    <label for="width_percentage" class="block text-sm font-medium text-gray-700 mb-2">
                        Breite
                    </label>
                    <select name="width_percentage" id="width_percentage"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="" {{ old('width_percentage', $widget->width_percentage) === '' || old('width_percentage', $widget->width_percentage) === null ? 'selected' : '' }}>
                            Default (auto ~25%)
                        </option>
                        @foreach([10, 25, 33, 50, 66, 75, 100] as $width)
                            <option value="{{ $width }}" {{ old('width_percentage', $widget->width_percentage) == $width ? 'selected' : '' }}>
                                {{ $width }}%
                            </option>
                        @endforeach
                    </select>
                    <p class="mt-1 text-sm text-gray-500">
                        Bei "Default" werden Widgets automatisch gleichmäßig verteilt (ca. 25% pro Widget).
                    </p>
                    @error('width_percentage')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Center on Small -->
                <div class="flex items-center">
                    <input type="checkbox" name="center_on_small" id="center_on_small" value="1"
                           {{ old('center_on_small', $widget->center_on_small) ? 'checked' : '' }}
                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                    <label for="center_on_small" class="ml-2 block text-sm text-gray-900">
                        Auf kleinen Displays zentrieren (< 390px)
                    </label>
                </div>

                <!-- Content Tabs -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Widget-Inhalt <span class="text-red-500">*</span>
                    </label>

                    <!-- Hidden Content Type Field -->
                    <input type="hidden" name="content_type" id="content_type" value="{{ old('content_type', $widget->content_type ?? 'html') }}">

                    <!-- Tab Navigation -->
                    <div class="mb-4">
                        <nav class="flex space-x-1 bg-gray-100 p-1 rounded-lg">
                            <button type="button" id="tab-html" class="tab-button {{ ($widget->content_type ?? 'html') === 'html' ? 'active bg-white text-gray-900 shadow-sm' : 'text-gray-500' }} flex-1 py-2 px-4 text-sm font-medium text-center rounded-md transition-colors duration-200"
                                    data-tab="html">
                                HTML
                            </button>
                            <button type="button" id="tab-plain" class="tab-button {{ ($widget->content_type ?? 'html') === 'plain' ? 'active bg-white text-gray-900 shadow-sm' : 'text-gray-500' }} flex-1 py-2 px-4 text-sm font-medium text-center rounded-md hover:text-gray-700 transition-colors duration-200"
                                    data-tab="plain">
                                Plain
                            </button>
                            <button type="button" id="tab-news" class="tab-button {{ ($widget->content_type ?? 'html') === 'news' ? 'active bg-white text-gray-900 shadow-sm' : 'text-gray-500' }} flex-1 py-2 px-4 text-sm font-medium text-center rounded-md hover:text-gray-700 transition-colors duration-200"
                                    data-tab="news">
                                News
                            </button>
                            <button type="button" id="tab-events" class="tab-button {{ ($widget->content_type ?? 'html') === 'events' ? 'active bg-white text-gray-900 shadow-sm' : 'text-gray-500' }} flex-1 py-2 px-4 text-sm font-medium text-center rounded-md hover:text-gray-700 transition-colors duration-200"
                                    data-tab="events">
                                Events
                            </button>
                            <button type="button" id="tab-gallery" class="tab-button {{ ($widget->content_type ?? 'html') === 'gallery' ? 'active bg-white text-gray-900 shadow-sm' : 'text-gray-500' }} flex-1 py-2 px-4 text-sm font-medium text-center rounded-md hover:text-gray-700 transition-colors duration-200"
                                    data-tab="gallery">
                                Gallery
                            </button>
                            <button type="button" id="tab-stats" class="tab-button {{ ($widget->content_type ?? 'html') === 'stats' ? 'active bg-white text-gray-900 shadow-sm' : 'text-gray-500' }} flex-1 py-2 px-4 text-sm font-medium text-center rounded-md hover:text-gray-700 transition-colors duration-200"
                                    data-tab="stats">
                                Stats
                            </button>
                            <button type="button" id="tab-custom" class="tab-button {{ ($widget->content_type ?? 'html') === 'custom' ? 'active bg-white text-gray-900 shadow-sm' : 'text-gray-500' }} flex-1 py-2 px-4 text-sm font-medium text-center rounded-md hover:text-gray-700 transition-colors duration-200"
                                    data-tab="custom">
                                Custom
                            </button>
                        </nav>
                    </div>

                    <!-- Tab Content -->
                    <div class="space-y-4">
                        <!-- HTML Tab -->
                        <div id="content-html" class="tab-content {{ ($widget->content_type ?? 'html') === 'html' ? '' : 'hidden' }}">
                            <label for="content_html" class="block text-sm font-medium text-gray-700 mb-2">
                                HTML-Inhalt
                            </label>

                            <!-- HTML Editor Container -->
                            <div class="relative">
                                <textarea name="content_html" id="content_html" rows="15"
                                          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent font-mono text-sm leading-relaxed"
                                          placeholder="<div class='content'>
    <h2>Überschrift</h2>
    <p>Text content</p>
    <ul>
        <li>List item</li>
    </ul>
</div>"
                                          style="tab-size: 4; white-space: pre; overflow-wrap: normal; overflow-x: auto;">{{ old('content_html', $widget->content_html) }}</textarea>

                                <!-- Code Formatting Helper -->
                                <div class="mt-2 flex items-center justify-between text-xs text-gray-500">
                                    <div>
                                        <span class="font-medium">Tipp:</span> Verwende Tab für korrekte Einrückung (4 Leerzeichen)
                                    </div>
                                    <div class="flex space-x-2">
                                        <button type="button" id="format-html" class="text-blue-600 hover:text-blue-800 underline">
                                            Formatieren
                                        </button>
                                        <button type="button" id="preview-html" class="text-green-600 hover:text-green-800 underline">
                                            Vorschau
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- HTML Preview Modal (hidden by default) -->
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
                                        <!-- Preview content will be inserted here -->
                                    </div>
                                </div>
                            </div>

                            <p class="mt-1 text-sm text-gray-500">
                                HTML-Inhalt zwischen den Card-DIVs. Alle HTML-Tags sind erlaubt.
                            </p>
                        </div>

                        <!-- Plain Tab -->
                        <div id="content-plain" class="tab-content {{ ($widget->content_type ?? 'html') === 'plain' ? '' : 'hidden' }}">
                            <label for="content_plain" class="block text-sm font-medium text-gray-700 mb-2">
                                Plain-Text-Inhalt (BB-Code Editor)
                            </label>

                            <!-- BB-Code Editor Toolbar -->
                            <div class="news-editor-toolbar" role="toolbar" aria-label="BB-Code-Werkzeugleiste">
                                <div class="toolbar-group">
                                    <button type="button" class="toolbar-btn" data-bb-undo aria-label="Rückgängig">
                                        <i class="fas fa-undo" aria-hidden="true"></i>
                                        <span class="sr-only">Rückgängig</span>
                                    </button>
                                    <button type="button" class="toolbar-btn" data-bb-redo aria-label="Wiederholen">
                                        <i class="fas fa-redo" aria-hidden="true"></i>
                                        <span class="sr-only">Wiederholen</span>
                                    </button>
                                </div>
                                <div class="toolbar-group">
                                    @foreach($bbTagButtons as $button)
                                    <button type="button" class="toolbar-btn" data-bb-tag="{{ $button['tag'] }}" aria-label="{{ $button['label'] }}" title="{{ $button['label'] }}">
                                        <i class="fas fa-{{ $button['icon'] }}" aria-hidden="true"></i>
                                        <span class="sr-only">{{ $button['label'] }}</span>
                                    </button>
                                    @endforeach
                                </div>
                                <div class="toolbar-group">
                                    @foreach($bbHeadingButtons as $heading)
                                    <button type="button" class="toolbar-btn" data-bb-heading="{{ $heading['level'] }}" aria-label="{{ $heading['label'] }}" title="{{ $heading['label'] }}">
                                        <span class="heading-label">{{ $heading['label'] }}</span>
                                    </button>
                                    @endforeach
                                </div>
                                <div class="toolbar-group">
                                    @foreach($bbAlignButtons as $align)
                                    <button type="button" class="toolbar-btn" data-bb-align="{{ $align['align'] }}" aria-label="{{ $align['label'] }}">
                                        <i class="fas fa-{{ $align['icon'] }}" aria-hidden="true"></i>
                                        <span class="sr-only">{{ $align['label'] }}</span>
                                    </button>
                                    @endforeach
                                    <button type="button" class="toolbar-btn" data-bb-link aria-label="Link einfügen">
                                        <i class="fas fa-link" aria-hidden="true"></i>
                                        <span class="sr-only">Link</span>
                                    </button>
                                </div>
                                <div class="toolbar-group">
                                    @foreach($bbSuitButtons as $suit)
                                    <button type="button" class="toolbar-btn suit-btn" data-bb-suit="{{ $suit['type'] }}" aria-label="{{ $suit['label'] }}">
                                        <span class="suit-symbol suit-{{ $suit['type'] }} suit-{{ $suit['type'] === 'heart' || $suit['type'] === 'diamond' ? 'red' : 'black' }}">{{ $suit['symbol'] }}</span>
                                        <span class="sr-only">{{ $suit['label'] }}</span>
                                    </button>
                                    @endforeach
                                </div>
                                <div class="toolbar-group icon-picker-group">
                                    <input type="hidden" id="plain-icon-value" name="plain_icon_selection" value="{{ old('plain_icon_selection', '') }}">
                                    <button type="button" class="toolbar-btn icon-picker-toggle" id="openPlainIconModal" aria-haspopup="dialog" aria-expanded="false">
                                        <i class="fas fa-icons" aria-hidden="true"></i>
                                        <span>Icon wählen</span>
                                    </button>
                                    <div class="icon-picker-preview" aria-live="polite">
                                        <i id="plain-icon-preview-icon" class="fas fa-circle" aria-hidden="true"></i>
                                        <span id="plain-icon-preview" class="icon-picker-label">kein Icon gewählt</span>
                                    </div>
                                    <button type="button" class="toolbar-btn" data-bb-icon aria-label="Ausgewähltes Icon einfügen">
                                        <i class="fas fa-plus" aria-hidden="true"></i>
                                        <span class="sr-only">Icon einfügen</span>
                                    </button>
                                </div>
                            </div>

                            <p id="plain-bb-warning" class="news-bb-warning hidden" role="alert" aria-live="assertive"></p>
                            <div id="plain-bb-suggestions" class="news-bb-suggestions hidden" aria-live="polite">
                                <div class="news-bb-suggestions__header">
                                    <span class="news-bb-suggestions__pretitle">KI-Assistent</span>
                                    <span class="news-bb-suggestions__title">Korrekturvorschläge</span>
                                    <span class="news-bb-suggestions__subtitle">Wähle eine Empfehlung, um den BB-Code schnell zu säubern.</span>
                                </div>
                                <div id="plain-bb-suggestions-list" class="news-bb-suggestions__list"></div>
                            </div>

                            <textarea name="content_plain" id="plain-content" rows="10"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent font-mono text-sm leading-relaxed"
                                      placeholder="Hier den BB-Code-Inhalt eingeben">{{ old('content_plain', $widget->content_plain ?? '') }}</textarea>

                            <div class="flex flex-wrap items-center justify-between gap-3 mt-2">
                                <p class="text-xs text-gray-500">{{ __('Zeichen:') }} <span id="plain-content-count">0</span></p>
                                <button type="button" id="openPlainPreview" class="toolbar-btn plain-preview-trigger">
                                    <i class="fas fa-eye" aria-hidden="true"></i>
                                    <span class="sr-only">Vorschau öffnen</span>
                                    Vorschau
                                </button>
                            </div>

                            <p class="mt-1 text-sm text-gray-500">
                                BB-Code-Editor für formatierte Texte. Verwende die Toolbar für einfache Formatierung.
                            </p>
                        </div>

                        <!-- Plain Icon Modal -->
                        <div id="plain-icon-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50" role="dialog" aria-modal="true" aria-labelledby="plain-icon-modal-title">
                            <div class="relative top-20 mx-auto p-5 border w-11/12 max-w-4xl shadow-lg rounded-md bg-white max-h-[80vh] overflow-y-auto">
                                <div class="flex items-center justify-between mb-4">
                                    <h3 id="plain-icon-modal-title" class="text-lg font-medium text-gray-900">Icon auswählen</h3>
                                    <button type="button" data-close-modal class="text-gray-400 hover:text-gray-600">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                </div>
                                <div class="grid grid-cols-6 gap-4 mb-4">
                                    <button type="button" data-icon="circle" class="icon-btn" aria-label="Circle Icon">
                                        <i class="fas fa-circle text-2xl"></i>
                                    </button>
                                    <button type="button" data-icon="star" class="icon-btn" aria-label="Star Icon">
                                        <i class="fas fa-star text-2xl"></i>
                                    </button>
                                    <button type="button" data-icon="heart" class="icon-btn" aria-label="Heart Icon">
                                        <i class="fas fa-heart text-2xl"></i>
                                    </button>
                                    <button type="button" data-icon="thumbs-up" class="icon-btn" aria-label="Thumbs Up Icon">
                                        <i class="fas fa-thumbs-up text-2xl"></i>
                                    </button>
                                    <button type="button" data-icon="check" class="icon-btn" aria-label="Check Icon">
                                        <i class="fas fa-check text-2xl"></i>
                                    </button>
                                    <button type="button" data-icon="times" class="icon-btn" aria-label="Times Icon">
                                        <i class="fas fa-times text-2xl"></i>
                                    </button>
                                    <button type="button" data-icon="exclamation-triangle" class="icon-btn" aria-label="Warning Icon">
                                        <i class="fas fa-exclamation-triangle text-2xl"></i>
                                    </button>
                                    <button type="button" data-icon="info-circle" class="icon-btn" aria-label="Info Icon">
                                        <i class="fas fa-info-circle text-2xl"></i>
                                    </button>
                                    <button type="button" data-icon="question-circle" class="icon-btn" aria-label="Question Icon">
                                        <i class="fas fa-question-circle text-2xl"></i>
                                    </button>
                                    <button type="button" data-icon="cog" class="icon-btn" aria-label="Settings Icon">
                                        <i class="fas fa-cog text-2xl"></i>
                                    </button>
                                    <button type="button" data-icon="user" class="icon-btn" aria-label="User Icon">
                                        <i class="fas fa-user text-2xl"></i>
                                    </button>
                                    <button type="button" data-icon="envelope" class="icon-btn" aria-label="Envelope Icon">
                                        <i class="fas fa-envelope text-2xl"></i>
                                    </button>
                                </div>
                                <div class="flex justify-end">
                                    <button type="button" data-close-modal class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400">
                                        Schließen
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Plain Preview Modal -->
                        <div id="plain-preview-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50" role="dialog" aria-modal="true" aria-labelledby="plain-preview-modal-title">
                            <div class="relative top-20 mx-auto p-5 border w-11/12 max-w-4xl shadow-lg rounded-md bg-white">
                                <div class="flex items-center justify-between mb-4">
                                    <h3 id="plain-preview-modal-title" class="text-lg font-medium text-gray-900">BB-Code Vorschau</h3>
                                    <button type="button" data-close-modal class="text-gray-400 hover:text-gray-600">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                </div>
                                <div id="plain-preview-content" class="border border-gray-200 rounded-md p-4 bg-gray-50 max-h-96 overflow-y-auto prose prose-sm max-w-none">
                                    <!-- Preview content will be inserted here -->
                                </div>
                            </div>
                        </div>

                        <!-- News Tab -->
                        <div id="content-news" class="tab-content {{ ($widget->content_type ?? 'html') === 'news' ? '' : 'hidden' }}">
                            <div class="text-center py-8 text-gray-500">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">News-Widget</h3>
                                <p class="mt-1 text-sm text-gray-500">Diese Funktion wird bald verfügbar sein.</p>
                            </div>
                        </div>

                        <!-- Events Tab -->
                        <div id="content-events" class="tab-content {{ ($widget->content_type ?? 'html') === 'events' ? '' : 'hidden' }}">
                            <div class="text-center py-8 text-gray-500">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">Events-Widget</h3>
                                <p class="mt-1 text-sm text-gray-500">Diese Funktion wird bald verfügbar sein.</p>
                            </div>
                        </div>

                        <!-- Gallery Tab -->
                        <div id="content-gallery" class="tab-content {{ ($widget->content_type ?? 'html') === 'gallery' ? '' : 'hidden' }}">
                            <div class="text-center py-8 text-gray-500">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">Gallery-Widget</h3>
                                <p class="mt-1 text-sm text-gray-500">Diese Funktion wird bald verfügbar sein.</p>
                            </div>
                        </div>

                        <!-- Stats Tab -->
                        <div id="content-stats" class="tab-content {{ ($widget->content_type ?? 'html') === 'stats' ? '' : 'hidden' }}">
                            <div class="text-center py-8 text-gray-500">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">Stats-Widget</h3>
                                <p class="mt-1 text-sm text-gray-500">Diese Funktion wird bald verfügbar sein.</p>
                            </div>
                        </div>

                        <!-- Custom Tab -->
                        <div id="content-custom" class="tab-content {{ ($widget->content_type ?? 'html') === 'custom' ? '' : 'hidden' }}">
                            <div class="text-center py-8 text-gray-500">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">Custom-Widget</h3>
                                <p class="mt-1 text-sm text-gray-500">Diese Funktion wird bald verfügbar sein.</p>
                            </div>
                        </div>
                    </div>

                    @error('content_html')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Actions -->
                <div class="flex items-center justify-end pt-6 border-t border-gray-200">
                    <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700">
                        Änderungen speichern
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

@vite('resources/js/admin/widget-editor.js')
@vite('resources/js/admin/news-editor.js')
@endsection