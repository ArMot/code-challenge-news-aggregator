<?php

namespace App\Repositories;

use App\Models\User;

interface UserRepository
{
    /**
     * @param array<string, mixed> $data
     *
     * @return User
     */
    public function create(array $data): User;

    /**
     * @param string $email
     * @return User|null
     */
    public function findByEmail(string $email): ?User;
}
