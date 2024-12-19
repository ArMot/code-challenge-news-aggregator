<?php

namespace App\Services\Sources;

use App\DTO\NewsArticle;
use Illuminate\Support\Facades\Http;

use function App\mapCategory;

class NewsApiSource implements NewsSourceInterface
{
    protected string $baseUrl;
    protected string $apiKey;

    public function __construct()
    {
        $this->apiKey = config('news_sources.newsapi.api_key');
        $this->baseUrl = config('news_sources.newsapi.base_url');
    }

    public function fetchArticles(): array
    {
        $response = Http::get("{$this->baseUrl}/top-headlines", [
            'apiKey' => $this->apiKey,
            'country' => 'us',
        ]);

        $articles = $response->json('articles') ?? [];
        return array_map(fn ($article) => $this->transform($article), $articles);
    }

    public function transform(array $article): NewsArticle
    {
        $category = array_key_exists('category', $article) ? $article['category'] : 'General';
        return new NewsArticle(
            title: $article['title'] ?? 'Untitled',
            author: $article['author'] ?? null,
            description: $article['description'] ?? null,
            content: $article['content'] ?? null,
            url: $article['url'] ?? '',
            source: 'NewsAPI',
            category: mapCategory($category),
            published_at: now(),
            image_url: $article['urlToImage'] ?? null
        );
    }
}
