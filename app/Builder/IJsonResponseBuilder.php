<?php

namespace App\Builder;

use Illuminate\Http\JsonResponse;
use Throwable;

interface IJsonResponseBuilder
{
    public function build(
        bool $error,
        ?string $message,
        ?string $developerMessage,
        $data,
        ?Throwable $exception,
        int $status,
        array $headers = []
    ): JsonResponse;
}
