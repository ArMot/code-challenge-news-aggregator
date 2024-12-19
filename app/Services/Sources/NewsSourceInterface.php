<?php

namespace App\Services\Sources;

use App\DTO\NewsArticle;
use App\Data\Article;

interface NewsSourceInterface
{
    /**
     * Fetch articles from the external source.
     *
     * @return NewsArticle[]
     */
    public function fetchArticles(): array;

    /**
     * Transform raw article data into an Article object.
     *
     * @param array $article
     * @return Article
     */
    public function transform(array $article): NewsArticle;

    /**
     * Map an external category to an internal one.
     *
     * @param string|null $externalCategory
     * @return string
     */
    // public function mapCategory(?string $externalCategory): string;
}
