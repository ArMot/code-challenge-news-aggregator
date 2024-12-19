<?php

namespace App\Repositories;

use App\DTO\NewsArticle;
use App\Models\Article;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;

class ArticleRepositoryImpl implements ArticleRepository
{
    public function getPaginated(int $perPage): LengthAwarePaginator
    {
        return Article::paginate($perPage);
    }

    public function findById(int $id): ?Article
    {
        return Article::find($id);
    }

    public function create(array $data): Article
    {
        return Article::create($data);
    }

    public function search(array $filters, int $perPage = 10): LengthAwarePaginator
    {
        return Article::query()
            ->when(
                $filters['keyword'] ?? null,
                fn ($query, $keyword) =>
                $query->where('title', 'like', "%{$keyword}%")
            )
            ->when(
                $filters['category'] ?? null,
                fn ($query, $category) =>
                $query->where('category', $category)
            )
            ->when(
                $filters['source'] ?? null,
                fn ($query, $source) =>
                $query->where('source', $source)
            )
            ->when(
                $filters['date'] ?? null,
                fn ($query, $date) =>
                $query->whereDate('published_at', $date)
            )
            ->paginate($perPage);
    }

    public function store(NewsArticle $article): void
    {
        Article::updateOrCreate(
            ['url' => $article->url],
            [
                'title' => $article->title,
                'author' => $article->author,
                'description' => $article->description,
                'content' => $article->content,
                'source' => $article->source,
                'category' => $article->category,
                'published_at' => $article->published_at,
                'image_url' => $article->image_url,
            ]
        );
    }

    public function getArticlesByPreferences(
        array $sources = [],
        array $categories = [],
        array $authors = [],
        int $perPage = 10
    ): LengthAwarePaginator {

        // cache frequently accessed articles for a set duration.
        $cacheKey = $this->generateCacheKey($sources, $categories, $authors);
        return Cache::remember($cacheKey, now()->addMinutes(30), function () use ($sources, $categories, $authors) {
            $query = Article::query();

            if ($sources) {
                $query->whereIn('source', $sources);
            }

            if ($categories) {
                $query->whereIn('category', $categories);
            }

            if ($authors) {
                $query->whereIn('author', $authors);
            }

            return $query->paginate(10);
        });
    }
    /**
     * @param string[] $sources
     * @param string[] $categories
     * @param string[] $authors
     * @return string
     */
    private function generateCacheKey($sources, $categories, $authors): string
    {
        return md5(json_encode(compact('sources', 'categories', 'authors')));
    }
}
