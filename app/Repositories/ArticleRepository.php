<?php

namespace App\Repositories;

use App\DTO\NewsArticle;
use App\Models\Article;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface ArticleRepository
{
    public function getPaginated(int $perPage): LengthAwarePaginator;
    public function findById(int $id): ?Article;
    /**
    * @param array<string, mixed> $data
    */
    public function create(array $data): Article;

    /**
    * @param array<string, mixed> $filters
    */
    public function search(array $filters, int $perPage = 10): LengthAwarePaginator;

    public function store(NewsArticle $article): void;

}
