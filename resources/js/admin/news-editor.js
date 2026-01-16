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
                description: "Wenn die Öffnung fehlt, wird sie hier eingefügt.",
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

const initNewsEditor = () => {
    // Check if news editor elements exist
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
            bbEditor.insertTag(tag);
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

const initPlainEditor = () => {
    const editor = document.getElementById("plain-content");
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

    const counter = document.getElementById("plain-content-count");
    const warningEl = document.getElementById("plain-bb-warning");
    const suggestionsWrapper = document.getElementById("plain-bb-suggestions");
    const suggestionsList = document.getElementById(
        "plain-bb-suggestions-list"
    );
    let latestSuggestions = [];

    // Update counter
    const updateCounter = () => {
        if (counter) {
            counter.textContent = editor.value.length;
        }
    };

    // Validate BB code and show warnings/suggestions
    const validateAndSuggest = () => {
        const content = editor.value;
        const validation = validateBBCode(content);

        // Show/hide warning
        if (warningEl) {
            if (!validation.valid) {
                warningEl.textContent = validation.message;
                warningEl.classList.remove("hidden");
            } else {
                warningEl.classList.add("hidden");
            }
        }

        // Show/hide suggestions
        if (suggestionsWrapper && suggestionsList) {
            if (validation.issue) {
                const rawSuggestions = generateSuggestions(validation.issue);
                const suggestions = rawSuggestions.map((suggestion) => {
                    let code = editor.value;
                    if (suggestion.action) {
                        if (suggestion.action.type === "append") {
                            code += suggestion.action.text;
                        } else if (suggestion.action.type === "remove") {
                            code =
                                code.substring(0, suggestion.action.start) +
                                code.substring(suggestion.action.end);
                        } else if (suggestion.action.type === "insert") {
                            code =
                                code.substring(0, suggestion.action.position) +
                                suggestion.action.text +
                                code.substring(suggestion.action.position);
                        }
                    }
                    return {
                        label: suggestion.title,
                        code,
                    };
                });
                suggestionsList.innerHTML = "";
                suggestions.forEach((suggestion) => {
                    const button = document.createElement("button");
                    button.type = "button";
                    button.className = "news-bb-suggestions__item";
                    button.textContent = suggestion.label;
                    button.addEventListener("click", () => {
                        editor.value = suggestion.code;
                        updateCounter();
                        validateAndSuggest();
                    });
                    suggestionsList.appendChild(button);
                });
                suggestionsWrapper.classList.remove("hidden");
                latestSuggestions = suggestions;
            } else {
                suggestionsWrapper.classList.add("hidden");
                latestSuggestions = [];
            }
        }
    };

    // Event listeners
    editor.addEventListener("input", () => {
        updateCounter();
        validateAndSuggest();
    });

    // Toolbar button handlers
    const toolbar = editor
        .closest(".tab-content")
        ?.querySelector(".news-editor-toolbar");
    if (toolbar) {
        // Undo/Redo
        toolbar
            .querySelector("[data-bb-undo]")
            ?.addEventListener("click", () => bbEditor.undo());
        toolbar
            .querySelector("[data-bb-redo]")
            ?.addEventListener("click", () => bbEditor.redo());

        // Tag buttons
        toolbar.querySelectorAll("[data-bb-tag]").forEach((btn) => {
            btn.addEventListener("click", () => {
                const tag = btn.getAttribute("data-bb-tag");
                bbEditor.insertTag(tag);
            });
        });

        // Heading buttons
        toolbar.querySelectorAll("[data-bb-heading]").forEach((btn) => {
            btn.addEventListener("click", () => {
                const level = btn.getAttribute("data-bb-heading");
                insertSnippet(`[${level}]Text hier... [/${level}]`);
            });
        });

        // Align buttons
        toolbar.querySelectorAll("[data-bb-align]").forEach((btn) => {
            btn.addEventListener("click", () => {
                const align = btn.getAttribute("data-bb-align");
                bbEditor.insertAlign(align);
            });
        });

        // Link button
        toolbar
            .querySelector("[data-bb-link]")
            ?.addEventListener("click", () => {
                const url = window.prompt("Gib die URL ein:");
                if (url) {
                    const text = window.prompt(
                        "Gib den Link-Text ein:",
                        "Link"
                    );
                    if (text) {
                        bbEditor.insertLink(url, text);
                    }
                }
            });

        // Suit buttons
        toolbar.querySelectorAll("[data-bb-suit]").forEach((btn) => {
            btn.addEventListener("click", () => {
                const suit = btn.getAttribute("data-bb-suit");
                bbEditor.insertSuit(suit);
            });
        });

        // Icon picker
        const iconValueInput = document.getElementById("plain-icon-value");
        const iconPreviewIcon = document.getElementById(
            "plain-icon-preview-icon"
        );
        const iconPreviewLabel = document.getElementById("plain-icon-preview");

        const updateIconPreviewFromInput = () => {
            if (!iconValueInput || !iconPreviewIcon || !iconPreviewLabel)
                return;

            const iconName = iconValueInput.value?.trim();
            if (iconName) {
                iconPreviewIcon.className = `fas fa-${iconName}`;
                iconPreviewLabel.textContent = iconName;
            } else {
                iconPreviewIcon.className = "fas fa-circle";
                iconPreviewLabel.textContent = "kein Icon gewählt";
            }
        };

        // Icon picker modal
        const iconModal = document.getElementById("plain-icon-modal");
        const openIconModalBtn = document.getElementById("openPlainIconModal");

        if (openIconModalBtn && iconModal) {
            openIconModalBtn.addEventListener("click", () => {
                iconModal.classList.remove("hidden");
                openIconModalBtn.setAttribute("aria-expanded", "true");
            });

            // Close modal handlers
            const closeIconModal = () => {
                iconModal.classList.add("hidden");
                openIconModalBtn.setAttribute("aria-expanded", "false");
            };

            iconModal.querySelectorAll("[data-close-modal]").forEach((btn) => {
                btn.addEventListener("click", closeIconModal);
            });

            iconModal.addEventListener("click", (e) => {
                if (e.target === iconModal) {
                    closeIconModal();
                }
            });

            // Icon selection
            iconModal.querySelectorAll("[data-icon]").forEach((iconBtn) => {
                iconBtn.addEventListener("click", () => {
                    const iconName = iconBtn.getAttribute("data-icon");
                    if (iconValueInput) {
                        iconValueInput.value = iconName;
                    }
                    updateIconPreviewFromInput();
                    closeIconModal();
                });
            });
        }

        // Insert icon button
        toolbar
            .querySelector("[data-bb-icon]")
            ?.addEventListener("click", () => {
                const iconName = iconValueInput?.value?.trim();
                if (!iconName) {
                    window.alert(
                        "Bitte wähle ein Icon aus, bevor du es einfügst."
                    );
                    return;
                }

                bbEditor.insertSnippet(`[icon=${iconName}]`);
            });

        updateIconPreviewFromInput();
    }

    // Preview modal
    const previewModal = document.getElementById("plain-preview-modal");
    const openPreviewBtn = document.getElementById("openPlainPreview");

    if (previewModal && openPreviewBtn) {
        const previewBodyEl = previewModal.querySelector(
            "#plain-preview-content"
        );

        openPreviewBtn.addEventListener("click", () => {
            if (previewBodyEl) {
                previewBodyEl.innerHTML = convertBBToPreviewHtml(editor.value);
            }
            previewModal.classList.remove("hidden");
        });

        // Close modal handlers
        const closePreviewModal = () => {
            previewModal.classList.add("hidden");
        };

        previewModal.querySelectorAll("[data-close-modal]").forEach((btn) => {
            btn.addEventListener("click", closePreviewModal);
        });

        previewModal.addEventListener("click", (e) => {
            if (e.target === previewModal) {
                closePreviewModal();
            }
        });
    }

    // Initial setup
    updateCounter();
    validateAndSuggest();
};

document.addEventListener("DOMContentLoaded", () => {
    initNewsEditor();
    initPlainEditor();
    // Listen for tab activation events
    document.addEventListener("tabActivated", (event) => {
        const { tabName } = event.detail;
        if (tabName === "plain") {
            setTimeout(() => initPlainEditor(), 10);
        } else if (tabName === "news") {
            setTimeout(() => initNewsEditor(), 10);
        }
    });
});

// Export functions globally for tab switching
window.initNewsEditor = initNewsEditor;
window.initPlainEditor = initPlainEditor;
