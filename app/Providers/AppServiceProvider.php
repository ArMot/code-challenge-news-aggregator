<?php

namespace App\Providers;

use App\Console\Commands\FetchNewsCommand;
use App\Repositories\UserPreferenceRepository;
use App\Repositories\UserPreferenceRepositoryImpl;
use App\Services\Sources\GuardianSource;
use App\Services\Sources\NewsApiSource;
use App\Services\Sources\NewsSourceInterface;
use Illuminate\Support\Facades\Route;
use App\Repositories\ArticleRepository;
use App\Repositories\ArticleRepositoryImpl;
use Illuminate\Support\ServiceProvider;
use App\Repositories\UserRepository;
use App\Repositories\UserRepositoryImpl;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(UserRepository::class, UserRepositoryImpl::class);
        $this->app->bind(ArticleRepository::class, ArticleRepositoryImpl::class);
        $this->app->bind(UserPreferenceRepository::class, UserPreferenceRepositoryImpl::class);


        $this->app->singleton('newsSources', function () {
            return [
                'NewsAPI' => app(NewsApiSource::class),
                'Guardian' => app(GuardianSource::class),
            ];
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Define Global route pattern, from now on all of the (id) params in routes must be numeric
        Route::pattern('id', '[0-9]+');
    }
}
