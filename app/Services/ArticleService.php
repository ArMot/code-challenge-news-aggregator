<?php

namespace App\Services;

use App\Models\Article;
use App\Repositories\ArticleRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ArticleService
{
    protected ArticleRepository $repository;

    public function __construct(ArticleRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getPaginated(int $perPage = 10): LengthAwarePaginator
    {
        return $this->repository->getPaginated($perPage);
    }

    /**
    * @param array<string, mixed> $filters
    * @param int $perPage
    */
    public function search(array $filters, int $perPage = 10): LengthAwarePaginator
    {
        return $this->repository->search($filters, $perPage);
    }

    public function findById(int $id): ?Article
    {
        return $this->repository->findById($id);
    }
}
