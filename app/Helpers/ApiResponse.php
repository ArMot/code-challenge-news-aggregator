<?php

namespace App\Helpers;

use Illuminate\Http\JsonResponse;

class ApiResponse
{
    /**
     * Return a success response.
     *
     * @param mixed $data
     * @param string|null $message
     * @param int $statusCode
     * @return JsonResponse
     */
    public static function success(mixed $data = null, ?string $messageKey = null, int $statusCode = 200): JsonResponse
    {
        $message = $messageKey ? __($messageKey) : null;

        return response()->json([
            'status' => 'success',
            'data' => $data,
            'message' => $message,
        ], $statusCode);
    }

    /**
     * Return an error response.
     *
     * @param string $message
     * @param int $statusCode
     * @param array<string>|null $details
     * @return JsonResponse
     */
    public static function error(string $messageKey, int $statusCode = 400, ?array $details = null): JsonResponse
    {
        $message = __($messageKey);

        return response()->json([
            'status' => 'error',
            'errors' => [
                [
                    'code' => $statusCode,
                    'message' => $message,
                    'details' => $details,
                ]
            ]
        ], $statusCode);
    }
}
