<?php

namespace App\Repositories;

use App\Models\UserPreference;
use Illuminate\Support\Facades\Cache;

class UserPreferenceRepositoryImpl implements UserPreferenceRepository
{
    public function findByUserId(int $userId): ?UserPreference
    {
        return Cache::remember("user-preferences:$userId", now()->addMinutes(30), function () use ($userId) {
            return UserPreference::firstOrCreate(['user_id' => $userId]);
        });
    }
    /**
     * @param array<int,mixed> $data
     * @param int $userId
     */
    public function createOrUpdate(int $userId, array $data): ?UserPreference
    {
        $userPreference = UserPreference::updateOrCreate(
            ['user_id' => $userId],
            $data
        );

        // Clear cache
        Cache::forget("user-preferences:$userId");

        return $userPreference;
    }
}
