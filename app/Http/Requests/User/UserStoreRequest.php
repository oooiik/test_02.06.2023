<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     title="UserStoreRequest",
 *     description="User store request",
 *     schema="UserStoreRequest",
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
class UserStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|min:3|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|max:255|confirmed',
            'phone' => 'required|string|min:10|max:20',
            'address' => 'required|string|min:10|max:255',
        ];
    }
}
