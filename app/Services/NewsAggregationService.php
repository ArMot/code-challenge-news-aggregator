<?php

namespace App\Services;

use App\Repositories\ArticleRepository;
use App\Services\Sources\NewsSourceInterface;
use Illuminate\Support\Facades\Log;

class NewsAggregationService
{
    protected ArticleRepository $repository;

    /** @@property NewsSourceInterface[] $newsSources */
    protected array $newsSources;

    public function __construct(ArticleRepository $repository)
    {
        $this->repository = $repository;
        $this->newsSources = app('newsSources');
    }

    public function fetchAndStoreNews(): void
    {
        foreach ($this->newsSources as $source) {
            $articles = $source->fetchArticles();

            foreach ($articles as $article) {
                try {
                    $this->repository->store($article);
                } catch (\Exception $e) {
                    Log::error('Could not save article with url {$article->url}, error: {$e->message()}');
                }
            }
        }
    }
}
