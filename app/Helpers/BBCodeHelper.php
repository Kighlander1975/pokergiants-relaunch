<?php

if (!function_exists('convertBBToHtml')) {
    /**
     * Convert BB-Code to HTML for frontend display
     *
     * @param string|null $raw
     * @return string
     */
    function convertBBToHtml(?string $raw): string
    {
        if (empty($raw)) {
            return '<span class="text-gray-500">(kein Inhalt)</span>';
        }

        $html = htmlspecialchars($raw, ENT_QUOTES, 'UTF-8');

        // Basic formatting
        $html = preg_replace('/\[b\](.*?)\[\/b\]/si', '<strong>$1</strong>', $html);
        $html = preg_replace('/\[i\](.*?)\[\/i\]/si', '<em>$1</em>', $html);
        $html = preg_replace('/\[u\](.*?)\[\/u\]/si', '<span style="text-decoration:underline">$1</span>', $html);
        $html = preg_replace('/\[p\](.*?)\[\/p\]/si', '<p>$1</p>', $html);

        // URLs
        $html = preg_replace_callback(
            '/\[url=([^\]]+)\](.*?)\[\/url\]/si',
            function ($matches) {
                $href = htmlspecialchars_decode($matches[1], ENT_QUOTES);
                $label = $matches[2];
                return '<a href="' . htmlspecialchars($href, ENT_QUOTES) . '" target="_blank" rel="noreferrer">' . $label . '</a>';
            },
            $html
        );

        $html = preg_replace_callback(
            '/\[url\](.*?)\[\/url\]/si',
            function ($matches) {
                $href = htmlspecialchars_decode($matches[1], ENT_QUOTES);
                return '<a href="' . htmlspecialchars($href, ENT_QUOTES) . '" target="_blank" rel="noreferrer">' . htmlspecialchars($href, ENT_QUOTES) . '</a>';
            },
            $html
        );

        // Horizontal rule
        $html = preg_replace('/\[hr\]/si', '<hr class="news-preview-card__divider">', $html);

        // Alignment
        $html = preg_replace('/\[align=(left|center|right|justify)\](.*?)\[\/align\]/si', '<div style="text-align:$1">$2</div>', $html);

        // Icons
        $html = preg_replace('/\[icon=([^\]]+)\]/si', '<i class="fas fa-$1" aria-hidden="true"></i>', $html);

        // Suits
        $html = preg_replace_callback(
            '/\[suit=([^\]]+)\]/si',
            function ($matches) {
                $type = strtolower($matches[1]);
                $symbols = [
                    'club' => '♣',
                    'spade' => '♠',
                    'heart' => '♥',
                    'diamond' => '♦',
                ];
                $symbol = $symbols[$type] ?? '';
                $color = in_array($type, ['heart', 'diamond']) ? 'red' : 'black';
                return '<span class="suit suit-' . $type . ' suit-' . $color . '">' . $symbol . '</span>';
            },
            $html
        );

        // Lists
        $html = preg_replace_callback(
            '/\[ul\](.*?)\[\/ul\]/si',
            function ($matches) {
                $content = $matches[1];
                $listItems = array_filter(array_map('trim', explode('[*]', $content)));
                $listHtml = '';
                foreach ($listItems as $item) {
                    $listHtml .= '<li>' . $item . '</li>';
                }
                return '<ul>' . $listHtml . '</ul>';
            },
            $html
        );

        $html = preg_replace_callback(
            '/\[ol\](.*?)\[\/ol\]/si',
            function ($matches) {
                $content = $matches[1];
                $listItems = array_filter(array_map('trim', explode('[*]', $content)));
                $listHtml = '';
                foreach ($listItems as $item) {
                    $listHtml .= '<li>' . $item . '</li>';
                }
                return '<ol>' . $listHtml . '</ol>';
            },
            $html
        );

        // Line breaks
        $html = str_replace("\n", "<br>", $html);

        return trim($html);
    }
}