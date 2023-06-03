<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     title="AuthLoginRequest",
 *     description="Auth login request",
 *     schema="AuthLoginRequest",
 *     required={"email","password"},
 *     example={
 *         "email":"admin@test.com",
 *         "password":"password"
 *    }
 * )
 */
class AuthLoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => 'required|email',
            'password' => 'required|string'
        ];
    }
}
