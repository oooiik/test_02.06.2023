<?php

namespace App\Http\Requests\Statement;

use App\Models\Permission;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *      title="Statement Index Request",
 *      description="Statement Index Request body data",
 *      type="object",
 *      required={"user_id"},
 *      @OA\Property( property="user_id", description="User ID", type="integer", example="1" )
 * )
 */
class StatementIndexRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user_id' => 'integer|exists:users,id,users.deleted_at,NULL',
        ];
    }

    public function validated($key = null, $default = null): array
    {
        $validated = parent::validated();
        /** @var User $user */
        $user = auth()->user();
        $hasPermission = $user->hasPermission(Permission::findWithModelAndAction('statement', 'read'));
        if (!isset($validated['user_id']) && !$hasPermission) {
            $validated['user_id'] = auth()->id();
        }
        return $validated;
    }
}
