import {
    convertBBToPreviewHtml,
    validateBBCode,
    getAvailableTags,
} from "./bbParser";

class BBEditor {
    constructor(textarea, options = {}) {
        this.textarea = textarea;
        this.options = {
            mode: "full", // 'full', 'minimal', 'custom'
            customTags: [], // for mode 'custom'
            enablePreview: true,
            enableCounter: true,
            enableToolbar: true,
            maxLength: null,
            ...options,
        };

        this.allowedTags = this.getAllowedTags();
        this.history = [textarea.value]; // History for undo/redo
        this.historyIndex = 0;
        this.init();
    }

    getAllowedTags() {
        const allTags = getAvailableTags();
        switch (this.options.mode) {
            case "minimal":
                return ["suit", "b", "i"];
            case "custom":
                return this.options.customTags;
            case "full":
            default:
                return allTags;
        }
    }

    init() {
        if (this.options.enableToolbar) {
            this.createToolbar();
        }
        if (this.options.enableCounter) {
            this.createCounter();
        }
        if (this.options.enablePreview) {
            this.createPreview();
        }
        this.bindEvents();
    }

    createToolbar() {
        const toolbar = document.createElement("div");
        toolbar.className = "bb-toolbar flex flex-wrap gap-1 mb-2";
        this.allowedTags.forEach((tag) => {
            const button = document.createElement("button");
            button.type = "button";
            button.className =
                "bb-button px-2 py-1 bg-gray-200 hover:bg-gray-300 rounded text-sm flex items-center justify-center";
            button.dataset.tag = tag;
            button.title = tag.toUpperCase(); // Tooltip für alle Tags
            button.addEventListener("click", () => this.insertTag(tag));

            // Spezielle Behandlung für bestimmte Tags
            if (tag === "ul") {
                button.innerHTML =
                    '<span class="text-xs">•</span><span class="text-xs">•</span><span class="text-xs">•</span>';
                button.title = "Ungeordnete Liste (UL)";
            } else if (tag === "ol") {
                button.innerHTML =
                    '<span class="text-xs">1.</span><span class="text-xs">2.</span><span class="text-xs">3.</span>';
                button.title = "Geordnete Liste (OL)";
            } else if (tag === "hr") {
                button.innerHTML = "―";
                button.title = "Horizontale Linie (HR)";
            } else if (tag === "p") {
                button.innerHTML = "¶";
                button.title = "Absatz (P)";
            } else if (tag === "h2") {
                button.innerHTML = "H₂";
                button.title = "Überschrift 2 (H2)";
            } else if (tag === "h3") {
                button.innerHTML = "H₃";
                button.title = "Überschrift 3 (H3)";
            } else if (tag === "h4") {
                button.innerHTML = "H₄";
                button.title = "Überschrift 4 (H4)";
            } else if (tag === "h5") {
                button.innerHTML = "H₅";
                button.title = "Überschrift 5 (H5)";
            } else if (tag === "b") {
                button.innerHTML = "<strong>B</strong>";
                button.title = "Fett (Bold)";
            } else if (tag === "i") {
                button.innerHTML = "<em>I</em>";
                button.title = "Kursiv (Italic)";
            } else if (tag === "u") {
                button.innerHTML =
                    '<span style="text-decoration:underline">U</span>';
                button.title = "Unterstrichen (Underline)";
            } else {
                // Standard für alle anderen Tags
                button.textContent = tag.toUpperCase();
            }

            toolbar.appendChild(button);
        });
        this.textarea.parentNode.insertBefore(toolbar, this.textarea);
    }

    createCounter() {
        this.counter = document.createElement("div");
        this.counter.className = "bb-counter text-sm text-gray-500 mt-1";
        this.updateCounter();
        this.textarea.parentNode.insertBefore(
            this.counter,
            this.textarea.nextSibling
        );
    }

    createPreview() {
        this.previewBtn = document.createElement("button");
        this.previewBtn.type = "button";
        this.previewBtn.className =
            "bb-preview-btn px-4 py-2 bg-blue-500 text-white rounded mt-2";
        this.previewBtn.textContent = "Vorschau";
        this.previewBtn.addEventListener("click", () => this.showPreview());
        this.textarea.parentNode.insertBefore(
            this.previewBtn,
            this.textarea.nextSibling
        );

        this.previewModal = document.createElement("div");
        this.previewModal.className =
            "bb-preview-modal fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50";
        this.previewModal.innerHTML = `
            <div class="bg-white p-6 rounded max-w-2xl w-full max-h-96 overflow-y-auto">
                <h3 class="text-lg font-bold mb-4">Vorschau</h3>
                <div class="bb-preview-content"></div>
                <button class="bb-preview-close px-4 py-2 bg-red-500 text-white rounded mt-4">Schließen</button>
            </div>
        `;
        document.body.appendChild(this.previewModal);

        this.previewModal
            .querySelector(".bb-preview-close")
            .addEventListener("click", () => {
                this.previewModal.classList.add("hidden");
            });
    }

    bindEvents() {
        this.textarea.addEventListener("input", () => {
            this.validate();
            if (this.options.enableCounter) {
                this.updateCounter();
            }
            this.addToHistory();
        });
    }

    insertTag(tag) {
        const start = this.textarea.selectionStart;
        const end = this.textarea.selectionEnd;
        const selectedText = this.textarea.value.substring(start, end);

        let replacement;

        // Special handling for lists
        if (tag === "ul" || tag === "ol") {
            if (selectedText) {
                // With selected text: split by line breaks and create list items
                const lines = selectedText
                    .split("\n")
                    .filter((line) => line.trim());
                const listItems = lines
                    .map((line) => `\n[*]${line.trim()}`)
                    .join("");
                replacement = `[${tag}]${listItems}\n[/${tag}]`;
            } else {
                // Without selected text: insert template with two example items
                replacement = `[${tag}]\n[*]Erster Punkt\n[*]Zweiter Punkt\n[/${tag}]`;
            }
        } else {
            // Default behavior for other tags
            const tagOpen = `[${tag}]`;
            const tagClose = `[/${tag}]`;
            replacement = selectedText
                ? `${tagOpen}${selectedText}${tagClose}`
                : `${tagOpen}${tagClose}`;
        }

        this.textarea.setRangeText(replacement, start, end, "end");
        this.textarea.focus();
        this.validate();
        this.addToHistory();
    }

    insertText(text) {
        const start = this.textarea.selectionStart;
        const end = this.textarea.selectionEnd;
        this.textarea.setRangeText(text, start, end, "end");
        this.textarea.focus();
        this.validate();
        this.addToHistory();
    }

    undo() {
        if (this.historyIndex > 0) {
            this.historyIndex--;
            this.textarea.value = this.history[this.historyIndex];
            this.validate();
        }
    }

    redo() {
        if (this.historyIndex < this.history.length - 1) {
            this.historyIndex++;
            this.textarea.value = this.history[this.historyIndex];
            this.validate();
        }
    }

    addToHistory() {
        // Remove future history if we're not at the end
        this.history = this.history.slice(0, this.historyIndex + 1);
        this.history.push(this.textarea.value);
        this.historyIndex = this.history.length - 1;
        // Limit history to 50 entries
        if (this.history.length > 50) {
            this.history.shift();
            this.historyIndex--;
        }
    }

    validate() {
        const result = validateBBCode(this.textarea.value);
        // Could add visual feedback for errors
        console.log("Validation:", result);
    }

    updateCounter() {
        const length = this.textarea.value.length;
        const max = this.options.maxLength;
        this.counter.textContent = max
            ? `${length}/${max}`
            : `${length} Zeichen`;
    }

    showPreview() {
        const html = convertBBToPreviewHtml(this.textarea.value);
        this.previewModal.querySelector(".bb-preview-content").innerHTML = html;
        this.previewModal.classList.remove("hidden");
    }
}

export default BBEditor;
