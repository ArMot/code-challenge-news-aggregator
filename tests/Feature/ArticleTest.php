<?php

namespace Tests\Feature;

use App\Models\Article;

use function Pest\Laravel\getJson;

it('fetches paginated articles', function () {
    Article::factory()->count(15)->create();

    $response = getJson(route('articles.index', ['perPage' => 5]));

    $response->assertStatus(200)
        ->assertJsonStructure([
            'status',
            'data' => [
                'data' => [
                    '*' => ['id', 'title', 'content', 'author', 'source', 'published_at', 'category'],
                ],
                'links',
            ],
            'message',
        ]);
});

it('returns a 404 when article is not found', function () {
    $response = getJson(route('articles.show', ['id' => 999]));

    $response->assertStatus(404)
        ->assertJson([
            'status' => 'error',
            'errors' => [
                ['message' => 'Article not found.']
            ],
        ]);
});

it('returns an empty list when no articles exist', function () {
    $response = getJson(route('articles.index'));

    $response->assertStatus(200)
        ->assertJson([
            'status' => 'success',
            'data' => [
                'data' => [],
                'links' => [],
            ],
        ]);
});

it('handles pagination beyond bounds gracefully', function () {
    Article::factory()->count(10)->create();

    $response = getJson(route('articles.index', ['page' => 99]));

    $response->assertStatus(200)
        ->assertJson([
            'status' => 'success',
            'data' => [
                'data' => [],
            ],
        ]);
});

// it('validates article ID as an integer', function () {
//     $response = getJson(route('articles.show', ['id' => 'abc']));
//
//     $response->assertStatus(422)
//         ->assertJson([
//             'status' => 'error',
//             'message' => __('api.validation.id_integer'),
//         ]);
// });
