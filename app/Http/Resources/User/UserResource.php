<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     title="UserResource",
 *     description="User resource",
 *     schema="UserResource",
 *     example={
 *         "id": 1,
 *         "name": "John Doe",
 *         "email": "eamil@mail.com",
 *         "phone": "1234567890",
 *         "email_verified_at": "2021-01-01 00:00:00",
 *         "created_at": "2021-01-01 00:00:00"
 *     }
 * )
 */
class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return parent::toArray($request);
    }
}
