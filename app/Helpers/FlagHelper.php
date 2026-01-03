<?php

if (!function_exists('getFlagCode')) {
    /**
     * Convert country flag code to flag-icons CSS class
     * Supports regional flags like Scotland, England, Wales, Northern Ireland
     *
     * @param string $countryFlag Country flag in format XX_YY or XX_REGION
     * @return string Flag-icons CSS class (e.g., 'gb', 'gb-sct', 'de')
     */
    function getFlagCode(string $countryFlag): string
    {
        if (empty($countryFlag)) {
            return 'de'; // Default to Germany
        }

        $parts = explode('_', $countryFlag);
        $country = strtolower($parts[0]);
        $region = isset($parts[1]) ? strtoupper($parts[1]) : null;

        // Special regional mappings for UK
        if ($country === 'gb') {
            switch ($region) {
                case 'SCT':
                    return 'gb-sct'; // Scotland
                case 'ENG':
                    return 'gb-eng'; // England
                case 'WLS':
                    return 'gb-wls'; // Wales
                case 'NIR':
                    return 'gb-nir'; // Northern Ireland
                default:
                    return 'gb'; // Default UK
            }
        }

        // For other countries, just return the country code
        return $country;
    }
}
