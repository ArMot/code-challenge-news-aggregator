<?php

namespace App\Services\Sources;

use App\DTO\NewsArticle;
use Illuminate\Support\Facades\Http;

use function App\mapCategory;

class GuardianSource implements NewsSourceInterface
{
    protected string $baseUrl;
    protected string $apiKey;

    public function __construct()
    {
        $this->apiKey = config('news_sources.guardian.api_key');
        $this->baseUrl = config('news_sources.guardian.base_url');
    }

    public function fetchArticles(): array
    {
        $response = Http::get("{$this->baseUrl}/search", [
            'api-key' => $this->apiKey,
            'show-fields' => 'all',
        ]);

        $articles = $response->json('response.results') ?? [];
        return array_map(fn ($article) => $this->transform($article), $articles);
    }

    public function transform(array $article): NewsArticle
    {
        return new NewsArticle(
            title: $article['webTitle'] ?? 'Untitled',
            author: $article['fields']['byline'] ?? null,
            description: $article['fields']['trailText'] ?? null,
            content: $article['fields']['body'] ?? null,
            url: $article['webUrl'] ?? '',
            source: 'The Guardian',
            category: mapCategory($article['sectionName'] ?? null),
            published_at: now(),
            image_url: $article['fields']['thumbnail'] ?? null
        );
    }
}
