<?php

use Illuminate\Support\Str;

if (!function_exists('getAvailableFontAwesomeIconOptions')) {
    /**
     * @return array<int, array<string, string>>
     */
    function getAvailableFontAwesomeIconOptions(): array
    {
        static $options;

        if ($options !== null) {
            return $options;
        }

        $path = base_path('resources/data/fontawesome-free-icons.json');
        if (!file_exists($path)) {
            return [];
        }

        $raw = json_decode(file_get_contents($path) ?: '[]', true);
        $flattened = [];

        foreach ($raw as $icon) {
            $name = $icon['name'] ?? null;
            $styles = $icon['styles'] ?? [];

            if (!is_string($name) || empty($styles)) {
                continue;
            }

            foreach ($styles as $style) {
                $prefix = match ($style) {
                    'brands' => 'fab',
                    'regular' => 'far',
                    'solid' => 'fas',
                    default => 'fas',
                };

                $flattened[] = [
                    'name' => $name,
                    'style' => $style,
                    'prefix' => $prefix,
                    'label' => Str::headline(str_replace(['-', '_'], ' ', $name)),
                ];
            }
        }

        usort($flattened, fn($a, $b) => $a['name'] <=> $b['name']);

        return $options = $flattened;
    }
}
