<?php

namespace Tests\Feature;

use App\Repositories\ArticleRepository;
use App\Services\NewsAggregationService;
use App\Services\Sources\NewsApiSource;
use App\Services\Sources\NewsSourceInterface;
use App\DTO\NewsArticle;
use App\Models\Article;
use Carbon\Carbon;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

use function Pest\Laravel\getJson;

it('fetches and stores articles from a news source', function () {
    $mockArticle = new NewsArticle(
        title: 'Sample Article',
        author: 'Jane Doe',
        description: 'Sample description',
        content: 'Sample content',
        url: 'http://example.com/sample',
        source: 'NewsAPI',
        category: 'Technology',
        published_at: Carbon::now(),
        image_url: 'http://example.com/sample-image.jpg'
    );

    $mockSource = mock(NewsSourceInterface::class)
        ->shouldReceive('fetchArticles')
        ->once()
        ->andReturn([$mockArticle])
        ->getMock();

    app()->instance(NewsSourceInterface::class, $mockSource);

    Artisan::call('news:fetch'); // Simulate the scheduled command

    expect(Article::count())->toBe(1);
    $storedArticle = Article::first();

    expect($storedArticle)
        ->title->toBe($mockArticle->title)
        ->source->toBe($mockArticle->source)
        ->category->toBe($mockArticle->category);
});


it('transforms raw news data into a NewsArticle DTO', function () {
    $rawData = [
        'title' => 'Raw Article Title',
        'author' => 'John Doe',
        'description' => 'Raw description',
        'content' => 'Raw content',
        'url' => 'http://example.com/raw',
        'source' => 'NewsAPI',
        'category' => 'Technology',
        'publishedAt' => '2024-12-18T10:00:00Z',
        'urlToImage' => 'http://example.com/raw-image.jpg',
    ];

    $newsApiSource = new NewsApiSource();
    $article = $newsApiSource->transform($rawData);

    expect($article)
        ->toBeInstanceOf(NewsArticle::class)
        ->title->toBe('Raw Article Title')
        ->author->toBe('John Doe')
        ->category->toBe('Technology');
});

it('prevents duplicate articles from being stored', function () {
    $articleData = new NewsArticle(
        title: 'Duplicate Article',
        author: 'John Doe',
        description: 'Duplicate description',
        content: 'Duplicate content',
        url: 'http://example.com/duplicate',
        source: 'NewsAPI',
        category: 'Technology',
        published_at: Carbon::now(),
        image_url: 'http://example.com/duplicate-image.jpg'
    );

    $repository = resolve(ArticleRepository::class);

    // Store the article once
    $repository->store($articleData);

    // Attempt to store the same article again
    $repository->store($articleData);

    expect(Article::count())->toBe(1); // Only one article should be stored
});

it('returns articles via the API', function () {
    Article::factory()->create([
        'title' => 'API Test Article',
        'source' => 'NewsAPI',
        'category' => 'Technology',
    ]);

    $response = getJson('api/articles');

    $response
        ->assertStatus(200)
        ->assertJsonStructure([
            'data' => [
                'data' => [
                    '*' => [
                        'id',
                        'title',
                        'author',
                        'content',
                        'url',
                        'source',
                        'category',
                        'published_at',
                    ],
                ],
            ],
        ])
        ->assertJsonFragment([
            'title' => 'API Test Article',
            'source' => 'NewsAPI',
            'category' => 'Technology',
        ]);
});

it('invokes the news aggregation service', function () {
    /** @var TestCase $this */
    $newsAggregationService = $this->mock(NewsAggregationService::class);
    $newsAggregationService->shouldReceive('fetchAndStoreNews')->once();

    $this->app->instance(NewsAggregationService::class, $newsAggregationService);

    $this->artisan('news:fetch')
        ->expectsOutput('Starting news aggregation process...')
        ->expectsOutput('News aggregation completed.')
        ->assertExitCode(0);
});
