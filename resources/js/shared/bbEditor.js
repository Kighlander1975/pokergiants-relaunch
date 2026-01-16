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
                "bb-button px-2 py-1 bg-gray-200 hover:bg-gray-300 rounded text-sm";
            button.textContent = tag.toUpperCase();
            button.dataset.tag = tag;
            button.addEventListener("click", () => this.insertTag(tag));
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
        const tagOpen = `[${tag}]`;
        const tagClose = `[/${tag}]`;
        const replacement = selectedText
            ? `${tagOpen}${selectedText}${tagClose}`
            : `${tagOpen}${tagClose}`;

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
