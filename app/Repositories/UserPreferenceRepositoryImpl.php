<?php

namespace App\Repositories;

use App\Models\UserPreference;

class UserPreferenceRepositoryImpl implements UserPreferenceRepository
{
    public function findByUserId(int $userId): ?UserPreference
    {
        return UserPreference::where('user_id', $userId)->first();
    }
    /**
     * @param array<int,mixed> $data
     * @param int $userId
     */
    public function createOrUpdate(int $userId, array $data): ?UserPreference
    {
        return UserPreference::updateOrCreate(
            ['user_id' => $userId],
            $data
        );
    }
}
