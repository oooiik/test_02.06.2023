<?php

namespace App\Http\Resources\Base;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     title="BaseNotFoundResource",
 *     description="Base not found resource",
 *     schema="BaseNotFoundResource",
 *     example={
 *         "message":"Not found",
 *         "success":false
 *     }
 * )
 */
class BaseNotFoundResource extends BaseResource
{
    public function __construct($message = 'Not found', $status = 404, $success = false)
    {
        parent::__construct(null, $message, $status, $success);
    }

    public function toArray(Request $request): array
    {
        return [
            'message' => $this->message,
            'success' => $this->success,
        ];
    }

    public function withResponse($request, $response): void
    {
        $response->setStatusCode($this->status);
    }

    public static function resource($resource = null, $message = 'Not found', $status = 404, $success = false): JsonResponse
    {
        return response()->json(new self(null, $message, $status, $success), $status);
    }
}
