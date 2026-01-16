<?php

namespace App\Support;

class BBCodeRenderer
{
    public static function render($bbCode)
    {
        if (!$bbCode) {
            return '';
        }

        $html = htmlspecialchars($bbCode);

        // BB-Code Tags ersetzen
        $html = preg_replace('/\[b\](.*?)\[\/b\]/is', '<strong>$1</strong>', $html);
        $html = preg_replace('/\[i\](.*?)\[\/i\]/is', '<em>$1</em>', $html);
        $html = preg_replace('/\[u\](.*?)\[\/u\]/is', '<span style="text-decoration:underline">$1</span>', $html);
        $html = preg_replace('/\[p\](.*?)\[\/p\]/is', '<p>$1</p>', $html);

        // Suit Tags
        $html = preg_replace_callback('/\[suit=(club|spade|heart|diamond)\]/i', function ($matches) {
            $type = strtolower($matches[1]);
            $symbols = [
                'club' => '♣',
                'spade' => '♠',
                'heart' => '♥',
                'diamond' => '♦'
            ];
            $symbol = $symbols[$type] ?? '';
            $color = in_array($type, ['heart', 'diamond']) ? 'red' : 'black';
            return '<span class="suit suit-' . $type . ' suit-' . $color . '">' . $symbol . '</span>';
        }, $html);

        // Icon Tags
        $html = preg_replace('/\[icon=([^\]]+)\]/i', '<i class="fas fa-$1" aria-hidden="true"></i>', $html);

        // URL Tags
        $html = preg_replace('/\[url=([^\]]+)\](.*?)\[\/url\]/is', '<a href="$1" target="_blank" rel="noreferrer">$2</a>', $html);
        $html = preg_replace('/\[url\](.*?)\[\/url\]/is', '<a href="$1" target="_blank" rel="noreferrer">$1</a>', $html);

        // Align Tags
        $html = preg_replace('/\[align=(left|center|right|justify)\](.*?)\[\/align\]/is', '<div style="text-align:$1">$2</div>', $html);

        // HR Tag
        $html = str_replace('[hr]', '<hr class="news-preview-card__divider">', $html);

        // Heading Tags
        $html = preg_replace('/\[h([1-6])\](.*?)\[\/h[1-6]\]/is', '<h$1>$2</h$1>', $html);

        // Zeilenumbrüche
        $html = nl2br($html);

        return $html;
    }
}
