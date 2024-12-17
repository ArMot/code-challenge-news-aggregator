<?php

namespace App\Exceptions;

use App\Helpers\ApiResponse;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as IlluminateHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

class Handler extends IlluminateHandler
{
    public function render($request, Throwable $e): JsonResponse
    {
        // Handle ValidationException
        if ($e instanceof ValidationException) {
            return ApiResponse::error(
                'Validation error.',
                422,
                $e->errors()
            );
        }

        // Handle AuthenticationException
        if ($e instanceof AuthenticationException) {
            return ApiResponse::error(
                'Unauthenticated.',
                401
            );
        }

        // Handle Generic HttpException
        if ($e instanceof HttpException) {
            return ApiResponse::error(
                $e->getMessage(),
                $e->getStatusCode()
            );
        }

        // Handle Other Exceptions
        return ApiResponse::error(
            'An unexpected error occurred.',
            500,
            ['exception' => $e->getMessage()]
        );
    }
}
