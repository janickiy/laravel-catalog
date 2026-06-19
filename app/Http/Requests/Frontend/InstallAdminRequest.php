<?php

namespace App\Http\Requests\Frontend;

use Illuminate\Foundation\Http\FormRequest;

class InstallAdminRequest extends FormRequest
{
    /**
     * Разрешает создание первого администратора во время установки.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Возвращает правила валидации учетной записи администратора.
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
