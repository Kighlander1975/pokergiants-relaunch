document.addEventListener("DOMContentLoaded", function () {
    const tabButtons = document.querySelectorAll(".tab-button");
    const tabContents = document.querySelectorAll(".tab-content");
    const contentTypeField = document.getElementById("content_type");

    // Tab switching functionality
    tabButtons.forEach((button) => {
        button.addEventListener("click", function () {
            const tabName = this.getAttribute("data-tab");

            // Update hidden content_type field
            contentTypeField.value = tabName;

            // Remove active class from all buttons
            tabButtons.forEach((btn) => {
                btn.classList.remove(
                    "active",
                    "bg-white",
                    "text-gray-900",
                    "shadow-sm"
                );
                btn.classList.add("text-gray-500");
            });

            // Add active class to clicked button
            this.classList.add(
                "active",
                "bg-white",
                "text-gray-900",
                "shadow-sm"
            );
            this.classList.remove("text-gray-500");

            // Hide all tab contents
            tabContents.forEach((content) => {
                content.classList.add("hidden");
            });

            // Show selected tab content
            const activeContent = document.getElementById("content-" + tabName);
            if (activeContent) {
                activeContent.classList.remove("hidden");

                // Dispatch custom event to initialize editor for the newly active tab
                const event = new CustomEvent("tabActivated", {
                    detail: { tabName: tabName },
                });
                document.dispatchEvent(event);
            }
        });
    });

    // HTML Editor enhancements
    const htmlTextarea = document.getElementById("content_html");
    const formatButton = document.getElementById("format-html");
    const previewButton = document.getElementById("preview-html");
    const previewModal = document.getElementById("html-preview-modal");
    const previewContent = document.getElementById("html-preview-content");
    const closePreview = document.getElementById("close-preview");

    let isFirstFocus = true; // Flag to track first focus

    // Format HTML function
    function formatHTML(html) {
        if (!html.trim()) return html;

        // Basic HTML formatting - improve indentation
        let formatted = html
            .replace(/></g, ">\n<") // Add line breaks between tags
            .split("\n")
            .map((line) => line.trim())
            .filter((line) => line.length > 0)
            .map((line, index, arr) => {
                const indentLevel = getIndentLevel(line, arr, index);
                return "    ".repeat(indentLevel) + line;
            })
            .join("\n");

        return formatted;
    }

    function getIndentLevel(line, allLines, currentIndex) {
        let level = 0;

        // Count opening tags before this line
        for (let i = 0; i < currentIndex; i++) {
            const prevLine = allLines[i];
            if (
                prevLine.includes("<") &&
                !prevLine.includes("</") &&
                !prevLine.includes("/>")
            ) {
                level++;
            }
            if (prevLine.includes("</")) {
                level = Math.max(0, level - 1);
            }
        }

        // Adjust for current line
        if (line.includes("</")) {
            level = Math.max(0, level - 1);
        }

        return level;
    }

    // Format button click
    if (formatButton) {
        formatButton.addEventListener("click", function () {
            const currentValue = htmlTextarea.value;
            const formatted = formatHTML(currentValue);
            htmlTextarea.value = formatted;
        });
    }

    // Preview button click
    if (previewButton) {
        previewButton.addEventListener("click", function () {
            const htmlContent = htmlTextarea.value;
            previewContent.innerHTML =
                htmlContent ||
                '<p class="text-gray-500 italic">Keine Inhalte zum Anzeigen</p>';
            previewModal.classList.remove("hidden");
        });
    }

    // Close preview modal
    if (closePreview) {
        closePreview.addEventListener("click", function () {
            previewModal.classList.add("hidden");
        });
    }

    // Close modal when clicking outside
    if (previewModal) {
        previewModal.addEventListener("click", function (e) {
            if (e.target === previewModal) {
                previewModal.classList.add("hidden");
            }
        });
    }

    // Auto-format HTML content before form submission
    const widgetForm = document.querySelector('form[method="POST"]');
    if (widgetForm && htmlTextarea) {
        widgetForm.addEventListener("submit", function (e) {
            // Format HTML content before saving
            if (htmlTextarea.value.trim()) {
                htmlTextarea.value = formatHTML(htmlTextarea.value);
            }
        });
    }

    // Auto-indent on Tab key and auto-format on first focus
    if (htmlTextarea) {
        htmlTextarea.addEventListener("keydown", function (e) {
            if (e.key === "Tab") {
                e.preventDefault();
                const start = this.selectionStart;
                const end = this.selectionEnd;

                // Insert 4 spaces
                this.value =
                    this.value.substring(0, start) +
                    "    " +
                    this.value.substring(end);
                this.selectionStart = this.selectionEnd = start + 4;
            }
        });

        // Auto-format on first focus
        htmlTextarea.addEventListener("focus", function () {
            if (isFirstFocus && this.value.trim()) {
                const formatted = formatHTML(this.value);
                if (formatted !== this.value) {
                    this.value = formatted;
                }
                isFirstFocus = false;
            }
        });
    }
});
