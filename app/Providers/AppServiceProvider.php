<?php

namespace App\Providers;

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
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
