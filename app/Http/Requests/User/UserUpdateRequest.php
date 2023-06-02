<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

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
