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

<div id="news-preview-modal" class="news-preview-modal hidden" role="dialog" aria-modal="true" aria-labelledby="newsPreviewTitle">
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

@php
$newsCategoryLabels = App\Models\News::categories();
$newsExternalCategory = App\Models\News::CATEGORY_EXTERNAL;
@endphp

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const editor = document.getElementById('news-content');
        if (!editor) {
            return;
        }

        const counter = document.getElementById('news-content-count');
        const warningEl = document.getElementById('news-bb-warning');
        const suggestionsWrapper = document.getElementById('news-bb-suggestions');
        const suggestionsList = document.getElementById('news-bb-suggestions-list');
        let latestSuggestions = [];
        const tagsInput = document.getElementById('tags');
        const tagsBadgeContainer = document.getElementById('news-tags-badges');
        const titleInput = document.getElementById('title');
        const authorInput = document.getElementById('author');
        const externalAuthorInput = document.getElementById('author_external');
        const categorySelect = document.getElementById('category');
        const publishedToggle = document.getElementById('published');
        const autoPublishInput = document.getElementById('auto_publish_at');
        const sourceTextInput = document.getElementById('source_text');
        const sourceUrlInput = document.getElementById('source_url');
        const previewModal = document.getElementById('news-preview-modal');
        const previewIconContainer = document.getElementById('news-preview-icon');
        const previewTitleEl = document.getElementById('news-preview-card-title');
        const previewMetaEl = document.getElementById('news-preview-card-meta');
        const previewBodyEl = document.getElementById('news-preview-card-body');
        const previewSourceEl = document.getElementById('news-preview-card-source');
        const previewTagsEl = document.getElementById('news-preview-card-tags');
        const previewCategoryLabels = @json($newsCategoryLabels);
        const previewExternalCategory = @json($newsExternalCategory);
        const openPreviewBtn = document.getElementById('openNewsPreview');
        const previewCloseButtons = document.querySelectorAll('[data-news-preview-close]');

        const setWarning = (message) => {
            if (!warningEl) {
                return;
            }
            if (message) {
                warningEl.textContent = `BB-Code nicht korrekt verschachtelt: ${message}`;
                warningEl.classList.remove('hidden');
            } else {
                warningEl.textContent = '';
                warningEl.classList.add('hidden');
            }
        };

        const escapeHtml = (value = '') => value
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#39;');

        const decodeHtmlEntities = (value = '') => value
            .replace(/&amp;/g, '&')
            .replace(/&lt;/g, '<')
            .replace(/&gt;/g, '>')
            .replace(/&quot;/g, '"')
            .replace(/&#39;/g, "'");

        const convertBBToPreviewHtml = (raw) => {
            let html = escapeHtml(raw ?? '');
            html = html
                .replace(/\[b\]([\s\S]*?)\[\/b\]/gi, '<strong>$1</strong>')
                .replace(/\[i\]([\s\S]*?)\[\/i\]/gi, '<em>$1</em>')
                .replace(/\[u\]([\s\S]*?)\[\/u\]/gi, '<span style="text-decoration:underline">$1</span>')
                .replace(/\[p\]([\s\S]*?)\[\/p\]/gi, '<p>$1</p>')
                .replace(/\[url=([^\]]+)\]([\s\S]*?)\[\/url\]/gi, (match, href, label) => {
                    const decodedHref = decodeHtmlEntities(href);
                    return `<a href="${decodedHref}" target="_blank" rel="noreferrer">${label}</a>`;
                })
                .replace(/\[url\]([\s\S]*?)\[\/url\]/gi, (match, href) => {
                    const decodedHref = decodeHtmlEntities(href);
                    return `<a href="${decodedHref}" target="_blank" rel="noreferrer">${decodedHref}</a>`;
                })
                .replace(/\[hr\]/gi, '<hr class="news-preview-card__divider">')
                .replace(/\[align=(left|center|right|justify)\]([\s\S]*?)\[\/align\]/gi, '<div style="text-align:$1">$2</div>')
                .replace(/\[icon=([^\]]+)\]/gi, '<i class="fas fa-$1" aria-hidden="true"></i>')
                .replace(/\n/g, '<br>');

            return html.trim() || '<span class="text-gray-500">(kein Inhalt)</span>';
        };

        const formatPreviewDate = (value) => {
            if (!value) {
                return null;
            }
            const parsed = new Date(value);
            if (Number.isNaN(parsed)) {
                return null;
            }
            return new Intl.DateTimeFormat('de-DE', {
                dateStyle: 'medium',
                timeStyle: 'short'
            }).format(parsed);
        };

        const buildPreviewMeta = () => {
            const parts = [];
            const categoryValue = categorySelect?.value;
            const categoryLabel = categoryValue ? (previewCategoryLabels?.[categoryValue] ?? categorySelect.options[categorySelect.selectedIndex]?.text) : null;
            if (categoryLabel) {
                parts.push(categoryLabel);
            }
            const externalAuthor = externalAuthorInput?.value?.trim();
            const internalAuthor = authorInput?.value?.trim();
            if (externalAuthor && categoryValue === previewExternalCategory) {
                parts.push(externalAuthor);
            } else if (internalAuthor) {
                parts.push(internalAuthor);
            }
            if (publishedToggle?.checked) {
                parts.push('Status: veröffentlicht');
            } else {
                parts.push('Status: Entwurf');
            }
            const autoPublishValue = autoPublishInput?.value;
            const formatted = formatPreviewDate(autoPublishValue);
            if (formatted) {
                parts.push(`Geplant: ${formatted}`);
            }
            return parts.join(' · ');
        };

        const updatePreviewSource = () => {
            if (!previewSourceEl) {
                return;
            }
            previewSourceEl.innerHTML = '';
            const label = sourceTextInput?.value?.trim();
            const url = sourceUrlInput?.value?.trim();
            if (!label && !url) {
                previewSourceEl.textContent = 'Keine Quelle angegeben.';
                return;
            }
            const prefix = document.createElement('span');
            prefix.textContent = 'Quelle: ';
            previewSourceEl.appendChild(prefix);
            if (url) {
                const link = document.createElement('a');
                link.href = url;
                link.target = '_blank';
                link.rel = 'noreferrer';
                link.textContent = label || url;
                previewSourceEl.appendChild(link);
            } else if (label) {
                const text = document.createElement('span');
                text.textContent = label;
                previewSourceEl.appendChild(text);
            }
        };

        const syncPreviewIcon = () => {
            if (!previewIconContainer || !iconPreviewSymbol) {
                return;
            }
            previewIconContainer.innerHTML = iconPreviewSymbol.outerHTML;
        };

        const parseTags = (value) => (
            (value ?? '')
            .split(',')
            .map(tag => tag.trim())
            .filter(Boolean)
        );

        const renderTagBadges = (container, tags) => {
            if (!container) {
                return;
            }
            if (!tags.length) {
                container.innerHTML = '';
                return;
            }
            container.innerHTML = tags.map(tag => `<span class="news-tag-badge">${tag}</span>`).join('');
        };

        const syncTagBadges = () => {
            const tags = parseTags(tagsInput?.value);
            renderTagBadges(tagsBadgeContainer, tags);
            renderTagBadges(previewTagsEl, tags);
        };

        const updatePreviewCard = () => {
            if (previewTitleEl) {
                previewTitleEl.textContent = titleInput?.value?.trim() || 'Unbenannte News';
            }
            if (previewMetaEl) {
                previewMetaEl.textContent = buildPreviewMeta() || 'Kategorie · Autor';
            }
            if (previewBodyEl) {
                previewBodyEl.innerHTML = convertBBToPreviewHtml(editor.value);
            }
            updatePreviewSource();
            syncPreviewIcon();
            syncTagBadges();
        };

        const closePreview = () => {
            previewModal?.classList.add('hidden');
        };

        const openPreview = () => {
            updatePreviewCard();
            previewModal?.classList.remove('hidden');
        };


        const renderSuggestions = (suggestions) => {
            latestSuggestions = suggestions;
            if (!suggestionsWrapper || !suggestionsList) {
                return;
            }
            if (!suggestions.length) {
                suggestionsWrapper.classList.add('hidden');
                suggestionsList.innerHTML = '';
                return;
            }
            suggestionsWrapper.classList.remove('hidden');
            suggestionsList.innerHTML = suggestions.map((suggestion, index) => `
                <button type="button" class="news-bb-suggestion" data-suggestion-index="${index}">
                    <span class="news-bb-suggestion__title">${suggestion.title}</span>
                    <span class="news-bb-suggestion__description">${suggestion.description}</span>
                </button>
            `).join('');
        };

        const generateSuggestions = (issue) => {
            if (!issue) {
                return [];
            }

            const suggestions = [];

            if (issue.type === 'missingClosing') {
                if (issue.tag) {
                    suggestions.push({
                        title: `Füge [/${issue.tag}] hinzu`,
                        description: 'Ergänzt das fehlende Ende am Textende.',
                        action: {
                            type: 'append',
                            text: `[/${issue.tag}]`
                        },
                    });
                }
                if (typeof issue.openStart === 'number') {
                    suggestions.push({
                        title: `Entferne [${issue.tag}]`,
                        description: 'Löscht die offene Eröffnung, wenn sie nicht gebraucht wird.',
                        action: {
                            type: 'remove',
                            start: issue.openStart,
                            end: issue.openStart + (issue.openLength ?? 0),
                        },
                    });
                }
            } else if (issue.type === 'unexpectedClosing') {
                if (issue.expected) {
                    suggestions.push({
                        title: `Schließe [/${issue.expected}] zuerst`,
                        description: `Fügt [/${issue.expected}] direkt vor [/${issue.tag}] ein.`,
                        action: {
                            type: 'insert',
                            text: `[/${issue.expected}]`,
                            position: issue.matchStart
                        },
                    });
                }
                if (issue.tag) {
                    suggestions.push({
                        title: `Entferne [/${issue.tag}]`,
                        description: 'Löscht den fehlerhaften Tag und lässt den Rest unberührt.',
                        action: {
                            type: 'remove',
                            start: issue.matchStart,
                            end: issue.matchStart + (issue.matchLength ?? 0),
                        },
                    });
                    suggestions.push({
                        title: `Ergänze [${issue.tag}]`,
                        description: 'Wenn die Öffnung fehlt, wird sie hier eingefügt.',
                        action: {
                            type: 'insert',
                            text: `[${issue.tag}]`,
                            position: issue.matchStart
                        },
                    });
                }
            }

            return suggestions.slice(0, 4);
        };

        const applySuggestionAction = (action) => {
            if (!action) {
                return;
            }
            let newValue = editor.value;
            let cursor = editor.selectionStart ?? newValue.length;

            if (action.type === 'insert') {
                const position = Math.max(0, Math.min(newValue.length, action.position ?? cursor));
                newValue = newValue.slice(0, position) + action.text + newValue.slice(position);
                cursor = position + (action.text?.length ?? 0);
            } else if (action.type === 'append') {
                const position = newValue.length;
                newValue = newValue + action.text;
                cursor = position + (action.text?.length ?? 0);
            } else if (action.type === 'remove') {
                const start = Math.max(0, Math.min(newValue.length, action.start ?? 0));
                const end = Math.max(start, Math.min(newValue.length, action.end ?? start));
                newValue = newValue.slice(0, start) + newValue.slice(end);
                cursor = start;
            }

            editor.value = newValue;
            editor.focus();
            editor.setSelectionRange(cursor, cursor);
            editor.dispatchEvent(new Event('input'));
        };

        const validateBBCode = (content) => {
            const stack = [];
            const selfClosing = new Set(['hr', 'icon']);
            const regex = /\[(\/?)([a-z0-9-]+)(?:=[^\]]+)?\]/gi;
            let match;

            while ((match = regex.exec(content)) !== null) {
                const isClosing = Boolean(match[1]);
                const tag = (match[2] ?? '').toLowerCase();

                if (!tag) {
                    continue;
                }

                if (selfClosing.has(tag)) {
                    continue;
                }

                if (isClosing) {
                    if (stack.length === 0) {
                        return {
                            valid: false,
                            message: `Der Tag [/${tag}] hat keine passende Öffnung.`,
                            issue: {
                                type: 'unexpectedClosing',
                                tag,
                                matchStart: match.index,
                                matchLength: match[0].length,
                            },
                        };
                    }

                    const expectedEntry = stack[stack.length - 1];
                    const expectedTag = expectedEntry.tag;

                    if (expectedTag !== tag) {
                        return {
                            valid: false,
                            message: `Der Tag [/${tag}] schließt [${expectedTag}]; bitte zuerst [/${expectedTag}] nutzen.`,
                            issue: {
                                type: 'unexpectedClosing',
                                tag,
                                expected: expectedTag,
                                matchStart: match.index,
                                matchLength: match[0].length,
                            },
                        };
                    }

                    stack.pop();
                } else {
                    stack.push({
                        tag,
                        start: match.index,
                        length: match[0].length
                    });
                }
            }

            if (stack.length > 0) {
                const last = stack[stack.length - 1];
                return {
                    valid: false,
                    message: `Der Tag [${last.tag}] wurde nicht geschlossen.`,
                    issue: {
                        type: 'missingClosing',
                        tag: last.tag,
                        openStart: last.start,
                        openLength: last.length,
                    },
                };
            }

            return {
                valid: true
            };
        };

        const updateWarning = () => {
            const validation = validateBBCode(editor.value);
            setWarning(validation.valid ? null : validation.message);
            const suggestions = validation.issue ? generateSuggestions(validation.issue) : [];
            renderSuggestions(suggestions);
        };

        const updateCounter = () => {
            counter && (counter.textContent = editor.value.length);
            updateWarning();
        };
        updateCounter();
        editor.addEventListener('input', updateCounter);
        suggestionsList?.addEventListener('click', (event) => {
            const clicked = event.target instanceof Element ? event.target : null;
            const button = clicked?.closest('[data-suggestion-index]');
            if (!button) {
                return;
            }

            const index = Number(button.dataset.suggestionIndex ?? -1);
            const suggestion = latestSuggestions[index];
            if (!suggestion) {
                return;
            }

            applySuggestionAction(suggestion.action);
        });

        tagsInput?.addEventListener('input', () => {
            syncTagBadges();
        });
        syncTagBadges();
        openPreviewBtn?.addEventListener('click', openPreview);
        previewCloseButtons.forEach(button => button.addEventListener('click', closePreview));
        previewModal?.addEventListener('click', (event) => {
            if (event.target === previewModal) {
                closePreview();
            }
        });
        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape') {
                closePreview();
            }
        });

        const insertSnippet = (snippet) => {
            const start = editor.selectionStart;
            const end = editor.selectionEnd;
            const value = editor.value;
            editor.value = value.slice(0, start) + snippet + value.slice(end);
            const cursor = start + snippet.length;
            editor.setSelectionRange(cursor, cursor);
            editor.focus();
            editor.dispatchEvent(new Event('input'));
        };

        const runCommand = (command) => {
            if (typeof document.queryCommandSupported === 'function' && !document.queryCommandSupported(command)) {
                return;
            }
            editor.focus();
            document.execCommand(command);
            updateCounter();
        };

        document.querySelector('[data-bb-undo]')?.addEventListener('click', () => {
            runCommand('undo');
        });

        document.querySelector('[data-bb-redo]')?.addEventListener('click', () => {
            runCommand('redo');
        });

        document.querySelectorAll('[data-bb-tag]').forEach(button => {
            button.addEventListener('click', () => {
                const tag = button.dataset.bbTag;
                const selection = editor.value.substring(editor.selectionStart, editor.selectionEnd);
                insertSnippet(`[${tag}]${selection}[/${tag}]`);
            });
        });

        document.querySelectorAll('[data-bb-heading]').forEach(button => {
            button.addEventListener('click', () => {
                const level = button.dataset.bbHeading;
                insertSnippet(`[${level}]Text hier... [/${level}]`);
            });
        });

        document.querySelectorAll('[data-bb-align]').forEach(button => {
            button.addEventListener('click', () => {
                const direction = button.dataset.bbAlign;
                insertSnippet(`[align=${direction}]Text hier...[/align]`);
            });
        });

        document.querySelector('[data-bb-link]')?.addEventListener('click', () => {
            const url = prompt('URL eingeben');
            if (!url) return;
            const label = prompt('Linktext (optional)');
            const snippet = label ? `[url=${url}]${label}[/url]` : `[url]${url}[/url]`;
            insertSnippet(snippet);
        });

        const iconValueInput = document.getElementById('news-icon-value');
        const iconPreview = document.getElementById('news-icon-preview');
        const iconPreviewSymbol = document.getElementById('news-icon-preview-icon');
        const iconModal = document.getElementById('iconModal');
        const iconSearch = document.getElementById('iconSearch');
        const iconOptions = Array.from(document.querySelectorAll('.icon-option'));
        const openIconModalBtn = document.getElementById('openIconModal');

        const setPreview = (name, prefix = 'fas') => {
            if (iconPreview) {
                iconPreview.textContent = name || 'kein Icon gewählt';
            }
            if (iconPreviewSymbol) {
                const iconName = name && name !== 'kein Icon gewählt' ? name : 'circle';
                iconPreviewSymbol.className = `${prefix} fa-${iconName} icon-option__symbol`;
            }
        };

        const clearActiveIcons = () => {
            iconOptions.forEach(option => option.classList.remove('icon-option--active'));
        };

        const activateOption = (option) => {
            clearActiveIcons();
            option?.classList.add('icon-option--active');
        };

        const applySelection = (option) => {
            if (!option) {
                return;
            }
            iconValueInput.value = option.dataset.icon ?? '';
            setPreview(option.dataset.icon, option.dataset.prefix);
            activateOption(option);
        };

        const updateIconPreviewFromInput = () => {
            const iconName = iconValueInput?.value?.trim();
            if (!iconName) {
                setPreview('kein Icon gewählt', 'fas');
                clearActiveIcons();
                return;
            }

            const match = iconOptions.find(option => option.dataset.icon === iconName);
            if (match) {
                activateOption(match);
                setPreview(match.dataset.icon, match.dataset.prefix);
            } else {
                setPreview(iconName, 'fas');
                clearActiveIcons();
            }
        };

        const filterIcons = (value = '') => {
            const query = value.trim().toLowerCase();
            iconOptions.forEach(option => {
                const haystack = option.dataset.search?.toLowerCase() ?? '';
                option.style.display = (!query || haystack.includes(query)) ? '' : 'none';
            });
        };

        const openModal = () => {
            iconModal?.classList.remove('hidden');
            openIconModalBtn?.setAttribute('aria-expanded', 'true');
            setTimeout(() => iconSearch?.focus(), 0);
        };

        const closeModal = () => {
            iconModal?.classList.add('hidden');
            openIconModalBtn?.setAttribute('aria-expanded', 'false');
            if (iconSearch) {
                iconSearch.value = '';
            }
            filterIcons('');
        };

        iconOptions.forEach(option => {
            option.addEventListener('click', () => {
                applySelection(option);
                closeModal();
            });
        });

        iconSearch?.addEventListener('input', (event) => {
            filterIcons(event.target.value);
        });

        openIconModalBtn?.addEventListener('click', openModal);

        document.querySelectorAll('[data-icon-modal-close]').forEach(button => {
            button.addEventListener('click', closeModal);
        });

        iconModal?.addEventListener('click', (event) => {
            if (event.target === iconModal) {
                closeModal();
            }
        });

        document.querySelector('[data-bb-icon]')?.addEventListener('click', () => {
            const iconName = iconValueInput?.value?.trim();
            if (!iconName) {
                window.alert('Bitte wähle ein Icon aus, bevor du es einfügst.');
                return;
            }

            insertSnippet(`[icon=${iconName}]`);
        });

        updateIconPreviewFromInput();
    });
</script>
@endpush