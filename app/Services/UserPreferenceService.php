<?php

namespace App\Services;

use App\Models\UserPreference;
use App\Repositories\UserPreferenceRepository;

class UserPreferenceService
{
    protected UserPreferenceRepository $repository;

    public function __construct(UserPreferenceRepository $repository)
    {
        $this->repository = $repository;
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
}
