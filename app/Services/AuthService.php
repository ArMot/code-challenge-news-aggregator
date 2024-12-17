<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthService
{
    protected UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @param array<string, mixed> $data
     *
     * @return User
     */
    public function register(array $data): User
    {
        return $this->userRepository->create($data);
    }

    /**
     * @param array<string, string> $credentials
     *
     * @return string
     */
    public function login(array $credentials): string
    {
        $user = $this->userRepository->findByEmail($credentials['email']);

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            throw ValidationException::withMessages(['email' => ['The provided credentials are incorrect.']]);
        }

        return $user->createToken('API Token')->plainTextToken;
    }
}
