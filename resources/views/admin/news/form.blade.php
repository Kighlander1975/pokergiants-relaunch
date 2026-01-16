@props(['news'])

@push('styles')
@vite('resources/css/backend/news-editor.css')
@endpush

@php
$bbTagButtons = [
['tag' => 'b', 'icon' => 'bold', 'label' => 'Fett'],
['tag' => 'i', 'icon' => 'italic', 'label' => 'Kursiv'],
['tag' => 'u', 'icon' => 'underline', 'label' => 'Unterstrichen'],
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

<form action="{{ $news ? route('admin.news.update', $news) : route('admin.news.store') }}" method="POST" class="space-y-6 bg-white shadow-sm rounded-lg p-6">
    @csrf
    @if($news)
    @method('PATCH')
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label for="title" class="text-sm font-medium text-gray-700">Titel</label>
            <input id="title" name="title" value="{{ old('title', $news->title ?? '') }}" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm px-3 py-2" placeholder="Titel der News">
            @error('title')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
        </div>
        <div>
            <label for="category" class="text-sm font-medium text-gray-700">Kategorie</label>
            <select id="category" name="category" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm px-3 py-2">
                @foreach(App\Models\News::categories() as $value => $label)
                <option value="{{ $value }}" {{ old('category', $news->category ?? '') === $value ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
            @error('category')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label for="author" class="text-sm font-medium text-gray-700">Autor</label>
            <input id="author" name="author" value="{{ old('author', $news->author ?? auth()->user()->nickname ?? '') }}" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm px-3 py-2" placeholder="Name des Autors">
            @error('author')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
        </div>
        <div>
            <label for="author_external" class="text-sm font-medium text-gray-700">Externer Autor</label>
            <input id="author_external" name="author_external" value="{{ old('author_external', $news->author_external ?? '') }}" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm px-3 py-2" placeholder="Name eines externen Autors">
            @error('author_external')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
        </div>
    </div>

    <div>
        <label for="tags" class="text-sm font-medium text-gray-700">Tags</label>
        <input id="tags" name="tags" value="{{ old('tags', isset($news) && $news->tags ? implode(', ', $news->tags) : '') }}" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm px-3 py-2" placeholder="Poker, Turnier, Ergebnis">
        <div id="news-tags-badges" class="news-tags-badges" aria-live="polite"></div>
        <p class="text-xs text-gray-500 mt-1">Kommagetrennt, eindeutige Tags werden gespeichert und helfen bei Suchen/Filtern.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label for="source_text" class="text-sm font-medium text-gray-700 flex items-center gap-1">
                Quelle (Text)
                <i class="fas fa-info-circle text-xs text-gray-400" title="Kurzbeschreibung, z. B. 'Pressetext PokerStars'."></i>
            </label>
            <input id="source_text" name="source_text" value="{{ old('source_text', $news->source['text'] ?? '') }}" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm px-3 py-2" placeholder="Text der Quelle">
            <p class="text-xs text-gray-500 mt-1">Gibt an, wie die Quelle benannt werden soll (Bsp. Magazine, Twitter-Handle, Event-Seite) und hilft Lesern, den Ursprung zu verstehen.</p>
        </div>
        <div>
            <label for="source_url" class="text-sm font-medium text-gray-700 flex items-center gap-1">
                Quelle (Link)
                <i class="fas fa-info-circle text-xs text-gray-400" title="Optionaler Link zur ursprünglichen Meldung. Wird nur übernommen, wenn er korrekt formatiert ist."></i>
            </label>
            <input id="source_url" name="source_url" value="{{ old('source_url', $news->source['url'] ?? '') }}" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm px-3 py-2" placeholder="https://...">
            <p class="text-xs text-gray-500 mt-1">Der Link wird sichtbar, wenn Sie ihn mit angeben – ideal für offizielle Pressemitteilungen oder Social-Media-Posts.</p>
        </div>
    </div>

    <div class="flex flex-col gap-2">
        <div class="text-sm font-medium text-gray-700">Content</div>
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
                <button type="button" class="toolbar-btn" data-bb-tag="{{ $button['tag'] }}" aria-label="{{ $button['label'] }}">
                    <i class="fas fa-{{ $button['icon'] }}" aria-hidden="true"></i>
                    <span class="sr-only">{{ $button['label'] }}</span>
                </button>
                @endforeach
            </div>
            <div class="toolbar-group">
                @foreach($bbHeadingButtons as $heading)
                <button type="button" class="toolbar-btn" data-bb-heading="{{ $heading['level'] }}" aria-label="{{ $heading['label'] }}">
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
                <input type="hidden" id="news-icon-value" name="news_icon_selection" value="{{ old('news_icon_selection', '') }}">
                <button type="button" class="toolbar-btn icon-picker-toggle" id="openIconModal" aria-haspopup="dialog" aria-expanded="false">
                    <i class="fas fa-icons" aria-hidden="true"></i>
                    <span>Icon wählen</span>
                </button>
                <div class="icon-picker-preview" aria-live="polite">
                    <i id="news-icon-preview-icon" class="fas fa-circle" aria-hidden="true"></i>
                    <span id="news-icon-preview" class="icon-picker-label">kein Icon gewählt</span>
                </div>
                <button type="button" class="toolbar-btn" data-bb-icon aria-label="Ausgewähltes Icon einfügen">
                    <i class="fas fa-plus" aria-hidden="true"></i>
                    <span class="sr-only">Icon einfügen</span>
                </button>
            </div>
        </div>
        <p id="news-bb-warning" class="news-bb-warning hidden" role="alert" aria-live="assertive"></p>
        <div id="news-bb-suggestions" class="news-bb-suggestions hidden" aria-live="polite">
            <div class="news-bb-suggestions__header">
                <span class="news-bb-suggestions__pretitle">KI-Assistent</span>
                <span class="news-bb-suggestions__title">Korrekturvorschläge</span>
                <span class="news-bb-suggestions__subtitle">Wähle eine Empfehlung, um den BB-Code schnell zu säubern.</span>
            </div>
            <div id="news-bb-suggestions-list" class="news-bb-suggestions__list"></div>
        </div>
        <textarea id="news-content" name="content" rows="10" class="mt-2 w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 font-mono" placeholder="Hier den BB-Code-Inhalt eingeben">{{ old('content', $news->content ?? '') }}</textarea>
        <div class="flex flex-wrap items-center justify-between gap-3">
            <p class="text-xs text-gray-500">{{ __('Zeichen:') }} <span id="news-content-count">0</span></p>
            <button type="button" id="openNewsPreview" class="toolbar-btn news-preview-trigger">
                <i class="fas fa-eye" aria-hidden="true"></i>
                <span class="sr-only">Vorschau öffnen</span>
                Vorschau
            </button>
        </div>
        @error('content')<p class="text-xs text-red-600">{{ $message }}</p>@enderror
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="flex items-center gap-3">
            <input type="checkbox" name="comments_allowed" id="comments_allowed" value="1" {{ old('comments_allowed', $news->comments_allowed ?? true) ? 'checked' : '' }}>
            <label for="comments_allowed" class="text-sm text-gray-700">Kommentare erlauben</label>
        </div>
        <div class="flex items-center gap-3">
            <input type="checkbox" name="published" id="published" value="1" {{ old('published', $news->published ?? false) ? 'checked' : '' }}>
            <label for="published" class="text-sm text-gray-700">Veröffentlichen</label>
        </div>
    </div>

    <div>
        <label for="auto_publish_at" class="text-sm font-medium text-gray-700">Automatisch veröffentlichen am</label>
        <input id="auto_publish_at" name="auto_publish_at" type="datetime-local" value="{{ old('auto_publish_at', optional($news?->auto_publish_at)->format('Y-m-d\TH:i')) }}" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm px-3 py-2">
        <p class="text-xs text-gray-500 mt-1">Falls kein Datum gesetzt wird, bleibt die News im Entwurf und erscheint erst, wenn Sie das Kästchen &ldquo;Veröffentlichen&rdquo; aktivieren. Automatische Veröffentlichungen nutzen die Zeitangabe.</p>
    </div>

    <div class="flex justify-end">
        <button type="submit" class="inline-flex items-center px-6 py-3 bg-indigo-600 text-white font-semibold rounded-md hover:bg-indigo-700">Speichern</button>
    </div>
</form>

@php
$faIconOptions = getAvailableFontAwesomeIconOptions();
@endphp

<div id="iconModal" class="icon-modal hidden" role="dialog" aria-modal="true" aria-labelledby="iconModalTitle">
    <div class="icon-modal__dialog">
        <div class="icon-modal__header">
            <h3 id="iconModalTitle" class="text-lg font-semibold text-gray-900">Icon auswählen</h3>
            <button type="button" class="icon-modal__close" data-icon-modal-close aria-label="Modal schließen">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <div class="icon-modal__search">
            <input type="text" id="iconSearch" placeholder="Nach Icon suchen..." autocomplete="off">
        </div>

        <div id="iconGrid" class="icon-modal__grid">
            @foreach($faIconOptions as $icon)
            <button type="button" class="icon-option" data-icon="{{ $icon['name'] }}" data-prefix="{{ $icon['prefix'] }}" data-style="{{ $icon['style'] }}" data-search="{{ $icon['name'] }} {{ $icon['style'] }}">
                <i class="{{ $icon['prefix'] }} fa-{{ $icon['name'] }} icon-option__symbol" aria-hidden="true"></i>
                <span class="icon-option__label">{{ $icon['label'] }}</span>
                <span class="icon-option__style">{{ strtoupper($icon['style']) }}</span>
            </button>
            @endforeach
        </div>

        <div class="icon-modal__footer">
            <button type="button" class="icon-modal__close-btn" data-icon-modal-close>Abbrechen</button>
        </div>
    </div>
</div>

@php
$newsCategoryLabels = App\Models\News::categories();
$newsExternalCategory = App\Models\News::CATEGORY_EXTERNAL;
@endphp

<div id="news-preview-modal" class="news-preview-modal hidden" role="dialog" aria-modal="true" aria-labelledby="newsPreviewTitle" data-category-labels='@json($newsCategoryLabels)' data-external-category="{{ $newsExternalCategory }}">
    <div class="news-preview-modal__dialog">
        <div class="news-preview-modal__header">
            <div>
                <p class="news-preview-modal__pretitle">Backend-Vorschau</p>
                <h3 id="newsPreviewTitle" class="news-preview-modal__title">Glass-Card Vorschau</h3>
            </div>
            <button type="button" class="news-preview-modal__close" data-news-preview-close aria-label="Vorschau schließen">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <div class="news-preview-modal__body">
            <div class="glass-card news-preview-card">
                <div class="news-preview-card__icon" id="news-preview-icon"></div>
                <h2 id="news-preview-card-title">Titel</h2>
                <p id="news-preview-card-meta" class="news-preview-card__meta">Kategorie · Autor</p>
                <div id="news-preview-card-body" class="news-preview-card__body"></div>
                <div id="news-preview-card-tags" class="news-preview-card__tags" aria-label="Tags"></div>
                <div id="news-preview-card-source" class="news-preview-card__source"></div>
            </div>
        </div>
        <div class="news-preview-modal__footer">
            <button type="button" class="toolbar-btn" data-news-preview-close>
                Schließen
            </button>
        </div>
    </div>
</div>
</div>

@push('scripts')
@vite('resources/js/admin/news-editor.js')
@endpush