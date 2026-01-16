import BBEditor from "../shared/bbEditor";

let isInitialized = false;

const initHeadlineEditor = () => {
    if (isInitialized) {
        console.log("Headline editor already initialized, skipping...");
        return;
    }

    // Check if headline elements exist on this page
    const headlineEditor = document.getElementById("headline_text");
    const sublineEditor = document.getElementById("subline_text");

    if (!headlineEditor && !sublineEditor) {
        // No headline elements found - silently skip initialization
        return;
    }

    console.log("Initializing headline editor...");
    isInitialized = true;

    if (headlineEditor) {
        console.log("Found headline editor");
        const headlineBBEditor = new BBEditor(headlineEditor, {
            mode: "minimal",
            enableToolbar: false,
            enableCounter: false,
            enablePreview: false,
        });

        // Setup toolbar buttons for headline
        const headlineToolbar = headlineEditor.previousElementSibling;
        console.log("Headline toolbar:", headlineToolbar);
        if (
            headlineToolbar &&
            headlineToolbar.classList.contains("headline-editor-toolbar")
        ) {
            console.log("Setting up headline toolbar");
            setupToolbar(headlineToolbar, headlineBBEditor);
        }
    }

    if (sublineEditor) {
        const sublineBBEditor = new BBEditor(sublineEditor, {
            mode: "minimal",
            enableToolbar: false,
            enableCounter: false,
            enablePreview: false,
        });

        // Setup toolbar buttons for subline
        const sublineToolbar = sublineEditor.previousElementSibling;
        if (
            sublineToolbar &&
            sublineToolbar.classList.contains("headline-editor-toolbar")
        ) {
            setupToolbar(sublineToolbar, sublineBBEditor);
        }
    }
};

const setupToolbar = (toolbar, bbEditor) => {
    if (!toolbar) return;

    // Undo/Redo buttons
    const undoBtn = toolbar.querySelector("[data-bb-undo]");
    const redoBtn = toolbar.querySelector("[data-bb-redo]");

    if (undoBtn) {
        undoBtn.addEventListener("click", () => bbEditor.undo());
    }

    if (redoBtn) {
        redoBtn.addEventListener("click", () => bbEditor.redo());
    }

    // Tag buttons (bold, italic)
    const tagButtons = toolbar.querySelectorAll("[data-bb-tag]");
    tagButtons.forEach((btn) => {
        btn.addEventListener("click", () => {
            const tag = btn.getAttribute("data-bb-tag");
            bbEditor.insertTag(tag);
        });
    });

    // Suit buttons
    const suitButtons = toolbar.querySelectorAll("[data-bb-suit]");
    suitButtons.forEach((btn) => {
        btn.addEventListener("click", () => {
            const suit = btn.getAttribute("data-bb-suit");
            console.log("Suit button clicked:", suit);
            bbEditor.insertText(`[suit=${suit}]`);
        });
    });
};

// Initialize when DOM is ready
document.addEventListener("DOMContentLoaded", initHeadlineEditor);

// Also initialize immediately if DOM is already loaded
if (document.readyState !== "loading") {
    initHeadlineEditor();
}
