<?php

namespace App\Http\Resources\User;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     title="UserUpdateResource",
 *     description="User update resource",
 *     schema="UserUpdateResource",
 *     example={
 *         "message":"User updated successfully",
 *         "data":{
 *             "id":1,
 *             "name":"John Doe",
 *             "email":"email@mail.com",
 *             "email_verified_at":null,
 *             "created_at":"2020-12-31T00:00:00.000000Z",
 *             "updated_at":"2020-12-31T00:00:00.000000Z"
 *         },
 *         "success":true,
 *     }
 * )
 */
class UserUpdateResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'message' => 'User updated successfully',
            'data' => new UserResource($this->resource),
            'success' => true
        ];
    }

    public function withResponse($request, $response): void
    {
        $response->setStatusCode($this->status);
    }

    public static function resource($resource = null, $message = null, $status = 200, $success = true): JsonResponse
    {
        return response()->json(new self($resource), $status);
    }
}
