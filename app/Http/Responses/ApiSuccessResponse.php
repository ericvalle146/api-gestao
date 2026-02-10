<?php

declare(strict_types=1);

namespace App\Http\Responses;

use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;

final class ApiSuccessResponse implements Responsable
{
    public function __construct(
        private JsonResource $resource,
        private int $code = Response::HTTP_OK,
        private array $headers = []
    ) {}

    public function toResponse($request): JsonResponse
    {
        return response()->json(
            $this->resource,
            $this->code,
            $this->headers
        );
    }
}
