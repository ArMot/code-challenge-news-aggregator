<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Services\ArticleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    protected ArticleService $service;

    public function __construct(ArticleService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request): JsonResponse
    {
        $articles = $this->service->getPaginated($request->input('perPage', 10));

        return ApiResponse::success($articles, __('api.articles.fetched_success'));
    }

    public function search(Request $request): JsonResponse
    {
        $filters = $request->only(['keyword', 'category', 'source', 'date']);
        $articles = $this->service->search($filters, $request->input('perPage', 10));

        return ApiResponse::success($articles, __('api.articles.search_success'));
    }

    public function show(int $id): JsonResponse
    {
        $article = $this->service->findById($id);

        if (!$article) {
            return ApiResponse::error(__('api.articles.not_found'), 404);
        }

        return ApiResponse::success($article, __('api.articles.retrieved_success'));
    }
}
