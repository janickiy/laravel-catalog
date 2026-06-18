<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreAdminRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'login' => 'required|unique:admin|max:255',
            'name' => 'required',
            'password' => 'required|min:6',
            'password_again' => 'required|min:6|same:password',
        ];
    }
}
