<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     title="UserUpdateRequest",
 *     description="User update request",
 *     schema="UserUpdateRequest",
 *     required={"name","email","password","password_confirmation","phone","address"},
 *     example={
 *         "name":"John Doe",
 *         "email":"email@mail.com",
 *         "password":"password",
 *         "password_confirmation":"password",
 *         "phone":"0123456789",
 *         "address":"123 Street, City, Country"
 *     }
 * )
 */
class UserUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'     => 'string|min:3|max:255',
            'email'    => 'email|unique:users,email',
            'password' => 'string|min:6|max:255|confirmed',
            'phone'    => 'string|min:10|max:20',
            'address'  => 'string|min:10|max:255',
        ];
    }
}
