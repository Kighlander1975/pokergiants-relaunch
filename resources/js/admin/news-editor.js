import { convertBBToPreviewHtml, validateBBCode } from "../shared/bbParser";
import BBEditor from "../shared/bbEditor";

const safeParseJson = (value) => {
    if (!value) {
        return {};
    }
    try {
        return JSON.parse(value);
    } catch (error) {
        console.error("news-editor: failed to parse JSON data", error);
        return {};
    }
};

const initNewsEditor = () => {
    const editor = document.getElementById("news-content");
    if (!editor) {
        return;
    }

    // Initialize BBEditor with toolbar disabled since we have custom toolbar
    const bbEditor = new BBEditor(editor, {
        mode: "full",
        enableToolbar: false,
        enableCounter: false, // We have custom counter
        enablePreview: false, // We have custom preview
    });

    const counter = document.getElementById("news-content-count");
    const warningEl = document.getElementById("news-bb-warning");
    const suggestionsWrapper = document.getElementById("news-bb-suggestions");
    const suggestionsList = document.getElementById("news-bb-suggestions-list");
    let latestSuggestions = [];
    const tagsInput = document.getElementById("tags");
    const tagsBadgeContainer = document.getElementById("news-tags-badges");
    const titleInput = document.getElementById("title");
    const authorInput = document.getElementById("author");
    const externalAuthorInput = document.getElementById("author_external");
    const categorySelect = document.getElementById("category");
    const publishedToggle = document.getElementById("published");
    const autoPublishInput = document.getElementById("auto_publish_at");
    const sourceTextInput = document.getElementById("source_text");
    const sourceUrlInput = document.getElementById("source_url");
    const previewModal = document.getElementById("news-preview-modal");
    const previewIconContainer = document.getElementById("news-preview-icon");
    const previewTitleEl = document.getElementById("news-preview-card-title");
    const previewMetaEl = document.getElementById("news-preview-card-meta");
    const previewBodyEl = document.getElementById("news-preview-card-body");
    const previewSourceEl = document.getElementById("news-preview-card-source");
    const previewTagsEl = document.getElementById("news-preview-card-tags");
    const previewCategoryLabels = safeParseJson(
        previewModal?.dataset.categoryLabels
    );
    const previewExternalCategory =
        previewModal?.dataset.externalCategory ?? null;
    const openPreviewBtn = document.getElementById("openNewsPreview");
    const previewCloseButtons = document.querySelectorAll(
        "[data-news-preview-close]"
    );
    const iconPreviewSymbol = document.getElementById("news-icon-preview-icon");

    const setWarning = (message) => {
        if (!warningEl) {
            return;
        }
        if (message) {
            warningEl.textContent = `BB-Code nicht korrekt verschachtelt: ${message}`;
            warningEl.classList.remove("hidden");
        } else {
            warningEl.textContent = "";
            warningEl.classList.add("hidden");
        }
    };

    const formatPreviewDate = (value) => {
        if (!value) {
            return null;
        }
        const parsed = new Date(value);
        if (Number.isNaN(parsed)) {
            return null;
        }
        return new Intl.DateTimeFormat("de-DE", {
            dateStyle: "medium",
            timeStyle: "short",
        }).format(parsed);
    };

    const buildPreviewMeta = () => {
        const parts = [];
        const categoryValue = categorySelect?.value;
        const categoryLabel = categoryValue
            ? previewCategoryLabels?.[categoryValue] ??
              categorySelect.options[categorySelect.selectedIndex]?.text
            : null;
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
            parts.push("Status: veröffentlicht");
        } else {
            parts.push("Status: Entwurf");
        }
        const autoPublishValue = autoPublishInput?.value;
        const formatted = formatPreviewDate(autoPublishValue);
        if (formatted) {
            parts.push(`Geplant: ${formatted}`);
        }
        return parts.join(" · ");
    };

    const updatePreviewSource = (isExternal) => {
        if (!previewSourceEl) {
            return;
        }
        previewSourceEl.innerHTML = "";
        const label = sourceTextInput?.value?.trim();
        const url = sourceUrlInput?.value?.trim();
        if (!label && !url) {
            previewSourceEl.textContent = "Keine Quelle angegeben.";
            return;
        }
        const prefix = document.createElement("span");
        prefix.textContent = "Quelle: ";
        previewSourceEl.appendChild(prefix);
        if (url) {
            const link = document.createElement("a");
            link.href = url;
            link.target = "_blank";
            link.rel = "noreferrer";
            link.textContent = label || url;
            previewSourceEl.appendChild(link);
        } else if (label) {
            const text = document.createElement("span");
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

    const parseTags = (value) =>
        (value ?? "")
            .split(",")
            .map((tag) => tag.trim())
            .filter(Boolean);

    const renderTagBadges = (container, tags) => {
        if (!container) {
            return;
        }
        if (!tags.length) {
            container.innerHTML = "";
            return;
        }
        container.innerHTML = tags
            .map((tag) => `<span class="news-tag-badge">${tag}</span>`)
            .join("");
    };

    const syncTagBadges = () => {
        const tags = parseTags(tagsInput?.value);
        renderTagBadges(tagsBadgeContainer, tags);
        renderTagBadges(previewTagsEl, tags);
    };

    const updatePreviewCard = () => {
        if (previewTitleEl) {
            previewTitleEl.textContent =
                titleInput?.value?.trim() || "Unbenannte News";
        }
        if (previewMetaEl) {
            previewMetaEl.textContent =
                buildPreviewMeta() || "Kategorie · Autor";
        }
        if (previewBodyEl) {
            previewBodyEl.innerHTML = convertBBToPreviewHtml(editor.value);
        }
        const categoryValue = categorySelect?.value;
        const isExternal = Boolean(
            categoryValue && categoryValue === previewExternalCategory
        );
        updatePreviewSource(isExternal);
        syncPreviewIcon();
        syncTagBadges();
    };

    const closePreview = () => {
        previewModal?.classList.add("hidden");
    };

    const openPreview = () => {
        updatePreviewCard();
        previewModal?.classList.remove("hidden");
    };

    const renderSuggestions = (suggestions) => {
        latestSuggestions = suggestions;
        if (!suggestionsWrapper || !suggestionsList) {
            return;
        }
        if (!suggestions.length) {
            suggestionsWrapper.classList.add("hidden");
            suggestionsList.innerHTML = "";
            return;
        }
        suggestionsWrapper.classList.remove("hidden");
        suggestionsList.innerHTML = suggestions
            .map(
                (suggestion, index) => `
                <button type="button" class="news-bb-suggestion" data-suggestion-index="${index}">
                    <span class="news-bb-suggestion__title">${suggestion.title}</span>
                    <span class="news-bb-suggestion__description">${suggestion.description}</span>
                </button>
            `
            )
            .join("");
    };

    const generateSuggestions = (issue) => {
        if (!issue) {
            return [];
        }

        const suggestions = [];

        if (issue.type === "missingClosing") {
            if (issue.tag) {
                suggestions.push({
                    title: `Füge [/${issue.tag}] hinzu`,
                    description: "Ergänzt das fehlende Ende am Textende.",
                    action: {
                        type: "append",
                        text: `[/${issue.tag}]`,
                    },
                });
            }
            if (typeof issue.openStart === "number") {
                suggestions.push({
                    title: `Entferne [${issue.tag}]`,
                    description:
                        "Löscht die offene Eröffnung, wenn sie nicht gebraucht wird.",
                    action: {
                        type: "remove",
                        start: issue.openStart,
                        end: issue.openStart + (issue.openLength ?? 0),
                    },
                });
            }
        } else if (issue.type === "unexpectedClosing") {
            if (issue.expected) {
                suggestions.push({
                    title: `Schließe [/${issue.expected}] zuerst`,
                    description: `Fügt [/${issue.expected}] direkt vor [/${issue.tag}] ein.`,
                    action: {
                        type: "insert",
                        text: `[/${issue.expected}]`,
                        position: issue.matchStart,
                    },
                });
            }
            if (issue.tag) {
                suggestions.push({
                    title: `Entferne [/${issue.tag}]`,
                    description:
                        "Löscht den fehlerhaften Tag und lässt den Rest unberührt.",
                    action: {
                        type: "remove",
                        start: issue.matchStart,
                        end: issue.matchStart + (issue.matchLength ?? 0),
                    },
                });
                suggestions.push({
                    title: `Ergänze [${issue.tag}]`,
                    description:
                        "Wenn die Öffnung fehlt, wird sie hier eingefügt.",
                    action: {
                        type: "insert",
                        text: `[${issue.tag}]`,
                        position: issue.matchStart,
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

        if (action.type === "insert") {
            const position = Math.max(
                0,
                Math.min(newValue.length, action.position ?? cursor)
            );
            newValue =
                newValue.slice(0, position) +
                action.text +
                newValue.slice(position);
            cursor = position + (action.text?.length ?? 0);
        } else if (action.type === "append") {
            const position = newValue.length;
            newValue = newValue + action.text;
            cursor = position + (action.text?.length ?? 0);
        } else if (action.type === "remove") {
            const start = Math.max(
                0,
                Math.min(newValue.length, action.start ?? 0)
            );
            const end = Math.max(
                start,
                Math.min(newValue.length, action.end ?? start)
            );
            newValue = newValue.slice(0, start) + newValue.slice(end);
            cursor = start;
        }

        editor.value = newValue;
        editor.focus();
        editor.setSelectionRange(cursor, cursor);
        editor.dispatchEvent(new Event("input"));
    };

    const updateWarning = () => {
        const validation = validateBBCode(editor.value);
        setWarning(validation.valid ? null : validation.message);
        const suggestions = validation.issue
            ? generateSuggestions(validation.issue)
            : [];
        renderSuggestions(suggestions);
    };

    const updateCounter = () => {
        if (counter) {
            counter.textContent = String(editor.value.length);
        }
        updateWarning();
    };
    updateCounter();

    editor.addEventListener("input", updateCounter);
    suggestionsList?.addEventListener("click", (event) => {
        const clicked = event.target instanceof Element ? event.target : null;
        const button = clicked?.closest("[data-suggestion-index]");
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

    tagsInput?.addEventListener("input", () => {
        syncTagBadges();
    });
    syncTagBadges();
    openPreviewBtn?.addEventListener("click", openPreview);
    previewCloseButtons.forEach((button) =>
        button.addEventListener("click", closePreview)
    );
    previewModal?.addEventListener("click", (event) => {
        if (event.target === previewModal) {
            closePreview();
        }
    });
    document.addEventListener("keydown", (event) => {
        if (event.key === "Escape") {
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
        editor.dispatchEvent(new Event("input"));
    };

    const runCommand = (command) => {
        if (
            typeof document.queryCommandSupported === "function" &&
            !document.queryCommandSupported(command)
        ) {
            return;
        }
        editor.focus();
        document.execCommand(command);
        updateCounter();
    };

    document.querySelector("[data-bb-undo]")?.addEventListener("click", () => {
        runCommand("undo");
    });

    document.querySelector("[data-bb-redo]")?.addEventListener("click", () => {
        runCommand("redo");
    });

    document.querySelectorAll("[data-bb-tag]").forEach((button) => {
        button.addEventListener("click", () => {
            const tag = button.dataset.bbTag;
            const selection = editor.value.substring(
                editor.selectionStart,
                editor.selectionEnd
            );
            insertSnippet(`[${tag}]${selection}[/${tag}]`);
        });
    });

    document.querySelectorAll("[data-bb-heading]").forEach((button) => {
        button.addEventListener("click", () => {
            const level = button.dataset.bbHeading;
            insertSnippet(`[${level}]Text hier... [/${level}]`);
        });
    });

    document.querySelectorAll("[data-bb-align]").forEach((button) => {
        button.addEventListener("click", () => {
            const direction = button.dataset.bbAlign;
            insertSnippet(`[align=${direction}]Text hier...[/align]`);
        });
    });

    document.querySelectorAll("[data-bb-suit]").forEach((button) => {
        button.addEventListener("click", () => {
            const type = button.dataset.bbSuit;
            insertSnippet(`[suit=${type}]`);
        });
    });

    document.querySelector("[data-bb-link]")?.addEventListener("click", () => {
        const url = prompt("URL eingeben");
        if (!url) return;
        const label = prompt("Linktext (optional)");
        const snippet = label
            ? `[url=${url}]${label}[/url]`
            : `[url]${url}[/url]`;
        insertSnippet(snippet);
    });

    const iconValueInput = document.getElementById("news-icon-value");
    const iconPreview = document.getElementById("news-icon-preview");
    const iconModal = document.getElementById("iconModal");
    const iconSearch = document.getElementById("iconSearch");
    const iconOptions = Array.from(document.querySelectorAll(".icon-option"));
    const openIconModalBtn = document.getElementById("openIconModal");

    const setPreview = (name, prefix = "fas") => {
        if (iconPreview) {
            iconPreview.textContent = name || "kein Icon gewählt";
        }
        if (iconPreviewSymbol) {
            const iconName =
                name && name !== "kein Icon gewählt" ? name : "circle";
            iconPreviewSymbol.className = `${prefix} fa-${iconName} icon-option__symbol`;
        }
    };

    const clearActiveIcons = () => {
        iconOptions.forEach((option) =>
            option.classList.remove("icon-option--active")
        );
    };

    const activateOption = (option) => {
        clearActiveIcons();
        option?.classList.add("icon-option--active");
    };

    const applySelection = (option) => {
        if (!option) {
            return;
        }
        iconValueInput.value = option.dataset.icon ?? "";
        setPreview(option.dataset.icon, option.dataset.prefix);
        activateOption(option);
    };

    const updateIconPreviewFromInput = () => {
        const iconName = iconValueInput?.value?.trim();
        if (!iconName) {
            setPreview("kein Icon gewählt", "fas");
            clearActiveIcons();
            return;
        }

        const match = iconOptions.find(
            (option) => option.dataset.icon === iconName
        );
        if (match) {
            activateOption(match);
            setPreview(match.dataset.icon, match.dataset.prefix);
        } else {
            setPreview(iconName, "fas");
            clearActiveIcons();
        }
    };

    const filterIcons = (value = "") => {
        const query = value.trim().toLowerCase();
        iconOptions.forEach((option) => {
            const haystack = option.dataset.search?.toLowerCase() ?? "";
            option.style.display =
                !query || haystack.includes(query) ? "" : "none";
        });
    };

    const openModal = () => {
        iconModal?.classList.remove("hidden");
        openIconModalBtn?.setAttribute("aria-expanded", "true");
        setTimeout(() => iconSearch?.focus(), 0);
    };

    const closeModal = () => {
        iconModal?.classList.add("hidden");
        openIconModalBtn?.setAttribute("aria-expanded", "false");
        if (iconSearch) {
            iconSearch.value = "";
        }
        filterIcons("");
    };

    iconOptions.forEach((option) => {
        option.addEventListener("click", () => {
            applySelection(option);
            closeModal();
        });
    });

    iconSearch?.addEventListener("input", (event) => {
        filterIcons(event.target.value);
    });

    openIconModalBtn?.addEventListener("click", openModal);

    document.querySelectorAll("[data-icon-modal-close]").forEach((button) => {
        button.addEventListener("click", closeModal);
    });

    iconModal?.addEventListener("click", (event) => {
        if (event.target === iconModal) {
            closeModal();
        }
    });

    document.querySelector("[data-bb-icon]")?.addEventListener("click", () => {
        const iconName = iconValueInput?.value?.trim();
        if (!iconName) {
            window.alert("Bitte wähle ein Icon aus, bevor du es einfügst.");
            return;
        }

        insertSnippet(`[icon=${iconName}]`);
    });

    updateIconPreviewFromInput();
};

document.addEventListener("DOMContentLoaded", initNewsEditor);
