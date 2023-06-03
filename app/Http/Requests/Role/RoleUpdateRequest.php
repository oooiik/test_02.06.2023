<?php

namespace App\Http\Requests\Role;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *      title="Role update request",
 *      description="Role update request body data",
 *      type="object",
 *      required={"title", "permissions"},
 *      @OA\Property( property="title", type="string", example="Operator" ),
 *      @OA\Property( property="permissions", type="array", @OA\Items( type="integer", example="1" ) )
 * )
 */
class RoleUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'string|max:255',
            'permissions' => 'array',
            'permissions.*' => 'integer|exists:permissions,id'
        ];
    }
}
