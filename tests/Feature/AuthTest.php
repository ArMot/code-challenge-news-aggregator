<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

use function Pest\Laravel\postJson;

it('validates registration data', function () {
    $response = postJson(route('user.register'), [
        'email' => 'invalid-email',
        'password' => 'short',
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['email', 'password']);
});

it('registers a user with valid data', function () {
    $response = postJson(route('user.register'), [
        'name' => 'John Doe',
        'email' => 'john.doe@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ]);

    $response->assertStatus(201)
         ->assertJson([
            'status' => 'success',
            'message' => __('api.auth.register_success'),
        ]);
});

it('validates login data', function () {
    $response = postJson(route('user.login'), [
        'email' => 'not-an-email',
        'password' => '',
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['email', 'password']);
});

it('authenticate a user with correct credentials', function () {
    $user = User::factory()->create([
        'password' => bcrypt('password123'),
    ]);

    $response = postJson(route('user.login'), [
        'email' => $user->email,
        'password' => 'password123',
    ]);

    $response->assertStatus(200)
        ->assertJsonStructure(['data' => ['token']]);
});

it('prevents login with incorrect credentials', function () {
    $user = User::factory()->create([
        'password' => bcrypt('password123'),
    ]);

    $response = postJson(route('user.login'), [
        'email' => $user->email,
        'password' => 'wrongpassword',
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['email']);
});

it('logs out a user', function () {
    $user = User::factory()->create();

    $token = $user->createToken('TestToken')->plainTextToken;

    /** @var TestCase $this */
    $response = $this->withHeader('Authorization', "Bearer $token")
        ->postJson(route('user.logout'));

    $response->assertStatus(204);
});

it('prevents duplicate email registration', function () {
    User::factory()->create(['email' => 'duplicate@example.com']);

    $response = postJson(route('user.register'), [
        'name' => 'Jane Doe',
        'email' => 'duplicate@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['email']);
});

it('allows login with case-insensitive email', function () {
    $user = User::factory()->create([
        'email' => 'user@example.com',
        'password' => bcrypt('password123'),
    ]);

    $response = postJson(route('user.login'), [
        'email' => 'USER@example.com',
        'password' => 'password123',
    ]);

    $response->assertStatus(200)
        ->assertJsonStructure(['data' => ['token']]);
});

it('handles invalid tokens during logout gracefully', function () {
    /** @var TestCase $this */
    $response = $this->withHeader('user.rization', 'Bearer invalid-token')
        ->postJson(route('user.logout'));

    $response->assertStatus(401);
});
