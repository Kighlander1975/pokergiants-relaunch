const escapeHtml = (value = "") =>
    value
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;")
        .replace(/'/g, "&#39;");

const decodeHtmlEntities = (value = "") =>
    value
        .replace(/&amp;/g, "&")
        .replace(/&lt;/g, "<")
        .replace(/&gt;/g, ">")
        .replace(/&quot;/g, '"')
        .replace(/&#39;/g, "'");

export const convertBBToPreviewHtml = (raw) => {
    let html = escapeHtml(raw ?? "");
    html = html
        .replace(/\[b\]([\s\S]*?)\[\/b\]/gi, "<strong>$1</strong>")
        .replace(/\[i\]([\s\S]*?)\[\/i\]/gi, "<em>$1</em>")
        .replace(
            /\[u\]([\s\S]*?)\[\/u\]/gi,
            '<span style="text-decoration:underline">$1</span>'
        )
        .replace(/\[p\]([\s\S]*?)\[\/p\]/gi, "<p>$1</p>")
        .replace(
            /\[url=([^\]]+)\]([\s\S]*?)\[\/url\]/gi,
            (match, href, label) => {
                const decodedHref = decodeHtmlEntities(href);
                return `<a href="${decodedHref}" target="_blank" rel="noreferrer">${label}</a>`;
            }
        )
        .replace(/\[url\]([\s\S]*?)\[\/url\]/gi, (match, href) => {
            const decodedHref = decodeHtmlEntities(href);
            return `<a href="${decodedHref}" target="_blank" rel="noreferrer">${decodedHref}</a>`;
        })
        .replace(/\[hr\]/gi, '<hr class="news-preview-card__divider">')
        .replace(
            /\[align=(left|center|right|justify)\]([\s\S]*?)\[\/align\]/gi,
            '<div style="text-align:$1">$2</div>'
        )
        .replace(
            /\[icon=([^\]]+)\]/gi,
            '<i class="fas fa-$1" aria-hidden="true"></i>'
        )
        .replace(/\[suit=([^\]]+)\]/gi, (match, type) => {
            const symbols = {
                club: "♣",
                spade: "♠",
                heart: "♥",
                diamond: "♦",
            };
            const symbol = symbols[type] || "";
            const color = ["heart", "diamond"].includes(type) ? "red" : "black";
            return `<span class="suit suit-${type} suit-${color}">${symbol}</span>`;
        })
        .replace(/\[ul\]([\s\S]*?)\[\/ul\]/gi, (match, content) => {
            const listItems = content
                .split(/\[\*\]/)
                .filter((item) => item.trim())
                .map((item) => `<li>${item.trim()}</li>`)
                .join("");
            return `<ul>${listItems}</ul>`;
        })
        .replace(/\[ol\]([\s\S]*?)\[\/ol\]/gi, (match, content) => {
            const listItems = content
                .split(/\[\*\]/)
                .filter((item) => item.trim())
                .map((item) => `<li>${item.trim()}</li>`)
                .join("");
            return `<ol>${listItems}</ol>`;
        })
        .replace(/\n/g, "<br>");

    return html.trim() || '<span class="text-gray-500">(kein Inhalt)</span>';
};

export const getAvailableTags = () => [
    "b",
    "i",
    "u",
    "p",
    "hr",
    "h2",
    "h3",
    "h4",
    "h5",
    "align",
    "url",
    "icon",
    "suit",
    "ul",
    "ol",
];

export const validateBBCode = (content) => {
    const stack = [];
    const selfClosing = new Set(["hr", "icon", "suit"]);
    const regex = /\[(\/?)([a-z0-9-]+)(?:=[^\]]+)?\]/gi;
    let match;

    while ((match = regex.exec(content)) !== null) {
        const isClosing = Boolean(match[1]);
        const tag = (match[2] ?? "").toLowerCase();

        if (!tag || selfClosing.has(tag)) {
            continue;
        }

        if (isClosing) {
            if (stack.length === 0) {
                return {
                    valid: false,
                    message: `Der Tag [/${tag}] hat keine passende Öffnung.`,
                    issue: {
                        type: "unexpectedClosing",
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
                        type: "unexpectedClosing",
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
                length: match[0].length,
            });
        }
    }

    if (stack.length > 0) {
        const last = stack[stack.length - 1];
        return {
            valid: false,
            message: `Der Tag [${last.tag}] wurde nicht geschlossen.`,
            issue: {
                type: "missingClosing",
                tag: last.tag,
                openStart: last.start,
                openLength: last.length,
            },
        };
    }

    return { valid: true };
};
