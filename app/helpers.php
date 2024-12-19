<?php

namespace App;

if (!function_exists('mapCategory')) {
    /**
     * Maps a source-specific category to your application's category.
     *
     * @param string $sourceCategory
     * @return string
     */
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
