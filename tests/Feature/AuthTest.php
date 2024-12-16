<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

use function Pest\Laravel\postJson;

test('example', function () {

    /** @var TestCase $this */
    $response = $this->get('/');

    $response->assertStatus(200);
});



describe("register", function () {
    it("succeed with proper data", function () {

        /** @var TestCase $this */
        $this->assertDatabaseCount(User::class, 0);

        $payload = [
            'name' => 'armot',
            'email' => 'valid@mail.com',
            'password' => 'thisIsASuperSecurePassword',
            'password_confirmation' => 'thisIsASuperSecurePassword',
        ];
        $registerRoute = route('user.register', $data = $payload);
        $response = postJson($registerRoute);

        $response->assertStatus(201);


        $this->assertDatabaseHas(User::class, ['email' => 'valid@mail.com', 'name' => 'armot']);

        $this->assertDatabaseCount(User::class, 1);
    });

    it("fails with invalid data ", function () {

        /** @var TestCase $this */
        $this->assertDatabaseCount(User::class, 0);
        $payloadWithNoName = ['email' => 'valid@mail.com', 'password' => 'securePassword'];

        $registerRoute = route('user.register');
        $response = postJson($registerRoute, $data = $payloadWithNoName);

        $response->assertStatus(422);
        $this->assertDatabaseMissing(User::class, ['email' => 'valid@mail.com']);

        $payloadWithNoEmail = ['name' => 'armot', 'password' => 'securePassword'];

        $response = postJson($registerRoute, $data = $payloadWithNoEmail);

        $response->assertStatus(422);
        $this->assertDatabaseMissing(User::class, ['name' => 'armot']);

        $payloadWithNoPassword = ['name' => 'armot', 'email' => 'valid@mail.com'];

        $response = postJson($registerRoute, $data = $payloadWithNoPassword);

        $response->assertStatus(422);
        $this->assertDatabaseMissing(User::class, ['name' => 'armot', 'email' => 'valid@mail.com']);

        $this->assertDatabaseCount(User::class, 0);
    });
});

describe('login', function () {
    it('succeed with correct credentials', function () {
        $user = User::factory()->create(['password' => 'securePassword']);

        $this->assertDatabaseHas(User::class, ['id' => $user->id]);

        $loginRoute = route('user.login');
        $response = postJson($loginRoute, $data = ['email' => $user->email, 'password' => 'securePassword']);
        // dd($response);

        $response->assertStatus(200);
    });
    it('fails with invalid credentials', function () {
        $user = User::factory()->create(['password' => 'correctPassword']);

        $loginRoute = route('user.login');
        $response = postJson($loginRoute, ['email' => $user->email, 'password' => 'wrongPassword']);


        $response->assertStatus(401);
    });
});
