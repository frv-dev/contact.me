<?php

namespace App\Builder;

use Illuminate\Http\JsonResponse;
use Throwable;

class JsonResponseBuilder implements IJsonResponseBuilder
{
    public function build(
        bool $error,
        ?string $message,
        ?string $developerMessage,
        $data,
        ?Throwable $exception,
        int $status,
        array $headers = []
    ): JsonResponse {
        return response()->json([
            'error' => $error,
            'message' => $message,
            'developerMessage' => $developerMessage,
            'data' => $data,
            'exception' => $exception,
        ], $status, $headers, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }
}
