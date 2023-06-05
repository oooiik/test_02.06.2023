<?php

namespace App\Http\Requests\Statement;

use App\Models\Permission;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *      title="Statement Store Request",
 *      description="Statement Store Request body data",
 *      type="object",
 *      required={"title"},
 *      @OA\Property( property="title", description="Title", type="string", example="Statement Title" ),
 *      @OA\Property(
 *          property="description",
 *          description="Description",
 *          type="string",
 *          example="Statement Description"
 *      ),
 *      @OA\Property( property="number", description="Number", type="integer", example="1" ),
 *      @OA\Property(
 *          property="date",
 *          description="Date",
 *          type="string",
 *          format="date-time",
 *          example="2021-01-01 00:00:00"
 *      ),
 *      @OA\Property( property="user_id", description="User ID", type="integer", example="1" )
 * )
 */
class StatementStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string',
            'description' => 'nullable|string',
            'number' => 'nullable|integer',
            'date' => 'nullable|date_format:Y-m-d H:i:s',
            'user_id' => ['integer',
                'exists:users,id',
                function ($attribute, $value, $fail) {
                    /** @var User $auth */
                    $auth = auth()->user();
                    $permissionCreate = Permission::findWithModelAndAction('statement', 'create');
                    if ($auth->hasPermission($permissionCreate)) {
                        return;
                    }
                    if ($auth->id !== $value) {
                        $fail('You can\'t create statement for another user');
                    }
                },
            ],
        ];
    }
}
