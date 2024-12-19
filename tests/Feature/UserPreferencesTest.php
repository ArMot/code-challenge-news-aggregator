<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\User;
use App\Models\UserPreference;
use Tests\TestCase;

use function Pest\Laravel\getJson;
use function Pest\Laravel\postJson;

it('fetches user preferences', function () {
    $user = User::factory()->create();

    /** @var TestCase $this */
    $this->actingAs($user);

    UserPreference::factory()->create([
        'user_id' => $user->id,
        'categories' => ['tech', 'sports'],
        'sources' => ['BBC', 'CNN'],
        'authors' => ['John Doe'],
    ]);

    $response = getJson(route('user.preferences.index'));

    $response->assertStatus(200)
        ->assertJsonPath('data.categories', ['tech', 'sports']);
});

it('updates user preferences', function () {
    $user = User::factory()->create();

    /** @var TestCase $this */
    $this->actingAs($user);

    $payload = [
        'categories' => ['health', 'business'],
        'sources' => ['Reuters', 'The Guardian'],
        'authors' => ['Jane Smith'],
    ];

    $response = postJson(route('user.preferences.store'), $payload);

    $response->assertStatus(200)
        ->assertJsonPath('data.categories', ['health', 'business']);
});

it('retrieves personalized feed', function () {
    $user = User::factory()->create();
    $preferences = UserPreference::factory()->create([
        'user_id' => $user->id,
        'sources' => ['NewsApi'],
        'categories' => ['Sports'],
        'authors' => ['John Doe'],
    ]);

    $articles = Article::factory(5)->create([
        'source' => 'NewsApi',
        'category' => 'Sports',
        'author' => 'John Doe',
    ]); // Must be included in response

    Article::factory(7)->create([
        'source' => 'Guardian',
        'category' => 'Technology',
    ]); // Must be excluded from response


    /** @var TestCase $this */
    $this->actingAs($user, 'sanctum')
        ->getJson(route('user.preferences.feed'))
        ->assertStatus(200)
        ->assertJsonCount(5, 'data.data');
});
