<?php

namespace App\Http\Requests\Role;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *      title="Role store request",
 *      description="Role store request body data",
 *      type="object",
 *      required={"title", "permissions"},
 *      @OA\Property( property="title", type="string", example="Operator" ),
 *      @OA\Property( property="permissions", type="array", @OA\Items( type="integer", example="1" ) )
 * )
 */
class RoleStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'permissions' => 'required|array',
            'permissions.*' => 'required|integer|exists:permissions,id'
        ];
    }
}
