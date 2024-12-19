<?php

namespace App\Services;

use App\Models\Article;
use App\Models\User;
use App\Models\UserPreference;
use App\Repositories\ArticleRepository;
use App\Repositories\UserPreferenceRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class UserPreferenceService
{
    protected UserPreferenceRepository $repository;
    protected ArticleRepository $articleRepository;

    public function __construct(UserPreferenceRepository $repository, ArticleRepository $articleRepository)
    {
        $this->repository = $repository;
        $this->articleRepository = $articleRepository;
    }

    public function getUserPreferences(int $userId): ?UserPreference
    {
        return $this->repository->findByUserId($userId);
    }
    /**
     * @param int $userId
     * @param array<int,mixed> $preferences
     */
    public function setUserPreferences(int $userId, array $preferences): ?UserPreference
    {
        return $this->repository->createOrUpdate($userId, $preferences);
    }
    /**
     * @return Article[]|void
     */
    public function getPersonalizedFeed(int $userId): LengthAwarePaginator
    {
        $preferences = $this->repository->findByUserId($userId);

        if (! $preferences) {
            return [];
        }

        $articles = $this->articleRepository->getArticlesByPreferences(
            $preferences->sources,
            $preferences->categories,
            $preferences->authors
        );

        return $articles;
    }
}
