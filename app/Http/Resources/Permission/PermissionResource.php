<?php

namespace App\Http\Resources\Permission;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     title="PermissionResource",
 *     description="Permission resource",
 *     @OA\Property ( property="id", type="integer", example=1 ),
 *     @OA\Property ( property="model", type="string", example="user" ),
 *     @OA\Property ( property="action", type="string", example="create" )
 * )
 *
 * @property int $id
 * @property string $model
 * @property string $action
 */
class PermissionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'model' => $this->model,
            'action' => $this->action,
        ];
    }
}
