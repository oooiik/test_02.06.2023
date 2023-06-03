<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     title="UserResource",
 *     description="User resource",
 *     schema="UserResource",
 *     @OA\Property(property="id", type="integer", example="1"),
 *     @OA\Property(property="name", type="string", example="John Doe"),
 *     @OA\Property(property="email", type="string", example="emial@mail.com"),
 *     @OA\Property(property="email_verified_at", type="string", example="2021-01-01 00:00:00"),
 *     @OA\Property(property="created_at", type="string", example="2021-01-01 00:00:00"),
 *     @OA\Property(property="updated_at", type="string", example="2021-01-01 00:00:00")
 * )
 */
class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return parent::toArray($request);
    }
}
