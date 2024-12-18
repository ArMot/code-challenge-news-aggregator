<?php

namespace App\Repositories;

use App\Models\UserPreference;

interface UserPreferenceRepository
{
    public function findByUserId(int $userId): ?UserPreference;
    public function createOrUpdate(int $userId, array $data);
}
