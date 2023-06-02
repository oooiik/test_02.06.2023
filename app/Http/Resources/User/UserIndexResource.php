<?php

namespace App\Http\Resources\User;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     title="UserIndexResource",
 *     description="User index resource",
 *     schema="UserIndexResource",
 *     example={
 *         "data": {
 *             {
 *                 "id":1,
 *                 "name":"John Doe",
 *                 "email":"email@mail.com",
 *                 "email_verified_at":null,
 *                 "created_at":"2020-12-31T00:00:00.000000Z",
 *                 "updated_at":"2020-12-31T00:00:00.000000Z"
 *             },
 *             {
 *                 "id":2,
 *                 "name":"Jane Doe",
 *                 "email":"email@mail.com",
 *                 "email_verified_at":null,
 *                 "created_at":"2020-12-31T00:00:00.000000Z",
 *                 "updated_at":"2020-12-31T00:00:00.000000Z"
 *             }
 *         },
 *         "message":"Success",
 *         "success":true
 *     }
 * )
 */
class UserIndexResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'data' => UserResource::collection($this->resource),
        ];
    }

    public function with($request): array
    {
        return [
            'message' => 'Users retrieved successfully',
            'success' => true
        ];
    }

    public static function resource($resource): JsonResponse
    {
        return response()->json(new self($resource), 200);
    }
}
