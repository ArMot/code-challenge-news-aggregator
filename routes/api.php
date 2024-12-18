<?php

use App\Http\Controllers\UserPreferenceController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ArticleController;

Route::prefix('user')->name('user.')->group(function () {
    Route::post('register', [AuthController::class, 'register'])->name('register');
    Route::post('login', [AuthController::class, 'login'])->name('login');
    Route::post('logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth:sanctum');

    Route::name('preferences.')->middleware('auth:sanctum')->group(function () {
        Route::get('preferences', [UserPreferenceController::class, 'index'])->name('index');
        Route::post('preferences', [UserPreferenceController::class, 'store'])->name('store');
    });
});

Route::prefix('articles')->name('articles.')->group(function () {
    Route::get('/', [ArticleController::class, 'index'])->name('index');
    Route::get('/{id}', [ArticleController::class, 'show'])->name('show');
    Route::get('/search', [ArticleController::class, 'search'])->name('search');
});
