<?php

namespace App\Http\Resources\Role;

use App\Http\Resources\Permission\PermissionResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     title="RoleResource",
 *     description="Role resource",
 *     @OA\Property ( property="id", type="integer", example="1" ),
 *     @OA\Property ( property="name", type="string", example="admin" )
 * )
 *
 * @property int $id
 * @property string $title
 * @property PermissionResource[] $permissions
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 */
class RoleResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'permissions' => PermissionResource::collection($this->permissions),
        ];
    }
}
