<?php

namespace App\Http\Resources\Base;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BaseResource extends JsonResource
{
    protected $message;
    protected $status;
    protected $success;

    public function __construct($resource = null, $message = null, $status = 200, $success = true)
    {
        parent::__construct($resource);
        $this->message = $message;
        $this->status = $status;
        $this->success = $success;
    }

    public function toArray(Request $request): array
    {
        return [
            'message' => $this->message ?? 'Success',
            'data' => $this->resource,
            'success' => $this->success,
        ];
    }

    public function withResponse($request, $response): void
    {
        $response->setStatusCode($this->status);
    }

    public static function resource($resource = null, $message = null, $status = 200, $success = true): JsonResponse
    {
        return response()->json(new self($resource, $message, $status, $success));
    }
}
