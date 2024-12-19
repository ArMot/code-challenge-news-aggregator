<?php

namespace App\Helpers\;

if (!function_exists('mapCategory')) {
    function mapCategory(string $sourceCategory): string
    {
        $categoryMap = config('news_sources.categories');

        foreach ($categoryMap as $appCategory => $sourceCategories) {
            if (in_array(strtolower($sourceCategory), $sourceCategories, true)) {
                return $appCategory;
            }
        }

        return 'General';
    }
}
