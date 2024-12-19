<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Http\Requests\SetUserPreferenceRequest;
use App\Services\UserPreferenceService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Request;

class UserPreferenceController extends Controller
{
    protected UserPreferenceService $service;

    public function __construct(UserPreferenceService $service)
    {
        $this->service = $service;
    }

    public function index(): JsonResponse
    {
        $preferences = $this->service->getUserPreferences(Auth::id());

        return ApiResponse::success($preferences, __('messages.preferences.fetched_success'));
    }

    public function store(SetUserPreferenceRequest $request): JsonResponse
    {
        $preferences = $this->service->setUserPreferences(Auth::id(), $request->validated());

        return ApiResponse::success($preferences, __('messages.preferences.updated_success'));
    }

    public function getPersonalizedFeed(Request $request): JsonResponse
    {
        $userId = $request->user()->id;

        $articles = $this->service->getPersonalizedFeed($userId);

        return ApiResponse::success($articles);
    }
}
