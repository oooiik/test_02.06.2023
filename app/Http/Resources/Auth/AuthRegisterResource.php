<?php

namespace App\Http\Resources\Auth;

use App\Http\Resources\User\UserResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     title="AuthRegisterResource",
 *     description="Auth register resource",
 *     schema="AuthRegisterResource",
 *     example={
 *         "token":"token",
 *         "user":{},
 *         "message":"Registered successfully"
 *     }
 * )
 */
class AuthRegisterResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'token' => $this->token,
            'user' => new UserResource($this->user),
            'message' => 'Registered successfully',
        ];
    }

    public static function resource($resource = null): JsonResponse
    {
        return response()->json(new self($resource), 201);
    }
}
