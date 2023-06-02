<?php

namespace App\Http\Resources\Base;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     title="BaseUnauthorizedResource",
 *     description="Base unauthorized resource",
 *     schema="BaseUnauthorizedResource",
 *     example={
 *         "message":"Unauthorized",
 *         "data":null,
 *         "success":false
 *     }
 * )
 */
class BaseUnauthorizedResource extends BaseResource
{
    public function __construct($resource = null, $message = 'Unauthorized', $status = 401, $success = false)
    {
        parent::__construct($resource, $message, $status, $success);

    }

    public function toArray(Request $request): array
    {
        return [
            'message' => $this->message,
            'data' => $this->resource,
            'success' => $this->success,
        ];
    }

    public static function resource($resource = null, $message = 'Unauthorized', $status = 401, $success = false): JsonResponse
    {
        return response()->json(new self($resource), $status);
    }
}
