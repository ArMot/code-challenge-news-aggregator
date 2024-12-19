<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Services\Sources\NewsSourceInterface;
use App\DTO\NewsArticle;
use App\Repositories\ArticleRepository;
use Illuminate\Support\Facades\Artisan;
use Mockery\MockInterface;
use Tests\TestCase;

use function Pest\Laravel\mock;

it('fetches and stores news articles from all sources', function () {
    $mockArticle = new NewsArticle(
        title: 'Test Title',
        author: 'Test Author',
        description: 'Test Description',
        content: 'Test Content',
        url: 'http://example.com',
        source: 'NewsAPI',
        category: 'Technology',
        published_at: now(),
        image_url: 'http://example.com/image.jpg'
    );

    $mockSource = mock(NewsSourceInterface::class);
    app()->instance('newsSources', ['MockSource' => $mockSource]);
    $mockSource->shouldReceive('fetchArticles')->once()->andReturn([$mockArticle]);


    $mockRepository = mock(ArticleRepository::class);
    app()->instance(ArticleRepository::class, $mockRepository);
    $mockRepository->shouldReceive('store')->once()->with($mockArticle);


    Artisan::call('news:fetch');

    /* @var TestCase $this */
    $this->assertTrue(true); // Verify the test ran without exceptions
});
