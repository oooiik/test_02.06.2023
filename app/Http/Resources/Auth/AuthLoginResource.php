<?php

namespace App\Http\Resources\Auth;

use App\Http\Resources\User\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     title="AuthLoginResource",
 *     description="Auth login resource",
 *     schema="AuthLoginResource",
 *     example={
 *         "token":"token",
 *         "user":{
 *             "id": 1,
 *             "name": "John Doe",
 *             "email": "email@mail.com",
 *             "phone": "1234567890",
 *             "email_verified_at": "2021-01-01 00:00:00",
 *             "created_at": "2021-01-01 00:00:00"
 *         }
 *     }
 * )
 */
class AuthLoginResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'token' => $this->token,
            'user' => new UserResource($this->user)
        ];
    }
}
