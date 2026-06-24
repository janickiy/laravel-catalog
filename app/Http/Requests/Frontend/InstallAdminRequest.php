<?php

namespace App\Http\Requests\Frontend;

use Illuminate\Foundation\Http\FormRequest;

class InstallAdminRequest extends FormRequest
{
    /**
     * Authorizes first administrator creation during installation.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Returns the validation rules for the administrator account.
     */
    public function rules(): array
    {
        return [
            'login' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string', 'min:6'],
            'confirm_password' => ['required', 'string', 'min:6', 'same:password'],
        ];
    }
}
